<?php
/**
 * Created by PhpStorm.
 * User: sick
 * Date: 17-5-26
 * Time: 下午2:31
 */
class payAction extends baseAction
{
    public $payment;
    public $cache;
    public function __init(){
        parent::__init();
         $this->cache = E ('RedisClusterServer', APP_LIB.'class');
         E ('multiPay', APP_LIB.'class');
    }

       public function getPrePayOrder(){

           $user_id = $this->checkAccount();
           $type = sget('type','i',1);
           $goods_id = sget('goods_id','i',12);
           $goods_num = sget('goods_num','i',1);

           $order_id = $this->buildOrderId ();
           $send_amount = $amount = 10;
           if($type == 'weixin')
           {
               $send_amount = $amount*100;
           }
          /* $url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
           $notify_url = $this->config["notify_url"];

           $onoce_str = $this->getRandChar(32);

           $data["appid"] = $this->config["appid"];
           $data["body"] = $body;
           $data["mch_id"] = $this->config['mch_id'];
           $data["nonce_str"] = $onoce_str;
           $data["notify_url"] = $notify_url;
           $data["out_trade_no"] = $out_trade_no;
           $data["spbill_create_ip"] = $this->get_client_ip();
           $data["total_fee"] = $total_fee;
           $data["trade_type"] = "APP";
           $s = $this->getSign($data, false);
           $data["sign"] = $s;

           $xml = $this->arrayToXml($data);
           $response = $this->postXmlCurl($xml, $url);

           //将微信返回的结果xml转成数组
           //return $this->xmlstr_to_array($response);*/
           $this->payment = new multiPay($type);
           $res = $this->payment->getPrePayOrder($order_id, $send_amount);

           $order  = M('order:onlineOrder');

           $data = $order ->addOrder($order_id,$type,$res['prepay_id'],$amount,$goods_id,$goods_num,$user_id,$this->uuid,$res['appid'],$this->platform,$res['status'],$res['remark']);

           //showTrace();
           if($res['status'] == 0&&!empty($data))
           {
               $tmp['order_id'] = $order_id;
               $tmp['partner_order_sn'] = $res['prepay_id'];

               $this->json_output (array(
                   'err' => 0,
                   'msg' => '订单生成成功',
                   'data'=> $tmp
               ));
           }else{
               $this->_errCode(1001);
           }
       }


    public function getOrder($prepayId){
        /*$data["appid"] = $this->config["appid"];
        $data["noncestr"] = $this->getRandChar(32);;
        $data["package"] = "Sign=WXPay";
        $data["partnerid"] = $this->config['mch_id'];
        $data["prepayid"] = $prepayId;
        $data["timestamp"] = time();
        $s = $this->getSign($data, false);
        $data["sign"] = $s;*/
        $type = sget('type','s','wechatpay');
        $prepayId = sget('prepayId','s');
        $this->pay = new multiPay($type);

        $res = $this->pay->$type->getOrder($prepayId);

        $this->json_output (array(
            'err'  => 0,
            'res' => $res,
        ));
    }








}