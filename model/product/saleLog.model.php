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
			->join('product pro','pl.p_id=pro.id')
			->join('factory fa','pro.f_id=fa.fid')
			->join('store st','pl.store_id=st.id')
			->select('pl.*,pro.model,pro.product_type as type,fa.f_name,st.store_name')
			->where("o_id=$o_id")
			->page($page,$size)
			->getPage();
		return $list;
	}

}