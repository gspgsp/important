<?php
/**
 * 客户信息管理
 */
class customerAction extends adminBaseAction {
	public function __init(){
		ini_set('display_errors','On');
		$this->debug = false;
		$this->assign('status',L('status'));// 联系人用户状态
		$this->assign('type',L('company_type'));//工厂类型
		$this->assign('level',L('company_level'));//客户级别
		$this->assign('identification',L('identification'));//客户级别
		$this->assign('credit',L('is_credit'));//授信状态
		$this->assign('company_chanel',L('company_chanel'));//客户来源渠道
		$this->assign('send_msg',L('msg'));//发短信
		$this->db=M('public:common')->model('customer');
		$this->doact = sget('do','s');
		$this->pt = sget('pt','i',2);
		$this->public = sget('isPublic','i',0);
		$this->moreChoice = sget('moreChoice','i',0);
		$this->cooperation = sget('cooperation','i',0);
		$this->supplier = sget('supplier','i',0);
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
		$this->assign('privated',sget('privated','i',1));//私有客户
		//私有客户
		$this->assign('choose',sget('choose','s')); //单选传参
		$this->assign('page_title','会员列表');
		$this->display('customer.list.html');
	}
	//交易助手信息
	public function init_helper(){
		$action=sget('action','s');
		if($action=='grid'){ //获取列表
			$this->_grid();exit;
		}
		$this->assign('privated',sget('privated','i',1));//私有客户
		//私有客户
		 $this->assign('helper',1);
		$this->assign('choose',sget('choose','s')); //单选传参
		$this->assign('page_title','会员列表');
		$this->display('customer.list.html');
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
	//已合作客户
	public function cooperation(){
		$this->assign('cids',sget('cids','s'));
		$action=sget('action','s');
		if($action=='grid'){ //获取列表
			$this->_grid();exit;
		}
		$this->assign('cooperation',1);//已合作客户的标识
		$this->assign('page_title','已合作会员列表');
		$this->display('customer.list.html');
	}
	//已合作客户助手
	public function cooperation_helper(){
		$action=sget('action','s');
		if($action=='grid'){ //获取列表
			$this->_grid();exit;
		}
		$this->assign('helper',1);
		$this->assign('cooperation',1);//已合作客户的标识
		$this->assign('page_title','已合作会员列表');
		$this->display('customer.list.html');
	}
	//已合作供应商
	public function supplier(){
		$action=sget('action','s');
		if($action=='grid'){ //获取列表
			$this->_grid();exit;
		}
		$this->assign('supplier',1);//已合作供应商的标识
		$this->assign('page_title','已合作供应商会员列表');
		$this->display('customer.list.html');
	}
	//根据客户id拉客户列表，不分客户类型
	public function customerList(){
		$this->assign('cids',sget('cids','s'));
		$action=sget('action','s');
		if($action=='grid'){ //获取列表
			$this->_grid();exit;
		}
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
		$pt = sget("pt",'i',2);
		$sortField = sget("sortField",'s','c_id'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
		$where = ' 1 ';
		$where .= ' and `status` != 10 ';
		// 筛选合作客户
		if(sget('cooperation','i',0) == 1) $where .= ' and `is_sale` = 1 and `customer_manager` > 0  ';
		// 筛选供应商
		if(sget('supplier','i',0) == 1) $where .= ' and `is_pur` = 1  and `customer_manager` > 0';
		//私海客户
		if(sget('privated','i',0) == 1) $where .= ' and `is_pur` = 0 and  `is_sale` = 0  and `customer_manager` > 0 ';
		// if($pt ==2) $where .= ' and `status` != 9 and  `status` != 8';
		// pt主要标示黄名单客户的一些信息（8代表黄明单）
		if($pt ==2) $where .= ' and `status` != 9 and  `status` != 8 ';
		if($pt ==1) $where .= ' and `status` != 9 ';
		$where .= $this->public == 0 ? ' and `customer_manager` != 0 ' : ' and `customer_manager` = 0 ';
		$sTime = sget("sTime",'s','input_time'); //搜索时间类型
		$where.=getTimeFilter($sTime); //时间筛选
		$status = sget("status",'s',''); //状态
		if($status!=''){
			$where.=" and status='$status' ";
		}else{
			//xianghui,公海客户默认显示已审核
			if(sget('isPublic','i',0) == 1) $where .= ' and `status` = 2';
		}
		$type = sget("type",'s',''); //状态
		if($type!='') $where.=" and type='$type' ";//type 客户类型
		$company_chanel = sget("company_chanel",'s',''); //渠道来源筛选
		if($company_chanel!='') $where.=" and chanel = '$company_chanel' ";//type 渠道来源筛选
		$invoice = sget("invoice",'i',''); //开票资料状态
		if($invoice != 0) $where .=" and invoice=$invoice ";//type 客户类型
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
		//接收由cid组成的字符串（1,2,3,4）
		$cids=sget('cids','s');//
		if($cids)  $where.=" and `c_id` in ".$cids;
		//筛选自己的客户
		if($this->public == 0 && $this->moreChoice == 0){
			if($_SESSION['adminid'] != 1 && $_SESSION['adminid'] > 0 && $key_type != 'customer_manager'){
				$sons = M('rbac:rbac')->getSons($_SESSION['adminid']);  //领导
				// $pools = M('user:customer')->getCidByPoolCus($_SESSION['adminid']); //共享客户(原来共享不存在上下级修改为存在上下级)
				$pools = M('user:customer')->getCidPoolCus($sons);
				$where .= " and `customer_manager` in ($sons) ";
				if(!empty($keyword) && $cidshare){
					//我用这个用户的id去共享表查询下看有没有这个id
					if(M('user:customer')->judgeShare($cidshare)) $where .= " or `c_id` in ($cidshare)";
				}else{
					// 默认列表显示全部的共享客户
					if(!empty($pools) && $key_type != 'need_product'){
						$where .= " or `c_id` in ($pools)";
					}
				}
			}
		}
		// p($where);
		$list=$this->db ->where($where)->page($page+1,$size)->order("$sortField $sortOrder")->getPage();
		foreach($list['data'] as $k=>$v){
			$list['data'][$k]['customer_manager'] = M('rbac:adm')->getUserByCol($v['customer_manager']);
			$list['data'][$k]['chanel']=L('company_chanel')[$v['chanel']];//客户渠道
			$list['data'][$k]['level']=L('company_level')[$v['level']];
			$list['data'][$k]['depart']=C('depart')[$v['depart']];
			// $list['data'][$k]['identification']=L('identification')[$v['identification']];//认证
			$list['data'][$k]['type']=L('company_type')[$v['type']];
			$list['data'][$k]['input_time']=$v['input_time']>1000 ? date("y-m-d H:i",$v['input_time']) : '-';
			$list['data'][$k]['update_time']=$v['update_time']>1000 ? date("y-m-d H:i",$v['update_time']) : '-';
			$list['data'][$k]['chk'] = $this->_accessChk();
			//获取联系人的姓名和手机号
			$contact = $this->db->model('customer_contact')->select('name,mobile,tel')->where('user_id='.$v['contact_id'])->getRow();
			//超管+李总+业务员+物流自己可以看电话，其余都是打星
			$see = $this->_getSee($v['customer_manager']);
			if($this->public==0){
				$list['data'][$k]['mobile'] = $this->_hidestr($contact['mobile'],$see);
			}else{
				$list['data'][$k]['mobile'] = $contact['mobile'];
			}
			//公海不做操作
			if($this->public==0){
				$list['data'][$k]['tel'] = $this->_hidestr($contact['tel'],$see);
			}else{
				$list['data'][$k]['tel'] = $contact['tel'];
			}

			$list['data'][$k]['name'] = $contact['name'];
			//对客户名称打星(战队领导才打星号)
			if($this->public==0){
				$list['data'][$k]['c_name']  = _leader($v['c_name'], $v['customer_manager'],!M('user:customer')->judgeShare($v['c_id']));
				//作为领导只有自己的和别人共享的才可以看到
				$list['data'][$k]['c_name'] = _leader($v['c_name'], $v['customer_manager'],!M('user:customer')->judgeLogin($v['c_id']));
			}
			//获取最新一次跟踪消息
			$message = $this->db->model('customer_follow')->select('remark')->where('c_id='.$v['c_id'])->order('input_time desc')->getOne();
			$list['data'][$k]['remark'] = $message;
			$list['data'][$k]['bli'] = $this->db->model('customer_billing')->select('id')->where("`c_id`={$v['c_id']}")->getOne();
			$list['data'][$k]['invoice'] =  $v['invoice']==2 ? '是' : '否';
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
				'month_consum'=>$v['month_consum'],
				'need_product'=>$v['need_product'],
				'main_product'=>$v['main_product'],
				'msg'=>$v['msg'],
				'update_time'=>CORE_TIME,
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
		$c_id=sget('id','i');
		$cType=sget('ctype','i'); //用户类型
		$this->assign('regionList', arrayKeyValues(M('system:region')->get_regions(1),'id','name'));//第一级省市
		$this->assign('sex',L('sex'));// 性别
		// $this->assign('status',L('contact_status'));// 联系人用户状态
		$this->assign('ctype',$cType);//人员类型
		if($c_id<1){
			if($cType==1){
				$this->assign('page_title','新增个人联系人');
				//如果带过来的信息有u_add则自动带信息过来
				if($u_add = sget('u_add','i',0)){
					$this->assign('info',$this->db->model('customer')->select("`c_name`,`c_id`")->where("c_id = $u_add")->getRow());
				}
				$this->display('contact.add.html');
			}elseif($cType==3){
				$this->assign('is_pur',sget('supplier'));//添加客户的入口
				$this->assign('type',L('company_type'));//工厂类型
				$this->assign('level',L('company_level'));//客户类别
				$chanel = L('company_chanel');
				unset($chanel[1]);
				unset($chanel[2]);
				unset($chanel[6]);
				$this->assign('chanel',$chanel);//客户渠道
				$this->assign('credit_level',L('credit_level'));//信用等级
				$this->assign('page_title','新增企业用户');
				$this->display('company.add.html');
			}
			exit;
		}
		$info=$this->db->getPk($c_id); //查询公司信息
		if(empty($info)){
			$this->error('错误的公司信息');
		}
		if($info['origin']){
			$areaArr = explode('|', $info['origin']);
			$info['company_province'] = $areaArr[1];
			$info['company_city']=$areaArr[0];
		}
		/**20170317添加团队领导隐藏客户姓名*S*/
		if($info['customer_manager'] > 0){
			$info['c_name']  = _leader($info['c_name'], $info['customer_manager'],!M('user:customer')->judgeShare($info['c_id']));
		}
		/**20170317添加团队领导隐藏客户姓名*E*/
		$info['file_url1'] = FILE_URL.'/upload/'.$info['file_url'];
		$info['business_licence_pic1'] = FILE_URL.'/upload/'.$info['business_licence_pic'];
		$info['organization_pic1'] = FILE_URL.'/upload/'.$info['organization_pic'];
		$info['tax_registration_pic1'] = FILE_URL.'/upload/'.$info['tax_registration_pic'];
		$info['legal_idcard_pic1'] = FILE_URL.'/upload/'.$info['legal_idcard_pic'];
		//联系人详情
		$this->assign('c_id',$c_id);//分配公司id信息
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
		/**封堵李总不能让领导看见姓名的要求*S*/
		if(_leader() && $info['customer_manager'] != $_SESSION['adminid']) $this->error('团队领导不能修改下面员工的客户信息');
		/**封堵李总不能让领导看见姓名的要求*E*/
		if(empty($info)) $this->error('错误的公司信息');
		// 根据公司查询联系人信息
		$info_ext = $this->db->model('customer_contact')->getPk($info['contact_id']);
		if(!$info_ext){
			$cid_ext=$this->db->model('customer_contact')->where("{$info['c_id']}=c_id")->order("input_time asc")->getOne();
			$this->db->model('customer')->where("c_id={$info['c_id']}")->update(array('contact_id'=>$cid_ext));
		}
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
		//获取可以修改贸易商类型的人员权限(根据人员id <赵飞、饶伟平、王凯晨、刘京、季雯琼、杨杰、沈辉、王春华、孙朝晖、张玉超、范小勇、李红颖、许在文>)
		$see = in_array($_SESSION['adminid'],array(1,10,11,730,734,735,737,772,774,775,847,912,955,968));
		$this->assign('see',$see);
		//授信限制
		$users = $this->db->model('adm_role_user')->select('user_id')->where("`role_id` in (2,57,24)")->getCol();
		$see1 = in_array($_SESSION['adminid'],array_merge($users,array(1,726)));
		$this->assign('see1',$see1);
		// p(arrayKeyValues(M('system:region')->get_regions(1),'id','name'));
		$this->assign('regionList', arrayKeyValues(M('system:region')->get_regions(1),'id','name'));//第一级省市
		$this->assign('type',L('company_type'));//工厂类型
		$this->assign('level',L('company_level'));//客户类别
		$this->assign('chanel',L('company_chanel'));//客户渠道
		$this->assign('credit_level',L('credit_level'));//信用等级
		$this->assign('sex',L('sex'));// 性别
		$this->assign('status',L('contact_status'));// 联系人用户状态
		$creditlimit=M('system:setting')->get('creditlimit')['creditlimit'];
		$this->assign('creditlimit',$creditlimit);
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
		$data['c_name'] = trim($data['c_name']);
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
				'c_name'=>trim($data['c_name']),
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
		    if($result['msg']=='手机号已存在'||$result['msg']=='手机号已存在22'){
		        //显示已经存在的手机号
		        $mobile_exit=M('public:common')->model('customer_contact cc')->select('cc.name, t.c_name,cc.tel,cc.mobile,cc.customer_manager')->leftjoin('customer t', 'cc.c_id=t.c_id')->where('cc.mobile='.$data['info_mobile'])->getRow();
		        $mobile_exit['customer_manager_name']=M('rbac:adm')->getUserByCol($mobile_exit['customer_manager']);
		        $mobile_exit['err']='1';$mobile_exit['msg']='手机号已存在';$mobile_exit['have']='1';
		        $this->json_output($mobile_exit);
		    }
			// showtrace();
			$this->error($result['msg']);
		}
		//新增客户流转记录日志----S
		if(isset($result['c_id']) && $result['c_id']>0){
			$remarks = "客户注册:".date('Y-m-d H:i:s',time());
			M('user:customerLog')->addLog($result['c_id'],'register','不存在','注册',1,$remarks);
		}
		//新增客户流转记录日志----E
		$this->success('操作成功');
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
					//新增客户流转记录日志----S
					$remarks = "对客户操作：拉入黑名单";// 审核用户
					M('user:customerLog')->addLog($v,'check','私海客户','申请拉入黑名单',1,$remarks);
					//新增客户流转记录日志----E
				}
			}
		}
		$result=$this->db->model('customer')->where("c_id in ($ids)")->update(array('status'=>10));
		if($result){
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}
	//标记客户为黄名单
	public function yellow(){
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
					$this->db->model('customer_contact')->where("`c_id`=$v")->update(array('status'=>8));
					//新增客户流转记录日志----S
					$remarks = "对客户操作：拉入黄名单";// 审核用户
					M('user:customerLog')->addLog($v,'check','私海客户','拉入黄名单',1,$remarks);
					//新增客户流转记录日志----E
				}
			}
		}
		$result=$this->db->model('customer')->where("c_id in ($ids)")->update(array('status'=>8));
		if($result){
			$this->success('标记黄名单成功');
		}else{
			$this->error('标记黄名单数据处理失败');
		}
	}

	public function setsea(){
		$this->is_ajax=true; //指定为Ajax输出
		$ids=sget('ids','s');
		if(empty($ids)){
			$this->error('操作有误');
		}
		//新增客户流转记录日志----S
		$remarks = "对客户操作：还原为公海客户";// 审核用户
		M('user:customerLog')->addLog($ids,'check','私海客户','还原为公海客户',1,$remarks);
		//新增客户流转记录日志----E
		$result=$this->db->model('customer')->where("c_id in ($ids)")->update(array('customer_manager'=>0,'depart'=>0,'status'=>1,));
		if($result){
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}
	//捡回客户
	public function retrieve(){
		$this->is_ajax=true;
		$c_id = sget('cid','i',0); //通过审核后的客户cid
		if($c_id<1) $this->error('错误的分配信息');
		$_data=array(
			'update_time'=>CORE_TIME,
			'update_admin'=>$_SESSION['name'],
		);
		//增加每人最多每天领取10个人的限制
		$limit = M('system:setting')->get('limit');
		$_key='max_allot_'.$_SESSION['adminid'];
		$cache=cache::startMemcache();

		$max_allot=intval($cache->get($_key));
		if($_key != 'max_allot_894'){
			if($max_allot>intval($limit['limit']['allot']-1)) $this->error('每日领取公海客户最多为'.$limit['limit']['allot'].'人，请明天在来领取');
		}

		//强开开关
		if($limit['limit']['recycle'] ==1){
			//新增限制，原始交易员不能认领三天以内的之前是自己的公海客户
			$my_key = $c_id.'_'.$_SESSION['adminid'];
			$is_mine=intval($cache->get($my_key));
			if($is_mine == 1) $this->error("根据公司规定，客户强开后，原本的交易员三天内不得认领自己的客户，谢谢");
			//客户保有量超限问题
			$sum = intval($this->db->select("count(*) as sum")->where("`customer_manager` = {$_SESSION['adminid']}")->getOne());
			// p($sum);
			$is_sales = intval(M('rbac:adm')->judgeSaleById($_SESSION['adminid']));
			$my_rule = M('rbac:adm')->getRuleById($_SESSION['adminid']);
			$rule_num = empty($my_rule) ? 0 : intval($my_rule['private_customer_nums']);
			if($is_sales == 0) $this->error('被分配的人不是销售员，故不能分配');
			if($sum > $rule_num && $rule_num != 0) $this->error("根据公司规则，您的客户上限为".$my_rule['private_customer_nums']."人,目前已经超过，故不能分配，请找领导吧");
		}
		//新增客户流转记录日志----S
		//查询原始交易员
		$old_manager = M('user:customer')->getColByName($c_id,'customer_manager');
		if($old_manager == '-' || $old_manager==0){
			$old_manager_name = '没有原始交易员';
		}else{
			$old_manager_name = M('rbac:adm')->getUserByCol($old_manager);
		}
		//新交易员信息
		$customer_name = M('rbac:adm')->getUserByCol($_SESSION['adminid']);
		$remarks = "客户分配交易员:".$customer_name;
		M('user:customerLog')->addLog($c_id,'allocation',$old_manager_name,$customer_name,1,$remarks);
		//新增客户流转记录日志----E
		// 查询下分配的管理员所属的部门
		$depart = M('rbac:adm')->getUserByCol($_SESSION['adminid'],'depart');
		$result = $this->db->where(" c_id = '$c_id'")->update($_data+array('customer_manager'=>$_SESSION['adminid'],'depart'=>$depart,`last_follow`=>CORE_TIME,`last_sale`=>CORE_TIME,`last_no_sale`=>CORE_TIME,));
		//hui捡回客户时，也修改开票资料的交易员
		$this->db->model('customer_billing')->where(" c_id = '$c_id'")->update(array('customer_manager'=>$_SESSION['adminid']));
		//根据数据更新客户对应的默认联系人对应的交易员
		$main_user = $this->db->model('customer')->select('contact_id')->where(" c_id = '$c_id'")->getOne();
		$this->db->model('customer_contact')->where("`user_id` = $main_user")->update(array('customer_manager'=>$_SESSION['adminid']));
		$this->db->model('customer_contact')->where("`user_id` != $main_user and  `c_id` = '$c_id' and `is_default` = 1")->update(array('customer_manager'=>$_SESSION['adminid']));

		if(!$result) $this->error('操作失败');
		// 每日剩余结束时间
		$mem_time =strtotime(date('Ymd')) + 86400-time();
		if(empty($max_allot)){
			$cache->set($_key,1,$mem_time); //加入缓存
		}else{
			$now_time = $max_allot+1;
			$cache->set($_key,$now_time,$mem_time); //加入缓存
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
			'update_time'=>CORE_TIME,
			'update_admin'=>$_SESSION['name'],
		);
		//查询原始的交易员
		$old_manager = M('user:customer')->getColByName($c_id,'customer_manager');
		//增加每人最多每天领取10个人的限制
		$limit = M('system:setting')->get('limit');
		$_key='max_allot_'.$data['id'];  $cache=cache::startMemcache();
		$max_allot=intval($cache->get($_key));
		if($_key != 'max_allot_894' && $old_manager == 0){
			if($max_allot>intval($limit['limit']['allot']-1)) $this->error('每日领取公海客户最多为'.$limit['limit']['allot'].'人，请明天在来领取');
		}
		//强开开关
		if($limit['limit']['recycle'] ==1){
			//新增限制，原始交易员不能认领三天以内的之前是自己的公海客户
			$my_key = $c_id.'_'.$data['id'];
			$is_mine=intval($cache->get($my_key));
			if($is_mine == 1) $this->error("根据公司规定，客户强开后，原本的交易员三天内不得认领自己的客户，谢谢");
			//客户保有量超限问题
			$sum = intval($this->db->select("count(*) as sum")->where("`customer_manager` = {$data['id']}")->getOne());
			// p($sum);
		   $is_sales = intval(M('rbac:adm')->judgeSaleById($_SESSION['adminid']));
		   $my_rule = M('rbac:adm')->getRuleById($_SESSION['adminid']);
		   $rule_num = empty($my_rule) ? 0 : intval($my_rule['private_customer_nums']);
		   if($is_sales == 0) $this->error('被分配的人不是销售员，故不能分配');
		   if($sum > $rule_num && $rule_num != 0) $this->error("根据公司规则，您的客户上限为".$my_rule['private_customer_nums']."人,目前已经超过，故不能分配，请找领导吧");
		}
		//新增客户流转记录日志----S
		//根据上面查询的原始交易员判断新的信息
		if($old_manager == '-' || $old_manager==0){
			$old_manager_name = '没有原始交易员';
		}else{
			$old_manager_name = M('rbac:adm')->getUserByCol($old_manager);
		}
		//新交易员信息
		$customer_name = M('rbac:adm')->getUserByCol($data['id']);
		$remarks = "客户分配交易员:".$customer_name;
		M('user:customerLog')->addLog($c_id,'allocation',$old_manager_name,$customer_name,1,$remarks);
		//新增客户流转记录日志----E
		// 查询下分配的管理员所属的部门
		$depart = M('rbac:adm')->getUserByCol($data['id'],'depart');
		$result = $this->db->where(" c_id = '$c_id'")->update(array('customer_manager'=>$data['id'],'depart'=>$depart,'last_follow'=>CORE_TIME,'last_sale'=>CORE_TIME,'last_no_sale'=>CORE_TIME,)+$_data);
		//hui分配公海客户时，也修改开票资料表的交易员
		$this->db->model('customer_billing')->where(" c_id = '$c_id'")->update(array('customer_manager'=>$data['id']));
		//根据数据更新客户对应的默认联系人对应的交易员
		$main_user = $this->db->model('customer')->select('contact_id')->where(" c_id = '$c_id'")->getOne();
		$this->db->model('customer_contact')->where("`user_id` = $main_user")->update(array('customer_manager'=>$data['id']));
		$this->db->model('customer_contact')->where("`user_id` != $main_user and  `c_id` = '$c_id' and `is_default` = 1")->update(array('customer_manager'=>$data['id']));
		if(!$result) $this->error('操作失败');
		// 每日剩余结束时间
		$mem_time =strtotime(date('Ymd')) + 86400-time();
		if(empty($max_allot)){
			$cache->set($_key,1,$mem_time); //加入缓存
		}else{
			$now_time = $max_allot+1;
			$cache->set($_key,$now_time,$mem_time); //加入缓存
		}
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
		//新增客户流转记录日志----S
		$old_status = M('user:customer')->getColByName($id,'status');//查询原始状态
		$remarks = "客户审核操作:".L('status')[$status];// 审核用户
		M('user:customerLog')->addLog($id,'check',L('status')[$old_status],L('status')[$status],1,$remarks);
		//新增客户流转记录日志----E
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
		//处理工厂客户不能共享问题(1为工厂，工厂客户不能共享)
		$ty = intval($this->db->model('customer')->select("`type`")->where("`c_id` = {$data['c_id']}")->getOne());
		if($ty == 1) $this->error("根据公司规定，工厂客户不能共享，找领导去吧");
		//新增客户流转记录日志----S
		$old_cusid = M('user:customer')->getColByName($data['c_id'],'customer_manager');
		$old_cus = M('rbac:adm')->getUserByCol($old_cusid);//查询共享给人姓名
		$new_cus = M('rbac:adm')->getUserByCol($data['id']);//查询共享给人姓名
		$remarks = "客户共享操作:".$old_cus."把客户共享给".$new_cus;// 审核用户
		M('user:customerLog')->addLog($data['c_id'],'share',$old_cus,$new_cus,1,$remarks);
		//新增客户流转记录日志----E
		$result = $this->db->model('customer_pool')->add(array('customer_manager'=>$data['id'],'c_id'=>$data['c_id'],'input_time'=>CORE_TIME,'input_admin'=>$_SESSION['name'],'share_manager'=>$_SESSION['adminid'],'share_managername'=>$_SESSION['username']));
		if(!$result) $this->error('操作失败');
		$this->success('操作成功');
	}
	//批量分配交易员
	public function exchange(){
		$ids =sget('ids','s','');
		if(empty($ids)) $this->error('批量替换交易员错误');
		$this->assign('ids',$ids);
		$this->display('customer.exchange.html');
	}
	//批量替换交易员提交
	public function exchangeSubmit(){
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //获取UI传递的参数
		if(empty($data)) $this->error('用户信息错误');
		$cids = explode(',',$data['c_ids']);
		if(is_array($cids)){
			foreach ($cids as $k => $v) {
				//新增客户流转记录日志----S
				//查询原始交易员
				$old_manager = M('user:customer')->getColByName($v,'customer_manager');
				if($old_manager == '-' || $old_manager==0){
					$old_manager_name = '没有原始交易员';
				}else{
					$old_manager_name = M('rbac:adm')->getUserByCol($old_manager);
				}
				//新交易员信息
				$customer_name = M('rbac:adm')->getUserByCol($data['id']);
				$remarks = "客户批量更换交易员:原来交易员是".$old_manager_name." 新交易员：".$customer_name;
				M('user:customerLog')->addLog($v,'allocation',$old_manager_name,$customer_name,1,$remarks);
				//新增客户流转记录日志----E
				$result = $this->db->model('customer')->where("c_id = $v")->update(array('customer_manager'=>$data['id'],`last_follow`=>CORE_TIME,`last_sale`=>CORE_TIME,`last_no_sale`=>CORE_TIME,));
				//根据数据更新客户对应的默认联系人对应的交易员
				$main_user = $this->db->model('customer')->select('contact_id')->where("c_id = $v")->getOne();
				$this->db->model('customer_contact')->where('`c_id` = $v and `user_id` = $main_user')->update(array('customer_manager'=>$data['id']));
				$this->db->model('customer_contact')->where("`user_id` != $main_user and  `c_id` = $v and `is_default` = 1")->update(array('customer_manager'=>$data['id']));
			}
		}
		if(!$result) $this->error('操作失败');
		$this->success('操作成功');
	}
	//公司名去重
	public function curUnique(){
		$data = trim($_POST['data']);
		$data = str_replace(' ', '', $data); //去中间空格
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
	//拉黑合作客户
	public function remove_cooperation(){
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
					//新增客户流转记录日志----S
					$remarks = "对客户操作：从合作客户中拉入黑名单，状态待审核，说明：is_sale 更新为 2";// 审核用户
					M('user:customerLog')->addLog($v,'check','合作客户','拉入黑名单',1,$remarks);
					//新增客户流转记录日志----E
				}
			}
		}
		$result=$this->db->model('customer')->where("c_id in ($ids)")->update(array('is_sale'=>2)); //说明黑名单待审
		if($result){
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}
	//拉黑供应商
	public function remove_supplier(){
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
					//新增客户流转记录日志----S
					$remarks = "对客户操作：从供应商中拉入黑名单，状态待审核,说明：is_pur更新为 2";// 审核用户
					M('user:customerLog')->addLog($v,'check','供应商客户','拉入黑名单',1,$remarks);
					//新增客户流转记录日志----E
				}
			}
		}
		$result=$this->db->model('customer')->where("c_id in ($ids)")->update(array('is_pur'=>2)); //说明黑名单待审
		if($result){
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}
	/**
	 * [hidestr description]隐藏电话号码信息
	 * @Author   cuiyinming               QQ:1203116460
	 * @DateTime 2017-03-15T17:07:02+0800
	 * @param    string                   $str          [description]
	 * @param    integer                  $see          [description]
	 * @param    integer                  $len          [description]
	 * @return   [type]                                 [description]
	 */
	private function _hidestr($str='',$see=0,$len=4){
		$hide ='';
		if($see==1) return $str;
		for ($i=0; $i < $len; $i++) {
			$hide .= '*';
		}
		$str = empty($str) ? '' : substr($str,0,strlen($str)-$len).$hide;
		return $str;
	}
	/**
	 * 获取可查看信息的权限
	 * @Author   cuiyinming               QQ:1203116460
	 * @DateTime 2017-03-15T17:08:52+0800
	 * @return   [type] //超管+李总+业务员+物流自己可以看电话，其余都是打星
	 */
	private function _getSee($customer=0){
		$uid = $_SESSION['adminid'];
		//超管与李总
		if($uid == 1 || $uid == 726) return $see = 1;
		//判断交易员是否是自己的
		if($customer == $uid) return $see = 1;
		//判断当前用户是否是物流人员
		if($this->db->model('adm_role_user')->where("role_id in (24,25) and user_id = $uid")->getAll()) return $see = 1;
	}

}