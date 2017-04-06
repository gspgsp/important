<?php
/**
 * 用户注册--黎贤
 */
class registerAction extends homeBaseAction{

	protected $db,$reg_vcode;
	public function __init()
	{
		$this->db=M('public:common');
		$this->reg_vcode = $this->sys['security']['reg']['vcode'];
	}
	public function init()
	{
		$this->display('register');
	}

	//注册首页
	public function reg()
	{
		$mobile=sget('phone','s');
		if(!$this->_chkmobile($mobile)) $this->error($this->err);
		//验证码
		if($this->reg_vcode){
			$vcode=strtolower(sget('regcode','s'));
			if(empty($vcode)){
				$this->error('请输入验证码');
			}
			//检查验证码
			if(!chkVcode('regcode',$vcode)){
				$this->error('验证码不正确，请重新输入');	
			}
		}
		$mcode=spost('code','s');

		$result=M('system:sysSMS')->chkDynamicCode($mobile,$mcode);
		if($result['err']>0){
			$this->error($result['msg']);	
		}
		$_SESSION['check_reg_ok']=true;
		$_SESSION['mobile']=$mobile;
		$this->forward('/user/register/reginfo');

	}
	//注册第二页(填写注册信息)
	public function reginfo()
	{
		if(!$_SESSION['check_reg_ok']) $this->forward('/user/register');

		//第三方登陆
		$auth_openid=$_SESSION['auth_openid'];
		$auth_info=$_SESSION['auth_info'];
		unset($_SESSION['auth_openid']);
		unset($_SESSION['auth_info']);

		if($_POST)
		{
			$this->is_ajax=true;

			$mobile=$_SESSION['mobile'];
			// $mobile=13554911557;
			if(!$this->_chkmobile($mobile)) $this->error($this->err);
			
			$password=sget('password','s');
			$repass=sget('password2','s');

			$origin=sget('origin');//省市
			if(!$this->_chkpass($password,$repass)) $this->error($this->err);
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
						'type'=>sget('type','i'),
						'need_product'=>sget('need_product','s'),
						'com_intro'=>sget('com_intro','s'),
						'chanel'=>1,
						'origin'=>implode('|',$origin),
					);

					if(!$cus_model->add($_customer)) throw new Exception("系统错误 reg:101");
// 					$c_id=$cus_model->getLastID();
					$c_id=$cus_model->select('c_id')->where("c_name='$c_name'")->getOne();
				}
				$salt=randstr(6);
				$is_default=empty($customer)?1:0;
				$_user=array(
					'name'=>sget('name','s'),
					'mobile'=>$mobile,
					'input_time'=>CORE_TIME,
					'qq'=>sget('qq','s'),
					'c_id'=>$c_id,
					'salt'=>$salt,
					'is_default'=>$is_default,
					'password'=>$user_model->genPassword($password.$salt),
				);
				if(!$user_model->add($_user)) throw new Exception("系统错误 reg:102");
// 				$user_id=$user_model->getLastID();
				$user_id=$user_model->select('user_id')->where("mobile='$mobile'")->getOne();
				$_info=array(
					'user_id'=>$user_id,
					'reg_ip'=>get_ip(),
					'reg_time'=>CORE_TIME,
					'reg_chanel'=>1,
				);
				
				//1.如果是新公司contact_id就为当前申请人user_id
				//2.否则为已存在公司的

				if(!$this->db->model('contact_info')->add($_info)) throw new Exception("系统错误 reg:103");
				if(!$customer){
// 					if(!$this->db->model('customer')->where("c_id=$c_id")->update("contact_id=1")) throw new Exception("系统错误 reg:104");
				    if(!$this->db->model('customer')->where("c_id=$c_id")->update("contact_id=$user_id")) throw new Exception("系统错误 reg:104");
				}
// 				showtrace();
				//的三方授权登录绑定账号
				if($auth_openid){
					M('user:userOuter')->bindUser($user_id,$auth_openid,$auth_info);
				}
			} catch (Exception $e) {
				$user_model->rollback();
				$this->error($e->getMessage());
			}
			$user_model->commit();
			$_SESSION['mobile']=null;
			$_SESSION['check_reg_ok']=null;
			$msg=L("msg_template.register");
			M('system:sysMsg')->sendMsg($user_id,$msg,1);
			$this->success('注册成功');
		}else{
			//地区
			$this->area=$this->db->model('lib_region')->where("pid=1")->getAll();
			//企业类型
			$this->type=L('company_type');
			$this->display('reginfo');
		}
		
	}

	/**
	 * 验证密码
	 */
	private function _chkpass($pass,$repass){
		if(strlen($pass)<6){
			$this->err='密码格式不正确';
			return false;
		}
		if($pass!=$repass){
			$this->err='两次密码不一致';
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
		$chk=M('system:sysUser')->usrUnique('mobile',$value);//非重复
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
		//验证手机，邮箱，验证码
		$mobile=sget('mobile','s');
		if(!$this->_chkmobile($mobile)){
			$this->error($this->err);
		}

		if($this->reg_vcode){
			$vcode=sget('regcode','s');
			if(empty($vcode)){
				$this->error('请输入验证码');
			}
			//检查验证码
			if(!chkVcode('regcode',$vcode)){
				$this->error('验证码不正确，请重新输入');	
			}
		}
		
		$sms=M('system:sysSMS');

		//检查注册的限制
		// $result=$sms->chkRegLimit($mobile,get_ip());
		// if(empty($result)){
		// 	$this->error($sms->getError()); 	
		// }
		
		//请求动态码
		$result=$sms->genDynamicCode($mobile);
		if($result['err']>0){ //请求错误
			$this->error($result['msg']);
		}
		$msg=$result['msg']; //短信内容
		//发送手机动态码
		$sms->send(0,$mobile,$msg,1);
		$this->success('发送成功');
	}


//	// 获取已存在的客户
//	public function get_company()
//	{
//		if($_GET)
//		{
//			$pid=sget('pid','i',0);
//			$cid=sget('cid','i',0);
//			$origin=implode('|',array($pid,$cid));
//			// p($origin);
//			$model=$this->db->model('customer');
//			$list=$model->where("origin='{$origin}'")->select('c_id,c_name,need_product,com_intro,type,c_name as text')->getAll();
//			json_output($list);
//		}
//	}
	//模糊匹配公司
	public function getCompany(){
		if($_GET){
			$company=sget('keyword','s','');
			$model=$this->db->model('customer');
			$list=$model->where("c_name like '$company%'")->select('c_id,c_name')->limit('10')->getAll();
			json_output($list);

		}

////		spinx  模糊匹配
//		$company = trim(sget('keyword', 's', ''));
//		if($company){
//			$sphinx = new SphinxClient;
//			$sphinx->SetServer('localhost',9312);
//			$sphinx->SetMatchMode(SPH_MATCH_ALL);
//			$sphinx->setLimits(0,10,10);
//			$result = $sphinx->query('*'."$company".'*','customer');
//			$lids = array_keys($result['matches']);
//			$list = $this->db->model('customer')->get_search_list($lids);
//			json_output($list);
//		}

	}

}