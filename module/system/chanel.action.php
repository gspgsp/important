<?php
/*
 * 渠道管理
*/
class chanelAction extends adminBaseAction {
	public function __init(){
		$this->db=M('public:common')->model('chanel');
		$this->uid = sget('uid','i');
	}

    /**
     * 渠道列表
     * @access public
     */
	public function init(){
		//获取列表数据
		$action=sget('action');
		if($action=='grid'){
			$page = sget("pageIndex",'i',0); //页码
			$size = sget("pageSize",'i',20); //每页数
			$sortField = sget("sortField",'s','c.chanel_id'); //排序字段
			$sortOrder = sget("sortOrder",'s','desc'); //排序

			//搜索条件
			$where=" 1 ";
			if($this->uid>0){
				$where.=" and u.uid='$this->uid' ";	
			}
			
			//关键词
			$key_type=sget('key_type','s','name');
			$keyword=sget('keyword','s');
			if(!empty($keyword)){
				if($key_type=='remark'){
					$where.=" and c.remark like '%$keyword%' ";	
				}elseif($key_type=='username'){
					$where.=" and u.username='$keyword' ";	
				}else{
					$where.=" and c.$key_type='$keyword' ";	
				}
			}

			$list=$this->db->select('c.*, from_unixtime(c.input_time) as input_time, from_unixtime(c.update_time) as update_time,u.username')
						->from('chanel c')
						->leftjoin('chanel_user u','c.uid=u.uid')
						->where($where)
						->page($page+1,$size)
						->order("$sortField $sortOrder")
						->getPage();
			
			$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>'');
			$this->json_output($result);
		}
		
		$this->assign('page_title','渠道管理');
		$this->display('chanel.list.html');
    }
	
	/**
	 * 删除渠道数据
	 * @access public 
	 */
	public function remove(){
		$this->is_ajax=true; //指定为Ajax输出
		$ids=sget('ids','s');
		if(empty($ids)){
			$this->error('操作有误');	
		}
		$result=$this->db->where("chanel_id in ($ids)")->delete();
		if($result){
			$cache=cache::startMemcache();
			$cache->delete('chanels');
			$this->success('操作成功');
		}else{
			$this->error('删除操作失败');
		}
	}

	/**
	 * 编辑渠道信息
	 * @access public 
	 */
	public function ajaxSave(){
		$this->is_ajax=true; //指定为Ajax输出
		
		$data = sdata(); //传递的参数
		if(empty($data) || empty($data['name'])){
			$this->error('错误的请求');	
		}
		
		$id=(int)$data['chanel_id'];
		$where=''; $_data=array();
		
		$chkName=true;
		if($id>0){
			$old=$this->db->model('chanel')->wherePk($id)->getRow();
			if(empty($old)){
				$this->error('错误的请求');	
			}
			if($old['name']==$data['name']){ //名字没有改变，无需检查
				$chkName=false;	
			}			
		}
		if($chkName){ //是否存在
			$exist=$this->db->model('chanel')->where("name='$data[name]'".$where)->getOne();
			if($exist){
				$this->error('该渠道号已存在');	
			}
		}
		
		$data['status']=intval($data['status']);
		$data['uid']=intval($data['uid']);
		unset($data['chanel_id']);
		$data['update_time']=CORE_TIME;
		if($id>0){
			$this->db->model('chanel')->wherePk($id)->update($data);
		}else{
			$data['input_time']=CORE_TIME;
			$this->db->model('chanel')->add($data);
		}
		
		$this->clearMCache('chanels');
		$this->success('操作成功');
	}

	/**
	 * 编辑渠道数据
	 * @access public 
	 * @return html
	 */
	public function save(){
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //获取UI传递的参数
		if(empty($data)){
			$this->error('错误的操作');
		}
		
		$sql=array(); $n=count($chanels);
		foreach($data as $v){
			$_id=$v['chanel_id'];
			if($_id>0){
				$update=array(
					'status'=>(int)$v['status'],			  
					'remark'=>$v['remark'],			  
					'update_time'=>CORE_TIME,			  
				);
				$sql[]=$this->db->wherePk($_id)->updateSql(saddslashes($update));
			}
		}
		if(empty($sql)){
			$this->error('操作数据为空');
		}
		$result=$this->db->commitTrans($sql);
		if($result){
			$this->clearMCache('chanels');
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}
	
	/**
	 * 检查渠道编码是否唯一
	 * @access public 
	 * @return html
	 */
	public function chkUnique(){
		$this->is_ajax=true; //指定为Ajax输出
		$code=sget('code','s');
		if(empty($code)){
			$this->error('错误的请求');	
		}
		$exist=$this->db->model('chanel')->where("name='$code'")->getOne();
		if($exist){
			$this->error('渠道编号已存在');	
		}else{
			$this->success('可以使用');	
		}
	}
}

?>