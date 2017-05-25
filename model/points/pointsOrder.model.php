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
        //$cache = E('RedisClusterServer', APP_LIB . 'class');

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
        foreach($ret as $key=>$date)
        {
            if(strtotime($date)<strtotime(date('Y-m-d')))
            {
                unset($ret[$key]);
            }
        }
        $dates = array_unique($ret);
        natsort($dates);

        return array_values($dates);
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

    public function getPurchaseRecord($user_id,$goods_id,$page=1,$size=10)
    {
        $where = " 1 ";
        if(is_array($goods_id))
        {
            $con = join(',',$goods_id);
            $where .= " and order.goods_id in ({$con})";
        }elseif(is_string($goods_id)||is_numeric($goods_id)){
            $where .= " and order.goods_id = {$goods_id}";
        }

        $where .= " and order.uid = {$user_id}";

        $where .= " and order.status = 5 ";

        $orders = $this->from('points_order order')->select ('order.*,goods.thumb,goods.name,,goods.image')
            ->leftjoin('points_goods as goods','goods.id=order.goods_id')
            ->where ($where)->order("create_time desc")->page($page, $size)->getPage();

        return  $orders;
    }

}