<?php
/**
*积分商城模型
*/
class creditshopModel extends model
{

	public function __construct() {
		parent::__construct(C('db_default'), 'points_goods');
	}
	//返回商品推荐
    public function getCreditShop($uid){
		$_key='credit_shop'.$uid;
		$cache=cache::startMemcache();
		//$cache->delete($_key);
		$data=$cache->get($_key);
		if(empty($data)){
			$list = $this->model('points_goods')->select('id,cate_id,thumb,image,name,points')->getAll();
		$cache->set($_key,$list,86400);//加入缓存
		}
		$index = mt_rand(0,count($data)-1);
		if($index>count($data)-4){
			$recommand = array_slice($data,0,4);
		}else{
			$recommand = array_slice($data,$index,4);
		}
		//返回四条推荐商品
		return $recommand;
	}
}