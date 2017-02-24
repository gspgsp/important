<?php 
/**
 * 订单查询管理
 */
class profitAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->db=M('public:common')->model('sale_log');
		$this->doact = sget('do','s');
		$this->assign('depart',C('depart'));//所属部门
		$this->assign('team',L('team')); //战队名称
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
		$this->display('profit.list.html');
	}
	/**
	 * 导出报表
	 * @access public 
	 * @return html
	 */
	public function download(){
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$roleid = M('rbac:rbac')->model('adm_role_user')->select('role_id')->where("`user_id` = {$_SESSION['adminid']}")->getOne();
		$sortField = sget("sortField",'s','s_input_time'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
		//筛选
		$where .=' where 1 AND profit <> 0 AND s_input_time>0 AND p_input_time>0';
		//战队筛选
		$team=sget('team','s');
		$team_id=sget('team_id','i');
		if(!empty($team_id)){
			if($team == 'sale_team'){
				$where.=" and s_team_id = '$team_id' ";	
			}else{
				$where.=" and p_team_id = '$team_id' ";	
			}
		}
		//筛选时间
		$sTime = sget("sTime",'s'); //搜索时间类型
		if($sTime == 'sale_time'){
			$where.=getTimeFilter('s_input_time'); //时间筛选
		}else{
			$where.=getTimeFilter('p_input_time'); //时间筛选
			$sortField = sget("sortField",'s','p_input_time'); //排序字段
		}
		//关键词搜索
		$skey_type=sget('skey_type','s','s_uname');
		$skeyword=sget('skeyword','s');
		$pkey_type=sget('pkey_type','s','p_uname');
		$pkeyword=sget('pkeyword','s');
		$model=sget('model','s');
		$fname=sget('fname','s');
		if(!empty($skeyword) && $skey_type=='s_uname'  ){
			$where.=" and `s_uname` = '$skeyword'";
		}elseif(!empty($skeyword) && $skey_type=='s_sn'){
			$where.=" and `s_sn` like '%$skeyword%'";
		}elseif(!empty($skeyword) && $skey_type=='s_pname'){
			$where.=" and `s_name`  = '$skeyword' ";
		}
		if(!empty($pkeyword) && $pkey_type=='p_uname'  ){
			$where.=" and `p_uname` = '$pkeyword'";
		}elseif(!empty($pkeyword) && $pkey_type=='p_sn'){
			$where.=" and `p_sn` like '%$pkeyword%'";
		}elseif(!empty($pkeyword) && $pkey_type=='p_pname'){
			$where.=" and `p_name`  = '$pkeyword' ";
		}
		if(!empty($model)){
			$where.=" and `s_model` = '$model'";
		}
		if(!empty($fname)){
			$where.=" and `s_fname` = '$fname'";
		}
		$orderby = " order by $sortField $sortOrder";
		//筛选过滤自己的订单信息
		if($_SESSION['adminid'] != 1 && $_SESSION['adminid'] > 0){
			if(!in_array($roleid, array('30','26','27'))){
				$sons = M('rbac:rbac')->getSons($_SESSION['adminid']);
				$where .= " and (`s_customer_manager` = {$_SESSION['adminid']} or `p_customer_manager` = {$_SESSION['adminid']})  ";
			}
		}
		$list = $this->db->getAll('SELECT * FROM ( SELECT * FROM ( SELECT 	(SELECT s_cus.`c_name` FROM `p2p_customer` s_cus WHERE (SELECT o2.`c_id` FROM `p2p_order` o2 WHERE o2.o_id=o.o_id)=s_cus.c_id) AS s_name,
			(SELECT o2.`order_name` FROM `p2p_order` o2 WHERE o2.o_id=o.o_id) AS s_ordname,
			o.`order_sn` AS s_sn,
			pro.`model` AS s_model,
			fac.`f_name` AS s_fname,
			sale.o_id AS s_oid,
			sale.p_id AS s_pid,
			sale.number AS s_num,
			sale.remainder AS s_rem,
			sale.unit_price AS s_price,
			(sale.number * sale.unit_price) AS s_xj,
			o.`payd_time` AS s_input_time,
			sale.customer_manager AS s_customer_manager,
			(SELECT admin.`name` FROM `p2p_admin` admin WHERE sale.customer_manager=admin.admin_id) AS s_uname,
			(SELECT role.`role_id` FROM `p2p_adm_role_user` role WHERE sale.customer_manager=role.user_id AND role.role_id IN (34,35,36,37,38,40,41,42,46,49)) AS s_team_id,
			IFNULL(`out`.ship,0) AS ship,
		    (sale.number * (sale.unit_price - pu.unit_price))- IFNULL(out.ship,0) AS profit,
			o.`join_id` AS p_oid,
			(SELECT s_cus.`c_name` FROM `p2p_customer` s_cus WHERE (SELECT o2.`c_id` FROM `p2p_order` o2 WHERE o2.o_id=o.join_id)=c_id) AS p_name,
			(SELECT o2.`order_name` FROM `p2p_order` o2 WHERE o2.o_id=o.join_id) AS p_ordname,
			(SELECT o2.order_sn FROM `p2p_order` o2 WHERE o2.o_id=o.join_id) AS p_sn,
			pu.p_id AS p_pid,
			pu.number AS p_num,
			pu.remainder AS p_rem,
			pu.unit_price AS p_price,
			(pu.number * pu.unit_price) AS p_xj,
			(SELECT o2.`payd_time` FROM `p2p_order` o2 WHERE o2.o_id=o.join_id) AS p_input_time,
			pu.customer_manager AS p_customer_manager,
			(SELECT admin.`name` FROM `p2p_admin` admin WHERE pu.customer_manager=admin.admin_id) AS p_uname,
			(SELECT role.`role_id` FROM `p2p_adm_role_user` role WHERE pu.customer_manager=role.user_id AND role.role_id IN (34,35,36,37,38,40,41,42,46,49)) AS p_team_id
		FROM `p2p_sale_log` AS sale 
		LEFT JOIN `p2p_order` AS o ON sale.`o_id` = o.`o_id`
		LEFT JOIN `p2p_purchase_log` AS pu ON o.`join_id` = pu.`o_id` AND sale.`p_id` = pu.`p_id` AND sale.`purchase_id` = pu.`id`
		LEFT JOIN `p2p_product` AS pro ON sale.`p_id` = pro.`id`
		LEFT JOIN `p2p_factory` AS fac ON pro.`f_id` = fac.`fid`
		LEFT JOIN `p2p_out_log` AS `out` ON sale.`id` = `out`.`sale_log_id`
		WHERE sale.p_id >0 AND o.`join_id` > 0 AND o.`order_status`=2 AND o.`transport_status`=2 AND o.`collection_status` = 3 AND o.payd_time >0) aa
		UNION 
		SELECT * FROM ( SELECT 	(SELECT s_cus.`c_name` FROM `p2p_customer` s_cus WHERE (SELECT o2.`c_id` FROM `p2p_order` o2 WHERE o2.o_id=o.o_id)=s_cus.c_id) AS s_name,
			(SELECT o2.`order_name` FROM `p2p_order` o2 WHERE o2.o_id=o.o_id) AS s_ordname,
			o.`order_sn` AS s_sn,
			pro.`model` AS s_model,
			fac.`f_name` AS s_fname,
			sale.o_id AS s_oid,
			sale.p_id AS s_pid,
			sale.number AS s_num,
			sale.remainder AS s_rem,
			sale.unit_price AS s_price,
			(sale.number * sale.unit_price) AS s_xj,
			o.`payd_time` AS s_input_time,
			sale.customer_manager AS s_customer_manager,
			(SELECT admin.`name` FROM `p2p_admin` admin WHERE sale.customer_manager=admin.admin_id) AS s_uname,
			(SELECT role.`role_id` FROM `p2p_adm_role_user` role WHERE sale.customer_manager=role.user_id AND role.role_id IN (34,35,36,37,38,40,41,42,46,49)) AS s_team_id,
			IFNULL(`out`.ship,0) AS ship,
		    (sale.number * (sale.unit_price - pu.unit_price))- IFNULL(out.ship,0) AS profit,
			o.`store_o_id` AS p_oid,
			(SELECT s_cus.`c_name` FROM `p2p_customer` s_cus WHERE (SELECT o2.`c_id` FROM `p2p_order` o2 WHERE o2.o_id=o.store_o_id)=c_id) AS p_name,
			(SELECT o2.`order_name` FROM `p2p_order` o2 WHERE o2.o_id=o.store_o_id) AS p_ordname,
			(SELECT o2.order_sn FROM `p2p_order` o2 WHERE o2.o_id=o.store_o_id) AS p_sn,
			pu.p_id AS p_pid,
			pu.number AS p_num,
			pu.remainder AS p_rem,
			pu.unit_price AS p_price,
			(pu.number * pu.unit_price) AS p_xj,
			(SELECT o2.payd_time FROM `p2p_order` o2 WHERE o2.o_id=o.store_o_id) AS p_input_time,
			pu.customer_manager AS p_customer_manager,
			(SELECT admin.`name` FROM `p2p_admin` admin WHERE pu.customer_manager=admin.admin_id) AS p_uname,
			(SELECT role.`role_id` FROM `p2p_adm_role_user` role WHERE pu.customer_manager=role.user_id AND role.role_id IN (34,35,36,37,38,40,41,42,46,49)) AS p_team_id
		FROM `p2p_sale_log` AS sale 
		LEFT JOIN `p2p_order` AS o ON sale.`o_id` = o.`o_id`
		LEFT JOIN `p2p_purchase_log` AS pu ON o.`store_o_id` = pu.`o_id` AND sale.`p_id` = pu.`p_id`  AND sale.`purchase_id` = pu.`id`
		LEFT JOIN `p2p_product` AS pro ON sale.`p_id` = pro.`id`
		LEFT JOIN `p2p_factory` AS fac ON pro.`f_id` = fac.`fid`
		LEFT JOIN `p2p_out_log` AS `out` ON sale.`id` = `out`.`sale_log_id`
		WHERE sale.p_id >0 AND o.`store_o_id` > 0 AND o.`order_status`=2 AND o.`transport_status`=2 AND o.`collection_status` = 3 AND o.payd_time >0) bb ) AS cc '.$where.$orderby);
	// showtrace();
		foreach($list as &$value){
			$value['s_input_time']=$value['s_input_time']>1000 ? date("Y-m-d H:i:s",$value['s_input_time']) : '-';
			$value['p_input_time']=$value['p_input_time']>1000 ? date("Y-m-d H:i:s",$value['p_input_time']) : '-';
			$value['s_ordname']=L('company_account')[$value['s_ordname']];
			
			if($_SESSION['adminid'] != 1 && !in_array($roleid, array('30','26','27'))){
				 $sons_arr = explode(',', $sons);
				if($value['s_customer_manager'] != $_SESSION['adminid'] && !in_array($value['s_customer_manager'],$sons_arr)){
					$value['s_name'] = '******';
				}
				if($value['p_customer_manager'] != $_SESSION['adminid'] && !in_array($value['p_customer_manager'],$sons_arr)){
					$value['p_name'] = '******';
				}
			}
		}
		$str = '<meta http-equiv="Content-Type" content="text/html; charset=utf8" /><table width="100%" border="1" cellspacing="0">';
			$str .= '<tr><td>销售单号</td><td>客户</td><td>抬头</td><td>牌号</td>
						<td>厂家</td><td>数量</td><td>未发数量</td><td>单价</td>
						<td>小计</td><td>销售员</td><td>销售时间</td><td>运费</td>
						<td>毛利润</td><td>采购单号</td><td>供应商</td><td>数量</td>
						<td>未入数量</td><td>单价</td><td>小计</td><td>采购员</td>
						<td>采购时间</td>
					</tr>';
			foreach($list as $k=>$v){
				$str .= "<tr><td style='vnd.ms-excel.numberformat:@'>".$v['s_sn']."</td><td>".$v['s_name']."</td>
				<td>".$v['s_ordname']."</td><td>".$v['s_model']."</td><td>".$v['s_fname']."</td><td>".$v['s_num']."</td>
				<td>".$v['s_rem']."</td><td>".$v['s_price']."</td><td>".$v['s_xj']."</td><td>".$v['s_uname']."</td>
				<td style='vnd.ms-excel.numberformat:@'>".$v['s_input_time']."</td><td>".$v['ship']."</td><td>".$v['profit']."</td>
				<td style='vnd.ms-excel.numberformat:@'>".$v['p_sn']."</td><td>".$v['p_name']."</td><td>".$v['p_num']."</td>
				<td>".$v['p_rem']."</td><td>".$v['p_price']."</td><td>".$v['p_xj']."</td><td>".$v['p_uname']."</td>
				<td style='vnd.ms-excel.numberformat:@'>".$v['p_input_time']."</td>
				</tr>";
			}
		$str .= '</table>';
		$filename = 'profit.'.date("Y-m-d");
		header("Content-type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment; filename=$filename.xls");
		echo $str;
		exit;
	}
	/**
	 * Ajax获取列表内容
	 */
	public function _grid(){
		$page = sget("pageIndex",'i',0); //页码
		$size = sget("pageSize",'i',20); //每页数
		$roleid = M('rbac:rbac')->model('adm_role_user')->select('role_id')->where("`user_id` = {$_SESSION['adminid']}")->getOne();
		$sortField = sget("sortField",'s','s_input_time'); //排序字段
		$sortOrder = sget("sortOrder",'s','desc'); //排序
		//筛选
		$where .=' where 1 and profit <> 0 AND s_input_time>0 AND p_input_time>0';
		//战队筛选
		$team=sget('team','s');
		$team_id=sget('team_id','i');
		if(!empty($team_id)){
			if($team == 'sale_team'){
				$where.=" and s_team_id = '$team_id' ";	
			}else{
				$where.=" and p_team_id = '$team_id' ";	
			}
		}
		//筛选时间
		$sTime = sget("sTime",'s'); //搜索时间类型
		if($sTime == 'sale_time'){
			$where.=getTimeFilter('s_input_time'); //时间筛选
		}else{
			$where.=getTimeFilter('p_input_time'); //时间筛选
			$sortField = sget("sortField",'s','p_input_time'); //排序字段
		}
		//关键词搜索
		$skey_type=sget('skey_type','s','s_uname');
		$skeyword=sget('skeyword','s');
		$pkey_type=sget('pkey_type','s','p_uname');
		$pkeyword=sget('pkeyword','s');
		$model=sget('model','s');
		$fname=sget('fname','s');
		if(!empty($skeyword) && $skey_type=='s_uname'  ){
			$where.=" and `s_uname` = '$skeyword'";
		}elseif(!empty($skeyword) && $skey_type=='s_sn'){
			$where.=" and `s_sn` like '%$skeyword%'";
		}elseif(!empty($skeyword) && $skey_type=='s_pname'){
			$where.=" and `s_name`  = '$skeyword' ";
		}
		if(!empty($pkeyword) && $pkey_type=='p_uname'  ){
			$where.=" and `p_uname` = '$pkeyword'";
		}elseif(!empty($pkeyword) && $pkey_type=='p_sn'){
			$where.=" and `p_sn` like '%$pkeyword%'";
		}elseif(!empty($pkeyword) && $pkey_type=='p_pname'){
			$where.=" and `p_name`  = '$pkeyword' ";
		}
		if(!empty($model)){
			$where.=" and `s_model` = '$model'";
		}
		if(!empty($fname)){
			$where.=" and `s_fname` = '$fname'";
		}
		$orderby = " order by $sortField $sortOrder";
		//筛选过滤自己的订单信息
		if($_SESSION['adminid'] != 1 && $_SESSION['adminid'] > 0 && $_SESSION['adminid'] != 10){
			if(!in_array($roleid, array('30','26','27'))){
				$sons = M('rbac:rbac')->getSons($_SESSION['adminid']);
				$where .= " and (`s_customer_manager` in ($sons) or `p_customer_manager` = {$_SESSION['adminid']})  ";
			}
		}
		// p($where);die;
		// --交易员所在战队写死，如有新增战队或者删除战队，就要修改这个in(里面的id)
	$list = $this->db->getAll('SELECT * FROM ( SELECT * FROM ( SELECT 	(SELECT s_cus.`c_name` FROM `p2p_customer` s_cus WHERE (SELECT o2.`c_id` FROM `p2p_order` o2 WHERE o2.o_id=o.o_id)=s_cus.c_id) AS s_name,
			(SELECT o2.`order_name` FROM `p2p_order` o2 WHERE o2.o_id=o.o_id) AS s_ordname,
			o.`order_sn` AS s_sn,
			pro.`model` AS s_model,
			fac.`f_name` AS s_fname,
			sale.o_id AS s_oid,
			sale.p_id AS s_pid,
			sale.number AS s_num,
			sale.remainder AS s_rem,
			sale.unit_price AS s_price,
			(sale.number * sale.unit_price) AS s_xj,
			o.`payd_time` AS s_input_time,
			sale.customer_manager AS s_customer_manager,
			(SELECT admin.`name` FROM `p2p_admin` admin WHERE sale.customer_manager=admin.admin_id) AS s_uname,
			(SELECT role.`role_id` FROM `p2p_adm_role_user` role WHERE sale.customer_manager=role.user_id AND role.role_id IN (34,35,36,37,38,40,41,42,46,49)) AS s_team_id,
			IFNULL(`out`.ship,0) AS ship,
		    (sale.number * (sale.unit_price - pu.unit_price))- IFNULL(out.ship,0) AS profit,
			o.`join_id` AS p_oid,
			(SELECT s_cus.`c_name` FROM `p2p_customer` s_cus WHERE (SELECT o2.`c_id` FROM `p2p_order` o2 WHERE o2.o_id=o.join_id)=c_id) AS p_name,
			(SELECT o2.`order_name` FROM `p2p_order` o2 WHERE o2.o_id=o.join_id) AS p_ordname,
			(SELECT o2.order_sn FROM `p2p_order` o2 WHERE o2.o_id=o.join_id) AS p_sn,
			pu.p_id AS p_pid,
			pu.number AS p_num,
			pu.remainder AS p_rem,
			pu.unit_price AS p_price,
			(pu.number * pu.unit_price) AS p_xj,
			(SELECT o2.`payd_time` FROM `p2p_order` o2 WHERE o2.o_id=o.join_id) AS p_input_time,
			pu.customer_manager AS p_customer_manager,
			(SELECT admin.`name` FROM `p2p_admin` admin WHERE pu.customer_manager=admin.admin_id) AS p_uname,
			(SELECT role.`role_id` FROM `p2p_adm_role_user` role WHERE pu.customer_manager=role.user_id AND role.role_id IN (34,35,36,37,38,40,41,42,46,49)) AS p_team_id
		FROM `p2p_sale_log` AS sale 
		LEFT JOIN `p2p_order` AS o ON sale.`o_id` = o.`o_id`
		LEFT JOIN `p2p_purchase_log` AS pu ON o.`join_id` = pu.`o_id` AND sale.`p_id` = pu.`p_id` AND sale.`purchase_id` = pu.`id`
		LEFT JOIN `p2p_product` AS pro ON sale.`p_id` = pro.`id`
		LEFT JOIN `p2p_factory` AS fac ON pro.`f_id` = fac.`fid`
		LEFT JOIN `p2p_out_log` AS `out` ON sale.`id` = `out`.`sale_log_id`
		WHERE sale.p_id >0 AND o.`join_id` > 0 AND o.`order_status`=2 AND o.`transport_status`=2 AND o.`collection_status` = 3 AND o.payd_time >0) aa
		UNION 
		SELECT * FROM ( SELECT 	(SELECT s_cus.`c_name` FROM `p2p_customer` s_cus WHERE (SELECT o2.`c_id` FROM `p2p_order` o2 WHERE o2.o_id=o.o_id)=s_cus.c_id) AS s_name,
			(SELECT o2.`order_name` FROM `p2p_order` o2 WHERE o2.o_id=o.o_id) AS s_ordname,
			o.`order_sn` AS s_sn,
			pro.`model` AS s_model,
			fac.`f_name` AS s_fname,
			sale.o_id AS s_oid,
			sale.p_id AS s_pid,
			sale.number AS s_num,
			sale.remainder AS s_rem,
			sale.unit_price AS s_price,
			(sale.number * sale.unit_price) AS s_xj,
			o.`payd_time` AS s_input_time,
			sale.customer_manager AS s_customer_manager,
			(SELECT admin.`name` FROM `p2p_admin` admin WHERE sale.customer_manager=admin.admin_id) AS s_uname,
			(SELECT role.`role_id` FROM `p2p_adm_role_user` role WHERE sale.customer_manager=role.user_id AND role.role_id IN (34,35,36,37,38,40,41,42,46,49)) AS s_team_id,
			IFNULL(`out`.ship,0) AS ship,
		    (sale.number * (sale.unit_price - pu.unit_price))- IFNULL(out.ship,0) AS profit,
			o.`store_o_id` AS p_oid,
			(SELECT s_cus.`c_name` FROM `p2p_customer` s_cus WHERE (SELECT o2.`c_id` FROM `p2p_order` o2 WHERE o2.o_id=o.store_o_id)=c_id) AS p_name,
			(SELECT o2.`order_name` FROM `p2p_order` o2 WHERE o2.o_id=o.store_o_id) AS p_ordname,
			(SELECT o2.order_sn FROM `p2p_order` o2 WHERE o2.o_id=o.store_o_id) AS p_sn,
			pu.p_id AS p_pid,
			pu.number AS p_num,
			pu.remainder AS p_rem,
			pu.unit_price AS p_price,
			(pu.number * pu.unit_price) AS p_xj,
			(SELECT o2.payd_time FROM `p2p_order` o2 WHERE o2.o_id=o.store_o_id) AS p_input_time,
			pu.customer_manager AS p_customer_manager,
			(SELECT admin.`name` FROM `p2p_admin` admin WHERE pu.customer_manager=admin.admin_id) AS p_uname,
			(SELECT role.`role_id` FROM `p2p_adm_role_user` role WHERE pu.customer_manager=role.user_id AND role.role_id IN (34,35,36,37,38,40,41,42,46,49)) AS p_team_id
		FROM `p2p_sale_log` AS sale 
		LEFT JOIN `p2p_order` AS o ON sale.`o_id` = o.`o_id`
		LEFT JOIN `p2p_purchase_log` AS pu ON o.`store_o_id` = pu.`o_id` AND sale.`p_id` = pu.`p_id`  AND sale.`purchase_id` = pu.`id`
		LEFT JOIN `p2p_product` AS pro ON sale.`p_id` = pro.`id`
		LEFT JOIN `p2p_factory` AS fac ON pro.`f_id` = fac.`fid`
		LEFT JOIN `p2p_out_log` AS `out` ON sale.`id` = `out`.`sale_log_id`
		WHERE sale.p_id >0 AND o.`store_o_id` > 0 AND o.`order_status`=2 AND o.`transport_status`=2 AND o.`collection_status` = 3 AND o.payd_time >0) bb ) AS cc  '.$where.$orderby.' limit '.($page)*$size.','.$size);
	// showtrace();
	$list_count = $this->db->getRow('SELECT count(*) as total,sum(s_xj) as s_xj,sum(s_num) as s_num,sum(profit) as profit,sum(ship) as ship,sum(p_num) as p_num,sum(p_xj) as p_xj from ( SELECT * from ( SELECT 	(SELECT s_cus.`c_name` FROM `p2p_customer` s_cus WHERE (SELECT o2.`c_id` FROM `p2p_order` o2 WHERE o2.o_id=o.o_id)=s_cus.c_id) AS s_name,
			(SELECT o2.`order_name` FROM `p2p_order` o2 WHERE o2.o_id=o.o_id) AS s_ordname,
			o.`order_sn` AS s_sn,
			pro.`model` AS s_model,
			fac.`f_name` AS s_fname,
			sale.o_id AS s_oid,
			sale.p_id AS s_pid,
			sale.number AS s_num,
			sale.remainder AS s_rem,
			sale.unit_price AS s_price,
			(sale.number * sale.unit_price) AS s_xj,
			o.`payd_time` AS s_input_time,
			sale.customer_manager AS s_customer_manager,
			(SELECT admin.`name` FROM `p2p_admin` admin WHERE sale.customer_manager=admin.admin_id) AS s_uname,
			(SELECT role.`role_id` FROM `p2p_adm_role_user` role WHERE sale.customer_manager=role.user_id and role.role_id in (34,35,36,37,38,40,41,42,46,49)) AS s_team_id,
			ifnull(`out`.ship,0) AS ship,
		    (sale.number * (sale.unit_price - pu.unit_price))- ifnull(out.ship,0) AS profit,
			o.`join_id` AS p_oid,
			(SELECT s_cus.`c_name` FROM `p2p_customer` s_cus WHERE (SELECT o2.`c_id` FROM `p2p_order` o2 WHERE o2.o_id=o.join_id)=c_id) AS p_name,
			(SELECT o2.`order_name` FROM `p2p_order` o2 WHERE o2.o_id=o.join_id) AS p_ordname,
			(SELECT o2.order_sn FROM `p2p_order` o2 WHERE o2.o_id=o.join_id) AS p_sn,
			pu.p_id AS p_pid,
			pu.number AS p_num,
			pu.remainder AS p_rem,
			pu.unit_price AS p_price,
			(pu.number * pu.unit_price) AS p_xj,
			(SELECT o2.`payd_time` FROM `p2p_order` o2 WHERE o2.o_id=o.join_id) AS p_input_time,
			pu.customer_manager AS p_customer_manager,
			(SELECT admin.`name` FROM `p2p_admin` admin WHERE pu.customer_manager=admin.admin_id) AS p_uname,
			(SELECT role.`role_id` FROM `p2p_adm_role_user` role WHERE pu.customer_manager=role.user_id and role.role_id in (34,35,36,37,38,40,41,42,46,49)) AS p_team_id
		FROM `p2p_sale_log` AS sale 
		LEFT JOIN `p2p_order` AS o ON sale.`o_id` = o.`o_id`
		LEFT JOIN `p2p_purchase_log` AS pu ON o.`join_id` = pu.`o_id` and sale.`p_id` = pu.`p_id` and sale.`purchase_id` = pu.`id`
		LEFT JOIN `p2p_product` AS pro ON sale.`p_id` = pro.`id`
		LEFT JOIN `p2p_factory` AS fac ON pro.`f_id` = fac.`fid`
		LEFT JOIN `p2p_out_log` AS `out` ON sale.`id` = `out`.`sale_log_id`
		WHERE sale.p_id >0 AND o.`join_id` > 0 and o.`order_status`=2 and o.`transport_status`=2 AND o.`collection_status` = 3 AND o.payd_time >0) aa
		UNION 
		SELECT * from ( SELECT 	(SELECT s_cus.`c_name` FROM `p2p_customer` s_cus WHERE (SELECT o2.`c_id` FROM `p2p_order` o2 WHERE o2.o_id=o.o_id)=s_cus.c_id) AS s_name,
			(SELECT o2.`order_name` FROM `p2p_order` o2 WHERE o2.o_id=o.o_id) AS s_ordname,
			o.`order_sn` AS s_sn,
			pro.`model` AS s_model,
			fac.`f_name` AS s_fname,
			sale.o_id AS s_oid,
			sale.p_id AS s_pid,
			sale.number AS s_num,
			sale.remainder AS s_rem,
			sale.unit_price AS s_price,
			(sale.number * sale.unit_price) AS s_xj,
			o.`payd_time` AS s_input_time,
			sale.customer_manager AS s_customer_manager,
			(SELECT admin.`name` FROM `p2p_admin` admin WHERE sale.customer_manager=admin.admin_id) AS s_uname,
			(SELECT role.`role_id` FROM `p2p_adm_role_user` role WHERE sale.customer_manager=role.user_id and role.role_id in (34,35,36,37,38,40,41,42,46,49)) AS s_team_id,
			ifnull(`out`.ship,0) AS ship,
		    (sale.number * (sale.unit_price - pu.unit_price))- ifnull(out.ship,0) AS profit,
			o.`store_o_id` AS p_oid,
			(SELECT s_cus.`c_name` FROM `p2p_customer` s_cus WHERE (SELECT o2.`c_id` FROM `p2p_order` o2 WHERE o2.o_id=o.store_o_id)=c_id) AS p_name,
			(SELECT o2.`order_name` FROM `p2p_order` o2 WHERE o2.o_id=o.store_o_id) AS p_ordname,
			(SELECT o2.order_sn FROM `p2p_order` o2 WHERE o2.o_id=o.store_o_id) AS p_sn,
			pu.p_id AS p_pid,
			pu.number AS p_num,
			pu.remainder AS p_rem,
			pu.unit_price AS p_price,
			(pu.number * pu.unit_price) AS p_xj,
			(SELECT o2.payd_time FROM `p2p_order` o2 WHERE o2.o_id=o.store_o_id) AS p_input_time,
			pu.customer_manager AS p_customer_manager,
			(SELECT admin.`name` FROM `p2p_admin` admin WHERE pu.customer_manager=admin.admin_id) AS p_uname,
			(SELECT role.`role_id` FROM `p2p_adm_role_user` role WHERE pu.customer_manager=role.user_id and role.role_id in (34,35,36,37,38,40,41,42,46,49)) AS p_team_id
		FROM `p2p_sale_log` AS sale 
		LEFT JOIN `p2p_order` AS o ON sale.`o_id` = o.`o_id`
		LEFT JOIN `p2p_purchase_log` AS pu ON o.`store_o_id` = pu.`o_id` and sale.`p_id` = pu.`p_id`  and sale.`purchase_id` = pu.`id`
		LEFT JOIN `p2p_product` AS pro ON sale.`p_id` = pro.`id`
		LEFT JOIN `p2p_factory` AS fac ON pro.`f_id` = fac.`fid`
		LEFT JOIN `p2p_out_log` AS `out` ON sale.`id` = `out`.`sale_log_id`
		WHERE sale.p_id >0 AND o.`store_o_id` > 0 and o.`order_status`=2 and o.`transport_status`=2 AND o.`collection_status` = 3 AND o.payd_time >0) bb ) as cc '.$where);
		// p($list_count);die;
		foreach($list as &$value){
			$value['s_input_time']=$value['s_input_time']>1000 ? date("Y-m-d H:i:s",$value['s_input_time']) : '-';
			$value['p_input_time']=$value['p_input_time']>1000 ? date("Y-m-d H:i:s",$value['p_input_time']) : '-';
			$value['s_ordname']=L('company_account')[$value['s_ordname']];
			// $depart =  M('rbac:adm')->getPartByID($value['s_customer_manager']);
			// $value['team_name']=$depart['name'];
			// p($value);die;
			if($_SESSION['adminid'] != 1 && !in_array($roleid, array('30','26','27'))){
				 $sons_arr = explode(',', $sons);
				if($value['s_customer_manager'] != $_SESSION['adminid'] && !in_array($value['s_customer_manager'],$sons_arr)){
					$value['s_name'] = '******';
				}
				if($value['p_customer_manager'] != $_SESSION['adminid'] && !in_array($value['p_customer_manager'],$sons_arr)){
					$value['p_name'] = '******';
				}
			}
		}
		$msg="";
		if($list_count>0){
			$maoli = price_format($list_count['profit']) + price_format($list_count['ship']);
			$msg="[筛选结果]销售总金额:【".price_format($list_count['s_xj'])."】销售总吨数:【".$list_count['s_num']."】采购总金额:【".price_format($list_count['p_xj'])."】采购总吨数:【".$list_count['p_num']."】总运费:【".price_format($list_count['ship'])."】净利:【".price_format($list_count['profit'])."】毛利:【".$maoli."】";
		}
		$result=array('total'=>$list_count['total'],'data'=>$list,'msg'=>$msg);
		$this->json_output($result);
	}
}