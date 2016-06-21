<?php
/** 
 * 管理员列表
 */
class admAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->action = sget('action','');
		$this->db=M('rbac:adm');
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
			$sortField = sget("sortField",'s','admin_id'); //排序字段
			$sortOrder = sget("sortOrder",'s','asc'); //排序
			$where='1';
			if( $this->public == 1) $where.= ' and `admin_id` != 1 ';
			//部门搜索
			$depart = sget('depart','i','');
			if(!empty($depart)){
				$where.=" and `depart`='$depart' ";	
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
				$list['data'][$k]['last_login']=$v['last_login']>1000 ? date("Y-m-d H:i:s",$v['last_login']) : '-';
				$list['data'][$k]['status'] = L('adm_status')[$v['status']];
			}
			$result=array('total'=>$list['count'],'data'=>$list['data']);
			$this->json_output($result);
		}
		$lock = sget('lock','s');
		$this->assign('isPublic',1);
		$this->assign('lock',$lock);
		$this->depart=C('depart'); //所属部门
		$this->depart_json=setMiniConfig($this->depart);
		$this->assign('page_title','管理员列表');
		$this->display('adm.init.html');
	}
	
	/**
	 * 所有被锁定的管理员
	 * @access public 
	 * @return html
	 */
	public function lockAdm(){
		$action=sget('action');
		if($action=='grid'){
			$page = sget("pageIndex",'i',0); //页码
			$size = sget("pageSize",'i',20); //每页数
			$sortField = sget("sortField",'s','admin_id'); //排序字段
			$sortOrder = sget("sortOrder",'s','asc'); //排序
			$where='1 and login_fail_count>4';
			
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
				$list['data'][$k]['last_login']=$v['last_login']>1000 ? date("Y-m-d H:i:s",$v['last_login']) : '-';
				$list['data'][$k]['login_status']=$v['login_fail_count']==5 ? '锁定4小时': '-';
			}
			$result=array('total'=>$list['count'],'data'=>$list['data']);
			$this->json_output($result);
		}
		
		$this->depart=C('depart'); //所属部门
		$this->depart_json=setMiniConfig($this->depart);
		$this->assign('page_title','管理员列表');
		$this->display('lockAdm.list.html');
	}
	/**
	 * Ajax保持数据
	 * @access public 
	 */
	public function ajaxSave(){
		$this->is_ajax=true; //指定为Ajax输出
		
		$data = sdata(); //传递的参数
		if(empty($data) || empty($data['username'])){
			$this->error('错误的请求');	
		}
		$id=(int)$data['admin_id'];
		$where=''; $_data=array();
		if(empty($id)){
			if(empty($data['password'])){
				$this->error('请设置管理员密码');	
			}
		}else{
			$where=' and admin_id!='.$id;	
		}
		//用户名是否存在
		$exist=$this->db->model('admin')->where("username='$data[uername]'".$where)->getOne();
		if($exist){
			$this->error('该管理员账户已存在');	
		}
		$_data=array(
			'name'=>$data['name'],
			'mobile'=>$data['mobile'],	 
			'depart'=>(int)$data['depart'],	 
			'username'=>$data['username'],	 
		);
		if(!empty($data['password'])){
			$_data['password']=md5($data['password']);
		}
		if($id!=1){ //非默认管理员：可以修改是否为超管
			//$_data['is_super']=intval($data['is_super']);
			$_data['status']=intval($data['status']);
		}
		if($id>0){
			$this->db->model('admin')->wherePk($id)->update($_data);
		}else{
			$this->db->model('admin')->add($_data);
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
		$this->db->model('admin')->where("admin_id in ($ids)")->update($update);

		//解除登录锁定用户，写日志
		$remarks = "解除登录锁定用户";
		M('user:applyLog')->addLog($ids,'unlock_user','锁定4小时','正常',1,$remarks);
		
		$this->success('操作成功');
	}
}
?>
