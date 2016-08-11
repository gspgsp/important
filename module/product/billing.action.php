<?php
/**
*开票管理控制器
*/
class billingAction extends adminBaseAction
{
	public function __init(){
		$this->db=M('public:common')->model('billing');
		$this->assign('bile_type',L('bile_type'));		 	 			//票据类型
		$this->assign('invoice_one_status',L('invoice_one_status'));    //开票状态
		$this->assign('billing_type',L('billing_type'));    			//开票类型
		$this->assign('company_account',L('company_account')); 			//交易公司账户
	}

	/**
	 *
	 * @access public
	 * @return html
	 */
	public function init(){
		//获取列表数据
		$action=sget('action');
		if($action=='grid'){ //获取列表
			$this->_grid();exit;
		}elseif($action=='billingLog'){
			$this->_billingLog();exit;
		}
		$this->assign('type','1');
		$this->assign('page_title','销售开票明细');
		$this->display('billing.list.html');
	}
	/**
	 *
	 * @access public
	 * @return html
	 */
	public function itin(){
		//获取列表数据
		$action=sget('action');
		if($action=='grid'){ //获取列表
			$this->_grid();exit;
		}
		$this->assign('type','2');
		$this->assign('page_title','采购开票明细');
		$this->display('billing.list.html');
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
		//搜索条件
		$where = 1;
		$type = sget('type','i');
		if ($type == 1) {
			$where .=" and `billing_type`= 1 ";
		}elseif($type ==2){
			$where .=" and `billing_type`= 2 ";
		}
		//tap项的传值搜索
		$oid = sget('oid','i',0);
		if($oid!=0) $where.=" and `o_id` = '$oid' ";
		//交易日期
		$sTime = sget("sTime",'s','payment_time'); //搜索时间类型
		$where.= getTimeFilter($sTime); //时间筛选
		//票据类型
		$bile_type = sget("bile_type",'s','');
		if($bile_type!='') $where.=" and `bile_type` = '$bile_type' ";
		//开票状态
		$invoice_status = sget("invoice_one_status",'s','');
		if($invoice_status!='') $where.=" and `invoice_status` = '$invoice_status' ";

		//关键词搜索
		$key_type=sget('key_type','s','order_sn');
		$keyword=sget('keyword','s');
		if(!empty($keyword)){
			switch ($key_type) {
				case 'order_sn':
					$newword = "更正".$keyword;
					$where.=" and `order_sn` = '$keyword' or `order_sn` = '$newword'";
					break;
				case 'c_name':					
					$c_ids = M('user:customer')->getInfoByCname($key_type,$keyword);
					$str_cids = implode(',',array_values($c_ids));
					$where.=" and `c_id` in ($str_cids)";
					break;
				case 'admin':
					$customer_manager = M('rbac:adm')->getAdmin_Id($keyword);
					$where.=" and `customer_manager` = $customer_manager";
					break;
				default:
					$where.=" and `$key_type`  = '$keyword' ";
					break;
			}
		}

		//筛选领导级别
		if($_SESSION['adminid'] != 1 && $_SESSION['adminid'] > 0){
			$sons = M('rbac:rbac')->getSons($_SESSION['adminid']);  //领导
			$where .= " and `customer_manager` in ($sons) ";
		}
		
		$list=$this->db->where($where)
					->page($page+1,$size)
					->order("$sortField $sortOrder")
					->getPage();
		foreach($list['data'] as $k=>$v){
			$list['data'][$k]['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
			$list['data'][$k]['update_time']=$v['update_time']>1000 ? date("Y-m-d H:i:s",$v['update_time']) : '-';
			$list['data'][$k]['payment_time']=$v['payment_time']>1000 ? date("Y-m-d H:i:s",$v['payment_time']) : '-';
			//开票业务员
			$list['data'][$k]['input_admin']=M('rbac:adm')->getUserByCol($v['customer_manager']);
			//开票主题
			$list['data'][$k]['order_name'] = L('company_account')[$list['data'][$k]['order_name']];
			$list['data'][$k]['c_name']=M('user:customer')->getColByName($value=$v['c_id'],$col='c_name',$condition='c_id');

			empty($v['accessory'])?:$list['data'][$k]['accessory']=FILE_URL.'/upload/'.$v['accessory'];
			//每笔订单 收付款明细的审核状态
			$arr = M('product:billing')->getLastInfo($name='o_id',$value=$v['o_id']);
			$red_status = $this->db->where('invoice_status =1 and o_id='.$arr[0]['o_id'])->getAll();
			$list['data'][$k]['red_status']=empty($red_status)?0:1;
			
		}
		$msg="";
		if($list['count']>0){
			$sum=$this->db->select("sum(total_price) as tsum, sum(billing_price) as bsum, sum(unbilling_price) as usum")->where($where)->getRow();
			$msg="[合计]总额:【".price_format($sum['tsum'])."】已开票:【".$sum['bsum']."】未开票:【".$sum['usum']."】";
		}
		$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>$msg);
		$this->json_output($result);
	}


	//开票
	public function transactionInfo(){
		$o_id=sget('o_id','i',0);
		if(empty($o_id)) $this->error('信息错误');	
		$this->type=sget('order_type','s');//type=1为销售订单，type=2为采购订单
		$this->is_head=sget('is_head','i',0);
		$id = sget('id','i',0);
		$this->assign('id',$id);
		$this->assign('o_id',$o_id);
		$finance=sget('finance','i');
		$this->assign('finance',$finance);

		//开票申请与审核时添加最后收付款的时间
		$res = M('product:collection')->getLastInfo($name='o_id',$value="$o_id");
		$this->assign('payment_time',date("Y-m-d h:i:s",$res[0]['payment_time']));
		if ($finance ==1 ) {
			//开票审核
			$headData=$this->db->model('billing')
				->wherePk($id)
				->select('rise as c_name,total_price,billing_price,unbilling_price,order_sn,order_name,c_id')
				->getRow();
			$this->assign('headData',$headData);

		}else{
			//开票申请
			//未付款金额
			if($this->type==1){
				$modelName='sale_log';
			}else{
				$modelName='purchase_log';
			}
			
			$this->un_pay=$this->db->model("$modelName")->where("o_id=$o_id")->select("sum(b_number*unit_price)")->getOne();
			$headData=$this->db->from('order o')
				->join('customer c','o.c_id=c.c_id')
				->where("o.o_id=$o_id")
				->select('c.c_name,o.total_price,o.order_name,o.o_id,o.c_id,o.order_sn')
				->getRow();
			$this->assign('headData',$headData);

		}
		//公司开票资料
		
		$this->companyInfo=M("user:customer_billing")->getCinfoById($headData['c_id']);
		// $invoice_account=desDecrypt($this->companyInfo['invoice_account']);
		$invoice_account=$this->companyInfo['invoice_account'];
		
		$this->assign('invoice_account',$invoice_account);

		$this->display('billing.add.html');

	}

	public function ajaxSave(){
		$data = sdata();
		$type = sget('do','i');//区分销售采购订单
		$detail = $data['detail'];
		unset($data['detail']);
		$billingModel=M('product:billing');
		$billingLogModel=M('product:billingLog');
		$data['payment_time']=strtotime($data['payment_time']);

		$purchaseLogModel=M('product:purchaseLog');
		$saleLogModel=M('product:saleLog');
		$orderModel=M('product:order');

		if($data['finance']==1){
			//财务审核开票信息
			$data['update_time']	=CORE_TIME;
			$data['update_admin']	=$_SESSION['username'];
			$data['invoice_status']	=2;//完成开票状态
			$data['payment_time']	=CORE_TIME;
			$data['unbilling_price']=$data['unbilling_price']-$data['billing_price'];
			//销售开票号去重
			if ($type==1) {
				$res = M('product:billing')->curUnique('invoice_sn',$data['invoice_sn']);
				if(!$res) $this->error("发票号重复,请更换！");
			}
			$billingModel->startTrans();

			try {
				if(!$billingModel->where("id={$data['id']}")->update($data)) throw new Exception("开票审核更新表头失败");
				foreach ($detail as $key => $value) {
					$value['update_time']=CORE_TIME;
					$value['status']=2;
					if(!$billingLogModel->where("id={$value['id']}")->update($value)) throw new Exception("开票明细更新失败");
					if($type==1){
						$b_number=$saleLogModel->where("id={$value['l_id']}")->select('b_number')->getOne();
						if($value['b_number']>$b_number) throw new Exception("开票数量不能大于未开票数量");
						if(!$saleLogModel->where("id={$value['l_id']}")->update("b_number=b_number-{$value['b_number']}")) throw new Exception("销售明细更新失败");
						$sum=$saleLogModel->where("o_id={$data['o_id']}")->select("sum(b_number)")->getOne();
					}elseif($type==2){
						$b_number=$purchaseLogModel->where("id={$value['l_id']}")->select('b_number')->getOne();
						if($value['b_number']>$b_number) throw new Exception("开票数量不能大于未开票数量");
						if(!$purchaseLogModel->where("id={$value['l_id']}")->update("b_number=b_number-{$value['b_number']}")) throw new Exception("采购明细更新失败");
						$sum=$purchaseLogModel->where("o_id={$data['o_id']}")->select("sum(b_number)")->getOne();
					}
					if($sum==0){
						//更新为全部开票
						if(!$orderModel->where("o_id={$data['o_id']}")->update(array("invoice_status"=>3,"update_time"=>CORE_TIME,"update_admin"=>$_SESSION['username']))) throw new Exception("订单状态更新失败");
					}else{
						//更新为部分开票
						if(!$orderModel->where("o_id={$data['o_id']}")->update(array("invoice_status"=>2,"update_time"=>CORE_TIME,"update_admin"=>$_SESSION['username']))) throw new Exception("订单状态更新失败");
					}
				}
				if($billingLogModel->where("status=1 and parent_id={$data['id']}")->getRow()){
					if(!$billingLogModel->where("status=1 and parent_id={$data['id']}")->delete()) throw new Exception("开票明细删除失败");
				}
				
			} catch (Exception $e) {
				$billingModel->rollback();
				$this->error($e->getMessage());
			}
			$billingModel->commit();
			$this->success('操作成功');
			
		}else{
			//业务员提交申请开票			
			$data['input_time']=CORE_TIME;
			$data['input_admin']=$_SESSION['username'];
			$data['order_name']	=$data['title'];
			//根据o_id获取订单中的业务员id,即customer_manager
			$customer_manager = $this->db->model('order')->select('customer_manager')->where('o_id='.$data['o_id'])->getOne();
			$data['customer_manager']=$customer_manager;
			//判断生成开票号,审核时才有
			$date=date("Ymd").str_pad(mt_rand(0, 100), 3, '0', STR_PAD_LEFT);
			$data['billing_type']==1?($data['billing_sn']= 'sk'.$date):($data['billing_sn']= 'pk'.$date);
			$billingModel->startTrans();
			try {
				if(!$billingModel->add($data)) throw new Exception("开票申请表头添加失败");
				$parent_id=$billingModel->getLastID();
				foreach ($detail as $key => $value) {

					if($value['b_number']>$value['un_number']) throw new Exception("开票数量不能大于未开票数量");

					$log_data=array(
						'parent_id'=>$parent_id,
						'l_id'=>$value['id'],
						'p_id'=>$value['p_id'],
						'number'=>$value['number'],
						'b_number'=>$value['b_number'],
						'type'=>$value['type'],
						'model'=>$value['model'],
						'f_name'=>$value['f_name'],
						'unit_price'=>$value['unit_price'],
						'input_time'=>CORE_TIME, 
						'input_admin'=>$_SESSION['username'],
					);
					if(!$billingLogModel->add($log_data)) throw new Exception("开票明细添加失败");;
				}
				
			} catch (Exception $e) {
				$billingModel->rollback();
				$this->error($e->getMessage());
			}
			$billingModel->commit();
			$this->success('提交成功');
		}

	}


	protected function _billingLog(){
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$sortField = sget("sortField",'s','id'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
		$type=sget('type','i',0);

		if($is_head=sget('is_head','i',0)){
			$o_id=sget('o_id','i',0);

			if($type==1){
				//销售明细
				$list=M('product:saleLog')->getLogListByOid($o_id,$page,$size);
			}else{
				//采购明细
				$list=M('product:purchaseLog')->getLogListByOid($o_id,$page,$size);
			}
		}else{
			$id=sget('id','i',0);
			if($type==1){
				$listModel=$this->db->from('billing_log b')
					->join('sale_log l','b.l_id=l.id')
					->join('store st','l.store_id=st.id')
					->select('b.*,l.b_number as u_number,l.lot_num,st.store_name');
			}else{
				$listModel=$this->db->from('billing_log b')
					->join('purchase_log l','b.l_id=l.id')
					->select('b.*,l.b_number as u_number');
			}
			$list=$listModel->where("b.parent_id=$id")->page($page,$size)->getPage();
		}
		foreach ($list['data'] as &$value) {
			$value['sum']=floatval($value['b_number']*$value['unit_price']);
			if($is_head){
				$value['un_number']=$value['b_number'];
				$value['type']=L("product_type")[$value['type']];
			}else{
				$value['un_number']=$value['u_number'];
			}
		}

		$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>'');
		$this->json_output($result);

	}

	public function billingLog(){
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$sortField = sget("sortField",'s','id'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
		$type=sget('type','i',0);

		if($is_head=sget('is_head','i',0)){
			$o_id=sget('o_id','i',0);
			if($type==1){
				//销售明细
				$list=M('product:saleLog')->getLogListByOid($o_id,$page,$size);
			}else{
				//采购明细
				$list=M('product:purchaseLog')->getLogListByOid($o_id,$page,$size);
			}
		}else{
			$id=sget('id','i',0);
			if($type==1){
				$listModel=$this->db->from('billing_log b')
					->join('sale_log l','b.l_id=l.id')
					->join('store st','l.store_id=st.id')
					->select('b.*,l.b_number as u_number,l.lot_num,st.store_name');
			}else{
				$listModel=$this->db->from('billing_log b')
					->join('purchase_log l','b.l_id=l.id')
					->select('b.*,l.b_number as u_number');
			}
			$list=$listModel->where("b.parent_id=$id")->page($page,$size)->getPage();
		}

		foreach ($list['data'] as &$value) {
		//聚乙烯：HDPE、LDPE、LLDPE，聚丙烯：均聚PP、共聚PP，塑料ABS：ABS、MABS，塑料PC:PC
			$t = $value['type'];
			if($t==(1||2||3)){
				$value['type'] =1;
			}elseif($t==(4||6)){
				$value['type'] =2;
			}elseif($t==(7||9)){
				$value['type'] =3;
			}elseif($t==8){
				$value['type'] =4;
			}else{
				$value['type'] =5;
			}
			$value['sum']=floatval($value['b_number']*$value['unit_price']);
			if($is_head){
				$value['un_number']=$value['b_number'];
				$value['type']=L("finance_p_type")[$value['type']];
			}else{
				$value['un_number']=$value['u_number'];
			}
		}

		$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>'');
		$this->json_output($result);

	}
	//红字更正
	public function changeRed(){
		$this->is_ajax=true;
		$id=sget('id','i','0');
		$billingModel=M("product:billing");
		$orderModel=M("product:order");
		$billingLogModel=M('product:billingLog');
		$purchaseLogModel=M('product:purchaseLog');
		$saleLogModel=M('product:saleLog');
		if(!$data=$billingModel->where("id=$id")->getRow()) $this->error('信息不存在');

		$list=$billingLogModel->where("parent_id=$id")->getAll();
		unset($data['id']);
		$data['billing_sn']="更正".$data['billing_sn'];
		$data['input_time']=CORE_TIME;
		$data['input_admin']=$_SESSION['username'];
		$data['update_time']='';
		$data['update_admin']='';
		$data['invoice_status']=3;	
		$data['unbilling_price']=$data['unbilling_price']+$data['billing_price'];
		
		$billingModel->startTrans();
		try {
			$billingModel->where("id=$id")->update(array("invoice_status"=>3,"update_time"=>CORE_TIME,"update_admin"=>$_SESSION['username']));
			$billingModel->add($data);
			foreach ($list as $key => $value) {
				unset($value['id']);
				$value['status']=3;
				$value['input_time']=CORE_TIME;
				$value['input_admin']=$_SESSION['username'];
				$value['update_time']='';
				$value['update_admin']='';
				
				$billingLogModel->add($value);
				if($data['billing_type']==1){
					$saleLogModel->where("id={$value['l_id']}")->update("b_number=b_number+{$value['b_number']}");
					$billingNumber=$saleLogModel->where("id={$value['l_id']}")->select("number-b_number")->getOne();
				}else{
					$purchaseLogModel->where("id={$value['l_id']}")->update("b_number=b_number+{$value['b_number']}");
					$billingNumber=$purchaseLogModel->where("id={$value['l_id']}")->select("number-b_number")->getOne();
				}
			}
			if($billingNumber==0){
				$invoice_status=1;
			}else{
				$invoice_status=2;
			}
			$orderModel->where("o_id={$data['o_id']}")->update(array("invoice_status"=>$invoice_status,"update_time"=>CORE_TIME,"update_admin"=>$_SESSION['username']));

		} catch (Exception $e) {
			$billingModel->rollback();
			$this->error($e->getMessage());
		}
		$billingModel->commit();
		$this->success('操作成功');

	}


	// public function info(){
	// 	$id=sget('id','i',0);

	// 	$headData=$this->db->model('billing')
	// 		->wherePk($id)
	// 		->select('rise as c_name,total_price,billing_price,unbilling_price,order_sn,order_name,tax_price,bile_type,remark,invoice_sn')
	// 		->getRow();
	// 	$this->assign('headData',$headData);
	// 	$this->assign('id',$id);
	// 	$this->display('billing.info');
	// }

	// public function infoList(){
	// 	$id=sget('id','i',0);
	// 	$list=$this->db->from('billing_log')->where("parent_id=$id")->getAll();
	// 	foreach ($list as &$value) {
	// 		$value['sum']=floatval($value['b_number']*$value['unit_price']);
	// 	}
	// 	$result=array('data'=>$list);
	// 	$this->json_output($result);
	// }


	/**
	 * 保存行内编辑数据
	 * @access public 
	 * @return html
	 */
	public function save(){
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //获取UI传递的参数
		if(empty($data)) $this->error('错误的操作');
		$sql=array();
		foreach($data as $v){
			$_id=$v['id'];
			if($_id>0){
				$update=array(
					'payment_time'=>strtotime($v['payment_time']),
					'input_time'  =>strtotime($v['input_time']),
					'update_time' =>CORE_TIME,
					'update_admin'=>$_SESSION['name'],
					'remark'      =>$v['remark'],
					'paying_info' =>$v['paying_info'],
            		'receipt_info'=>$v['receipt_info'],
				);
				$sql[]=$this->db->wherePk($_id)->updateSql(saddslashes($update));
			}
		}
		if(empty($sql)){
			$this->error('操作数据为空');
		}
		$result=$this->db->commitTrans($sql);
		if($result){
			$cache=cache::startMemcache();
			$cache->delete('billing');
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}
	
	/**
	 *撤销开票时,返还订单明细数量
	 */
	public function returnNumber($_ids,$type){
		//p($_ids);p($type);die;
		foreach ($_ids as $v) {
			if ($type ==1) {
				$this->db->model('sale_log')->where('id='.$v['sale_log_id'])->update("`billing_number`=billing_number-".$v['number']);
			}else{
				$this->db->model('purchase_log')->where('id='.$v['purchase_log_id'])->update("`billing_number`=billing_number-".$v['number']);
			}
		}
	}

	// /**
	//  * 处理数量
	//  * @access private 
	//  */
	// public function changeNumber(){
	// 	$this->is_ajax=true; //指定为Ajax输出
	// 	$data = sdata(); //获取UI传递的参数
	// 	$index = sget('index','i');
	// 	if(empty($data)) $this->error('错误的操作');
	// 	//for循环遍历
	// 	$unit_price =0;
	// 	for($i=0;$i<count($data);$i++){
	// 		$unit_price = ($data[$index]['unit_price']);
	// 		continue;
	// 	}
	// 	$result=array('unit_price'=>$unit_price);
	// 	$this->json_output($result);
	// 	//$this->assign('unit_price',$unit_price);
	// 	//p($unit_price);

	//}
	
	/**
	 * 发票号去重
	 */
	public function curUnique(){
		$data = trim($_POST['data']);
		if(empty($data)) $this->error('请填写发票号');
		$res = M('product:billing')->curUnique('invoice_sn',$data);	
		if(!$res) $this->error("发票号重复,请更换！");
	}

}