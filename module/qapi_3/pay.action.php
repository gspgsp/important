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

    public function __init ()
    {
        parent::__init ();
        $this->cache = E ('RedisClusterServer', APP_LIB.'class');
    }

    /**
     * 网上支付-获取预定义订单
     * @api {post} /qapi_3/pay/getPrePayOrder 网上支付-获取预定义订单
     * @apiVersion 3.2.0
     * @apiName  getPrePayOrder
     * @apiGroup pay
     * @apiUse UAHeader
     *
     * @apiParam {int} type               1  weixin zhifu     2支付宝
     * @apiParam {Number} goods_id        商品id（必填）  现在都穿99 //  为了以后的商品，也用支付，现在我们只用来充塑豆 ，传total_fee
     * @apiParam {Number} goods_num       商品数量（必填）传递苏豆个数
     * @apiParam {String} total_fee       总金额  单位元（必填）
     *@apiParam {String} open_id       open_id 微信用户表示（微信端独有）（必填）
     * @apiSuccess {String}  msg   描述
     * @apiSuccess {Boolean} err   错误码
     * @apiSuccess {Array} data    微信加密信息
     * @apiSuccess {String} order_id   内部使用order_id
     *
     * @apiSuccessExample Success-Response:
     *     {
            "err": 0,
            "msg": "订单生成成功",
            "data": {
            "appid": "wxc0eb2ef58d5df955",
            "noncestr": "M1goYqk13hnd5cHj9JVZInAb1Mlrpi9B",
            "package": "Sign=WXPay",
            "partnerid": "1473441002",
            "prepayid": "wx20170606133730794f3ed98e0001656934",
            "timestamp": 1496727450,
            "sign": "93607A8B3CCDCB7EEE8CCE1538C40628"
            },
            "order_id": "2017060657102514"
            }
     *
     *
     */

    public function getPrePayOrder ()
    {
        E ('multiPay', APP_LIB.'class');

        $user_id   = $this->checkAccount ();
        $type      = sget ('type', 'i', 1);   // 1 weixin   2  支付宝
        $goods_id  = sget ('goods_id', 'i');
        $goods_num = sget ('goods_num', 'i');
        $total_fee = sget ('total_fee', 's');//  单位为元
        $open_id = sget('open_id','s');
        $total_fee = sprintf ("%.2f", $total_fee);
        $total_fee = $total_fee + 0;

        if (empty($goods_id) && empty($goods_num) && empty($total_fee)) {
            $this->_errCode (6);
        }
        if (empty($goods_id) && empty($goods_num) && !empty($total_fee)) {
            $msg = $this->goods_name;
        }
        if ($type != 1) {
            $this->_errCode (5);
        }


        $order_id      = $this->buildOrderId ();

        $send_amount   = $total_fee;
        $this->payment = new multiPay($type);


        if($type == 1 && $this->platform =="weixin"){
            $_platform = get_platform();
            if($_platform['platform'] !=='weixin') $this->json_output(array('err'=>1,'msg'=>'请在微信内打开'));
            if(empty($open_id)) $this->json_output(array('err'=>1,'msg'=>'请刷新后重试'));
            $res = $this->payment->getJsOrder($open_id,$order_id,$send_amount);
            $order = M ('order:onlineOrder');
            $_data = json_decode($res['data']);
            $prepay_id = substr($_data->package,10);
            $data = $order->addOrder ($order_id, $type, $prepay_id, $total_fee, $goods_id, $goods_num, $user_id, $this->uuid, $_data->appId, $this->platform, $res['err'], $res['msg']);
            if($res['err'] == 1 && !empty($data)){
                $this->json_output (array(
                    'err'  => 0,
                    'msg'  => '订单生成成功',
                    'data' => $res['data'],
                    'order_id'=>$order_id
                ));
            }else{
                $this->_errCode (1002);
            }
        }


        $res           = $this->payment->getPrePayOrder ($order_id, $send_amount);

        $order = M ('order:onlineOrder');

        $data = $order->addOrder ($order_id, $type, $res['prepay_id'], $total_fee, $goods_id, $goods_num, $user_id, $this->uuid, $res['appid'], $this->platform, $res['status'], $res['remark']);

        if ($res['status'] == 1 && !empty($data)) {
            $x = $this->payment->getOrder ($res['prepay_id']);

            $this->json_output (array(
                'err'  => 0,
                'msg'  => '订单生成成功',
                'data' => $x,
                'order_id'=>$order_id
            ));

        } else {
            $this->_errCode (1002);
        }

    }
    /**
     * 网上支付-更新订单状态
     * @api {post} /qapi_3/pay/updateOrderStatus 网上支付-更新订单状态
     * @apiVersion 3.2.0
     * @apiName  updateOrderStatus
     * @apiGroup pay
     * @apiUse UAHeader
     *
     * @apiParam {int}    type           1  weixin zhifu     2支付宝
     * @apiParam {Number} order_id       订单ID（必填）
     * @apiParam {Number} status         APP状态（必填）  //  状态 2 唤醒APP成功 -2 唤醒APP失败 -3用户取消支付；4支付成功客户端回调，-4支付失败客户端回调；
     *
     * @apiSuccess {String}  msg   描述
     * @apiSuccess {Boolean} err   错误码
     * @apiSuccess {Array} data   信息
     *
     * @apiSuccessExample Success-Response:
     *     {
     *      "err":0
     *      "msg":"订单状态更新成功"
     *      }
     *
     *
     */
    public function updateOrderStatus ()
    {

        $user_id    = $this->checkAccount ();
        $type       = sget ('type', 'i', 1);   // 1 weixin   2  支付宝
        $order_id   = sget ('order_id', 'i');
        $status     = sget ('status', 'i');

        $order      = M ('order:onlineOrder');
        $order_info = $order->getPk ($order_id);
        if ($order_info['type'] != $type || $order_info['user_id'] != $user_id) {
            $this->_errCode (1001);
        }

        if($order_info['status']<5) {
            $data = array(
                'status'      => $status,
                'update_time' => CORE_TIME
            );

            $res = $order->updatePk ($data, $order_id);

            if (empty($res)) {
                $this->_errCode (101);
            }
        }
        $this->json_output (array(
            'err'  => 0,
            'msg'  => '订单状态更新成功',
        ));
    }


    /**
     * 获取金钱苏豆对应关系
     * @api {post} /qapi_3/pay/getPayConfig 获取金钱苏豆对应关系
     * @apiVersion 3.2.0
     * @apiName  getPayConfig
     * @apiGroup pay
     * @apiUse UAHeader
     *
     * @apiSuccess {String}  msg   描述
     * @apiSuccess {Boolean} err   错误码
     * @apiSuccess {Array} data   信息
     *
     * @apiSuccessExample Success-Response
     *     {
            "err": 0,
            "data": [
            {
            "money": 10,
            "plasticBean": 100
            },
            {
            "money": 20,
            "plasticBean": 200
            },
            {
            "money": 30,
            "plasticBean": 300
            },
            {
            "money": 50,
            "plasticBean": 500
            },
            {
            "money": 100,
            "plasticBean": 1000
            },
            {
            "money": 200,
            "plasticBean": 2000
            },
            {
            "money": 300,
            "plasticBean": 3000
            },
            {
            "money": 500,
            "plasticBean": 500
            },
            {
            "money": 600,
            "plasticBean": 600
            }
            ]
            }
     *
     */

    public function getPayConfig()
    {
        $arr = array(
            array(
                'money'=>10,
                'plasticBean'=>100
            ),
            array(
                'money'=>20,
                'plasticBean'=>200
            ),
            array(
                'money'=>30,
                'plasticBean'=>300
            ),
            array(
                'money'=>50,
                'plasticBean'=>500
            ),
            array(
                'money'=>100,
                'plasticBean'=>1000
            ),
            array(
                'money'=>200,
                'plasticBean'=>2000
            ),
            array(
                'money'=>300,
                'plasticBean'=>3000
            ),
            array(
                'money'=>500,
                'plasticBean'=>5000
            ),
            array(
                'money'=>600,
                'plasticBean'=>6000
            )
        );
        $this->json_output (array(
            'err'  => 0,
            'data'  => $arr,
        ));
    }
    /**
     * 获取具体金额金钱苏豆
     * @api {post} /qapi_3/pay/getExactAmount 网上支付-获取具体金额金钱苏豆
     * @apiVersion 3.2.0
     * @apiName  getExactAmount
     * @apiGroup pay
     * @apiUse UAHeader
     *
     * @apiParam {int}    money
     *
     * @apiSuccess {String}  msg   描述
     * @apiSuccess {Boolean} err   错误码
     * @apiSuccess {Array} plasticBean   信息
     *
     * @apiSuccessExample Success-Response:
     * {"err":0,"plasticBean":1000}
     */
    public function getExactAmount()
    {
        if(preg_match('/\D/',$_POST['money']))
        {
            $this->_errCode(6);
        }

        $money = sget('money','i');
        if($money>10000||$money<1||!is_int($money))
        {
            $this->_errCode(6);
        }

        $bean = 10*$money;
        $this->json_output (array(
            'err'  => 0,
            'plasticBean'  => $bean,
        ));
    }

}