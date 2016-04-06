<?php
/** 
 * 管理员登录日志
 * deray.wang@2014-07-15
 */
class adminLogAction extends adminBaseAction {
	private $title='用户登录日志'; //
	public function __init(){
		$this->debug = false;
		$this->db=M('public:common')->model('adminlog_login');
	}

	/**
	 * 管理员登录日志
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

			$list=$this->db->where($where)
						->page($page+1,$size)
						->order("$sortField $sortOrder")
						->getPage();
						
			//登录渠道:1web,2app,3wap,4微信
		    $user_chanel = L('user_chanel');

			foreach($list['data'] as $k=>$val){
				//请求时间
				$list['data'][$k]['input_time']=$val['input_time']>1000 ? date("Y-m-d H:i:s",$val['input_time']) : '-';
				//操作描述
				$list['data'][$k]['chanel']=$user_chanel[$val['chanel']];
				
			}
			$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>'');
			$this->json_output($result);
		}
		
		$this->assign('page_title','管理员登录日志');
		$this->display('adminLog.list.html');
	}
	
	/**
	 * 管理员登录错误日志
	 * @access public 
	 * @return html
	 */
	public function logErr(){

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

			$list=M('public:common')->model('adminlog_login_err')->where($where)
						->page($page+1,$size)
						->order("$sortField $sortOrder")
						->getPage();
						
			//登录渠道:1web,2app,3wap,4微信
		    $user_chanel = L('user_chanel');

			foreach($list['data'] as $k=>$val){
				//请求时间
				$list['data'][$k]['input_time']=$val['input_time']>1000 ? date("Y-m-d H:i:s",$val['input_time']) : '-';
				if($val['err_code']==2){
					$err_code = "用户名错误";
				}elseif($val['err_code']==3){
					$err_code = "密码错误";
				}
				$list['data'][$k]['err_code']=$err_code;
				//用户IP
				$list['data'][$k]['ip']=$val['ip'];
				//操作描述
				$list['data'][$k]['chanel']=$user_chanel[$val['chanel']];
				
			}
			$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>'');
			$this->json_output($result);
		}
		
		$this->assign('page_title','管理员登录错误日志');
		$this->display('adminLogErr.list.html');
	}
}
?>
