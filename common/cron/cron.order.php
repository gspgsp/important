<?php
/**
 * 每5分钟处理的定时任务
 * Create: Andy@2013-02-16
 */
#*/5 * * * * * /usr/bin/php
require_once 'config.php';
$cron = new cronFiveMin;
$cron->start();

/**
 * 每周更新所需牌号
 */
class cronFiveMin{
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
		$this->logfile=CACHE_PATH.'log/cron/cronFiveMin.log'; //日志文件
		$this->db=M('public:common');
	}

	/**
	 * 启动需要批处理的任务项目
	 * @access public 
	 */
	public function start(){
		$this->orderProcess(); //处理订单（此定时任务处理的bug为有协助者的销售订单所关联的采购订单协助者看不到的问题）
		$this->orderStatus();//处理入库流水对应订单的开票状态（主要是为了处理有的入库没有付款就开始销售的情况）
	}

	private function orderProcess(){
		$time_stamp = strtotime('last day 23:59:59');
		set_time_limit(0);
		$sale = $this->db->select("o_id,join_id,store_o_id,partner")->model('order')->where(" `order_type` = 1 and `sales_type` = 2 and `partner` != `customer_manager` and `input_time` > $time_stamp ")->getAll();
		foreach ($sale as $k => $v) {
			//更新协助者的订单
			if($v['join_id']>0){
				$this->db->model('order')->where("`o_id` = {$v['join_id']} and `order_type` = 2 ")->update(array('customer_manager'=>$v['partner'],));
				$this->db->model('purchase_log')->where("`o_id` = {$v['join_id']}")->update(array('customer_manager'=>$v['partner'],));
			}
			
		}
	}
	//处理订单的付款状态
	private function orderStatus(){
		set_time_limit(0);
		$time_stamp = strtotime('-5 day 23:59:59');
		$in_log = $this->db->model('in_log')->where("input_time > $time_stamp")->getAll();
		if($in_log){
			foreach ($in_log as $k => $v) {
				//查询订单状态
				$pay = $this->db->model('order')->select('collection_status')->where("`o_id` = {$v['o_id']}")->getOne();
				$this->db->model('in_log')->where("`id` = {$v['id']}")->update(array('pay_status'=>$pay));
			}	
		}
	}
}