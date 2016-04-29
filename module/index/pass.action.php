<?php
/*
 * 管理员登陆控制器
*/
class passAction extends action {
	public function __init(){
		startAdminSession();		
		$this->debug=false;
		$this->db=M('public:common');
		
		$setting_security = M('system:setting')->get('security');
		$this->login_mcode = isset($setting_security['cp']) ? $setting_security['cp']['login_mcode'] : FALSE;
	}
	
    /**
     * 后台登陆UI
     * @access public
     */
    public function init(){
		if($_SESSION['adminid']>0){
			$this->forward('/');	
		}else{
			$this->chkAuthy();
		}
		
		$this->assign('page_title','管理员登录');
		$this->display('login.html');
    }

    /**
     * 退出管理
     * @access public
     */
    public function logout(){
		$this->setSession();
		$this->forward('/');	
    }

    /**
     * 后台登陆
     * @access public
     */
    public function login(){
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //获取UI传递的参数
		if(empty($data)){
			$this->error('错误的操作');
		}
		//检查验证码
		$this->_vcode($data['cpvcode']);
		
		$username=$data['username'];

		$user=$this->db->model('admin')->select('*')->where("username='$username' and status=1")->getRow();
		
		if(empty($user)){
			$this->_loginError(0,$username,2,$data['pwd'],1);
			$this->error('错误的账号');
		}
		
		//判断账号是否锁定
		if($user['login_unlock_time'] > CORE_TIME){
			$this->error('您的账号已被锁定，请稍候再试');
		}
		//管理员登录--开启手机动态码
		if($this->login_mcode){
			$regmcode = $data['regmcode'];
			if(!$this->_chkmcode($regmcode,$user['mobile'])){
				$this->error($this->err);	
			}
		}
		if(md5($data['pwd'])!=$user['password']){
			$remark="用户名【".$data['username']."】和密码【".$data['pwd']."】不匹配！";
		    $this->_loginError($user['admin_id'],$username,3,$remark,1);
			
			$_data = array();
			$_data['login_fail_count'] = $user['login_fail_count'] >= 5 ? 1 : $user['login_fail_count']+1;
			$msg = '账号密码不正确，您已输错'.$_data['login_fail_count'].'次';

			if($_data['login_fail_count'] == 5){
				$_data['login_unlock_time'] = CORE_TIME + 14400;
				$msg = '已输错5次密码，账号将锁定4小时';
			}
			$this->db->model('admin')->wherePk($user['admin_id'])->update($_data);
			
			$this->error($msg);
		}
		
		//用户成功登录
		$this->_loginSuccess($user,1);
		
		$this->setSession($user);
		$this->success('登录成功');
    }
	
	 /**
     * 验证手机码
     * @access private
     * @return bool
     */
	private function _chkmcode($mcode='',$mobile=''){
		if(isset($_SESSION['mcode']) && isset($_SESSION['mctime'])){
			if((CORE_TIME-$_SESSION['mctime'])>300){
				$this->err='动态码已失效';
				return false;	
			}
			if($_SESSION['mcode']==$mcode.'.'.$mobile){
				return true;	
			}
		}
		$this->err='错误的动态码';
		return false;	
	}

    /**
     * 检查验证码
     * @access private
     */
	private function _vcode($vcode=''){
		$vcode=strtolower($vcode);
		//检查验证码
		if(!chkVcode('cpvcode',$vcode)){
			unsetVcode('cpvcode');
			$this->error('错误的验证码');
		}
		unsetVcode('cpvcode');
	}

	/**
	 * 用户成功登录的动作
	 * @access public 
	 * @param array $user 用户信息
	 * @param int $chanel渠道:1web,2app,3wap,4微信
	 * @return bool
	 */
	private function _loginSuccess($user=array(),$chanel=1){
		//更新登陆记录
		$_data=array(
			'login_times'=>'+=1',
			'last_login'=>CORE_TIME,
			'login_fail_count'=>0,
			'login_unlock_time'=>0,
			'user_ip'=>get_ip()
		);
		$this->db->model('admin')->wherePk($user['admin_id'])->update($_data);

		$_login=array(
			'user_id'=>$user['admin_id'],
			'username'=>$user['username'],
			'input_time'=>CORE_TIME,
			'ip'=>get_ip(),		 
			'chanel'=>1, 
		);
		$this->db->model('adminlog_login')->add($_login);
		return true;
	}
	
	/**
	 * 用户登录错误
	 * @access private 
	 * @param array $user 用户信息
	 * @param int $chanel渠道:1web,2app,3wap,4微信
	 * @return bool
	 */
	private function _loginError($user_id=0,$username='',$err_code=1,$remark='',$chanel=1){
		$_login=array(
			'user_id'=>$user_id,
			'username'=>$username,
			'err_code'=>$err_code,
			'remark'=>$remark,
			'input_time'=>CORE_TIME,
			'ip'=>get_ip(),		 
			'chanel'=>$chanel, 
		);

		return $this->db->model('adminlog_login_err')->add($_login);
	}

    /**
     * 设置后台SESSION
     * @access private
     */
	private function setSession($uinfo=array()){
		if(empty($uinfo)){ //注销COOKIE和SESSION
			$_SESSION=array();
			$GLOBALS['CORE_SESS']->destroy();
			cookie::set('admincy', '');
			return true;	
		}
		
		//$authcy =  md5($_SERVER["HTTP_USER_AGENT"].$uinfo['password'].'isAdmin');
		//$atime = 86400 * 7; //记住密码
		//cookie::set('admincy', desEncrypt($uinfo['admin_id']."|".$authcy), $atime);
		$_SESSION['depart']=$uinfo['depart'];
		$_SESSION['adminid']=$uinfo['admin_id'];
		$_SESSION['name']=$uinfo['username'];
		$_SESSION['username']=$uinfo['name'];
		$_SESSION['call_no']=$uinfo['call_no'];
		$_SESSION['call_pwd']=$uinfo['call_pwd'];
		$_SESSION['is_super']=$uinfo['is_super'];
		$this->db->model('admin')->wherePk($uinfo['admin_id'])->update(array('login_times'=>"+=1",'last_login'=>CORE_TIME,'user_ip'=>get_ip()));
		return $uinfo;	
	}
	
    /**
     * 设置后台SESSION
     * @access private
     */
	private function chkAuthy(){
		$admincy=cookie::get('admincy');
		if(strlen($admincy)>50){
			$arr=explode("|",desDecrypt($admincy));	
			if(count($arr)==2){
				$user=$this->db->model('admin')->wherePk($arr[0])->getRow();
				$authcy =  md5($_SERVER["HTTP_USER_AGENT"].$user['password'].'isAdmin');
				if($authcy==$arr[1]){ //cookie验证通过
					$this->setSession($user);
					$this->forward('/');
				}
			}
		}
		return '';
	}
	
	/**
     * 发送手机验证码
     * @access public
     * @return html
     */
	public function sendMobileMsg($codeType=""){
		$this->is_ajax=true; //指定为Ajax输出
		$name=sget('name','s');
		$pwd=sget('pwd','s');
		if(empty($name)){
			$this->error('错误的操作');
		}
		$user=$this->db->model('admin')->select('*')->where("username='$name' and status=1")->getRow();
		if(empty($user)){
			$this->error('错误的账号');
		}
		
		//判断账号是否锁定
		if($user['login_unlock_time'] > CORE_TIME){
			$this->error('您的账号已被锁定，请稍候再试');
		}
		
		if(md5($pwd)!=$user['password']){
			$msg = '账号密码不正确，无法获取验证码！';
			$this->error($msg);
		}
		
		$mobile = $user['mobile'];
		//设置发送信息中的变量参数值
		$msgData = array();
		$stype = 99;
		$msg = "sms_template.dynamic_code";

		if(!is_mobile($mobile)){
			$this->error('错误的手机号码');
		}

		//发送手机动态码
		$sms=M('system:sysSMS');
		$rs = $sms->sendMobileMsg($user['admin_id'],$mobile,$msg,$stype,$msgData);
		if($rs['error']){
			$this->error($rs['msg']);
		}else{
			$this->success();
		}
	}
}
?>
