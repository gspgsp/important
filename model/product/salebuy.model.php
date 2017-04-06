<?php 
//订单模型
class salebuyModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'sale_buy');
	}
	/**
	 * 根据订单id获取报价订单的相关信息
	 */
	public function getSalebuyInfo($id = 0){
		if($id>0){
			return $this->select('sb.*,p.id as purchaseid, p.c_id as pc_id, p.p_id as pp_id, p.number as pnumber, p.unit_price as punit_price, p.origin as porigin, p.store_house as pstore_house, p.cargo_type as pcargo_type,p.period as pperiod, p.customer_manager as ptraderid, p.status as pstatus, p.chk_time as pchk_time, p.remark as premark, p.bargain as pbargain, p.shelve_type as pshelve_type, p.input_time as pinput_time')->from('sale_buy sb')->leftjoin('purchase p','p.id = sb.p_id')->where("sb.id = $id")->getRow();
		}
		return array();
	}
	/**
	 * 根据指定字段取得对应的值
	 * @Author   cuiyinming
	 * @DateTime 2016-05-20T14:54:36+0800
	 * @return   [type]                   [description]
	*/
	public function getColById($pid=0,  $col = 'number', $value = 'id'){
		$result=$this->select("$col")->where("$value =".$pid)->getOne();
		return empty($result) ? '' : $result;
	}
	/**
	 * 根据报价id获取已经销售出去数目
	 */
	public function getCount($p_id=0, $status=2){
		$result = $this->select("sum(number) as total")->where("`status` = $status and `p_id` = $p_id")->getOne();
		return $result>0 ? $result : 0;
	}
	/**
	 * 获取报价的单条记录
	 */
	public function getSalebuyInfoById($id=0,$col = 'id'){
		return $this->where("$col = $id")->getRow();
	}

	/**
	 * 查询我的供货信息
	 *
     */
	public function getPurPage($where,$page=1,$pageSize=10){
	     return 	$this->from('purchase as pur')
						 ->join('sale_buy as sb ','pur.user_id=sb.user_id')
						 ->join('customer as cus','sb.c_id=cus.c_id')
						 ->join('lib_region as r','sb.delivery_place=r.id')
			             ->join('union_order as un','un.sale_user_id=sb.user_id')
						 ->join('product as pro','pur.p_id=pro.id')
		                 ->join('factory as fac','fac.fid=pro.f_id')
						 ->where($where.' and pur.last_buy_sale=sb.id')
						 ->select('pur.id, `pur`.`p_id`,pur.last_buy_sale,sb.c_id,sb.user_id,`sb`.`number`, `un`.`deal_price`,`un`.`total_price`, `un`.`delivery_time`, `un`.`order_status`, `sb`.`ship_type`, `sb`.`remark`,`sb`.`id` AS `sb_id`,`cus`.`c_name`,`r`.`name` AS `delivery_place`,`pro`.`process_type`,`pro`.`product_type`, `pro`.`model`, `fac`.`f_name`,sb.remark')
				         ->page($page,$pageSize)
						 ->getPage();
	}
	public function get_purs($where,$page=1,$pageSize=10){
		return $this->from('sale_buy as sb')
					->join('purchase as pur','sb.id=pur.last_buy_sale')
					->join('customer as cus','pur.c_id=cus.`c_id`')
					->join('lib_region as r','sb.delivery_place=r.`id`')
					->join('union_order as un','un.sale_user_id=sb.user_id')
					->join('product as pro','pur.p_id=pro.id')
					->join('factory as fac','fac.fid=pro.f_id')
					->where($where)
			        ->select(' pur.id, `pur`.`p_id`,pur.last_buy_sale,sb.c_id,sb.user_id,`sb`.`number`,`un`.`deal_price`,`un`.`total_price`, `un`.`delivery_time`, `un`.`order_status`,`sb`.`ship_type`, `sb`.`remark`,`sb`.`id` AS `sb_id`,`cus`.`c_name`,`r`.`name` AS `delivery_place`,`pro`.`process_type`,`pro`.`product_type`, `pro`.`model`, `fac`.`f_name`,sb.remark')
					->page($page,$pageSize)
					->getPage();
//		           ->getAll();
	}

}






























