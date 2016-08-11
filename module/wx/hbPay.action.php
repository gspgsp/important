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
		// define(WEIXIN_MCHID,'1324710901');//商户号id
		// define(WEIXIN_PARTNERKEY, 'x39kmrlyOBOYvfR3vBlJpnAvkNsmQygJ');//秘钥
		// $this->openid="o1SYHw7UuAqoEoM1Yoyk7DEoqp7g";
	}
	//进入红包支付页面
	public function enPay(){
		$this->display('record');
	}
	//兑换红包
	public function hongbao(){
		$this->is_ajax=true;
		$total_amount = sget('total_amount','i',0);
		$parameter = array(
		   'mch_billno' => $this->get_billno(WEIXIN_MCHID),//商户订单
		   'mch_id' => '1324710901',//商户id
		   'wxappid' => 'wxbe66e37905d73815',//服务号appid
		   'send_name' => '我的塑料网',//红包发送者名称
		   'nick_name'=>'红包',//提供方名称
		   're_openid' => $this->openid,//相对于一脉互通的openid
		   'total_amount' => $total_amount,//付款金额，单位分
		   'min_value'=>100,//最小红包金额，单位分
		   'max_value'=>300,//最大红包金额，单位分
		   'total_num' => 1,//红包収放总人数
		   'wishing' => '我的塑料网红包',//红包祝福诧
		   'client_ip' => $_SERVER['SERVER_ADDR'],//调用接口的机器 Ip 地址
		   'act_name' => '微信红包',//活劢名称
		   'remark' => '微信红包',//备注信息
		   'nonce_str' => $this->rand_str(),//随机字符串
		);
		ksort($parameter);
		$result = $this->formatQueryParaMap($parameter, false);
		$sign = $this->sign($result);
		$parameter['sign'] = $sign;
		$xmlTpl = $this->arrayToXml($parameter);
		$url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
		$responseXml = $this->curl_post_ssl($url, $xmlTpl);
		logger($responseXml);
		$postObj = simplexml_load_string($responseXml, 'SimpleXMLElement', LIBXML_NOCDATA);
		if($postObj->result_code == 'SUCCESS'){
		    //return true;
		}else{
		    return false;
		}
	}
	/**
	 * [formatQueryParaMap 按照字段名的ASCII 码从小到大排序（字典序）红包接口用]
	 * @param  [type] $paraMap   [生成排序的数组]
	 * @param  [type] $urlencode [false]
	 * @return [type]            [description]
	 */
	protected function formatQueryParaMap($paraMap, $urlencode=false){
		$buff = "";
		ksort($paraMap);
		foreach ($paraMap as $k => $v){
		   if (null != $v && "null" != $v && "sign" != $k) {
		       if($urlencode){
		         $v = urlencode($v);
		      }
		      $buff .= $k . "=" . $v . "&";
		   }
		}
		$reqPar;
		if (strlen($buff) > 0) {
		   $reqPar = substr($buff, 0, strlen($buff)-1);
		}
		return $reqPar;
	}
	/**
	 * [arrayToXml 数组转xml 红包接口用]
	 * @param  [type] $arr [传入数组]
	 * @return [type]      [返回xml]
	 */
	protected function arrayToXml($arr){
		$xml = "<xml>";
		foreach ($arr as $key=>$val)
		{
		  if (is_numeric($val))
		  {
		    $xml.="<".$key.">".$val."</".$key.">"; 
		  }
		  else{
		    $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";  
		  } 
		}
		$xml.="</xml>";
		return $xml; 
	}
	//微信日志
	protected function logger($postStr, $type=1){
		$model = M('weixin_log');
		$data = array(
			'content' 		=> $postStr,
			'type'	  		=> $type,
			'input_time'	=> time(),
		);
		return $model->add($data);
	}

	/**
	 * [get_billno 生产商户订单ID 红包接口用]
	 * @param  [type] $mch_id [商户id]
	 * @return [type]         [description]
	 */
	protected function get_billno($mch_id){
	     return  $mch_id . date('Ymd', time()) . rand(1000000000,9999999999);
	}
	/**
	 * [sign 生成md5签名-红包接口用]
	 * @param  [type] $content [formatQueryParaMap 返回的 ASCII 码从小到大排序字符串]
	 * @return [type]          [description]
	 */
	protected function sign($content){
		$signStr = $content . "&key=" . WEIXIN_PARTNERKEY;
		return strtoupper(md5($signStr));
	}

	protected function curl_post_ssl($url, $vars, $second=30,$aHeader=array()){
	   $ch = curl_init();
	   //超时时间
	   curl_setopt($ch,CURLOPT_TIMEOUT,$second);
	   curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
	   //这里设置代理，如果有的话/验证主机
	   curl_setopt($ch,CURLOPT_URL,$url);
	   curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
	   curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);  
	   
	   //cert 与 key 分别属于两个.pem文件
	   curl_setopt($ch,CURLOPT_SSLCERT,FILE_URL.'/myapp/certify/apiclient_cert.pem');
	   curl_setopt($ch,CURLOPT_SSLKEY,FILE_URL.'/myapp/certify/apiclient_key.pem');
	   curl_setopt($ch,CURLOPT_CAINFO,FILE_URL.'/myapp/certify/rootca.pem');
	 
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
	protected function rand_str($length=32) {
	  $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	  $str = "";
	  for ($i = 0; $i < $length; $i++) {
	    $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
	  }
	  return $str;
	}
}