<?php
/**
 * 风控预警
 */
class riskWarningAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->assign('warning_type',L('warning_type'));// 预警类型
		$this->assign('type',L('company_type'));//客户类型
		$this->assign('riskin_status',L('riskin_status')); //申请处理状态
		$this->db=M('public:common')->model('finance_warning');
		$this->doact = sget('do','s');
	}

	/**
	 * 塑料白条预警列表
	 * @access public 
	 * @return html
	 */
	public function white(){
		$action=sget('action','s');
		if($action=='grid'){ //获取列表
			$this->_grid();exit;
		}
		// $this->assign('choose',sget('choose','s')); //单选传参
		$this->assign('product_id',1);
		$this->assign('riskType','white');
		$this->assign('page_title','客户准入列表');
		$this->display('warningWhite.list.html');
	}
	/**
	 * 塑料代采预警列表
	 * @access public 
	 * @return html
	 */
	public function purchase(){
		$action=sget('action','s');
		if($action=='grid'){ //获取列表
			$this->_grid();exit;
		}
		// $this->assign('choose',sget('choose','s')); //单选传参
		$this->assign('product_id',2);
		$this->assign('riskType','purchase');
		$this->assign('page_title','塑料代采预警列表');
		$this->display('warningWhite.list.html');
		
	}
	/**
	 * 仓单融资预警列表
	 * @access public 
	 * @return html
	 */
	public function store(){
		$action=sget('action','s');
		if($action=='grid'){ //获取列表
			$this->_grid();exit;
		}
		// $this->assign('choose',sget('choose','s')); //单选传参
		$this->assign('product_id',3);
		$this->assign('riskType','store');
		$this->assign('page_title','仓库准入列表');
		$this->display('warningWhite.list.html');
	}
	/**
	 * 公海客户列表
	 */
	public function publicCustomer(){
		$action=sget('action','s');
		if($action=='grid'){ //获取列表
			$this->_grid();exit;
		}
		$this->assign('isPublic',1);//公海客户标识
		$this->display('customer.list.html');
	}

	/**
	 * 会员搜索
	 * @access public 
	 * @return html
	 */
	public function soso(){
		$action=sget('action','s');
		if($action=='grid'){ //获取列表
			$this->_grid();exit;
		}
		$this->assign('page_title','会员搜索');
		$this->display('user.so.html');
	}

	/**
	 * Ajax获取列表内容
	 * @access private 
	 * @return html
	 */
	public function _grid(){
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$sortField = sget("sortField",'s','id'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
		$where = ' 1 ';
		$riskType = sget("riskType",'s'); //状态
		if($riskType == 'purchase'){
			$where.=" and warn.product_id = 1 ";	
		}elseif ($riskType == 'white') {
			$where.=" and warn.product_id = 2 ";	
		}elseif($riskType == 'store'){
			$where.=" and warn.product_id = 3 ";	
		}

		$sTime = sget("sTime",'s','create_time'); //搜索时间类型
		// p($sTime);die;
		$where.=getTimeFilterByDateTime($sTime); //时间筛选

		$c_name = sget("c_name",'s',''); //客户名称
		if($c_name!='') $where.=" and cus.c_name='$c_name' ";
		$order_sn = sget("order_sn",'s',''); //订单号
		if($order_sn!='') $where.=" and o.order_sn='$order_sn' ";
		$type = sget("type",'i',''); //客户类型
		if($type!='') $where.=" and cus.type='$type' ";
		$warning_status = sget("warning_status",'i'); //状态
		if($warning_status!='') $where.=" and warn.risk_status=$warning_status ";//预警状态
		// //筛选自己的客户
		// 	if($_SESSION['adminid'] != 1 && $_SESSION['adminid'] > 0){
		// 		$sons = M('rbac:rbac')->getSons($_SESSION['adminid']);  //领导
		// 		$pools = M('user:customer')->getCidByPoolCus($_SESSION['adminid']); //共享客户
		// 		$where .= " and `customer_manager` in ($sons) ";
		// 		if(!empty($cidshare)){
		// 			$where .= " or `c_id` in ($cidshare)";
		// 		}else{
		// 			if(!empty($pools)){
		// 				$cids = explode(',', $pools);
		// 				$where .= " or `c_id` in ($pools)";
		// 			}
		// 		}
		// 	}
			// p($where);die;
		$list=$this->db->model('finance_warning warn')
					->select('warn.*,o.order_sn,o.finance_apply_status,o.c_id,o.total_price,cus.c_name,cus.type')
					->leftjoin('order o','o.o_id=warn.o_id')
					->leftjoin('customer cus','o.c_id=cus.c_id')
					->where($where)
					->page($page+1,$size)
					->order("$sortField $sortOrder")
					->getPage();
		// p($list);
		foreach($list['data'] as $k=>$v){
			$finance_customer = M('finance:customer')->getCinfoById($v['c_id']);
			$list['data'][$k]['status']=L('warning_type')[$v['risk_status']];
			$list['data'][$k]['type']=L('company_type')[$v['type']];
			$list['data'][$k]['limit'] = $finance_customer['limit'];		
			$list['data'][$k]['remain']	= $finance_customer['remain']; 	
			$list['data'][$k]['product_name'] = $this->db->model('finance_product')->select('product_name')->where('product_id='.$v['product_id'])->getCol();
		}
		$this->assign('isPublic',$this->public);
		$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>'');
		$this->json_output($result);
	}
	public function getNowOrderValue(){
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //获取UI传递的参数
		p($_POST);die;
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
		// $this->assign('status',L('contact_status'));// 联系人用户状态
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
	 * 新增公司及其联系人信息
	 * @access public 
	 * @return html
	 */
	public function addSubmit() {
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //获取UI传递的参数
		$utype = $data['ctype'];

		if($utype==1){
			if(empty($data['mobile']) && empty($data['tel'])) $this->error('手机或者电话至少填写一个'); 
			//验证联系人信息
			$param=array(
				'mobile'=>$data['mobile'],		 
				'email'=>$data['email'],		 
				'qq'=>$data['qq'],	 
			);
		}else{
			if(empty($data['info_mobile']) && empty($data['info_tel'])) $this->error('手机或者电话至少填写一个'); 
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

}