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
	public function getCreditShop($gtype){
		//1=>'家居',2=>'数码',空，全部
		if($gtype == 1){
			$list = $this->model('points_goods')->where("cate_id=$gtype and status=1")->select('id,cate_id,thumb,image,name,points,type')->getAll();
		}elseif ($gtype == 2) {
			$list = $this->model('points_goods')->where("cate_id=$gtype and status=1")->select('id,cate_id,thumb,image,name,points,type')->getAll();
		}else{
			$list = $this->model('points_goods')->where("status=1")->select('id,cate_id,thumb,image,name,points,type')->getAll();
		}
		// $index = mt_rand(0,count($list)-1);
		// if($index>count($list)-4){
		// 	$recommand = array_slice($list,0,4);
		// }else{
		// 	$recommand = array_slice($list,$index,4);
		// }
		//返回四条推荐商品
		return $list;
	}
	//返回商品详情页
	public function getShopDetail($gid){
		$list = $this->model('points_goods')->where('id='.$gid)->select('id,thumb,image,name,points,cate_id,type')->getRow();
		if(empty($list)) return array('err'=>20,'msg'=>'没有该商品');
		return $list;
	}
}