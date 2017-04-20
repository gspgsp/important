<?php
/**
 * 客户信息管理
 */
class blackcustomerAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->assign('status',L('status'));// 联系人用户状态
		$this->assign('type',L('company_type'));//工厂类型
		$this->assign('level',L('company_level'));//客户级别
		$this->assign('identification',L('identification'));//客户级别
		$this->db=M('public:common')->model('customer');
		$this->doact = sget('do','s');
		$this->public = sget('isPublic','i',0);
	}

	/**
	 * 会员列表
	 * @access public
	 * @return html
	 */
	public function init(){
		$action=sget('action','s');
		if($action=='grid'){ //获取列表
			$this->_grid();exit;
		}
		$this->assign('page_title','黑名单列表');
		$this->display('blackcustomer.list.html');
	}


	/**
	 * Ajax获取列表内容
	 * @access private
	 * @return html
	 */
	public function _grid(){
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$sortField = sget("sortField",'s','c_id'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序

		$where = '`status` = 9';
		$sTime = sget("sTime",'s','input_time'); //搜索时间类型
		$where.=getTimeFilter($sTime); //时间筛选
		$status = sget("status",'s',''); //状态
		if($status!='') $where.=" and status='$status' ";
		$type = sget("type",'s',''); //状态
		if($type!='') $where.=" and type='$type' ";//type 客户类型
		$level = sget("level",'s',''); //状态
		if($level!='') $where.=" and level='$level' ";//level 客户级别
		$identification = sget("identification",'s',''); //认证
		if($identification!='') $where.=" and identification='$identification' ";
		// 关键词
		$key_type=sget('key_type','s','c_id');
		$keyword=sget('keyword','s');
		if(!empty($keyword)){
			if($key_type=='c_name'){
				$cidshare = M('user:customer')->getcidByCname($keyword);
				$where.=" and $key_type like '%$keyword%' ";
			}elseif($key_type=='customer_manager'){
				$adms = join(',',M('rbac:adm')->getIdByName($keyword));
				$where.=" and $key_type in ($adms) ";
				$sons = explode(',',M('rbac:rbac')->getSons($_SESSION['adminid']));  //领导
				$pass = in_array($adms,$sons);
				if(!M('rbac:adm')->getIdByName($keyword) && $_SESSION['adminid'] != 1){
					$this->error('<font style="color:red">查询的交易员不存在！</font>');
				}else if(count(M('rbac:adm')->getIdByName($keyword)) > 1 && $_SESSION['adminid'] != 1){
					$this->error('<font style="color:red">暂时不支持模糊查询交易员</font>');
				}else if($_SESSION['adminid'] != $adms && $_SESSION['adminid'] != 1 && 	!$pass){
					$this->error('<font style="color:red">只支持查询自己及下属哦！</font>');
				}
			}elseif($key_type=='need_product'){
				$where.=" and `need_product_adm` like '%$keyword%' ";
			}else{
				$where.=" and $key_type='$keyword' ";
			}
		}
		//筛选自己的客户
		// if($_SESSION['adminid'] != 1 && $_SESSION['adminid'] > 0){
		// 	$where .= " and `customer_manager` =  {$_SESSION['adminid']} ";
		// }
		$list=$this->db
		            ->where($where)
			->page($page+1,$size)
			->order("$sortField $sortOrder")
			->getPage();
		foreach($list['data'] as $k=>$v){
		 	$list['data'][$k]['customer_manager'] = M('rbac:adm')->getUserByCol($v['customer_manager']);
			$list['data'][$k]['chanel']=L('company_chanel')[$v['chanel']];//客户渠道
			$list['data'][$k]['level']=L('company_level')[$v['level']];
			$list['data'][$k]['depart']=C('depart')[$v['depart']];
			// $list['data'][$k]['identification']=L('identification')[$v['identification']];//认证
			$list['data'][$k]['type']=L('company_type')[$v['type']];
			$list['data'][$k]['input_time']=$v['input_time']>1000 ? date("y-m-d H:i",$v['input_time']) : '-';
			$list['data'][$k]['update_time']=$v['update_time']>1000 ? date("y-m-d H:i",$v['update_time']) : '-';
		}
		$this->assign('isPublic',$this->public);
		$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>'');
		$this->json_output($result);
	}
	/**
	 * 批量设置客户状态
	 * @access public
	 * @return html
	 */
	public function saveTags(){
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //获取UI传递的参数
		if(empty($data)){
			$this->error('错误的操作');
		}
		foreach($data as $v){
			$update=array(
				'status'=>$v['status'],
				'identification'=>$v['identification'],
			);
			$this->db->wherePk($v['c_id'])->update($update);
		}
		$this->success('操作成功');
	}

    /**
     * 会员详细信息
     * @access public
     */
	public function info(){
		$user_id=sget('id','i');
		$cType=sget('ctype','i'); //用户类型
		$this->assign('regionList', arrayKeyValues(M('system:region')->get_regions(1),'id','name'));//第一级省市
		$this->assign('sex',L('sex'));// 性别
		$this->assign('status',L('contact_status'));// 联系人用户状态
		$this->assign('ctype',$cType);//人员类型
		if($user_id<1){
			if($cType==1){
				$this->assign('page_title','新增个人联系人');
				$this->display('contact.add.html');
			}elseif($cType==3){
				$this->assign('type',L('company_type'));//工厂类型
				$this->assign('level',L('company_level'));//客户类别
				$this->assign('chanel',L('company_chanel'));//客户渠道
				$this->assign('credit_level',L('credit_level'));//信用等级
				$this->assign('page_title','新增企业用户');
				$this->display('company.add.html');
			}
			exit;
		}
		$info=$this->db->getPk($user_id); //查询公司信息
		if(empty($info)){
			$this->error('错误的公司信息');
		}
		if($info['origin']){
			$areaArr = explode('|', $info['origin']);
			$info['company_province'] = $areaArr[1];
			$info['company_city']=$areaArr[0];
		}
		$info['file_url1'] = FILE_URL.'/upload/'.$info['file_url'];
		$info['business_licence_pic1'] = FILE_URL.'/upload/'.$info['business_licence_pic'];
		$info['organization_pic1'] = FILE_URL.'/upload/'.$info['organization_pic'];
		$info['tax_registration_pic1'] = FILE_URL.'/upload/'.$info['tax_registration_pic'];
		$info['legal_idcard_pic1'] = FILE_URL.'/upload/'.$info['legal_idcard_pic'];
		//联系人详情
		$this->assign('c_id',$user_id);//分配公司id信息
		$this->assign('regionList', arrayKeyValues(M('system:region')->get_reg(),'id','name'));//第一级省市
		$this->assign('type',L('company_type'));//工厂类型
		$this->assign('level',L('company_level'));//客户类别
		$this->assign('chanel',L('company_chanel'));//客户渠道
		$this->assign('credit_level',L('credit_level'));//信用等级
		$this->assign('info',$info);
		$this->assign('sex',L('sex'));
		$this->display('customer.viewInfo.html');
    }

	 /**
     * 会员详细信息
     * @access public
     */
	public function edit(){
		$c_id=sget('id','i');
		$utype=sget('ctype','i',0);
		$info=$this->db->model('customer')->getPk($c_id);//查询公司信息
		if($info['origin']){
			$areaArr = explode('|', $info['origin']);
			$info['company_province'] = $areaArr[0];
			$info['company_city']=$areaArr[1];
		}
		if(empty($info)) $this->error('错误的公司信息');
		// 根据公司查询联系人信息
		$info_ext = $this->db->model('customer_contact')->getPk($info['contact_id']);
		$this->assign('info_ext',$info_ext); //分陪l联系人信息
		$this->assign('ctype',3);
		// p(arrayKeyValues(M('system:region')->get_regions(1),'id','name'));
		$this->assign('regionList', arrayKeyValues(M('system:region')->get_regions(1),'id','name'));//第一级省市
		$this->assign('type',L('company_type'));//工厂类型
		$this->assign('level',L('company_level'));//客户类别
		$this->assign('chanel',L('company_chanel'));//客户渠道
		$this->assign('credit_level',L('credit_level'));//信用等级
		$this->assign('sex',L('sex'));// 性别
		$this->assign('status',L('contact_status'));// 联系人用户状态
		// p($info);die;
		$this->assign('user',$info); //分陪公司信息
		$this->display('company.info.html');
   	}

	/**
	 * 获取地区列表
	 * @access public
	 * @return html
	 */
	public function getRegion($pid=0){
		//地区列表
		$regList = M('system:region')->get_allRegions();
		//print_r($regList);
		$list = array();
		$pid = sget('pid','i');
		foreach($regList as $k=>$v){
			if($v['pid']==$pid){
				$list[]=array('id'=> $v['id'],'name' => $v['name']);
			}
		}
		$this->json_output($list);
	}

	/**
	 * 新增公司及其联系人信息
	 * @access public
	 * @return html
	 */
	public function addSubmit() {
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //获取UI传递的参数
		$utype = $data['ctype'];
		if($utype==1){
			//验证联系人信息
			$param=array(
				'mobile'=>$data['mobile'],
				'email'=>$data['email'],
				'qq'=>$data['qq'],
			);
		}else{
			//验证公司信息
			$param=array(
				'c_name'=>$data['c_name'],
				'business_licence'=>$data['business_licence'],
				'organization_code'=>$data['organization_code'],
				'tax_registration'=>$data['tax_registration'],
				'legal_idcard'=>$data['legal_idcard'],
				'info_mobile'=>$data['info_mobile'],
				'info_qq'=>$data['info_qq'],
				'info_email'=>$data['info_email'],
			);
		}
		$result=M('user:customerContact')->customerAdd($param,$data);
		if($result['err']>0){
			$this->error($result['msg']);
		}
		$this->success('操作成功');
	}

	//分配公海客户
	function allotCustomer(){
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //获取UI传递的参数
		$c_id = sget('cid','i',0); //未通过审核的产品ID
		if($c_id<1) $this->error('错误的分配信息');
		$_data=array(
			'input_time'=>CORE_TIME,
			'input_admin'=>$_SESSION['name'],
		);
		// 查询下分配的管理员所属的部门
		$depart = M('rbac:adm')->getUserByCol($data['id'],'depart');
		$result = $this->db->where(" c_id = '$c_id'")->update($_data+array('customer_manager'=>$data['id'],'depart'=>$depart,));
		if(!$result) $this->error('操作失败');
		$this->success('操作成功');
	}
	//检查唯一性
	private function _chkUnique($name='mobile',$value=''){
		$exist=$this->db->model('user')->select('user_id')->where("$name='$value'")->getOne();
		return $exist>0 ? true : false;
	}
	//彻底删除客户
	public function del(){
		$this->is_ajax=true; //指定为Ajax输出
		$ids=sget('ids','s');
		if(empty($ids)){
			$this->error('操作有误');
		}
		$data = explode(',',$ids);
		if(is_array($data)){
			foreach ($data as $k => $v) {
				$res = M('user:customer')->getColByName($v,"c_id","c_id");
				if($res>0){
					//核查是否有关联订单
					if($this->db->model('order')->where("`c_id`= $v")->getRow()) $this->error('存在关联订单不能删除');
					if($this->db->model('union_order')->where("`sale_id`= $v OR `buy_id` = $v")->getRow()) $this->error('存在关联联营不能删除');
					if($this->db->model('sale_buy')->where("`c_id`= $v")->getRow()) $this->error('存在针对采购的报价不能删除');
					//删除联系人
					$this->db->model('customer_contact')->where("`c_id`=$v")->delete();
					//新增客户流转记录日志----S
					$remarks = "对客户操作：从黑名单中彻底删除";// 审核用户
					M('user:customerLog')->addLog($v,'delete','黑名单客户','从系统中彻底删除',1,$remarks);
					//新增客户流转记录日志----E
				}
				//查询是不是有该用户的报价
				if($this->db->model('purchase')->where("`c_id`=$v")->getRow()){
					$this->db->model('purchase')->where("`c_id`=$v")->delete();
				}
			}
		}
		$result=$this->db->model('customer')->where("c_id in ($ids)")->delete();
		if($result){
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}
	//还原进入黑名单的客户
	public function restore(){
		$this->is_ajax=true; //指定为Ajax输出
		$ids=sget('ids','s');
		if(empty($ids)){
			$this->error('操作有误');
		}
		$data = explode(',',$ids);
		if(is_array($data)){
			foreach ($data as $k => $v) {
				$res = M('user:customer')->getColByName($v,"c_id","c_id");
				if($res>0){
					//还原联系人
					$this->db->model('customer_contact')->where("`c_id`=$v")->update(array('status'=>2));
					//新增客户流转记录日志----S
					$remarks = "对客户操作：黑名单还原为公海或私海客户";// 审核用户
					M('user:customerLog')->addLog($v,'revocation','黑名单客户','还原为公海或私海客户',1,$remarks);
					//新增客户流转记录日志----E
				}
			}
		}
		$result=$this->db->model('customer')->where("c_id in ($ids)")->update(array('status'=>2));
		if($result){
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}

}