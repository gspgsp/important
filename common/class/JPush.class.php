<?php
/**
 *激光推送消息
 **/
class JPush{
	private $key = '';
	private $secret = '';
	private $options = array();
	private static $instance = null;

	public function __construct(){
		$setting = M('system:setting')->get('app');
		if(empty($setting['jpush']) || empty($setting['jpush']['secret'])) throw new Exception('请先设置极光推送key和secret', 1);
		$this->key = trim($setting['jpush']['key']);
		$this->secret = trim($setting['jpush']['secret']);
		
		//推送环境
		$this->options['apns_production'] = $setting['jpush']['apns_production'];
	}

	/**
	 * 根据用户ID推送消息
	 * @param integer $user_id 用户ID
	 * @param string $msg     用户消息
	 * @param string $type    类型 item,msg,url
	 * @param string $value   类型数据
	 */
	public static function SendByUserId($user_id,$msg,$type='',$value=''){
		return self::Send(array('audience'=>array('alias'=>(array)$user_id),'message'=>array('msg_content'=>$msg,'content_type'=>$type,'extras'=>compact('type','value'))));
	}

	/**
	 * 推送消息
	 * http://docs.jpush.io/server/rest_api_v3_push/#_6
	 * @param  array $data 消息数组
	 * @return bool
	 */
	public static function Send(array $data){
		$class_name = __CLASS__;
		if(self::$instance == null) self::$instance = new $class_name;
		return self::$instance->_send($data);
	}

	/**
	 * 推送消息
	 */
	private function _send(array $data){
		$data = array_merge(array('platform'=>'all','audience'=>'all','options'=>$this->options), $data);

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, 'https://api.jpush.cn/v3/push');
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($curl, CURLOPT_USERPWD, implode(':',array($this->key,$this->secret)));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
		//curl_setopt($curl, CURLINFO_HEADER_OUT, 1);
		//curl_setopt($curl, CURLOPT_HEADER, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type'=>'application/json'));
		$ret = json_decode(curl_exec($curl),TRUE);
		if($ret){
			if($ret['error']) throw new Exception($ret['error']['message'], 1);
			return true;
		}
		throw new Exception('推送失败：'.curl_error($curl), 1);
	}
}
