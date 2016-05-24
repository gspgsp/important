<?php
/**
 * 积分商品模型
 */

class pointsGoodsModel extends Model{
	public function __construct() {
		parent::__construct(C('db_default'), 'points_goods');
	}
}