<?php
/**
 * 每周执行任务
 * Create: Andy@2013-02-16
 */
#59 23 * * * * /usr/bin/php
 
require_once 'config.php';

$cron = new cronHour;
$cron->start();

/**
 * 每周更新所需牌号
 */
class cronHour{
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
		$this->logfile=CACHE_PATH.'log/cron/cronHour.log'; //日志文件
		$this->db=M('public:common');
	}

	/**
	 * 启动需要批处理的任务项目
	 * @access public 
	 */
	public function start(){
		$this->storeProduct(); //同步报价平台
	}
	//同步库存到报价平台每小时
	private function storeProduct(){
		set_time_limit(0);
		$this->db->model('share')->where('`is_stock` = 1')->delete();
		$data = $this->db->model('in_log')->select('*,(`remainder` -  `lock_number`) as `num`')->where(" 1 and  (`remainder` -  `lock_number`) > 0 and `pay_status` <> 1")->order('input_time desc')->getAll();
		if($data){
			foreach ($data as &$v) {
				unset($v['id']);
				$v['grade'] = M('product:product')->getModelById($v['p_id']);//牌号
				$v['factory'] = M('product:product')->getFnameByid($v['p_id']);//厂家名字
				$v['store'] = '上海';
				$v['ship_type'] = '自提';
				$v['true_price'] = '实价';
				$v['input_time'] = CORE_TIME;
				$v['uname'] = M('product:order')->getNameBySid($v['o_id']); //根据订单id获取交易员姓名
				$v['is_stock']  = 1;
				$v['cost'] =  $v['unit_price'];
				$v['pay'] = $this->db->model('order')->select('order_name')->where("`o_id` = {$v['o_id']}")->getOne()==1 ? '中晨' : '梓晨';
				$this->db->model('share')->add($v);
			}
		}
	}

}