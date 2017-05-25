<?php
/** 
 * app每日统计数据
 */
class appStatDayAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->db=M('public:common')->model('app_stat_day');
	}

	/**
	 * 统计数据
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
			$sortField = sget("sortField",'s','day_id'); //排序字段
			$sortOrder = sget("sortOrder",'s','desc'); //排序

			//搜索条件
			$where="1 ";
			
			$starTime=sget('startTime','s'); //开始时间
			$endTime=sget('endTime','s');  //结束时间
			if(!empty($starTime) && strlen($starTime)>=10){
				$where.=' and day_id>='.substr(str_replace(array('-',' '),'',$starTime),2);	
			}
			if(!empty($endTime) && strlen($endTime)>=10){
				$where.=' and day_id<='.substr(str_replace(array('-',' '),'',$endTime),2);	
			}

			$list=$this->db->where($where)
						->page($page+1,$size)
						->order("$sortField $sortOrder")
						->getPage();
						
			$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>'');
			$this->json_output($result);
		}
		
		$this->assign('page_title','app每日统计数据');
		$this->display('appStatDay.list.html');
	}
}
?>
