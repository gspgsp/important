<?php
/**
 * 积分商品模型
 */

class pointsGoodsModel extends Model{
	public function __construct() {
		parent::__construct(C('db_default'), 'points_goods');
	}
	public function getProduct($id){
		$result = $this->model('points_goods')->getPk($id);
		if($result)return $result;
		return false;
	}
}