<?php
/**
 * 物流信息管理
 */
class shipAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->db=M('public:common')->model('ship_price');
	}
	/**
	 * 会员列表
	 * @access public 
	 * @return html
	 */
	public function init(){
		$action=sget('action','s');
		if($action=='grid'){ //获取列表
			$this->_grid();exit;
		}elseif($action=='remove'){ //获取列表
			$this->_remove();exit;
		}elseif($action=='submit'){ //获取列表
			$this->_submit();exit;
		}elseif($action=='save'){ //获取列表
			$this->_save();exit;
		}
		$this->assign('page_title','资源库列表');
		$this->display('ship_price.list.html');
	}
	/**
	 * Ajax获取列表内容
	 * @access private 
	 * @return html
	 */
	private function _grid(){
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$sortField = sget("sortField",'s','input_time'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
		//搜索条件
		$where=" 1 ";
		//信息类型
		$prince_type=sget('prince_type','s','');
		if($prince_type != ''){
			$prince_s=sget('prince_s','i',0);
			$prince_e=sget('prince_e','i',0);
			$where.=" and `$prince_type` between $prince_s and $prince_e ";
		}
		//关键词搜索
		$key_type=sget('key_type','s','starting');
		$keyword=sget('keyword','s');
		if(!empty($keyword)){
			$where.=" and `$key_type`='$keyword' ";
		}
		$list=$this->db->where($where)
					->page($page+1,$size)
					->order("$sortField $sortOrder")
					->getPage();
		foreach($list['data'] as $k=>$v){
			$list['data'][$k]['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
			$list['data'][$k]['update_time']=$v['update_time']>1000 ? date("Y-m-d H:i:s",$v['update_time']) : '-';
		}
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
		$result=$this->db->where("id in ($ids)")->delete();
		if($result){
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}
	
	/**
	 * 编辑已存在的数据
	 * @access public 
	 * @return html
	 */
	private function _save(){
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //获取UI传递的参数
		$sql=array();
		if(empty($data)){
			$this->error('操作数据为空');
		}
		foreach($data as $k=>$v){
			$_data=array(
				'start'=>$v['start'],		 
				'end'=>$v['end'],
				'5to10'=>(int)$v['5to10'],
				'10to15'=>(int)$v['10to15'],
				'15to20'=>(int)$v['15to20'],
				'20to25'=>(int)$v['20to25'],
				'25to30'=>(int)$v['25to30'],
				'30plus'=>(int)$v['30plus'],
				'addition'=>$v['addition'],
				'cities'=>$v['cities'],
				'update_time'=>CORE_TIME,
				'admin_name'=>$_SESSION['name'],
			);
			if(isset($v['_state']) && $v['_state']=='added'){
				$res=$this->db->add($_data+array(
					'input_time'=>CORE_TIME,
				));
			}else{
				$sql[]=$this->db->wherePk($v['id'])->updateSql($_data);
			}
			
		}
		$result=$this->db->commitTrans($sql);
		if($result || $res){
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}
	
}