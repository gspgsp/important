<?php
/**
* 微信红包入口文件
*/
class hbIndexAction extends null2Action{
	public $openid,$code;
	public $AppID,$AppSecret;
	public function __construct(){
		// parent::__construct();
		parent::__construct(C('db_default'), 'weixin_name');
		$this->AppID = 'wxbe66e37905d73815';
		$this->AppSecret = '7eb6cc579a7d39a0e123273913daedb0';
		$get = $_GET['param'];
		$code = $_GET['code'];
		$cache = cache::startMemcache();
		//判断code是否存在
		if(!empty($code)){
// 		    if(($cache->get('open_access')==null)||($cache->get('open_access')=="")){
// 		        $open_access = $this->get_author_access_token($code);
// 		        $cache->set('open_access',$open_access,7200);
// 		    }else{
// 		        $open_access = $cache->get('open_access');
// 		        $cache->delete('open_access');
// 		    }
		    $open_access = $this->get_author_access_token($code);
		    $cache->set('open_access',$open_access,7200);
		    $userinfo = $open_access;		    
		    $cache->set('userinfo',$userinfo,7200);
		    $info=$this->get_user_info($userinfo['openid'],$userinfo['access_token']);
		    $this->openid = $userinfo['openid'];
		    if(!empty($info)){
		        $cache->set('weixinAuth'.$this->openid,$info,7200);
		    }else{
		        $cache->delete('weixinAuth'.$this->openid);
		        $cache->delete('open_access');
		        exit('authError');
		    }
		}
	}
	public function __init(){
// 		$this->debug = false;
		$this->db=M('public:common');
	}
	public function init(){
	    $cache = cache::startMemcache();
		$get = $_GET['param'];
		$code = $_GET['code'];
		//判断code是否存在
		if($get=='access_token' && !empty($code)){
// 			$code = $_GET['code'];
// 			$this->code = $_GET['code'];
// 			if($cache->get('open_access')){
// 			      $open_access = $this->get_author_access_token($code);
// 			      $cache->set('open_access',$open_access,7200);
//             }else{
//             	      $open_access = $cache->get('open_access');
//              }
// 			$userinfo = $open_access;
// 			$cache->set('userinfo',$userinfo,7200);
// 			$info=$this->get_user_info($userinfo['openid'],$userinfo['access_token']);
// 			$this->openid = $userinfo['openid'];
// 			if(!empty($info)){
// 			    p($info);
// 				$cache->set('weixinAuth',$info,7200);
// 				p($cache->get('weixinAuth'));
// 			}else{
// 				exit('authError');
// 			}
		}else{
			$this->get_authorize_url("http://m.myplas.com/wx/hbIndex/enHbPage?param=access_token",'STATE');
		}
	}
	//活动页面
	public function enHbPage(){
	    $cache = cache::startMemcache();
// 	    p($this->openid);
// 	    p($cache->get('weixinAuth'.$this->openid));
		if(($cache->get('weixinAuth'.$this->openid)==null)||($cache->get('weixinAuth'.$this->openid)=="")){
		}else{
		    $this->openid=$cache->get('weixinAuth'.$this->openid)['openid'];
		    $this->update_times();
		    $times =$this->db->model('weixin_name')->where("openid='{$this->openid}'")->select('times')->getOne();
		    $this->assign('times',$times);
		    $this->assign('openid',$this->openid);
		    $this->display('index');
		}
	}
	//规则页面
	public function enRuler(){
		$this->display('rule');
	}
	//更新抽奖次数以及关联微信用户
	protected function update_times(){
	    $cache = cache::startMemcache();
	    if(($cache->get('weixinAuth'.$this->openid)==null)||($cache->get('weixinAuth'.$this->openid)=="")){
	        exit('用户信息为空');
	    }
		$userinfo=$cache->get('weixinAuth'.$this->openid);
		$openid = $cache->get('weixinAuth'.$this->openid)['openid'];
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
				$where2 = "log.user_id={$data['uid']} && log.input_time > $today && log.chanel=2 && cinfo.reg_time < $today";
				$res = $this->db->model('log_login')->select('log.user_id')->from('log_login log')
			            ->join('contact_info cinfo','log.user_id=cinfo.user_id')
			            ->where($where2)
			            ->getRow();
	            if(!empty($res)){
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
// 		p($url);
// 		p($result);
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
   public function doLogin(){
        if(empty($_POST['openid'])){
              exit('openid为空');
        }
        $this->openid = $_POST['openid'];
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
	    if(empty($_POST['openid'])){
	        exit('openid为空');
	    }
	    $this->openid = $_POST['openid'];
	    $openid = $this->openid;
// 	    $cache = cache::startMemcache();
		$userinfo = $data=$this->db->model('weixin_name')->where("openid='{$this->openid}'")->getRow();
		if($userinfo['times']<=0&&$userinfo['username']=='') $this->json_output(array('err'=>2,'msg'=>'次数用完，未登录账号','times'=>$userinfo['times']));
		if($userinfo['times']<=0&&$userinfo['username']!='') $this->json_output(array('err'=>3,'msg'=>'次数用完，已登录账号','times'=>$userinfo['times']));
		//更新抽奖次数
		$data=$this->db->model('weixin_name')->where("id={$userinfo['id']}")->update(saddslashes(array('times'=>$userinfo['times']-1)));

		$prize_name=array('未中奖','微信红包');
		$res['yes']=$this->gethonor();
		$res['prize_name']=$prize_name[$res['yes']];//$prize_name[1],微信红包
		//获取当天红包总数
		$count= $this->db->model('weixin_count')->where("id=1")->getRow();
		$price=0;
		$hold=0;
		// $count=$countModel->find();
		if($count['count']<=0){
			$res['yes']=0;
			$hold=1;//拦截红包
		}
		if($userinfo['id']>17181){
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
			$countArr = array(
				'count'=>$count['count']-($price/100),
				'input_time'=>time()
				);
			$prizeArr = array(
				'oid'=>$userinfo['id'],
				'openid'=>$this->openid,
				'prize'=>$res['yes'],
				'price'=>$price,
				'addtime'=>time(),
				'prize_name'=>$prize_name[$res['yes']],
				'is_hold'=>$hold
				);
			if(!$this->db->model('weixin_count')->where("id=1")->update(saddslashes($countArr))) throw new Exception("系统错误。code:102");
			if(!$this->db->model('weixin_prize')->add(saddslashes($prizeArr))) throw new Exception("系统错误。code:102");
			$countModel->commit();
		} catch (Exception $e) {
			p($e->getMessage());
			$res=array('yes'=>0,'prize_name'=>'未中奖');
			$countModel->rollback();
		}
		$res['times']=$this->db->model('weixin_name')->select('times')->where("id={$userinfo['id']}")->getOne();//剩余抽奖的次数
		$res['name']=$userinfo['name'];//微信用户名
		if($price==0){
			$res['price']=0;
		}
		$this->json_output(array('err'=>4,'res'=>$res));//返回抽奖的结果
	}
	//算法
	// public function get_rand($proArr){
	// 	$result = '';
	// 	//概率数组的总概率精度
	// 	$proSum = array_sum($proArr);
	// 	//概率数组循环
	// 	foreach ($proArr as $key => $proCur) {
	// 		$randNum = mt_rand(1, $proSum);
	// 		if ($randNum <= $proCur) {
	// 			$result = $key;
	// 			break;
	// 		} else {
	// 			$proSum -= $proCur;
	// 		}
	// 	}
	// 	unset ($proArr);
	// 	return $result;
	// }
	//新的中奖算法
	public function gethonor(){
		$options = array(0,0,0,1,0,0,1,0,0,1,0);
		$index = rand(0, count($option)-1);
		return $options[$index];
	}
	//动态获取5条数据
	public function getHonorData(){
		$this->is_ajax=true;
		$names = $this->db->model('weixin_name')->select("id,name,'0' as price")->limit('0,5')->order('addtime desc')->getAll();
		foreach($names as $key => $value){
			$price = $this->db->model('weixin_prize')->where("oid={$value['id']}")->limit('0,1')->order('addtime desc')->getRow();
            if(empty($price)||($price['price']==0)){
                $price['price']=0;
            }else{
			    $price['price']= $price['price']/100;
            }
			$names[$key]['price'] = $price['price'];
		}
		$this->json_output(array('err'=>0,'names'=>$names));//滚动获奖信息
	}

}