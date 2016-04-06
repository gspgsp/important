<?php
/**
 * 管理节点 
 */
class nodeModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'adm_node');
	}
}
?>