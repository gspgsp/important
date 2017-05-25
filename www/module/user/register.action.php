<?php
/**
 * 用户注册
 * @Author:yumeilin
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
	    $this->area=$this->db->model('lib_region')->where("pid=1")->getAll();
	    $city=$this->db->model('lib_region')->select('name')->where('id='.$_SESSION['reg_data']['origin']['1'])->getOne();
         $this->assign('city',$city);
	    $this->assign('reg_data_list',$_SESSION['reg_data']);    
	    $_SESSION['reg_data']=NULL;
		$this->display('register');
	}
	/**
     * 检查手机是否注册
     * @access public
     * @return bool
     */
	public function check_phone(){
	    $mobile=spost('phone','s');
	    if(!$this->_chkmobile($mobile)){ 
	        echo 'false';
	    }else{
	        echo 'true';
	    }
	}
	/**
     * 检查验证码
     * @access public
     * @return bool
     */
	public function check_regcode(){
	    if($this->reg_vcode){
	        $vcode=strtolower(spost('regcode','s'));
	        if(!chkVcode('regcode',$vcode)){	        
    	        echo 'false';
    	    }else{
    	        echo 'true';
    	    }
	    }
	}
	/**
     * 注册
     * @access public
     * @return json
     */
	public function reg(){		    
	    $_SESSION['reg_data']=$_POST;  
		$mobile=spost('phone','s');			
		$mcode=spost('code','s');			
		$result=M('system:sysSMS')->chkDynamicCode($mobile,$mcode);
			if($result['err']>0){
			    $this->error($result['msg'],'/user/register');
			}
		$password=sget('password','s');
		$repass=sget('password2','s');
		$origin=sget('origin');//省市
		$c_name=sget('c_name','s');
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
						'main_product'=>sget('main_product','s'),
						'com_intro'=>sget('com_intro','s'),
						'chanel'=>1,
						'origin'=>implode('|',$origin),
					    'input_time'=>CORE_TIME,
					    'update_time'=>CORE_TIME,
					);

					if(!$cus_model->add($_customer)) throw new Exception("系统错误 reg:101");
					$c_id=$cus_model->select('c_id')->where("c_name='$c_name'")->getOne();
				}
				$salt=randstr(6);
				$is_default=empty($customer)?1:0;
				$_user=array(
					'name'=>sget('name','s'),
					'mobile'=>$mobile,
				    'chanel'=>1,
					'input_time'=>CORE_TIME,
				    'update_time'=>CORE_TIME,
					'qq'=>sget('qq','s'),
					'c_id'=>$c_id,
					'salt'=>$salt,
					'is_default'=>$is_default,
					'password'=>$user_model->genPassword($password.$salt),
				);
				if(!$user_model->add($_user)) throw new Exception("系统错误 reg:102");
				$user_id=$user_model->select('user_id')->where("mobile='$mobile'")->getOne();
				$_info=array(
					'user_id'=>$user_id,
					'reg_ip'=>get_ip(),
					'reg_time'=>CORE_TIME,
				    'update_time'=>CORE_TIME,
					'reg_chanel'=>1,
				);
				//1.如果是新公司contact_id就为当前申请人user_id
				//2.否则为已存在公司的申请人user_id
				if(!$this->db->model('contact_info')->add($_info)) throw new Exception("系统错误 reg:103");
				if(!$customer){
				    if(!$this->db->model('customer')->where("c_id=$c_id")->update("contact_id=$user_id")) throw new Exception("系统错误 reg:104");
				}
			} catch (Exception $e) {
				$user_model->rollback();
				$this->error($e->getMessage(),'/user/register');
			}
		$user_model->commit();
		$_SESSION['mobile']=null;
		$msg=L("msg_template.register");
		M('system:sysMsg')->sendMsg($user_id,$msg,1);
		$_SESSION['register_success']=true;
		$_SESSION['reg_data']=NULL;	
		$this->forward('/user/register/regpass');
	}
	/**
     * 注册成功
     * @access public
     * @return html
     */
	public function regpass(){
	    if($_SESSION['register_success']==true){
        $this->display('register_success.html');
	    $_SESSION['register_success']=null;
	    }else{
	        $this->error('您访问的页面不存在','/');
	    }
	}
    /**
     * 发送手机验证码
     * @access public
     * @return html
     */
	public function sendmsg(){
		$this->is_ajax=true;
		//验证手机，验证码
		$mobile=sget('mobile','s');
		if(!$this->_chkmobile($mobile)){
			die;
		}		
		$sms=M('system:sysSMS');	
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
	        $this->err='手机号已经注册,请直接登陆';
	        return false;
	    }else{
	        return true;
	    }
	}
	/**
     * 模糊匹配公司
     * @access public
     * @return json
     */
	public function getCompany(){
		if($_GET){
			$company=sget('keyword','s','');
			$model=$this->db->model('customer');
			$list=$model->where("c_name like '$company%'")->select('c_id,c_name')->limit('10')->getAll();
			json_output($list);
		}
	}
}