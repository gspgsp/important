<?php
/**
 * 销售订单详情管理
 */
class saleLogAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->db=M('public:common')->model('sale_log');
		$this->doact = sget('do','s');
		$this->assign('price_type',L('price_type')); //价格单位
		$this->assign('company_account',L('company_account')); //公司抬头
		$this->assign('invoice_status',L('invoice_status')); //开票状态
		$this->assign('out_storage_status',L('out_storage_status')); //出库状态
	}
	/**
	 *
	 * @access public
	 * @return html
	 */
	public function init(){
		$doact=sget('do','s');
		$action=sget('action','s');

		//如果type ==1就是开票新增页面
		$type = sget('type','i',0);
		if($action=='grid'){ //获取列表
			$this->_grid($type);exit;
		}
		//显示新增开票信息页面
		if ($type == 1) {
			$this->display('billing.add.html');
		}else{
			$this->assign('doact',$doact);
			$this->assign('oid',sget('oid','i',0));
			$this->assign('page_title','订单管理列表');
			$this->display('saleLog.list.html');
		}
	}
	/**
	 * Ajax获取列表内容
	 * @access private
	 * @return html
	 */
	private function _grid($type){
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$sortField = sget("sortField",'s','input_time'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
		//筛选
		if($type==1){//开票审核时，数量已完成的不显示
			$where.= "billing_number <> number";
		}else{
			$where .=' 1 ';
		}
		$o_id=sget('oid','i',0);
		if($o_id !=0)  $where.=" and `o_id` =".$o_id;
		//开票状态
		$invoice_status=sget('invoice_status','i',0);
		if($invoice_status != 0) $where.=" and `invoice_status` =".$invoice_status;
		//入库状态
		$out_storage_status=sget('out_storage_status','i',0);
		if($out_storage_status != 0) $where.=" and `out_storage_status` =".$out_storage_status;
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
		}elseif(!empty($keyword) && $key_type=='o_sn' ){
			$keyword=M('product:purchase')->getoidBysn($keyword);
			$where.=" and `o_id` = '$keyword' ";
		}elseif(!empty($keyword)&&$key_type=='c_id'){
			$keyword=M('product:order')->getIdByCname($keyword);
			$where.=" and `o_id` in ($keyword) ";
		}elseif(!empty($keyword) && $key_type=='input_admin'  ){
			$admin_id = M('rbac:adm')->getAdmin_Id($keyword);
			$where.=" and `customer_manager` = '$admin_id'";
		}elseif(!empty($keyword)){
			$where.=" and `$key_type`  like '%$keyword%' ";
		}
		//p($where);die;
		//筛选过滤自己的订单信息
		if($_SESSION['adminid'] != 1 && $_SESSION['adminid'] > 0){
			$sons = M('rbac:rbac')->getSons($_SESSION['adminid']);
			$where .= " and `customer_manager` in ($sons) ";
		}
		$list=$this->db->where($where)
				->page($page+1,$size)
				->order("$sortField $sortOrder")
				->getPage();
		foreach($list['data'] as $k=>$v){
			$pinfo=M("product:product")->getFnameByPid($v['p_id']);
			$list['data'][$k]['f_name']=$pinfo['f_name'];//根据cid取客户名
			$list['data'][$k]['order_sn']=M("product:order")->getColByName($v['o_id'],'order_sn');//根据oid取订单号
			$list['data'][$k]['c_name']=M("product:order")->getCnameByOid($v['o_id']);//根据oid取客户名
			$list['data'][$k]['order_name']=L('company_account')[M("product:order")->getColByName($v['o_id'])];
			$list['data'][$k]['store_name']=M("product:store")->getStoreNameBySid($v['store_id']);
			$list['data'][$k]['model']=strtoupper(M("product:product")->getModelById($v['p_id']));
			//比较价格较上次是否小于1%
			$list['data'][$k]['min_price'] = M('product:factory')->minPrice($v['p_id'],$v['input_time']);
			$list['data'][$k]['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
			$list['data'][$k]['update_time']=$v['update_time']>1000 ? date("Y-m-d H:i:s",$v['update_time']) : '-';
			$list['data'][$k]['sign_time']=$v['sign_time']>1000 ? date("Y-m-d H:i:s",$v['sign_time']) : '-';
			$list['data'][$k]['invoice_status'] = L('invoice_status')[$v['invoice_status']];
			$list['data'][$k]['out_storage_status'] = L('out_storage_status')[$v['out_storage_status']];
			$list['data'][$k]['sales_type'] = L('sales_type')[$v['sales_type']];
			$list['data'][$k]['cmanager'] = M('rbac:adm')->getUserByCol($v['customer_manager']);
			$list['data'][$k]['total'] = price_format($v['unit_price']*$v['number']);
			//开票申请与审核时所需的值
			if($type==1){
				//开票申请与审核时已未发送的数量
				$v['number'] = $v['number']-$v['billing_number'];
				//开票申请与审核的小计
				$list['data'][$k]['sum'] = $v['unit_price']*$v['number'];
			}
			//出库确认
			$list['data'][$k]['out_check'] = intval(M("product:outStorage")->getFildById($v['o_id'],$v['p_id']));
			//$tot +=$list['data']['sum'];
		}
		//$to="mn";
		//$this->assign('to',$to);
		//$this->assign('tot',$tot);
		$msg="";
		if($list['count']>0){
			// 循环计算小计
			$tol = 0;
			foreach ($list['data'] as $k => $v) {
				$tol += $v['total'];
			}
			$sum=$this->db->select("sum(number) as wsum, sum(remainder) as msum")->where($where)->getRow();
			$msg="[筛选结果]数量:【".$sum['wsum']."】未入数量:【".$sum['wsum']."】小计：【".price_format($tol)."】";
		}
		$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>$msg);
		$this->assign('doact',$doact);
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
		$choose=sget('choose','i',0);
		$nostore=sget('nostore','i',0); //不销库存
		$require_num=sget('require_num','i',0);
		$this->assign('require_num',$require_num);
		if($id<1){
			$this->assign('choose',$choose); //用于miniopen 弹窗的销毁按钮
			if($o_id>0){
				$this->assign('o_id',$o_id);
				$order_name=M("product:order")->getColByName($o_id);
				$this->assign('order_name',$order_name);
			}
			if($nostore>0){ //表示不消耗库存的明细新增
				$this->display('saleLogWithPur.edit.html');
				exit;
			}
			$this->display('saleLog.edit.html');
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
		if($type !="edit") $info['sales_type']=M('product:order')->getColByName($info['o_id'],'sales_type'); //根据pid取厂家名

		$info['count']=$info['number']*$info['unit_price'];
		$info['c_name']=M("user:customer")->getColByName($info['c_id']);//根据cid取客户名
		//根据pid取厂家名
		$info['store_name']=M("product:store")->getStoreNameBySid($info['store_id']); //根据仓库id取其名
		$info['admin_name']=M("product:outStorage")->getNameBySid($info['store_aid']); //获得出库人姓名
		$admin_list = $this->db->model('store_admin')->select("a.admin_id,a.name")->from('store_admin s')->join('admin a','a.admin_id=s.admin_id')->getAll();
		$this->assign('admin_list',arrayKeyValues($admin_list,'admin_id','name'));//分配仓库对应的交易员
		$this->assign('process_type',L('process_level'));//加工级别
		$this->assign('sales_type',L('sales_type'));//加工级别
		$this->assign('product_type',L('product_type'));//产品类型
		$this->assign('order_name',L('company_account')[$order_name]);
		$this->assign('model',$model);
		$this->assign('info',$info);//分配订单信息
		$this->assign('type',$type);//分配订单id信息
		if($type =="edit"){
			$this->display('saleLog.edit.html');
			exit;
		}
		$this->display('saleLog.viewInfo.html');
	}
	/**
	 * 新增及修改订单
	 * @access public
	 * @return html
	 */
	public function addSubmit() {
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //获取UI传递的参数
		$data['number'] = $data['require_number'];
		$data['input_time'] = CORE_TIME;
		$data['input_admin'] = $_SESSION['name'];
		if(empty($data)) $this->error('错误的请求');
		$up_data = array(
				'update_time'=>CORE_TIME,
				'update_admin'=>$_SESSION['name'],
		);
		$time_price=$data['require_number']*$data['unit_price'];
		$this->db->startTrans();//开启事务
		try {
			if( !$this->db->model('sale_log')->add($data) ) throw new Exception("新增订单明细失败!");
			if( !$this->db->model('in_log')->where('id = '.$data['inlog_id'])->update(' controlled_number = controlled_number - '.$data['require_number'].' , lock_number = lock_number + '.$data['require_number'].' , update_time='.CORE_TIME.' , `update_admin` =" '.$_SESSION['name'].'" ') ) throw new Exception("同步操作库存失败!");
			if( !$this->db->model('order')->where('o_id = '.$data['o_id'])->update('total_price = total_price +'.$time_price) ) throw new Exception("订单总金额更新失败!");
		} catch (Exception $e) {
			showtrace();
			$this->db->rollback();
			$this->error($e->getMessage());
		}
		$this->db->commit();
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
}