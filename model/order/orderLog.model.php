<?php
/**
 * 订单流转记录
 */
class orderLogModel extends model{
	private $cache=NULL; //缓存
 	public function __construct() {
		parent::__construct(C('db_default'), 'order_flow');
		$this->cache=cache::startMemcache();
	}

	/**
	 * [addLog description]
	 * @Author   cuiyinming
	 * @DateTime 2017-02-28T17:35:26+0800
	 * @contract qq:1203116460            cuiyinming@126.com
	 * @param    integer                  $o_id              [订单id]
	 * @param    integer                  $type               [0信息 1物流 2资金 3发票]
	 * @param    integer                  $total             [总金额]
	 * @param    integer                  $payed             [支付金额]
	 * @param    integer                  $lefted            [剩余金额]
	 */
	public function addLog($o_id=0,$step=0,$type=0,$spend_time=0,$total=0,$payed=0,$lefted=0){
		$user_ip = get_ip();
		$input_time = CORE_TIME;
		$input_admin = $_SESSION['username'];
		$this->cache->delete('getLog_'.$o_id);
		return $this->add(compact('o_id','step','type','total','payed','lefted','spend_time','input_time','user_ip','input_admin'));
	}
	/**
	 * 获取可视化的信息
	 */
	public function getLog($o_id = 0,$type=0,$step=-1){
		// $_key='getLog_'.$o_id.'_'.$type.'_'.$step;
		$where = "`o_id` = $o_id and `type` = $type";
		if($step > -1) $where .= " and `step` = $step ";
		// $data=$this->cache->get($_key);
		if(empty($data)){
			$data=$this->where($where)->order('input_time desc')->limit(1)->getAll();
			if($data){
				foreach ($data as &$v) {
					$v['spend_time'] = $v['spend_time'] == 0 ?: Sec2Time($v['spend_time']);
				}
			}
			$this->cache->set($_key,$data); //加入缓存
		}
		return $data;
	}
}