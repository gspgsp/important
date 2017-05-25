<?php
/** 
 * App消息推送
 */
class pushModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'),'app_push');
	}

	/**
	 * 获取当前可用的推送消息
	 * @return array
	 */
	public function getAvailabled(){
		$curr_time = CORE_TIME;
		return $this->where("status=1 AND (start_time = 0 OR start_time <= {$curr_time}) AND (end_time = 0 OR end_time >= {$curr_time})")->getAll();
	}


}
?>