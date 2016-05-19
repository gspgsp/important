<?php 
//订单模型
class orderModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'order');
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
	 * 更具字段取出对应的值
	 */
	public function getColByName($value=0,$col='order_name',$condition='o_id'){
		$result =  $this->select("$col")->where("$condition='$value'")->getOne();
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
	public function getODidByOid($o_id=0,$col='id'){
		$result=$this->model('sale_log')->select("$col")->where("o_id='$o_id'")->getOne();
		return empty($result) ? false : $result;
	}
}