<?php
//订单模型
class purchaseLogModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'purchase_log');
	}

	/**
	 * 通过明细ID 或许指定值
	 */
	public function getColByDetId($id=0,$col='p_id'){
		$result=$this->select("$col")->where("id='$id'")->getRow();
		return empty($result) ? false : $result;
	}

	public function getLogListByOid($o_id,$page,$size){
		$list=$this->from('purchase_log pl')
			->join('product pro','pl.p_id=pro.id')
			->join('factory fa','pro.f_id=fa.fid')
			->select('pl.*,pro.model,pro.product_type as type,fa.f_name')
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
		$list=$this->from('purchase_log pl')
			->join('product pro','pl.p_id=pro.id')
			->join('factory fa','pro.f_id=fa.fid')
			->select('pl.*,pro.model,pro.product_type as type,fa.f_name')
			->where("o_id=$o_id and pl.b_number!=0")
			->page($page,$size)
			->getPage();
		return $list;
	}

}