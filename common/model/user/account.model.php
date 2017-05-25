<?php
/**
*个人中心-模型
*/
class accountModel extends model
{

	public function __construct() {
		parent::__construct(C('db_default'), 'concerned_product');
	}
}