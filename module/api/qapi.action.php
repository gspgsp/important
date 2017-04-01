<?php

/**
 * 塑料圈的api中心
 * create by zp 2016/10/26
 * 备注：为了应成哥的要求，现在这套接口已不复通用性了
 * 现在所有的chanel全部写死变成6  同时quan_type 变为1 才能标示数据是从塑料圈app来的
 * 还有就是我感觉注册的时候，还是写一个交易员比较的好，要不然，等到分配交易员，不知道要到什么时候
 * app 不能重新注册，一个号码注册成功后，不能重新注册
 *
 */
class qapiAction extends null2Action
{
    protected $db, $err, $cates, $pointsType, $orderStatus, $rePoints;

    public function __init ()
    {
        $this->db          = M ('public:common');
        $this->cates       = array(
            '2'  => '塑料上游',
            '1'  => '早盘预测',
            '9'  => '企业动态',
            '4'  => '中晨塑说',
            '5'  => '外盘快递',
            '21' => '期货资讯',
            '11' => '装置动态',
            '12' => '期刊报告',
            '22' => '独家解读',
        );
        $this->pointsType  = array(
            1  => '签到',
            2  => '登陆',
            3  => '发布报价',
            4  => '订单取消积分返还',
            5  => '兑换礼品',
            6  => '发布采购',
            7  => '注册完善信息送',
            8  => 'app注册',
            9  => '资源库发布',
            10 => '资源库搜索',
            11 => '塑料圈',
        );
        $this->orderStatus = array(
            1 => '已兑换，待确认',
            2 => '已确认，待发货',
            3 => '已发货',
            4 => '订单取消',
            5 => '订单完成',
        );
        $this->rePoints    = 50;//引荐一人50分
    }

    public function init ()
    {
        echo '<div style="margin-top:80px;text-align:center;font-size:50px">This is plasticZone app API center</div>';
    }

    /**
     * 获取用户当前的sessionID字符串，用于在cookie关闭模式
     */
    public function getSession ()
    {
        $sessionId = M ('qapp:appSession')->getSessionAppend ();
        $this->json_output (array( 'err' => 0, 'msg' => '获取成功', 'sessionQuery' => $sessionId ));
    }

    /**
     * zhuce
     */
    public function register ()
    {
        //$cache = cache::startMemcache();
        $cache         = E ('RedisCluster', APP_LIB.'class');
        $this->is_ajax = true;
        $mobile        = sget ('mobile', 's');
        if (!$this->_chkmobile ($mobile)) {
            $this->error ($this->err);
        }
        $password = sget ('password', 's');
        if (strlen ($password) < 6) {
            $this->error ('密码格式不正确,至少6位');
        }
        $mcode  = sget ('code', 's');
        $result = M ('system:sysSMS')->qAppChkDynamicCode ($mobile, $mcode);
        if ($result['err'] > 0) {
            $this->error ($result['msg']);
        }
        $user_model   = M ('system:sysUser');
        $salt         = randstr (6);
        $passwordSalt = $user_model->genPassword ($password.$salt);
        $cache->set ($mobile.'check_reg_ok', true, 300);
        $cache->set ($mobile.'password', $passwordSalt, 300);
        $cache->set ($mobile.'salt', $salt, 300);
        $this->success ('注册成功');
    }

    /**
     * 注册信息补全页面
     * 已经消除引荐人是自己的bug,而且引荐人一定要正确
     */
    public function reginfo ()
    {
        $this->is_ajax = true;
        $mobile        = sget ('mobile', 's');
        if (!$this->_chkmobile ($mobile)) {
            $this->error ($this->err);
        }
        //$cache = cache::startMemcache();
        $cache = E ('RedisCluster', APP_LIB.'class');
        if (!$cache->get ($mobile.'check_reg_ok')) {
            $this->error ('已过期，请重新注册');
        }
        if ($_GET) {
            if (!$name = sget ('name', 's')) {
                $this->error ('请输入姓名');
            }
            if (!sget ('qq', 's', '')) {
                $this->error ('请输入qq号码');
            }
            if (!is_qq (sget ('qq', 's'))) {
                $this->error ('请输入有效的qq号码');
            }
            $c_name = sget ('c_name', 's');
            $region = sget ('region', 's', '');
            if (!$c_name) {
                $this->error ('请输入公司名称');
            }
            $cus_model  = $this->db->model ('customer');
            $customer   = $cus_model->select ('c_id')
                                    ->where ("c_name='$c_name'")
                                    ->getOne ();//获取公司的id
            $user_model = M ('system:sysUser');
            $user_model->startTrans ();
            try {
                $c_id = $customer;
                if (!$customer) {
                    $_customer = array(
                        'c_name'           => $c_name,
                        'chanel'           => 6,
                        'input_time'       => CORE_TIME,
                        'customer_manager' => 859,//交易员
                        'quan_type'        => 1,
                    );
                    if (!$cus_model->add ($_customer)) {
                        throw new Exception("系统错误 reg:101");
                    }
                    $c_id = $cus_model->getLastID ();
                }
                //查看是否为老用户
                $old_user      = $this->db->model ('customer_contact')
                                          ->select ('user_id,parent_mobile')
                                          ->where ("mobile=".$mobile)
                                          ->getRow ();
                $salt          = $cache->get ($mobile.'salt');
                $password      = $cache->get ($mobile.'password');
                $parent_mobile = sget ('parent_mobile', 's');
                if ($mobile == $parent_mobile) {
                    throw new Exception("引荐人不能是自己，先有蛋，还是先有鸡，这是个问题");
                }
                if (empty($parent_mobile)) {
                    $parent_mobile = '';
                } else {
                    if (!$this->db->from ('customer_contact')
                                  ->select ('user_id')
                                  ->where ('mobile='.$parent_mobile)
                                  ->getOne ()
                    ) {
                        throw new Exception("引荐人错误，请重新选择引荐人，或者独自踏上征途。");
                    }

                }
                /*
                 * 其实这一步可以省了，因为现在的已经注册过的，不能重新注册了，只能找回密码了
                 */
                if ($old_user['user_id']) {
                    $_user = array(
                        'salt'          => $salt,
                        'password'      => $password,
                        'name'          => sget ('name', 's'),
                        'qq'            => sget ('qq', 's', ''),
                        'parent_mobile' => empty($old_user['parent_mobile']) ? '' : $old_user['parent_mobile'],
                        'c_id'          => $c_id,
                        'sex'           => sget ('sex', 'i', 0),
                        'chanel'        => 6,
                        'update_time'   => CORE_TIME,
                        'quan_type'     => 1,
                    );
                    if (!$user_model->where ("mobile=".$mobile)
                                    ->update ($_user)
                    ) {
                        throw new Exception("系统错误 reg:105");
                    }
                } else {
                    $is_default = empty($customer) ? 1 : 0;
                    $_user      = array(
                        'mobile'           => $mobile,
                        'salt'             => $salt,
                        'password'         => $password,
                        'name'             => sget ('name', 's'),
                        'qq'               => sget ('qq', 's'),
                        'c_id'             => $c_id,
                        'sex'              => sget ('sex', 'i', 0),
                        'customer_manager' => 859,//交易员
                        'input_time'       => CORE_TIME,
                        'is_default'       => $is_default,
                        'parent_mobile'    => sget ('parent_mobile', 's', ''),
                        'chanel'           => 6,
                        'quan_type'        => 1,
                    );
                    if (!$user_model->add ($_user)) {
                        throw new Exception("系统错误 reg:102");
                    }
                    $user_id = $user_model->getLastID ();
                    //throw new Exception('test');
                    //直接关注和加积分
                    if (!empty($_user['parent_mobile'])) {
                        $focused_id = $this->db->model ('customer_contact')
                                               ->where ("mobile=".$_user['parent_mobile'])
                                               ->select ('user_id')
                                               ->getOne ();
                        if (!M ('plasticzone:plasticAttention')->getAttention ($user_id, $focused_id)) {
                            throw new Exception("系统错误 reg:111");
                        }
                        if (!M ("qapp:pointsBill")->addPoints ($this->rePoints, $focused_id, 12)) {
                            throw new Exception("系统错误 reg:112");
                        }
                    }
                    $mobile_area = getCityByMobile ($mobile);
                    $_info       = array(
                        'user_id'         => $user_id,
                        'reg_ip'          => get_ip (),
                        'reg_time'        => CORE_TIME,
                        'thumbcard'       => '',
                        'reg_chanel'      => 6,
                        'mobile_province' => empty($mobile_area['province']) ? '' : $mobile_area['province'],
                        'mobile_area'     => empty($mobile_area['city']) ? '' : $mobile_area['city'],
                        'region'          => empty($region) ? '' : $region,
                        'quan_type'       => 1,
                    );
                    if (!$this->db->model ('contact_info')
                                  ->add ($_info)
                    ) {
                        throw new Exception("系统错误 reg:103");
                    }
                    if (!$customer) {
                        if (!$this->db->model ('customer')
                                      ->where ("c_id=$c_id")
                                      ->update ("contact_id=".$user_id)
                        ) {
                            throw new Exception("系统错误 reg:104");
                        }
                    }
                    //新增用户默认排序最前
                    $this->db->model ('weixin_ranking')
                             ->add (array( 'user_id' => $user_id, 'pm' => 0, 'rownum' => 0, ));
                }
            } catch (Exception $e) {
                $user_model->rollback ();
                $this->error ($e->getMessage ());
            }
            $user_model->commit ();
            $cache->delete ($mobile.'password');
            $cache->delete ($mobile.'salt');
            $cache->delete ($mobile.'check_reg_ok');
            $this->success ('完善成功');
        }
    }

    /**
     * 验证手机号码
     * @access private
     *
     * @param $type 0 zhuce  1 zhaohuimima/yanzhemadenglu
     *
     * @return bool
     */
    private function _chkmobile ($value = '', $type = 0)
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
                $this->err = '手机号已注册';

                return false;
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

    /**
     * 重置密码
     */
    public function finfMyPwd ()
    {
        if ($_GET) {
            $this->is_ajax = true;
            $mobile        = sget ('mobile', 's');
            if (!$this->_chkmobile ($mobile, 1)) {
                $this->error ($this->err);
            }
            $password = sget ('password', 's');
            if (strlen ($password) < 6) {
                $this->error ('密码格式不正确,至少6位');
            }
            $mcode  = sget ('code', 's');
            $result = M ('system:sysSMS')->qAppChkDynamicCode ($mobile, $mcode);
            if ($result['err'] > 0) {
                $this->error ($result['msg']);
            }
            $user_model   = M ('system:sysUser');
            $salt         = randstr (6);
            $passwordSalt = $user_model->genPassword ($password.$salt);

            //用户重置密码
            $result = $user_model->where ('mobile='.$mobile)
                                 ->update (array( 'password' => $passwordSalt, 'salt' => $salt ));
            //$this->getDBError();
            if (!$result) {
                $this->error ('密码重置失败，请稍后重试');
            } else {
                $this->success ('密码重置成功');
            }
        }
    }


    /**
     * 验证密码
     */
    private function _chkpass ($pass, $repass)
    {
        if (strlen ($pass) < 6) {
            $this->err = '密码格式不正确';

            return false;
        }
        if ($pass != $repass) {
            $this->err = '两次密码不一致';

            return false;
        }

        return true;
    }

    /**
     * 发送手机验证码
     * @access public
     *
     * @param type int 0 zhuce  1 zhaohuimima
     *
     * @return html
     */
    public function sendmsg ()
    {
        $this->is_ajax = true;
        //验证手机
        $mobile = sget ('mobile', 's');
        $type   = sget ('type', 's', '0');//方式
        if (!$this->_chkmobile ($mobile, $type)) {
            $this->error ($this->err);
        }
        $sms = M ('system:sysSMS');
        //检查注册的限制
        $result = $sms->chkRegLimit ($mobile, get_ip ());
        if (empty($result)) {
            $this->error ($sms->getError ());
        }
        //请求动态码
        $result = $sms->qAppDynamicCode ($mobile);
        if ($result['err'] > 0) { //请求错误
            $this->error ($result['msg']);
        }
        $msg = $result['msg']; //短信内容
        if (empty($type)) {
            //发送手机动态码(注册)
            $sms->send (0, $mobile, $msg, 1);
        } else {
            //发送手机动态码（找回密码）
            $sms->send (0, $mobile, $msg, 2);
        }

        $this->success ('发送成功'.$msg);
    }

    //登录
    public function login ()
    {
        if ($_GET) {
            $this->is_ajax = true;
            $username      = sget ('username', 's');
            $password      = sget ('password', 's');
            $this->error ('此版本已不再维护更新，请前往应用商店更新！');
            if (!$this->_chkmobile ($username, 1)) {
                $this->error ($this->err);
            } elseif (strlen ($password) < 6) {
                $this->error ('密码长度应该大于6个字符');
            }
            $chanel = 6; //塑料圈app渠道
            $result = M ('user:passport')->login ($username, $password, $chanel);
            if ($result['err'] > 0) {
                $this->error ($result['msg']);
            } else {
                $token = M ('qapp:appToken')->insert ($result['user']['user_id'], $result['user']);
                //$cache=cache::startMemcache();
                //$this->success('登录成功');
                $this->json_output (array( 'err'       => 0,
                                           'msg'       => '登录成功',
                                           'dataToken' => $token,
                                           'user_id'   => $result['user']['user_id']
                ));
            }

        }
    }

    //获取首页数据
    //通讯录
    public function getPlasticPerson ()
    {
        $this->is_ajax = true;
        if ($_GET) {
            $this->json_output (array( 'err' => 2, 'msg' => '此版本已不再维护更新，请前往应用商店更新！' ));
            //a b c d e f ...获取联系人(可以取消，现在这个功能不需要了)
            $letter = sget ('letter', 's');
            //搜素关键字
            $keywords = sget ('keywords', 's');
            $user_id  = $this->checkAccount (0);
            $page     = sget ('page', 'i', 1);
            $size     = sget ('size', 'i', 10);
            //根本没有排序字段
            //加缓存提交效率
            $cache = cache::startMemcache ();
            $data  = array();
            if (empty($keywords)) {
                if ($page < 4) {//前三页
                    if ($user_id > 0) {
                        if (!$data['data'] = $cache->get ('qgetPlasticPerson'.$page)) {
                            $data = M ('plasticzone:plasticPerson')->getPlasticPerson ($user_id, $letter, $keywords, $page, $size);
                            $cache->set ('qgetPlasticPerson'.$page, $data['data'], 60);//1分钟缓存
                        }
                    } else {
                        if (!$data['data'] = $cache->get ('qgetPlasticPerson0_'.$page)) {
                            $data = M ('plasticzone:plasticPerson')->getPlasticPerson ($user_id, $letter, $keywords, $page, $size);
                            $cache->set ('qgetPlasticPerson0_'.$page, $data['data'], 60);//1分钟缓存
                        }
                    }
                } else {
                    $data = M ('plasticzone:plasticPerson')->getPlasticPerson ($user_id, $letter, $keywords, $page, $size);
                }
            } else {
                $data = M ('plasticzone:plasticPerson')->getPlasticPerson ($user_id, $letter, $keywords, $page, $size);
            }
            if (empty($data['data']) && $page == 1) {
                $this->json_output (array( 'err' => 2, 'msg' => '没有相关数据' ));
            }
            $this->_checkLastPage ($data['count'], $size, $page);
            if ($page >= 3 && $user_id <= 0) {
                $this->json_output (array( 'err' => 1, 'msg' => '想要查看更多，请登录', 'count' => $data['count'] ));
            }
            if ($user_id > 0) {
                $dayTime = strtotime (date ("Y-m-d"));
                if ($page == 1 && !$this->db->from ('log_login')
                                            ->select ('input_time')
                                            ->where ("input_time >".$dayTime." and chanel =6 and quan_type = 1 and user_id=$user_id")
                                            ->getOne ()
                ) {

                    $arr = array(
                        'user_id'    => $user_id,
                        'input_time' => CORE_TIME,
                        'ip'         => get_ip (),
                        'chanel'     => 6,
                        'quan_type'  => 1,
                    );
                    $this->db->model ("log_login")
                             ->add ($arr);
                };
            }
            if ($page == 1) {
                $members = M ('plasticzone:plasticPersonalInfo')->getAllMembers ();
                $members = empty($members) ? 0 : $members;
                $this->json_output (array( 'err' => 0, 'persons' => $data['data'], 'member' => $members ));
            }
            $this->json_output (array( 'err' => 0, 'persons' => $data['data'] ));
        }
    }

    //我的塑料圈
    public function myZone ()
    {
        $this->is_ajax = true;
        if ($_GET) {
            $this->json_output (array( 'err' => 2, 'msg' => '此版本已不再维护更新，请前往应用商店更新！' ));
            $user_id = $this->checkAccount ();
            //我的求购
            $s_in_count = M ('plasticzone:plasticPersonalInfo')->getConut ($user_id, 1, 6);//purchase表
            $s_in_count = empty($s_in_count) ? 0 : $s_in_count;
            //我的报价
            $s_out_count = M ('plasticzone:plasticPersonalInfo')->getConut ($user_id, 2, 6);//purchase表
            $s_out_count = empty($s_out_count) ? 0 : $s_out_count;
            //我的积分
            $points = M ('points:pointsBill')->getUerPoints ($user_id);
            $points = empty($points) ? 0 : $points;
            //我的留言
            $leaveword = M ('plasticzone:plasticMsgCount')->getMsgCount ($user_id, 1);
            if (!$user_id > 0 || !$leaveword > 0) {
                $leaveword = 0;
            }
            //我的未读消息
            $message = M ('plasticzone:plasticMsgCount')->getMsgCount ($user_id, 2);
            if (!$user_id > 0 || !$message > 0) {
                $message = 0;
            }
            //我的引荐
            $data         = M ('plasticzone:plasticIntroduction')->getqAppMyIntroduction ($user_id);
            $introduction = empty($data['count']) ? 0 : $data['count'];
            //我的粉丝
            $data   = M ('plasticzone:plasticIntroduction')->getMyFuns ($user_id, 1);
            $myfans = empty($data['count']) ? 0 : $data['count'];
            //我的关注
            $data       = M ('plasticzone:plasticIntroduction')->getMyFuns ($user_id, 2);
            $myconcerns = empty($data['count']) ? 0 : $data['count'];
            //获取我的塑料圈个人信息
            $headimgurl = '';
            $data       = M ('plasticzone:plasticPersonalInfo')->getMyPlastic ($user_id, $headimgurl);
            if (empty($data)) {
                $this->json_output (array( 'err' => 2, 'msg' => '没有相关资料' ));
            }
            $data = empty($data) ? array( 'err' => 2, 'msg' => '没有相关资料' ) : $data;
            $this->json_output (array(
                's_in_count'   => $s_in_count,//我的求购
                's_out_count'  => $s_out_count,//我的供给
                'points'       => $points,//我的积分
                'leaveword'    => $leaveword,//我的留言
                'message'      => $message,//我的未读消息
                'introduction' => $introduction,//我的引荐
                'myfans'       => $myfans,//我的粉丝
                'myconcerns'   => $myconcerns,//我的关注
                'data'         => $data,//我的个人信息
            ));
        }
    }


    //获取我的引荐(引荐数)
    public function getMyIntroduction ()
    {
        $this->is_ajax = true;
        if ($_GET) {
            $user_id = $this->checkAccount ();
            $page    = sget ('page', 'i', 1);
            $size    = sget ('size', 'i', 10);
            $data    = M ('plasticzone:plasticIntroduction')->getqAppMyIntroduction ($user_id, $page, $size);
            if (empty($data['data']) && $page == 1) {
                $this->json_output (array( 'err' => 2, 'msg' => '没有相关的数据', 'count' => 0 ));
            }
            $this->_checkLastPage ($data['count'], $size, $page);
            $this->json_output (array( 'err' => 0, 'data' => $data['data'], 'count' => $data['count'] ));
        }
    }


    //(中间供求信息)获取供求发布和消息回复
    public function getReleaseMsg ()
    {
        $this->is_ajax = true;
        if ($_GET) {
            $this->checkAccount ();
            //筛选条件
            $keywords = sget ('keywords', 's');
            $type     = sget ('type', 'i', 0);//0 全部 1 求购 2 供给
            //普通条件
            $page = sget ('page', 'i', 1);
            $size = sget ('size', 'i', 10);
            $data = M ('plasticzone:plasticRelease')->getReleaseMsg ($keywords, $page, $size, $type);
            if (empty($data['data']) && $page == 1) {
                $this->json_output (array( 'err' => 2, 'msg' => '没有相关的数据' ));
            }
            $this->_checkLastPage ($data['count'], $size, $page);
            $this->json_output (array( 'err' => 0, 'data' => $data['data'] ));
        }
    }


    //进入物性表的发布采购
    //物性表的发布采购(委托洽谈),单独写一个方法physical表(getPhysicalDelegateData)和搜索中的不能共用
    //判断提交的发布报价(采购1、报价2)数据/user/mypurchase/pub(现已修改到下面的方法)
    public function pub ()
    {
        $this->is_ajax = true;
        if ($data = $_POST['data']) {
            $user_id    = $this->checkAccount ();
            $data       = saddslashes ($data);
            $pt         = $data[0]['pt'];//平台,1:塑料圈(6) 空:其他
            $remark     = $data[0]['remark'];//备注
            $remark     = empty($remark) ? '' : $remark;
            $content    = $data[0]['content'];//客户直接填写的求购内容，无格式
            $cargo_type = $data[0]['cargo_type'];//现货、期货
            $type       = $data[0]['type'];//采购1、报价2
            $pur_model  = M ('product:purchase');
            $fac_model  = M ('product:factory');
            $pro_model  = M ('product:product');
            $soms       = M ('plasticzone:plasticPerson')
                ->select ('c_id,customer_manager')
                ->where ('user_id='.$user_id)
                ->getRow ();

            //判断是否只有content
            if (empty($data[0]['model']) && empty($data[0]['f_name']) && empty($data[0]['store_house'])) {
                $_content = array(
                    'user_id'    => $user_id,//用户id
                    'c_id'       => $soms['c_id'],//客户id
                    'status'     => $type == 1 ? 1 : 2,//状态，报价不需要审核，采购需要审核
                    'sync'       => 6,//报价来源平台
                    'type'       => $type,//采购、报价
                    'quan_type'  => 1,
                    'content'    => str_replace (PHP_EOL, '', $content),//客户直接填写的求购内容
                    'input_time' => CORE_TIME,//创建时间
                    'remark'     => $remark,//备注
                );
                if (!$pur_model->add ($_content)) {
                    $this->json_output (array( 'err' => 3, 'msg' => '插入数据失败' ));
                }
                if ($type == 2) {//报价
                    $spoints = intval (M ('system:setting')->get ('points')['points']['sale']);
                    if (!M ("qapp:pointsBill")
                        ->select ('id')
                        ->where ("addtime >".strtotime (date ("Y-m-d"))."  and type=3 and uid=".$user_id)
                        ->order ("id desc")
                        ->getOne ()
                    ) {
                        if (!$arr = M ("qapp:pointsBill")->addPoints ($spoints, $user_id, 3)) {
                            $this->json_output (array( 'err' => 5, 'msg' => "系统错误 pubpur:103" ));
                        }
                    }
                } elseif ($type == 1) {//采购
                    $spoints = intval (M ('system:setting')->get ('points')['points']['pur']);
                    if (!M ("qapp:pointsBill")
                        ->select ('id')
                        ->where ("addtime >".strtotime (date ("Y-m-d"))." and type=6 and uid=".$user_id)
                        ->order ("id desc")
                        ->getOne ()
                    ) {
                        if (!$arr = M ("qapp:pointsBill")->addPoints ($spoints, $user_id, 6)) {
                            $this->json_output (array( 'err' => 5, 'msg' => "系统错误 pubpur:103" ));
                        }
                    }
                }
                $this->success ('提交成功');
            }
            //
            foreach ($data as $key => $value) {
                //是否已有该产品
                $model = $this->db->from ('product p')
                                  ->join ('factory f', 'p.f_id=f.fid');
                //                 $where="p.model='{$value['model']}' and p.product_type={$value['product_type']} and f.f_name='{$value['f_name']}'";
                $where = "p.model='{$value['model']}'  and f.f_name='{$value['f_name']}'";
                $pid   = $model->where ($where)
                               ->select ('p.id')
                               ->getOne ();

                $_data = array(
                    'user_id'          => $user_id,//用户id
                    'c_id'             => $soms['c_id'],//客户id
                    'customer_manager' => $soms['customer_manager'],//交易员
                    'number'           => $value['number'],//吨数
                    'unit_price'       => $value['price'],//单价
                    'provinces'        => $value['provinces'],//省份id
                    'store_house'      => $value['store_house'],//仓库
                    'cargo_type'       => $cargo_type,//现货期货
                    'period'           => $value['period'],//期货周期
                    'bargain'          => $value['bargain'],//是否实价
                    'type'             => $type,//采购、报价
                    'sync'             => 6,//报价来源平台
                    'quan_type'        => 1,
                    'status'           => $type == 1 ? 1 : 2,//状态，报价不需要审核，采购需要审核
                    'input_time'       => CORE_TIME,//创建时间
                    'remark'           => $remark,//备注
                    'content'          => str_replace (PHP_EOL, '', $content),//客户直接填写的求购内容
                );

                if ($pid) {
                    //已有产品直接添加采购信息
                    $_data['p_id'] = $pid;//产品id
                    $pur_model->add ($_data);
                } else {
                    //没有产品则新增一个产品
                    $pur_model->startTrans ();
                    try {
                        // 是否已有厂家
                        $f_id = $fac_model->where ("f_name='{$value['f_name']}'")
                                          ->select ('fid')
                                          ->getOne ();
                        if (!$f_id) {
                            //创建新厂家
                            $_factory = array(
                                'f_name'     => $value['f_name'],//厂家名称
                                'input_time' => CORE_TIME,//创建时间
                            );
                            if (!$fac_model->add ($_factory)) {
                                throw new Exception("系统错误 pubpur:101");
                            }
                            $f_id = $fac_model->getLastID ();
                        }
                        $_product = array(
                            'model'        => $value['model'],//牌号
                            'product_type' => $value['product_type'],//产品类型
                            'process_type' => $value['process_level'],//加工级别
                            'input_time'   => CORE_TIME,//创建时间
                            'f_id'         => $f_id,//厂家id
                            'status'       => 3,//审核状态
                        );
                        if (!$pro_model->add ($_product)) {
                            throw new Exception("系统错误 pubpur:102");
                        }
                        $pid           = $pro_model->getLastID ();
                        $_data['p_id'] = $pid;
                        if (!$pur_model->add ($_data)) {
                            throw new Exception("系统错误 pubpur:103");
                        }
                        if ($type == 2) {//报价
                            $spoints = intval (M ('system:setting')->get ('points')['points']['sale']);
                            if (!M ("qapp:pointsBill")
                                ->select ('id')
                                ->where ("addtime >".strtotime (date ("Y-m-d"))." and type=3")
                                ->order ("id desc")
                                ->getOne ()
                            ) {
                                if (!$arr = M ("qapp:pointsBill")->addPoints ($spoints, $user_id, 3)) {
                                    throw new Exception("系统错误 pubpur:103");
                                }
                            }
                        } elseif ($type == 1) {//采购
                            $spoints = intval (M ('system:setting')->get ('points')['points']['pur']);
                            if (!M ("qapp:pointsBill")
                                ->select ('id')
                                ->where ("addtime >".strtotime (date ("Y-m-d"))." and type=6")
                                ->order ("id desc")
                                ->getOne ()
                            ) {
                                if (!$arr = M ("qapp:pointsBill")->addPoints ($spoints, $user_id, 6)) {
                                    throw new Exception("系统错误 pubpur:103");
                                }
                            }
                        }
                    } catch (Exception $e) {
                        $pur_model->rollback ();
                        $this->error ($e->getMessage ());
                    }
                    $pur_model->commit ();
                }
            }
            // if(!empty($pt) && CORE_TIME>strtotime('2016-08-5') && CORE_TIME<strtotime('2016-08-19')){
            //     $this->json_output(array('err'=>2,'msg'=>'微信来源'));
            // }
            $this->success ('提交成功');
        }
        $this->error ('提交方式错误');
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

    //删除报价或供给de单条回复
    public function deleteRepeat ()
    {
        $this->is_ajax = true;
        if ($_GET) {
            $this->checkAccount ();
            $id     = sget ('id', 'i', 0);
            $result = M ('plasticzone:plasticMyMsg')->deleteRepeat ($id);
            $this->json_output ($result);
        }
    }

    //回复供求消息
    public function saveMsg ()
    {
        $this->is_ajax = true;
        if ($_GET) {
            $user_id = $this->checkAccount ();
            // $user_id = sget('user_id','i',0)//回复人id
            $pur_id  = sget ('pur_id', 'i', 0);//purchase表的消息id
            $send_id = sget ('send_id', 'i', 0);//purchase表发报价或采购人的(pur.user_id)
            $content = sget ('content', 's');//回复的内容
            if (empty($content)) {
                $this->json_output (array( 'err' => 1, 'msg' => '回复内容不能为空' ));
            }
            $result = M ('plasticzone:plasticRepeat')->saveMsg ($user_id, $pur_id, $send_id, $content);
            if ($result) {
                $this->json_output (array( 'err' => 0, 'msg' => '回复消息保存成功' ));
            }
        }
    }

    //获取我的供给或求购
    public function getMyMsg ()
    {
        $this->is_ajax = true;
        if ($_GET) {
            $user_id = $this->checkAccount ();
            $page    = sget ('page', 'i', 1);
            $size    = sget ('size', 'i', 10);
            $type    = sget ('type', 'i');//1采购 2报价
            $data    = M ('plasticzone:plasticMyMsg')->getMyMsg ($user_id, $page, $size, $type);
            if (empty($data['data']) && $page == 1) {
                $this->json_output (array( 'err' => 2, 'msg' => '没有相关的数据' ));
            }
            $this->_checkLastPage ($data['count'], $size, $page);
            $this->json_output (array( 'err' => 0, 'data' => $data['data'] ));
        }
    }

    //删除我的供给或求购
    public function deleteMyMsg ()
    {
        $this->is_ajax = true;
        if ($_GET) {
            $this->checkAccount ();
            $id     = sget ('id', 'i');//当前我的报价或采购的id
            $result = M ('plasticzone:plasticMyMsg')->deleteMyMsg ($id);
            $this->json_output ($result);
        }
    }


    //获取我的(留言)
    public function getMyComment ()
    {
        $this->is_ajax = true;
        if ($_GET) {
            $user_id = $this->checkAccount ();
            $page    = sget ('page', 'i', 1);
            $size    = sget ('size', 'i', 10);
            $data    = M ('plasticzone:plasticMyMsg')->getMyComment ($user_id, $page, $size, 6);//塑料圈app
            if (empty($data['data']) && $page == 1) {
                $this->json_output (array( 'err' => 2, 'msg' => '没有相关的数据' ));
            }
            $this->_checkLastPage ($data['count'], $size, $page);
            $this->json_output (array( 'err' => 0, 'data' => $data['data'] ));
        }
    }

    //获取我的粉丝和我的关注(数)
    public function getMyFuns ()
    {
        $this->is_ajax = true;
        if ($_GET) {
            $user_id = $this->checkAccount ();
            $type    = sget ('type', 'i');//1粉丝2关注
            $page    = sget ('page', 'i', 1);
            $size    = sget ('size', 'i', 10);
            $data    = M ('plasticzone:plasticIntroduction')->getMyFuns ($user_id, $type, $page, $size);
            if (empty($data['data']) && $page == 1) {
                $this->json_output (array( 'err' => 2, 'msg' => '没有相关的数据', 'count' => 0 ));
            }
            $this->_checkLastPage ($data['count'], $size, $page);
            $this->json_output (array( 'err' => 0, 'data' => $data['data'], 'count' => $data['count'] ));
        }
    }


    //查看塑料圈好友资料
    public function getZoneFriend ()
    {
        $this->is_ajax = true;
        if ($_GET) {
            $user_id = $this->checkAccount ();
            $userid  = sget ('userid', 'i');//当前联系人的id
            $data    = M ('plasticzone:plasticPersonalInfo')->getPersonalInfo ($user_id, $userid);
            if (empty($data)) {
                $this->json_output (array( 'err' => 2, 'msg' => '没有相关资料' ));
            }
            $this->json_output (array( 'err' => 0, 'data' => $data ));
        }
    }

    //偏好设置-发送短信
    public function favorateSet ()
    {
        $this->is_ajax = true;
        if ($_GET) {
            $user_id  = $this->checkAccount ();
            $type     = sget ('type', 'i');//0 关注 1 回复
            $is_allow = sget ('is_allow', 'i', 0);//0:允许 1:不允许
            $result   = M ('plasticzone:plasticAttention')->favorateSet ($user_id, $type, $is_allow);
            $this->json_output ($result);
        }
    }


    //获取我的塑料圈个人信息
    public function getMyPlastic ()
    {
        $cache         = cache::startMemcache ();
        $this->is_ajax = true;
        if ($_GET) {
            $user_id    = $this->checkAccount ();
            $headimgurl = sget ('headimgurl', 's');
            $data       = M ('plasticzone:plasticPersonalInfo')->getMyPlastic ($user_id, $headimgurl);
            if (empty($data)) {
                $this->json_output (array( 'err' => 2, 'msg' => '没有相关资料' ));
            }
            $this->json_output (array( 'err' => 0, 'data' => $data ));
        }
    }


    //查看我的资料
    public function getSelfInfo ()
    {
        $this->is_ajax = true;
        if ($_GET) {
            $user_id = $this->checkAccount ();
            $data    = M ('plasticzone:plasticPersonalInfo')->getSelfInfo ($user_id);
            if (empty($data)) {
                $this->json_output (array( 'err' => 2, 'msg' => '没有相关资料' ));
            }
            $this->json_output (array( 'err' => 0, 'data' => $data ));
        }
    }

    //保存我的资料
    public function saveSelfInfo ()
    {
        $this->is_ajax = true;
        if ($_GET) {
            $user_id = $this->checkAccount ();
            $type    = sget ('type', 'i');//类型 1 地址 2 性别 3 主营牌号
            $field   = sget ('field', 's');
            $result  = M ('plasticzone:plasticSave')->saveSelfInfo ($user_id, $type, $field);
            if (empty($result)) {
                $this->json_output (array( 'err' => 2, 'msg' => '保存资料失败' ));
            }
            $this->json_output (array( 'err' => 0, 'msg' => '保存资料成功' ));
        }
    }

    //获取ta的求购或供给
    public function getTaPur ()
    {
        $this->is_ajax = true;
        if ($_GET) {
            $user_id  = $this->checkAccount ();
            $keywords = sget ('keywords', 's');//这里传空值
            $page     = sget ('page', 'i', 1);
            $size     = sget ('size', 'i', 10);
            $userid   = sget ('userid', 'i');//当前联系人的id
            $type     = sget ('type', 'i', 1);//1采购 2报价
            $userid   = sget ('userid', 'i');//当前联系人的id
            $data     = M ('plasticzone:plasticRelease')->getReleaseMsg2 ($keywords, $page, $size, $userid, $type);
            if (empty($data['data']) && $page == 1) {
                $this->json_output (array( 'err' => 2, 'msg' => '没有相关的数据' ));
            }
            $this->_checkLastPage ($data['count'], $size, $page);
            $this->json_output (array( 'err' => 0, 'data' => $data['data'] ));
        }
    }

    //关注或取消关注
    public function focusOrCancel ()
    {
        $this->is_ajax = true;
        if ($_GET) {
            $user_id    = $this->checkAccount ();
            $focused_id = sget ('focused_id', 'i');//当前联系人的id
            $result     = M ('plasticzone:plasticAttention')->getAttention ($user_id, $focused_id);
            $this->json_output ($result);
        }
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
        if ($_GET) {
            $user_id = $this->checkAccount ();
            $userId  = sget ('userId', 'i');//接受消息人的id
            $content = sget ('content', 's');
            $result  = M ('plasticzone:plasticRepeat')->saveZoneMsg ($user_id, $userId, $content);
            if ($result) {
                $this->json_output (array( 'err' => 0, 'msg' => '消息发送成功' ));
            }
        }
    }

    //塑料圈联系人的-我的消息（yuepao）
    public function getZoneContactMsg ()
    {
        $this->is_ajax = true;
        if ($_GET) {
            $user_id = $this->checkAccount ();
            $page    = sget ('page', 'i', 1);
            $size    = sget ('size', 'i', 10);
            $type    = sget ('type', 'i', 1);//1:我接受的 2:我发送的
            $data    = M ('plasticzone:plasticMyMsg')->getZoneContactMsg ($user_id, $type, $page, $size);
            if (empty($data['data']) && $page == 1) {
                $this->json_output (array( 'err' => 2, 'msg' => '没有相关的数据' ));
            }
            $this->_checkLastPage ($data['count'], $size, $page);
            $this->json_output (array( 'err' => 0, 'data' => $data['data'] ));
        }
    }


    //退出登录/mobi/personalCenter/logOut
    //获取我的资料
    //获取我的积分
    //判断是否到最后一页
    private function _checkLastPage ($count, $size, $page)
    {
        if ($count > 0) {
            if ($count % $size == 0 && ceil ($count / $size) < $page) {
                p (1);
                exit;
                $this->json_output (array( 'err' => 3, 'msg' => '没有更多数据' ));
            } elseif ($count % $size != 0 && ceil ($count / $size) < $page) {
                p (2);
                exit;
                $this->json_output (array( 'err' => 3, 'msg' => '没有更多数据' ));
            }
        }
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


    //判断账户
    /**
     * @param int $type 0 qushouyeshuju  1 biede
     *
     * @return int|string
     */
    private function checkAccount ($type = 1)
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
                $this->json_output (array( 'err' => 1, 'msg' => '账号错误' ));
            }
        } else {
            if ($user_id <= 0) {
                $this->json_output (array( 'err' => 1, 'msg' => '您未登录塑料圈,无法查看企业及个人信息' ));
            }
        }

        return $user_id;
    }

    //退出登录
    public function logOut ()
    {
        if ($_GET) {
            $this->checkAccount ();
            $this->is_ajax = true;
            $token         = sget ('token', 's');
            $cache         = cache::startMemcache ();
            $cache->delete ($token);
            M ('qapp:appToken')->destory ($token);
            $this->json_output (array( 'err' => 0, 'msg' => '退出成功' ));
        }
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
        if ($_GET) {
            $this->is_ajax = true;
            //$this->checkAccount();
            $cache = cache::startMemcache ();
            if (!$info = $cache->get ('qtopLineInfo')) {
                $info = M ("news:news")->getqAppList ('public', 1, null, 5);
                foreach ($info as $key => &$value) {
                    $value['input_time']  = $this->checkTime ($value['input_time']);
                    $value['content']     = stripslashes ($value['content']);
                    $str                  = $value['content'];
                    $value['content']     = $this->cleanhtml ($str, '<p>');
                    $value['description'] = mb_substr (strip_tags ($value['content']), 0, 60, 'utf-8').'...';
                    if ($value['type'] == 'public') {
                        $arr           = array( 'pe', 'pp', 'pvc' );
                        $tmp           = array_rand ($arr, 1);
                        $value['type'] = $arr[$tmp];
                    }
                    $value['type'] = strtoupper ($value['type']);
                }
                unset($value['content']);
                $cache->set ('qtopLineInfo', $info, 600);
            }
            $this->json_output (array(
                'err'  => 0,
                'data' => array(
                    'topLine' => $this->cates,
                    'info'    => $info,
                )
            ));
        }
    }

    /**
     * 塑料头条-分类列表
     */
    public function getCateList ()
    {
        if ($_GET) {
            $this->is_ajax = true;
            $page          = sget ('page', 'i', 1);
            $size          = sget ('size', 'i', 10);
            $cate_id       = sget ('cate_id', 'i', 7);
            $data          = M ("news:news")->getqAppList ('public', $cate_id, $page, $size);
            $this->checkAccount ();
            $cache = cache::startMemcache ();
            if ($page <= 2) {
                $data = array();
                if (!$data['data'] = $cache->get ('qcateListInfo'.$page.'_'.$cate_id)) {
                    $data = M ("news:news")->getqAppList ('public', $cate_id, $page, $size);
                    if (empty($data['data']) && $page == 1) {
                        $this->json_output (array( 'err' => 2, 'msg' => '没有相关数据' ));
                    }
                    $this->_checkLastPage ($data['count'], $size, $page);
                    //截取示例文章文字
                    foreach ($data['data'] as $key => &$v) {
                        $v['content']                      = $this->cleanhtml (strip_tags ($v['content']), '');
                        $data['data'][$key]['description'] = mb_substr (strip_tags ($v['content']), 0, 50, 'utf-8').'...';
                        //取出右键导航分类名称
                        $data['data'][$key]['cate_name']  = $this->cates[$cate_id];
                        $data['data'][$key]['input_time'] = $this->checkTime ($v['input_time']);
                        if ($v['type'] == 'public') {
                            $arr       = array( 'pe', 'pp', 'pvc' );
                            $tmp       = array_rand ($arr, 1);
                            $v['type'] = $arr[$tmp];
                        }
                        $v['type'] = strtoupper ($v['type']);
                        unset($v['content']);
                    }
                    $cache->set ('qcateListInfo'.$page.'_'.$cate_id, $data['data'], 600);
                }
            } else {
                $data = M ("news:news")->getqAppList ('public', $cate_id, $page, $size);
                if (empty($data['data']) && $page == 1) {
                    $this->json_output (array( 'err' => 2, 'msg' => '没有相关数据' ));
                }
                $this->_checkLastPage ($data['count'], $size, $page);
                //截取示例文章文字
                foreach ($data['data'] as $key => &$v) {
                    $v['content']                      = $this->cleanhtml (strip_tags ($v['content']), '');
                    $data['data'][$key]['description'] = mb_substr (strip_tags ($v['content']), 0, 50, 'utf-8').'...';
                    //取出右键导航分类名称
                    $data['data'][$key]['cate_name']  = $this->cates[$cate_id];
                    $data['data'][$key]['input_time'] = $this->checkTime ($v['input_time']);
                    if ($v['type'] == 'public') {
                        $arr       = array( 'pe', 'pp', 'pvc' );
                        $tmp       = array_rand ($arr, 1);
                        $v['type'] = $arr[$tmp];
                    }
                    $v['type'] = strtoupper ($v['type']);
                    unset($v['content']);
                }
            }
            $this->json_output (array( 'err' => 0, 'info' => $data['data'] ));
        }
    }

    /**
     * 塑料头条-详情列表
     */
    public function getDetailInfo ()
    {
        if ($_GET) {
            $this->is_ajax = true;
            $id            = sget ('id', i);
            $this->checkAccount ();
            if (empty($id)) {
                $this->error (array( 'err' => 5, 'msg' => '参数错误，请稍后再试' ));
            }
            M ("news:news")->updateqAppPv ($id);
            $data               = $this->db->model ('news_content')
                                           ->where ('id='.$id)
                                           ->getRow ();
            $time               = $data['input_time'];
            $data['input_time'] = $this->checkTime ($data['input_time']);
            $data['author']     = empty($data['author']) ? '中晨' : $data['author'];
            $data['content']    = stripslashes ($data['content']);
            $data['content']    = preg_replace ("/style=.+?[*|\"]/i", '', $data['content']);
            //$str= preg_replace("/border="0"",'',$str);
            $data['content'] = preg_replace ("/width=.+?[*|\"]/i", '', $data['content']);
            //$data['content']=$this->cleanhtml(($data['content']),'<img><a><br /><table></table><tr></tr><td></td>');
            //取出右键导航分类名称
            $data['cate_name'] = $this->cates[$data['cate_id']];
            if ($data['type'] == 'public') {
                $arr          = array( 'pe', 'pp', 'pvc' );
                $tmp          = array_rand ($arr, 1);
                $data['type'] = $arr[$tmp];
            }
            $data['type'] = strtoupper ($data['type']);
            //取出上一篇和下一篇input_time desc,sort_order desc  上一篇是最新的
            //取出上一篇和下一篇
            $data['lastOne'] = $this->db->model ('news_content')
                                        ->where ('cate_id='.$data['cate_id'].' and id >'.$id)
                                        ->select ('id')
                                        ->order ('id asc')
                                        ->limit (1)
                                        ->getOne ();
            $data['nextOne'] = $this->db->model ('news_content')
                                        ->where ('cate_id='.$data['cate_id'].' and id <'.$id)
                                        ->select ('id')
                                        ->order ('id desc')
                                        ->limit (1)
                                        ->getOne ();
            $this->json_output (array( 'err' => 0, 'info' => $data ));
        }
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
            '@<script[^>]*?>.*?</script>@si',  // Strip out javascript
            /*  '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags*/
            "'<style[^>]*?>.*?</style>'si", /*'@<style[^>]*?>.*?</style>@si',    // Strip style tags properly*/
            '@<![\s\S]*?--[ \t\n\r]*>@',         // Strip multi-line comments including CDATA
        );
        $array  = array( '', '', '' );
        $str    = preg_replace ($search, $array, $str);
        $str    = str_replace (array( "\r\n", "\r", "\n", "&nbsp;" ), "", $str);
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
        if ($_GET) {
            $this->is_ajax = true;
            $user_id       = $this->checkAccount ();
            $page          = sget ('page', 'i', 1);
            $size          = sget ('size', 'i', 10);
            $data          = M ("points:pointsGoods")
                ->select ('id,cate_id,thumb,name,points')
                ->where ("status = 1 and receive_num < num")
                ->order ('id desc')
                ->page ($page, $size)
                ->getPage ();
            //我的积分
            $points = M ('qapp:pointsBill')->getUerPoints ($user_id);
            $points = empty($points) ? 0 : $points;
            if (empty($data['data']) && $page == 1) {
                $this->json_output (array( 'err' => 2, 'msg' => '没有相关数据' ));
            }
            $this->_checkLastPage ($data['count'], $size, $page);
            foreach ($data['data'] as $k => &$v) {
                if ($v['thumb']) {
                    $v['thumb'] = FILE_URL.'/upload/'.$v['thumb'];
                }
                if ($v['image']) {
                    $v['image'] = FILE_URL.'/upload/'.$v['image'];
                }
            }
            $this->json_output (array( 'err' => 0, 'info' => $data['data'], 'pointsAll' => $points ));
        }
    }

    /*
     * 塑料圈app之积分商品详情页
     */
    public function getProductInfo ()
    {
        if ($_GET) {
            $this->is_ajax = true;
            $this->checkAccount ();
            $id = sget ('id', i);//商品的id
            if ($id < 1) {
                $this->json_output (array( 'err' => 1, 'msg' => 'id参数错误' ));
            }
            $arr    = $this->db->from ("points_goods")
                               ->select ('id,cate_id,thumb,image,name,points,type')
                               ->where ("status = 1 and receive_num < num and id = $id")
                               ->getRow ();
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
        }
        $this->json_output (array( 'err' => 0, 'info' => $arr ));
    }

    /*
     * 塑料圈app之退货规定
     */
    public function returnRule ()
    {
        if ($_GET) {
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
            $this->json_output (array( 'err' => 0, 'rule' => $v['rule'] ));
        }
    }

    /*
     * 塑料圈app之积分规定
     */
    public function pointsRule ()
    {
        if ($_GET) {
            $this->is_ajax = true;
            $this->checkAccount ();
            $salePoints = intval (M ('system:setting')->get ('points')['points']['sale']);
            $purPoints  = intval (M ('system:setting')->get ('points')['points']['pur']);
            $rule       = '';
            $rule .= '<span>1. 每日发布报价/求购一条，增加'.$salePoints.'/'.$purPoints.'积分</span><br />';
            $rule .= '<span>2. 与我的塑料网成交后自动累计积分，买的多送的多</span><br />';
            $rule .= '<span>3. 积分商城积分兑换的商品不但免费还免运费</span>';
            $this->json_output (array( 'err' => 0, 'rule' => $rule ));
        }
    }

    /*
     * 获取兑换时间段
     * @param $type   0是通讯录置顶 1供求信息置顶
     */
    public function exchangeTime ($type = 1)
    {
        $this->is_ajax = true;
        $arr           = $this->db->model ('corn')
                                  ->where ("type = $type and status_e = 0 ")
                                  ->select ('user_id,exe_time_s,exe_time_e,purchase')
                                  ->getAll ();

        return $arr;
    }

    /*
     * 兑换置顶
     */
    public function exchangeSupplyOrDemand ()
    {
        $this->is_ajax = true;
        if ($_GET) {
            $type    = sget ('type', 'i');//  0 实物  1 通讯录 2 供求信息
            $user_id = $this->checkAccount ();
            if (!in_array ($type, array( 0, 1, 2 ))) {
                $this->json_output (array( 'err' => 11, 'msg' => 'type参数错误' ));
            }
            $goods_id = sget ('goods_id', 'i');   //所需要的商品的id
            if ($goods_id < 1) {
                $this->json_output (array( 'err' => 12, 'msg' => 'goods_id参数错误' ));
            }
            $pointsModel = M ("qapp:pointsBill");
            $pointsModel->startTrans ();
            try {
                $pointsRow = $pointsModel->from ("points_goods")
                                         ->select ("points,name,cate_id")
                                         ->where ("status = 1 and receive_num < num and id = $goods_id")
                                         ->getRow ();
                $points    = (int)$pointsRow['points'];
                if ($pointsRow['cate_id'] == 7) {
                    throw new Exception("系统错误 pubpur:116");
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
                    if (!in_array ($stime, array( 0, 10, 20, 30, 40, 50 ))) {
                        throw new Exception("系统错误 pubpur:103");
                    }
                    if ($hour == date ("H") && $stime < date ("i")) {
                        throw new Exception("系统错误 pubpur:115");
                    }
                    $time = sget ('time', 'i', 60);//所需要兑换的时长
                    if (!$time) {
                        throw new Exception("系统错误 pubpur:104");
                    }
                    if (!in_array ($time, array( 10, 30, 60 ))) {
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
                    if ($goods_id < 10) {
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
                    }
                }
                if (in_array ($type, array( 1, 2 ))) {
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
                    if (!$this->db->model ('corn')
                                  ->add ($sqlArray)
                    ) {
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
                switch ($code) {
                    case 100:
                        $this->json_output (array( 'err' => 100, 'msg' => '积分不足,请多努力!' ));
                        break;
                    case 101:
                        $this->json_output (array( 'err' => 101, 'msg' => '系统错误' ));
                        break;
                    case 102:
                        $this->json_output (array( 'err' => 102, 'msg' => '小时参数错误' ));
                        break;
                    case 103:
                        $this->json_output (array( 'err' => 103, 'msg' => '分钟参数错误' ));
                        break;
                    case 104:
                        $this->json_output (array( 'err' => 104, 'msg' => '兑换时长参数不正确' ));
                        break;
                    case 105:
                        $this->json_output (array( 'err' => 105, 'msg' => 'purchase_id参数错误' ));
                        break;
                    case 106:
                        $this->json_output (array( 'err' => 106, 'msg' => '选择时间段错误,已被人预定过了' ));
                        break;
                    case 107:
                        $this->json_output (array( 'err' => 107, 'msg' => '年份参数错误' ));
                        break;
                    case 108:
                        $this->json_output (array( 'err' => 108, 'msg' => '月份参数错误' ));
                        break;
                    case 109:
                        $this->json_output (array( 'err' => 109, 'msg' => '日子参数错误,只能选今天和以后7天的，不能大于该月的日子' ));
                        break;
                    case 111:
                        $this->json_output (array( 'err' => 111, 'msg' => '兑换失败' ));
                        break;
                    case 112:
                        $this->json_output (array( 'err' => 112, 'msg' => '收货人错误' ));
                        break;
                    case 113:
                        $this->json_output (array( 'err' => 113, 'msg' => '手机号错误' ));
                        break;
                    case 114:
                        $this->json_output (array( 'err' => 114, 'msg' => '收货地址错误' ));
                        break;
                    case 115:
                        $this->json_output (array( 'err' => 115, 'msg' => '白驹过隙，时间一去不复返，不能停留在逝去的时光里' ));
                        break;
                    case 116:
                        $this->json_output (array( 'err' => 116, 'msg' => 'App内暂时不进行现金兑换，请于微信内搜索《塑料圈》进行兑换' ));
                        break;
                    case 117:
                        $this->json_output (array( 'err' => 117, 'msg' => '当前时间段已过，请提前一小时兑换' ));
                        break;
                    case 118:
                        $this->json_output (array( 'err' => 118, 'msg' => '名额已完,下次请早' ));
                        break;
                    default:
                        $this->json_output (array( 'err' => 999, 'msg' => '未知错误编码' ));
                }
            }
            $pointsModel->commit ();
            $this->success ('兑换成功');
        }
        $this->json_output (array( 'err' => 10, 'msg' => '提交方式错误' ));
    }

    //生产订单号
    protected function buildOrderId ()
    {
        return date ('Ymd').substr (implode (NULL, array_map ('ord', str_split (substr (uniqid (), 7, 13), 1))), 0, 8);
    }


    //判断积分是否足够
    protected function checkSupply ($num = null, $outType = 0)
    {
        $this->is_ajax = true;
        $user_id       = $this->checkAccount ();
        if ($outType == 0) {
            $goods_id  = sget ('goods_id', 'i');
            $pointsRow = M ('public:common')
                ->from ("points_goods")
                ->select ("points,name,cate_id")
                ->where ("status = 1 and receive_num < num and id = $goods_id")
                ->getRow ();
            $num       = (int)$pointsRow['points'];
            if ($pointsRow['cate_id'] == 7) {
                $this->json_output (array( 'err' => 116, 'msg' => 'App内暂时不进行现金兑换，请于微信内搜索《塑料圈》进行兑换' ));
            }
            $user = M ('public:common')->model ('contact_info');
            if ($info = $user->where ("user_id=$user_id")
                             ->getRow ()
            ) {
                if (($info['quan_points'] - $num) < 0) {
                    $this->json_output (array( 'err' => 100, 'msg' => '积分不足,请多努力!' ));
                }
                $this->json_output (array( 'err' => 0, 'msg' => '积分足够兑换' ));
            }
        } elseif ($outType == 1) {
            $num  = (int)$num;
            $user = M ('public:common')->model ('contact_info');
            if ($info = $user->where ("user_id=$user_id")
                             ->getRow ()
            ) {
                if (($info['quan_points'] - $num) < 0) {
                    return false;
                }

                return true;
            }
        }
    }

    /**
     * 积分记录
     */
    public function pointSupplyList ()
    {
        $this->is_ajax = true;
        if ($_GET) {
            $user_id = $this->checkAccount ();
            $page    = sget ('page', 'i', 1);
            $size    = sget ('size', 'i', 10);
            $data    = M ("qapp:pointsBill")
                ->select ('id,addtime,type,points')
                ->where ("uid = $user_id and type in (3,5,6)")
                ->order ('id desc')
                ->page ($page, $size)
                ->getPage ();
            if (empty($data['data']) && $page == 1) {
                $this->json_output (array( 'err' => 2, 'msg' => '没有相关数据' ));
            }
            $this->_checkLastPage ($data['count'], $size, $page);
            //我的积分
            $points = M ('qapp:pointsBill')->getUerPoints ($user_id);
            $points = empty($points) ? 0 : $points;
            foreach ($data['data'] as $k => &$v) {
                $v['typename'] = $this->pointsType[$v['type']];
                $v['addtime']  = $this->checkTime ($v['addtime']);
                unset($v['type']);
            }
            $this->json_output (array( 'err' => 0, 'data' => $data['data'], 'pointsAll' => $points ));
        }
    }

    /*
     * 兑换记录
     */
    public function exchangeList ()
    {
        $this->is_ajax = true;
        if ($_GET) {
            $user_id    = $this->checkAccount ();
            $page       = sget ('page', 'i', 1);
            $size       = sget ('size', 'i', 10);
            $orderModel = M ('points:pointsOrder');
            $data       = $orderModel->where ("uid = $user_id")
                                     ->order ("id desc")
                                     ->page ($page, $size)
                                     ->getPage ();
            if (empty($data['data']) && $page == 1) {
                $this->json_output (array( 'err' => 2, 'msg' => '没有相关数据' ));
            }
            $this->_checkLastPage ($data['count'], $size, $page);
            foreach ($data['data'] as $k => &$v) {
                $v['name']        = M ("points:pointsGoods")
                    ->where ("goods_id =".$v['goods_id'])
                    ->select ('name')
                    ->getOne ();
                $v['create_time'] = date ("Y-m-d H:i", $v['create_time']);
                $v['status']      = $this->orderStatus[$v['status']];
            }
            $this->json_output (array( 'err' => 0, 'info' => $data['data'] ));
        }

    }

    /*
     * 供求信息置顶之供求信息列表
     */
    public function supplyDemandList ()
    {
        $this->is_ajax = true;
        if ($_GET) {
            $user_id = $this->checkAccount ();
            $page    = sget ('page', 'i', 1);
            $size    = sget ('size', 'i', 5);
            $type    = sget ('type', 'i');//1采购 2报价
            $where   = " pur.sync in (6,8) and pur.user_id=$user_id ";
            $data    = $this->db->select ('pur.id,pur.p_id,pur.user_id,pro.model,pur.unit_price,pur.store_house,fa.f_name,pur.type,pur.content,pur.input_time')
                                ->from ('purchase pur')
                                ->leftjoin ('product pro', 'pur.p_id=pro.id')
                                ->leftjoin ('factory fa', 'pro.f_id=fa.fid')
                                ->page ($page, $size)
                                ->where ($where)
                                ->order ('pur.input_time desc')
                                ->getPage ();
            if (empty($data['data']) && $page == 1) {
                $this->json_output (array( 'err' => 2, 'msg' => '没有相关的数据' ));
            }
            $this->_checkLastPage ($data['count'], $size, $page);
            $arr = array();
            foreach ($data['data'] as $k => $v) {
                $arr[$k]['content'] = '';
                $arr[$k]['content'] .= $this->checkTime ($v['input_time']);
                if (!empty($v['model'])) {
                    $arr[$k]['content'] .= '牌号:'.$v['model'];
                }
                if (!empty($v['store_house'])) {
                    $arr[$k]['content'] .= '厂家:'.$v['store_house'];
                }
                if (!empty($v['unit_price'])) {
                    $arr[$k]['content'] .= '价格:'.$v['unit_price'];
                }
                if (!empty($v['f_name'])) {
                    $arr[$k]['content'] .= '交货地:'.$v['f_name'];
                }
                if (!empty($v['content'])) {
                    $arr[$k]['content'] .= $v['content'];
                }
                $arr[$k]['p_id']    = $v['id'];
                $arr[$k]['content'] = mb_substr (strip_tags ($arr[$k]['content']), 0, 50, 'utf-8').'...';
            }
            $this->json_output (array( 'err' => 0, 'data' => $arr ));
        }
    }


    public function test4 ()
    {
        p (M ('system:setting')->get ('points'));
    }



    //    /*
    //     * app更新消息
    //     */
    //    public function updateApp(){
    //        $this->is_ajax = true;
    //        if ($_GET) {
    //            $user_id = $this->checkAccount();
    //            $version=sget('version','s');
    //            $chanel=sget('chanel',i);//0 anzhuo 1 apple
    //            $result=array(
    //                'version'=>'1.0',
    //                'description'=>'目前已经更新了卡屏功能',
    //                'url'=>'http://localhost/index.php/API/Server/download',
    //            );
    //            switch ($code)
    //            {
    //                case 100:
    //
    //                    break;
    //                case 101:
    //                    $this->json_output(array('err'=>101,'msg'=>'系统错误'));
    //                    break;
    //                case 115:
    //                    $this->json_output(array('err'=>115,'msg'=>'白驹过隙，时间一去不复返，不能停留在逝去的时光里'));
    //                    break;
    //                default:
    //                    $this->json_output(array('err'=>999,'msg'=>'未知错误编码'));
    //            }
    //            $this->json_output(array('err'=>1,'info'=>$result));
    //
    //        }
    //    }
    //
    //    public function janfly(){
    //        $cate_id=sget('cate_id','i');
    //        $callback=sget('callback','s');
    //        $arr=array('err'=>0,'cate_id'=>$cate_id);
    //        echo $callback.'('.json_encode($arr).')';
    //    }


}