<?php
/**
 * 退款处理 
 */
class refundModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'uaccount_pay');
	}
	
	/**
	 * 用户退款
	 * @param string $order_no  支付订单号号
	 * @param string  $remark 退款描述
	 * @return array(err,msg)
	 */
	public function doRefund($order_no='',$remark=''){
		#订单信息
		$order = $this->model('uaccount_pay')->select('status,log_id,log_sn,user_id,amount,payment')->where("order_no='{$order_no}'")->getRow();
		if(empty($order) || $order['user_id']<10000 || $order['status']!=1){
			return array('err'=>1,'msg'=>'错误的退款请求');	
		}

		$user_id=(int)$order['user_id']; //用户id
		$amount=(float)$order['amount']; //退款金额
		$fee=0; //手续费
		
		$trade_type=2; //提现
		$sdb=M('system:sysAccount')->setTrade($trade_type);

		//检查现金账户余额
		$account=$sdb->getUserAccount($user_id,1);
		if($amount>$account['useSurplus']){
			return array('err'=>2,'msg'=>'账户余额不足，不能退款');	
		}
		
		$log_sn=$sdb->getLogSn(true);
		
		//开始处理付款事务
		$this->startTrans();
		$desc="订单退款{$order_no}($remark)";
		
		/*
		//支付方式对应内帐-101
		$payment=C('payment.'.$order['payment']);
		$account_id=(int)$payment['account']; //支付对应内帐
		if($account_id>0){
			$sdb->_iaccount($account_id,$trade_type,$amount,$desc,$fee);
		}*/
		
		//提现记录
		$data=array(
			'user_id'=>$user_id,
			'log_sn'=>$log_sn,
			'amount'=>$amount,
			'fee'=>$fee,
			'input_time'=>CORE_TIME,
			'status'=>0, //0申请1成功2取消
			'bank_id'=>0, //银行信息ID
			'user_ip'=>get_ip(),
		);
		$this->model('uaccount_draw')->add($data);
		
		//总交易流水+100=用户-100手续费+1（实际到账99）
		$sdb->_trade($user_id,$trade_type,$amount,$desc);

		//手续费+
		if($fee>0.001){ //支付手续费
			$account_id=1; //支付手续费账户
			$sdb->_iaccount($account_id,$trade_type,$fee,$desc);
		}
		
		//用户账户-100
		$sdb->_uaccount($user_id,1,$trade_type,-$amount,$desc);
		
		//用户总资产-100
		$data=array('capital'=>"-=".$amount);
		$this->model('uaccount')->where('user_id='.$user_id)->update($data);

		//支付流水设置为退款
		$data=array('status'=>3);
		$this->model('uaccount_pay')->where('log_id='.$order['log_id'])->update($data);
		
		//退款记录
		$data=array(
			'order_no'=>$order_no,
			'draw_sn'=>$log_sn,
			'pay_no'=>'',
			'amount'=>$amount,
			'refund_amount'=>$amount,
			'admin_name'=>$_SESSION['name'],
			'input_time'=>CORE_TIME	
		);
		$this->model('uaccount_refund')->add($data);

		if($this->commit()){
			return array('err'=>0,'msg'=>'退款处理成功');
		}else{
			$this->rollback();
			return array('err'=>1,'msg'=>'数据处理失败');	
		}	
	}
}
?>