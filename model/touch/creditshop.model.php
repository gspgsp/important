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

		$list = $this->model('points_goods')->select('id,cate_id,thumb,image,name,points')->getAll();
		$index = mt_rand(0,count($list)-1);
		if($index>count($data)-4){
			$recommand = array_slice($data,0,4);
		}else{
			$recommand = array_slice($data,$index,4);
		}
		//返回四条推荐商品
		return $recommand;
	}
}