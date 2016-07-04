<?php
/**
 * 资源库数据采集管理
 */
class accountlogAction extends adminBaseAction {
	public function __init(){
		$this->db=M('public:common')->model('company_account_log');
		$this->assign('account_type',L('account_type'));//记账类型
		$this->assign('order_type',L('order_type'));//订单类型
		$this->assign('order_chanel',L('order_chanel'));//订单来源
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
		$this->display('accountlog.list.html');
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
		$o_chanel=sget('order_chanel','i');//订单来源
		$o_type=sget('order_type','i');//订单类型
		$a_type=sget('type','i');//记账类型
		$keyword=sget('keyword','s');//关键词

		if(!empty($o_chanel)){
			$where.=" and `order_chanel` = '$o_chanel'";
		}
		if(!empty($o_type)){
			$where.=" and `order_type` = '$o_type'";
		}
		if(!empty($a_type)){
			$where.=" and `type` = '$a_type'";
		}
		if(!empty($keyword)){
			//在只有中晨梓辰两个账户时，默认id=1为中晨，id=2为梓辰，后期调整时改账户为点选，传id用于查询
			$keyword=$keyword=='中晨'?1:2;
			$where.=" and `account_id` = '$keyword'";
		}
		$list=$this->db->where($where)
					->page($page+1,$size)
					->order("$sortField $sortOrder")
					->getPage();
		foreach($list['data'] as $k=>$v){
			$list['data'][$k]['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
			$list['data'][$k]['input_admin']=$this->db->model('admin')->where('admin_id ='.$v['input_admin'])->select('name')->getOne();
			$list['data'][$k]['account_name']=$this->db->model('company_account')->where('id ='.$v['account_id'])->select('account_name')->getOne();

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