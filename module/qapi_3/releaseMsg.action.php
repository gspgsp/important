<?php
/**
 * Created by PhpStorm.
 * User: sick
 * Date: 17-5-3
 * Time: 上午10:48
 */

class releaseMsgAction extends baseAction
{


    /**
     * (中间供求信息)获取供求发布和消息回复
     * @api {post} /qapi_3/releaseMsg/getReleaseMsg 获取通讯录首页数据
     * @apiVersion 3.1.0
     * @apiName  getReleaseMsg
     * @apiGroup releaseMsg
     * @apiUse UAHeader
     *
     * @apiParam   {int} type   0 全部 1 求购 2 供给
     * @apiParam   {int} sortField1
     * @apiParam   {int} sortField2
     * @apiParam   {int} version
     * @apiParam   {int} page   页码
     * @apiParam   {int} size   每页显示数量
     *
     * @apiSuccess {int}  err   错误码
     * @apiSuccess {String}   msg   描述
     * @apiSuccess {String}   top   指定信息
     * @apiSuccess {String}   data   数据
     *
     * @apiSuccessExample {json} Success-Response:
             *     {
            "err": 0,
            "data": [
            {
            "id": "98174",
            "p_id": "0",
            "user_id": "20380",
            "model": null,
            "unit_price": "0.00",
            "store_house": "",
            "f_name": null,
            "input_time": "05-15 09:38",
            "type": "2",
            "content": "临沂出伊朗2119\r\n\r\n求 伊朗2119  2102TX00\r\n阿赛 托母15803\r\n\r\n电话：13290209006\r\n     0539-7168803",
            "c_name": "临沂泓盛达塑化",
            "name": "赵清兵",
            "thumb": "http://statics.myplas.com/myapp/img/male.jpg",
            "thumbqq": null,
            "sex": "0",
            "mobile_province": null,
            "is_pass": "0",
            "contents": "临沂出伊朗2119\r\n\r\n求 伊朗2119  2102TX00\r\n阿赛 托母15803\r\n\r\n电话：13290209006\r\n     0539-7168803",
            "saysCount": 0,
            "deliverPriceCount": 0
            },
            {
            "id": "98173",
            "p_id": "0",
            "user_id": "9942",
            "model": null,
            "unit_price": "0.00",
            "store_house": "",
            "f_name": null,
            "input_time": "05-15 09:37",
            "type": "2",
            "content": "天津出：中沙5502\r\n淄博出：7151U    M04\r\n黄岛出：伊朗9450F、中空BL3、注塑62N07\r\n????????????????????????????墨西哥高压ZLF002\r\n临沂出：伊朗52518、BL3、美国低压拉丝副牌\r\n08P??????????????????????????墨西哥低压膜ZHF001、高压ZLF002\r\n电话：13954456191",
            "c_name": "临沂市明泽进出口有限公司",
            "name": "王瑞",
            "thumb": "http://statics.myplas.com/myapp/img/male.jpg",
            "thumbqq": null,
            "sex": "0",
            "mobile_province": null,
            "is_pass": "0",
            "contents": "天津出：中沙5502\r\n淄博出：7151U    M04\r\n黄岛出：伊朗9450F、中空BL3、注塑62N07\r\n????????????????????????????墨西哥高压ZLF002\r\n临沂出：伊朗52518、BL3、美国低压拉丝副牌\r\n08P??????????????????????????墨西哥低压膜ZHF001、高压ZLF002\r\n电话：13954456191",
            "saysCount": 0,
            "deliverPriceCount": 0
            },
            {
            "id": "98172",
            "p_id": "0",
            "user_id": "29985",
            "model": null,
            "unit_price": "0.00",
            "store_house": "",
            "f_name": null,
            "input_time": "05-15 09:36",
            "type": "2",
            "content": "出PC1100 PC1220",
            "c_name": "上海景程化工科技有限公司",
            "name": "王玉",
            "thumb": "http://statics.myplas.com/myapp/img/male.jpg",
            "thumbqq": "",
            "sex": "0",
            "mobile_province": "山东",
            "is_pass": "0",
            "contents": "出PC1100 PC1220",
            "saysCount": 0,
            "deliverPriceCount": 0
            },
            {
            "id": "98171",
            "p_id": "0",
            "user_id": "28981",
            "model": null,
            "unit_price": "0.00",
            "store_house": "",
            "f_name": null,
            "input_time": "05-15 09:35",
            "type": "2",
            "content": "天津出：中沙5502\r\n淄博出：滚塑7151U、M04\r\n黄岛出：伊朗9450F、中空BL3、注塑62N07\r\n              墨西哥高压ZLF002\r\n临沂出：伊朗52518/BL3/2420H、低压拉丝副牌\r\n              墨西哥低压膜ZHF001、高压ZLF002\r\n",
            "c_name": "临沂市明泽进出口有限公司",
            "name": "娄兴隆",
            "thumb": "http://statics.myplas.com/myapp/weixin/1475032231.jpg",
            "thumbqq": "http://statics.myplas.com/myapp/weixin/1475032231.jpg",
            "sex": "0",
            "mobile_province": "山东",
            "is_pass": "0",
            "contents": "天津出：中沙5502\r\n淄博出：滚塑7151U、M04\r\n黄岛出：伊朗9450F、中空BL3、注塑62N07\r\n              墨西哥高压ZLF002\r\n临沂出：伊朗52518/BL3/2420H、低压拉丝副牌\r\n              墨西哥低压膜ZHF001、高压ZLF002\r\n",
            "saysCount": 0,
            "deliverPriceCount": 0
            },
            {
            "id": "98169",
            "p_id": "0",
            "user_id": "40199",
            "model": null,
            "unit_price": "0.00",
            "store_house": "",
            "f_name": null,
            "input_time": "05-15 09:33",
            "type": "2",
            "content": "P0M现货20吨，价格11600一吨不含税自提，欢迎来电17685866822微信同号",
            "c_name": "青岛聚福工程塑胶有限公司",
            "name": "唐勇松",
            "thumb": "http://statics.myplas.com/upload/17/02/21/58abf0f570dc1.jpg",
            "thumbqq": "http://statics.myplas.com/upload/17/02/21/58abf0f570dc1.jpg",
            "sex": "0",
            "mobile_province": "山东",
            "is_pass": "0",
            "contents": "P0M现货20吨，价格11600一吨不含税自提，欢迎来电17685866822微信同号",
            "saysCount": 0,
            "deliverPriceCount": 0
            },
            {
            "id": "98168",
            "p_id": "0",
            "user_id": "60738",
            "model": null,
            "unit_price": "0.00",
            "store_house": "",
            "f_name": null,
            "input_time": "05-15 09:33",
            "type": "2",
            "content": "本公司专业制作流延CPE      其具有透明度好，薄厚均匀，走机速度快",
            "c_name": "河北精瑞包装材料有限",
            "name": "姜女士",
            "thumb": "http://statics.myplas.com/myapp/img/female.jpg",
            "thumbqq": "",
            "sex": "1",
            "mobile_province": "河北",
            "is_pass": "0",
            "contents": "本公司专业制作流延CPE      其具有透明度好，薄厚均匀，走机速度快",
            "saysCount": 0,
            "deliverPriceCount": 0
            },
            {
            "id": "98167",
            "p_id": "0",
            "user_id": "32905",
            "model": null,
            "unit_price": "0.00",
            "store_house": "",
            "f_name": null,
            "input_time": "05-15 09:33",
            "type": "2",
            "content": "上海出：2426H(泰国) \r\n黄岛出：2426H(泰国)\r\n            FJ00952(沙特）    \r\n电话：13331051968",
            "c_name": "北京秋硕金隆塑料制品有限公司",
            "name": "王艳",
            "thumb": "http://statics.myplas.com/myapp/img/female.jpg",
            "thumbqq": "",
            "sex": "1",
            "mobile_province": "北京",
            "is_pass": "0",
            "contents": "上海出：2426H(泰国) \r\n黄岛出：2426H(泰国)\r\n            FJ00952(沙特）    \r\n电话：13331051968",
            "saysCount": 0,
            "deliverPriceCount": 0
            },
            {
            "id": "98166",
            "p_id": "0",
            "user_id": "41176",
            "model": null,
            "unit_price": "0.00",
            "store_house": "",
            "f_name": null,
            "input_time": "05-15 09:32",
            "type": "2",
            "content": "青岛出:\r\n高压:15803托姆\r\n       801YY马来大腾涂覆\r\n       2119伊朗\r\n       LDF0823巴西0.8重包\r\n    BF2021巴西溶质2不开口\r\n茂金属：3518CB\r\n拉丝276-73俄卢克石油\r\n??????????????W50A009A印度\r\n?",
            "c_name": "临沂中诚贸易有限公司",
            "name": "罗小莉",
            "thumb": "http://statics.myplas.com/myapp/img/female.jpg",
            "thumbqq": "",
            "sex": "1",
            "mobile_province": "山东",
            "is_pass": "0",
            "contents": "青岛出:\r\n高压:15803托姆\r\n       801YY马来大腾涂覆\r\n       2119伊朗\r\n       LDF0823巴西0.8重包\r\n    BF2021巴西溶质2不开口\r\n茂金属：3518CB\r\n拉丝276-73俄卢克石油\r\n??????????????W50A009A印度\r\n?",
            "saysCount": 0,
            "deliverPriceCount": 0
            },
            {
            "id": "98165",
            "p_id": "0",
            "user_id": "29810",
            "model": null,
            "unit_price": "0.00",
            "store_house": "",
            "f_name": null,
            "input_time": "05-15 09:30",
            "type": "2",
            "content": "上海出：阿塞15803  宝山现货\r\n上海求：伊朗2420H  宝山塑托现货一柜",
            "c_name": "武汉汉帆塑料有限公司",
            "name": "张剑",
            "thumb": "http://statics.myplas.com/myapp/img/male.jpg",
            "thumbqq": "",
            "sex": "0",
            "mobile_province": "湖北",
            "is_pass": "0",
            "contents": "上海出：阿塞15803  宝山现货\r\n上海求：伊朗2420H  宝山塑托现货一柜",
            "saysCount": 0,
            "deliverPriceCount": 0
            },
            {
            "id": "98164",
            "p_id": "0",
            "user_id": "28981",
            "model": null,
            "unit_price": "0.00",
            "store_house": "",
            "f_name": null,
            "input_time": "05-15 09:28",
            "type": "2",
            "content": "天津出：中沙5502\r\n淄博出：滚塑7151U、M04\r\n黄岛出：伊朗9450F、中空BL3、注塑62N07\r\n              墨西哥高压ZLF002\r\n临沂出：伊朗52518/BL3/2420H、低压拉丝副牌\r\n              墨西哥低压膜ZHF001、高压ZLF002\r\n",
            "c_name": "临沂市明泽进出口有限公司",
            "name": "娄兴隆",
            "thumb": "http://statics.myplas.com/myapp/weixin/1475032231.jpg",
            "thumbqq": "http://statics.myplas.com/myapp/weixin/1475032231.jpg",
            "sex": "0",
            "mobile_province": "山东",
            "is_pass": "0",
            "contents": "天津出：中沙5502\r\n淄博出：滚塑7151U、M04\r\n黄岛出：伊朗9450F、中空BL3、注塑62N07\r\n              墨西哥高压ZLF002\r\n临沂出：伊朗52518/BL3/2420H、低压拉丝副牌\r\n              墨西哥低压膜ZHF001、高压ZLF002\r\n",
            "saysCount": 0,
            "deliverPriceCount": 0
            }
            ],
            "top": {}
            }
     * @apiErrorExample {json} Error-Response:
     *      {
     *       "err": 2,
     *       "msg": "没有相关数据"
     *      }
     */
    public function getReleaseMsg ()
    {
        $this->is_ajax = true;
        if ($_POST) {
            $user_id = $this->checkAccount ();
            //筛选条件
            $keywords   = sget ('keywords', 's');
            $keywords   = $this->clearStr ($keywords);
            $type       = sget ('type', 'i', 0);//0 全部 1 求购 2 供给
            $sortField1 = strtoupper (sget ('sortField1', 's'));
            $sortField2 = strtoupper (sget ('sortField2', 's'));
            $version    = sget ('version', 's');//版本号
            $platform   = $this->checkPlatform ()['platform'];
            //$sortField =array('AUTO','NC');
            $fieldNum = '';
            $fieldNum .= $sortField1;
            if (empty($sortField1)) {
                if (empty($sortField2)) {
                    $fieldNum = '';
                } else {
                    $fieldNum = $sortField2;
                }
            } else {
                if (!empty($sortField2)) {
                    $fieldNum .= ','.$sortField2;
                }
            }


            if ($type == 0) {
                $sortOrder = 'All';
            } elseif ($type == 1) {
                $sortOrder = 'BUY';
            } elseif ($type == 2) {
                $sortOrder = 'SALE';
            }
            /**
             * 加上搜索记录
             */
            $arr = array(
                'user_id'    => $user_id,
                'sort_field' => $fieldNum,
                'sort_order' => $sortOrder,
                'content'    => $keywords,
                'version'    => $version,
                'ip'         => get_ip (),
                'chanel'     => $platform,
                'input_time' => CORE_TIME,
            );
            M ('qapp:plasticSearch')->add ($arr);


            //普通条件
            $page = sget ('page', 'i', 1);
            $size = sget ('size', 'i', 10);
            // 检测是否有标准格式供求

            if ($page == 1 && $sortField2 == 'AUTO' && empty($keywords)) {
                $has_purchase = M ('qapp:plasticRelease')->checkPurchase ($user_id,2);
                if(empty($has_purchase))
                {
                    $this->json_output (array(
                        'err' => 7,
                        'msg' => '您最近5天内未发布供求信息,暂无推荐！赶紧点击按钮去发布哦～',
                    ));
                }
                $has_standard = M ('qapp:plasticRelease')->checkPurchase ($user_id,1);
                if (empty($has_standard)) {
                    $this->json_output (array(
                        'err' => 7,
                        'msg' => '您最近5天内未发布标准格式供求信息,暂无推荐！赶紧点击按钮去发布哦～',
                    ));
                }
            }
            //检测是否有塑料圈关注的人
            if ($page == 1 && $sortField2 == 'CONCERN') {
                $has_concern = M ('qapp:plasticIntroduction')->getMyFuns ($user_id, 2, 1, $size);
                if (empty($has_concern['data'])) {
                    $this->json_output (array(
                        'err' => 9,
                        'msg' => '您尚未在系统中关注任何用户，暂无推荐！赶紧点击“通讯录-查看个人信息”页面-【关注】按钮去关注吧！',
                    ));
                }
            }
            //获取供求详细数据
            $data = M ('qapp:plasticRelease')->getReleaseMsg ($keywords, $page, $size, $type, $sortField1, $sortField2, $user_id);

            if ($data == 'tempErr') {
                $this->_errCode (5);
            }
            if (empty($data['data']) && $page == 1 && $sortField2 == 'AUTO' && empty($keywords)) {
                $this->json_output (array(
                    'err' => 4,
                    'msg' => '系统暂未为您匹配到相应的牌号，暂无推荐！',
                ));
            }
            if (empty($data['data']) && $page == 1 && $sortField2 == 'CONCERN') {
                $this->json_output (array(
                    'err' => 6,
                    'msg' => '您关注的塑料圈用户暂无供求信息！',
                ));
            }
            if (empty($data['data']) && $page == 1 && $sortField2 == 'DEMANDORSUPPLY') {
                $this->json_output (array(
                    'err' => 8,
                    'msg' => '您未发布任何供求信息！',
                ));
            }
            if (empty($data['data']) && $page == 1) {
                $this->json_output (array(
                    'err' => 2,
                    'msg' => '没有相关数据',
                ));
            }
            if (empty($data['data'])) {
                $this->json_output (array(
                    'err' => 3,
                    'msg' => '没有更多数据',
                ));
            }
            $this->_checkLastPage ($data['count'], $size, $page);
            $goods_id    = $this->db->model ("points_goods")->select ('id')->where (" type =1 and status =1")
                                    ->getOne ();
            $pointsOrder = M ("points:pointsOrder");

            $info0 = $pointsOrder->get_supply_demand_top ($goods_id);

            //只有在有置顶头条并且页面是首页或者智能推荐时候有效
            if ($info0 && ($sortField1 == 'ALL' || $sortField2 == 'AUTO')) {

                $top      = M ('qapp:plasticRelease')->getReleaseMsgDetail ($info0['pur_id'],$info0['uid']);
                $personal = M ('qapp:plasticPersonalInfo')->getMyOwnInfo ($info0['uid']);
                $_tmp     = $top['info'];
                unset($top['info']);
                $top = array_merge ($top, $_tmp, $personal);

                foreach ($data['data'] as $key => &$val) {
                    if ($val['id'] == $top['id']) {
                        unset($data['data'][$key]);
                        $data['data'] = array_values ($data['data']);
                    }
                }
                unset($val);

                $arr = array(
                    'err'  => 0,
                    'data' => $data['data'],
                    'top'  => $top,
                );
            } else {
                $top = (object)array();

                $arr = array(
                    'err'  => 0,
                    'data' => $data['data'],
                    'top'  => $top,
                );

            }

            if($page == 1 && $sortField1 == 'ALL'){
                $stmp_releaseMsgNum = $this->cache->get('qappsreleaseMsgNum'.$user_id);
                $this->cache->set('qappsreleaseMsgNum'.$user_id,$data['count'],1800);
                $arr['show_msg'] = $data['count'] >$stmp_releaseMsgNum ? '更新了'.($data['count'] - $stmp_releaseMsgNum).'条数据':'';
            }

            $this->json_output ($arr);
        }
        $this->_errCode (6);
    }
    /**
     * (中间供求信息)获取供求发布(详情)
     * @api {post} /qapi_3/releaseMsg/getReleaseMsgDetail (中间供求信息)获取供求发布(详情)
     * @apiVersion 3.1.0
     * @apiName  getReleaseMsgDetail
     * @apiGroup releaseMsg
     * @apiUse UAHeader
     *
     * @apiParam   {Number} user_id  用户id
     * @apiParam   {Number} id   信息ID
     *
     * @apiSuccess {int}  err   错误码
     * @apiSuccess {String}   msg   描述
     * @apiSuccess {String}   data   数据
     *
     * @apiSuccessExample {json} Success-Response:
     *{
    "err": 0,
    "data": {
    "id": "98174",
    "p_id": "0",
    "user_id": "20380",
    "model": null,
    "unit_price": "0.00",
    "store_house": "",
    "f_name": null,
    "type": "2",
    "content": "临沂出伊朗2119\r\n\r\n求 伊朗2119  2102TX00\r\n阿赛 托母15803\r\n\r\n电话：13290209006\r\n     0539-7168803",
    "input_time": "05-15 09:38",
    "contents": "临沂出伊朗2119\r\n\r\n求 伊朗2119  2102TX00\r\n阿赛 托母15803\r\n\r\n电话：13290209006\r\n     0539-7168803",
    "b_and_s": "",
    "deal_price": "",
    "saysCount": 0,
    "deliverPriceCount": 0,
    "info": {
    "name": "",
    "c_name": "",
    "need_product": "",
    "thumb": "http://statics.myplas.com/myapp/img/male.jpg",
    "fans": 0,
    "member_level": null,
    "sex": null,
    "buy_count": "",
    "sale_count": "",
    "status": "关注"
    }
    }
    }
     *  @apiErrorExample {json} Error-Response:
     *      {
     *       "err": 6,
     *       "msg": "参数错误"
     *      }
     */
    public function getReleaseMsgDetail ()
    {
        if ($_POST['token']) {
            $user_id = $this->checkAccount ();
            $id      = sget ('id', 'i');
            $own_id  = sget ('user_id', 'i');
            if ($id < 1 || $user_id < 1) {
                $this->_errCode (6);
            }
            $data = M ('qapp:plasticRelease')->getReleaseMsgDetail ($id, $own_id, $user_id);
            $this->_errCode (0, $data);
        }
        $this->_errCode (6);
    }

    /**
     * (中间供求信息)获取供求发布(详情)的消息回复
     * @api {post} /qapi_3/releaseMsg/getReleaseMsgDetailReply (中间供求信息)获取供求发布(详情)的消息回复
     * @apiVersion 3.1.0
     * @apiName  getReleaseMsgDetailReply
     * @apiGroup releaseMsg
     * @apiUse UAHeader
     *
     * @apiParam   {Number} user_id  用户id
     * @apiParam   {Number} id   信息ID
     * @apiParam   {int} page   页码
     * @apiParam   {int} size   每页显示数量
     *
     * @apiSuccess {int}  err   错误码
     * @apiSuccess {String}   msg   描述
     * @apiSuccess {String}   data   数据
     *
     * @apiSuccessExample {json} Success-Response:
    {
    "err": 0,
    "data": {
    "count": "1",
    "data": [
    {
    "id": "1740",
    "rev_id": "3259",
    "user_id": "37848",
    "is_read": "0",
    "content": "要埃克森的什么牌号？2045G   1018HA",
    "input_time": "3小时前",
    "info": {
    "user_id": "37848",
    "name": "高鹏",
    "c_id": "28963",
    "is_pass": "0",
    "mobile": "18369517155",
    "sex": "男",
    "thumb": "http://statics.myplas.com/upload/17/05/03/5909ab447abc2.jpg",
    "thumbqq": "http://statics.myplas.com/upload/17/05/03/5909ab447abc2.jpg",
    "thumbcard": "",
    "c_name": "临沂国际商品交易中心",
    "need_product": "PP拉丝 7042 2102TN26 2100TN0 中沙6010 6098 9085 LD100AC F5606 F5608",
    "address": "山东省临沂市兰山区"
    }
    }
    ]
    }
    }
     *  @apiErrorExample {json} Error-Response:
     *      {
     *       "err": 2,
     *       "msg": "没有相关的数据"
     *      }
     */
    public function getReleaseMsgDetailReply ()
    {
        if ($_POST['token']) {
            $this->checkAccount ();
            $id      = sget ('id', 'i');
            $user_id = sget ('user_id', 'i');
            $page    = sget ('page', 'i', 1);
            $size    = sget ('size', 'i', 5);
            if ($id < 1 || $user_id < 1) {
                $this->_errCode (6);
            }
            $data = M ('qapp:plasticRelease')->getReleaseMsgDetailReply ($id, $user_id, $page, $size);
            if (empty($data['data']) && $page == 1) {
                $this->json_output (array(
                    'err' => 2,
                    'msg' => '没有相关的数据',
                ));
            }
            $this->_checkLastPage ($data['count'], $size, $page);
            $this->_errCode (0, $data);
        }
        $this->_errCode (6);
    }
    /**
     * 供求消息中的出价
     * @api {post} /qapi_3/releaseMsg/deliverPrice 供求消息中的出价
     * @apiVersion 3.1.0
     * @apiName  deliverPrice
     * @apiGroup releaseMsg
     * @apiUse UAHeader
     *
     * @apiParam   {Number} rev_id  接收报价的人
     * @apiParam   {Number} id   信息ID 对应purchase表的id
     * @apiParam   {int} page   页码
     * @apiParam   {int} size   每页显示数量
     * @apiParam   {int} price  价格
     * @apiParam   {int} type   1 求购 2 供给
     *
     * @apiSuccess {int}  err   错误码
     * @apiSuccess {String}   msg   描述
     * @apiSuccess {String}   data   数据
     *
     * @apiSuccessExample {json} Success-Response:
     *     {
     *       "err": 0,
     *       "msg": "发布成功"
     *      }
     * @apiErrorExample {json} Error-Response:
     *      {
     *       "err": 5,
     *       "msg": "发布失败"
     *      }
     */
    public function deliverPrice ()
    {
        if ($_POST['token']) {
            $user_id = $this->checkAccount ();
            $id      = sget ('id', 'i');//对应purchase表的id
            $rev_id  = sget ('rev_id', 'i');//接收报价的人
            $type    = sget ('type', 'i');// 1 求购 2 供给
            $price   = sget ('price', 'f');
            if ($price <= 0 || $price > 50000) {
                $this->json_output (array(
                    'err' => 6,
                    'msg' => '价格输入不正常，请重新输入',
                ));
            }
            $price = sprintf ("%.2f", $price);
            $arr   = array(
                'pur_id'     => $id,
                'send_id'    => $user_id,
                'user_id'    => $rev_id,
                'type'       => $type,
                'price'      => $price,
                'input_time' => CORE_TIME,
            );
            if (M ('qapp:plasticQuote')->setPurchasePrice ($arr)) {
                $this->json_output (array(
                    'err' => 0,
                    'msg' => '发布成功',
                ));
            }
            $this->json_output (array(
                'err' => 5,
                'msg' => '发布失败',
            ));
        }
        $this->_errCode (6);
    }
    /**
     * 获取供求消息的出价
     * @api {post} /qapi_3/releaseMsg/getDeliverPrice 获取供求消息的出价
     * @apiVersion 3.1.0
     * @apiName  getDeliverPrice
     * @apiGroup releaseMsg
     * @apiUse UAHeader
     *
     * @apiParam   {Number} rev_id  //接收出价的人(即发布purchase报价消息的人)
     * @apiParam   {Number} id   信息ID 对应purchase表的id
     * @apiParam   {int} page   页码
     * @apiParam   {int} size   每页显示数量
     *
     * @apiSuccess {int}  err   错误码
     * @apiSuccess {String}   msg   描述
     * @apiSuccess {String}   data   数据
     *
     * @apiSuccessExample {json} Success-Response:
     *     {
     *       "err": 0,
     *       "msg": "发布成功"
     *      }
     * @apiErrorExample {json} Error-Response:
     *      {
     *       "err": 5,
     *       "msg": "发布失败"
     *      }
     */
    public function getDeliverPrice ()
    {
        if ($_POST) {
            $this->checkAccount ();
            $id     = sget ('id', 'i');//对应purchase表的id
            $rev_id = sget ('rev_id', 'i');//接收出价的人(即发布purchase报价消息的人)
            $page   = sget ('page', 'i', 1);
            $size   = sget ('size', 'i', 5);
            $data   = M ('qapp:plasticQuote')->getPurchasePrice ($id, $rev_id, $page, $size);
            $this->_errCode (0, $data);
            if (empty($data['data']) && $page == 1) {
                $this->json_output (array(
                    'err' => 2,
                    'msg' => '没有相关的数据',
                ));
            }
            $this->_checkLastPage ($data['count'], $size, $page);
        }
        $this->_errCode (6);
    }

    /**
     * 判断提交的发布报价(采购1、报价2)数据/user/mypurchase/pub(现已修改到下面的方法)
     * @api {post} /qapi_3/releaseMsg/pub 判断提交的发布报价(采购1、报价2)数据/user/mypurchase/pub(现已修改到下面的方法)
     * @apiVersion 3.1.0
     * @apiName  pub
     * @apiGroup releaseMsg
     * @apiUse UAHeader
     *
     * @apiParamExample {json} Request-Example:
     * data[0][model]=HF5100&data[0][f_name]=shanghai&data[0][store_house]=shanghai&data[0][price]=2000&data[0][type]=2&data[0][quan_type]=0&data[0][content]=&token=14b57d4fca253b982e715f65cd619649
     *
     * @apiSuccess {int}  err   错误码
     * @apiSuccess {String}   msg   描述
     *
     * @apiSuccessExample {json} Success-Response:
     *     {
     *       "err": 0,
     *       "msg": "提交成功"
     *      }
     * @apiErrorExample {json} Error-Response:
     *      {
     *       "err": 5,
     *       "msg": "发布失败"
     *      }
     */
    public function pub ()
    {
        $this->is_ajax = true;
        if ($data = $_POST['data']) {
            $user_id = $this->checkAccount ();
            $data    = saddslashes ($data);
            $content = $data[0]['content'];//客户直接填写的求购内容，无格式
            //$cargo_type = $data[0]['cargo_type'];//现货、期货
            $type      = $data[0]['type'];//采购1、报价2
            $quan_type = sget ('quan_type', 'i');
            $pur_model = M ('product:purchase');
            $fac_model = M ('product:factory');
            $pro_model = M ('product:product');
            $soms      = M ('plasticzone:plasticPerson')->select ('c_id,customer_manager')->where ('user_id='.$user_id)
                                                        ->getRow ();
            $content   = str_replace (PHP_EOL, '', $content);
            //判断是否只有content
            if (empty($data[0]['model']) && empty($data[0]['f_name']) && empty($data[0]['store_house'])) {
                $_content = array(
                    'user_id'    => $user_id,
                    //用户id
                    'c_id'       => $soms['c_id'],
                    //客户id
                    'status'     => $type == 1 ? 1 : 2,
                    //状态，报价不需要审核，采购需要审核
                    'sync'       => 6,
                    //报价来源平台
                    'type'       => $type,
                    //采购、报价
                    'quan_type'  => $quan_type,
                    'content'    => $content,
                    //客户直接填写的求购内容
                    'input_time' => CORE_TIME,
                    //创建时间
                );
                $pur_model->startTrans ();
                try {
                    if (!$pur_model->add ($_content)) {
                        throw new Exception("系统错误 pubpur:3");
                    }
                    $pur_id = $pur_model->getLastID ();
                    //非标准发布
                    if (A("api:points")->addScoreByPur($type,2,$user_id)['err'] > 0) {
                        throw new Exception("系统错误 pubpur:3");
                    }

                } catch (Exception $e) {
                    $pur_model->rollback ();
                    $this->json_output (array(
                        'err' => 3,
                        'msg' => '插入数据失败',
                    ));
                }
                $pur_model->commit ();

                //robot表插入消息
                $tmpFuns    = M ("qapp:plasticIntroduction")->getMyFunsId ($user_id, 1);
                $tmpContent = "您关注的";
                $tmpContent .= M ("public:common")->model ('customer_contact')->select ('name')
                                                  ->where ("user_id=".$user_id)->getOne ();
                $tmpContent .= "发布了1条";
                if ($type == 1) {
                    $tmpContent .= "求购";
                } elseif ($type == 2) {
                    $tmpContent .= "供给";
                }
                $tmpContent .= "信息，信息内容为:".$content;
                if (!empty($tmpFuns)) {
                    foreach ($tmpFuns as $v) {
                        M ("qapp:robotMsg")->saveRobotMsg ($pur_id, $user_id, $v['user_id'], $tmpContent, $type = 1);
                        usleep (10);
                    }
                }
                $this->success ('提交成功');
            }
            //
            foreach ($data as $key => $value) {
                //是否已有该产品
                $model = $this->db->from ('product p')->join ('factory f', 'p.f_id=f.fid');
                //                 $where="p.model='{$value['model']}' and p.product_type={$value['product_type']} and f.f_name='{$value['f_name']}'";
                $where          = "p.model='{$value['model']}'  and f.f_name='{$value['f_name']}'";
                $pid            = $model->where ($where)->select ('p.id')->getOne ();
                $value['price'] = sprintf ("%.2f", $value['price']);
                $value['model'] = $this->clearStr ($value['model']);
                $value['model'] = trim ($value['model']);
                $value['model'] = strtoupper ($value['model']);
                if (empty($value['model'])) {
                    $value['model'] = '';
                }
                $_data = array(
                    'user_id'          => $user_id,
                    //用户id
                    'c_id'             => $soms['c_id'],
                    //客户id
                    'model'            => $value['model'],
                    //牌号
                    'customer_manager' => $soms['customer_manager'],
                    //交易员
                    //'number' => $value['number'],//吨数
                    'unit_price'       => $value['price'],
                    //单价
                    'provinces'        => $value['provinces'],
                    //省份id
                    'store_house'      => $value['store_house'],
                    //仓库
                    //'period' => $value['period'],//期货周期
                    //'bargain' => $value['bargain'],//是否实价
                    'type'             => $type,
                    //采购、报价
                    'sync'             => 6,
                    //报价来源平台
                    'quan_type'        => $quan_type,
                    'status'           => $type == 1 ? 1 : 2,
                    //状态，报价不需要审核，采购需要审核
                    'input_time'       => CORE_TIME,
                    //创建时间
                    // 'remark' => $remark,//备注
                    'content'          => $content,
                    //客户直接填写的求购内容
                );

                if ($pid) {
                    //已有产品直接添加采购信息
                    $_data['p_id'] = $pid;//产品id
                    $pur_model->startTrans ();
                    if ($pur_model->add ($_data)) {
                        $pur_id = $pur_model->getLastID ();
                        $pur_model->commit ();
                    } else {
                        $pur_model->rollback ();
                    }
                } else {
                    //没有产品则新增一个产品
                    $pur_model->startTrans ();
                    try {
                        // 是否已有厂家
                        $f_id = $fac_model->where ("f_name='{$value['f_name']}'")->select ('fid')->getOne ();
                        if (!$f_id) {
                            //创建新厂家
                            $_factory = array(
                                'f_name'     => $value['f_name'],
                                //厂家名称
                                'input_time' => CORE_TIME,
                                //创建时间
                            );
                            if (!$fac_model->add ($_factory)) {
                                throw new Exception("系统错误 pubpur:101");
                            }
                            $f_id = $fac_model->getLastID ();
                        }
                        $_product = array(
                            'model'        => $value['model'],
                            //牌号
                            'product_type' => $value['product_type'],
                            //产品类型
                            'process_type' => $value['process_level'],
                            //加工级别
                            'input_time'   => CORE_TIME,
                            //创建时间
                            'f_id'         => $f_id,
                            //厂家id
                            'status'       => 3,
                            //审核状态
                        );
                        if (!$pro_model->add ($_product)) {
                            throw new Exception("系统错误 pubpur:102");
                        }
                        $pid           = $pro_model->getLastID ();
                        $_data['p_id'] = $pid;
                        if (!$pur_model->add ($_data)) {
                            throw new Exception("系统错误 pubpur:103");
                        }

                        $pur_id = $pur_model->getLastID ();
                    } catch (Exception $e) {
                        $pur_model->rollback ();
                        $this->error ($e->getMessage ());
                    }
                    $pur_model->commit ();
                }


                //robot表插入消息
                $tmpFuns    = M ("qapp:plasticIntroduction")->getMyFunsId ($user_id, 1);
                $tmpContent = "您关注的";
                $tmpContent .= M ("public:common")->model ('customer_contact')->select ('name')
                                                  ->where ("user_id=".$user_id)->getOne ();
                $tmpContent .= "发布了1条";
                if ($type == 1) {
                    $tmpContent .= "求购";
                } elseif ($type == 2) {
                    $tmpContent .= "供给";
                }
                $tmpContent .= "信息，信息内容为:";
                $tmpContent .= '价格'.$value['price'].'元左右/'.$value['model'].'/'.$value['f_name'].'/'.$value['store_house'];
                //标准发布加积分
                if (A("api:points")->addScoreByPur($type,1,$user_id)['err'] > 0) {
                    $this->_errCode(7);
                }
                if (!empty($tmpFuns)) {
                    foreach ($tmpFuns as $v) {
                        M ("qapp:robotMsg")->saveRobotMsg ($pur_id, $user_id, $v['user_id'], $tmpContent, $type = 1);
                        usleep (10);
                    }
                }

            }
            /**
             *  添加到redis里面
             */
            // M("suggestion:suggestion")->suggestion_purchase($user_id,$pur_id);
            /**
             * 把供求需要的牌号添加到suggestion_model中
             */
            if (!empty($data[0]['model'])) {
                $suggestion_model_arr = array(
                    'user_id'     => $user_id,
                    'name'        => $data[0]['model'],
                    'type'        => 'ME',
                    'create_time' => date ("Y-m-d H:i:s"),
                    'pur_type'    => $data[0]['type'],
                );
                M ("public:common")->model ('suggestion_model')->add ($suggestion_model_arr);
            }


            $this->success ('提交成功');
        }
        $this->_errCode (6);
    }
    /**
     * 删除回复
     * @api {post} /qapi_3/releaseMsg/deleteRepeat 删除回复
     * @apiVersion 3.1.0
     * @apiName  pub
     * @apiGroup releaseMsg
     * @apiUse UAHeader
     *
     * @apiParam   {String} id     这条回复的ID
     *
     * @apiSuccess {int}  err   错误码
     * @apiSuccess {String}   msg   描述
     *
     * @apiSuccessExample {json} Success-Response:
     *     {
     *       "err": 0,
     *       "msg": "删除成功"
     *      }
     * @apiErrorExample {json} Error-Response:
     *      {
     *       "err": 6,
     *       "msg": "参数错误"
     *      }
     */
    public function deleteRepeat ()
    {
        $this->is_ajax = true;
        if ($_POST) {
            $this->checkAccount ();
            $id     = sget ('id', 'i', 0);
            $result = M ('plasticzone:plasticMyMsg')->deleteRepeat ($id);
            $this->json_output ($result);
        }
        $this->_errCode (6);
    }
    /**
     * 回复供求消息
     * @api {post} /qapi_3/releaseMsg/saveMsg 回复供求消息
     * @apiVersion 3.1.0
     * @apiName  saveMsg
     * @apiGroup releaseMsg
     * @apiUse UAHeader
     *
     * @apiParam   {String} pur_id     purchase表的消息id
     * @apiParam   {String} send_id     purchase表发报价或采购人的(pur.user_id)
     * @apiParam   {String} content     回复的内容
     *
     * @apiSuccess {int}  err   错误码
     * @apiSuccess {String}   msg   描述
     *
     * @apiSuccessExample {json} Success-Response:
     *     {
     *       "err": 0,
     *       "msg": "回复消息保存成功"
     *      }
     * @apiErrorExample {json} Error-Response:
     *      {
     *       "err": 7,
     *       "msg": "回复内容不能为空"
     *      }
     */
    public function saveMsg ()
    {
        $this->is_ajax = true;
        if ($_POST) {
            $user_id = $this->checkAccount ();
            // $user_id = sget('user_id','i',0)//回复人id
            $pur_id  = sget ('pur_id', 'i', 0);//purchase表的消息id
            $send_id = sget ('send_id', 'i', 0);//purchase表发报价或采购人的(pur.user_id)
            $content = sget ('content', 's');//回复的内容
            if (empty($content)) {
                $this->json_output (array(
                    'err' => 6,
                    'msg' => '回复内容不能为空',
                ));
            }

            //robot表插入消息
            $where  = "pur.id=$pur_id";
            $detail = M ('product:purchase')->getPurchaseLeftById ($where, null, null);//p($detail);showTrace();exit;
            $value  = $detail;
            if (empty($value['content'])) {
                if ($value['unit_price'] == 0.00 && empty($value['model']) && empty($value['f_name']) && empty($value['store_house'])) {
                    $value['contents'] = '';
                } else {
                    $value['contents'] = '价格'.$value['unit_price'].'元左右/'.$value['model'].'/'.$value['f_name'].'/'.$value['store_house'];
                }
            } elseif (!empty($value['content'])) {
                if ($value['unit_price'] == 0.00 && empty($value['model']) && empty($value['f_name']) && empty($value['store_house'])) {
                    $value['contents'] = $value['content'];
                } else {
                    $value['contents'] = '价格'.$value['unit_price'].'元左右/'.$value['model'].'/'.$value['f_name'].'/'.$value['store_house'].'/'.$value['content'];
                }
            }
            $value['input_time'] = date ("m-d H:i", $value['input_time']);
            $tmpContent          = "您于 ".$value['input_time']." 发布的";
            if ($value['type'] == 1) {
                $tmpContent .= "求购";
            } elseif ($value['type'] == 2) {
                $tmpContent .= "供给";
            }
            $tmpContent .= "信息:".$value['contents']."收到一条回复:$content";
            M ("qapp:robotMsg")->saveRobotMsg ($pur_id, $send_id, $send_id, $tmpContent, $type = 2);
            //$tmpContent.=M("public:common")->model('customer_contact')->select('name')->where("user_id=".$user_id)->getOne();
            $result = M ('qapp:plasticRepeat')->saveMsg ($user_id, $pur_id, $send_id, $content);
            if ($result) {
                $this->json_output (array(
                    'err' => 0,
                    'msg' => '回复消息保存成功',
                ));
            }
        }
        $this->_errCode (6);
    }

    /**
     * 获取我的供给或求购
     * @api {post} /qapi_3/releaseMsg/getMyMsg 获取我的供给或求购
     * @apiVersion 3.1.0
     * @apiName  getMyMsg
     * @apiGroup releaseMsg
     * @apiUse UAHeader
     *
     * @apiParam   {String} page     页数
     * @apiParam   {String} size     煤业数量
     * @apiParam   {String} type     1采购 2报价
     *
     * @apiSuccess {int}      err   错误码
     * @apiSuccess {String}   msg   描述
     * @apiSuccess {String}   data   数据
     *
     * @apiSuccessExample {json} Success-Response:
     *     {
    "err": 0,
    "data": [
    {
    "id": "90126",
    "p_id": "0",
    "user_id": "40418",
    "model": null,
    "unit_price": "0.00",
    "store_house": "",
    "f_name": null,
    "type": "1",
    "content": "求购hf5110",
    "input_time": "04-24 16:59",
    "b_and_s": "",
    "deal_price": "",
    "says": [],
    "contents": "求购hf5110"
    }
    ]
    }
     * @apiErrorExample {json} Error-Response:
     *      {
     *       "err": 2,
     *       "msg": "没有相关的数据"
     *      }
     */
    public function getMyMsg ()
    {
        $this->is_ajax = true;
        if ($_POST) {
            $user_id = $this->checkAccount ();
            $page    = sget ('page', 'i', 1);
            $size    = sget ('size', 'i', 10);
            $type    = sget ('type', 'i');//1采购 2报价
            $data    = M ('qapp:plasticMyMsg')->getMyMsg ($user_id, $page, $size, $type);
            if (empty($data['data']) && $page == 1) {
                $this->json_output (array(
                    'err' => 2,
                    'msg' => '没有相关的数据',
                ));
            }
            $this->_checkLastPage ($data['count'], $size, $page);
            $this->json_output (array(
                'err'  => 0,
                'data' => $data['data'],
            ));
        }
        $this->_errCode (6);
    }
    /**
     * 删除我的供给或求购
     * @api {post} /qapi_3/releaseMsg/deleteMyMsg 删除我的供给或求购
     * @apiVersion 3.1.0
     * @apiName  deleteMyMsg
     * @apiGroup releaseMsg
     * @apiUse UAHeader
     *
     * @apiParam   {String} id     当前我的报价或采购的id
     *
     * @apiSuccess {int}  err   错误码
     * @apiSuccess {String}   msg   描述
     *
     * @apiSuccessExample {json} Success-Response:
     *     {
     *       "err": 0,
     *       "msg": "删除成功"
     *      }
     * @apiErrorExample {json} Error-Response:
     *      {
     *       "err": 6,
     *       "msg": "参数错误"
     *      }
     */
    public function deleteMyMsg ()
    {
        $this->is_ajax = true;
        if ($_POST) {
            $this->checkAccount ();
            $id     = sget ('id', 'i');//当前我的报价或采购的id
            $result = M ('plasticzone:plasticMyMsg')->deleteMyMsg ($id);
            $this->json_output ($result);
        }
        $this->_errCode (6);
    }

    /**
     * 获取我的(留言)
     * @api {post} /qapi_3/releaseMsg/getMyComment 获取我的(留言)
     * @apiVersion 3.1.0
     * @apiName  getMyComment
     * @apiGroup releaseMsg
     * @apiUse UAHeader
     *
     * @apiParam   {String} id     当前我的报价或采购的id
     * @apiParam   {String} page     页数
     * @apiParam   {String} size     煤业数量
     *
     * @apiSuccess {int}  err   错误码
     * @apiSuccess {String}   msg   描述
     * @apiSuccess {String}   data   数据
     *
     * @apiSuccessExample {json} Success-Response:
     *     {
     *       "err": 0,
     *       "msg": "删除成功"
     *      }
     * @apiErrorExample {json} Error-Response:
     *      {
     *       "err": 2,
     *       "msg": "没有相关的数据"
     *      }
     */
    public function getMyComment ()
    {
        $this->is_ajax = true;
        if ($_POST) {
            $user_id = $this->checkAccount ();
            $page    = sget ('page', 'i', 1);
            $size    = sget ('size', 'i', 10);
            $data    = M ('qapp:plasticMyMsg')->getMyComment ($user_id, $page, $size, 6);//塑料圈app
            if (empty($data['data']) && $page == 1) {
                $this->json_output (array(
                    'err' => 2,
                    'msg' => '没有相关的数据',
                ));
            }
            $this->_checkLastPage ($data['count'], $size, $page);
            $this->json_output (array(
                'err'  => 0,
                'data' => $data['data'],
            ));
        }
        $this->_errCode (6);
    }


    /**
     * 二次发布
     * @api {post} /qapi_3/releaseMsg/secondPub 二次发布
     * @apiVersion 3.1.0
     * @apiName  secondPub
     * @apiGroup releaseMsg
     * @apiUse UAHeader
     *
     * @apiParam   {String} id     当前我的报价或采购的id
     *
     * @apiSuccess {int}  err   错误码
     * @apiSuccess {String}   msg   描述
     * @apiSuccess {String}   data   数据
     *
     * @apiSuccessExample {json} Success-Response:
    {
    "err": 0,
    "data": {
    "id": "90126",
    "p_id": "0",
    "user_id": "40418",
    "model": null,
    "unit_price": "0.00",
    "store_house": "",
    "f_name": null,
    "type": "1",
    "content": "求购hf5110",
    "input_time": "1493024389",
    "f_type": 2
    }
    }
     * @apiErrorExample {json} Error-Response:
     *      {
     *       "err": 1,
     *       "msg": "此记录输入有误，请手动补充"
     *      }
     */
    public function secondPub ()
    {
        if ($_POST) {
            $id = sget ('id', 'i');
            if (empty($id)) {
                $this->_errCode (6);
            }
            $this->checkAccount ();
            $where = " pur.sync = 6 and pur.id=$id ";
            $data  = M ("product:purchase")->getPurchaseLeftById ($where);
            if (empty($data['content'])) {
                if ($data['unit_price'] == 0.00 && empty($data['model']) && empty($data['f_name']) && empty($data['store_house'])) {
                    $this->json_output (array(
                        'err' => 1,
                        'msg' => '此记录输入有误，请手动补充',
                    ));
                }
                $data['f_type'] = 1;//格式化输出
            } elseif (!empty($data['content'])) {
                if ($data['unit_price'] == 0.00 || empty($data['model']) || empty($data['f_name']) || empty($data['store_house'])) {
                    $data['f_type'] = 2;//未格式化输出
                } else {
                    $data['f_type'] = 1;//格式化输出
                }
            }
            if (empty($data)) {
                $this->_errCode (2);
            }
            $this->_errCode (0, $data);
        }
        $this->_errCode (6);
    }


    /**
     * 供求信息置顶之供求信息列表
     * @api {post} /qapi_3/releaseMsg/supplyDemandList 供求信息置顶之供求信息列表
     * @apiVersion 3.1.0
     * @apiName  supplyDemandList
     * @apiGroup releaseMsg
     * @apiUse UAHeader
     *
     * @apiParam   {String} user_id     用户id
     * @apiParam   {String} page     页数
     * @apiParam   {String} size     煤业数量
     *@apiParam   {int} type     0全部  1采购 2报价
     *
     * @apiSuccess {int}  err   错误码
     * @apiSuccess {String}   msg   描述
     * @apiSuccess {String}   data   数据
     *
     * @apiSuccessExample {json} Success-Response:
    {
    "err": 0,
    "data": {
    "id": "90126",
    "p_id": "0",
    "user_id": "40418",
    "model": null,
    "unit_price": "0.00",
    "store_house": "",
    "f_name": null,
    "type": "1",
    "content": "求购hf5110",
    "input_time": "1493024389",
    "f_type": 2
    }
    }
     * @apiErrorExample {json} Error-Response:
     *      {
     *       "err": 2,
     *       "msg": "没有相关的数据"
     *      }
     */
    public function supplyDemandList ()
    {
        $this->is_ajax = true;
        if ($_POST) {
            $user_id = $this->checkAccount ();
            $page    = sget ('page', 'i', 1);
            $size    = sget ('size', 'i', 5);
            $type    = sget ('type', 'i');// 0全部  1采购 2报价
            $own_id  = sget ('user_id', 'i', 0);
            if (!empty($own_id)) {
                $where = " pur.sync = 6 and pur.user_id=$own_id ";
            } else {
                $where = " pur.sync = 6 and pur.user_id=$user_id ";
            }
            if (!empty($type)) {
                $where .= " and pur.type=$type";
            }
            $data = $this->db->select ('pur.id,pur.p_id,pur.user_id,pro.model,pur.unit_price,pur.store_house,fa.f_name,pur.type,pur.content,pur.input_time')
                             ->from ('purchase pur')->leftjoin ('product pro', 'pur.p_id=pro.id')
                             ->leftjoin ('factory fa', 'pro.f_id=fa.fid')->page ($page, $size)->where ($where)
                             ->order ('pur.input_time desc')->getPage ();
            if (empty($data['data']) && $page == 1) {
                $this->json_output (array(
                    'err' => 2,
                    'msg' => '没有相关的数据',
                ));
            }
            $this->_checkLastPage ($data['count'], $size, $page);
            $arr = array();
            foreach ($data['data'] as $k => &$value) {
                $value['input_time'] = $this->checkTime ($value['input_time']);
                if (empty($value['content'])) {
                    if ($value['unit_price'] == 0.00 && empty($value['model']) && empty($value['f_name']) && empty($value['store_house'])) {
                        $value['contents'] = '';
                    } else {
                        $value['contents'] .= '价格'.$value['unit_price'].'元左右/'.$value['model'].'/'.$value['f_name'].'/'.$value['store_house'];
                    }
                } elseif (!empty($value['content'])) {
                    if ($value['unit_price'] == 0.00 && empty($value['model']) && empty($value['f_name']) && empty($value['store_house'])) {
                        $value['contents'] = $value['content'];
                    } else {
                        $value['contents'] = '价格'.$value['unit_price'].'元左右/'.$value['model'].'/'.$value['f_name'].'/'.$value['store_house'].'/'.$value['content'];
                    }
                }
                $arr[$k]['type']       = $value['type'];
                $arr[$k]['input_time'] = $value['input_time'];
                $arr[$k]['p_id']       = $value['id'];
                $arr[$k]['content']    = mb_substr (strip_tags ($value['contents']), 0, 50, 'utf-8').'...';
            }
            $this->json_output (array(
                'err'  => 0,
                'data' => $arr,
            ));
        }
        $this->_errCode (6);
    }
}