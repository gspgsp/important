<?php 
	class newsCateAction extends adminBaseAction {
		public function __init(){
			$this->db=M('public:common')->model('news_cate');
		}

		//列表页
		public function lst(){
		 	$pid=sget('pid','i');
		 	$action=sget('action','s');
		 	if($action=='grid'){
		 		$page=sget('pageIndex','i',0);	//当前页
		 		$page_size=sget('pageSize','i',20);	//每页显示数
		 		$sortField=sget('sortField','s','sort_order');	//排序字段
		 		$sortOrder=sget('sortOrder','s','asc');	//排序方式
		 		$data=$this->db->where('pid='.$pid.' and status = 1')->page($page+1,$page_size)->order("$sortField $sortOrder")->getPage();	//获取列表数据
		 		//p($data);exit;
		 		$result=array('total'=>$data['count'],'data'=>$data['data']);
		 		$this->json_output($result);
		 	}
		 	if($pid>0){
		 		$parent=$this->db->getPk($pid);
		 		$parent['next']=$parent['level']+1;
		 	}else{
		 		$parent=array('pid'=>0,'next'=>1);
		 	}
		 	$this->assign(array(
		 		'action' =>ROUTE_A,
		 		'pid'      =>$pid,
		 		'parent'	 =>$parent,
		 	));
		 	$this->display('newsCate_list');
		 }

		 //保存数据
		 public function save(){
		 	$this->is_ajax=true;
		 	$data=sdata();
		 	if(empty($data)){
		 		$this->error('操作数据为空');
		 		exit;
		 	}

		 	//通过分类名字检查，是否名字重复
		 	$names=$this->db->where('pid ='.$data[0]['pid'])->select('cate_name')->getCol();
		 	$sql=array();
		 	foreach ($data as $k=> $v) {
		 		$id=$v['cate_id'];
		 		if($id<1 && !empty($names) && in_array($v['cate_name'], $names)){
		 			$this->error('第['.($k+1).']行不能有相同分类名['.$v['cate_name'].']');
		 			exit();
		 		}
		 		$names[]=$v['cate_name'];	//追加分类名，后面的进行判断
		 		unset($v['cate_id']);
		 		$v['input_time']=time();
		 		$v['input_admin']=$_SESSION['name'];
		 		//p($v);exit;
		 		if($id>0){
		 			$sql[]=$this->db->wherePk($id)->updateSql($v);
		 		}else{
		 			$sql[]=$this->db->addSql($v);
		 		}
		 	}
		 	if(empty($sql)){$this->error('操作数据为空');exit;}
		 	$result=$this->db->commitTrans($sql);
		 	if($result){
		 		$this->success('数据保存成功');
		 	}else{
		 		$this->error('数据处理失败');
		 	}
		 }

		 //删除分类
		 public function remove(){
		 	$this->is_ajax=true;
		 	$ids=spost('ids','s');
		 	if(!$ids){
		 		$this->error('操作数据为空');
		 		exit();
		 	}

		 	//判断分类有没有子分类
		 	$childs=$this->db->where('pid in ('.$ids.')')->select('cate_id')->getOne();
		 	if($childs>0){
		 		$this->error('该分类下有子分类，请先删除子分类！');
		 		exit;
		 	}
		 	$result=$this->db->where('cate_id in ('.$ids.')')->delete();
		 	if($result){
		 		$this->success('删除成功');
		 	}else{
		 		$this->error('删除操作失败');
		 	}
		 }
	}
 ?>