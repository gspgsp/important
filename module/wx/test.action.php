<?php
/**
*token
*/
class testAction
{
	function __construct()
	{
		define("TOKEN", "wodesuliao");
		$this->AppID = 'wxbe66e37905d73815';
		$this->AppSecret = '7eb6cc579a7d39a0e123273913daedb0';
		$this->mch_id = '1324710901';
		$this->partnerkey = 'x39kmrlyOBOYvfR3vBlJpnAvkNsmQygJ';
		if(isset($_GET['echostr']))
		{
 			$echoStr = $_GET["echostr"];
			if($this->valid()){
		 		echo $echoStr;
		 		exit;
		 	}
		}
	}
	protected function valid() {
		if (!defined("TOKEN")) {
			define("TOKEN", "wodesuliao");
		}
      $signature = $_GET["signature"];
      $timestamp = $_GET["timestamp"];
      $nonce = $_GET["nonce"];
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
   }
}