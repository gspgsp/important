<?php
/** 
 * 身份证库管理
 */
class libIdCardAction extends adminBaseAction {
	private $status=array();
	public function __init(){
		$this->debug = false;
		$this->action = sget('action','');
		$this->db=M('public:common')->model('lib_idcard');
		$this->status=array(0=>'检查失败',1=>'库中无此号',2=>'不一致',3=>'一致');
	}
	
	/**
	 * 身份证库列表
	 * @access public 
	 * @return html
	 */
	public function init(){
		$action=sget('action');
		if($action=='grid'){
			$page = sget("pageIndex",'i',0); //页码
			$size = sget("pageSize",'i',20); //每页数
			$sortField = sget("sortField",'s','check_time'); //排序字段
			$sortOrder = sget("sortOrder",'s','desc'); //排序
			$where='1';
			
			//关键词
			$key_type=sget('key_type','s','idcard');
			$keyword=sget('keyword','s');
			
			if(!empty($keyword)){
				if($key_type=="mobile"){
					$_id=$this->db->model('user')->select('user_id')->where("mobile like '%$keyword%'")->getCol();
					$id=!empty($_id) ? join(',',$_id) : '-1';
					$where.=" and user_id in ($id) ";	
				}else{
					if($key_type=="idcard"){
						$dbCard=M('system:sysIdCard');
						$keyword=$dbCard->encrypt($keyword);
					}
					$where.=" and $key_type='$keyword' ";
				}
			}
			$check_status=sget('check_status','i');
			if(!empty($check_status)){
				$where.=" and check_status='$check_status' ";
			}			
			
			$dbCard=M('system:sysIdCard');
			$list=$this->db->model('lib_idcard')->where($where)
						->page($page+1,$size)
						->order("$sortField $sortOrder")
						->getPage();
			foreach($list['data'] as $k=>$v){
				$list['data'][$k]['check_time']=$v['check_time']>1000 ? date("Y-m-d H:i:s",$v['check_time']) : '-';
				$list['data'][$k]['status']=$this->status[$v['check_status']];
				$list['data'][$k]['idcard']=$dbCard->decrypt($v['idcard']);
			}
			$result=array('total'=>$list['count'],'data'=>$list['data']);
			$this->json_output($result);
		}
		
		$this->assign('page_title','身份证库');
		$this->display('lib_idcard.html');
	}

	/**
	 * 身份证保存数据
	 * @access public 
	 */
	public function ajaxSave(){
		$this->is_ajax=true; //指定为Ajax输出
		
		$data = sdata(); //传递的参数
		
		if(empty($data) || empty($data['idcard']) || empty($data['real_name'])){
			$this->error('错误的请求');	
		}
		$id=$data['id']; unset($data['id']);

		$dbCard=M('system:sysIdCard');
		$info=array();
		if($id>0){
			$info=$this->db->model('lib_idcard')->getPk($id);
			if(empty($info) || $info['check_status']==3){ //已经通过检查
				$this->error('错误的请求2');
			}
			$info['idcard']=$dbCard->decrypt($info['idcard']);
			//未修改
			if($info['real_name'].$info['idcard']==$data['real_name'].$data['idcard'] && $info['check_status']==$data['check_status']){
				$this->success('操作成功');
			}	
		}
		
		if($data['check_status']==4){ //要求在线检查
			$result=$dbCard->set($data['real_name'],$data['idcard'])->libCheck();
			if($result){
				$this->success('操作成功');
			}else{
				$this->error($dbCard->getError());	
			}
			exit;
		}		
		
		//是否存在
		$data['idcard']=$dbCard->encrypt($data['idcard']);
		$data['check_time']=CORE_TIME;
		if($id>0){ //更新
			$this->db->where('id='.$id)->update($data);
		}else{
			$exist=$this->db->where("idcard='$data[idcard]'")->getOne();
			if($exist){
				$this->error('该身份证号已存在');	
			}
			$this->db->add($data);
		}
		$this->success('操作成功');
	}
}
?>