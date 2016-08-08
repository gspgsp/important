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
	public function getOrder($uid=0,$status=1,$col = 'order_status'){
		$tm = date(strtotime('-90 day'));
		return $this->from('union_order ')->select('COUNT(id) AS number')->where("buy_user_id=$uid and input_time > $tm and $col = $status")->getOne();
	}

	/*
	*根据订单purchase_id 获取puchase表中的字段值
	 */
	public function getPcol($id = 0, $col='user_id'){
		return $this->model('purchase')->select("$col")->where("id='$id'")->getOne();
	}
	/*
	*根据订单salebuy_id 获取sale_buy表中的字段值
	 */
	public function getScol($id = 0, $col='user_id'){
		return $this->model('sale_buy')->select("$col")->where("id='$id'")->getOne();
	}
}