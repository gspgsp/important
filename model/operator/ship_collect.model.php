<?php
/**
 * 物流订单信息
 */
class ship_collectModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'ship_collect');
	}
	//根据id获取物流订单信息
	public function getInfoById($id=0){
		return $info=$this->model('ship_collect')->where('id='.$id)->getRow();

	}
	//根据用户id获取对应用户的所有订单
	public function getAllInfoByUid($uid=0){
		return $info=$this->model('ship_collect')->where('user_id='.$uid)->getAll();
	}
	//根据用户id获取对应用户的一条订单
	public function getOneInfoByUid($uid=0){
		return $info=$this->model('ship_collect')->where('user_id='.$uid)->getRow();
	}
	//根据id获取指定的字段值
	public function getColById($id=0,$col='price'){
		return $info=$this->select("$col")->model('ship_collect')->where('id='.$id)->getOne();
	}
	
}