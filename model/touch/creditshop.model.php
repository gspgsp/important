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
	public function getCreditShop(){
		$list = $this->model('points_goods')->select('id,cate_id,thumb,image,name,points')->getAll();
		$index = mt_rand(0,count($list)-1);
		if($index>count($list)-4){
			$recommand = array_slice($list,0,4);
		}else{
			$recommand = array_slice($list,$index,4);
		}
		//返回四条推荐商品
		return $recommand;
	}
	//返回商品详情页
	public function getShopDetail($gid){
		$list = $this->model('points_goods')->where('id='.$gid)->select('id,thumb,image,name,points')->getRow();
		if(empty($list)) return array('err'=>1,'msg'=>'没有该商品');
		return $list;
	}
}