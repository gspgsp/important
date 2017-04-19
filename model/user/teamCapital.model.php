<?php
//战队配资模型
class teamCapitalModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'team_capital');
	}
	public function goMoney($team_capital = array(),$go_money){
		$result = $this->model('team_capital')->where('id='.$team_capital['id'])->update(array('available_money'=>$team_capital['available_money']-$go_money,'used_money'=>$team_capital['used_money']+$go_money,'update_time'=>time()));
		return $result;

	}
	public function comeMoney($team_capital = array(),$come_money){
		$result = $this->model('team_capital')->where('id='.$team_capital['id'])->update(array('available_money'=>$team_capital['available_money']+$come_money,'used_money'=>$team_capital['used_money']-$come_money,'update_time'=>time()));
		return $result;
	}
	/**
	 * 增加一条记录
	 * @param int $user_id     用户ID
	 * @param string $action_type 操作类型
	 * @param string $old_value   旧值
	 * @param string $new_value   新值
	 * @param string $remarks        备注描述
	 */
	public function addLog($o_id,$team_id,$action_type,$old_value='',$new_value='',$success=1,$pirce = 0,$description=''){
		$input_time = CORE_TIME;
		$old_value = addslashes($old_value);
		$new_value = addslashes($new_value);
		$price = addslashes($pirce);
		$input_admin = $_SESSION['username'];
		$order_sn = M('product:order')->getColByName($o_id,'order_sn');
		return $this->model('log_team_capital')->add(compact('o_id','order_sn','team_id','action_type','old_value','new_value','input_time','success','price','description','input_admin'));
	}
}