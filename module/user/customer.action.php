<?php
/**
 * 客户信息管理
 */
class customerAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->db=M('public:common')->model('customer');
		$this->doact = sget('do','s');
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
		$this->assign('status',L('contact_status'));// 联系人用户状态
		$this->assign('type',L('company_type'));//工厂类型
		$this->assign('level',L('company_level'));//客户级别
		$this->assign('page_title','会员列表');
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
	private function _grid(){
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$sortField = sget("sortField",'s','c_id'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
		
		$where=" 1 ";
		$sTime = sget("sTime",'s','input_time'); //搜索时间类型
		$where.=getTimeFilter($sTime); //时间筛选
		$status = sget("status",'s',''); //状态
		if($status!='') $where.=" and status='$status' ";	
		// 关键词
		$key_type=sget('key_type','s','c_id');
		$keyword=sget('keyword','s');
		if(!empty($keyword)){
			if($key_type=='legal_person' || $key_type=='c_name'){
				$where.=" and $key_type like '%$keyword%' ";
			}else{
				$where.=" and $key_type='$keyword' ";
			}
		}
		
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
			$list['data'][$k]['type']=L('company_type')[$v['type']];
			$list['data'][$k]['input_time']=$v['input_time']>1000 ? date("y-m-d H:i",$v['input_time']) : '-';
			$list['data'][$k]['update_time']=$v['update_time']>1000 ? date("y-m-d H:i",$v['update_time']) : '-';
		}
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
			);
			$this->db->wherePk($v['c_id'])->update($update);
		}
		$this->success('操作成功');
	}
	/**
	 * 显示用户资质上传页
	 * @access public 
	 * @return html
	 */
	function showHolder(){
		//$this->is_ajax=true;
		$user_id=sget('user_id','i');
		$ctype = sget('ctype','s');
		$this->assign('ctype',$ctype);
		$this->assign('user_id',$user_id);
		$this->display('holder.html');	
	}
	
	/**
	 * 保存用户资质
	 * @access public 
	 * @return html
	 */
	function submitAptitude(){
		$this->is_ajax=true;
		$data = sdata(); //获取UI传递的参数
		$aptitudeId = sget('aptitude_id','a','');
		$user_id=sget('user_id','i');
		$atype = sget('atype','i');
		if($aptitudeId){
			foreach($aptitudeId as $k=>$v){
				if($v){
					$user_att = array(
						'user_id' =>intval($user_id) ,
						'atype'=>$atype,
						'ctype'=>$k,
						'att_id'=>$v,
						'status'=>1,
						'access_time'=>CORE_TIME,
						'admin_name'=>$_SESSION['name']
					);
					$result = M('public:user')->addUserAtt($this->user_id,$user_att);
				}
			}

			if($result){
				$data=array('err'=>0,'msg'=>'提交成功');
			}else{
				$data=array('err'=>1,'msg'=>'系统出错！');
			}
		}else{
			$data=array('err'=>1,'msg'=>'系统出错！');
		}
		
		$this->json_output($data);
	}

	/**
	 * 审核用户认证信息
	 * @return [type]
	 */
	function verifyUserAtt(){
		$user_id=sget('user_id','i');
		$atype = sget('atype','i');
		$status = sget('status','i',0);
		
		if($user_id && $atype){
			M('user:userAtt')->where('user_id='.$user_id.' AND atype='.$atype)->update(array('status'=>$status,'access_time'=>CORE_TIME,'admin_name'=>$_SESSION['name']));
			$data=array('err'=>0,'msg'=>'');
		}else{
			$data=array('err'=>1,'msg'=>'系统出错！');
		}
		$this->json_output($data);
	}
	
	/**
	 * 会员查询
	 * @access public 
	 * @return html
	 */
	public function search(){
		$action=sget('action','s');
		if($action=='grid'){
			$page = sget("pageIndex",'i',0); //页码
			$size = sget("pageSize",'i',20); //每页数
			$sortField = sget("sortField",'s','u.user_id'); //排序字段
			$sortOrder = sget("sortOrder",'s','desc'); //排序

			$where=" 1 ";
			
			$status = sget("status",'i',0); //状态
			if($status>0) $where.=" and status='$status' ";	

			$utype = sget("utype",'i',0); //状态
			if($utype>0) $where.=" and utype='$utype' ";	

			//关键词
			$key_type=sget('key_type','s','user_id');
			$keyword=sget('keyword','s');
			if(!empty($keyword)){
				$where.=" and $key_type='$keyword' ";
			}
			
			$mobile = sget("mobile",'s'); //手机号
			if(!empty($mobile)){
				$where.=" and u.mobile='$mobile' ";
			}
			
			$real_name = sget("real_name",'s'); //手机号
			if(!empty($real_name)){
				$where.=" and i.real_name='$real_name' ";
			}
			
			$email = sget("email",'s'); //手机号
			if(!empty($email)){
				$where.=" and u.email='$email' ";
			}
			
			$user_id = sget("user_id",'s'); //手机号
			if(!empty($user_id)){
				$where.=" and u.user_id='$user_id' ";
			}
			$invite_code = sget("invite_code",'s'); //邀请码
			if(!empty($invite_code)){
				$invite_code = operationAlphaID($invite_code);
				$where.=" and u.user_id='$invite_code' ";
			}
			
			$list=$this->db->select('u.*,i.real_name,utype,reg_time')
						->from('user u')->join('user_info i','u.user_id=i.user_id')
						->where($where)
						->page($page+1,$size)
						->order("$sortField $sortOrder")
						->getPage();
			foreach($list['data'] as $k=>$v){
				$list['data'][$k]['status']=$this->status[$v['status']];
				//企业用户取出公司名称
				if($list['data'][$k]['utype']==2){
					$list_c = $this->db->select('name')
						->from('user_info_extagent')
						->where("user_id ='".$list['data'][$k]['user_id']."'")
						->getRow();
					$list['data'][$k]['name'] = $list_c['name'];
				}else if($list['data'][$k]['utype']==3){
					$list_c = $this->db->select('company_name')
						->from('user_info_extcompany')
						->where("user_id ='".$list['data'][$k]['user_id']."'")
						->getRow();
					$list['data'][$k]['name'] = $list_c['company_name'];
				}else{
					$list['data'][$k]['name'] = $v['real_name'];
				}
				$list['data'][$k]['utype']=$this->utype[$v['utype']];
				$list['data'][$k]['last_login']=$v['last_login']>1000 ? date("y-m-d H:i",$v['last_login']) : '-';
				$list['data'][$k]['reg_time']=$v['reg_time']>1000 ? date("y-m-d H:i",$v['reg_time']) : '-';
			}
			$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>'');
			$this->assign('doact','search');
			$this->json_output($result);
		}

		$this->assign('page_title','用户查找');
		$this->display('user.search.html');
	}
	
	 /**
     * 会员详细信息
     * @access public
     */
	 private function getAgentData(){
	 	return $list=$this->db->select('u.*,i.real_name,utype,reg_time')
						->from('user u')
						->join('user_info i','u.user_id=i.user_id')
						->join('user_info_extagent a','u.user_id=a.user_id')
						->where('i.utype = 2')
						->getAll();
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
		$user=$this->db->model('user')->getPk($user_id);
		if(empty($user)){
			$this->error('错误的用户信息');	
		}
		$user['info']=$this->db->model('user_info')->getPk($user_id);
		if($user['info']['id_card']){
			$user['info']['id_card']=M('system:sysIdCard')->decrypt($user['info']['id_card']);	
		}
		//用户ip地址
		if(empty($user['info']['ip_address'])){
			$ip_address=$city=getCityByMobile($user['mobile']).'@'.getCityByIP($user['info']['reg_ip']);
			if(strlen($ip_address)>10){
				$this->db->model('user_info')->where('user_id='.$user_id)->update(array('ip_address'=>substr($ip_address,0,60)));	
				$user['info']['ip_address']=$ip_address;
			}
		}
		
		//用户渠道名
		$user['info']['chanel_name']='';
		if($user['info']['chanel_id']>0){
			$user['info']['chanel_name']=M('system:chanel')->getChanelName($user['info']['chanel_id']);
		}
		
		$user['infoExt']=$this->db->model('user_info_ext')->wherePk($user_id)->getRow();
		//地区列表			
		$regList = M('system:region')->get_allRegions();
		
		if($user['infoExt']['origin']){
			$areaArr = explode('|', $user['infoExt']['origin']);
			$origin = "";
			foreach ($areaArr as $v1){
				foreach ($regList as $v){
				
					if($v['id']==$v1){
						$origin .= $v['name']."*";
					}
				}
			}
			$user['infoExt']['origin'] = $origin;
		}

		if($user['infoExt']['domicile_place']){
			$areaArr = explode('|', $user['infoExt']['domicile_place']);
			$domicile_place = "";
			foreach ($areaArr as $v2){
				foreach ($regList as $v){
				
					if($v['id']==$v2){
						$domicile_place .= $v['name']."*";
					}
				}
			}
			$user['infoExt']['domicile_place'] = $domicile_place;
		}

		//地址
		if($user['infoExt']['area']){
			$areaArr = explode('|', $user['infoExt']['area']);
			$area = "";
			foreach ($areaArr as $v3){
				//echo $v."<br />";
				foreach ($regList as $v){
				
					if($v['id']==$v3){
						$area .= $v['name']."*";
					}
				}
			}
			$user['infoExt']['area'] = $area;
		}

		if($user['info']['utype']==2){//经纪人
			$eco_type=L('eco_type'); //
			$this->assign('eco_type',$eco_type);
			$user['infoExt']['agent']=$this->db->model('user_info_extagent')->where("user_id='{$user_id}'")->getRow();			
		}elseif($user['info']['utype']==3){//筹资机构
			$user['infoExt'] = array_merge((array)$user['infoExt'], M('user:userInfo')->getUserInfoExt($user_id,'company'));
		}

		$param_fee=M('public:common')->model('setting')->select('value')->where("code='param_fee'")->getOne();
		if(!empty($param_fee)){
			$param_fee=json_decode($param_fee,true);
		}else{
			$param_fee=array();
		}
		$credit_level = array();
		if(count($param_fee)){
			$credit_data = $param_fee['credit_level'];

			for($i=0;$i<count($credit_data);$i++){
				if(!empty($credit_data[$i.'_name'])){
					$credit_level[$i] = $credit_data[$i.'_name'];
				}
			}
		}
		$this->assign('credit_level',$credit_level);
		
		//用户认证项目
		$this->assign('user_verify',L('user_verify'));
		//用户认证附件信息
		$userAtt = M('user:userAtt')->getUserAtt($user_id);
		$att_arr = array();
		//echo "<pre>";print_r($userAtt);
		foreach($userAtt as $key=>$val){
			$att_id = unserialize($val['att_id']);
			$att_arr[$val['atype']][]=array('att_id'=>$val['att_id'],'url'=>$val['file_url'],'status'=>$val['status'],'ctype'=>$val['ctype']);
		}
		$this->assign('userAtt',$att_arr);
		//证件名称
		$this->assign('certificate_ctype',L('certificate_ctype'));
		//财务信息
		$user['account']=$this->db->model('uaccount')->getPk($user_id);
		$this->assign('user',$user);
		//登录渠道:1web,2app,3wap,4微信
		$this->assign('user_chanel',L('user_chanel'));
		//最高学历
		$this->assign('education',L('education'));
		//职业状况
		$this->assign('occupation',L('occupation'));
		//婚姻状况
		$this->assign('marital',L('marital'));
		//有无子女
		$this->assign('children',L('children'));
		//房贷情况
		$this->assign('mortgage_situation',L('mortgage_situation'));
		$this->assign('truck_situation',L('truck_situation'));//车贷情况
		$this->assign('company_scale',L('company_scale'));
		$this->assign('work_years',L('work_years'));
		//公司类别
		$this->assign('company_category',L('company_category'));		
		//公司行业
		$this->assign('company_industry',L('company_industry'));
		//月收入
		$this->assign('mloan_amount',L('mloan_amount'));
		$this->assign('user_tag',L('user_tag'));		
		
		//居住时长
		$this->assign('live_long',L('live_long'));
		$this->assign('page_title','会员详细信息');
		$this->display('user.info.html');
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
			$info['company_province'] = $areaArr[1];
			$info['company_city']=$areaArr[0];
		}
		if(empty($info)) $this->error('错误的公司信息');
		// 根据公司查询联系人信息
		$info_ext = $this->db->model('customer_contact')->getPk($info['contact_id']);
		$this->assign('info_ext',$info_ext); //分陪l联系人信息
		$this->assign('ctype',3);
		$this->assign('regionList', arrayKeyValues(M('system:region')->get_regions(1),'id','name'));//第一级省市
		$this->assign('type',L('company_type'));//工厂类型
		$this->assign('level',L('company_level'));//客户类别
		$this->assign('chanel',L('company_chanel'));//客户渠道
		$this->assign('credit_level',L('credit_level'));//信用等级
		$this->assign('sex',L('sex'));// 性别
		$this->assign('status',L('contact_status'));// 联系人用户状态
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
     * 会员登录密码修改
     * @access public
     */
	public function modifyPasswd(){
		$user_id=sget('id','i');
		
		if($user_id<1){
			$this->error('错误的用户信息');
		}
		
		$user=$this->db->model('user')->getPk($user_id);
		if(empty($user)){
			$this->error('错误的用户信息');	
		}
		$user['info']=$this->db->model('user_info')->getPk($user_id);
		$this->assign('user',$user);
		$this->assign('page_title','会员登录密码修改');
		$this->display('user.modPasswd.html');
    }

	 /**
     * 会员交易密码修改
     * @access public
     */
	public function modifyPayPasswd(){
		$user_id=sget('id','i');
		
		if($user_id<1){
			$this->error('错误的用户信息');
		}
		
		$user=$this->db->model('user')->getPk($user_id);
		if(empty($user)){
			$this->error('错误的用户信息');	
		}
		$user['info']=$this->db->model('user_info')->getPk($user_id);
		$this->assign('user',$user);
		$this->assign('page_title','会员交易密码修改');
		$this->display('user.modPayPasswd.html');
    }
	
	 /**
     * 会员审核
     * @access public
     */
	public function applyUser(){
		$this->idcard_type=L('idcard_type'); //证件类型
		$this->chk_risk=L('chk_risk'); //风险评估

		$user_id=sget('id','i');
		
		if($user_id<1){
			$this->error('错误的用户信息');
		}
		
		$user=$this->db->model('user')->getPk($user_id);
		if(empty($user)){
			$this->error('错误的用户信息');	
		}
		$user['info']=$this->db->model('user_info')->getPk($user_id);
		if($user['info']['id_card']){
			$user['info']['id_card']=M('system:sysIdCard')->decrypt($user['info']['id_card']);	
		}

		//财务信息
		$user['account']=$this->db->model('uaccount')->getPk($user_id);
		$this->assign('user',$user);
		//登录渠道:1web,2app,3wap,4微信
		$this->assign('user_chanel',L('user_chanel'));

		$this->assign('page_title','会员登录密码修改');
		$this->display('user.apply.html');
    }
	
	/**
     * 修改用户推荐人
     * @access public
     */
	public function editRfUser(){
		$this->idcard_type=L('idcard_type'); //证件类型
		$this->chk_risk=L('chk_risk'); //风险评估

		$user_id=sget('id','i');
		
		if($user_id<1){
			$this->error('错误的用户信息');
		}
		
		$user=$this->db->model('user')->getPk($user_id);
		if(empty($user)){
			$this->error('错误的用户信息');	
		}
		$user['info']=$this->db->model('user_info')->getPk($user_id);
		if($user['info']['id_card']){
			$user['info']['id_card']=M('system:sysIdCard')->decrypt($user['info']['id_card']);	
		}

		//财务信息
		$user['account']=$this->db->model('uaccount')->getPk($user_id);
		$this->assign('user',$user);
		//登录渠道:1web,2app,3wap,4微信
		$this->assign('user_chanel',L('user_chanel'));

		$this->assign('page_title','会员登录密码修改');
		$this->display('editRfUser.html');
    }
	
	 /**
     * 设置客户经理
     * @access public
     */
	public function setUser(){
		$this->idcard_type=L('idcard_type'); //证件类型
		$this->chk_risk=L('chk_risk'); //风险评估

		$user_id=sget('ids','s');
		
		if($user_id<1){
			$this->error('错误的用户信息');
		}
		
		$this->assign('user_id',$user_id);
		$this->assign('page_title','设置客户经理');
		$this->display('user.set.html');
    }

	/**
	 * 修改用户信用等级
	 * @access public 
	 * @return html
	 */
	public function creditSubmit() {
		$this->is_ajax=true;
		$_info=sget('info','a');
			
		if(strlen($_info['credit_level'])<0){
			$this->error('请选择用户信用等级');	
		}
		
		//更改用户信用等级
		$_data=array(
			'credit_level'=>$_info['credit_level'],
		);
		$this->db->model('uaccount')->wherePk($_info['user_id'])->update($_data) || $this->error('操作失败');
	
		//修改用户信用等级，写日志
		$remarks = $_info['comments'];
		M('user:applyLog')->addLog($_info['user_id'],'credit_level',$_info['old_credit_level'],$_info['credit_level'],1,$remarks);
		$this->success();
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
	
	/**
	 * 更新用户信息
	 * @access public 
	 * @return html
	 */
	public function editSubmit() {
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //获取UI传递的参数
		$user_id=(int)$data['user_id'];
		if(empty($user_id)){
			$this->error('错误的用户信息');	
		}
		
		//用户原信息
		$info=$this->db->model('user')->getPk($user_id);
		if(empty($info)){
			$this->error('错误的用户信息');	
		}
		$info += $this->db->model('user_info')->getPk($user_id);
		
		//需要更新的信息
		$basic=$detail=$ext=array();
		if(!empty($data['password'])){
			if(strlen($data['password'])<5){
				$this->error('密码应为6-20位');	
			}
			//更新密码
			$basic['salt']=randstr(6);
			$basic['password']=M('system:sysUser')->genPassword($data['password'].$basic['salt']);
		}
		//邮箱修改了
		if(!empty($data['email']) && $data['email']!=$info['email']){
			if(!is_email($data['email'])){
				$this->error('email格式不正确');	
			}	
			if(!M('system:sysUser')->usrUnique('email',$data['email'])){
				$this->error('email格式不正确');	
				return array('err'=>1,'msg'=>'email已存在');
			}
			$basic['email']=$data['email'];
		}
		//更新用户状态
		if(!empty($data['status']) && $data['status']!=$info['status']){
			$basic['status']=(int)$data['status'];	
		}
		//更新用户登录手机号
		if(!empty($data['mobile']) && $data['mobile']!=$info['mobile']){
			$basic['mobile']=(int)$data['mobile'];	
		}
		//真实姓名
		if(!empty($data['real_name']) && $data['real_name']!=$info['real_name']){
			$detail['real_name']=$data['real_name'];	
		}
		//证件没有验证呢
		if(empty($info['chk_idcard']) && $data['chk_idcard']!=$info['chk_idcard'] && !empty($data['chk_idcard'])){
			if($data['id_type']!=$info['id_type']){
				$detail['id_type']=(int)$data['id_type'];	
			}
			if($data['id_card']!=$info['id_card']){
				$detail['id_card']=$data['id_card'];	
			}
		}
		if($data['is_security']!=''){
			$detail['is_security']=$data['is_security'];	
		}
		//推荐人
		if(!empty($data['ref_uid']) && $data['ref_uid']!=$info['ref_uid']){
			$detail['ref_uid']=$data['ref_uid'];	
		}

		//开始更新数据
		if(!empty($basic)){
			$this->db->model('user')->wherePk($user_id)->update($basic) or $this->error('操作失败:'.$this->db->getDbError());	
		}
		if(!empty($detail)){
			$this->db->model('user_info')->wherePk($user_id)->update($detail) or $this->error('操作失败:'.$this->db->getDbError());	
			
			//推荐人
			if(!empty($data['ref_uid']) && $data['ref_uid']!=$info['ref_uid']){
				//修改了推荐人
				$this->db->model('manager_user')->where('user_id='.$user_id)->update(array('ref_uid'=>$detail['ref_uid'])) or $this->error('操作失败:'.$this->db->getDbError());
				//修改原推荐人推荐人数-1
				if($info['ref_uid'] && $info['ref_count']>0){
					$this->db->model('user_info')->wherePk($info['ref_uid'])->update(array('ref_count'=>'-=1')) or $this->error('操作失败:'.$this->db->getDbError());
					//同时更新CRM用户表中推荐用户数量
					$this->db->model('manager_user')->where('user_id='.$info['ref_uid'])->update(array('ref_count'=>'-=1')) or $this->error('操作失败:'.$this->db->getDbError());
				}
				//修改新推荐人推荐人数+1
				$this->db->model('user_info')->wherePk($data['ref_uid'])->update(array('ref_count'=>'+=1')) or $this->error('操作失败:'.$this->db->getDbError());
				//同时更新CRM用户表中推荐用户数量
				$this->db->model('manager_user')->where('user_id='.$data['ref_uid'])->update(array('ref_count'=>'+=1')) or $this->error('操作失败:'.$this->db->getDbError());
			}
		}

		//只更新基本信息
		if(sget('base','s',0)) $this->success();

		//更新扩展资料
		$ext = array(
			'user_id'=>$user_id,
			'bio'=>$data['bio'],
			'identify'=>$data['identify'],
			'education'=>$data['education'],
			'entrance'=>$data['entrance'],
			'school'=>$data['school'],
			'origin'=>$data['origin_province']."|".$data['origin_city'],
			'domicile_place'=>$data['domicile_place_province']."|".$data['domicile_place_city'],
			'area'=>$data['province']."|".$data['city'],
			'address'=>$data['address'],
			'zip'=>$data['zip'],
			'length_residence'=>$data['lengthOfResidence'],  //居住时长
			'user_id' =>$user_id ,
			'marital'=>$data['marital'],
			'children'=>$data['children'],
			'immediate_name'=>$data['immediate_name'],
			'immediate_relation'=>$data['immediate_relation'],
			'immediate_mobile'=>$data['immediate_mobile'],
			'immediate_name2'=>$data['immediate_name2'],
			'immediate_relation2'=>(int)$data['immediate_relation2'],
			'immediate_mobile2'=>$data['immediate_mobile2'],
			'colleague_name'=>$data['colleague_name'],
			'colleague_relation'=>(int)$data['colleague_relation'],
			'colleague_mobile'=>$data['colleague_mobile'],
			'other_name'=>$data['other_name'],
			'other_relation'=>$data['other_relation'],
			'other_mobile'=>$data['other_mobile'],
			'occupation'=>$data['occupation'],
			'work_name'=>$data['work_name'],
			'work_identify'=>$data['workIdentify'],
			'revenue_prove'=>$data['revenueProve'],
			'position'=>$data['position'],
			'mloan_amount'=>$data['mLoanAmount'],
			'work_email'=>$data['work_email'],
			'work_city'=>$data['work_city'],
			'work_address'=>$data['work_address'],
			'work_category'=>$data['work_category'],
			'work_industry'=>$data['work_industry'],
			'work_scale'=>$data['work_scale'],
			'work_years'=>$data['work_years'],
			'work_phone'=>$data['work_phone'],
			'house_loan'=>$data['houseLoan'],//房贷情况
			'repay_loanamount'=>addslashes($data['repayLoanAmount']),//平均每月还款额
			'car_loan'=>$data['carLoan'],//车贷情况
			'car_loanamount'=>addslashes($data['carLoanAmount']),//每月车贷还款金额
		);
		$this->db->model('user_info_ext')->add($ext,TRUE) or $this->error('操作失败:'.$this->db->getDbError());

		//更新认证资料
		$db = M('user:userAtt');
		foreach($data as $k=>$v){
			if(strpos($k,'user_att_') !== FALSE && intval($v)){
				$atype = str_replace('user_att_','',$k);
				$where = 'user_id='.$user_id.' AND atype='.$atype;
				if($db->where($where)->select('count(*)')->getOne()){
					$db->where($where)->update(array('att_id'=>$v));
				}else{
					$db->add(array('user_id'=>$user_id,'atype'=>$atype,'att_id'=>$v,'status'=>1,'access_time'=>CORE_TIME,'admin_name'=>$_SESSION['name']));
				}
			}
		}

		$ext = array();
		if($info['utype']==2){
			foreach($data as $k=>$v){
				if(strpos($k,'agent_') !== FALSE){
					$ext[str_replace('agent_','',$k)] = $v;
				}
			}
			$this->db->model('user_info_extagent')->where('user_id='.$user_id)->update($ext) or $this->error('操作失败:'.$this->db->getDbError());
		}elseif($info['utype']==3){
			foreach($data as $k=>$v){
				if(strpos($k,'company_') !== FALSE){
					$ext[$k] = $v;
				}
			}
			$ext['user_id'] = $user_id;
			$this->db->model('user_info_extcompany')->add($ext,TRUE) or $this->error('操作失败:'.$this->db->getDbError());
		}

		$this->success('操作成功');
	}
	
	/**
	 * 更新用户密码
	 * @access public 
	 * @return html
	 */
	public function passWdSubmit() {
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //获取UI传递的参数
		$user_id=(int)$data['user_id'];
		if(empty($user_id)){
			$this->error('错误的用户信息');	
		}
		
		//用户原信息
		$info=$this->db->model('user')->getPk($user_id);
		if(empty($info)){
			$this->error('错误的用户信息');	
		}
		
		//需要更新的信息
		$basic=array();
		if(!empty($data['password'])){
			if(strlen($data['password'])<8){
				$this->error('密码应为8-20位');	
			}
			//更新密码
			$basic['salt']=randstr(6);
			$basic['password']=M('system:sysUser')->genPassword($data['password'].$basic['salt']);
		}
		
		//开始更新数据
		if(!empty($basic)){
			$msg = sprintf(L('sms_template.passwd_edit_success'),$data['password']);
			$this->db->model('user')->wherePk($user_id)->update($basic);	

			//管理员重置密码，写日志
			$remarks = "管理员重置用户密码";
			M('user:applyLog')->addLog($user_id,'reset_passwd','',$data['password'],1,$remarks);
			
			//发送手机短信
			M('system:sysSMS')->send($user_id,$info['mobile'],$msg,2);
		}

		$this->success('操作成功');
	}

	/**
	 * 更新用户交易密码
	 * @access public 
	 * @return html
	 */
	public function paypasswdSubmit() {
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //获取UI传递的参数
		$user_id=(int)$data['user_id'];
		if(empty($user_id)){
			$this->error('错误的用户信息');	
		}
		
		//用户原信息
		$info=$this->db->model('user')->getPk($user_id);
		if(empty($info)){
			$this->error('错误的用户信息');	
		}
		
		//需要更新的信息
		$basic=array();
		if(!empty($data['password'])){
			if(strlen($data['password'])<8){
				$this->error('密码应为8-20位');	
			}
			//更新密码
			$basic['salt']=randstr(6);
			$basic['pay_passwd']=M('system:sysUser')->genPassword($data['password'].$basic['salt']);
		}
		
		//开始更新数据
		if(!empty($basic)){
			$msg = sprintf(L('sms_template.paypasswd_edit_success'),$data['password']);
			$this->db->model('uaccount')->wherePk($user_id)->update($basic);	

			//管理员重置密码，写日志
			$remarks = "管理员重置用户交易密码";
			M('user:applyLog')->addLog($user_id,'reset_paypasswd','',$data['password'],1,$remarks);
			
			//发送手机短信
			M('system:sysSMS')->send($user_id,$info['mobile'],$msg,2);
		}

		$this->success('操作成功');
	}
	
	/**
	 * 会员下线列表
	 * @access public 
	 * @return html
	 */
	public function viewOwnUsers(){
		$user_id=sget('id','i');
		
		if($user_id<1){
			exit;
		}
		$this->user_id=$user_id;
		
		$atpye=sget('atpye','i');
		$this->atpye=$atpye;
		$action=sget('action','s');
		if($action=='grid'){
			$page = sget("pageIndex",'i',0); //页码
			$size = sget("pageSize",'i',20); //每页数
			$sortField = sget("sortField",'s','u.user_id'); //排序字段
			$sortOrder = sget("sortOrder",'s','desc'); //排序

			$where=" 1 ";
			$sTime = sget("sTime",'s','last_login'); //搜索时间类型
			$where.=getTimeFilter($sTime); //时间筛选
			$status = sget("status",'i',0); //状态
			if($status>0) $where.=" and status='$status' ";	

			$utype = sget("utype",'i',0); //状态
			if($utype>0) $where.=" and utype='$utype' ";	

			//关键词
			$key_type=sget('key_type','s','user_id');
			$keyword=sget('keyword','s');
			if(!empty($keyword)){
				$where.=" and $key_type='$keyword' ";
			}
			
			if($atpye=='1'){
				$where.=" and i.ref_uid='$user_id' ";
				$list=$this->db->select('u.*,i.real_name,utype,reg_time,i.reg_ip,i.ref_count')
						->from('user u')
						->join('user_info i','u.user_id=i.user_id')
						->where($where)
						->page($page+1,$size)
						->order("$sortField $sortOrder")
						->getPage();
			}else{
				$where.=" and i.own_uid='$user_id' ";
				$list=$this->db->select('u.*,i.real_name,utype,reg_time,i.reg_ip,i.ref_count')
						->from('user u')
						->leftjoin('user_info i','u.user_id=i.user_id')
						->leftjoin('user_info_extagent a','u.user_id=a.user_id')
						->where($where)
						->page($page+1,$size)
						->order("$sortField $sortOrder")
						->getPage();
			}
			
			foreach($list['data'] as $k=>$v){
				$list['data'][$k]['status']=$this->status[$v['status']];
				$list['data'][$k]['utype']=$this->utype[$v['utype']];
				$list['data'][$k]['last_login']=$v['last_login']>1000 ? date("y-m-d H:i",$v['last_login']) : '-';
				$list['data'][$k]['reg_time']=$v['reg_time']>1000 ? date("y-m-d H:i",$v['reg_time']) : '-';
			}

			$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>'');
			$this->json_output($result);
		}
		
		$this->assign('page_title','会员列表');
		$this->display('ownUsers.list.html');
	}

	//检查唯一性
	private function _chkUnique($name='mobile',$value=''){
		$exist=$this->db->model('user')->select('user_id')->where("$name='$value'")->getOne();
		return $exist>0 ? true : false;
	}
	/**
	 * 设置用户客户经理
	 * @access public 
	 * @return html
	 */
	public function setSubmit(){
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //获取UI传递的参数
		if(empty($data)){
			$this->error('错误的操作');
		}
		$ids = $data['user_id'];
		
		$userList = $this->db->select("u.user_id,u.mobile,u.last_login,u.visit_count,u.status,ui.real_name,ui.reg_time,ui.chanel_id,ui.sex,ui.birthday,ui.chk_idcard")->from('user u')->join('user_info ui','u.user_id=ui.user_id')->where("u.user_id in ($ids)")->getAll();

		foreach($userList as $k=>$v)	{
			$uid = $v['user_id'];
			$adminId = $data['admin_id'];

			$result = $this->db->model('manager_user')->where("user_id='{$uid}' and manager_id=".$adminId)->getRow();
			if(empty($result)){
				$in_data = array(
					'manager_id'=>$adminId,//客户经理ID
				);
				$this->db->model('manager_user')->where("user_id='{$uid}'")->update($in_data);
	
				//设置客户经理，写日志
				$remarks = '设置客户经理【'.$_SESSION['name'].'】';
				M('user:applyLog')->addLog($v['user_id'],'set_customer_manager',$v['customer_manager'],$adminId,1,$remarks);
			}

		}
		
		$this->success('操作成功');
	}
	
	/**
	 * 解除登录锁定用户
	 * @access public 
	 * @return html
	 */
	public function unlockSubmit(){
		$ids = sget('ids','s');; //获取UI传递的参数
		if(empty($ids)){
			$this->error('错误的操作');
		}

		$update=array(
			'login_fail_count'=>0,
			'login_unlock_time'=>0,			  
		);
		$this->db->model('user')->where("user_id in ($ids)")->update($update);

		//解除登录锁定用户，写日志
		$remarks = "解除登录锁定用户";
		M('user:applyLog')->addLog($ids,'unlock_user','锁定4小时','正常',1,$remarks);
		
		$this->success('操作成功');
	}
}
?>
