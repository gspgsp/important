<?php
/** 
 * 客户评论管理
 */
class commentsAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->db=M('public:common')->model('comments');
	}

	/**
	 * 客户评论管理列表
	 * @access public 
	 * @return html
	 */
	public function init(){
		
		$action=sget('action','s');
		$this->user_id=sget('user_id','i',0);
		if($action=='grid'){
			//分页
			$page = sget("pageIndex",'i',0); //页码
			$size = sget("pageSize",'i',20); //每页数
			$sortField = sget("sortField",'s','input_time'); //排序字段
			$sortOrder = sget("sortOrder",'s','desc'); //排序

			$where=" 1 ";
			$sTime = sget("sTime",'s','last_time'); //搜索时间类型
			$where.=getTimeFilter($sTime); //时间筛选
			
			$status = strlen($_REQUEST['status']) ? (int)$_REQUEST['status'] : ''; //状态
			if($status!==''){
				$where.=" and status='$status' ";
			} 	

			if($this->user_id>0) $where.=" and user_id='$this->user_id' ";	

			//关键词
			$key_type=sget('key_type','s','user_id');
			$keyword=sget('keyword','s');
			
			if(!empty($keyword)){
				$where.=" and $key_type='$keyword' ";
			}
			
			$list=$this->db->where($where)
						->page($page+1,$size)
						->order("$sortField $sortOrder")
						->getPage();

			foreach($list['data'] as $k=>$val){

				$list['data'][$k]['input_time']=$val['input_time']>1000 ? date("Y-m-d H:i:s",$val['input_time']) : '-';
				
			}
			$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>'');
			$this->json_output($result);
		}
		
		$this->assign('page_title','客户评论列表');
		$this->display('comments.list.html');
	}
	
	/**
     * 客户评论详细信息
     * @access public
     */
	public function view(){
		$id=sget('id','i');
			
		$user=$this->db->getPk($id);
	
		$this->assign('user',$user);

		$this->display('comments.view.html');
		
    }
	
	/**
	 * 删除客户评论
	 * @access public 
	 * @return html
	 */
	public function remove(){
		$this->is_ajax=true; //指定为Ajax输出
		$ids=sget('ids','s');

		if(empty($ids)){
			$this->error('操作有误'.$ids);	
		}

		$result=$this->db->where("id in ($ids)")->delete();

		if($result){
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}

	/**
	 * 审核客户评论
	 * @access public 
	 * @return html
	 */
	public function audit(){
		$this->is_ajax=true; //指定为Ajax输出
		$ids=sget('ids','s');
		$status=sget('status','i',1);

		if(empty($ids)){
			$this->error('操作有误'.$ids);	
		}

		$result=$this->db->where("id in ($ids)")->update(compact('status'));

		if($result){
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}

	/**
	 * 回复客户评论
	 * @access public 
	 * @return html
	 */
	public function reply(){
		$this->is_ajax=true; //指定为Ajax输出
		$ids=sget('ids','s');
		$reply=sget('reply','s',1);
		if(empty($ids)){
			$this->error('操作有误'.$ids);	
		}
		$reply_time = CORE_TIME;
		$reply_admin = $_SESSION['name'];
		$result=$this->db->where("id in ($ids)")->update(compact('reply','reply_time','reply_admin'));

		if($result){
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}
}
?>
