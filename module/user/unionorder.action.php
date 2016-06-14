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

		$where="(slae_user_id=$this->user_id or buy_user_id=$this->user_id)";

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
			// ->select('o_id,order_name,order_sn,user_id,admin_id,total_price,pay_method,transport_type,freight_price,order_status,goods_status,invoice_status,input_time')
			->where($where)
			->page($page,$size)
			// ->order('input_time desc')
			->getPage();


		// p($orderList);

		// $this->pages = pages($orderList['count'], $page, $size);

		// foreach ($orderList['data'] as &$value) {
		// 	$value['totalNum']=$this->db->model('sale_log')->where("o_id={$value['o_id']}")->select("sum(number)")->getOne();
		// }
		// $this->assign('orderList',$orderList);
		$this->display('union_order');
	}
}