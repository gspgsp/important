<?php
/**
 * 积分商品模型
 */

class pointsGoodsModel extends Model{
	public function __construct() {
		parent::__construct(C('db_default'), 'points_goods');
	}

    //积分兑换
	public function getGoods(){
        return  $this->from('points_goods as pg')
            ->where('pg.status=1 and pg.is_mobile=0')
            ->order('pg.points desc')
            ->select('pg.id,pg.goods_id,pg.name,pg.thumb,pg.points')
            ->limit('3')
            ->getAll();

    }

	public function getOnsaleGoods($type)
	{
		$goods= $this->model('points_goods')->select('*')->where (" type = {$type} and status =1 and is_mobile = 1")->getRow();
		return $goods;
	}

	public function getGoodsInfo($goods_id)
	{
		$goods= $this->getPk($goods_id);
		return $goods;
	}

	public function getAllOnsaleGoods()
	{
		//type 1 是供求 2 是通讯录
		$goods_ids = $this->model ("points_goods")->select ('id')->where (" type in (1,2) and status =1 and is_mobile =1")->getAll();

		$goodsIds = array();
		foreach($goods_ids as $goods_id)
		{
			$goodsIds[]= $goods_id['id'];
		}

		return $goodsIds;
	}

}




















