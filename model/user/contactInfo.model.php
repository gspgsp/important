<?php
/**
 * 用户拓展表 --黎贤
 */
class contactInfoModel extends Model{
	public function __construct() {
		parent::__construct(C('db_default'), 'contact_info');
	}
	
}