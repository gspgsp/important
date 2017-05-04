<?php

/**
 * 塑料圈的api中心
 * create by zp 2016/10/26
 * 备注：为了应成哥的要求，现在这套接口已不复通用性了
 * 现在所有的chanel全部写死变成6  同时quan_type 变为1 才能标示数据是从塑料圈app来的
 * 还有就是我感觉注册的时候，还是写一个交易员比较的好，要不然，等到分配交易员，不知道要到什么时候
 * app 不能重新注册，一个号码注册成功后，不能重新注册
 *
 * edit by zp 2016/12/8
 * 现在的这套接口，微信也在用，因为之前我没有想到微信也要用，
 * 但是不妨事，接口应该是能通用的
 *
 *
 *
 * 现在为了给算法创造数据
 * 在pub  saveSelfInfo   focusOrCancel
 *
 *把 getMyFuns
 * getMyIntroduction
 *
 * 这样的列表型页面，电话号码隐藏
 *
 *
 * 2017年2月21日15:04:03
 * 更新了头条的推荐接口
 *
 * 2017年2月22日 11:39:38
 * 修改了报价推荐的返回，现在不补最新的报价了
 *
 *
 * 2017年3月1日09:44:03
 *
 * ps:问题是 聪明反被聪明误
 *
 *
 * 以后写版本一定要有问题，还没想好怎么做
 *
 *
 *
 * 2017-4-4 17:59:55 *
 * 现在的注册和完善信息放在了一起了，
 * 我偷个小懒，我把原来两个的接口合并在了一起了
 *
 *
 */
class qapi1_2Action extends null2Action
{
    protected $db, $err, $cates, $catesAll, $pointsType, $orderStatus, $rePoints, $points, $newsSubscribe, $newsSubscribeDefault, $cache, $randomTime, $randomMdTime, $shareType;

    public function __init ()
    {
        $this->db    = M ('public:common');
        $this->cache = E ('RedisCluster', APP_LIB.'class');
        $this->cates = array(
            '21' => '期货资讯',
            '20' => '美金市场',
            '2'  => '塑料上游',
            '11' => '装置动态',
            '1'  => '早盘预测',
            '9'  => '企业动态',
            '4'  => '中晨塑说',
            '12' => '期刊报告',
            '22' => '独家解读',
        );
        if (!$this->catesAll = unserialize ($this->cache->get ('qappInitCatesAll'))) {
            $data           = M ("public:common")->model ("news_cate")->select ("cate_id,cate_name")->where ("status=1")
                                                 ->getAll ();
            $this->catesAll = arrayKeyValues ($data, 'cate_id', 'cate_name');
            $this->cache->set ('qappInitCatesAll', serialize ($this->catesAll), 3600);
        }

        $this->pointsType  = array(
            1  => '签到',
            2  => '登陆',
            3  => '发布报价',
            4  => '订单取消塑豆返还',
            5  => '兑换礼品',
            6  => '发布采购',
            7  => '注册完善信息送',
            8  => 'app注册',
            9  => '资源库发布',
            10 => '资源库搜索',
            11 => '塑料圈',
            12 => '塑料圈引荐',
            13 => '塑料圈分享',
            14 => '查看通讯录',
            15 => '查看文章',
            16 =>'现金充值',
        );
        $this->shareType   = array(
            1 => '求购分享',
            2 => '供给分享',
            3 => '文章分享',
            4 => '引荐分享',
        );
        $this->orderStatus = array(
            1 => '已兑换，待确认',
            2 => '已确认，待发货',
            3 => '已发货',
            4 => '订单取消',
            5 => '订单完成',
        );
        $this->points      = M ('system:setting')->get ('points')['points']; //这个是加了缓存的
        /**
         *       Array
         * (
         * [login] => 10
         * [pur] => 20
         * [sale] => 10
         * [ref] => 50
         * [share] => 30
         * )
         */
        $this->rePoints = $this->points['ref'];

        $this->newsSubscribe = 6;

        $this->newsSubscribeDefault = array(
            '21',
            '20',
            '2',
            '11',
        );

        $this->randomTime   = mt_rand (10, 20) * 180; // 1-2 h
        $this->randomMdTime = mt_rand (40, 60) * 120; // 4-6 h
    }

    /**
     * @api {get} /api/api1_2/init 初始化页面
     * @apiName  init
     * @apiGroup User
     *
     * @apiSuccessExample Success-Response:
     *     <div style="margin-top:80px;text-align:center;font-size:50px">This is plasticZone app API center</div>
     *
     */
    public function init ()
    {
        echo '<div style="margin-top:80px;text-align:center;font-size:50px">This is plasticZone app API center</div>';
    }



    //    /**
    //     * 验证密码
    //     */
    //    protected function _chkpass($pass, $repass)
    //    {
    //        if (strlen($pass) < 6) {
    //            $this->err = '密码格式不正确';
    //            return false;
    //        }
    //        if ($pass != $repass) {
    //            $this->err = '两次密码不一致';
    //            return false;
    //        }
    //        return true;
    //    }





    //我的塑料圈
    public function myZone ()
    {
        $this->is_ajax = true;
        if ($_POST['token']) {
            $user_id = $this->checkAccount ();
            //我的求购
            $s_in_count = M ('qapp:plasticPersonalInfo')->getConut ($user_id, 1, 6);//purchase表
            $s_in_count = empty($s_in_count) ? 0 : $s_in_count;
            //我的报价
            $s_out_count = M ('qapp:plasticPersonalInfo')->getConut ($user_id, 2, 6);//purchase表
            $s_out_count = empty($s_out_count) ? 0 : $s_out_count;
            //我的积分
            $points = M ('qapp:pointsBill')->getUerPoints ($user_id);
            $points = empty($points) ? 0 : $points;
            //我的留言
            $leaveword = M ('qapp:plasticMsgCount')->getMsgCount ($user_id, 1);
            if (!$user_id > 0 || !$leaveword > 0) {
                $leaveword = 0;
            }
            //我的未读消息
            $message = M ('qapp:plasticMsgCount')->getRobotCount ($user_id);
            if (!$user_id > 0 || !$message > 0) {
                $message = 0;
            }
            //我的引荐
            $data         = M ('qapp:plasticIntroduction')->getqAppMyIntroduction ($user_id);
            $introduction = empty($data['count']) ? 0 : $data['count'];
            //我的粉丝
            $data   = M ('qapp:plasticMsgCount')->getMyFunsCount ($user_id, 1);
            $myfans = empty($data) ? 0 : $data;
            //我的关注
            $data       = M ('qapp:plasticMsgCount')->getMyFunsCount ($user_id, 2);
            $myconcerns = empty($data) ? 0 : $data;
            //获取我的塑料圈个人信息
            $headimgurl = '';
            $data       = M ('qapp:plasticPersonalInfo')->getMyPlastic ($user_id, $headimgurl);
            //            var_dump($user_id);
            //            var_dump($data);exit;
            if (empty($data)) {
                $this->json_output (array(
                    'err' => 2,
                    'msg' => '没有相关资料',
                ));
            }
            $data = empty($data) ? array(
                'err' => 2,
                'msg' => '没有相关资料',
            ) : $data;
            $this->json_output (array(
                's_in_count'   => $s_in_count,
                //我的求购
                's_out_count'  => $s_out_count,
                //我的供给
                'points'       => $points,
                //我的积分
                'leaveword'    => $leaveword,
                //我的留言
                'message'      => $message,
                //我的未读消息
                'introduction' => $introduction,
                //我的引荐
                'myfans'       => $myfans,
                //我的粉丝
                'myconcerns'   => $myconcerns,
                //我的关注
                'data'         => $data,
                //我的个人信息
            ));
        }
        $this->_errCode (6);
    }


    //获取我的引荐(引荐数)
    public function getMyIntroduction ()
    {
        $this->is_ajax = true;
        if ($_POST['token']) {
            $user_id = $this->checkAccount ();
            $page    = sget ('page', 'i', 1);
            $size    = sget ('size', 'i', 10);
            $data    = M ('qapp:plasticIntroduction')->getqAppMyIntroduction ($user_id, $page, $size);
            if (empty($data['data']) && $page == 1) {
                $this->json_output (array(
                    'err'   => 2,
                    'msg'   => '没有相关的数据',
                    'count' => 0,
                ));
            }
            $this->_checkLastPage ($data['count'], $size, $page);
            //unset($data['data'][2]);
            //            echo '<pre>';
            //            var_dump($data);exit;
            $this->json_output (array(
                'err'   => 0,
                'data'  => $data['data'],
                'count' => $data['count'],
            ));
        }
        $this->_errCode (6);
    }


    /**
     * @param $serverId jssdk文件上传返回的serverId
     *
     * @return string
     */
    public function savePicToServer ()
    {
        $this->is_ajax = true;
        $user_id       = $this->checkAccount ();
        //$this->json_output(array('err'=>1,'msg'=>'test'));
        A ('public:upload')->saveqAppCardImg ('', 2, $user_id);
    }

    //保存名片到服务器
    public function saveCardImg ()
    {
        $this->is_ajax = true; //指定为Ajax输出
        //$this->json_output(array('err'=>1,'msg'=>'test'));
        $user_id = $this->checkAccount ();
        $result  = A ('public:upload')->saveqAppCardImg ('', 1, $user_id);
    }



    //获取系统消息robot
    public function getRobotMsg ()
    {
        $this->is_ajax = true;
        if ($_POST) {
            $user_id = $this->checkAccount ();
            $page    = sget ('page', 'i', 1);
            $size    = sget ('size', 'i', 10);
            $data    = M ('qapp:robotMsg')->getRobotMsg ($user_id, $page, $size);
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

    //获取我的粉丝和我的关注(数)
    public function getMyFuns ()
    {
        $this->is_ajax = true;
        if ($_POST) {
            $user_id = $this->checkAccount ();
            $type    = sget ('type', 'i');//1粉丝2关注
            $page    = sget ('page', 'i', 1);
            $size    = sget ('size', 'i', 10);
            $data    = M ('qapp:plasticIntroduction')->getMyFuns ($user_id, $type, $page, $size);
            if (empty($data['data']) && $page == 1) {
                $this->json_output (array(
                    'err'   => 2,
                    'msg'   => '没有相关的数据',
                    'count' => 0,
                ));
            }
            $this->_checkLastPage ($data['count'], $size, $page);
            $this->json_output (array(
                'err'   => 0,
                'data'  => $data['data'],
                'count' => $data['count'],
            ));
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

    //偏好设置-发送短信
    public function favorateSet ()
    {
        $this->is_ajax = true;
        if ($_POST) {
            $user_id  = $this->checkAccount ();
            $type     = sget ('type', 'i');//0 关注 1 回复 2是否公开电话
            $is_allow = sget ('is_allow', 'i', 0);//0:允许 1:不允许
            $result   = M ('qapp:plasticAttention')->favorateSet ($user_id, $type, $is_allow);
            $this->json_output ($result);
        }
        $this->_errCode (6);
    }


    //获取我的塑料圈个人信息
    public function getMyPlastic ()
    {
        $cache         = cache::startMemcache ();
        $this->is_ajax = true;
        if ($_POST) {
            $user_id    = $this->checkAccount ();
            $headimgurl = sget ('headimgurl', 's');
            $data       = M ('qapp:plasticPersonalInfo')->getMyPlastic ($user_id, $headimgurl);
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


    //查看我的资料
    public function getSelfInfo ()
    {
        $this->is_ajax = true;
        if ($_POST) {
            $user_id = $this->checkAccount ();
            $data    = M ('qapp:plasticPersonalInfo')->getSelfInfo ($user_id);
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

    //保存我的资料
    public function saveSelfInfo ()
    {
        $this->is_ajax = true;

        if ($_POST) {
            $user_id              = $this->checkAccount ();
            $_tmpAddress          = sget ('address', 's');
            $_tmpSex              = sget ('sex', 's');
            $_tmpMajor            = sget ('major', 's');
            $_tmpConcern          = sget ('concern', 's');
            $_tmpDist             = sget ('dist', 's', 'EC');
            $_tmpType             = sget ('type', 's');
            $_tmpMonthConsum      = sget ('month_consum', 's');
            $_tmpMainProduct      = sget ('main_product', 's');
            $data['address']      = $_tmpAddress;
            $data['sex']          = $_tmpSex;
            $data['major']        = $_tmpMajor;
            $data['concern']      = $_tmpConcern;
            $data['dist']         = $_tmpDist;
            $data['month_consum'] = $_tmpMonthConsum;
            $data['type']         = $_tmpType;
            $data['main_product'] = $_tmpMainProduct;
            foreach ($data as $key => &$row) {
                if ($key == 'address') {
                    $row = $this->clearStr ($row);
                } elseif ($key == 'sex') {
                    if (!in_array ($row, array(
                        '1',
                        '0',
                    ))
                    ) {
                        $this->_errCode (6);
                    }
                    $row = (int)$row;
                } elseif ($key == 'major') {
                    $field = $this->clearStr ($row);
                    if (!is_string ($row)) {
                        $this->json_output (array(
                            'err' => 1,
                            'msg' => '格式错误',
                        ));
                    }
                    if (!empty($field)) {
                        $field = explode (",", $field);
                        $field = array_map ('strtoupper', $field);
                        foreach ($field as $key1 => $row1) {
                            if (empty($row1)) {
                                unset($field[$key1]);
                            }
                        }
                        $field = array_unique ($field);
                        //$field=explode(",",array_map('strtoupper',$field));
                        if (count ($field) > 10) {
                            $this->json_output (array(
                                'err' => 6,
                                'msg' => '牌号个数不能超过十个',
                            ));
                        }
                        $row = $field;
                    } else {
                        unset($data[$key]);
                    }
                } elseif ($key == 'concern') {
                    $row   = $this->clearStr ($row);
                    $field = preg_replace ("/(\n)|(\s)|(\t)|(\')|(')|(，)|( )|(\.)/", ',', $row);
                    //$field=explode(",",array_map('strtoupper',$field));
                    if (!is_string ($row)) {
                        $this->json_output (array(
                            'err' => 1,
                            'msg' => '格式错误',
                        ));
                    }
                    if (!empty($field)) {
                        $field = explode (",", $field);
                        $field = array_map ('strtoupper', $field);
                        foreach ($field as $key1 => $row1) {
                            if (empty($row1)) {
                                unset($field[$key1]);
                            }
                        }
                        $field = array_unique ($field);
                        //$field=explode(",",array_map('strtoupper',$field));
                        if (count ($field) > 10) {
                            $this->json_output (array(
                                'err' => 6,
                                'msg' => '牌号个数不能超过十个',
                            ));
                        }
                        $row = $field;
                    } else {
                        unset($data[$key]);
                    }
                } elseif ($key == 'dist') {
                    if (empty($row) || (!in_array ($row, array(
                            'EC',
                            'NC',
                            'SC',
                            'OT',
                        )))
                    ) {
                        $this->_errCode (6);
                    }
                } elseif ($key == 'type') {
                    if (empty($row) || (!in_array ($row, array(
                            '1',
                            '2',
                            '3',
                            '4',
                        )))
                    ) {
                        $this->_errCode (6);
                    }
                } elseif ($key == 'month_consum') {
                    $row = $this->clearStr ($row);
                    if (mb_strlen ($row) > 15) {
                        $this->json_output (array(
                            'err' => 1,
                            'msg' => '1字符过长',
                        ));
                    }
                } elseif ($key == 'main_product') {
                    $row = $this->clearStr ($row);
                    if (mb_strlen ($row) > 25) {
                        $this->json_output (array(
                            'err' => 1,
                            'msg' => '2字符过长',
                        ));
                    }
                }
            }
            $result = M ('qapp:plasticSave')->saveSelfInfo1_2 ($user_id, $data);
            if ($result['err'] > 0) {
                $this->json_output (array(
                    'err' => 2,
                    'msg' => $result['msg'],
                ));
            }
            $this->json_output (array(
                'err' => 0,
                'msg' => '保存资料成功',
            ));
        }
        $this->_errCode (6);
    }


    //登录时计算会员等级
    public function getMemberLevel ()
    {
        $this->is_ajax = true;
        $user_id       = $this->checkAccount ();
        M ('plasticzone:plasticPersonalInfo')->getMemberLevel ($user_id);
        $this->success ('会员等级更新成功');
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

    //塑料圈联系人的-我的消息（yuepao）
    public function getZoneContactMsg ()
    {
        $this->is_ajax = true;
        if ($_POST) {
            $user_id = $this->checkAccount ();
            $page    = sget ('page', 'i', 1);
            $size    = sget ('size', 'i', 10);
            $type    = sget ('type', 'i', 1);//1:我接受的 2:我发送的
            $data    = M ('qapp:plasticMyMsg')->getZoneContactMsg ($user_id, $type, $page, $size);
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



    /*
     * 二次发布
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

    //关注。粉丝的头像
    public function headPicture ()
    {
        if ($_POST) {
            $user_id = $this->checkAccount ();
            //我的粉丝
            $data   = M ('plasticzone:plasticIntroduction')->getMyFuns ($user_id, 1);
            $myfans = array();
            foreach ($data['data'] as $row) {
                $myfans[] = $row['user_id']['thumb'];
                if (count ($myfans) >= 9) {
                    break;
                }
            }
            //我的关注
            $data       = M ('plasticzone:plasticIntroduction')->getMyFuns ($user_id, 2);
            $myconcerns = array();
            foreach ($data['data'] as $row) {
                $myconcerns[] = $row['focused_id']['thumb'];
                if (count ($myconcerns) >= 9) {
                    break;
                }
            }
            $this->json_output (array(
                'err'        => 0,
                'myfans'     => $myfans,
                'myconcerns' => $myconcerns,
            ));
        }
        $this->_errCode (6);
    }

    /**
     * 获取证书
     */
    public function creditCertificate ()
    {
        $user_id = $this->checkAccount (0);
        $link_id = sget ('link_id', 'i');
        $fname   = sget ('fname', 's');
        $type    = sget ('type', 's');  //   1 精确  2 模糊
        $page    = sget ('page', 'i');
        //模糊查询没有了
        if (!empty($fname) && empty($link_id)) { //获取别人
            if (empty($type)) {
                $this->_errCode (6);
            }
            if ($type == 2 && empty($page)) {
                $this->_errCode (6);
            } //模糊查询没有page，报错
            $data = M ("qapp:plasticPersonalInfo")->getCompanyCredit ($fname, $type, $page);
            if (empty($data)) {
                $this->json_output (array(
                    'err' => 2,
                    'msg' => '没有此公司或此公司尚未被授信！',
                ));
            }
            $this->json_output (array(
                'err'  => 0,
                'data' => $data,
            ));
        }
        if (empty($link_id) || $link_id == 'undefined') {
            if ($user_id <= 0) {
                $this->_errCode (6);
            }//找不到用户
        } else {
            $user_id = $link_id;//获取微信用户分享人的id
        }
        //获取自己的
        $data = M ("qapp:plasticPersonalInfo")->getMyCredit ($user_id);
        if (empty($data)) {
            $this->json_output (array(
                'err' => 2,
                'msg' => '没有此公司或此公司尚未被授信！',
            ));
        }
        $this->json_output (array(
            'err'  => 0,
            'data' => $data,
        ));
    }





    //脚本开启
    /*  public function start()
      {

          ob_implicit_flush();
          set_time_limit(0);

          for ($a = 0; $a < 32000; $a += 100) {
              $sql = "select p1.user_id,p2.mobile,p1.mobile_area from p2p_contact_info p1 join p2p_customer_contact p2 using(user_id) order by user_id desc limit $a,100";
              //echo $sql;
              echo $a;
              echo '<pre>';
              echo '<br />';
              echo '<br />';
              $rs=$this->db->getAll($sql);
              //var_dump($rs);exit;
              if (!$rs) break;
              foreach ($rs as $row) {
  //                echo '<pre>';
  //                var_dump($row);
  //                 echo '<br />';
  //                 echo '<br />';exit;
                  if (empty($row['mobile_area'])) {
                      $_info = file_get_contents("http://tcc.taobao.com/cc/json/mobile_tel_segment.htm?tel=" . $row['mobile']);
                      $_info = iconv("gbk", "UTF-8", $_info);
                      if (strstr($_info, 'carrier')) {
                          $tmp = substr($_info, strpos($_info, 'carrier') + 9, -6);
                          $sql1 = "update p2p_contact_info set mobile_area='$tmp' where user_id=" . $row['user_id'];
                          echo $sql1;
                          echo '<br />';
                          echo '<br />';
                          $this->db->query($sql1);
                          //showTrace();p(2);exit;
                      }
                      usleep(30);
                  }
              }
          }
      }
    */


    /**
     * 获取企查查的东西的接口东西
     */

    public function getQiChaCha ()
    {
        if ($_POST['token'] && $_POST['name']) {
            $user_id = $this->checkAccount ();
            $name    = sget ('name', 's');
            $name    = $this->clearStr ($name);
            if (empty($name)) {
                $this->_errCode (6);
            }
            $tmp = M ('qapp:customerBase')->selectOrNot ($name);
            $this->json_output ($tmp);
        }
        $this->_errCode (6);
    }











    public function getRegion ()
    {
        $pid  = sget ('id', 'i', 1);
        $_tmp = unserialize ($this->cache->get ('getqappRegion'.$pid));
        if (!empty($_tmp)) {
            $this->json_output (array(
                'err'  => 0,
                'data' => $_tmp,
            ));
        }
        $_tmp    = M ('system:region')->select ('id,pid,name')->where ('pid='.$pid)->getAll ();
        $_tmpRow = '';
        foreach ($_tmp as $key => &$row) {
            if ($row['name'] == '上海' && $row['pid'] == 1) {
                unset($row['pid']);
                $_tmpRow = $row;
                unset($_tmp[$key]);
            }
            unset($row['pid']);
        }
        if (!empty($_tmpRow)) {
            array_unshift ($_tmp, $_tmpRow);
        }
        $this->cache->set ('getqappRegion'.$pid, serialize ($_tmp), $this->randomMdTime);
        $this->json_output (array(
            'err'  => 0,
            'data' => $_tmp,
        ));
    }

    public function getModel ()
    {
        $keywords = sget ('keywords', 's');
        $keywords = strtoupper ($keywords);
        if (empty($keywords)) {
            $this->_errCode (6);
        }
        $_tmpModel = M ('qapp:product')->select ('model')->where ('status=1 and model like "%'.$keywords.'%"')
                                       ->limit (20)->getAll ();
        if (empty($_tmpModel)) {
            $this->_errCode (2);
        }
        $this->json_output (array(
            'err'  => 0,
            'data' => $_tmpModel,
        ));
    }

    /**
     * APP检查更新接口
     * @api {get} /api/api1_2/checkVersion APP检查更新接口
     * @apiName  checkVersion
     * @apiGroup User
     *
     * @apiParam   {String} version  3.0.0
     * @apiParam   {String} platform  ios andriod h5
     *
     * @apiSuccess {String}  msg   描述
     * @apiSuccess {String}  err   错误码
     * @apiSuccess {String}  url   apk下载地址
     *
     * @apiSuccessExample Success-Response:
     *      {
     *      "err":0
     *      "msg":"密码重置成功"
     *      }
     */
    public function checkVersion ()
    {
        $version  = sget ('version', 's');
        $platform = sget ('platform', 's');
        if (!in_array ($platform, array(
                'ios',
                'android',
                'h5',
            )) || empty($version)
        ) {
            $this->json_output (array(
                'err' => 4,
                'msg' => '参数错误',
            ));
        }
        $version = explode ('.', $version);
        $version = array_splice ($version, 0, 3);
        if (count ($version) != 3) {
            $this->json_output (array(
                'err' => 2,
                'msg' => '不规范的版本格式，不予支持',
            ));
        }
        $settings        = M ('system:setting')->getSetting ();
        $newest_version0 = $settings['qapp_newest_version'];
        $newest_qapp_url = $settings['qapp_newest_url'];

        if (empty($newest_version0) || empty($newest_qapp_url)) {
            $this->json_output (array(
                'err' => 3,
                'msg' => '系统错误',
            ));
        }
        $newest_version = explode ('.', $newest_version0);

        if ($version[0] < $newest_version[0]) {
            $this->json_output (array(
                'err'         => 1,
                'msg'         => '当前版本已经停止支持，请迅速更新',
                'new_version' => $newest_version0,
                'url'         => FILE_URL.$newest_qapp_url[$platform],
            ));
        } elseif ($version[1] < $newest_version[1]) {
            $this->json_output (array(
                'err'         => 1,
                'msg'         => '当前版本已经停止支持，请迅速更新',
                'new_version' => $newest_version0,
                'url'         => FILE_URL.$newest_qapp_url[$platform],
            ));
        } elseif ($version[2] < $newest_version[2]) {

            $this->json_output (array(
                'err'         => 1,
                'msg'         => '当前版本已经停止支持，请迅速更新',
                'new_version' => $newest_version0,
                'url'         => FILE_URL.$newest_qapp_url[$platform],
            ));
        } else {
            $this->json_output (array(
                'err' => 0,
                'msg' => '当前版本是最新版本，棒棒哒',
            ));
        }

    }

    /* public function test()
     {
         $id= $_GET['id'];
         $contacts = $this->db->model('customer_contact')->where("c_id=" . $id)->getAll();
         var_dump($contacts);

         foreach ($contacts as $contact) {
             if(!empty($contact['mobile'])) {
                 $region = M("system:region")->get_system_region_by_phone($contact['mobile']);

                 if (!empty($region)) {
                     $this->db->model('customer')->where("c_id=" . $id)->update(array('china_area' => $region));
                     break;
                 }
             }
             if(!empty($contact['tel'])) {
                 $region = M("system:region")->get_system_region_by_phone($contact['tel']);
                 if (!empty($region)) {
                     $this->db->model('customer')->where("c_id=" . $id)->update(array('china_area' => $region));
                     echo 111;
                     var_dump($this->db->getLastSql());
                     break;
                 }
             }
         }
     }*/
    //    public function json_output ($result = array())
    //    {
    //        //header('Content-Type:text/html; charset=utf-8');
    //        header ('Content-type: application/json; charset=utf-8');
    //        $result       = json_encode ($result);
    //        $jsoncallback = sget ('jsoncallback');
    //        if (!empty($jsoncallback)) {
    //            $result = $jsoncallback."($result)";
    //        }
    //        echo $result;
    //        if ($this->debug || isset($_GET[C ('SHOW_DEBUG')])) {
    //            log::showTrace ();
    //        }
    //        die();
    //    }


}