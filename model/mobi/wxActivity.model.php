<?php
/**
*wxActivity
*/
class wxActivityModel extends model
{

	public function __construct() {
		parent::__construct(C('db_default'), 'purchase');
	}
	public function getActivity($uid){
		$start = strtotime('2016-08-5');
		$end = strtotime('2016-08-19');
		$now = strtotime(date("Y-m-d",CORE_TIME));
		if($now>$start){
			$start = $now;
		}
		$where = "user_id = $uid and input_time between $start and $end";
		return $this->from('purchase as pur')->where($where)->select('count(pur.id) as total')->getAll();
	}
	public function saveAnswer($uid,$ques_id,$answer){
		if($this->model('wxactivity')->where("user_id=$uid and ques_id=$ques_id")->getRow()){
			if($this->model('wxactivity')->where("user_id=$uid and ques_id=$ques_id")->update(array('answer'=>$answer,'update_time'=>CORE_TIME))) return true;
			return false;
		}else{
			if($this->model('wxactivity')->add(array('user_id'=>$uid,'ques_id'=>$ques_id,'answer'=>$answer,'input_time'=>CORE_TIME))) return true;
			return false;
		}
	}
}