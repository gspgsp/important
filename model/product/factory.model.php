<?php 
//厂家模型
class factoryModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'factory');
	}
	//更具id取得厂家的名字
	public function getFnameById($fid){
		return $this->where("fid = $fid")->select('f_name')->getOne();
	}
	//根具名字取得厂家的id
	public function getIdsByName($name){
		return $this->where("f_name like '%$name%'")->select('fid')->getCol();
	}
}