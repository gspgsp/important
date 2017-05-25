<?php
//订单模型
class saleLogModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'sale_log');
	}

	/**
	 * 通过明细ID 或许指定值
	 */
	public function getColByDetId($id=0,$col='p_id'){
		$result=$this->select("$col")->where("id='$id'")->getOne();
		return empty($result) ? false : $result;
	}

	public function getLogListByOid($o_id,$page,$size){
		$list=$this->from('sale_log pl')
			->leftjoin('product pro','pl.p_id=pro.id')
			->leftjoin('factory fa','pro.f_id=fa.fid')
			->leftjoin('store st','pl.store_id=st.id')
			->select('pl.*,pro.model,pro.product_type as type,fa.f_name,st.store_name')
			->where("o_id=$o_id")
			->page($page,$size)
			->getPage();
		return $list;
	}

	/**
	 * 在上述方法上，过滤掉已经开票，b_number数量为0的情况
	 * @Author   xianghui
	 * @DateTime 2017-01-17T17:44:19+0800
	 * @return   [type]                   [description]
	 */
	public function getLogListByOid2($o_id,$page,$size){
		$list=$this->from('sale_log pl')
			->leftjoin('product pro','pl.p_id=pro.id')
			->leftjoin('factory fa','pro.f_id=fa.fid')
			->leftjoin('store st','pl.store_id=st.id')
			->select('pl.*,pro.model,pro.product_type as type,fa.f_name,st.store_name')
			->where("o_id=$o_id and pl.b_number !=0")
			->page($page,$size)
			->getPage();
		return $list;
	}

	/**
	 * 根据交易员id获取当月的毛利
	 * @Author   yezhongbao
	 * @DateTime 2016-10-09T11:03:35+0800
	 * @param    [int]  $customer_manager [交易员id]
	 * @return   [type] [结果集]
	 */
	public function getMonthProfitByCustomerManager($customer_manager){
		$start=strtotime( date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m"),1,date("Y"))) );
		$end  =strtotime( date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("t"),date("Y"))) );
		$res = $this->from('sale_log as sale')
					->select('SUM((sale.unit_price - pur.unit_price)*sale.number) AS profit')
					->where('o.order_status = 2 and o.`transport_status` = 2 and sale.input_time > '.$start.' and sale.input_time< '.$end.' and sale.customer_manager = '.$customer_manager.' and purchase_id <> 0')
					->leftjoin('purchase_log pur','sale.purchase_id = pur.id')
					->leftjoin('order `o`','`o`.o_id = sale.o_id')
					->getRow();
					// showtrace();
		return empty($res) ? array() : $res;
	}
	/**
	 * 根据订单号查询订单是否已经入库
	 */
	public function checkInLog($o_id = 0){
		$sign = $this->model('out_log')->select("sign")->where("o_id = $o_id")->getOne();
		return intval($sign);
	}
	/**
	 * 根据交易员id获取当天的毛利
	 * @Author   yezhongbao
	 * @DateTime 2017-01-09T15:06:55+0800
	 * @param    integer                  $customer_manager [description]
	 * @return   [type]                                     [description]
	 */
	public function getTodayProfitByCustomerManager($customer_manager=0){
		$start = strtotime(date('Y-m-d',time()));
		$end  =time();
		$res = $this->from('sale_log as sale')
					->select('o.`o_id`,SUM((sale.unit_price - pur.unit_price)*sale.number) AS profit')
					->where('o.`collection_status`=3 and o.payd_time > '.$start.' and o.payd_time< '.$end.' and sale.customer_manager = '.$customer_manager.' and sale.purchase_id <> 0')
					->leftjoin('purchase_log pur','sale.purchase_id = pur.id')
					->leftjoin('order `o`','`o`.o_id = sale.o_id')
					->group('o_id')
					->getAll();
					// showtrace();
		if($res){
			foreach ($res as $key => $value) {
				$data['o_id'] .= $value['o_id'].',';
				$data['profit'] += $value['profit'];
			}
			$data['o_id'] = "(".trim($data['o_id'],',').")";
		}else{
			$data['o_id'] = "('')";
			$data['profit'] = 0;
		}
		return $data;
	}
	/**
	 * 获取业务员当月利润完成率
	 * @Author   yezhongbao
	 * @DateTime 2017-01-09T15:14:19+0800
	 * @param    integer                  $customer_manager [description]
	 * @return   [type]                                     [description]
	 */
	public function getMonthProfitRateByCustomerManager($customer_manager = 0 ){
		$start=strtotime( date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m"),1,date("Y"))) );
		$res = $this->model('sale_log')
			->getOne('SELECT ROUND((SUM((sale.unit_price - pur.unit_price)*sale.number)/re.profit)*100,2) AS profit
				FROM `p2p_sale_log` `sale`
				LEFT JOIN `p2p_purchase_log` `pur` ON sale.purchase_id = pur.id
				LEFT JOIN `p2p_order` `o` ON `o`.o_id = sale.o_id
				LEFT JOIN `p2p_report_user` AS re ON re.`admin_id` = sale.`customer_manager` AND re.`report_date` = '.$start.'
				WHERE o.collection_status = 3 AND o.payd_time > '.$start.' AND sale.customer_manager = '.$customer_manager.' AND purchase_id <> 0');
		return empty($res) ? 0 : $res;
	}
	public function getLastSalePriceByPid($p_id = 0){
		$res = $this->model('sale_log as `s`')
			->select('`s`.unit_price')
			->leftjoin('order as `o`','`o`.o_id = `s`.o_id')
			->where('`o`.order_status = 2 and `o`.transport_status = 2 and `s`.p_id = '.$p_id)
			->order('`s`.o_id desc')
			->getOne();
		return empty($res) ? 0 : $res;
	}
}