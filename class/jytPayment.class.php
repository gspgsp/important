<?php
/**
 * 金运通-代付
		require_file(APP_LIB.'class/jytPayment.class.php');
		$payment=new jytPayment();
		
		$order=array(
			'orderId'=>date("YmdHis"),		 
			'amount'=>'1',		 
			'bankId'=>'TESTBANK',		 
		);
		$result=$payment->getPayCode($order);
 */
class jytPayment{
	private $merchantId='100060120001'; //商户号
	private $xml=''; //请求内容
	public $error='';
	private $rsa_key='';
	private $rsa_public='';
	
	/**
	 * 构造函数
	 * @access public 
	 */
    public function __construct(array $config){
		if(!empty($config)){
			$this->merchantId=$config['merchantId'];
			$this->rsa_key=$config['rsa_private'];
			$this->rsa_public=$config['rsa_public'];
		}
	}
	
	/**
	 * 首次支付（绑卡）请求鉴权---TD1001
	 * @access public 
	 * @param array $order(order_no,amount,bank_name,bank_pan,name,idcard) 
	*/ 
	public function firstChk($order=array()){
		$param=array(
			'cust_no'=>$order['user_id'],	//客户号
			'order_id'=>$order['order_no'],	//商户订单号	  
			'bank_card_no'=>$order['bank_pan'],	//银行账号	  
			'id_card_no'=>$order['idcard'],	//证件号码	  
			'mobile'=>$order['mobile'],	//手机号  
			'name'=>$order['name'],	//姓名	  
			'tran_amount'=>price_format($order['amount']),	//交易金额：保留小数2位
		);
		
		$param=$this->_genXml($order['order_no'],'TD1001',$param);
		$xml=$this->_curlXML($param);
		
		$result=$this->_parseResponse($xml,array('tran_state'));
		if(empty($result['err']) && $result['tran_state']=='01'){
			$result['err']=0;
		}else $result['err']=1;
		return $result;
	}
	
	/**
	 * 首次支付（绑卡）请求鉴权---TD1001
	 * @access public 
	 * @param array $order(order_no,amount,bank_name,bank_pan,name,idcard) 
	*/ 
	public function firstPay($order=array()){
		$param=array(
			'mobile'=>$order['mobile'],	//手机号  
			'order_id'=>$order['order_no'],	//商户订单号	  
			'verify_code'=>$order['vcode'],	//银行账号	  
		);
		
		$param=$this->_genXml($order['order_no'],'TD4001',$param);
		$xml=$this->_curlXML($param);
		
		$result=$this->_parseResponse($xml,array('tran_state'));
		return $result;
	}

/**
	 * 第二次获取验证码---TD1002
	 * @access public 
	 * @param array $order(order_no,amount,bank_name,bank_pan,name,idcard) 
	*/ 
	public function secChk($order=array()){
		$param=array(
			'cust_no'=>$order['user_id'],	//客户号
			'order_id'=>$order['order_no'],	//商户订单号	  
			'bank_card_no'=>$order['bank_pan'],	//银行账号	  
			'tran_amount'=>price_format($order['amount']),	//交易金额：保留小数2位
		);
		$param=$this->_genXml($order['order_no'],'TD1002',$param);
		$xml=$this->_curlXML($param);
		
		//当响应码为S0000000时有值。01验证成功（验证码已发送）,03银行验证失败
		$result=$this->_parseResponse($xml,array('tran_state'));
		if(empty($result['err']) && $result['tran_state']=='01'){
			$result['err']=0;
		}else $result['err']=1;
		return $result;
	}
	
	/**
	 * 第二次支付---TD4004
	 * @access public 
	 * @param array $order(order_no,amount,bank_name,bank_pan,name,idcard) 
	*/ 
	public function secPay($order=array()){
		$param=array(
			'mobile'=>$order['mobile'],	//手机号  
			'order_id'=>$order['order_no'],	//商户订单号	  
			'verify_code'=>$order['vcode'],	//银行账号	  
		);
		$param=$this->_genXml($order['order_no'],'TD4004',$param);
		$xml=$this->_curlXML($param);
		$result=$this->_parseResponse($xml,array('tran_state'));
		return $result;
	}

	/**
	 * 获得支付参数
	 * @access public 
	 * @param array $order(order_no,amount,bank_name,bank_pan,name,idcard) 
	*/
	public function getPayCode($order=array()){}

	/**
	 * 页面返回验证
	 * @access public 
	*/
	public function response(){

	}
	
	/**
	 * 订单查询
	 * @access public 
	 * @param string $order_no 订单号
	 * @return bool
	*/
	public function query($order_no=''){
		$param=array(
			'order_id'=>$order_no,	//原交易流水号
		);
		$param=$this->_genXml($order_no,'TD2001',$param);
		$xml=$this->_curlXML($param);
		$result=$this->_parseResponse($xml,array('tran_state'));

		$response=array('success'=>0,'amount'=>0,'result'=>$result);
		if(empty($result['err']) && $result['tran_state']=='00'){
			$response['success']=1;	  
			$response['amount']=$result['tran_amount'];	  
		}else{
			$this->error=$result['msg'];
		}
		return $response;
	}
	
	
	private function _genXml($order_no='',$tran_code='',$param=array()){
		$headString='';
		$head=array(
			'version'=>'1.0.0',		  
			'tran_type'=>'01',	 //报文类型包括:请求01,响应02
			'merchant_id'=>$this->merchantId,		  
			'tran_date'=>date('Ymd'),		  
			'tran_time'=>date('His'),		  
			'tran_flowid'=>$this->merchantId.$this->shortOrder($order_no),	//交易流水号	  
			'tran_code'=>$tran_code,	//交易代码	  
		);
		foreach($head as $k=>$v){
			$headString.='<'.$k.'>'.$v.'</'.$k.'>';
		}
		$bodyString='';
		foreach($param as $k=>$v){
			$bodyString.='<'.$k.'>'.$v.'</'.$k.'>';
		}
		$this->xml='<?xml version="1.0" encoding="UTF-8"?><message><head>'.$headString.'</head><body>'.$bodyString.'</body></message>';
		
		$des_key='12345678';
		$des=new jytCryptDes($des_key);
		$xml_enc=$des->encrypt($this->xml); //$xml_enc=$des->decrypt($xml_enc);
		
		$key_enc=$this->_rsaPublicEncrypt($des_key);
		$sign=$this->_rsaSign($this->xml);
		
		$request=array(
			'merchant_id'=>$this->merchantId,   
			'xml_enc'=>$xml_enc,  //对xml的（DES/CBC/PKCS5Padding）加密以后的十六进制字符串  
			'key_enc'=>$key_enc,  //对des秘钥进行（RSA/ECB/PKCS1Padding）加密
			'sign'=>$sign, //对xml进行RSA签名   
		);
		return $request;
	}

	/**
	 * curl请求快捷支付
	 * @access private 
	 * @param string $interface 请求接口
	 * @param string $data 请求报文
	 * @return string
	 */
	private function _curlXML($data=''){
		set_time_limit(120);
		
		wlog(CACHE_PATH.'log/quick/jyt.log', date("Y-m-d H:i:s")."\r\nRequest:".$this->xml."\r\n");
		
		$url='https://www.jytpay.com:9410/JytRNPay/tranCenter/encXmlReq.do';
		//$url='http://test1.jytpay.com:16080/JytRNPay/tranCenter/encXmlReq.do';

		$result=curl_data($url,$data); //curl_data，httpPost
		parse_str($result,$arr);
		
		if(count($arr)!=4){
			$this->error='请求数据失败';
			return '';	
		}
		
		$key_enc=$this->_rsaPrivDecrypt($arr['key_enc']);
		$des=new jytCryptDes($key_enc);
		$xml=$des->decrypt($arr['xml_enc']); //响应内容
		
		wlog(CACHE_PATH.'log/quick/jyt.log',"Response:".$xml."\r\n\r\n");
		return $xml;
	}

	/**
	 * xml节点解析
	 * @access private 
	 * @param string $content 字符串内容
	 * @param string $node 查找节点
	 * @return string
	*/
	private function _xmlParse($content='',$node=''){
		preg_match('/<'.$node.'>(.*?)<\/'.$node.'>/',$content,$match);
		return isset($match[1]) ? $match[1] : '';
	}

	/**
	 * 解析返回的结果
	 * @access public 
	 * @param string $content 解析内容
	 * @param array $ext 额外结点
	 * @return array(err-是否错误,msg-信息提醒,ext-追加的信息)
	*/
	private function _parseResponse($content='',$ext=array()){
		$response=array('err'=>0,'msg'=>'');
		if(strlen($content)<20){
			return array('err'=>1,'msg'=>'请求失败:'.$this->error);
		}
		if(strstr($content,'<resp_code>S0000000</resp_code>')){ //返回成功
			foreach($ext as $v){
				$response[$v]=$this->_xmlParse($content,$v);
			}		
			$response['msg']=$this->_xmlParse($content,'resp_desc');
		}else{
			$response['err']=1;
			if(strstr($content,'<resp_desc>')){
				$response['msg']=$this->_xmlParse($content,'resp_desc');
			}else{
				$response['msg']='支付异常:'.$this->_xmlParse($content,'tran_state');	
			}
		}
		return $response;		
	}

	
	private function _rsaSign($data=''){
		$res = openssl_get_privatekey($this->rsa_key);
		openssl_sign($data, $sign, $res, "sha1WithRSAEncryption");
		openssl_free_key($res);
		return bin2hex($sign);
	}
	
	private function _rsaPrivDecrypt($content=''){
		$content=$this->hex2bin($content);
		$res = openssl_get_privatekey($this->rsa_key);
		if(openssl_private_decrypt($content, $result, $res, OPENSSL_PKCS1_PADDING)){
			return $result;
		} 
		return '';
	}
	
	private function _rsaPublicEncrypt($data){
		$res=openssl_get_publickey($this->rsa_public);
		if (openssl_public_encrypt($data, $encrypted, $res,OPENSSL_PKCS1_PADDING)){
			return bin2hex($encrypted);
		} 
		return ''; 
	}
	
	//16进制转2进制
	private function hex2bin($data){
		$len = strlen($data);
		return pack("H" . $len, $data); 
	}
	
	//短订单号
	private function shortOrder($order_no=''){
		return substr($order_no,2); 
	}
}

class jytCryptDes{
	private $key ="";
	private $iv = "";
	
	public function __construct($key) {
		$this->key = $key;
		$this->iv= $key;
	}

    public function encrypt($str) {
		//加密，返回大写十六进制字符串
		$size = mcrypt_get_block_size (MCRYPT_DES, MCRYPT_MODE_CBC );
		$str = $this->pkcs5Pad ( $str, $size );
		$hexString = bin2hex( mcrypt_cbc(MCRYPT_DES, $this->key, $str, MCRYPT_ENCRYPT, $this->iv ) );
		return ( $hexString );
	}

	public function decrypt($str) {
		//解密  
		$strBin = $this->hex2bin( strtolower( $str ) );  
		$str = mcrypt_cbc( MCRYPT_DES, $this->key, $strBin, MCRYPT_DECRYPT, $this->iv );
		$str = $this->pkcs5Unpad( $str );
		return $str;
	}
	function hex2bin($data) {  
		$len = strlen($data);
		return pack("H" . $len, $data); 
	}
	function pkcs5Pad($text, $blocksize) {
		$pad = $blocksize - (strlen ( $text ) % $blocksize);
		return $text . str_repeat ( chr ( $pad ), $pad );
	}
	function pkcs5Unpad($text) {
		$pad = ord ( $text {strlen ( $text ) - 1} );  
		if ($pad > strlen ( $text )) return false;
		if (strspn ( $text, chr ( $pad ), strlen ( $text ) - $pad ) != $pad)   return false;  
		return substr ( $text, 0, - 1 * $pad );
	}
}
?>