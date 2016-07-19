<?php  
  /************************************************************************************
   * 测试主页
   * @author KYLE
   ************************************************************************************/
  header("content-type:text/html; charset=utf-8");  
  require_once("http://127.0.0.1:8080/JavaBridge/java/Java.inc");
  java_require($_SERVER['DOCUMENT_ROOT']."/Javatest/Java"); //一定要把刚才生成的jar文件放到这个require的目录下面

 $system = new Java("java.lang.System");  
 $s = new Java("java.lang.String", "php-java-bridge config...<br><br>");  
 echo $s;  
	
 //demonstrate property access  
 print "Java version=".$system->getProperty("java.version")." <br> ";  
 print "Java vendor=" .$system->getProperty('java.vendor')." <br> ";  
 print "OS=".$system->getProperty(" os.name ")." ".  
 $system->getProperty("os.version")." on ".  
 $system->getProperty(" os.arch ")." <br> ";  
   
 // java.util.Date example  
 $formatter = new Java('java.text.SimpleDateFormat',"EEEE, MMMM dd, yyyy 'at' h:mm:ss a zzzz");  
 echo $formatter. "<br>";  
 print $formatter->format(new Java("java.util.Date"));  
  
 echo "<br /><br />";
   
 // com.easterpay.base.util.SignUtil example   生成签名方法
 $signUtil = Java('com.easterpay.base.util.SignUtil'); 
 $path = $_SERVER['DOCUMENT_ROOT'].'/Javatest/Java/hbtest2.pfx';   //证书路径
 $password = "999999";  //key 值不变
 $str = '{"name":"123456"}'; // 字符串
 
 echo $path;
 
 
 echo $signUtil->signDataDetached($path,$password,$str);    //打印签名密文
 
 echo "<br /><br />";
 
 //验证签名方法
 $path = $_SERVER['DOCUMENT_ROOT'].'/Javatest/Java/dfft.pfx';   //证书路径
 $password = "999999";  //key 值不变
 $str = '{"name":"123456"}'; // 字符串
 

 echo '签名测试';
 //密文
 $sign ='MIIDsgYJKoZIhvcNAQcCoIIDozCCA58CAQExCzAJBgUrDgMCGgUAMAsGCSqGSIb3DQEHAaCCAnAwggJsMIIB1aADAgECAgQbtl1qMA0GCSqGSIb3DQEBBQUAMFAxCzAJBgNVBAYTAkNOMSEwHwYDVQQKHhhbnZSilsZW4o0iUqFnCZZQjSNO+1FsU/gxDTALBgNVBAsTBEJTRkMxDzANBgNVBAMTBmJzZmNjYTAeFw0xMzA5MTgwNTQzMDZaFw0xNTA5MTgwNTQzMDZaMCwxCzAJBgNVBAYTAkNOMR0wGwYDVQQDHhSDA4++V84AIFudTtiQGgAgi8FOZjCBnzANBgkqhkiG9w0BAQEFAAOBjQAwgYkCgYEAuMGSVk23FEnk46JFQwi+ja7xZ8BS5tuWpOp4e4d4RPYyP2KXXeQ3wTkfA/UwZOCKYMuJTEU3QRtkQ0aUSxCeBgUoVA5K7BltqGkU+zPxyk5lW+3XE9xKhsK/jtJ85P5mQjfE95ZQD1Hg8MDGuukga2btn70h2x8cyFPKGmitm1ECAwEAAaN3MHUwHQYDVR0OBBYEFHqRqX9WF1GoV1Lz6GkPonUxNxYeMA4GA1UdDwEB/wQEAwIFoDAMBgNVHRMBAf8EAjAAMB8GA1UdIwQYMBaAFFSh8cJ+0m1qOck5Uyaor6BPO7GaMBUGCWCBHAGN2EYBAgQIEwYxNDQyMDIwDQYJKoZIhvcNAQEFBQADgYEAhhLfSCIIIJwBa8d/LCh8fBN645jpIILf9aBbOfIWZG7GxWmzCbB0QMjMLUeEk2PkEPAGeOkX4LYCQVDrwCwTbS0qLjqj6gn3vUCgnBq/6+LUOaceRfSYh7IyPwpKh8aFNwLW0rqQoPbkgKOnfWj+flsXypHXrdf3n6tvsGA457MxggEKMIIBBgIBATBkMFwxCzAJBgNVBAYTAkNOMS0wKwYDVQQKDCTlrp3pkqLpm4blm6LotKLliqHmnInpmZDotKPku7vlhazlj7gxDTALBgNVBAsTBEJTRkMxDzANBgNVBAMTBmJzZmNjYQIEG7ZdajAJBgUrDgMCGgUAMA0GCSqGSIb3DQEBAQUABIGAaO90cLB05M1uHo4SCZSovfw0ulCu6xeXkSXVB9Qqj++W4efJue57N5fmjpk+dTdYB2a1BGoSoiB0M4A9DnGLUtOmyXj12mQR5Z17T9eV4kfE1GlNTyiZCHmtDfd/tw4ZcKiLmuF+odhXe7HVKKEbk830p7tCSDR7kK4ZU/VhjgQ= ';
 //验证签名 可以尝试改下字符串 查看结果
 $result = $signUtil->verifySignedDataDetached($path,$password,$sign,$str);
 if($result == "1"){
	echo "验证成功！";
 }else{
	echo "验证失败！";
 }

?> 
<p><a href="jspay.php" target='_blank'>直接支付测试</a></p>
<p><a href="closejspay.php" target='_blank'>关闭直接支付测试</a></p>
<p><a href="callbackpay.php" target='_blank'>支付回调</a></p>