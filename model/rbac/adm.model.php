<?php
/**
 * 管理员模型
 */
class admModel extends model{
	private $cache=NULL; //缓存
	public function __construct() {
		parent::__construct(C('db_default'), 'admin');
		$this->cache=cache::startMemcache();
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
	/*
	 * 取得当前用户的所有信息
	 * @access public
	 * @param int $admin_id 用户ID
	 * @return array
	 */
	public function getUserInfoById($admin_id=0) {
		//获取所有非超管
		$nodes=$this->where("admin_id = $admin_id ")->getRow();
		return $nodes;
	}

	/*
	 * 根据所属部门，查出所有管理员id
	 * @access public
	 * @param int $depart 所属部门
	 * @return array
	 */
	public function getIdByDepart($depart) {
		//获取所有非超管
		$nodes=$this->model('admin')->select('admin_id')->where("depart = $depart ")->getCol();
		return $nodes;
	}

	/*
	 * 根据管理员姓名，模糊查出所有管理员id
	 * @access public
	 * @param int $name 管理员姓名
	 * @return array
	 */
	public function getIdByName($name) {
		//获取所有非超管
		$nodes=$this->model('admin')->select('admin_id')->where("name like '%$name%' ")->getCol();
		return $nodes;
	}

	/*
	 * 根据管理员姓名，精确查出所有管理员id
	 * @access public
	 * @param int $name 管理员姓名
	 * @return array
	 */
	public function getAdmin_Id($name) {
		//获取所有非超管
		$nodes=$this->model('admin')->select('admin_id')->where("name='$name'")->getOne();
		return $nodes;
	}
	/**
	 * 根据管理员id获取部门name
	 * @Author   yezhongbao
	 * @DateTime 2016-09-07T13:44:30+0800
	 * @param    [int] $admin_id [管理员id]
	 * @return   [string] [部门名称]
	 */
	public function getPartByID($admin_id){
		$part = $this->model('adm_role_user as user')
					->join('adm_role as role','role.id = user.role_id')
					->where("user.user_id='$admin_id'")
					->select('role.name,role.id')
					->getRow();
		return $part;
	}

	/**
	 * [getNameByUser 根据管理员帐号获取其名字]
	 * @Author   xianghui
	 * @DateTime 2017-01-12T09:15:05+0800
	 * @param    [string] $username [管理员账号]
	 * @return   [string][用户汉语名字]
	 */
	public function getNameByUser($username){
		$name=$this->model('admin')->select('name')->where("username='$username'")->getOne();
		return $name;
	}
	/**
	 * 传递管理员id获取该管理员对应的强开规则
	 */
	public function getRuleById($adm = 0,$filds="`private_customer_nums`"){
		$rule =array();
		if ($adm != 0) {
			$rid = $this->model('dismiss_rule_admin')->select("rule_id")->where("`admin_id` = $adm")->getOne();
			if($rid){
				$rule = $this->model('dismiss_rule')->select($filds)->where("`id` = $rid")->getRow();
			}
		}
		return $rule;
		
	}
	/**
	 * 根据用户id判断用户是不是业务员
	 */
	public function judgeSaleById($adm=0){
		$role_ids = $this->model('adm_role')->select('id')->where("pid = 22")->getCol();//获取销售角色id
		$exit = $this->model('adm_role_user')->select('user_id')->where("role_id in (".join(',',$role_ids).")")->getOne();
		return empty($exit) ? 0 : 1;
	}
	/**
	 * 获取战队情况
	 * 根据角色的id
	 */
	public function getTeam(){
		$_key='getTeam_new';
		$data=$this->cache->get($_key);
		if(empty($data)){
			$data =$this->model('adm_role')->where('pid=22')->getAll();
			if(!empty($data)){
				$this->cache->set($_key,$data,86400*3); //加入缓存
			}
		}
		return $data;
	}
	/**
	 * 根据战队id获取战队人员并以字符串返回
	 */
	public function getTeamMembers($teamid = 1){
		$_key='getTeamMembers'.$teamid;
		$data=$this->cache->get($_key);
		if(empty($data)){
			$data =$this->model('adm_role_user')->select('user_id')->where("role_id=$teamid")->getCol();
			if(!empty($data)){
				$data = join(',',$data);
				$this->cache->set($_key,$data,86400*3); //加入缓存
			}
		}
		return $data;
	}
	/**
	 * 根据业务员id获取该战队当月配资状况
	 * @Author   yezhongbao
	 * @DateTime 2017-04-01T09:45:30+0800
	 * @param    integer                  $customer_manager [description]
	 * @return   [type]                                     [description]
	 */
	public function getThisMonthTemaCapitalByCustomer($customer_manager = 0){
		$month_start = strtotime(date('Y-m',time()));
		$role_id = $this->model('adm_role_user as `role_user`')
						->select('`role`.id')
						->leftjoin('adm_role as `role`','`role_user`.role_id = `role`.id')
						->where('`role_user`.user_id = '.$customer_manager.' and `role`.pid = 22')
						->getOne();
		//根据业务员id查询角色是否是战队角色，如果没有结果说明是非战队人员
		if(!$role_id){
			return $this->getThisMonthTemaCapitalBySpecialTeamId();
		}
		$res = $this->model('adm_role_user as `user`')
						   ->select('c.*')
						   ->leftjoin('adm_role as role','role.id = `user`.role_id')
						   ->rightjoin('team_capital as c','c.team_id = role.id and c.input_date='.$month_start)
						   ->where("`user_id` = ".$customer_manager)
						   ->getRow();
	   return empty($res)?array():$res;
	}
	/**
	 * 根据特殊战队id查询该战队当月配资状况（特殊战队就是非战队人员，统归为特殊战队 team_id = 1）
	 * @Author   yezhongbao
	 * @DateTime 2017-04-01T10:05:37+0800
	 * @return   [type]                   [description]
	 */
	public function getThisMonthTemaCapitalBySpecialTeamId(){
		$month_start = strtotime(date('Y-m',time()));
		$res = $this->model('team_capital')
				   ->where("`team_id` = 1 and input_date = ".$month_start."")
				   ->getRow();
	   return empty($res)?array():$res;
	}
	/**
	 * 根据交易员id，获取战队id，如果找不到战队id，则战队id=1，作为非战队人员分类
	 * @Author   yezhongbao
	 * @DateTime 2017-04-01T15:44:17+0800
	 * @return   [type]                   [description]
	 */
	public function getCustomerManagerTeamId($customer_manager = 0){
		$role_id = $this->model('adm_role_user as `user`')
						->select('role.id')
						->leftjoin('adm_role as role','role.id = `user`.role_id')
						->where('role.pid = 22 and `user`.user_id='.$customer_manager)
						->getOne();
		return !$role_id ? 1:$role_id;
	}
	/**	
	 * 根据业务员id获取业务员手机号
	 * @Author   yezhongbao
	 * @DateTime 2017-05-03T17:37:54+0800
	 * @param    integer                  $admin_id [description]
	 * @return   [type]                             [description]
	 */
	public function getPhoneByAdminId($admin_id = 0){
		$mobile = $this->model('admin')->select('mobile')->where('admin_id = '.$admin_id)->getOne();
		return empty($mobile)?'':$mobile;
	}
}