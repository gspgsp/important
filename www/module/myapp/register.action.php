<?php
/**
 * 用户注册--app
 */
class registerAction extends homeBaseAction{

	protected $db;
	public function __init()
	{
		$this->db=M('public:common');
	}
	public function init()
	{
		$this->display('me_reg');
	}

	public function register()
	{
		if($_POST)
		{
			$this->is_ajax = true;
			$mobile=sget('mobile','s');
			if(!$this->_chkmobile($mobile)) $this->error($this->err);
			$password=sget('password','s');
			if(!$this->_chkpass($password)) $this->error($this->err);
			$mcode=sget('code','s');
			$result=M('myapp:msg')->chkDynamicCode($mobile,$mcode,1);
			if($result['err']>0){
				$this->error($result['msg']);
			}
			$user_model=M('system:sysUser');
			$salt=randstr(6);
			$passwordSalt = $user_model->genPassword($password.$salt);
			if(!sget('name','s')) $this->json_output(array('err'=>2,'msg'=>'请输入姓名'));
			$c_name=sget('c_name','s');
			if(!$c_name) $this->json_output(array('err'=>3,'msg'=>'请输入公司名称'));

			$cus_model=$this->db->model('customer');
			$customer=$cus_model->select('c_id')->where("c_name='$c_name'")->getOne();
			$user_model=M('system:sysUser');
			$user_model->startTrans();
			try {
				$c_id=$customer;
				if(!$customer){
					$_customer=array(
						'c_name'=>$c_name,
						'chanel'=>2,
					);
					if(!$cus_model->add($_customer)) throw new Exception("系统错误 reg:101");
					$c_id=$cus_model->getLastID();
				}
				$is_default=empty($customer)?1:0;
				$_user=array(
					'mobile'=>$mobile,
					'salt'=>$salt,
					'password'=>$passwordSalt,
					'name'=>sget('name','s'),
					'c_id'=>$c_id,
					'is_default'=>$is_default,
				);
				if(!$user_model->add($_user)) throw new Exception("系统错误 reg:102");
				$user_id=$user_model->getLastID();
				$_info=array(
					'user_id'=>$user_id,
					'reg_ip'=>get_ip(),
					'reg_time'=>CORE_TIME,
					'reg_chanel'=>2,
				);
				if(!$this->db->model('contact_info')->add($_info)) throw new Exception("系统错误 reg:103");
				if(!$customer){
					if(!$this->db->model('customer')->where("c_id=$c_id")->update("contact_id=".$user_id)) throw new Exception("系统错误 reg:104");
				}
			} catch (Exception $e) {
				$user_model->rollback();
				$this->error($e->getMessage());
			}
			$user_model->commit();
			$this->success('注册成功');
		}

	}

	/**
	 * 验证密码
	 */
	private function _chkpass($pass,$repass){
		if(strlen($pass)<6){
			$this->err='密码格式不正确,至少6位';
			return false;
		}
		return true;
	}


    /**
     * 验证手机号码
     * @access private
     * @return bool
     */
	private function _chkmobile($value=''){
		if(!is_mobile($value)){
			if(empty($value)){
				$this->err='请输入手机号码';
			}else{
				$this->err='错误的手机号码';
			}

			return false;
		}
		$chk=M('system:sysUser')->usrUnique('mobile',$value);
		if(!$chk){
			$this->err='手机号已存在';
			return false;
		}else{
			return true;
		}
	}

    /**
     * 发送手机验证码
     * @access public
     * @return html
     */
	public function sendmsg(){
		$this->is_ajax=true;
		//验证手机
		$mobile=sget('mobile','s');
		if(!$this->_chkmobile($mobile)){
			$this->error($this->err);
		}
		$sms=M('myapp:msg');
		//请求动态码
		$result=$sms->genDynamicCode($mobile,1);
		if($result['err']>0){ //请求错误
			$this->error($result['msg']);
		}
		$msg=$result['msg']; //短信内容
		//发送手机动态码
		$sms->send(0,$mobile,$msg,1);
		$this->success('发送成功');

	}
}