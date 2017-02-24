<?php
//订单模型
class orderModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'order');
	}
	/**
	 * 更具字段取出对应的值
	 */
	public function getColByName($value=0,$col='order_name',$condition='o_id'){
		$result =  $this->model('order')->select("$col")->where("$condition='$value'")->getOne();
		return empty($result) ? '-' : $result;
	}
	/**
	 * 模糊查询客户名匹配的订单
	 */
	public function getOidByCname($value=''){
		$arr=$this->model('customer')->select('c_id')->where("c_name like '%".$value."%'")->getAll();
		foreach ($arr as $key => $value) {
			$cids[]=$value['c_id'];
		}
		$data = implode(',',$cids);
		return empty($data)? false : $data;
	}
	/*
	  根据客户名称查询相应的订单ID
	 */
	public function getIdByCname($value=''){
		$c_ids=$this->getOidByCname($value);
		$data=$this->model('order')->select('o_id')->where("c_id in (".$c_ids.")")->getAll();
		foreach($data as $v){
			$oId_Arr[]=$v['o_id'];
		}
		$o_id=implode(',', $oId_Arr);
		return empty($o_id)? false : $o_id;
	}
	/**
	 * 模糊查询订单名匹配的明细
	 */
	public function getidByOname($value=''){
		$arr=$this->select('o_id')->where("order_name like '%".$value."%'")->getAll();
		foreach ($arr as $key => $value) {
			$oids[]=$value['o_id'];
		}
		$data = implode(',',$oids);
		return empty($data)? false : $data;
	}

	/**
	 * 根据id查订单明细表的值
	 */
	public function getColByOid($o_id=0,$col='id'){
		$result=$this->model('order')->select("$col")->where("`o_id`=$o_id")->getOne();
		return empty($result) ? '' : $result;
	}

	/**
	 * 根据o_id去查对应的客户名
	 */
	public function getCnameByOid($o_id=0){
		$result = $this->select('c.c_name')->from('order o')->join('customer c' , 'o.c_id = c.c_id')->where(" o_id = $o_id ")->getOne();
		return empty($result) ? '-' : $result ;
	}

	/**
	 * 根据id查订单明细表的值
	 */
	public function getODidByOid($o_id=0,$col='id'){
		$result=$this->model('sale_log')->select("$col")->where("o_id='$o_id'")->getOne();
		return empty($result) ? false : $result;
	}

	/**
	 * 根据字段取出该表所有的值
	 */
	public function getAllByName($value=0,$condition='o_id'){
		$result =  $this->select("*")->where("$condition='$value'")->getAll();
		return empty($result) ? '-' : $result;
	}

	/** (自营)
	 *获取近三个月(待审核)订单信息('待审核 order_status=1，已取消order_status=3，$col =collection_status 代开票 ')
     	*/
	public function getOrder($uid=0,$status = 1, $col ='order_status'){
		$tm = date(strtotime('-90 day'));
		return $this->from('order')->select('COUNT(o_id) AS number')->where("user_id=$uid and input_time > $tm and $col = $status")->getOne();
	}
	/**
	 * 根据订单id获取订单信息
	 */
	public function getOinfoById($oid = 0){
		return $this->where("o_id = $oid")->getRow();
	}
	/**
	 * 根据where 条件查询已收款（销售订单）和已付款（采购id）
	 * @Author   yezhongbao
	 * @DateTime 2016-08-16T14:42:42+0800
	 * @return   [type]                   [description]
	 */
	public function get_collection($where='1'){
		$collection = $this->model('order as o')->select('o_id')->where($where)->getAll();
		foreach ($collection as $key => $value) {
			$str.=$value['o_id'].',';
		}
		$string = trim($str,',');
		$collection_data = $this->model('collection')->select('sum(collected_price) as collection')->where('collection_status=2 and o_id in ('.$string.')')->getOne();
		return $collection_data;
	}
	/**
	 * 根据sn模糊匹配返回的订单id值
	 * @Author   cuiyinming
	 * @DateTime 2016-09-01T09:47:33+0800
	 * @return   [string]                   [1,2,3,4]
	 */
	public function getIdsBySn($sn = ''){
		return join(',',$this->where("`order_sn` like '%$sn%'")->getCol());
	}

	/**
	 * 获取最新采购单
	 * @param string $where
	 * @return mixed
	 * @Author: yuanjiaye
	 */
	public function getPurPage($where='1'){
		return $this->from('order as o')
			->join('purchase_log as pur','o.`o_id`=pur.o_id')
			->join('product as pro','pro.id=pur.`p_id`')
			->join('factory as fac','pro.`f_id`=fac.`fid`')
			->where($where)
			->order('o.`update_time` desc')
			->select('o.`o_id`,o.`update_time` ,pur.`p_id`,pro.model,fac.f_name,pro.product_type,
o.`delivery_location`,o.`pickup_location`,pur.number')
			->limit('5')
			->getAll();
	}

	/**
	 * 获取采购信息
	 * @param int $where
	 * @param int $page
	 * @param int $pageSize
	 * @return mixed
	 * @Author: yuanjiaye
     */
	public function getPurs($where=1, $page=1, $pageSize=10){
		return $this->from('order as o')
			->join('purchase_log as pur','o.`o_id`=pur.o_id')
			->join('product as pro','pro.id=pur.`p_id`')
			->join('factory as fac','pro.`f_id`=fac.`fid`')
			->where($where)
			->order('o.`update_time` desc')
			->page($page,$pageSize)
			->select('o.`o_id`,o.`update_time`,pur.unit_price,pur.`p_id`,pro.model,fac.f_name,pro.product_type,pur.number')
			->getPage();
	}

	/**
	 * 根据订单的id获取订单的交易员姓名
	 */
	public function getNameBySid($o_id = 0){
		$aid = $this->model('order')->select('customer_manager')->wherePk($o_id)->getOne();
		if($aid>0){
			return $this->model('admin')->select('name')->wherePk($aid)->getOne();
		}else{
			return '';
		}

	}
	//根据订单customer_id 查询
	public function getIdsByAId($customer_manager = ''){
		return join(',',$this->model('order')->select('o_id')->where("`customer_manager` in ($customer_manager)")->getCol());
	}
	/**
	 * 根据订单类型以及交易员id查询当日销售/采购总吨数
	 * @Author   yezhongbao
	 * @DateTime 2016-09-08T17:22:48+0800
	 * @param    [int]   $type  [订单类型]
	 * @param   [type]  $admin_id [交易员id]
	 * @return   [string]   [吨数]
	 */
	public function getTodaySaleAndBuyNum($type=1,$admin_id=1){
		$start = strtotime(date('Y-m-d',CORE_TIME));
		$end = CORE_TIME;
		$where =' 1 ';
		$where .= 'and order_type = '.$type.' and order_status = 2 and transport_status = 2 and input_time > '.$start.'	 and input_time < '.$end.' and customer_manager = '.$admin_id;
		$select = 'sum(total_num) as total_num';
		$data = $this->select($select)
						 ->where($where)
						 ->group('customer_manager')
						 ->getOne();
		return empty($data) ? '0' : $data;
	}

	/**
	 * 临时的实时成交价
	 * @return mixed
	 * @Author: yuanjiaye
	 */
	public function getTrad(){
		return $this->model('sale_log as s')
			->join('product as pro','s.p_id=pro.id')
			->join('factory as fac','pro.f_id=fac.fid')
			->order('s.input_time desc')
			->select('s.input_time,s.update_time,s.unit_price,pro.product_type,pro.model,fac.f_name')
			->limit('10')
			->getAll();
	}
	/**
	 * 根据客户id获取该客户的所有订单信息
	 * @Author   yezhongbao
	 * @DateTime 2016-09-27T10:09:54+0800
	 * @param    [int] $c_id [客户id]
	 * @return   [array][结果集]
	 */
	public function getOrderInfoByCid($c_id=0){
		$result = $this->select("count(c_id) as num,order_type,sum(total_price) as total_price,max(input_time) as input_time")
			           ->where('c_id='.$c_id.' and order_status = 2 and transport_status = 2')
			           ->group('order_type')
			           ->getAll();
		return empty($result) ? array() : $result;
	}
	/**
	 * 根据业务员id获取该业务员当月的销售和采购吨数总和
	 * @Author   yezhongbao
	 * @DateTime 2016-10-09T15:01:10+0800
	 * @param    [type]                   $customer_manager [description]
	 * @return   [type]                                     [description]
	 */
	public function getMonthNumByCustomerManager($customer_manager=0)
	{
		$start=strtotime( date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m"),1,date("Y"))) );
		$end  =strtotime( date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("t"),date("Y"))) );
		$where = 'order_status = 2 and transport_status = 2 and input_time > '.$start.' and input_time < '.$end.' and customer_manager = '.$customer_manager;
		$select = 'sum(total_num) as num';
		$total_num = $this->select($select)
						 ->where($where)
						 ->getRow();
		return empty($total_num) ? array() : $total_num;
	}
	/**
	 * 根据业务员id获取该业务员今日的销售吨数和采购吨数
	 * @Author   yezhongbao
	 * @DateTime 2016-10-10T14:23:21+0800
	 * @param    integer  $customer_manager [description]
	 * @return   [array]  [description]
	 */
	public function getTodayNumByCustomerManager($customer_manager=0){
		$today_start = strtotime(date("Y-m-d",time()));
		// $today_start = 1473728400;
		$today_end = time();
		//销售-where1 采购-where2
		$where1 = 'order_type = 1 and order_status = 2 and transport_status = 2 and input_time > '.$today_start.' and input_time < '.$today_end.' and customer_manager = '.$customer_manager;
		$where2 = 'order_type = 2 and order_status = 2 and transport_status = 2 and input_time > '.$today_start.' and input_time < '.$today_end.' and customer_manager = '.$customer_manager;
		// $select = 'sum(total_num) as num';
		$select = 'o_id,total_num';

		$sale_num = $this->select($select)
						 ->where($where1)
						 ->getAll();
		if($sale_num){
			foreach ($sale_num as $key => $value) {
				$sale['o_id'] .= $value['o_id'].',';
				$sale['num'] += $value['total_num'];
			}
			$sale['o_id'] = "(".trim($sale['o_id'],',').")";
		}else{
			$sale['o_id'] = "('')";
			$sale['num'] = 0;
		}
		$buy_num = $this->select($select)
						 ->where($where2)
						 ->getAll();
		if($buy_num){
			foreach ($buy_num as $key => $value) {
				$buy['o_id'] .= $value['o_id'].',';
				$buy['num'] += $value['total_num'];
			}
			$buy['o_id'] = "(".trim($buy['o_id'],',').")";
		}else{
			$buy['o_id'] = "('')";
			$buy['num'] = 0;
		}
		$res['sale_num'] = $sale;
		$res['buy_num'] = $buy;
		return empty($res) ? array() : $res;
	}
	/**
	 * 获取所有业务员今日的吨数（销售+采购）参数是起始时间
	 * @Author   yezhongbao
	 * @DateTime 2016-11-9
	 * @return   [array]  [description]
	 */
	public function getAllCustomerManagerTodayNum($today_start=0,$today_end=0){
		$where = ' where o.order_status = 2 and o.transport_status = 2 and o.input_time > '.$today_start.' and o.input_time < '.$today_end;
		$num = $this->model('adm_role_user') 
			 ->getAll('SELECT aa.customer_manager,aa.role_id AS team_id,aa.name AS team_name, SUM(aa.total_num) AS total_num,0 AS call_num FROM (
				SELECT user.`user_id` AS customer_manager,user.role_id, role.`name`,0 AS total_num FROM p2p_adm_role_user AS `user`
				LEFT JOIN p2p_adm_role AS role ON user.`role_id` = role.id
				LEFT JOIN `p2p_admin` AS adm ON adm.`admin_id` = user.`user_id`
				WHERE role.pid = 22 AND adm.`status`=1
				UNION ALL
				SELECT o.customer_manager,0 AS role_id, 0 AS NAME,SUM(o.total_num) AS total_num FROM p2p_order o '.$where.'
				GROUP BY o.customer_manager
			)aa
			GROUP BY aa.customer_manager');
			 // showtrace();
		return empty($num) ? array() : $num;
	}
	/**
	 * [getCollection 根据订单的o_id获取付/收款总金额]
	 * @Author   cuiyinming
	 * @DateTime 2016-10-27T14:52:22+0800
	 * @contract qq:1203116460            cuiyinming@126.com
	 * @param    integer                  $o_id              [description]
	 * @return   [type]                                      [description]
	 */
	public function getCollection($o_id = 0){
		$money = $this->model('collection')->select("(`total_price`-`uncollected_price`) as total_collect")->where("o_id = $o_id")->order("id desc")->getOne();
		if(empty($money)){
			return 0;
		}
		return  $money;
	}
	/**
	 * 奇葩需求根据交易员在出库流水中查询订单
	 * @Author   cuiyinming
	 * @DateTime 2016-12-15T09:58:59+0800
	 * @contract qq:1203116460            cuiyinming@126.com
	 * @param    string                   $name              [description]
	 * @return   [type]                                      [description]
	 */
	public function getOidsByAdmin($name = ''){
		$aid = $this->model('admin')->select('admin_id')->where("name = '$name'")->getOne();
		$oids = $this->model('order')->select('o_id')->where("order_type = 1 and customer_manager = ".intval($aid))->getCol();
		return implode(',', $oids);
	}
	/**
	 * 奇葩需求之根据客户名字查询出库流水
	 * @Author   cuiyinming
	 * @DateTime 2016-12-15T10:16:36+0800
	 * @contract qq:1203116460            cuiyinming@126.com
	 * @param    string                   $cname             [description]
	 * @return   [type]                                      [description]
	 */
	public function getOidsByCname($cname=""){
		$cids = $this->model('customer')->select('c_id')->where("c_name like '%$cname%'")->getCol();
		$oids = $this->model('order')->select('o_id')->where("order_type = 1 and c_id in ( ".implode(',', $cids).")")->getCol();
		return implode(',', $oids);
	}

}