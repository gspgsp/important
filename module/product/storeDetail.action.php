<?php 
/**
 * 订单管理
 */
class storeDetailAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->db=M('public:common')->model('in_log');
		$this->doact = sget('do','s');
	}
	/**
	 *
	 * @access public
	 * @return html
	 */
	public function init(){
		$doact=sget('do','s');
		$action=sget('action','s');
		if($action=='grid'){ //获取列表
			$this->_grid();exit;
		}
		$this->assign('doact',$doact);
		$this->assign('page_title','订单管理列表');
		$this->display('storeDetail.list.html');
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
		//筛选
		$where.= 1;
		// //筛选状态
		if(sget('number_status','i',0) !=0) {
			$number_status=sget('remainder','i',0);//有剩余数量可用
			$where.=" and `remainder` > 0";
		}
		//筛选时间
		$sTime = sget("sTime",'s','input_time'); //搜索时间类型
		$where.=getTimeFilter($sTime); //时间筛选
		//关键词搜索
		$key_type=sget('key_type','s','store_id');
		$keyword=sget('keyword','s');
		if(!empty($keyword) && $key_type=='store_id'  ){
			$keyword=M('product:store')->getSidBySname($keyword);
			$where.=" and `$key_type` in ('$keyword') ";
		}elseif(!empty($keyword) && $key_type=='store_aid'){
			$keyword=M('product:store_admin')->getSaidByName($keyword);
			$where.=" and `$key_type` in ('$keyword') ";
		}elseif(!empty($keyword) && $key_type=='p_id' ){
			$keyword=M('product:product')->getpidByPname($keyword);
			$where.=" and `$key_type` in ($keyword) ";
		}elseif(!empty($keyword)){
			$where.=" and `$key_type`  like '%$keyword%' ";
		}
		$list=$this->db->where($where)
				->page($page+1,$size)
				->order("$sortField $sortOrder")
				->getPage();
		foreach($list['data'] as $k=>$v){
			$list['data'][$k]['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
			$list['data'][$k]['update_time']=$v['update_time']>1000 ? date("Y-m-d H:i:s",$v['update_time']) : '-';
			$list['data'][$k]['price_type']=L('price_type')[$v['price_type']];
			$list['data'][$k]['unit']=L('unit')[$v['unit']];	
			$list['data'][$k]['store_name']=M("product:store")->getStoreNameBySid($list['data'][$k]['store_id']); //获取仓库名
			$list['data'][$k]['model']=M("product:product")->getModelById($list['data'][$k]['p_id']);//获取牌号
			$list['data'][$k]['admin_name']=M("product:inStorage")->getNameBySid($list['data'][$k]['store_aid']); //获得入库人姓名
		}
		$result=array('total'=>$list['count'],'data'=>$list['data']);
		$this->json_output($result);
	}
	/**
	* 订单信息
	* @access public
	*/
	public function info(){
		$id=sget('id','i',0);
		if($id<1) $this->error('错误的订单信息');
		$type=sget('type','s');
		$info=$this->db->getPk($id); //查询订单信息
		if(empty($info)){
			$this->error('错误的订单信息');	
		}
		$info['input_time']=date("Y-m-d H:i:s",$info['input_time']);
		$info['model']=M("product:product")->getModelById($info['p_id']);//获取牌号
		$info['admin_name']=M("product:inStorage")->getNameBySid($info['store_aid']); //获得出库人姓名
		$info['store_name']=M("product:store")->getStoreNameBySid($info['store_id']); //获取仓库名
		$this->assign('info',$info);//分配订单信息
		if($type=="edit"){
			$this->display('storeDetail.edit.html');
			exit;
		}	
		$this->assign('order_type',$order_type);
		$this->assign('o_id',$o_id);
		$this->display('order.viewInfo.html');
	}
	/**
	 * 新增及修改订单
	 * @access public 
	 * @return html
	 */
	public function ajaxSave() {
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //获取UI传递的参数
		if(empty($data)) $this->error('错误的请求');	
		$_data=array(
			'update_time'=>CORE_TIME,
			'update_admin'=>$_SESSION['name'],
		);
		$remainder = $this->db->select('remainder')->wherePk($data['id'])->getOne();
		$subtract = abs($number-$data['remainder']); //与修改后相减

		$update =  $number > $data['remainder'] ? "number=number-$subtract" : "number=number+$subtract";
		// p($update);
		$this->db->startTrans(); //开启事务
		try {
			if( !$this->db->update($data+$_data) ) throw new Exception ('仓库明细更新失败');
			if( !$this->db->model('store_product')->where(' s_id = '.$data['store_id'])->update($update)) throw new Exception ('仓库货品表更新失败');
		} catch (Exception $e) {
			$this->db->rollback();
			$this->error($e->getMessage());
		}
		$this->db->commit();
		$this->success('操作成功');	

	}
	/**
	 * Ajax删除
	 * @access private 
	 */
	public function remove(){
		$this->is_ajax=true; //指定为Ajax输出
		$ids=sget('ids','s');
		if(empty($ids)){
			$this->error('操作有误');	
		}
		$data = explode(',',$ids);
		if(is_array($data)){
			foreach ($data as $k => $v) {
				$result=$this->db->where("id in ($ids)")->delete();
			}
		}
		if($result){
			$this->success('操作成功');
		}else{
			$this->error('订单有相关明细存在');
		}
	}
}