<?php

class shareModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'share');
	}

}