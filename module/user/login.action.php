<?php

/**
 * 用户登录
 */
class loginAction extends homeBaseAction{

	protected $reg_vcode;
	public function __init()
	{
		$this->db=M('public:common');
		$this->reg_vcode = $this->sys['security']['reg']['vcode'];
	}

	public function init()
	{
		$this->display('logion');
	}

	public function dolongin()
	{
		if(isset($_REQUEST['username'])){
			$this->is_ajax=true;
			$username=sget('username','s');
			$password=sget('password','s');

			if(strlen($username)<10 || !is_mobile($username)){
				$this->error('账号或密码错误');
			}elseif(strlen($password)<6){
				$this->error('账号或密码错误');
			}
			$chanel=1; //web渠道
			$result=M('user:passport')->login($username,$password,$chanel);
			if($result['err']>0){
				$this->error($result['msg']);
			}else{
				//的三方授权登录绑定账号
				if($_SESSION['auth_openid']){
					M('user:userOuter')->bindUser($result['user']['user_id'],$_SESSION['auth_openid'],$_SESSION['auth_info']);
				}
				M('user:passport')->setSession($result['user']['user_id'],$result['user']);

				unset($_SESSION['gurl']);
				$this->success('登录成功');
			}
			$this->forward('/');exit;
		}
	}
	//短信验证码登录
	public function dolongin2()
	{
	    if(isset($_REQUEST['phonenum'])){
	        $this->is_ajax=true;
	        $phonenum=sget('phonenum','s');
	        $phonevaild=sget('phonevaild','s');

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
	        if(strlen($phonenum)<10 || !is_mobile($phonenum)){
	            $this->error('手机或验证码错误');
	        }elseif(strlen($phonevaild)<6){
	            $this->error('手机或验证码错误');
	        }
	        //检查验证码
	        if(!$this->_chkmcode($phonevaild,$phonenum)){
	            $this->error($this->err);
	        }
	        $user=$this->db->model('customer_contact')->select('*')->where("mobile='{$phonenum}' and status=1")->getRow();
	        if(empty($user)){
	            $this->error('当前账号不存在或错误的账号!');
	        }
	        $chanel=1; //web渠道
	        $result=M('user:passport')->login2($phonenum,$user['password'],$chanel);
	        //p($result);
	        if($result['err']>0){
	            $this->error($result['msg']);
	        }else{
	            //的三方授权登录绑定账号
	            if($_SESSION['auth_openid']){
	                M('user:userOuter')->bindUser($result['user']['user_id'],$_SESSION['auth_openid'],$_SESSION['auth_info']);
	            }
	            M('user:passport')->setSession($result['user']['user_id'],$result['user']);
	            unset($_SESSION['gurl']);
	            $this->success('登录成功');
	        }
	        $this->forward('/');exit;
	    }
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

	//登录弹窗
	public function loginbox()
	{
		$this->type=sget('type', 's', '');
		$this->display('loginbox');
	}

	public function auth(){
		$sns=thinkOauth::getInstance('qq');
		redirect($sns->getRequestCodeURL());
	}

	// 回调
	public function callback(){
		$type='qq';
		$sns=thinkOauth::getInstance($type);
		if(!$code=sget('code','s','')) $this->error('授权失败');
		$extend=null;
		if(!$token = $sns->getAccessToken($code , $extend)) $this->error('授权失败');
		$snsType = E('SnsType',APP_LIB.'extend');
		$snsInfo=$snsType->$type($token);
		$openid=$token['openid'];
		$outerModel=M('user:userOuter');
		$type=strtoupper($type);	
// 		p($openid);
// 		die;
		if($outerInfo=$outerModel->where("outer_id='{$openid}' and outer_source='{$type}'")->getRow()){
			$userInfo=M("public:common")->model('customer_contact')->where("user_id='{$outerInfo['user_id']}'")->getRow();
			M('user:passport')->setSession($outerInfo['user_id'],$userInfo);
			redirect('/user');
		}else{
			$_SESSION['auth_openid']=$openid;
			$_SESSION['auth_info']=$snsInfo;
			redirect('/user/login/bindLogin');
		}

	}

	public function bindLogin(){
		if($this->user_id>0) $this->forward('/user');
		if(!$openid=$_SESSION['auth_openid']) $this->forward('/user/login');
		$this->assign('auth_info',$_SESSION['auth_info']);
		$this->display('bindLogin');
	}
	
	/**
	 * 发送手机验证码
	 * @access public
	 * @return html
	 */
	public function sendMobileMsg($codeType=""){
	    $this->is_ajax=true; //指定为Ajax输出
	    $phonenum=spost('phonenum','s');
	    $phonevaild=spost('phonevaild','s');
	    if(empty($phonenum)){
	        $this->error('手机号码不能为空!');
	    }
	    $user=$this->db->model('customer_contact')->select('*')->where("mobile='{$phonenum}' and status=1")->getRow();
	    if(empty($user)){
	        $this->error('当前账号不存在或错误的账号!');
	    }
	    
	    //判断账号是否锁定
	    if($user['login_unlock_time'] > CORE_TIME){
	        $this->error('您的账号已被锁定，请稍候再试');
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
	    $rs = $sms->sendMobileMsg($user['user_id'],$mobile,$msg,$stype,$msgData);
	    if($rs['error']){
	        $this->error($rs['msg']);
	    }else{
	        $this->success();
	    }
	}

}