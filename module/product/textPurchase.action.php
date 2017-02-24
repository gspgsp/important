<?php
/**
 * 文本采购
 */
class textPurchaseAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->doact = sget('doact','s');
		$this->db=M('public:common')->model('purchase');
	}
	/**
	 * 会员列表
	 * @access public 
	 * @return html
	 */
	public function init(){
		$action=sget('action','s');
		if($action=='grid'){ //获取列表
			$this->_grid();exit;
		}elseif($action=='remove'){ //获取列表
			$this->_remove();exit;
		}
		$this->assign('id',sget('id','i',''));
		$this->assign('doact',$this->doact);
		$this->assign('slt','slt');
		$this->assign('ctype','2');
		$this->assign('page_title','文本采购列表');
		$this->display('textpurchase.list.html');
	}

	/**
	 * Ajax获取列表内容
	 * @access private
	 * @return html
	 */
	private function _grid(){
		$slt = sget("do",'s','');
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$sortField = sget("sortField",'s','input_time'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
		//搜索条件
		$where=" 1  and `type` = 1 and `is_union` = 0 and `p_id` = 0 ";   //1 采购
		$sTime = sget("sTime",'s','input_time'); //搜索时间类型
		$where.= getTimeFilter($sTime); //时间筛选
		// 状态
		$status = sget("status",'s',''); 
		if($status!='') $where.=" and p.status='$status' ";
		//关键词搜索
		$key_type=sget('key_type','s','p.period');
		$keyword=sget('keyword','s');
		if(!empty($keyword)){
			if($key_type == 'f_name'){
				$result = M('product:factory')->getIdsByName($keyword);
				$result = implode($result,',');
				$where.=" and pd.f_id in ($result) ";
			}else if($key_type == 'username'){
				//方法是getOne,所以不考虑同名的情况
				$ids = M('rbac:adm')->getAdmin_Id($keyword);
				$where.=" and `customer_manager`='$ids' ";
			}else{
				$where.=" and `$key_type`='$keyword' ";
			}
			
		}
		$list=$this->db->where($where)
				->page($page+1,$size)
				->order("$sortField $sortOrder")
				->getPage();
		foreach($list['data'] as $k=>$v){
			$list['data'][$k]['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
			$list['data'][$k]['update_time']=$v['update_time']>1000 ? date("Y-m-d H:i:s",$v['update_time']) : '-';
			$list['data'][$k]['username'] = M('rbac:adm')->getUserByCol($v['customer_manager'],'name');
			$list['data'][$k]['c_name'] = M('user:customer')->getColByName($v['c_id']);
			$list['data'][$k]['name'] = M('user:customerContact')->getNameByUserId($v['user_id']);
			$list['data'][$k]['customer_manager'] = M('user:customerContact')->getCusNameByUserId($v['user_id']);
		}
		$result=array('total'=>$list['count'],'data'=>$list['data']);
		$this->json_output($result);
	}
	/**
	 * Ajax删除节点s
	 * @access private 
	 */
	public function remove(){
		$this->is_ajax=true; //指定为Ajax输出
		$ids=sget('ids','s');
		if(empty($ids)){
			$this->error('操作有误');	
		}
		$result=$this->db->where("id in ($ids)")->delete();
		if($result){
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}

}