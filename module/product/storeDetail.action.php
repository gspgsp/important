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
		$roleid = M('rbac:rbac')->model('adm_role_user')->select('role_id')->where("`user_id` = {$_SESSION['adminid']}")->getOne();
		$exits  = (in_array($roleid, array('24','25')) || $_SESSION['adminid'] == 1 ) ? '1' : '0';
		$this->assign('exits',$exits);
		$this->assign('pid',sget('id','i',0));
		$this->assign('doact',$doact);
		$this->assign('company_account',L('company_account')); //交易公司账户
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
		$sortField = sget("sortField",'s','il.input_time'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
		//筛选
		$isAddSale=sget("isAddSale",'i',0); //虚拟入库的库存屏蔽
		$where.= $isAddSale==1? " il.`join_id` = 0" : " 1 "  ;//虚拟入库的库存屏蔽
		//筛选商品
		$pid = sget('pid','i',0);
		if($pid>0) $where .= "  and il.`p_id` =  $pid and il.`remainder` > 0 ";
		//是否可售
		$controlled_number = sget('controlled_number','i',0);
		if($controlled_number==1) $where .= "  and (il.`remainder` -  il.`lock_number`) > 0 ";
		//筛选状态
		if(sget('remainder','i') ==2 ){ //join_id 代表关联了销售订单id  (虚拟入库的库存)
			$where.=" and il.`join_id` > 0";
		}
		//抬头
		$company = sget('company_account','i',0);
		if( $company != 0 ) $where .=" and o.`order_name` =  $company ";
		//筛选订单是不是开票
		$pay_status = sget('pay_status','i',0);
		if($pay_status > 0){
			$where.=" and il.`pay_status`  =  $pay_status ";
		}
		if($isAddSale==1) $where.=" and il.`controlled_number` > 0 "; //销库存时之显示可用数量大于0的产品
		//筛选时间
		$sTime = sget("sTime",'s','input_time'); //搜索时间类型
		$where.=getTimeFilter($sTime); //时间筛选
		//关键词搜索
		$key_type=sget('key_type','s','store_id');
		$keyword=sget('keyword','s');
		if(!empty($keyword) && $key_type=='store_id'  ){
			$keyword=M('product:store')->getSidBySname($keyword);
			$where.=" and il.`$key_type` in ('$keyword') ";
		}elseif(!empty($keyword) && $key_type=='store_aid'){
			$keyword=M('product:store_admin')->getSaidByName($keyword);
			$where.=" and il.`$key_type` in ('$keyword') ";
		}elseif(!empty($keyword) && $key_type=='p_id' ){
			$keyword=M('product:product')->getpidByPname($keyword);
			$where.=" and il.`$key_type` in ($keyword) ";
		}elseif(!empty($keyword) && $key_type=='order_sn' ){
			$keyword=M('product:order')->getIdsBySn($keyword);
			$where.=" and il.`o_id` in ($keyword) ";
		}elseif(!empty($keyword) && $key_type=='customer_manager' ){
			$admin_id = M('rbac:adm')->getIdByName($keyword);
			//根据id去查订单
			$oids = M('product:order')->getIdsByAId(join($admin_id,','));
			$where.=" and il.`o_id` in ($oids) ";
		}elseif(!empty($keyword)){
			$where.=" and il.`$key_type`  like '%$keyword%' ";
		}
		$list=$this->db->select('il.*')->from('in_log il')->leftjoin('order o','o.o_id = il.o_id')->where($where)
				->page($page+1,$size)
				->order("$sortField $sortOrder")
				->getPage();
		foreach($list['data'] as $k=>$v){
			$list['data'][$k]['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
			$list['data'][$k]['update_time']=$v['update_time']>1000 ? date("Y-m-d H:i:s",$v['update_time']) : '-';
			$order_name = M("product:order")->getColByOid($v['o_id'],'order_name');
			$list['data'][$k]['order_name'] = L('company_account')[$order_name];
			$list['data'][$k]['order_sn']=M("product:order")->getColByOid($v['o_id'],'order_sn');
			$list['data'][$k]['business_model']= M("product:order")->getColByOid($v['o_id'],'business_model') == 1 ? '利润':'撮合';
			$list['data'][$k]['store_name']=M("product:store")->getStoreNameBySid($v['store_id']); //获取仓库名
			$list['data'][$k]['model']=strtoupper(M("product:product")->getModelById($v['p_id']));//获取牌号
			$list['data'][$k]['f_name']=M("product:product")->getFnameByid($v['p_id']);
			$list['data'][$k]['unit_price'] =  (M("product:order")->getColByOid($v['p_id'],'customer_manager') == $_SESSION['adminid'] OR in_array($_SESSION['adminid'],array(1,726,10,11))) ? $v['unit_price'] : '***';//只有李总超管饶卫平赵飞可以看到
			$list['data'][$k]['admin_name']=M("product:inStorage")->getNameBySid($v['store_aid']); //获得入库人姓名
			$list['data'][$k]['customer_manager']=M("product:order")->getNameBySid($v['o_id']); //获得交易员姓名
			$list['data'][$k]['cname']=M("product:order")->getCnameByOid($v['o_id']); //获得客户姓名
			$list['data'][$k]['controlled_number'] = $v['remainder']-$v['lock_number'];
			$list['data'][$k]['collection'] = M("product:order")->getCollection($v['o_id']);
			$list['data'][$k]['price_s'] = M('product:factory')->getNeighborSprice($v['p_id']);
			$list['data'][$k]['order_name_id']= $order_name;
		}
		$msg="";
		if($list['count']>0){
			// 计算付款金额
			$log=$this->db->select('il.*')->from('in_log il')->leftjoin('order o','o.o_id = il.o_id')->where($where)->order("$sortField $sortOrder")->getAll();
			foreach ($log as $value) {
				$pay += M("product:order")->getCollection($value['o_id']);
			}
			$sum=$this->db->select('il.*')->from('in_log il')->leftjoin('order o','o.o_id = il.o_id')->select("sum(remainder) as wsum, sum(number) as usum, sum(lock_number) as lnumber")->where($where)->getRow();
			$msg="剩余总吨:【".$sum['wsum']."】进货数：【".$sum['usum']."】锁定数：【".$sum['lnumber']."】可售数：【".($sum['wsum']-$sum['lnumber'])."】当前页付款总额：".$pay;
		}
		$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>$msg);
		$this->json_output($result);
	}
	/**
	 * 入库详细流水
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
			$where = $id >0 ? " `inlog_id` = $id " : " 1 and status = 1";
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
			$list=$this->db->model('in_logs')->where($where)
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
				$sum=$this->db->select("sum(remainder) as wsum")->where($where)->getRow();
				$msg="剩余总吨:【".$sum['wsum']."】";
			}
			$result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>$msg);
			$this->json_output($result);
		}else{
			$this->display('inLogDetial.list.html');
		}
	}
	/**
	 * 撤销入库详细流水
	 */
	public function instoreBack(){
		$this->is_ajax=true; //指定为Ajax输出
		$ids=sget('ids','s');
		if(empty($ids)){
			$this->error('操作有误');
		}
		$vas = explode(',', $ids);
		$this->db->startTrans();
		foreach ($vas as $k => $v) {
			//更新明细为失效状态（采购未销售情况下的撤销）
			$inlogs = $this->db->model('in_logs')->where("id = $v")->getRow();
			if(empty($inlogs)) $this->error('错误的流水信息');
			//如果说暂时没有出库
			if($inlogs['number'] == $inlogs['remainder']){
				//入库明细的明细
				$this->db->model('in_logs')->where("id = $v")->delete();
				if(!$this->db->model('in_logs')->where("id != $v and storage_id = {$inlogs['storage_id']}")->getRow()){
					//入库明细头（出库头）
					$this->db->model('in_storage')->where("id = {$inlogs['storage_id']}")->delete();
				}
				//接下来判断订单的入库状态并更新(主要判断是否存在同订单的入库信息)
				if(!empty($inlogs)){
					$o_id = $this->db->model('in_log')->select("o_id")->where("`id` = {$inlogs['inlog_id']}")->getOne();//反查出o_id
					$p_ids = $this->db->model('in_log')->select("p_id")->where("o_id = $o_id")->getCol();//根据oid查出该订单对应的oid
					$in_logs_list = $this->db->model('in_logs')->where("`p_id` in (".join(",",$p_ids).")")->getAll();
					if(!empty($in_logs_list)){
						$o_status = 2;
					}else{
						$o_status = 1;
					}
					$this->db->model('order')->where("`o_id` = $o_id")->update(array('in_storage_status'=>$o_status,'update_time'=>CORE_TIME,));
				}
				//更新入库明细
				$innum = $this->db->model('in_log')->select('number')->where("id = {$inlogs['inlog_id']}")->getOne();
				if($innum>$inlogs['number']){
					$this->db->model('in_log')->where("id = {$inlogs['inlog_id']}")->update(array('remainder'=>'-='.$inlogs['number'],'controlled_number'=>'-='.$inlogs['number'],'number'=>'-='.$inlogs['number'],'update_time'=>CORE_TIME,));
				}else{
					$this->db->model('in_log')->where("id = {$inlogs['inlog_id']}")->delete();
				}
				// 查询采购订单明细的状态
				$pinfo = $this->db->model('purchase_log')->where("id = {$inlogs['purchase_id']}")->getRow();
				if($pinfo['number']==( $pinfo['remainder']+$inlogs['number'])){
					$in_status = 1;
				}else{
					$in_status = 2;
				}
				//如果订单状态是全部入库则修改入库状态为部分入库（并更剩余未入数量）
				$this->db->model('purchase_log')->where("id = {$inlogs['purchase_id']}")->update(array('remainder'=>'+='.$inlogs['number'],'in_storage_status'=>$in_status,'update_time'=>CORE_TIME,));
				// 取出这个订单的关联销售订单（此次的入库流水）---如果是关联
				$oinfo = $this->db->model('order')->where("`o_id` = {$pinfo['o_id']}")->getRow();
				if($oinfo['order_type']==2 && $oinfo['purchase_type'] == 1){
					if($oinfo['join_id']>0) $this->db->model('order')->where("`o_id` = {$oinfo['join_id']}")->update(array('is_join_in'=>0,'update_time'=>CORE_TIME,));
				}
				//把仓库的商品删除
				$this->db->model('store_product')->where("`s_id`={$inlogs['store_id']} and `p_id` = {$inlogs['p_id']}")->update(array('number'=>'-='.$inlogs['number'],'remainder'=>'-='.$inlogs['number'],));
			}else{
				//查询这个出库的关联订单及入库
				$outs = $this->db->model('out_logs')->where("inlogs_id = '$v'")->getRow();
				if (!empty($outs)) {
					$oid = $this->db->model('sale_log')->select('o_id')->where("id = {$outs['sale_id']}")->getOne();
					//查询订单号
					$sn = $this->db->model('order')->select('order_sn')->where("`o_id` = $oid")->getOne();
					$this->error('该采购已经出库,请先撤销出库记录后在操作,订单SN为'.$sn.',出库id为：'.$outs['id']);
				}
				$this->error('该采购已经出库,请先撤销出库记录后在操作');
			}

		}
		if($this->db->commit()){
			$this->success('撤销成功');
		}else{
			showtrace();
			$this->db->rollback();
			$this->error('撤销失败');
		}
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
		$inlog=sget('inlog','s');
		$remainders=sget('remainders','i');
		if(empty($ids)){
			$this->error('操作有误');
		}
		$list = $this->db->select('p_id,remainder')->where("p_id in ($ids)")->getAll();
		// p($list);
		try {
			if( $this->db->model('sale_log')->where("inlog_id in ($inlog)")->getAll() )  throw new Exception("存在相关销售订单无法删除!");
			if( !$this->db->model('in_log')->where("p_id in ($ids)")->delete() ) throw new Exception("删除库存明细失败");

			foreach ($list as $k => $v) {
				if( $this->db->model('store_product')->where('p_id = '.$v['p_id'])->update('number=number-'.$v['remainder']) ) throw new Exception("仓库货品表数量关联失败");
			}
		} catch (Exception $e) {
			$this->db->rollback();
			$this->error($e->getMessage());
		}
		// $this->db->model('store_product')->where('p_id = '.$v['p_id'])->update('number=number-'.$v['remainder']) ;
		// 	showTrace();

		$this->db->commit();
		$this->success('操作成功');
	}
	/**
	 * 编辑修改的数据
	 * @Author   cuiyinming
	 * @DateTime 2017-02-10T17:48:50+0800
	 * @contract qq:1203116460            cuiyinming@126.com
	 * @return   [type]                   [description]
	 */
	public function saveData(){
		$this->is_ajax=true; //指定为Ajax输出
		$data = sdata(); //获取UI传递的参数
		$sql=array();
		if(empty($data)){
			$this->error('操作数据为空');
		}
		foreach($data as $k=>$v){
			$_data=array(
				'expect_price'=>$v['expect_price'],
				'update_time' =>CORE_TIME,
			);
			$sql[]=$this->db->wherePk($v['id'])->updateSql($_data);
		}
		$result=$this->db->commitTrans($sql);
		if($result){
			$this->success('操作成功');
		}else{
			$this->error('数据处理失败');
		}
	}
}