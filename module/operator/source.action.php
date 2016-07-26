<?php
/**
 * 资源库数据采集管理
 */
class sourceAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->db=M('public:common')->model('resourcelib');
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
		}elseif($action=='remove'){ //获取列表
			$this->_remove();exit;
		}elseif($action=='submit'){ //获取列表
			$this->_submit();exit;
		}elseif($action=='save'){ //获取列表
			$this->_save();exit;
		}
		$this->assign('page_title','资源库列表');
		$this->display('source.list.html');
	}

	public function ulist(){
		$this->assign('doact','search');
		$this->assign('page_title','资源库会员发布信息列表');
		$this->display('source.list.html');
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
		//搜索条件
		$where=" 1 ";
		//筛选人工发布或者是系统采集
		$this->doact=='search' ? $where.= ' and `uid` > 0 ' : $where.= ' and `uid` = 0 ';
		//状态
		$status=sget('status','i',0);
		if($status>0){
			$where.=' and status='.($status-1);	
		}
		//推荐
		$elite=sget('elite','i',0);
		if($elite>0){
			$where.=' and elite='.($elite-1);	
		}
		//信息类型
		$type=sget('type','i',0);
		if($type>0){
			$where.=' and `type`='.($type-1);	
		}
		//关键词
		$key_type=sget('key_type','s','content');
		$keyword=sget('keyword','s');
		if(!empty($keyword)){
			if($key_type=='content' || $key_type=='user_nick' || $key_type=='qq_qunname' || $key_type=='qq_qunmingpian' || $key_type=='company' || $key_type=='realname'){
				$where.=" and $key_type like '%$keyword%' ";
			}else{
				$where.=" and $key_type='$keyword' ";
			}
		}
		$list=$this->db->where($where)
					->page($page+1,$size)
					->order("$sortField $sortOrder")
					->getPage();
		foreach($list['data'] as $k=>$v){
			$list['data'][$k]['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
			$list['data'][$k]['update_time']=$v['update_time']>1000 ? date("Y-m-d H:i:s",$v['update_time']) : '-';
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
		foreach($data as $k=>$v){
			$_data=array(
				'content'=>$v['content'],		 
				'status'=>(int)$v['status'],
				'type'=>(int)$v['type'],
				'elite'=>(int)$v['elite'],
				'user_nick'=>$v['user_nick'],
				'user_qq'=>$v['user_qq'],
				'qq_qunname'=>$v['qq_qunname'],
				'qq_qunnum'=>$v['qq_qunnum'],
				'qq_qunmingpian'=>$v['qq_qunmingpian'],
				'update_time'=>CORE_TIME,
				'admin_name'=>$_SESSION['name'],
			);
			if(isset($v['_state']) && $v['_state']=='added'){
				$sql[]=$this->db->addSql($_data+array(
					'input_time'=>CORE_TIME,
					'company'=>$v['company'],
					'realname'=>$v['realname'],
					'uid'=>$v['uid'],
				));
			}else{
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