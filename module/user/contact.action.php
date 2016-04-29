<?php
/**
 * 联系人信息管理
 */
class contactAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->db=M('public:common')->model('customer_contact');
	}
	/**
	 * 联系人列表
	 * @access public 
	 * @return html
	 */
	public function init(){
		$action=sget('action','s');
		if($action=='grid'){ //获取列表
			$this->_grid();exit;
		}elseif($action=='remove'){ //获取列表
			$this->_remove();exit;
		}
		$this->assign('page_title','资源库列表');
		$this->display('contact.list.html');
	}
	/**
	 * Ajax获取列表内容
	 * @access private 
	 * @return html
	 */
	private function _grid(){
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$sortField = sget("sortField",'s','input_time'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
		//搜索条件
		$where=" 1 ";
		//筛选状态
		$status=sget('status','i',0);
		if($status !=0)  $where.=" and `status` =".$status;
		//筛选时间
		$sTime = sget("sTime",'s','input_time'); //搜索时间类型
		$where.=getTimeFilter($sTime); //时间筛选
		//关键词搜索
		$key_type=sget('key_type','s','name');
		$keyword=sget('keyword','s');
		if(!empty($keyword) && $key_type=='name' ){
			$where.=" and `$key_type`  like '%$keyword%' ";
		}elseif(!empty($keyword) && $key_type=='c_id'){
			$keyword=M('user:customer')->getColByName($keyword,$key_type,'c_name');
			$where.=" and `$key_type`  = '$keyword' ";
		}elseif(!empty($keyword)){
			$where.=" and `$key_type`  = '$keyword' ";
		}
		$list=$this->db->where($where)
					->page($page+1,$size)
					->order("$sortField $sortOrder")
					->getPage();
		foreach($list['data'] as $k=>$v){
			$list['data'][$k]['c_id']= M('user:customer')->getColByName($v['c_id']);
			$list['data'][$k]['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
			$list['data'][$k]['update_time']=$v['update_time']>1000 ? date("Y-m-d H:i:s",$v['update_time']) : '-';
		}
		
		$result=array('total'=>$list['count'],'data'=>$list['data']);
		$this->json_output($result);
	}	
	/**
	 * Ajax删除节点s
	 * @access private 
	 */
	private function _remove(){
		$this->is_ajax=true; //指定为Ajax输出
		$ids=sget('ids','s');
		if(empty($ids)){
			$this->error('操作有误');	
		}
		$result=$this->db->where("user_id in ($ids)")->delete();
		if($result){
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}
	
	/**
	 * 编辑已存在的数据
	 * @access public 
	 * @return html
	 */
	public function submit(){
		$this->is_ajax=true; //指定为Ajax输出
		$user_id=sget('user_id','i',0);
		$data = sdata(); //获取UI传递的参数
		$utype = $data['ctype'];
		if($utype==1){
			//验证联系人信息
			$para=array(
				'moblie'=>$data['moblie'],
				'email'=>$data['moblie'],
				'qq'=>$data['moblie'],
			);
		}

		$result=M("user:customerContact")->customerUpdate($param,$data,$user_id);
		if($result['err']>0){
			$this->error($result['msg']);
		}
		$this->success('操作成功');

	}



	public function info(){
		$this->is_ajax=true;
		$user_id=sget('id','i');
		if($user_id>0){
			$info=$this->db->wherePk($user_id)->getRow();
		}
		//联系人详情
		$this->assign('info',$info);
		$this->assign('status',L('status'));
		$this->assign('sex',L('sex'));
		$this->assign('page_title','联系人列表');
		$this->display('contact.edit.html');

	}


}