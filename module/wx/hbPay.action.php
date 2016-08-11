<?php
/**
*微信红包支付功能
*/
class hbPayAction extends homeBaseAction
{
	protected $openid;
	public function __init(){
		if(!isset($_SESSION['weixinAuth'])) exit('noAuthorization');
		$this->openid=$_SESSION['weixinAuth']['openid'];
		// $this->openid="o1SYHw7UuAqoEoM1Yoyk7DEoqp7g";
	}
	//进入红包支付页面
	public function enPay(){
		$this->display('record');
	}
	//
	public function hongbao($re_openid='', $total_amount=100, $total_num=1){
		$parameter = array(
		   'mch_billno' => $this->get_billno(C('WEIXIN_MCHID')),//商户订单
		   'mch_id' => C('WEIXIN_MCHID'),//商户id
		   'wxappid' => C('WEIXIN_APPID'),//服务号appid
		   'send_name' => C('WEIXIN_SEND_NAME'),
		   're_openid' => $re_openid,
		   'total_amount' => $total_amount,
		   'total_num' => $total_num,
		   'wishing' => C('WEIXIN_WISHING'),
		   'client_ip' => $_SERVER['SERVER_ADDR'],
		   'act_name' => C('WEIXIN_ACT_NAME'),
		   'remark' => C('WEIXIN_REMARK'),
		   'nonce_str' => rand_str(32),
		);
		ksort($parameter);
		$result = formatQueryParaMap($parameter, false);
		$sign = $this->sign($result);
		$parameter['sign'] = $sign;
		$xmlTpl = arrayToXml($parameter);
		$url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
		$responseXml = $this->curl_post_ssl($url, $xmlTpl);
		logger($responseXml);
		$postObj = simplexml_load_string($responseXml, 'SimpleXMLElement', LIBXML_NOCDATA);
		if($postObj->result_code == 'SUCCESS'){
		    return true;
		}else{
		    return false;
		}
	}

	/**
	 * [get_billno 生产商户订单ID 红包接口用]
	 * @param  [type] $mch_id [商户id]
	 * @return [type]         [description]
	 */
	private function get_billno($mch_id){
	     return  $mch_id . date('Ymd', time()) . rand(1000000000,9999999999);
	}
	/**
	 * [sign 生成md5签名-红包接口用]
	 * @param  [type] $content [formatQueryParaMap 返回的 ASCII 码从小到大排序字符串]
	 * @return [type]          [description]
	 */
	private function sign($content){
		$signStr = $content . "&key=" . C('WEIXIN_PARTNERKEY');
		return strtoupper(md5($signStr));
	}

	private function curl_post_ssl($url, $vars, $second=30,$aHeader=array())
	{
	   $ch = curl_init();
	   //超时时间
	   curl_setopt($ch,CURLOPT_TIMEOUT,$second);
	   curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
	   //这里设置代理，如果有的话/验证主机
	   curl_setopt($ch,CURLOPT_URL,$url);
	   curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
	   curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);  
	   
	   //cert 与 key 分别属于两个.pem文件
	   curl_setopt($ch,CURLOPT_SSLCERT,'./Public/keyfile/apiclient_cert.pem');
	   curl_setopt($ch,CURLOPT_SSLKEY,'./Public/keyfile/apiclient_key.pem');
	   curl_setopt($ch,CURLOPT_CAINFO,'./Public/keyfile/rootca.pem');
	 
	   if( count($aHeader) >= 1 ){
	      curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);
	   }
	 
	   curl_setopt($ch,CURLOPT_POST, 1);
	   curl_setopt($ch,CURLOPT_POSTFIELDS,$vars);
	   $data = curl_exec($ch);
	   if($data){
	      curl_close($ch);
	      return $data;
	   }
	   else { 
	      $error = curl_errno($ch);
	      curl_close($ch);
	      return $error;
	   }
	}
}