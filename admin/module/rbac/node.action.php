<?php
/** 
 * 管理员节点管理
 */
class nodeAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->action = sget('action','');
		$this->db=M('rbac:node');
	}
	/**
	 * 节点列表
	 * @access public 
	 * @return html
	 */
	public function init(){
		$pid = sget("pid",'i',0); //父级
		
		//获取列表数据
		$action=sget('action');
		if($action=='grid'){
			$page = sget("pageIndex",'i',0); //页码
			$size = sget("pageSize",'i',20); //每页数
			$size = 10000000; //取全部数据
			$sortField = sget("sortField",'s','level'); //排序字段
			$sortOrder = sget("sortOrder",'s','asc'); //排序

			//搜索条件
			$where=" pid='$pid' ";
			//关键词
			$keyword=sget('key','s');
			if(!empty($keyword)){
				$where.=" and (name like '%$keyword%' or title like '%$keyword%' or remark like '%$keyword%')";	
			}
			
			$list=$this->db->where($where)
						->page($page+1,$size)
						->order("$sortField $sortOrder,sort_order asc")
						->getPage();

			$_list=array();  //列表
			$_desc=array(); //列表的备注
			if($pid>0 && empty($keyword)){
				$node=$this->db->getPk($pid);
				$pre='';
				if($node['ntype']==2){ //模块类型
					$pre=$node['remark'].'/';
					if($node['level']==1){ //查找模块
						$dirs = glob(APP_PATH.'module/*');
						foreach($dirs as $v){
							if(is_dir($v)){
								$_name=basename($v);
								if($_name=='public'){
									continue;	
								}
								$_list[]=$_name;
							}
						}
					}elseif($node['level']==2){ //映射控制器
						$pre='/'.$node['remark'].'/';
						$dirs = glob(APP_PATH.'module/'.$node['name'].'/*');
						foreach($dirs as $v){
							if(substr($v,-11)=='.action.php'){
								$_list[]=substr(basename($v),0,-11);
							}
						}
					}elseif($node['level']==3){ //映射方法
						$actions=array();
						$pre=$node['remark'].'/';
						
						//查找action中的方法
						$exist=require_file(APP_PATH.'module/'.$node['remark'].'.action.php');
						if($exist){
							$class=$node['name'].'Action';
							$pActions= get_class_methods('action');
							
							$class = new ReflectionClass($class);
							$methods = $class->getMethods(ReflectionProperty::IS_PUBLIC);
							foreach($methods as $method){
								$v=$method->name; //方法名
								if(in_array($v,$pActions) || preg_match('/^_/',$v)){ //将父类和_类删除
								   continue;
								}else{
									$_list[]=$v;
									$_desc[$v]=$this->_getScript($method->getDocComment());
								}
							}
						}
					}
				}
			}
			if(!empty($_list)){ //查看是否追加
				$count=$list['count'];
				$_comp=array();
				foreach($list['data'] as $val){
					$_comp[]=$val['name'];
				}
				foreach($_list as $val){
					if(!in_array($val,$_comp)){ //不在数据库中
						$list['data'][$count]=array(
									'id'=>0,'name'=>$val,'title'=>(isset($_desc[$val]) ? $_desc[$val] : '??'),'status'=>1,'sort_order'=>0, 'remark'=>$pre.$val,
									'pid'=>$node['id'],'level'=>$node['level']+1,'ntype'=>2,'_state'=>'added',
						);						
						$count++;
					}
				}
				//$list['data']=array_reverse($list['data']);
			}
			$result=array('total'=>$count,'data'=>$list['data'],'msg'=>'');
			$this->json_output($result);
		}
		if($action=='menu'){ //框架左侧菜单
			$data=$this->db->where('(ntype=2 and level<3) or (ntype=1 and level<=3)')->order('pid asc, sort_order asc')->getAll();
			$this->json_output($data);
		}
		
		$this->assign('pid',$pid);
		$this->assign('page_title','节点管理');
		$this->display('node.list.html');
	}
	
	/**
	 * 保存数据(新增或编辑)
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
			if($v['title']=='??')  continue;
			if(empty($v['name']) || empty($v['title'])){ //空数据排除
				$this->error('第['.($k+1).']行存在空数据');
			}
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
	 * Ajax删除节点s
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
			$this->error('存在子节点，不能删除');	
		}
		$result=$this->db->where("id in ($ids)")->delete();
		if($result){
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}
	
	/**
	 * 更新菜单权限
	 * @access public 
	 */
	public function updateMenuAccess(){
		$this->is_ajax=true; //指定为Ajax输出
		//查找所有权限节点
		$arr=$this->db->select('id,remark')->where("level=4 and ntype=2")->getAll();
		$access=array();
		foreach($arr as $k=>$v){
			$access[$v['remark']]=$v['id'];
		}		
		//更新所有目录子节点
		$nodes=$this->db->select('id,name')->where("level=4 and ntype=1")->getAll();
		foreach($nodes as $k=>$v){
			$id=isset($access[$v['name']]) ? $access[$v['name']] : '0';
			$this->db->wherePk($v['id'])->update(array('remark'=>$id));
		}
		$this->success('操作成功');
	}
	
	/**
	 * 获取文件中解释文字描述的第一行
	 * @access public 
	 */
	private function _getScript($docblock=''){
		if(strstr($docblock,'/**')){
			$arr=explode("\n",$docblock);
			if(count($arr)>2){
				return trim(substr($arr[1],strpos($arr[1],"*")+1));
			}
		}
		return '';
	}

	/**
	 * 展示审核流程
	 */
	public function admChk(){
		$data=  L("chk_node");
		$this->assign('data',json_encode($data));
		//查询已经存在的节点
		$id = sget('id','i',0);
		$this->assign('id',$id);
		$this->assign('page_title','审核流程');
		$this->display('chk.tree.html');
	}
	/**
	 * 提交保存审核流程
	 */
	public function saveChk(){
		$node_id = sget('id','i',0);//节点
		$chk_flows =sget('nodes','s');   // 权限流id
		$adm_id = sget('adm_id','i',0);//管理员名字
		// 先删除对应管理员的权限流
		$this->db->model('adm_chk')->where("`adm_id` = $adm_id and node_id=$node_id")->delete();
		$chk_flows = explode(',', $chk_flows);
		$data = array(
			'adm_id'=>$adm_id,
			'node_id'=>$node_id,
			'input_time'=>CORE_TIME,
			);
		if($chk_flows){
			foreach ($chk_flows as $v) {
				$data['node_flow'] = $v;
				$this->db->model('adm_chk')->add($data);
			}
		}
		$this->success('修改成功');
		
	}
	//获取已有用户的节点
	public function geiadmnodes(){
		$this->is_ajax = true;
		$adm = sget('adm','i',0);
		$node_id = sget('id','i',0);//节点
		$nodes = $this->db->model('adm_chk')->select('node_flow')->where("adm_id = $adm and node_id=$node_id")->getCol();
		$out = implode(',',$nodes);
		$this->json_output($out);
	}
}
?>
