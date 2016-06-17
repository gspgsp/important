<?php
/**
*收付款管理控制器
*/
class collectionAction extends adminBaseAction
{
	public function __init(){
		$this->db=M('public:common')->model('collection');
		$this->assign('pay_method',L('pay_method'));		 	//付款方式
		$this->assign('invoice_status',L('invoice_status'));    //开票状态
		$this->assign('company_account',L('company_account'));  //交易公司账户order_type
		// $this->assign('ordertype',L('order_type'));  			//订单类型

	}

	/**
	 *
	 * @access public
	 * @return html
	 */
	public function init(){
		//获取列表数据
		$action=sget('action');
		if($action=='grid'){ //获取列表
			$this->_grid();exit;
		}
		$this->assign('type','1');
		$this->assign('collection_status',L('gatheringt_status'));    //订单收款状态
		$this->assign('page_title','销售收款明细');
		$this->display('collection.list.html');
	}

	/**
	 *
	 * @access public
	 * @return html
	 */
	public function itin(){
		//获取列表数据
		$action=sget('action');
		if($action=='grid'){ //获取列表
			$this->_grid();exit;
		}
		$this->assign('type','2');

		$this->assign('collection_status',L('payment_status'));  //订单付款状态
		$this->assign('page_title','采购付款明细');
		$this->display('collection.list.html');
	}

		/**
	 * Ajax获取列表内容
	 * @access private 
	 * @return html
	 */
	private function _grid(){
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$sortField = sget("sortField",'s','o_id'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
		//搜索条件
		$where = 1;
		$type = sget('type','i');//1销售,2采购
		if ($type == 1) {
			$where=" `order_type`=1 ";
		}elseif($type ==2){
			$where=" `order_type`=2 ";
		}
		$o_id=sget('oid','i',0);
		if($o_id !=0)  $where.=" and `o_id` =".$o_id;
		//交易日期
		$sTime = sget("sTime",'s','payment_time'); //搜索时间类型
		$where.= getTimeFilter($sTime); //时间筛选
		//交易方式
		$pay_method = sget("pay_method",'s','');
		if($pay_method!='') $where.=" and `pay_method` = '$pay_method' ";
		//收付款审核状态
		$collection_status = sget("collection_status",'s','');
		if($collection_status!='') $where.=" and `collection_status` = '$collection_status' ";
		//开票状态
		$invoice_status = sget("invoice_status",'s','');
		if($invoice_status!='') $where.=" and `invoice_status` = '$invoice_status' ";
		//收付款状态
		$company_account = sget("company_account",'s','');
		if($company_account!='') $where.=" and `account` = '$company_account' ";
		//关键词
		$keyword=sget('keyword','s');
		if($keyword!='') $where.=" and `order_sn` = '$keyword' ";
		$list=$this->db->where($where)
					->page($page+1,$size)
					->order("$sortField $sortOrder")
					->getPage();
		foreach($list['data'] as $k=>$v){
			$list['data'][$k]['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
			$list['data'][$k]['update_time']=$v['update_time']>1000 ? date("Y-m-d H:i:s",$v['update_time']) : '-';
			$list['data'][$k]['payment_time']=$v['payment_time']>1000 ? date("Y-m-d H:i:s",$v['payment_time']) : '-';
			$list['data'][$k]['c_name']=M('user:customer')->getColByName($value=$v['c_id'],$col='c_name',$condition='c_id');
			$list['data'][$k]['invoice_status']=M('product:order')->getColByName($value=$v['o_id'],$col='invoice_status',$condition='o_id');

		}
		$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>'');
		$this->json_output($result);	
	}
	

	/**
	 * 充红
	 * @access private 
	 */
	public function changeRed(){
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //获取UI传递的参数
		if(empty($data)) $this->error('错误的操作');
		$arr = M('product:collection')->getLastInfo($name='o_id',$value=$data['oid']);
		$arr2 = array(
			'id'=>'',
			'order_name'=>'退款',
			'order_sn'=>'更正'.$data['o_sn'],
			'collected_price'=>'0',
			'uncollected_price'=>$arr[0]['uncollected_price']+$data['c_price'],
			'refund_amount'=>$data['c_price'],
			'update_time'=>CORE_TIME,
			'update_admin'=>$_SESSION['name'],
			);		
            //退款可能变化的值 [pay_method] => 4   [payment_time] => 0     [account] => 1
 		$update=array_merge($arr[0],$arr2);
		$this->db->startTrans();//开启事务
			try {
				if(!$this->db->model('collection')->add($update) )throw new Exception("新增退款失败");
				if(!$this->db->model('collection')->wherePK($data['id'])->update( array('collection_status'=>4)) )throw new Exception("修改退款状态失败");		
			} catch (Exception $e) {
				$this->db->rollback();
				$this->error($e->getMessage());
			}
		$this->db->commit();
		$this->success('操作成功');

	}
	/**
	 * 保存行内编辑数据
	 * @access public 
	 * @return html
	 */
	public function save(){
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //获取UI传递的参数
		if(empty($data)) $this->error('错误的操作');
		$sql=array();
		foreach($data as $v){
			$_id=$v['id'];
			if($_id>0){
				$update=array(
					'payment_time'=>strtotime($v['payment_time']),
					'input_time'  =>strtotime($v['input_time']),
					'update_time' =>CORE_TIME,
					'update_admin'=>$_SESSION['name'],
					'remark'      =>$v['remark'],
				);
				$sql[]=$this->db->wherePk($_id)->updateSql(saddslashes($update));
			}
		}
		if(empty($sql)){
			$this->error('操作数据为空');
		}
		$result=$this->db->commitTrans($sql);
		if($result){
			$cache=cache::startMemcache();
			$cache->delete('factory');
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}

}