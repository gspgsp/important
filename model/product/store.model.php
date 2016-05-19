<?php 
//仓库锁定业务员模型
class storeModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'store');
	}

	/*
	 * 检查唯一性
	 * @param string $name 检查类型
	 * @param string $value 检查值
	  * @return bool（true唯一）
	 */
	public function curUnique($name='',$value='',$id=0){
		$where = "$name='$value'";
		if($id){
			$where .= " and id !='$id'";
		}
		$exist=$this->model('store')->select('id')->where($where)->getOne();
		return $exist>0 ? false : true;
	}
	/**
	 * 通过仓库id取仓库名
	 */
	public function getStoreNameBySid($id=0){
		return $this->select('store_name')->where("id='$id'")->getOne();
	}

	/**
	 * 模糊查询仓库名匹配的id
	 */
	public function getSidBySname($value=''){
		$arr=$this->select('id')->where("store_name like '%".$value."%'")->getAll();
		foreach ($arr as $key => $v) {
			$ids[]=$v['id'];
		}
		$data = implode(',',$ids);
		return empty($data)? false : $data;
	}
}