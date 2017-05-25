<?php
/**
*求购模型
*/
class purchaseModel extends model
{
	public function __construct() {
		parent::__construct(C('db_default'), 'purchase');
	}
	//1采购 2报价
	public function getPurchase($user_id,$type){
		$pdata = array();
		$result = array();

		$purchase = $this->model('purchase')->select('id,unit_price,number,store_house,input_time,p_id,user_id,shelve_type')->where("user_id=$user_id and type=$type")->getAll();
		foreach ($purchase as $value1) {
			$product = $this->model('product')->select('model,f_id,product_type')->where('id='.$value1['p_id'])->getRow();
			$f_name = $this->model('factory')->select('f_name')->where('fid='.$product['f_id'])->getRow();
			$pdata['id'] =$value1['id'];
			$pdata['p_id'] =$value1['p_id'];
			$pdata['model'] =$product['model'];
			$pdata['product_type'] = L('product_type')[$product['product_type']];//产品类型
			$pdata['unit_price'] =$value1['unit_price'];
			$pdata['f_name'] =$f_name['f_name'];
			$pdata['number'] =$value1['number'];
			$pdata['store_house'] =$value1['store_house'];
			$pdata['input_time'] =$value1['input_time'];
			$pdata['shelve_type'] =$value1['shelve_type'];
			$result[] = $pdata;
			unset($pdata);
		}
		//二维数组
		return $result;
	}
}