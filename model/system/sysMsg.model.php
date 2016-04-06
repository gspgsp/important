<?php
/**
 * 用户站内信 
 */
class sysMsgModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'user_msg');
	}
	
	/*
	 * 发送站内信
     * @access public
	 * @param int $user_id 用户ID
	 * @param string $msg 短信内容
	 * @param int $type 类型:1系统
     * @return bool
	 */
	public function sendMsg($user_id=0,$msg='',$type=1){
		if($user_id<1) return false;
		$_data=array(
			'user_id'=>(int)$user_id,
			'msg'=>addslashes($msg),
			'type'=>(int)$type,	
			'input_time'=>CORE_TIME,
		);
		return $this->model('user_msg')->add($_data);
	}
	public function send($user_id=0,$msg='',$type=1){
		return $this->sendMsg($user_id,$msg,$type);
	}

	/**
	 * 获取消息列表
	 * @param  integer $user_id    指定用户ID
	 * @param  integer $type       指定消息类型:1系统
	 * @param  integer $is_read    筛选是否已读消息
	 * @param  integer $time_start 筛选消息开始时间
	 * @param  integer $time_end   筛选消息结束时间
	 * @param  integer $page       页码
	 * @param  integer $page_size  分页大小
	 * @return array
	 */
	public function getMsgList($user_id=0,$type=1,$is_read=null,$time_start=0,$time_end=0,$page=1,$page_size=10){
		$where = array('AND');

		if($user_id) $where[] = 'user_id='.intval($user_id);
		if($is_read !== null) $where[] = 'is_read='.intval($is_read);
		if($type) $where[] = 'type='.intval($type);
		if($time_start && $time_end) {
			if($time_start > $time_end) $time_end = $time_start;
			$time_end += 86400;//加上24小时
			$where[] = 'input_time>='.$time_start;
			$where[] = 'input_time<='.$time_end;
		}else{
			//默认查3个月记录
			$time_start = strtotime('-3 months');
			$time_end = time();
		}
		return $this->where($where)->order('id desc')->page($page,$page_size)->getPage();
	}

	/**
	 * 统计未读消息数量
	 * @param  integer  $user_id 用户ID
	 * @return integer
	 */
	public function countUnread($user_id){
		$where = array('AND');
		$where[] = 'user_id='.intval($user_id);
		$where[] = 'is_read=0';
		return (int)$this->where($where)->select('count(*)')->getOne();
	}
}
?>