<?php
/**
 * Created by PhpStorm.
 * User: sick
 * Date: 17-5-3
 * Time: 上午10:33
 */
class friendAction extends baseAction
{
    //获取首页数据
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


    //获取ta的求购或供给
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

    //关注或取消关注
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
    //塑料圈联系人的-发送消息
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