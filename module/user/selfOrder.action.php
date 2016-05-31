<?php

/**
 * 自营商城订单
 */
class selfOrderAction extends homeBaseAction{

	protected $db;
	public function __init(){
		$this->db=M('public:common');
	}


	public function init()
	{
		$this->act="selforder";

		$this->transport_type=L('transport_type');
		$this->goods_status=L('goods_status');
		$this->invoice_status=L('invoice_status');
		$this->order_status=L('order_status');

		$where="user_id=$this->user_id";

		//订单筛选
		if($orderSn=sget('sn','s','')){
			$where.=" and order_sn=$orderSn";
		}
		//日期筛选
		if($input_time=sget('input_time','s','')){

		}
		//运输方式
		if($transport_type=sget('transport_type','i',0)){
			$where.=" and transport_type=$transport_type";
		}
		//发货状态
		if($goods_status=sget('goods_status','i',0)){
			$where.=" and goods_status=$goods_status";
		}
		//开票状态
		if($invoice_status=sget('invoice_status','i',0)){
			$where.=" and invoice_status=$invoice_status";
		}
		//订单状态
		if($order_status=sget('order_status','i',0)){
			$where.=" and order_status=$order_status";
		}
		$page=sget('page','i',1);
		$size=10;
		$orderList=M('product:order')
			->select('o_id,order_name,order_sn,user_id,admin_id,total_price,pay_method,transport_type,freight_price,order_status,goods_status,invoice_status,input_time')
			->where($where)
			->page($page,$size)
			->getPage();

		$this->pages = pages($orderList['count'], $page, $size);

		foreach ($orderList['data'] as &$value) {
			$value['totalNum']=$this->db->model('sale_log')->where("o_id={$value['o_id']}")->select("sum(number)")->getOne();
		}
		$this->assign('orderList',$orderList);
		$this->display('selforder');
	}


	// 订单详细查看
	public function detail()
	{
		$id=sget('id','i',0);
		if(!$this->db->model('order')->where("o_id=$id and user_id=$this->user_id")->getRow()) $this->forward('/');
		$order=$this->db->from('order o')
			->join('admin ad','o.admin_id=ad.admin_id')
			->select('o.*,ad.name,ad.mobile')
			->where("o_id=$id and user_id={$this->user_id}")
			->getRow();

		$sale_log=$this->db->from('sale_log s')
			->leftjoin('product p','s.p_id=p.id')
			->leftjoin('factory f','p.f_id=f.fid')
			->select('s.id,s.number,s.unit_price,p.model,p.product_type,f.f_name')
			->where("o_id={$order['o_id']}")
			->getAll();
		// p($order);
		foreach ($sale_log as $key => &$value) {
			$value['totalPrice']=$value['number']*$value['unit_price'];
		}
		// p($sale_log);

		$this->assign('sale_log',$sale_log);
		$this->assign('order',$order);
		$this->display('selforder.detail');
	}



}