<?php
/**
 * 角色的权限 
 */
class roleModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'adm_role');
	}
	
	/*
	 * 取得当前用户的角色
	 * @access public
	 * @param int $admin_id 用户ID
     * @return array
	 */
	public function getUserRole($admin_id=0) {
		$roles=$this->model('adm_role_user')->select('role_id')->where('user_id='.$admin_id)->getCol();
		return $roles;
    }
	
	/*
	 * 取得当前用户的所有权限列表
	 * @access public
	 * @param string $role_id 角色ID
     * @return array
	 */
	public function getRoleUserList($role_id=''){
		if($role_id){
			$arr=$this->select('u.admin_id,u.name,u.username')
					->from('adm_role_user r')
					->join('admin u','u.admin_id=r.user_id')
					->where('role_id in ('.$role_id.') ')
					->getAll();
			$users=array();
			foreach($arr as $k=>$v){
				$users[$v['admin_id']]=$v['name'];
			}
			return $users;
		}else{
			return ;	
		}
    }
	
	/*
	 * 检查当前用户是否为客服
	 * @access public
     * @return bool
	 */
	public function isCrmkefu(){
		if($_SESSION['is_super']>0) return false;
		if(!isset($_SESSION['is_kefu'])){
			$is_kefu=false;
			$role_id=getSystemParam('crm_role');
			$myRole=$this->getUserRole($_SESSION['adminid']);
			//有相同角色时
			$_SESSION['is_kefu']= array_intersect($myRole,explode(',',$role_id)) ? true : false;
			$role_pzid=getSystemParam('crm_pzrole');
			$_SESSION['is_pkefu']= array_intersect($myRole,explode(',',$role_pzid)) ? true : false;
		}
	}
	
}
?>