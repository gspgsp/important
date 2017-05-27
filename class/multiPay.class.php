<?php
/**
 * Created by PhpStorm.
 * User: sick
 * Date: 17-5-26
 * Time: 下午2:52
 */
class multiPay{
    public $type;
    public $goods_name;
    public function __construct ($type='wechatpay')
    {
        $this ->type = $type;
        $this ->goods_name = '塑料圈通讯录-塑豆';
        switch($type){

            case 1:
                $this->{$this->type} =  E('wechatPay',APP_LIB.'class');
                break;
            case 2:
                $this->{$this->type}  =  E('alipay',APP_LIB.'class');
                break;
            default:
                $this->{$this->type}  =  E('wechatPay',APP_LIB.'class');
        }

    }

    public function getPrePayOrder($order_id, $send_amount)
    {


        return $this->{$this->type}->getPrePayOrder($this->goods_name, $order_id, $send_amount);


    }


}