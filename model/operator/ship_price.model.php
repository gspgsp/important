<?php
/**
 * 资源库信息
 */
class ship_priceModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'ship_price');
	}
	
}