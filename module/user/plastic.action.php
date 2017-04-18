<?php
/**
 * 联系人信息管理
 */
class plasticAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->doact = sget('do','s');
		$this->db=M('public:common')->model('customer_contact');
		$this->assign('status',L('contact_status'));// 联系人用户状态
		$this->assign('is_pass',L('is_pass'));//客户级别
		$this->operation_type=L('operation_type');//管理员审批行为
		$this->quan_type =L('quan_type');//熟料圈来源
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
		$this->display('plastic.list.html');
	}
	/**
	 * 锁定的会员
	 */
	public function lockUserList(){
		$this->assign('doact','search');
		$this->assign('page_title','产品审核列表');
		$this->display('user.list.html');
	}
	/**
	 * Ajax获取列表内容
	 * @access private
	 * @return html
	 */
	private function _grid(){
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$sortField = sget("sortField",'s','user_id'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
		//搜索条件
		$where=" c.chanel = 6";
		//注册来源
		$chanel=sget('chanel','i',0);
		if($chanel != 0) $where.=" and c.`quan_type` =".($chanel-1);
		$where.= $this->doact=='search' ?  ' and `login_unlock_time` > 0 ' : ' and `login_unlock_time` = 0';
		$c_id=sget('c_id','i',0);
		if($c_id !=0)  $where.=" and `c.c_id` =".$c_id;
		//筛选状态
		$status=sget('status','i',0);
		if($status !=0)  $where.=" and `c.status` =".$status;
		//筛选时间
		$sTime = sget("sTime",'s','input_time'); //搜索时间类型
		$where.=getTimeFilter('c.'.$sTime); //时间筛选
		//关键词搜索
		$key_type=sget('key_type','s','name');
		$keyword=sget('keyword','s');
		if(!empty($keyword) && $key_type=='name' ){
			$where.=" and c.`$key_type`  like '%$keyword%' ";
		}elseif(!empty($keyword) && $key_type=='c_id'){
			$keyword=M('user:customer')->getColByName($keyword,$key_type,'c_name');
			$where.=" and c.`$key_type`  = '$keyword' ";
		}elseif(!empty($keyword) && $key_type=='customer_manager'){
			$keyword=M('rbac:adm')->getIdByName($keyword);
			$_ids = implode(',',$keyword);
			$where.=" and c.`$key_type` in ($_ids) ";
		}elseif(!empty($keyword)){
			$where.=" and c.`$key_type`  = '$keyword' ";
		}
		//初审状态筛选
		$is_trial = sget('trial_type','i');
		if($is_trial < 100) $where.=" and c.is_trial = $is_trial";
		// p($where);
		$list=$this->db->from('customer_contact c')
					->leftjoin('contact_info ci','c.user_id=ci.user_id')
					->select("c.*,ci.thumbcard,ci.mobile_area,ci.mobile_province")
					->where($where)
					->page($page+1,$size)
					->order("$sortField $sortOrder")
					->getPage();

		foreach($list['data'] as $k=>$v){
			$list['data'][$k]['customer_manager'] = M('rbac:adm')->getUserByCol($v['customer_manager']);
			$list['data'][$k]['depart']=C('depart')[$v['depart']];
			$list['data'][$k]['sex']=L('sex')[$v['sex']];
			$list['data'][$k]['c_id']= M('user:customer')->getColByName($v['c_id']);
			$list['data'][$k]['customer_id']= $v['c_id'];
			$list['data'][$k]['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
			$list['data'][$k]['update_time']=$v['update_time']>1000 ? date("Y-m-d H:i:s",$v['update_time']) : '-';
			$list['data'][$k]['parent_mobile']=$v['parent_mobile']=='undefined' ? '-' : $v['parent_mobile'];//联系人手机
			$list['data'][$k]['thumbcard']=$v['thumbcard']=='' ? '-' : $v['thumbcard'];//联系人头像
			//主营牌号 月用量 关注牌号
			$customer = M('user:customer')->getCinfoById($v['c_id']);
			$list['data'][$k]['main_product']=$customer['main_product'];
			$list['data'][$k]['month_consum']=$customer['month_consum'];
			//关注的牌号
			$where1 = "user_id = {$v['user_id']} and is_enable = 1 and is_concern = 1 and type = 1 ";
			$focus_mode = $this->db->model('suggestion_model')->select('name')->where($where1)->getAll();
			if(!empty($focus_mode)){
				$n_focus_mode = array();
				foreach ($focus_mode as $value) {
					array_push($n_focus_mode, $value['name']);
				}
				$s_focus_mode = implode(' ',$n_focus_mode);
				$list['data'][$k]['focus_mode'] = $s_focus_mode;
			}else{
				$list['data'][$k]['focus_mode'] = '';
			}

		}
		$this->assign('do',$this->doact);
		$result=array('total'=>$list['count'],'data'=>$list['data']);
		$this->json_output($result);
	}
	/**
	 * Ajax删除节点s
	 * @access private
	 */
	private function _remove(){
		$this->is_ajax=true; //指定为Ajax输出
		$cids=sget('cids','s');
		if(empty($cids)){
			$this->error('操作有误');
		}
		$u_ids = $this->db->where("c_id in ($cids)")->select('user_id')->getCol();
		$u_ids=implode($u_ids,',');
		// p($u_ids);
		// die;
//删除联系人
		$result1 = $this->db->where("user_id in ($u_ids)")->delete();
//删除联系人详细信息
		$result2 = M("user:contactInfo")->where("user_id in ($u_ids)")->delete();
//删除公司信息
		$result3 = M("user:customer")->where("c_id in ($cids)")->delete();
		// $this->db->startTrans();
		// try {
		// } catch (Exception $e) {
		// 	$this->db->rollback();
		// 	$this->error($e->getMessage());
		// }
		// $this->db->commit();
		if($result1 && $result2 && $result3){
			$this->success('操作成功');
		}else{
			$this->error('删除操作失败');
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
	/**
	 * 获取塑料圈会员相关信息
	 * @auth gsp
	 * @return [type] [description]
	 */
	public function contactInfos(){
		$this->is_ajax=true;
		$user_id = sget('id','i',0);//当前用户的id
        $chtype = sget('chtype','i',0);
        $isEdit = sget('isEdit','i',0);
        if($user_id>0){
			$usermodel = M('user:customerContact')->getContactModel($user_id,'3');//会员牌号 公司
		}
		$this->assign('status',L('contact_status'));
		$this->assign('sex',L('sex'));
		$this->assign('page_title','联系人列表');
		$this->assign('usermodel',$usermodel);
		$this->assign('chtype',$chtype);
		$this->assign('isEdit',$isEdit);
		$this->display('plastic.edit.html');
	}
	/**
	 * 核查公司
	 * @auth gsp
	 * @return [type] [description]
	 */
	public function checkCompany(){
		$this->is_ajax=true;
		$user_id = sget('user_id','i',0);
		$c_name = sget('c_name','s','');
		$cus = $this->db->model('customer')->where("c_name = '{$c_name}'")->select('c_id,c_name,status')->getAll();
		if(!empty($cus)){
			foreach ($cus as $key => $value) {
				$tem[] = $value['c_id'];
			}
			$cids = implode(',',$tem);
			$this->json_output(array('err'=>0,'msg'=>'公司存在','cids'=>$cids));
		}else{
			$this->json_output(array('err'=>2,'msg'=>'公司不存在'));
		}
	}
	/**
	 * 获取公司
	 * @auth gsp
	 * @return [type] [description]
	 */
	public function getCompanys(){
		$cids = sget('cids','s','');
		$action=sget('action','s');
		if($action=='company'){ //获取列表
			$this->_company();exit;
		}
		$this->assign('cids',$cids);
		$this->display('plastic.cus.html');
	}
	/**
	 * 获取数据
	 * @auth gsp
	 * @return [type] [description]
	 */
	private function _company(){
		$cids = sget('cids','s','');
		$cus = $this->db->model('customer')->where("c_id in($cids)")->select('c_id,c_name,status,customer_manager,is_sale,is_pur')->getAll();
		foreach ($cus as &$value) {
			// $value['status'] = A('user:customercheck')->chkstatus($value['customer_manager'],$value['is_sale'],$value['is_pur']);
			$value['status'] = $this->_chkstatus($value['customer_manager'],$value['is_sale'],$value['is_pur']);
		}
		$this->json_output(array('total'=>1,'data'=>$cus));
	}
	/**
	 * 检查状态
	 * @param  [type] $c    [description]
	 * @param  [type] $sale [description]
	 * @param  [type] $pur  [description]
	 * @return [type]       [description]
	 */
	private function  _chkstatus($c,$sale,$pur){
        $str='';
        $str .= $c==0 ? '公海客户':'';
        if($c>0 && ($sale==1 && $pur==1)){
            $str .='已合作客户+已合作供应商';
        }
        elseif($c>0 && ($sale==1 && $pur!=1)){
            $str .='已合作客户';
        }
        elseif($c>0 && ($sale!=1 && $pur==1)){
            $str .='已合作供应商';
        }
        elseif($c>0 && ($sale!=1 && $pur!=1)){
            $str .='私海客户';
        }
        return $str;
    }
    /**
     * 保存审核的数据
     * @auth gsp
     * @return [type] [description]
     */
    public function savePlasticData(){
    	$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //获取UI传递的参数
		if($data['cus_type'] == 0) $this->json_output(array('err'=>3,'msg'=>'客户类型必选!'));
		if(empty($data)) $this->json_output(array('err'=>2,'msg'=>'提交数据不能为空'));
		$con = array(
				'name'=>$data['name'],
				'sex'=>$data['sex'],
				'mobile'=>$data['mobile'],
				'tel'=>$data['tel'],
				'qq'=>$data['qq'],
				'fax'=>$data['fax'],
				'email'=>$data['email'],
				'remarks'=>$data['remark'],
				'status'=>$data['status'],
				'is_trial'=>$data['chtype'],
				'update_time'=>CORE_TIME,
				'update_admin'=>'admin'
				);
		$cus = array(
			'c_name'=>$data['c_name'],
			'main_product'=>$data['main_product'],
			'month_consum'=>$data['month_consum'],
			'type'=>$data['cus_type'],
			'update_time'=>CORE_TIME,
			'update_admin'=>'admin'
			);
		if($data['cus_type'] == 2) $con['update_time'] = 1483243920;
		//处理牌号
		$data['model_1'] = preg_replace("/(\n)|(\s)|(\t)|(\')|(')|( )|(，)|(\.)/",',',$data['model_1']);
        $data['model_1']=explode(",",$data['model_1']);
        $data['model_1']=array_map('strtoupper',$data['model_1']);
        foreach($data['model_1'] as $key=>$row){
            if(empty($row)) unset($data['model_1'][$key]);
        }
        if(!empty($data['model_1'])){
        	// $data['model_1'] = implode(' ',$data['model_1']);
        	$sug = array(
        	'user_id'=>$data['info_user_id'],
			'type'=>1,
			'is_concern'=>1,
			'create_time'=>date("Y-m-d H:i:m",CORE_TIME),
			'name'=>implode(' ',$data['model_1'])
			);
        }
		$this->db->model('customer_contact')->startTrans();
		try {
			if($data['stype'] == 0){//存在
				if($data['c_id'] == $data['nc_id']){
					if(!$this->db->model('customer_contact')->where("user_id = {$data['info_user_id']}")->update($con)) throw new Exception(" 用户更新失败 101");
					if(!empty($data['model_1'])) $cus['need_product'] = $this->getLinkUserConcern($data['c_id'],$data['model_1']);
					if(!$this->db->model('customer')->where("c_id = {$data['c_id']}")->update($cus)) throw new Exception(" 客户更新失败 102");
				}else{
					$con['c_id'] = $data['nc_id'];
					if(!$this->db->model('customer_contact')->where("user_id = {$data['info_user_id']}")->update($con)) throw new Exception(" 用户更新失败 103");
					if(!empty($data['model_1'])) $cus['need_product'] = $this->getLinkUserConcern($data['nc_id'],$data['model_1']);
					if(!$this->db->model('customer')->where("c_id = {$data['nc_id']}")->update($cus)) throw new Exception(" 客户更新失败 104");
				}
			}else{//不存在
				if(!$this->db->model('customer_contact')->where("user_id = {$data['info_user_id']}")->update($con)) throw new Exception(" 用户更新失败 105");
				if(!empty($data['model_1'])) $cus['need_product'] = $this->getLinkUserConcern($data['c_id'],$data['model_1']);
				if(!$this->db->model('customer')->where("c_id = {$data['c_id']}")->update($cus)) throw new Exception(" 客户更新失败 106");
			}
			if(!empty($data['model_1'])){
				if(!$this->db->model('suggestion_model')->add($sug)) throw new Exception(" 新增牌号失败 101");
			}
		} catch (Exception $e) {
			$this->db->model('customer_contact')->rollback();
			$this->error($e->getMessage());
		}
		$this->db->model('customer_contact')->commit();
		$this->success('编辑/初审成功');
    }
    /**
     * 拒绝审核通过
     * @auth gsp
     * @return [type] [description]
     */
    public function rejectContact(){
    	$this->is_ajax=true; //指定为Ajax输出
    	$user_id = sget('user_id','i',0);
    	$is_trial = sget('is_trial','i',3);
    	if(!empty($user_id)){
    		$con = array(
    			'is_trial'=>$is_trial,
    			'update_time'=>CORE_TIME,
				'update_admin'=>'admin'
    			);
    		if($this->db->where("user_id = $user_id")->update($con)){
    			$this->json_output(array('err'=>0,'msg'=>'更新成功'));
    		}else{
    			$this->json_output(array('err'=>2,'msg'=>'更新失败'));
    		}
    	}
    }
    /**
     * 获取同一个公司所有用户关注的牌号
     * @auth gsp
     * [getLinkUserConcern description]
     * @return [type] [description]
     */
    public function getLinkUserConcern($c_id,$modea){
    	$res = explode(' ',M('user:customer')->getCinfoById($c_id)['need_product']);
    	if(!empty($res)){
    		$res=array_filter($res,create_function('$v','return !empty($v);'));
	    	$arr = array_diff($modea,$res);
	    	$newArr = array_merge($arr,$res);
	    	return implode(' ',$newArr);
    	}else{
    		return implode(' ',$modea);
    	}
    }
	/**
	 * 解锁会员
	 */
	function deblocking(){
		$this->is_ajax = true;
		$user_id = sget('uid','i',0);
		if($user_id < 1) $this->error('操作有误');
		$data = array(
			'user_id'=>$user_id,
			'action_type'=>'unlock_user',
			'old_value'=>'锁定',
			'new_value'=>'解锁',
			'ip'=>get_ip(),
			'success'=>1,
			'input_time'=>CORE_TIME,
			'remark'=>'管理员解锁用户',
			'operator'=>$_SESSION['name'],
		);
		$this->db->startTrans();//开启事务
		try {
			if( !$this->db->where('user_id = '.$user_id)->update(array('login_unlock_time'=>0)) ) throw new Exception("解锁失败");
			if( !$this->db->model('apply_log')->add($data) ) throw new Exception("审批日志更新失败");

		} catch (Exception $e) {
			$this->db->rollback();
			$this->error($e->getMessage());
		}
		$this->db->commit();
		$this->success('操作成功');

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
		$user=$this->db->model('customer_contact')->getPk($user_id);
		if(empty($user)){
			$this->error('错误的用户信息');
		}
		$this->assign('user',$user);
		$this->assign('page_title','会员登录密码修改');
		$this->display('user.modPasswds.html');
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
		$info=$this->db->model('customer_contact')->getPk($user_id);
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
			$this->db->model('customer_contact')->wherePk($user_id)->update($basic);

			//管理员重置密码，写日志
			$remarks = "管理员重置用户密码";
			M('user:applyLog')->addLog($user_id,'reset_passwd','',$data['password'],1,$remarks);
			//发送手机短信
			M('system:sysSMS')->send($user_id,$info['mobile'],$msg,2);
		}

		$this->success('操作成功');
	}

	/**
	 * 导出报表
	 */
	/**
	 * 导出报表
	 * @access public
	 * @return html
	 */
	public function download(){
		set_time_limit(0);

		$sortField = sget("sortField",'s','user_id'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
		//搜索条件
		$where=" c.chanel = 6 ";
// p($this->doact);
// die;
		$where.= $this->doact=='search' ?  ' and `login_unlock_time` > 0 ' : ' and `login_unlock_time` = 0';
		$c_id=sget('c_id','i',0);
		if($c_id !=0)  $where.=" and `c.c_id` =".$c_id;
		//筛选状态
		$status=sget('status','i',0);
		if($status !=0)  $where.=" and `c.status` =".$status;
		//筛选时间
		$sTime = sget("sTime",'s','input_time'); //搜索时间类型
		$where.=getTimeFilter('c.'.$sTime); //时间筛选
		//关键词搜索
		$key_type=sget('key_type','s','name');
		$keyword=sget('keyword','s');
		if(!empty($keyword) && $key_type=='name' ){
			$where.=" and c.`$key_type`  like '%$keyword%' ";
		}elseif(!empty($keyword) && $key_type=='c_id'){
			$keyword=M('user:customer')->getColByName($keyword,$key_type,'c_name');
			$where.=" and c.`$key_type`  = '$keyword' ";
		}elseif(!empty($keyword) && $key_type=='customer_manager'){
			$keyword=M('rbac:adm')->getIdByName($keyword);
			$_ids = implode(',',$keyword);
			$where.=" and c.`$key_type` in ($_ids) ";
		}elseif(!empty($keyword)){
			$where.=" and c.`$key_type`  = '$keyword' ";
		}
		$list=$this->db->from('customer_contact c')
					->leftjoin('contact_info ci','c.user_id=ci.user_id')
					->select("c.*,ci.thumbcard,ci.mobile_area")
					->where($where)
					->page($page+1,$size)
					->order("$sortField $sortOrder")
					->getAll();

		foreach($list as $k=>$val){
			$list[$k]['customer_manager'] = M('rbac:adm')->getUserByCol($val['customer_manager']);
			$list[$k]['depart']=C('depart')[$val['depart']];
			$list[$k]['sex']=L('sex')[$val['sex']];
			$list[$k]['c_id']= M('user:customer')->getColByName($val['c_id']);
			$list[$k]['input_time']=$val['input_time']>1000 ? date("Y-m-d H:i:s",$val['input_time']) : '-';
			$list[$k]['update_time']=$val['update_time']>1000 ? date("Y-m-d H:i:s",$val['update_time']) : '-';
			$list[$k]['parent_mobile']=$val['parent_mobile']=='undefined' ? '-' : $val['parent_mobile'];//联系人手机

		}

		$str = '<meta http-equiv="Content-Type" content="text/html; charset=utf8" /><table width="100%" border="1" cellspacing="0">';
		$str .= '<tr><td>姓名</td><td>性别</td><td>公司</td><td>电话</td><td>归属地</td><td>QQ号</td><td>登录次数</td><td>引荐人手机号</td><td>创建时间</td></tr>';
		foreach($list as $k=>$v){
			$str .= "<tr><td>".$v['name']."</td><td>".$v['sex']."</td><td>".$v['c_id']."</td><td  style='vnd.ms-excel.numberformat:@'>".$v['mobile']."</td><td>".$v['mobile_area']."</td>
			<td  style='vnd.ms-excel.numberformat:@'>".$v['qq']."</td><td>".$v['visit_count']."</td><td>".$v['parent_mobile']."</td><td style='vnd.ms-excel.numberformat:@'>".$v['input_time']."</td></tr>";
		}
		$str .= '</table>';
		$filename = 'plastic.'.date("Y-m-d");
		header("Content-type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment; filename=$filename.xls");
		echo $str;
		exit;
	}

	/**
	 * 批量审核客户认证状态
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
				'is_pass'=>$v['is_pass'],
				'remarks'=>$v['remarks'],
				'update_time'=>CORE_TIME,
			);
			$this->db->wherePk($v['user_id'])->update($update);
		}
		$this->success('操作成功');
	}
	/**
	 * 保存初审信息
	 * @author gsp <[<email address>]>
	 * @return [type] [description]
	 */
//	public function saveTrialInfo(){
//		$this->is_ajax=true;
//		$data = sdata(); //获取UI传递的参数
//		$sale_buy=sget('sale_buy','i',0); //  买:1   卖:2
//		p($data);
//		p($sale_buy);die;
//		$data['c_name'] = trim($data['c_name']);//公司
//		$sgs_model = $this->db->model('suggestion_model');
//		$sgsArr = array(
//			'name'=>$data['name'],
//			'update_time'=>CORE_TIME
//			);
//		$wheres = "user_id = {$data['user_id']} and type = 1 and is_enable = 1";
//		$cusArr = array(
//			'c_name'=>$data['c_name'],
//			'update_time'=>CORE_TIME
//			);
//		$sgs_model->startTrans();
//		try{
//			if(!$sgs_model->where($wheres)->update($sgsArr)) throw new Exception("系统错误 reg:110");
//
//			$c_id = $this->db->model('customer_contact as con')->leftjoin('customer as cus','cus.c_id = con.c_id')
//				->where("con.user_id = ".$data['user_id'])
//				->select('cus.c_id')
//				->getOne();
//			if(!M('user:customer')->where("c_id = $c_id")->update($cusArr)) throw new Exception("系统错误 reg:111");
//
//			$is_trial = M('user:customerContact')->getContactTrialStatus($data['user_id']);
//			$conArr = array(
//				'is_trial'=>1,
//				'update_time'=>CORE_TIME,
//				);
//			if($is_trial == 0){
//				if(!M('user:customerContact')->where("user_id = {$data['user_id']}")->update($conArr)) throw new Exception("系统错误 reg:112");
//			}
//
//		}catch (Exception $e) {
//			$sgs_model->rollback();
//			$this->error($e->getMessage());
//		}
//		$sgs_model->commit();
//		$this->success('初审成功');
//	}


	/**
	 * 保存塑料圈会员初审信息
	 * @Author: yuanjiaye
     */
	public function saveInfo(){
		$this->is_ajax=true;
		$data = sdata(); //获取UI传递的参数
        $is_trial=sget('is_trial','i','0');
        $data['is_trial'] = $is_trial=='0'?'1':$is_trial;
		$type=4;// 关注牌号
		if(empty($data['cus_type'])){
			$this->json_output(array('err' => 99, 'msg' => '客户类型为必填项'));
		}
        // 牌号为空
        if(empty($data['model_1'])){
            $c_id = $this->db->model('customer_contact as con')->leftjoin('customer as cus','cus.c_id = con.c_id')
                ->where("con.user_id = ".$data['user_id'])
                ->select('cus.c_id')
                ->getOne();
//            $is_trial = M('user:customerContact')->getContactTrialStatus($data['user_id']);
            $conArr = array(
                'is_trial'=>$data['is_trial'],             //初审变为转公海
                'update_time'=>CORE_TIME,
                'remarks' => $data['remarks']              //塑料圈会员初审备注
            );
            $cusArray=array(
                'c_name'=>$data['c_name'],                 //公司名
                'month_consum'=>$data['month_consum'],     // 月用量
                'main_product'=> $data['main_product'],    //主营产品
                'type'=>$data['cus_type'],
                'update_time'=>CORE_TIME,
            );
			if($data['cus_type']==2){
				$cusArray['update_time'] = strtotime("2017-01-01 12:00:00");
				$conArr['update_time'] = strtotime("2017-01-01 12:00:00");
			}

			if(!M('user:customer')->where("c_id={$data['c_id']}")->update($cusArray)) throw new Exception('月用量更新失败');

                if(!M('user:customerContact')->where("user_id = {$data['user_id']}")->update($conArr)) throw new Exception("系统错误 reg:112");

        }else{   //牌号不为空
            $data['model_1'] = preg_replace("/(\n)|(\s)|(\t)|(\')|(')|( )|(，)|(\.)/",',',$data['model_1']);
            $data['model_1']=explode(",",$data['model_1']);
            $data['model_1']=array_map('strtoupper',$data['model_1']);
            foreach($data['model_1'] as $key=>$row){
                if(empty($row)) unset($data['model_1'][$key]);
            }
            $data['model_1']=array_unique($data['model_1']);
            if(count($data['model_1'])>3) $this->json_output(array('err'=>5,'msg'=>'牌号个数不能超过3个'));

            $sgs_model = $this->db->model('suggestion_model');
            try{
                $result = M('qapp:plasticSave')->saveSelfInfo($data['user_id'], $type, $data['model_1']);
                if (!$result) throw new Exception(array('err' => 2, 'msg' => '保存资料失败'));
                $c_id = $this->db->model('customer_contact as con')->leftjoin('customer as cus','cus.c_id = con.c_id')
                    ->where("con.user_id = ".$data['user_id'])
                    ->select('cus.c_id')
                    ->getOne();

//                $is_trial = M('user:customerContact')->getContactTrialStatus($data['user_id']);
                $conArr = array(
                    'is_trial'=>$data['is_trial'],             //初审变为转公海
                    'update_time'=>CORE_TIME,
                    'remarks' => $data['remarks']              //塑料圈会员初审备注
                );
				//干嘛写了又不把牌号加上去 --xielei --04/14
				$cusArray=array(
                    'c_name'=>$data['c_name'],                 //公司名
                    'month_consum'=>$data['month_consum'],     // 月用量
                    'main_product'=> $data['main_product'],    //主营产品
					'need_product'=>join(',',$data['model_1']),
                    'type'=>$data['cus_type'],
                    'update_time'=>CORE_TIME,
                );
				if($data['cus_type']==2){
					$cusArray['update_time'] = strtotime("2017-01-01 12:00:00");
					$conArr['update_time'] = strtotime("2017-01-01 12:00:00");
				}

				if(!M('user:customer')->where("c_id={$data['c_id']}")->update($cusArray)) throw new Exception('月用量更新失败');
//                if($is_trial == 0){
                    if(!M('user:customerContact')->where("user_id = {$data['user_id']}")->update($conArr)) throw new Exception("系统错误 reg:112");
//                }

            }catch (Exception $e) {
                $sgs_model->rollback();
                $this->error($e->getMessage());
            }
            $sgs_model->commit();
        }
        $this->json_output(array('err' => 0, 'msg' => '保存资料成功'));
	}



	/**
	 * 模糊匹配p2p_product表 MODEL
	 * @Author: yuanjiaye
     */
	public function getModel(){
		$productModel=$this->db->model('product');
		$keywords=sget('key','s','');
		if($keywords =='') return false;
		if($keywords){
			$list=$productModel->where("model like '$keywords%'")->select('id,model')->limit('5')->getAll();
			json_output($list);
		}
	}
	/**
	 * 根据牌号模糊查找商品
	 */
	public function getPrdoucts(){
		$keywords=sget('key','s','');
		if($keywords =='') return false;
		if($keywords){
			$list=$this->db->model('product')->where("model like '%$keywords%'")->select('id,model,f_id')->limit('5')->getAll();
			if($list){
				foreach ($list as &$v) {
					$v['model'] = $v['model'].'--'.$this->db->model('factory')->select("f_name")->where("`fid` = {$v['f_id']}")->getOne();
				}

			}
			json_output($list);
		}
	}


}