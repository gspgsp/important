<?php 
//联营订单模型
class unionOrderDetailModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'union_order_detail');
	}
	
}