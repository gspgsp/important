<?php
/** 
 * 管理员日志
 * Andy@2013-03-22
 */
class logAdminAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->db=M('public:common')->model('log_admin');
	}

	/**
	 * 管理员操作日志
	 * @access public 
	 * @return html
	 */
	public function init(){
		$action=sget('action','s');
		if($action=='grid'){
			//分页
			$page = sget("pageIndex",'i',0); //页码
			$size = sget("pageSize",'i',20); //每页数
			$sortField = sget("sortField",'s','input_time'); //排序字段
			$sortOrder = sget("sortOrder",'s','desc'); //排序

			$where=" 1 ";
			$sTime = sget("sTime",'s','input_time'); //搜索时间类型
			$where.=getTimeFilter($sTime); //时间筛选
			
			//关键词
			$key_type=sget('key_type','s','admin_id');
			$keyword=sget('keyword','s');
			if(!empty($keyword)){
				if($key_type=='admin_name'){
					$arr=$this->db->model('admin')->select('admin_id')->where("username='$keyword'")->getCol();
					$_ids=empty($arr) ? '-1' : join(',',$arr);
					$where.=" and admin_id in ($_ids) ";	
				}else{
					$where.=" and $key_type='$keyword' ";
				}
			}
			$adminList = $this->db->model('admin')->select('admin_id,username')->getAll();
			$adminArr = array();
			foreach($adminList as $k=>$v){
				$adminArr[$v['admin_id']]=$v['username'];
			}
			$list=$this->db->model('log_admin')->where($where)
						->page($page+1,$size)
						->order("$sortField $sortOrder")
						->getPage();
						
			foreach($list['data'] as $k=>$val){
				//请求时间
				$list['data'][$k]['username']=$adminArr[$val['admin_id']];
				$list['data'][$k]['input_time']=$val['input_time']>1000 ? date("Y-m-d H:i:s",$val['input_time']) : '-';
			}
			$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>'');
			$this->json_output($result);
		}
		
		$this->assign('page_title','管理员操作日志');
		$this->display('log.admin.html');
	}
}
?>
