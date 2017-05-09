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


    //生产订单号
    protected function buildOrderId ()
    {
        return date ('Ymd').substr (implode (null, array_map ('ord', str_split (substr (uniqid (), 7, 13), 1))), 0, 8);
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