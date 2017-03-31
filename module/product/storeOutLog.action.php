<?php
/**
 * 仓库出库明细
 */
class storeOutLogAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->cache=cache::startMemcache();
		$this->db=M('public:common')->model('out_log');
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
		$this->assign('page_title','订单仓库出库明细');
		$this->display('outlog.list.html');
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
		$where =" 1 and `del` = 0 ";
		//筛选时间
		$sTime = sget("sTime",'s','input_time'); //搜索时间类型
		$where.=getTimeFilter($sTime); //时间筛选
		//出库状态搜索
		$status = sget('status','i');
		if (!empty($status)) {
			$where.= "and `out_storage_status` = $status";
		}
		//收货状态状态搜索
		$sign = sget('sign','i');
		if (!empty($sign)) {
			$sign=$sign==1?1:0;
			$where.= "and `sign` = $sign";
		}
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
		}elseif(!empty($keyword) && $key_type=='order_sn' ){
			$keyword=M('product:order')->getIdsBySn($keyword);
			$where.=" and `o_id` in ($keyword) ";
		}elseif(!empty($keyword) && $key_type=='customer_manager'){
			$admin_id = M('product:order')->getOidsByAdmin($keyword);
			$where.=" and `o_id` in ($admin_id) ";
		}elseif(!empty($keyword) && $key_type=='c_id'){
			$keyword=M('product:order')->getOidsByCname($keyword);
			$where.=" and `o_id` in ($keyword) ";
		}elseif(!empty($keyword)){
			$where.=" and `$key_type`  like '%$keyword%' ";
		}
		$list=$this->db->where($where)
				->page($page+1,$size)
				->order("$sortField $sortOrder")
				->getPage();
		foreach($list['data'] as $k=>$v){
			$list['data'][$k]['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
			$list['data'][$k]['out_time']=$v['out_time']>1000 ? date("Y-m-d H:i:s",$v['out_time']) : '-';
			$list['data'][$k]['store_name']=M("product:store")->getStoreNameBySid($v['store_id']); //获取仓库名
			$list['data'][$k]['model']=strtoupper(M("product:product")->getModelById($v['p_id']));//获取牌号
			$list['data'][$k]['fname']=M("product:product")->getFnameByid($v['p_id']);//获取厂家
			$list['data'][$k]['admin_name']=M("product:inStorage")->getNameBySid($v['store_aid']); //获得入库人姓名
			$list['data'][$k]['sales_type']=L('sales_type')[M("product:order")->getColByName($v['o_id'],'sales_type')];//出库流水
			$list['data'][$k]['order_sn']=M("product:order")->getColByName($v['o_id'],'order_sn');//订单id
			$list['data'][$k]['out_storage_status']=L('out_storage_status')[$v['out_storage_status']];
			$list['data'][$k]['cname'] = M("product:order")->getCnameByOid($v['o_id']);
			$list['data'][$k]['ship_time']=$v['ship_time']>1000 ? date("Y-m-d H:i:s",$v['ship_time']) : '-';
			$list['data'][$k]['customer_manager'] = M("product:order")->getNameBySid($v['o_id']);
			$list['data'][$k]['sign']=L('sign')[$v['sign']];//读取收货状态的语言包
		}
		$msg="";
		if($list['count']>0){
			$sum=$this->db->select("sum(number) as wsum, sum(ship) as ship")->where($where)->getRow();
			$msg="出库总吨:【".$sum['wsum']."】&nbsp; 运费总额：【".$sum['ship']."】";
		}
		$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>$msg);
		$this->json_output($result);
	}
	/**
	 * 出库详细流水
	 */
	public function logDetail(){
		$doact=sget('do','s');
		$id=sget('id','i',0);
		$this->assign('id',$id);//查寻明细下所有入库动作
		$action=sget('action','s');
		$this->assign('page_title','订单管理列表');
		if($action=='grid'){ //获取列表
			$page = sget("pageIndex",'i',0); //页码
			$size = sget("pageSize",'i',20); //每页数
			$sortField = sget("sortField",'s','input_time'); //排序字段
			$sortOrder = sget("sortOrder",'s','desc'); //排序
			//筛选
			$where = $id >0 ? " `inlog_id` = $id " : " 1 ";
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
			}
			$list=$this->db->model('out_logs')->where($where)
					->page($page+1,$size)
					->order("$sortField $sortOrder")
					->getPage();
			foreach($list['data'] as $k=>$v){
				$list['data'][$k]['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
				$list['data'][$k]['store_name']=M("product:store")->getStoreNameBySid($v['store_id']); //获取仓库名
				$list['data'][$k]['model']=M("product:product")->getModelById($v['p_id']);//获取牌号
				$list['data'][$k]['f_name']=M("product:product")->getFnameByid($v['p_id']);//获取厂家
				$list['data'][$k]['admin_name']=M("product:inStorage")->getNameBySid($v['store_aid']); //获得入库人姓名
			}
			$msg="";
			if($list['count']>0){
				$sum=$this->db->select("sum(number) as wsum")->where($where)->getRow();
				$msg="出库总吨:【".$sum['wsum']."】";
			}
			$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>$msg);
			$this->json_output($result);
		}else{
			$this->display('outLogDetial.list.html');
		}
	}
	/**
	 * 撤销出库详细流水
	 */
	public function outstoreBack(){
		$this->is_ajax=true; //指定为Ajax输出
		$ids=sget('ids','s');
		if(empty($ids)){
			$this->error('操作有误');
		}
		$vas = explode(',', $ids);
		$this->db->startTrans();
		foreach ($vas as $k => $v) {
			//更新明细为失效状态（对出库流水该处理）
			$outlogs = $this->db->model('out_logs')->where("id = $v")->getRow();
			if(empty($outlogs)) $this->error('错误的流水信息');
			$out_storage = $this->db->model('out_storage')->where(" id =  {$outlogs['storage_id']}")->getRow();
			//查询是否有相同的出库头流水
			$exits =  $this->db->model('out_logs')->where("id != $v and `storage_id` = {$outlogs['storage_id']}")->getRow();
			//删除明细
			$this->db->model('out_logs')->where("id = $v")->delete();
			if(empty($exits)) $this->db->model('out_storage')->where("`id` = {$outlogs['storage_id']}")->delete();
			// 查询明细中的数量
			$outnum = $this->db->model('out_log')->select("number")->where("id = {$outlogs['outlog_id']}")->getOne();
			if($outnum > $outlogs['number']){
				$this->db->model('out_log')->where("id = {$outlogs['outlog_id']}")->update(array('number'=>'-='.$outlogs['number'],'update_time'=>CORE_TIME,'out_storage_status'=>2));
			}else{
				$this->db->model('out_log')->where("id = {$outlogs['outlog_id']}")->delete();
			}
			// 查询销售订单明细的状态
			$pinfo = $this->db->model('sale_log')->where("id = {$outlogs['sale_id']}")->getRow();
			if($pinfo['number']==($pinfo['remainder']+$outlogs['number'])){
				$in_status = 1;
			}else{
				$in_status = 2;
			}
			//如果订单状态是全部入库则修改入库状态为部分入库（并更剩余未入数量）
			$this->db->model('sale_log')->where("id = {$outlogs['sale_id']}")->update(array('remainder'=>'+='.$outlogs['number'],'out_storage_status'=>$in_status,'update_time'=>CORE_TIME,));
			//接下来判断订单的出库状态并更新(主要判断是否存在同订单的出库信息)
			if($this->db->model('out_logs')->where("`id` != $v and `sale_id` = {$outlogs['sale_id']}")->getRow()){
				$o_status = 2;
			}else{
				$o_status = 1;
			}
			//操作订单
			$this->db->model('order')->where("`o_id` = {$pinfo['o_id']}")->update(array('out_storage_status'=>$o_status,'update_time'=>CORE_TIME,));
			$oinfo = $this->db->model('order')->where("`o_id` = {$pinfo['o_id']}")->getRow();
			if($oinfo['order_type']==1 && $oinfo['sales_type']==2){
				$backnum = 0;
			}else{
				$backnum = $outlogs['number'];
			}
			// 据in_logs获取in_log相关信息（返还in_log表）
			$inlogs = $this->db->model('in_logs')->where("id = {$outlogs['inlogs_id']}")->getRow();
			$this->db->model('in_log')->where("id = {$inlogs['inlog_id']}")->update(array('remainder'=>'+='.$outlogs['number'],'lock_number'=>'+='.$outlogs['number'],));
			//返还入库流水中的可用数量
			if($outlogs['inlogs_id'] > 0) $this->db->model('in_logs')->where("id = {$outlogs['inlogs_id']}")->update(array('remainder'=>'+='.$outlogs['number'],));
			//把仓库的商品删除
			$this->db->model('store_product')->where("`s_id`={$outlogs['store_id']} and `p_id` = {$outlogs['p_id']}")->update(array('number'=>'+='.$backnum,'remainder'=>'+='.$outlogs['number'],));
			//循环回撤可视化
			if($this->db->model('order_flow')->where("`o_id` =  '{$pinfo['o_id']}' and `type` =  1 and `step` = 0")->getRow()){
				$_key='getLog_'.$pinfo['o_id'].'_1_0';
				$this->cache->delete($_key); //删除缓存
				$this->db->model('order_flow')->where("`o_id` = '{$pinfo['o_id']}' and `type` =  1 and `step` = 0")->delete();
			}
		}
		if($this->db->commit()){
			$this->success('撤销成功');
		}else{
			$this->db->rollback();
			$this->error('撤销失败');
		}
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
		$list = $this->db->where("id in ($ids)")->update(array('del'=>1));
		$this->success('操作成功');
	}
	/**
	 * 保存发货信息
	 */
	public function shipSave(){
		$this->is_ajax = true;
		$data = sdata();
		$id = $data['id'];
		if(empty($id)) $this->error('操作有误！');
		$data['ship_time'] = strtotime($data['ship_time']);
		$result = $this->db->where("id = $id")->update($data+array('update_time'=>CORE_TIME, 'update_admin'=>$_SESSION['name'],));
		if($result) $this->success('操作成功！');
		$this->error('操作失败');
	}
	/**
	 * [receivecheck description]
	 * @Author   xianghui
	 * @DateTime 2017-01-06T15:46:58+0800
	 * @return   [type]                   [description]
	 * 保存是否收货状态
	 */
	public function  receivecheck(){
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //获取UI传递的参数
		if(empty($data)) $this->error('错误的操作');
		$_data=array(
			'update_time'=>CORE_TIME,
			'update_admin'=>$_SESSION['name'],
		);
		try {
			if( !$this->db->where('id = '.$data['id'])->update($data+$_data) ) throw new Exception("收货记录更新失败");
		} catch (Exception $e) {
			$this->error($e->getMessage());
		}
		//添加订单可视化订单审不通过
		M('order:orderLog')->addLog($data['oid'],1,1,CORE_TIME-intval($this->db->model('order_flow')->select('input_time')->where("o_id = {$data['oid']} and type = 1 and step = 0")->getOne()));
		$this->success('操作成功');
	}
}