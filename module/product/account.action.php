<?php
/**
 * 资源库数据采集管理
 */
class accountAction extends adminBaseAction {
	public function __init(){
		$this->db=M('public:common')->model('company_account');
		$this->doact = sget('do','s');

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
		}elseif($action=='remove'){ //删除列表数据
			$this->_remove();exit;
		}elseif($action=='save'){ //获取列表
			$this->_save();exit;
		}
		$this->assign('page_title','资源库列表');
		$this->display('account.list.html');
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
		$where=" 1 ";		
		$keyword=sget('keyword','s');//关键词

		if(!empty($keyword)){
			$where.=" and `account_no` = '$keyword'";
		}
		$list=$this->db->where($where)
					->page($page+1,$size)
					->order("$sortField $sortOrder")
					->getPage();
		foreach($list['data'] as $k=>$v){
			$list['data'][$k]['update_time']=$v['update_time']>1000 ? date("Y-m-d H:i:s",$v['update_time']) : '-';
			$list['data'][$k]['type'] = L('account_status')[$list['data'][$k]['type']];//账户类型	

			$list['data'][$k]['update_admin']=$this->db->model('admin')->where('admin_id ='.$v['update_admin'])->select('name')->getOne();
		}
		$result=array('total'=>$list['count'],'data'=>$list['data']);
		$this->json_output($result);
	}	
	/**
	 * Ajax删除节点s
	 * @access private 
	 */
	private function _remove(){
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
	
	/**
	 * 编辑已存在的数据
	 * @access public 
	 * @return html
	 */
	private function _save(){
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //获取UI传递的参数

		$sql=array();
		if(empty($data)){
			$this->error('操作数据为空');
		}
		if(($this->doact)=='add'){
			$data['update_time']=strtotime($data['update_time']);
			$result = $this->db->add($data+array('update_admin'=>$_SESSION['adminid']));
		}else{
			foreach($data as $k=>$v){
				$_data=$v;
				$_data['update_time']=strtotime($_data['update_time']);//创建时间，不会变化
				$_data['update_admin']=$_SESSION['adminid'];			//修改者，随操作人变化
				$sql[]=$this->db->wherePk($v['id'])->updateSql($_data);
			}
		}
		$result=$this->db->commitTrans($sql);
		if($result){
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}
	
	
		
	
}