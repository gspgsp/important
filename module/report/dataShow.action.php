<?php 
/**
 * 数据汇总
 */
class dataShowAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->db=M('public:common');
		$this->doact = sget('do','s');
		$this->assign('team',L('team')); //战队名称
	}
	/**
	 * @access public
	 * @return html
	 */
	public function init(){
		$dailyDataSummary = $this->dailyDataSummary();//每日数据统计
		$getInvoiceData = $this->getInvoiceData();//获取收款/付款，进项/销项未开数据
		$notice = $this->notice();//公告列表前5条
		$cooperationCustomerRemind = $this->cooperationCustomerRemind();//合作客户强开提醒
		$UnCooperationCustomerRemind = $this->UnCooperationCustomerRemind();//未合作客户强开提醒
		$todayFollowCustomersNums = $this->getDismissRuleDayFollowCustomersByAdminId();//强开规则中规定的业务员当日要跟踪的客户数
		// p(($UnCooperationCustomerRemind));die;
		// p($getCooperationCustomer);die;
		$this->assign('notice',$notice);
		//私海客户 数组和 个数
		$this->assign('UnCooperationCustomerRemind',$UnCooperationCustomerRemind);
		//合作客户 数组和个数
		$this->assign('cooperationCustomerRemind',$cooperationCustomerRemind);
		$this->assign('cooperationCustomerRemindTotal',count($cooperationCustomerRemind));
		//每日数据统计
		$this->assign('dailyDataSummary',$dailyDataSummary);
		//收付款
		$this->assign('getInvoiceData',$getInvoiceData);

		$this->assign('todayFollowCustomersNums',$todayFollowCustomersNums);

		$this->display('dataShow.list.html');
	}
	/**
	 * 每日数据统计		
	 * 今日新增客户数：今日销售吨数：今日跟进客户数：今日采购吨数：今日新客户成交数：今日利润：今日电话数：目标完成率：
	 * @Author   yezhongbao
	 * @DateTime 2017-01-09T11:41:30+0800
	 * @return   [type]                   [description]
	 */
	public function dailyDataSummary(){
		$new_clients_num = M('user:customer')->getTodayNewClientsByCustomerManager($_SESSION['adminid']);//今日新增客户数
		$new_clients_order_num = M('user:customer')->getTodayNewClientsOrderNums($_SESSION['adminid']);//今日客户成交数
		$today_follow_customer_num = M('user:customer')->getTodayFollowCustomers($_SESSION['adminid']);//每日跟进客户数
		$today_sale_buy_num = M('product:order')->getTodayNumByCustomerManager($_SESSION['adminid']);//今日销售吨数 和采购吨数
		$today_profit = M('product:saleLog')->getTodayProfitByCustomerManager($_SESSION['adminid']);//业务员每日利润
		$month_profit_rate = M('product:saleLog')->getMonthProfitRateByCustomerManager($_SESSION['adminid']);//业务员当月利润完成率
		$today_phone_num = $this->getTodayPhoneNum($_SESSION['adminid']);//业务员今日电话数

		$return_arr = array(
			'new_clients_num'=>$new_clients_num,
			'new_clients_order_num'=>$new_clients_order_num,
			'getTodayFollowCustomers'=>$today_follow_customer_num,
			'today_sale_num'=>$today_sale_buy_num['sale_num'],
			'today_buy_num'=>$today_sale_buy_num['buy_num'],
			'today_profit'=>$today_profit,
			'month_profit_rate'=>$month_profit_rate.'%',
			'today_phone_num'=>$today_phone_num,
			);
		return $return_arr;
	}
	/**
	 * 获取业务员当日电话数
	 * @Author   yezhongbao
	 * @DateTime 2017-01-09T15:43:25+0800
	 * @param    integer                  $customer_manager [description]
	 * @return   [type]                                     [description]
	 */
	public function getTodayPhoneNum($customer_manager=0){
		$today_begin = strtotime(date('Y-m-d',time()));

		$res = $this->db->model('api')
			->getRow('SELECT api.*
				FROM
				  (SELECT COUNT(id) AS num,phone FROM `p2p_api` WHERE ctime > '.$today_begin.' AND TIME > 0 AND callstatus="ou" GROUP BY phone)
				AS api 
				LEFT JOIN `p2p_admin` AS admin ON admin.seat_phone = api.`phone`
				WHERE admin.name IS NOT NULL AND admin.admin_id='.$customer_manager);
			return empty($res['num'])?0:$res['num'];
	}
	/**
	 * 获取收款/付款，进项/销项数据
	 * @Author   yezhongbao
	 * @DateTime 2017-01-09T15:45:28+0800
	 * @return   [type]                   [description]
	 */
	public function getInvoiceData(){
		//未收欠款  销售订单款未到
		$where1 = 'order_status = 2 AND transport_status = 2 AND order_type = 1 AND customer_manager = '.$_SESSION['adminid'].' AND collection_status IN (1,2)';
		$res1 = $this->db->model('order')->where($where1)->getCol();

		if($res1){
			$sale_collection['num']=count($res1);
			$sale_collection['o_id']='('.implode(',', $res1).')';
		}else{
			$sale_collection['num']=0;
			$sale_collection['o_id']="('')";
		}
		//未付欠款 采购未付款
		$where2 = 'order_status = 2 AND transport_status = 2 AND order_type = 2 AND customer_manager = '.$_SESSION['adminid'].' AND collection_status IN (1,2)';
		$res2 = $this->db->model('order')->where($where2)->getCol();
		if($res2){
			$pur_collection['num']=count($res2);
			$pur_collection['o_id']='('.implode(',', $res2).')';
		}else{
			$pur_collection['num']=0;
			$pur_collection['o_id']="('')";
		}
		//未开销项  销售订单发票未开
		$where3 = 'order_status = 2 AND transport_status = 2 AND order_type = 1 AND customer_manager = '.$_SESSION['adminid'].' AND invoice_status IN (1,2)';
		$res3 = $this->db->model('order')->where($where3)->getCol();
		if($res3){
			$sale_unBilling['num']=count($res3);
			$sale_unBilling['o_id']='('.implode(',', $res3).')';
		}else{
			$sale_unBilling['num']=0;
			$sale_unBilling['o_id']="('')";
		}
		//未开进项 采购订单发票未到
		$where4 = 'order_status = 2 AND transport_status = 2 AND order_type = 2 AND customer_manager = '.$_SESSION['adminid'].' AND invoice_status IN (1,2)';
		$res4 = $this->db->model('order')->where($where4)->getCol();
		if($res4){
			$pur_unBilling['num']=count($res4);
			$pur_unBilling['o_id']='('.implode(',', $res4).')';
		}else{
			$pur_unBilling['num']=0;
			$pur_unBilling['o_id']="('')";
		}
		return $arr = array(
			'sale_unBilling'=>$sale_unBilling,
			'pur_unBilling'=>$pur_unBilling,
			'sale_collection'=>$sale_collection,
			'pur_collection'=>$pur_collection,
			);
	}
	/**
	 * 获取当前业务员的私海客户
	 * @Author   yezhongbao
	 * @DateTime 2017-01-10T10:23:10+0800
	 * @return   [type]                   [description]
	 */
	public function getSelfCustomer(){
		$res = $this->db->model('customer')
				->select('c_id')
				->where('is_sale = 0 and is_pur = 0 and customer_manager = '.$_SESSION['adminid'])
				->getCol();
		return empty($res)?array():$res;
	}
	/**
	 * 未合作客户强开提醒
	 * @Author   yezhongbao
	 * @DateTime 2017-01-18T10:23:10+0800
	 * @return   [type]                   [description]
	 */
	public function UnCooperationCustomerRemind(){
		$res1 = $this->db->model('customer')
				->getAll('SELECT * FROM (
		SELECT cus.c_id,cus.c_name,rule.`private_uncooperation_days`,rule.`private_uncooperation_remind`,cus.`last_no_sale`,cus.`last_follow`  FROM p2p_customer AS cus
		LEFT JOIN p2p_dismiss_rule_admin AS admin ON admin.`admin_id` = cus.`customer_manager`
		LEFT JOIN p2p_dismiss_rule AS rule ON rule.`id` = admin.`rule_id`
		WHERE cus.`is_pur` <> 1 AND cus.`is_sale` <> 1 AND cus.last_no_sale > 1 AND cus.last_follow > 1 AND cus.customer_manager = '.$_SESSION['adminid'].')
	AS a
		WHERE UNIX_TIMESTAMP() - last_no_sale > (private_uncooperation_days-private_uncooperation_remind)*86400 AND UNIX_TIMESTAMP() - last_no_sale < private_uncooperation_days*86400 ORDER BY last_no_sale ASC');
			if($res1){
			foreach ($res1 as $key => &$value) {
				//距离强开天数算法
				//向上取整 ceil（（最后合作的日期+规则中合作客户强开天数*86400 - 当前时间）/86400）
				$value['remind_day'] = ceil(($value['last_no_sale'] + $value['private_uncooperation_days']*86400 - time())/86400);
				$value['type'] = $value['private_uncooperation_days'].'天未合作';
			}
		}
	$res2 = $this->db->model('customer')
			->getAll('SELECT * FROM (
				SELECT cus.c_id,cus.c_name,rule.`private_unfollow_days`,rule.`private_unfollow_remind`,cus.last_follow,cus.`last_no_sale` FROM p2p_customer AS cus
				LEFT JOIN p2p_dismiss_rule_admin AS admin ON admin.`admin_id` = cus.`customer_manager`
				LEFT JOIN p2p_dismiss_rule AS rule ON rule.`id` = admin.`rule_id`
				WHERE cus.`is_pur` <> 1 AND cus.`is_sale` <> 1 AND cus.last_no_sale > 1 AND cus.last_follow > 1 AND cus.customer_manager = '.$_SESSION['adminid'].')
				AS a
				WHERE UNIX_TIMESTAMP() - last_follow > (private_unfollow_days-private_unfollow_remind)*86400 AND UNIX_TIMESTAMP() - last_follow < private_unfollow_days*86400 ORDER BY last_follow ASC');
		if($res2){
			foreach ($res2 as $key => &$value) {
				//距离强开天数算法
				//向上取整 ceil（（最后合作的日期+规则中合作客户强开天数*86400 - 当前时间）/86400）
				$value['remind_day'] = ceil(($value['last_follow'] + $value['private_unfollow_days']*86400 - time())/86400);
				$value['type'] = $value['private_unfollow_days'].'天未跟进';
			}
		}

		$res = array_merge($res1,$res2);
		if($res){
			foreach ($res as $key => &$value) {
				if (strpos($value['type'],'跟进')) {
					$data .= $value['c_id'].',';
				}
			}
			$data = "(".trim($data,',').")";
		}else{
			$data = "('')";
		}
		$result=array('num'=>count($res),
					  'c_id'=>$data,
					  'data'=>$res);
		return $result;
	}
	/**
	 * 合作客户强开提前提醒：
	 * @Author   yezhongbao
	 * @DateTime 2017-01-10T10:43:15+0800
	 * @return   [type]                   [description]
	 */
	public function cooperationCustomerRemind(){
		$res = $this->db->model('customer')
				->getAll('SELECT * FROM (
				SELECT cus.c_id,cus.c_name,(rule.`cooperation_remind`) AS cooperation_remind,rule.`cooperation_days`,cus.last_sale FROM p2p_customer AS cus
				LEFT JOIN p2p_dismiss_rule_admin AS admin ON admin.`admin_id` = cus.`customer_manager`
				LEFT JOIN p2p_dismiss_rule AS rule ON rule.`id` = admin.`rule_id`
				WHERE (cus.`is_sale` = 1) AND cus.last_sale > 1 AND cus.customer_manager = '.$_SESSION['adminid'].')
			AS a
				WHERE UNIX_TIMESTAMP() - last_sale > (cooperation_days-cooperation_remind)*86400 AND UNIX_TIMESTAMP() - last_sale < cooperation_days*86400 ORDER BY last_sale ASC');
				// AND UNIX_TIMESTAMP() - last_sale < cooperation_days*86400
		if($res){
			foreach ($res as $key => &$value) {
				//距离强开天数算法
				//向上取整 ceil（（最后合作的日期+规则中合作客户强开天数*86400 - 当前时间）/86400）
				$value['remind_day'] = ceil(($value['last_sale'] + $value['cooperation_days']*86400 - time())/86400);
				$value['type'] = $value['cooperation_days'].'天未合作';
			}
		}
		return $res;

	}
	/**
	 * 系统公告
	 * @Author   yezhongbao
	 * @DateTime 2017-01-13T11:31:29+0800
	 * @return   [type]                   [description]
	 */
	public function notice(){
		$res = $this->db->model('notice')
				->limit('5')
				->order('input_time desc')
				->getAll();
		foreach ($res as $k => &$v) {
			$urlArr = explode('#', $v['accessory']);
			$v['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
		}
		return empty($res)?array():$res;
	}
	public function info(){
		$id = sget('id','i');
		$res = $this->db->model('notice')
					  ->where('id='.$id)
					  ->getRow();
		$res['input_time'] = date("Y-m-d H:i:s",$res['input_time']);

		$nameArr = explode('#', $res['accessory']);
		$file['name'] = array_filter($nameArr);
		$urlArr = explode('#', $res['path']);
		$file['url'] = array_filter($urlArr);
		$file_url = array_combine($file['name'],$file['url']);
		//过滤只要图片url
		foreach ($file_url as $key => $value) {
			if(!strrpos($key, 'jpg') && !strrpos($key, 'jpeg') && !strrpos($key, 'png') && !strrpos($key, 'bmp')){
				unset($file_url[$key]);
			}
		}
		$this->assign('file_url',$file_url);
		$this->assign('name',$name);
		$this->assign('info',$res);
		$this->display('notice.detail.html');
	}

	public function getDismissRuleDayFollowCustomersByAdminId(){
		$res = $this->db->model('dismiss_rule_admin as admin')
				->select('rule.day_follow_nums')
				->leftjoin('dismiss_rule as rule','admin.rule_id = rule.id')
				->where('admin.admin_id = '.$_SESSION['adminid'])
				->getOne();
				return empty($res)?0:$res;
	}
}