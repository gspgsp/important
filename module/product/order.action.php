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
		$this->assign('invoice_status',L('invoice_status'));        //开票状态
		$this->assign('invoice_one_status',L('invoice_one_status'));    //单笔明细开票状态
		$this->assign('price_type',L('price_type')); //价格单位
		$this->assign('in_storage_status',L('in_storage_status')); //入库状态
		$this->assign('order_type',L('order_type')); //订单类型：
		$this->assign('company_account',L('company_account')); //交易公司账户
		$this->assign('sales_type',L('sales_type')); //销售类型
		$this->assign('purchase_type',L('purchase_type')); //采购类型
		$this->assign('bile_type',L('bile_type'));              //票据类型
		$this->assign('billing_type',L('billing_type'));        //开票类型
		$this->assign('c_fax',L('c_fax'));  //联系传真
		$this->assign('pay_remark',L('pay_remark'));  //联系传真
		$this->assign('admin_id',$_SESSION['admin_id']);
		$this->moreChoice = sget('moreChoice','i',0);
		$this->teams = M('rbac:adm')->getTeam();
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
		$this->assign('order_sn',sget('order_sn','s'));
		$this->assign('o_ids',sget('o_ids','s'));
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
		$this->assign('order_sn',sget('order_sn','s'));
		$this->assign('o_ids',sget('o_ids','s'));
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
		$where.= "1";
		//筛选状态
		$order_sn=sget('order_sn','s');
		if($order_sn)  $where.=" and `order_sn` = '$order_sn' ";
		if(sget('type','i',0) !=0) $order_type=sget('type','i',0);//订单类型
		if(sget('order_type','i',0) !=0) $order_type=sget('order_type','i',0);
		if($order_type !=0)  $where.=" and `order_type` =".$order_type;
		$financial_records=sget('financial_records','s');//抬头
		if($financial_records !='')  $where.=" and `financial_records` = ".$financial_records;
		$order_source=sget('order_source','i',0);//订单来源
		if($order_source !=0)  $where.=" and `order_source` =".$order_source;
		$order_name=sget('order_name','i',0);//订单抬头
		if($order_name !=0)  $where.=" and `order_name` =".$order_name;
		$collection_status=sget('collection_status','i',0);//付款状态
		if($collection_status !=0)  $where.=" and `collection_status` =".$collection_status;
		$invoice_status=sget('invoice_status','i',0);//开票状态
		if($invoice_status !=0)  $where.=" and `invoice_status` =".$invoice_status;
		$pay_method=sget('pay_method','i',0);//付款方式
		if($pay_method !=0)  $where.=" and `pay_method` =".$pay_method;
		$transport_type=sget('transport_type','i',0);//运输方式
		if($transport_type !=0)  $where.=" and `transport_type` =".$transport_type;
		$business_model=sget('business_model','i',0);//业务模式
		if($business_model !=0)  $where.=" and `business_model` =".$business_model;
		$c_id=sget('c_id','i',0);//根据客户id查询
		if($c_id !=0)  $where.=" and `c_id` =".$c_id;
		$transport_status=sget('transport_status','i',0);//物流审核
		if($transport_status !=0)  $where.=" and `transport_status` =".$transport_status;
		$out_storage_status=sget('out_storage_status','i',0);//发货状态
		if($out_storage_status !=0)  $where.=" and `out_storage_status` =".$out_storage_status;
		$in_storage_status=sget('in_storage_status','i',0);//发货状态
		if($in_storage_status !=0)  $where.=" and `in_storage_status` =".$in_storage_status;
		//首页 收、付款，进、销项提前提醒 跳转的查询,传o_id的字符串 如 (1,2,3,4)
		$o_ids=sget('o_ids','s');//
		if($o_ids)  $where.=" and `o_id` in ".$o_ids;
		// p($where);die;
		//筛选时间
		$sTime = sget("sTime",'s','input_time'); //搜索时间类型
		$where.=getTimeFilter($sTime); //时间筛选
		//关键词搜索
		$key_type=sget('key_type','s','order_sn');
		$keyword=sget('keyword','s');
		if(empty($keyword)){
			$order_status=sget('order_status','i',0);//订单审核
			if($order_status !=0){
				$where.=" and `order_status` =".$order_status;
			}else{
				$where.= " and `order_status` <> 3";
			}
		}
		//筛选战队
		if($teamid = sget('team','i',0)){
			$users = M('rbac:adm')->getTeamMembers($teamid);
			$where.=" and `customer_manager`  in ($users)";
		}
		if(!empty($keyword) && $key_type=='input_admin'  ){
			$admin_id = M('rbac:adm')->getAdmin_Id($keyword);
			$where.=" and `customer_manager` = '$admin_id'";
		}elseif(!empty($keyword) && $key_type=='c_id'){
			$keyword=M('product:order')->getOidByCname($keyword);
			$where.=" and `$key_type` in ($keyword) ";
		}elseif(!empty($keyword)){
			$where.=" and `$key_type` like '%".$keyword."%'";
		}
		$orderby = "$sortField $sortOrder";
		if($this->moreChoice == 0){
			//筛选过滤自己的订单信息
			if($_SESSION['adminid'] != 1 && $_SESSION['adminid'] > 0){
				$sons = M('rbac:rbac')->getSons($_SESSION['adminid']);
				if($_SESSION['adminid'] != 10){
					$where .= " and (`customer_manager` in ($sons) or `partner` = {$_SESSION['adminid']})  ";
				}
				//筛选财务
				if(in_array($roleid, array('30','26','27'))){
					 $where .= " and `order_status` = 2 and `transport_status` = 2 ";
				}
			}
		}
		$list=$this->db->where($where)->page($page+1,$size)->order($orderby)->getPage();
		//echo $_SESSION['adminid'];
		//p($list);die;
		foreach($list['data'] as &$v){
			$v['c_name']=  ($v['partner'] == $_SESSION['adminid'] && $v['customer_manager'] != $_SESSION['adminid']) ?  '*******' : M("user:customer")->getColByName($v['c_id']);//根据cid取客户名
			$v['c_status']= M("user:customer")->getColByName($v['c_id'],'status');//根据cid获取用户状态吗
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
			$v['is_supper'] = $_SESSION['adminid']==1 ? 1 : 0;
			if($order_type ==1){ //销售订单
			//判断当前单子是否全部收款
				$ifqbsk = $this->db->model('order')->select("count(0)")->where("o_id={$v['o_id']} AND collection_status=3")->getOne();
				//获取关联的订单号
				$content_id = M('product:order')->getAssociationID($v['o_id']);
				if($content_id>0){
					//通过获取到的关联订单号=>来获取关联订单的开票状态是否全部开票
					$ifqbkp = $this->db->model('order')->select("count(0)")->where("o_id={$content_id}  AND invoice_status=3")->getOne();
					if($ifqbsk==1&&$ifqbkp==1){
						$v['pur_status']=1;
					}else{
						$v['pur_status']=0;
					}
				}else{
					$v['pur_status']=0;
				}
				//还需要判断开票表p2p_billing
				//1.没有开票记录待开票
				if($row_tmp['invoice_status']==1){
					$i = 0;
					$bfkps = $this->db->model('billing')->where("o_id={$v['o_id']} AND invoice_status<>3")->getAll();
					foreach ($bfkps as $k){
							if($k['invoice_status']<>2){
								$v['pur_status']=0;
								break;
							}else{
								$i = $i +1;
							}
						}
						if(($i==count($bfkps))){
							($v['pur_status']==0)?$v['pur_status']=0:$v['pur_status']=1;
						}
				}
				//2.部分开票
				if($row_tmp['invoice_status']==2){
					$i = 0;
					$bfkps = $this->db->model('billing')->where("o_id={$v['o_id']} AND invoice_status<>3")->getAll();
					foreach ($bfkps as $k){
						if($k['invoice_status']<>2){
							$v['pur_status']=0;
							break;
						}else{
							$i = $i +1;
						}
					}
					if($i!=0&&($i==count($bfkps))){
						($v['pur_status']==0)?$v['pur_status']=0:$v['pur_status']=1;
					}
				}
				//3.全部开票
				if($row_tmp['invoice_status']==3){
					$v['pur_status']=0;
				}
			}
			$v['see'] =  ($v['customer_manager'] == $_SESSION['adminid'] ||  in_array($v['customer_manager'], explode(',', $sons)) || $_SESSION['adminid']  == '1') ? '1':'0';
			//获取单笔订单收付款状态
			$m = M("product:collection")->getLastInfo($name='o_id',$value=$v['o_id']);
			$v['one_c_status'] =$m[0]['collection_status'];
			//获取单笔订单是否存在开票状态
			$v['one_b_status']=M("product:billing")->where("o_id={$v['o_id']} and invoice_status=1")->select('id')->getOne();
			//获取订单的发货状态
			$v['ship_status'] = $order_type=='1' ? M('product:saleLog')->checkInLog($v['o_id']) : 0;
		}
		$msg="";
		if($list['count']>0){
			$sum=$this->db->model('order')->select("sum(total_num) as wsum, sum(total_price) as msum")->where($where)->getRow();
			// showtrace();
			$collection = M('product:order')->get_collection($where);
			// p($collection);die();
			$order_type=='1'?$collection_name='收款':$collection_name='付款';
			$msg="[筛选结果]总额:【".price_format($sum['msum'])."】总吨:【".$sum['wsum']."】".$collection_name.":【".price_format($collection)."】";
		}
		$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>$msg);
		$this->json_output($result);
	}
	/**
	* 订单信息
	* @access public
	*/
	public function info(){
		$getReportData = $this->getReportData();//调用adminBase的方法，验证业务员是否设置本月指标
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
		if($info['h_pur_cid']>0) $info['c_name_pur'] = M('user:customer')->getColByName($info['h_pur_cid'],"c_name");
		$info['sign_time']=date("Y-m-d",$info['sign_time']);
		$info['pickup_time']=date("Y-m-d",$info['pickup_time']);
		$info['delivery_time']=date("Y-m-d",$info['delivery_time']);  //转换时默认发货时间为当前时间
		$info['payment_time']=date("Y-m-d",$info['payment_time']);
		$info['sales_type']=L('sales_type')[$info['sales_type']];
		$info['p_type']=$info['purchase_type'];
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
		/**对比重复修改**S**/
		$update_time = sget('update_time','i',0);
		$old_time =$this->db->model('order')->select('update_time,order_status,transport_status')->where('o_id = '.$data['o_id'])->getRow();
		if($old_time['transport_status'] != 1) $this->error('该订单物流已经审核，请刷新后操作');
		if($old_time['order_status'] != 1) $this->error('该订单销售已经审核，请刷新后操作');
		if($old_time['update_time'] !=$update_time) $this->error('订单已经被他人修改或过期，请刷新后从新修改');
		/**对比重复修改**E**/
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
							if($v['require_number']< 0.01) $this->error('牌号吨位有误');//判断修改后的数量是不是不合法
							//判断销售订单的吨位输入是否合法----S
							$order_info = $this->db->model('order')->where('o_id = '.$data['o_id'])->getRow();
							if($order_info['sales_type'] == 1){
								$in_info = $this->db->model('in_log')->where("id = {$v['inlog_id']}")->getRow();
								if($v['require_number'] > $in_info['controlled_number']) $this->error('需求数量超过了剩余可用数量（'.$in_info['controlled_number'].'吨），请检查！');
							}
							//判断销售订单的吨位输入是否合法----E
							if( !$this->db->model('sale_log')->where('id = '.$v['id'])->update($update_data+array('remainder'=>$v['require_number'],'number'=>$v['require_number'],'unit_price'=>$v['unit_price'],'b_number'=>$v['require_number'],))) throw new Exception("更新明细失败");
						}else{//采购明细
							if($v['require_number']< 0.01) $this->error('牌号吨位有误');//判断修改后的数量是不是不合法
							if( !$this->db->model('purchase_log')->where('id = '.$v['id'])->update($update_data+array('number'=>$v['number'],'b_number'=>$v['number'],'remainder'=>$v['number'],'unit_price'=>$v['unit_price'],))) throw new Exception("更新明细失败");
						}
					}
				}
			// showtrace();
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
		$getReportData = $this->getReportData();//调用adminBase的方法，验证业务员是否设置本月指标
		$o_id=sget('o_id','i',0);
		if($o_id<1) $this->error('信息错误');
		$order_sn='PO'.genOrderSn();
		$info=$this->db->model('order')->getPk($o_id); //查询订单信息
		$detailinfo=$this->db->model('sale_log')->where('o_id = '.$o_id)->getAll();
		foreach ($detailinfo as &$value) {
			$value['model']=M("product:product")->getModelById($value['p_id']);
			$pinfo=M("product:product")->getFnameByPid($value['p_id']);
			$value['f_name']=$pinfo['f_name'];//根据cid取客户名
			//下面一行是原始的逻辑--s
			// $value['time_price']=$value['number']*$value['unit_price'];
			//下面一行是原始的逻辑--e
			// 下面两行代码是为了解决销售生成采购时候会带上总价和单价的问题--s
			$value['time_price']=0;
			$value['unit_price'] = 0;
			// 下面两行代码是为了解决销售生成采购时候会带上总价和单价的问题--e
			//最近历史价格
			$value['price_p']=M('product:factory')->getNeighborPprice($value['p_id']);
			$value['require_number']=$value['number'];
		}
		if($info['c_id']>0) $c_name = M('user:customer')->getColByName($info['c_id'],"c_name");
		$info['purchase_type']=1;
		$info['c_id']='' ;
		$info['sign_time']=date("Y-m-d",$info['sign_time']);
		$info['pickup_time']=date("Y-m-d",time());
		$info['delivery_time']=date("Y-m-d",time());  //转换时默认发货时间为当前时间
		$info['payment_time']=date("Y-m-d",$info['payment_time']);
		//如果是代采订单则
		if($info['h_pur']==2){
			$info['h_pur_cname']  = M('user:customer')->getColByName($info['h_pur_cid'],"c_name");
		}
		/**处理采购订单生成时候的信息卸载问题***S***/
		unset($info['freight_price']);
		unset($info['transport_type']);
		unset($info['payment_way']);
		unset($info['payment_time']);
		unset($info['transport_status']);
		unset($info['remark']);
		unset($info['pickup_time']);
		unset($info['sign_place']);
		unset($info['delivery_time']);
		unset($info['pay_method']);
		unset($info['c_fax']);
		unset($info['sign_time']);
		unset($info['pickuplocation']);
		/**处理采购订单生成时候的信息卸载问题****E****/
		$this->assign('info',$info);//分配订单信息
		$this->assign('detail',json_encode($detailinfo));//明细数据
		$this->assign('order_sn',$order_sn);
		$this->assign('order_type','2');
		$this->assign('sales_type','2');
		$this->assign('h_pur',$info['h_pur']);
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
			if(!$this->db->model('order')->add($add_data+$data)) $this->error("新增订单失败2");//新增订单
			$o_id=$this->db->getLastID();//获取新增订单ID
			if(!$o_id) $this->error("新增订单失败1");
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
					if(intval($v['unit_price']) < 0.1) $this->error('订单详情价格异常，请修改');
					$detail[$k]['purchase_price']=empty($v['m_p_price']) ? 0 : $v['m_p_price'];
					if($data['order_type'] == 1){//销售明细
						$detail[$k]['number']=$v['require_number'];
						$detail[$k]['purchase_id']= empty($v['purchase_id']) ? 0 : $v['purchase_id'];
						if( !$this->db->model('sale_log')->add($detail[$k]+$add_data)) $this->error("新增明细失败1");
					}else{//采购明细
						// $detail[$k]['sale_log_id']= empty($v['id']) ? 0 : $v['id'];
						$inc = $v['id'];
						unset($detail[$k]['id']);
						if( !$this->db->model('purchase_log')->add($detail[$k]+$add_data)) $this->error("新增明细失败2");
						if($data['purchase_type']==1 && $data['order_type']==2){
							$purchase_id = $this->db->getLastID();
							$this->db->model('sale_log')->where("id=$inc")->update(array('purchase_id'=>$purchase_id));
						}
					}
				}
			}
			if($this->db->commit()){
				//添加订单可视化
				M('order:orderLog')->addLog($o_id);
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
			if($data['s_or_p'] == '1' && $data['order_status'] == '2'){
				unset($data['sales_type']);
				if( !$re=$this->db->model('order')->where(' o_id = '.$data['o_id'])->update($_data+$data+array('node_flow'=>'+=1','order_remark'=>$data['order_remark'],)) ) throw new Exception("物流审核失败");
				if( !$re2=$this->db->model('purchase_log')->where(' o_id = '.$data['o_id'])->update(array('order_status'=>2,)+$_data) ) throw new Exception("订单明细审核状态更新失败");
				//添加订单可视化订单审核过
				M('order:orderLog')->addLog($data['o_id'],1,0,CORE_TIME-intval($this->db->model('order')->select('input_time')->where(' o_id = '.$data['o_id'])->getOne()));
			}elseif($data['s_or_p'] == '' && $data['order_status'] == '2'){
				//销售审核通过即锁库存 2:通过  ,  3:不通过
				$order_source  = $this->db->model('order')->select('order_source')->where(' o_id = '.$data['o_id'])->getOne();
					if( !$this->db->model('order')->where(' o_id = '.$data['o_id'])->update("order_status = 2 , node_flow=node_flow+1, order_remark='{$data['order_remark']}'") ) throw new Exception("订单审核失败");
					if( !$this->db->model('sale_log')->where(' o_id = '.$data['o_id'])->update('order_status = 2') ) throw new Exception("订单明细审核状态更新失败");
					if($order_source == 2){
						if( $data['sales_type'] != '2' ){ //如果销库存则循环锁定产品库存
							$detail = $this->db->model('sale_log')->select('inlog_id,number')->where(' o_id = '.$data['o_id'])->getAll();
							foreach ($detail as $k => $v) {
							if( !$this->db->model('in_log')->where(' id = '.$v['inlog_id'])->update(' controlled_number = controlled_number - '.$v['number'].' , lock_number = lock_number+'.$v['number']) ) throw new Exception("锁定库存失败!");
							}
						}
					}
				//添加订单可视化订单审核过
				M('order:orderLog')->addLog($data['o_id'],1,0,CORE_TIME-intval($this->db->model('order')->select('input_time')->where(' o_id = '.$data['o_id'])->getOne()));
			}
			 if($data['order_status'] == '3'){
				if( !$this->db->model('order')->where(' o_id = '.$data['o_id'])->update('order_status = 3') ) throw new Exception("订单审核失败");
				if($data['s_or_p'] == '1'){
					if( !$this->db->model('purchase_log')->where(' o_id = '.$data['o_id'])->update('order_status = 3') ) throw new Exception("订单明细审核状态更新失败");
				}else{
					if( !$this->db->model('sale_log')->where(' o_id = '.$data['o_id'])->update('order_status = 3') ) throw new Exception("订单明细审核状态更新失败");
				}
				//添加订单可视化订单审不通过
				M('order:orderLog')->addLog($data['o_id'],3,0,CORE_TIME-intval($this->db->model('order')->select('input_time')->where(' o_id = '.$data['o_id'])->getOne()));
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
			'transport_time'=>CORE_TIME,
			'update_admin'=>$_SESSION['name'],
		);
		try {
			if( !$this->db->model('order')->where(' o_id = '.$data['o_id'])->update($data+$_data) ) throw new Exception("物流审核失败");
			$transport_status = $data['transport_status'] == '2' ? '1' : '2';
			if( !$this->db->model('order')->where(' join_id = '.$data['o_id'])->update(array('is_join_check'=>$transport_status)+$_data) ) throw new Exception("关联的销售订单接收采购订单状态更新失败");
			//添加订单可视化 1审核过 2审核不过
			M('order:orderLog')->addLog($data['o_id'],$transport_status = $transport_status==2 ? 3 : 2,0,CORE_TIME-intval($this->db->model('order')->select('input_time')->where(' o_id = '.$data['o_id'])->getOne()));

			if($transport_status==2 && (M('product:order')->getColByName($data['o_id'],'order_type')==2)){
				$res= M('user:customer')->updateCreditLimit($data['o_id'],$transport_status,'-');
				if($res!=1) throw new Exception('可用额度更新失败');
			}
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
	/**
	 * 订单撤销
	 */
	public function orderBack(){
		$this->is_ajax=true; //指定为Ajax输出
		$ids=sget('ids','s');
		if(empty($ids)){
			$this->error('操作有误');
		}
		$vas = explode(',', $ids);
		$this->db->startTrans();
		//查询订单类型
		foreach ($vas as $k => $v) {
			$order_info = $this->db->model('order')->where("o_id = $v")->getRow();
			//如果是销售订单
			if($order_info['order_type']==1){
				//查询订单存在出库的信息
				$sale_logs = $this->db->model('sale_log')->where("o_id = $v")->getAll();
				foreach ($sale_logs as $key => $val) {
					$exit = $this->db->model('out_logs')->where("sale_id = {$val['id']}")->getRow();
					if(!empty($exit)) $this->error('存在已经出库的订单流水记录，出库流水id为【'.$exit['id'].'】');
				}
				//查询出库的out_log
				$outlog = $this->db->model('out_log')->where("o_id = $v")->getRow();
				if(!empty($outlog)) $this->error('存在出库记录,出库记录id为：【'.$outlog['id'].'】');
				if($order_info['sales_type']==2){  //1.先采后销  2.先销后采'
					if($order_info['join_id']>0){
						$join = $this->db->model('order')->where("o_id = {$order_info['join_id']}")->getRow();
						if(!empty($join)) $this->error("此订单为先销后采订单，请先删除采购订单方能删除销售订单</br>，采购订单号为：".$join['order_sn']);
					}
				}else{
					if($order_info['order_status']==2){//如果销库存则循环锁定产品库存
						$detail = $this->db->model('sale_log')->select('inlog_id,number')->where("o_id = $v")->getAll();
						foreach ($detail as $va) {
							if(!$this->db->model('in_log')->where(' id = '.$va['inlog_id'])->update(array('controlled_number'=>'+='.$va['number'],'lock_number'=>'-='.$va['number']))){
								$this->error('库存锁定撤销失败');
							}
						}
					}
				}
				//如果不存在任何记录就开始操作
				$this->db->model('order')->where("o_id = $v")->delete();
				$this->db->model('sale_log')->where("o_id = $v")->delete();
			}elseif($order_info['order_type']==2){
				//查询订单存在的明细
				$sale_logs = $this->db->model('purchase_log')->where("o_id = $v")->getAll();
				foreach ($sale_logs as $key => $val) {
					$exit = $this->db->model('in_logs')->where("purchase_id = {$val['id']}")->getRow();
					if(!empty($exit)) $this->error('存在已经入库的订单流水，入库流水id为【'.$exit['id'].'】');
				}
				//查询入库的in_log
				$inlog = $this->db->model('in_log')->where("o_id = $v")->getRow();
				if(!empty($inlog)) $this->error('存在出库记录,出库记录id为：【'.$inlog['id'].'】');
				if($order_info['purchase_type']==1){  //1.先采后销  2.先销后采'
					if($order_info['join_id']>0){
						$this->db->model('order')->where("o_id = {$order_info['join_id']}")->update(array('join_id'=>0,'update_time'=>CORE_TIME,));
					}
				}
				//如果不存在任何记录就开始操作
				$this->db->model('order')->where("o_id = $v")->delete();
				$this->db->model('purchase_log')->where("o_id = $v")->delete();
			}
			//添加订单可视化订单撤销
			M('order:orderLog')->addLog($v,3);
		}

		if($this->db->commit()){
			$this->success('撤销成功');
		}else{
			$this->db->rollback();
			$this->error('撤销失败');
		}
	}
	/**
	 * 获取订单流程的页面展示
	 */
	public function getFlow(){
		$oid = sget('o_id','i',0);
		//信息
		$this->assign('info1',M('order:orderLog')->getLog($oid,0,0));
		$this->assign('info2',M('order:orderLog')->getLog($oid,0,1));
		$this->assign('info3',M('order:orderLog')->getLog($oid,0,2));
		$this->assign('info4',M('order:orderLog')->getLog($oid,0,3));
		//物流
		$this->assign('ship1',M('order:orderLog')->getLog($oid,1,0));
		$this->assign('ship2',M('order:orderLog')->getLog($oid,1,1));
		$this->assign('ship3',M('order:orderLog')->getLog($oid,1,2));
		$this->assign('ship4',M('order:orderLog')->getLog($oid,1,3));
		//资金
		$this->assign('fund2',M('order:orderLog')->getLog($oid,2,1));
		$this->assign('fund3',M('order:orderLog')->getLog($oid,2,2));
		$this->assign('fund4',M('order:orderLog')->getLog($oid,2,3));
		//发票
		$this->assign('tick2',M('order:orderLog')->getLog($oid,3,1));
		$this->assign('tick3',M('order:orderLog')->getLog($oid,3,2));
		$this->assign('tick4',M('order:orderLog')->getLog($oid,3,3));
		//订单类型 1销售 2采购
		$this->assign('type',M('product:order')->getColByName($oid,'order_type'));
		//订单创建
		$this->display('order.flow.html');
	}
}