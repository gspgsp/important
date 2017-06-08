<?php

class wechatPayAction extends null2Action
{
    protected $wechatPay;

    public $config = array(
        'appid' => "wxc0eb2ef58d5df955",
        'mch_id' => "1473441002",
        'api_key' => "5eb7836d20e7727b7f7be8ec96ab4838",
        'notify_url' => APP_URL.'/api/wechatPay/notifySome'
    );
    public function __init ()
    {
        $this->wechatPay = E ('wechatPay', APP_LIB.'class');

    }

    public function getPrePayOrder ()
    {
        $orderBody = "test商品";
        $tade_no   = "abc_".time ();
        $total_fee = 1;
        $wechatPay = $this->wechatPay;
        $response  = $wechatPay->getPrePayOrder ($orderBody, $tade_no, $total_fee);

        $msg = date ("Y-m-d H:i:s")." 下单成功".serialize ($response)."\n";
        $msg .= $orderBody."\n";
        $msg .= $tade_no."\n";
        $msg .= $total_fee."\n";
        $x = $wechatPay->getOrder ($response['prepay_id']);
        $this->json_output (array('err' => 0, 'data' => $x));

    }

    public function notifySome ()
    {
        $xmlData = file_get_contents ('php://input');

        $data = $this->wechatPay->xmlstr_to_array ($xmlData);

        ksort ($data);
        $buff = '';
        foreach ($data as $k => $v) {
            if ($k != 'sign') {
                $buff .= $k.'='.$v.'&';
            }
        }

        $stringSignTemp = $buff.'key='.$this->config['api_key'];//key为证书密钥
        $sign           = strtoupper (md5 ($stringSignTemp));
        $order_info     = M ('order:onlineOrder')->getPk ($data['out_trade_no']);

        if ($sign == $data['sign']) {
            //            $msg = date("Y-m-d H:i:s")." 支付通知验签通过\n";
            //            $msg.=serialize($data)."\n";
            //            file_put_contents('./wechatPay.log', $msg, FILE_APPEND | LOCK_EX);

            if ($order_info['status'] < 5 ) {
                $arr = array(
                    'partner_order_id'   => $data['transaction_id'],
                    'status'             => 5,
                    'partner_fee_type'   => $data['fee_type'],
                    'partner_bank_type'  => $data['bank_type'],
                    'partner_trade_type' => $data['trade_type'],
                    'partner_app_id'     => $data['appid'],
                    'fee_type'           => $data['fee_type'],
                    'update_time'        => CORE_TIME,
                );

                M ('order:onlineOrder')->updatePk($arr,$data['out_trade_no']);

                if($order_info['channel']==6 && $order_info['is_cashed']==0)
                {
                    file_put_contents('/tmp/xielei.txt',print_r(1111111,true)."\n",FILE_APPEND);

                    M ('order:onlineOrder')->updatePlasticBean($order_info['order_id']);

                    file_put_contents('/tmp/xielei.txt',print_r(88888,true)."\n",FILE_APPEND);

                }


            }

            echo '<xml>
              <return_code><![CDATA[SUCCESS]]></return_code>
              <return_msg><![CDATA[OK]]></return_msg>
          </xml>';
            exit();
        } else {
            //            $msg = date("Y-m-d H:i:s")." 支付通知验签未通过\n";
            //            echo $msg;
            //            file_put_contents('./wechatPay.log', $msg, FILE_APPEND | LOCK_EX);

            if ($order_info['status'] < 1 && $order_info['status'] > -5) {
                M ('order:onlineOrder')->where ("order_id=".$data['out_trade_no'])->update (array(
                    'status' => -5,
                    'remark' => '支付通知验签未通过',
                ));
            }
            echo '<xml>
              <return_code><![CDATA[fail]]></return_code>
              <return_msg><![CDATA[]]></return_msg>
                </xml>';
            exit();
        }
    }


    public function closeOrder ($out_trade_no = null)
    {
        if (!$tmp = $this->wechatPay->closeOrder ($out_trade_no)) {
            return array('err' => 1, 'msg' => '商户订单号错误');
        } else {
            if ($tmp['return_code'] == 'SUCCESS') {

                // do something  修改数据库状态
                return array('err' => 0, 'msg' => '订单关闭成功');
            }

        }
    }

    public function downloadbill ($bill_date = '20140603', $bill_type = 'ALL')
    {
        if (!$tmp = $this->wechatPay->downloadbill ($bill_date, $bill_type)) {
            return array('err' => 1, 'msg' => '参数错误');
        } else {
            if ($tmp['return_code'] == "FAIL") {
                echo "通信出错：".$tmp["return_msg"];
            } else {
                p ("对账单详情<br />");
                $get_data = file_get_contents ("php://input");
                p ();

            }
        }
    }

    public function getWxJsPay(){
        try{
            require_once(APP_LIB."class/WxpayAPI_php_v3/example/jsapi.php");

        }catch(WxPayException $e){
            p($e->getMessage());
        }
        }

    public function getJsNotify(){
        echo 'this is js success notify';
    }

    public function getQcode(){
        require_once(APP_LIB."class/WxpayAPI_php_v3/example/qrcode.php");
    }


    public function wxJsNotify(){
//        $xmlData = file_get_contents ('php://input');
//        $data = $this->wechatPay->xmlstr_to_array ($xmlData);
//        file_put_contents('./sjs.txt',print_r($xmlData,true)."\n",FILE_APPEND);
//        file_put_contents('./sjs.txt',print_r($data,true)."\n",FILE_APPEND);
        require_file(APP_LIB."class/WxpayAPI_php_v3/example/notify.php");
        $notify = new PayNotifyCallBack();
        file_put_contents('./sjs.txt',print_r('s',true)."\n",FILE_APPEND);
        $notify->Handle(false);
        file_put_contents('./sjs.txt',print_r('---------------------------',true)."\n",FILE_APPEND);
        file_put_contents('./sjs.txt',print_r('lslok',true)."\n",FILE_APPEND);
        $result = $notify->GetValues();
        file_put_contents('./sjs.txt',print_r($result,true)."\n",FILE_APPEND);
        $order      = M ('order:onlineOrder');
        $order_id = $result['out_trade_no'];
        $order_info = $order->getPk ($order_id);

        if(array_key_exists("return_code", $result)
            && array_key_exists("result_code", $result)
            && $result["return_code"] == "SUCCESS"
            && $result["result_code"] == "SUCCESS")
        {
            $data = $result;
            if ($order_info['status'] < 5 ) {
                $arr = array(
                    'partner_order_id'   => $data['transaction_id'],
                    'status'             => 5,
                    'partner_fee_type'   => $data['fee_type'],
                    'partner_bank_type'  => $data['bank_type'],
                    'partner_trade_type' => $data['trade_type'],
                    'partner_app_id'     => $data['appid'],
                    'fee_type'           => $data['fee_type'],
                    'update_time'        => CORE_TIME,
                );

                M ('order:onlineOrder')->updatePk($arr,$data['out_trade_no']);

                if($order_info['channel']==6 && $order_info['is_cashed']==0)
                {
                    M ('order:onlineOrder')->updatePlasticBean($order_info['order_id']);
                }

                file_put_contents('./sjs.txt',print_r(showTrace(),true)."\n",FILE_APPEND);

            }
        }
        file_put_contents('./sjs.txt',print_r('this is victory end',true)."\n",FILE_APPEND);
        if ($order_info['status'] < 1 && $order_info['status'] > -5) {
            M ('order:onlineOrder')->where ("order_id=".$data['out_trade_no'])->update (array(
                'status' => -5,
                'remark' =>$result['return_msg'],
            ));
        }

    }

}