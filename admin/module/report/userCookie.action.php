<?php
/** 
 * 用户cookie日志
 * deray.wang@2014-09-19
 */
class userCookieAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->db=M('public:common')->model('user_cookie');
	}

	/**
	 * 用户cookie日志
	 * @access public 
	 * @return html
	 */
	public function init(){
		$action=sget('action','s');
		if($action=='grid'){
			//分页
			$page = sget("pageIndex",'i',0); //页码
			$size = sget("pageSize",'i',20); //每页数
			$sortField = sget("sortField",'s','access_time'); //排序字段
			$sortOrder = sget("sortOrder",'s','desc'); //排序

			$where=" 1 ";
			$sTime = sget("sTime",'s','access_time'); //搜索时间类型
			$where.=getTimeFilter($sTime); //时间筛选

			//关键词
			$key_type=sget('key_type','s','user_id');
			$keyword=sget('keyword','s');
			if(!empty($keyword)){
				$where.=" and $key_type='$keyword' ";
			}
			$mobile = sget('mobile','s');
			if(!empty($mobile)){
				$where.=" and mobile='$mobile' ";
			}
			$list=$this->db->where($where)
						->page($page+1,$size)
						->order("$sortField $sortOrder")
						->getPage();
						
			foreach($list['data'] as $k=>$val){
				//请求时间
				$list['data'][$k]['access_time']=$val['access_time']>1000 ? date("Y-m-d H:i:s",$val['access_time']) : '-';		
			}
			$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>'');
			$this->json_output($result);
		}
		
		$this->assign('page_title','用户cookie日志');
		$this->display('cookie.list.html');
	}
	
	/**
     * 用户cookie详细信息
     * @access public
     */
	public function view(){
		$id=sget('id','i');
		$user['msg']=$this->db->getPk($id);
	
		$this->assign('user',$user);
		$this->display('cookie.view.html');	
    }
}
?>
