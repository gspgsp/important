<?php
/**
*开票管理控制器
*/
class billingAction extends adminBaseAction
{
	public function __init(){
		$this->db=M('public:common')->model('billing');
		$this->assign('bile_type',L('bile_type'));		 	 	//票据类型
		$this->assign('invoice_status',L('invoice_one_status'));    //开票状态
		$this->assign('billing_type',L('billing_type'));    	//开票类型
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
		$this->assign('page_title','销售开票明细');
		$this->display('billing.list.html');
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
		$this->assign('page_title','采购开票明细');
		$this->display('billing.list.html');
	}
	/**
	 * Ajax获取列表内容
	 * @access private 
	 * @return html
	 */
	private function _grid(){
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$sortField = sget("sortField",'s','id'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
		//搜索条件
		$where = 1;
		$type = sget('type','i');
		if ($type == 1) {
			$where .=" and `billing_type`= 1 ";
		}elseif($type ==2){
			$where .=" and `billing_type`= 2 ";
		}
		//tap项的传值搜索
		$oid = sget('oid','i',0);
		if($oid!=0) $where.=" and `o_id` = '$oid' ";
		//交易日期
		$sTime = sget("sTime",'s','payment_time'); //搜索时间类型
		$where.= getTimeFilter($sTime); //时间筛选
		//票据类型
		$bile_type = sget("bile_type",'s','');
		if($bile_type!='') $where.=" and `bile_type` = '$bile_type' ";
		//开票状态
		$invoice_status = sget("invoice_status",'s','');
		if($invoice_status!='') $where.=" and `invoice_status` = '$invoice_status' ";
		//关键词搜索
		$key_type=sget('key_type','s','order_sn');
		$keyword=sget('keyword','s');
		if(!empty($keyword)){
			$where.=" and `$key_type`  = '$keyword' ";
		}
		$list=$this->db->where($where)
					->page($page+1,$size)
					->order("$sortField $sortOrder")
					->getPage();
		foreach($list['data'] as $k=>$v){
			$list['data'][$k]['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
			$list['data'][$k]['update_time']=$v['update_time']>1000 ? date("Y-m-d H:i:s",$v['update_time']) : '-';
			$list['data'][$k]['payment_time']=$v['payment_time']>1000 ? date("Y-m-d H:i:s",$v['payment_time']) : '-';
			//开票主题
			$list['data'][$k]['title'] = L('company_account')[$list['data'][$k]['title']];

			$list['data'][$k]['c_name']=M('user:customer')->getColByName($value=$v['c_id'],$col='c_name',$condition='c_id');
			empty($v['accessory'])?:$list['data'][$k]['accessory']=FILE_URL.'/upload/'.$v['accessory'];
		}
		$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>'');
		$this->json_output($result);
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
					'paying_info' =>$v['paying_info'],
            		'receipt_info'=>$v['receipt_info'],
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
			$cache->delete('billing');
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}
	/**
	 * 开票充红
	 * @access private 
	 */
	public function changeRed(){
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //获取UI传递的参数
		if(empty($data)) $this->error('错误的操作');
		$arr = M('product:billing')->getLastInfo($name='o_id',$value=$data['oid']);
		$arr2 = array(
			'id'=>'',
			'order_name'=>'更正开票',
			'order_sn'=>'更正'.$data['o_sn'],
			'billing_price'=>'0',
			'unbilling_price'=>$arr[0]['unbilling_price']+$data['b_price'],
			'refund_amount'=>$data['c_price'],
			'update_time'=>CORE_TIME,
			'update_admin'=>$_SESSION['name'],
			'invoice_status'=>2,
			);		
            //开票可能变化的值 [pay_method] => 4   [payment_time] => 0     [account] => 1
 		$update=array_merge($arr[0],$arr2);
		$this->db->startTrans();//开启事务
			try {
				if(!$this->db->model('billing')->add($update) )throw new Exception("新增退款失败");
				if(!$this->db->model('billing')->wherePK($data['id'])->update( array('invoice_status'=>3)) )throw new Exception("修改退款状态失败");
				//根据撤销付款金额与总金额的大小，判断订单付款状态
				if($data['total_price'] == $data['b_price']){
					if(!$this->db->model('order')->wherePK($data['oid'])->update( array('invoice_status'=>1)) )throw new Exception("修改订单表退款状态失败");
				}else{
					if(!$this->db->model('order')->wherePK($data['oid'])->update( array('invoice_status'=>2)) )throw new Exception("修改订单表退款状态失败");
				}
				
			} catch (Exception $e) {
				$this->db->rollback();
				$this->error($e->getMessage());
			}
		$this->db->commit();
		$this->success('操作成功');

	}

	/**
	 * 处理数量
	 * @access private 
	 */
	public function changeNumber(){
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //获取UI传递的参数
		$index = sget('index','i');
		if(empty($data)) $this->error('错误的操作');
		//for循环遍历
		$unit_price =0;
		for($i=0;$i<count($data);$i++){
			$unit_price = ($data[$index]['unit_price']);
			continue;
		}
		$result=array('unit_price'=>$unit_price);
		$this->json_output($result);
		//$this->assign('unit_price',$unit_price);
		//p($unit_price);

	}

}