<?php 
/**
 * 订单详情管理
 */
class orderDetailAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->db=M('public:common')->model('order_detail');
		$this->doact = sget('do','s');
		$this->assign('price_type',L('price_type')); //价格单位
		$this->assign('invoice_status',L('invoice_status')); //开票状态
		$this->assign('in_storage_status',L('in_storage_status')); //入库状态
		$this->assign('sales_type',L('sales_type')); //销售类型
	}
	/**
	 *
	 * @access public
	 * @return html
	 */
	public function init(){
		$doact=sget('do','s');
		$action=sget('action','s');
		if($action=='grid'){ //获取列表
			$this->_grid();exit;
		}
		$this->assign('doact',$doact);
		$this->assign('oid',sget('oid','i',0));
		$this->assign('page_title','订单管理列表');
		$this->display('orderDetail.list.html');
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
		$o_id=sget('oid','i',0);
		if($o_id !=0)  $where.=" and `o_id` =".$o_id;
		//开票状态
		$invoice_status=sget('invoice_status','i',0);
		if($invoice_status != 0) $where.=" and `invoice_status` =".$invoice_status;
		//入库状态
		$in_storage_status=sget('in_storage_status','i',0);
		if($in_storage_status != 0) $where.=" and `in_storage_status` =".$in_storage_status;
		//销售类型
		$sales_type=sget('sales_type','i',0);
		if($sales_type != 0) $where.=" and `sales_type` =".$sales_type;
		//筛选时间
		$sTime=sget('sTime','s','input_time');
		$where.=getTimeFilter($sTime);
		//关键词搜索
		$key_type=sget('key_type','s','o_id');
		$keyword=sget('keyword','s');
		if(!empty($keyword) && $key_type=='o_id'  ){
			$keyword=M('product:order')->getidByOname($keyword);
			$where.=" and `$key_type` in ($keyword) ";
		}elseif(!empty($keyword) && $key_type=='p_id' ){
			$keyword=M('product:product')->getpidByPname($keyword);
			$where.=" and `$key_type` in ($keyword) ";
		}elseif(!empty($keyword)){
			$where.=" and `$key_type`  like '%$keyword%' ";
		}
		$list=$this->db->where($where)
				->page($page+1,$size)
				->order("$sortField $sortOrder")
				->getPage();
		foreach($list['data'] as $k=>$v){
			$list['data'][$k]['order_name']=M("product:order")->getColByName($list['data'][$k]['o_id']);
			$list['data'][$k]['model']=M("product:product")->getModelById($list['data'][$k]['p_id']);
			$list['data'][$k]['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
			$list['data'][$k]['update_time']=$v['update_time']>1000 ? date("Y-m-d H:i:s",$v['update_time']) : '-';
			$list['data'][$k]['sign_time']=$v['sign_time']>1000 ? date("Y-m-d H:i:s",$v['sign_time']) : '-';
			$list['data'][$k]['invoice_status'] = L('invoice_status')[$v['invoice_status']]; 
			$list['data'][$k]['in_storage_status'] = L('in_storage_status')[$v['in_storage_status']];
			$list['data'][$k]['sales_type'] = L('sales_type')[$v['sales_type']];
		}
		$this->assign('doact',$doact);
		$result=array('total'=>$list['count'],'data'=>$list['data']);
		$this->json_output($result);
	}
	/**
	* 订单明细信息
	* @access public
	*/
	public function info(){
		$id=sget('id','i',0);
		$type=sget('type','s');
		$o_id=sget('o_id','i',0);
		if($id<1){
			if($o_id>0){
				$this->assign('o_id',$o_id);
				$order_name=M("product:order")->getColByName($o_id);
				$this->assign('order_name',$order_name);
			}
			$purchase_order_no=genOrderSn();
			$this->assign('purchase_order_no',$purchase_order_no);
			$this->display('orderDetail.edit.html');
			exit;
		}
		$info=$this->db->getPk($id); //查询订单信息
		if(empty($info)){
			$this->error('错误的订单信息');	
		}
		if($info['o_id']>0) $order_name = M('product:order')->getColByName("$info[o_id]");
		if($info['p_id']>0) $model = M('product:product')->getModelById("$info[p_id]");
		if($type !="edit") $info['p_info']=M('product:product')->getFnameByPid($info['p_id']); 
		if($type !="edit") $info['order_sn']=M('product:order')->getColByName($info['o_id'],'order_sn'); //根据pid取厂家名
		$info['count']=$info['number']*$info['unit_price'];
		//根据pid取厂家名
		$this->assign('process_type',L('process_level'));//加工级别
		$this->assign('sales_type',L('sales_type'));//加工级别
		$this->assign('product_type',L('product_type'));//产品类型
		$this->assign('order_name',$order_name);
		$this->assign('model',$model);
		$this->assign('info',$info);//分配订单信息
		$this->assign('type',$type);//分配订单id信息
		if($type =="edit"){
			$this->display('orderDetail.edit.html');
			exit;
		}
		$this->display('orderDetail.viewInfo.html');
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
		$_data = array(			
			'update_time'=>CORE_TIME,
			'update_admin'=>$_SESSION['name'],
		);	
		if($data['id']>0){
			$result = $this->db->where('id='.$data['id'])->update($data+$_data);
		}else{
			$data['input_time']=CORE_TIME;
			$data['input_admin']=$_SESSION['name'];
			$result = $this->db->add($data+$_data);
		}
		if($result['err']>0){
			$this->error($result['msg']);
		}
		$this->success('操作成功');
	}
	/**
	 * Ajax删除
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
				$result=$this->db->where("id in ($ids)")->delete();
			}
		}
		if($result){
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}
}