<?php
/**
 * 产品信息管理
 */
class productAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		//产品信息表
		$this->db=M('public:common')->model('product');
		//产品分类语言包
		$this->assign('product_type',L('product_type'));
		//产品状态语言包
		$this->assign('product_status',L('product_status'));
		//产品单位
		$this->assign('unit',L('unit'));
		//加工级别
		$this->assign('process_type',L('process_level'));
	}
	/**
	 *
	 * @access public
	 * @return html
	 */
	public function init(){
		$action=sget('action','s');
		if($action=='grid'){ //获取列表
			$this->_grid();exit;
		}
		$this->assign('page_title','产品信息列表');
		$this->display('product.list.html');
	}
	/**
	 *
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
		$keyword=sget('keyword','s');
		$product_type=sget('product_type','s');
		$status =sget('status','s');
		if(!empty($keyword)){
			$where.=" and `model` like '%$keyword%' ";
		}
		if(!empty($product_type)){
			$where.=" and `product_type` = '$product_type' ";
		}
		if(!empty($status)){
			$where.=" and `status` = '$status' ";
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
	 * 保存(新/编辑)产品信息
	 * @access public
	 */
	public function ajaxSave(){
		$this->is_ajax=true; //指定为Ajax输出
		$action = sget('action','s');
		$data = sdata(); //传递的参数
		if(empty($data)){
			$this->error('错误的请求');
		}
		$id=$data['id'];
		$data = array(
			'p_name'=>$data['p_name'],
			'product_type'=>$data['product_type'],
			'model'=>$data['model'],
			'p_name'=>$data['p_name'],
			'f_id'=>$data['f_id'],
			'f_name'=>$data['f_name'],
			'process_type'=>$data['process_type'],
			'unit'=>$data['unit'],
			'package'=>$data['package'],
			'status'=>$data['status'],
			'remark'=> $data['remark'],
			'input_time'=>CORE_TIME,
			'input_admin'=>$_SESSION['name'],
		);
		if($action =='edit'){
			$data['update_time']=CORE_TIME;
			$data['update_admin']=$_SESSION['name'];
			$result = $this->db->where("id=$id")->update($data);
		}else{
			$result = $this->db->add($data);
		}
		if(!$result) $this->error('操作失败');
		$this->success('操作成功');
	}
	/**
	 * 删除产品数据
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
			$cache=cache::startMemcache();
			$cache->delete('product');
			$this->success('操作成功');
		}else{
			$this->error('删除操作失败');
		}
	}
	/**
     * 产品信息编辑
     * @access public
    */
	public function edit(){
		$product_id=sget('id','i');
		$data = $this->db->where('id='.$product_id)->getRow();
		$this->assign('data',$data);
		$this->display('product.edit.html');
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
		$sql=array();
		foreach($data as $v){
			$_id=$v['id'];
			if($_id>0){
				$update=array(
					'update_time'=>CORE_TIME,
					'update_admin'=>$_SESSION['name'],
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
			$cache=cache::startMemcache();
			$cache->delete('product');
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}
	//ajax获取状态，改变保存
	public function changeSave(){
		$this->is_ajax=true; //指定为Ajax输出
		$changeid = sget('changeid','i'); //传递的参数
		$tag = sget('tag','s');
		$data['status']= $tag==L('product_status')[1] ? 1:2;
		$data['update_time'] = CORE_TIME;
		$data['update_admin'] = $_SESSION['name'];
		$res = $this->db->wherePk($changeid)->update($data);
		//showtrace();
		if($res){
			$cache=cache::startMemcache();
			$cache->delete('product');
			$this->success('操作成功');
		}else{
			$this->error('操作失败');
		}
	}
}