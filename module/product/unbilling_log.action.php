<?php 
/**
 * 采购订单详情管理
 */
class unbilling_logAction extends adminBaseAction {
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
		$this->assign('product_type',L('product_type'));//产品类型
		$this->assign('process_type',L('process_level'));//加工级别
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
		$this->display('billing.add.html');

	}
	/**
	 * Ajax获取列表内容
	 * @access private 
	 * @return html
	 */
	private function _grid($type){
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$o_id = sget('oid','i',0);
		$finance = sget('finance','i',0);//判断是不是财务审核
		//P($type);die;
		$sortField = sget("sortField",'s','input_time'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
		//筛选
		$arr = $this->db->where('o_id='.$o_id)
				->page($page+1,$size)
				->order("$sortField $sortOrder")
				->getPage();
		if ($type==1) {
			$list=M('product:sale_log')->from('sale_log s')
                ->leftjoin('unbilling_log un')
                ->select('s.*,un.billing_number,un.unbilling_id')
                ->where("s.id=un.sale_log_id")
                ->page($page+1,$size)
				->order("$sortField $sortOrder")
				->getPage();
		}else{
			$list=$this->db->model('purchase_log')->from('purchase_log p')
                ->leftjoin('unbilling_log un')
                ->select('p.*,un.billing_number,un.unbilling_id')
                ->where("p.id=un.purchase_log_id")
                ->page($page+1,$size)
				->order("$sortField $sortOrder")
				->getPage();
		}
		if (empty($list['data'])) $list =$arr;
		//p($list);die;
		foreach($list['data'] as &$v){		
			$pinfo=M("product:product")->getFnameByPid($v['p_id']);			
			$v['f_name']=$pinfo['f_name'];//根据cid取客户名
			$v['order_sn']=M("product:order")->getColByName($v['o_id'],'order_sn');//根据oid取订单号
			$v['order_name']=M("product:order")->getColByName($v['o_id']);
			$v['model']=M("product:product")->getModelById($v['p_id']);
			//$v['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
			//$v['update_time']=$v['update_time']>1000 ? date("Y-m-d H:i:s",$v['update_time']) : '-';
			//$v['sign_time']=$v['sign_time']>1000 ? date("Y-m-d H:i:s",$v['sign_time']) : '-';
			$v['invoice_status'] = L('invoice_status')[$v['invoice_status']]; 
			$v['in_storage_status'] = L('in_storage_status')[$v['in_storage_status']];
			$v['purchase_type'] = L('purchase_type')[$v['purchase_type']];
			$v['order_name']=L('company_account')[M("product:order")->getColByName($v['o_id'],'order_name')];
			//p($finance);die;
			//p($v);die;
			
			//开票申请与审核时所需的值
			if($finance==1){
				if (empty($v['billing_number'])) {
					$v['billing_number'] = $v['number'];
				}
				//开票申请与审核时未发送的数量
				//$v['number'] = $v['number']-$v['billing_number'];
				//开票申请与审核的小计
				$v['sum'] = $v['unit_price']*$v['billing_number'];
			}else{
				if ($v['billing_number']) {
					$v['number']=$v['number']-$v['billing_number'];
				}
				$v['sum'] = $v['unit_price']*$v['number'];
			}
		}

		$result=array('total'=>$list['count'],'data'=>$list['data']);
		$this->json_output($result);
	}


}