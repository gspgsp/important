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

		$_key='credit_detail'.$user_id;
		$cache=cache::startMemcache();
		//$cache->delete($_key);
		$data=$cache->get($_key);
		if(empty($data)){
			$purchase = $this->model('purchase')->select('unit_price,number,store_house,input_time,	p_id,user_id')->where('user_id='.$user_id)->getAll();
			$cache->set($_key,$purchase,86400);//加入缓存
		}
		$data=$cache->get($_key);
		//$purchase = $this->model('purchase')->where('c_id='.$c_id)->getAll();
		foreach ($data as $value1) {
			$product = $this->model('product')->select('model,f_id')->where('id='.$value1['p_id'])->getRow();
			$f_name = $this->model('factory')->select('f_name')->where('fid='.$product['f_id'])->getRow();
			$pdata[] =$product['model'];
			$pdata[] =$value1['unit_price'];
			$pdata[] =$f_name['f_name'];
			$pdata[] =$value1['number'];
			$pdata[] =$value1['store_house'];
			$pdata[] =$value1['input_time'];
			$result[] = $pdata;
			unset($pdata);
		}
		//二维数组
		return $result;
	}
}