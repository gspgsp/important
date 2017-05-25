<?php
/** 
 * 用户登录错误日志
 * deray.wang@2014-06-19
 */
class userLogErrAction extends adminBaseAction {
	private $title='用户登录错误日志'; //
	public function __init(){
		$this->debug = false;
		$this->db=M('public:common')->model('log_login_err');
	}

	/**
	 * 用户登录错误日志
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
			$sTime = sget("sTime",'s','input_time'); //搜索时间类型
			$where.=getTimeFilter($sTime); //时间筛选

			if($this->user_id>0) $where.=" and user_id='$this->user_id' ";	

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
						
			//登录渠道:1web,2app,3wap,4微信
		    $user_chanel = L('user_chanel');

			foreach($list['data'] as $k=>$val){
				//请求时间
				$list['data'][$k]['input_time']=$val['input_time']>1000 ? date("Y-m-d H:i:s",$val['input_time']) : '-';
				//用户IP
				$list['data'][$k]['ip']=$val['ip'];
				//操作描述
				$list['data'][$k]['chanel']=$user_chanel[$val['chanel']];
				//会员名
				$list['data'][$k]['name']= M('user:customerContact')->getColByName($val['user_id']);
				
			}
			$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>'');
			$this->json_output($result);
		}
		
		$this->assign('page_title','用户登录错误日志');
		$this->display('userLogErr.list.html');
	}
}
?>
