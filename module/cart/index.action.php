<?php
class indexAction extends homeBaseAction{

	protected $db;
	public function __init()
	{
		E('Cart',APP_LIB.'extend');
		$this->db=M('public:common');
	}


	public function init()
	{
		if($this->user_id<=0) $this->forward('/user/login');
		$this->pay_method=L('pay_method');
		$this->transport_type=L('transport_type');
		$this->display('index');
	}

	public function loadCart()
	{
		$this->cartList=Cart::getGoods();
		$this->totalNums=Cart::getTotalNums(); //总数量
		$this->totalPrice=Cart::getTotalPrice(); //总价格
		$this->display('cart');
	}

	//修改购物车数量
	public function setCart()
	{
		if($_POST)
		{
			$sid=sget('sid','s','');
			$num=sget('num','i',0);
			Cart::update(array('sid'=>$sid,'num'=>$num));
			$this->success('修改成功');
		}
	}

	// 下订单
	public function addOrder()
	{
		if($this->user_id<=0) $this->forward('/user/login');
		if($_POST)
		{
			$orderSn=genOrderSn();
			$data=saddslashes($_POST);
			$contact=$this->db->model('customer_contact')->where("user_id=$this->user_id")->getRow();
			$data['order_sn']=$orderSn;
			$data['order_type']=1;	//销售类型
			$data['order_source']=1;	//订单来源 1网站
			$data['c_id']=$contact['c_id'];
			$data['user_id']=$this->user_id;	//用户id
			$data['admin_id']=$contact['customer_manager'];
			$data['sign_time']=CORE_TIME;	//签订日期
			$data['total_price']=Cart::getTotalPrice();	//总金额
			$data['pickup_time']=strtotime($data['pickup_time']);	//提货日期
			$data['delivery_time']=strtotime($data['delivery_time']);	//送货日期
			$data['input_time']=CORE_TIME;	//创建时间
			$model=$this->db->model('order');

			$goods=Cart::getGoods(); //购物车列表

			$model->startTrans();
			try {
				if(!$model->add($data)) throw new Exception("系统错误。 cart:101");
				$o_id=$model->getLastID();
				foreach ($goods as $key => $value) {
					$sale_log=array(
						'o_id'=>$o_id,
						'p_id'=>$value['options']['p_id'],
						'number'=>$value['num'],
						'unit_price'=>$value['price'],
						'input_time'=>CORE_TIME,
					);
					if(!$this->db->model('sale_log')->add($sale_log)) throw new Exception("系统错误。 cart:102");
				}
			} catch (Exception $e) {
				$model->rollback();
				$this->error('提交失败');
			}
			$model->commit();
			Cart::delAll(); //删除购物车所有商品
			$this->success('提交成功');
		}
	}

}