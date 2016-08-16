<?php
/**
 * 产品信息管理
 */
class reportAction extends adminBaseAction {
	protected $db;
	public function __init(){
		$this->db=M('public:common')->model('report_user');//客户跟进信息表
		$this->assign('depart',C('depart'));//所属部门
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
		$this->assign('page_title','绩效考核列表');
		$this->display('report.list.html');
	}


	/**
	 *
	 * @access private
	 * @return html
	 */
	private function _grid(){
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$sortField = sget("sortField",'s','input_time'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
		//筛选显示类别
		$where=" 1 ";

		// $c_id=sget('c_id','i',0);
		// if($c_id !=0)  $where.=" and `c_id` =".$c_id;

		// 特殊时间搜索
		$status = sget("status",'s','');
		if($status=='this_week') {
			$start=strtotime( date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m"),date("d")-date("w")+1,date("Y"))) );
			$end  =strtotime( date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("d")-date("w")+7,date("Y"))) );
		}
		if($status=='last_week') {
			$start=strtotime( date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m"),date("d")-date("w")+1-7,date("Y"))) );
			$end  =strtotime( date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("d")-date("w")+7-7,date("Y"))) );
		}
		if($status=='this_month') {
			$start=strtotime( date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m"),1,date("Y"))) );
			$end  =strtotime( date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("t"),date("Y"))) );
		}
		if($status=='last_month') {
			$start=strtotime( date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m")-1,1,date("Y"))) );
			$end  =strtotime( date("Y-m-d H:i:s",mktime(23,59,59,date("m") ,0,date("Y"))) );
		}
		if (!empty($start)) {
			$where.=" and report_date >=".$start.' and report_date <='.$end;
		}else{
			//自定义时间搜索
			$sTime = sget("sTime",'s','report_date'); //搜索时间类型
			$where.= getTimeFilter($sTime); //时间筛选
		}

		//所属部门搜索
		$depart=sget('depart','s','');
		if (!empty($depart)) {
			$ids = M('rbac:adm')->getIdByDepart("$depart");
			$str=implode(',',$ids);
			$where.=" and `admin_id` in ($str)";
		}
		
		//关键词
		$keyword=sget('keyword','s');
		if(!empty($keyword)){
			$ids = M('rbac:adm')->getIdByName("$keyword");
			$str=implode(',',$ids);
			$where.=" and `admin_id` in ($str)";
		}

		$list=$this->db->where($where)
					->page($page+1,$size)
					->order("$sortField $sortOrder")
					->getPage();
		foreach($list['data'] as &$v){
			$v['report_date']=$v['report_date']>1000 ? date("Y-m",$v['report_date']) : '-';
			$v['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
			$v['update_time']=$v['update_time']>1000 ? date("Y-m-d H:i:s",$v['update_time']) : '-';
			$message = M('rbac:adm')->getUserInfoById($admin_id=$v['admin_id']);
			$v['depart']=C('depart')[$message['depart']];
			$v['name']  =$message['name'];
		}
		$result=array('total'=>$list['count'],'data'=>$list['data']);
		$this->json_output($result);
	}

	/**
	 * 弹出添加绩效考核列表
	 * @access private
	 * @return html
	 */
	public function addreport(){
		$this->display('report.add.html');
	}

	/**
	 * 添加绩效信息
	 * @access public
	 */
	public function ajaxSave(){
		$this->is_ajax=true; //指定为Ajax输出
		$action = sget('action','s');
		$data = sdata(); //传递的参数
		if(empty($data)) $this->error('错误的请求');
    		$data['input_time']=CORE_TIME;
    		$data['input_admin']=$_SESSION['name'];
    		$data['report_date']= strtotime(date('Y-m',strtotime($data['report_date'])));
		$result = $this->db->add($data);
		if(!$result) $this->error('操作失败');
		$this->success('操作成功');
	}
	

	/**
	 * 保存行内编辑仓库数据
	 * @access public 
	 * @return html
	 */
	public function save(){
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //获取UI传递的参数
		if(empty($data)) $this->error('错误的操作');

		$sql=array();
		foreach($data as $v){
			$_id=$v['id'];
			if($_id>0){
				$update=array(
					'input_time'  =>strtotime($v['input_time']),
					'update_time'  =>CORE_TIME,
					'update_admin' =>$_SESSION['name'],
				)+$v;
				$sql[]=$this->db->wherePk($_id)->updateSql(saddslashes($update));
			}
		}
		if(empty($sql)){
			$this->error('操作数据为空');
		}
		$result=$this->db->commitTrans($sql);
		if($result){
			$cache=cache::startMemcache();
			$cache->delete('factory');
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}

}