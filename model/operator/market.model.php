<?php
/**
 * 行情动态  --黎贤
 */
 
 class marketModel extends Model{
 	protected $cache,$keys;
 	public function __construct() {
 		parent::__construct(C('db_default'), 'market');
 		$this->cache=cache::startMemcache();
 		$this->keys='quotation_index';

 	}

 	public function get_quotation_index(){

 		if( $quotation = $this->cache->get($this->keys) ) return $quotation;
 		$quotation_cate = L('product_type');
 		foreach ($quotation_cate as $key => $value) {
 			$quotation[$value] = $this->get_quotation($key);
 		}
 		$this->cache->set($this->keys,$quotation);
 		return $quotation;
 	}

 	public function get_quotation($cid)
 	{
 		$temp = array();
 		$data = $this->where("cid=$cid")->order('input_time desc')->limit(6)->getAll();
 		krsort($data);
 		foreach ($data as $key => $value) {
 			$temp['price'][] = $value['price'];
 			$temp['date'][] = date('m/d', $value['input_time']);
 			$temp['max'] = ceil(max($temp['price']))+10;
 			$temp['min'] = ceil(min($temp['price']))-10;
 		}
 		$temp['date'][0] = '';
 		$temp['date'][count($temp['date'])-1] = '';
 		return $temp;
 	}

 	public function delCache(){
 		$this->cache->delete($this->keys);
 	}
 }