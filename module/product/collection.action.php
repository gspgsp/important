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
		$this->assign('order_type',L('order_type'));  			//订单类型
		$this->assign('collection_status',array(1=>'待收付款',2=>'部分收付款',3=>'已完成'));  //收付款审核状态
	}
	public function init(){
		//获取列表数据
		$action=sget('action');
		$doact=sget('do','s');
		if($action=='grid'){
			$page = sget("pageIndex",'i',0); //页码
			$size = sget("pageSize",'i',20); //每页数
			$sortField = sget("sortField",'s','id'); //排序字段
			$sortOrder = sget("sortOrder",'s','desc'); //排序
			//搜索条件
			$where=" 1 ";
			//交易日期
			$sTime = sget("sTime",'s','payment_time'); //搜索时间类型
			$where.= getTimeFilter($sTime); //时间筛选
			//订单类型
			$order_type = sget("order_type",'s','');
			if($order_type!='') $where.=" and `order_type` = '$order_type' ";
			//交易方式
			$pay_method = sget("pay_method",'s','');
			if($pay_method!='') $where.=" and `pay_method` = '$pay_method' ";
			//收付款审核状态
			$collection_status = sget("collection_status",'s','');
			if($collection_status!='') $where.=" and `collection_status` = '$collection_status' ";
			//开票状态
			$invoice_status = sget("invoice_status",'s','');
			if($invoice_status!='') $where.=" and `invoice_status` = '$invoice_status' ";
			//开票状态
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
			}
			$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>'');
			$this->json_output($result);
		}
		$this->assign('doact',$doact);

		$this->assign('page_title','收付款明细');
		$this->display('collection.list.html');
   	}

	/**
	 * 充红
	 * @access private 
	 */
	public function remove(){
		
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