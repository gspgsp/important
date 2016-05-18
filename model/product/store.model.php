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
		$exist=$this->model('customer')->select('id')->where($where)->getOne();
		return $exist>0 ? false : true;
	}
	/**
	 * 根据ID取指定值
	 */
	public function getColByid($id=0,$col=''){

	}
}