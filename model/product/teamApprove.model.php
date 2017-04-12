<?php
//战队特批列表模型
class teamApproveModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'team_capital_approve');
	}
	public function getTeamApproveResByOid($o_id = 0){
		$result=$this->select('id')->where("o_id = $o_id and status = 0")->getOne();
		return empty($result) ? '0' : $result;

	}
	public function getTeamApproveResByCustomerManager($customer_manager = 0){
		$result=$this->select('id')->where("customer_manager = $customer_manager and status = 0")->getOne();
		return empty($result) ? '0' : $result;
	}
}