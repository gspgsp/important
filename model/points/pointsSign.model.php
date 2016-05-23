<?php
/**
 * 积分签到
 */
class pointsSignModel extends Model{
	public function __construct() {
		parent::__construct(C('db_default'), 'points_sign');
	}
	public function add($data){
		if(!$this->add($data))return false;
		return true;
	}
}