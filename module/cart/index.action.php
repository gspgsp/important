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
		$cartList=Cart::getGoods();
		if(empty($cartList)) $this->forward('/offers');
		$this->pay_method=L('pay_method');
		$this->transport_type=L('transport_type');
		//配送地点
		$this->area=M('system:region')->get_regions(1);
		$customer_manager=$this->db->model('admin')
			->where("admin_id={$_SESSION['uinfo']['customer_manager']}")
			->select('admin_id,name,mobile')
			->getRow();

		$this->assign('customer_manager',$customer_manager);
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
			$num=round(sget('num'),2);
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
			$this->is_ajax=true;
			$orderSn='PO'.genOrderSn();
			$data=saddslashes($_POST);
			$contact=$this->db->model('customer_contact')->where("user_id=$this->user_id")->getRow();
			$data['order_sn']=$orderSn;
			$c_name=$this->db->model('lib_region')->select('name')->where("id={$data['delivery_place']}")->getOne();//地址
			if($data['transport_type']==1){       //1 自提
				$data['pickup_time']='--';	      //提货日期
				$data['delivery_time']='--';	  //送货日期
				$data['pickup_location']='--';    //配送地点
				$data['delivery_location']='--';  //提货地点
				unset($data['delivery_place']);
				unset($data['delivery_date']);
			}else{
				$data['pickup_time']=strtotime($data['delivery_date']);	    //提货日期
				$data['delivery_time']=strtotime($data['delivery_date']);	//送货日期
				$data['pickup_location']=$c_name.$data['address'];           //配送地点
				$data['delivery_location']=$c_name.$data['address'];         //提货地点
			}

			$data['order_type']=1;	     //销售类型
			$data['sign_place']='网站签约';	//签约地点
			$data['order_source']=1;	//订单来源 1网站
			$data['c_id']=$contact['c_id'];
			$data['user_id']=$this->user_id;	//用户id
			$data['customer_manager']=$_SESSION['uinfo']['customer_manager'];//交易员id
			$data['sign_time']=CORE_TIME;	//签订日期
			$data['total_num']=$_SESSION['cart']['total_rows'];//总数量
			$data['total_price']=Cart::getTotalPrice();	//总金额
			$data['financial_records']=2;
			$data['input_time']=CORE_TIME;	//创建时间
			$model=$this->db->model('order');
			$goods=Cart::getGoods(); //购物车列表
			$model->startTrans();
			try {
				if(!$model->add($data)) throw new Exception("系统错误。 cart:101");
				$o_id=$model->getLastID();
				$modelName='';
				$priceName='';
				foreach ($goods as $key => $value) {
					$modelName.=$value['name'].",";
					$priceName.=$value['price'].",";
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
			$msg=L('msg_template.order');
			$msg=sprintf($msg,trim($modelName,','),trim($priceName,','),$o_id);
			M("system:sysMsg")->sendMsg($this->user_id,$msg,4);
			$_SESSION['order_success']=true;
			$this->success('提交成功');
		}
	}


	public function msg()
	{
		if(!$_SESSION['order_success'] || $this->user_id<=0) $this->forward('/');
		$_SESSION['order_success']=null;
		
		$customer_manager=$this->db->model('admin')
			->where("admin_id={$_SESSION['uinfo']['customer_manager']}")
			->select('admin_id,name,mobile')
			->getRow();
		$this->assign('customer_manager',$customer_manager);
		$this->display('success');
	}

}