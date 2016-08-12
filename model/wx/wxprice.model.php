<?php
/**
*用户红包抢到金额
*/
class wxpriceModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'weixin_prize');
	}
}