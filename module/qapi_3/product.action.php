<?php
/**
 * Created by PhpStorm.
 * User: sick
 * Date: 17-5-5
 * Time: 下午4:10
 */
class productAction extends baseAction
{

    /*
     * 塑料圈app兑换积分的接口
     *  注册一人，暂时不送积分
     * 引荐一个人50分
     * 发布报价10分（不需要审核）、
     * 发布采购20分（需要审核，但是直接加积分）
     */

    /*
     * 塑料圈app之积分商品列表
     */
    public function getProductList ()
    {
        if ($_POST) {
            $this->is_ajax = true;
            $user_id       = $this->checkAccount ();
            $page          = sget ('page', 'i', 1);
            $size          = sget ('size', 'i', 10);
            $data          = M ("points:pointsGoods")->select ('id,cate_id,thumb,name,points,type')
                                                     ->where ("status = 1 and receive_num < num and is_mobile =1")
                                                     ->order ('id desc')->page ($page, $size)->getPage ();
            //我的积分
            $points = M ('qapp:pointsBill')->getUerPoints ($user_id);
            $points = empty($points) ? 0 : $points;
            if (empty($data['data']) && $page == 1) {
                $this->json_output (array(
                    'err' => 2,
                    'msg' => '没有相关数据',
                ));
            }
            $this->_checkLastPage ($data['count'], $size, $page);
            $supply_and_demand = M ("qapp:plasticRelease")->getReleaseMsg ('', 1, 5, 0, 'ALL', 'DEMANDORSUPPLY', $user_id);
            /*if (empty($supply_and_demand['count'])) {
                foreach ($data['data'] as $key => $info) {
                    if ($info['id'] == 35) {
                        unset($data['data'][$key]);
                        break;
                    }
                }
            }*/

            //type 1 是供求 2 是通讯录
            $goods_id = $this->db->model ("points_goods")->select ('id')->where (" type =1 and status =1")->getOne ();

            foreach ($data['data'] as $k => &$v) {
                if (!empty($goods_id) && $v['id'] == $goods_id && !empty($supply_and_demand['count'])) {
                    $v['myMsg'] = $supply_and_demand['data'];
                } else {
                    $v['myMsg'] = array();
                }
                if ($v['thumb']) {
                    $v['thumb'] = FILE_URL.'/upload/'.$v['thumb'];
                }
                if ($v['image']) {
                    $v['image'] = FILE_URL.'/upload/'.$v['image'];
                }
            }
            $ret = array(
                'err'       => 0,
                'info'      => $data['data'],
                'pointsAll' => $points,
            );

            $this->json_output ($ret);
        }
        $this->_errCode (6);
    }


    /*
 * 塑料圈app之退货规定
 */
    public function returnRule ()
    {
        if ($_POST) {
            $this->is_ajax = true;
            $this->checkAccount ();
            $v         = array();
            $v['rule'] = '<span>兑换商品若出现以下情况，我的塑料网允许退换货：</span><br />';
            $v['rule'] .= '<span>1）商品本身有质量问题，影响使用</span><br />';
            $v['rule'] .= '<span>2）兑换的商品在运输过程中出现损毁</span><br />';
            $v['rule'] .= '<span>用户可在签收后7日内拨打我的塑料网客服热线400-6129-965，申请退换货，退回时，请务必将原包装、内附赠品及说明书和相关文件一并寄回。</span><br />';
            $v['rule'] .= '<br />';
            $v['rule'] .= '<span>若出现以下情况，我的塑料网有权不予进行商品退换货：</span><br />';
            $v['rule'] .= '<span>1)非我的塑料网积分商城的兑换商品</span><br />';
            $v['rule'] .= '<span>2)不正常使用商品造成的质量问题</span><br />';
            $v['rule'] .= '<span>3)超过我的塑料网积分商城承诺的7天退换货有效时间</span><br />';
            $v['rule'] .= '<span>4)将商品存储、暴露在不适宜环境中造成的损坏</span><br />';
            $v['rule'] .= '<span>5)因未经授权的修理、改动、不正确的安装造成损坏</span><br />';
            $v['rule'] .= '<span>6)不可抗力导致礼品损坏</span><br />';
            $v['rule'] .= '<span>7)商品的正常磨损</span><br />';
            $v['rule'] .= '<span>8)在退换货之前未与我的塑料网客服取得联系，进行过退换货登记</span><br />';
            $v['rule'] .= '<span>9)退回商品包装或其他附属物不完整或有毁损</span><br />';
            $v['rule'] .= '<br />';
            $v['rule'] .= '<span>注：商品图片及文字仅供参考，具体以实物为准。</span><br />';
            $this->json_output (array(
                'err'  => 0,
                'rule' => $v['rule'],
            ));
        }
        $this->_errCode (6);
    }

    /*
 * 兑换置顶
 */
    public function exchangeSupplyOrDemand ()
    {
        $this->is_ajax = true;
        if ($_POST) {
            $type    = sget ('type', 'i');//  0 实物  1 通讯录 2 供求信息
            $user_id = $this->checkAccount ();
            if (!in_array ($type, array(
                0,
                1,
                2,
            ))
            ) {
                $this->json_output (array(
                    'err' => 11,
                    'msg' => 'type参数错误',
                ));
            }
            $goods_id = sget ('goods_id', 'i');   //所需要的商品的id
            if ($goods_id < 1) {
                $this->json_output (array(
                    'err' => 12,
                    'msg' => 'goods_id参数错误',
                ));
            }
            $pointsModel = M ("qapp:pointsBill");
            $pointsModel->startTrans ();
            try {
                $pointsRow = $pointsModel->from ("points_goods")->select ("points,name,cate_id")
                                         ->where ("status = 1 and receive_num < num and id = $goods_id")->getRow ();
                $points    = (int)$pointsRow['points'];
                if ($pointsRow['cate_id'] == 7) {
                    throw new Exception("系统错误 pubpur:119");
                }
                if (!$points) {
                    throw new Exception("系统错误 pubpur:101");
                }
                if (!$this->checkSupply ($points, 1)) {
                    throw new Exception("系统错误 pubpur:100");
                }
                //)兑换方式5 ：兑换礼品
                $exchangeType = 5; //兑换方式
                if ($type == 2) {
                    $hour = sget ('hour', 's', 13);
                    $hour = (int)$hour;
                    if ($hour < 0 || $hour > 23) {
                        throw new Exception("系统错误 pubpur:102");
                    }
                    if ($hour < date ("H")) {
                        throw new Exception("系统错误 pubpur:115");
                    }
                    if ($hour == date ("H")) {
                        throw new Exception("系统错误 pubpur:117");
                    }
                    $stime = sget ('stime', 'i', 0);//开始的时间
                    if (!in_array ($stime, array(
                        0,
                        10,
                        20,
                        30,
                        40,
                        50,
                    ))
                    ) {
                        throw new Exception("系统错误 pubpur:103");
                    }
                    if ($hour == date ("H") && $stime < date ("i")) {
                        throw new Exception("系统错误 pubpur:115");
                    }
                    $time = sget ('time', 'i', 60);//所需要兑换的时长
                    if (!$time) {
                        throw new Exception("系统错误 pubpur:104");
                    }
                    if (!in_array ($time, array(
                        10,
                        30,
                        60,
                    ))
                    ) {
                        throw new Exception("系统错误 pubpur:104");
                    }
                    $purchase_id = sget ('purchase_id', 'i', 25895);//供求信息id
                    if ($purchase_id < 1) {
                        throw new Exception("系统错误 pubpur:105");
                    }//$this->error('purchase_id参数错误');
                    $etime      = $stime + $time;
                    $data       = date ("Y-m-d");//当前的年月日
                    $exe_time_s = strtotime ($data." ".$hour.":".$stime);
                    if ($etime >= 60) {
                        $etime = $etime - 60;
                        $hour++;
                    }
                    //年跟月跟日的bug，转钟转月的bug，目前没有解决。
                    $exe_time_e = strtotime ($data." ".$hour.":".$etime);
                    $arr        = $this->exchangeTime (1);//供求信息
                    foreach ($arr as $k => $v) {
                        if ($exe_time_s >= $v['exe_time_s'] && $exe_time_e <= $v['exe_time_e']) {
                            throw new Exception("系统错误 pubpur:106"); //$this->error('选择时间段错误,已被人预定过了');
                        }
                    }
                    //开始往points_bill表中加记录了，和积分开始增加了
                    if (!$pointsModel->decPoints ($points, $user_id, $exchangeType, $goods_id)) {
                        throw new Exception("系统错误 pubpur:101");
                    }

                } elseif ($type == 1) {
                    $year = sget ('year', 's', 2016);
                    if ($year != date ("Y")) {
                        throw new Exception("系统错误 pubpur:107");
                    }//$this->error('年份参数错误');
                    $s_month = sget ('month', 's', 11);
                    if ($s_month < 1 || $s_month > 12) {
                        throw new Exception("系统错误 pubpur:108");
                    }//$this->error('月份参数错误');
                    $day = sget ('d', 's', 30);
                    //一样的，同样没有解决跨月份的问题，现在留在这里，等以后有时间再来解决这个问题
                    //跨月份已经解决了
                    //当月的只能选当月的，当月的最多只能选距离七天的时间里
                    //目前基本完成的东西
                    if ($s_month != date ("m")) {//不是当月date("t",$year."-".$s_month."-".$day)
                        $s_month_copy = date ("m") + 1;
                        if ($s_month_copy > 12) {
                            $s_month_copy = $s_month_copy - 12;
                            if ($s_month_copy != $s_month) {
                                throw new Exception("系统错误 pubpur:109");
                            }
                        } else {
                            if ($s_month < date ("m")) {
                                throw new Exception("系统错误 pubpur:115");
                            }
                            if ($s_month > (date ("m") + 1)) {
                                throw new Exception("系统错误 pubpur:109");
                            }
                            $month_copy = date ("m");
                            if ($s_month == ($month_copy + 1)) {
                                if ($day > date ("t")) {
                                    throw new Exception("系统错误 pubpur:109");
                                }//大于该月拥有的时间
                                if ($day > (date ("d") + 7 - date ("t"))) {
                                    throw new Exception("系统错误 pubpur:109");
                                }//超过七天
                            }
                        }
                    } else {//当月
                        if ($day > date ("t")) {
                            throw new Exception("系统错误 pubpur:109");
                        }//大于该月拥有的时间
                        //以前的时间
                        if ($day < date ('d') && $s_month == date ("m")) {
                            throw new Exception("系统错误 pubpur:115");
                        }//小于当前的日期
                        if ((date ('d') + 7) < $day) {
                            throw new Exception("系统错误 pubpur:109");
                        }
                    }
                    $year       = (int)$year;
                    $s_month    = (int)$s_month;
                    $day        = (int)$day;
                    $exe_time_s = strtotime ($year.'-'.$s_month.'-'.$day);
                    $exe_time_e = $exe_time_s + 86400;
                    $arr        = $this->checkTime (0);
                    $tmp        = 0;
                    foreach ($arr as $k => $v) {
                        if ($exe_time_s >= $v['exe_time_s'] && $exe_time_e <= $v['exe_time_e']) {
                            if (++$tmp >= 3) {
                                throw new Exception("系统错误 pubpur:118");
                            }
                            //$this->error('选择时间段错误,已被人预定过了');
                        }
                    }
                    //开始往points_bill表中加记录了，和积分开始增加了
                    //兑换方式5 ：兑换礼品
                    if (!$pointsModel->decPoints ($points, $user_id, 5, $goods_id)) {
                        throw new Exception("系统错误 pubpur:101");
                    }
                    //if(true) throw new Exception("系统错误 pubpur:999");
                } elseif ($type == 0) {
                    //if($goods_id<10){
                    $points   = (int)$points;
                    $receiver = sget ('receiver', 's');   //所需要的收货人
                    if (empty($receiver)) {
                        throw new Exception("系统错误 pubpur:112");
                    }
                    $phone = sget ('phone', 's');    //收货人的手机号
                    if (!is_mobile ($phone)) {
                        throw new Exception("系统错误 pubpur:113");
                    }
                    $address = sget ('address', 's'); //收货地址
                    if (empty($address)) {
                        throw new Exception("系统错误 pubpur:114");
                    }
                    if (!$pointsModel->decPoints ($points, $user_id, $exchangeType, $goods_id)) {
                        throw new Exception("系统错误 pubpur:101");
                    }
                    //}
                }
                if (in_array ($type, array(
                    1,
                    2,
                ))) {
                    $purchase_id = isset($purchase_id) ? $purchase_id : 0;
                    if ($type == 2) {
                        $type = 1;
                    } elseif ($type == 1) {
                        $type = 0;
                    }
                    $sqlArray = array(
                        'user_id'    => $user_id,
                        'type'       => $type,
                        'exe_time_s' => $exe_time_s,
                        'exe_time_e' => $exe_time_e,
                        'input_time' => CORE_TIME,
                        'purchase'   => $purchase_id,
                    );
                    if (!$this->db->model ('corn')->add ($sqlArray)) {
                        throw new Exception("系统错误 pubpur:111");
                    }//$this->error('兑换失败');
                }
                $orderModel = M ('points:pointsOrder');
                $_orderData = array(
                    'status'      => 5,
                    'create_time' => CORE_TIME,
                    'order_id'    => $this->buildOrderId (),
                    'goods_id'    => $goods_id,
                    'receiver'    => $receiver,
                    'phone'       => $phone,
                    'address'     => $address,
                    'uid'         => $user_id,
                    'usepoints'   => $points,
                    'remark'      => $pointsRow['name'],
                );
                if ($goods_id < 10) {
                    $_orderData['status'] = 1;
                }
                if (!$orderModel->add ($_orderData)) {
                    throw new Exception('系统错误，无法兑换。code:101');
                }
            } catch (Exception $e) {
                $pointsModel->rollback ();
                $code = (int)substr ($e->getMessage (), -3);
                $this->_errCode ($code);
            }
            $pointsModel->commit ();
            $this->success ('兑换成功');
        }
        $this->_errCode (6);
    }


    /**
     * 新版兑换置顶
     * @param goods_id
     * @param num
     * @param pur_id
     *
     * @return json
     */
    public function newExchangeSupplyOrDemand ()
    {
        $this->is_ajax = true;
        if ($_GET) {
            $user_id = $this->checkAccount ();
            /*if (!in_array ($type, array( 0, 1, 2 ))) {
                $this->json_output (array( 'err' => 11, 'msg' => 'type参数错误' ));
            }*/
            $goods_id = sget ('goods_id', 'i');   //所需要的商品的id
            $num      = sget ('num', 'i');   //所需要的商品的id
            $pur_id   = sget ('pur_id', 'i', 0);

            if ($goods_id < 1 || $num < 1) {
                $this->json_output (array(
                    'err' => 12,
                    'msg' => '参数错误',
                ));
            }
            $goods_info = M ('public:common')->model ('points_goods')->where ("id= $goods_id")->getRow ();
            if ($goods_info['type'] == 1 && empty($pur_id)) {
                $this->json_output (array(
                    'err' => 21,
                    'msg' => '请选择您要置顶的供求信息',
                ));
            }
            if ($goods_info['type'] == 2) {
                $pur_id = $user_id;
            }

            $user = M ('public:common')->model ('contact_info');
            if ($info = $user->where ("user_id=$user_id")->getRow ()) {
                if (($info['quan_points'] - $num * $goods_info['points']) < 0) {
                    $this->json_output (array(
                        'err' => 15,
                        'msg' => '塑豆不足',
                    ));
                }
            }
            $pointsOrder = M ("points:pointsOrder");
            $is_exist    = $pointsOrder->get_supply_demand_top ($goods_id);
            if ($is_exist) {
                $this->json_output (array(
                    'err' => 13,
                    'msg' => '有人抢先一步,如有需要，请联系客服400-6129-965',
                ));
            }
            $pointsRow = M ('public:common')->from ("points_goods")->select ("points,name,cate_id")
                                            ->where ("status = 1 and receive_num < num and id = $goods_id")->getRow ();
            $point     = (int)$pointsRow['points'];

            $_orderData = array(
                'status'      => 5,
                'create_time' => CORE_TIME,
                'order_id'    => $this->buildOrderId (),
                'goods_id'    => $goods_id,
                'uid'         => $user_id,
                'usepoints'   => $point * $num,
                'remark'      => $pointsRow['name'],
                'num'         => $num,
                'pur_id'      => $pur_id,
                'outpu_time'  => CORE_TIME + $num * 24 * 60 * 60,
            );
            if ($goods_id < 10) {
                $_orderData['status'] = 1;
            }
            if (!$pointsOrder->add ($_orderData)) {
                //throw new Exception('系统错误，无法兑换。code:101');
                $this->json_output (array(
                    'err' => 101,
                    'msg' => '系统错误，无法兑换。',
                ));
            }
            $this->json_output (array(
                'err' => 0,
                'msg' => '购买成功',
            ));
        }

    }

    /*
     * 兑换记录
    */
    public function exchangeList ()
    {
        $this->is_ajax = true;
        if ($_POST) {
            $user_id    = $this->checkAccount ();
            $page       = sget ('page', 'i', 1);
            $size       = sget ('size', 'i', 10);
            $orderModel = M ('points:pointsOrder');
            $data       = $orderModel->where ("uid = $user_id")->order ("id desc")->page ($page, $size)->getPage ();
            if (empty($data['data']) && $page == 1) {
                $this->json_output (array(
                    'err' => 2,
                    'msg' => '没有相关数据',
                ));
            }
            $this->_checkLastPage ($data['count'], $size, $page);
            foreach ($data['data'] as $k => &$v) {
                $v['name']        = M ("points:pointsGoods")->where ("id =".$v['goods_id'])->select ('name')->getOne ();
                $v['create_time'] = date ("Y-m-d H:i", $v['create_time']);
                $v['status']      = $this->orderStatus[$v['status']];
            }
            $this->json_output (array(
                'err'  => 0,
                'info' => $data['data'],
            ));
        }
        $this->_errCode (6);
    }

}