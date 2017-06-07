<?php
/**
 * Created by PhpStorm.
 * User: sick
 * Date: 17-5-26
 * Time: 下午2:52
 */
class multiPay
{
    public $type;
    public $goods_name='塑料圈通讯录-塑豆';

    public function __construct($type = 'wechatpay')
    {
        $this->type = $type;
        /*if($name =C('qapp_payment_goods_name'))
        {
            $this->goods_name = $name;
        }*/
        switch ($type) {

            case 1:
                $this->{$this->type} = E('wechatPay', APP_LIB . 'class');
                break;
            case 2:
                $this->{$this->type} = E('alipay', APP_LIB . 'class');
                break;
            default:
                $this->{$this->type} = E('wechatPay', APP_LIB . 'class');
        }

    }

    public function getPrePayOrder($order_id, $send_amount)
    {

        //微信支付单位为分
        if($this->type != 2)
        {
            $send_amount = (int)($send_amount*100);
        }

        $res = $this->{$this->type}->getPrePayOrder($this->goods_name, $order_id, $send_amount);
        switch ($this->type) {
            case 1:
                if ($res['return_code'] == 'SUCCESS') {
                    $res['status'] = 1;
                    $res['remark'] = '';
                } else {
                    $res['status'] = -1;
                    $res['remark'] = $res['return_msg'];
                }

                return $res;
                break;
            case 2:

                return array();
                break;
        }


    }


    public function getOrder($prepare_id)
    {
        switch ($this->type) {
            case 1:
                return $this->{$this->type}->getOrder($prepare_id);
                break;
            case 2:

                return array();
                break;
        }
    }

    public function getJsOrder($openId,$order_id, $send_amount){//APP_LIB."class/WxpayAPI_php_v3/example/jsapi.php"
        require_file(APP_LIB."class/WxpayAPI_php_v3/lib/WxPay.Api.php");
        require_file(APP_LIB."class/WxpayAPI_php_v3/example/WxPay.JsApiPay.php");

        $send_amount = (int)($send_amount*100);

        try{
            $tools = new JsApiPay();
            $input = new WxPayUnifiedOrder();
            $input->SetBody($this->goods_name);
            //$input->SetAttach("test");
            $input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
            $input->SetTotal_fee($send_amount);
            $input->SetTime_start(date("YmdHis"));
            $input->SetTime_expire(date("YmdHis", time() + 600));
            $input->SetGoods_tag("test");
            $input->SetNotify_url(APP_URL."/qapi_3/pay/wxJsNotify");
            $input->SetTrade_type("JSAPI");
            $input->SetOpenid($openId);
            $order = WxPayApi::unifiedOrder($input);
//        echo '<font color="#f00"><b>统一下单支付单信息</b></font><br/>';
//        printf_info($order);
            $jsApiParameters = $tools->GetJsApiParameters($order);

            //获取共享收货地址js函数参数
            $editAddress = $tools->GetEditAddressParameters();

            return array('err'=> 1,'data'=>$jsApiParameters,'msg'=>'');


        }catch(WxPayException $e){
            return array('err'=> -1,'msg'=>$e->getMessage());
        }
    }


}

