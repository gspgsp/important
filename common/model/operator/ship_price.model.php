<?php
/**
 * 资源库信息
 */
class ship_priceModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'ship_price');
	}

	public function get_index_ship($limit)
	{
		$cache = cache::startMemcache();
		$keys = 'ship_price_index';
		if( $data = $cache->get($keys) ) return $data;
		$data = $this->order('rand()')->limit($limit)->getAll();
		foreach ($data as $key => $value) {
			$weight = rand(20,30);
			$data[$key]['weight'] = $weight;
			$price = $weight <= 10 ? '5to10' : ( $weight>10 && $weight<=15 ? '10to15' : ( $weight>15 && $weight<=20 ? '15tp20' : $weight>20 && $weight<=25 ? '20to25' : $weight>25 && $weight<=30 ? '25to30' : '30plus' ));
			$data[$key]['price'] = $weight * $value[$price];
		}
		$cache->set($keys,$data,43200);
		return $data;
	}
	
}