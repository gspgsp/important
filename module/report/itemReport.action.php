<?php
/** 
 * 日报表
 */
class itemReportAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->db=M('public:common')->model('sys_report');
		for($i=2014;$i<=date("Y");$i++){
			$yearList[$i]=$i;
		}
		$this->yearList = $yearList;
	}

	/**
	 * 日报统计
	 * @access public 
	 * @return html
	 */
	public function init(){

		$action=sget('action','s');
		if($action=='grid'){
			//分页
			$page = sget("pageIndex",'i',0); //页码
			$size = sget("pageSize",'i',20); //每页数
			$sortField = sget("sortField",'s','year'); //排序字段
			$sortOrder = sget("sortOrder",'s','desc'); //排序

			//搜索条件
			$where="1 ";
			
			$year = sget('year','s'); //开始时间
			$ctype = sget('ctype','s'); //报表类型

			if(!empty($year)){
				$where.=' and year='.$year;	
			}
			if(!empty($ctype)){
				$where.=' and ctype='.$ctype;	
			}

			$list=$this->db->where($where)
						->page($page+1,$size)
						->order("$sortField $sortOrder")
						->getPage();
			//showTrace();
						
			$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>'');
			$this->json_output($result);
		}
		
		$this->assign('page_title','日报表');
		$this->display('itemReport.list.html');
	}


}
?>
