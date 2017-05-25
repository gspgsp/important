<?php



class indexAction extends homeBaseAction
{

    public function __init()
    {
        $this->db=M('public:common');
    }

    public function init()
    {
        $RedisCluster = new RedisCluster();
        $RedisCluster->set("aabbcc","123456");
        $ss=$RedisCluster->get("aabbcc");
        p($ss);
        $RedisCluster->lpush("rr","1");
        $RedisCluster->lpush("rr","2");
        $RedisCluster->lpush("rr","3");
        p($RedisCluster->rpop("rr"));
        p($RedisCluster->rpop("rr"));
        p($RedisCluster->rpop("rr"));

//         $cache=cache::startRedis();
//         p($cache->get('aa'));
//         p($cache->status());
//         die;
//         phpinfo();
//         ini_set("display_errors", "On");
//         error_reporting(E_ALL | E_STRICT);
//          header("content-type:text/html; charset=utf-8");
//          $homepage = file_get_contents('http://127.0.0.1:8080/JavaBridge/java/Java.inc');
//          file_put_contents($_SERVER['DOCUMENT_ROOT']."/Javatest/Java/Java.inc",$homepage);
//          require_once($_SERVER['DOCUMENT_ROOT']."/Javatest/Java/Java.inc");
//          java_require($_SERVER['DOCUMENT_ROOT']."/Javatest/Java");
//          $system = new Java("java.lang.System");
//          $s = new Java("java.lang.String", "php-java-bridge config...<br><br>");
//          echo $s;

//          //demonstrate property access
//          print "Java version=".$system->getProperty("java.version")." <br> ";
//          print "Java vendor=" .$system->getProperty('java.vendor')." <br> ";
//          print "OS=".$system->getProperty(" os.name ")." ".
//          $system->getProperty("os.version")." on ".
//          $system->getProperty(" os.arch ")." <br> ";
//          // java.util.Date example
//          $formatter = new Java('java.text.SimpleDateFormat',"EEEE, MMMM dd, yyyy 'at' h:mm:ss a zzzz");
//          echo $formatter. "<br>";
//          print $formatter->format(new Java("java.util.Date"));

//          echo "<br /><br />";

//          // com.easterpay.base.util.SignUtil example   生成签名方法
//          $signUtil = Java('com.easterpay.base.util.SignUtil');
//          $path = $_SERVER['DOCUMENT_ROOT'].'/Javatest/Java/hbtest2.pfx';   //证书路径
//          $password = "999999";  //key 值不变
//          $str = '{"name":"123456"}'; // 字符串

//          echo $path;


//          echo $signUtil->signDataDetached($path,$password,$str);    //打印签名密文

//          echo "<br /><br />";

//          //验证签名方法
//          $path = '/home/wwwroot/test/branches/web/Javatest/Java/dfft.pfx';   //证书路径
//          $password = "999999";  //key 值不变
//          $str = '{"name":"123456"}'; // 字符串


//          echo '签名测试';
//          //密文
//          $sign ='MIIDsgYJKoZIhvcNAQcCoIIDozCCA58CAQExCzAJBgUrDgMCGgUAMAsGCSqGSIb3DQEHAaCCAnAwggJsMIIB1aADAgECAgQbtl1qMA0GCSqGSIb3DQEBBQUAMFAxCzAJBgNVBAYTAkNOMSEwHwYDVQQKHhhbnZSilsZW4o0iUqFnCZZQjSNO+1FsU/gxDTALBgNVBAsTBEJTRkMxDzANBgNVBAMTBmJzZmNjYTAeFw0xMzA5MTgwNTQzMDZaFw0xNTA5MTgwNTQzMDZaMCwxCzAJBgNVBAYTAkNOMR0wGwYDVQQDHhSDA4++V84AIFudTtiQGgAgi8FOZjCBnzANBgkqhkiG9w0BAQEFAAOBjQAwgYkCgYEAuMGSVk23FEnk46JFQwi+ja7xZ8BS5tuWpOp4e4d4RPYyP2KXXeQ3wTkfA/UwZOCKYMuJTEU3QRtkQ0aUSxCeBgUoVA5K7BltqGkU+zPxyk5lW+3XE9xKhsK/jtJ85P5mQjfE95ZQD1Hg8MDGuukga2btn70h2x8cyFPKGmitm1ECAwEAAaN3MHUwHQYDVR0OBBYEFHqRqX9WF1GoV1Lz6GkPonUxNxYeMA4GA1UdDwEB/wQEAwIFoDAMBgNVHRMBAf8EAjAAMB8GA1UdIwQYMBaAFFSh8cJ+0m1qOck5Uyaor6BPO7GaMBUGCWCBHAGN2EYBAgQIEwYxNDQyMDIwDQYJKoZIhvcNAQEFBQADgYEAhhLfSCIIIJwBa8d/LCh8fBN645jpIILf9aBbOfIWZG7GxWmzCbB0QMjMLUeEk2PkEPAGeOkX4LYCQVDrwCwTbS0qLjqj6gn3vUCgnBq/6+LUOaceRfSYh7IyPwpKh8aFNwLW0rqQoPbkgKOnfWj+flsXypHXrdf3n6tvsGA457MxggEKMIIBBgIBATBkMFwxCzAJBgNVBAYTAkNOMS0wKwYDVQQKDCTlrp3pkqLpm4blm6LotKLliqHmnInpmZDotKPku7vlhazlj7gxDTALBgNVBAsTBEJTRkMxDzANBgNVBAMTBmJzZmNjYQIEG7ZdajAJBgUrDgMCGgUAMA0GCSqGSIb3DQEBAQUABIGAaO90cLB05M1uHo4SCZSovfw0ulCu6xeXkSXVB9Qqj++W4efJue57N5fmjpk+dTdYB2a1BGoSoiB0M4A9DnGLUtOmyXj12mQR5Z17T9eV4kfE1GlNTyiZCHmtDfd/tw4ZcKiLmuF+odhXe7HVKKEbk830p7tCSDR7kK4ZU/VhjgQ= ';
//          //验证签名 可以尝试改下字符串 查看结果
//          $result = $signUtil->verifySignedDataDetached($path,$password,$sign,$str);
//          if($result == "1"){
//         	echo "验证成功！";
//          }else{
//         	echo "验证失败！";
//          }


//         header("content-type:text/html; charset=utf-8");
//         $url="http://127.0.0.1:8080/JavaBridge/java/Java.inc";
//         ini_set("display_errors", "On");
//         ini_set("allow_url_include", "On");
//         error_reporting(E_ALL | E_STRICT);
// //         echo HttpClient::quickGet($url);
//         echo $_SERVER['DOCUMENT_ROOT'];
//         require_once("http://127.0.0.1:8080/JavaBridge/java/Java.inc");
//         java_require($_SERVER['DOCUMENT_ROOT']."/Javatest/Java"); //一定要把刚才生成的jar文件放到这个require的目录下面
//         $system = new Java("java.lang.System");
//         $s = new Java("java.lang.String", "php-java-bridge config...<br><br>");
//         echo $s;

//         //demonstrate property access
//         echo "Java version=".$system->getProperty("java.version")." <br> ";
//         echo "Java vendor=" .$system->getProperty('java.vendor')." <br> ";
//         echo "OS=".$system->getProperty(" os.name ")." ".
//             $system->getProperty("os.version")." on ".
//             $system->getProperty(" os.arch ")." <br> ";
//         die;

//         error_reporting(E_ALL);
//         if (!extension_loaded('zip')) {
//             dl('zip.so');
//         }
//         echo dl('zip.so');
//         $thisdir = dirname(__FILE__);
//         echo $thisdir;
//         // unlink("/home/wwwroot/test/branches/static/upload/zip/test.zip");
//         $zip = new ZipArchive();
//         $filename = "/home/wwwroot/test/branches/static/upload/zip/test.zip";

//         if (!$zip->open($filename, ZIPARCHIVE::CREATE)) {
//             exit("cannot open ".$filename."\n");
//         } else {
//             echo "file ".$filename." OK\n";
//         }

//         $zip->addFromString("/home/wwwroot/test/branches/static/upload/zip/test.txt", "#1 This is a test string added as testfilephp.txt.\n");
//         $zip->addFromString("/home/wwwroot/test/branches/static/upload/zip/test.txt", "#2 This is a test string added as testfilephp2.txt.\n");
//         echo "numfiles: " . $zip->numFiles . "\n";
//         echo "status:" . $zip->status . "\n";
//         $zip->close();
//         unset($zip);
//            $obj = E('dfftPayment',APP_LIB.'class');//引入dfftPayment类
//            echo $obj->_getSign('{"name":"123456"}');
//            echo $obj->_base64Sign('1231321312312312');
//            echo $obj->_base64Verify('{"name":"123456"}',$obj->_getSign('{"name":"123456"}'));
//             // 账户查询
//             $params = array(
//                 'memCode' => '0000002',
//                 'payType' => '06011',
//                 'mallID' => '000106',
//                 'startDate' => date('Y-m-d',strtotime('1991-01-01')),
//                 'endDate' => date('Y-m-d',strtotime('2016-07-20')),
//             );
//            echo $obj->AccountQuery(json_encode($params));
//             // 订单查询
//            $params = array(
//                 'payID' => 'JG20160718113114-6071' ,
//                 'payType' => '05011' ,
//                 'mallID' => '000106',
//            );
//            echo $obj->OrderQuery(json_encode($params));
            // 会员绑定查询
//            $params = array(
//                 'mallID'     => '000106',
//                 'payType'    => '09011',
//                 'memCode'    => '5041',
//                 'memName'    => '上海中晨电子商务股份有限公司',
//            );
//            echo $obj->memberbindquery(json_encode($params));
//               // 会员绑定
//            $params = array(
//                     'mallID'     => '000106',
//         	        'payType'    => '09020',
//         	        'memCode'    => '0000002',
//         	        'memName'    => 'cs0000002',
//             );
//            echo $obj->memberbind(json_encode($params));
//               // 关闭直接支付
//             $params = array(
//                 'payID' => 'JG20160720110742-1657' ,
//                 'payType' => '01021' ,
//                 'originalPayID' => 'JG20160720110742-1657' ,
//                 'tradeOrder'    => 'JG20160720110742-1657' ,
//                 'mallID' => '000106' ,
//             );
//            echo $obj->CloseDirectPayment(json_encode($params));
//               // 代扣款
//             $payid = 'JG'.date('Ymdhis',time()).'-'.rand(999,9999);
//             $params = array(
//                     'mallID' => '000106' ,
//                     'payType' => '04011' ,
//                     'payID' => $payid ,
//                     'originalPayID' => '' ,
//                     'tradeOrder' => $payid ,
//                     'payMemCode' => '0000002' ,
//                     'payMemName' => 'cs0000002' ,
//                     'recMemCode' => '0000001' ,
//                     'recMemName' => 'cs0000001' ,
//                     'currency' => 'CNY' ,
//                     'payAmt' => '10000000' ,
//                     'summary' => '' ,
//                     'callBackUrl' => 'http://fkphsk.6655.la:10515/pay/rtnWithholdPayment' ,
//             );
//            echo $obj->WithholdPayment(json_encode($params));
//               // 关闭代扣款
//             $payid = 'JG'.date('Ymdhis',time()).'-'.rand(999,9999);
//             $params = array(
//                 'mallID' => '000106' ,
//                 'payType' => '04031' ,
//                 'payID' => $payid ,
//                 'originalPayID' => 'JG20160720112126-6712' ,
//                 'tradeOrder' => $payid ,
//                 'payMemCode' => '0000001' ,
//                 'payMemName' => 'cs0000001' ,
//                 'recMemCode' => '0000002' ,
//                 'recMemName' => 'cs0000002' ,
//                 'currency' => 'CNY' ,
//                 'payAmt' => '10000000' ,
//                 'summary' => '' ,
//                 'callBackUrl' => 'http://fkphsk.6655.la:10515/pay/rtnCloseWithholdPayment' ,
//             );
//            echo $obj->CloseWithholdPayment(json_encode($params));
//         保证金处理
//         支付类型 03031：保证金锁定 03041：保证金释放 03051：保证金支付 03061：保证金追加
//             $payid = 'JG'.date('Ymdhis',time()).'-'.rand(999,9999);
//             $params = array(
//                 'mallID'     => '000106',
//                 'payType'    => '03041',
//                 'payID' => $payid ,
//                 'originalPayID' => 'JG20160720114704-3897' ,
//                 'tradeOrder'    => 'JG20160720114704-3897' ,
//                 'payMemCode' => '0000002' ,
//                 'payMemName' => 'cs0000002' ,
//                 'recMemCode' => '0000001' ,
//                 'recMemName' => 'cs0000001' ,
//                 'currency'    => 'CNY',
//                 'payAmt'    => '100',
//                 'summary'    => '',
//                 'callBackUrl'     => 'http://fkphsk.6655.la:10515/pay/rtnMargin',
//                 'auditFlag'    => '0',
//             );
//            echo $obj->Margin(json_encode($params));
//            $this->display('index.html');



    }

}

?>