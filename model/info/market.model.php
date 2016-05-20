<?php
/**
 * 行情动态  --黎贤
 */
 
 class marketModel extends Model{
 	public function __construct() {
 		parent::__construct(C('db_default'), 'market');
 	}

 	public function get_quotation_index(){
		$cache = cache::startMemcache();
 		$key = 'quotation_index';
 		if( $quotation = $cache->get($key) ) return $quotation;
 		$quotation_cate = L('product_type');
 		foreach ($quotation_cate as $key => $value) {
 			$quotation[$value] = $this->get_quotation($key);
 		}
 		$cache->set($key,$quotation);
 		return $quotation;
 	}

 	public function get_quotation($cid)
 	{
 		$temp = array();
 		$data = $this->where("cid=$cid")->order('addtime desc')->limit(6)->getAll();
 		krsort($data);
 		foreach ($data as $key => $value) {
 			$temp['price'][] = $value['price'];
 			$temp['date'][] = date('m/d', $value['addtime']);
 			$temp['max'] = ceil(max($temp['price']))+10;
 			$temp['min'] = ceil(min($temp['price']))-10;
 		}
 		$temp['date'][0] = '';
 		$temp['date'][count($temp['date'])-1] = '';
 		return $temp;
 	}
 }