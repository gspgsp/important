<?php 
/**
 * 公告
 */
class noticeAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->db=M('public:common')->model('in_notice');
		$this->doact = sget('do','s');
	}
	/**
	 * @access public
	 * @return html
	 */
	public function init(){
		$doact=sget('do','s');
		$action=sget('action','s');
		//$action='grid';
		if($action=='grid'){ //获取列表
			$this->_grid();exit;
		}
		$this->assign('doact',$doact);
		$this->display('notice.list.html');
	}
	/**
	 * Ajax获取列表内容
	 */
	public function _grid(){
		
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		//$roleid = M('rbac:rbac')->model('adm_role_user')->select('role_id')->where("`user_id` = {$_SESSION['adminid']}")->getOne();
		$sortField = sget("sortField",'s','input_time'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
		/* //筛选
		$where.= "1";
		$admin_name=sget('admin_name','s');
		if($admin_name)  $where.=" and `admin_name` = '$admin_name' ";
		$team_id=sget('team_id','i');
		if($team_id)  $where.=" and `depart_id` = '$team_id' ";
		// 筛选时间
		$sTime = sget("sTime",'s','input_time'); //搜索时间类型
		$where.=getTimeFilter($sTime); //时间筛选 */
		$orderby = "$sortField $sortOrder";
		$list=$this->db->where()->page($page+1,$size)->order($orderby)->getPage();
		//showTrace();
		//p($list);
		foreach($list['data'] as $k=>$val){
			$list['data'][$k]['input_time']=!empty($val['input_time']) ? date("Y-m-d H:i:s",$val['input_time']) : '-';
			$list['data'][$k]['update_time']=!empty($val['update_time']) ? date("Y-m-d H:i:s",$val['update_time']) : '-';
		}                                                                                                
		$msg="";
		$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>$msg);
		$this->json_output($result);
	}
	/**
	 * 新增公告
	 */
	public function add(){
	    $this->display('notice.add.html');
	}
}