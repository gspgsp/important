<?php
/**
 * 快钱-快捷支付类
 */
class kuaiqianPayment{
	private $certFile=''; //证书路径
	private $certPasswd='vpos123'; //证书密码
	private $merchantId='104110045112012'; //商户号
	private $terminalId='00002012'; //终端编号
	private $version=''; //商户号
	public $error='';
	public $order=array();
	
	/**
	 * 构造函数
	 * @access public 
	 */
    public function __construct(array $config){
		if(!empty($config)){
			$this->merchantId=$config['merchantId'];
			$this->terminalId=$config['terminalId'];
			$this->certPasswd=$config['certPasswd'];
			$this->certFile=ROOT_PATH.$config['certFile'];
		}
	 	$this->version='1.0';
	}

	/**
	 * 获得支付参数
	 * @access public 
	 * @param array $order(log_sn,amount) 
	*/
	public function getPayCode($order=array()){
	}
	
	/**
	 * 页面返回验证
	 * @access public 
	*/
	public function response(){

	}
	
	/**
	 * 绑定银行卡
	 * @access public 
	 * @param array $user 用户信息
	 		(user_id-用户ID,mobile-手机号,cardNo-银行卡号,name-姓名,idcard-18位身份证,order_no-订单号)
	 * @return array(err-是否错误,msg-信息提醒,token-短信令牌)
	*/
	public function bindQuery($user=array()){
		$param=array(
			'customerId'=>$user['user_id'], //客户号			 
			'phoneNO'=>$user['mobile'], //手机号			 
			'externalRefNumber'=>$user['order_no'], //外部跟踪编号-订单号-随机号			 
			'pan'=>$user['cardNo'], //卡号			 
			'cardHolderName'=>$user['name'], //客户姓名			 
			'idType'=>'0', //证件类型			 
			'cardHolderId'=>$user['idcard'], //客户身份证号			 
		);
		
		$xmlstr = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>";
        $xmlstr.= "<MasMessage xmlns=\"http://www.99bill.com/mas_cnp_merchant_interface\">";
        $xmlstr.= "<version>".$this->version."</version>";
        $xmlstr.= "<indAuthContent>";
			$xmlstr.= "<merchantId>".$this->merchantId."</merchantId>";
			$xmlstr.= "<terminalId>".$this->terminalId."</terminalId>";
			$xmlstr.= "<customerId>".$param['customerId']."</customerId>";
			$xmlstr.= "<externalRefNumber>".$param['externalRefNumber']."</externalRefNumber>";
			$xmlstr.= "<pan>".$param['pan']."</pan>";
			$xmlstr.= "<cardHolderName>".$param['cardHolderName']."</cardHolderName>";
			$xmlstr.= "<idType>".$param['idType']."</idType>";
			$xmlstr.= "<cardHolderId>".$param['cardHolderId']."</cardHolderId>";
			//if(!empty($param['cvv2'])){
				//$xmlstr.= "<expiredDate>".$param['expiredDate']."</expiredDate>";
				//$xmlstr.= "<cvv2>".$param['cvv2']."</cvv2>";
			//}
			$xmlstr.= "<phoneNO>".$param['phoneNO']."</phoneNO>";
        $xmlstr.= "</indAuthContent>";
        $xmlstr.= "</MasMessage>";
		
		$result=$this->_curlXML('/cnp/ind_auth',$xmlstr);
		return $this->_parseResponse($result,array('token'));
	}

	/**
	 * 绑定验证短信
	 * @access public 
	 * @param array $user 用户信息
	 		(user_id-用户ID,mobile-手机号,cardNo-银行卡号,order_no-订单号,vcode-手机验证码,token-短信令牌)
	 * @return array(err-是否错误,msg-信息提醒,token-短信令牌)
	*/
	public function bindVerify($data=array()){
		$param=array(
			'customerId'=>$data['user_id'], //客户号			 
			'phoneNO'=>$data['mobile'], //手机号			 
			'externalRefNumber'=>$data['order_no'], //原请求绑定号			 
			'pan'=>$data['cardNo'], //卡号			 
			'validCode'=>$data['vcode'], //手机验证码			 
			'token'=>$data['token'], //手机验证码令牌			 
		);
		
		$xmlstr = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>";
        $xmlstr.= "<MasMessage xmlns=\"http://www.99bill.com/mas_cnp_merchant_interface\">";
        $xmlstr.= "<version>".$this->version."</version>";
        $xmlstr.= "<indAuthDynVerifyContent>";
			$xmlstr.= "<merchantId>".$this->merchantId."</merchantId>";
			$xmlstr.= "<customerId>".$param['customerId']."</customerId>";
			$xmlstr.= "<externalRefNumber>".$param['externalRefNumber']."</externalRefNumber>";
			$xmlstr.= "<pan>".$param['pan']."</pan>";
			$xmlstr.= "<phoneNO>".$param['phoneNO']."</phoneNO>";
			$xmlstr.= "<validCode>".$param['validCode']."</validCode>";
			$xmlstr.= "<token>".$param['token']."</token>";
        $xmlstr.= "</indAuthDynVerifyContent>";
        $xmlstr.= "</MasMessage>";
		
		$result=$this->_curlXML('/cnp/ind_auth_verify',$xmlstr);
		return $this->_parseResponse($result);
	}
	
	/**
	 * 绑定后快捷支付
	 * @access public 
	 * @param array $order 交易参数--必须传入
	 		(user_id-用户ID,cardNo-银行卡号,order_no-订单号,amount-订单金额) 
	 * @return array(err-是否错误,msg-信息提醒,token-短信令牌)
	*/
	public function bindPay($order=array()){
		$param=array(
			'customerId'=>$order['user_id'], //客户号			 
			'storableCardNo'=>$this->_shortCardNo($order['cardNo']), //短卡号			 
			'externalRefNumber'=>$order['order_no'], //外部跟踪编号-订单号			 
			'amount'=>$order['amount'], //交易金额			 
		);
		
        $str = "<extMap>";
        $str.= "<extDate><key>phone</key><value></value></extDate>";
        $str.= "<extDate><key>validCode</key><value></value></extDate>";
        $str.= "<extDate><key>savePciFlag</key><value>0</value></extDate>"; //保存鉴权信息:0-不绑定	
        $str.= "<extDate><key>token</key><value></value></extDate>";
        $str.= "<extDate><key>payBatch</key><value>2</value></extDate>";
        $str.= "</extMap>";
        $xmlstr = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
        $xmlstr.= "<MasMessage xmlns=\"http://www.99bill.com/mas_cnp_merchant_interface\">";
			$xmlstr.= "<version>".$this->version."</version>";
			$xmlstr.= "<TxnMsgContent>";
				$xmlstr.= "<txnType>PUR</txnType>";
				$xmlstr.= "<interactiveStatus>TR1</interactiveStatus>";
				$xmlstr.= "<merchantId>".$this->merchantId."</merchantId>";
				$xmlstr.= "<terminalId>".$this->terminalId."</terminalId>";
				//$xmlstr.= "<tr3Url>".$this->terminalId."</tr3Url>"; //服务器回调地址
				$xmlstr.= "<entryTime>".date("YmdHis")."</entryTime>"; //商户发送交易请求时的系统时间
				$xmlstr.= "<storableCardNo>".$param['storableCardNo']."</storableCardNo>";
				$xmlstr.= "<amount>".$param['amount']."</amount>";
				$xmlstr.= "<externalRefNumber>".$param['externalRefNumber']."</externalRefNumber>";
				$xmlstr.= "<customerId>".$param['customerId']."</customerId>";
				$xmlstr.= "<spFlag>QPay02</spFlag>";
				$xmlstr.= $str;
			$xmlstr.= "</TxnMsgContent>";
        $xmlstr.= "</MasMessage>";

		$result=$this->_curlXML('/cnp/purchase',$xmlstr);
		return $this->_parseResponse($result);
	}
		
	/**
	 * 获取短银行卡号（取前6位后四位）
	 * @access private 
	 * @param string $card_no 银行卡号
	 * @return string
	*/
	private function _shortCardNo($card_no=''){
		if(strlen($card_no)>10){
			return substr($card_no,0,6).substr($card_no,-4);
		}
		return $card_no;
	}
	
	/**
	 * 快捷支付时：Step1：获取手机动态码
	 * @access public 
	 * @param array $order 交易参数--必须传入
	 		(user_id-用户ID,mobile-手机号,cardNo-银行卡号,order_no-订单号,amount-订单金额) 
	 * @param array $user  用户信息--第二次可不必传入
	 		(name-姓名,idcard-18位身份证,expire-卡有效期,cvv2-校验码)
	 * @return array(err-是否错误,msg-信息提醒,token-短信令牌)
	*/
	public function getDynCode($order=array(),$user=array()){
		$param=array(
			'customerId'=>$order['user_id'], //客户号			 
			'storablePan'=>$this->_shortCardNo($order['cardNo']), //短卡号			 
			'externalRefNumber'=>$order['order_no'], //外部跟踪编号-订单号			 
			'amount'=>$order['amount'], //交易金额			 
		);
		if(!empty($user['name'])){ //第一次绑定时
			$param += array(
				'cardHolderName'=>$user['name'], //客户姓名			 
				'idType'=>'0', //证件类型			 
				'cardHolderId'=>$user['idcard'], //客户身份证号			 
				'pan'=>$order['cardNo'], //卡号			 
				'expiredDate'=>'', //卡效期			 
				'phoneNO'=>$order['mobile'], //手机号			 
				'cvv2'=>'', //安全校验值			 
			);
		}
		
		$xmlstr = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
        $xmlstr.= "<MasMessage xmlns=\"http://www.99bill.com/mas_cnp_merchant_interface\">";
        $xmlstr.= "<version>".$this->version."</version>";
        $xmlstr.= "<GetDynNumContent>";
			$xmlstr.= "<merchantId>".$this->merchantId."</merchantId>";
			$xmlstr.= "<customerId>".$param['customerId']."</customerId>";
			$xmlstr.= "<externalRefNumber>".$param['externalRefNumber']."</externalRefNumber>";
			
			if(!empty($param['cardHolderName'])){
				$xmlstr.= "<cardHolderName>".$param['cardHolderName']."</cardHolderName>";
				$xmlstr.= "<idType>".$param['idType']."</idType>";
				$xmlstr.= "<cardHolderId>".$param['cardHolderId']."</cardHolderId>";
				$xmlstr.= "<pan>".$param['pan']."</pan>";
				//$xmlstr.= "<bankId></bankId>";
				if(!empty($param['cvv2'])){
					$xmlstr.= "<expiredDate>".$param['expiredDate']."</expiredDate>";
					$xmlstr.= "<cvv2>".$param['cvv2']."</cvv2>";
				}
				$xmlstr.= "<phoneNO>".$param['phoneNO']."</phoneNO>";
			}else{
				$xmlstr.= "<storablePan>".$param['storablePan']."</storablePan>";
			}
			
			$xmlstr.= "<amount>".$param['amount']."</amount>";
        $xmlstr.= "</GetDynNumContent>";
        $xmlstr.= "</MasMessage>";
		
		$result=$this->_curlXML('/cnp/getDynNum',$xmlstr);
		return $this->_parseResponse($result,array('token'));
	}

	/**
	 * 订单消费
	 * @access public 
	 * @param array $order 交易参数--必须传入
	 		(user_id-用户ID,mobile-手机号,cardNo-银行卡号,order_no-订单号,amount-订单金额,vcode-手机验证码,token-短信令牌) 
	 * @param array $user  用户信息--第二次可不比传入
	 		(name-姓名,idcard-18位身份证,expire-卡有效期,cvv2-校验码)
	 * @return array(err-是否错误,msg-信息提醒,issuer-发行银行)
	*/
	public function purchase($order=array(),$user=array()){
		$param=array(
			'validCode'=>$order['vcode'], //手机验证码			 
			'token'=>$order['token'], //手机验证码令牌			 
			'customerId'=>$order['user_id'], //客户号			 
			'storableCardNo'=>$this->_shortCardNo($order['cardNo']), //短卡号			 
			'externalRefNumber'=>$order['order_no'], //外部跟踪编号-订单号			 
			'amount'=>$order['amount'], //交易金额			 
			'payBatch'=>2, //快捷支付批次：1-首次,2-再次			 
		);
		if(!empty($user['name'])){ //第一次绑定时
			$param['payBatch']=1; //首次
			$param += array(
				'phone'=>$order['mobile'], //手机号			 
				'cardNo'=>$order['cardNo'], //卡号			 
				'expiredDate'=>'', //卡效期			 
				'cvv2'=>'', //安全校验值			 
				'cardHolderName'=>$user['name'], //客户姓名			 
				'cardHolderId'=>$user['idcard'], //客户身份证号			 
				'idType'=>'0', //证件类型			 
			);
		}

        $str = "<extMap>";
        $str.= "<extDate><key>phone</key><value>".$param['phone']."</value></extDate>";
        $str.= "<extDate><key>validCode</key><value>".$param['validCode']."</value></extDate>";
        $str.= "<extDate><key>savePciFlag</key><value>1</value></extDate>"; //保存鉴权信息:0-不绑定，1绑定	
        $str.= "<extDate><key>token</key><value>".$param['token']."</value></extDate>";
        $str.= "<extDate><key>payBatch</key><value>".$param['payBatch']."</value></extDate>";
        $str.= "</extMap>";
        $xmlstr = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
        $xmlstr.= "<MasMessage xmlns=\"http://www.99bill.com/mas_cnp_merchant_interface\">";
			$xmlstr.= "<version>".$this->version."</version>";
			$xmlstr.= "<TxnMsgContent>";
				$xmlstr.= "<txnType>PUR</txnType>";
				$xmlstr.= "<interactiveStatus>TR1</interactiveStatus>";
				
				if($param['payBatch']==1){ //首次使用
					$xmlstr.= "<cardNo>".$param['cardNo']."</cardNo>";
					if(!empty($param['cvv2'])){
						$xmlstr.= "<expiredDate>".$param['expiredDate']."</expiredDate>";
						$xmlstr.= "<cvv2>".$param['cvv2']."</cvv2>";
					}
					$xmlstr.= "<cardHolderName>".$param['cardHolderName']."</cardHolderName>";
					$xmlstr.= "<cardHolderId>".$param['cardHolderId']."</cardHolderId>";
					$xmlstr.= "<idType>".$param['idType']."</idType>";
				}else{
					$xmlstr.= "<storableCardNo>".$param['storableCardNo']."</storableCardNo>";
				}
				$xmlstr.= "<amount>".$param['amount']."</amount>";
				$xmlstr.= "<merchantId>".$this->merchantId."</merchantId>";
				$xmlstr.= "<terminalId>".$this->terminalId."</terminalId>";
				//$xmlstr.= "<tr3Url>".$this->terminalId."</tr3Url>"; //服务器回调地址
				$xmlstr.= "<entryTime>".date("YmdHis")."</entryTime>"; //商户发送交易请求时的系统时间
				$xmlstr.= "<externalRefNumber>".$param['externalRefNumber']."</externalRefNumber>";
				$xmlstr.= "<customerId>".$param['customerId']."</customerId>";
				$xmlstr.= "<spFlag>QuickPay</spFlag>";
				$xmlstr.= $str;
			$xmlstr.= "</TxnMsgContent>";
        $xmlstr.= "</MasMessage>";

		$result=$this->_curlXML('/cnp/purchase',$xmlstr);
		return $this->_parseResponse($result,array('issuer'));
	}

	/**
	 * 订单查询
	 * @access public 
	 * @param string $order_no 订单号
	 * @return array(err-是否错误,msg-信息提醒,issuer-发行银行)
	*/
	public function query($order_no=''){
        $xmlstr = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
        $xmlstr.= "<MasMessage xmlns=\"http://www.99bill.com/mas_cnp_merchant_interface\">";
			$xmlstr.= "<version>".$this->version."</version>";
			$xmlstr.= "<QryTxnMsgContent>";
				$xmlstr.= "<externalRefNumber>".$order_no."</externalRefNumber>";
				$xmlstr.= "<txnType>PUR</txnType>"; //交易类型
				$xmlstr.= "<merchantId>".$this->merchantId."</merchantId>";
				$xmlstr.= "<txnStatus></txnStatus>"; //交易状态:S-成功,F-失败
				$xmlstr.= $str;
			$xmlstr.= "</QryTxnMsgContent>";
        $xmlstr.= "</MasMessage>";

		$result=$this->_curlXML('/cnp/query_txn',$xmlstr);
		
		$response=array('success'=>0,'amount'=>0,'result'=>$result);
		if(strstr($result,'<externalRefNumber>'.$order_no.'</externalRefNumber>')){ //返回成功
			if(strstr($result,'<txnStatus>S</txnStatus>')){
				$response['success']=1;	  
			}
			$amount=$this->_xmlParse($result,'amount');
			$response['amount']=$amount;
		}else{
			$this->error='错误的查询结果'; //错误代码
		}
		return $response;
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
		if(strstr($content,'<responseCode>00</responseCode>')){ //返回成功
			foreach($ext as $v){
				$response[$v]=$this->_xmlParse($content,$v);
			}		
			$response['msg']=$this->_xmlParse($content,'responseTextMessage');
		}else{
			$response['err']=1;
			if(strstr($content,'<responseTextMessage>')){
				$response['msg']=$this->_xmlParse($content,'responseTextMessage');
			}else{
				$response['msg']=$this->_xmlParse($content,'errorMessage');
			}
		}
		return $response;		
	}

	/**
	 * curl请求快捷支付
	 * @access private 
	 * @param string $interface 请求接口
	 * @param string $data 请求报文
	 * @return string
	 */
	private function _curlXML($interface='',$data=''){
		set_time_limit(120);

		wlog(CACHE_PATH.'log/quick/line.log', date("Y-m-d H:i:s")."==".$interface."\r\nRequest:".$data."\r\n");

		$url='https://mas.99bill.com:443'.$interface;
		$loginInfo = array("Authorization: Basic " . base64_encode($this->merchantId.":".$this->certPasswd));

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,2);
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,FALSE);
		curl_setopt($ch, CURLOPT_CAINFO, $this->certFile);
		curl_setopt($ch, CURLOPT_SSLCERT, $this->certFile);
		curl_setopt($ch, CURLOPT_SSLCERTPASSWD, $this->certPasswd);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, $loginInfo);

		$result=curl_exec($ch);
		
		$this->error='ok';
		if(curl_errno($ch)){
			$this->error=curl_error($ch);
		}else{
			curl_close($ch);
		}
				
		wlog(CACHE_PATH.'log/quick/line.log',"Response:".$result."(".$this->error.")"."\r\n\r\n");
		return $result;
	}
}
?>
