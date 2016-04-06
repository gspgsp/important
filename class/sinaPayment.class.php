<?php
/**
 * 新浪支付类
 */
class sinaPayment{
	private $partner_id=''; //商户的memberId
	private	$notify_url=''; //系统异步回调通知地址
	private	$return_url=''; //页面跳转同步返回页面路径
	private	$md5_key='1234567890qwertyuiopasdfghjklzxc';
	private	$sign_type='RSA'; //MD5、RSA
	private	$request_url='https://gate.pay.sina.com.cn/mas/gateway.do';
	private $rsa_private=''; //商户密钥
	private $rsa_public=''; //sina公钥
	public $error='';
	
	/**
	 * 构造函数
	 * @access public 
	 */
    public function __construct(array $config){
		if(!empty($config)){
			$this->partner_id=$config['partner_id'];
			$this->sign_type=$config['sign_type'];
			$this->rsa_private=$config['rsa_private'];
			$this->rsa_public=$config['rsa_public'];
		}

		$this->notify_url = APP_URL.'/pay/response/sina';
		$this->return_url = APP_URL.'/pay/index/payment?order=%s';
    }
	
	/**
	 * 获得支付参数
	 * @access public 
	 * @param array $order(orderId,amount,orderTime,bankId) 
	 * @param bool $isMobile是否手机支付
	*/
	public function getPayCode($order=array()){
		//待请求参数数组
        $param = $this->commonParam('create_instant_order',$order['orderId']) + array(
			'out_trade_no'=>$order['orderId'] , //商户订单号
			'seller_identity_id'=>$this->partner_id, //卖家标志
			'seller_identity_type'=>'MEMBER_ID', //卖家标志类型WEIBO_UID,MEMBER_ID
			'amount' => price_format($order['amount']), //商户订单金额RMB 
			'pay_method'=>'online_bank^'.price_format($order['amount']).'^'.$order['bankId'].',DEBIT,C',
        );
		
		$param['sign']=$this->_genSign($param);
		
		$sHtml = "<form name='payForm' action='".$this->request_url."' method='post' style='display:none'>";
		foreach($param as $key=>$val){
			$sHtml .= "<input type='hidden' name='".$key."' value='".urlencode($val)."'/>";
		}
		$sHtml .= "<input type='submit' value='提交支付'></form>";
		return $sHtml;
	}
	
	/**
	 * 页面返回验证
	 * @access public 
	*/
	public function response(){
		$param=$this->responseParam('b2c_trade_status_sync') + array(
			'out_trade_no'	=>	'',  //交易订单号
			'trade_amount'	=>	'',  //交易金额
			'inner_trade_no'	=>	'',  //内部交易凭证号
			'fee'	=>	'',  //平台方费用
			'gmt_create' =>	'',  //交易创建时间
			'gmt_payment'	=>	'',  //(N)交易支付时间
			'gmt_close'	=>	'',  //(N)交易关闭时间
			'trade_status' => '', //交易状态:PAY_FINISHED,TRADE_FINISHED 
		);
		foreach($param as $k=>$v){
			if(empty($v) && isset($_REQUEST[$k])){
				$param[$k]=trim($_REQUEST[$k]);
			}
			if($param[$k]==''){
				unset($param[$k]);	
			}
		}
		
		$verify=$this->_chkSign($param,$param['sign']);
		if(empty($verify)){
			$this->error='返回签名错误';
			return false;	
		}
		
		if($param['trade_status']=='TRADE_FINISHED' || $param['trade_status']=='PAY_FINISHED'){ //支付成功
			$_REQUEST['order_no']=$param['out_trade_no'];	
			$_REQUEST['amount']=$param['trade_amount'];	
			return true;
		}else{
			$this->error='错误的返回代码'.$param['error_code'].':'.$param['error_message'];
			return false;
		}
	}
	
	/**
	 * 查询订单
	 * @access public 
	 * @param array $order(orderId,startTime,endTime) 
	*/
	public function query($order_id=''){
		//待请求参数数组
        $param = $this->commonParam('query_b2c_order',$order_id) + array(
			'start_time' => substr($order_id,0,8)."000000", //开始时间14位YmdHis
			'end_time' =>  substr($order_id,0,8)."235959", //结束时间14位YmdHis
			'out_trade_no'=>$order_id , //商户订单号
        );
		
		$param['sign']=urlencode($this->_genSign($param));
		
		$url=$this->request_url.'?'.http_build_query($param);
		$result=httpPost($url);		
		
		$result =urldecode($result);
		$result = json_decode($result,true);
		
		$response=array('success'=>0,'amount'=>0,'result'=>$result);
		if($result['response_code']=='APPLY_SUCCESS'){
			$record=explode('~',$result['record_list']);
			if($record[2]=='TRADE_FINISHED' || $record[2]=='PAY_FINISHED'){ //支付成功
				$response['success'] = true;
				$response['amount'] = $record[3]; //订单支付金额
			}else{
				$this->error='无交易记录';
			}
		}else{
			$this->error='请求错误';
		}
		return $response;
	}
	
	
	/**
	 * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
	 * @param $para 需要拼接的数组
	 * return 拼接完成以后的字符串
	 */
	private function _createParamString($param=array()){
		ksort($param);
		$string  = "";
		foreach($param as $key=>$val){
			if($val=='' || $key=='sign' || $key=='sign_type' || $key=='sign_version'){
				continue;
			}
			$string .= $key."=".$val."&";
		}
		//去掉最后一个&字符
		$string=substr($string,0,-1);
		
		//如果存在转义字符，那么去掉转义
		if(get_magic_quotes_gpc()){
			$string = stripslashes($string);
		}
		return $string;
	}
	
	/**
	 * 生成签名
	 * @param $param 签名参数
	 * return 签名
	 */
	private function _genSign($param=array()){
		$string=$this->_createParamString($param);
		$sign='';
		if($param['sign_type']=='RSA'){
			$sign = $this->_rsaSign($string);
		}else{
			$string = $string.$this->md5_key;
			$sign = strtolower(md5($string));
		}
		return $sign;
	}

	/**
	 * 检查签名是否正确
	 * @param $param 签名参数
	 * return bool
	 */
	private function _chkSign($param=array(),$signString=''){
		$string=$this->_createParamString($param);
		$result=false;
		if($param['sign_type']=='RSA'){
			$result = $this->_rsaVerify($string, $signString);		
		}else{
			$string = $string.$this->md5_key;
			$sign = strtolower(md5($string));
			$result= $sign==$signString;
		}
		return $result;
	}

	//请求公用参数
	private function commonParam($service='',$order_no=''){
		return array(
			'service'=>$service, //接口名称
			'version'=>'1.0', //版本
			'request_time'=>date("YmdHis"), //请求时间
			'partner_id'=>$this->partner_id, //合作者身份ID 
			'_input_charset'=>'utf-8', //参数编码字符集
			'sign'=>'',
			'sign_type'=>$this->sign_type, //RSA、MD5
			'notify_url'=>$this->notify_url, //系统异步回调通知地址
			'return_url'=>sprintf($this->return_url,$order_no), //页面跳转同步返回页面路径
			//'memo'=>'', //(N)备注
		);
	}
	
	//返回公用参数
	private function responseParam($notify_type=''){
		return array(
			'notify_type' =>$notify_type, //通知类型:b2c_trade_status_sync
			'notify_id'	=>	'',  //通知编号
			'_input_charset'=>'', //参数编码字符集
			'notify_time'=>'', //通知时间
			'sign'=>'', //
			'sign_type'=>$this->sign_type, //RSA、MD5
			'version'=>'', //接口版本号
			'memo' => '' , //(N)备注
			'error_code' => '' , //(N)返回错误码
			'error_message'=>'', //(N)返回错误原因
		);
	}
	/**
	
	 * RSA加密
	 */
	private function _rsaSign($data=''){
		$res = openssl_get_privatekey($this->rsa_private);
		openssl_sign($data, $sign, $res, OPENSSL_ALGO_SHA1);
		openssl_free_key($res);
		//base64编码
		return base64_encode($sign);
	}
	
	/**
	 * RSA验签
	 */
	private function _rsaVerify($data='',$sign=''){
		$res = openssl_get_publickey($this->rsa_public);
		$result = (bool)openssl_verify($data, base64_decode($sign), $res, OPENSSL_ALGO_SHA1);
		openssl_free_key($res);    
		return $result;
	}
}
?>
