<?php
/** 
 * 每日任务消息提醒
 * 
 */
class alertAction extends adminBaseAction {
	// private $title='后台用户信息列表'; //
	public function __init(){
		$this->debug = false;
		$this->db=M('public:common')->model('alert_log');
	}
	/**
	 * 站内信息列表
	 * @access public 
	 * @return html
	 */
	public function init(){
		$action=sget('action','s');
		$type=sget('type','i');
		$this->user_id= $_SESSION['adminid'];
		if($action=='grid'){
			//分页
			$page = sget("pageIndex",'i',0); //页码
			$size = sget("pageSize",'i',20); //每页数
			$sortField = sget("sortField",'s','input_time'); //排序字段
			$sortOrder = sget("sortOrder",'s','desc'); //排序
			$where = '  1 ';
			//关键词
			$key_type=sget('key_type','s','order_sn');
			$keyword=sget('keyword','s');
			if(!empty($keyword) && $key_type=='order_sn'){
				$where.=" and order_sn='$keyword' ";
			}elseif(!empty($keyword) && $key_type=='input_admin'){
				$admin_id = M('rbac:adm')->getAdmin_Id($keyword);
				$where.=" and `customer_manager` = '$admin_id'";
			}
			//处理分类下拉框
			$alert_type=sget('alert_type','s');//分类下拉框
			if($alert_type !='')  $where.=" and `type` = ".$alert_type;
			//处理分类按钮
			if(!empty($type)){
				$where .= " and type = ".$type." and input_time > ".strtotime(date("Y-m-d",CORE_TIME));
			}
			//筛选时间
			$sTime = sget("sTime",'s','input_time'); //搜索时间类型
			$where.=getTimeFilter($sTime); //时间筛选
			//筛选自己的数据
			if($_SESSION['adminid'] != 1 && $_SESSION['adminid'] > 0){
				$sons = M('rbac:rbac')->getSons($_SESSION['adminid']);
				$where .= " and `customer_manager` in ($sons)";
			}
			$list=$this->db->where($where)
						->page($page+1,$size)
						->order("$sortField $sortOrder")
						->getPage();
			foreach($list['data'] as $k=>$val){
				//短信内容
				$list['data'][$k]['content']=$val['content'];
				//请求时间
				$list['data'][$k]['input_time']=$val['input_time']>1000 ? date("Y-m-d H:i:s",$val['input_time']) : '-';
				$list['data'][$k]['admin_name']=$this->db->model('admin')->select('name')->where("admin_id = {$val['customer_manager']}")->getOne();
				//是否已读
				switch ($val['is_read'])
				{
					case 1:
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
		// $this->assign('page_title','提醒信息列表');
		$this->display('alert.list.html');
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
		$result=$this->db->model('alert_log')->where("id in ($ids)")->delete();
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
		$result=$this->db->model('alert_log')->where("id in ($ids)")->update(array('is_read'=>1,));
		if($result){
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}
}
?>
