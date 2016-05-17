<?php 
/**
 * 订单详情管理
 */
class outStorageAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->db=M('public:common')->model('out_storage');
		$this->assign('ship_company',L('ship_company')); //物流公司
		$this->assign('storage_type',L('storage_type')); //出库类型
		$this->assign('out_storage_status',L('out_storage_status')); //出库状态

	}

	/**
	 * 新增发货记录
	 */
	public function info(){
		$o_id=sget('o_id','i',0);
		if( $o_id<1 ) $this->error('错误的出库信息');
		$storage_no=genOrderSn();//出库单号
		$this->assign('o_id',$o_id);
		$this->assign('storage_no',$storage_no);
		$this->display('outStorage.edit.html');
	}

	//异步保存
	public function addSubmit(){
		$this->is_ajax=true; //指定为Ajax输出
		$data=sdata(); //获取UI传递的参数
		if(empty($data)) $this->error('操作有误');	
		$data['storage_date']=strtotime($data['storage_date']);
		$data['order_sn']= M("product:order")->getColByName($data['o_id'],'order_sn'); //关联订单号
		$_data=array(
			'input_time'=>CORE_TIME,
			'input_admin'=>$_SESSION['name'],
			'customer_manager'=>$_SESSION['adminid'],
		);
		
		$diff_num=M("product:outStorage")->checkNum($data['detail_id'],$data['number']); //订单和出库数量比较
		if(!$diff_num) $this->error('数量有误');
		$result=$this->db->add($data+$_data+array('detail_id'=>$detail_ids[$i]));
		if($result){
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
		

	}

}