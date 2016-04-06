<?php
/**
 * 发送邮件 
 */
class sysMailModel extends model{
	private $smtp = null;

	public function __construct() {
		parent::__construct(C('db_default'), 'log_mail');
	}
	
	/**
	 * 发送邮件
	 * @param  integer $user_id   用户ID
	 * @param  string  $email     email地址
	 * @param  string  $subject   邮件标题
	 * @param  string  $content   邮件内容
	 * @param  string  $user_name 用户名称
	 * @param  integer $stype     邮件类型：1邮箱验证 2网站通知
	 * @return bool
	 */
	public function send($user_id,$email='',$subject='',$content='',$user_name='',$stype=1){

		if(empty($email)) return false;

		$_data=array(
			'user_id'=>(int)$user_id,
			'user_name'=>$user_name,	 
			'email'=>$email,
			'subject'=>addslashes($subject),
			'content'=>addslashes($this->_assignParams($content)),
			'stype'=>(int)$stype,	
			'input_time'=>CORE_TIME,
			'status'=>0
		);

		return $this->model('log_mail')->add($_data);
	}

	/**
	 * 批量发送邮件
	 * @param  array   $emails  email信息 array(array(user_id=>0,email=>'',user_name=>''))
	 * @param  string  $subject   邮件标题
	 * @param  string  $content   邮件内容
	 * @param  integer $stype 	  邮件类型：1邮箱验证 2网站通知
	 * @return bool
	 */
	public function sendBatch(array $emails,$subject='',$content='',$stype=1){
		$sql_string = 'INSERT INTO '.$this->ftable.'(user_id,user_name,email,subject,content,stype,input_time,status) VALUES';

		$input_time = CORE_TIME;
		$subject = addslashes($subject);
		$content = addslashes($this->_assignParams($content));
		foreach($emails as $v){
			if(!is_array($v)) continue;

			$user_id = intval($v['user_id']);
			$user_name = isset($v['user_name']) ? addslashes($v['user_name']) : '';

			$email = addslashes($v['email']);
			$sql_string .= "('$user_id','$user_name','$email','$subject','$content','$stype',$input_time,'0'),";
		}

		$sql_string = rtrim($sql_string,',');
		return $this->execute($sql_string);
	}

	private function _assignParams(&$content){
		$sys = M('system:setting')->getSetting();
		$content = str_replace(array('{__IMG__}','{APP_URL}','{SITE_NAME}','{COMPANY_NAME}','{SERVICE_PHONE}','{SERVICE_EMAIL}'),
							   array(C('TEMP_REPLACE.__IMG__'), APP_URL, $sys['site_name'], $sys['company_name'], $sys['service_phone'], $sys['service_email']),$content);
		return $content;
	}
}
