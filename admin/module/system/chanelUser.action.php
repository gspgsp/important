<?php
/** 
 * 渠道用户列表
 * Andy@2013-08-28
 */
class chanelUserAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->action = sget('action','');
		$this->db=M('public:common')->model('chanel_user');
		$this->doact = sget('do','s');
	}
	
	/**
	 * 渠道用户列表
	 * @access public 
	 * @return html
	 */
	public function init(){
		$action=sget('action');
		if($action=='grid'){
			$page = sget("pageIndex",'i',0); //页码
			$size = sget("pageSize",'i',20); //每页数
			$sortField = sget("sortField",'s','uid'); //排序字段
			$sortOrder = sget("sortOrder",'s','asc'); //排序
			$where='1';
			
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
				$list['data'][$k]['reg_time']=$v['reg_time']>1000 ? date("Y-m-d H:i:s",$v['reg_time']) : '-';
				$list['data'][$k]['password']=$v['user_id']>0 ? '-' : $v['password'];
			}
			$result=array('total'=>$list['count'],'data'=>$list['data']);
			$this->json_output($result);
		}
		
		$this->assign('page_title','渠道用户列表');
		$this->display('chanel.user.html');
	}

	/**
	 * 设置渠道用户
	 * @access public 
	 */
	public function ajaxSave(){
		$this->is_ajax=true; //指定为Ajax输出
		
		$data = sdata(); //传递的参数
		if(empty($data) || empty($data['username'])){
			$this->error('错误的请求');	
		}
		$id=(int)$data['uid'];
		$where=''; $_data=array();
		if(empty($id)){
			if(empty($data['password'])){
				$this->error('请设置密码');	
			}
		}else{
			$where=' and uid!='.$id;	
		}
		//用户名是否存在
		$exist=$this->db->model('chanel_user')->where("username='$data[username]'".$where)->getOne();
		if($exist){
			$this->error('该账户已存在');	
		}
		$_data=array(
			'username'=>$data['username'],	 
		);
		if(!empty($data['password'])){
			$_data['password']=getUnionPasswd($data['password']);
		}
		$_data['status']=intval($data['status']);
		$_data['if_invest']=intval($data['if_invest']);
		if($id>0){
			$this->db->model('chanel_user')->wherePk($id)->update($_data);
		}else{
			$this->db->model('chanel_user')->add($_data);
		}
		$this->success('操作成功');
	}
}
?>
