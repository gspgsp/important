<?php
/**
*微信红包金额
*/
class wxcountModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'weixin_count');
	}
}