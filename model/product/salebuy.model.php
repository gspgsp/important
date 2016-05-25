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
}