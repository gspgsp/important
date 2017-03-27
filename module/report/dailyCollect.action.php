<?php
/** 
 * 每日业务完成情况
 * 
 */
class dailyCollectAction extends adminBaseAction {
	// private $title='后台用户信息列表'; //
	public function __init(){
		$this->debug = false;
		$this->assign('team',L('team')); //战队名称
		$this->assign('startTime', date('Y-m-d',time())); //开始时间
		$this->assign('endTime',date('Y-m-d',time())); //结束时间
		$this->db=M('public:common')->model('daily_collect');
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
			$where = '  1 ';
			//关键词
			$keyword=sget('keyword','s');
			if(!empty($keyword)){
				$where.=" and name='$keyword' ";
			}
			$team_id=sget('team_id','i');
			if($team_id)  $where.=" and `team_id` = '$team_id' ";
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
				//请求时间
				$list['data'][$k]['input_time']=$val['input_time']>1000 ? date("Y-m-d H:i:s",$val['input_time']) : '-';
				$list['data'][$k]['finish_rate']=$val['finish_rate'].'%';
				$team=M('rbac:adm')->getPartByID($val['customer_manager']);
				$list['data'][$k]['team_name']=$team['name'];
			}
			$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>'');
			$this->json_output($result);
		}
		$this->display('dailyCollect.list.html');
	}
	
}