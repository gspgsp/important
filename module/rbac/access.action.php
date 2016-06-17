<?php
/** 
 * 设置管理员节点（权限）
 */
class accessAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->action = sget('action','');
		$this->db=M('rbac:access');
	}
	
	/**
	 * 查看某角色权限
	 * @access public 
	 * @return html
	 */
	public function init(){
		$this->assign('mini_list',0); //非列表页面:有滚动条
		$role_id=sget('id','i',0);
		if($role_id<1){
			$this->error('请选择管理员角色');	
		}
		$this->assign('role_id',$role_id);
		
		//获取目前所有的权限
		$nodes=$this->db->select('node_id')->where('role_id='.$role_id)->getCol();
		$this->assign('nodes',join(",",$nodes));

		//所有的权限(模块)节点

		//是否有上级权限
		$pid=$this->db->model('adm_role')->select('pid')->wherePk($role_id)->getOne();
		if($pid<1){ //所有的权限(模块)节点
			$data=$this->db->model('adm_node')->select('id,concat(title,"（",name,"）") as text,pid')->where('status=1 and ntype=2 and level>1')->getAll();
		}else{//取上级权限的子集
			$data=$this->db->select('id,concat(title,"（",name,"）") as text,pid')->from('adm_role_access a')->join('adm_node n','a.node_id=n.id')->where('status=1 and ntype=2 and level>1 and a.role_id='.$pid)->getAll();
			
			$_pids=array(); //找到所有跟树：保证根数都存在
			$_ids=array();
			foreach($data as $k=>$v){
				$_ids[]=$v['id'];
				if($v['pid']>0 && !in_array($v['pid'],$_pids) && !in_array($v['pid'],$_ids)){
					$_pids[]=$v['pid'];
				}
			}
			
			if($_pids){ //控制器一级
				//模块分组一级
				$_ppids=$this->db->model('adm_node')->select('pid')->where('id in ('.join(',',$_pids).') and level>1')->getCol();
				$_pids=array_unique(array_merge($_pids,$_ppids)); //追加
				
				//清除已存在的
				foreach($_pids as $k=>$v){
					if(in_array($v,$_ids)){
						unset($_pids[$k]);	
					}
				}
				if($_pids){
					$_data=$this->db->model('adm_node')->select('id,concat(title,"（",name,"）") as text,pid')->where('id in ('.join(',',$_pids).') and level>1')->getAll();
					$data=array_merge($data,$_data);
				}
			}
		}
		$this->assign('data',json_encode($data));
		
		$this->assign('page_title','角色权限管理');
		$this->display('access.tree.html');
	}

	/**
	 * 保存节点数据
	 * @access public 
	 */
	public function saveNode(){
		$this->is_ajax=true; //指定为Ajax输出
		$role_id=sget('id','i',0);
		if($role_id<1){
			$this->error('请选择管理员角色');	
		}
		$nodes=sget('nodes','s');
		$this->db->where('role_id='.$role_id)->delete();
		$sql=array(); $result=1;
		if(!empty($nodes)){
			$ids=explode(",",$nodes);
			$sql=array();
			foreach($ids as $v){
				$sql[]=$this->db->addSql(array('role_id'=>$role_id,'node_id'=>$v));
			}
			$result=$this->db->commitTrans($sql);
		}
		if($result){
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}
	
	/**
	 * 设置某角色的管理员
	 * @access public 
	 * @return html
	 */
	public function staff(){
		$this->assign('mini_list',0); //非列表页面:有滚动条
		$role_id=sget('id','i',0);
		if($role_id<1){
			$this->error('请选择管理员角色');	
		}
		$this->assign('role_id',$role_id);
		
		//获取所有非超管
		$nodes=$this->db->model('adm_role_user')->select('user_id')->where('role_id='.$role_id)->getCol();
		$this->assign('nodes',join(",",$nodes));

		//所有的权限(模块)节点
		$data=$this->db->model('admin')->select('admin_id as id,concat(username," ",name) as text')->where('is_super=0')->getAll();
		$this->assign('data',json_encode($data));
		
		$this->assign('page_title','角色管理员管理');
		$this->display('access.staff.html');
	}

	/**
	 * 保存管理员数据
	 * @access public 
	 */
	public function saveStaff(){
		$this->is_ajax=true; //指定为Ajax输出
		$role_id=sget('id','i',0);
		if($role_id<1){
			$this->error('请选择管理员角色');	
		}
		$staff=sget('staff','s');
		$this->db->model('adm_role_user')->where('role_id='.$role_id)->delete();
		
		$sql=array(); $result=1;
		if(!empty($staff)){
			$ids=explode(",",$staff);
			$sql=array();
			foreach($ids as $v){
				$sql[]=$this->db->model('adm_role_user')->addSql(array('role_id'=>$role_id,'user_id'=>$v));
			}
			$result=$this->db->commitTrans($sql);
		}
		if($result){
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}
	
	/**
	 * 设置某管理员的角色
	 * @access public 
	 * @return html
	 */
	public function role(){
		$this->assign('mini_list',0); //非列表页面:有滚动条
		$admin_id=sget('id','i',0);
		if($admin_id<1){
			$this->error('请选择管理员');	
		}
		$this->assign('admin_id',$admin_id);
		
		//获取所有非超管
		$nodes=$this->db->model('adm_role_user')->select('role_id')->where('user_id='.$admin_id)->getCol();
		$this->assign('nodes',join(",",$nodes));

		//所有的权限(模块)节点
		$data=$this->db->model('adm_role')->select('id,name as text,pid')->where('status=1')->getAll();
		$this->assign('data',json_encode($data));
		
		$this->assign('page_title','管理员角色管理');
		$this->display('access.role.html');
	}

	/**
	 * 保存角色数据
	 * @access public 
	 */
	public function saveRole(){
		$this->is_ajax=true; //指定为Ajax输出
		$admin_id=sget('id','i',0);
		if($admin_id<1){
			$this->error('请选择管理员');	
		}
		$roles=sget('roles','s');
		$this->db->model('adm_role_user')->where('user_id='.$admin_id)->delete();
		
		$sql=array(); $result=1;
		if(!empty($roles)){
			$ids=explode(",",$roles);
			$sql=array();
			foreach($ids as $v){
				$sql[]=$this->db->model('adm_role_user')->addSql(array('role_id'=>$v,'user_id'=>$admin_id));
			}
			$result=$this->db->commitTrans($sql);
		}
		if($result){
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}
}
?>
