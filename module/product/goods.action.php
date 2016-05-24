<?php
/**
 * 资源库数据采集管理
 */
class goodsAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->db=M('public:common')->model('points_goods');
		$this->doact = sget('do','s');
		$this->assign('goods_category',L('goods_category')); //商品分类

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
		}elseif($action=='ajaxSave'){ //获取列表
			$this->_ajaxSave();exit;
		}elseif($action=='save'){ //获取列表
			$this->_save();exit;
		}
		$this->assign('page_title','资源库列表');
		$this->display('goods.list.html');
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
		$sortField = sget("sortField",'s','create_time'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
		//搜索条件
		$where=" 1 ";		
		$status=sget('status','i');//状态
		$c_id=sget('cate_id','i');//商品分类id
		$keyword=sget('keyword','s');//关键词

		if($status>0){
			$where.=' and status='.$status;	
		}
		if(!empty($c_id)){
			$where.=' and cate_id='.$c_id;	
		}
		if(!empty($keyword)){
			$where.=" and name like '%$keyword%'";	
		}

		$list=$this->db->where($where)
					->page($page+1,$size)
					->order("$sortField $sortOrder")
					->getPage();
		foreach($list['data'] as $k=>$v){
			$list['data'][$k]['create_time']=$v['create_time']>1000 ? date("Y-m-d H:i:s",$v['create_time']) : '-';
			$list['data'][$k]['goods_category']=L('goods_category')[$v['cate_id']];
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
			$result = $this->db->add($data+array('create_time'=>CORE_TIME));
		}else{
			foreach($data as $k=>$v){
				$_data=$v;
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