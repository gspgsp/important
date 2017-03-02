<?php
/**
 * 订单管理
 */
class orderAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->db=M('public:common')->model('order');
		$this->doact = sget('do','s');
		$this->assign('order_source',L('order_source')); //订单来源
		$this->assign('pay_method',L('pay_method')); //付款方式
		$this->assign('transport_type',L('transport_type')); //运输方式
		$this->assign('business_model',L('business_model')); //业务模式
		$this->assign('financial_records',L('financial_records')); //财务记录
		$this->assign('order_status',L('order_status')); //订单审核
		$this->assign('transport_status',L('transport_status')); //物流审核
		$this->assign('out_storage_status',L('out_storage_status')); //发货状态
		$this->assign('invoice_status',L('invoice_status')); 		//开票状态
		$this->assign('invoice_one_status',L('invoice_one_status'));    //单笔明细开票状态
		$this->assign('price_type',L('price_type')); //价格单位
		$this->assign('in_storage_status',L('in_storage_status')); //入库状态
		$this->assign('order_type',L('order_type')); //订单类型：
		$this->assign('company_account',L('company_account')); //交易公司账户
		$this->assign('sales_type',L('sales_type')); //销售类型
		$this->assign('purchase_type',L('purchase_type')); //采购类型
		$this->assign('bile_type',L('bile_type'));		 	 	//票据类型
		$this->assign('billing_type',L('billing_type'));    	//开票类型
		$this->assign('c_fax',L('c_fax'));  //联系传真
	}
	/**
	* 订单信息
	* @access public
	*/
	public function info(){
		$o_id=sget('oid','i',0);
		$sale=sget('sale','i','0');

		if ($sale ==1) {//查看订单对应得销售订单信息
			if($o_id>0){
				$o_id = M("product:order")->where('join_id='.$o_id.' or store_o_id='.$o_id)->select('o_id')->getOne();
			}
		}

		$change_id=sget('change_id',i,0); //接收不销库存的o_id 用于生成采购
		$order_type=sget('order_type','i',0); //用于区分销售还是采购
		$o_type = sget('o_type','i',0);//用于双击弹出查看时，区分销售还是采购
		if($o_id<1){
			if($order_type  == 1){
				$order_sn='SO'.genOrderSn();
			}else{
				$order_sn='PO'.genOrderSn();
			}
			$this->assign('input_admin',$_SESSION['name']); //用于把
			$this->assign('order_sn',$order_sn);
			$this->assign('otype','addopus'); //新增订单关联前台显示
			$info['sign_place']="上海";
			$info['delivery_location']="上海";
			$info['pickup_location']="上海";
			$info['sign_time']=date("Y-m-d",CORE_TIME);
			$this->assign('info',$info);
			$this->assign('order_type',$order_type);
			$this->display('order.edit.html');
			exit;
		}
		$info=$this->db->getPk($o_id); //查询订单信息
		//关联的交易员id
		// $join_manager=$this->db->select('customer_manager as cmer')->where("`o_id` = {$info['join_id']}")->getOne();
		if(empty($info)) $this->error('错误的订单信息');
		if($info['c_id']>0){
			$roleid = M('rbac:rbac')->model('adm_role_user')->select('role_id')->where("`user_id` = {$_SESSION['adminid']}")->getOne();
			//如果是财务部屏蔽
			$exits  = in_array($roleid, array('30','26','27','25','24','21')) ? '1' : '0';
			if(($info['partner'] != $info['customer_manager'] && $info['customer_manager'] != $_SESSION['adminid'])  &&   $_SESSION['adminid'] != 1 && $exits !='1'){
				$c_name =  '*******';
			 }else{
			 	$c_name = M("user:customer")->getColByName($info['c_id'],"c_name");//根据cid取客户名
			 	$var = M("user:customer")->getRowByName($info['c_id'],"credit_limit,available_credit_limit");//根据cid 取出 信用额度、可用额度
			 }
		}
		 $info['credit_limit']=$var['credit_limit'];
		$info['available_credit_limit']=$var['available_credit_limit'];
		$info['sales_type']=L('sales_type')[$info['sales_type']];//前台读取销售类型
		$info['purchase_type']=L('purchase_type')[$info['purchase_type']];//前台读取采购类型
		$info['order_name']=L('company_account')[$info['order_name']];
		$info['sign_time']=date("Y-m-d",$info['sign_time']);
		$info['pickup_time']=date("Y-m-d",$info['pickup_time']);
		$info['delivery_time']=date("Y-m-d",$info['delivery_time']);
		$info['payment_time']=date("Y-m-d",$info['payment_time']);
		$info['partner']=M('rbac:adm')->getUserByCol($info['partner']);
		$info['creater']=M('rbac:adm')->getUserByCol($info['customer_manager']);
		if($info['h_pur_cid']>0){
			$h_pur_cid = M("user:customer")->getColByName($info['h_pur_cid'],"c_name");//根据cid取客户名
			$this->assign('h_pur_cid',$h_pur_cid);
		}
		$this->assign('c_name',$c_name);
		$this->assign('info',$info);//分配订单信息
		if($o_type ==1){
			$this->assign('collection_status',L('gatheringt_status'));//订单收款状态
		}else{
			$this->assign('collection_status',L('payment_status'));//订单付款状态
		}
		//不同部门审核
		// $this->assign('sexits',(in_array($_SESSION['adminid'], array('21')) || $_SESSION['adminid']==1) ? 1 : 0);//销售审核（销售经理）
		// $this->assign('texits',(in_array($_SESSION['adminid'], array('24','25')) || $_SESSION['adminid']==1)  ? 1 : 0);//物流审核 (物流经理)
		$this->assign('type',$o_type);
		$order_type = $info['order_type'] == 1? 'saleLog' : 'purchaseLog';
		$this->assign('order_type',$order_type);
		$this->assign('o_id',$o_id);
		$this->display('order.viewInfo.html');
	}


	/**
	* 订单信息
	* @access public
	*/
	public function viewInfo(){
		$o_id=sget('oid','i',0);
		$sale=sget('sale','i','0');

		if ($sale ==1) {//查看订单对应得销售订单信息
			if($o_id>0){
				$o_id = M("product:order")->where('join_id='.$o_id.' or store_o_id='.$o_id)->select('o_id')->getOne();
			}
		}

		$change_id=sget('change_id',i,0); //接收不销库存的o_id 用于生成采购
		$order_type=sget('order_type','i',0); //用于区分销售还是采购
		$o_type = sget('o_type','i',0);//用于双击弹出查看时，区分销售还是采购
		if($o_id<1){
			$this->error("没用对应的销售订单");
		}
		$info=$this->db->getPk($o_id); //查询订单信息
		//关联的交易员id
		// $join_manager=$this->db->select('customer_manager as cmer')->where("`o_id` = {$info['join_id']}")->getOne();
		if(empty($info)) $this->error('错误的订单信息');
		if($info['c_id']>0){
			$roleid = M('rbac:rbac')->model('adm_role_user')->select('role_id')->where("`user_id` = {$_SESSION['adminid']}")->getOne();
			//如果是财务部屏蔽
			$exits  = in_array($roleid, array('30','26','27')) ? '1' : '0';
			if(($info['partner'] == $_SESSION['adminid'] || $info['customer_manager'] != $_SESSION['adminid'])  &&   $_SESSION['adminid'] != 1 && $exits !='1'){
				$c_name =  '*******';
			 }else{
			 	$c_name = M("user:customer")->getColByName($info['c_id'],"c_name");//根据cid取客户名
			 }
		}
		$info['order_name']=L('company_account')[$info['order_name']];
		$info['sign_time']=date("Y-m-d",$info['sign_time']);
		$info['pickup_time']=date("Y-m-d",$info['pickup_time']);
		$info['delivery_time']=date("Y-m-d",$info['delivery_time']);
		$info['payment_time']=date("Y-m-d",$info['payment_time']);
		$info['partner']=M('rbac:adm')->getUserByCol($info['partner']);
		$info['creater']=M('rbac:adm')->getUserByCol($info['customer_manager']);
		$this->assign('c_name',$c_name);
		$this->assign('info',$info);//分配订单信息
		if($o_type ==1){
			$this->assign('collection_status',L('gatheringt_status'));//订单收款状态
		}else{
			$this->assign('collection_status',L('payment_status'));//订单付款状态
		}
		$this->assign('type',$o_type);
		$order_type = $info['order_type'] == 1? 'saleLog' : 'purchaseLog';
		$this->assign('order_type',$order_type);
		$this->assign('o_id',$o_id);
		$this->display('order.viewInfo.html');
	}

}