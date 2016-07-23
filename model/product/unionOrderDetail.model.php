<?php 
//联营订单模型
class unionOrderDetailModel extends model{
	public function __construct() {
		parent::__construct(C('db_default'), 'union_order_detail');
	}

	public function getInfo(){
		return 	$this->from('union_order_detail as d')
					->join('union_order as u','u.order_sn=d.o_id')
					->join('product as p','d.p_id=p.id')
					->join('factory as f','p.f_id=f.fid')
					->join('customer_contact as c','c.user_id=buy_user_id')
					->where('u.order_status=1 and u.type=2')
					->order('u.input_time desc')
					->select('c.name,f.f_name,p.model,p.product_type,d.unit_price')
					->limit('8')
					->getAll();

	}







}