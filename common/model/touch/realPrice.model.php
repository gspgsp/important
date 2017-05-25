<?php
/**
*实时成交价格
*/
class realPriceModel extends model
{
	public function __construct() {
		parent::__construct(C('db_default'), 'sale_log');
	}
	public function getRealPrice($page,$size,$today,$sortField='input_time',$sortOrder='desc'){
		$where = " sl.order_status=2 and sl.input_time>$today ";//
		$data = $this->select('sl.id,sl.p_id,sl.unit_price,sl.input_time,pro.product_type,pro.model,fa.f_name')->from('sale_log sl')
		->join('product pro','sl.p_id=pro.id')
        ->join('factory fa','pro.f_id=fa.fid')
		->where($where)
        ->page($page,$size)
        ->order("$sortField $sortOrder")
        ->getPage();
		foreach ($data['data'] as &$value) {
			$value['product_type'] = L('product_type')[$value['product_type']];
			$value['input_time'] =date("H:i",$value['input_time']);
			$value['cur_time'] = date("Y-m-d",time());
		}
		return $data;
	}
}