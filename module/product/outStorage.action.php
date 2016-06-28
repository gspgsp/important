<?php 
/**
 * 出库详情管理
 */
class outStorageAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->db=M('public:common')->model('out_storage');
		$this->assign('ship_company',L('ship_company')); //物流公司
		$this->assign('out_type',L('out_type')); //出库类型
		$this->assign('out_storage_status',L('out_storage_status')); //出库状态
	}
	/**
	 * 新增发货记录
	 */
	public function info(){
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$sortField = sget("sortField",'s','input_time'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
		$o_id=sget('o_id','i',0);
		if( $o_id<1 ) $this->error('错误的出库信息');
		$out_no='CK'.genOrderSn();//出库单号
		$action=sget('action','s');
		if($action=='grid'){
			$where = "`o_id`=".$o_id;
			$where .= " and out_storage_status != 3";
			$list=$this->db->model('sale_log')->where($where)
					->page($page+1,$size)
					->order("$sortField $sortOrder")
					->getPage();
			foreach($list['data'] as $k=>&$v){
				$v['model']=M("product:product")->getModelById($v['p_id']); //获取客户名称
				$v['store_name']=M("product:store")->getStoreNameBySid($v['store_id']);
			}
			$result=array('total'=>$list['count'],'data'=>$list['data']);
			$this->json_output($result);
		}
		$out_info=$this->db->where("o_id = '$o_id'")->getRow();
		if(!$out_info) {
			$this->assign('doyet','doyet');
		}
		$c_id = $this->db->model('order')->select('c_id')->where("o_id = '$o_id'")->getOne();
		$order_name = $this->db->model('order')->select('order_name')->where("o_id = '$o_id'")->getOne();
		$out_info['out_date']=date("Y-m-d",$out_info['out_date']);
		$out_info['c_name']=M("user:customer")->getColByName($out_info['c_id']); //获取客户名称
		$out_info['admin_name']=M("product:outStorage")->getNameBySid($out_info['store_aid']); //获得出库人姓名
		$this->assign('order_name',L('company_account')[$order_name]);
		$this->assign('c_id',$c_id);
		$this->assign('out_info',$out_info);
		$this->assign('out_no',$out_no);
		$this->assign('o_id',$o_id);
		$this->display('outStorage.edit.html');
	}
	/**
	 * 订单发货
	 */
	// public function addSubmit(){
	// 	$this->is_ajax=true; //指定为Ajax输出
	// 	$data=sdata(); //获取UI传递的参数
	// 	// p($data);die;
	// 	$add_data=array(
	// 			'input_time'=>CORE_TIME,
	// 			'input_admin'=>$_SESSION['name'],
	// 	);
	// 	// $this->db->startTrans(); //开启事务
	// 	$info = $this->db->model('order')->select('delivery_time,join_id')->where(' o_id ='.$data['o_id'])->getRow();
	// 	try {
	// 		if( !$this->db->model('out_storage')->add($data) ) throw new Exception("新增出库单失败");	
	// 		$out_id=$this->db->getLastID(); //获取新增出库单ID	
	// 		foreach ($data['log'] as $k => $v) {
	// 			$log[$k]=$v;
	// 			$log[$k]['out_time']=$info['delivery_time'];
	// 			$log[$k]['out_id']=$out_id;
	// 			$log[$k]['detail_id']=$v['id']; //订单明细
	// 			$log[$k]['number']=$v['out_number'];	 //发货数量
	// 			$log[$k]['out_storage_status']=3;
	// 			unset($log[$k]['id']);
	// 			if( !$this->db->model('out_log')->add($log[$k]+$add_data) ) throw new Exception("出库明细新增失败");
	// 			if( !$this->db->model('sale_log')->where('id = '.$v['id'])->update(' out_number = out_number +'.$v['out_number'].'  , update_admin="'.$_SESSION['name'].'" , update_time='.CORE_TIME) ) throw new Exception("更新订单明细失败");
	// 			$detailcheck = $this->db->model('sale_log')->where('id = '.$v['id'].' and number!=0')->getOne();

	// 			if($detailcheck<1){ //判断明细中的数量是否全部出库
	// 				if( !$this->db->model('sale_log')->where('id = '.$v['id'])->update(' out_storage_status=3 , update_admin="'.$_SESSION['name'].'" , update_time='.CORE_TIME) ) throw new Exception("更新订单明细失败");
	// 			}else{
	// 				if( !$this->db->model('sale_log')->where('id = '.$v['id'])->update(' out_storage_status=2 , update_admin="'.$_SESSION['name'].'" , update_time='.CORE_TIME) ) throw new Exception("更新订单明细失败");
	// 			}

	// 			if($info['join_id']>0){ //order表中的joinid 代表此订单是 先销后采,虚拟入库 , 流程中没有 销售核通过锁库存 这个操作 , 所以发货时对库存扣减的字段是不同的
	// 				if( !$this->db->model('in_log')->where('id = '.$v['inlog_id'])->update('remainder = remainder-'.$v['out_number'].' , controlled_number = controlled_number - '.$v['out_number'].' , update_admin="'.$_SESSION['name'].'" , update_time='.CORE_TIME) ) throw new Exception("库存更新失败");
	// 			}else{
	// 				if( !$this->db->model('in_log')->where('id = '.$v['inlog_id'])->update('remainder = remainder-'.$v['out_number'].' , lock_number = lock_number - '.$v['out_number'].' , update_admin="'.$_SESSION['name'].'" , update_time='.CORE_TIME) ) throw new Exception("库存更新失败");
	// 			}
	// 			if( !$this->db->model('store_product')->where('p_id = '.$v['p_id'].' and s_id = '.$v['store_id'])->update('remainder = remainder-'.$v['out_number'].' , update_admin="'.$_SESSION['name'].'" , update_time='.CORE_TIME) ) throw new Exception("仓库货品更新失败");
	// 		}
	// 		//每次操作完查询一下明细是否全部出库
	// 		$check = $this->db->model('sale_log')->select('id')->where(' out_storage_status <3 and o_id = '.$data['o_id'] )->getOne();
	// 		if($check<1){ //通过明细的出库状态情况改变订单出库状态
	// 			if( !$this->db->model('order')->where(' o_id ='.$data['o_id'])->update( array('out_storage_status' =>3,'update_admin'=>$_SESSION['name'],'update_time'=>CORE_TIME)) ) throw new Exception("订单入库更新失败1！");
	// 		}else{
	// 			if( !$this->db->model('order')->where(' o_id ='.$data['o_id'])->update( array('out_storage_status' =>2,'update_admin'=>$_SESSION['name'],'update_time'=>CORE_TIME))  ) throw new Exception("订单入库更新失败2！");
	// 		}
	// 	} catch (Exception $e) {
	// 		// $this->db->rollback();
	// 		$this->error($e->getMessage());
	// 	}
	// 	// $this->db->commit();
	// 	$this->success('操作成功');
	// }
	// 
	// 
	public function addSubmit(){
		$this->is_ajax=true; //指定为Ajax输出
		$data=sdata(); //获取UI传递的参数
		p($data);die;
		$basic_info=array(
				'input_time'=>CORE_TIME,
				'input_admin'=>$_SESSION['name'],
		);
		$this->db->startTrans(); //开启事务
		//获取订单中的发货时间和销售订单的关联采购joinid,用于区分后续扣库存操作
		$info = $this->db->model('order')->select('delivery_time,join_id')->where(' o_id ='.$data['o_id'])->getRow();
		$data['out_time']=$info['delivery_time'];
		$this->db->model('out_storage')->add($data+$basic_info);
		$storage_id=$this->db->getLastID(); //获取新增出库单ID
		foreach ($data['log'] as $k => $v) {
			$_data['o_id']=$v['o_id']; //订单id
			$_data['out_id']=$storage_id; //出库头id
			$_data['detial']=$v['id']; //销售订单明细id
			$_data['p_id']=$v['p_id']; //产品id
			$_data['store_id']=$v['store_id']; //仓库id
			$_data['store_aid']=$v['store_aid']; //仓库管理员id
			$_data['unit_price']=$v['unit_price']; 
			$_data['number']=$v['out_number'];
			//新增出库明细
			$this->db->model('out_log')->add($_data+$basic_info);
			//更新销售明细中的未发数量
			$this->db->model('sale_log')->where(' id = '.$_data['detial'])->update(' remainder = remainder-'.$v['out_number']);
			//查询明细是否全部出库
			$remainder = $this->db->model('sale_log')->select('remainder')->where(' id = '.$_data['detial'])->getOne();
			if($remainder>0){//大于0说明还有剩余数量没出库
				//把状态改为部分出库
				$this->db->model('sale_log')->where(' id = '.$_data['detial'])->update('out_storage_status = 2 , update_admin = "'. $_SESSION['name'].'" , update_time='.CORE_TIME);
			}else{//没有剩余数量表示这条明细全部发出
				//把状态改为全部出库
				$this->db->model('sale_log')->where(' id = '.$_data['detial'])->update('out_storage_status = 3 , update_admin = "'. $_SESSION['name'].'" , update_time='.CORE_TIME);
			}

			//扣库存
			if($info['join_id']>0){ //上面查询的订单中存在joinid 表示不销库存 
				$this->db->model('in_log')->where('id = '.$v['inlog_id'])->update(' remainder = remainder-'.$v['out_number'].' , controlled_number = controlled_number-'.$v['out_number'].', update_admin = "'. $_SESSION['name'].'" , update_time='.CORE_TIME);
			}else{ //正常销库存的操作
				$this->db->model('in_log')->where('id = '.$v['inlog_id'])->update(' remainder = remainder-'.$v['out_number'].' , lock_number = lock_number - '.$v['out_number'].' , update_admin="'.$_SESSION['name'].'" , update_time='.CORE_TIME);
			}

			//扣仓库货品数量
			$this->db->model('store_product')->where('p_id = '.$_data['p_id'].' and s_id = '.$_data['store_id'])->update('remainder = remainder-'.$v['out_number'].' , update_admin="'.$_SESSION['name'].'" , update_time='.CORE_TIME);
		}
		//查询订单中明细状态是否存在有部分出库的
		if( $this->db->model('sale_log')->where(' o_id = '.$_data['o_id'].' and out_storage_status < 3')->getOne() ){
			//更新订单为部分入库
			$this->db->model('order')->where(' o_id = '.$_data['o_id'])->update('out_storage_status = 2 , update_admin = "'. $_SESSION['name'].'" , update_time='.CORE_TIME);
		}else{//反之更新为全部入库
			$this->db->model('order')->where(' o_id = '.$_data['o_id'])->update('out_storage_status = 3 , update_admin = "'. $_SESSION['name'].'" , update_time='.CORE_TIME);
		}
		if($this->db->commit()){
			 $this->success('操作成功');	
		}else{
			showtrace();
			$this->db->rollback();
			$this->error('操作失败');
		}
	}


	/**
	 * 获取联系人信息
	 * @access public
	 */
	function get_store_aid(){
		$this->is_ajax=true;
		$s_id=sget('sid','i');
		$admin_list=M('product:store_admin')->getColByid($s_id);
		$this->json_output($admin_list);
	}
	/**
	 * Ajax删除流水
	 * @access private 
	 */
	public function remove(){
		$this->is_ajax=true; //指定为Ajax输出
		$ids=sget('ids','s');
		if(empty($ids)){
			$this->error('操作有误');	
		}
		$data = explode(',',$ids);
		if(is_array($data)){
			foreach ($data as $k => $v) {
				$result=$this->db->model('out_log')->where("id in ($ids)")->delete();
			}
		}
		if($result){
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}


	/**
	 * 编辑已存在的数据
	 * @access public 
	 * @return html
	 */
	public function save(){
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //获取UI传递的参数
		$sql=array();
		if(empty($data)){
			$this->error('操作数据为空');
		}
		foreach($data as $k=>$v){
			$_data=array(
				'number'=>$v['number'],		 
			);
			$diff_num=M("product:outStorage")->checkNum($v['sale_id'],$v['number']); //订单和出库数量比较
			if(!$diff_num) $this->error('数量有误');
			$result=$this->db->model('out_log')->wherePk($v['id'])->update($_data);
		}
		if($result){
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}
	
}