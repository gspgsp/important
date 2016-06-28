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
		$this->assign('invoice_status',L('invoice_status')); //开票状态
		$this->assign('price_type',L('price_type')); //价格单位
		$this->assign('in_storage_status',L('in_storage_status')); //入库状态
		$this->assign('order_type',L('order_type')); //订单类型：
		$this->assign('company_account',L('company_account')); //交易公司账户
		$this->assign('sales_type',L('sales_type')); //销售类型
		$this->assign('purchase_type',L('purchase_type')); //采购类型
		$this->assign('bile_type',L('bile_type'));		 	 	//票据类型
		$this->assign('billing_type',L('billing_type'));    	//开票类型
		
	}
	/**
	 *
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
		$sortField = sget("sortField",'s','input_time'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
		//筛选
		$where.= 1;
		//筛选状态
		if(sget('type','i',0) !=0) $order_type=sget('type','i',0);//订单类型
		if(sget('order_type','i',0) !=0) $order_type=sget('order_type','i',0);
		if($order_type !=0)  $where.=" and `order_type` =".$order_type;
		$order_source=sget('order_source','i',0);//订单来源
		if($order_source !=0)  $where.=" and `order_source` =".$order_source;
		$pay_method=sget('pay_method','i',0);//付款方式
		if($pay_method !=0)  $where.=" and `pay_method` =".$pay_method;
		$transport_type=sget('transport_type','i',0);//运输方式
		if($transport_type !=0)  $where.=" and `transport_type` =".$transport_type;
		$business_model=sget('business_model','i',0);//业务模式
		if($business_model !=0)  $where.=" and `business_model` =".$business_model;
		$order_status=sget('order_status','i',0);//订单审核
		if($order_status !=0)  $where.=" and `order_status` =".$order_status;
		$transport_status=sget('transport_status','i',0);//物流审核
		if($transport_status !=0)  $where.=" and `transport_status` =".$transport_status;
		$goods_status=sget('goods_status','i',0);//发货状态
		if($goods_status !=0)  $where.=" and `order_source` =".$goods_status;
		//筛选时间
		$sTime = sget("sTime",'s','input_time'); //搜索时间类型
		$where.=getTimeFilter($sTime); //时间筛选
		//关键词搜索
		$key_type=sget('key_type','s','order_sn');
		$keyword=sget('keyword','s');
		if(!empty($keyword) && $key_type=='order_name'  ){
			$where.=" and `$key_type`  like '%$keyword%' ";
		}elseif(!empty($keyword) && $key_type=='c_id'){
			$keyword=M('product:order')->getOidByCname($keyword);
			$where.=" and `$key_type` in ('$keyword') ";
		}elseif(!empty($keyword)){
			$where.=" and `$key_type`  = '$keyword' ";
		}
		$list=$this->db->where($where)
				->page($page+1,$size)
				->order("$sortField $sortOrder")
				->getPage();

		foreach($list['data'] as &$v){
			$v['c_name']=M("user:customer")->getColByName($v['c_id']);//根据cid取客户名
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
			$v['payments_status']= ( $v['order_type'] == '1' ? L('gatheringt_status')[$v['collection_status']] :  L('payment_status')[$v['collection_status']] ) ;
			$v['order_type']=L('order_type')[$v['order_type']];
			$v['out_storage_status']=L('out_storage_status')[$v['out_storage_status']];
			$v['invoice_status']=L('invoice_status')[$v['invoice_status']];
			$v['type_status']= L('order_status')[$v['order_status']].'|'.L('transport_status')[$v['transport_status']];
			$v['node_flow'] = $this->_accessChk($this->db->model('order')->select('node_flow')->where("`o_id` ={$v['o_id']} ")->getOne());

			//获取采购订单开票状态
			if(!empty($v['store_o_id'])){
				$v['newstatus'] = M("product:order")->getColByName($value=$v['store_o_id'],$col='invoice_status',$condition='o_id');
			}
			if(!empty($v['join_id'])){
				$v['newstatus'] = M("product:order")->getColByName($value=$v['join_id'],$col='invoice_status',$condition='o_id');
			}
		}
		$result=array('total'=>$list['count'],'data'=>$list['data']);
		$this->json_output($result);
	}
	/**
	* 订单信息
	* @access public
	*/
	public function info(){
		$o_id=sget('oid','i',0);
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
			$this->assign('order_type',$order_type);
			$this->display('order.edit.html');
			exit;
		}
		$info=$this->db->getPk($o_id); //查询订单信息
		if(empty($info)) $this->error('错误的订单信息');	
		if($info['c_id']>0) $c_name = M('user:customer')->getColByName($info['c_id'],"c_name");
		$info['order_name']=L('company_account')[$info['order_name']];
		$info['sign_time']=date("Y-m-d",$info['sign_time']);
		$info['pickup_time']=date("Y-m-d",$info['pickup_time']);
		$info['delivery_time']=date("Y-m-d",$info['delivery_time']);
		$info['payment_time']=date("Y-m-d",$info['payment_time']);
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
		$info['delivery_time']=date("Y-m-d",time());  //转换时默认发货时间为当前时间
		$info['payment_time']=date("Y-m-d",$info['payment_time']);
		$info['sales_type']=L('sales_type')[$info['sales_type']];
		$info['purchase_type']=L('purchase_type')[$info['purchase_type']];
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
		// p($data);
		if(empty($data)) $this->error('错误的请求');	
		$data['sign_time']=strtotime($data['sign_time']);
		$data['pickup_time']=strtotime($data['pickup_time']);
		$data['delivery_time']=strtotime($data['delivery_time']);
		$data['payment_time']=strtotime($data['payment_time']);
		$data['total_price']=$data['price'];
		$data['total_num']=$data['num'];
			$this->db->startTrans(); //开启事务
			$update_data=array(
				'update_time'=>CORE_TIME,
				'update_admin'=>$_SESSION['name'],
			);
			try {	
				if( !$this->db->model('order')->where('o_id = '.$data['o_id'])->update($data+$update_data) ) throw new Exception("更新订单失败");//更新订单
				if(!empty($data['detail'])){ 
					foreach ($data['detail'] as $k => $v) {
						$detail[$k]['unit_price']=$v['unit_price'];
						if($data['order_type'] == 1){//销售明细
							if( !$this->db->model('sale_log')->where('o_id = '.$data['o_id'])->update($detail[$k]+$update_data) ) throw new Exception("更新明细失败");		
						}else{//采购明细
							if( !$this->db->model('purchase_log')->where('o_id = '.$data['o_id'])->update($detail[$k]+$update_data) ) throw new Exception("更新明细失败");
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
	* 付款收款开票信息
	* @access public
	*/
	public function transactionInfo(){
		$o_id=sget('o_id','i',0);
		$type=sget('order_type','s');

		//获取开票总金额
		// if($type==1){
		// 	//$sum=$this->db->model('sale_log')->where("o_id=$o_id")->select("sum(number*unit_price)")->getOne();
		// 	$unit_price=$this->db->model('sale_log')->where("o_id=$o_id")->select("unit_price")->getOne();
		// }else{
		// 	//$sum=$this->db->model('purchase_log')->where("o_id=$o_id")->select("sum(number*unit_price)")->getOne();
		// 	$unit_price=$this->db->model('purchase_log')->where("o_id=$o_id")->select("unit_price")->getOne();
		// }
		// $this->assign('unit_price',$unit_price);
		
		if(empty($o_id)) $this->error('信息错误');	
		$data      = M('product:order')->getAllByName($value=$o_id,$condition='o_id');
		$c_info    = M('user:customer')->getCinfoById($data[0][c_id]);//获取公司所有信息
		$user_name = M('rbac:adm')->getUserInfoById($data[0][admin_id]);//获取前台添加的业务员名字
		$username  = $user_name[username];
		
		//订单中没有业务员id就传input_admin过去
		if (empty($username)) {
			$this->assign('input_admin',$data[0][input_admin]);
		}else{
			$this->assign('input_admin',$username);
		}
		//传递表头信息
		$this->assign('p_method',$data[0][pay_method]);
		$this->assign('order_name',$data[0][order_name]);
		$this->assign('c_name',$c_info[c_name]);
		$this->assign('c_id',$data[0][c_id]);
		$this->assign('type',$type);
		$this->assign('o_id',$o_id);
		$this->assign('price',$data[0]['total_price']);
		$this->assign('order_sn',$data[0][order_sn]);

		//获取是开票还是收付款
		$invoice=sget('invoice','i');
		//获取是不是财务审核
		$finance=sget('finance','i');
		//p($finance);die;
		if($invoice==1){
			//发送开票公司信息
			$this->assign('tax_id',$c_info['tax_id']);
			$this->assign('invoice_address',$c_info['invoice_address']);
			$this->assign('invoice_tel',$c_info['invoice_tel']);
			$this->assign('invoice_bank',$c_info['invoice_bank']);
			$this->assign('invoice_account',$c_info['invoice_account']);

			$this->assign('bile_type',L('bile_type'));//票据类型

			if ($finance ==1 ) {
				//获取要审核的开票信息的id，传送出信息
				$id = sget('id','i',0);
				$this->assign('finance',$finance);
				$this->assign('id',$id);

				$res = M('product:billing')->where('id='.$id)->getAll();
            	if($res){
            		$un_price = $res[0]['billing_price']+$res[0]['unbilling_price'];
					$this->assign('b_price',$res[0]['billing_price']);
					$this->assign('u_price',$un_price);
				}
			}else{
				//获取最后一条开票信息
				$res=M('product:billing')->getLastInfo($name='o_id',$value=$data[0][o_id]);
				if($res){
					$this->assign('unbilling_price',$res[0]['unbilling_price']);
				}
			}
			
			$this->display('billing.add.html');
			
		}else{
			if ($finance ==1 ) {
				//获取要审核的收付款的id，传送出信息
				$id = sget('id','i',0);
				$this->assign('finance',$finance);
				$this->assign('id',$id);

				$res = M('product:collection')->where('id='.$id)->getAll();
            	if($res){
            		$un_price = $res[0]['collected_price']+$res[0]['uncollected_price'];
					$this->assign('c_price',$res[0]['collected_price']);
					$this->assign('u_price',$un_price);
				}
			}else{
				//获取最后一条收付款信息	
				$res = M('product:collection')->getLastInfo($name='o_id',$value=$data[0][o_id]);
				if($res){
					$this->assign('total_price',$res[0]['total_price']);
					$this->assign('uncollected_price',$res[0]['uncollected_price']);
				}
			}
			$this->display('collection.add.html');
		}
		
	}

	/**
	* 保存付款收款开票信息
	*/
	public function ajaxSave(){
		$data = sdata();
		//p($data);die;
		$billing = sget('do','i');
		if($billing >0){
			//保存开票信息
			$detail = $data['detail'];
			unset($data['detail']);
			// p($detail);
			// p($data);
				!empty($data['unbilling_price'])?$m = ($data['unbilling_price']-$data['billing_price']):$m = ($data['total_price']-$data['billing_price']);
			$this->db->startTrans();//开启事务	
			try {

				if($m>0){
					$data['unbilling_price'] = $m;
					$data['invoice_status'] = 2;
					if( !$sss=$this->db->model('order')->where('o_id='.$data['o_id'])->update(array('invoice_status'=>2,'update_time'=>CORE_TIME)) )throw new Exception("跟新订单开票状态失败1");				
				}
				if($m==0){
					$data['unbilling_price'] = 0;
					$data['invoice_status'] = 2;
					if( !$this->db->model('order')->where('o_id='.$data['o_id'])->update(array('invoice_status'=>3,'update_time'=>CORE_TIME)) )throw new Exception("跟新订单开票状态失败2");
				}
				if($m<0){
					$this->error("数据错误");
				}

				//判断生成开票号
				$date=date("Ymd").str_pad(mt_rand(0, 100), 3, '0', STR_PAD_LEFT);
				$data['billing_type']==1?($data['billing_sn']= 'sk'.$date):($data['billing_sn']= 'pk'.$date);
				$data['payment_time']=strtotime($data['payment_time']);

				//财务审核
				if($data['finance'] ==1){
					//修改订单明细表中的开票数量
					foreach ($detail as $v) {
						if ($billing ==1) {
							$this->db->model('sale_log')->where('id='.$v['id'])->update("`billing_number`=billing_number+".$v['number']);
						}else{
							$this->db->model('purchase_log')->where('id='.$v['id'])->update("`billing_number`=billing_number+".$v['number']);
						}
					}
					//财务审核通过，更改开票数据
					if(!$this->db->model('billing')->where('id='.$id)->update($data+array('update_time'=>CORE_TIME, 'admin_id'=>$_SESSION['adminid']))) throw new Exception("交易失败");
				}else{
					if(!$this->db->model('billing')->add($data+array('input_time'=>CORE_TIME, 'admin_id'=>$_SESSION['adminid'])) )throw new Exception("开票失败");
				}
			} catch (Exception $e) {
				$this->db->rollback();
				$this->error('保存失败：'.$this->db->getDbError());
			}

		}else{
			//保存收付款相关信息
			if(empty($data['uncollected_price'])){
				$this->db->model('order')->where('o_id='.$data['o_id'])->update('total_price ='.$data['total_price'].',invoice_status=1');
				$m = $data['total_price']-$data['collected_price'];
			}else{
				$m = $data['uncollected_price']-$data['collected_price'];
			}

			$this->db->startTrans();//开启事务

			try {
				if($data['finance'] ==1){
					if($m>0){
						$data['uncollected_price'] = $m;
						$data['collection_status'] = 2;
						if(!$this->db->model('order')->where('o_id='.$data['o_id'])->update(array('collection_status'=>2,'update_time'=>CORE_TIME))) throw new Exception("跟新订单交易状态失败1");
					}
					
					if($m==0){
						$data['uncollected_price'] = 0;
						$data['collection_status'] = 2;
						if(!$this->db->model('order')->where('o_id='.$data['o_id'])->update(array('collection_status'=>3,'update_time'=>CORE_TIME))) throw new Exception("跟新订单交易状态失败2");
					}
					if($m<0){
						$this->error("数据错误");
					}
					$data['payment_time']=strtotime($data['payment_time']);
					$id = $data['id'];
					unset($data['id']);
					if(!$re=$this->db->model('collection')->where('id='.$id)->update($data+array('update_time'=>CORE_TIME, 'admin_id'=>$_SESSION['adminid']))) throw new Exception("交易失败");
				}else{
					$data['uncollected_price'] = $m;
					if(!$re=$this->db->model('collection')->add($data+array('input_time'=>CORE_TIME, 'admin_id'=>$_SESSION['adminid']))) throw new Exception("交易失败");
				}
			} catch (Exception $e) {
				$this->db->rollback();
				$this->error('保存失败：'.$this->db->getDbError());
			}

		}
		$this->db->commit();
		$this->success('操作成功');
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
		$data['join_id']=$data['o_id']; //把销售订单的id 关联到新增的采购订单中
		unset($data['o_id']); //避免和后面的add 订单冲突
		$data['sign_time']=strtotime($data['sign_time']);
		$data['pickup_time']=strtotime($data['pickup_time']);
		$data['delivery_time']=strtotime($data['delivery_time']);
		$data['payment_time']=strtotime($data['payment_time']);
		$data['total_price']=$data['price']; //前台计算的所有明细总价
		$data['total_num']=$data['num']; //所有明细总数
		$data['order_source'] = 2; //订单默认来源ERP
		//新增
			$this->db->startTrans(); //开启事务
			$add_data=array(
				'input_time'=>CORE_TIME,
				'input_admin'=>$_SESSION['name'],
				'admin_id'=>$_SESSION['adminid'],
			);
			try {	
				if($data['join_id']>0) unset($data['store_o_id']); //不销库存的订单 不存在此字段
				if( !$this->db->model('order')->add($data+$add_data) ) throw new Exception("新增订单失败");//新增订单
				$o_id=$this->db->getLastID(); //获取新增订单ID
				if($data['join_id']>0){  //反向把新增的采购订单id 保存在所关联的销售订单中
					if( !$this->db->model('order')->where(' o_id ='.$data['join_id'])->update(' join_id = '.$o_id) ) throw new Exception("关联的销售订单更新失败");	
				}
				if( !$o_id ) throw new Exception("新增订单失败");
				if(!empty($data['detail'])){ 
					foreach ($data['detail'] as $k => $v) {
						$detail[$k]=$v;
						$detail[$k]['o_id']=$o_id;
						$detial[$k]['order_sn']=$data['order_sn'];
						$detail[$k]['remainder']=$v['require_number'];
						if($data['order_type'] == 1){//销售明细
							$detail[$k]['number']=$v['require_number'];
							if( !$this->db->model('sale_log')->add($detail[$k]+$add_data) ) throw new Exception("新增明细失败");		
						}else{//采购明细
							if( !$this->db->model('purchase_log')->add($detail[$k]+$add_data) ) throw new Exception("新增明细失败");
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
	 * 销售审核
	 * @access public
	 * @return html
	 */
	public function ordercheck(){
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //获取UI传递的参数
		if(empty($data)){
			$this->error('错误的操作');
		}
		$_data=array(
			'update_time'=>CORE_TIME,
			'update_admin'=>$_SESSION['name'],
		);
		$this->db->startTrans(); //开启事务
		try {	
			if($data['s_or_p'] == '1'){
				unset($data['sales_type']);
				if( !$re=$this->db->model('order')->where(' o_id = '.$data['o_id'])->update($_data+$data) ) throw new Exception("物流审核失败");
				if( !$re2=$this->db->model('purchase_log')->where(' o_id = '.$data['o_id'])->update(array('order_status'=>2)+$_data) ) throw new Exception("订单明细审核状态更新失败");
			}else{
				//销售审核通过即锁库存 2:通过  ,  3:不通过
				//检查来源
				$order_source  = $this->db->model('order')->select('order_source')->where(' o_id = '.$data['o_id'])->getOne();
				if($data['order_status'] == '2'){
					if( !$this->db->model('order')->where(' o_id = '.$data['o_id'])->update('order_status = 2 , node_flow=node_flow+1 ') ) throw new Exception("订单审核失败");
					// showtrace();
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
					if( !$this->db->model('order')->where(' o_id = '.$data['o_id'])->update('order_status = 2') ) throw new Exception("订单审核失败");				
				}
			}
	
		} catch (Exception $e) {
			// showtrace();
			$this->db->rollback();
			$this->error($e->getMessage());
		}
			// showtrace();

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
		if(empty($data)){
			$this->error('错误的操作');
		}
		$_data=array(
			'update_time'=>CORE_TIME,
			'update_admin'=>$_SESSION['name'],
		);
		try {
			if( !$this->db->model('order')->where(' o_id = '.$data['o_id'])->update($data+$_data) ) throw new Exception("物流审核失败");	
			// if( !$this->db->model('order')->where(' join_id = '.$data['o_id'])->update('is_join_in = 1') ) throw new Exception("关联的销售订单更新失败");
		} catch (Exception $e) {
			$this->db->rollback();
			$this->error($e->getMessage());
		}
		$this->success('操作成功');
	}

	public function pdf(){
		$oid = sget('oid','i',0);
		if($oid>0){
			$orderLists=$this->db->from('order as o')->leftjoin('customer as c','c.c_id=o.c_id')->select('o.order_sn,o.total_price,o.pay_method,o.pickup_location,o.delivery_location,o.sign_time,o.sign_place,o.transport_type,o.payment_time,o.pickup_time,c.c_name')->where('o_id='.$oid)->getRow();
			$detiless=$this->db->from('sale_log as s')
					->leftjoin('product as p','p.id=s.p_id')
					->leftjoin('factory as f','p.f_id=f.fid')
					->select('s.o_id,s.number,s.unit_price,p.model,p.product_type,f.f_name')
					->where('s.o_id='.$oid)->getAll();
		}
		$orderList=!empty($orderLists)?$orderLists:false;
		$detiles=!empty($detiless)?$detiless:false;
		//提货日期
		$pickup_time=!empty($orderLists['pickup_time'])?date('Y年m月d日',$orderLists['pickup_time']):'-';
		//签约时间
		$sign_time=!empty($orderLists['sign_time'])?date('Y-m-d',$orderLists['sign_time']):'-';
		//付款时间
		$payment_time=!empty($orderLists['payment_time'])?date('Y-m-d',$orderList['payment_time']):'-';
		foreach($detiles as $k => $v){
			$detail_info .= '<tr >
				<td  width="120" align="center">'.L('product_type')[$v['product_type']].'</td>
				<td  width="140" align="center">'.$v['model'].'</td>
				<td  width="80"  align="center">'.$v['f_name'].'</td>
				<td  width="80"  align="center">吨</td>
				<td  width="80"  align="center">'.$v['number'].'</td>
				<td  width="80"  align="center">'.$v['unit_price'].'</td>
				<td  width="80"  align="center">'.$v['number']*$v['unit_price'].'</td>
			</tr >';
		}
		E('TCPdf',APP_LIB.'extend');
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->SetTitle('上海中晨电商合同报表');
		$pdf->SetHeaderData('config/pdflogo.jpg', 33, '','', array(0,33,43), array(0,64,128));
		// 设置默认等宽
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		// 设置间距
		$pdf->SetMargins(15, 25, 15);
		$pdf->SetHeaderMargin(5);
		$pdf->SetFooterMargin(10);
		// 设置分页
		$pdf->SetAutoPageBreak(TRUE, 25);
		$pdf->setImageScale(1.25);
		$pdf->setFontSubsetting(true);
		// 设置中文字体
		$pdf->SetFont('stsongstdlight','', 10);
		$pdf->AddPage();
		$total=$this->_cny($orderList['total_price']);
		$table1='<tr >
			<td  width="120" align="center"><b>产品名称</b></td>
			<td  width="140" align="center" ><b>规格/规格</b></td>
			<td  width="80"  align="center"><b>产地</b></td>
			<td  width="80"  align="center"> <b>单位</b></td>
			<td  width="80"  align="center"><b>数量</b></td>
			<td  width="80"  align="center"><b>单价</b></td>
			<td  width="80"  align="center"><b>金额</b></td>
			</tr>' .$detail_info. '<tr >
			<td  width="120"  align="center">合计</td>
			<td  width="540" colspan="6" align="left">'.$orderList['total_price'].'</td>
			</tr>
			<tr >
			<td  width="120" align="center">合计人民币(大写)</td>
			<td  width="540" colspan="6" align="left">'.$total.'整</td>
			</tr>';
		$table2='<tr >
			<td  width="140" align="center">甲方(签章):</td>
			<td  width="140" align="center">'.上海梓晨实业有限公司.'</td>
			<td  width="140" align="center">乙方(签章):</td>
			<td  width="200" align="center">'.$orderList['c_name'].'</td>
		</tr>
		<tr >
			<td  width="140" align="center">法人:</td>
			<td  width="140" align="center">'.李铁道.'</td>
			<td  width="140" align="center">法人:</td>
			<td  width="140" align="center"></td>
		</tr>
		<tr >
			<td  width="140"align="center">经办人:</td>
			<td  width="140" align="center"></td>
			<td  width="140" align="center">经办人:</td>
			<td  width="140"align="center"></td>
		</tr >
		<tr>
			<td  width="140" align="center">传真:</td>
			<td  width="140" align="center">010-123456789</td>
			<td  width="140" align="center">传真:</td>
			<td  width="140" align="center">020-98765432</td>
		 </tr>';
		$location=!empty($orderList['pickup_location'])?$orderList['pickup_location']:$orderList['delivery_location'];
		$str = sprintf(L('htmls.html'),(L('transport_type')[$orderList['transport_type']]),'上海梓晨实业有限公司',$orderList['order_sn'],$orderList['c_name'],$orderList['sign_place'],$sign_time,$table1,$location,$pickup_time,(L('transport_type')[$orderList['transport_type']]),(L('pay_method')[$orderList['pay_method']]),$payment_time,'提前付清全款',$table2);
		//echo $str;
		$pdf->writeHTMLCell(0, 0, '', '', $str, 0, 1, 0, true, '', true);
		// 输出pdf
		$pdf->Output("{$orderLists['order_sn']}.pdf", 'I');
		}
		/**
		 * 人民币转文字
		 * @Author   cuiyinming
		 * @DateTime 2016-06-16T12:17:28+0800
		 * @param    [type]                   $ns [description]
		 * @return   [type]                       [description]
		 */
		private function _cny($ns){
			static $cnums = array("零","壹","贰","叁","肆","伍","陆","柒","捌","玖"),
			$cnyunits = array("圆","角","分"),
			$grees = array("拾","佰","仟","万","拾","佰","仟","亿");
			list($ns1,$ns2) = explode(".",$ns,2);
			$ns2 = array_filter(array($ns2[1],$ns2[0]));
			$ret = array_merge($ns2,array(implode("",$this->_cny_map_unit(str_split($ns1), $grees)), ""));
			$ret = implode("",array_reverse($this->_cny_map_unit($ret,$cnyunits)));
			return str_replace(array_keys($cnums), $cnums,$ret);
		}
		/**
		 * 循环处理
		 * @Author   cuiyinming
		 * @DateTime 2016-06-16T12:18:07+0800
		 * @param    [type]                   $list  [description]
		 * @param    [type]                   $units [description]
		 * @return   [type]                          [description]
		 */
		private function _cny_map_unit($list,$units){
			$ul = count($units);
			$xs = array();
			foreach (array_reverse($list) as $x)
			{
				$l = count($xs);
				if($x!="0" || !($l%4))
				{
					$n=($x=='0'?'':$x).($units[($l-1)%$ul]);
				}
				else
				{
					$n=is_numeric($xs[0][0]) ? $x : '';
				}
				array_unshift($xs, $n);
			}
			return $xs;
		}
}