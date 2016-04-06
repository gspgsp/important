<?php
/**
 *发送短信接口类 
 *调用示例：
 * SMS::SetChannel('1');//设置短信发送渠道
 * SMS::Send($mobile,$msg); 
 *
 **/
class SMS{
	public static $channel = '1';//默认指定哪个接口
	private static $account = '';
	private static $password = '';
	private static $sign = '';

	/**
	 * 设置短信发送渠道
	 * @param  string $mobile 手机号码
	 * @param  string $msg    短信内容
	 * @return bool
	 */
	public static function SetChannel($channel,$account='',$password='',$sign=''){
		if(is_array($channel)) extract($channel);
		if(class_exists('SMS_'.$channel)){
			self::$channel = $channel;
			self::$account = $account;
			self::$password = $password;
			self::$sign = $sign;
			return true;
		}
		return false;
	}

	/**
	 * 发送短信
	 * @param  string $mobile 手机号码
	 * @param  string $msg    短信内容
	 * @return bool
	 */
	public static function Send($mobile,$msg){
		$class_name = 'SMS_'.self::$channel;
		$sms = new $class_name(self::$account, self::$password, self::$sign);
		
		return is_array($mobile) ? $sms->sendBatch($mobile,$msg) : $sms->send($mobile,$msg);
	}
}

/*
 短信发送基类
 */
abstract class SMSbase{
	protected $sign = '';//签名【随时融】
	protected $mobile = null;//手机号码
	protected $msg = null;//短信内容
	protected $account = '';
	protected $password = '';
	public $msg_id = null;//消息id

	public function __construct($account, $password, $sign){
		$this->account = $account;
		$this->password = $password;
		$this->sign = $sign;
	}

	/**
	 * 发送单条短信
	 * @return bool
	 */
	public function send($mobile,$msg){
		$this->_clear();
		$this->addMobile($mobile);
		$this->setMsg($msg);
		return $this->_send();
	}

	/**
	 * 批量发送短信
	 * @return bool
	 */
	public function sendBatch($mobile,$msg){
		$this->_clear();
		foreach((array)$mobile as $m) $this->addMobile($m);
		$this->setMsg($msg);
		return $this->_send();
	}

	/**
	 * 增加接收号码
	 * @param string $phone 手机号
	 */
	public function addMobile($phone){
		$phone = trim($phone);
		if($phone && preg_match('/^(13|14|15|17|18)\d{9}$/', $phone)){
			$this->mobile[] = $phone;
		}else{
			throw new Exception("手机号码有误！", 1);
		}
	}

	/**
	 * 设置短信内容
	 * @param string $msg 短信内容
	 */
	public function setMsg($msg){
		if(empty($msg)) throw new Exception("短信内容不能为空！", 2);
		elseif(mb_strlen($msg,'utf-8') > 580) throw new Exception("短信内容太长！", 2);
		$this->msg = $this->sign.$msg;
	}

	protected function _send(){}

	/**
	 * 清空短信资料
	 * @return void
	 */
	protected function _clear(){
		$this->mobile = array();
		$this->msg = '';
		return TRUE;
	}
}

/**
 * 创蓝文化
 * 该接口返回错误代码：
 * 101	无此用户
 * 102	密码错
 * 103	提交过快（提交速度超过流速限制）
 * 104	系统忙（因平台侧原因，暂时无法处理提交的短信）
 * 105	敏感短信（短信内容包含敏感词）
 * 106	消息长度错（>536或<=0）
 * 107	包含错误的手机号码
 * 108	手机号码个数错（群发>50000或<=0;单发>200或<=0）
 * 109	无发送额度（该用户可用短信数已使用完）
 * 110	不在发送时间内
 * 111	超出该账户当月发送额度限制
 * 112	无此产品，用户没有订购该产品
 * 113	extno格式错（非数字或者长度不对）
 * 115	自动审核驳回
 * 116	签名不合法，未带签名（用户必须带签名的前提下）
 * 117	IP地址认证错,请求调用的IP地址不是系统登记的IP地址
 * 118	用户没有相应的发送权限
 * 119	用户已过期
 */
class SMS_5 extends SMSbase{
	private $needstatus = false;//是否需要状态报告

	private $url = 'http://222.73.117.158/msg/';//接口地址
	
	/**
	 * 批量发送短信
	 * @return bool
	 */
	public function sendBatch(){
		return $this->_send('HttpBatchSendSM');
	}

	protected function _send($api_name='HttpBatchSendSM',array $params=array()){
		if($this->mobile && $this->msg){
			$data = array('pswd'=>$this->password, 'mobile'=>implode(',',$this->mobile),'needstatus'=>$this->needstatus?'true':'false');
			$data = array_merge($data,$params);
			foreach(array('account','msg','product','extno') as $k){
				$data[$k] = strval($this->$k);
			}

			$url = $this->url . $api_name . '?' . http_build_query($data);
			$ret = HttpClient::quickGet($url);
			$this->_clear();

			if($ret && strpos($ret,',')){
				$arr = explode(',',$ret);
				if($arr[1] === '0'){
					$arr = explode("\n",$ret);
					$this->msg_id = $ret[1];//取msgid
					return true;
				}
				throw new Exception("短信发送失败！".$arr[1], $arr[1]);
			}else{
				throw new Exception("系统错误，返回信息：{$ret}", 99);
			}
		}else{
			throw new Exception("短信资料不完整，无法发送！", 0);
		}
	}
}

/**
 * 上海月呈
 * 该接口返回错误信息：
 * ok	提交成功
 * 用户名或密码不能为空	提交的用户名或密码为空
 * 发送内容包含sql注入字符	包含sql注入字符
 * 用户名或密码错误	表示用户名或密码错误
 * 短信号码不能为空	提交的被叫号码为空
 * 短信内容不能为空	发送内容为空
 * 包含非法字符：	表示检查到不允许发送的非法字符
 * 对不起，您当前要发送的量大于您当前余额	当支付方式为预付费是，检查到账户余额不足
 * 其他错误	其他数据库操作方面的错误
 */
class SMS_4 extends SMSbase{
	private $url = 'http://sms.pro-group.com.cn/sms.aspx';//接口地址

	protected function _send(array $params=array()){
		if($this->mobile && $this->msg){
			$data = array('userid'=>252,'mobile'=>implode(';',$this->mobile),'content'=>$this->msg,'action'=>'send','countnumber'=>count($this->mobile),'mobilenumber'=>count($this->mobile));
			$data = array_merge($data,$params);
			foreach(array('account','password') as $k){
				$data[$k] = strval($this->$k);
			}

			$ret = HttpClient::quickPost($this->url,$data);

			$this->_clear();

			if($ret && ($arr = xml2array($ret))){
				if($arr['returnstatus'] == 'Success')
					return true;
				throw new Exception("短信发送失败：".$arr['message'], 44);
			}else{
				throw new Exception("短信发送失败：".var_export($ret,true), 99);
			}

		}else{
			throw new Exception("短信资料不完整，无法发送！", 1);
		}
	}
}

/**
 * 大汉三通
 * 该接口返回错误代码：
 * 0——提交成功；
 * 1——账号无效；
 * 2——密码错误；
 * 3——msgid不唯一；
 * 4——存在无效手机号码；
 * 5——手机号码个数超过最大限制；
 * 6——短信内容超过最大限制；
 * 7——扩展子号码无效；
 * 8——发送时间格式无效；
 * 9——请求来源地址无效；
 * 10——内容包含敏感词；
 * 11——余额不足；
 * 12——订购关系无效；
 * 13——短信签名无效；
 * 14——无效的手机子码；
 * 15——产品不存在；
 * 16——号码个数小于最小限制；
 * 17——超出流量监控
 * 18——业务标识无效
 * 19——用户被禁发
 * 20——ip鉴权失败
 * 21——短信内容为空
 * 97——接入方式错误；
 * 98——系统繁忙；
 * 99——消息格式错误。
 */
class SMS_3 extends SMSbase{
	private $subcode = '';//扩展号码

	private $url = 'http://3tong.net/http/sms/Submit';//接口地址

	protected function _send(array $params=array()){
		if($this->mobile && $this->msg){
			$data = array('phones'=>implode(',',$this->mobile),'password'=>md5($this->password),'content'=>$this->msg);
			$data = array_merge($data,$params);
			foreach(array('account','subcode') as $k){
				$data[$k] = strval($this->$k);
			}
			$data = xml_encode($data,'UTF-8','message');
//echo 'http://3tong.net/services/sms';
//echo iconv("utf-8","gbk",$data);
			if(class_exists('SoapClient')){
				$soap = new SoapClient('http://3tong.net/services/sms?wsdl');
				$ret = $soap->submit($data);
			}else{
				$ret = HttpClient::quickPost($this->url,$data);
			}
//echo iconv("utf-8","gbk",$ret);
//die();
			$this->_clear();

			if($ret && ($arr = xml2array($ret))){
				if($arr['result'] === '0'){
					return true;
				}
				throw new Exception("短信发送失败：".$arr['desc'], $arr['result']);
			}else{
				throw new Exception("系统错误，返回信息：{$ret}", 99);
			}

		}else{
			throw new Exception("短信资料不完整，无法发送！", 0);
		}
	}
}

/**
 * 食指网络
 * 该接口返回错误代码：
 * 0——提交成功；
 * 1——账号无效；
 * 2——密码错误；
 * 3——msgid不唯一；
 * 4——存在无效手机号码；
 * 5——手机号码个数超过最大限制；
 * 6——短信内容超过最大限制；
 * 7——扩展子号码无效；
 * 8——发送时间格式无效；
 * 9——请求来源地址无效；
 * 10——内容包含敏感词；
 * 11——余额不足；
 * 12——订购关系无效；
 * 13——短信签名无效；
 * 14——无效的手机子码；
 * 15——产品不存在；
 * 16——号码个数小于最小限制；
 * 17——超出流量监控
 * 18——业务标识无效
 * 19——用户被禁发
 * 20——ip鉴权失败
 * 21——短信内容为空
 * 97——接入方式错误；
 * 98——系统繁忙；
 * 99——消息格式错误。
 */
class SMS_2 extends SMSbase{
	private $subcode = '';//扩展号码

	private $url = 'http://112.65.212.107:8191/if/http/sms/Submit';//接口地址


	// public function setMsg($msg){
	// 	parent::setMsg($msg);
	// 	$this->msg .= $this->sign;
	// }

	protected function _send(array $params=array()){
		if($this->mobile && $this->msg){
			$data = array('phones'=>implode(',',$this->mobile),'password'=>md5($this->password),'content'=>$this->msg);
			$data = array_merge($data,$params);
			foreach(array('account','subcode') as $k){
				$data[$k] = strval($this->$k);
			}
			$data = xml_encode($data,'UTF-8','message');

			if(class_exists('SoapClient')){
				$soap = new SoapClient('http://112.65.212.107:8191/if/services/sms?wsdl');
				$ret = $soap->submit($data);
			}else{
				$ret = HttpClient::quickPost($this->url,$data);
			}
			$this->_clear();

			if($ret && ($arr = xml2array($ret))){
				if($arr['result'] === '0'){
					return true;
				}
				throw new Exception("短信发送失败：".$arr['desc'], $arr['result']);
			}else{
				throw new Exception("系统错误，返回信息：{$ret}", 99);
			}

		}else{
			throw new Exception("短信资料不完整，无法发送！", 0);
		}
	}
}

/**
 *	嘉讯软件
 * 该接口返回错误代码：
 * 101	无此用户
 * 102	密码错
 * 103	提交过快（提交速度超过流速限制）
 * 104	系统忙（因平台侧原因，暂时无法处理提交的短信）
 * 105	敏感短信（短信内容包含敏感词）
 * 106	消息长度错（>536或<=0）
 * 107	包含错误的手机号码
 * 108	手机号码个数错（群发>50000或<=0;单发>200或<=0）
 * 109	无发送额度（该用户可用短信数已使用完）
 * 110	不在发送时间内
 * 111	超出该账户当月发送额度限制
 * 112	无此产品，用户没有订购该产品
 * 113	extno格式错（非数字或者长度不对）
 * 115	自动审核驳回
 * 116	签名不合法，未带签名（用户必须带签名的前提下）
 * 117	IP地址认证错,请求调用的IP地址不是系统登记的IP地址
 * 118	用户没有相应的发送权限
 * 119	用户已过期
 * 
 */
class SMS_1 extends SMSbase{
	private $needstatus = false;//是否需要状态报告
	private $product = '';//可选参数。用户订购的产品id，不填写（针对老用户）系统采用用户的默认产品，用户订购多个产品时必填，否则会发生计费错误。
	private $extno = '';//可选参数，扩展码，用户定义扩展码，3位

	private $url = 'http://117.135.143.35/msg/';//接口地址

	/**
	 * 批量发送短信
	 * @return bool
	 */
	public function sendBatch(){
		return $this->_send('HttpBatchSendSM');
	}

	protected function _send($api_name='HttpSendSM',array $params=array()){
		if($this->mobile && $this->msg){
			$data = array('pswd'=>$this->password, 'mobile'=>implode(',',$this->mobile),'needstatus'=>$this->needstatus?'true':'false');
			$data = array_merge($data,$params);
			foreach(array('account','msg','product','extno') as $k){
				$data[$k] = strval($this->$k);
			}

			$url = $this->url . $api_name . '?' . http_build_query($data);
			//die($url);
			$ret = HttpClient::quickGet($url);
			$this->_clear();

			if($ret && strpos($ret,',')){
				$arr = explode(',',$ret);
				if($arr[1] === '0'){
					$arr = explode("\n",$ret);
					$this->msg_id = $ret[1];//取msgid
					return true;
				}
				throw new Exception("短信发送失败！", $arr[1]);
			}else{
				throw new Exception("系统错误，返回信息：{$ret}", 99);
			}

		}else{
			throw new Exception("短信资料不完整，无法发送！", 0);
		}
	}
}
