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


}




















