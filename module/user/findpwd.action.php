<?php
class findpwdAction extends homeBaseAction{

	protected $err,$reg_vcode;
	public function __init(){
		$this->reg_vcode = $this->sys['security']['reg']['vcode'];
	}
	public function init(){
		if($_POST){
			$this->is_ajax=true;
			$data=saddslashes($_POST);
			if(!$this->_chkmobile($data['phone'])) $this->error($this->err);
			$result=M('system:sysSMS')->chkDynamicCode($data['phone'],$data['code']);
			p($result);die;
			if($result['err']>0){
				$this->error($result['msg']);	
			}
			$_SESSION['mobile']=$data['phone'];
			$_SESSION['check_find_ok']=true;
			$this->success('验证通过');
		}else{
			$this->display('findpwd');
		}
	}

	public function dofind(){
		if(!$_SESSION['check_find_ok']||!$_SESSION['mobile']) $this->error('验证时间过期，请重新验证。','/user/login');
		if($_POST){
			$this->is_ajax=true;
			$pass=sget('pass','s','');
			$repass=sget('repass','s','');
			$mobile=$_SESSION['mobile'];
			if(!$this->_chkpass($pass,$repass)) $this->error($this->err);
			$model=M("user:customerContact");
			if(!$uinfo=$model->where("mobile=$mobile")->getRow()) $this->error('错误的账号');
			$newpass=M('system:sysUser')->genPassword($pass.$uinfo['salt']);
			$_data=array(
				'password'=>$newpass,
				'update_time'=>CORE_TIME,
			);
			if(!$model->where("mobile=$mobile")->update($_data)) $this->error('操作失败，系统错误');
			unset($_SESSION['check_find_ok']);
			unset($_SESSION['mobile']);
			$this->success('修改成功');
		}else{
			$this->display('dofind');
		}
	}

	public function findSuccess(){
		$this->display('find_success');
	}

	/**
	 * 验证密码
	 */
	private function _chkpass($pass,$repass){
		if(strlen($pass)<6||strlen($pass)>16){
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
		if($chk){
			$this->err='账号不存在';
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
		if(!$this->_chkmobile($mobile)){$this->error($this->err);}
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
}