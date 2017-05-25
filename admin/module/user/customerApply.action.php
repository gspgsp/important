<?php
/**
*客户共享申请
*/
class customerApplyAction extends adminBaseAction
{
	public function __init(){
		$this->db=M('public:common')->model('customer_share_apply');
		$this->assign('type',L('company_type'));//工厂类型
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
		$this->assign('ids',sget('ids','s'));
		$this->display('customerApply.list.html');
	}
	/**
	 * Ajax获取列表内容
	 * @access private
	 * @return html
	 */
	private function _grid(){
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		// $sortField = sget("sortField",'s','input_time,status'); //排序字段
		// $sortOrder = sget("sortOrder",'s','desc,asc'); //排序
		//搜索条件
		$where ="  1 ";
		//交易日期
		$sTime = sget("sTime",'s','`input_time`'); //搜索时间类型
		$where.= getTimeFilter($sTime); //时间筛选

		//关键词搜索
		$key_type=sget('key_type','s','admin');
		$keyword=sget('keyword','s');
		if(!empty($keyword)){
			switch ($key_type) {
				case 'admin':
					$newword = $keyword;
					$admin_id = M('rbac:adm')->getAdmin_Id($keyword);
					$where.=" and `apply_uid` =".$admin_id;
					break;
			}
		}
		//首页 共享申请的查询,传id的字符串 如 (1,2,3,4)
		$ids=sget('ids','s');
		if($ids)  $where.=" and `id` in ".$ids;
		if($_SESSION['adminid'] != 1 && $_SESSION['adminid'] > 0){
			$where .= " and (`apply_to_uid` = {$_SESSION['adminid']})  ";
		}
		$list=$this->db->where($where)
					->select("a.*,c.type")
					->from('customer_share_apply a')
					->leftjoin('customer c','c.c_id = a.c_id')
					->page($page+1,$size)
					->order("input_time desc,status asc")
					->getPage();
					// showtrace();
		foreach($list['data'] as $k=>$v){
			$list['data'][$k]['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
			$list['data'][$k]['update_time']=$v['update_time']>1000 ? date("Y-m-d H:i:s",$v['update_time']) : '-';
			$list['data'][$k]['apply_name'] =M('rbac:adm')->getUserByCol($v['apply_uid']);
			// $list['data'][$k]['update_name'] =M('rbac:adm')->getUserByCol($v['upd_uid']);
			$list['data'][$k]['type']=L('company_type')[$v['type']];
			$list['data'][$k]['c_name']=M('user:customer')->getColByName($value=$v['c_id'],$col='c_name',$condition='c_id');
		}

		$msg="";
		$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>$msg);
		$this->json_output($result);
	}
	public function share(){
		$id=sget('id','i',0);
		if(empty($id)) $this->error('信息错误');
		$find_res = $this->db->model('customer_share_apply')->select('*')->where("id = ".$id)->getRow();
		$exits = $this->db->model('customer_pool')->where("`customer_manager` = {$find_res['apply_uid']} and `c_id` = {$find_res['c_id']}")->getRow();
		if($exits){
			$this->db->model('customer_share_apply')->where('id='.$id)->update(array('update_admin'=>$_SESSION['username'],'update_time'=>CORE_TIME,'status'=>2));
			$this->error('共享记录已经存在');	
		}
		//处理工厂客户不能共享问题(1为工厂，工厂客户不能共享)
		// $ty = intval($this->db->model('customer')->select("`type`")->where("`c_id` = {$data['c_id']}")->getOne());
		// if($ty == 1) $this->error("根据公司规定，工厂客户不能共享，找领导去吧");
		//新增客户流转记录日志----S
		$old_cusid = M('user:customer')->getColByName($find_res['c_id'],'customer_manager');
		$old_cus = M('rbac:adm')->getUserByCol($old_cusid);//查询共享给人姓名
		$new_cus = M('rbac:adm')->getUserByCol($find_res['apply_uid']);//查询共享给人姓名
		$remarks = "客户共享操作:".$old_cus."把客户共享给".$new_cus;// 审核用户
		M('user:customerLog')->addLog($find_res['c_id'],'share',$old_cus,$new_cus,1,$remarks);
		//新增客户流转记录日志----E
		$result = $this->db->model('customer_pool')->add(array('customer_manager'=>$find_res['apply_uid'],'c_id'=>$find_res['c_id'],'input_time'=>CORE_TIME,'input_admin'=>$_SESSION['name'],'share_manager'=>$_SESSION['adminid'],'share_managername'=>$_SESSION['username']));
		if($result){
			$this->db->model('customer_share_apply')->where('id='.$id)->update(array('update_admin'=>$_SESSION['username'],'update_time'=>CORE_TIME,'status'=>2));
			$this->success('操作成功');
		}else{
		 	$this->error('操作失败');	
		}
	}
}