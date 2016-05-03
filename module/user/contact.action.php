<?php
/**
 * 联系人信息管理
 */
class contactAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->db=M('public:common')->model('customer_contact');
		$this->assign('status',L('contact_status'));// 联系人用户状态
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
		$c_id=sget('c_id','i',0);
		if($c_id !=0)  $where.=" and `c_id` =".$c_id;
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
			$list['data'][$k]['customer_manager'] = M('rbac:adm')->getUserByCol($v['customer_manager']);
			$list['data'][$k]['depart']=C('depart')[$v['depart']];
			$list['data'][$k]['sex']=L('sex')[$v['sex']];
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
		$data = explode(',',$ids);
		if(is_array($data)){
			foreach ($data as $k => $v) {
				$res = M('user:customer')->getColByName($v,"c_id","contact_id");
				if($res>0){
					$this->error('主联系人不能删除');
				}
			}
		}
		$result=$this->db->where("user_id in ($ids)")->delete();
		if($result){
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}
	
	public function info(){
		$this->is_ajax=true;
		$user_id=sget('id','i');
		if($user_id>0){
			$info=$this->db->wherePk($user_id)->getRow();
			if($info['c_id']>0) $c_name = M('user:customer')->getColByName("$info[c_id],c_name"); // 根据公司id查询公司名字
		}
		//联系人详情
		$this->assign('c_name',$c_name);
		$this->assign('info',$info);
		$this->assign('status',L('contact_status'));
		$this->assign('sex',L('sex'));
		$this->assign('page_title','联系人列表');
		$this->display('contact.edit.html');

	}

	public function viewInfo(){
		$this->is_ajax=true;
		$user_id=sget('id','i');
		if($user_id>0){
			$info=$this->db->wherePk($user_id)->getRow();
			$c_info = M('user:customer')->getInfoByUid("$user_id"); // 根据公司id查询公司名字
			if($c_info['origin']){
				$areaArr = explode('|', $c_info['origin']);
				$c_info['company_province'] = $areaArr[1];
				$c_info['company_city']=$areaArr[0];
			}
		}
		$c_info['file_url1'] = FILE_URL.'/upload/'.$c_info['file_url'];
		$c_info['business_licence_pic1'] = FILE_URL.'/upload/'.$c_info['business_licence_pic'];
		$c_info['organization_pic1'] = FILE_URL.'/upload/'.$c_info['organization_pic'];
		$c_info['tax_registration_pic1'] = FILE_URL.'/upload/'.$c_info['tax_registration_pic'];
		$c_info['legal_idcard_pic1'] = FILE_URL.'/upload/'.$c_info['legal_idcard_pic'];
		//联系人详情
		$this->assign('regionList', arrayKeyValues(M('system:region')->get_reg(),'id','name'));//第一级省市
		$this->assign('type',L('company_type'));//工厂类型
		$this->assign('level',L('company_level'));//客户类别
		$this->assign('chanel',L('company_chanel'));//客户渠道
		$this->assign('credit_level',L('credit_level'));//信用等级
		$this->assign('c_info',$c_info);
		$this->assign('info',$info);
		$this->assign('status',L('contact_status'));
		$this->assign('sex',L('sex'));
		$this->assign('page_title','联系人列表');
		$this->display('contact.viewInfo.html');

	}


}