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
		$this->assign('goods_status',L('goods_status')); //发货状态
		$this->assign('invoice_status',L('invoice_status')); //开票状态
		$this->assign('price_type',L('price_type')); //价格单位
		$this->assign('in_storage_status',L('in_storage_status')); //入库状态
		$this->assign('order_type',L('order_type')); //订单类型：
		$this->assign('company_account',L('company_account')); //交易公司账户
		$this->assign('sales_type',L('sales_type')); //销售类型
		$this->assign('purchase_type',L('purchase_type')); //采购类型
		$this->assign('collection_status',array(1=>'待收付款',2=>'部分收付款',3=>'已完成'));  //订单收付款状态
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
		$this->assign('order_type',$order_type);
		$this->assign('doact',$doact);
		$this->assign('page_title','订单管理列表');
		$this->display('order.list.html');
	}

	/**
	 * Ajax获取列表内容
	 * @access private 
	 * @return html
	 */
	private function _grid(){
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
			$v['pay_method'] =L('pay_method')[$v['pay_method']];
			$v['transport_type']=L('transport_type')[$v['transport_type']];
			$v['business_model']=L('business_model')[$v['business_model']];
			$v['financial_records']=L('financial_records')[$v['financial_records']];
			// $list['data'][$k]['order_status']=L('order_status')[$v['order_status']];
			// $list['data'][$k]['transport_status']=L('transport_status')[$v['transport_status']];
			$v['goods_status']=L('goods_status')[$v['goods_status']];
			$v['invoice_status']=L('invoice_status')[$v['invoice_status']];
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
		$type=sget('type','s');
		$order_type=sget('order_type','i',0);
		if($o_id<1){
			$order_sn=genOrderSn();
			$this->assign('order_sn',$order_sn);
			$this->assign('otype','addopus'); //新增订单关联前台显示
			$this->assign('order_type',$order_type);
			$this->display('order.edit.html');
			exit;
		}
		$info=$this->db->getPk($o_id); //查询订单信息
		if(empty($info)){
			$this->error('错误的订单信息');	
		}
		if($info['c_id']>0) $c_name = M('user:customer')->getColByName($info['c_id'],"c_name");
		$info['sign_time']=date("Y-m-d",$info['sign_time']);
		$info['pickup_time']=date("Y-m-d",$info['pickup_time']);
		$info['delivery_time']=date("Y-m-d",$info['delivery_time']);
		$info['payment_time']=date("Y-m-d",$info['payment_time']);
		$this->assign('c_name',$c_name);
		$this->assign('type',$type);
		$this->assign('info',$info);//分配订单信息
		if($type=="edit"){
			$this->display('order.edit.html');
			exit;
		}	
		$order_type = $info['order_type'] == 1? 'saleLog' : 'purchaseLog';
		
		$this->assign('order_type',$order_type);
		$this->assign('o_id',$o_id);
		$this->display('order.viewInfo.html');
	}

	/**
	* 付款收款开票信息
	* @access public
	*/
	public function transactionInfo(){
		$o_id=sget('o_id','i',0);
		$type=sget('order_type','s');

		if($type==1){
			$sum=$this->db->model('sale_log')->where("o_id=$o_id")->select("sum(number*unit_price)")->getOne();
		}else{
			$sum=$this->db->model('purchase_log')->where("o_id=$o_id")->select("sum(number*unit_price)")->getOne();
		}
		$this->assign('sum',$sum);
		
		$invoice=sget('invoice','i');
		if(empty($o_id)) $this->error('信息错误');	
		$data      = M('product:order')->getAllByName($value=$o_id,$condition='o_id');
		$c_name    = M('user:customer')->getColByName($data[0][c_id]);//获取公司名
		$c_info    = M('user:customer')->getCinfoById($data[0][c_id]);//获取公司所有信息
		$user_name = M('rbac:adm')->getUserInfoById($data[0][admin_id]);//获取前台添加的业务员名字
		$username  = $user_name[username];

		if (empty($username)) {
			$this->assign('input_admin',$data[0][input_admin]);
		}else{
			$this->assign('input_admin',$username);
		}
		$this->assign('c_name',$c_name);
		$this->assign('c_id',$data[0][c_id]);
		$this->assign('type',$type);
		$this->assign('o_id',$o_id);
		$this->assign('price',$data[0]['total_price']);
		$this->assign('order_sn',$data[0][order_sn]);
		if($invoice==1){//获取最后一条开票信息
			//发送开票公司信息
			$this->assign('Tax_Id',$c_info['Tax_Id']);
			$this->assign('Invoice_Address',$c_info['Invoice_Address']);
			$this->assign('Invoice_Tel',$c_info['Invoice_Tel']);
			$this->assign('Invoice_Bank',$c_info['Invoice_Bank']);
			$this->assign('Invoice_Account',$c_info['Invoice_Account']);

			$this->assign('bile_type',L('bile_type'));//票据类型
			$res = M('product:billing')->getLastInfo($name='o_id',$value=$data[0][o_id]);
			if($res){
				$hasbilling_price = ($res[0]['total_price']-$res[0]['unbilling_price']);
				$this->assign('hasbilling_price',$hasbilling_price);
				$this->assign('unbilling_price',$res[0]['unbilling_price']);
			}
			$this->display('billing.add.html');
			
		}else{
			//获取最后一条收付款信息	
			$res = M('product:collection')->getLastInfo($name='o_id',$value=$data[0][o_id]);
			if($res){
				$this->assign('total_price',$res[0]['total_price']);
				$this->assign('uncollected_price',$res[0]['uncollected_price']);
			}
			$this->display('collection.add.html');
		}
		
	}

	/**
	* 保存付款收款开票信息
	* @access public
	*/
	public function ajaxSave(){
		$data    = sdata();
		$silling = sget('do','i');
		if($silling == 1){
			//保存开票信息
			!empty($data['unbilling_price'])?$m = ($data['unbilling_price']-$data['billing_price']):$m = ($data['total_price']-$data['billing_price']);
			if($m>0){
				$data['unbilling_price'] = $m;
				$data['invoice_status'] = 2;
				$this->db->model('order')->where('o_id='.$data['o_id'])->update('invoice_status=2');
			}
			if($m==0){
				$data['unbilling_price'] = 0;
				$data['invoice_status'] = 3;
				$this->db->model('order')->where('o_id='.$data['o_id'])->update('invoice_status=3');
			}
			if($m<0){
				$this->error("数据错误");
			}

			//判断生成开票号
			$date=date("Ymd").str_pad(mt_rand(0, 100), 3, '0', STR_PAD_LEFT);
			$data['billing_type']==1?($data['billing_sn']= 'sk'.$date):($data['billing_sn']= 'pk'.$date);
			$data['payment_time']=strtotime($data['payment_time']);
			
			$this->db->startTrans();//开启事务
			try {
				if(!$this->db->model('billing')->add($data+array('input_time'=>CORE_TIME, 'admin_id'=>$_SESSION['adminid'])) )throw new Exception("开票失败");
			} catch (Exception $e) {
				$this->db->rollback();
				$this->error($e->getMessage());
			}

		}else{
			//保存收付款相关信息	
			if(empty($data['uncollected_price'])){
				$this->db->model('order')->where('o_id='.$data['o_id'])->update('total_price ='.$data['total_price'].',invoice_status=1');
				$m = $data['total_price']-$data['collected_price'];
			}else{
				$m = $data['uncollected_price']-$data['collected_price'];
			}
				
			if($m>0){
				$data['uncollected_price'] = $m;
				$data['collection_status'] = 2;
			}
			if($m==0){
				$data['uncollected_price'] = 0;
				$data['collection_status'] = 3;
			}
			if($m<0){
				$this->error("数据错误");
			}
			
			$data['payment_time']=strtotime($data['payment_time']);
			$this->db->startTrans();//开启事务
			try {
				if(!$this->db->model('collection')->add($data+array('input_time'=>CORE_TIME, 'admin_id'=>$_SESSION['adminid'],'invoice_status'=>1)) ) throw new Exception("付款失败");
			} catch (Exception $e) {
				$this->db->rollback();
				$this->error($e->getMessage());
			}
		}
		$this->db->commit();
		$this->success('操作成功');

	}


	/**
	 * 新增及修改订单
	 * @access public 
	 * @return html
	 */
	public function addSubmit() {
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //获取UI传递的参数
		if(empty($data)) $this->error('错误的请求');	
		$data['sign_time']=strtotime($data['sign_time']);
		$data['pickup_time']=strtotime($data['pickup_time']);
		$data['delivery_time']=strtotime($data['delivery_time']);
		$data['payment_time']=strtotime($data['payment_time']);
		$data['order_source'] = 2; //订单默认来源ERP
		foreach ($data as $k=> $v) {
			if( preg_match('/\d/',$k) ){
				preg_match_all('/\d/',$k,$matches);
				$detail[$matches[0][0]][substr($k,0,strlen($k)-1)]=$v;
			}else{
				$data[$k]=$v;
			}
		}
		if($data['o_id']>0){ //编辑
			$up_data = array(			
				'update_time'=>CORE_TIME,
				'update_admin'=>$_SESSION['name'],
			);	
			$result = $this->db->where('o_id='.$data['o_id'])->update($data+$up_data);
			if($result){
				$this->success('操作成功');
			}else{
				$this->error('更新失败');
			}
		}else{ //新增
			$this->db->startTrans(); //开启事务
			$add_data=array(
				'input_time'=>CORE_TIME,
				'input_admin'=>$_SESSION['name'],
				'admin_id'=>['adminid'],
			);
			try {	
				if( !$this->db->model('order')->add($data+$add_data) ) throw new Exception("新增订单失败");//新增订单
				$o_id=$this->db->getLastID(); //获取新增订单ID
				if( !$o_id ) throw new Exception("新增订单失败");
				if(!empty($detail)){ 
					for($i=1;$i<=count($detail);$i++){
						$detail[$i]['o_id']=$o_id;
						$detail[$i]['sale_order_no']=genOrderSn();
						if($data['order_type']==1 ){ //销售明细
							$detail[$i]['number']=$detail[$i]['require_number'];
							if( !$this->db->model('sale_log')->add($detail[$i]+$add_data) ) throw new Exception("新增明细失败");
							if(count($detail[$i])>10){ //如果数组长度大于6说明是消耗库存的订单
								if( !$this->db->model('in_log')->where('id = '.$detail[$i]['inlog_id'])->update(' controlled_number = controlled_number - '.$detail[$i]['require_number'].' , lock_number = lock_number + '.$detail[$i]['require_number']) ) throw new Exception("同步操作库存失败!");
							}
							
						}else{
							if( !$this->db->model('purchase_log')->add($detail[$i]+$add_data) ) throw new Exception("新增明细失败");
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
	}
	    /**
	 * 保存行内编辑工厂数据
	 * @access public
	 * @return html
	 */
	public function save(){
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //获取UI传递的参数
		if(empty($data)){
			$this->error('错误的操作');
		}
		$_data=array(
			'update_time'=>CORE_TIME,
			'update_admin'=>$_SESSION['name'],
		);
		
		foreach($data as $k=>$v){
			if($v['o_id']<0)  $this->error('错误的操作');
			$status_lsit=$this->db->model('order')->select('order_status,transport_status,order_type')->where( 'o_id ='.$v['o_id'] )->getAll(); //获取选择订单的状态
		
		$this->db->startTrans(); //开启事务	
			try {
			foreach ($status_lsit as $j => $val) { //判断数据库中的状态
				$table = ($val['order_type'] == 1 ? 'sale_log' : 'purchase_log' );
				if($val['order_status']==3 || $val['transport_status']==3) $this->error('已取消的订单无法操作');
			}
				if( ($v['order_status'] == 3 || $v['transport_status'] == 3) && $v['order_type'] == 1){ //类型是销售订单,并且审核不通过返还库存锁定数量
					$product_num=$this->db->model('sale_log')->select('store_id,number')->where( 'o_id ='.$v['o_id'] )->getAll(); //获取所有订单明细的产品锁定数量
					if(  !$product_num  ) throw new Exception("此订单没有相关明细");
					foreach ($product_num as $key => $value) { //循环对订单中每条明细操作
						if(  !$this->db->model('in_log')->where('store_id = '.$value['store_id'])->update(' remainder = remainder+ '.$value['number'].'  , lock_number = lock_number- '.$value['number'])  ) throw new Exception("库存明细数量解锁失败");//把订单中锁定的数量返还库存明细

						if(  !$this->db->model('store_product')->where('s_id = '.$value['store_id'])->update('  number = number+  '.$value['number'])  ) throw new Exception("仓库产品表总数量返还失败"); //仓库产品表数量返还
					}	
				}
				if( !$this->db->model($table)->where('o_id ='.$v['o_id'])->update($_data+array('order_status'=>$v['order_status'],'transport_status'=>$v['transport_status'] )) ) throw new Exception("明细审核状态更新失败!!!");
				if( !$this->db->model('order')->where('o_id ='.$v['o_id'])->update($_data+array('order_status'=>$v['order_status'],'transport_status'=>$v['transport_status'] )) ) throw new Exception("审核状态更新失败!!!");
				
			} catch (Exception $e) {
				$this->db->rollback();
				$this->error($e->getMessage());
			}
			$this->db->commit();
			$this->success('操作成功');
		}
	}
	// /**
	//  * Ajax删除
	//  * @access private 
	//  */
	// public function remove(){
	// 	$this->is_ajax=true; //指定为Ajax输出
	// 	$ids=sget('ids','s');
	// 	if(empty($ids)){
	// 		$this->error('操作有误');	
	// 	}
	// 	$data = explode(',',$ids);
	// 	if(is_array($data)){
	// 		foreach ($data as $k => $v) {
	// 			if(M('product:order')->getODidByOid($v)){
	// 				continue;
	// 			}else{
	// 				$result=$this->db->where("o_id = ($v)")->delete();
	// 			}
	// 		}
	// 	}
	// 	if($result){
	// 		$this->success('操作成功');
	// 	}else{
	// 		$this->error('订单有相关明细存在');
	// 	}
	// }
}