<?php
/**
 * 每周执行任务
 * Create: Andy@2013-02-16
 */
#59 23 * * * * /usr/bin/php
 
require_once 'config.php';

$cron = new cronWeek;
$cron->start();

/**
 * 每周更新所需牌号
 */
class cronWeek{
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
		$this->logfile=CACHE_PATH.'log/cron/week.log'; //日志文件
		$this->db=M('public:common');
	}

	/**
	 * 启动需要批处理的任务项目
	 * @access public 
	 */
	public function start(){
		$this->dbProcess(); //处理所需牌号更新
		$this->invoice();
	}

	private function dbProcess(){
		set_time_limit(0);
		$arr = array();
		$arrpur = array();
		$sale = $this->db->select("c_id,o_id")->model('order')->where(" `order_status` = 2 and `transport_status` = 2 and `order_type` = 1 ")->getAll();
		$pur = $this->db->select("c_id,o_id")->model('order')->where(" `order_status` = 2 and `transport_status` = 2 and `order_type` = 2 ")->getAll();
		foreach ($sale as $k => $v) {
			$arr[$v['c_id']][] = $v['o_id']; 
		}
		foreach ($pur as $k => $v) {
			$arrpur[$v['c_id']][]= $v['o_id']; 
		}
		foreach ($arr as $key => $value) {
			//查询订单明细
			$sales =  $this->db->model('sale_log')->select('p_id')->where("o_id in (".join($value,',').")")->getCol();
			$purs =  $this->db->model('purchase_log')->select('p_id')->where("o_id in (".join($arrpur[$key],',').")")->getCol();
			$pids = array_unique(array_merge($sales,$purs));
			$models = array_unique($this->db->model('product')->select('model')->where("id in(".join($pids,',').")")->getCol());
			//更新需要的牌号
			$this->db->model('customer')->where("c_id = $key")->update(array('need_product_adm'=>join($models,',')));
		}
	}
	//开票资料维护
	private function invoice(){
		set_time_limit(0);
		$data = $this->db->model('customer_billing')->select('c_id')->where("c_id > 0")->getCol();
		if($data){
			foreach ($data as $k => $v) {
				$this->db->model('customer')->where("c_id =  $v")->update(array('invoice'=>2));
			}
		}
	}

}
