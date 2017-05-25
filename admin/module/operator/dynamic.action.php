<?php
/**
 * 调价动态
 */
class dynamicAction extends adminBaseAction{

	public function __init(){
		$this->debug = false;
		//调价动态
		$this->db=M('operator:dynamic');
		$this->db->delCache();//删除调价动态缓存
	}

	public function init(){

		$this->assign('dynamic_status',L('dynamic_status'));//涨跌状态
		$action=sget('action','s');
		if($action=='grid'){
			$this->_grid();exit;
		}
		$this->display('dynamic');
	}

	// 列表
	protected function _grid(){
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数

		$list=$this->db->order('input_time desc')->page($page+1,$size)->getPage();
		foreach ($list['data'] as &$value) {
			$value['input_time']= $value['input_time']==0?'--':date('y-m-d H:i:s',$value['input_time']);
		}
		$result=array('total'=>$list['count'],'data'=>$list['data']);
		$this->json_output($result);
	}

	/**
	 * 删除调价动态
	 * @access public
	 */
	public function del(){
		$this->is_ajax=true;
		$id=sget('id','i',0);
		if(!$this->db->wherePk($id)->getRow()) $this->error('信息不存在');
		$this->db->wherePk($id)->delete();
		$this->success('操作成功');
	}

	/**
	 * 保存新增调价动态
	 * @access public
	 */
	public function ajaxSave(){
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //传递的参数
		if(empty($data)){
			$this->error('错误的请求');
		}
		$id=$data['id'];
		unset($data['id']);
		$data['input_time']=CORE_TIME;
		if(!$id){
			$result = $this->db->add($data);
		}else{
			$result = $this->db->wherePk($id)->update($data);
		}
		if(!$result) $this->error('操作失败');
		$this->success('操作成功');
	}


	public function saveTags(){
		$this->is_ajax=true;
		$data=sdata();
		if(empty($data)) $this->error('信息不存在');
		foreach ($data as $key => $value) {
			$value['input_time']=CORE_TIME;
			$this->db->wherePk($value['id'])->update($value);
		}
		$this->success('操作成功');
	}

}