<?php
/**
 * Created by PhpStorm.
 * User: sick
 * Date: 17-5-26
 * Time: 下午5:09
 */
class onlineOrderModel extends model{
    private $cache=NULL; //缓存
    private $config;
    public function __construct() {
        parent::__construct(C('db_default'), 'online_order');
        $this->cache = E ('RedisClusterServer', APP_LIB.'class');
        $this->config = array('1'=>'wechatpay','2'=>'alipay');
    }


    public function addOrder($order_id,$type,$partner_prepay_sn,$total_fee,$goods_id,$goods_num,$user_id,$uuid,$partner_app_id,$platform,$status,$remark)
    {

        $goods_info = M('points:pointsGoods')->getGoodsInfo($goods_id);

        $data= array(
            'order_id'=>$order_id,
            'type'=>$type,
            'partner_prepay_sn'=>$partner_prepay_sn,
            'total_fee'=>$total_fee,
            'goods_id'=>$goods_id,
            'goods_num'=>$goods_num,
            'goods_name'=>$goods_info['name'],
            'user_id'=>$user_id,
            'uuid'=>$uuid,
            'partner_app_id'=>$partner_app_id,
            'remote_ip'=>get_ip(),
            'input_time'=>CORE_TIME,
            'platform'=>$platform,
            'status'=>$status,
            'remark'=>$remark
        );

        $res = $this->add($data);
        return $res;
    }










}