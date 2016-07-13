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
	//根具名字取得厂家的id
	public function getIdByFName($name){
		return $this->select('fid')->where("f_name = '$name'")->getOne();
	}
	/**
	 * 根据名字取得厂家id
	 * 返回的是id组成的字符串
	 */
	public function getIdByName($name){
		$ids = $this->select('fid')->where("f_name like '%$name%'")->getCol();
		if (!empty($ids)){
			foreach ($ids as $v) {
				$ids[]=$v['f_id'];
			}
		}
		$data = implode(',',$ids);
		return empty($data)? false : $data;
	}
}