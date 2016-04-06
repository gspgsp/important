<?php
/**
 * 角色权限管理 
 */
class rbacModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), '');
	}

	/*
	 * 查看用户权限是否足够
	 * @access public
	 * @param int $admin_id 用户ID
     * @return bool
	 */
    public function checkAccess($admin_id=0){
		//不需要检查的排除
		$no_auth_module=array('index','pass');
		if(!empty($no_auth_module) && in_array(strtolower(ROUTE_C),$no_auth_module)){
			return true;	
		}
		
		//存在认证识别号，则进行进一步的访问决策
		if(empty($_SESSION['is_super'])) { //非超管
			//权限列表
			$accessList = $this->getAccessList($admin_id);
			$accessId=strtolower('/'.ROUTE_M.'/'.ROUTE_C.'/'.ROUTE_A);
			if(!isset($accessList[$accessId])) { //不在权限列表中
				return false;
			}
		}
        return true;
    }
	
	/*
	 * 取得当前用户的所有权限列表
	 * @access public
	 * @param int $admin_id 用户ID
     * @return array
	 */
	public function getAccessList($admin_id=0) {
		if(!isset($_SESSION['role_access'])){
			$nodes = $this->select('node.id, node.remark')
					->from('adm_role_access access')
					->join('adm_node node','node.id = access.node_id and node.status=1')
					->join('adm_role_user user','user.role_id = access.role_id and user.user_id='.$admin_id)
					->where('node.ntype=2 and node.level=4')
					->getAll(); //方法或模块
			$access=array();
			foreach($nodes as $k=>$v){
				$access[strtolower($v['remark'])]=$v['id'];
			}
			//$_SESSION['role_access']=$access;
		}else{
			$access=$_SESSION['role_access'];	
		}
        return $access;
    }
	
	
    /**
     * 获取我的菜单
     * @access private
     */
	public function _getMyMenu(){
		if(!isset($_SESSION['role_menu'])){
			//所有平级菜单
			$arr=$this->select('id,name as url,title,pid,remark as role,level')->model('adm_node')->where('status=1 and ntype=1 and level>1')->order('pid asc,sort_order asc')->getAll();
			$menu=$this->_menuTree($arr);
			
			if(empty($_SESSION['is_super'])){ //非超级用户:计算菜单权限
				$access = $this->getAccessList($_SESSION['adminid']);
				foreach($menu as $k1=>$v1){ //1级
					foreach($v1['child'] as $k2=>$v2){ //2级
						foreach($v2['child'] as $k3=>$v3){  //3级
							//非首页模型+在权限中 // 
							if(!strstr($v3['url'],'/index/index') && !in_array($v3['role'],$access)){ //不再允许权限中：放弃
								unset($menu[$k1]['child'][$k2]['child'][$k3]);
							}
						}
						if(empty($menu[$k1]['child'][$k2]['child'])){ //第2级没有子级：放弃
							unset($menu[$k1]['child'][$k2]);
						}
					}
					if(empty($menu[$k1]['child'])){ //第1级没有子级：放弃
						unset($menu[$k1]);
					}
				}
			}
			
			$top=$left=array();
			foreach($menu as $k1=>$v1){
				$top[$v1['url']]=$v1['title'];
				foreach($v1['child'] as $k2=>$v2){
					foreach($v2['child'] as $k3=>$v3){
						$left[$v1['url']][$v2['title']][]=array(
							'name'=>$v3['title'],									
							'file'=>$v3['url'],									
						);
					}
				}
			}
			unset($menu);
			
			$menu['top']=$top;
			$menu['left']=$left;
			//$_SESSION['role_menu']=$menu;
		}else{
			$menu=$_SESSION['role_menu'];	
		}
		return $menu;
	}
	
    /**
     * 生成菜单树
     * @param array $arr 平级菜单数组
     * @param int $pid 父ID
     * @access private
     */
	private function _menuTree($arr=array(), $pid=1){
		$tree=array();
		foreach($arr as $k=>$v){
			if($v['pid']==$pid){
				unset($arr[$k]);
				$child=$this->_menuTree($arr,$v['id']);
				$v['child']=$child;
				$tree[$v['id']]=$v;
			}
		}
		return $tree;
	}
}
?>