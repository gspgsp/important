<?php
/**
 * 积分商品订单
 */
class pointsOrderModel extends Model{

	public function __construct() {
		parent::__construct(C('db_default'), 'points_order');
	}
}