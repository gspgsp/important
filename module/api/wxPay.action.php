<?php

class wxPayAction extends null2Action{
    protected $wxPay;
    public function __init(){
        $this->wxPay = E('wxPay',APP_LIB.'class');
    }

    public function getPrePayOrder(){
        $orderBody = "test商品";
        $tade_no = "abc_" . time();
        $total_fee = 1;
        $WxPay = $this->wxPay;
        $response = $WxPay->getPrePayOrder($orderBody, $tade_no, $total_fee);

//        p("---response----");
//        p($response);
//        p("---拿到prepayId再次签名----");
        $x = $WxPay->getOrder($response['prepay_id']);
        $this->json_output($x);
//        p($x);

    }

    public function notifySome(){
        $xmlData = file_get_contents('php://input');

        $data = $this->wxPay->xmlstr_to_array($xmlData);

        ksort($data);
        $buff = '';
        foreach ($data as $k => $v){
            if($k != 'sign'){
                $buff .= $k . '=' . $v . '&';
            }
        }
        $stringSignTemp = $buff . 'key=807066fb67e13b985b591f32d54219b9';//key为证书密钥
        $sign = strtoupper(md5($stringSignTemp));
        if($sign == $data['sign']){


            echo '<xml>
              <return_code><![CDATA[SUCCESS]]></return_code>
              <return_msg><![CDATA[OK]]></return_msg>
          </xml>';
            exit();
        }else{
            $msg = date("Y-m-d H:i:s")." 支付通知验签未通过\n";
            echo $msg;
            file_put_contents('./wxPay.log', $msg, FILE_APPEND | LOCK_EX);
            exit();
        }
    }


    public function closeOrder($out_trade_no=null){
        if(!$tmp = $this->wxPay->closeOrder($out_trade_no)){
            return array('err'=>1,'msg'=>'商户订单号错误');
        }else{
            if($tmp['return_code'] == 'SUCCESS'){

                // do something  修改数据库状态
                return array('err'=>0,'msg'=>'订单关闭成功');
            }

        }
    }

    public function downloadbill($bill_date = '20140603' ,$bill_type = 'ALL'){
        if(!$tmp = $this->wxPay->downloadbill($bill_date,$bill_type)){
            return array('err'=>1,'msg'=>'参数错误');
        }else{
            if ($tmp['return_code'] == "FAIL") {
                echo "通信出错：".$tmp["return_msg"];
            }else{
                p("对账单详情<br />");
                $get_data = file_get_contents("php://input");
                p();

            }
        }
    }




}


