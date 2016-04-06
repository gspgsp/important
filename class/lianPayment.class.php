<?php
/**
 * 连连支付类
 */
class lianPayment{
	private $ReturnUrl=''; //返回前台通知地址
	private $NotifyUrl=''; //后台通知地址
	private $MerchantNo='201408071000001543'; //商户号
	private $MerchantKey='201408071000001543test_20140812'; //md5秘钥
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

		$this->ReturnUrl=APP_URL.'/pay/response/lian'; //返回前台通知地址
		$this->NotifyUrl=APP_URL.'/pay/response/lianNotify'; //后台通知地址
    }

	/**
	 * 获得支付参数
	 * @access public 
	 * @param array $order(orderId,amount,bankId,orderTime,user_id) 
	*/
	public function getPayCode($order=array()){
		if(empty($order['user_id'])) $order['user_id']=10001;		
        $param = array(
			'version' => '1.0' , //固定值
			'charset_name' => 'UTF-8', //GBK、UTF-8、GB2312
			'oid_partner' => $this->MerchantNo , //商户号
			'user_id' => $order['user_id'], //用户ID
			'timestamp'=>date("YmdHis"), //时间戳：YmdHis
			'sign_type' => 'MD5' , //签名类型:RSA/MD5
			
			'busi_partner' => '101001' , //商户业务类型:虚拟商品：101001 实物商品：109001 账户充值：108001
			'no_order' => $order['orderId'] , //商户订单号
			'dt_order'=>$order['orderTime'], //订单时间：YmdHis
			'name_goods'=>'充值', //商品名称
			'info_order' => '' , //(N)订单备注，原样返回
			'money_order'=>price_format($order['amount']), //商户订单金额(元),包含2位小数
			'notify_url' => $this->NotifyUrl , //服务端通知发货地址：只能定义为“OK”
			'url_return' => $this->ReturnUrl , //(N)在收银台跳转到商户指定的地址
			'userreq_ip' => str_replace('.','_',get_ip()) , //(N)用户IP
			'valid_order' => '' , //(N)订单有效时间：默认10080分钟，7天
			'bank_code' => $order['bankId'] , //银行编码，8位数字
			'pay_type' => '1' , //支付方式：1借记卡，8信用卡
        );
		
		$string= $this->_createParamString($param);
		$param['sign']=$this->_md5Sign($string);
		
		//待请求参数数组
		$sHtml = "<form name='payForm' action='https://yintong.com.cn/payment/bankgateway.htm' method='post' style='display:'>";
		foreach($param as $key=>$val){
			$sHtml .= "<input type='hidden' name='".$key."' value='".$val."'/>";
		}
		$sHtml .= "<input type='submit' value='提交支付'></form>";
		return $sHtml;
	}
	
	/**
	 * web认证支付
	 * @access public 
	 * @param array $order(orderId,amount,bankId,orderTime,user_id) 
	*/
	public function webAuth($order=array()){
        $param = array(
			'version' => '1.0' , //固定值
			'charset_name' => 'UTF-8', //GBK、UTF-8、GB2312
			'oid_partner' => $this->MerchantNo , //商户号
			'user_id' => $order['user_id'], //用户ID
			'timestamp'=>date("YmdHis"), //时间戳：YmdHis
			'sign_type' => 'MD5' , //签名类型:RSA/MD5
			
			'busi_partner' => '101001' , //商户业务类型:虚拟商品：101001 实物商品：109001 账户充值：108001
			'no_order' => $order['orderId'] , //商户订单号
			'dt_order'=>$order['orderTime'], //订单时间：YmdHis
			'name_goods'=>'充值', //商品名称
			'info_order' => '' , //(N)订单备注，原样返回
			'money_order'=>price_format($order['amount']), //商户订单金额(元),包含2位小数
			'notify_url' => $this->NotifyUrl , //服务端通知发货地址：只能定义为“OK”
			'url_return' => $this->ReturnUrl , //(N)在收银台跳转到商户指定的地址
			'userreq_ip' => str_replace('.','_',get_ip()) , //(N)用户IP
			'valid_order' => '' , //(N)订单有效时间：默认10080分钟，7天
			'bank_code' => $order['bankId'] , //银行编码，8位数字
			'pay_type' => 'D' , //支付方式：D认证支付
			'no_agree' => '' , //(N)签约协议号：
			'risk_item' => '{"frms_ware_category":"101","user_info_mercht_userno":"'.$order['user_id'].'","user_info_bind_phone":"'.$order['mobile'].'","user_info_dt_register":"'.date("YmdHis").'"}' , //JSON格式：风控参数：frms_ware_category-商品栏目,user_info_mercht_userno-客户编号,user_info_bind_phone-绑定手机号,user_info_dt_register-注册时间
			'id_type' => '0' , //证件类型:0-身份证
			'id_no' => $order['idcard'] , //证件号码
			'acct_name' => $order['name'] , //银行账号姓名
			'card_no' => $order['cardNo'] , //(N)银行卡号
        );
		
		$string= $this->_createParamString($param);
		$param['sign']=$this->_md5Sign($string);
		print_r($param);exit;
		
		//待请求参数数组
		$sHtml = "<form name='payForm' action='https://yintong.com.cn/payment/authpay.htm' method='post' style='display:'>";
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
			'oid_partner' => $this->MerchantNo , //商户号
			'sign_type' => 'MD5' , //签名类型:RSA/MD5

			'dt_order'=>'', //订单时间：YmdHis
			'no_order' => '', //商户订单号
			'oid_paybill'=>'', //连连订单号
			'money_order'=>'',
			'result_pay'=>'', //支付结果：SUCCESS-成功
			'settle_date'=>'', //(N)清算日
			'info_order'=>'', //(N)订单描述
			'pay_type' => '' , //(N)支付方式：1借记卡，8信用卡:D-认证支付
			'bank_code'=>'', //(N)银行编号
			//'no_agree'=>'', //(N)银通签约协议号
			//'id_type'=>'', //(N)0-身份证，1户口簿
			//'id_no'=>'', //(N)证件号码
			//'acct_name'=>'', //(N)银行账号姓名
		);
		foreach($param as $k=>$v){
			if(empty($v) && isset($_REQUEST[$k])){
				$param[$k]=trim($_REQUEST[$k]);
			}	
		}
		$sign=$_REQUEST['sign'];

		//把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
		$string= $this->_createParamString($param);
		$verify = $this->_md5Verify($string, $sign);		
		if(empty($verify)){
			$this->error='返回签名错误';
			return false;	
		}
		if($param['result_pay']=='SUCCESS'){ //支付成功：01，等待付款中	00；付款失败02
			$_REQUEST['order_no']=$param['no_order'];	
			$_REQUEST['amount']=$param['money_order'];	
			$_REQUEST['outer_no']=$param['oid_paybill'];	//外部订单号
			#notify_url时响应json数据{"ret_code":"0000","reg_msg":"交易成功"}			
			return true;
		}else{
			$this->error='错误的信息'.$param['result_pay'];
			return false;
		}
	}
	
	/**
	 * 检查订单状态
	 * @access public 
	*/
	public function query($order_id=''){
        $param = array(
			'oid_partner' => $this->MerchantNo , //商户号
			'sign_type' => 'MD5' , //签名类型:RSA/MD5
			
			'no_order' => $order_id , //(N)商户订单号
			'dt_order'=>date("YmdHis"), //订单时间：YmdHis
			'oid_paybill'=>'', //(N)连连订单号
			'query_version'=>'1.0', //(N)版本号
        );
		$string= $this->_createParamString($param);
		$param['sign']=$this->_md5Sign($string);
		
		$url='https://yintong.com.cn/traderapi/orderquery.htm';
		$result=$this->_curlJson($url,$param);
		$response=array('success'=>0,'amount'=>0,'result'=>$result);
		$result=json_decode($result,true);
		if($result['ret_code']=='0000'){ //查询成功
			if($result['result_pay']=='SUCCESS'){ //SUCCESS-成功,WAITING 等待支付
				$response['success'] = 1;
				$response['amount'] = $result['money_order']; //订单支付金额
			}else{
				$this->error='无交易记录';
			}
		}else{
			$this->error=$result['ret_msg'];
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
			if($val=='' || $key=='sign'){
				continue;
			}
			$string .= $key."=".$val."&";
		}
		return substr($string,0,-1);
	}
	
	/**
	 * 加密
	 */
	private function _md5Sign($data=''){
		return md5($data.'&key='.$this->MerchantKey);
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
	private function _curlJson($url,$data){
    	$data = json_encode($data);     
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);  
		curl_setopt($ch, CURLOPT_HEADER, false);
		$header=array(
			'Accept: application/json',
			'Content-Type: application/json; charset=utf-8',			
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
