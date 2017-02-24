<?php
/**
 * 原油指数
 */
class oilpriceModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'oil_price');
	}
	public function get_oil_price(){
		$data = array();
		$data[0] = $this->get_index(1);//1
		$data[1] = $this->get_index(2);//2
		return $data;
	}
	public function get_index($type)
	{
		$result = array();
		$where = "type=$type";
		$data = $this->getOilData($where);
		$alph = $data[0]['price'] - $data[1]['price'];
		if($alph>0){
			$data[0]['float'] = 1;
		}elseif ($alph==0) {
			$data[0]['float'] = 0;
		}elseif ($alph<0) {
			$data[0]['float'] = -1;
		}
		$data[0]['type'] = L('oil_type')[$data[0]['type']];
		return $data[0];
	}
	public function getOilData($where){
		return $this->where($where)->order('input_time desc')->limit('0,2')->getAll();
	}

}