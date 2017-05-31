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

    public function getExchangeList1($user_id,$page,$size)
    {
        //$data       = $orderModel->where ("uid = $user_id")->order ("id desc")->page ($page, $size)->getPage ();
        $goods1 = M('points:pointsGoods')->getOnsaleGoods(1);
        $goods2 = M('points:pointsGoods')->getOnsaleGoods(2);


        $sql1 =  " select p2p_points_order.*, p2p_customer_contact.name from p2p_points_order LEFT join p2p_customer_contact on p2p_points_order.uid = p2p_customer_contact.user_id where p2p_points_order.goods_id = {$goods2['id']} and p2p_points_order.uid = {$user_id}";

        $sql2 = " select p2p_points_order.*, p2p_purchase.content, p2p_purchase.model , p2p_purchase.fname , p2p_purchase.store_house, p2p_purchase.store_house  from p2p_points_order LEFT join p2p_purchase on p2p_points_order.pur_id = p2p_purchase.id where p2p_points_order.goods_id = {$goods1['id']} and p2p_points_order.uid = {$user_id}";

        $sql = $sql1." union ".$sql2;

        $res =  $this->page($page,$size)->getPage($sql);

        foreach($res['data'] as &$data) {
            //显示的内容
            if (empty($data['content'])) {
                if ($data['unit_price'] == 0.00 && empty($data['model']) && empty($data['fname']) && empty($data['store_house'])) {
                    return false;
                } else {
                    $data['contents'] = '价格'.$data['unit_price'].'元左右/'.$data['model'].'/'.$data['fname'].'/'.$data['store_house'];
                }
            } elseif (!empty($data['content'])) {
                if ($data['unit_price'] == 0.00 && empty($data['model']) && empty($data['fname']) && empty($data['store_house'])) {
                    $data['contents'] = $data['content'];
                } else {
                    $data['contents'] = '价格'.$data['unit_price'].'元左右/'.$data['model'].'/'.$data['fname'].'/'.$data['store_house'].'/'.$data['content'];
                }
            }
        }

        return $res;

    }

    public function getExchangeList($user_id,$page,$size)
    {
        $goods1 = M('points:pointsGoods')->getOnsaleGoods(1);
        $goods2 = M('points:pointsGoods')->getOnsaleGoods(2);
        $data       = $this->where ("uid = $user_id and status = 5")->order ("create_time desc")->page ($page, $size)->getPage ();

        foreach($data['data'] as $info )
        {
            if($info['goods_id']==$goods1['id'])
            {
                $type1[] = $info['id'];
            }

            if($info['goods_id']==$goods2['id'])
            {
                $type2[] = $info['id'];
            }
        }

        if(!empty($type1))
        {
            $str = join(',',$type1);

            $where = " po.id in (".join(',',$type1).")";
            //$sql1 = " select p2p_points_order.*, p2p_purchase.content, p2p_purchase.model , p2p_purchase.fname , p2p_purchase.store_house, p2p_purchase.store_house  from p2p_points_order LEFT join p2p_purchase on p2p_points_order.pur_id = p2p_purchase.id where p2p_points_order.id in (".$str.")";
            $data1 = $this->model('points_order')->select('po.*,pur.content,pur.model,pur.fname,pur.store_house,pur.store_house')
                         ->from('points_order po')
                         ->join('purchase pur','pur.id=po.pur_id')
                         ->page($page,$size)
                         ->where($where)
                         ->order("po.create_time DESC")
                         ->getPage();

        }

        if(!empty($type2))
        {
            $str = join(',',$type2);

            $where = " po.id in (".join(',',$type2).")";
            //$sql1 = " select p2p_points_order.*, p2p_purchase.content, p2p_purchase.model , p2p_purchase.fname , p2p_purchase.store_house, p2p_purchase.store_house  from p2p_points_order LEFT join p2p_purchase on p2p_points_order.pur_id = p2p_purchase.id where p2p_points_order.id in (".$str.")";
            $data2 = $this->model('points_order')->select('po.*, con.name')
                         ->from('points_order po')
                         ->join('customer_contact con','con.user_id=po.uid')
                         ->page($page,$size)
                         ->where($where)
                         ->order("po.create_time DESC")
                         ->getPage();

        }
        $data0['count'] = $data1['count']+$data2['count'];
            foreach($data2['data'] as $key=> &$value)
            {
                $value['type'] = 2;
                $value['contents'] = '';
            }

        foreach ($data1['data'] as $key=> &$value) {
            $value['type'] = 1;
            $value['name'] = '';
            if (empty($value['content'])) {
                if ($value['unit_price'] == 0.00 && empty($value['model']) && empty($value['fname']) && empty($value['store_house'])) {
                    $value['contents'] = '';
                } else {
                    $value['contents'] = '价格'.$value['unit_price'].'元左右/'.$value['model'].'/'.$value['f_name'].'/'.$value['store_house'];
                }
            } elseif (!empty($value['content'])) {
                if ($value['unit_price'] == 0.00 && empty($value['model']) && empty($value['fname']) && empty($value['store_house'])) {
                    $value['contents']   = $value['content'];
                    $value['b_and_s']    = '';
                    $value['deal_price'] = '';
                } else {
                    $value['contents'] = '价格'.$value['unit_price'].'元左右/'.$value['model'].'/'.$value['fname'].'/'.$value['store_house'].'/'.$value['content'];
                }
            }
            unset($value['unit_price']);
            unset($value['model']);
            unset($value['fname']);;
            unset($value['store_house']);
            unset($value['contents']);
            unset($value['content']);
        }
        $data0['data'] = arraySort(array_merge($data1['data'],$data2['data']),'create_time','desc');

        return $data0;
    }
    //$data       = $orderModel->where ("uid = $user_id")->order ("id desc")->page ($page, $size)->getPage ();


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