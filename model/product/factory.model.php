<?php 
//厂家模型
class factoryModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'factory');
	}

	public function getFnameById($fid){
		return $this->where("fid = $fid")->select('f_name')->getOne();
	}
}