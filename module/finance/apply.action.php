<?php
/**
 * 客户申请管理
 */
class applyAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->assign('cus_status',L('status'));// 客户状态
		$this->assign('company_type',L('company_type'));//客户类型
		$this->assign('dispose_status',L('dispose_status')); //申请处理状态
		$this->db=M('public:common')->model('finance_apply');
		$this->doact = sget('do','s');
	}

	/**
	 * 客户申请列表
	 * @access public 
	 * @return html
	 */
	public function init(){
		$action=sget('action','s');
		if($action=='grid'){ //获取列表
			$this->_grid();exit;
		}
		// $this->assign('choose',sget('choose','s')); //单选传参
		$this->assign('page_title','客户申请列表');
		$this->display('customerApply.list.html');
	}
	/**
	 * Ajax获取列表内容
	 * @access private 
	 * @return html
	 */
	public function _grid(){
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$sortField = sget("sortField",'s','create_date'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
		$where = ' 1 ';
		$sTime = sget("sTime",'s','create_date'); //搜索时间类型
		$where.=getTimeFilterByDateTime($sTime); //时间筛选
		$c_name = sget("c_name",'s',''); //公司名
 		$cname_str = M('user:customer')->getLikeCidByCname($c_name);
 		if($c_name!='') $where.=' and app.c_id in ('.$cname_str.')';
		$status = sget("dispose_status",'i'); //审核状态
		if($status!='') $where.=" and app.status=".$status;
		$company_type = sget("company_type",'i'); //客户类型
		if($company_type!='') $where.=" and cus.type=".$company_type;
		$list=$this->db->model('finance_apply app')
					->select('app.*,cus.status as c_status,cus.type,cus.c_name')
					->leftjoin('customer cus','cus.c_id=app.c_id')
					->where($where)->page($page+1,$size)
					->order("$sortField $sortOrder")
					->getPage();
		
		foreach($list['data'] as $k=>$v){
		 	// $cus_infos = M('user:customer')->getRowByName($v['c_id'],'c_name,type,status');
		 	// $list['data'][$k]['admin'] = M('rbac:adm')->getUserByCol($v['customer_manager']);
			$list['data'][$k]['id']=$v['id'];
			$list['data'][$k]['review_info']=$v['review_info'];
			$list['data'][$k]['contact_id']=$v['contact_id'];
			// $list['data'][$k]['c_name']=$cus_infos['c_name'];
			$info = json_decode($v['info'],true);
			$json_arr = array();
			foreach ($info as $key => $value) {
		 		$value['factory'] = M('product:factory')->getFnameById($value['factory']);
				if($v['product_id'] == 1){
					$json_arr[] = array('牌号'=>$value['model'],'品种'=>$value['product_type'],'厂家'=>$value['factory'],'金额'=>$value['total'],'保证金比例'=>$value['percent'],'预计赎期'=>$value['day']);
				}elseif($v['product_id'] == 2){
					$json_arr[] = array('牌号'=>$value['model'],'品种'=>$value['product_type'],'厂家'=>$value['factory'],'金额'=>$value['total'],'预计赎期'=>$value['day']);
				}elseif($v['product_id'] == 3){
				 	// $value['storage'] = M('product:store')->getStoreNameBySid($value['storage']);
				 	$json_arr[] = array('牌号'=>$value['model'],'品种'=>$value['product_type'],'厂家'=>$value['factory'],'金额'=>$value['total'],'融资比例'=>$value['rzbl'],'预计赎期'=>$value['day']);
				}
			}
			$info_json = json_encode($json_arr,JSON_UNESCAPED_UNICODE);
			$list['data'][$k]['info'] = str_replace(array('{','}','[',']'), "", $info_json);
			$list['data'][$k]['dispose_status']=L('dispose_status')[$v['status']];
			$list['data'][$k]['company_type']=L('company_type')[$v['type']];
			$list['data'][$k]['cus_status']=L('status')[$v['c_status']];
			//获取联系人的姓名和手机号
			$contact = $this->db->model('customer_contact')->select('name,mobile')->where('user_id='.$v['contact_id'])->getRow();
			$list['data'][$k]['customer_mobile'] = $contact['name'].'/'.$contact['mobile'];
			if($v['chanel'] == 1){
		 		$list['data'][$k]['create_user']= M('rbac:adm')->getUserByCol($v['create_user']);
			}else{
		 		$list['data'][$k]['create_user'] = $contact['name'];
			}
			$list['data'][$k]['product_name'] = $this->db->model('finance_product')->select('product_name')->where('product_id='.$v['product_id'])->getCol();
			// showtrace();
		}
		$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>'');
		$this->json_output($result);
	}
	public function addModel(){
		$type=sget('type','i',0);
		$this->assign('type',$type); //根据类型显示不同字段
		$this->assign('choose',1); //用于miniopen 弹窗的销毁按钮
		$this->display('product.edit.html');
	}
	/**
	 * 转入风控或拒绝操作
	 */
	public function changeToRisk(){
		$this->is_ajax=true; //指定为Ajax输出
		$data = !empty(sdata())?sdata():$_POST;
		if(empty($data)) $this->error('错误的操作');
		$_data=array(
			'modify_date'=>date('Y-m-d H:i:s',CORE_TIME),
			'modify_user'=>$_SESSION['adminid'],
		);
		// p($data);die;
		//修改申请池的客户状态
		$res = $this->db->model('finance_apply')->where('id = '.$data['id'])->update($data+$_data);
		if(!$res){
			$this->error('操作失败');
		}
		$c_info = $this->db->select('c_id,contact_id')->where('id='.$data['id'])->getRow();
		//去准入列表中查看是否存在该客户，若存在则不进入准入列表
		$c_id = $this->db->model('finance_customer')->select('c_id')->where('c_id='.$c_info['c_id'])->getOne();
		if($c_id){
			$this->success('该客户已在准入列表中');
		}
		$customer_data = array(
			'c_id'=>$c_info['c_id'],
			'contact_id'=>$c_info['contact_id'],
			'status'=>1,
			'create_user'=>$_SESSION['adminid'],
			'create_date'=>date('Y-m-d H:i:s',time()),
			);
		$result = $this->db->model('finance_customer')->add($customer_data);
		if($result){
			$this->success('操作成功');
		}else{
			$this->error('操作失败');
		}
	}
    /**
     * 会员详细信息
     * @access public
     */
	public function info(){
		$viewType=sget('viewType','s');
		$c_id=sget('cid','i');
		$aid=sget('aid','i');
		$contact_id=sget('contact_id','i');
		$this->assign('regionList', arrayKeyValues(M('system:region')->get_regions(1),'id','name'));//第一级省市
		// if($viewType == 'risk_customer_info'){
		// 	$sp_id=sget('sp_id','i');
		// 	$this->assign('sp_id',$sp_id);
		// }
		$this->assign('viewType',$viewType);
		if($c_id<1){
			$this->assign('page_title','新增客户');
			$this->assign('otype','addopus'); //新增订单关联前台显示
			$this->display('customerApply.add.html');
			exit;
		}
		//处理公司信息
		$info=M('user:customer')->getPk($c_id); //查询公司信息
		if(empty($info)){
			$this->error('错误的公司信息');	
		}
		if($info['origin']){
			$areaArr = explode('|', $info['origin']);
			$info['company_province'] = $areaArr[1];
			$info['company_city']=$areaArr[0];
		}
		$info['input_time'] = date('Y-m-d H:i:s',$info['input_time']);
		$info['update_time'] = date('Y-m-d H:i:s',$info['update_time']);
		$info['file_url1'] = FILE_URL.'/upload/'.$info['file_url'];
		$info['business_licence_pic1'] = FILE_URL.'/upload/'.$info['business_licence_pic'];
		$info['organization_pic1'] = FILE_URL.'/upload/'.$info['organization_pic'];
		$info['tax_registration_pic1'] = FILE_URL.'/upload/'.$info['tax_registration_pic'];
		$info['legal_idcard_pic1'] = FILE_URL.'/upload/'.$info['legal_idcard_pic'];
		$this->assign('c_id',$c_id);//分配公司id信息
		$this->assign('aid',$aid);
		$this->assign('regionList', arrayKeyValues(M('system:region')->get_reg(),'id','name'));//第一级省市
		$this->assign('type',L('company_type'));//工厂类型
		$this->assign('level',L('company_level'));//客户类别
		$this->assign('chanel',L('company_chanel'));//客户渠道
		$this->assign('credit_level',L('credit_level'));//信用等级
		$this->assign('info',$info);
		//处理联系人信息
		$contact = $this->db->model('customer_contact')->select('*')->where('user_id='.$contact_id)->getRow();
		$this->assign('contact',$contact);
		//处理审核信息回显
		$review_info = $this->db->model('finance_apply')->select('review_info')->where('id='.$aid)->getOne();
		$this->assign('review_info',$review_info);
		$this->display('customer.viewInfo.html');
    }
    /**
     * 审核列表
     * @Author   yezhongbao
     * @DateTime 2016-10-24T09:31:57+0800
     * @return   [type]                   [description]
     */
    public function verifyList(){
    	$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$sortField = sget("sortField",'s','modify_date'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
    	$aid = sget("aid",'i'); //页码
		$where = 'id='.$aid;
		$list=$this->db ->where($where)->page($page+1,$size)->order("$sortField $sortOrder")->getPage();
		foreach($list['data'] as $k=>$v){
			$list['data'][$k]['status']=L('dispose_status')[$v['status']];
		 	$list['data'][$k]['modify_user'] = M('rbac:adm')->getUserByCol($v['modify_user']);
			$list['data'][$k]['review_info']=$v['review_info'];
		}
		$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>'');
		$this->json_output($result);
    }
	/**
	 * 新增公司及其联系人信息
	 * @access public 
	 * @return html
	 */
	public function addSubmit() {
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //获取UI传递的参数
		if(empty($data)) $this->error('错误的请求');
		$json_arr = array();
		//过滤数组中无用数据
		unset($data['pagesize']);
		foreach ($data['detail'] as $key => $value) {
			unset($data['detail'][$key]['_uid']);
			unset($data['detail'][$key]['_index']);
			$json_arr[] = $value;
		}
		$json_str = json_encode($json_arr);

		$data['info']=$json_str;
		$data['status']=1;
		$data['chanel']=1;
		$data['create_date']=date('Y-m-d H:i:s',time());
		$data['create_user']=$_SESSION['adminid'];
		$res = $this->db->add($data);
		if($res){
			$this->success('操作成功');
		}else{
			$this->error('操作失败');
		}
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
		if($_SESSION['adminid'] != 1 && $_SESSION['adminid'] > 0){
			$pools = M('user:customer')->getCidByPoolCus($_SESSION['adminid']); //共享客户
			if(!empty($pools)){
				$cids = array_values(explode(',', $pools));
			}
		}
		$info_ext['name'] = in_array($info_ext['c_id'],$cids) ? '*' :  $info_ext['name'];
		$info_ext['mobile'] = in_array($info_ext['c_id'],$cids) ? '*' :  $info_ext['mobile'];
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
	 * Ajax删除节点s
	 * @access public 
	 */
	public function remove(){
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
					//删除联系人
					$this->db->model('customer_contact')->where("`c_id`=$v")->update(array('status'=>9));
				}
			}
		}
		$result=$this->db->model('customer')->where("c_id in ($ids)")->update(array('status'=>9));
		if($result){
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}

	public function setsea(){
		$this->is_ajax=true; //指定为Ajax输出
		$ids=sget('ids','s');
		if(empty($ids)){
			$this->error('操作有误');	
		}
		$result=$this->db->model('customer')->where("c_id in ($ids)")->update(array('customer_manager'=>0,'depart'=>0,'status'=>1,));
		if($result){
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
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
	//展示审核页面
	public function chkpage(){
		$id = sget('id','i',0);
		if($id<1) $this->error('信息错误');
		$this->assign('id',$id);
		$this->assign('status',L('status'));// 联系人用户状态
		$this->display('customer.chk.html');
	}
	//审核页面公海用户
	public function chkSubmit(){
		$id =sget('id','i',0);
		$status = sget('status','i');
		if($id<1) $this->error('用户信息错误');
		$result = $this->db->wherePk($id)->update(array('status'=>$status));
		if(!$result) $this->error('操作失败');
		$this->success('操作成功');
	}
	//检查唯一性
	private function _chkUnique($name='mobile',$value=''){
		$exist=$this->db->model('user')->select('user_id')->where("$name='$value'")->getOne();
		return $exist>0 ? true : false;
	}
	//共享客户
	public function share(){
		$id =sget('id','i',0);
		if($id<1) $this->error('用户信息错误');
		$this->assign('id',$id);
		$this->display('customer.share.html');
	}
	//共享客户提交
	public function shareSubmit(){
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //获取UI传递的参数
		if(empty($data)) $this->error('用户信息错误');
		$exits = $this->db->model('customer_pool')->where("`customer_manager` = {$data['id']} and `c_id` = {$data['c_id']}")->getRow();
		if($exits) $this->error('共享记录已经存在');
		$result = $this->db->model('customer_pool')->add(array('customer_manager'=>$data['id'],'c_id'=>$data['c_id'],'input_time'=>CORE_TIME,'input_admin'=>$_SESSION['name'],'share_manager'=>$_SESSION['adminid'],'share_managername'=>$_SESSION['username']));
		if(!$result) $this->error('操作失败');
		$this->success('操作成功');
	}

	//公司名去重
	public function curUnique(){
		$data = trim($_POST['data']);
		if(empty($data)) $this->error('请填写公司名称');
		$res = M('user:customer')->curUnique('c_name',$data);

		$name=$this->db->from('customer c')
            ->join('admin a','a.admin_id=c.customer_manager')
            ->where("c.c_name='$data'")
            ->select("a.name")
            ->getOne();
		if(!$res) $this->success("存在相同的公司名称,请与".$name."联系共享！");
		$this->success('此公司名不重复，可添加');
	}

}