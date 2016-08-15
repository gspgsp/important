<?php

/**
 * 自营商城订单
 */
class selforderAction extends userBaseAction{

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
		$this->collection_p_status=L('collection_p_status');

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
		//付款状态
		if($collection_p_status=sget('collection_p_status','i',0)){
			$where="and collection_status=$collection_p_status";
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
//				->leftjoin('sale_log as s','o.order_sn=s.o_id')
//				->leftjoin('product p','s.p_id=p.id')
//				->leftjoin('factory f','p.f_id=f.fid')
				->select('o_id,order_name,order_sn,user_id,total_price,pay_method,transport_type,freight_price,order_status,invoice_status,input_time,remark,collection_status')
				->where($where)
				->page($page,$size)
				->order('input_time desc')
				->getPage();
		$this->pages = pages($orderList['count'], $page, $size);

		foreach ($orderList['data'] as &$value) {
			$value['totalNum']=$this->db->model('sale_log')->where("o_id={$value['o_id']}")->select("sum(number)")->getOne();
			$value['model']=$this->db->model('sale_log as s')
				->leftjoin('product p','s.p_id=p.id')
				->leftjoin('factory f','p.f_id=f.fid')
				->select("p.model")
				->where("o_id={$value['o_id']}")->getOne();
			$value['f_name']=$this->db->model('sale_log as s')
				->leftjoin('product p','s.p_id=p.id')
				->leftjoin('factory f','p.f_id=f.fid')
				->select("f_name")
				->where("o_id={$value['o_id']}")->getOne();
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
			->join('admin ad','o.customer_manager=ad.admin_id')
			->select('o.*,ad.name,ad.mobile')
			->where("o_id=$id and user_id={$this->user_id}")
			->getRow();

		$order['transport_type']==1?($order['pickup_time']='--'):($order['delivery_time']=$order['delivery_time']);
		$order['transport_type']==1?($order['pickup_location']='--'):($order['delivery_location']=$order['delivery_location']);

		$sale_log=$this->db->from('sale_log s')
			->leftjoin('product p','s.p_id=p.id')
			->leftjoin('factory f','p.f_id=f.fid')
			->select('s.id,s.number,s.unit_price,p.model,p.product_type,f.f_name')
			->where("o_id={$order['o_id']}")
			->getAll();
		foreach ($sale_log as $key => &$value) {
			$value['totalPrice']=$value['number']*$value['unit_price'];
		}

		$this->assign('sale_log',$sale_log);
		$this->assign('order',$order);
		$this->display('selforder.detail');
	}
	
	// 订单支付
	public function pay()
	{
	    if($_POST){
	        $this->is_ajax=true;
	        $data=saddslashes($_POST);
	        $id = empty($data['id'])?0:$data['id'];
	        if(!$this->db->model('order')->where("o_id=$id and user_id=$this->user_id")->getRow()) $this->forward('/');
	        $order=$this->db->from('order o')
	        ->join('admin ad','o.customer_manager=ad.admin_id')
	        ->select('o.*')
	        ->where("o_id=$id and user_id={$this->user_id}")
	        ->getRow();
	        $obj = E('dfftPayment',APP_LIB.'class');//引入dfftPayment类
	        $payID = 'PAY'.date('Ymdhis',time()).'-'.rand(999,9999);	        
	        //参数封装
	        $params['payID'] =  $payID; // 支付号码，东方付通对此订单的唯一标识，商城必须保存此订单号，下次支付时使用
	        $params['tradeOrder'] =  $order['order_sn']; // 合同号码，商城的订单号	        
	        $params['mallID'] = $obj->mallID;  // 商城号
	        $params['payType'] = '01010'; // 接口类型，直接支付
	        $c_info = M('user:customer')->getCinfoById($order['c_id']);
	        if($order['order_type']=='1')//销售 中晨卖方
	        {
	            $params['payMemCode'] = $order['c_id']; // 付款人代码
	            $params['payMemName'] = $c_info['c_name']; // 付款人名称
	            $params['recMemCode'] = '5041';            // 收款人代码
	            $params['recMemName'] = '上海中晨电子商务股份有限公司'; // 收款人名称
	        }
	        if($order['order_type']=='2')//销售 中晨买方
	        {
	            $params['payMemCode'] = '5041'; // 付款人代码
	            $params['payMemName'] = '上海中晨电子商务股份有限公司'; // 付款人名称
	            $params['recMemCode'] = $order['c_id']; // 收款人代码
	            $params['recMemName'] = $c_info['c_name']; // 收款人名称
	        }
	        $params['currency'] = 'CNY'; // 人民币
	        $params['payAmt'] = $order['total_price']; // 付款金额
	        $params['originalPayID'] = '';  // 直接支付不需要赋值
	        $params['callBackUrl'] = APP_URL.'/pay/rtnpay/selforder_callback'; //回调通知地址，订单支付成功后通知商城的地址
	        $params['summary'] = ''; //摘要	        	        
// 	        echo "支付号码：".$payID;      	        	              
	        $params['customFiels'] ='';//自定义字段
	        $params['instAccount']='0'; //优先记账0 或空：不记账99：记账
	        $params['locktag']='1';// 锁定标识 1 锁定到收款方  到货支付此处必须为1 直接支付为0或空
	        $params['bankUse']='';//银行用途
	        $params['bankDigest']='';//银行摘要	
	        // 生成签名
	        $params['signature'] = $obj->_getSign(json_encode($params));    //打印签名密文      
	        $json = json_encode($params);        
// 	        echo "支付参数：<br />".$json;
// 	        echo "<br /><br />";        
	        $dataorder = $obj->_base64Sign($json);
// 	        echo "参数：<br />".$dataorder;
// 	        $this->assign('dataorder',$dataorder);
// 	        $this->display('pay.html');
	        $update=array(
	            'pay_id'      => $payID,
	        );
	        $this->db->model('order')->where("o_id=$id and user_id=$this->user_id")->update(saddslashes($update));
	   	    $this->success($dataorder);
	    }
	}
	
// 	// 支付成功回调
// 	public function callback()
// 	{   
// 	    // $token=cookie::get(C('SESSION_TOKEN'));
// 	    file_put_contents($_SERVER['DOCUMENT_ROOT']."/Javatest/Java/pay.txt",$_POST['postdata']);
//         //获取参数
//         if(isset($_POST['postdata']) || !empty($_POST['postdata'])){
//             $postdata = $_POST['postdata'];
//         }else{
//             $postdata = file_get_contents("php://input");
//         }
// //         file_put_contents("./pay.txt", $postdata,FILE_APPEND);
//         $param = json_decode($postdata);
//         if(isset($param)){
//           // 支付消息
//           $message = $param->payMessage;
//           // 支付订单的支付号码
//           $payID = $param->payID;
//           // 支付状态
//           $payStatus = $param->payStatus;
//           // 签名
//           $signature = $param->signature;
          
//           $obj = E('dfftPayment',APP_LIB.'class');//引入dfftPayment类
//           $rtn = $obj->_base64Verify($postdata,$signature);
//           //验证签名，是否为东方付通发送的指令
//           if($rtn == "1"){
//         	// 订单支付成功(其他不处理)
//         	if ($payStatus == "000000") {
//         		//$payID
//         	    $this->db->startTrans();
//         		// 修改订单状态 已支付 (商城逻辑处理)
//         		//状态为3默认全部付款
//         	    $update=array(
//         	        'collection_status' => "3",
//         	    );
//         	    if(!$this->db->model('order')->where("pay_id={$payID}")->update(saddslashes($update))) throw new Exception("更新支付状态失败!");
//         	    if(!$this->db->model('pay_message')->add($param)) throw new Exception("插入支付信息失败!");
//         	    if($this->db->commit()){
//         	        $this->success('生成成功');
//         	    }else{
//         	        $this->db->rollback();
//         	        $this->error('生成失败:'.$this->db->getDbError());
//         	    }
//         	}
//             // 响应支付平台已接收,接收到消息必须返回  // echo "{\"payStatus\":\"000000\"}";
//           }else{
//         	//签名验证失败！
//               $this->error('签名验证失败!');
//           }
//         }else{
//             $this->error('支付失败,回调内容为空!');
//         }
// 	}
	
	//时时查询支付状态
	public function querySucess(){
	    if($_POST){
	        $this->is_ajax=true;
	        $data=saddslashes($_POST);
	        $id = empty($data['id'])?0:$data['id'];
	        if(!$this->db->model('order')->where("o_id=$id and user_id=$this->user_id")->getRow()) $this->error('查询订单失败!');
	        $order=$this->db->from('order o')
	        ->join('admin ad','o.admin_id=ad.admin_id')
	        ->select('o.*,ad.name,ad.mobile')
	        ->where("o_id=$id and user_id={$this->user_id}")
	        ->getRow();
	        $payid = $order['pay_id'];
	        $rtn = $this->db->model('pay_message')->where("pay_id='$payid'")->getRow();
	        if(!$rtn) $this->error('查询订单失败!');
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