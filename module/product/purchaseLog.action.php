<?php 
/**
 * 采购订单详情管理
 */
class purchaseLogAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->db=M('public:common')->model('purchase_log');
		$this->doact = sget('do','s');
		$this->assign('price_type',L('price_type')); //价格单位
		$this->assign('invoice_status',L('invoice_status')); //开票状态
		$this->assign('in_storage_status',L('in_storage_status')); //入库状态

		//订单语言包
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
		$this->assign('order_type',L('order_type')); //销售类型
	}
	/**
	 *
	 * @access public
	 * @return html
	 */
	public function init(){
		$doact=sget('do','s');
		$action=sget('action','s');
		$in_status=sget('in_status','i');
		if($action=='grid'){ //获取列表
			$this->_grid();exit;
		}
		$type = sget('type','i',0);
		if ($type == 1) {
			$this->display('billing.add.html');
		}else{
			$this->assign('in_status',$in_status);
			$this->assign('doact',$doact);
			$this->assign('oid',sget('oid','i',0));
			$this->assign('page_title','订单管理列表');
			$this->display('purchaseLog.list.html');
		}
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
		$in_storage_status=sget('in_status','i',0); //入库时选择未入库的订单
		if($in_storage_status != 0) $where.=" and `in_storage_status` =1";
		//开票状态
		$invoice_status=sget('invoice_status','i',0);
		if($invoice_status != 0) $where.=" and `invoice_status` =".$invoice_status;
		//入库状态
		$out_storage_status=sget('out_storage_status','i',0);
		if($out_storage_status != 0) $where.=" and `out_storage_status` =".$out_storage_status;
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
		}elseif(!empty($keyword) && $key_type=='c_id' ){
			$keyword=M('user:customer')->getcidByCname($keyword);
			$where.=" and `$key_type` in ($keyword) ";
		}elseif(!empty($keyword)){
			$where.=" and `$key_type`  like '%$keyword%' ";
		}
		$list=$this->db->where($where)
				->page($page+1,$size)
				->order("$sortField $sortOrder")
				->getPage();
		$tot=0;
		foreach($list['data'] as &$v){		
			$pinfo=M("product:product")->getFnameByPid($v['p_id']);				
			$v['f_name']=$pinfo['f_name'];//根据cid取客户名
			$v['order_name']=M("product:order")->getColByName($v['o_id']);
			$v['model']=M("product:product")->getModelById($v['p_id']);
			$v['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
			$v['update_time']=$v['update_time']>1000 ? date("Y-m-d H:i:s",$v['update_time']) : '-';
			$v['sign_time']=$v['sign_time']>1000 ? date("Y-m-d H:i:s",$v['sign_time']) : '-';
			$v['invoice_status'] = L('invoice_status')[$v['invoice_status']]; 
			$v['in_storage_status'] = L('in_storage_status')[$v['in_storage_status']];
			$v['purchase_type'] = L('purchase_type')[$v['purchase_type']];
			$v['sum'] = $v['unit_price']*$v['number'];
			$tot=$tot+$v['sum'];
		}
		$to='mn';
		$this->assign('to',$to);
		$this->assign('tot',$tot);
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
		$choose=sget('choose','i');
		$sale_id=sget('sale_id','i',0);
		if($id<1){
			if($o_id>0){
				$this->assign('o_id',$o_id);
				$order_name=M("product:order")->getColByName($o_id);
				$this->assign('order_name',$order_name);
			}
			$order_sn=genOrderSn();
			$this->assign('order_sn',$order_sn);
			$this->assign('sale_id',$sale_id);
			$this->assign('choose',$choose);
			$this->display('purchaseLog.edit.html');
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
		$info['c_name']=M("user:customer")->getColByName($info['c_id']);//根据cid取客户名
		//根据pid取厂家名
		$info['store_name']=M("product:store")->getStoreNameBySid($info['store_id']); //根据仓库id取其名
		$info['admin_name']=M("product:outStorage")->getNameBySid($info['store_aid']); //获得入库人姓名
		$admin_list = $this->db->model('store_admin')->select("a.admin_id,a.name")->from('store_admin s')->join('admin a','a.admin_id=s.admin_id')->getAll();
		$this->assign('admin_list',arrayKeyValues($admin_list,'admin_id','name'));//
		$this->assign('process_type',L('process_level'));//加工级别
		$this->assign('purchase_type',L('purchase_type'));//加工级别
		$this->assign('product_type',L('product_type'));//产品类型
		$this->assign('order_name',$order_name);
		$this->assign('model',$model);
		$this->assign('info',$info);//分配订单信息
		$this->assign('type',$type);//分配订单id信息
		if($type =="edit"){
			$this->display('purchaseLog.edit.html');
			exit;
		}
		$this->display('purchaseLog.viewInfo.html');
	}
	/**
	 * 新增及修改订单
	 * @access public 
	 * @return html
	 */
	public function addSubmit() {
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //获取UI传递的参数
		// p($data);die;
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
			$data['order_source']=2;//来源2:erp
			if($data['sale_id'] ==0){
				$result = $this->db->add($data);
			}else{
				$this->db->startTrans(); //开启事务
				try {
					if( !$this->db->model('order')->add($data) )  throw new Exception("新增订单失败");//新增订单
					$o_id=$this->db->getLastID(); //获取新增订单ID
					$data['o_id']=$o_id;
					if( !$this->db->model('purchase_log')->add($data) )  throw new Exception("新增采购明细失败");
				} catch (Exception $e) {
					$this->db->rollback();
					$this->error($e->getMessage());					
				}
				$this->db->commit();
				$this->success();
			}

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