<?php
/**
*登录控制器
*/
class loginAction extends homeBaseAction
{
	public function __init() {
		$this->debug = false;
		$this->db=M('public:common')->model('customer_contact');
    }
    public function init()
	{
		$this->display('login');
	}
	//用户登录验证
	public function doLogin(){
		$this->is_ajax=true;
		$username=sget('username','s');
		$password=sget('password','s');
		$vcode=sget('regcode','s');
		$loginresult = M('touch:register')->login($username, $password, $vcode);
		if($loginresult['err']!=0) $this->error($loginresult['msg']);
		//保存登陆成功的日志
		$info = $this->db->model('customer_contact')->where('mobile='.$username)->getRow();
		M('user:passport')->loginSuccess($info['user_id'],$chanel=4);
		//保存用户的id
		$_SESSION['uid'] = $info['user_id'];
		$this->success('登录成功');
	}

}