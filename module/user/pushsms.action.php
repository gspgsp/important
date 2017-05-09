<?php
/**
 *客户报价推送短信信息
 *yumeilin
 */
class pushsmsAction extends adminBaseAction {
	protected $db;
	public function __init(){
		$this->db=M('public:common')->model('log_sms_history');//短信发送记录表
		$this->status=array(1=>'待发',2=>'成功',3=>'失败');//短信发送状态
	}
	/**
	 *
	 * @access public
	 * @return html
	 */
	public function init(){
		$action=sget('action','s');
		if($action=='grid'){ //获取列表
			$this->_grid();exit;
		}
	}
	/**
	 *
	 * @access private
	 * @return html
	 */
	private function _grid(){
	    $c_id = sget("c_id",'i');
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$sortField = sget("sortField",'s','input_time'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
		//条件筛选查询
		$where=" 1 ";
		//客户联系人筛选
		$where.="and ct.c_id=$c_id and ls.stype=12";
		//时间搜索
		//$sTime = sget("sTime",'s','input_time'); //搜索时间类型
		$where.= getTimeFilter('ls.input_time'); //时间筛选
		//关键词
		$key_type=sget('key_type','s');
		$keyword=sget('keyword','s');
		if(!empty($keyword)){
		$where.=" and ad.name like '%$keyword%' ";
		}
		$list1=M('public:common')->model('customer_contact ct')
            		->select('ls.msg,ls.input_time,ls.status,ls.user_id,ct.name as cn_name,ct.mobile,ct.customer_manager,ad.name')
            		->rightjoin('log_sms_history ls','ct.user_id=ls.user_id')
            		->leftjoin('admin ad','ad.admin_id = ct.customer_manager')->where($where)
					->page($page+1,$size)
					->order("$sortField $sortOrder")
					->getPage();
		$list2=M('public:common')->model('customer_contact ct')
            		->select('ls.msg,ls.input_time,ls.status,ls.user_id,ct.name,ct.mobile,ct.customer_manager,ad.name')
            		->rightjoin('log_sms ls','ct.user_id=ls.user_id')
            		->leftjoin('admin ad','ad.admin_id = ct.customer_manager')->where($where)
            		->page($page+1,$size)
            		->order("$sortField $sortOrder")
            		->getPage();
		for($i=0;$i<count($list1['data']);$i++){
		array_push($list2['data'],$list1['data'][$i]);
		}
		foreach($list2['data'] as &$v){
			$v['customer_manager_name'] = M('rbac:adm')->getUserByCol($v['customer_manager']);//交易员
			$v['sms_status'] = $this->status[$v['status']];
			$v['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
		}
		$msg="";
		$result=array('total'=>($list1['count']+$list2['count']),'data'=>$list2['data'],'msg'=>$msg);
		$this->json_output($result);
	}
}