<?php
/** 
 * 分类管理
 * Andy@2013-03-22
 */
class cateAction extends adminBaseAction {
	private $hasChild=0; //是否允许子分类
	private $title='分类管理'; //
	public function __init(){
		$this->debug = false;
		$this->action = sget('action','');
		$this->db=M('public:common')->model('cate');
	}

	/**
	 * 站内资讯分类
	 * @access public 
	 * @return html
	 */
	public function info(){
		$this->hasChild=1;
		$this->title='站内资讯分类';
		$this->_list(1);
	}
 
	/**
	 * 帮助中心分类管理
	 * @access public 
	 * @return html
	 */
	public function help(){
		$this->hasChild=0;
		$this->title='帮助中心分类';
		$_GET['pid'] = M('system:cate')->getfieldbyspell('help','cate_id');
		$this->_list(1);
	}

	/**
	 * 列表分类
	 * @access private 
	 * @return html
	 */
	private function _list($cate_type=0){
		$pid=sget('pid','i');
		$action=sget('action','s');
		if($action=='grid'){
			$page = sget("pageIndex",'i',0); //页码
			$size = sget("pageSize",'i',20); //每页数
			$size = 100000; //取全部数据
			$sortField = sget("sortField",'s','sort_order'); //排序字段
			$sortOrder = sget("sortOrder",'s','asc'); //排序

			//搜索条件
			$where=" pid='$pid' and cate_type='$cate_type' ";
			
			$list=$this->db->where($where)
						->page($page+1,$size)
						->order("$sortField $sortOrder")
						->getPage();
			$result=array('total'=>$list['count'],'data'=>$list['data']);
			$this->json_output($result);
		}
		$this->assign('pid',$pid);
		
		if($pid>0){
			$parent=$this->db->getPk($pid);
			$parent['next']=$parent['level']+1;
		}else{
			$parent=array('pid'=>0,'cate_type'=>$cate_type,'next'=>1);
		}

		$this->assign('parent',$parent);
		$this->assign('action',ROUTE_A); //当前方法
		$this->assign('hasChild',(int)$this->hasChild); //是否允许子类
		$this->assign('page_title',$this->title);
		$this->display('cate_list.html');
	}

	/**
	 * 新增或编辑分类数据
	 * @access public 
	 * @return html
	 */
	public function save(){
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //获取UI传递的参数
		$sql=array();
		if(empty($data)){
			$this->error('操作数据为空');
		}
		
		//检查是否存在
		$names=$this->db->select('cate_name')->where('pid='.$data[0]['pid'])->getCol();
		foreach($data as $k=>$v){
			$_id=$v['cate_id'];
			if($_id<1 && !empty($names) && in_array($v['cate_name'],$names)){
				$this->error('第['.($k+1).']行不能有相同模块名['.$v['cate_name'].']');
			}
			$names[]=$v['cate_name']; //追加检查模块名
			unset($v['cate_id']);
			if($_id>0){
				$sql[]=$this->db->wherePk($_id)->updateSql($v);
			}else{
				$sql[]=$this->db->addSql($v);
			}
		}
		
		if(empty($sql)){
			$this->error('操作数据为空');
		}
		$result=$this->db->commitTrans($sql);
		if($result){
			$this->clearMCache('cate_'.sget('cate_type','i'));
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}
	
	/**
	 * 删除分类数据
	 * @access public 
	 */
	public function remove(){
		$this->is_ajax=true; //指定为Ajax输出
		$ids=sget('ids','s');
		if(empty($ids)){
			$this->error('操作有误');	
		}
		//查询子节点是否存在
		$child=$this->db->select('cate_id')->where("pid in ($ids)")->getOne();
		if($child>0){
			$this->error('存在下级数据，不能删除');	
		}
		$result=$this->db->where("cate_id in ($ids)")->delete();
		if($result){
			$this->clearMCache('cate_'.sget('cate_type','i'));
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}
}
?>
