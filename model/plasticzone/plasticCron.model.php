<?php
/**
*9 积分商品兑换-gsp
*/
class plasticCronModel extends model
{
	public function __construct() {
		parent::__construct(C('db_default'), 'corn');
	}
	public function is_allow_set($time_s,$time_e,$time_type){
		$count = 0;
		switch ($time_type) {
			case 0:
				// $alph = $time_e - $time_s;
				// if($alph == 1740){//29 min
				// 	$where = " type=1 and exe_time_s >= $time_s and exe_time_e <= $time_e";
				// 	$count = $this->where($where)->select('count(id)')->getOne();
				// }elseif ($alph == 540) {//9 min
				// 	$where = " type=1 and (exe_time_s >= $time_s and exe_time_e <= $time_e or (exe_time_s <= time_s and exe_time_e >= $time_e))";
				// 	$count = $this->where($where)->select('count(id)')->getOne();
				// }
				$where = " type=1 and exe_time_s >= $time_s and exe_time_e <= $time_e";
				$count = $this->where($where)->select('count(id)')->getOne();
				return $count>0 ? false : true ;
			case 1:
				$where = " type=0 and exe_time_s >= $time_s and exe_time_e <= $time_e ";
				$count = $this->where($where)->select('count(id)')->getOne();
				return $count>3 ? false : true ;
		}
	}
}