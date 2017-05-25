<?php
/** 
 * 管理员角色管理
 */
class roleAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->action = sget('action','');
		$this->db=M('rbac:role');
	}
	
	/**
	 * 角色列表
	 * @access public 
	 * @return html
	 */
	public function init(){
		//获取列表数据
		$data=$this->db->order('pid asc')->getAll();
		foreach($data as $k=>$v){
			$data[$k]['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
			$data[$k]['update_time']=$v['update_time']>1000 ? date("Y-m-d H:i:s",$v['update_time']) : '-';
		}
		$this->assign('data',json_encode($data));
		
		$this->assign('page_title','角色管理');
		$this->display('role.list.html');
	}

	/**
	 * Ajax新增角色
	 * @access public 
	 */
	public function add(){
		$this->is_ajax=true; //指定为Ajax输出
		$this->info(0);	
	}

	/**
	 * Ajax编辑角色
	 * @access public 
	 */
	public function edit(){
		$this->is_ajax=true; //指定为Ajax输出
		$id=sget('id','i',0);
		if($id<0){
			$this->error('信息有误');	
		}
		$this->info($id);	
	}

	/**
	 * Ajax编辑角色
	 * @access public 
	 */
	public function remove(){
		$this->is_ajax=true; //指定为Ajax输出
		$id=sget('id','i',0);
		if($id<0){
			$this->error('信息有误');	
		}
		
		//需要检查是否有关联的管理员和角色权限
		//************to-do**********
		$this->db->model('adm_role')->deletePk($id);
		$this->db->model('adm_role_access')->where('role_id='.$id)->delete();
		$this->db->model('adm_role_user')->where('role_id='.$id)->delete();
		$this->success('删除处理成功');
	}

	/**
	 * 角色处理
	 * @access private 
	 * @return html
	 */
	private function info($id=0){
		$data = sdata(); //获取UI传递的参数
		$role_id=(int)$data['role_id'];
		$data['pid']=(int)$data['pid'];
		unset($data['role_id']);
		if($role_id>0 && $role_id==$id){
			$data['update_time']=CORE_TIME;
			$this->db->updatePk($data,$role_id);	
		}elseif($role_id==0){
			$data['input_time']=CORE_TIME;
			$this->db->add($data);
		}
		$this->success('信息处理成功');
	}
}
?>
