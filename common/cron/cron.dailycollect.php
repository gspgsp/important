<?php
/**
 * 每天统计业务员首页 《每日数据汇总》的8项指标数据
 */
require_once 'config.php';

$cron = new cronDailycollect;
$cron->start();
/**
 * 每分钟需处理计划任务主类
 */
class cronDailycollect{
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
		$this->logfile=CACHE_PATH.'log/cron/report.log'; //日志文件
		$this->db=M('public:common');
	}

	/**
	 * 启动需要处理的任务项目
	 * @access public 
	 */
	public function start(){
		$res = $this->get_report_user_data(); //获取统计数据
		//检查当天数据是否采集
		$collect_date  = strtotime(date("Y-m-d"),time());
		$date = $this->db->select('collect_date')->model('daily_collect')->where('collect_date='.$collect_date)->getOne();
		if(!$date){
			foreach ($res as $key => $value) {
				foreach ($value as $k => $v) {
					if(!$v){
						$value[$k] = 0;
					}
				}
				$data['input_time'] = time(); 
				$data['collect_date'] = strtotime(date('Y-m-d',time()));
				$this->db->model('daily_collect')->add($data+$value);
				// showtrace();
			}
		}else{
			foreach ($res as $key => $value) {
				foreach ($value as $k => $v) {
					if(!$v){
						$value[$k] = 0;
					}
				}
				$data['input_time'] = time(); 
				$data['collect_date'] = strtotime(date('Y-m-d',time())); 
				$where = 'customer_manager = '.$value['customer_manager'].' and collect_date = '.$collect_date;
				$this->db->model('daily_collect')->where($where)->update($data+$value);
			}
		}
	}
	public function get_report_user_data(){
		//今日新增客户数
		$res1 = $this->get_today_new_cilents();
		//今日客户成交数
		$res2 = $this->get_today_new_cilents_orders();
		//今日客户跟进数
		$res3 = $this->get_today_follow_cilents();
		// 今日电话数
		$res4 = $this->get_toady_calls();
		// 今日销售
		$res5 = $this->get_today_sales();
		// 今日采购
		$res6 = $this->get_today_buys();
		// 今日利润
		$res7 = $this->get_today_profit();
		// 目标完成率
		$res8 = $this->get_profit_rate();
		$res = array_merge($res1,$res2,$res3,$res4,$res5,$res6,$res7,$res8);
		foreach ($res as $key => $value) {
			if(array_key_exists('customer_manager',$value)){
				$temp[$value['customer_manager']]['customer_manager']  = $value['customer_manager'];
			}
			if (array_key_exists('name',$value)) {
				$temp[$value['customer_manager']]['name']  = $value['name'];
			}
			if (array_key_exists('team_id',$value)) {
				$temp[$value['customer_manager']]['team_id']  = $value['team_id'];
			}
			if (array_key_exists('new_clients',$value)) {
				$temp[$value['customer_manager']]['new_clients']  = $value['new_clients'];
			}
			if (array_key_exists('new_clients_ids',$value)) {
				$temp[$value['customer_manager']]['new_clients_ids']  = $value['new_clients_ids'];
			}

			if (array_key_exists('clients_orders',$value)) {
				$temp[$value['customer_manager']]['clients_orders']  = $value['clients_orders'];
			}
			if (array_key_exists('clients_orders_ids',$value)) {
				$temp[$value['customer_manager']]['clients_orders_ids']  = $value['clients_orders_ids'];
			}
			if (array_key_exists('new_clients_ids',$value)) {
				$temp[$value['customer_manager']]['new_clients_ids']  = $value['new_clients_ids'];
			}
			if (array_key_exists('follow_clients',$value)) {
				$temp[$value['customer_manager']]['follow_clients']  = $value['follow_clients'];
			}
			if (array_key_exists('follow_clients_ids',$value)) {
				$temp[$value['customer_manager']]['follow_clients_ids']  = $value['follow_clients_ids'];
			}
			if (array_key_exists('calls',$value)) {
				$temp[$value['customer_manager']]['calls']  = $value['calls'];
			}
			if (array_key_exists('today_sales_oids',$value)) {
				$temp[$value['customer_manager']]['today_sales_oids']  = $value['today_sales_oids'];
			}
			if (array_key_exists('today_sales',$value)) {
				$temp[$value['customer_manager']]['today_sales']  = $value['today_sales'];
			}
			if (array_key_exists('today_buys_oids',$value)) {
				$temp[$value['customer_manager']]['today_buys_oids']  = $value['today_buys_oids'];
			}
			if (array_key_exists('today_buys',$value)) {
				$temp[$value['customer_manager']]['today_buys']  = $value['today_buys'];
			}
			if (array_key_exists('profit',$value)) {
				$temp[$value['customer_manager']]['profit']  = $value['profit'];
			}
			if (array_key_exists('profit_oids',$value)) {
				$temp[$value['customer_manager']]['profit_oids']  = $value['profit_oids'];
			}
			if (array_key_exists('finish_rate',$value)) {
				$temp[$value['customer_manager']]['finish_rate']  = $value['finish_rate'];
			}

		}
			return $temp;
	}
	//今日新增客户数
	public function get_today_new_cilents(){
		$today_begin = strtotime(date('Y-m-d',time()));
		// $today_begin = 1474278400;
		$res = $this->db->model('customer')->getAll("SELECT aa.new_clients,aa.new_clients_ids,bb.customer_manager,bb.name,bb.team_id FROM (
			SELECT COUNT(c_id) AS new_clients,GROUP_CONCAT(c_id) AS new_clients_ids,`customer_manager`
			FROM p2p_customer
			WHERE input_time > ".$today_begin."
			GROUP BY customer_manager) AS aa
		RIGHT JOIN (
			SELECT admin.`admin_id` AS customer_manager,admin.`name`,role.`id` as team_id FROM p2p_admin AS admin
			LEFT JOIN p2p_adm_role_user AS role_user ON role_user.`user_id` = admin.`admin_id`
			LEFT JOIN p2p_adm_role AS role ON role.`id` = role_user.`role_id`
			WHERE role.pid = 22 AND admin.`status` =1
		) AS bb ON aa.customer_manager = bb.customer_manager");
		return $res;
	}
	//今日客户成交数
	public function get_today_new_cilents_orders(){
		$today_begin = strtotime(date('Y-m-d',time()));
		// $today_begin = 1474278400;
		$res = $this->db->model('order')
							->getAll("SELECT aa.clients_orders,aa.clients_orders_ids,bb.customer_manager,bb.name,bb.team_id FROM (
			SELECT  COUNT(DISTINCT(c_id)) AS clients_orders,GROUP_CONCAT(DISTINCT(c_id)) AS clients_orders_ids,customer_manager
			FROM p2p_order
			WHERE order_type=1 AND collection_status = 3 AND payd_time >".$today_begin."
			GROUP BY customer_manager) AS aa
		RIGHT JOIN (
			SELECT admin.`admin_id` AS customer_manager,admin.`name`,role.`id` as team_id FROM p2p_admin AS admin
			LEFT JOIN p2p_adm_role_user AS role_user ON role_user.`user_id` = admin.`admin_id`
			LEFT JOIN p2p_adm_role AS role ON role.`id` = role_user.`role_id`
			WHERE role.pid = 22 AND admin.`status` =1
		) AS bb ON aa.customer_manager = bb.customer_manager");
		return 	$res;	
	}
	//今日跟进客户
	public function get_today_follow_cilents(){
		$today_begin = strtotime(date('Y-m-d',time()));
		// $today_begin = 1472200000;
		$res = $this->db->model('customer_follow')
				   ->getAll("SELECT aa.follow_clients,aa.follow_clients_ids,bb.customer_manager,bb.name,bb.team_id FROM (
			SELECT COUNT(`id`) AS follow_clients,GROUP_CONCAT(id) AS follow_clients_ids, `customer_manager`
			FROM p2p_customer_follow
			WHERE input_time >".$today_begin."
			GROUP BY customer_manager) AS aa
		RIGHT JOIN (
			SELECT admin.`admin_id` AS customer_manager,admin.`name`,role.`id` as team_id FROM p2p_admin AS admin
			LEFT JOIN p2p_adm_role_user AS role_user ON role_user.`user_id` = admin.`admin_id`
			LEFT JOIN p2p_adm_role AS role ON role.`id` = role_user.`role_id`
			WHERE role.pid = 22 AND admin.`status` =1
		) AS bb ON aa.customer_manager = bb.customer_manager");
		// p($res);
		return $res;
	}
	// 今日电话数
	public function get_toady_calls(){
		$today_begin = strtotime(date('Y-m-d',time()));
		// $today_begin = 1475251200;
		$res = $this->db->model('api')
			->getAll('SELECT aa.`calls`,bb.customer_manager,bb.name,bb.team_id FROM (
			SELECT api.*,admin.`admin_id` AS customer_manager
			FROM
			(SELECT COUNT(id) AS `calls`,phone FROM `p2p_api` WHERE ctime > '.$today_begin.' AND TIME > 0 AND callstatus="ou" GROUP BY phone)
			AS api 
			LEFT JOIN `p2p_admin` AS admin ON admin.seat_phone = api.`phone`
			WHERE admin.name IS NOT NULL) AS aa
		RIGHT JOIN (
			SELECT admin.`admin_id` AS customer_manager,admin.`name`,role.`id` as team_id FROM p2p_admin AS admin
			LEFT JOIN p2p_adm_role_user AS role_user ON role_user.`user_id` = admin.`admin_id`
			LEFT JOIN p2p_adm_role AS role ON role.`id` = role_user.`role_id`
			WHERE role.pid = 22 AND admin.`status` =1
		) AS bb ON aa.customer_manager = bb.customer_manager');
		return $res;
	}
	// 今日销售
	public function get_today_sales(){
		$today_start = strtotime(date("Y-m-d",time()));
		// $today_start = 1473728400;
		$res = $this->db->model('order')
						 ->getAll("SELECT aa.today_sales,aa.today_sales_oids,bb.customer_manager,bb.name,bb.team_id FROM (
			SELECT GROUP_CONCAT(`o_id`) AS today_sales_oids, SUM(`total_num`) AS today_sales,customer_manager
			FROM p2p_order
			WHERE order_type = 1 AND order_status = 2 AND transport_status != 3 AND input_time > ".$today_start."
			GROUP BY customer_manager) AS aa
		RIGHT JOIN (
			SELECT admin.`admin_id` AS customer_manager,admin.`name`,role.`id` as team_id FROM p2p_admin AS admin
			LEFT JOIN p2p_adm_role_user AS role_user ON role_user.`user_id` = admin.`admin_id`
			LEFT JOIN p2p_adm_role AS role ON role.`id` = role_user.`role_id`
			WHERE role.pid = 22 AND admin.`status` =1
		) AS bb ON aa.customer_manager = bb.customer_manager");
		return $res;
	}
	//今日采购
	public function get_today_buys(){
		$today_start = strtotime(date("Y-m-d",time()));
		// $today_start = 1473728400;
		$res = $this->db->model('order')
						 ->getAll("SELECT aa.today_buys,aa.today_buys_oids,bb.customer_manager,bb.name,bb.team_id FROM (
			SELECT GROUP_CONCAT(`o_id`) AS today_buys_oids, SUM(`total_num`) AS today_buys,customer_manager
			FROM p2p_order
			WHERE order_type = 2 AND order_status = 2 AND transport_status = 2 AND input_time > ".$today_start."
			GROUP BY customer_manager) AS aa
		RIGHT JOIN (
			SELECT admin.`admin_id` AS customer_manager,admin.`name`,role.`id` as team_id FROM p2p_admin AS admin
			LEFT JOIN p2p_adm_role_user AS role_user ON role_user.`user_id` = admin.`admin_id`
			LEFT JOIN p2p_adm_role AS role ON role.`id` = role_user.`role_id`
			WHERE role.pid = 22 AND admin.`status` =1
		) AS bb ON aa.customer_manager = bb.customer_manager");

		return $res;
	}
	//今日利润
	public function get_today_profit(){
		$today_start = strtotime(date('Y-m-d',time()));
		// $today_start = 1470284800;
		$res = $this->db->model('sale_log as `sale`')
					->getAll("SELECT aa.profit_oids,aa.profit,bb.customer_manager,bb.name,bb.team_id FROM (
			SELECT GROUP_CONCAT(DISTINCT(o.`o_id`)) AS profit_oids,SUM((`sale`.unit_price - pur.unit_price)*`sale`.number) AS profit,o.`customer_manager`
			FROM p2p_sale_log AS `sale`
			LEFT JOIN `p2p_purchase_log` `pur` ON `sale`.purchase_id = pur.id
			LEFT JOIN `p2p_order` `o` ON `o`.o_id = `sale`.o_id
			WHERE o.`collection_status`=3 AND o.payd_time > ".$today_start." AND `sale`.purchase_id <> 0
			GROUP BY `customer_manager`) AS aa
		RIGHT JOIN (
			SELECT admin.`admin_id` AS customer_manager,admin.`name`,role.`id` as team_id FROM p2p_admin AS admin
			LEFT JOIN p2p_adm_role_user AS role_user ON role_user.`user_id` = admin.`admin_id`
			LEFT JOIN p2p_adm_role AS role ON role.`id` = role_user.`role_id`
			WHERE role.pid = 22 AND admin.`status` =1
		) AS bb ON aa.customer_manager = bb.customer_manager");
		return $res;
	}
	//目标完成率
	public function get_profit_rate(){
		$start=strtotime( date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m"),1,date("Y"))) );
		// $start = 1458297600;
		$res = $this->db->model('sale_log')
			->getAll("SELECT aa.finish_rate,bb.customer_manager,bb.name,bb.team_id FROM (
			SELECT ROUND((SUM((`sale`.unit_price - pur.unit_price)*`sale`.number)/re.profit)*100,2) AS finish_rate,o.`customer_manager`
			FROM `p2p_sale_log` `sale`
			LEFT JOIN `p2p_purchase_log` `pur` ON `sale`.purchase_id = pur.id
			LEFT JOIN `p2p_order` `o` ON `o`.o_id = `sale`.o_id
			LEFT JOIN `p2p_report_user` AS re ON re.`admin_id` = `sale`.`customer_manager` AND re.`report_date` = ".$start."
			WHERE o.collection_status = 3 AND o.payd_time > ".$start." AND purchase_id <> 0 
			GROUP BY o.`customer_manager`) AS aa
		RIGHT JOIN (
			SELECT admin.`admin_id` AS customer_manager,admin.`name`,role.`id` as team_id FROM p2p_admin AS admin
			LEFT JOIN p2p_adm_role_user AS role_user ON role_user.`user_id` = admin.`admin_id`
			LEFT JOIN p2p_adm_role AS role ON role.`id` = role_user.`role_id`
			WHERE role.pid = 22 AND admin.`status` =1
		) AS bb ON aa.customer_manager = bb.customer_manager");
		return $res;
	}

}