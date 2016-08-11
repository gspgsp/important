<?php
/**
* 微信红包入口文件
*/
class hbIndexAction extends homeBaseAction{

	protected $openid;
	protected $AppID,$AppSecret;
	public function __construct(){
		parent::__construct();
		if( !isset($_SESSION['weixinAuth']) ) {
			if( isset($_GET['code']) && isset($_GET['state']) ){
				$code = $_GET['code'];
				$userinfo = $this->get_author_access_token($code);
				$info=$this->get_user_info($userinfo['openid'],$userinfo['access_token']);
				if($info){
					$_SESSION['weixinAuth'] = $info;
				}else{
					exit('authError');
				}
			}else
			{
				$url = $this->get_url();
				$this->get_authorize_url($url);
			}
		}
		$this->openid=$_SESSION['weixinAuth']['openid'];
		$this->AppID = 'wxbe66e37905d73815';
		$this->AppSecret = '7eb6cc579a7d39a0e123273913daedb0';
		// $this->openid="o1SYHw7UuAqoEoM1Yoyk7DEoqp7g";
		//$this->update_times();
	}
	//活动页面
	public function enHbPage(){
		$this->display('index');
	}
	//规则页面
	public function enRuler(){
		$this->display('rule');
	}
	//将用户和红包关联
	// protected function update_times(){
	// 	$model=M('weixin_name');
	// 	$userinfo=$_SESSION['weixinAuth'];
	// 	//记录openid
	// 	if( $data=$model->where(array('openid'=>$this->openid))->find() ){
	// 		$today=strtotime(date('Y-m-d'),time());
	// 		if($data['updatetime']<$today){
	// 			//每天更新抽奖机会
	// 			$model->where(array('id'=>$data['id']))->save(array('updatetime'=>time(),'times'=>$data['base_num']));
	// 		}
	// 	}else{
	// 		//未保存openid 保存openid
	// 		$_data=array(
	// 			'openid'=>$userinfo['openid'],
	// 			'name'=>$userinfo['nickname'],
	// 			'times'=>1,
	// 			'img'=>$userinfo['headimgurl'],
	// 			'base_num'=>1,
	// 			'addtime'=>time(),
	// 			'updatetime'=>time(),
	// 			);
	// 		$model->add($_data);
	// 	}

	// }
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

}