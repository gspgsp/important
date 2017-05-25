<?php
/**
 * 工作台客户报价权限限制
 */
class powerAction extends adminBaseAction {
	public function __init(){
		$this->db=M('public:common')->model('offers_power');
	}
	/**
	 *
	 * @access public
	 * @return html
	 */
	public function init(){
		$action=sget('action','s');
		if($action=='grid'){ //获取列表
			$this->_grid();exit;
		}
		$this->display('power.list.html');
	}
	//添加规则列表
	public function info(){
		$this->assign('action','add');
		$this->display('power.add.html');
	}

	/**
	 *
	 * @access private
	 * @return html
	 */
	private function _grid(){
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$sortField = sget("sortField",'s','type_id'); //排序字段
		$sortOrder = sget("sortOrder",'s','asc'); //排序
		//筛选显示类别
		$where=" 1 ";
		$list=$this->db->where($where)
					->page($page+1,$size)
					->order("$sortField $sortOrder")
					->getPage();

		$result=array('total'=>$list['count'],'data'=>$list['data']);
		$this->json_output($result);
	}
	/**
	 * 添加强开规则
	 * @access public
	 */
	public function ajaxSave(){
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //传递的参数
		if(empty($data)) $this->error('错误的请求');
		$result = $this->db->model('offers_power')->add($data);
		if(!$result) $this->error('操作失败');
		$this->success('操作成功');
	}
	public function update(){
		$id = sget('id','i');
		if(empty($id)) $this->error('错误的请求');
		$info = $this->db->where('id = '.$id)->getRow();
		$this->assign('info',$info);
		$this->assign('action','update');
		$this->display('power.add.html');
	}
		/**
	 * 修改强开规则
	 * @access public
	 */
	public function updateSave(){
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //传递的参数
		if(empty($data)) $this->error('错误的请求');
		$res = $this->db->where('id = '.$data['id'])->update($data+array('update_time'=>CORE_TIME, 'update_admin'=>$_SESSION['name']));
		if(!$res) $this->error('操作失败');
		$this->success('操作成功');
	}
	
	/**
	 * 删除数据
	 * @access public
	 */
	public function remove(){
		$this->is_ajax=true; //指定为Ajax输出
		$ids=sget('type_id','s');
		if(empty($ids)){
			$this->error('操作有误');
		}

		$res = $this->db->model('offers_power_admin')
				->where("type_id in ($ids)")->getOne();
		if($res){
			$this->error('该类型下有相应业务员，不可直接删除,如要删除，请先移除业务员');
		}
		$result=$this->db->model('offers_power')->where("type_id in ($ids)")->delete();
		if($result){
			$this->success('操作成功');
		}else{
			$this->error('删除操作失败');
		}
	}
	public function staff(){
		$this->assign('mini_list',0); //非列表页面:有滚动条
		$type_id=sget('type_id','i',0);
		$action=sget('action','i',1);// 1是添加 2是删除
		$this->assign('action',$action);
		if($type_id<1){
			$this->error('请选择一个类别');	
		}
		$this->assign('type_id',$type_id);
		if ($action == 1) {
			$dismiss_rule_admins = $this->db->model('offers_power_admin')
							->where('type_id='.$type_id)
							->select('admin_id')
							->getCol();
			$admin_ids=implode(",",$dismiss_rule_admins);
			if (empty($admin_ids)) {
				$admin_ids = "''";
			}
			// p($admin_ids);die;
			$customer_managers=$this->db->model('adm_role_user as user')
					->leftjoin('adm_role as role','user.role_id = role.id')
					->leftjoin('admin as admin','user.user_id = admin.admin_id')
					->select('admin.admin_id as id ,admin.name as text')
					->where('admin.status=1 and (role.pid=22 or role.id=2) and admin.admin_id not in ('.$admin_ids.')')
					->getAll();
					// showtrace();
		}else{
			$dismiss_rule_admins = $this->db->model('offers_power_admin')
							->select('admin_id')
							->where('type_id = '.$type_id)
							->getCol();
			$admin_ids=implode(",",$dismiss_rule_admins);
			if (empty($admin_ids)) {
				$admin_ids = "''";
			}
			$customer_managers=$this->db->model('adm_role_user as `user`')
					->leftjoin('adm_role as role','user.role_id = role.id')
					->leftjoin('admin as admin','user.user_id = admin.admin_id')
					->select('admin.admin_id as id ,admin.name as text')
					->where('admin.status=1 and (role.pid=22 or role.id=2) and admin.admin_id in ('.$admin_ids.')')
					->getAll();
		}
		$this->assign('data',json_encode($customer_managers));
		$nodes=$this->db->model('offers_power_admin')->select('admin_id')->where('type_id='.$type_id)->getCol();
		$this->assign('nodes',join(",",$nodes));
		$this->display('select.admin.html');
	}
/**
	 * 保存数据
	 * @access public 
	 */
	public function saveStaff(){
		$this->is_ajax=true; //指定为Ajax输出
		$type_id=sget('type_id','i',0);
		$action=sget('action','i',1);
		if($type_id<1){
			$this->error('请选择一个类别');	
		}
		$admin_ids=sget('admin_id','s');
		if($action == 2){//删除时候  清空数据
			$this->db->model('offers_power_admin')->where('type_id='.$type_id)->delete();
		}
		$sql=array(); $result=1;
		if(!empty($admin_ids)){
			$ids=explode(",",$admin_ids);
			$sql=array();
			foreach($ids as $v){
				$sql[]=$this->db->model('offers_power_admin')->addSql(array('type_id'=>$type_id,'admin_id'=>$v));
			}
			$result=$this->db->model('offers_power_admin')->commitTrans($sql);
			// showtrace();
		}
		if($result){
			$this->success('操作成功');
		}else{
			$this->error('操作失败');
		}
	}
	
}