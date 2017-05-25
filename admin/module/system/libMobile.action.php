<?php
/** 
 * 手机号库管理
 */
class libMobileAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->action = sget('action','');
		$this->db=M('public:common')->model('lib_mobile');
	}
	
	/**
	 * 手机库列表
	 * @access public 
	 * @return html
	 */
	public function init(){
		$action=sget('action');
		if($action=='grid'){
			$page = sget("pageIndex",'i',0); //页码
			$size = sget("pageSize",'i',20); //每页数
			$sortField = sget("sortField",'s','id'); //排序字段
			$sortOrder = sget("sortOrder",'s','desc'); //排序
			$where='1';
			
			//关键词
			$key_type=sget('key_type','s','mnum');
			$keyword=sget('keyword','s');
			if(!empty($keyword)){
				if($key_type=='mnum'){
					$where.=" and $key_type='$keyword' ";
				}else{
					$where.=" and $key_type like '%$keyword%' ";
				}
			}
			
			$list=$this->db->where($where)
						->page($page+1,$size)
						->order("$sortField $sortOrder")
						->getPage();
			
			$result=array('total'=>$list['count'],'data'=>$list['data']);
			$this->json_output($result);
		}
		
		$this->assign('page_title','手机库');
		$this->display('lib_mobile.html');
	}

	/**
	 * 手机号库保存数据
	 * @access public 
	 */
	public function ajaxSave(){
		$this->is_ajax=true; //指定为Ajax输出
		
		$data = sdata(); //传递的参数
		if(empty($data) || empty($data['mnum']) || empty($data['mcode'])){
			$this->error('错误的请求');	
		}
		$id=(int)$data['id'];
		$where=''; $_data=array();
		if(!empty($id)){
			$where=' and id!='.$id;	
		}
		$data['mnum']=substr($data['mnum'],0,7);
		//是否存在
		$exist=$this->db->where("mnum='$data[mnum]'".$where)->getOne();
		if($exist){
			$this->error('该手机号已存在');	
		}
		unset($data['id']);
		if($id>0){
			$this->db->wherePk($id)->update($data);
		}else{
			$data['mok']='sys';
			$this->db->add($data);
		}
		$this->success('操作成功');
	}

	/**
	 * 手机号库删除
	 * @access public 
	 */
	public function remove(){
		$this->is_ajax=true; //指定为Ajax输出
		$ids=sget('ids','s');
		if(empty($ids)){
			$this->error('操作有误');	
		}
		$result=$this->db->where("id in ($ids)")->delete();
		if($result){
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}
}
?>
