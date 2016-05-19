<?php
/**
*积分明细模型
*/
class creditdetailModel extends model
{
	public function __construct() {
		parent::__construct(C('db_default'), 'points_bill');
	}
	//获取对应用户的积分明细
	public function getCreditDetail($uid){

		$list = $this->model('points_bill')->select('id,points,type,addtime')->where('uid='.$uid)->getPage();
		return $list['data'];
	}
}