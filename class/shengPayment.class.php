<?php
/**
 * 盛付通支付类
 */
class shengPayment{
	private $PageUrl=''; //返回前台通知地址
	private $BackUrl=''; //撤销地址
	private $NotifyUrl=''; //后台通知地址
	private $MerchantNo='100894'; //商户号
	private $MerchantKey='shengfutongSHENGFUTONGtest'; //md5秘钥
	public $error='';
	
	/**
	 * 构造函数
	 * @access public 
	 */
    public function __construct(array $config){
		if(!empty($config)){
			$this->MerchantNo=$config['MerchantNo'];
			$this->MerchantKey=$config['MerchantKey'];
		}

		$this->PageUrl=APP_URL.'/pay/response/sheng'; //返回前台通知地址
		$this->BackUrl=APP_URL.'/pay/response/shengNotify'; //撤销地址
		$this->NotifyUrl=APP_URL.'/my/cash/recharge'; //后台通知地址
    }

	/**
	 * 获得支付参数
	 * @access public 
	 * @param array $order(log_sn,amount) 
	*/
	public function getPayCode($order=array()){
        $param = array(
			'Name' => 'B2CPayment' , //固定值-版本名称
			'Version' => 'V4.1.1.1.1' , //固定值
			'Charset' => 'UTF-8', //GBK、UTF-8、GB2312
			
			'MsgSender' => $this->MerchantNo , //商户号
			'SendTime' => '', //(N)发送支付请求时间
			'OrderNo' => $order['orderId'] , //商户订单号
			'OrderAmount' => price_format($order['amount']), //商户订单金额(元),包含2位小数
			'OrderTime' => $order['orderTime'] , //提交时间：14位：YmdHis
			'PayType' => 'PT001' , //(N)支付类型编码,PT001-网银
			'PayChannel' => '19' , //(N)支付渠道，PT001网银时，直连支付（19 储蓄卡，20 信用卡 12企业网银）
			'InstCode' => $order['bankId'] , //(N)银行编码，如ICBC
			
			'PageUrl' => $this->PageUrl , //支付成功后客户端浏览器回调地址 
			'BackUrl' => $this->BackUrl , //(N)在收银台跳转到商户指定的地址
			'NotifyUrl' => $this->NotifyUrl , //服务端通知发货地址：只能定义为“OK”
					   
			'ProductName' => '' , //(N)商品名称
			'BuyerContact' => '' , //(N)支付人联系方式
			'BuyerIp'=> get_ip(), //用户下单IP
			'Ext1' => '' , //(N)扩展字段,照原样返回
			'SignType' => 'MD5' , //签名类型
        );
		
		$string= $this->_createParamString($param);
		$param['SignMsg']=$this->_md5Sign($string);
		
		//待请求参数数组
		$sHtml = "<form name='payForm' action='https://mas.sdo.com/web-acquire-channel/cashier.htm' method='post' style='display:none'>";
		foreach($param as $key=>$val){
			$sHtml .= "<input type='hidden' name='".$key."' value='".$val."'/>";
		}
		$sHtml .= "<input type='submit' value='提交支付'></form>";
		return $sHtml;
	}
	
	/**
	 * 页面返回验证
	 * @access public 
	*/
	public function response(){
		//所有待签名的参数
		$param=array(
			'Name' => 'REP_B2CPAYMENT' , //固定值-版本名称
			'Version' => 'V4.1.2.1.1' , //固定值
			'Charset' => 'UTF-8', //GBK、UTF-8、GB2312
			'TraceNo' => '', //请求序列号,报文发起方唯一消息标识
			
			'MsgSender' => '' , //发送方标识,默认为:SFT
			'SendTime' => '', //发送支付请求时间
			'InstCode' => '' , //银行编码
			'OrderNo' => '', //商户订单号
			'OrderAmount' => '', //商户订单金额(元),包含2位小数
			'TransNo' => '' , //盛付通交易号
			'TransAmount' => '', //盛付通实际支付金额
			'TransStatus' => '', //支付状态
			'TransType' => '', //盛付通交易类型
			'TransTime' => '', //盛付通交易时间:yyyyMMddHHmmss
			'MerchantNo' =>$this->MerchantNo, //商户号
			'ErrorCode' =>'', //错误代码
			'ErrorMsg' =>'', //商户交易错误消息
			'Ext1' =>'', //扩展1
			'SignType' => 'MD5' , //签名类型
			//'SignMsg'=> '', //签名串
		);
		foreach($param as $k=>$v){
			if(empty($v) && isset($_REQUEST[$k])){
				$param[$k]=trim($_REQUEST[$k]);
			}	
		}
		$sign=$_REQUEST['SignMsg'];
		
		//把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
		$_string = $this->_createParamString($param);
		$verify = $this->_md5Verify($_string, $sign);		
		if(empty($verify)){
			$this->error='返回签名错误';
			return false;	
		}
		if($param['TransStatus']=='01'){ //支付成功：01，等待付款中	00；付款失败02
			$_REQUEST['order_no']=$param['OrderNo'];	
			$_REQUEST['amount']=$param['TransAmount'];	
			$_REQUEST['outer_no']=$param['TransNo'];	//外部订单号
			return true;
		}else{
			$this->error='错误的信息'.$param['ErrorMsg'];
			return false;
		}
	}
	
	/**
	 * 检查订单状态
	 * @access public 
	*/
	public function query($order_id=''){
        $param = array(
			'ServiceCode' => 'QUERY_ORDER_REQUEST' , //固定值-版本名称
			'Version' => 'V4.3.1.1.1' , //固定值
			'Charset' => 'UTF-8', //GBK、UTF-8、GB2312
			'SenderId' => $this->MerchantNo , //商户号
			'SendTime' => date("YmdHis"), //(N)发送支付请求时间
			'MerchantNo' => $this->MerchantNo , //商户号
			'OrderNo' => $order_id, //商户订单号
			'TransNo' => '' , //(N)盛付通订单号
			'Ext1' => '' , //(N)扩展1
			'SignType' => 'MD5' , //签名类型
			'SignMsg'=> '', //签名串
        );
		$string= $this->_createParamString($param);
		$param['SignMsg']=$this->_md5Sign($string);
		
		$url='http://mas.sdo.com/api-acquire-channel/services/queryOrderService?wsdl';
		
		//组装的xml参数
		$data='<?xml version="1.0" encoding="utf-8"?><soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"><soap:Body><queryOrder xmlns="http://www.sdo.com/mas/api/query"><arg0 xmlns=""><extension><ext1>'.$param['Ext1'].'</ext1></extension><header><charset>'.$param['Charset'].'</charset><sendTime>'.$param['SendTime'].'</sendTime><sender><senderId>'.$param['SenderId'].'</senderId></sender><service><serviceCode>'.$param['ServiceCode'].'</serviceCode><version>'.$param['Version'].'</version></service></header><merchantNo>'.$param['MerchantNo'].'</merchantNo><orderNo>'.$param['OrderNo'].'</orderNo><signature><signMsg>'.$param['SignMsg'].'</signMsg><signType>'.$param['SignType'].'</signType></signature><transNo /></arg0></queryOrder></soap:Body></soap:Envelope>';
		$result=$this->_curlXML($url,$data);
		
		//<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/"><soap:Body><ns2:queryOrderResponse xmlns:ns2="http://www.sdo.com/mas/api/query"><return><extension/><header><charset>UTF-8</charset><sendTime>20140709170334</sendTime><sender><senderId>SFT</senderId></sender><service><serviceCode>QUERY_ORDER_RESPONSE</serviceCode><version>V4.3.2.1.1</version></service><traceNo>b3cf969e-cea7-4256-a598-72c7d2314006</traceNo></header><orderAmount>0.05</orderAmount><orderNo>2014070910001702563638</orderNo><returnInfo/><signature><signMsg>8C62961B48CD41E4DD8C04A572CC42FF</signMsg><signType>MD5</signType></signature><transAmoumt>0.05</transAmoumt><transNo>20140709170334159191</transNo><transStatus>01</transStatus><transTime>20140709170334</transTime></return></ns2:queryOrderResponse></soap:Body></soap:Envelope>
		//<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/"><soap:Body><ns2:queryOrderResponse xmlns:ns2="http://www.sdo.com/mas/api/query"><return><extension/><header><charset>UTF-8</charset><sender><senderId>SFT</senderId></sender><service><serviceCode>QUERY_ORDER_RESPONSE</serviceCode><version>V4.3.2.1.1</version></service></header><returnInfo><errorCode>B0524013</errorCode><errorMsg>所查询的订单(订单号为{0})的订单不存在</errorMsg></returnInfo><signature><signMsg>62B986AA9B831741452D2395AD78B68D</signMsg><signType>MD5</signType></signature></return></ns2:queryOrderResponse></soap:Body></soap:Envelope>
		
		$response=array('success'=>0,'amount'=>0,'result'=>$result);
		if(strstr($result,'<serviceCode>QUERY_ORDER_RESPONSE</serviceCode>')){ //返回成功
			if(strstr($result,'<orderNo>'.$order_id.'</orderNo>')){ //需要查询的订单号
				
				//00:等待付款中;01:付款成功";02:付款失败;03:过期;04:撤销成功;05:退款中;06:退款成功;07:退款失败;08:部分退款成功
				preg_match('/<transStatus>(.*?)<\/transStatus>/',$result,$match);
				$response['success']=$match[1]==='01';
				
				preg_match('/<transAmoumt>(.*?)<\/transAmoumt>/',$result,$match);
				$response['amount']=$match[1];
			}elseif(strstr($result,'<errorMsg>')){ //错误代码
				preg_match('/<errorMsg>(.*?)<\/errorMsg>/',$result,$match);
				$this->error=$match[1]; //错误代码
			}
		}else{
			$this->error='错误的查询结果'; //错误代码
		}
		return $response;
	}
	
	/**
	 * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
	 * @param $para 需要拼接的数组
	 * return 拼接完成以后的字符串
	 */
	private function _createParamString($param=array()){
		$string  = "";
		foreach($param as $key=>$val){
			if($val=='' || $key=='SignMsg'){
				continue;
			}
			$string .= $val;
		}
		return $string;
	}
	
	/**
	 * 加密
	 */
	private function _md5Sign($data=''){
		//echo $data.$this->MerchantKey.'<br>';
		return strtoupper(md5($data.$this->MerchantKey));
	}
	
	/**
	 * 验签
	 */
	private function _md5Verify($data='',$sign=''){
		$data=$this->_md5Sign($data);
		return $data==$sign;
	}
	
	/**
	 * curl查询webservice
	 */
	private function _curlXML($url,$data){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);  
		curl_setopt($ch, CURLOPT_HEADER, false);
		$header=array(
			'Accept: application/xml',
			'Content-Type: application/xml; charset=utf-8',			
		);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		$result=curl_exec($ch);
		if(curl_errno($ch)){
			$this->error=curl_error($ch);
		}else{
			curl_close($ch);
		}
		return $result;
	}
	
}
?>
