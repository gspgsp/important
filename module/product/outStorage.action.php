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
				$pinfo=M("product:product")->getFnameByPid($v['p_id']);
				$v['f_name']=$pinfo['f_name'];//根据cid取客户名
				$list['data'][$k]['out_number']=$v['remainder']; //默认全部数量
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
	public function addSubmit(){
		$this->is_ajax=true; //指定为Ajax输出
		$data=sdata(); //获取UI传递的参数
		$basic_info=array(
				'input_time'=>CORE_TIME,
				'input_admin'=>$_SESSION['name'],
		);
		$this->db->startTrans(); //开启事务
		//获取订单中的发货时间和销售订单的关联采购joinid,用于区分后续扣库存操作
		$info = $this->db->model('order')->select('delivery_time,join_id,order_type')->where(' o_id ='.$data['o_id'])->getRow();
		$data['out_time']=$info['delivery_time'];
		$this->db->model('out_storage')->add($data+$basic_info);
		$storage_id=$this->db->getLastID(); //获取新增出库单ID
		foreach ($data['log'] as $k => $v) {
			$_data['o_id']=$v['o_id']; //订单id
			$_data['detial']=$v['id']; //销售订单明细id
			$_data['p_id']=$v['p_id']; //产品id
			$_data['store_id']=$v['store_id']; //仓库id
			$_data['store_aid']=$v['store_aid']; //仓库管理员id
			$_data['unit_price']=$v['unit_price'];
			$_data['number']=$v['out_number'];
			//新增出库明细
			$out_id = $this->db->model('out_log')->select('id')->where("`sale_log_id` = {$v['id']}")->getOne();
			if($out_id>0){ //判断此出库订单的明细之前有没有出过库
				// 判断出货数量与详情数量
				$out_storage_status = $v['remainder'] == $v['out_number'] ? 3 : 2;
				//更新此次入库数
				$this->db->model('out_log')->where("id = $out_id")->update(array('number'=>'+='.$v['out_number'],'out_storage_status'=>$out_storage_status,'ship'=>'+='.$data['ship'],));
				$inlog_id=$out_id;
			}else{ //如果没有新增入库明细
				// 判断出货数量与详情数量
				$out_storage_status = $v['remainder'] == $v['out_number'] ? 3 : 2;
				//新增入库明细
				$this->db->model('out_log')->add($_data+$basic_info+array('sale_log_id'=>$v['id'],'out_time'=>$data['out_time'],'ship'=>$data['ship'],'store_id'=>$data['store_id'],'store_aid'=>$data['store_aid'],'out_storage_status'=>$out_storage_status,));
				//获取新增入库单ID
				$inlog_id=$this->db->getLastID();
			}
			//更新销售明细中的未发数量
			$this->db->model('sale_log')->where(' id = '.$_data['detial'])->update(' remainder = remainder-'.$v['out_number']);
			//查询明细是否全部出库
			$remainder = $this->db->model('sale_log')->select('remainder')->where(' id = '.$_data['detial'])->getOne();
			$out_status = $remainder>0 ? 2 : 3;//大于0说明还有剩余数量没出库(2为部分出库 3为全部出库)
			//把状态改为部分出库
			$this->db->model('sale_log')->where(' id = '.$_data['detial'])->update('out_storage_status = '.$out_status.' , update_admin = "'. $_SESSION['name'].'" , update_time='.CORE_TIME);
			//扣库存
			// if($info['join_id']>0){ //上面查询的订单中存在joinid 表示先销后采
				//调用循环扣库存并打log
				$this->chkoutlog($v['out_number'],$v['inlog_id'],$v['id'],$inlog_id,$storage_id);
				//库存的总明细扣减
				$this->db->model('in_log')->where('id = '.$v['inlog_id'])->update(array('remainder'=>'-='.$v['out_number'],'lock_number'=>'-='.$v['out_number'],'update_admin'=>$_SESSION['name'],'update_time'=>CORE_TIME,));
			// }else{ //正常销库存的操作
			// 	//新增出库明细单流水// 循环扣库存
			// 	$this->chkoutlog($v['out_number'],$v['inlog_id'],$v['id'],$inlog_id,$storage_id);
			// 	// $res = $this->nchkoutlog($v['out_number'],$v['inlog_id']);//从入库明细扣除商品
			// 	// $this->db->model('out_logs')->add(array('p_id'=>$v['p_id'],'sale_id'=>$v['id'],'number'=>$v['out_number'],'outlog_id'=>$inlog_id,'store_id'=>$data['store_id'],'store_aid'=>$data['store_aid'],'storage_id'=>$storage_id,'inlogs_id'=>$res)+array('input_time'=>CORE_TIME,));
			// 	//库存的总明细扣减
			// 	$this->db->model('in_log')->where('id = '.$v['inlog_id'])->update(array('remainder' =>'-='.$v['out_number'],'controlled_number'=>'-='.$v['out_number'],'lock_number' =>'-='.$v['out_number'],'update_admin'=>$_SESSION['name'],'update_time'=>CORE_TIME,));
			// }
			//扣仓库货品数量
			$this->db->model('store_product')->where('p_id = '.$_data['p_id'].' and s_id = '.$_data['store_id'])->update('remainder = remainder-'.$v['out_number'].' , update_admin="'.$_SESSION['name'].'" , update_time='.CORE_TIME);
		}
		//查询订单中明细状态是否存在有部分出库的
		if( $this->db->model('sale_log')->where(' o_id = '.$_data['o_id'].' and out_storage_status < 3')->getOne()){
			//更新订单为部分入库
			$this->db->model('order')->where(' o_id = '.$_data['o_id'])->update('out_storage_status = 2 , update_admin = "'. $_SESSION['name'].'" , update_time='.CORE_TIME);
		}else{//反之更新为全部入库
			$this->db->model('order')->where(' o_id = '.$_data['o_id'])->update('out_storage_status = 3 , update_admin = "'. $_SESSION['name'].'" , update_time='.CORE_TIME);
		}
		if($this->db->commit()){
			//添加订单可视化订单审不通过
			M('order:orderLog')->addLog($data['o_id'],0,1,CORE_TIME-intval($this->db->model('order_flow')->select('input_time')->where("o_id = {$data['o_id']} and type = 0 and step = 2")->getOne()));
			//添加成功后的发货短信信息发送
			if(intval($info['order_type']) ==  1){
				M('order:orderLog')->sendMsg($data['o_id'],$info['order_type'],'，现已全部发货,请您查收。');
			}
			 $this->success('操作成功');
		}else{
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
	/**
	 * [chkoutlog description]判断处理出库&关联入库详情
	 * @Author   cuiyinming
	 * @DateTime 2016-06-29T16:24:02+0800
	 * @param    integer                  $number   [出库数量]
	 * @param    integer                  $inlog_id [入库明细id]
	 * @return   [type]                             [description]
	 */
	private function chkoutlog($number=0,$inlog_id=0,$sale_id=0,$outlog_id=0,$storage_id=0,$chk=0){
		//查询入库明细
		$logs_info = $this->db->model('in_logs')->where(" inlog_id = $inlog_id and remainder<>0 ")->order('input_time asc')->getAll();
		foreach ($logs_info as $k => $v) {
			if($chk == 0){
					//循环扣库存
					$out = $number > $v['remainder'] ? $v['remainder'] : $number;
					$this->db->model('in_logs')->where("id = {$v['id']}")->update("`remainder` = `remainder` - $out");
					//新增出库明细流水
					$outlogs = array(
						'p_id'=>$v['p_id'],
						'sale_id'=>$sale_id,
						'outlog_id'=>$outlog_id,
						'number'=>$out,
						'inlogs_id'=>$v['id'],
						'store_id'=>$v['store_id'],
						'store_aid'=>$v['store_aid'],
						'input_time'=>CORE_TIME,
						'storage_id'=>$storage_id,
						);
					$this->db->model('out_logs')->add($outlogs);
					$number -= $out;
					$chk = $number == 0 ? 1 : 0;
			}

		}
		return true;
	}
	// /**
	//  * 循环处理发货信息
	//  * @Author   cuiyinming
	//  * @DateTime 2016-08-16T16:24:20+0800
	//  * @param    integer                  $number    [description]
	//  * @param    integer                  $inlog_id  [description]
	//  * @param    integer                  $sale_id   [description]
	//  * @param    integer                  $outlog_id [description]
	//  * @param    integer                  $chk       [description]
	//  * @return   [type]                              [description]
	//  */
	// private function nchkoutlog($number=0,$inlog_id=0,$chk=0){
	// 	//查询入库明细
	// 	$logs_info = $this->db->model('in_logs')->where(" inlog_id = $inlog_id and remainder<>0 ")->order('input_time asc')->getAll();
	// 	foreach ($logs_info as $k => $v) {
	// 		if($chk == 0){
	// 				//循环扣库存
	// 				$out = $number > $v['remainder'] ? $v['remainder'] : $number;
	// 				$this->db->model('in_logs')->where("id = {$v['id']}")->update("`remainder` = `remainder` - $out");
	// 				//新增出库明细流水
	// 				$outlogs = array(
	// 					'p_id'=>$v['p_id'],
	// 					'sale_id'=>$sale_id,
	// 					'outlog_id'=>$outlog_id,
	// 					'number'=>$out,
	// 					'inlogs_id'=>$v['id'],
	// 					'store_id'=>$v['store_id'],
	// 					'store_aid'=>$v['store_aid'],
	// 					'input_time'=>CORE_TIME,
	// 					'storage_id'=>$storage_id,
	// 					);
	// 				$this->db->model('out_logs')->add($outlogs);
	// 				$number -= $out;
	// 				// $return .= $v['id'].'|';
	// 				$chk = $number == 0 ? 1 : 0;

	// 		}

	// 	}
	// 	// return $return;
	// 	return true;
	// }


}