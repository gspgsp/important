<?php
/**
 * 资源库信息
 */
class userInfoModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'resourcelib');
	}
	
}