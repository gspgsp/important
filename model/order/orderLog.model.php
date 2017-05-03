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
		$this->cache->delete('getLog_'.$o_id.'_'.$type.'_'.$step);
		return $this->add(compact('o_id','step','type','total','payed','lefted','spend_time','input_time','user_ip','input_admin'));
	}
	/**
	 * 获取可视化的信息
	 */
	public function getLog($o_id = 0,$type=0,$step='-1'){
		$_key='getLog_'.$o_id.'_'.$type.'_'.$step;
		$where = "`o_id` = $o_id and `type` = $type ";
		if($step > -1 && $type < 2 ) $where .= " and `step` = $step ";
		if($type > 1) $where .= " and `step` in ($step) ";
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
	/**
	 * [sendMsg 给指定的用户发送短信]
	 * @Author   cuiyinming               QQ:1203116460
	 * @DateTime 2017-05-03T19:12:43+0800
	 * @param    integer                  $oid          [订单id]
	 * @param    integer                  $msg          [短信内容]
	 * @return   [type]                                 [订单类型 1为销售 2为采购]
	 */
	public function sendMsg($oid = 0,$type=1,$ext='现在已经将合同传给您'){
		$o_info = $this->model('order')->where("o_id = $oid")->getRow();
		$order_sn = $o_info['order_sn']; //订单的号码
		// 根据订单号获取货物信息
		if(empty($order_sn)){
			return;
		}
		$goods_str = '单号:'.$order_sn;
		if($type == 1){
			$goods = $this->model('sale_log')->where("`o_id` = $oid")->getAll();
		}else{
			$goods = $this->model('purchase_log')->where("`o_id` = $oid")->getAll();
		}
		if(!empty($goods)){
			foreach ($goods as $k => $v) {
				//查询牌号
				$product = $this->model('product')->where("`id` = {$v['p_id']}")->getRow();
				$goods_str .= '  '.$v['number'].'吨 '.$product['model'];
			}
		}
		// 货物信息
		$goods_str .= $ext.'请注意查收';
		//处理发送给谁
		$resives = $this->model('customer_contact')->where("`customer_manager` = {$o_info['customer_manager']} and `c_id` = {$o_info['c_id']}")->getAll();
		if(empty($resives)){
			$resives = $this->model('customer_contact')->where("`is_default` = 1 and `c_id` = {$o_info['c_id']}")->getAll();
		}
		if(!empty($resives)){
			foreach ($resives as $key => $value) {
				if(!empty($value['mobile'])){
					M('system:sysSMS')->sendMobileMsg($value['user_id'],$value['mobile'],$goods_str,8);
				}
			}
		}
	}
}