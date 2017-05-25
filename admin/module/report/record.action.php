<?php
/**
 * 用户流转记录
 * xianghui@2017-01-12
 */

class recordAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->db=M('public:common')->model('log_customer');
	}

	/**
	 * 用户流转记录列表
	 * @access public
	 * @return html
	 */
	public function init(){
		$this->action_type=L('action_type');

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

			$action_type = sget("action_type",'s',''); //操作类型
			if($action_type!=''){
				$where.=" and action_type='$action_type' ";
			}
			$c_id=sget('c_id','i',0);
			if($c_id > 0) $where.=" and `c_id` = $c_id ";

			//公司名字搜索
			$c_name=sget('c_name','s');
			if(!empty($c_name)){
				$c_ids = M('user:customer')->getLikeCidByCname($c_name);
				$where.=" and `c_id` in ($c_ids) ";
			}
			$list=$this->db->where($where)
						->page($page+1,$size)
						->order("$sortField $sortOrder")
						->getPage();
			$status=L('is_security');
			foreach($list['data'] as $k=>$val){
				//操作类型
				$list['data'][$k]['action_type']=$this->action_type[$val['action_type']];
				if(in_array($val['action_type'],array('paypasswd_modify','passwd_modify','paypasswd_find','paypasswd_set','reset_passwd'))){
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
				$list['data'][$k]['success']=$status[$val['success']];
				//请求时间
				$list['data'][$k]['input_time']=$val['input_time']>1000 ? date("Y-m-d H:i:s",$val['input_time']) : '-';
			}
			$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>'');
			$this->json_output($result);
		}
		$this->assign('page_title','用户流转记录');
		$this->display('record.list.html');
	}
}
?>
