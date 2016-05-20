<?php 
/**
 * 入库详情管理
 */
class inStorageAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->db=M('public:common')->model('in_storage');
		$this->assign('ship_company',L('ship_company')); //物流公司
		$this->assign('in_type',L('in_type')); //入库类型
		$this->assign('in_storage_status',L('in_storage_status')); //入库状态

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
		$in_storage_no=genOrderSn();//入库单号
		$action=sget('action','s','aa');
		if($action=='grid'){
			$where = "`o_id`=".$o_id;
			$list=$this->db->model('in_log')->where($where)
					->page($page+1,$size)
					->order("$sortField $sortOrder")
					->getPage();
			foreach($list['data'] as $k=>$v){
				$list['data'][$k]['model']=M("product:product")->getModelById($list['data'][$k]['p_id']); //获取客户名称
				$list['data'][$k]['store_name']=M("product:store")->getStoreNameBySid($list['data'][$k]['store_id']); //获取仓库名
				$list['data'][$k]['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
			}
			$result=array('total'=>$list['count'],'data'=>$list['data']);
			$this->json_output($result);
		}
		$in_info=$this->db->where("o_id = '$o_id'")->getRow();
		if(!$in_info) {
			$this->assign('doyet','doyet');
		}
		$in_info['storage_date']=date("Y-m-d",$in_info['storage_date']);
		$in_info['c_name']=M("user:customer")->getColByName($in_info['c_id']); //获取客户名称
		$in_info['admin_name']=M("product:inStorage")->getNameBySid($in_info['store_aid']); //获得出库人姓名
		$this->assign('in_info',$in_info);
		$this->assign('o_id',$o_id);
		$this->assign('in_storage_no',$in_storage_no);
		$this->display('inStorage.edit.html');
	}
	/**
	 * 异步保存
	 */
	public function addSubmit(){
		$this->is_ajax=true; //指定为Ajax输出
		$data=sdata(); //获取UI传递的参数
		if(empty($data)) $this->error('操作有误');	
		$data['in_date']=strtotime($data['in_date']);
		$_data=array(
			'in_storage_no'=>genOrderSn(), //出库单号
			'input_time'=>CORE_TIME,
			'input_admin'=>$_SESSION['name'],
			'p_id'=>M("product:purchaseLog")->getColByDetId($data['purchase_id']),
		);
		p($data);
		$this->db->startTrans(); //开启事务
		$diff_num=M("product:inStorage")->checkNum($data['detail_id'],$data['number']); //订单和出库数量比较
		if(!$diff_num) $this->error('数量有误');
		if($data['doyet'] == 'doyet') $result=$this->db->add($data+$_data);		
		$in_log=$this->db->model('in_log')->add($data+$_data);
		if($this->db->commit()){
				$this->success('操作成功');
			}else{
				$this->db->rollback();
				$this->error('数据处理失败');
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
		p($data);
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