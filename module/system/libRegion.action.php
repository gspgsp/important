<?php
/** 
 * 地区管理
 */
class libRegionAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->action = sget('action','');
		$this->db=M('public:common')->model('lib_region');
	}
	
	/**
	 * 地区列表
	 * @access public 
	 * @return html
	 */
	public function init(){
		$pid=sget('pid','i');
		$action=sget('action','s');
		if($action=='grid'){
			$page = sget("pageIndex",'i',0); //页码
			$size = sget("pageSize",'i',20); //每页数
			$size = 10000000; //取全部数据
			$sortField = sget("sortField",'s','id'); //排序字段
			$sortOrder = sget("sortOrder",'s','asc'); //排序

			//搜索条件
			$where=" pid='$pid' ";
			
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
			$parent['next']=$parent['level_id']+1;
		}else{
			$parent=array('pid'=>0,'next'=>0);
		}
		$this->assign('parent',$parent);
		
		$this->assign('page_title','地区设置');
		$this->display('lib_region.html');
	}

	/**
	 * 地区保存数据
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
		
		//检查是否存在统计的模块名
		$names=$this->db->select('name')->where('pid='.$data[0]['pid'])->getCol();
		foreach($data as $k=>$v){
			$_id=$v['id'];
			if($_id<1 && !empty($names) && in_array($v['name'],$names)){
				$this->error('第['.($k+1).']行不能有相同模块名['.$v['name'].']');
			}
			$names[]=$v['name']; //追加检查模块名
			unset($v['id']);
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
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}

	/**
	 * 地区删除
	 * @access public 
	 */
	public function remove(){
		$this->is_ajax=true; //指定为Ajax输出
		$ids=sget('ids','s');
		if(empty($ids)){
			$this->error('操作有误');	
		}
		//查询子节点是否存在
		$child=$this->db->select('id')->where("pid in ($ids)")->getOne();
		if($child>0){
			$this->error('存在下级数据，不能删除');	
		}
		$result=$this->db->where("id in ($ids)")->delete();
		if($result){
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}
}
?>
