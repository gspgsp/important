<?php
/**
* 微信红包入口文件
*/
class hbIndexAction extends null2Action{
	public $openid;
	protected $AppID,$AppSecret;
	public function __construct(){
		// parent::__construct();
		parent::__construct(C('db_default'), 'weixin_name');
		$this->AppID = 'wxbe66e37905d73815';
		$this->AppSecret = '7eb6cc579a7d39a0e123273913daedb0';
		$get = $_GET['param'];
		$code = $_GET['code'];
		//判断code是否存在
		if($get=='access_token' && !empty($code)){
			$code = $_GET['code'];
			$open_access = $this->get_author_access_token($code);
			$userinfo = $open_access;
			$info=$this->get_user_info($userinfo['openid'],$userinfo['access_token']);
			if(!empty($info)){
				$_SESSION['weixinAuth'] = $info;
			}else{
				exit('authError');
			}
		}else{
		    // $this->AppID = 'wx00df62a914e25294';
		    // $this->AppSecret = '9be0026abfd442209334c1ef28bc46e6';
			// $url = $this->get_url();
			$this->get_authorize_url("http://m.myplas.com/wx/hbIndex?param=access_token",'STATE');
			// $this->get_authorize_url($url);
		}
	}
	public function __init(){
// 		$this->debug = false;
		$this->db=M('public:common');
		if(!empty($_SESSION['weixinAuth'])){
		    $this->openid=$_SESSION['weixinAuth']['openid'];
		    $this->update_times();
		    $this->display('index');
		}
	}
	//活动页面
	// public function enHbPage(){
	// 	$this->display('index');
	// }
	//规则页面
	public function enRuler(){
		$this->display('rule');
	}
	//更新抽奖次数以及关联微信用户
	protected function update_times(){
	    if(empty($_SESSION['weixinAuth'])){
	        exit('authError');
	    }
		$userinfo=$_SESSION['weixinAuth'];
		$openid = $_SESSION['weixinAuth']['openid'];
		// M('wx:hb')->updateTimes($this->openid,$userinfo);
		//记录openid
		if($data=$this->db->model('weixin_name')->where("openid='{$openid}'")->getRow()){
				$today = strtotime(date('Y-m-d',time()));
				//每天更新抽奖机会
				if($data['updatetime']<$today){
					$this->db->model('weixin_name')->where("id={$data['id']}")->update(saddslashes(array('updatetime'=>time(),'times'=>$data['base_num'])));
				}
				//先判断有没有绑定用户(就是已经注册过的用户)
				if($data['uid']>0 && $data['app_time']<$today){
					//新注册app，加一次机会
					$where1 = "user_id={$data['uid']} && reg_time > $today && reg_chanel==2";
					if($this->db->model('contact_info')->where($where1)->getRow()){
						$this->db->model('weixin_name')->where("id={$data['id']}")->update(saddslashes(array('app_time'=>time(),'times'=>$data['times']+1)));
					}
					//登录app，加一次机会
					$where2 = "log.user_id={$data['uid']} && log.input_time > $today && log.chanel==2 && cinfo.reg_time < $today";
					$res = $this->db->model('log_login')->select('log.user_id')->from('log_login log')
		            ->join('contact_info cinfo','log.user_id=cinfo.user_id')
		            ->where($where2)
		            ->getRow();
		            if($res){
		            	$this->db->model('weixin_name')->where("id={$data['id']}")->update(saddslashes(array('app_time'=>time(),'times'=>$data['times']+1)));
		            }
				}

		}else{
			//未保存openid 保存openid
			$_data=array(
				'openid'=>$userinfo['openid'],
				'name'=>$userinfo['nickname'],
				'times'=>1,
				'img'=>$userinfo['headimgurl'],
				'base_num'=>1,
				'addtime'=>time(),
				'updatetime'=>time(),
				);
			$this->db->model('weixin_name')->add($_data);
		}
	}
	//获取授权的token
	protected function get_author_access_token($code=''){
		if($code == '') return false;
		$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$this->AppID}&secret={$this->AppSecret}&code={$code}&grant_type=authorization_code";
		$result = $this->http($url);
		if( $result ) $result = json_decode($result, true);
		if( isset($result['errcode']) ){
			return false;
		}else{
			return $result;
		}

	}
	//通过openid 和 token获取用户信息
	protected function get_user_info($openid,$access_token){
		$url = "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$openid}&lang=zh_CN";
		$result = json_decode($this->http($url), true);
		if(!isset($result['errcode']))
		{
			return $result;
		}else
		{
			return false;
		}
	}
	//http-->curl
	protected function http($url){
	    $ch = curl_init($url);
	    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
	    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	    curl_setopt($ch, CURLOPT_HEADER, false);
	    $output = curl_exec($ch);//输出内容
	    curl_close($ch);
	    return $output;
	}
	//获取当前用户的url
	protected function get_url(){
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		$url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		return $url;
	}
	//通过回调方法获取用户的code
	protected function get_authorize_url($redirect_uri = '', $state = ''){
       $redirect_uri = urlencode($redirect_uri);
       $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->AppID}&redirect_uri={$redirect_uri}&response_type=code&scope=snsapi_userinfo&state={$state}#wechat_redirect";
       echo "<script language='javascript' type='text/javascript'>";
       echo "window.location.href='$url'";
       echo "</script>";
   }


   //用户登录和微信账号绑定
   public function dologin(){
		$this->is_ajax=true;
		$username=sget('username','s');
		$password=sget('password','s');
		if($username==''||$password=='') $this->error('手机号或密码格式不正确');
		$chanel=2; //app渠道
		$where="mobile='$username'";
		$uinfo=M('user:passport')->where($where)->getRow();
		$npassword=M('system:sysUser')->genPassword($password.$uinfo['salt']);
		if(!M('user:passport')->where("mobile='{$username}' and password='{$npassword}'")->getRow()) $this->json_output(array('err'=>2,'msg'=>'用户名或密码错误'));

		//判断是否分配交易员
		$cinfo=$this->db->model('customer')->where("c_id={$uinfo['c_id']}")->select('customer_manager,status')->getRow();
		if($cinfo['status']==1) $this->json_output(array('err'=>3,'msg'=>'账号等待审核中，请稍候再试'));
		if($cinfo['status']==3) $this->json_output(array('err'=>4,'msg'=>'账号审核未通过，请联系客服'));
		if(!$cinfo['customer_manager']) $this->json_output(array('err'=>5,'msg'=>'正在等待分配交易员，请稍候再试'));

		//检查绑定是否微信
		$data=$this->db->model('weixin_name')->where("openid='{$this->openid}'")->getRow();
			if(!$data['username']){
				$_data=array(
				'username'=>$uinfo['mobile'],
				'uid'=>$uinfo['user_id'],
				// 'base_num'=>3,
				// 'times'=>$data['times']+2,
				);
				$this->db->model('weixin_name')->where("openid='{$this->openid}'")->update(saddslashes($_data));
		}
		//绑定后更新抽奖次数
		$this->update_times();
		$this->json_output(array('err'=>0,'msg'=>'登录成功'));
	}
	//每次点击活动按钮
	public function comeback(){
		$userinfo = $data=$this->db->model('weixin_name')->where("openid='{$this->openid}'")->getRow();
		if($userinfo['times']<=0&&$userinfo['username']=='') $this->json_output(array('err'=>2,'msg'=>'次数用完，未登录账号'));
		if($userinfo['times']<=0&&$userinfo['username']!='') $this->json_output(array('err'=>3,'msg'=>'次数用完，已登录账号'));
		//更新抽奖次数
		$data=$this->db->model('weixin_name')->where("id={$userinfo['id']}")->update(saddslashes(array('times'=>$userinfo['times']-1)));

		$prize_name=array('未中奖','微信红包');
		$prize_arr = array(
		    '0' => array('id'=>1,'prize'=>1,'v'=>0),
		    '1' => array('id'=>2,'prize'=>0,'v'=>0),
		    '2' => array('id'=>3,'prize'=>0,'v'=>0),
		    '3' => array('id'=>4,'prize'=>1,'v'=>40),
		    '4' => array('id'=>5,'prize'=>1,'v'=>0),
		    '5' => array('id'=>6,'prize'=>1,'v'=>0),
		    '6' => array('id'=>7,'prize'=>1,'v'=>0),
		    '7' => array('id'=>8,'prize'=>1,'v'=>0),
		    '8' => array('id'=>9,'prize'=>0,'v'=>60),
		);
		foreach ($prize_arr as $key => $val) {
		  $arr[$val['id']] = $val['v'];
		}
		$rid = $this->get_rand($arr); //根据概率获取奖项id
		$res['yes'] = $prize_arr[$rid-1]['prize']; //中奖项,只有$rid=4的时候才有奖
		$res['prize_name']=$prize_name[$res['yes']];//$prize_name[1],微信红包
		//获取当天红包总数
		$count= $this->db->model('weixin_count')->getRow();
		$price=0;
		$hold=0;
		// $count=$countModel->find();
		if($count['count']<=0){
			$res['yes']=0;
			$hold=1;//拦截红包
		}
		if($userinfo['id']>8000){
			$res['yes']=0;
			$hold=2;
		}
		if($res['yes']==1){
			if(!$userinfo['username']){
				$price=rand(20,30);//未登录的时候奖
			}else{
				$price=rand(30,80);//登录时候的奖
			}
			$res['price']=$price/100;//中的奖金额度
		}
		//红包模型
		$countModel = $this->db->model('weixin_count');
		$prizeModel = $this->db->model('weixin_prize'); 
		$countModel->startTrans();
		try {
			if(!$countModel->where("1=1")->update(saddslashes(array('count'=>$count['count']-($price/100),'input_time'=>time())))) throw new Exception("系统错误。code:102");
			if(!$prizeModel->add(saddslashes(array('oid'=>$userinfo['id'],'openid'=>$this->openid,'prize'=>$res['yes'],'price'=>$price,'addtime'=>time(),'prize_name'=>$prize_name[$res['yes']],'is_hold'=>$hold)))) throw new Exception("系统错误。code:102");
			$countModel->commit();
		} catch (Exception $e) {
			$res=array('yes'=>0,'prize_name'=>'未中奖');
			$countModel->rollback();
		}
		$res['times']=$this->db->model('weixin_name')->select('times')->where("id={$userinfo['id']}")->getOne();//剩余抽奖的次数
		$res['name']=$userinfo['name'];//微信用户名
		$this->json_output(array('err'=>4,'res'=>$res));//返回抽奖的结果
	}
	//算法
	public function get_rand($proArr){
		$result = '';
		//概率数组的总概率精度
		$proSum = array_sum($proArr);
		//概率数组循环
		foreach ($proArr as $key => $proCur) {
			$randNum = mt_rand(1, $proSum);
			if ($randNum <= $proCur) {
				$result = $key;
				break;
			} else {
				$proSum -= $proCur;
			}
		}
		unset ($proArr);
		return $result;
	}
	//动态获取5条数据
	public function getHonorData(){
		$names = $this->db->model('weixin_name')->select('id,name')->limit('0,5')->order('addtime desc')->getAll();
		foreach ($names as $key => $value) {
			$prize = $this->db->model('weixin_prize')->select('price')->where('oid='.$value['id'])->limit('0,1')->order('addtime desc')->getOne();
			$prize= $prize/100;
			$names['price'] = $prize;
		}
		return $names;
	}

}