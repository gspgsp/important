<?php
ini_set('display_errors', 'On');
/**
 * 联系人信息管理
 */
class userAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->doact = sget('do','s');
		$this->db=M('public:common')->model('customer_contact');
		$this->assign('status',L('contact_status'));// 联系人用户状态
		$this->operation_type=L('operation_type');//管理员审批行为
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
		$this->display('user.list.html');
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
		$plas=sget('plas','i',0);
		$where=" chanel != 5 and  chanel != 6 ";
		$where.= $this->doact=='search' ?  ' and `login_unlock_time` > 0 ' : ' and `login_unlock_time` = 0';
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
			$list['data'][$k]['is_default']=L('is_default')[$v['is_default']];
			$list['data'][$k]['c_id']= M('user:customer')->getColByName($v['c_id']);
			$list['data'][$k]['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
			$list['data'][$k]['update_time']=$v['update_time']>1000 ? date("Y-m-d H:i:s",$v['update_time']) : '-';
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
		$ids=sget('ids','s');
		if(empty($ids)){
			$this->error('操作有误');
		}
		$data = explode(',',$ids);
		if(is_array($data)){
			foreach ($data as $k => $v) {
				//默认联系人对应的公司的c_id
				// $res = M('user:customer')->getColByName($v,"c_id","contact_id");
				$con = M('user:customerContact')->getListByUserid($v);
				$c_id =  $con['c_id'];
				$is_default = $con['is_default'];
				if($c_id > 0 && $is_default > 0) {
					$contacts = $this->db->where("c_id = {$c_id}")->select('user_id')->getAll();
					if(count($contacts) > 1)	$this->json_output(array('err'=>3,'c_id'=>$c_id,'user_id'=>$v));
				}
				// if($res >0){
				// 	$this->error('主联系人不能删除');
				// }
			}
		}
		$result=$this->db->where("user_id in ($ids)")->delete();
		if($result){
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}
	/**
	 * 获取所有联系人信息页面
	 * @auth gsp
	 * @return [type] [description]
	 */
	public function uerdel(){
		$action=sget('action','s');
		$user_id=sget('user_id','i',0);//当前用户的id
		$c_id = sget('c_id','i',0);//当前公司的id
		if($action=='contact'){ //获取列表
			$this->_contact();exit;
		}
		$this->assign('c_id',$c_id);
        $this->assign('cur_uid',$user_id);
		$this->assign('page_title','重定义公司主要联系人');
		$this->display('contact.del.html');
	}
	/**
	 * 获取所有联系人信息
	 * @auth gsp
	 * @return [type] [description]
	 */
	private function _contact(){
		$this->is_ajax=true;
		$c_id = sget('c_id','i',0);//当前公司的id
       	$user_id = sget('cur_uid','i',0);//获取当前用户的id
        $page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$sortField = sget("sortField",'s','user_id'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
		$list = $this->db->where("c_id = $c_id and user_id != $user_id")
					->select('user_id,name,sex,tel,mobile,is_default')
					->page($page+1,$size)
					->order("$sortField $sortOrder")
					->getPage();
		foreach ($list['data'] as &$value) {
			$value['sex'] = L('sex')[$value['sex']];
			$value['is_default'] = L('is_default')[$value['is_default']];
		}
		$this->assign('do',$this->doact);
		$result=array('total'=>$list['count'],'data'=>$list['data']);
		$this->json_output($result);
	}
	/**
	 * 删除当前用户
	 * @auth gsp
	 */
	public function deleteCurtCon(){
		$this->is_ajax=true;
		$cur_uid = sget('cur_uid','i',0);
		$ids = sget('ids','i',0);
		if($cur_uid > 0 && $ids > 0){
			$arr = array(
				'is_default'=>1,
				'update_time'=>CORE_TIME,
				'update_admin'=>'admin'
				);
			$this->db->where("user_id = {$cur_uid}")->delete();
			$result = $this->db->where("user_id = {$ids}")->update($arr);
			if($result){
				$this->success('操作成功');
			}else{
				$this->error('数据处理失败');
			}
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
	 * 解锁会员
	 */
	public function deblocking(){
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
     * 获取省份列表
     *
     */
    public function getProvince(){
        $regList = M('system:region')->getProvinceCache();
        $list = array();
        $pid = sget('pid','i');
        foreach($regList as $k=>$v){
                $list[]=array('id'=> $v['id'],'name' => $v['name']);
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
		$plas=sget('plas','i',0);
		if($plas==1){
			$where=" chanel = 6 ";
		}else{
			$where=" chanel != 5 and  chanel != 6 ";
		}
		$where.= $this->doact=='search' ?  ' and `login_unlock_time` > 0 ' : ' and `login_unlock_time` = 0';
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
		$list=$this->db->where($where)->order("$sortField $sortOrder")->getAll();
		foreach($list as $k=>$v){
			$list[$k]['customer_manager'] = M('rbac:adm')->getUserByCol($v['customer_manager']);
			$list[$k]['depart']=C('depart')[$v['depart']];
			$list[$k]['sex']=L('sex')[$v['sex']];
			$list[$k]['c_id']= M('user:customer')->getColByName($v['c_id']);
			$list[$k]['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
			$list[$k]['update_time']=$v['update_time']>1000 ? date("Y-m-d H:i:s",$v['update_time']) : '-';
		}
		$str = '<meta http-equiv="Content-Type" content="text/html; charset=utf8" /><table width="100%" border="1" cellspacing="0">';
		$str .= '<tr><td>姓名</td><td>性别</td><td>公司</td><td>电话</td><td>QQ号</td><td>引荐人手机号</td><td>创建时间</td></tr>';
		foreach($list as $k=>$v){
			$str .= "<tr><td>".$v['name']."</td><td>".$v['sex']."</td><td>".$v['c_id']."</td><td  style='vnd.ms-excel.numberformat:@'>".$v['mobile']."</td>
			<td  style='vnd.ms-excel.numberformat:@'>".$v['qq']."</td><td>".$v['parent_mobile']."</td><td style='vnd.ms-excel.numberformat:@'>".$v['input_time']."</td></tr>";
		}
		$str .= '</table>';
		$filename = 'users.'.date("Y-m-d");
		header("Content-type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment; filename=$filename.xls");
		echo $str;
		exit;
	}
	/**
	 * 塑料圈联系人转到公海
	 * @author gsp <[<email address>]>
	 * @return [type] [description]
	 */
	public function toPublicSea(){
		$this->is_ajax=true;
		$user_id = sget('uid','i',0);
		$result = $this->db->select('con.user_id,con.is_default,con.is_trial,cus.c_id,cus.customer_manager')->from('customer_contact con')->leftjoin('customer cus','cus.c_id = con.c_id')->where('con.user_id ='.$user_id)->getRow();
		if(!empty($result)){
			$data = array(
				'customer_manager'=>0,
				'update_time'=>CORE_TIME,
				'update_admin'=>'admin',
				);
			$conData = array(
				'is_trial'=>2,
				'update_time'=>CORE_TIME,
				);
			$this->db->startTrans();
			try{
				if(!$this->db->model('customer_contact')->where('user_id = '.$user_id)->update($conData)) throw new Exception("系统错误 reg:114");
				if($result['customer_manager'] = 0){
					if(!$this->db->model('customer')->where('c_id = '.$result['c_id'])->update($data)) throw new Exception("系统错误 reg:113");
				}
			}catch (Exception $e){
				$this->db->rollback();
				$this->error($e->getMessage());
			}
			$this->db->commit();
			$this->success('转公海成功');
		}
		showtrace();
	}

}