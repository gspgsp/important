<?php
/**
 * 每天下班前统计所有业务员当天业绩情况
 */
require_once 'config.php';

$cron = new cronReport;
$cron->start();

/**
 * 每分钟需处理计划任务主类
 */
class cronReport{
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
		$this->this_month_start=strtotime( date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m"),1,date("Y"))) );
	}

	/**
	 * 启动需要处理的任务项目
	 * @access public 
	 */
	public function start(){
		$this->get_report_user_data(); //获取统计数据
	}
	/**
	 * 整合所有数据，更新数据库 report_user
	 * @Author   yezhongbao
	 * @DateTime 2016-08-10T14:57:20+0800
	 */
	public function get_report_user_data()
	{
		
		$res = $this->db->model('report_user')->where('report_date = '.$this->this_month_start)->getAll();
		if(!$res){
			echo "this month is ".date('F').", quota is not set for this month";die();
		}
		$pur_and_sale_data = $this->get_pur_and_sale_data();//业务员完成销售采购总吨数和总金额统计
		$old_and_new_user_data = $this->get_old_and_new_user();//业务员开发新老用户统计
		$profit_data = $this->get_profit();//业务员销售利润统计
		//合并销售采购总数统计 + 新老用户统计
		foreach ($pur_and_sale_data as $key => $value) {
			foreach ($old_and_new_user_data as $k => $v) {
				if($value['customer_manager'] == $v['customer_manager']){
					$temp[] = array_merge($value,$v);
					unset($pur_and_sale_data[$key]);
					unset($old_and_new_user_data[$k]);
				}
			}
		}
		$pur_sale_user_data = array_merge($pur_and_sale_data,$old_and_new_user_data,$temp);
		//合并（销售采购总数统计和新老用户统计）+ 利润统计
		foreach ($pur_sale_user_data as $key => $value) {
			foreach ($profit_data as $k => $v) {
				if($value['customer_manager'] == $v['customer_manager']){
					$temp1[] = array_merge($value,$v);
					unset($pur_sale_user_data[$key]);
					unset($profit_data[$k]);
				}
			}
		}
		$pur_sale_user_profit_data = array_merge($pur_sale_user_data,$profit_data,$temp1);
		foreach ($pur_sale_user_profit_data as $key => $value) {
			if(empty($value['pur_num'])){
				$pur_sale_user_profit_data[$key]['pur_num'] = 0;
			}
			if(empty($value['pur_price'])){
				$pur_sale_user_profit_data[$key]['pur_price'] = 0;
			}
			if(empty($value['sale_num'])){
				$pur_sale_user_profit_data[$key]['sale_num'] = 0;
			}
			if(empty($value['sale_price'])){
				$pur_sale_user_profit_data[$key]['sale_price'] = 0;
			}
			if(empty($value['old'])){
				$pur_sale_user_profit_data[$key]['old'] = 0;
			}
			if(empty($value['new'])){
				$pur_sale_user_profit_data[$key]['new'] = 0;
			}
			if(empty($value['profit'])){
				$pur_sale_user_profit_data[$key]['profit'] = 0;
			}
		}
		// p($pur_sale_user_profit_data);die();
		foreach ($pur_sale_user_profit_data as $key => $value) {
			$data['saled'] = $value['sale_price'];
			$data['saled_num'] = $value['sale_num'];
			$data['buyd'] = $value['pur_price'];
			$data['buyd_num'] = $value['pur_num'];
			$data['old_userd'] = $value['old'];
			$data['new_userd'] = $value['new'];
			$data['profitd'] = $value['profit'];
			$data['update_time'] = time();
			$this->db->model('report_user')
					 ->where('admin_id = '.$value['customer_manager'].' and report_date = '.$this->this_month_start)
					 ->update($data);
		}
	}
	/**
	 * 获取销售总吨数和总金额数
	 * @Author   yezhongbao
	 * @DateTime 2016-08-09T14:31:49+0800
	 * @return   [array][销售总吨数和总金额统计]
	 */
	public function get_sale_data()
	{
		$where =' 1 ';
		$where .= 'and order_type = 1 and order_status = 2 and transport_status = 2 and input_time > '.$this->this_month_start;
		$select = 'sum(total_num) as sale_num,sum(total_price) as sale_price,customer_manager';
		$sale_data = $this->db->model('order')
						 ->select($select)
						 ->where($where)
						 ->group('customer_manager')
						 ->getAll();
				 // return $this->db->getLastSql();		 
		return $sale_data;
	}
	/**
	 * 获取采购总吨数和总金额数
	 * @Author   yezhongbao
	 * @DateTime 2016-08-09T14:32:55+0800
	 * @return   [array][采购总吨数和总金额统计]
	 */
	public function get_purchase_data()
	{
		$where =' 1 ';
		$where .= 'and order_type = 2 and order_status = 2 and transport_status = 2 and input_time > '.$this->this_month_start;
		$select = 'sum(total_num) as pur_num,sum(total_price) as pur_price,customer_manager';
		$pur_data = $this->db->model('order')
						 ->select($select)
						 ->where($where)
						 ->group('customer_manager')
						 ->getAll();
						  // return $this->db->getLastSql();
		return $pur_data;
	}
	/**
	 * 处理销售和采购吨数，总额
	 * @Author   yezhongbao
	 * @DateTime 2016-08-10T11:03:31+0800
	 * @return   [array][销售+采购总吨数和总金额统计]
	 */
	public function get_pur_and_sale_data(){
		$sale_data = $this->get_sale_data();
		$pur_data = $this->get_purchase_data();
		foreach ($pur_data as $key => $value) {
			foreach ($sale_data as $k => $v) {
				if($value['customer_manager'] == $v['customer_manager']){
					$temp[] = array_merge($v,$value);
					unset($pur_data[$key]);
					unset($sale_data[$k]);
				}
			}
		}
		return $new = array_merge($pur_data,$sale_data,$temp);
	}
	/**处理新老客户 
	 *第一步：将order表中的c_id和customer_manager拿出来，
	 *第二步：用逗号将cid拼接成字符串
	 *第三步：根据 cid拼接的字符串，从customer中将满足条件的cid，和customer_manager拿出来
	 *第四步：遍历数据，判断2个表中的cid和customer_manager同时都成立，再判断创建时间，大于当月的就是新开发用户，否则老用户
	 * @Author   yezhongbao
	 * @DateTime 2016-08-10T14:53:48+0800
	 * @return   [array][新老客户统计数组]
	 */
	public function get_old_and_new_user(){
		$where = ' 1 ';
		$where .= 'and o.order_status = 2 and o.transport_status = 2 and o.input_time > '.$this->this_month_start;
		$select ='o.c_id,o.customer_manager as man';
		$old_data = $this->db->model('order as o')
			 			 ->select($select)
			 			 ->where($where)
				 		 ->getAll();
				 		 // return $this->db->getLastSql();
		foreach ($old_data as $key => $value) {
			$temp_cid_arr[] = $value['c_id'];
		}
		$unique_cid_arr = array_unique($temp_cid_arr);
		$old_data_str = implode(',', $unique_cid_arr);
		$customer = $this->db->model('customer as cus')
			 			 ->select('cus.c_id,cus.customer_manager as admin,cus.input_time as time')
			 			 ->where('cus.c_id in ('.$old_data_str.')')
				 		 ->getAll();
				 		 // return $this->db->getLastSql();
		$old_data = array_values($this->array_unique_fb($old_data));
		foreach ($customer as $key => $value) {
			foreach ($old_data as $k => $v) {
				if($value['c_id'] == $v[0] && $value['admin'] == $v[1]){
					//创建时间大于当月，新开发客户+1
					if($value['time'] >= $this->this_month_start){
						$new[$value['admin']]['customer_manager'] = $value['admin'];
						$new[$value['admin']]['new'] += 1;
					}else{
					//创建时间小于当月,老客户+1
						$new[$value['admin']]['customer_manager'] = $value['admin'];
						$new[$value['admin']]['old'] += 1;
					}	
				}
			}
		}
		return $old_and_new_user_data = array_values($new);
	}
	/**获取利润 1：先销后采 2：先采后销
	 * @Author   yezhongbao
	 * @DateTime 2016-08-09T14:33:30+0800
	 * @return   [array][利润统计数组]
	 */
	public function get_profit(){
		//先销后采 sale_pur_data  
		$sale_pur_data = $this->db->getAll('SELECT sale.`customer_manager`,SUM((sale.`s_price` - pur.`p_price`) )AS profit
							FROM (
							SELECT o.`o_id`, o.`join_id`, o.`customer_manager`,SUM(s.`number` * s.`unit_price`) AS s_price
							FROM p2p_order AS o
							JOIN p2p_sale_log AS s ON o.`o_id` = s.`o_id`
							WHERE  1 AND o.sales_type = 2 AND o.order_type = 1 AND o.order_status = 2 AND o.transport_status = 2 AND o.is_join_check = 1  AND o.input_time > '.$this->this_month_start.'
							GROUP BY o.o_id
							) AS sale
							JOIN (
							SELECT o.`o_id`, o.`join_id`, o.`customer_manager`,SUM(p.`number` * p.`unit_price`) AS p_price
							FROM p2p_order AS o
							JOIN p2p_purchase_log AS p ON o.`join_id` = p.`o_id`
							WHERE  1 AND o.sales_type = 2 AND o.order_type = 1 AND o.order_status = 2 AND o.transport_status = 2 AND o.is_join_check = 1  AND o.input_time > '.$this->this_month_start.'
							GROUP BY o.o_id
							) AS pur
							ON (sale.`o_id` = pur.`o_id`)
							GROUP BY sale.`customer_manager`'
						);
		
		// 先采后销 pur_sale_data
		$where = ' 1 ';
		$where .= 'and o.sales_type = 1 and o.order_type = 1 and o.order_status = 2 and o.transport_status = 2 and o.input_time > '.$this->this_month_start;
		$pur_sale_data = $this->db->model('order as o')
								 ->select('o.customer_manager, SUM((s.unit_price - s.purchase_price)*s.number) AS profit')
								 ->leftjoin('sale_log as s','o.o_id=s.o_id')
								 ->where($where)
								 ->group('customer_manager')
								 ->getAll();
				 		 // return $this->db->getLastSql();
		//合并2种方式的利润
		foreach ($pur_sale_data as $key => $value) {
			foreach ($sale_pur_data as $k => $v) {
				if($value['customer_manager'] == $v['customer_manager']){
					$value['profit'] += $v['profit'];
					$temp[] = $value;
					unset($pur_sale_data[$key]);
					unset($sale_pur_data[$k]);
				}
			}
		}
		return $new = array_merge($pur_sale_data,$sale_pur_data,$temp);
	}
	//二维数组去重
	public function array_unique_fb($array2D) 
	{ 
		foreach ($array2D as $v) 
		{ 
			$v = join(",",$v); //降维,也可以用implode,将一维数组转换为用逗号连接的字符串 
			$temp[] = $v; 
		} 
		$temp = array_unique($temp); //去掉重复的字符串,也就是重复的一维数组 
		foreach ($temp as $k => $v) 
		{ 
			$temp[$k] = explode(",",$v); //再将拆开的数组重新组装 
		} 
		return $temp; 
	} 
}