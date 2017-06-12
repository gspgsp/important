<?php
/**
 * Created by PhpStorm.
 * User: sick
 * Date: 17-5-3
 * Time: 上午10:33
 */
class friendAction extends baseAction
{
    /**
     * 获取通讯录首页数据
     * @api {post} /qapi_3/friend/getPlasticPerson 获取通讯录首页数据
     * @apiVersion 3.2.0
     * @apiName  getPlasticPerson
     * @apiGroup friend
     * @apiUse UAHeader
     *
     * @apiParam   {String} letter 联系人首字母 已废弃字段
     * @apiParam   {String} keywords   关键词
     * @apiParam   {Number} page   页码
     * @apiParam   {String} sortField   排序字段
     * @apiParam   {String} sortOrder   排序字段 默认DESC
     * @apiParam   {String} channel   数据类型 已废弃 默认为6
     * @apiParam   {String} quan_type   关键词
     * @apiParam   {Number} region   区域 华东华南华北其他
     * @apiParam   {String} c_type   客户类型 全部 塑料制品业厂 原材料供应商 物流服务商 其他
     *
     * @apiSuccess {String}  msg   描述
     * @apiSuccess {int}  err   错误码
     * @apiSuccess {int}  member   APP总人数
     * @apiSuccess {bool}  is_show_banner   是否显示banner 1显示 0不显示
     * @apiSuccess {json}  is_show_focus   是否显示我关注的人 1显示 0不显示
     * @apiSuccess {json}  is_show_cover   是否显示封面蒙层   1显示 0不显示
     * @apiSuccess {String}  banner_url      banner显示的图片地址
     * @apiSuccess {String}  banner_jump_url   banner的跳转url地址 平台需要在之后拼接？platform=ios/android
     * @apiSuccess {String}  banner_jump_native   banner是否跳转原生页面
     * @apiSuccess {String}  banner_jump_native_addreess   banner跳转原生页面回调地址
     * @apiSuccess {String}  cover_url      cover显示的图片地址
     * @apiSuccess {String}  cover_jump_url   cover的跳转地址 平台需要在之后拼接？platform=ios/android
     * @apiSuccess {String}  data   系统执行时间
     * @apiSuccess {int}  show_ctype   显示企业类型qqqqqqqqqqqqqqq
     * @apiSuccess {json}  top   置顶展示信息
     *
     * @apiSuccessExample {json} Success-Response:
            {
            "err": 0,
            "persons": [
            {
            "user_id": "53453",
            "name": "娟***",
            "c_id": "50383",
            "sex": "女",
            "member_level": "列兵",
            "is_pass": "0",
            "thumb": "http://pic.myplas.com/upload/17/06/02/59311fdc9437f.jpg",
            "thumbqq": "http://pic.myplas.com/upload/17/06/02/59311fdc9437f.jpg",
            "c_name": "上海晨达物流有限公司",
            "need_product": "7000F",
            "month_consum": "963",
            "main_product": "2119",
            "type": "1",
            "fans": "7",
            "gender": "1",
            "buy_count": "16",
            "sale_count": "10"
            },
            {
            "user_id": "53452",
            "name": "小***",
            "c_id": "50382",
            "sex": "男",
            "member_level": "列兵",
            "is_pass": "0",
            "thumb": "http://static.svnonline.com/myapp/img/male.jpg",
            "thumbqq": "",
            "c_name": "上海新村电子商务股份有限公司",
            "need_product": "7000F",
            "month_consum": "129",
            "main_product": "2119",
            "type": "1",
            "fans": "3",
            "gender": "0",
            "buy_count": "0",
            "sale_count": "0"
            },
            {
            "user_id": "53451",
            "name": "小***",
            "c_id": "50382",
            "sex": "男",
            "member_level": "列兵",
            "is_pass": "0",
            "thumb": "http://static.svnonline.com/myapp/img/male.jpg",
            "thumbqq": "",
            "c_name": "上海新村电子商务股份有限公司",
            "need_product": "7000F",
            "month_consum": "129",
            "main_product": "2119",
            "type": "1",
            "fans": "1",
            "gender": "0",
            "buy_count": "0",
            "sale_count": "0"
            },
            {
            "user_id": "53446",
            "name": "小***",
            "c_id": "50382",
            "sex": "男",
            "member_level": "列兵",
            "is_pass": "0",
            "thumb": "http://static.svnonline.com/myapp/img/male.jpg",
            "thumbqq": "",
            "c_name": "上海新村电子商务股份有限公司",
            "need_product": "7000F",
            "month_consum": "129",
            "main_product": "2119",
            "type": "1",
            "fans": 0,
            "gender": "0",
            "buy_count": "0",
            "sale_count": "0"
            },
            {
            "user_id": "53441",
            "name": "韩***",
            "c_id": "50377",
            "sex": "女",
            "member_level": "列兵",
            "is_pass": "0",
            "thumb": "http://static.svnonline.com/myapp/img/female.jpg",
            "thumbqq": "",
            "c_name": "中国能之光新材料科技股份有限公司（宁波分公司）",
            "need_product": "7000F",
            "month_consum": "120吨",
            "main_product": "2769",
            "type": "1",
            "fans": "3",
            "gender": "1",
            "buy_count": "20",
            "sale_count": "10"
            },
            {
            "user_id": "53398",
            "name": "刘***",
            "c_id": "50310",
            "sex": "女",
            "member_level": "列兵",
            "is_pass": "0",
            "thumb": "http://static.svnonline.com/myapp/img/female.jpg",
            "thumbqq": null,
            "c_name": "上海塑料制品企业",
            "need_product": "X M2 T24",
            "month_consum": "0.00",
            "main_product": "de",
            "type": "1",
            "fans": "6",
            "gender": "1",
            "buy_count": "6",
            "sale_count": "0"
            },
            {
            "user_id": "53395",
            "name": "张***",
            "c_id": "50307",
            "sex": "女",
            "member_level": "列兵",
            "is_pass": "0",
            "thumb": "http://static.svnonline.com/myapp/img/female.jpg",
            "thumbqq": null,
            "c_name": "成都商业塑料加工厂",
            "need_product": "TG-1000S OPE792",
            "month_consum": "140吨",
            "main_product": "???",
            "type": "1",
            "fans": "7",
            "gender": "1",
            "buy_count": "4",
            "sale_count": "5"
            },
            {
            "user_id": "41013",
            "name": "黄***",
            "c_id": "38505",
            "sex": "男",
            "member_level": "列兵",
            "is_pass": "0",
            "thumb": "http://pic.myplas.com/upload/17/05/26/5927d9a957fcd.jpg",
            "thumbqq": "http://pic.myplas.com/upload/17/05/26/5927d9a957fcd.jpg",
            "c_name": "余姚市万信塑染有限公司",
            "need_product": "生产/销售：色母粒、填充料厂家",
            "month_consum": "1231",
            "main_product": "pp",
            "type": "1",
            "fans": "2",
            "gender": "0",
            "buy_count": "0",
            "sale_count": "0"
            },
            {
            "user_id": "28922",
            "name": "王***",
            "c_id": "50395",
            "sex": "女",
            "member_level": "列兵",
            "is_pass": "0",
            "thumb": "http://pic.myplas.com/upload/17/06/08/5938c9fdae099.jpg",
            "thumbqq": "http://pic.myplas.com/upload/17/06/08/5938c9fdae099.jpg",
            "c_name": "石家庄塑料编织有限司",
            "need_product": "",
            "month_consum": "0.00",
            "main_product": "",
            "type": "1",
            "fans": "19",
            "gender": "1",
            "buy_count": "16",
            "sale_count": "44"
            },
            {
            "user_id": "53459",
            "name": "阿***",
            "c_id": "50389",
            "sex": "男",
            "member_level": "列兵",
            "is_pass": "0",
            "thumb": "http://static.svnonline.com/myapp/img/male.jpg",
            "thumbqq": "",
            "c_name": "上海自晨电子商务有限公司",
            "need_product": "",
            "month_consum": "0.00",
            "main_product": "",
            "type": "1",
            "fans": "2",
            "gender": "0",
            "buy_count": "1",
            "sale_count": "2"
            },
            {
            "user_id": "53397",
            "name": "郑***",
            "c_id": "50309",
            "sex": "女",
            "member_level": "列兵",
            "is_pass": "0",
            "thumb": "http://static.svnonline.com/myapp/img/female.jpg",
            "thumbqq": null,
            "c_name": "阿尔法信息有限公司",
            "need_product": "DG-1300 DG-700",
            "month_consum": "130T",
            "main_product": "7000F",
            "type": "2",
            "fans": "7",
            "gender": "1",
            "buy_count": "19",
            "sale_count": "15"
            }
            ],
            "member": 3317,
            "is_show_banner": 0,
            "is_show_focus": 1,
            "is_show_cover": 0,
            "banner_url": "",
            "banner_jump_url": "",
            "cover_url": "",
            "cover_jump_url": "",
            "data": 1497255962,
            "show_ctype": 0,
            "show_msg": "",
            "top": {
            "member_level": "列兵",
            "user_id": "53454",
            "name": "姐姐",
            "c_id": "50384",
            "is_pass": "0",
            "mobile": "13812345678",
            "sex": "女",
            "thumb": "http://static.svnonline.com/myapp/img/female.jpg",
            "thumbqq": "",
            "thumbcard": "http://pic.myplas.com/upload/17/06/02/593138efe0a7c.jpg",
            "c_name": "胖墩后果不堪设想。呵呵呵",
            "need_product": "2119",
            "address": "哦哦",
            "main_product": "",
            "month_consum": "0.00",
            "type": "4",
            "buy_count": "1",
            "sale_count": "1",
            "fans": "1"
            }
            }
     * @apiErrorExample {json} Error-Response:
     *     {
     *       "err": 2,
     *       "msg": "没有相关数据"
     *      }
     */
    public function getPlasticPerson ()
    {
        $this->is_ajax = true;
        //a b c d e f ...获取联系人(可以取消，现在这个功能不需要了)
        $letter = sget ('letter', 's');
        //搜素关键字
        $keywords = sget ('keywords', 's');
        $keywords = $this->clearStr ($keywords);
        $user_id  = $this->checkAccount (0);
        $page     = sget ('page', 'i', 1);
        //$size = sget('size', 'i', 10);
        $size      = 10;
        $sortField = sget ('sortField', 's', 'default');
        $sortOrder = sget ('sortOrder', 's', 'desc');
        $chanel    = (int)sget ('chanel', 'i', 6);
        $quan_type = sget ('quan_type', 'i');
        $version   = sget ('version', 's');//版本号
        $platform  = $this->checkPlatform ()['platform'];
        $region    = sget ('region', 'i', 0);
        $c_type    = sget ('c_type', 'i',0);

        // 1 工厂 2 贸易商 3 工贸一体 4 服务商  5 其他  0全部
        if (!empty($c_type)&&$page!=1&&!in_array ($c_type, array(
                0,
                1,
                2,
                4,
                5
            ))
        ) {
            $this->json_output (array(
                'err' => 1,
                'msg' => 'c_type参数错误',
            ));
        }

        //0 全部 1 华东 2 华北 3 华南 4 其他
        if (!in_array ($region, array(
            0,
            1,
            2,
            3,
            4,
        ))
        ) {
            $this->json_output (array(
                'err' => 1,
                'msg' => 'region参数错误',
            ));
        }
        if ($sortField != 'default' && $sortField != 'input_time') {
            $this->json_output (array(
                'err' => 1,
                'msg' => 'sortField参数错误',
            ));
        }
        if (!in_array ($sortOrder, array(
            'desc',
            'asc',
        ))
        ) {
            $this->json_output (array(
                'err' => 1,
                'msg' => 'sortOrder参数错误',
            ));
        }


        /**
         * 加搜索记录
         * sort_field  'DEFAULT','INPUT_TIME','NC','SC','CC','ALL','AUTO','CONCERN','DEMANDORSUPPLY'
         * 首页默认排序default  注册时间排序input_time 华北nc  华南sc  华中cc
         * 全国站all  智能推荐auto  我的关注concern  我的供求 demandorsupply
         *
         *sort_order   'ALL','SALE','BUY','ASC','DESC'
         *all 不分求购还是供给  sale 供给  buy 求购  asc 注册时间正序 desc  注册时间倒序
         */

        if (!empty($keywords)) {
            $arr = array(
                'user_id'    => $user_id,
                'sort_field' => strtoupper ($sortField),
                'sort_order' => strtoupper ($sortOrder),
                'content'    => $keywords,
                'version'    => $version,
                'ip'         => get_ip (),
                'chanel'     => $platform,
                'input_time' => CORE_TIME,
            );
            M ('qapp:plasticSearch')->add ($arr);
        }
        //加缓存提交效率
        //备注，修改时，文档和代码需要修改
        $cache = E ('RedisClusterServer', APP_LIB.'class');
        $data  = array();

        if (empty($keywords)) {
            if ($page < 4) {//前三页
                if ($user_id > 0) {

                    if (!$data['data'] = $cache->get ('qgetPlasticPerson'.$sortField.$sortOrder.$page.':'.$size.':'.$region.':'.$c_type)) {

                        $data = M ('qapp:plasticPerson')->getPlasticPerson ($user_id, $keywords, $page, $size, $region,$c_type);

                    }
                } else {
                    if (!$data['data'] = $cache->get ('qgetPlasticPerson0_'.$sortField.$sortOrder.$page.':'.$size.':'.$region.':'.$c_type)) {
                        $data = M ('qapp:plasticPerson')->getPlasticPerson ($user_id, $keywords, $page, $size, $region,$c_type);

                    }
                }
            } else {
                $data = M ('qapp:plasticPerson')->getPlasticPerson ($user_id, $keywords, $page, $size, $region,$c_type);

            }
        } else {
            $data = M ('qapp:plasticPerson')->getPlasticPerson ($user_id, $keywords, $page, $size, $region,$c_type);

        }
        if (empty($data['data']) && $page == 1) {
            $this->json_output (array(
                'err' => 2,
                'msg' => '没有相关数据',
            ));
        } elseif (empty($data['data']) && $page > 1) {
            $this->json_output (array(
                'err' => 3,
                'msg' => '没有更多记录了',
            ));
        }

        $this->_checkLastPage ($data['count'], $size, $page);
        if ($page >= 3 && $user_id <= 0) {
            $this->json_output (array(
                'err'   => 1,
                'msg'   => '想要查看更多，请登录',
                'count' => $data['count'],
            ));
        }

        if ($user_id > 0) {
            $dayTime = strtotime (date ("Y-m-d"));
            if ($page == 1 && !$this->db->from ('log_login')->select ('input_time')
                                        ->where ("input_time >".$dayTime." and chanel =6 and quan_type = $quan_type and user_id=$user_id")
                                        ->getOne ()
            ) {

                $arr = array(
                    'user_id'    => $user_id,
                    'input_time' => CORE_TIME,
                    'ip'         => get_ip (),
                    'chanel'     => $chanel,
                    'quan_type'  => $quan_type,
                );
                $this->db->model ("log_login")->add ($arr);
            };
        }

        $goods_id = $this->db->model ("points_goods")->select ('id')->where (" type =2 and status =1")->getOne ();

        $pointsOrder = M ("points:pointsOrder");
        $contact_id  = $pointsOrder->get_supply_demand_top ($goods_id);
        //showTrace();
        if ($contact_id) {
            $top = M ('qapp:plasticPersonalInfo')->getMyOwnInfo ($contact_id['pur_id']);
            foreach ($data['data'] as $key => &$val) {
                if ($val['user_id'] == $top['user_id']) {
                    unset($data['data'][$key]);
                    $data['data'] = array_values ($data['data']);
                }
            }
            unset($val);
        } else {
            $top = (object)array();
        }
        if ($page == 1) {


            // 第一页检测客户角色
            $ret_ctype = 0;

            if ($user_id > 0) {
                $cus_ctype = M ("qapp:plasticPersonalInfo")->getCusType ($user_id);
                if ($cus_ctype == 1) {
                    $ret_ctype = 2;
                } elseif ($cus_ctype == 2) {
                    $ret_ctype = 1;
                } elseif ($cus_ctype == 4) {
                    $ret_ctype = 2;
                }
            }

            $members = M ('qapp:plasticPersonalInfo')->getAllMembers ();
            $cacheMembers = $this->cache->get('qappsgetAllMember'.$user_id);
            $this->cache->set('qappsgetAllMember'.$user_id,$members,1800);
            $stemp_info = $members > $cacheMembers?'又有'.($members - $cacheMembers).'个用户入圈啦！':'';
            $members = empty($members) ? 0 : $members;
            $arr = array(
                'err'             => 0,
                'persons'         => $data['data'],
                'member'          => $members,
                'is_show_banner'  => 0,
                'is_show_focus'   => 1,
                'is_show_cover'   => 0,
                'banner_url'      => '',
                'banner_jump_url' => '',
                'cover_url'       => '',
                'cover_jump_url'  => '',
                'data'            => CORE_TIME,
                'show_ctype'       => $c_type,
                'show_msg'     =>  $stemp_info
            );
            if (!empty($top)) {
                $arr['top'] = $top;
            }
            //是否显示banner
            //M ("system:globalSetting")->del_cache ("setting");
            $setting = M ("system:globalSetting")->getSetting ();

            if (!empty($setting['qapp_banner']) && !empty($setting['qapp_banner']['start_time']) && !empty($setting['qapp_banner']['end_time']) && !empty($setting['qapp_banner']['url']) && CORE_TIME > $setting['qapp_banner']['start_time'] && CORE_TIME < $setting['qapp_banner']['end_time']) {
                $arr['is_show_banner']  = 1;
                $arr['is_show_focus']   = 0;
                $arr['banner_url']      = $setting['qapp_banner']['url'];
                $arr['banner_jump_native'] = $setting['qapp_banner']['jump_native'];
                if(empty($arr['banner_jump_native'])) {
                    $arr['banner_jump_url'] = $setting['qapp_banner']['jump_url'];
                    $arr['banner_jump_native_address'] = '';
                }else{
                    $arr['banner_jump_url'] = '';
                    $arr['banner_jump_native_address'] = $setting['qapp_banner']['jump_native_address'];
                }
            }
            //是否显示cover photo 未来待定
            if (!empty($setting['qapp_cover']) && !empty($setting['qapp_cover']['start_time']) && !empty($setting['qapp_cover']['end_time']) && !empty($setting['qapp_cover']['url']) && CORE_TIME > $setting['qapp_cover']['start_time'] && CORE_TIME < $setting['qapp_cover']['end_time']) {
                $arr['is_show_cover']  = 1;
                $arr['cover_url']      = $setting['qapp_cover']['url'];
                $arr['cover_jump_url'] = $setting['qapp_cover']['jump_url'];
            }
            $this->json_output ($arr);
        }
        $arr = array(
            'err'     => 0,
            'persons' => $data['data'],
            'data'    => CORE_TIME,
        );
        if (!empty($top)) {
            $arr['top'] = $top;
        }
        $this->json_output ($arr);
    }
    /**
     * 获取ta的求购或供给
     * @api {post} /qapi_3/friend/getTaPur  获取ta的求购或供给
     * @apiVersion 3.2.0
     * @apiName  getTaPur
     * @apiGroup friend
     * @apiUse UAHeader
     *
     * @apiParam   {String} keywords  关键词 默认为空
     * @apiParam   {int} page   页码      默认1
     * @apiParam   {int} size   每页数量  默认10
     * @apiParam   {int} type   类型      1采购 2报价
     * @apiParam   {int} user_id   userid  上海中辰电子商务有限公司
     *
     * @apiSuccess {String}  msg   描述
     * @apiSuccess {String}  err   错误码
     * @apiSuccess {String}  data  数据
     *
     * @apiSuccessExample {json} Success-Response:
             {
        "err": 0,
        "data": [
        {
        "id": "73494",
        "p_id": "8907",
        "user_id": "9266",
        "model": "500F",
        "unit_price": "12500.00",
        "store_house": "上海",
        "f_name": "沙特",
        "input_time": "02-22 11:04",
        "type": "2",
        "content": "",
        "name": "成平",
        "is_pass": "0",
        "c_name": "上海梓辰实业有限公司",
        "thumb": "http://statics.myplas.com/upload/16/10/25/580ebd01ea3db.png",
        "contents": "价格12500.00元左右/500F/沙特/上海",
        "says": []
        },
        {
        "id": "73461",
        "p_id": "8039",
        "user_id": "9266",
        "model": "7042",
        "unit_price": "30000.00",
        "store_house": "上海",
        "f_name": "上海",
        "input_time": "02-22 10:23",
        "type": "2",
        "content": "",
        "name": "成平",
        "is_pass": "0",
        "c_name": "上海梓辰实业有限公司",
        "thumb": "http://statics.myplas.com/upload/16/10/25/580ebd01ea3db.png",
        "contents": "价格30000.00元左右/7042/上海/上海",
        "says": []
        },
        {
        "id": "25302",
        "p_id": "5666",
        "user_id": "9266",
        "model": "7000F",
        "unit_price": "9550.00",
        "store_house": "上海",
        "f_name": "泰国石化",
        "input_time": "09-29 18:22",
        "type": "2",
        "content": "特价",
        "name": "成平",
        "is_pass": "0",
        "c_name": "上海梓辰实业有限公司",
        "thumb": "http://statics.myplas.com/upload/16/10/25/580ebd01ea3db.png",
        "contents": "价格9550.00元左右/7000F/泰国石化/上海/特价",
        "says": []
        }
        ]
        }
     * @apiErrorExample {json} Error-Response:
     *     {
     *       "err": 2,
     *       "msg": "没有相关数据"
     *      }
     */
    public function getTaPur ()
    {
        $this->is_ajax = true;
        if ($_POST) {
            $user_id  = $this->checkAccount ();
            $keywords = sget ('keywords', 's');//这里传空值P
            $page     = sget ('page', 'i', 1);
            $size     = sget ('size', 'i', 10);
            $type     = sget ('type', 'i', 1);//1采购 2报价
            $userid   = sget ('userid', 'i');//当前联系人的id
            $data     = M ('qapp:plasticRelease')->getReleaseMsg2 ($keywords, $page, $size, $userid, $type);
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
     * 关注或取消关注
     * @api {post} /qapi_3/friend/focusOrCancel    关注或取消关注
     * @apiVersion 3.2.0
     * @apiName  focusOrCancel
     * @apiGroup friend
     * @apiUse UAHeader
     *
     * @apiParam   {int} focused_id 当前联系人的id
     *
     * @apiSuccess {String}  msg   描述
     * @apiSuccess {String}  err   错误码
     * @apiSuccess {String}  data  数据
     *
     */
    public function focusOrCancel ()
    {
        $this->is_ajax = true;
        if ($_POST) {
            $user_id    = $this->checkAccount ();
            $focused_id = sget ('focused_id', 'i');//当前联系人的id
            $result     = M ('qapp:plasticAttention')->getAttention ($user_id, $focused_id);
            $this->json_output ($result);
        }
        $this->_errCode (6);
    }
    /**
     * 塑料圈联系人的-发送消息
     * @api {post} /qapi_3/friend/sendZoneContactMsg   塑料圈联系人的-发送消息
     * @apiVersion 3.2.0
     * @apiName  sendZoneContactMsg
     * @apiGroup friend
     * @apiUse UAHeader
     *
     * @apiParam   {int} userId     接受消息人的id
     * @apiParam   {String} content  content
     *
     * @apiSuccess {String}  msg   描述
     * @apiSuccess {String}  err   错误码
     * @apiSuccess {String}  data  数据
     *
     * @apiSuccessExample {json} Success-Response:
         *{
        "err": 0,
        "msg": "消息发送成功"
        }
     *
     */
    public function sendZoneContactMsg ()
    {
        $this->is_ajax = true;
        if ($_POST) {
            $user_id = $this->checkAccount ();
            $userId  = sget ('userId', 'i');//接受消息人的id
            $content = sget ('content', 's');
            $result  = M ('plasticzone:plasticRepeat')->saveZoneMsg ($user_id, $userId, $content);
            if ($result) {
                $this->json_output (array(
                    'err' => 0,
                    'msg' => '消息发送成功',
                ));
            }
        }
        $this->_errCode (6);
    }

    /**
     * 查看塑料圈好友资料
     * @api {post} /qapi_3/friend/getZoneFriend   查看塑料圈好友资料
     * @apiVersion 3.2.0
     * @apiName  getZoneFriend
     * @apiGroup friend
     * @apiUse UAHeader
     *
     * @apiParam   {int} user_id     当前联系人的id
     * @apiParam   {int} showType     是否消耗塑豆   点击确定  showType  为 5   第一次  传1  即可
     *
     * @apiSuccess {String}  msg   描述
     * @apiSuccess {String}  err   错误码
     * @apiSuccess {String}  data  数据
     *
     * @apiSuccessExample {json} Success-Response:
            {
            "err": 0,
            "data": {
            "user_id": "56211",
            "name": "林少剑",
            "c_id": "52732",
            "is_pass": "0",
            "mobile": "13162719631",
            "sex": "男",
            "thumb": "http://statics.myplas.com/myapp/img/male.jpg",
            "thumbqq": "",
            "thumbcard": "",
            "c_name": "上海凰玺贸易有限公司",
            "need_product": "赛科PE|PP料\n线性|0220KJ；0220AA\n中空HDPE.|5520FA；540AA\n拉丝丙|S1003；纤维丙S2040\n低融共聚K8003；高融共聚K7929",
            "address": "",
            "main_product": "贸易商 PP PE",
            "month_consum": "100吨",
            "type": "1",
            "buy": 0,
            "sale": "1",
            "status": "关注"
            }
            }
     *
     */

    public function getZoneFriend ()
    {
        $this->is_ajax = true;
        if ($_POST) {
            $user_id = $this->checkAccount ();
            $userid  = sget ('user_id', 'i');//当前联系人的id

            if($userid <= 0) $this->_errCode(6);

            $showType = sget('showType','i'); // 5  不显示 99
            if($showType!=5){
                if ($user_id != $userid) {
                    $_tmp = M ("qapp:infoList")->where ("user_id= $user_id and other_id = $userid")
                        ->order ("info_list_id desc")->getRow ();
                    if (!$_tmp) {
                        $this->_errCode (99);
                    }
                }
            }else{
                $_errTmp = A("api:points")->desScoreByTongxulu($user_id, $userid);
               if($_errTmp['err']>0){
                   if($_errTmp['err'] == 100) $this->json_output($_errTmp);
                   $this->json_output (array(
                       'err' => 7,
                       'msg' => '服务器繁忙,请稍后再试！',
                   ));
               }
            }




            /**
             * 添加记录
             * -4避免descScore第一次出现相同的记录
             */
            if($_tmp['input_time']< ( CORE_TIME - 4)){
                M ("qapp:infoList")->add (array(
                    'user_id'    => $user_id,
                    'other_id'   => $userid,
                    'input_time' => CORE_TIME,
                ));
            }
            $data = M ('qapp:plasticPersonalInfo')->getPersonalInfo ($user_id, $userid);
            if (empty($data)) {
                $this->json_output (array(
                    'err' => 2,
                    'msg' => '没有相关资料',
                ));
            }
            $this->json_output (array(
                'err'  => 0,
                'data' => $data,
            ));
        }
        $this->_errCode (6);
    }

}