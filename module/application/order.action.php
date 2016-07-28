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
	// /**
	//  *
	//  * @access public
	//  * @return html
	//  */
	// public function init(){
	// 	$doact=sget('do','s');
	// 	$action=sget('action','s');
	// 	$order_type=sget('order_type','s');
	// 	if($action=='grid'){ //获取列表
	// 		$this->_grid();exit;
	// 	}
	// 	$this->assign('order_type',1);
	// 	$this->assign('doact',$doact);
	// 	$this->assign('page_title','订单管理列表');
	// 	$this->display('order.list.html');
	// }
	// /**
	//  * @access public
	//  * @return html
	//  * 默认是采购订单
	//  **/
	// public function purchase(){
	// 	$doact=sget('do','s');
	// 	$action=sget('action','s');
	// 	$order_type=sget('order_type','s');
	// 	if($action=='grid'){ //获取列表
	// 		$this->_grid();exit;
	// 	}
	// 	$this->assign('order_type',2);
	// 	$this->assign('doact',$doact);
	// 	$this->assign('page_title','订单管理列表');
	// 	$this->display('order.list.html');
	// }

	// /**
	//  * Ajax获取列表内容
	//  */
	// public function _grid(){
	// 	$page = sget("pageIndex",'i',0); //页码
	// 	$size = sget("pageSize",'i',20); //每页数
	// 	$sortField = sget("sortField",'s','input_time'); //排序字段
	// 	$sortOrder = sget("sortOrder",'s','desc'); //排序
	// 	//筛选
	// 	$where.= 1;
	// 	//筛选状态
	// 	if(sget('type','i',0) !=0) $order_type=sget('type','i',0);//订单类型
	// 	if(sget('order_type','i',0) !=0) $order_type=sget('order_type','i',0);
	// 	if($order_type !=0)  $where.=" and `order_type` =".$order_type;
	// 	$financial_records=sget('financial_records','s');//抬头
	// 	if($financial_records !='')  $where.=" and `financial_records` = ".$financial_records;
	// 	$order_source=sget('order_source','i',0);//订单来源
	// 	if($order_source !=0)  $where.=" and `order_source` =".$order_source;
	// 	$pay_method=sget('pay_method','i',0);//付款方式
	// 	if($pay_method !=0)  $where.=" and `pay_method` =".$pay_method;
	// 	$transport_type=sget('transport_type','i',0);//运输方式
	// 	if($transport_type !=0)  $where.=" and `transport_type` =".$transport_type;
	// 	$business_model=sget('business_model','i',0);//业务模式
	// 	if($business_model !=0)  $where.=" and `business_model` =".$business_model;
	// 	$order_status=sget('order_status','i',0);//订单审核
	// 	if($order_status !=0)  $where.=" and `order_status` =".$order_status;
	// 	$transport_status=sget('transport_status','i',0);//物流审核
	// 	if($transport_status !=0)  $where.=" and `transport_status` =".$transport_status;
	// 	$out_storage_status=sget('out_storage_status','i',0);//发货状态
	// 	if($out_storage_status !=0)  $where.=" and `out_storage_status` =".$out_storage_status;
	// 	//筛选时间
	// 	$sTime = sget("sTime",'s','input_time'); //搜索时间类型
	// 	$where.=getTimeFilter($sTime); //时间筛选
	// 	//关键词搜索
	// 	$key_type=sget('key_type','s','order_sn');
	// 	$keyword=sget('keyword','s');
	// 	if(!empty($keyword) && $key_type=='input_admin'  ){
	// 		$admin_id = M('rbac:adm')->getAdmin_Id($keyword);
	// 		$where.=" and `customer_manager` = '$admin_id'";
	// 	}elseif(!empty($keyword) && $key_type=='c_id'){
	// 		$keyword=M('product:order')->getOidByCname($keyword);
	// 		$where.=" and `$key_type` in ('$keyword') ";
	// 	}elseif(!empty($keyword)){
	// 		$where.=" and `$key_type`  = '$keyword' ";
	// 	}
	// 	$orderby = "$sortField $sortOrder";
	// 	//筛选过滤自己的订单信息
	// 	if($_SESSION['adminid'] != 1 && $_SESSION['adminid'] > 0){
	// 		$sons = M('rbac:rbac')->getSons($_SESSION['adminid']);
	// 		$where .= " and (`customer_manager` in ($sons) or `partner` = {$_SESSION['adminid']})  ";
	// 		//筛选财务
	// 		$roleid = M('rbac:rbac')->model('adm_role_user')->select('role_id')->where("`user_id` = {$_SESSION['adminid']}")->getOne();
	// 		if(in_array($roleid, array('30','26','27'))){
	// 			 $where .= " and `order_status` = 2 and `transport_status` = 2 ";
	// 			 $orderby = "update_time desc";
	// 		} 
	// 	}
	// 	$list=$this->db->where($where)->page($page+1,$size)->order($orderby)->getPage();
	// 	foreach($list['data'] as &$v){
	// 		$v['c_name']=  $v['partner'] == $_SESSION['adminid'] ?  '*******' : M("user:customer")->getColByName($v['c_id']);//根据cid取客户名
	// 		$v['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
	// 		$v['update_time']=$v['update_time']>1000 ? date("Y-m-d H:i:s",$v['update_time']) : '-';
	// 		$v['sign_time']=$v['sign_time']>1000 ? date("Y-m-d H:i:s",$v['sign_time']) : '-';
	// 		$v['order_source']=L('order_source')[$v['order_source']]; 
	// 		$v['order_name']=L('company_account')[$v['order_name']]; 
	// 		$v['pay_method'] =L('pay_method')[$v['pay_method']];
	// 		$v['in_storage_status']=L('in_storage_status')[$v['in_storage_status']];
	// 		$v['transport_type']=L('transport_type')[$v['transport_type']];
	// 		$v['business_model']=L('business_model')[$v['business_model']];
	// 		$v['financial_records']=L('financial_records')[$v['financial_records']];
	// 		$v['partner']=M('rbac:adm')->getUserByCol($v['partner']);
	// 		//订单收付款状态
	// 		$v['payments_status']= ( $v['order_type'] == '1' ? L('collection_g_status')[$v['collection_status']] :  L('collection_p_status')[$v['collection_status']] ) ;
	// 		$v['order_type']=L('order_type')[$v['order_type']];
	// 		$v['out_storage_status']=L('out_storage_status')[$v['out_storage_status']];
	// 		$v['invoice_status']=L('invoice_status')[$v['invoice_status']];
	// 		$v['type_status']= L('order_status')[$v['order_status']].'|'.L('transport_status')[$v['transport_status']];
	// 		$v['node_flow'] = $this->_accessChk($this->db->model('order')->select('node_flow')->where("`o_id` ={$v['o_id']} ")->getOne());
	// 		$v['cmanager'] = M('rbac:adm')->getUserByCol($v['customer_manager']);
	// 		//获取采购订单开票状态
	// 		if(!empty($v['store_o_id'])){
	// 			$v['newstatus'] = M("product:order")->getColByName($value=$v['store_o_id'],$col='invoice_status',$condition='o_id');
	// 		}
	// 		if(!empty($v['join_id'])){
	// 			$v['newstatus'] = M("product:order")->getColByName($value=$v['join_id'],$col='invoice_status',$condition='o_id');
	// 		}
	// 		$v['see'] =  ($v['customer_manager'] == $_SESSION['adminid'] ||  in_array($v['customer_manager'], explode(',', $sons)) || $_SESSION['adminid']  == '1') ? '1':'0';
	// 		//获取单笔订单收付款状态			
	// 		$m = M("product:collection")->getLastInfo($name='o_id',$value=$v['o_id']);
	// 		$v['one_c_status'] =$m[0]['collection_status'];
	// 		//获取单笔订单开票状态
	// 		// $n = M("product:billing")->getLastInfo($name='o_id',$value=$v['o_id']);
	// 		// $v['one_b_status'] =$n[0]['invoice_status'];
	// 		$v['one_b_status']=M("product:billing")->where("o_id={$v['o_id']} and invoice_status=1")->select('invoice_status')->getOne();

	// 	}
	// 	$msg="";
	// 	if($list['count']>0){
	// 		$sum=$this->db->select("sum(total_num) as wsum, sum(total_price) as msum")->where($where)->getRow();
	// 		$msg="[筛选结果]总额:【".price_format($sum['msum'])."】总吨:【".$sum['wsum']."】";
	// 	}
	// 	$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>$msg);
	// 	$this->json_output($result);
	// }
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