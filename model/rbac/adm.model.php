<?php
/**
 * 管理员模型 
 */
class admModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'admin');
	}
	
	/*
	 * 取得当前用户的所有权限列表
	 * @access public
	 * @param int $admin_id 用户ID
	 * @return array
	 */
	public function getUserById($admin_id=0) {
		//获取所有非超管
		$nodes=$this->model('admin')->select('admin_id,username')->where('admin_id in('.$admin_id.')')->getAll();
		return $nodes;
	}
	/*
	 * 取得指定字段内容
	 * @access public
	 * @param int $admin_id 用户ID
	 * @return array
	 */
	public function getUserByCol($admin_id=0,$col='name') {
		return $this->model('admin')->select("$col")->where("admin_id = ".$admin_id)->getOne();
	}

}
?>