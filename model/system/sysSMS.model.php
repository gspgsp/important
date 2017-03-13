<?php
/**
 * 发送短信 
 */
class sysSMSModel extends model{
	private $channel = 0;
	public function __construct() {
		parent::__construct(C('db_default'), 'log_sms');
	}
	
	/**
	 * 设置短信发送渠道
	 * @param int $channel 1=>'嘉讯软件', 2=>'食指网络',3=>'大汉三通',
	 */
	public function setChannel($channel){
		$this->channel = $channel;
		return $this;
	}
	/*
	 * 发送短信
     * @access public
	 * @param int $user_id 用户ID
	 * @param string $mobile 手机号
	 * @param string $msg 短信内容
	 * @param int $stype 类型:1注册2更新密码3支付密码4交易类,8提醒类,10促销类
	 * @param int $input_time 发送时间
     * @return bool
	 */
	public function send($user_id=0,$mobile='',$msg='',$stype=1,$input_time=0){

		if(!$input_time){
			//提醒类短信发送时间限制
			$input_time=CORE_TIME;
			if($stype==8){
				if(date('G') > 21){
					$input_time = strtotime('tomorrow 09:10:00');
				}elseif(date('G') < 9){
					$input_time = strtotime('today 09:10:00');
				}
			}
		}

		$_data=array(
			'user_id'=>(int)$user_id,		 
			'mobile'=>$mobile,		 
			'msg'=>addslashes($msg),		 
			'stype'=>(int)$stype,	
			'input_time'=>$input_time,
			'user_ip'=>get_ip(),
			'chanel'=>$this->channel
		);

		return $this->model('log_sms')->add($_data);
	}
	
	/**
	 * 批量发送短信
	 * @param  array   $mobiles 手机号码array(array(user_id=>0,mobile=>'138888888'))
	 * @param  string  $msg     短信内容
	 * @param  integer $stype 	类型:1注册2更新密码3支付密码4交易类,5提醒类,10促销类]
	 * @return bool
	 */
	public function sendBatch(array $mobiles,$msg='',$stype=1,$input_time=CORE_TIME){
		$sql_string = 'INSERT INTO '.$this->ftable.'(user_id,mobile,msg,stype,user_ip,input_time,chanel) VALUES';

		$msg = addslashes($msg);
		foreach($mobiles as $v){
			if(!is_numeric($v['mobile']) && strlen($v['mobile']) >= 11) continue;
			$user_ip = get_ip();
			$user_id = intval($v['user_id']);
			$mobile = $v['mobile'];
			$sql_string .= "('$user_id','$mobile','$msg','$stype','$user_ip',$input_time,'{$this->channel}'),";
		}
		$sql_string = rtrim($sql_string,',');
		return $this->execute($sql_string);
	}

	/*
	 * 检查发送数据[24小时内]
     * @access public
	 * @param string $mobile 手机号
	 * @param int $stype 类型
     * @return num
	 */
	public function getSendNum($mobile='',$stype=1){
		$where='input_time>'.(CORE_TIME-86400);
		if($stype){
			$where .= " and stype='$stype'";
		}
		if($mobile){
			$where .= " and mobile='$mobile'";
		}
		return $this->model('log_sms')->select('count(id)')->where($where)->getOne();
	}
	
	/*
	 * 生成动态码信息
        * @access public
	 * @param string $mobile 手机号
        * @return (err,msg)
	 */
	public function genDynamicCode($mobile,$stype=0){
		//有效期300秒
		if(isset($_SESSION['mcode']) && isset($_SESSION['mctime'])){
			if(CORE_TIME-$_SESSION['mctime']<60){
				return array('err'=>1,'msg'=>'动态码已发送成功，请稍候再试');
			}
			if(strpos($_SESSION['mcode'],$mobile)>0 && (CORE_TIME-$_SESSION['mctime'])<300){
				$mcode=substr($_SESSION['mcode'],0,6);
			}
		}
		if(empty($mcode)){
			$mcode=mt_rand(100820,999560);
		}
		
		$msg=sprintf(L('sms_template.dynamic_code'),$mcode);
		
		$_SESSION['mctype']=$stype;
		$_SESSION['mcode']=$mcode.'.'.$mobile;
		$_SESSION['mctime']=CORE_TIME;
		return array('err'=>0,'msg'=>$msg);
	}

	/*
    * 塑料圈app生成动态码信息
       * @access public
    * @param string $mobile 手机号
       * @return (err,msg)
    */
	public function qAppDynamicCode($mobile,$stype=0){
		//$cache=cache::startMemcache();
		$cache= E('RedisCluster',APP_LIB.'class');
		$mcode=$cache->get($mobile.'mcode');
		$mctype=$cache->get($mobile.'mctype');
		$mctime=$cache->get($mobile.'mctime');
		//有效期300秒
		if(isset($mcode) && isset($mctype)){
			if(CORE_TIME-($mctime)<15){
				return array('err'=>1,'msg'=>'动态码已发送成功，请稍候再试');
			}
			if(strpos($mcode,$mobile)>0 && (CORE_TIME-$mctime)<300){
				$mcode=substr($mcode,0,6);
			}
		}
		if(empty($mcode)){
			$mcode=mt_rand(100820,999560);
			$cache->set($mobile.'mcode',$mcode.'.'.$mobile,300);
			$cache->set($mobile.'mctime',CORE_TIME,300);
			$cache->set($mobile.'mctype',$stype,300);
		}
		$msg=sprintf(L('sms_template.dynamic_code'),$mcode);
		return array('err'=>0,'msg'=>$msg);
	}


	/*
	 * 检查动态码
     * @access public
	 * @param string $mobile 手机号
     * @return (err,msg)
	 */
	public function chkDynamicCode($mobile='',$mcode='',$stype=0){
		if(isset($_SESSION['mcode']) && isset($_SESSION['mctime'])){
			if((CORE_TIME-$_SESSION['mctime'])>300){
				return array('err'=>1,'msg'=>'手机动态码已失效');
			}
			if($stype && $stype != $_SESSION['mctype']){
				//return array('err'=>1,'msg'=>'手机动态码不正确');
			}
			
			wlog(CACHE_PATH.'sms/code.log',date("Y-m-d H:i:s")."@".$mcode.'.'.$mobile.' = '.json_encode($_SESSION)."--".get_ip()."\r\n\r\n");

			if($_SESSION['mcode']==$mcode.'.'.$mobile){
				unset($_SESSION['mcode']);
				unset($_SESSION['mctime']);
				return array('err'=>0,'msg'=>'验证通过');
			}
		}
		return array('err'=>1,'msg'=>'手机动态码输入不正确，请重新输入');	
	}

	/*
     * 塑料圈app检查动态码
     * @access public
     * @param string $mobile 手机号
     * @return (err,msg)
     */
	public function qAppChkDynamicCode($mobile='',$mcode='',$stype=0){
		//$cache=cache::startMemcache();
		$cache= E('RedisCluster',APP_LIB.'class');
		$mcode1=$cache->get($mobile.'mcode');
		$mctype=$cache->get($mobile.'mctype');
		$mctime=$cache->get($mobile.'mctime');
		if(isset($mcode1) && isset($mctime)){
			if((CORE_TIME-($cache->get($mobile.'mctime')))>300){
				return array('err'=>1,'msg'=>'手机动态码已失效');
			}
			if($stype && $stype != $cache->get($mobile.'mctype')){
				//return array('err'=>1,'msg'=>'手机动态码不正确');
			}

			wlog(CACHE_PATH.'sms/code.log',date("Y-m-d H:i:s")."@".$mcode.'.'.$mobile.' = '.json_encode($_SESSION)."--".get_ip()."\r\n\r\n");

			if($mcode1==$mcode.'.'.$mobile){
				$cache->delete($mobile.'mcode');
				$cache->delete($mobile.'mctime');
				$cache->delete($mobile.'mctype');
				return array('err'=>0,'msg'=>'验证通过');
			}
		}
		return array('err'=>1,'msg'=>'手机动态码输入不正确，请重新输入');
	}


	/*
	 * 发送手机短信
     * @access public
	 * @param string $mobile 手机号 $user_id 用户ID
     * @return (error,msg)
	 */
	public function sendMobileMsg($user_id,$mobile,$msgType="",$stype=1,$msg=array()){
		//检查手机发送次数:注册
		$num=$this->getSendNum($mobile,$stype);
		if($num>20){
			return array('error'=>1,'msg'=>'动态码已发送多次，请耐心等待');
		}
		
		//发送短信
		$mcode=0;
		
		//有效期300秒
		if(isset($_SESSION['mcode']) && isset($_SESSION['mctime'])){
			if(CORE_TIME-$_SESSION['mctime']<60){
				return array('error'=>1,'msg'=>'动态码已发送成功，请稍候再试');	
			}
			if(strpos($_SESSION['mcode'],$mobile)>0 && (CORE_TIME-$_SESSION['mctime'])<300){
				$mcode=substr($_SESSION['mcode'],0,6);
			}
		}
		if(empty($mcode)){
			$mcode=mt_rand(100820,999560);	
		}
		if($msg){
			$msg=sprintf(L($msgType),$msg['NO'],$msg['ACC']);
		}else{
			$msg=sprintf(L($msgType),$mcode);
		}
		//发送手机动态码
		$this->send($user_id,$mobile,$msg,$stype);
		$_SESSION['mcode']=$mcode.'.'.$mobile;
		$_SESSION['mctime']=CORE_TIME;
		return array('error'=>0,'msg'=>$msg);

	}
	
	/*
	 * 注册时限制检查
     * @access public
	 * @param string $mobile 手机号
     * @return bool	 */
	public function chkRegLimit($mobile='',$ip=''){
		$setting_security = getSystemParam('security');
		//ip限制
		$limit=(int)$setting_security['reg']['ip_limit'];
		if($limit>0){
			$white=M('system:whiteIp')->checkIp($ip);
			if(empty($white)){ //不在白名单中
				$exists = $this->model('log_sms')->select('count(id)')->where("user_ip='$ip' and stype=1 and input_time>".(time()-86400))->getOne();
				if($exists>=$limit){
					$this->error='您的请求过于频繁，请与客服联系';
					return false;
				}
			}
		}
		
		//检查手机发送次数:注册
		$limit=(int)$setting_security['reg']['mobile_limit'];
		if($limit>0){
			$num=$this->getSendNum($mobile,1);
			if($num>=$limit){
				$this->error='该手机号码已多次获取动态码，如仍未收到，请联系客服';
				return false;
			}
		}
		return true;
	}
}


?>
