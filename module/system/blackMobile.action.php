<?php
/** 
 * 手机黑名单
 */
class blackMobileAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->action = sget('action','');
		$this->db=M('public:common')->model('black_mobile');
	}
	
	/**
	 * 手机黑名单列表
	 * @access public 
	 * @return html
	 */
	public function init(){
		$action=sget('action','s');
		if($action=='grid'){
			$page = sget("pageIndex",'i',0); //页码
			$size = sget("pageSize",'i',20); //每页数
			$sortField = sget("sortField",'s','input_time'); //排序字段
			$sortOrder = sget("sortOrder",'s','desc'); //排序

			//搜索条件
			$where=" 1 ";
			
			//关键词
			$key_type=sget('key_type','s','mobile');
			$keyword=sget('keyword','s');
			if(!empty($keyword)){
				$where.=" and $key_type like '$keyword%' ";
			}
			
			$list=$this->db->where($where)
						->page($page+1,$size)
						->order("$sortField $sortOrder")
						->getPage();
			foreach($list['data'] as $k=>$v){
				$list['data'][$k]['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
				$list['data'][$k]['expiration']=$v['expiration']>1000 ? date("Y-m-d H:i:s",$v['expiration']) : '-';
			}
			$result=array('total'=>$list['count'],'data'=>$list['data']);
			$this->json_output($result);
		}
		
		$this->assign('page_title','手机黑名单');
		$this->display('black_mobile.html');
	}

	/**
	 * 手机黑名单保存数据
	 * @access public 
	 * @return html
	 */
	public function ajaxSave(){
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //获取UI传递的参数
		$sql=array();
		if(empty($data)){
			$this->error('操作数据为空');
		}
		
		//检查是否存在
		$exist=$this->db->select('id')->where('mobile='.$data['mobile'])->getOne();
		if($exist){
			$this->error('此手机已经在黑名单中，请重新输入');	
		}
		$data['input_time']=CORE_TIME;
		$data['admin']=$_SESSION['name'];
		$data['expiration']=CORE_TIME+$data['day']*86400;
		unset($data['day']);
		
		$result=$this->db->add($data);
		if($result){
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}

	/**
	 * 手机黑名单删除
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
