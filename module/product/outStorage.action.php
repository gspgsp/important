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
		$out_no=genOrderSn();//出库单号
		$action=sget('action','s');
		if($action=='grid'){
			$where = "`o_id`=".$o_id;
			$list=$this->db->model('sale_log')->where($where)
					->page($page+1,$size)
					->order("$sortField $sortOrder")
					->getPage();
			foreach($list['data'] as $k=>&$v){
				$v['model']=M("product:product")->getModelById($v['p_id']); //获取客户名称
				$v['out_number']=$v['number'];
			}
			$result=array('total'=>$list['count'],'data'=>$list['data']);
			$this->json_output($result);
		}
		$out_info=$this->db->where("o_id = '$o_id'")->getRow();
		if(!$out_info) {
			$this->assign('doyet','doyet');
		}
		$c_id = $this->db->model('order')->select('c_id')->where("o_id = '$o_id'")->getOne();
		$out_info['out_date']=date("Y-m-d",$out_info['out_date']);
		$out_info['c_name']=M("user:customer")->getColByName($out_info['c_id']); //获取客户名称
		$out_info['admin_name']=M("product:outStorage")->getNameBySid($out_info['store_aid']); //获得出库人姓名
		$this->assign('c_id',$c_id);
		$this->assign('out_info',$out_info);
		$this->assign('out_no',$out_no);
		$this->assign('o_id',$o_id);
		$this->display('outStorage.edit.html');
	}
	/**
	 * 异步保存
	 */
	public function addSubmit(){
		$this->is_ajax=true; //指定为Ajax输出
		$data=sdata(); //获取UI传递的参数
		$add_data=array(
				'input_time'=>CORE_TIME,
				'input_admin'=>$_SESSION['name'],
		);
		$this->db->startTrans(); //开启事务
		try {
			if( !$this->db->model('out_storage')->add($data) ) throw new Exception("新增出库单失败");	
			$out_id=$this->db->getLastID(); //获取新增出库单ID	
			foreach ($data['log'] as $k => $v) {
				$log[$k]=$v;
				$log[$k]['out_id']=$out_id;
				$log[$k]['detail_id']=$v['id']; //订单明细
				$log[$k]['number']=$v['out_number'];	 //发货数量
				if( !$this->db->model('out_log')->add($log[$k]+$add_data) ) throw new Exception("出库明细新增失败");
				if( !$this->db->model('sale_log')->where('id = '.$v['id'])->update('out_number = out_number +'.$v['out_number'].' , out_storage_status=3') ) throw new Exception("更新订单明细失败");		
				if( !$this->db->model('in_log')->where('id = '.$v['inlog_id'])->update('remainder = remainder-'.$v['out_number'].' , lock_number = lock_number - '.$v['out_number']) ) throw new Exception("库存更新失败");
				if( !$this->db->model('store_product')->where('p_id = '.$v['p_id'].' and s_id = '.$v['store_id'])->update('remainder = remainder-'.$v['out_number']) ) throw new Exception("仓库货品更新失败");
			}
		} catch (Exception $e) {
			$this->db->rollback();
			$this->error($e->getMessage());
		}
		$this->db->commit();
		$this->success('操作成功');
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