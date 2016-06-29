<?php
class productModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'cop_product');
	}
}