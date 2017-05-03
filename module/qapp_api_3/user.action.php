<?php
/**
 * Created by PhpStorm.
 * User: sick
 * Date: 17-5-3
 * Time: 上午10:28
 */
class userAction extends null2Action
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
            16 => '现金充值',
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
     * @api {get} /qapp_api_3/user/init 初始化页面
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


    /**
     * @api {post} /api/api1_2/register 注册
     * @apiName  register
     * @apiGroup User
     *
     * @apiParam {String} mobile       手机号.
     * @apiParam {String} password     密码.
     * @apiParam {String} code         验证码
     * @apiParam {Number} c_name       公司名称
     * @apiParam {Number} name         姓名
     * @apiParam {Number} region       区域
     * @apiParam {Number} chanel       区域
     * @apiParam {Number} region       区域
     * @apiParam {Number} region       区域
     * @apiSuccess {String}  msg   描述
     * @apiSuccess {Boolean} err   错误码
     *
     * @apiSuccessExample Success-Response:
     *     {
     *      "err":0
     *      "msg":"注册成功"
     *      }
     *
     *
     */
    public function register ()
    {
        if ($_POST['mobile']) {
            //$cache = cache::startMemcache();
            $cache         = E ('RedisCluster', APP_LIB.'class');
            $this->is_ajax = true;
            $mobile        = sget ('mobile', 's');
            if (!$this->_chkmobile ($mobile)) {
                $this->error ($this->err);
            }
            $password = sget ('password', 's');
            $password = $this->clearStr ($password);
            if (strlen ($password) < 6) {
                $this->error ('密码格式不正确,至少6位');
            }
            $mcode = sget ('code', 's');
            if (empty($mcode)) {
                $this->error ('请获取验证码');
            }
            $user_model   = M ('system:sysUser');
            $salt         = randstr (6);
            $passwordSalt = $user_model->genPassword ($password.$salt);
            $cache->set ($mobile.'check_reg_ok', true, 1200);
            $cache->set ($mobile.'password', $passwordSalt, 1200);
            $cache->set ($mobile.'salt', $salt, 1200);
            if (!$cache->get ($mobile.'check_reg_ok')) {
                $this->error ('令牌已过期，请重新注册');
            }
            $name = sget ('name', 's');
            if (mb_strlen ($name, 'UTF8') < 2) {
                $this->error ('请输入二位字以上的姓名');
            }
            //            if (!sget ('qq', 's', '')) {
            //                $this->error ('请输入qq号码');
            //            }
            //            if (!is_qq (sget ('qq', 's'))) {
            //                $this->error ('请输入有效的qq号码');
            //            }
            $c_name    = sget ('c_name', 's');
            $region    = sget ('region', 's', '');
            $chanel    = (int)sget ('chanel', 'i', 6);
            $quan_type = sget ('quan_type', 'i');//sget()函数要理解一下，里面有个empty函数
            //$origin = sget('origin','a');
            $ctype = sget ('c_type', 'i');  //'客户类型(1 工厂(买家)、2 贸易(卖家)、3 工贸一体(买卖一体))4物流',
            //$_model = sget('model','a');  //牌号
            //            if(empty($origin)||count($origin)!=2) $this->_errCode(6);
            if (empty($ctype)) {
                $this->_errCode (6);
            }
            if (!in_array ($ctype, array(
                '1',
                '2',
                '3',
                '4',
            ))
            ) {
                $this->_errCode (6);
            }
            $name   = $this->clearStr ($name);
            $c_name = $this->clearStr ($c_name);
            if (mb_strlen ($c_name, 'UTF8') < 5) {
                $this->error ('请输入完整的公司名');
            }
            $regison = $this->clearStr ($region);
            if (!$c_name) {
                $this->error ('请输入公司名称');
            }
            $result = M ('system:sysSMS')->qAppChkDynamicCode ($mobile, $mcode);
            if ($result['err'] > 0) {
                $this->error ($result['msg']);
            }

            $china_area = M ('system:region')->get_system_region_by_phone ($mobile);

            //if(empty($_model)||count($_model)>10) $this->_errCode(6);
            //$_model = implode(',',$_model);

            $cus_model  = $this->db->model ('customer');
            $customer   = $cus_model->select ('c_id')->where ("c_name='$c_name'")->getOne ();//获取公司的id
            $user_model = M ('system:sysUser');
            $user_model->startTrans ();
            try {
                $c_id = $customer;
                if (!$customer) {
                    $_customer = array(
                        'c_name'           => $c_name,
                        'chanel'           => $chanel,
                        'input_time'       => CORE_TIME,
                        'customer_manager' => 859,
                        //交易员
                        //'origin'=>implode('|',$origin),
                        //'need_product'=>$_model,
                        'type'             => $ctype,
                        'china_area'       => $china_area,
                        'quan_type'        => $quan_type,
                    );
                    if (!$cus_model->add ($_customer)) {
                        throw new Exception("系统错误 reg:101");
                    }
                    $c_id = $cus_model->getLastID ();
                }
                //查看是否为老用户
                $old_user      = $this->db->model ('customer_contact')->select ('user_id,parent_mobile')
                                          ->where ("mobile=".$mobile)->getRow ();
                $salt          = $cache->get ($mobile.'salt');
                $password      = $cache->get ($mobile.'password');
                $parent_mobile = sget ('parent_mobile', 's');
                if ($mobile == $parent_mobile) {
                    throw new Exception("引荐人不能是自己，先有蛋，还是先有鸡，这是个问题");
                }
                if (empty($parent_mobile) || $parent_mobile == 'undefined') {
                    $parent_mobile = '';
                } else {
                    if (!$this->db->from ('customer_contact')->select ('user_id')->where ('mobile='.$parent_mobile)
                                  ->getOne ()
                    ) {
                        throw new Exception("引荐人错误，请重新选择引荐人，或者独自踏上征途。");
                    }

                }
                /*
                 * 其实这一步可以省了，因为现在的已经注册过的，不能重新注册了，只能找回密码了
                 * 现在这一步，还是不能省，因为现在成哥又要能重新注册了，
                 * 主要原因是公司名不能删除
                 * 现在还有一个bug，
                 * 也不能算是bug，
                 * 一切都是以手机号来区分的
                 * 而且没有加密，会出现问题的
                 * 暂时没有时间，以后会修改吧
				 *2017-4-19 09:27:54
                 * 还是没有关闭重新注册
                 *
                 * 重新注册，会员状态清零
                 */
                if ($old_user['user_id']) {
                    $_user = array(
                        'salt'          => $salt,
                        'password'      => $password,
                        'name'          => $name,
                        'qq'            => sget ('qq', 's', ''),
                        'parent_mobile' => empty($old_user['parent_mobile']) ? '' : $old_user['parent_mobile'],
                        'c_id'          => $c_id,
                        'sex'           => sget ('sex', 'i', 0),
                        'chanel'        => $chanel,
                        'update_time'   => CORE_TIME,
                        'is_trial'      => 0,
                        //公海状态清零
                        'quan_type'     => $quan_type,
                    );
                    if (!$user_model->where ("mobile=".$mobile)->update ($_user)) {
                        throw new Exception("系统错误 reg:105");
                    }

                    $stype = 2;//老用户
                    //老用户也要检测contact_info 表的信息,防止后台乱添加用户少了信息
                    $mobile_area = getCityByMobile ($mobile);
                    $_info       = array(
                        'user_id'         => $old_user['user_id'],
                        'reg_ip'          => get_ip (),
                        'reg_time'        => CORE_TIME,
                        'thumbcard'       => '',
                        'reg_chanel'      => $chanel,
                        'region'          => empty($region) ? '' : $region,
                        'mobile_province' => empty($mobile_area['province']) ? '' : $mobile_area['province'],
                        'mobile_area'     => empty($mobile_area['city']) ? '' : $mobile_area['city'],
                        'quan_type'       => $quan_type,
                    );
                    if (!$this->db->model ('contact_info')->select ('user_id')->where ("user_id={$old_user['user_id']}")
                                  ->getOne ()
                    ) {
                        if (!$this->db->model ('contact_info')->add ($_info)) {
                            throw new Exception("系统错误 reg:103");
                        }
                        $stype = 1; //新用户
                    }
                } else {
                    $is_default = empty($customer) ? 1 : 0;
                    $_user      = array(
                        'mobile'           => $mobile,
                        'salt'             => $salt,
                        'password'         => $password,
                        'name'             => $name,
                        'qq'               => sget ('qq', 's'),
                        'c_id'             => $c_id,
                        'sex'              => sget ('sex', 'i', 0),
                        'customer_manager' => 859,
                        //交易员
                        'input_time'       => CORE_TIME,
                        'is_default'       => $is_default,
                        'parent_mobile'    => $parent_mobile,
                        'chanel'           => $chanel,
                        'quan_type'        => $quan_type,
                    );
                    if (!$user_model->add ($_user)) {
                        throw new Exception("系统错误 reg:102");
                    }
                    $user_id = $user_model->getLastID ();
                    //throw new Exception('test');
                    //直接关注和加积分
                    if (!empty($_user['parent_mobile'])) {
                        $focused_id = $this->db->model ('customer_contact')->where ("mobile=".$_user['parent_mobile'])
                                               ->select ('user_id')->getOne ();
                        if (!M ("plasticzone:plasticAttention")->getAttention ($user_id, $focused_id)) {
                            throw new Exception("系统错误 reg:111");
                        }
                        //                        if (!M ("qapp:pointsBill")->addPoints ($this->rePoints, $focused_id, 12)) {
                        //                            //var_dump($user_id);var_dump($focused_id);var_dump($_user['parent_mobile']);showTrace();
                        //                            throw new Exception("系统错误 reg:112");
                        //                        }//引荐加积分
                        //                        if (!M ("qapp:pointsBill")->addPoints ($this->points['register'], $user_id, 7)) {
                        //                            //var_dump($user_id);var_dump($focused_id);var_dump($_user['parent_mobile']);showTrace();
                        //                            throw new Exception("系统错误 reg:112");
                        //                        }//注册加积分
                    }
                    $mobile_area = getCityByMobile ($mobile);
                    $_info       = array(
                        'user_id'         => $user_id,
                        'reg_ip'          => get_ip (),
                        'reg_time'        => CORE_TIME,
                        'thumbcard'       => '',
                        'reg_chanel'      => $chanel,
                        'region'          => empty($region) ? '' : $region,
                        'mobile_province' => empty($mobile_area['province']) ? '' : $mobile_area['province'],
                        'mobile_area'     => empty($mobile_area['city']) ? '' : $mobile_area['city'],
                        'quan_type'       => $quan_type,
                    );
                    if (!$this->db->model ('contact_info')->add ($_info)) {
                        throw new Exception("系统错误 reg:103");
                    }
                    //这一步少不了，$c_id之前不知道
                    if (!$customer) {
                        if (!$this->db->model ('customer')->where ("c_id=$c_id")->update ("contact_id=".$user_id)) {
                            throw new Exception("系统错误 reg:104");
                        }
                    }
                    //新增用户默认排序最前
                    $this->db->model ('weixin_ranking')->add (array(
                        'user_id' => $user_id,
                        'pm'      => 0,
                        'rownum'  => 0,
                    ));
                }
            } catch (Exception $e) {
                $user_model->rollback ();
                $this->error ($e->getMessage ());
            }
            $user_model->commit ();
            $cache->delete ($mobile.'password');
            $cache->delete ($mobile.'salt');
            $cache->delete ($mobile.'check_reg_ok');
            //$this->success ('注册成功');
            $this->json_output (array(
                'err'   => 0,
                'msg'   => '注册成功',
                'stype' => $stype,
            ));

        }
        $this->_errCode (6);
    }


    /**
     * @api {post} /qapp_api_3/api1_2/finfMyPwd 找回密码
     * @apiName  finfMyPwd
     * @apiGroup User
     *
     * @apiSuccess {String}  msg   描述
     * @apiSuccess {Boolean} err   错误码
     *
     * @apiSuccessExample Success-Response:
     *      {
     *      "err":0
     *      "msg":"密码重置成功"
     *      }
     *
     *
     */
    public function finfMyPwd ()
    {
        if ($_POST['mobile']) {
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
            $result = $user_model->where ('mobile='.$mobile)->update (array(
                'password' => $passwordSalt,
                'salt'     => $salt,
            ));
            //$this->getDBError();
            if (!$result) {
                $this->error ('密码重置失败，请稍后重试');
            } else {
                $this->success ('密码重置成功');
            }
        }
        $this->_errCode (6);
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
        if ($_POST['mobile']) {
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

            $this->success ('发送成功');
        }
        $this->_errCode (6);
    }

    //登录
    //现在默认的login的都是塑料圈web的方式，但是会出现问题的
    //区分不了东西了，其实没什么大不了，app每天的登录记录是出现在取首页数据的时候
    public function login ()
    {
        if ($_POST['username']) {
            $this->is_ajax = true;
            $username      = sget ('username', 's');
            $password      = sget ('password', 's');
            $chanel        = (int)sget ('chanel', 's', 6);
            $username      = $this->clearStr ($username);
            $password      = $this->clearStr ($password);
            if (!$this->_chkmobile ($username, 1)) {
                $this->error ($this->err);
            } elseif (strlen ($password) < 6) {
                $this->error ('密码长度应该大于6个字符');
            }
            $result = M ('user:passport')->login ($username, $password, $chanel);
            if ($result['err'] > 0) {
                $this->error ($result['msg']);
            } else {
                $token = M ('qapp:appToken')->insert ($result['user']['user_id'], $result['user']);
                //$cache=cache::startMemcache();
                //$this->success('登录成功');
                if (!M ("qapp:pointsBill")->select ('id')
                                          ->where ("addtime >".strtotime (date ("Y-m-d"))." and type=2 and uid={$result['user']['user_id']}")
                                          ->order ("id desc")->getOne ()
                ) {
                    $user_id = $result['user']['user_id'];
                    $tmp     = M ('public:common')->model ('contact_info')->where ("user_id=$user_id")->getRow ();
                    if (empty($tmp)) {
                        $this->json_output (array(
                            'err' => 101,
                            'msg' => '注册信息不完整，请联系客服400-6129-965或重新注册',
                        ));
                    }
                    $spoints = $this->points['login'];
                    //                    if (!$arr = M ("qapp:pointsBill")->addPoints ($spoints, $user_id, 2)) {
                    //                        $this->json_output (array( 'err' => 101, 'msg' => '系统错误' ));
                    //                    }
                }
                $this->json_output (array(
                    'err'       => 0,
                    'msg'       => '登录成功',
                    'dataToken' => $token,
                    'user_id'   => $result['user']['user_id'],
                ));
            }
        }
        $this->_errCode (6);
    }


    //退出登录
    public function logOut ()
    {
        if ($_POST['token']) {
            $this->checkAccount ();
            $this->is_ajax = true;
            $token         = sget ('token', 's');
            $cache         = cache::startMemcache ();
            $cache->delete ($token);
            M ('qapp:appToken')->destory ($token);
            $this->json_output (array(
                'err' => 0,
                'msg' => '退出成功',
            ));
        }
        $this->_errCode (6);
    }
}
