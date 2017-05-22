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

        $top = $this->getTookDate($goods_id);
        $today = date('Y-m-d');
        /*var_dump($today);
        var_dump($top);
        var_dump(in_array($today,$top));*/
        if(in_array($today,$top)) {
            $key = 'msg_top:'.$goods_id;
            //$pur_id = $cache->get($key);
            $pur_id = 0;
            if (!empty($pur_id)) {
                return $pur_id;
            } else {
                $orderModel = M ('points:pointsOrder');

                $info = $orderModel->where ("address like '%".$today."%' and status = 5 and goods_id =".$goods_id)
                                   ->order ("outpu_time desc")->getRow ();

                if (!empty($info)) {

                    //$cache->set($key, $info['pur_id'], $info['outpu_time'] - time());
                    return $info;
                } else {
                    return false;
                }
            }

        }else{
            return false;
        }
    }

    //获取可选选择日期
    public function getTookDate($goods_id)
    {
        $time = time() - 30*24*60*60;
        $orders = $this->model('points_order')->select ('*')->where (" goods_id = ".$goods_id." and status =5 and outpu_time > ".$time)->order("outpu_time desc")->getAll();
        $ret = array();
        $this->getLastSql();
        foreach($orders as $order)
        {
            if(empty($order['address']))
            {
                continue;
            }else{
                $tmp = explode(',',$order['address']);
                if(empty($tmp))
                {
                    continue;
                }else{
                    $ret = array_filter(array_merge($ret,$tmp));
                }
            }
        }
        foreach($ret as &$date)
        {
            if(strtotime($date)<strtotime(date('Y-m-d')))
            {
                unset($date);
            }
        }

        $dates = array_values(array_unique($ret));
        natsort($dates);
        return $dates;
    }

    //判断积分是否足够
    public function checkSupply($user_id, $num = null)
    {

        $pointsRow =array();
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