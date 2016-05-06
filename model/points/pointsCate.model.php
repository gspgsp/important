<?php
/**
 * 积分商品分类
 */
class pointsCateModel extends Model{
	public function __construct() {
		parent::__construct(C('db_default'), 'points_cate');
	}
}