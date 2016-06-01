<?php
/**
 * 原油价格 -黎贤
 */

class oilPriceModel extends Model{

	public function __construct() {
		parent::__construct(C('db_default'), 'oil_price');
	}

	/**
	 * [get_index 原油指数]
	 * @param  [type] $type [原油类型]
	 * @return [type]       [原油数组]
	 */
	public function get_index($type)
	{
		$t = $type == 0 ? 'w' : 'b';
		$keys = "oli_". $t;
		$cache = cache::startMemcache();
		$arr=$cache->get($keys);
		if($arr) return $arr;
		$arr = array();
		$temp = array();
		$data =$this->where("type=$type")->order('input_time desc')->limit(9)->getAll();
		$arr['last'] = $data[0];
		krsort($data);
		foreach ($data as $key => $value) {
			$temp['price'][] = $value['price'];
			$temp['date'][] = date('m/d', $value['input_time']);
		}
		$temp['max'] = ceil(max($temp['price'])+1);
		$temp['min'] = ceil(min($temp['price'])-1);
		$temp['date'][0] = '';
		$temp['date'][count($temp['date'])-1] = '';
		$arr['oil'] = $temp;
		$cache->set($keys,$arr);
		return $arr;
	}
}