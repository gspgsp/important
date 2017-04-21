<?php
/**
*收付款管理控制器
*/
class teamApproveAction extends adminBaseAction
{
	public function __init(){
		$this->db=M('public:common')->model('team_capital_approve');
		$this->assign('pay_method',L('pay_method'));		 	//付款方式
		$this->assign('invoice_status',L('invoice_status'));    //开票状态
		$this->assign('company_account',L('company_account'));  //交易公司账户order_type
		$this->assign('team',L('team')+array('1'=>'其他')); //战队名称
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
		$this->assign('type','2');
		$this->display('teamApprove.list.html');
	}
	/**
	 * Ajax获取列表内容
	 * @access private
	 * @return html
	 */
	private function _grid(){
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$sortField = sget("sortField",'s','apply_time'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
		//搜索条件
		$where ="  1 ";
		//交易日期
		$sTime = sget("sTime",'s','t.`apply_time`'); //搜索时间类型
		$where.= getTimeFilter($sTime); //时间筛选
		//交易方式
		$pay_method = sget("pay_method",'s','');
		if($pay_method!='') $where.=" and `pay_method` = '$pay_method' ";

		//付款主题
		$title = sget("title",'s','');
		if($title!='') $where.=" and `title` = '$title' ";

		//关键词搜索
		$key_type=sget('key_type','s','order_sn');
		$keyword=sget('keyword','s');
		if(!empty($keyword)){
			switch ($key_type) {
				case 'order_sn':
					$newword = "更正".$keyword;
					$where.=" and `order_sn` = '$keyword' or `order_sn` = '$newword'";
					break;
				case 'c_name':
					$c_ids = M('user:customer')->getInfoByCname($key_type,$keyword);
					$str_cids = implode(',',array_values($c_ids));
					$where.=" and `c_id` in ($str_cids)";
					break;
				case 'admin':
					$where.=" and t.`apply_admin` = '$keyword'";
					break;
				default:
					$where.=" and `$key_type`  = '$keyword' ";
					break;
			}
		}
		// p($where);die;
		$list=$this->db->where($where)
					->select("`t`.id AS t_id,`t`.o_id AS oid,t.team_total_money,t.team_name,t.`apply_time`,t.`apply_admin`,t.`review_admin`,t.`review_time`,t.`status`,`c`.*")
					->from('team_capital_approve t')
					->join('collection as c','c.id=t.coll_id')
					->page($page+1,$size)
					->order("$sortField $sortOrder")
					->getPage();
		foreach($list['data'] as $k=>$v){
			$list['data'][$k]['apply_time']=$v['apply_time']>1000 ? date("Y-m-d H:i:s",$v['apply_time']) : '-';
			$list['data'][$k]['review_time']=$v['review_time']>1000 ? date("Y-m-d H:i:s",$v['review_time']) : '-';
			//收付款主题
			$list['data'][$k]['title'] = L('company_account')[$list['data'][$k]['title']];
			$list['data'][$k]['pay_method'] = L('pay_method')[$list['data'][$k]['pay_method']];

			$list['data'][$k]['c_name']=M('user:customer')->getColByName($value=$v['c_id'],$col='c_name',$condition='c_id');
		}

		$msg="";
		$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>$msg);
		$this->json_output($result);
	}
	public function viewInfo(){
		$customer_manager=sget('customer_manager','i',0);
		$team_capital = M('rbac:adm')->getThisMonthTemaCapitalByCustomer($customer_manager);
		if(empty($team_capital)){
			$this->error("该业务员当月战队配资未设置");
		}
		$team_capital['input_date'] = date('Y-m',$team_capital['input_date']);
		$this->assign('info',$team_capital);
		$this->display('teamcapital.viewInfo.html');
	}
	public function check(){
		$id = sget('id','i');
		$t_id = sget('t_id','i');
		$status = sget('status','i');
		if(empty($id) || empty($t_id)){
			$this->error('操作有误');
		}
		$this->db->startTrans();//开启事务
		//特批操作：通过，则财务显示付款申请（修改collection 状态），并且将额度扣除，不通过，则财务不显示付款申请，修改collection状态
		if($status == 1){//通过，改状态，扣额度
			//修改付款申请中的战队配资状态为1:不在特批列表
			$this->db->model('collection')->where("id=".$id)->update('o_id = (-1)*o_id,update_time='.time());
			$coll_res = $this->db->model('collection')->where("id=".$id)->getRow();//获取付款申请数据
			$team_capital = M('rbac:adm')->getThisMonthTemaCapitalByCustomer($coll_res['customer_manager']);//获取战队配资
			if(!$team_capital) $this->error('当前订单业务员所在战队配资额度未设置，请先设置额度');
			//扣额度
			$buy_capital = M('user:teamCapital')->goMoney($team_capital,$coll_res['collected_price']);//采购战队付款后扣除资金
			if(!$buy_capital) $this->error("采购战队特批通过时更新战队配资失败");			
			//新增战队配资变动日志----S
			$team_capital_now = M('rbac:adm')->getThisMonthTemaCapitalByCustomer($coll_res['customer_manager']);
			$remarks = "领导特批通过，削减采购战队额度";
			M('user:teamCapital')->addLog($coll_res['o_id'],$team_capital['team_id'],'buy_pay_pass',$team_capital['available_money'],$team_capital_now['available_money'],1,$coll_res['collected_price'],$remarks);
			//新增战队配资变动日志----E
		}else{//不通过，该状态
			$coll_res = $this->db->model('collection')->where("id=".$id)->getRow();//获取付款申请数据
			$team_capital = M('rbac:adm')->getThisMonthTemaCapitalByCustomer($coll_res['customer_manager']);//获取战队配资
			$this->db->model('collection')->where("id=".$id)->update('collection_status=3,update_time='.time());//修改付款申请状态为3:已取消
			//新增战队配资变动日志----S
			$remarks = "领导特批不通过";
			M('user:teamCapital')->addLog($coll_res['o_id']*(-1),$team_capital['team_id'],'buy_pay_unpass',$team_capital['available_money'],$team_capital['available_money'],1,0,$remarks);
			//新增战队配资变动日志----E
		}
		//修改审批状态
		$result=$this->db->model('team_capital_approve')->where("id=".$t_id)->update(array('status'=>$status,'review_time'=>CORE_TIME,'review_admin'=>$_SESSION['username']));//修改审批状态
		if($this->db->commit()){
			$this->success('操作成功');
		}else{
			$this->db->rollback();
			$this->error('操作失败：'.$this->db->getDbError());                                          
		}
	}
}