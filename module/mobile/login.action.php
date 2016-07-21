<?php

/**
 * 用户登录- app
 */
class loginAction extends homeBaseAction{
	public function init()
	{
		$this->display('me_login');
	}

	public function login()
	{
		if($_POST){
			$this->is_ajax=true;
			$username=sget('username','s');
			$password=sget('password','s');
			if(strlen($username)<10 || !is_mobile($username)){
				$this->error('手机号错误');
			}elseif(strlen($password)<6){
				$this->error('密码错误');
			}
			$chanel=3; //wap渠道
			$result=M('user:passport')->login($username,$password,$chanel);

			if($result['err']>0){
				$this->error($result['msg']);
			}else{
				M('user:passport')->setSession($result['user']['user_id'],$result['user']);
				unset($_SESSION['gurl']);
				$this->success('登录成功');
			}

		}
	}
}