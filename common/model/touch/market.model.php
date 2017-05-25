<?php
/**
*market 模型
*/
class marketModel extends model
{
	public function __construct() {
 		parent::__construct(C('db_default'), 'market');
 	}
 	//返回数据
 	public function get_quotation_index(){
 		$quotation_cate = array_slice(L('product_type'),0,6);
 		foreach ($quotation_cate as $key => $value) {
 			$quo = $this->get_quotation($key);
 			if(!empty($quo)) $quotation[] = $quo;
 		}
 		return $quotation;
 	}
 	//分别获取每个类型的一条数据
 	public function get_quotation($cid)
 	{
 		$result = array();
 		$data = $this->where("cid=$cid")->select('id,price,rof,cid,input_time')->order('input_time desc')->limit('0,1')->getRow();
 		if(!empty($data)){
 			$result['id'] = $data['id'];
 			$result['price'] = $data['price'];
 			$result['rof'] = $data['rof'];
 			$result['cid'] = L('product_type')[$cid];
 		}
 		return $result;
 	}
}