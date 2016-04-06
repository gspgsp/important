<?php
/** 
 * App消息推送记录
 */
class pushLogModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'),'app_push_log');
	}

	/**
	 * 获取待发送的推送消息
	 * @return array
	 */
	public function getUnsent($user_id=0){
		return $this->where("status=0 AND user_id='{$user_id}'")->getRow();
	}

	/**
	 * 推送单条消息
	 * @return boolean
	 */
	public function send($user_id,$content,$type=2){
		return (bool)$this->add(get_defined_vars()+array('input_time'=>CORE_TIME));
	}

	/**
	 * 检查设备是否已接收过推送
	 * @return boolean
	 */
	public function isPushed($push_id,$deviceid){
		return (bool)$this->select('count(*)')->where("push_id={$push_id} AND device='{$deviceid}'")->getOne();
	}
}
?>