<?php
class billingLogModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'billing_log');
	}
}