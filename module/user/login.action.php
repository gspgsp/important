<?php

/**
 * 用户登录
 */
class loginAction extends homeBaseAction{
	public function init()
	{
		$this->display('login');
	}

	public function dolongin()
	{
		if(isset($_REQUEST['username'])){
			$this->is_ajax=true;
			$username=sget('username','s');
			$password=sget('password','s');

			if(strlen($username)<10 || !is_mobile($username)){
				$this->error('账号或密码错误');
			}elseif(strlen($password)<6){
				$this->error('账号或密码错误');
			}
			$chanel=1; //web渠道
			$result=M('user:passport')->login($username,$password,$chanel);

			if($result['err']>0){
				$this->error($result['msg']);
			}else{
				M('user:passport')->setSession($result['user']['user_id'],$result['user']);
				unset($_SESSION['gurl']);
				$this->success('登录成功');
			}
			$this->forward('/');exit;
		}
	}

	//登录弹窗
	public function loginbox()
	{
		$this->type=sget('type', 's', '');
		$this->display('loginbox');
	}

	public function auth(){

		$sns=thinkOauth::getInstance('qq');
		redirect($sns->getRequestCodeURL());

	}

	// 回调
	public function callback(){
		$sns=thinkOauth::getInstance('qq');
		if(!$code=sget('code','s','')) $this->error('授权失败');
		$extend=null;
		if(!$token = $sns->getAccessToken($code , $extend)) $this->error('授权失败');
		$snsType = E('SnsType',APP_LIB.'extend');
		$snsInfo=$snsType->qq($token);
		$openid=$token['openid'];
		$outerModel=M('user:userOuter');
		if($outerInfo=$outerModel->where("outer_id='{$openid}'")->getRow()){

		}else{
			$_SESSION['auth_openid']=$openid;
			redirect('/user/login/bindLogin');
		}
		
	}

	public function bindLogin(){
		$_SESSION['auth_openid']='aaa';
		if(!$openid=$_SESSION['auth_openid']) $this->forward('/user/login');
		p($openid);

		$this->display('bindLogin');
	}

}