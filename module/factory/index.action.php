<?php
/**
*工厂管理控制器
*/
class indexAction extends adminBaseAction
{
	public function __init(){
		$this->db=M('public:common')->model('factory');
		$this->uid = sget('uid','i');
	}
	public function index(){
		$this->display('index.html');
	}
	public function init(){
		//获取列表数据
		$action=sget('action');
		if($action=='grid'){
			$page = sget("pageIndex",'i',0); //页码
			$size = sget("pageSize",'i',20); //每页数
			$sortField = sget("sortField",'s','fid'); //排序字段
			$sortOrder = sget("sortOrder",'s','desc'); //排序

			//搜索条件
			$where=" 1 ";
			//关键词
			 $keyword=sget('keyword','s');
			if(!empty($keyword)){
					$where.=" and f.f_name like '%$keyword%' ";
			}

			$list=$this->db->select('f.*')
						->from('factory f')
						->where($where)
						->page($page+1,$size)
						->order("$sortField $sortOrder")
						->getPage();

			foreach($list['data'] as $k=>$v){
			$list['data'][$k]['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
			$list['data'][$k]['update_time']=$v['update_time']>1000 ? date("Y-m-d H:i:s",$v['update_time']) : '-';
			$list['data'][$k]['status']=$v[status]==1?L('factory_status')[1]:L('factory_status')[0];
			}
			$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>'');
			$this->json_output($result);
		}
		p($this->db->getLastSql());
		$this->assign('page_title','厂家管理');
		$this->display('index.html');
    }


	/**
	 * 保存(新)工厂信息
	 * @access public
	 */
	public function ajaxSave(){
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //传递的参数
		if(empty($data)){
			$this->error('错误的请求');
		}

		$fid=(int)$data['fid'];
		$where=''; $_data=array();
		$data['status']=intval($data['status']);
		//$data['update_time']=CORE_TIME;
		if($fid>0){
			$this->db->model('factory')->wherePk($fid)->update($data);
		}else{
			$data['f_name'] = $data['f_name'];
			$data['groupno'] = $data['groupno'];
			$data['input_admin'] = $data['input_admin'];
			$data['remark'] = $data['remark'];
			$data['status']=intval($data['status']);
			$data['input_time']=CORE_TIME;
			$this->db->add($data);
		    //p($this->db->getLastSql());
		}

		//$this->clearMCache('chanels');
		$this->success('操作成功');
	}
	//ajax获取状态，改变保存
	public function changeSave(){
		$this->is_ajax=true; //指定为Ajax输出
		$changeid = sget('changeid','i'); //传递的参数
		$tag = sget('tag','s');
		$data['status']= $tag=='启用' ? 1 : 0;
		$res = $this->db->wherePk($changeid)->update($data);
		if($res){
			$result['err']=0;
			$result['msg']='操作成功';
		}else{
			$result['err']=1;
			$result['msg']='操作失败';
		}
		$this->json_output($result);
	}
	/**
	 * 删除工厂数据
	 * @access public 
	 */
	public function remove(){
		$this->is_ajax=true; //指定为Ajax输出
		$ids=sget('ids','s');
		if(empty($ids)){
			$this->error('操作有误');
		}
		$result=$this->db->where("fid in ($ids)")->delete();
		if($result){
			// $cache=cache::startMemcache();
			// $cache->delete('chanels');
			$this->success('操作成功');
		}else{
			$this->error('删除操作失败');
		}
	}
	/**
	 * 保存行内编辑工厂数据
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
			$_id=$v['fid'];
			if($_id>0){
				$update=array(
					'f_name'=>$v['f_name'],
					'groupno'=>$v['groupno'],
					'update_time'=>CORE_TIME,
					'update_admin'=>$v['update_admin'],
					'status'=>(int)$v['status'],
					'remark'=>$v['remark'],
				);
				$sql[]=$this->db->wherePk($_id)->updateSql(saddslashes($update));
			}
		}
		if(empty($sql)){
			$this->error('操作数据为空');
		}
		$result=$this->db->commitTrans($sql);
		if($result){
			//$this->clearMCache('chanels');
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}

}