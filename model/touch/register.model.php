<?php
/**
*新用户注册
*/
class registerModel extends model
{
	public function __construct() {
		parent::__construct(C('db_default'), 'customer_contact');
	}
	/*
	 * 用户注册
     * @access public
	 * @param array $param 用户信息(mobile,password)
     * @return bool
	 */
	public function register($param=array()){
		//手机号检查
		if(!empty($param['mobile'])){
			if(!is_mobile($param['mobile'])){
				return array('err'=>1,'msg'=>'您的手机号码格式不正确');
			}
			if(!$this->_usrUnique('mobile',$param['mobile'])){
				return array('err'=>1,'msg'=>'手机号已存在');
			}
		}
		//密码检查
		if(strlen($param['password'])<8){
			return array('err'=>1,'msg'=>'密码长度应为(8-20位)');
		}
		$user=$param;
		if(empty($user['salt'])){
			$user['salt']=randstr(6);
			$user['password']=$this->_genPassword($param['password'].$user['salt']);
		}
		if(empty($user['last_ip'])){
			$user['last_ip']=get_ip();
			$user['last_login']=CORE_TIME;
			$user['visit_count']=1;
			$user['chanel']=3;
		}
		//加入数据库
		$result=$this->model('customer_contact')->add($user);
		if(empty($result)){
			return array('err'=>1,'msg'=>'数据操作失败:'.$this->getDBError());
		}else{
			return array('err'=>0,'msg'=>'数据操作成功!');
		}
	}
	/*
	 * 用户密码重置(找回密码)
     * @access public
	 * @param array $param 用户信息(mobile,password)
     * @return bool
	 */
	public function findpwd($param=array()){
		if(!empty($param['mobile'])){
			if(!is_mobile($param['mobile'])){
				return array('err'=>1,'msg'=>'您的手机号码格式不正确');
			}
			//只有手机号存在的时候才会密码重置
			$user=$param;
			if(!$this->_usrUnique('mobile',$param['mobile'])){
				$user['salt']=randstr(6);
				$user['password']=$this->_genPassword($param['password'].$user['salt']);
			}
			//更新数据库
			$result=$this->model('customer_contact')->where('mobile='.$param['mobile'])->update($user);
			if(empty($result)){
				return array('err'=>1,'msg'=>'数据操作失败:'.$this->getDBError());
			}else{
				return array('err'=>0,'msg'=>'数据操作成功!');
			}
		}
	}
	/*
	 * 检查唯一性
	 * @param string $name 检查类型
	 * @param string $value 检查值
     * @return bool（true唯一）
	 */
	private function _usrUnique($name='mobile',$value='',$user_id=0){
		$where = "$name='$value'";
		if($user_id){
			$where .= " and user_id !='$user_id'";
		}
		$exist=$this->model('customer_contact')->select('user_id')->where($where)->getOne();
		return $exist>0 ? false : true;
	}
	/*
	 * 生成加密密码
	 * @param string $password 明文密码
     * @return string 密文密码
	 */
	private function _genPassword($string=''){
		return md5('rs.'.$string.'.sr');
	}
	/**
	 *用户密码重置时检验用户名是否存在,只有存在时才调用短信验证
	 */
	public function isLegal($param=array()){
		if(!empty($param['mobile'])){
			//手机号检查
			if(!is_mobile($param['mobile'])){
				return array('err'=>1,'msg'=>'您的手机号码格式不正确');
			}
		//密码检查
		if(strlen($param['password'])<8){
			return array('err'=>1,'msg'=>'密码长度应为(8-20位)');
		}
		//只有手机号存在的时候才会密码重置
		if($this->_usrUnique('mobile',$param['mobile'])){
			return array('err'=>1,'msg'=>'手机号码不存在!');
		}
		}
	}
	/**
	 *用户登录信息验证
	 */
	public function login($username, $password, $vcode){

		//用户名或密码是否为空
		if(empty($username) || empty($password)){
			return array('err'=>1,'msg'=>'用户名或密码不能为空!');
        }
        //检查验证码
		if(!chkVcode('regcode',$vcode)){
			$this->_loginError(0, $username, 1, '验证码不正确，请重新输入!', 3);
			return array('err'=>1,'msg'=>'验证码不正确，请重新输入!');
		}
		//手机号匹配用户信息
		$info = $this->model('customer_contact')->where('mobile='.$username)->getRow();
		if(empty($info)){
			$this->_loginError(0, $username, 2, '没有该用户信息，请注册!', 3);
			return array('err'=>1,'msg'=>'没有该用户信息，请注册!');
		}else{
			$password = $this->_getLoginPassword(array('mobile'=>$username,'password'=>$password));//检查密码是否正确
			if($password != trim($info['password'])){
				//判断账号是否锁定
				if($info['login_unlock_time'] > CORE_TIME){
					$this->_loginError($info['user_id'], $username, 3, '您的账号已被锁定，请稍候再试!', 3);
					return array('err'=>1,'msg'=>'您的账号已被锁定，请稍候再试');
				}
				//记录密码输入的次数
				$_data = array();
				$_data['login_fail_count'] = $info['login_fail_count'] >= 5 ? 1 : $info['login_fail_count']+1;
				$msg = '密码不正确，连续输错5次将被锁定';
				if($_data['login_fail_count'] == 5){
					$_data['login_unlock_time'] = CORE_TIME + 14400;
					$msg = '已输错5次密码，账号将锁定4小时';
				}
				$this->model('customer_contact')->where('user_id='.$info['user_id'])->update($_data);
				$this->_loginError($info['user_id'], $username, 4, $msg, 3);
				return array('err'=>1,'msg'=>$msg,'user'=>$info);
			}
		}
	}
	/**
	 *用户登录密码验证
	 */
	private function _getLoginPassword($param=array()){
		//获取对应用户的额密码盐
			$salt = $this->model('customer_contact')->select('salt')->where('mobile='.$param['mobile'])->getOne();
			return $this->_genPassword($param['password'].$salt);
	}
	/**
	 *用户登录错误日志
	 */
	private function _loginError($user_id=0,$mobile='',$err_code=1,$remark='',$chanel=3){
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
}