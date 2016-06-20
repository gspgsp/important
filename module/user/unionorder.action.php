<?php
class unionorderAction extends userBaseAction{

	protected $db;
	public function __init()
	{
		$this->db=M('public:common');
	}
	public function init()
	{
		$this->act="unionorder";


		$this->transport_type=L('transport_type');
		$this->goods_status=L('goods_status');
		$this->invoice_status=L('invoice_status');
		$this->order_status=L('order_status');

		$where="buy_user_id=$this->user_id";

		//订单筛选
		// if($orderSn=sget('sn','s','')){
		// 	$where.=" and order_sn=$orderSn";
		// }
		// //日期筛选
		// if($input_time=sget('input_time','s','')){

		// }
		// //运输方式
		// if($transport_type=sget('transport_type','i',0)){
		// 	$where.=" and transport_type=$transport_type";
		// }
		// //发货状态
		// if($goods_status=sget('goods_status','i',0)){
		// 	$where.=" and goods_status=$goods_status";
		// }
		// //开票状态
		// if($invoice_status=sget('invoice_status','i',0)){
		// 	$where.=" and invoice_status=$invoice_status";
		// }
		// //订单状态
		// if($order_status=sget('order_status','i',0)){
		// 	$where.=" and order_status=$order_status";
		// }
		$page=sget('page','i',1);
		$size=10;
		$orderList=M('product:unionOrder')
			->select('id,type,order_name,order_sn,slae_user_id,buy_user_id,sale_id,buy_id,deal_price,total_price,pay_method,customer_manager,transport_type,freight_price,input_time,order_status,goods_status,invoice_status')
			// ->select('id,order_name,order_sn,user_id,admin_id,total_price,pay_method,transport_type,freight_price,order_status,goods_status,invoice_status,input_time')
			->where($where)
			->page($page,$size)
			->order('input_time desc')
			->getPage();


		$this->pages = pages($orderList['count'], $page, $size);

		foreach ($orderList['data'] as &$value) {
			$value['totalNum']=$this->db->model('union_order_detail')->where("o_id={$value['id']}")->select("sum(number)")->getOne();
			$value['c_name']=$this->db->model('customer')->where("c_id={$value['sale_id']}")->select('c_name')->getOne();
		}


		$this->assign('orderList',$orderList);
		$this->display('union_order');
	}

	public function detail()
	{
		$id=sget('id','i',0);

		$order=$this->db->from('union_order o')
			->join('admin ad','o.customer_manager=ad.admin_id')
			->select('o.*,ad.name,ad.mobile')
			->where("o.id=$id and buy_user_id={$this->user_id}")
			->getRow();
		$order['c_name']=$this->db->model('customer')->where("c_id={$order['sale_id']}")->select('c_name')->getOne();

		$sale_log=$this->db->from('union_order_detail s')
			->leftjoin('product p','s.p_id=p.id')
			->leftjoin('factory f','p.f_id=f.fid')
			->select('s.id,s.number,s.unit_price,p.model,p.product_type,f.f_name')
			->where("o_id={$order['id']}")
			->getAll();

		foreach ($sale_log as $key => &$value) {
			$value['totalPrice']=$value['number']*$value['unit_price'];
		}

		$this->assign('order',$order);
		$this->assign('sale_log',$sale_log);
		$this->display('union_order.detail');
	}
}