<?php
/**
 * 角色的权限 
 */
class accessModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'adm_role_access');
	}
}
?>