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
		// $this->AppID = 'wx00df62a914e25294';
		// $this->AppSecret = '9be0026abfd442209334c1ef28bc46e6';
		$this->mch_id = '1324710901';
		$this->partnerkey = 'x39kmrlyOBOYvfR3vBlJpnAvkNsmQygJ';
		if(!isset($_GET["echostr"])){
		    $this->responseMsg();
		}else{
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
   
   public function responseMsg()//执行接收器方法
   {
       $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
       if (!empty($postStr)){
           $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
           $RX_TYPE = trim($postObj->MsgType);
           switch(strtolower($RX_TYPE)){
               case "event":
                   $result = $this->receiveEvent($postObj);
                   breadk;
           }
           echo $result;
       }else{
           echo "";
           exit;
       }
   }
   
   private function receiveEvent($object){
       $content = "";
       switch (strtolower($postObj->Event)){
           case "subscribe":
               $content = "欢迎我的塑料网服务号";//这里是向关注者发送的提示信息
               break;
           case "unsubscribe":
               $content = "欢迎下次光临";
               break;
       }
       $result = $this->transmitText($object,$content);
       return $result;
   }
   private function transmitText($object,$content){
       $textTpl = "<xml>
       <ToUserName><![CDATA[%s]]></ToUserName>
       <FromUserName><![CDATA[%s]]></FromUserName>
       <CreateTime>%s</CreateTime>
       <MsgType><![CDATA[text]]></MsgType>
       <Content><![CDATA[%s]]></Content>
       </xml>";
       $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content);
       return $result;
   }
   private function checkSignature()
   {
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