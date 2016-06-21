<?php
/**
*兑换记录模型
*/
class creditRecordModel extends model
{
	public function __construct() {
		parent::__construct(C('db_default'), 'points_order');
	}
	//获取对应用户兑换订单
	public function getCreditRecord($uid){

		$list = $this->model('points_order')->select('order_id,create_time,status,update_time,usepoints,uid,goods_id')->where('uid='.$uid)->getPage();
		//取出对应的goods
		foreach ($list['data'] as $value2) {
			$goods = $this->model('points_goods')->select('thumb,name')->where('id='.$value2['goods_id'])->getRow();
			$tData['order_id'] = $value2['order_id'];
			$tData['create_time'] = $value2['create_time'];
			$tData['status'] = $value2['status'];
			$tData['update_time'] = CORE_TIME;
			$tData['usepoints'] = $value2['usepoints'];
			$tData['uid'] = $value2['uid'];
			$tData['thumb'] = $goods['thumb'];
			$tData['name'] = $goods['name'];
			$pushData[] = $tData;
			unset($tData);
		}
		return $pushData;
	}
}