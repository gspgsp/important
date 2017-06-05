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
     * @apiParam {int} type       1  weixin zhifu     2支付宝
     * @apiParam {Number} goods_id       商品id（非必填）  //  为了以后的商品，也用支付，现在我们只用来充塑豆 ，传total_fee
     * @apiParam {Number} goods_num       商品数量（非必填）
     * @apiParam {String} total_fee       总金额  单位元（必填）
     *
     *
     *
     * @apiSuccess {String}  msg   描述
     * @apiSuccess {Boolean} err   错误码
     * @apiSuccess {Array} data   信息
     *
     * @apiSuccessExample Success-Response:
     *     {
     *      "err":0
     *      "info":"xxx"
     *      }
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
        $send_amount   = 0;
        $send_amount   = (int)($total_fee * 100);
        $this->payment = new multiPay($type);
        $res           = $this->payment->getPrePayOrder ($order_id, $send_amount);

        $order = M ('order:onlineOrder');

        $data = $order->addOrder ($order_id, $type, $res['prepay_id'], $total_fee, $goods_id, $goods_num, $user_id, $this->uuid, $res['appid'], $this->platform, $res['status'], $res['remark']);

        if ($res['status'] == 0 && !empty($data)) {
            $x = $this->payment->getOrder ($res['prepay_id']);
            $this->json_output (array(
                'err'  => 0,
                'msg'  => '订单生成成功',
                'data' => $x,
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
     * @apiParam {Number} order_id       订单ID（非必填）  //  为了以后的商品，也用支付，现在我们只用来充塑豆 ，传total_fee
     * @apiParam {Number} status         APP状态  2 唤醒APP成功 -2 唤醒APP失败 -3用户取消支付
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
        $data = array(
            'status'=>$status,
        );

        $res = $order->updatePk($data,$order_id);

        if(empty($res)){
            $this->_errCode (101);
        }
        $this->json_output (array(
            'err'  => 0,
            'msg'  => '订单状态更新成功',
        ));
    }


    /**
     * 获取金钱苏豆对应关系
     * @api {post} /qapi_3/pay/getPayAmountConfig 网上支付-更新订单状态
     * @apiVersion 3.2.0
     * @apiName  getPayAmountConfig
     * @apiGroup pay
     * @apiUse UAHeader
     *
     * @apiSuccess {String}  msg   描述
     * @apiSuccess {Boolean} err   错误码
     * @apiSuccess {Array} data   信息
     *
     * @apiSuccessExample Success-Response:
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
     */

    public function getPayAmountConfig()
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
                'plasticBean'=>500
            ),
            array(
                'money'=>600,
                'plasticBean'=>600
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
     * @apiSuccess {String}  msg   描述
     * @apiSuccess {Boolean} err   错误码
     * @apiSuccess {Array} plasticBean   信息
     *
     * @apiSuccessExample Success-Response:
     * {"err":0,"plasticBean":1000}
     */
    public function getExactAmount()
    {
        $money = sget('money','i',1);
        if($money>10000||$money<1||!is_integer($money))
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