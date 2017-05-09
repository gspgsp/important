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
     * @api {get} /qapi_3/friend/getPlasticPerson 获取通讯录首页数据
     * @apiVersion 3.1.0
     * @apiName  getPlasticPerson
     * @apiGroup friend
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
     * @apiSuccess {String}  banner_jump_url   banner的跳转地址 平台需要在之后拼接？platform=ios/android
     * @apiSuccess {String}  cover_url      cover显示的图片地址
     * @apiSuccess {String}  cover_jump_url   cover的跳转地址 平台需要在之后拼接？platform=ios/android
     * @apiSuccess {String}  data   系统执行时间
     * @apiSuccess {int}  show_ctype   显示企业类型
     * @apiSuccess {json}  top   置顶展示信息
     *
     * @apiSuccessExample {json} Success-Response:
     * {
     *   "err": 0,
     *   "persons": [
     *   {
     *   "user_id": "58159",
     *   "name": "杨西娟",
     *   "c_id": "54609",
     *   "sex": "女",
     *   "member_level": "列兵",
     *   "is_pass": "0",
     *   "thumb": "http://statics.myplas.com/myapp/img/female.jpg",
        "thumbqq": "",
        "c_name": "兰州海西塑料模具制造有限公司",
        "need_product": "K8003",
        "month_consum": "100吨",
        "main_product": "采购 PP",
        "type": "1",
        "fans": "1",
        "gender": "1",
        "buy_count": "2",
        "sale_count": "0"
        },
        {
        "user_id": "58124",
        "name": "薛克",
        "c_id": "54583",
        "sex": "男",
        "member_level": "列兵",
        "is_pass": "0",
        "thumb": "http://statics.myplas.com/myapp/img/male.jpg",
        "thumbqq": "",
        "c_name": "徐州向阳包装有限公司",
        "need_product": "2426H",
        "month_consum": "700吨",
        "main_product": "采购 PE",
        "type": "1",
        "fans": 0,
        "gender": "0",
        "buy_count": "0",
        "sale_count": "0"
        },
        {
        "user_id": "57454",
        "name": "王先生",
        "c_id": "53953",
        "sex": "男",
        "member_level": "列兵",
        "is_pass": "0",
        "thumb": "http://statics.myplas.com/myapp/img/male.jpg",
        "thumbqq": "",
        "c_name": "鹏程塑业有限公司",
        "need_product": "5110 7000F",
        "month_consum": "10吨",
        "main_product": "采购 PP PE",
        "type": "1",
        "fans": "5",
        "gender": "0",
        "buy_count": "1",
        "sale_count": "0"
        },
        {
        "user_id": "57264",
        "name": "李亚",
        "c_id": "53765",
        "sex": "男",
        "member_level": "列兵",
        "is_pass": "0",
        "thumb": "http://statics.myplas.com/myapp/img/male.jpg",
        "thumbqq": "",
        "c_name": "个体经营者",
        "need_product": "塑料袋",
        "month_consum": "30吨",
        "main_product": "采购 PP PE",
        "type": "1",
        "fans": "1",
        "gender": "0",
        "buy_count": "0",
        "sale_count": "0"
        },
        {
        "user_id": "57161",
        "name": "高海生",
        "c_id": "53663",
        "sex": "男",
        "member_level": "列兵",
        "is_pass": "0",
        "thumb": "http://statics.myplas.com/myapp/img/male.jpg",
        "thumbqq": "",
        "c_name": "海云洁具公司",
        "need_product": "222WT",
        "month_consum": "40吨",
        "main_product": "采购 PP PE",
        "type": "1",
        "fans": "2",
        "gender": "0",
        "buy_count": "0",
        "sale_count": "0"
        },
        {
        "user_id": "56997",
        "name": "陈进虎",
        "c_id": "53499",
        "sex": "男",
        "member_level": "列兵",
        "is_pass": "0",
        "thumb": "http://statics.myplas.com/myapp/img/male.jpg",
        "thumbqq": "",
        "c_name": "安徽展翼塑业有限公司",
        "need_product": "TN26",
        "month_consum": "50吨",
        "main_product": "采购PP PE",
        "type": "1",
        "fans": "2",
        "gender": "0",
        "buy_count": "0",
        "sale_count": "0"
        },
        {
        "user_id": "56918",
        "name": "詹元喜",
        "c_id": "53427",
        "sex": "男",
        "member_level": "列兵",
        "is_pass": "0",
        "thumb": "http://statics.myplas.com/myapp/img/male.jpg",
        "thumbqq": "",
        "c_name": "宿迁市联升管业有限公司",
        "need_product": "BL3",
        "month_consum": "300吨",
        "main_product": "采购 PP PE",
        "type": "1",
        "fans": "5",
        "gender": "0",
        "buy_count": "0",
        "sale_count": "0"
        },
        {
        "user_id": "56703",
        "name": "王金星",
        "c_id": "53213",
        "sex": "男",
        "member_level": "列兵",
        "is_pass": "0",
        "thumb": "http://statics.myplas.com/upload/17/05/03/5909a7713f3a2.jpg",
        "thumbqq": "http://statics.myplas.com/upload/17/05/03/5909a7713f3a2.jpg",
        "c_name": "山东华宏化工有限公司",
        "need_product": "4157",
        "month_consum": "50吨",
        "main_product": "采购PE",
        "type": "1",
        "fans": "3",
        "gender": "0",
        "buy_count": "0",
        "sale_count": "13"
        },
        {
        "user_id": "56656",
        "name": "哈妹",
        "c_id": "4016",
        "sex": "女",
        "member_level": "列兵",
        "is_pass": "0",
        "thumb": "http://statics.myplas.com/myapp/img/female.jpg",
        "thumbqq": "",
        "c_name": "上海梓辰实业有限公司",
        "need_product": "AP3N，7000F",
        "month_consum": "120吨",
        "main_product": "模型",
        "type": "1",
        "fans": 0,
        "gender": "1",
        "buy_count": "0",
        "sale_count": "0"
        },
        {
        "user_id": "56122",
        "name": "彭小姐",
        "c_id": "52642",
        "sex": "女",
        "member_level": "列兵",
        "is_pass": "0",
        "thumb": "http://statics.myplas.com/myapp/img/female.jpg",
        "thumbqq": "",
        "c_name": "东莞市嘉上实业有限公司",
        "need_product": "5502 5621D 50100",
        "month_consum": "100吨",
        "main_product": "采购 PP PE",
        "type": "1",
        "fans": "2",
        "gender": "1",
        "buy_count": "1",
        "sale_count": "0"
        },
        {
        "user_id": "56086",
        "name": "张骞",
        "c_id": "52611",
        "sex": "男",
        "member_level": "列兵",
        "is_pass": "0",
        "thumb": "http://statics.myplas.com/myapp/img/male.jpg",
        "thumbqq": "",
        "c_name": "台州市宜人塑料制品有限公司",
        "need_product": "7726",
        "month_consum": "120吨",
        "main_product": "采购 PP",
        "type": "1",
        "fans": "4",
        "gender": "0",
        "buy_count": "0",
        "sale_count": "0"
        }
        ],
        "member": 16850,
        "is_show_banner": 1,
        "is_show_focus": 0,
        "is_show_cover": 0,
        "banner_url": "http://statics.myplas.com/myapp/img/toShop.jpg",
        "banner_jump_url": "http://q.myplas.com/#/pointsrule2",
        "cover_url": "",
        "cover_jump_url": "",
        "data": 1494293886,
        "show_ctype": 0,
        "top": {
        "member_level": "列兵",
        "user_id": "57194",
        "name": "王成",
        "c_id": "53695",
        "is_pass": "0",
        "mobile": "13805493986",
        "sex": "男",
        "thumb": "http://statics.myplas.com/upload/17/05/04/590b0a6cd4b0c.jpg",
        "thumbqq": "http://statics.myplas.com/upload/17/05/04/590b0a6cd4b0c.jpg",
        "thumbcard": "http://statics.myplas.com/upload/17/05/04/590b0a956262d.jpg",
        "c_name": "临沂成宇塑料包装有限公司",
        "need_product": "真空袋，复合彩印袋",
        "address": "山东临沂市束河南街天瑞国际大厦",
        "main_product": "塑料包装袋",
        "month_consum": "0.00",
        "type": "1",
        "buy_count": 0,
        "sale_count": "15",
        "fans": "3"
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
        $cache = cache::startMemcache ();
        $data  = array();

        if (empty($keywords)) {
            if ($page < 4) {//前三页
                if ($user_id > 0) {

                    if (!$data['data'] = $cache->get ('qgetPlasticPerson'.$sortField.$sortOrder.$page.':'.$size.':'.$region.':'.$c_type)) {
                        if(empty($c_type))
                        {

                            $data = M ('qapp:plasticPerson')->getAllPlasticPerson ($user_id, $keywords, $page, $size, $region);

                        }elseif($c_type==1)
                        {
                            $data = M ('qapp:plasticPerson')->get1PlasticPerson ($user_id, $keywords, $page, $size, $region);
                        }elseif($c_type==2)
                        {
                            $data = M ('qapp:plasticPerson')->get2PlasticPerson ($user_id, $keywords, $page, $size, $region);
                        }else {
                            $data = M ('qapp:plasticPerson')->getPlasticPerson ($user_id, $letter, $keywords, $page, $size, $sortField, $sortOrder, $region, $c_type);
                        }
                        $cache->set ('qgetPlasticPerson'.$sortField.$sortOrder.$page.':'.$size.':'.$region, $data['data'], 60);//1分钟缓存
                    }
                } else {
                    if (!$data['data'] = $cache->get ('qgetPlasticPerson0_'.$sortField.$sortOrder.$page.':'.$size.':'.$region.':'.$c_type)) {
                        if(empty($c_type))
                        {
                            $data = M ('qapp:plasticPerson')->getAllPlasticPerson ($user_id, $keywords, $page, $size, $region);
                        }elseif($c_type==1)
                        {
                            $data = M ('qapp:plasticPerson')->get1PlasticPerson ($user_id, $keywords, $page, $size, $region);
                        }elseif($c_type==2)
                        {
                            $data = M ('qapp:plasticPerson')->get2PlasticPerson ($user_id, $keywords, $page, $size, $region);
                        }else {
                            $data = M ('qapp:plasticPerson')->getPlasticPerson ($user_id, $letter, $keywords, $page, $size, $sortField, $sortOrder, $region, $c_type);
                        }
                        $cache->set ('qgetPlasticPerson0_'.$sortField.$sortOrder.$page.":".$size.":".$region, $data['data'], 60);//1分钟缓存
                    }
                }
            } else {
                if(empty($c_type))
                {
                    $data = M ('qapp:plasticPerson')->getAllPlasticPerson ($user_id, $keywords, $page, $size, $region);
                }elseif($c_type==1)
                {
                    $data = M ('qapp:plasticPerson')->get1PlasticPerson ($user_id, $keywords, $page, $size, $region);
                }elseif($c_type==2)
                {
                    $data = M ('qapp:plasticPerson')->get2PlasticPerson ($user_id, $keywords, $page, $size, $region);
                }else {
                    $data = M ('qapp:plasticPerson')->getPlasticPerson ($user_id, $letter, $keywords, $page, $size, $sortField, $sortOrder, $region, $c_type);
                }
            }
        } else {
            if(empty($c_type))
            {
                $data = M ('qapp:plasticPerson')->getAllPlasticPerson ($user_id, $keywords, $page, $size, $region);
            }elseif($c_type==1)
            {
                $data = M ('qapp:plasticPerson')->get1PlasticPerson ($user_id, $keywords, $page, $size, $region);
            }elseif($c_type==2)
            {
                $data = M ('qapp:plasticPerson')->get2PlasticPerson ($user_id, $keywords, $page, $size, $region);
            }else {
                $data = M ('qapp:plasticPerson')->getPlasticPerson ($user_id, $letter, $keywords, $page, $size, $sortField, $sortOrder, $region, $c_type);
            }
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
                'show_ctype'       => $c_type
            );
            if (!empty($top)) {
                $arr['top'] = $top;
            }
            //是否显示banner
            //M ("system:setting")->del_cache ("setting");
            $setting = M ("system:setting")->getSetting ();
            //var_dump($setting['qapp_banner']);

            if (!empty($setting['qapp_banner']) && !empty($setting['qapp_banner']['start_time']) && !empty($setting['qapp_banner']['end_time']) && !empty($setting['qapp_banner']['url']) && CORE_TIME > $setting['qapp_banner']['start_time'] && CORE_TIME < $setting['qapp_banner']['end_time']) {
                $arr['is_show_banner']  = 1;
                $arr['is_show_focus']   = 0;
                $arr['banner_url']      = $setting['qapp_banner']['url'];
                $arr['banner_jump_url'] = $setting['qapp_banner']['jump_url'];

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
     * @api {get} /qapi_3/friend/getTaPur  获取ta的求购或供给
     * @apiVersion 3.1.0
     * @apiName  getTaPur
     * @apiGroup friend
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
     * @api {get} /qapi_3/friend/focusOrCancel    关注或取消关注
     * @apiVersion 3.1.0
     * @apiName  focusOrCancel
     * @apiGroup friend
     *
     * @apiParam   {String} token  token qwre3123123121swqsq
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
     * @api {get} /qapi_3/friend/sendZoneContactMsg   塑料圈联系人的-发送消息
     * @apiVersion 3.1.0
     * @apiName  sendZoneContactMsg
     * @apiGroup friend
     *
     * @apiParam   {String} token  token qwre3123123121swqsq
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

    //查看塑料圈好友资料
    public function getZoneFriend ()
    {
        $this->is_ajax = true;
        if ($_POST) {
            $user_id = $this->checkAccount ();
            $userid  = sget ('userid', 'i');//当前联系人的id
            if ($user_id != $userid) {
                $_tmp = M ("qapp:infoList")->where ("user_id= $user_id and other_id = $userid")
                                           ->order ("info_list_id desc")->getRow ();
                if (!$_tmp) {
                    $this->_errCode (99);
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