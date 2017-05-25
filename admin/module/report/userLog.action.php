<?php
/**
 * 用户登录日志
 * deray.wang@2014-06-19
 */
class userLogAction extends adminBaseAction {
	private $title='用户登录日志'; //
	public function __init(){
		$this->debug = false;
		$this->db=M('public:common')->model('log_login');
	}

	/**
	 * 用户登录日志
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

			//查询条件
			$where=" 1 ";
			$sTime = sget("sTime",'s','input_time'); //搜索时间类型
			$where.=getTimeFilter($sTime); //时间筛选
			$chanel = sget('chanel','i',0);
			if($chanel>0) $where.=" and chanel='$chanel' ";
			//关键词
			$key_type=sget('key_type','s','user_id');
			//关键词的值
			$keyword=sget('keyword','s');
			if(!empty($keyword)){
				if($key_type=='mobile'){ //如果是根据手机号查找
					$_id=M('public:common')->model('user')->select('user_id')->where("mobile like '%$keyword%'")->getCol();
					$id=!empty($_id) ? join(',',$_id) : '-1';
					$where.=" and user_id in ($id) ";
				}else{
					$where.=" and $key_type='$keyword' ";
				}
			}
			//查询取出数据
			$list=$this->db->model('log_login')->where($where)
						->page($page+1,$size)
						->order("$sortField $sortOrder")
						->getPage();
			foreach($list['data'] as $k=>$val){
				$list['data'][$k]['input_time']=$val['input_time']>1000 ? date("Y-m-d H:i:s",$val['input_time']) : '-';//请求时间
				$list['data'][$k]['ip']=$val['ip'];//用户IP
				$list['data'][$k]['chanel']= L('user_chanel')[$val['chanel']];//操作描述
				$list['data'][$k]['name']= M('user:customerContact')->getColByName($val['user_id']);//会员名
				$list['data'][$k]['cname']= M('user:customer')->getInfoByUid($val['user_id'])['c_name'];//会员名
			}
			$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>'');
			$this->json_output($result);
		}
		$this->assign('page_title','用户登录日志');
		$this->display('userLog.list.html');
	}
}
?>
