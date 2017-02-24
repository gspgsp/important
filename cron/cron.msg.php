<?php
/**
 * 发送短信
 * Create: Andy@2013-02-16
 */
 
require_once 'config.php';

$cron = new cronMsg;
$cron->start();

/**
 * 发送短信/邮件
 */
class cronMsg{
	private $db=NULL; //数据库资源
	private $otime=0; //当前时间
	private $logfile=''; //日志文件
	private $nlimit=10; //每次处理个数

	/**
	 * 构造函数
	 * @access public 
	 */
	public function __construct() {
		$this->otime=time();
		$this->logfile=CACHE_PATH.'log/cron/msg.log'; //日志文件
		$this->db=M('public:common');
	}

	/**
	 * 启动需要批处理的任务项目
	 * @access public 
	 */
	public function start(){
		$this->nlimit=20; //每次发送10条
		for($i=0;$i<18;$i++){
			$this->smsSend();
			$this->emailSend();
			sleep(3);
		}
	}
	
	/**
	 * 发送短信
	 * @access private 
	 */
	private function smsSend(){
		$log = array();
		$sms_setting = M('system:setting')->get('sms');

		$sms=M('system:sysSMS')->select('id,mobile,msg,chanel')->where('status=0 and stype<100 AND input_time <= '.time())->limit($this->nlimit)->getAll();
		if(empty($sms)){
			return false;
		}
		
		//短信白名单
		$white_list = preg_split('/\s/',$sms_setting['white_list']);
		foreach($sms as $v){
			if(in_array($v['mobile'],$white_list)){ //短信白名单不发送短信
				$status = 1;
				$channel=0;
			}else{
				try {
					SMS::SetChannel($sms_setting['main']); //指定发送通道;
					SMS::Send($v['mobile'],$v['msg']);
					$channel = SMS::$channel;
					$status = 1;
				}catch (Exception $e) {
					$status = 2;
					wlog($this->logfile,date('Y-m-d H:i:s')."短信发送失败，错误代码：".$e->getCode().' 错误信息：'.$e->getMessage()."\r\n");
					try {
						//尝试用备用通道发送
						if($sms_setting['backup'] && SMS::SetChannel($sms_setting['backup'])){
							SMS::Send($v['mobile'],$v['msg']);
							$channel = SMS::$channel;
							$status = 1;
						}
					}catch (Exception $e) {
						wlog($this->logfile,"短信发送失败，错误代码：".$e->getCode().' 错误信息：'.$e->getMessage()."\r\n");
					}
				}
			}
			M('system:sysSMS')->wherePk($v['id'])->update(array('status'=>$status,'chanel'=>$channel));	
		}
	}

	/**
	 * 发送邮件
	 */
	private function emailSend(){
		$smtp = new smtp(M('system:setting')->get('smtp'));
		$emails = M('system:sysMail')->where('status=0 AND input_time <= '.time())->limit($this->nlimit)->getAll();
		foreach ($emails as $email) {
			$success = $smtp->send($email['user_name'],$email['email'],$email['subject'],$email['content'],1);
			M('system:sysMail')->wherePk($msg['id'])->update(array('status'=>$success ? 1 : -1));
		}
	}
}
?>
