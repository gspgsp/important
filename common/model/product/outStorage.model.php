<?php
//出库模型
class outStorageModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'out_storage');
	}
	/**
	 * 判断数量是否正确
	 */
	public function checkNum($id=0,$o_number=0){
		$number=$this->model('sale_log')->select('number')->where("id='$id'")->getOne();
		return $result = $number-$o_number<0? false : $number-$o_number;
	}
	/**
	 * 根据出库人ID 取名字
	 */
	public function getNameBySid($store_aid){
		return $this->model('admin')->select('name')->where("admin_id = '$store_aid'")->getOne();
	}
	/**
	 * 根据销售明细id返回sign状态
	 */
	public function getFildById($o_id = 0,$pid=0,$fild ='sign'){
		return $this->model('out_log')->select($fild)->where("`o_id` = $o_id and `p_id` = $pid")->getOne();
	}
	/**
	 * 更具字段取出对应的值
	 */
	public function getColByName($value=0,$col='o_id',$condition='plate_number'){
		$result =  $this->model('transport_contract')->select("$col")->where("$condition like'%$value%'")->getCol();
		return empty($result) ? '' : join(',',$result);
	}
	/**
	 * 更具字段取出对应的值
	 */
	public function getColByInfo($value=0,$col='ols.o_id',$condition='driver'){
		$result =  $this->select("$col")->from('out_logs ols')->leftjoin('out_log ol','ol.id = ols.outlog_id')->where("$condition like'%$value%'")->getCol();
		return empty($result) ? '' : join(',',$result);
	}
	//新添加方法匹配查询
	public function getLikes($value = ''){
		$ids =  array_unique($this->model('transport_contract')->select("o_id")->where("`plate_number` like '%$value%' OR `driver_name` like '%$value%' OR `driver_idcard` like '%$value%'")->getCol());

		$oids = array_unique($this->select("ols.o_id")->from('out_logs ols')->leftjoin('out_log ol','ol.id = ols.outlog_id')->where("`driver` like '%$value%' OR `car_code` like '%$value%'")->getCol());
		return join(',',array_merge($ids,$oids));
	}
}