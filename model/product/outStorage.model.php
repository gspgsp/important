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
}