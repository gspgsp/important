<?php
/**
 * 通行证模型 
 */
class passportModel extends model{
	private static $MUID=9865479876; //最大UID
	public function __construct() {
		parent::__construct(C('db_default'), 'user');
	}
	
	/*
	 * 检查唯一性
	 * @param string $name 检查类型
	 * @param string $value 检查值
     * @return bool
	 */
	public function chkUnique($name='mobile',$value=''){
		$exist=$this->model('user')->select('user_id')->where("$name='$value'")->getOne();
		return $exist>0 ? true : false;
	}
	
	/*
	 * 用户注册
     * @access public
	 * @param array $param 用户信息(mobile,email,password)
     * @return bool
	 */
	public function register($param=array()){
		//系统共用模块进行注册
		return M('system:sysUser')->register($param);
	}	

	/**
	 * 用户登陆
	 * @access public 
	 * @param string $mobile 登录名
	 * @param string $password 明文密码
	 * @param int $chanel渠道:1web,2app,3wap,4微信
	 * @return array(err,msg)
	 */
	public function login($username='',$password='',$chanel=1){
		//IP黑名单
		$black=M('system:blackIp')->checkIp(get_ip());
		if(!empty($black)){
			return array('err'=>9,'msg'=>'IP已限制访问，请与客服联系');
		}
		
		if(empty($username) || empty($password)){
			return array('err'=>1,'msg'=>'用户信息不完整');
		}

		$where='';
		if(is_mobile($username)) {
			$where="mobile='$username'";
		}else{
			$this->_loginError(0,$username,1,$password,$chanel);
			return array('err'=>1,'msg'=>'用户信息不完整');
		}
		
		//查找数据库		
		$uinfo=$this->model('user')->where($where)->getRow();
		if(empty($uinfo)){
			$this->_loginError(0,$username,2,$password,$chanel);
			return array('err'=>2,'msg'=>'错误的账号');
		}

		//判断账号是否锁定
		if($uinfo['login_unlock_time'] > CORE_TIME){
			return array('err'=>4,'msg'=>'您的账号已被锁定，请稍候再试');
		}

		//密文 暂时不使用密码盐
		// $npassword=M('system:sysUser')->genPassword($password.$uinfo['salt']);
		$npassword=md5($password);
		if($uinfo['password']!==$npassword){
			$this->_loginError($uinfo['user_id'],$username,3,$password,$chanel);

			$_data = array();
			$_data['login_fail_count'] = $uinfo['login_fail_count'] >= 5 ? 1 : $uinfo['login_fail_count']+1;
			$msg = '密码不正确，连续输错5次将被锁定';

			if($_data['login_fail_count'] == 5){
				$_data['login_unlock_time'] = CORE_TIME + 14400;
				$msg = '已输错5次密码，账号将锁定4小时';
			}
			$this->model('user')->wherePk($uinfo['user_id'])->update($_data);
			return array('err'=>3,'msg'=>$msg);
		}
		
		//状态:1正常,2冻结,3关闭
		if(in_array($uinfo['status'],array(2,3))){
			return array('err'=>4,'msg'=>'您的帐号已被冻结，请联系客服');
		}

		//用户成功登录
		$this->_loginSuccess($uinfo,$chanel);
		return array('err'=>0,'msg'=>'登录成功','user'=>$uinfo);
	}

	/**
	 * 用户登录成功，记录日志
	 */
	public function loginSuccess($user_id,$chanel=1){
		return $this->_loginSuccess(compact('user_id'),$chanel);
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
			'visit_count'=>'+=1',
			'last_login'=>CORE_TIME,
			'login_fail_count'=>0,
			'login_unlock_time'=>0,
			'last_ip'=>get_ip()
		);
		$this->model('user')->wherePk($user['user_id'])->update($_data);

		$_login=array(
			'user_id'=>$user['user_id'],
			'input_time'=>CORE_TIME,
			'ip'=>get_ip(),		 
			'chanel'=>$chanel, 
		);
		$this->model('log_login')->add($_login);
		return true;
	}

	/**
	 * 用户登录错误
	 * @access private 
	 * @param array $user 用户信息
	 * @param int $chanel渠道:1web,2app,3wap,4微信
	 * @return bool
	 */
	private function _loginError($user_id=0,$mobile='',$err_code=1,$remark='',$chanel=1){
		$_login=array(
			'user_id'=>$user_id,
			'mobile'=>$mobile,
			'err_code'=>$err_code,
			'remark'=>$remark,
			'input_time'=>CORE_TIME,
			'ip'=>get_ip(),		 
			'chanel'=>$chanel, 
		);

		return $this->model('log_login_err')->add($_login);
	}

	/*
	 * 生成签名串
     * @access public
	 * @param int $user_id 用户ID
	 * @param string $password 用户密码
     * @return string
	 */
	public function encrypt($user_id=0,$password=''){
		//待加密参数
		$user_id=str_pad(self::$MUID-$user_id,10, "+", STR_PAD_LEFT);
		$string=$user_id."||".substr($password,20,8);
		return desEncrypt($string);
	}	

	/*
	 * 解密签名串
	 * @access public
	 * @param string $string 签名串
     * @return array
	 */
	public function decrypt($string=''){
		$string=desDecrypt($string);
		if(empty($string)){
			return false;	
		}
		$arr=explode("||",$string);
		if(count($arr)<>2){
			return false;	
		}
		$user_id=self::$MUID-(str_replace("+",'',$arr[0]));
		$password=$arr[1];
		return array('user_id'=>$user_id,'password'=>$password);
	}
	
	/*
	 * 验证token
	 * @access public
     * @return user_id
	 */
	public function getToken($user_id=0){
		$password=$this->model('user')->select('password')->wherePk($user['user_id'])->getOne();
		return $this->encrypt($user_id,$password);
	}

	/*
	 * 验证token
	 * @access public
	 * @param string $token 令牌
     * @return user_id
	 */
	public function chkToken($token=''){
		if(strlen($token)<30){
			return 0;
		}
		$user=$this->decrypt($token);
		if(isset($user['user_id'])){ //检查用户
			$user_id=$user['user_id'];
			//检查token的正确性
			$uinfo=$this->model('user')->wherePk($user_id)->getRow();
			if(substr($uinfo['password'],20,8)==$user['password']){ //验证通过，重新登录
				$this->_loginSuccess($uinfo);
				return (int)$user['user_id'];
			}
		}
		cookie::set(C('SESSION_TOKEN'), ''); //非法token注销
		return 0;
	}
	
	/*
	 * 用户登录设置SESSION和COOKIE
	 * @access public
	 * @param int user_id 用户ID
	 * @param array user 用户信息
     * @return bool
	*/
	public function setSession($user_id=0,$user=array()){
		if(empty($user_id)){ //用户退出登陆
			$GLOBALS['CORE_SESS']->destroy($this->ssid);
			cookie::set(C('SESSION_TOKEN'), '');
			return true;
		}
		
		//获取用户信息
		if(empty($user)){
			$user=$this->model('user')->wherePk($user_id)->getRow();
		}
		
		//用户头像等信息
		$uinfo=$this->model('user_info')->wherePk($user_id)->getRow();
		
		//将数据写入cookie
		$token=$this->encrypt($user_id,$user['password']);
		//cookie::set(C('SESSION_TOKEN'), $token); //C('SESSION_TTL')
		
		//写入session
		$_SESSION['userid']=$user_id;
		$_SESSION['uinfo']=array(
							'mobile'=>$user['mobile'],		 
							'email'=>$user['email'],
							'status'=>$user['status'],
							'last_login'=>$user['last_login'],
							'last_ip'=>$user['last_ip'],
							'invite_code'=>operationAlphaID($user_id,true),
						) + (array)$uinfo;

		return true;
	}
	
}
?>
