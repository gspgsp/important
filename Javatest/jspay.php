<?php  
  /************************************************************************************
   * 直接支付
   * @author KYLE
   ************************************************************************************/
   
  header("content-type:text/html; charset=utf-8");  
  require_once("http://127.0.0.1:8080/JavaBridge/java/Java.inc");
  java_require($_SERVER['DOCUMENT_ROOT']."/Javatest/Java"); //一定要把刚才生成的jar文件放到这个require的目录下面

  
  $payID = 'JG'.date('Ymdhis',time()).'-'.rand(999,9999);
 
  //参数封装
  $param['payID'] =  $payID; // 支付号码，东方付通对此订单的唯一标识，商城必须保存此订单号，下次支付时使用
  $param['tradeOrder'] =  $payID; // 合同号码，商城的订单号
  
  $param['mallID'] = '000054';  // 商城号
  $param['payType'] = '01010'; // 接口类型，直接支付
  $param['payMemCode'] = 'F1000819'; // 付款人代码
  $param['payMemName'] = 'cs123'; // 付款人名称

  $param['recMemCode'] = 'F1000733'; // 收款人代码
  $param['recMemName'] = '北京化工宝电子商务有限公司'; // 收款人名称
  $param['currency'] = 'CNY'; // 人民币
  $param['payAmt'] = '999'; // 付款金额
  $param['originalPayID'] = '';  // 直接支付不需要赋值
  $param['callBackUrl'] = 'http://www.baidu.com'; //回调通知地址，订单支付成功后通知商城的地址
  $param['summary'] = ''; //摘要
  

  echo "支付号码：".$payID;
  echo "<br /><br />";
  
 //生成签名方法
 $signUtil = Java('com.easterpay.base.util.SignUtil'); 
 $path = $_SERVER['DOCUMENT_ROOT'].'/Javatest/Java/hbtest2.pfx';   //证书路径
 $password = "999999";  //证书密码
 
  // 生成签名
 $param['signature'] = $signUtil->signDataDetached($path,$password,json_encode($param))."";    //打印签名密文
 
 $json = json_encode($param);
 
 echo "支付参数：<br />".$json;
 echo "<br /><br />";
 
 $order = base64_encode($json);
 echo "参数：<br />".$order;
 
?> 
<p>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf8">
<title>pay</title>
</head>
<body>
	正在跳转到支付平台...<br />
	<form action="https://uat.easternpay.com.cn/bsteelpay/payment.do"  method="post">
		<input type="hidden" name="order" size="200" value='<?php echo $order ?>'/>
	</form>
	<script>
		document.forms[0].submit();
	</script>
</body>
</html>