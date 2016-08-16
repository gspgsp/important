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
		$this->assign('pay_remark',L('pay_remark'));  //联系传真
	}
	/**
	 * @access public
	 * @return html
	 */
	public function init(){
		$doact=sget('do','s');
		$action=sget('action','s');
		$order_type=sget('order_type','s');
		if($action=='grid'){ //获取列表
			$this->_grid();exit;
		}
		$this->assign('order_type',1);
		$this->assign('doact',$doact);
		$this->assign('page_title','订单管理列表');
		$this->display('order.list.html');
	}
	/**
	 * @access public
	 * @return html
	 * 默认是采购订单
	 **/
	public function purchase(){
		$doact=sget('do','s');
		$action=sget('action','s');
		$order_type=sget('order_type','s');
		if($action=='grid'){ //获取列表
			$this->_grid();exit;
		}
		$this->assign('order_type',2);
		$this->assign('doact',$doact);
		$this->assign('page_title','订单管理列表');
		$this->display('order.list.html');
	}

	/**
	 * Ajax获取列表内容
	 */
	public function _grid(){
		
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$roleid = M('rbac:rbac')->model('adm_role_user')->select('role_id')->where("`user_id` = {$_SESSION['adminid']}")->getOne();
		if(in_array($roleid, array('30','26','27'))){
			$sortField = sget("sortField",'s','update_time'); //排序字段
		}else{
			$sortField = sget("sortField",'s','input_time'); //排序字段
		}
		$sortOrder = sget("sortOrder",'s','desc'); //排序
		//筛选
		$where.= 1 and order_status;
		//筛选状态
		if(sget('type','i',0) !=0) $order_type=sget('type','i',0);//订单类型
		if(sget('order_type','i',0) !=0) $order_type=sget('order_type','i',0);
		if($order_type !=0)  $where.=" and `order_type` =".$order_type;
		$financial_records=sget('financial_records','s');//抬头
		if($financial_records !='')  $where.=" and `financial_records` = ".$financial_records;
		$order_source=sget('order_source','i',0);//订单来源
		if($order_source !=0)  $where.=" and `order_source` =".$order_source;
		$pay_method=sget('pay_method','i',0);//付款方式
		if($pay_method !=0)  $where.=" and `pay_method` =".$pay_method;
		$transport_type=sget('transport_type','i',0);//运输方式
		if($transport_type !=0)  $where.=" and `transport_type` =".$transport_type;
		$business_model=sget('business_model','i',0);//业务模式
		if($business_model !=0)  $where.=" and `business_model` =".$business_model;
		$order_status=sget('order_status','i',0);//订单审核
		if($order_status !=0){
			$where.=" and `order_status` =".$order_status;
		}else{
			$where.= " and `order_status` <> 3";
		}  
		$transport_status=sget('transport_status','i',0);//物流审核
		if($transport_status !=0)  $where.=" and `transport_status` =".$transport_status;
		$out_storage_status=sget('out_storage_status','i',0);//发货状态
		if($out_storage_status !=0)  $where.=" and `out_storage_status` =".$out_storage_status;
		//筛选时间
		$sTime = sget("sTime",'s','input_time'); //搜索时间类型
		$where.=getTimeFilter($sTime); //时间筛选
		//关键词搜索
		$key_type=sget('key_type','s','order_sn');
		$keyword=sget('keyword','s');
		if(!empty($keyword) && $key_type=='input_admin'  ){
			$admin_id = M('rbac:adm')->getAdmin_Id($keyword);
			$where.=" and `customer_manager` = '$admin_id'";
		}elseif(!empty($keyword) && $key_type=='c_id'){
			$keyword=M('product:order')->getOidByCname($keyword);
			$where.=" and `$key_type` in ($keyword) ";
		}elseif(!empty($keyword)){
			$where.=" and `$key_type`  = $keyword";
		}
		$orderby = "$sortField $sortOrder";
		//筛选过滤自己的订单信息
		if($_SESSION['adminid'] != 1 && $_SESSION['adminid'] > 0){
			$sons = M('rbac:rbac')->getSons($_SESSION['adminid']);
			$where .= " and (`customer_manager` in ($sons) or `partner` = {$_SESSION['adminid']})  ";
			//筛选财务
			if(in_array($roleid, array('30','26','27'))){
				 $where .= " and `order_status` = 2 and `transport_status` = 2 ";
			} 
		}

		$list=$this->db->where($where)->page($page+1,$size)->order($orderby)->getPage();
		//echo $_SESSION['adminid'];
		// p($list);die;
		foreach($list['data'] as &$v){
			$v['c_name']=  ($v['partner'] == $_SESSION['adminid'] && $v['customer_manager'] != $_SESSION['adminid']) ?  '*******' : M("user:customer")->getColByName($v['c_id']);//根据cid取客户名
			$v['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
			$v['update_time']=$v['update_time']>1000 ? date("Y-m-d H:i:s",$v['update_time']) : '-';
			$v['sign_time']=$v['sign_time']>1000 ? date("Y-m-d H:i:s",$v['sign_time']) : '-';
			$v['order_source']=L('order_source')[$v['order_source']]; 
			$v['order_name']=L('company_account')[$v['order_name']]; 
			$v['pay_method'] =L('pay_method')[$v['pay_method']];
			$v['in_storage_status']=L('in_storage_status')[$v['in_storage_status']];
			$v['transport_type']=L('transport_type')[$v['transport_type']];
			$v['business_model']=L('business_model')[$v['business_model']];
			$v['financial_records']=L('financial_records')[$v['financial_records']];
			$v['partner']=M('rbac:adm')->getUserByCol($v['partner']);
			//订单收付款状态
			$v['payments_status']= ( $v['order_type'] == '1' ? L('collection_g_status')[$v['collection_status']] :  L('collection_p_status')[$v['collection_status']] ) ;
			$v['order_type']=L('order_type')[$v['order_type']];
			$v['out_storage_status']=L('out_storage_status')[$v['out_storage_status']];
			$v['invoice_status']=L('invoice_status')[$v['invoice_status']];
			$v['type_status']= L('order_status')[$v['order_status']].'|'.L('transport_status')[$v['transport_status']];
			$v['node_flow'] = $this->_accessChk($this->db->model('order')->select('node_flow')->where("`o_id` ={$v['o_id']} ")->getOne());
			$v['cmanager'] = M('rbac:adm')->getUserByCol($v['customer_manager']);
			//获取采购订单开票状态
			if(!empty($v['store_o_id'])){
				$v['newstatus'] = M("product:order")->getColByName($value=$v['store_o_id'],$col='invoice_status',$condition='o_id');
			}
			if(!empty($v['join_id'])){
				$v['newstatus'] = M("product:order")->getColByName($value=$v['join_id'],$col='invoice_status',$condition='o_id');
			}
			$v['see'] =  ($v['customer_manager'] == $_SESSION['adminid'] ||  in_array($v['customer_manager'], explode(',', $sons)) || $_SESSION['adminid']  == '1') ? '1':'0';
			//获取单笔订单收付款状态			
			$m = M("product:collection")->getLastInfo($name='o_id',$value=$v['o_id']);
			$v['one_c_status'] =$m[0]['collection_status'];
			//获取单笔订单开票状态
			// $n = M("product:billing")->getLastInfo($name='o_id',$value=$v['o_id']);
			// $v['one_b_status'] =$n[0]['invoice_status'];
			$v['one_b_status']=M("product:billing")->where("o_id={$v['o_id']} and invoice_status=1")->select('invoice_status')->getOne();

		}
		$msg="";
		if($list['count']>0){
			$sum=$this->db->select("sum(total_num) as wsum, sum(total_price) as msum")->where($where)->getRow();
			$msg="[筛选结果]总额:【".price_format($sum['msum'])."】总吨:【".$sum['wsum']."】";
		}
		$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>$msg);
		$this->json_output($result);
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
			$exits  = in_array($roleid, array('30','26','27','25','24','21')) ? '1' : '0';
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
	/**
	 * 修改订单显示界面
	 */
	public function editOrder(){
		$o_id=sget('o_id','i',0);
		$order_type= sget('order_type','i',0)==1 ? 'sale_log' : 'purchase_log' ;
		if($o_id<1) $this->error('信息错误');
		$info=$this->db->model('order')->getPk($o_id); //查询订单信息
		$detailinfo=$this->db->model($order_type)->where('o_id = '.$o_id)->getAll(); //查询明细订单信息
		foreach ($detailinfo as &$value) {
			$value['model']=M("product:product")->getModelById($value['p_id']);
			$pinfo=M("product:product")->getFnameByPid($value['p_id']);
			$value['f_name']=$pinfo['f_name'];//根据cid取客户名
			$value['store_name']=M("product:store")->getStoreNameBySid($value['store_id']); 
			$value['time_price']=$value['number']*$value['unit_price'];
			$value['require_number']=$value['number'];
		}

		if($info['c_id']>0) $info['c_name'] = M('user:customer')->getColByName($info['c_id'],"c_name");
		$info['sign_time']=date("Y-m-d",$info['sign_time']);
		$info['pickup_time']=date("Y-m-d",$info['pickup_time']);
		$info['delivery_time']=date("Y-m-d",$info['delivery_time']);  //转换时默认发货时间为当前时间
		$info['payment_time']=date("Y-m-d",$info['payment_time']);
		$info['sales_type']=L('sales_type')[$info['sales_type']];
		$info['purchase_type']=L('purchase_type')[$info['purchase_type']];
		$info['partnername']= M('rbac:adm')->getUserByCol($info['partner']);
		$info['pickuplocation'] = $info['pickup_location'];
		$this->assign('info',$info);//分配订单信息
		$this->assign('detail',json_encode($detailinfo));//明细数据
		$this->assign('order_sn',$order_sn);
		$this->assign('order_type',sget('order_type','i'));
		$this->display('order.update.html');
	}
	/**
	 * 修改订单操作
	 */
	public function editOrderSubmit(){
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //获取UI传递的参数
		if($data['store_o_id']>0) unset($data['store_o_id']);
		if($data['join_id']>0) unset($data['join_id']);
		$data['delivery_location'] =  $data['pickup_location'] = $data['pickuplocation'];
		$data['pickup_time'] = $data['delivery_time'] = strtotime($data['delivery_time']);
		if(empty($data)) $this->error('错误的请求');	
		$data['sign_time']=strtotime($data['sign_time']);
		$data['payment_time']=strtotime($data['payment_time']);
		$data['total_price']=$data['price'];
		$data['financial_records']=2;//财务记录 默认否
		$data['total_num']=$data['num'];
			$this->db->startTrans(); //开启事务
			$update_data=array(
				'update_time'=>CORE_TIME,
				'update_admin'=>$_SESSION['name'],
			);
			try {	
				if(!$this->db->model('order')->where('o_id = '.$data['o_id'])->update($update_data+$data) ) throw new Exception("更新订单失败");//更新订单
				if(!empty($data['detail'])){ 
					foreach ($data['detail'] as $k => $v) {
						if($data['order_type'] == 1){//销售明细
							if( !$this->db->model('sale_log')->where('id = '.$v['id'])->update($update_data+$v)) throw new Exception("更新明细失败");	
						}else{//采购明细
							if( !$this->db->model('purchase_log')->where('id = '.$v['id'])->update($update_data+$v)) throw new Exception("更新明细失败");
						}
					}
				}	
			} catch (Exception $e) {
				$this->db->rollback();
				$this->error($e->getMessage());
			}
			$this->db->commit();
			$this->success();		
	}

	/**
	 * 销售订单生成采购(先销后采)
	 */
	public function changePurchase(){
		$o_id=sget('o_id','i',0);
		if($o_id<1) $this->error('信息错误');
		$order_sn='PO'.genOrderSn();
		$info=$this->db->model('order')->getPk($o_id); //查询订单信息
		$detailinfo=$this->db->model('sale_log')->where('o_id = '.$o_id)->getAll();
		foreach ($detailinfo as &$value) {
			$value['model']=M("product:product")->getModelById($value['p_id']);
			$pinfo=M("product:product")->getFnameByPid($value['p_id']);
			$value['f_name']=$pinfo['f_name'];//根据cid取客户名
			$value['time_price']=$value['number']*$value['unit_price'];
			$value['require_number']=$value['number'];
		}
		if($info['c_id']>0) $c_name = M('user:customer')->getColByName($info['c_id'],"c_name");
		$info['purchase_type']=1;
		$info['c_id']='' ;
		$info['sign_time']=date("Y-m-d",$info['sign_time']);
		$info['pickup_time']=date("Y-m-d",time());
		$info['delivery_time']=date("Y-m-d",time());  //转换时默认发货时间为当前时间
		$info['payment_time']=date("Y-m-d",$info['payment_time']);
		$this->assign('info',$info);//分配订单信息
		$this->assign('detail',json_encode($detailinfo));//明细数据
		$this->assign('order_sn',$order_sn);
		$this->assign('order_type','2');
		$this->assign('sales_type','2');
		$this->display('order.edit.html');
	}
	/**
	 * 新增销售采购订单
	 * @access public 
	 * @return html
	 */
	public function addSubmit() {
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //获取UI传递的参数
		if(empty($data)) $this->error('错误的请求');
		if($data['o_id']>0) $data['join_id']=$data['o_id']; //把销售订单的id 关联到新增的采购订单中
		unset($data['o_id']); //避免和后面的add 订单冲突
		$data['delivery_location'] =  $data['pickup_location'] = $data['pickuplocation'];
		$data['pickup_time'] = $data['delivery_time'] = strtotime($data['delivery_time']);
		$data['sign_time']=strtotime($data['sign_time']);
		$data['payment_time']=strtotime($data['payment_time']);
		$data['financial_records']=2;//财务记录 默认否
		$data['total_price']=$data['price']; //前台计算的所有明细总价
		$data['total_num']=$data['num']; //所有明细总数
		$data['order_source'] = 2; //订单默认来源ERP
		//新增
			$this->db->startTrans(); //开启事务
			$add_data=array(
				'input_time'=>CORE_TIME,
				'input_admin'=>$_SESSION['name'],
				'admin_id'=>$_SESSION['adminid'],
				'customer_manager'=>$_SESSION['adminid'],
				'depart'=>$data['depart']>0 ? $data['depart'] : $_SESSION['depart'],
			);	
			if($data['order_type'] == 1 && $data['sales_type'] == 1){//如果是销售订单(1.先采后销  2.先销后采')
				if(!$data['store_o_id'])  $this->error("采购订单未选择或者错误");//不销库存的订单 不存在此字段
				if(!$this->db->model('order')->where("o_id = {$data['store_o_id']}")->getRow()) $this->error("您选择的采购订单不存在");
			}
			if($data['order_type'] == 2){//如果是销售订单(1.先采后销  2.先销后采')
				if(isset($data['store_o_id'])) unset($data['store_o_id']);
			}
			if(!$this->db->model('order')->add($add_data+$data) ) $this->error("新增订单失败");//新增订单
			$o_id=$this->db->getLastID();//获取新增订单ID
			if(!$o_id) $this->error("新增订单失败");
			if($data['order_type'] == 1 && $data['sales_type'] == 1){ //如果是销售订单(1.先采后销  2.先销后采')
				if(!$data['store_o_id'])  $this->error("采购订单未选择或者错误");//不销库存的订单 不存在此字段
				$this->db->model('order')->wherePk($data['store_o_id'])->update(array('join_id'=>$o_id,));
			}
			if(isset($data['join_id']) && $data['join_id']>0){
				//反向把新增的采购订单id 保存在所关联的销售订单中
				if(!$this->db->model('order')->wherePk($data['join_id'])->update(array('join_id'=>$o_id,))) $this->error("关联的销售订单更新失败");
			}
			if(!empty($data['detail'])){ 
				foreach ($data['detail'] as $k => $v) {
					$detail[$k]=$v;
					$detail[$k]['o_id']=$o_id;
					$detial[$k]['order_sn']=$data['order_sn'];
					$detail[$k]['remainder']=$v['require_number'];
					$detail[$k]['b_number']=$v['require_number'];
					if($data['order_type'] == 1){//销售明细
						$detail[$k]['purchase_price']=$v['m_p_price'];
						$detail[$k]['number']=$v['require_number'];
						if( !$this->db->model('sale_log')->add($add_data+$detail[$k]) ) $this->error("新增明细失败");		
					}else{//采购明细
						if( !$this->db->model('purchase_log')->add($add_data+$detail[$k]) ) $this->error("新增明细失败");
					}
				}
			}
			if($this->db->commit()){
				$this->success();
			}else{
				$this->db->rollback();
				$this->error($this->db->getDbError());
			}
	}


	/**
	 * 销售审核
	 * @access public
	 * @return html
	 */
	public function ordercheck(){
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //获取UI传递的参数
		if(empty($data)) $this->error('错误的操作');
		$_data=array(
			'update_time'=>CORE_TIME,
			'update_admin'=>$_SESSION['name'],
		);
		$this->db->startTrans(); //开启事务
		try {	
			if($data['s_or_p'] == '1'){
				unset($data['sales_type']);
				if( !$re=$this->db->model('order')->where(' o_id = '.$data['o_id'])->update($_data+$data+array('node_flow'=>'+=1')) ) throw new Exception("物流审核失败");
				if( !$re2=$this->db->model('purchase_log')->where(' o_id = '.$data['o_id'])->update(array('order_status'=>2,)+$_data) ) throw new Exception("订单明细审核状态更新失败");
			}else{
				//销售审核通过即锁库存 2:通过  ,  3:不通过
				//检查来源
				$order_source  = $this->db->model('order')->select('order_source')->where(' o_id = '.$data['o_id'])->getOne();
				if($data['order_status'] == '2'){
					if( !$this->db->model('order')->where(' o_id = '.$data['o_id'])->update('order_status = 2 , node_flow=node_flow+1 ') ) throw new Exception("订单审核失败");
					if( !$this->db->model('sale_log')->where(' o_id = '.$data['o_id'])->update('order_status = 2') ) throw new Exception("订单明细审核状态更新失败");
					if($order_source == 2){
						if( $data['sales_type'] != '2' ){ //如果销库存则循环锁定产品库存
							$detail = $this->db->model('sale_log')->select('inlog_id,number')->where(' o_id = '.$data['o_id'])->getAll();
							foreach ($detail as $k => $v) {
							if( !$this->db->model('in_log')->where(' id = '.$v['inlog_id'])->update(' controlled_number = controlled_number - '.$v['number'].' , lock_number = lock_number+'.$v['number']) ) throw new Exception("锁定库存失败!");
							}
						}
					}

				}else if($data['order_status'] == '3'){
					if( !$this->db->model('order')->where(' o_id = '.$data['o_id'])->update('order_status = 3') ) throw new Exception("订单审核失败");				
				}
			}
	
		} catch (Exception $e) {
			$this->db->rollback();
			$this->error($e->getMessage());
		}
		$this->db->commit();
		$this->success('操作成功');
	}
	
	/**
	 * 物流审核
	 * @access public
	 * @return html
	 */
	public function transportcheck(){
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //获取UI传递的参数
		if(empty($data)) $this->error('错误的操作');
		$_data=array(
			'update_time'=>CORE_TIME,
			'update_admin'=>$_SESSION['name'],
		);
		try {
			if( !$this->db->model('order')->where(' o_id = '.$data['o_id'])->update($data+$_data) ) throw new Exception("物流审核失败");
			$transport_status = $data['transport_status'] == '2' ? '1' : '2';
			if( !$this->db->model('order')->where(' join_id = '.$data['o_id'])->update(array('is_join_check'=>$transport_status)+$_data) ) throw new Exception("关联的销售订单接收采购订单状态更新失败");

		} catch (Exception $e) {
			$this->error($e->getMessage());
		}
		$this->success('操作成功');
	}
	/**
	 * 财务记录
	 */
	public function financialCheck(){
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //获取UI传递的参数
		if(empty($data)) $this->error('错误的操作');
		$_data=array(
			'update_time'=>CORE_TIME,
			'update_admin'=>$_SESSION['name'],
		);
		try {
			if( !$this->db->model('order')->where(' o_id = '.$data['o_id'])->update($data+$_data) ) throw new Exception("财务记录更新失败");	
		} catch (Exception $e) {
			$this->error($e->getMessage());
		}
		$this->success('操作成功');
	}	
}