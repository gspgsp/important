<?php
/** 
 * 消息提醒
 * Andy@2013-03-22
 */
class sysMsgAction extends adminBaseAction {
	private $title='后台用户信息列表'; //
	public function __init(){
		$this->debug = false;
		$this->db=M('public:common')->model('user_msg');
	}

	/**
	 * 站内信息列表
	 * @access public 
	 * @return html
	 */
	public function init(){
		
		$action=sget('action','s');
		$this->user_id= $_SESSION['adminid'];
		if($action=='grid'){
			//分页
			$page = sget("pageIndex",'i',0); //页码
			$size = sget("pageSize",'i',20); //每页数
			$sortField = sget("sortField",'s','input_time'); //排序字段
			$sortOrder = sget("sortOrder",'s','desc'); //排序

			$where=" 1 and `utype` = 1 ";
			$sTime = sget("sTime",'s','input_time'); //搜索时间类型
			$where.=getTimeFilter($sTime); //时间筛选
			
			$ac_type = sget("ac_type",'i',''); //状态
			if($ac_type!=''){
				$chk_status = $ac_type-1;
				$where.=" and chk_status='$chk_status' ";
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
				//短信内容
				$list['data'][$k]['msg']=$val['msg'];
				//请求时间
				$list['data'][$k]['input_time']=$val['input_time']>1000 ? date("Y-m-d H:i:s",$val['input_time']) : '-';
				$list['data'][$k]['admin_name']=$this->db->model('admin')->select('name')->where("admin_id = {$val['user_id']}")->getOne();
				//是否已读
				switch ($val['is_read'])
				{
					case 2:
					  $is_read = "已读";
					  break;
					default:
					  $is_read = "未读";
				}
				$list['data'][$k]['is_read']=$is_read;
				
			}
			$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>'');
			$this->json_output($result);
		}
		
		$this->assign('page_title','提醒信息列表');
		$this->display('sysMsg.list.html');
	}

	/**
	 * 删除提醒信息
	 * @access private 
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
	/**
	 * 标记提醒信息
	 * @access private 
	 */
	public function read(){
		$this->is_ajax=true; //指定为Ajax输出
		$ids=sget('ids','s');
		if(empty($ids)){
			$this->error('操作有误');	
		}
		$result=$this->db->where("id in ($ids)")->update(array('is_read'=>2,));
		if($result){
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}
}
?>
