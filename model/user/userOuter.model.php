<?php
/**
* 第三方授权登录表
*/
class userOuterModel extends model
{
	public function __construct() {
		parent::__construct(C('db_default'), 'user_outer');
	}
}