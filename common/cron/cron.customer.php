<?php
/**
 * 每天定点将customer表中的is_sale 和 is_pur 区分开来，一个是销售客户，一个是供应商
 */
require_once 'config.php';
$cron = new cronCustomer();
$cron->start();
class cronCustomer{
	private $db=NULL; //数据库资源
	private $otime=0; //当前时间
	private $logfile=''; //日志文件
	/**
	 * 构造函数
	 * @access public
	 */
	public function __construct() {
		set_time_limit(0);
		$this->otime=time();
		$this->logfile=CACHE_PATH.'log/cron/customer.log'; //日志文件
		$this->db=M('public:common');
	}

	public function start(){
		$result = $this->db->model('customer')->getAll('UPDATE `p2p_customer` AS cus LEFT JOIN `p2p_order` AS o ON  cus.`c_id` = o.`c_id` SET cus.`is_sale` = 1 WHERE  o.`order_type` = 1 AND cus.`is_sale` = 0');
		print_r($result);
		$res = $this->db->model('customer')->getAll('UPDATE `p2p_customer` AS cus LEFT JOIN `p2p_order` AS o ON  cus.`c_id` = o.`c_id` SET cus.`is_pur` = 1 WHERE o.`order_type` = 2 AND cus.`is_pur` = 0');
		print_r($res);
	}
}