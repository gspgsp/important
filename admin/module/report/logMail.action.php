<?php
/** 
 * 邮件发送日志
 * Andy@2013-03-22
 */
class logMailAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->db=M('public:common')->model('log_mail');
		$this->stype=array(1=>'邮箱验证',2=>'网站通知',3=>'推荐邮件',4=>'No stype',5=>'留言回复');
		$this->status=array(1=>'待发',2=>'成功',3=>'失败');
	}

	/**
	 * 邮件发送日志
	 * @access public 
	 * @return html
	 */
	public function init(){
		
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

			$stype = sget('stype','i',0); //类型
			if($stype>0){
				$where.=" and stype='$stype' ";
			} 	
			$status = sget('status','i',0); //类型
			if($status>0){
				$where.=" and status=".($status-1);
			}

			//关键词
			$key_type=sget('key_type','s','email');
			$keyword=sget('keyword','s');
			if(!empty($keyword)){
				if($key_type=='subject'){
					$where.=" and subject like '%$keyword%' ";	
				}else{			
					$where.=" and $key_type='$keyword' ";
				}
			}			

			$list=$this->db->where($where)
						->page($page+1,$size)
						->order("$sortField $sortOrder")
						->getPage();
			
			foreach($list['data'] as $k=>$val){
				//用户名称
				$list['data'][$k]['user_name']=$val['user_name'];
				//短信内容
				$list['data'][$k]['email']=$val['email'];
				$list['data'][$k]['subject']=$val['subject'];
				
				//请求时间
				$list['data'][$k]['input_time']=$val['input_time']>1000 ? date("Y-m-d H:i:s",$val['input_time']) : '-';
				$list['data'][$k]['stype']=$this->stype[$val['stype']];
				$list['data'][$k]['status']=$this->status[$val['status']+1];
			}
			$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>'');
			$this->json_output($result);
		}
		
		$this->assign('page_title','邮件日志列表');
		$this->display('logMail.list.html');
	}
}
?>
