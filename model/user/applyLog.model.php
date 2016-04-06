<?php
/**
 * 用户操作记录
 */
class applyLogModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'apply_log');
	}

	/**
	 * 增加一条记录
	 * @param int $user_id     用户ID
	 * @param string $action_type 操作类型
	 * @param string $old_value   旧值
	 * @param string $new_value   新值
	 * @param string $remarks        备注描述
	 */
	public function addLog($user_id,$action_type,$old_value='',$new_value='',$success=1,$remarks=''){
		$ip = get_ip();
		$input_time = CORE_TIME;
		$old_value = addslashes($old_value);
		$new_value = addslashes($new_value);
		$operator = $_SESSION['name'];

		return $this->add(compact('user_id','action_type','old_value','new_value','input_time','ip','success','remarks','operator'));
	}
}