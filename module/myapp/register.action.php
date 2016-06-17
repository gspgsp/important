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
		$this->is_ajax = true;
		$mobile=sget('mobile','s');
		if(!$this->_chkmobile($mobile)) $this->error($this->err);
		$password=sget('password','s');
		if(!$this->_chkpass($password)) $this->error($this->err);
		$mcode=spost('code','s');
		$result=M('system:sysSMS')->chkDynamicCode($mobile,$mcode);
		if($result['err']>0){
			$this->error($result['msg']);
		}
		$user_model=M('system:sysUser');
		$salt=randstr(6);
		$_user=array(
					'mobile'=>$mobile,
					'input_time'=>CORE_TIME,
					'salt'=>$salt,
					'password'=>$user_model->genPassword($password.$salt),
				);
		if($user_model->add($_user)){
			$_SESSION['user_id'] = $user_model->getLastID();
			$_SESSION['check_reg_ok']=true;
			$this->success('注册成功');
			//$this->display('me_completeinfo');
		}

	}
	//进入补全信息页
	public function enReginfo()
	{
		$this->display('me_completeinfo');
	}
	//信息补全页面
	public function reginfo()
	{
		if(!$_SESSION['check_reg_ok']) $this->forward('/myapp/register');
		if($_POST)
		{
			$this->is_ajax=true;
			if(!sget('name','s')) $this->error('请输入姓名');
			if(!sget('qq','s')) $this->error('请输入qq号码');
			$c_name=sget('c_name','s');
			if(!$c_name) $this->error('请输入公司名称');
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
					'name'=>sget('name','s'),
					'qq'=>sget('qq','s'),
					'c_id'=>$c_id,
					'is_default'=>$is_default,
				);
				if(!$user_model->where('user_id='.$_SESSION['user_id'])->update($_user)) throw new Exception("系统错误 reg:102");
				$_info=array(
					'user_id'=>$_SESSION['user_id'],
					'reg_ip'=>get_ip(),
					'reg_time'=>CORE_TIME,
					'reg_chanel'=>2,
				);
				if(!$this->db->model('contact_info')->add($_info)) throw new Exception("系统错误 reg:103");
				if(!$customer){
					if(!$this->db->model('customer')->where("c_id=$c_id")->update("contact_id=".$_SESSION['user_id'])) throw new Exception("系统错误 reg:104");
				}
			} catch (Exception $e) {
				$user_model->rollback();
				$this->error($e->getMessage());
			}
			$user_model->commit();
			$_SESSION['mobile']=null;
			$_SESSION['check_reg_ok']=null;
			$this->success('完善成功');//完善成功后跳转到个人中心
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
		$sms=M('system:sysSMS');
		//检查注册的限制
		$result=$sms->chkRegLimit($mobile,get_ip());
		if(empty($result)){
			$this->error($sms->getError());
		}
		//请求动态码
		$result=$sms->genDynamicCode($mobile);
		if($result['err']>0){ //请求错误
			$this->error($result['msg']);
		}
		$msg=$result['msg']; //短信内容
		//发送手机动态码
		$sms->send(0,$mobile,$msg,2);
		$this->success('发送成功');
	}
}