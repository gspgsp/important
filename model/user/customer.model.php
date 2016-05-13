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

	/**
	 * 更具字段取出对应的值
	 */
	public function getColByName($value=0,$col='c_name',$condition='c_id'){
		$result =  $this->model('customer')->select("$col")->where("$condition='$value'")->getOne();
		return empty($result) ? '-' : $result;
	}
	/**
	 * 根据联系人id 取出所有的所属公司信息
	 */
	public function getInfoByUid($uid=0){
		$info=$this->model('customer_contact')->wherePk($uid)->getRow();
		if($info){
			$result =  $this->model('customer')->select("*")->where("c_id=$info[c_id]")->getRow();
		}
		return empty($result) ? array() : $result;
	}
	
	/**
	 * 根据模糊联系人 搜索出所有的公司信息
	 */
	public function getInfoByCname($c_name,$keyword,$condition='c_id'){
		$result =  $this->model('customer')->select("$condition")->where("$c_name like '%$keyword%'")->getCol();
		return empty($result) ? array() : $result;
	}
}