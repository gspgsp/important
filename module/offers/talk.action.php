<?php

class talkAction extends homeBaseAction{

	protected $db;
	public function __init(){
		$this->db=M('public:common');
		$this->ship_type=L('transport_type');   //运输方式
		$this->pay_method=L('pay_method');      // 付款方式
	}
	public function init()
	{
		if($this->user_id<=0) $this->forward('/user/login');
		$id=sget('id','i',0);
		$data=M('product:purchase')->getPurchaseById($id);
		if($_SESSION['uinfo']['type']==2 &&$data['type']==1 ) $this->error('买家不能进行此操作');
		if($_SESSION['uinfo']['type']==1 &&$data['type']==2 ) $this->error('卖家不能进行此操作');
		$this->title=$data['type']==1?'我要供货':'委托洽谈';
		$contact=M('user:customerContact')->getContactByuserid($data['user_id']);
		if(!$contact) $contact=array();
		$var=$this->db->model('lib_region')->select('id,name')->where('id='.$data['store_house'])->getRow();
		$data['city']=(!empty($var['name']))?$var['name']:$data['store_house'];
		//配送地点
		$this->area=M('system:region')->get_regions(1);
		//产品类型
		$product_type=L('product_type');
		$data['product_type']=$product_type[$data['product_type']];

		$data=$data+$contact;
		$this->assign('data',$data);
		$this->display('talk.html');
	}

//联营下订单
	public function addorder()
	{
		if($_POST){
			$this->is_ajax=true;
			$data=saddslashes($_POST);
			if($this->user_id==$data['user_id']) $this->error('采购人和供货人不能相同');
			if(!$data['price']||!$data['delivery_date']||!$data['pur_id']||!$data['city']||!$data['ship_type']) $this->error('信息填写不完整');
			$pur_id=$data['pur_id'];
			$model=M('product:purchase');
			$purData=$model->getPurchaseById($pur_id);
			if( !$purData ) $this->error('请求错误,信息不存在');
			$data['p_id']=$pur_id;                                          //报价id  (purchase)
			$data['c_id']=$_SESSION['uinfo']['c_id'];                          //客户id
			$data['customer_manager']=$_SESSION['uinfo']['customer_manager'];  //客户交易员id
			$data['delivery_date']=strtotime($data['delivery_date']);
			$data['delivery_place']=$data['city'];
			$data['ship_type']=$data['ship_type'];
			$data['pay_method']=$data['pay_method'];                           // 支付方式
			$data['input_time']=CORE_TIME;
			$data['update_time']=CORE_TIME;
//			$data['status']=($purData['type']==1)?1:2;
			$data['status']=2;
//			$data['user_id']=$this->user_id;
			$data['c_user_id']=$_SESSION['userid'];
			$data['user_id']=$data['user_id'];
			// 发布者id
			$data['sn']='UO'.genOrderSn();
			$this->db->startTrans();  //开启事物
			try{
				if(!($this->db->model('sale_buy')->add($data))) throw new Exception('下单失败，请重新下单');
				$id=$this->db->model('sale_buy')->getLastID();               // buy_sale (id)
				if($purData['type']==2){
					$arr=array(
						'order_name'=>'采购ID为【'.$id.'】销售ID为【'.$pur_id.'】',
						'total_price'=>$data['number']*$data['price'],      //  总价
//						'order_sn'=>genOrderSn(),
						'order_sn'=>$data['sn'],
						'order_source'=>1,
						'buy_id'=>($data['status']==2)?$data['c_id']:$purData['c_id'] ,          // 客户id
						'sale_id'=>($data['status']==2)?$purData['c_id']:$data['c_id'],      // 发布者客户(purchase表)c_id
						'p_buy_id'=>  $pur_id,             // 采购编号(销售id)
						'p_sale_id'=> $id ,                // 采购(sale_buy表)id
						'sale_user_id'=>($data['status']==2)?M('product:unionOrder')->getScol($id):$_SESSION['userid'],   // 发布者(purchase表) user_id
						'buy_user_id'=>($data['status']==2)?$_SESSION['userid']:$purData['user_id'],                      // 客户名下交易员id
						'sign_time'=>CORE_TIME,
						'deal_price'=>$data['price'],
						'pay_method'=>$data['pay_method'],
						'remark'=>$data['remark'],
						'customer_manager'=>$_SESSION['uinfo']['customer_manager'],
						'sign_place'=>'网站签约',              //签约地点
						'transport_type'=>$data['ship_type'], //运输方式
						'pickup_location'=>$data['city'],
						'delivery_location'=>$data['city'],
						'pickup_time'=>$data['delivery_date'],
						'delivery_time'=>$data['delivery_date'],
						'type'=>$data['status'],              // 联营订单类型（1、销售，2、采购）
					);

					//创建订单
					$this->db->model('union_order')->add($arr+array('input_time'=>CORE_TIME,'input_admin'=>$_SESSION['uinfo']['name'],));
					$o_id = $this->db->model('union_order')->getLastID();
					if(!$o_id) throw  new Exception('订单生成失败');
					$product = array(
						'p_id'=> $purData['p_id'],    //采购商品id(产品id)
						'o_id'=>$o_id,
						'number'=>$data['number'],
						'unit_price'=>$data['price'],
						'input_time'=>CORE_TIME,
						'input_admin'=>$_SESSION['uinfo']['name'],
					);
					//创建子订单详情
					if(!$this->db->model('union_order_detail')->add($product)) throw  new Exception('订单生成失败');
				}
				if($purData['type']==1){
					$arr=array(
						'order_name'=>'采购ID为【'.$id.'】销售ID为【'.$pur_id.'】',
						'total_price'=>$data['number']*$data['price'],      //  总价
//						'order_sn'=>genOrderSn(),
						'order_sn'=>$data['sn'],
						'order_source'=>1,
						'buy_id'=>$purData['c_id'] ,          // 客户id
						'sale_id'=>$data['c_id'],      // 发布者客户(purchase表)c_id
						'p_buy_id'=>  $pur_id,             // 采购编号(销售id)
						'p_sale_id'=> $id ,                // 采购(sale_buy表)id
//						'sale_user_id'=>($data['status']==2)?M('product:unionOrder')->getScol($id):$_SESSION['userid'],   // 发布者(purchase表) user_id
						'sale_user_id'=>M('product:unionOrder')->getScol($id),
						'buy_user_id'=>$purData['user_id'],
//						'buy_user_id'=>($data['status']==2)?$_SESSION['userid']:$purData['user_id'],  // 客户名下交易员id
						'sign_time'=>CORE_TIME,
						'deal_price'=>$data['price'],
						'pay_method'=>$data['pay_method'],
						'remark'=>$data['remark'],
						'customer_manager'=>$_SESSION['uinfo']['customer_manager'],
						'sign_place'=>'网站签约',              //签约地点
						'transport_type'=>$data['ship_type'], //运输方式
						'pickup_location'=>$data['city'],
						'delivery_location'=>$data['city'],
						'pickup_time'=>$data['delivery_date'],
						'delivery_time'=>$data['delivery_date'],
						'type'=>$data['status'],              // 联营订单类型（1、销售，2、采购）
					);

					//创建订单
					$this->db->model('union_order')->add($arr+array('input_time'=>CORE_TIME,'input_admin'=>$_SESSION['uinfo']['name'],));
					$o_id = $this->db->model('union_order')->getLastID();
					if(!$o_id) throw  new Exception('订单生成失败');
					$product = array(
						'p_id'=> $purData['p_id'],    //采购商品id(产品id)
						'o_id'=>$o_id,
						'number'=>$data['number'],
						'unit_price'=>$data['price'],
						'input_time'=>CORE_TIME,
						'input_admin'=>$_SESSION['uinfo']['name'],
					);
					//创建子订单详情
					if(!$this->db->model('union_order_detail')->add($product)) throw  new Exception('订单生成失败');

				}
				$this->db->commit();
			}catch (Exception $e){
				$this->db->rollback();
				$this->error('操作失败');
			}

//			$model->where("id=$p_id")->update("supply_count=supply_count+1");
			// //发送站内信

			$name=$purData['type']==1?'采购':'报价';
			$msgType=$purData['type']==1?2:3;
			$msg=L('msg_template.offers');
			$msg=sprintf($msg,$name,$purData['id'],$purData['model'],$purData['unit_price'],$_SESSION['uinfo']['name'],$purData['id'],$purData['type']);
			M("system:sysMsg")->sendMsg($purData['user_id'],$msg,$msgType);
			$_SESSION['order_success']=true;
			$this->success('提交成功');
		}else{
			$this->error('请求错误');
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