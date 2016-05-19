<?php
/**
*积分明细模型
*/
class creditdetailModel extends model
{
	public function __construct() {
		parent::__construct(C('db_default'), 'points_bill');
	}
	//获取对应用户的积分明细
	public function getCreditDetail($uid){
		// $_key='credit_detail'.$uid;
		// $cache=cache::startMemcache();
		// $cache->delete($_key);
		// $data=$cache->get($_key);
		// if(empty($data)){
		// 	$list = $this->model('points_bill')->select('id,points,type,addtime')->where('uid='.$uid)->getPage();
		// $cache->set($_key,$list['data'],86400);//加入缓存
		// }
		// $data=$cache->get($_key);
		$list = $this->model('points_bill')->select('id,points,type,addtime')->where('uid='.$uid)->getPage();
		return $list['data'];
	}
}