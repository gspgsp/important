<?php
/**
*求购模型
*/
class purchaseModel extends model
{
	public function __construct() {
		parent::__construct(C('db_default'), 'purchase');
	}
	public function getPurchase($user_id){
		$pdata = array();
		$result = array();

		$purchase = $this->model('purchase')->select('unit_price,number,store_house,input_time,	p_id,user_id')->where('user_id='.$user_id)->getAll();
		foreach ($purchase as $value1) {
			$product = $this->model('product')->select('model,f_id')->where('id='.$value1['p_id'])->getRow();
			$f_name = $this->model('factory')->select('f_name')->where('fid='.$product['f_id'])->getRow();
			$pdata['model'] =$product['model'];
			$pdata['unit_price'] =$value1['unit_price'];
			$pdata['f_name'] =$f_name['f_name'];
			$pdata['number'] =$value1['number'];
			$pdata['store_house'] =$value1['store_house'];
			$pdata['input_time'] =$value1['input_time'];
			$result[] = $pdata;
			unset($pdata);
		}
		//二维数组
		return $result;
	}
}