<?php
class unionorderAction extends userBaseAction{

	protected $db;
	public function __init()
	{
		$this->db=M('public:common');
	}

	/**
	 * 订单列表
	 * @Author: yuanjiaye
	 */
	public function init()
	{
		$this->act="unionorder";
		$this->transport_type=L('transport_type');
		$this->goods_status=L('goods_status');
		$this->invoice_status=L('invoice_status');
		$this->order_status=L('order_status');
		$this->collection_p_status=L('collection_p_status');
		$this->type=1;

		$where="un.buy_user_id=$this->user_id";
		//订单筛选
		 if($orderSn=sget('sn','s','')){
			 $orderSn=saddslashes($orderSn);
			 $this->sn=$orderSn;
			$where.=" and un.order_sn='{$orderSn}'";
		 }

		if($type=sget('type','s','')){
			//全部订单
			if($type==1){
				$this->type=$type;
				$where;
			}
			//已审核
			if($type==2){
				$order_status=2;
				$this->type=$type;
				$where.=" and un.order_status={$order_status}";
			}
			//待审核
			if($type==3){
				$order_status=1;
				$this->type=$type;
				$where.=" and  un.order_status={$order_status}";
			}

			//待开票
			if($type==4){
				$invoice_status=1;
				$this->type=$type;
				$where.= " and un.invoice_status={$invoice_status} and un.order_status=2";
			}
			//待付款
			if($type==5){
				$collection_status=1;
				$this->type=$type;
				$where.=" and un.collection_status={$collection_status} and un.order_status=2";
			}
			//已取消
			if($type==6){
				$order_status=3;
				$this->type=$type;
				$where.=" and un.order_status={$order_status}";
			}
		}

		$page=sget('page','i',1);
		$size=4;
		$orderList=$this->db->model('union_order as `un`')
			->leftjoin('collection as col','un.id=col.o_id')
			->leftjoin('sale_buy as sb','sb.id=un.p_sale_id')
			->leftjoin('union_order_detail as un_det','un.id=un_det.o_id')
			->leftjoin('product as pro','pro.id=un_det.p_id')
			->leftjoin('factory as fac','fac.fid=pro.f_id')
			->select('un.id,un.type,un.order_name,un.order_sn,un.sale_user_id,un.buy_user_id,un.sale_id,un.buy_id,un.deal_price,un.total_price,un.pay_method,un.customer_manager,un.transport_type,un.collection_status,un.pickup_time,un.delivery_time,un.freight_price,un.input_time,un.order_status,un.goods_status,un.invoice_status,sb.remark,col.uncollected_price,col.payment_time,pro.model,pro.product_type,fac.f_name')
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
//		$this->display('union_order');
		$this->display('order');
	}


	/**
	 * 联营订单明细
	 * @Author: yuanjiaye
	 */
	public function detail()
	{
		$id=sget('id','i',0);
		$order=$this->db->from('union_order o')
			->join('admin ad','o.customer_manager=ad.admin_id')
			->leftjoin('collection as col','o.id=col.o_id')
			->leftjoin('lib_region as r','r.id=o.delivery_location')
			->select('o.id,o.order_name,o.sale_id,o.customer_manager,o.total_price,o.pay_method
			,o.sign_place,o.sign_time,o.pickup_location,,o.collection_status,o.transport_type
			,o.freight_price,o.pickup_time,o.delivery_time,o.order_sn,o.order_status,o.input_time,ad.name as cus_manager_name,ad.mobile,col.payment_time,r.name')
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

	/**
	 * get 异步提交完成订单
	 * @param string $message
	 * @param string $url
	 * @param bool $ajax
	 */
	public  function ajax_submit()
	{
		$this->is_ajax=true;
		if(sget('id','i')){
			$id=saddslashes(sget('id'));
			$un_model=$this->db->model('union_order');
			$un_model->startTrans();
			$info=$un_model->where('id='.$id)->select('order_status,p_sale_id')->getRow();
			if($info['order_status']==4) $this->error('不能重复此操作');
			$arr=array(
				'order_status'=>4,
				'goods_status'=>3,
				'invoice_status'=>3,
				'collection_status'=>3,
				'update_time'=>CORE_TIME,
			);
			$arrs=array(
				'invoice_status'=>3,
				'update_time'=>CORE_TIME,
			);
			if(!$un_model->where("id=".$id)->update($arr)) $this->error('订单表更新失败');
			if(!$this->db->model('union_order_detail')->where('o_id='.$id)->update($arrs)) $this->error('订单详情表更新失败');
			if(!$this->db->model('sale_buy')->where('id='.$info['p_sale_id'])->update('status=5 and update_time='.CORE_TIME)) $this->error('sale_buy 更新失败');
			if($un_model->commit()){
				$this->success('操作成功');
			}else{
				$un_model->rollback();
				$this->error('操作失败');
			}

		}
	}

	// 订单支付
	public function pay()
	{
		if($_POST){
			$this->is_ajax=true;
			$data=saddslashes($_POST);
			$id = empty($data['id'])?0:$data['id'];
			if(!$this->db->model('union_order')->where("id=$id and buy_user_id=$this->user_id")->getRow()) $this->forward('/');
			$order=$this->db->from('union_order o')
			->join('admin ad','o.customer_manager=ad.admin_id')
			->select('o.*,ad.name,ad.mobile')
			->where("o.id=$id and buy_user_id={$this->user_id}")
			->getRow();
			$obj = E('dfftPayment',APP_LIB.'class');//引入dfftPayment类
			$payID = 'PAY'.date('Ymdhis',time()).'-'.rand(999,9999);
			//参数封装
			$params['payID'] =  $payID; // 支付号码，东方付通对此订单的唯一标识，商城必须保存此订单号，下次支付时使用
			$params['tradeOrder'] =  $order['order_sn']; // 合同号码，商城的订单号
			$params['mallID'] = $obj->mallID;  // 商城号
			$params['payType'] = '01010'; // 接口类型，直接支付
			$buy_info = M('user:customer')->getCinfoById($order['buy_id']);
			$sale_info = M('user:customer')->getCinfoById($order['sale_id']);
			$params['payMemCode'] = $order['buy_id']; // 付款人代码
			$params['payMemName'] = $buy_info['c_name']; // 付款人名称
			$params['recMemCode'] = $order['sale_id']; // 收款人代码
			$params['recMemName'] = $sale_info['c_name']; // 收款人名称
			$params['currency'] = 'CNY'; // 人民币
			$params['payAmt'] = $order['total_price']; // 付款金额
			$params['originalPayID'] = '';  // 直接支付不需要赋值
			$params['callBackUrl'] = APP_URL.'/pay/rtnpay/unionorder_callback'; //回调通知地址，订单支付成功后通知商城的地址
			$params['summary'] = ''; //摘要
			// 	        echo "支付号码：".$payID;
			$params['customFiels'] ='';//自定义字段
			$params['instAccount']='0'; //优先记账0 或空：不记账99：记账
			$params['locktag']='1';// 锁定标识 1 锁定到收款方  到货支付此处必须为1 直接支付为0或空
			$params['bankUse']='';//银行用途
			$params['bankDigest']='';//银行摘要
			$obj = E('dfftPayment',APP_LIB.'class');//引入dfftPayment类
			// 生成签名
			$params['signature'] = $obj->_getSign(json_encode($params));    //打印签名密文
			$json = json_encode($params);
			// 	        echo "支付参数：<br />".$json;
			// 	        echo "<br /><br />";
			$dataorder = $obj->_base64Sign($json);
			// 	        echo "参数：<br />".$dataorder;
			// 	        $this->assign('dataorder',$dataorder);
			// 	        $this->display('pay.html');
			$this->db->startTrans();
			$update=array(
				'pay_id'      => $payID,
			);
			$this->db->model('union_order')->where("id=$id and buy_user_id=$this->user_id")->update(saddslashes($update));
			$tmp=$this->db->model('pay_message')->select('payID')->where("tradeorder='".$order['order_sn']."'")->getOne();
			if(!empty($tmp)){
				$this->db->model('pay_message')->where("tradeorder='".$order['order_sn']."'")->delete();
				$this->db->model('pay_message')->add(saddslashes($params));
			}else{
				$this->db->model('pay_message')->add(saddslashes($params));
			}
			//自营订单支付操作
			$remarks = "联营订单支付操作";
			M('user:applyLog')->addLog($this->user_id,'unionorder/pay','',json_encode($params),1,$remarks);
			if($this->db->commit()){
				$this->success($dataorder);
			}else{
				$this->db->rollback();
				$this->error('生成失败:'.$this->db->getDbError());
			}
			$this->success($dataorder);
		}
	}
	
// 	// 支付成功回调
// 	public function callback()
// 	{
// 	    //获取参数
// 	    if(isset($_POST['postdata']) || !empty($_POST['postdata'])){
// 	        $postdata = $_POST['postdata'];
// 	    }else{
// 	        $postdata = file_get_contents("php://input");
// 	    }
// 	    //         file_put_contents("./pay.txt", $postdata,FILE_APPEND);
// 	    $param = json_decode($postdata);
// 	    if(isset($param)){
// 	        // 支付消息
// 	        $message = $param->payMessage;
// 	        // 支付订单的支付号码
// 	        $payID = $param->payID;
// 	        // 支付状态
// 	        $payStatus = $param->payStatus;
// 	        // 签名
// 	        $signature = $param->signature;
	
// 	        $obj = E('dfftPayment',APP_LIB.'class');//引入dfftPayment类
// 	        $rtn = $obj->_base64Verify($postdata,$signature);
// 	        //验证签名，是否为东方付通发送的指令
// 	        if($rtn == "1"){
// 	            // 订单支付成功(其他不处理)
// 	            if ($payStatus == "000000") {
// 	                //$payID
// 	                $this->db->startTrans();
// 	                // 修改订单状态 已支付 (商城逻辑处理)
// 	                //状态为3默认全部付款
// 	                $update=array(
// 	                    'collection_status'      => "3",
// 	                );
// 	                if(!$this->db->model('union_order')->where("pay_id={$payID}")->update(saddslashes($update))) throw new Exception("更新支付状态失败!");
// 	                if(!$this->db->model('pay_message')->add($param)) throw new Exception("插入支付信息失败!");
// 	                if($this->db->commit()){
// 	                    $this->success('生成成功');
// 	                }else{
// 	                    $this->db->rollback();
// 	                    $this->error('生成失败:'.$this->db->getDbError());
// 	                }
// 	            }
// 	            // 响应支付平台已接收,接收到消息必须返回  // echo "{\"payStatus\":\"000000\"}";
// 	        }else{
// 	            //签名验证失败！
// 	            $this->error('签名验证失败!');
// 	        }
// 	    }else{
// 	        $this->error('支付失败,回调内容为空!');
// 	    }
// 	}
	
	//时时查询支付状态
	public function querySucess(){
		if($_POST){
			$this->is_ajax=true;
			$data=saddslashes($_POST);
			$id = empty($data['id'])?0:$data['id'];
			if(!$this->db->model('union_order')->where("o_id=$id and user_id=$this->user_id")->getRow()) $this->forward('/');
			$order=$this->db->from('union_order o')
			->join('admin ad','o.customer_manager=ad.admin_id')
			->select('o.*,ad.name,ad.mobile')
			->where("o.id=$id and buy_user_id={$this->user_id}")
			->getRow();
			$payid = $order['pay_id'];
			$rtn = $this->db->model('pay_message')->where("payID='$payid'")->getRow();
			if(!$rtn) $this->error('查询支付明细失败!');
			if($rtn['pay_status']=="000000"){
				$this->success('支付成功');
			}else{
				$this->error('支付失败');
			}
		}
	}
	
	
	//取消支付
	public function payCancel(){
		p(1);
		die;
	}
	
}