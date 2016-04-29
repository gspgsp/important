<?php
/**
* 
*/
class indexAction extends adminBaseAction 
{
	public function __init(){
		$this->db=M('public:common')->model('factory');
		$this->uid = sget('uid','i');
	}

	public function init(){
		//获取列表数据
		$action=sget('action');
		if($action=='grid'){
			$page = sget("pageIndex",'i',0); //页码
			$size = sget("pageSize",'i',20); //每页数
			$sortField = sget("sortField",'s','fid'); //排序字段
			$sortOrder = sget("sortOrder",'s','desc'); //排序

			//搜索条件
			$where=" 1 ";
			// if($this->uid>0){
			// 	$where.=" and u.uid='$this->uid' ";	
			// }
			
			// //关键词
			// $key_type=sget('key_type','s','name');
			// $keyword=sget('keyword','s');
			// if(!empty($keyword)){
			// 	if($key_type=='remark'){
			// 		$where.=" and c.remark like '%$keyword%' ";	
			// 	}elseif($key_type=='username'){
			// 		$where.=" and u.username='$keyword' ";	
			// 	}else{
			// 		$where.=" and c.$key_type='$keyword' ";	
			// 	}
			// }

			$list=$this->db->select('f.*, from_unixtime(f.input_time) as input_time, from_unixtime(f.update_time) as update_tim')
						->from('factory f')
						->where($where)
						->page($page+1,$size)
						->order("$sortField $sortOrder")
						->getPage();
			
			$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>'');
			$this->json_output($result);
		}
		
		$this->assign('page_title','渠道管理');
		$this->display('index.html');
    }
	public function index(){
		$this->display('index.html');
	}

/**
	 * 编辑渠道信息
	 * @access public 
	 */
	public function ajaxSave(){
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //传递的参数
		if(empty($data)){
			$this->error('错误的请求');	
		}

		$id=(int)$data['fid'];
		$where=''; $_data=array();

		$data['status']=intval($data['status']);
		//$data['update_time']=CORE_TIME;
		if($id>0){
			$this->db->model('factory')->wherePk($id)->update($data);
		}else{
			$data['f_name'] = $data['f_name'];
			$data['groupno'] = $data['groupno'];
			$data['input_admin'] = $data['input_admin'];
			$data['remark'] = $data['remark'];
			$data['status']=intval($data['status']);
			$data['input_time']=CORE_TIME;
			$this->db->add($data);
		    //p($this->db->getLastSql());
		}
		
		//$this->clearMCache('chanels');
		$this->success('操作成功');
	}

}