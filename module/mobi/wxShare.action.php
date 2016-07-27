<?php
/**
*微信分享文章功能
*/
class wxShareAction extends action
{
	protected $AppID,$AppSecret;
	public function __construct(){
			$this->AppID = 'wx2cfaa7723ce834e9';
			$this->AppSecret = '141cb0a0a49efc54d29dd624a8355c8a';
		}
	//curl获取数据请求
	protected function http($url){
	    $ch = curl_init($url);
	    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
	    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	    curl_setopt($ch, CURLOPT_HEADER, false);
	    $output = curl_exec($ch);//输出内容
	    curl_close($ch);
	    return $output;
	}
	//获取token
	protected function wx_get_token(){
	    $_key='weixin_access_token';
	    $cache=cache::startMemcache();
	    $access_token=$cache->get($_key);
	    if(empty($access_token)){
	    	$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->AppID}&secret={$this->AppSecret}";
			$result = json_decode($this->http($url), true);
			if(isset($result['access_token'])){
				$access_token = $result['access_token'];
				$cache->set($_key,$access_token,7000);
				return $access_token;
			}else{
				return false;
			}
	    }else{
	    	return $access_token;
	    }
	}
	//生成随机字符串
	protected function createNonceStr($length = 16){
	  $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	  $str = "";
	  for($i = 0; $i < $length; $i++){
	    $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
	  }
	  return $str;
	}
	//获取票据
	protected function get_jsapi_ticket(){
		$_key='weixin_jsapi_ticket';
		$cache=cache::startMemcache();
	    $ticket=$cache->get($_key);
	    if(empty($ticket)){
	    	$access_token = $this->wx_get_token();
	    	$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token={$access_token}";
	      	$result = json_decode($this->http($url), true);
	      	if(isset($result['ticket'])){
				$ticket = $result['ticket'];
				$cache->set($_key,$ticket,7000);
				return $ticket;
			}else{
				return false;
			}
	    }else{
	    	return $ticket;
	    }
	}
	//格式化输出字符串
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
	//获取url
	protected function get_url(){
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		$url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		return $url;
	}
	//获取签名
	public function getSignPackage(){
		$signPackage = array(
			"jsapi_ticket" => $this->get_jsapi_ticket(),
			"noncestr"  => $this->createNonceStr(),
			"timestamp" => time(),
			"url"       => $this->get_url(),
		);
		$string = $this->formatQueryParaMap($signPackage, false);
		$signPackage['signature'] = sha1($string);
		$signPackage['appId'] = $this->AppID;
		$this->json_output(array('err'=>0,'signPackage'=>$signPackage));
	}
}