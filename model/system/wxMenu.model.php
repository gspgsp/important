<?php
class wxMenuModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'wxcms_menuitem');
	}
	/*
	** 设置菜单模板
	** 字段说明：（id、name:菜单名、level:菜单级别、create_time:创建时间
	** type:菜单类型、keyname:click类的key、access_times:访问次数、menu_string:菜单数据）
	*/
	public function setMenu($_data=array()){
		//检查当前输入关键字是否存在
		if($this->getMenuDetail('',$_data['mark'],$_data['level'])&&$_data['level']==1){
			$result = $this->getMenuDetail('',$_data['mark'],1);
			return array('id'=>$result['id'],'msg'=>'当前的一级菜单不能重复');
		}
		
		// 未传入模板id,添加一个模板
		$result = $this->add($_data);
		$id = $this->getLastID();
		
		if($result) return $this->getMenuDetail($id);
		return false;
	}
	/*
	** 编辑菜单模板
	** $keyname:菜单类型为click时的keyname;
	*/
	public function editMenu($_data=array(),$id="",$mark=""){
		$column = array();
		foreach($_data as $k=>$v){
			if(!isset($_data[$k])) continue;
			$column[$k] = $v;
		}
		if(!empty($id)){
			$id = (int)$id;
			
			// 根据id更新模板
			$result = $this->where("id='$id'")->update($column);
			if($result) return $this->getMenuDetail($id);
		}
		// 根据key更新
		if(!empty($mark)){
			$sql = array();
			// 根据菜单的key更新模板
			// 根据菜单的key更新模板
			$result = $this->where("mark='$mark'")->update(array('mark'=>$data['mark']));
			if($result) return $this->getMenuList($mark);
		}
		return false;
	}
	public function addAccessTimes($_data=array()){
		// $daily = strtotime($data['create_time']."00:00:00");
		$time = time();
		$daily = date("Y-m-d",$time);
		$_data['daily'] 	  = $daily;
		$_data['create_time'] = $time;
		
		$keyname = (string)$_data['keyname'];
		// 根据key更新
		if(!empty($keyname)){
			
			// 根据菜单的keyname更新模板
			if($this->getAccessTimes($keyname,$time)){
				$orign = $this->getAccessTimes($keyname,$time);
				$_data['access_times'] = (int)$orign['access_times']+1;
				$result = $this->model("wxcms_menuaccess")->where("keyname='$keyname' AND daily='$daily'")->update(array("access_times"=>$_data['access_times']));
			}else{
				$_data['access_times'] = 1;
				$result = $this->model("wxcms_menuaccess")->add($_data);
			}
			if($result) return $this->model("wxcms_menuaccess")->getLastID();
		}
		return false;
	}
	
	/*
	** 根据菜单级别查询
	** $keyname:访问关键字ctime：记录时间;
	** $start开始时间，$end结束时间
	** @access public 
	** @return array
	*/
	public function getAccessTimes($keyname="",$ctime="",$start="",$end=""){
		//日期和时间同时输入，则查询一条
		if(!empty($keyname)&&!empty($ctime)){
				$daily = date("Y-m-d",$ctime);
				$where_str = "keyname='$keyname' AND daily='$daily'";
				//查询某一条
				return $this->model("wxcms_menuaccess")->where($where_str)->getRow();
		}else{
			if(!empty($keyname)){
				$where_str = "keyname='$keyname'";
			}else if(!empty($ctime)){
				$daily = date("Y-m-d",$ctime);
				$where_str = "daily='$daily'";
			}
			
			if($start&&$end){
				$start = strtotime($start."00:00:00");
				$end = strtotime($end."23:59:59");
				if($end>$start){
					$where_str .="AND create_time>='$start' AND create_time<='$end'";
				}
			}
			// 查询keyname或者日期的所有访问统计记录				
			$result = $this->model("wxcms_menuaccess")->select("temp_id,name,keyname,sum(access_times) access_times")->where($where_str)->group('keyname')->getRow();
			return $result;
		}
		return false;
	}
	//每日的统计列表
	public function getAccessList($keyname="",$page=0,$size=30,$daily=""){
		$page=(int)$page;
		$size=(int)$size;
		
		if(!empty($keyname)||!empty($daily)){
			if(!empty($keyname)){
				$where_str = "keyname='$keyname'";
				if(!empty($daily)){
					$where_str .="AND daily='$daily'";
				}
			}else{
				$where_str = "daily='$daily'";
			}
			$result = $this->model("wxcms_menuaccess")->where($where_str)->page($page,$size)->getPage();
		}else{
			$result =  $this->model("wxcms_menuaccess")->page($page,$size)->getPage();
		}
		return $result;
	}
	
	public function getMonthCount($month=""){
		if(!empty($month)){
			$where_str = "daily LIKE '$month%'";
			$countList = $this->model("wxcms_menuaccess")->select("temp_id,name,keyname,sum(access_times) total")->group('keyname')->where($where_str)->order("create_time DESC")->getPage();
			//showtrace();
		}else{
			//获取总数
			$countList = $this->model("wxcms_menuaccess")->select("temp_id,name,keyname,sum(access_times) access_times")->group('keyname')->getAll();
		}
		return $countList;
	}
	
	/*
	** 根据菜单级别查询
	** $level:菜单的级别10,20,30：1及菜单，11,21,12:2级菜单;
	*/
	public function getMenuList($status=1,$mark="",$page=0,$size=20){
		if(empty($mark)){
			if($status=="0,1"){
				$where_str = "status=0 OR status=1";
			}else{
				$where_str = "status='$status'";
			}
			$result = $this->where($where_str)->order('mark,create_time')->page($page+1,$size)->getPage();
		}else{
			$arr = explode(",",$mark);
			$where_str = "";
			foreach($arr as $v){
				$v = (int)$v;
				$where_str .= "mark='$v' and";
			}
			//$where_str = preg_replace("/and$/","",$where_str);
			$where_str .= "status='$status'";
			// 分类查找
			$result = $this->where($where_str)
						->order('mark,create_time')
						->getAll();
			if($result){
				$result = array('data'=>$result,'count'=>count($result));
			}
		}
		return $result;
	}
	/*
	** 查询菜单详情
	** $mark:菜单位置;level为菜单等级
	** 针对菜单查询必须为等级和mark一起查；
	*/
	public function getMenuDetail($id="",$mark="",$level="",$keyname=""){
		if(!empty($id)){
			$id = (int)$id;
			$result = $this->where("id='$id'")->getRow();
			return $result;
		}
		
		if(!empty($level)){
			$mark = (int)$mark;
			$level = (int)$level;
			$result = $this->where("level='$level' AND mark='$mark'")->getRow();
			return $result;
		}
		
		if(!empty($keyname)){
			$keyname = (string)$keyname;
			$result  = $this->where("keyname='$keyname'")->getRow();
			return $result;
		}
		return $false;
	}
	
	//删除菜单
	public function deleteMenu($id='',$mark="",$level=""){
		$sql=array();
		// 传入多个id值，批处理删除
		if(!empty($id)){
			$arr = explode(",",$id);
			foreach($arr as $k=>&$v){
				$reply_id = (int)$v;
				$sql[] = $this->where("id='$reply_id'")->deleteSql();
			}
			if(!empty($sql) && $this->commitTrans($sql)){
				$result = $arr;
				return $result;
			}
		}else if(!empty($level)){
			$level = (int)$level;
			$result  = $this->where("level='$level'")
						    ->delete();
			return $result;
		}
		return false;
	}
	
	// 批量添加模板模板
	public function transTemple($_data=array()){
		foreach($_data as $k=>&$v){
			// 未传入模板id,添加一个模板
			$sql[] = $this->model('wxcms_template')->addSql($v);
		}
		if(!empty($sql) && $this->commitTrans($sql)){
			// $result = $arr;
		}
		// $id = $this->getLastID();
		if($result){
			return $id;
		}
		
		return false;
	}
	
	//创建菜单记录模板
	public function creatHistory($_data=array()){
		//获取上次的菜单id
		$lastMenu = $this->model("wxcms_menuhistory")->order("create_time DESC")->getRow();
		//销毁上一个历史菜单的状态
		$this->updateHistory($lastMenu['id']);
		//添加一条记录
		$result = $this->model("wxcms_menuhistory")->add($_data);
		if($result){
			return $this->getLastID();
		}
	}
	//创建菜单记录模板
	public function updateHistory($id=""){
		$result = $this->model("wxcms_menuhistory")->select("status")->where("id='$id'")->update(array("status"=>0));
	}	
	//获取菜单记录模板
	public function getHistory($id="",$start="",$end=""){
		//根据id查询
		if(!empty($id)){
			$where_str = "";
			$arr = explode(",",$id);
			foreach($arr as $k=>&$v){
				$reply_id = (int)$v;
				$where_str .= "id='$reply_id' and";
			}
			$where_str = preg_replace("/and$/","",$where_str);
			
			$result = $this->model("wxcms_menuhistory")->where($where_str)->getAll();
			return array('data'=>$result,'count'=>count($result));
		}
		//开始和结束时间
		if(!empty($end)){
			$result = $this->model("wxcms_menuhistory")->where("create_time>='$start' AND create_time<='$end'")->getAll();
			return array('data'=>$result,'count'=>count($result));
		}
		//获取最近的30条
		return $this->model("wxcms_menuhistory")->where("status=1")->order("creat_time DESC")->getRow();
	}
	
}	
?>