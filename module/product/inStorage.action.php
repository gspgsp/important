<?php 
/**
 * 入库详情管理
 */
class inStorageAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->db=M('public:common')->model('in_storage');
		$this->assign('ship_company',L('ship_company')); //物流公司
		$this->assign('purchase_type',L('purchase_type')); //状态
	}
	/**
	 * 新增入库记录
	 */
	public function info(){
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$sortField = sget("sortField",'s','input_time'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
		$o_id=sget('o_id','i',0);
		$join_id=sget('join_id','i',0);
		if( $o_id<1 ) $this->error('错误的出库信息');
		$in_storage_no='RK'.genOrderSn();//入库单号
		$action=sget('action','s');
		if($action=='grid'){
			$where = "`o_id`=".$o_id;
			$where .= " and `in_storage_status` != 3 ";
			$list=$this->db->model('purchase_log')->where($where)
					->page($page+1,$size)
					->order("$sortField $sortOrder")
					->getPage();
			foreach($list['data'] as $k=>$v){
				$pinfo=M("product:product")->getFnameByPid($v['p_id']);
				$list['data'][$k]['f_name']=$pinfo['f_name'];//根据cid取客户名
				$list['data'][$k]['order_sn']=M("product:order")->getColByOid($o_id,'order_sn');			
				$list['data'][$k]['model']=M("product:product")->getModelById($v['p_id']); //获取牌号名称
				$list['data'][$k]['store_name']=M("product:store")->getStoreNameBySid($v['store_id']); //获取仓库名
				$list['data'][$k]['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
			}
			$result=array('total'=>$list['count'],'data'=>$list['data']);
			$this->json_output($result);
		}
		$in_info=$this->db->model('in_storage')->where("o_id = '$o_id'")->getRow();
		if($in_info) { //查询是否存在入库头数据
			$this->assign('store_aid',$in_info['store_aid']);
			$this->assign('store_id',$in_info['store_id']);
			$this->assign('doyet','doyet');
		}
		$order_info = $this->db->select('order_name,purchase_type')->model('order')->where(' o_id = '.$o_id)->getRow();
		$in_info['storage_date']=date("Y-m-d",$in_info['storage_date']);
		$in_info['c_name']=M("user:customer")->getColByName($in_info['c_id']); //获取客户名称
		$in_info['admin_name']=M("product:inStorage")->getNameBySid($in_info['store_aid']); //获得出库人姓名
		$in_info['purchase_subject']=L('company_account')[$order_info['order_name']];
		$this->assign('order_info',$order_info );//采购订单带到入库的信息
		$this->assign('in_info',$in_info);//入库明细
		$this->assign('join_id',$join_id);//关联的销售订单id
		$this->assign('o_id',$o_id);
		$this->assign('in_storage_no',$in_storage_no);
		$this->display('inStorage.edit.html');
	}

	/**
	 * 备货的采购单入库
	 */

	public function addSubmit(){
		$this->is_ajax=true; //指定为Ajax输出
		$data=sdata(); //获取UI传递的参数
		if(empty($data)) $this->error('操作有误');
		$basic_info = array(
			'input_admin'=>$_SESSION['name'],
			'input_time'=>CORE_TIME,
		);
		$update =  array(
			'update_admin'=>$_SESSION['name'],
			'update_time'=>CORE_TIME,
		);
		$this->db->startTrans(); //开启事务
		//新增入库表头
		$this->db->model('in_storage')->add($data+$basic_info);
		$storage_id=$this->db->getLastID(); //获取新增出库单ID
		foreach ($data['list'] as $k => $v) {
			$_data['o_id']=$v['o_id'];
			$_data['purchase_id']=$v['id'];
			$_data['p_id']=$v['p_id'];
			$_data['storage_id']=$storage_id;
			$_data['store_id']=$data['store_id'];
			$_data['store_aid']=$data['store_aid'];
			if (!empty($v['lot_num'])) $_data['lot_num']= $v['lot_num']; //批次号 不是必填字段
			$_data['unit_price']=$v['unit_price'];
			$_data['number']=$v['in_number'];
			$_data['remainder']=$v['in_number'];
			$_data['controlled_number']=$v['in_number'];
			$_data['join_id']=$data['join_id'];
			//新增入库明细
			if( $this->db->model('in_log')->where(' purchase_id = '.$_data['purchase_id'])->getOne() ){ //判断此采购订单的明细之前有没有入过库
				//更新此次入库数
				$this->db->model('in_log')->where(' purchase_id = '.$_data['purchase_id'])->update(' number = number+'.$v['in_number'].' , remainder = remainder+'.$v['in_number'].' , controlled_number = controlled_number+'.$v['in_number']);
			}else{ //如果没有新增入库明细
				//新增入库明细
				$this->db->model('in_log')->add($_data+$basic_info);
			}
			
			//循环更新每条采购明细的已入库数量
			$this->db->model('purchase_log')->where(' id = '.$v['id'])->update('remainder = remainder-'.$v['in_number']);
			//查询明细是否全部入库
			$remainder = $this->db->model('purchase_log')->select('remainder')->where(' id = '.$v['id'])->getOne();
			//如果明细的入库数量小于采购数量则更改明细状态为部分入库
			if($remainder>0){
				$this->db->model('purchase_log')->where(' id = '.$v['id'])->update('in_storage_status = 2 , update_admin = "'. $_SESSION['name'].'" , update_time='.CORE_TIME);
			}else{//反之更改状态为全部入库
				$this->db->model('purchase_log')->where(' id = '.$v['id'])->update('in_storage_status = 3 , update_admin = "'. $_SESSION['name'].'" , update_time='.CORE_TIME);
			}


			$input_store['s_id']=$data['store_id'];
			$input_store['p_id']=$v['p_id'];
			$input_store['number']=$v['in_number'];
			$input_store['remainder']=$v['in_number'];
			//判断仓库货品表中是否存在此货品
			if( $this->db->model('store_product')->where('s_id = '.$input_store['s_id'].' and p_id = '.$input_store['p_id'])->getOne() ){
				//存在就更新数量
				$this->db->model('store_product')->where('s_id = '.$input_store['s_id'].' and p_id = '.$input_store['p_id'])->update('number = number+'.$v['in_number'].' , update_admin = "'. $_SESSION['name'].'" , update_time='.CORE_TIME);
			}else{//不存在就add
				$this->db->model('store_product')->add($input_store+$basic_info);
			}
		}	
		//查询订单中明细状态是否存在有部分入库的
		if( $this->db->model('purchase_log')->where(' o_id = '.$_data['o_id'].' and in_storage_status < 3')->getOne() ){
			//更新订单为部分入库
			$this->db->model('order')->where(' o_id = '.$_data['o_id'])->update('in_storage_status = 2 , update_admin = "'. $_SESSION['name'].'" , update_time='.CORE_TIME);
		}else{//反之更新为全部入库
			$this->db->model('order')->where(' o_id = '.$_data['o_id'])->update('in_storage_status = 3 , update_admin = "'. $_SESSION['name'].'" , update_time='.CORE_TIME);
		}
		if($this->db->commit()){
			 $this->success('操作成功');	
		}else{
			$this->db->rollback();
			$this->error('操作失败');
		}
	}

	/**
	 * 不销库存的采购入库
	 */
	public function addJoinSubmit(){
		$this->is_ajax=true; //指定为Ajax输出
		$data=sdata(); //获取UI传递的参数
		if(empty($data)) $this->error('操作有误');
		$basic_info = array(
			'input_admin'=>$_SESSION['name'],
			'input_time'=>CORE_TIME,
		);
		$update =  array(
			'update_admin'=>$_SESSION['name'],
			'update_time'=>CORE_TIME,
		);
		$this->db->startTrans(); //开启事务
		//新增入库表头
		$this->db->model('in_storage')->add($data+$basic_info);
		$storage_id=$this->db->getLastID(); //获取新增出库单ID
		foreach ($data['list'] as $k => $v) {
			$_data['o_id']=$v['o_id']; //采购订单id
			$_data['purchase_id']=$v['id']; //采购明细id
			$_data['p_id']=$v['p_id']; //产品id
			$_data['storage_id']=$storage_id; //出库表头ID
			$_data['store_id']=$data['store_id']; //仓库id
			$_data['store_aid']=$data['store_aid']; //仓库管理员id
			if (!empty($v['lot_num'])) $_data['lot_num']= $v['lot_num']; //批次号 不是必填字段
			$_data['unit_price']=$v['unit_price'];
			$_data['number']=$v['in_number']; //入库数量
			$_data['remainder']=$v['in_number']; //剩余数量
			$_data['controlled_number']=$v['in_number']; //可用数量
			$_data['join_id']=$data['join_id']; //关联的销售订单id
			//新增入库明细
			$in_id = $this->db->model('in_log')->select('id')->where(' purchase_id = '.$_data['purchase_id'])->getOne();
			if( $in_id>0 ){ //判断此采购订单的明细之前有没有入过库
				//更新此次入库数  compact()
				$this->db->model('in_log')->where(' purchase_id = '.$_data['purchase_id'])->update(' number = number+'.$v['in_number'].' , remainder = remainder+'.$v['in_number'].' , controlled_number = controlled_number+'.$v['in_number']);
				$inlog_id=$in_id; 
			}else{ //如果没有新增入库明细
				//新增入库明细
				$this->db->model('in_log')->add($_data+$basic_info);
				//获取新增入库单ID
				$inlog_id=$this->db->getLastID(); 
			}
			//新增入库明细单流水
			$this->db->model('in_logs')->add(array('p_id'=>$v['p_id'],'purchase_id'=>$v['id'],'number'=>$v['in_number'],'remainder'=>$v['in_number'],'inlog_id'=>$inlog_id,'store_id'=>$data['store_id'],'store_aid'=>$data['store_aid'])+$basic_info);
			if( $_data['join_id']>0 ){ //虚拟入库时的仓库 更新到销售明细 以便发货时把数据add到出库明细表
				$exist = $this->db->model('sale_log')->where('o_id='.$_data['join_id'].' and p_id='.$_data['p_id'].' and inlog_id ='.$inlog_id)->select('id')->getOne();
				//判断之前是否更新过
				if($exist<1){
					if(!empty($v['lot_num'])){
						$this->db->model('sale_log')->where('o_id = '.$_data['join_id'].' and p_id = '.$_data['p_id'])->update('store_id='.$_data['store_id'].' , store_aid='.$_data['store_aid'].' , inlog_id='.$inlog_id.' , lot_num='.$_data['lot_num'].' , update_admin = "'. $_SESSION['name'].'" , update_time='.CORE_TIME);
					}else{
						$this->db->model('sale_log')->where('o_id = '.$_data['join_id'].' and p_id = '.$_data['p_id'])->update('store_id='.$_data['store_id'].' , store_aid='.$_data['store_aid'].' , inlog_id='.$inlog_id.' , update_admin = "'. $_SESSION['name'].'" , update_time='.CORE_TIME);
					}	
				}	
			}

			//循环更新每条采购明细的已入库数量
			$this->db->model('purchase_log')->where(' id = '.$v['id'])->update('remainder = remainder-'.$v['in_number']);
			//查询明细是否全部入库
			$remainder = $this->db->model('purchase_log')->select('remainder')->where(' id = '.$v['id'])->getOne();
			//如果明细的入库数量小于采购数量则更改明细状态为部分入库
			if($remainder>0){
				$this->db->model('purchase_log')->where(' id = '.$v['id'])->update('in_storage_status = 2 , update_admin = "'. $_SESSION['name'].'" , update_time='.CORE_TIME);
			}else{//反之更改状态为全部入库
				$this->db->model('purchase_log')->where(' id = '.$v['id'])->update('in_storage_status = 3 , update_admin = "'. $_SESSION['name'].'" , update_time='.CORE_TIME);
			}


			$input_store['s_id']=$data['store_id'];
			$input_store['p_id']=$v['p_id'];
			$input_store['number']=$v['in_number'];
			$input_store['remainder']=$v['in_number'];
			//判断仓库货品表中是否存在此货品
			if( $this->db->model('store_product')->where('s_id = '.$input_store['s_id'].' and p_id = '.$input_store['p_id'])->getOne() ){
				//存在就更新数量
				$this->db->model('store_product')->where('s_id = '.$input_store['s_id'].' and p_id = '.$input_store['p_id'])->update('number = number+'.$v['in_number'].' , update_admin = "'. $_SESSION['name'].'" , update_time='.CORE_TIME);
			}else{//不存在就add
				$this->db->model('store_product')->add($input_store+$basic_info);
			}
		}	
		//查询订单中明细状态是否存在有部分入库的
		if( $this->db->model('purchase_log')->where(' o_id = '.$_data['o_id'].' and in_storage_status < 3')->getOne() ){
			//更新订单为部分入库
			$this->db->model('order')->where(' o_id = '.$_data['o_id'])->update('in_storage_status = 2 , update_admin = "'. $_SESSION['name'].'" , update_time='.CORE_TIME);
		}else{//反之更新为全部入库
			$this->db->model('order')->where(' o_id = '.$_data['o_id'])->update('in_storage_status = 3 , update_admin = "'. $_SESSION['name'].'" , update_time='.CORE_TIME);
			//采购全部入库了 给销售订单表示 可以发货
			$this->db->model('order')->where(' o_id = '.$data['join_id'])->update('is_join_in = 1 , update_admin = "'. $_SESSION['name'].'" , update_time='.CORE_TIME);
		}
		if($this->db->commit()){
			 $this->success('操作成功');	
		}else{
			$this->db->rollback();
			$this->error('操作失败');
		}

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
				$result=$this->db->model('in_log')->where("id in ($ids)")->delete();
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
			$diff_num=M("product:inStorage")->checkNum($v['detail_id'],$v['number']); //订单和出库数量比较
			if(!$diff_num) $this->error('数量有误');
			$result=$this->db->model('in_log')->wherePk($v['id'])->update($_data);
		}
		if($result){
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}
	
}