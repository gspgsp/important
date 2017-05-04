<?php
/**
 * Created by PhpStorm.
 * User: sick
 * Date: 17-5-3
 * Time: 上午10:33
 */
class baseAction extends null2Action
{
    protected $db, $err, $cates, $catesAll, $pointsType, $orderStatus, $rePoints, $points, $newsSubscribe, $newsSubscribeDefault, $cache, $randomTime, $randomMdTime, $shareType;

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
            if (!strstr ($thumb['thumb'], 'http')) {
                if (empty($thumb['thumb'])||$thumb['thumb']=="16/09/02/logos.jpg")
                {
                    if(empty($thumb['sex']))
                    {
                        $thumb['thumb'] = "http://statics.myplas.com/myapp/img/male.jpg";
                    }else{
                        $thumb['thumb'] = "http://statics.myplas.com/myapp/img/female.jpg";
                    }
                } else {
                    $thumb['thumb'] = FILE_URL . "/upload/" . $thumb['thumb'];
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
}