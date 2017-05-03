<?php
/**
 * Created by PhpStorm.
 * User: sick
 * Date: 17-5-3
 * Time: 上午10:33
 */
class friendAction extends null2Action
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


}