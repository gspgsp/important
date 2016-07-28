<?php



class indexAction extends homeBaseAction
{

    public function __init()
    {}

    public function init()
    {
        $zip = new ZipArchive;
        $res = $zip->open(FILE_URL.'/1234567.zip',ZipArchive::CREATE|ZipArchive::OVERWRITE);
        if ($res === TRUE) {
            $zip->addFile(FILE_URL.'/111.txt', '111.txt');
            $zip->close();
            echo 'ok';
        } else {
            echo 'failed, code:' . $res;
        }
        
//         E('PHPExcel',APP_LIB.'extend');
//         $tt = new PHPExcel_Shared_ZipArchive;
//         $tt::open("123456e7982323.zip");
//         $result = $tt::addFromString('e:/222.txt','121212');
//         $result = $tt::addZipFiles("12345679.zip",array('‪C:\Users\xqj\Desktop\111.txt'));
//         var_dump($result);
//         $obj = E('dfftPayment',APP_LIB.'class');//引入dfftPayment类
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
//             // 会员绑定查询
//            $params = array(
//                 'mallID'     => '000106',
//                 'payType'    => '09011',
//                 'memCode'    => '0000001',
//                 'memName'    => 'cs0000001',
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