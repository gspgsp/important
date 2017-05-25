<?php
/** 
 * 媒体任务列表
 * Andy@2014-10-06
 */
class chanelTaskAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->action = sget('action','');
		$this->db=M('public:common')->model('chanel_task');
		$this->status = array(0=>'禁用',1=>'可用');
		$this->type = array(1=>'图片',2=>'文章');
	}
	
	/**
	 * 媒体任务列表
	 * @access public 
	 * @return html
	 */
	public function init(){
		$action=sget('action');
		if($action=='grid'){
			$page = sget("pageIndex",'i',0); //页码
			$size = sget("pageSize",'i',20); //每页数
			$sortField = sget("sortField",'s','id'); //排序字段
			$sortOrder = sget("sortOrder",'s','desc'); //排序
			$where='1';
			
			$type=sget('type','i'); //渠道类型
			if($type>0){
				$where.=" and type='$type' ";	
			}
			$status=sget('status','i'); //审核状态
			if($status>0){
				$where.=" and status=".($status-1);	
			}
			
			//关键词
			$key_type=sget('key_type','s','title');
			$keyword=sget('keyword','s');
			if(!empty($keyword)){
				$where.=" and $key_type='$keyword' ";	
			}
			
			$list=$this->db->select('id,title,status,type,start_time,end_time')->where($where)
						->page($page+1,$size)
						->order("$sortField $sortOrder")
						->getPage();
			
			foreach($list['data'] as $k=>$v){
				$list['data'][$k]['start_time']=$v['start_time']>1000 ? date("Y-m-d H:i:s",$v['start_time']) : '-';
				$list['data'][$k]['end_time']=$v['end_time']>1000 ? date("Y-m-d H:i:s",$v['end_time']) : '-';
			}
			$result=array('total'=>$list['count'],'data'=>$list['data']);
			$this->json_output($result);
		}
		$this->assign('page_title','媒体任务列表');
		$this->display('chanel.task.html');
	}

	/**
	 * 查看任务信息
	 * @access public 
	 */
	public function info(){
		$this->is_ajax=true;
		$this->assign('mini_list',0);
		$this->type = setMiniConfig($this->type);
		$this->status = setMiniConfig($this->status);
		
		$id=sget('id','i');
		if($id>0){
			$info=$this->db->model('chanel_task')->wherePk($id)->getRow();
			if(empty($info)){
				$this->error('错误的数据请求');	
			}
			$info['input_time']=$info['input_time']>1000 ? date("Y-m-d H:i:s",$info['input_time']) : '-';
		}else{
			$info=array('status'=>1);
		}
		$this->assign('info',$info); 
		
		$this->assign('page_title','任务信息');
		$this->display('chanel.taskInfo.html');
	}
	
	/**
	 * 媒体任务保存
	 * @access public 
	 */
	public function save(){
		$this->is_ajax=true;
		$id=sget('id','i'); //产品ID

		$_info=sget('info','a');
		if(strlen($_info['title'])<3){
			$this->error('请输入标题');	
		}
		$_info['start_time']=strlen($_info['start_time'])>10 ? strtotime($_info['start_time']) : 0;
		$_info['end_time']=strlen($_info['end_time'])>10 ? strtotime($_info['end_time']) : 0; //截止日
		
		$_data=$_info;
		
		//更新或新增商品数据
		if($id>0){
			$result=$this->db->wherePk($id)->update($_data);	
		}else{
			$_data['input_time']=CORE_TIME;
			$result=$this->db->add($_data);
		}
		if($result){
			$this->success('操作成功');
		}
		$this->error('数据错误');
	}

	/**
	 * 删除任务
	 * @access public 
	 */
	public function remove(){
		$this->is_ajax=true; //指定为Ajax输出
		$ids=sget('ids','s');
		if(empty($ids)){
			$this->error('操作有误');	
		}
		$result=$this->db->where("id in ($ids)")->delete();
		if($result){
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}

}
?>
