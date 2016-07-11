<?php
/**
 * 客户信息表
 */
class customer_billingModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'customer_billing');
	}

	/*
	 * 检查唯一性
	 * @param string $name 检查类型
	 * @param string $value 检查值
	  * @return bool（true唯一）
	 */
	public function curUnique($name='c_id',$value=''){
		$where = "$name='$value'";
		$exist=$this->model('customer_billing')->select('id')->where($where)->getOne();
		return $exist>0 ? false : true;
	}


	/**
	 * 根据客户id获取所有的开票资料信息
	 * @Author   xianghui
	 * @DateTime 2016-07-110T11:18:38+0800
	 * @return   [type]                   [description]
		*/
	public function getCinfoById($cid = 0){
		if($cid>0){
			$result = $this->model('customer_billing')->where("c_id = $cid")->getRow();
		}
		return empty($result) ? array() : $result;
	}


}