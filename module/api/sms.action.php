<?php
/*
 * 手机验证码发送请求
*/
class smsAction extends homeBaseAction {
	private $mobile=''; //发送手机号
	private $stype=0; //请求
    public function __init(){
    }
	
	//请求发送验证码
	public function init(){
		$this->is_ajax=true;
		$type=sget('type','s'); //发送类型
		$mobile=sget('mobile','s'); //发送手机号
		if(empty($type)){
			$this->error('错误的请求');	
		}
		if(empty($mobile) && $this->user_id>0){
			$mobile=$_SESSION['uinfo']['mobile'];
		}
		$this->mobile=$mobile;
		if(!is_mobile($this->mobile)){
			$this->error('错误的手机号码');
		}
		
		if(!method_exists($this,$type)){
			$this->error('错误的请求');
		}
		$this->$type(); //调用回调方法
	}
	
	//设置支付密码：发送动态密码
	private function setPayPasswd(){
		$this->stype=3; //发送
		if($this->user_id<1){
			$this->error('错误的用户请求');	
		}
		//用户当前手机号码
		$this->mobile=$_SESSION['uinfo']['mobile'];
		
		//请求动态码
		$result=M('system:sysSMS')->genDynamicCode($this->mobile);
		if($result['err']>0){ //请求错误
			$this->error($result['msg']);
		}
		
		$msg=$result['msg']; //短信内容
		$result=$this->send($msg);
		$this->json_output($result);
	}

	//找回支付密码：发送动态码
	private function findPayPasswd(){
		$this->stype=3; //发送

		//找回密码用户的手机号码
		
		//请求动态码
		$result=M('system:sysSMS')->genDynamicCode($this->mobile);
		if($result['err']>0){ //请求错误
			$this->error($result['msg']);
		}
		
		$msg=$result['msg']; //短信内容
		$result=$this->send($msg);
		$this->json_output($result);
	}
	
	//修改登录密码：发送动态密码
	private function modPasswd(){
		$this->stype=2; //发送
		if($this->user_id<1){
			$this->error('错误的用户请求');	
		}
		//用户当前手机号码
		$this->mobile=$_SESSION['uinfo']['mobile'];
		
		//请求动态码
		$result=M('system:sysSMS')->genDynamicCode($this->mobile);
		if($result['err']>0){ //请求错误
			$this->error($result['msg']);
		}
		
		$msg=$result['msg']; //短信内容
		$result=$this->send($msg);
		$this->json_output($result);
	}
	
	//找回密码：发送动态码
	private function passwdFind(){
		$this->stype=2; //发送

		//找回密码用户的手机号码
		
		//请求动态码
		$result=M('system:sysSMS')->genDynamicCode($this->mobile);
		if($result['err']>0){ //请求错误
			$this->error($result['msg']);
		}
		
		$msg=$result['msg']; //短信内容
		$result=$this->send($msg);
		$this->json_output($result);
	}
	

	/*
	 * 发送短信
     * @access public
	 * @param int $user_id 用户ID
	 * @param string $mobile 手机号
	 * @param string $msg 短信内容
	 * @param int $stype 类型:1注册2更新密码3支付密码4交易类,5提醒类,10促销类
     * @return (err,msg)
	 */
	private function send($msg=''){
		$mobile=$this->mobile;
		$db=M('system:sysSMS');
		
		//检查手机发送次数
		$num=$db->getSendNum($mobile,$this->stype);
		if($num>30){
			return array('err'=>1,'msg'=>'信息已发送多次，请耐心等待');
		}	
		
		$result=$db->send($this->user_id,$mobile,$msg,$this->stype);
		if($result){
			return array('err'=>0,'msg'=>'发送成功');
		}
		return array('err'=>1,'msg'=>'发送失败，请稍后尝试');
	}

}

?>