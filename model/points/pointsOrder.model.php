<?php

/**
 * 积分商品订单
 */
class pointsOrderModel extends Model
{
    protected $cache;

    public function __construct()
    {
        parent::__construct(C('db_default'), 'points_order');
    }

    public function get_supply_demand_top($goods_id)
    {
        //$cache = E('RedisCluster', APP_LIB . 'class');
        $key = 'msg_top:' . $goods_id;
        //$pur_id = $cache->get($key);
        $pur_id = 0;
        if (!empty($pur_id)) {
            return $pur_id;
        } else {
            $orderModel = M('points:pointsOrder');

            $info = $orderModel->where("outpu_time > " . time() . " and status = 5 and goods_id =" . $goods_id)->order("outpu_time desc")->getRow();

            if (!empty($info)) {

                //$cache->set($key, $info['pur_id'], $info['outpu_time'] - time());
                return $info;
            } else {
                return false;
            }
        }
    }

    //判断积分是否足够
    public function checkSupply($user_id, $num = null)
    {

        if ($pointsRow['cate_id'] == 7) {
            return false;
        }
        $user = M('public:common')->model('contact_info');
        $info = $user->where("user_id=$user_id")->getRow();
        if(empty($info))
        {
            return false;
        }
        if (($info['quan_points'] - $num) < 0) {
            return false;
        }
        return true;


    }

}