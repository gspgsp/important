<?php
/**
*兑换记录模型
*/
class creditRecordModel extends model
{
	public function __construct() {
		parent::__construct(C('db_default'), 'points_order');
	}
	//获取对应用户兑换订单
	public function getCreditRecord($uid){
		$_key='credit_order'.$uid;
		$cache=cache::startMemcache();
		//$cache->delete($_key);
		$data=$cache->get($_key);
		$pushData = array();
		if(empty($data)){
			$list = $this->model('points_order')->select('order_id,create_time,status,update_time,usepoints,uid')->where('uid='.$uid)->getPage();
			$cache->set($_key,$list['data'],86400);//加入缓存
		}
		$goods = $this->model('points_goods')->select('thumb,name')->getRow();
		foreach ($data as $value) {
			array_push($value, $goods['thumb'],$goods['name']);
			$pushData[]=$value;
		}
		return $pushData;
	}
}