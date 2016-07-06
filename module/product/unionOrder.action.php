<?php 
/**
 * 订单管理
 */
class unionOrderAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->db=M('public:common')->model('union_order');
		$this->doact = sget('do','s');
		$this->assign('order_source',L('order_source')); //订单来源
		$this->assign('pay_method',L('pay_method')); //付款方式
		$this->assign('transport_type',L('transport_type')); //运输方式
		$this->assign('business_model',L('business_model')); //业务模式
		$this->assign('financial_records',L('financial_records')); //财务记录
		$this->assign('order_status',L('order_status')); //订单审核
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
		$order_type=sget('order_type','s');
		if($action=='grid'){ //获取列表
			$this->_grid();exit;
		}
		$this->assign('order_type',$order_type);
		$this->assign('doact',$doact);
		$this->assign('page_title','订单管理列表');
		$this->display('union_order.list.html');
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
		$where = "  1 ";
		//筛选状态
		$order_status=sget('order_status','i',0);//订单审核
		if($order_status !=0)  $where.=" and `order_status` =".$order_status;
		$goods_status=sget('goods_status','i',0);//发货状态
		if($goods_status !=0)  $where.=" and `order_source` =".$goods_status;
		$pay_method=sget('pay_method','i',0);//付款方式
		if($pay_method !=0) $where .= " and `pay_method` = $pay_method ";
		$transport_type =sget('transport_type','i',0);
		if($transport_type != 0) $where .= " and `transport_type` = $transport_type";
		//筛选时间
		$sTime = sget("sTime",'s','input_time'); //搜索时间类型
		$where.=getTimeFilter($sTime); //时间筛选
		//关键词搜索
		$key_type=sget('key_type','s','order_sn');
		$keyword=sget('keyword','s');
		if(!empty($keyword) && $key_type=='order_name'  ){
			$where.=" and `$key_type`  like '%$keyword%' ";
		}elseif(!empty($keyword) && $key_type=='c_id'){
			$keyword=M('product:order')->getOidByCname($keyword);
			$where.=" and `$key_type` in ('$keyword') ";
		}elseif(!empty($keyword)){
			$where.=" and `$key_type`  = '$keyword' ";
		}
		$list=$this->db->where($where)
				->page($page+1,$size)
				->order("$sortField $sortOrder")
				->getPage();
		foreach($list['data'] as $k=>$v){
			$list['data'][$k]['bc_name']=M("user:customer")->getColByName($v['sale_id']);//根据cid取客户名
			$list['data'][$k]['sc_name']=M("user:customer")->getColByName($v['buy_id']);//根据cid取客户名
			$list['data'][$k]['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
			$list['data'][$k]['update_time']=$v['update_time']>1000 ? date("Y-m-d H:i:s",$v['update_time']) : '-';
			$list['data'][$k]['sign_time']=$v['sign_time']>1000 ? date("Y-m-d H:i:s",$v['sign_time']) : '-';
			$list['data'][$k]['pay_method'] =L('pay_method')[$v['pay_method']];
			$list['data'][$k]['transport_type']=L('transport_type')[$v['transport_type']];
			$list['data'][$k]['order_status']=L('order_status')[$v['order_status']];
			$list['data'][$k]['goods_status']=L('goods_status')[$v['goods_status']];
			$list['data'][$k]['invoice_status']=L('invoice_status')[$v['invoice_status']];
		}
		$result=array('total'=>$list['count'],'data'=>$list['data']);
		$this->json_output($result);
	}
	/**
	* 订单信息
	* @access public
	*/
	public function info(){
		$o_id=sget('oid','i',0);
		$type=sget('type','s');
		if($o_id<1){
			$order_sn=genOrderSn();
			$this->assign('order_sn',$order_sn);
			$this->assign('otype','addopus');
			$this->display('union_order.edit.html');
			exit;
		}
		$info=$this->db->getPk($o_id); //查询订单信息
		if(empty($info)){
			$this->error('错误的订单信息');	
		}
		if($info['sale_id']>0) $info['sale_id'] = M('user:customer')->getColByName("$info[sale_id],c_name");
		if($info['buy_id']>0) $info['buy_id'] = M('user:customer')->getColByName("$info[buy_id],c_name");
		$info['delivery_location'] = M('system:region')->get_name($info['delivery_location']);
		$info['pickup_location'] = M('system:region')->get_name($info['pickup_location']);
		$info['sign_time']= $info['sign_time'] > 1000 ? date("Y-m-d",$info['sign_time']) : '-';
		$info['pickup_time']= $info['pickup_time'] > 1000 ? date("Y-m-d",$info['pickup_time']) : '-';
		$info['delivery_time']= $info['delivery_time'] > 1000 ? date("Y-m-d",$info['delivery_time']) : '-';
		$info['payment_time']= $info['payment_time'] > 1000 ? date("Y-m-d",$info['payment_time']) : '-';
		$this->assign('type',$type);
		$this->assign('info',$info);//分配订单信息
		if($type=="edit"){
			$this->display('union_order.edit.html');
			exit;
		}	
		$order_type = $info['order_type'] == 1? 'saleLog' : 'purchaseLog';


		$this->assign('order_type',$order_type);
		$this->assign('o_id',$o_id);
		$this->display('union_order.viewInfo.html');
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
				$result = $this->db->model('union_order_detail')->where("o_id = $v")->getRow();
				if(!empty($result)){
					$this->error('该订单存在订单明细，请先删除订单明细！');
					continue;
				}else{
					$result=$this->db->model('union_order')->where("id = ($v)")->delete();
				}
				
			}
		}
		if($result){
			$this->success('操作成功');
		}else{
			$this->error('订单有相关明细存在');
		}
	}
	/**
	 * 保存发货信息
	 */
	public function shipSave(){
	$this->is_ajax = true;
	$data = sdata();
	$id = $data['id'];
	if(empty($id)) $this->error('操作有误！');
	$data['delivery_time'] = strtotime($data['delivery_time']);
	$result = $this->db->where("id = $id")->update($data+array('update_time'=>CORE_TIME, 'update_admin'=>$_SESSION['name'],));
	if($result) $this->success('操作成功！');
	$this->error('操作失败');
	}
}