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

	public function loginbox()
	{
		$this->type=sget('type', 's', '');
		$this->display('loginbox');
	}

}