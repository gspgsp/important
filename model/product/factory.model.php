<?php 
//厂家模型
class factoryModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'factory');
	}
	//更具id取得厂家的名字
	public function getFnameById($fid){
		return $this->model('factory')->where("fid = $fid")->select('f_name')->getOne();
	}
	//根具名字取得厂家的id
	public function getIdsByName($name){
		return $this->model('factory')->where("f_name like '%$name%'")->select('fid')->getCol();
	}
	//根具名字取得厂家的id
	public function getIdByFName($name){
		return $this->model('factory')->select('fid')->where("f_name = '$name'")->getOne();
	}
	/**
	 * 根据名字取得厂家id
	 * 返回的是id组成的字符串
	 */
	public function getIdByName($name){
		$ids = $this->model('factory')->select('fid')->where("f_name like '%$name%'")->getCol();
		if (!empty($ids)){
			foreach ($ids as $v) {
				$ids[]=$v['f_id'];
			}
		}
		$data = implode(',',$ids);
		return empty($data)? false : $data;
	}
	/**
	 * 获取最近一次的销售成交价
	 */
	public function getNeighborSprice($productId=0){
		$price_s = intval($this->model('sale_log')->select('unit_price')->where("p_id = $productId")->order("input_time desc")->getOne());
		return $price_s > 0 ? $price_s : 0;
	}
	/**
	 * 获取最近一次的采购成交价
	 */
	public function getNeighborPprice($productId=0){
		$price_p = intval($this->model('purchase_log')->select('unit_price')->where("p_id = $productId")->order("input_time desc")->getOne());
		return $price_p > 0 ? $price_p : 0;
	}
	/**
	 * 取出指定单号的上一次销售价
	 * type 1 销售 2采购
	 */
	public function minPrice($productId=0,$input_time =0,$type = 1){
		$input_time = $input_time == 0 ? time() : $input_time;
		if($type==1){
			$price = intval($this->select('sl.unit_price')->from('sale_log sl')->leftjoin('order o' , 'o.o_id = sl.o_id')->where("sl.p_id = $productId and sl.input_time < $input_time and o.order_status !=3 and o.transport_status !=3")->order("sl.input_time desc")->getOne());
			// showtrace();
		}else{
			$price = intval($this->select('pl.unit_price')->from('purchase_log pl')->leftjoin('order o' , 'o.o_id = pl.o_id')->where("pl.p_id = $productId and pl.input_time < $input_time  and o.order_status !=3 and o.transport_status !=3")->order("pl.input_time desc")->getOne());
		}
		return $price > 0 ? $price : 0;
	}
}