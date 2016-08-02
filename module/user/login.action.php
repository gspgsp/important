<?php

/**
 * 用户登录
 */
class loginAction extends homeBaseAction{

	protected $reg_vcode;
	public function __init()
	{
		$this->db=M('public:common');
		$this->reg_vcode = $this->sys['security']['reg']['vcode'];
	}

	public function init()
	{
		$this->display('logion');
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
			//p($result);
			if($result['err']>0){
				$this->error($result['msg']);
			}else{
			    p($result);
				//的三方授权登录绑定账号
				if($_SESSION['auth_openid']){
					M('user:userOuter')->bindUser($result['user']['user_id'],$_SESSION['auth_openid'],$result);
				}
				M('user:passport')->setSession($result['user']['user_id'],$result['user']);
				unset($_SESSION['gurl']);
				p($_SESSION);die;
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
		$type='qq';
		$sns=thinkOauth::getInstance($type);
		if(!$code=sget('code','s','')) $this->error('授权失败');
		$extend=null;
		if(!$token = $sns->getAccessToken($code , $extend)) $this->error('授权失败');
		$snsType = E('SnsType',APP_LIB.'extend');
		$snsInfo=$snsType->$type($token);
		$openid=$token['openid'];
		$outerModel=M('user:userOuter');
		$type=strtoupper($type);
		if($outerInfo=$outerModel->where("outer_id='{$openid}' and outer_source='{$type}'")->getRow()){
			$userInfo=M("public:common")->model('customer_contact')->where("user_id='{$outerInfo['user_id']}'")->getRow();
			M('user:passport')->setSession($outerInfo['user_id'],$userInfo);
			redirect('/user');
		}else{
			$_SESSION['auth_openid']=$openid;
			$_SESSION['auth_info']=$snsInfo;
			redirect('/user/login/bindLogin');
		}
		
	}

	public function bindLogin(){
		if($this->user_id>0) $this->forward('/user');
		if(!$openid=$_SESSION['auth_openid']) $this->forward('/user/login');
		$this->assign('auth_info',$_SESSION['auth_info']);
		$this->display('bindLogin');
	}

}