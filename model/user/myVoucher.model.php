<?php
/**
*财务中心-我的抵用券模型
*/
class myVoucherModel extends model
{

	public function __construct() {
		parent::__construct(C('db_default'), 'coupon');
	}
	//更新购物券的状态
	public function checkVoucherState($id,$start_time,$end_time){
		if($end_time - time() < 86400 && $end_time - time()>0) {
			return 1;
		}elseif ($end_time >time()) {
			return 2;
		}elseif ($end_time < time()) {
			$die_time = $end_time+86400;
			if($this->model('coupon')->where('id='.$id)->update(array('die_time'=>$die_time))) return 3;
		}
	}
	//获取过期时间
	public function getDieTime($id){
		if($die_time = $this->model('coupon')->where('id='.$id)->select('die_time')->getOne())
			return $die_time;
	}
	//已使用时调用
	public function genOrderNum($type=1){
		$date=date("YmdHis");
		//日期+交易类型+时分+6为随机
		return substr($date,0,8).str_pad($type, 4, '0', STR_PAD_RIGHT).substr($date,8,4).str_pad(mt_rand(1000, 999999), 6, '0', STR_PAD_LEFT);
	}
}