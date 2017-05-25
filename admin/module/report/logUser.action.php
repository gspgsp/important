<?php
/** 
 * 分类管理
 * Andy@2013-03-22
 */
class logUserAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->db=M('public:common')->model('log_user');
	}

	/**
	 * 用户操作日志
	 * @access public 
	 * @return html
	 */
	public function init(){
		$operation_type=L('operation_type');
		
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
			
			$action_type = sget('action_type','s',''); //类型
			if(!empty($action_type)){
				$where.=" and action_type='$action_type' ";
			}
			
			//关键词
			$key_type=sget('key_type','s','mobile');
			$keyword=sget('keyword','s');
			if(!empty($keyword)){
				if($key_type=='user_ip'){
					$where.=" and user_ip like '%$keyword%' ";	
				}elseif($key_type=='mobile'){
					$_id=$this->db->model('user')->select('user_id')->where("mobile like '%$keyword%'")->getCol();
					$id=!empty($_id) ? join(',',$_id) : '-1';
					$where.=" and user_id in ($id) ";	
				}else{			
					$where.=" and $key_type='$keyword' ";
				}
			}			
			
			$list=$this->db->model('log_user')->where($where)
						->page($page+1,$size)
						->order("$sortField $sortOrder")
						->getPage();

			foreach($list['data'] as $k=>$val){
				//银行ID
				$list['data'][$k]['bank_id']=$val['id'];
				//操作类型
				$list['data'][$k]['action_type']=$operation_type[$val['action_type']];
				if(in_array($val['action_type'],array('paypasswd_modify','passwd_modify','paypasswd_find','paypasswd_set'))){
					$old_value = "******";
					$new_value = "******";
				}else{
					$old_value = $val['old_value'];
					$new_value = $val['new_value'];
				}
				//操作之前的信息
				$list['data'][$k]['old_value']=$old_value;
				//操作之后的信息
				$list['data'][$k]['new_value']=$new_value;
				//操作是否成功
				$list['data'][$k]['success']=$val['success'];
				//请求时间
				$list['data'][$k]['input_time']=$val['input_time']>1000 ? date("Y-m-d H:i:s",$val['input_time']) : '-';
				//用户IP
				$list['data'][$k]['user_ip']=$val['user_ip'];
				//操作描述
				$list['data'][$k]['description']=$val['description'];
				
			}
			$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>'');
			$this->json_output($result);
		}
		
		$this->assign('operation_type',$operation_type);
		$this->assign('page_title','用户日志列表');
		$this->display('logUser.list.html');
	}
}
?>
