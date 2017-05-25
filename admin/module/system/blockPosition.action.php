<?php
/** 
 * 页面模块管理
 */
class blockPositionAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->db->model('block_position');
	}

	/**
	 * 栏位列表
	 * @access public 
	 * @return html
	 */
	public function init(){
		$action=sget('action','s');
		if($action=='grid'){ //获取列表
			$page = sget("pageIndex",'i',0); //页码
			$size = sget("pageSize",'i',20); //每页数
			$sortField = sget("sortField",'s','id'); //排序字段
			$sortOrder = sget("sortOrder",'s','desc'); //排序
		
			//搜索条件
			$where=" 1 ";
			$list=$this->db->select('id,name,input_time')
						->where($where)
						->page($page+1,$size)
						->order("$sortField $sortOrder")
						->getPage();
			foreach($list['data'] as $k=>$v){
				$list['data'][$k]['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
			}
			$result=array('total'=>$list['count'],'data'=>$list['data']);
			$this->json_output($result);
		}
		$this->assign('page_title','栏位列表');
		$this->display('block.pos.list.html');
	}

	/**
	 * 编辑栏目内容详情
	 * @access public 
	 * @return html
	 */
	public function info(){
		$this->assign('mini_list',0);
		
		$id=sget('id','i');
		if($id>0){
			$info=$this->db->wherePk($id)->getRow();
			if(empty($info)){
				$this->error('错误的数据请求');	
			}
			$info['content']=json_decode($info['content'],true);
		}
		$this->assign('info',$info); 
		
		$this->assign('page_title','栏目内容');
		$this->display('block.pos.info.html');
	}
	
	/**
	 * 提交内容详情数据
	 * @access public 
	 * @return html
	 */
	public function submit() {
		$this->is_ajax=true;
		
		$id=sget('id','i');
		$name=sget('name','s');
		if(strlen($name)<2){
			$this->error('请输入栏位名');	
		}
		
		$data=array(
			'name'=>$name,
			'input_time'=>CORE_TIME,
		);
		$content=array();
		
		$col=sget('col','a');
		if(!empty($col)){ //栏位
			foreach($col as $k=>$v){
				$content[$k]=array(
					  'num'=>intval($_REQUEST['num'][$k]),		  
					  'width'=>intval($_REQUEST['width'][$k]),		  
					  'height'=>intval($_REQUEST['height'][$k]),		  
				  );	
			}
		}
		if(empty($content)){
			$this->error('请设置栏目内容');	
		}
		$data['content']=json_encode($content);
		$_data=saddslashes($data);
		
		//更新或新增商品数据
		if($id>0){
			$this->db->wherePk($id)->update($_data);	
		}else{
			$this->db->add($_data);
		}
		$this->clearMCache('blockPos');
		$this->success('操作成功');
	}
	
	/**
	 * 删除栏目内容
	 * @access public 
	 */
	public function remove() {
		$this->is_ajax=true; //指定为Ajax输出
		$ids=sget('ids','s');
		if(empty($ids)){
			$this->error('操作有误');	
		}
		$result=$this->db->where("id in ($ids)")->delete();
		if($result){
			$this->clearMCache('blockPos');
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}
}
?>