<?php

class wechatPayAction extends null2Action{
    protected $wechatPay;
    public function __init(){
        $this->wechatPay = E('wechatPay',APP_LIB.'class');
    }

    public function getPrePayOrder(){
        $orderBody = "test商品";
        $tade_no = "abc_" . time();
        $total_fee = 1;
        $wechatPay = $this->wechatPay;
        $response = $wechatPay->getPrePayOrder($orderBody, $tade_no, $total_fee);

        $msg = date("Y-m-d H:i:s")." 下单成功".serialize($response)."\n";
        $msg.=$orderBody."\n";
        $msg.=$tade_no."\n";
        $msg.=$total_fee."\n";
        $x = $wechatPay->getOrder($response['prepay_id']);
        $this->json_output(array('err'=>0,'data'=>$x));

    }

    public function notifySome(){
        $xmlData = file_get_contents('php://input');

        $data = $this->wechatPay->xmlstr_to_array($xmlData);

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
//            $msg = date("Y-m-d H:i:s")." 支付通知验签通过\n";
//            $msg.=serialize($data)."\n";
//            file_put_contents('./wechatPay.log', $msg, FILE_APPEND | LOCK_EX);

            M('order:onlineOrder')->where("order_id=".$data['out_trade_no'])->update(array('status'=>2));
            echo '<xml>
              <return_code><![CDATA[SUCCESS]]></return_code>
              <return_msg><![CDATA[OK]]></return_msg>
          </xml>';
            exit();
        }else{
//            $msg = date("Y-m-d H:i:s")." 支付通知验签未通过\n";
//            echo $msg;
//            file_put_contents('./wechatPay.log', $msg, FILE_APPEND | LOCK_EX);
            M('order:onlineOrder')->where("order_id=".$data['out_trade_no'])->update(array('status'=>3,'remark'=>'支付通知验签未通过'));
            exit();
        }
    }


    public function closeOrder($out_trade_no=null){
        if(!$tmp = $this->wechatPay->closeOrder($out_trade_no)){
            return array('err'=>1,'msg'=>'商户订单号错误');
        }else{
            if($tmp['return_code'] == 'SUCCESS'){

                // do something  修改数据库状态
                return array('err'=>0,'msg'=>'订单关闭成功');
            }

        }
    }

    public function downloadbill($bill_date = '20140603' ,$bill_type = 'ALL'){
        if(!$tmp = $this->wechatPay->downloadbill($bill_date,$bill_type)){
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


