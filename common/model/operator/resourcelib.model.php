<?php
/**
 * 资源库信息
 */
class resourcelibModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'resourcelib');
	}
	
}