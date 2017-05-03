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


    //http-->curl
    protected function http ($url)
    {
        $ch = curl_init ($url);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt ($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt ($ch, CURLOPT_HEADER, false);
        $output = curl_exec ($ch);//输出内容
        curl_close ($ch);

        return $output;
    }


    //通过回调方法获取用户的code
    protected function get_authorize_url ($redirect_uri = '', $state = '')
    {
        $redirect_uri = urlencode ($redirect_uri);
        $url          = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->AppID}&redirect_uri={$redirect_uri}&response_type=code&scope=snsapi_base&state={$state}#wechat_redirect";
        echo "<script language='javascript' type='text/javascript'>";
        echo "window.location.href='$url'";
        echo "</script>";
    }

    //curl 获取文件数据
    public function curl_file ($url)
    {
        $ch = curl_init ($url);
        curl_setopt ($ch, CURLOPT_HEADER, 0);
        curl_setopt ($ch, CURLOPT_NOBODY, 0);//只取body头
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);//curl_exec执行成功后返回执行的结果；不设置的话，curl_exec执行成功则返回true
        $output = curl_exec ($ch);
        curl_close ($ch);

        return $output;
    }



    //判断账户
    /**
     * @param int $type 0 qushouyeshuju  1 biede
     *
     * @return int|string
     */
    public function checkAccount ($type = 1)
    {
        $this->is_ajax = true;
        $token         = sget ('token', 's');
        if (empty($token)) {
            $user_id = 0;
        } else {
            $user_id = M ('qapp:appToken')->deUserId ($token);
            if (is_array ($user_id)) {
                $user_id = 0;
            }
            //$this->json_output($user_id);
        }
        if (empty($type)) {
            if ($user_id < 0) {
                $this->json_output (array(
                    'err' => 1,
                    'msg' => '账号错误',
                ));
            }
        } else {
            if ($user_id <= 0) {
                $this->json_output (array(
                    'err' => 1,
                    'msg' => '您未登录塑料圈,无法查看企业及个人信息',
                ));
            }

            $_tmpRow = M ("public:common")->select ("cus.status,con.status as con_status")->from ("customer cus")
                                          ->leftjoin ("customer_contact con", 'con.c_id=cus.c_id')
                                          ->where ("con.user_id=$user_id")->getRow ();
            if (in_array ($_tmpRow['status'], array(
                    3,
                    4,
                )) || in_array ($_tmpRow['con_status'], array(
                    2,
                    3,
                    4,
                ))
            ) {
                $this->json_output (array(
                    'err' => 1,
                    'msg' => '您账号已冻结，如有疑问请咨询客服：4006129965',
                ));
            }
        }

        return $user_id;
    }

    //判断是否显示电话号码的
    //true默认是显示的
    //false 是不显示的
    public function checkPhoneShow ($user_id = 0)
    {
        $data = M ('qapp:plasticPersonalInfo')->getSelfInfo ($user_id);
        if (isset($data['allow_send']) && isset($data['allow_send']['show'])) {
            if ($data['allow_send']['show'] === 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    //判断头像的显示
    /**
     * @param $thumb
     * $thumb=$this->model('contact_info')->select('thumb,thumbqq,mobile_province')->where('user_id='.$value['user_id'])->getRow();
     */
    public function checkPicture ($thumb = array())
    {
        if (empty($thumb['thumbqq'])) {
            if (strstr ($thumb['thumb'], 'http')) {
                $thumb['thumb'] = $thumb['thumb'];
            } else {
                if (empty($thumb['thumb'])) {
                    $thumb['thumb'] = "http://statics.myplas.com/upload/16/09/02/logos.jpg";
                } else {
                    $thumb['thumb'] = FILE_URL."/upload/".$thumb['thumb'];
                }
            }
        } else {
            $thumb['thumb'] = $thumb['thumbqq'];
        }

        return $thumb;
    }

    //判断是否到最后一页
    protected function _checkLastPage ($count, $size, $page)
    {
        if ($count > 0) {
            if ($count % $size == 0 && ceil ($count / $size) < $page) {
                $this->json_output (array(
                    'err' => 3,
                    'msg' => '没有更多数据',
                ));
            } elseif ($count % $size != 0 && ceil ($count / $size) < $page) {
                $this->json_output (array(
                    'err' => 3,
                    'msg' => '没有更多数据',
                ));
            }
        }
    }

    //判断积分是否足够
    protected function checkSupply ($num = null, $outType = 0)
    {
        $this->is_ajax = true;
        $user_id       = $this->checkAccount ();
        if ($outType == 0) {
            $goods_id  = sget ('goods_id', 'i');
            $pointsRow = M ('public:common')->from ("points_goods")->select ("points,name,cate_id")
                                            ->where ("status = 1 and receive_num < num and id = $goods_id")->getRow ();
            $num       = (int)$pointsRow['points'];
            if ($pointsRow['cate_id'] == 7) {
                $this->json_output (array(
                    'err' => 119,
                    'msg' => '活动时间已过，请关注我们《塑料圈》公众号，参与更多活动',
                ));
            }
            $user = M ('public:common')->model ('contact_info');
            if ($info = $user->where ("user_id=$user_id")->getRow ()) {
                if (($info['quan_points'] - $num) < 0) {
                    $this->json_output (array(
                        'err' => 100,
                        'msg' => '塑豆不足,请多努力!',
                    ));
                }
                $this->json_output (array(
                    'err' => 0,
                    'msg' => '塑豆足够兑换',
                ));
            }
        } elseif ($outType == 1) {
            $num  = (int)$num;
            $user = M ('public:common')->model ('contact_info');
            if ($info = $user->where ("user_id=$user_id")->getRow ()) {
                if (($info['quan_points'] - $num) < 0) {
                    return false;
                }

                return true;
            }
        }
    }


    /**
     * 验证手机号码
     * @access protected
     *
     * @param $type 0 zhuce  1 zhaohuimima/yanzhemadenglu
     *
     * @return bool
     */
    protected function _chkmobile ($value = '', $type = 0)
    {
        if (!is_mobile ($value)) {
            if (empty($value)) {
                $this->err = '手机号码不能为空';
            } else {
                $this->err = '手机号码输入有误,请重新输入';
            }

            return false;
        }
        $chk = M ('system:sysUser')->usrUnique ('mobile', $value);//非重复
        if (!$chk) {
            if (empty($type)) {
                //                $this->err = '手机号已注册,直接登录即可';
                //                return false;
                return true;
            } else {
                return true;
            }
        } else {
            if (empty($type)) {
                return true;
            } else {
                $this->err = '手机号未注册';

                return false;
            }
        }
    }

    //    /**
    //     * 检查浏览器
    //     * @return string
    //     */
    //    public function checkBrowser(){
    //        $agent=$_SERVER["HTTP_USER_AGENT"];
    //        if(strpos($agent,'MSIE')!==false || strpos($agent,'rv:11.0')) //ie11判断
    //            return "ie";
    //        else if(strpos($agent,'Firefox')!==false)
    //            return "firefox";
    //        else if(strpos($agent,'Chrome')!==false)
    //            return "chrome";
    //        else if(strpos($agent,'Opera')!==false)
    //            return 'opera';
    //        else if((strpos($agent,'Chrome')==false)&&strpos($agent,'Safari')!==false)
    //            return 'safari';
    //        else
    //            return 'unknown';
    //    }

    /**
     * 检查平台来源
     * @return json
     */
    public function checkPlatform ()
    {
        return get_platform ();
    }


    public function somes ()
    {
        $token   = sget ('token', 's');
        $_userid = M ('qapp:appToken')->deUserId ($token);
        var_dump ($_userid);
    }

    /*
     * 下面的都是塑料头条的
     */
    public function topLine ()
    {
        if ($_POST) {
            $this->is_ajax = true;
            //$this->checkAccount();
            $cache = cache::startMemcache ();
            //if (!$info = $cache->get('qtopLineInfo')) {
            $info = M ("qapp:news")->getqAppCateList ('public', 1, array(), null, 10);
            foreach ($info as $key => &$value) {
                $value['input_time'] = $this->checkTime ($value['input_time']);
                //$value['content']=stripslashes($value['content']);
                //  $str=$value['content'];
                // $value['content']=$this->cleanhtml($str,'<p>');
                $value['description'] = mb_substr (strip_tags ($value['description']), 0, 60, 'utf-8').'...';
                if ($value['type'] == 'public') {
                    $arr           = array(
                        'pe',
                        'pp',
                        'pvc',
                    );
                    $tmp           = array_rand ($arr, 1);
                    $value['type'] = $arr[$tmp];
                }
                $value['type'] = strtoupper ($value['type']);
            }
            //unset($value['content']);
            $cache->set ('qtopLineInfo', $info, 300);
            //}
            $this->json_output (array(
                'err'  => 0,
                'data' => array(
                    'topLine' => $this->cates,
                    'info'    => $info,
                ),
            ));
        }
        $this->_errCode (6);
    }

    /**
     * 塑料头条-分类列表
     */
    public function getCateList ()
    {
        if ($_POST) {
            $this->is_ajax = true;
            $page          = sget ('page', 'i', 1);
            $size          = sget ('size', 'i', 10);
            $cate_id       = sget ('cate_id', 'i');
            $this->checkAccount ();
            $cache = cache::startMemcache ();
            if ($page <= 2) {
                $data = array();
                if (!$data['data'] = $cache->get ('qcateListInfo'.$page.'_'.$cate_id)) {
                    $data = M ("qapp:news")->getqAppCateList ('public', $cate_id, array(), $page, $size);
                    if (empty($data['data']) && $page == 1) {
                        $this->json_output (array(
                            'err' => 2,
                            'msg' => '没有相关数据',
                        ));
                    }
                    $this->_checkLastPage ($data['count'], $size, $page);
                    //截取示例文章文字
                    foreach ($data['data'] as $key => &$v) {
                        //$v['content']=$this->cleanhtml(strip_tags($v['content']),'');
                        $data['data'][$key]['description'] = mb_substr (strip_tags ($v['description']), 0, 50, 'utf-8').'...';
                        //取出右键导航分类名称
                        $data['data'][$key]['cate_name']  = $this->cates[$cate_id];
                        $data['data'][$key]['input_time'] = $this->checkTime ($v['input_time']);
                        if ($v['type'] == 'public') {
                            $arr       = array(
                                'pe',
                                'pp',
                                'pvc',
                            );
                            $tmp       = array_rand ($arr, 1);
                            $v['type'] = 'pp';
                        }
                        $v['type'] = strtoupper ($v['type']);
                        //unset($v['content']);
                    }
                    $cache->set ('qcateListInfo'.$page.'_'.$cate_id, $data['data'], 300);
                }
            } else {
                $data = M ("qapp:news")->getqAppCateList ('public', $cate_id, array(), $page, $size);
                if (empty($data['data']) && $page == 1) {
                    $this->json_output (array(
                        'err' => 2,
                        'msg' => '没有相关数据',
                    ));
                }
                $this->_checkLastPage ($data['count'], $size, $page);
                //截取示例文章文字
                foreach ($data['data'] as $key => &$v) {
                    //$v['content']=$this->cleanhtml(strip_tags($v['content']),'');
                    $data['data'][$key]['description'] = mb_substr (strip_tags ($v['description']), 0, 50, 'utf-8').'...';
                    //取出右键导航分类名称
                    $data['data'][$key]['cate_name']  = $this->cates[$cate_id];
                    $data['data'][$key]['input_time'] = $this->checkTime ($v['input_time']);
                    if ($v['type'] == 'public') {
                        $arr       = array(
                            'pe',
                            'pp',
                            'pvc',
                        );
                        $tmp       = array_rand ($arr, 1);
                        $v['type'] = 'pp';
                    }
                    $v['type'] = strtoupper ($v['type']);
                    //unset($v['content']);
                }
            }
            $this->json_output (array(
                'err'  => 0,
                'info' => $data['data'],
            ));
        }
        $this->_errCode (6);
    }

    /**
     * 塑料头条-详情列表
     */
    public function getDetailInfo ()
    {
        A ("api:qapi1_1")->getDetailInfo ();
    }

    /**
     * 获取概率函数
     *
     * @param float $chance
     *
     * @return bool
     */
    public function getArrayChance ($chance = 0.7)
    {
        $chance = 10 * $chance;
        if (empty($chance)) {
            $chance = 7;
        }
        $tmp = array();
        $tmp = array_pad ($tmp, $chance, 'a');
        $tmp = array_pad ($tmp, 10, 'b');
        shuffle ($tmp);
        if ($tmp[1] == 'a') {
            return true;
        } elseif ($tmp[1] == 'b') {
            return false;
        }
    }

    /**
     * 获取订阅频道
     */
    public function getSelectCate ()
    {
        if ($_POST) {
            $this->is_ajax = true;
            $user_id       = $this->checkAccount ();
            $type          = sget ('type', 'i');// 1 set  2 get 3 getallCate
            if ($type == 1) {
                $cate_id = sget ('cate_id', 'a');
                if (empty($cate_id) || !is_array ($cate_id)) {
                    $this->_errCode (6);
                }
                if (M ("qapp:newsSubscribe")->setSubscribeByUserid ($user_id, $cate_id)) {
                    $this->json_output (array(
                        'err' => 0,
                        'msg' => '成功',
                    ));
                }
                $this->_errCode (101);
            } elseif ($type == 2) {
                $tmp = M ("qapp:newsSubscribe")->getSubscribeByUserid ($user_id);
                if (empty($tmp)) {
                    $tmp = $this->newsSubscribeDefault;
                }
                $this->json_output (array(
                    'err'  => 0,
                    'data' => $tmp,
                ));
            } elseif ($type == 3) {
                $this->json_output (array(
                    'err'  => 0,
                    'data' => $this->cates,
                ));
            }
        }
        $this->_errCode (6);
    }


    /**
     * 头条推荐
     */
    public function getSubscribe ()
    {
        A ("api:qapi1_1")->getSubscribe ();
    }


    /**
     * 判断时间和改变时间
     */
    protected function checkTime ($time)
    {
        if (date ("Y-m-d") == date ("Y-m-d", $time)) {
            return date ("H:i", $time);
        } else {
            return date ("Y-m-d", $time);
        }
    }

    /**
     * 内容过滤,过滤html标签
     */
    public function cleanhtml ($str, $tags = '<img><a>')
    {//过滤时默认保留html中的<a><img>标签
        $search = array(
            '@<script[^>]*?>.*?</script>@si',
            // Strip out javascript
            /*  '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags*/
            "'<style[^>]*?>.*?</style>'si",
            /*'@<style[^>]*?>.*?</style>@si',    // Strip style tags properly*/
            '@<![\s\S]*?--[ \t\n\r]*>@',
            // Strip multi-line comments including CDATA
        );
        $array  = array(
            '',
            '',
            '',
        );
        $str    = preg_replace ($search, $array, $str);
        $str    = str_replace (array(
            "\r\n",
            "\r",
            "\n",
            "&nbsp;",
        ), "", $str);
        $str    = strip_tags ($str, $tags);

        return $str;
    }

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

    //区域选择
    public function getDistinct ()
    {
        if ($_POST) {
            $this->is_ajax = true;
            $this->checkAccount ();
            $data = array(
                'EC' => '华东',
                'SC' => '华南',
                'NC' => '华北',
            );
            $this->json_output (array(
                'err'  => 0,
                'data' => $data,
            ));
        }
        $this->_errCode (6);
    }

    /*
     * 塑料圈app之积分商品详情页
     */
    public function getProductInfo ()
    {
        if ($_POST) {
            $this->is_ajax = true;
            $this->checkAccount ();
            $id = sget ('id', i);//商品的id
            if ($id < 1) {
                $this->json_output (array(
                    'err' => 1,
                    'msg' => 'id参数错误',
                ));
            }
            $arr    = $this->db->from ("points_goods")->select ('id,cate_id,thumb,image,name,points,type')
                               ->where ("status = 1 and receive_num < num and id = $id")->getRow ();
            $result = array();
            preg_match_all ("/(?:\（)(.*)(?:\）)/i", $arr['name'], $result);
            $str = (int)$result[1][0];
            if ($arr['image']) {
                $arr['image'] = FILE_URL.'/upload/'.$arr['image'];
            }
            if ($arr['thumb']) {
                $arr['thumb'] = FILE_URL.'/upload/'.$arr['thumb'];
            }
            //if (empty($arr['content'])) $arr['content'] = "<span>本置顶卡可使您的信息在供求信息版面置顶" . $str . "分钟</span><br />备注:<br />1.同一时间内最多一条信息置顶;";
            $this->json_output (array(
                'err'  => 0,
                'info' => $arr,
            ));
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
     * 塑料圈app之积分规定
     */
    public function pointsRule ()
    {
        $this->display ('plasticzone/points_rule.html');
        //        if ($_POST) {
        //            $this->is_ajax = true;
        //            $this->checkAccount ();
        //            $salePoints = intval (M ('system:setting')->get ('points')['points']['sale']);
        //            $purPoints  = intval (M ('system:setting')->get ('points')['points']['pur']);
        //            $rule       = '';
        //            $rule .= '<span>1. 每日发布报价/求购一条，增加'.$salePoints.'/'.$purPoints.'积分</span><br />';
        //            $rule .= '<span>2. 与我的塑料网成交后自动累计积分，买的多送的多</span><br />';
        //            $rule .= '<span>3. 积分商城积分兑换的商品不但免费还免运费</span>';
        //            $this->json_output (array( 'err' => 0, 'rule' => $rule ));
        //        }
        //        $this->_errCode (6);
    }

    /*
     * 获取兑换时间段
     * @param $type   0是通讯录置顶 1供求信息置顶
     */
    public function exchangeTime ($type = 1)
    {
        $this->is_ajax = true;
        $arr           = $this->db->model ('corn')->where ("type = $type and status_e = 0 ")
                                  ->select ('user_id,exe_time_s,exe_time_e,purchase')->getAll ();

        return $arr;
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
    public function new_exchangeSupplyOrDemand ()
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

    //生产订单号
    protected function buildOrderId ()
    {
        return date ('Ymd').substr (implode (null, array_map ('ord', str_split (substr (uniqid (), 7, 13), 1))), 0, 8);
    }


    /**
     * 积分记录
     */
    public function pointSupplyList ()
    {
        $this->is_ajax = true;
        if ($_POST) {
            $user_id = $this->checkAccount ();
            $page    = sget ('page', 'i', 1);
            $size    = sget ('size', 'i', 10);
            //$data=M("qapp:pointsBill")->select('id,addtime,type,points')->where("uid = $user_id and type in (2,3,5,6)")->order('id desc')->page($page,$size)->getPage();
            $data = M ("qapp:pointsBill")->select ('id,addtime,type,points,share_type')->where ("uid = $user_id and is_mobile =1")
                                         ->order ('id desc')->page ($page, $size)->getPage ();
            if ((empty($data['data']) && $page == 1) || $page>10) {
                $this->json_output (array(
                    'err' => 2,
                    'msg' => '没有相关数据',
                ));
            }
            $this->_checkLastPage ($data['count'], $size, $page);
            //我的积分
            $points = M ('qapp:pointsBill')->getUerPoints ($user_id);
            $points = empty($points) ? 0 : $points;
            foreach ($data['data'] as $k => &$v) {
                $v['typename'] = $this->pointsType[$v['type']];
                if ($v['type'] == 13) {
                    $v['typename'] = $this->shareType[$v['share_type']];
                }

                $v['addtime'] = $this->checkTime ($v['addtime']);
                unset($v['type']);
            }
            $this->json_output (array(
                'err'       => 0,
                'data'      => $data['data'],
                'pointsAll' => $points,
            ));
        }
        $this->_errCode (6);
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

    /*
     * 供求信息置顶之供求信息列表
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

    //分享我的供给或其求购
    public function shareMyPur ()
    {
        $this->is_ajax = true;
        $user_id       = $this->checkAccount (0);
        $id            = sget ('id', 'i');
        if ($id < 1) {
            $this->_errCode (6);
        }
        $data = M ('qapp:plasticShare')->getMySharePur ($id);
        $info = M ("product:purchase")->getInfoById ($id);
        //p($info);exit;
        //获取我的塑料圈个人信息
        $headimgurl = '';
        $info       = M ('qapp:plasticPersonalInfo')->getMyPlastic ($info['user_id'], $headimgurl);
        if (empty($data)) {
            $this->json_output (array(
                'err' => 2,
                'msg' => '没有相关的数据',
            ));
        }
        $this->json_output (array(
            'err'  => 0,
            'data' => $data,
            'info' => $info,
        ));
    }

    //验证是否分享成功日志
    public function saveShareLog ()
    {
        if ($_POST) {
            $share_id = sget ('id', 'i');
            $type     = sget ('type', 'i', 1);//分享类容类型  1采购 2报价 3 文章
            $user_id  = $this->checkAccount ();//分享人的id
            $share    = '';
            if (!M ('qapp:plasticShare')->saveShareLog ($share_id, $type, $user_id, $share)) {
                $this->json_output (array(
                    'err' => 6,
                    'msg' => '分享失败',
                ));
            }
            $this->json_output (array(
                'err' => 0,
                'msg' => '分享成功',
            ));
        }
        $this->_errCode (6);
    }


    public function test4 ()
    {
        p (M ('system:setting')->get ('points'));
    }

    //安全过滤用户输入的字符
    public function clearStr ($str)
    {
        $str = htmlspecialchars ($str);
        $str = safe_replace ($str);
        $str = trim ($str);

        return $str;
    }

    //错误输出的方法
    protected function _errCode ($code = 0, $data = '')
    {
        switch ($code) {
            case 0:
                if (empty($data)) {
                    $this->json_output (array(
                        'err' => 0,
                        'msg' => 'success',
                    ));
                }
                $this->json_output (array(
                    'err'  => 0,
                    'data' => $data,
                ));
                break;
            case 1:
                $this->json_output (array(
                    'err' => 1,
                    'msg' => '失效,请重新登录',
                ));
                break;
            case 2:
                $this->json_output (array(
                    'err' => 2,
                    'msg' => '没有相关数据',
                ));
                break;
            case 3:
                $this->json_output (array(
                    'err' => 3,
                    'msg' => '没有更多数据',
                ));
                break;
            case 5:
                $this->json_output (array(
                    'err' => 5,
                    'msg' => '不定时上线,敬请期待！',
                ));
                break;
            case 6:
                $this->json_output (array(
                    'err' => 6,
                    'msg' => '参数错误',
                ));
                break;
            case 7:
                $this->json_output (array(
                    'err' => 7,
                    'msg' => '服务器繁忙,请稍后再试！',
                ));
                break;
            case 8:
                $this->json_output (array(
                    'err' => 7,
                    'msg' => '服务正在维护,请稍后再试！',
                ));
                break;
            case 99:
                $this->json_output (array(
                    'err' => 99,
                    'msg' => "是否消耗{$this->points['see_list']}塑豆查看?",
                ));
                break;
            case 100:
                $this->json_output (array(
                    'err' => 100,
                    'msg' => '塑豆不足,请多努力!',
                ));
                break;
            case 101:
                $this->json_output (array(
                    'err' => 101,
                    'msg' => '系统错误',
                ));
                break;
            case 102:
                $this->json_output (array(
                    'err' => 102,
                    'msg' => '小时参数错误',
                ));
                break;
            case 103:
                $this->json_output (array(
                    'err' => 103,
                    'msg' => '分钟参数错误',
                ));
                break;
            case 104:
                $this->json_output (array(
                    'err' => 104,
                    'msg' => '兑换时长参数不正确',
                ));
                break;
            case 105:
                $this->json_output (array(
                    'err' => 105,
                    'msg' => 'purchase_id参数错误',
                ));
                break;
            case 106:
                $this->json_output (array(
                    'err' => 106,
                    'msg' => '选择时间段错误,已被人预定过了',
                ));
                break;
            case 107:
                $this->json_output (array(
                    'err' => 107,
                    'msg' => '年份参数错误',
                ));
                break;
            case 108:
                $this->json_output (array(
                    'err' => 108,
                    'msg' => '月份参数错误',
                ));
                break;
            case 109:
                $this->json_output (array(
                    'err' => 109,
                    'msg' => '日子参数错误,只能选今天和以后7天的，不能大于该月的日子',
                ));
                break;
            case 111:
                $this->json_output (array(
                    'err' => 111,
                    'msg' => '兑换失败',
                ));
                break;
            case 112:
                $this->json_output (array(
                    'err' => 112,
                    'msg' => '收货人错误',
                ));
                break;
            case 113:
                $this->json_output (array(
                    'err' => 113,
                    'msg' => '手机号错误',
                ));
                break;
            case 114:
                $this->json_output (array(
                    'err' => 114,
                    'msg' => '收货地址错误',
                ));
                break;
            case 115:
                $this->json_output (array(
                    'err' => 115,
                    'msg' => '白驹过隙，时间一去不复返，不能停留在逝去的时光里',
                ));
                break;
            case 116:
                $this->json_output (array(
                    'err' => 116,
                    'msg' => 'App内暂时不进行现金兑换，请于微信内搜索《塑料圈》进行兑换',
                ));
                break;
            case 117:
                $this->json_output (array(
                    'err' => 117,
                    'msg' => '当前时间段已过，请提前一小时兑换',
                ));
                break;
            case 118:
                $this->json_output (array(
                    'err' => 118,
                    'msg' => '名额已完,下次请早',
                ));
                break;
            case 119:
                $this->json_output (array(
                    'err' => 119,
                    'msg' => '活动时间已过，请关注我们《塑料圈》公众号，参与更多活动',
                ));
                break;
            default:
                $this->json_output (array(
                    'err' => 999,
                    'msg' => '未知错误编码',
                ));
        }
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


    /**
     * 加密的函数
     */
    public function getEncrypt ()
    {
        if ($_POST) {
            $encrypt_id = sget ('encrypt_id', 's');
            if (empty($encrypt_id)) {
                $this->_errCode (6);
            }
            $tmpStr = desEncrypt ($encrypt_id);
            $this->json_output (array(
                'err'  => 0,
                'data' => $tmpStr,
            ));
        }
        $this->_errCode (6);
    }


    /**
     * 解密函数
     */
    public function getDecrypt ()
    {
        if ($_POST) {
            $decrypt_id = sget ('decrypt_id', 's');
            if (empty($decrypt_id)) {
                $this->_errCode (6);
            }
            $tmpStr = desDecrypt ($decrypt_id);
            $this->json_output (array(
                'err'  => 0,
                'data' => $tmpStr,
            ));
        }
        $this->_errCode (6);
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


    public function clearPic ($data)
    {
        if (empty($data['thumbqq'])) {
            if (strstr ($data['thumb'], 'http')) {
                $data['thumb'] = $data['thumb'];
            } else {
                if (empty($data['thumb'])) {
                    $data['thumb'] = "http://statics.myplas.com/upload/16/09/02/logos.jpg";
                } else {
                    $data['thumb'] = FILE_URL."/upload/".$data['thumb'];
                }
            }

        } else {
            $data['thumb'] = $data['thumbqq'];
        }

        return $data['thumb'];
    }


    /*
     * app更新消息
     */
    public function updateApp ()
    {
        $this->is_ajax = true;
        if ($_POST) {
            $user_id   = $this->checkAccount ();
            $appName   = "塑料圈app";//app名字
            $version   = sget ('version', 's');//版本号
            $sysType   = sget ('sysType', 'i');//1 anzhuo 2 ios
            $deviceNum = sget ('deviceNum', 's');//设备号
            $appupdate = M ('system:setting')->get ('appupdate');

            $sysVersion = $appupdate['version'];
            $serverFlag = $appupdate['serverFlag'];//是否显示公告
            $lastForce  = $appupdate['lastForce'];//是否强制更新
            if (empty($sysType)) {
                $this->_errCode (6);
            }
            if ($sysType == 1) {
                $updateUrl   = $appupdate['and_updateurl']; //apk下载地址
                $upgradeInfo = $appupdate['and_upgradeinfo'];//版本的更新描述
            } elseif ($sysType == 2) {
                $updateUrl   = $appupdate['ios_updateurl']; //apk下载地址
                $upgradeInfo = $appupdate['ios_upgradeinfo'];//版本的更新描述
            }

            $versionArray    = explode ('.', $version);
            $sysVersionArray = explode ('.', $sysVersion);


            if ($versionArray[0] > $sysVersionArray[0]) {
                $lastForce = "0";
            } elseif (($versionArray[0] == $sysVersionArray[0]) && ($versionArray[1] >= $sysVersionArray[1])) {
                $lastForce = "0";
            } elseif ($versionArray[1] == $sysVersionArray[1]) {
                if (isset($versionArray[2]) && isset($sysVersionArray[2])) {
                    if ($versionArray[2] >= $sysVersionArray[2]) {
                        $lastForce = "0";//是否强制更新
                    }
                }
            }

            $this->json_output (array(
                'err'  => 0,
                'data' => compact ('serverFlag', 'lastForce', 'updateUrl', 'upgradeInfo'),
            ));

        }
        $this->_errCode (6);
    }


    public function getSignPackage ()
    {
        $jsapiTicket = $this->getJsApiTicket ();

        // 注意 URL 一定要动态获取，不能 hardcode.
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url      = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $timestamp = time ();
        $nonceStr  = $this->createNonceStr ();

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

        $signature = sha1 ($string);

        $signPackage = array(
            "appId"     => $this->AppID,
            "nonceStr"  => $nonceStr,
            "timestamp" => $timestamp,
            "url"       => $url,
            "signature" => $signature,
            "rawString" => $string,
        );

        return $signPackage;
    }

    //获取票据
    protected function getJsApiTicket ()
    {
        $_key   = 'weixin_jsapi_ticket';
        $cache  = cache::startMemcache ();
        $ticket = $cache->get ($_key);
        if (empty($ticket)) {
            $access_token = $this->wx_get_token ();
            $url          = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token={$access_token}";
            $result       = json_decode ($this->http ($url), true);
            if (isset($result['ticket'])) {
                $ticket = $result['ticket'];
                $cache->set ($_key, $ticket, 7000);

                return $ticket;
            } else {
                return false;
            }
        } else {
            return $ticket;
        }
    }

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




    /**
     *分享
     */
    //获取token
    protected function wx_get_token ()
    {
        $tokenFile = "access_token.txt";//缓存文件名
        $data      = json_decode (file_get_contents ($tokenFile), true);
        if ($data['expire_time'] < CORE_TIME) {
            $url          = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->AppID}&secret={$this->AppSecret}";
            $res          = json_decode ($this->http ($url), true);
            $access_token = $res['access_token'];
            if ($access_token) {
                $data['expire_time']  = CORE_TIME + 7000;
                $data['access_token'] = $access_token;
                $fp                   = fopen ($tokenFile, "w");
                fwrite ($fp, json_encode ($data));
                fclose ($fp);
            }

            return $access_token;
        } else {
            return $data['access_token'];
        }
    }

    //生成随机字符串
    protected function createNonceStr ($length = 16)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str   = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr ($chars, mt_rand (0, strlen ($chars) - 1), 1);
        }

        return $str;
    }

    //获取票据
    protected function get_jsapi_ticket ()
    {
        $_key   = 'weixin_jsapi_ticket';
        $cache  = cache::startMemcache ();
        $ticket = $cache->get ($_key);
        if (empty($ticket)) {
            $access_token = $this->wx_get_token ();
            $url          = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token={$access_token}";
            $result       = json_decode ($this->http ($url), true);
            if (isset($result['ticket'])) {
                $ticket = $result['ticket'];
                $cache->set ($_key, $ticket, 7000);

                return $ticket;
            } else {
                return false;
            }
        } else {
            return $ticket;
        }
    }

    //格式化输出字符串
    protected function formatQueryParaMap ($paraMap, $urlencode = false)
    {
        $buff = "";
        ksort ($paraMap);
        foreach ($paraMap as $k => $v) {
            if (null != $v && "null" != $v && "sign" != $k) {
                if ($urlencode) {
                    $v = urlencode ($v);
                }
                $buff .= $k."=".$v."&";
            }
        }
        //$reqPar;
        if (strlen ($buff) > 0) {
            $reqPar = substr ($buff, 0, strlen ($buff) - 1);
        }

        return $reqPar;
    }

    //获取url
    protected function get_url ()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url      = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        return $url;
    }


    public function janfly ()
    {
        function em_getallheaders ()
        {
            foreach ($_SERVER as $name => $value) {
                if (substr ($name, 0, 5) == 'HTTP_') {
                    $headers[str_replace (' ', '-', ucwords (strtolower (str_replace ('_', ' ', substr ($name, 5)))))] = $value;
                }
            }

            return $headers;
        }

        echo '<pre>';
        //var_dump(em_getallheaders());
        //throw_exception("No support db:");
        //        $this->json_output(array('err'=>0,'data'=>em_getallheaders()));
        //http_status(404);
        //$_SERVER['ALL_HTTP'] = isset($_SERVER['ALL_HTTP']) ?  $_SERVER['ALL_HTTP']: '';
        //        $_SERVER['HTTP_USER_AGENT'];
        //        echo '<pre>';
        //        var_dump($_SERVER['HTTP_USER_AGENT']);
        //        echo '<hr />';
        //        var_dump($_SERVER['ALL_HTTP']);
        var_dump (M ("system:block")->getBlock (3, 6));
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