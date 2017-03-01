<?php
/** 
 * 管理员列表
 */
class contractAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->action = sget('action','');
		$this->db=M('public:common')->model('transport_contract');
		$this->doact = sget('do','s');
		$this->public = sget('isPublic','i',0);
	}
	
	/**
	 * 所有管理员
	 * @access public 
	 * @return html
	 */
	public function init(){
		$action=sget('action');
		if($action=='grid'){
			$page = sget("pageIndex",'i',0); //页码
			$size = sget("pageSize",'i',20); //每页数
			$sortField = sget("sortField",'s','logistics_contract_id'); //排序字段
			$sortOrder = sget("sortOrder",'s','asc'); //排序
			$startTime = sget("startTime");
			$endTime = sget("endTime");
			$where='1';
			//状态搜索
			$status = sget('status','s','');
			if($status!==''){
				$where.=" and `status`= $status";
			}
			//时间搜索
			$sTime = sget('sTime','s','');
			if($sTime=='contract_time'){
				$where.=" and contract_time>='$startTime' and contract_time<='$endTime'";
			}
			if($sTime=='delivery_time'){
				$where.=" and delivery_time>='$startTime' and delivery_time<='$endTime'";
			}
			//关键词
			$key_type=sget('key_type','s','username');
			$keyword=sget('keyword','s');
			if(!empty($keyword)){
				$where.=" and $key_type='$keyword' ";
			}

			$list=$this->db->where($where)
				->page($page+1,$size)
				->order("$sortField $sortOrder")
				->getPage();

			foreach($list['data'] as $k=>$v){
				$map=$list['data'][$k]['second_part_company_id'];
				$map1=$list['data'][$k]['second_part_contact_id'];
				$company=M('public:common')->model('logistics_supplier')->where("supplier_id in ($map)")->select("supplier_name")->getAll();
				$company1=M('public:common')->model('logistics_contact')->where("id in ($map1)")->select("contact_name")->getAll();
				$list['data'][$k]['second_part_company_name']=$company['0']['supplier_name'];
				$list['data'][$k]['second_part_contact_name']=$company1['0']['contact_name'];
			}
			$result=array('total'=>$list['count'],'data'=>$list['data']);
			file_put_contents('/tmp/xielei.txt',print_r($result['data'],true),FILE_APPEND);
			$this->json_output($result);
		}
		$this->company_json=setMiniConfig(arrayKeyValues(M('public:common')->model('logistics_supplier')->select("supplier_id as id,supplier_name as name")->getAll(),'id','name'));
		$this->name_json=setMiniConfig(arrayKeyValues(M('public:common')->model('logistics_contact')->select("id,contact_name as name")->getAll(),'id','name'));
		$this->assign('page_title','管理员列表');
		$this->display('contract.init.html');
	}
	/**
	 * Ajax保持数据
	 * @access public 
	 */
	public function ajaxSave(){
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //传递的参数	
		if(empty($data)){
			$this->error('错误的请求');
		}
		$_data=array(
			'goods_name'=>$data['goods_name'],
			'start_place'=>$data['start_place'],
			'contract_time'=>$data['contract_time'],
			'delivery_time'=>$data['delivery_time'],
			'driver_name'=>$data['driver_name'],
			'plate_number'=>$data['plate_number'],
			'driver_idcard'=>$data['driver_idcard'],
			'second_part_company_id'=>$data['second_part_company_id'],
			'second_part_contact_id'=>$data['second_part_contact_id'],
			'second_part_contact_tel'=>$data['second_part_contact_tel'],
			'second_part_contact_fax'=>$data['second_part_contact_fax'],
		);
		if(!empty($data['logistics_contract_id'])){
			$this->db->wherePk($data['logistics_contract_id'])->update($_data);
		}else{
			$this->db->add($_data);
		}
		$this->success('操作成功');
	}
	/**
	 * 获得物流公司联系人列表数据详情
	 * @access public
	 * @return html
	 */
	public function get_contact_list()
	{
		$company_id = sget('company_id','s');
		$contacts   = M("operator:logisticsContact")->where("supplier_id=".$company_id)->getAll();
		//$order_info_new = M('public:common')->model('sale_log slg')->leftjoin("purchase p","p.id=slg.purchase_id")->where('slg.o_id='.$order_id)->getAll();
		//var_dump($order_info_new);
		foreach($contacts as $contact)
		{
			$contact_name_info[]= array('id'=>$contact['id'],'name'=>$contact['contact_name'],'tel'=>$contact['contact_tel'],'fax'=>$contact['comm_fax']);
		}

		$this->json_output($contact_name_info);

	}
	/**
	 * 获得物流公司联系人详情数据详情
	 * @access public
	 * @return html
	 */
	public function get_contact_info()
	{
	
	    $contact_id = sget('contact_id','s');
	
	    $contact    = M("operator:logisticsContact")->where("id=".$contact_id)->getRow();
	
	    $this->json_output($contact);
	}

	/**
	 * 销毁合同
	 * @access public
	 */
	public function remove(){
		$this->is_ajax=true; //指定为Ajax输出
		$ids=sget('ids','s');
		if(empty($ids)){
			$this->error('操作有误');
		}
		$ids=explode(',', $ids);
		foreach ($ids as $k => $v) {
			$result=$this->db->where("logistics_contract_id = ($v)")->delete();
		}
		if($result){
			$cache=cache::startMemcache();
			$cache->delete('contract');
			$this->success('操作成功');
		}else{
			$this->error('删除操作失败');
		}
	}
	/**
	 * 审核运输合同
	 * @access public
	 */
	public function contract_review($logistics_contract_id){
	    $this->logistics_contract_id = sget('logistics_contract_id','');
	    $this->display('contract.review.html');
	}
	/**
	 * 变更合同状态
	 * @access public
	 */
	public function change_status(){
	    $this->is_ajax=true; //指定为Ajax输出
	    $logistics_contract_id=sget('logistics_contract_id','i');
	    $last_edit_id=$_SESSION['adminid'];
	    if(!empty($logistics_contract_id)){
	        $this->db->where("logistics_contract_id=".$logistics_contract_id)->update(array('status'=>1,'last_edit_id'=>$last_edit_id));
	        $this->success('操作成功');
	    }else{
	        $this->error('操作有误');
	    }
	}
}
?>
