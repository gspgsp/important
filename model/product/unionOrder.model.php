<?php 
//订单模型
class unionOrderModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'union_order');
	}

	/**
	 * 获取订单总数
	 */
	public function getOrdNum($oid,$type){
		$result=$this->model('sale_log')->where('o_id ='.$oid)->getAll();
		$count=0;
		//总数求和
		for($i=0;$i<count($result);$i++){
			if($type==1){
				$count+=(int)$result[$i]['number']*(int)$result[$i]['unit_price'];
			}else{
				$count+=(int)$result[$i]['number'];
			}		
		}
		return $count < 1 ? '-' : $count;
	}
	/**
	 * 更具字段取出对应的值(OK已用)
	 */
	public function getColByName($value=0,$col='order_name',$condition='id'){
		$result =  $this->select("$col")->where("$condition='$value'")->getOne();
		return empty($result) ? '-' : $result;
	}
	/**
	 * 获取全部信息
	 */
	public function getAllInfoById($id=0,$col='id'){
		return $result = $this->where("$col = $id")->getRow();
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
	/**
	 * 模糊查询订单名匹配的明细
	 */
	public function getidByOname($value=''){
		$arr=$this->select('id')->where("order_name like '%".$value."%'")->getCol();
		$data = implode(',',$arr);
		return empty($data)? false : $data;
	}

	/**
	 * 根据id查订单明细表的值
	 */
	public function getODidByOid($o_id=0,$col='id'){
		$result=$this->model('sale_log')->select("$col")->where("o_id='$o_id'")->getOne();
		return empty($result) ? false : $result;
	}

	/** (联营)
	 *获取近三个月(待审核)订单信息
	 */
	public function getOrderStatusInFo($uid,$date){
		$result=$this->from('union_order as u')->select('COUNT(u.id) AS number')
			->where("u.buy_user_id=$uid and u.input_time between  $date and ".time()." and u.order_status=1")->getAll();
		return empty($result)? 0:$result;
	}
	/**
	 * (联营)
	 *获取近三个月(待付款)订单信息
	 */
	public function getOrder($uid,$date){
		$result=$this->from('union_order as u')->select('COUNT(u.id) AS number')
			->where("u.buy_user_id=$uid and u.input_time between  $date and ".time()." and u.collection_status=1")->getAll();
		return empty($result)? 0:$result;
	}
	/** ((联营))
	 *获取近三个月(代开票)订单信息
	 */
	public function getInvoice($uid,$date){
		$result=$this->from('union_order as u')
			->select('COUNT(u.id) AS number')
			->where("u.buy_user_id=$uid and u.input_time between  $date and ".time()." and u.invoice_status=1")->getAll();
		return empty($result)? 0:$result;
	}
	/** (联营)
	 *获取近三个月(已取消)订单信息
	 */
	public function getOrderCancel($uid,$date){
		$result=$this->from('union_order as u')->select('COUNT(u.id) AS number')
			->where("u.buy_user_id=$uid and u.input_time between  $date and ".time()." and u.order_status=3")->getAll();
		return empty($result)? 0:$result;
	}



}