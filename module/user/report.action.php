<?php
/**
 * 产品信息管理
 */
class reportAction extends adminBaseAction {
	protected $db;
	public function __init(){
		$this->db=M('public:common')->model('report_user');//客户跟进信息表
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

		$c_id=sget('c_id','i',0);
		if($c_id !=0)  $where.=" and `c_id` =".$c_id;
		//关键词
		$key_type=sget('key_type','s','c_name');
		$keyword=sget('keyword','s');
		if(!empty($keyword)){
			if($key_type == 'c_name'){
				$c_id =M('user:customer')->getInfoByCname($key_type,$keyword);
				$str=implode(',',$c_id);
				$where.=" and `c_id` in ($str)";
			}elseif($key_type == 'name'){
				$user_id =M('user:customerContact')->getCidByName($keyword);
				$str=implode(',',$user_id);
				$where.=" and `user_id` in ($str)";
			}else{
				$where.=" and $key_type like '%$keyword%'";
			}
		}

		$list=$this->db->where($where)
					->page($page+1,$size)
					->order("$sortField $sortOrder")
					->getPage();
		foreach($list['data'] as $k=>$v){
			$list['data'][$k]['follow_time']=$v['follow_time']>1000 ? date("Y-m-d H:i:s",$v['follow_time']) : '-';
			$list['data'][$k]['next_follow_time']=$v['next_follow_time']>1000 ? date("Y-m-d H:i:s",$v['next_follow_time']) : '-';
			$list['data'][$k]['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
			$list['data'][$k]['update_time']=$v['update_time']>1000 ? date("Y-m-d H:i:s",$v['update_time']) : '-';
			$list['data'][$k]['follow_up_way'] = L('follow_up_way')[$v['follow_up_way']]; 
			$list['data'][$k]['c_name'] = M('user:customer')->getColByName($v['c_id']);
			$list['data'][$k]['name'] = M('user:customerContact')->getColByName($v['user_id']);
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
			$data['admin_id']=$_SESSION['adminid'];
    		$data['input_time']=CORE_TIME;
    		$data['input_admin']=$_SESSION['name'];
    		$data['report_date']= strtotime($data['date']);
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