<?php  
  /************************************************************************************
   * 直接支付成功通知商城，接收通知接口
   * @author KYLE
   ************************************************************************************/
  header("content-type:text/html; charset=utf-8");  
  require_once("http://127.0.0.1:8080/JavaBridge/java/Java.inc");
  java_require($_SERVER['DOCUMENT_ROOT']."/Javatest/Java"); //一定要把刚才生成的jar文件放到这个require的目录下面

  //获取参数
  if(isset($_POST['postdata']) || !empty($_POST['postdata'])){
	 $postdata = $_POST['postdata'];
  }else{
     $postdata = file_get_contents("php://input");
  }
  $param = json_decode($postdata);
  if(isset($param)){
      // 支付消息
      $message = $param->payMessage;
      // 支付订单的支付号码
	  $payID = $param->payID;
	  // 支付状态
	  $payStatus = $param->payStatus;
	  // 签名
	  $signature = $param->signature;
	  
	  // 测试证书路径
	  $signUtil = Java('com.easterpay.base.util.SignUtil'); 
	  $path = $_SERVER['DOCUMENT_ROOT'].'/Javatest/Java/hbtest2.pfx';   //证书路径
	  $password = "999999";  //证书密码
	  $result = $signUtil->verifySignedDataDetached($path,$password,$signature,$postdata);
	  //验证签名，是否为东方付通发送的指令
	  if($result == "1"){
		// 订单支付成功(其他不处理)
		if ($payStatus == "000000") {
			//$payID
			// 修改订单状态 已支付 (商城逻辑处理)
		}
		// 响应支付平台已接收,接收到消息必须返回
		echo "{\"payStatus\":\"000000\"}";
	  }else{
		//签名验证失败！
		echo '签名验证失败!';
	  }
	}else{
		echo '请查看源代码!';
	}
?> 