<?php
/**
 * 客户信息表
 */
class customerModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'customer');
	}
	/*
	 * 检查唯一性
	 * @param string $name 检查类型
	 * @param string $value 检查值
	  * @return bool（true唯一）
	 */
	public function curUnique($name='legal_idcard',$value='',$c_id=0){
		$where = "$name='$value'";
		if($c_id){
			$where .= " and c_id !='$c_id'";
		}
		$exist=$this->model('customer')->select('c_id')->where($where)->getOne();
		return $exist>0 ? false : true;
	}
	
	
}