<?php

/**
 * Created by PhpStorm.
 * User: sick
 * Date: 17-5-3
 * Time: 上午10:33
 */
class toutiaoAction extends baseAction
{

    /**
     * @api {post} /qapi_3/toutiao/topLine 塑料头条
     * @apiVersion 3.1.0
     * @apiName  topLine
     * @apiGroup toutiao
     * @apiUse UAHeader
     *
     * @apiSuccess {String}  msg   描述
     * @apiSuccess {Boolean} err   错误码
     * @apiSuccess {Array} info   信息
     *
     *
     * @apiSuccessExample Success-Response:
     *     {
     *      "err":0
     *      "info":"xxxxxx"
     *      }
     *
     *
     */
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
     * @api {post} /qapi_3/toutiao/getCateList 塑料头条-分类列表
     * @apiVersion 3.1.0
     * @apiName  getCateList
     * @apiGroup toutiao
     * @apiUse UAHeader
     *
     * @apiParam {String} token       token
     * @apiParam {Number} page       页码
     * @apiParam {Number} size         条数
     * @apiParam {Number} cate_id       分类id
     * @apiSuccess {String}  msg   描述
     * @apiSuccess {Boolean} err   错误码
     * @apiSuccess {Array} info   信息
     *
     * @apiSuccessExample Success-Response:
     *    {
     * "err": 0,
     * "info": [
     * {
     * "id": "29334",
     * "title": "上游早报：原油及PE单体4月27日收盘价格",
     * "description": "上游早报：原油及PE单体4月19日收盘价格...",
     * "cate_id": "2",
     * "author": "",
     * "input_time": "2017-04-28",
     * "type": "PE",
     * "pv": "711",
     * "cate_name": "塑料上游"
     * },
     * {
     * "id": "29332",
     * "title": "上游早报：原油及PP单体4月27日收盘价格",
     * "description": "上游早报：原油及PE单体4月19日收盘价格...",
     * "cate_id": "2",
     * "author": "",
     * "input_time": "2017-04-28",
     * "type": "PP",
     * "pv": "738",
     * "cate_name": "塑料上游"
     * },
     * {
     * "id": "29327",
     * "title": "上游早报：原油及PVC单体4月27日收盘价格",
     * "description": "上游早报：原油及PVC单体4月27日收盘价格...",
     * "cate_id": "2",
     * "author": "",
     * "input_time": "2017-04-28",
     * "type": "PVC",
     * "pv": "757",
     * "cate_name": "塑料上游"
     * },
     * {
     * "id": "29318",
     * "title": "4月27日国际原油价格下跌",
     * "description": "WTI原油期货收跌0.65美元，跌幅1.31%，报48.97美元/桶。布伦特原油期货收跌0.38美元...",
     * "cate_id": "2",
     * "author": "",
     * "input_time": "2017-04-28",
     * "type": "PP",
     * "pv": "746",
     * "cate_name": "塑料上游"
     * },
     * {
     * "id": "29079",
     * "title": "上游早报：原油及PVC单体4月26日收盘价格",
     * "description": "上游早报：原油及PVC单体4月26日收盘价格...",
     * "cate_id": "2",
     * "author": "",
     * "input_time": "2017-04-27",
     * "type": "PVC",
     * "pv": "858",
     * "cate_name": "塑料上游"
     * },
     * {
     * "id": "29077",
     * "title": "上游早报：原油及PP单体4月26日收盘价格",
     * "description": "上游早报：原油及PE单体4月19日收盘价格...",
     * "cate_id": "2",
     * "author": "",
     * "input_time": "2017-04-27",
     * "type": "PP",
     * "pv": "765",
     * "cate_name": "塑料上游"
     * },
     * {
     * "id": "29076",
     * "title": "上游早报：原油及PE单体4月26日收盘价格",
     * "description": "上游早报：原油及PE单体4月19日收盘价格...",
     * "cate_id": "2",
     * "author": "",
     * "input_time": "2017-04-27",
     * "type": "PE",
     * "pv": "740",
     * "cate_name": "塑料上游"
     * },
     * {
     * "id": "29064",
     * "title": "4月26日国际原油价格涨跌不一",
     * "description": "WTI 6月原油期货收涨0.06美元，涨幅0.12%，报49.62美元/桶。\r\n布伦特6月原油期货收...",
     * "cate_id": "2",
     * "author": "",
     * "input_time": "2017-04-27",
     * "type": "PP",
     * "pv": "890",
     * "cate_name": "塑料上游"
     * },
     * {
     * "id": "28823",
     * "title": "上游早报：原油及PE单体4月25日收盘价格",
     * "description": "上游早报：原油及PE单体4月19日收盘价格...",
     * "cate_id": "2",
     * "author": "",
     * "input_time": "2017-04-26",
     * "type": "PE",
     * "pv": "1336",
     * "cate_name": "塑料上游"
     * },
     * {
     * "id": "28822",
     * "title": "上游早报：原油及PVC单体4月25日收盘价格",
     * "description": "上游早报：原油及PVC单体4月25日收盘价格...",
     * "cate_id": "2",
     * "author": "",
     * "input_time": "2017-04-26",
     * "type": "PVC",
     * "pv": "1247",
     * "cate_name": "塑料上游"
     * }
     * ]
     * }
     *
     *
     */
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
                if (!$data = $cache->get ('qcateListInfo'.$page.'_'.$cate_id)) {
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
                    $cache->set ('qcateListInfo'.$page.'_'.$cate_id, $data, 8);
                }
                $stmp_num = $cache->get ('qcateListInfoNum'.'_'.$cate_id) + 0;
                if (!empty($stmp_num) && $data['count'] > $stmp_num) {
                    $temp_show_msg = '更新了'.($data['count'] > $stmp_num + 10 ? ($data['count'] - $stmp_num + 5) : ($data['count'] - $stmp_num)).'条资讯';
                } else {
                    $temp_show_msg = '';
                }
                if ($page == 1) {
                    $cache->set ('qcateListInfoNum'.'_'.$cate_id, $data['count'], 5);
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
                $temp_show_msg = '';
            }
            $this->json_output (array(
                'err'      => 0,
                'info'     => $data['data'],
                'show_msg' => $temp_show_msg,
            ));
        }
        $this->_errCode (6);
    }

    /**
     * 塑料头条-详情列表
     * @api {post} /qapi_3/toutiao/getDetailInfo 塑料头条-详情列表
     * @apiVersion 3.1.0
     * @apiName  getDetailInfo
     * @apiGroup toutiao
     * @apiUse UAHeader
     *
     * @apiParam {String} token       token
     * @apiParam {Number} id       文件id
     * @apiSuccess {String}  msg   描述
     * @apiSuccess {Boolean} err   错误码
     * @apiSuccess {Array} info   信息
     *
     * @apiSuccessExample Success-Response:
     *     {
     *      "err":0
     *      "info":"xxx"
     *      }
     *
     *
     */
    public function getDetailInfo ()
    {
        if ($_POST) {
            $this->is_ajax = true;
            $id            = sget ('id', i);
            $this->checkAccount (0);
            if (empty($id)) {
                $this->error (array('err' => 5, 'msg' => '参数错误，请稍后再试'));
            }
            $cache = cache::startMemcache ();
            if (!$data = $cache->get ('qcateDetailInfo'.'_'.$id)) {
                $data = $this->db->model ('news_content')
                                 ->where ('id='.$id)
                                 ->getRow ();
                if (empty($data)) {
                    $this->_errCode (2);
                }
                /**
                 * 九个频道，每个推荐一条
                 */
                foreach ($this->cates as $key => $row) {
                    $_tmp = M ("qapp:news")->getNewsOrderByPv ('', $key, '', 1, 1)[0];

                    //                    if(empty($_tmp)) $_tmp = M("qapp:news")->getNewsOrderByPv('', $key, '', 1, 3)[0];
                    if (empty($_tmp)) {
                        $_tmp = M ("qapp:news")->getNewsOrderByPv ('', $key, '', 1, 7)[0];
                    }
                    if (empty($_tmp)) {
                        $_tmp = M ("qapp:news")->getNewsOrderByPv ('', $key, '', 1, 0)[0];
                    }
                    if (empty($_tmp)) {
                        continue;
                    }
                    $_tmp['cate_name']  = $this->catesAll[$_tmp['cate_id']];
                    $_tmp['input_time'] = $this->checkTime ($_tmp['input_time']);
                    if ($_tmp['type'] == 'public' || $_tmp['type'] == 'vip') {
                        $_tmp['type'] = 'pp';
                    }
                    $_tmp['type'] = strtoupper ($_tmp['type']);
                    if (!empty($_tmp)) {
                        $data['subscribe'][] = $_tmp;
                    }
                }
                if (!empty($data['subscribe'])) {
                    $_idss = array();
                    foreach ($data['subscribe'] as $key1 => $row1) {
                        $_idss[$key1] = $row1['id'];
                    }
                }
                array_multisort ($_idss, SORT_DESC, $data['subscribe']);
                if (empty($data['subscribe'])) {
                    $data['subscribe'] = '';
                }
                $time               = $data['input_time'];
                $data['input_time'] = $this->checkTime ($data['input_time']);
                $data['author']     = empty($data['author']) ? '中晨' : $data['author'];
                $data['content']    = stripslashes ($data['content']);
                //$data['content'] = preg_replace("/style=.+?[*|\"]/i", '', $data['content']);
                //$str= preg_replace("/border="0"",'',$str);
                $data['content'] = preg_replace ("/width=.+?[*|\"]/i", '', $data['content']);
                //$data['content']=$this->cleanhtml(($data['content']),'<img><a><br /><table></table><tr></tr><td></td>');
                //取出右键导航分类名称
                $data['cate_name'] = $this->catesAll[$data['cate_id']];
                if ($data['type'] == 'public') {
                    $arr          = array('pe', 'pp', 'pvc');
                    $tmp          = array_rand ($arr, 1);
                    $data['type'] = 'pp';
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
            }
            //添加缓存之后，页面显示效果  阅读数+1
            $data['pv']      = $data['pv'] + 1;
            $data['true_pv'] = $data['true_pv'] + 1;
            if (time () % 3 == 0) {
                M ("qapp:news")->updateqAppPvByNum ($id, $data['pv'], $data['true_pv']);
            }
            $cache->set ('qcateDetailInfo'.'_'.$id, $data, 18);
            $this->json_output (array('err' => 0, 'info' => $data));
        }
        $this->_errCode (6);
    }


    /**
     * @api {post} /qapi_3/toutiao/getSelectCate 塑料头条-获取订阅频道
     * @apiVersion 3.1.0
     * @apiName  getSelectCate
     * @apiGroup toutiao
     * @apiUse UAHeader
     *
     * @apiParam {Number} type         // 1 set  2 get 3 getallCate
     * @apiParam {Number} cate_id     品类ID 逗号分隔
     * @apiParam {Number} prop_id     物性ID 逗号分隔      1=>'高压重包', 2=>'高压涂覆',3=>'高压吹膜', 4=>'低压拉丝', 5=>'低压注塑', 6=>'低压中空',  7=>'低压薄膜', 8=>'线型',9=>'管材',10=>'均聚拉丝',11=>'茂金属',12=>'共聚注塑',13=>'其他'
     *
     * @apiSuccess {String}  msg   描述
     * @apiSuccess {Boolean} err   错误码
     * @apiSuccess {Array} info   信息
     *
     * @apiSuccessExample Success-Response:
     *     {
     *      "err":0
     *      "info":"xxx"
     *      }
     *
     *
     */
    public function getSelectCate ()
    {
        if ($_POST) {
            $this->is_ajax = true;
            $user_id       = $this->checkAccount ();
            $type          = sget ('type', 'i');// 1 set  2 get 3 getallCate
            if ($type == 1) {
                if (($this->platform == 'ios' && $this->app_version < 22) || ($this->platform == 'android' && $this->app_version < 87)) {
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
                } else {
                    //新版本
                    $cate_id = sget ('cate_id', 's');
                    $prop_id = sget ('prop_id', 's');

                    $cate_id = explode (',', $cate_id);
                    $prop_id = explode (',', $prop_id);

                    if (empty($cate_id) || empty($prop_id)) {
                        $this->_errCode (6);
                    }

                    $res1 = M ("qapp:newsSubscribe")->setSubscribeByUserid ($user_id, $cate_id);
                    $res2 = M ("qapp:newsSubscribe")->setPhysicalSubscribeByUserid ($user_id, $prop_id);

                    if ($res1 && $res2) {
                        $this->json_output (array(
                            'err' => 0,
                            'msg' => '成功',
                        ));
                    }
                    $this->_errCode (101);
                }
            } elseif ($type == 2) {
                if (($this->platform == 'ios' && $this->app_version < 22) || ($this->platform == 'android' && $this->app_version < 87)) {

                    $tmp = M ("qapp:newsSubscribe")->getSubscribeByUserid ($user_id);

                    if (empty($tmp)) {
                        $tmp = $this->newsSubscribeDefault;
                    }
                } else {
                    $res1      = M ("qapp:newsSubscribe")->getSubscribeByUserid ($user_id);
                    $user_info = M ("user:userInfo")->getQappUserInfo ($user_id);

                    $res2 = explode (',', $user_info['physical_subscribe']);
                    if (empty($res1)) {
                        $res1 = $this->newsSubscribeDefault;
                    }
                    $unconcealed = array(
                        2,
                        20,
                        21,
                        22,
                    );
                    function convertInt($v)
                    {
                        return intval($v);
                    }

                    $res1 = array_map("convertInt",$res1);
                    $res2 = array_map("convertInt",$res2);

                    $tmp         = array(
                        'subscribe'   => array_values(array_filter ($res1)),
                        'unconcealed_subscribe' => $unconcealed,
                        'property'    => array_values (array_filter ($res2)),
                    );
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
     *
     * @api {post} /qapi_3/toutiao/getSubscribe 塑料头条-sss头条推荐
     * @apiVersion 3.1.0
     * @apiName  getSubscribe
     * @apiGroup toutiao
     * @apiUse UAHeader
     *
     * @apiParam {String} token       token
     * @apiParam {Number} page       页码
     * @apiParam {Number} size         条数
     * @apiParam {Number} subscribe       //1  关键字搜索   2  推荐
     * @apiParam {String} keywords       搜索字
     *
     *
     * @apiSuccess {String}  msg   描述
     * @apiSuccess {Boolean} err   错误码
     * @apiSuccess {Array} info   信息
     *
     * @apiSuccessExample Success-Response:
     *     {
     *      "err":0
     *      "data":"xxx"
     *      }
     *
     *
     */
    public function getSubscribe ()
    {
        if ($_POST) {
            $this->is_ajax = true;
            $user_id       = $this->checkAccount ();
            $cache         = E ('RedisClusterServer', APP_LIB.'class');
            header ("Content-type: text/html; charset=utf-8");
            //分页
            $page      = sget ('page', 'i', 1);
            $subscribe = sget ('subscribe', 'i'); //1  关键字搜索   2  推荐
            $page_size = 10;
            //获取搜索值
            $keywords = sget ('keywords', 's');
            $version  = sget ('version', 's');//版本号
            $keywords = $this->clearStr ($keywords);
            if ($keywords && $subscribe == 1) {

                /**
                 * 加搜索记录
                 * sort_field  'DEFAULT','INPUT_TIME','NC','SC','CC','ALL','AUTO','CONCERN','DEMANDORSUPPLY'
                 * 首页默认排序default  注册时间排序input_time 华北nc  华南sc  华中cc
                 * 全国站all  智能推荐auto  我的关注concern  我的供求 demandorsupply
                 *
                 *sort_order   'ALL','SALE','BUY','ASC','DESC'
                 *all 不分求购还是供给  sale 供给  buy 求购  asc 注册时间正序 desc  注册时间倒序
                 */
                $chanel = $this->checkPlatform ()['platform'];
                if (!empty($keywords)) {
                    $arr = array(
                        'user_id'    => $user_id,
                        'sort_field' => strtoupper ('news'),
                        'sort_order' => '',
                        'content'    => $keywords,
                        'version'    => $version,
                        'ip'         => get_ip (),
                        'chanel'     => $chanel,
                        'input_time' => CORE_TIME,
                    );
                    M ('qapp:plasticSearch')->add ($arr);
                }
                //Sphinx取出关键词搜索数据
                $sphinx = new SphinxClient;
                $sphinx->SetServer ('localhost', 9312);
                $sphinx->SetMatchMode (SPH_MATCH_BOOLEAN);
                $sphinx->SetSortMode (SPH_SORT_EXTENDED, "input_time DESC, @id DESC");
                $sphinx->setLimits (abs ($page - 1) * $page_size, $page_size, 1000);
                $result = $sphinx->query ("$keywords", 'news');
                $ids    = array_keys ($result['matches']);
                if (!empty($ids)) {
                    $data['data'] = M ('qapp:news')->search ($ids);
                }
                foreach ($data['data'] as &$row) {
                    $row['input_time']  = date ("Y-m-d", $row['input_time']);
                    $row['description'] = mb_substr (strip_tags ($row['description']), 0, 50, 'utf-8').'...';
                    if ($row['type'] == 'public') {
                        $data['type'] = 'pp';
                    }
                    $row['type'] = strtoupper ($row['type']);
                }
                if (empty($data['data']) && $page == 1) {
                    $this->json_output (array('err' => 2, 'msg' => '没有相关数据'));
                }
                $this->_checkLastPage ($data['count'], $page_size, $page);
                $this->json_output (array('err' => 0, 'data' => $data['data']));
            } elseif ($subscribe == 2) {
                //现在所有的推荐就是
                $tmp_new_cate_id = M ("qapp:newsSubscribe")->getSubscribeByUserid ($user_id);
                if (empty($tmp_new_cate_id)) {
                    $tmp_new_cate_id = $this->newsSubscribeDefault;
                }//默认频道
                if (count ($tmp_new_cate_id) >= $this->newsSubscribe) {
                    if ($this->getArrayChance ()) {
                        shuffle ($tmp_new_cate_id);
                        array_splice ($tmp_new_cate_id, $this->newsSubscribe);
                        $completeNum = 1;
                    } else {
                        shuffle ($tmp_new_cate_id);
                        array_splice ($tmp_new_cate_id, ($this->newsSubscribe - 1));
                        $completeNum = 2;
                    }
                } elseif (count ($tmp_new_cate_id) == 5 || count ($tmp_new_cate_id) == 4) {
                    if ($this->getArrayChance ()) {
                        $completeNum = 1;
                    } else {
                        $completeNum = 2;
                    }
                }
                $allNum = array();
                $today  = strtotime (date ("Y-m-d"));
                foreach ($tmp_new_cate_id as $value) {
                    $tmp = M ("qapp:news")->getCateSons ($value); //获取子分类
                    if (!empty($tmp)) {
                        if (count ($tmp) >= 1) {
                            shuffle ($tmp);
                            array_splice ($tmp, 1);
                        }
                        foreach ($tmp as $value1) {//添加缓存
                            if (!$all = unserialize ($this->cache->get ('qappsdfsNewId'.$value1))) {
                                $all = M ("public:common")
                                    ->model ("news_content")
                                    ->select ('id')
                                    ->where ("cate_id=$value1 and input_time>$today")
                                    ->order ("input_time desc")
                                    ->limit (6)
                                    ->getCol ();
                            }
                            $this->cache->set ('qappsdfsNewId'.$value1, serialize ($all), 1800);
                            if (count ($all) > $completeNum) {
                                shuffle ($all);
                                array_splice ($all, $completeNum);
                                array_merge ($allNum, $all);
                            }
                        }
                    } else {//添加缓存
                        if (!$all = unserialize ($this->cache->get ('qappsdfsNewId'.$value1))) {
                            $all = M ("public:common")
                                ->model ("news_content")
                                ->select ('id')
                                ->where ("cate_id=$value and input_time>$today")
                                ->order ("input_time desc")
                                ->limit (6)
                                ->getCol ();
                        }
                        $this->cache->set ('qappsdfsNewId'.$value1, serialize ($all), 1800);
                        if (count ($all) > $completeNum) {
                            shuffle ($all);
                            $stmp = array_rand ($all, $completeNum);
                            if (!is_array ($stmp)) {
                                $stmp = array($stmp);
                            }
                            foreach ($stmp as $row) {
                                $allNum[] = $all[$row];
                            }
                        }
                    }
                }
                if (count ($allNum) >= ($this->newsSubscribe)) {
                    shuffle ($allNum);
                    $stmp   = array_rand ($allNum, 6);
                    $new_id = array();
                    foreach ($stmp as $row) {
                        $new_id[] = $allNum[$row];
                    }
                } else {
                    //取出排行榜文章
                    if (!$chartsData = unserialize ($this->cache->get ('qappChartList'))) {
                        $chartsData = M ('qapp:news')->charts ('', $tmp_new_cate_id, '', 6, 1);
                        if (count ($chartsData) < 6) {
                            $chartsData = M ('qapp:news')->charts ('', $tmp_new_cate_id, '', 10, 3);
                        }
                        if (count ($chartsData) < 6) {
                            $chartsData = M ('qapp:news')->charts ('', $tmp_new_cate_id, '', 10, 0);
                        }
                        $this->cache->set ('qappChartList', serialize ($chartsData), 1800);
                    }
                    $tmp = array();
                    foreach ($chartsData as $row) {
                        $tmp[] = $row['id'];
                    }
                    shuffle ($tmp);
                    $left = $this->newsSubscribe - count ($allNum);
                    $stmp = array_rand ($tmp, $left);
                    if (!is_array ($stmp)) {
                        $stmp = array($stmp);
                    }
                    $atmp = array();
                    foreach ($stmp as $row) {
                        $atmp[] = $tmp[$row];
                    }
                    $new_id = array_merge ($allNum, $atmp);
                }

                //未写完，2017年2月15日18:22:45  明天再做

                $size = $this->newsSubscribe;
                $data = M ("qapp:news")->getqAppCateList ('public', '', $new_id, $page, $size);
                if (empty($data['data']) && $page == 1) {
                    $this->json_output (array('err' => 2, 'msg' => '没有相关数据'));
                }
                $this->_checkLastPage ($data['count'], $size, $page);
                //截取示例文章文字
                foreach ($data['data'] as $key => &$v) {
                    //$v['content']=$this->cleanhtml(strip_tags($v['content']),'');
                    $data['data'][$key]['description'] = mb_substr (strip_tags ($v['description']), 0, 50, 'utf-8').'...';
                    //取出右键导航分类名称
                    $data['data'][$key]['cate_name']  = M ("qapp:news")->getSubName ($v['cate_id']);
                    $data['data'][$key]['input_time'] = $this->checkTime ($v['input_time']);
                    if ($v['type'] == 'public') {
                        $arr       = array('pe', 'pp', 'pvc');
                        $tmp       = array_rand ($arr, 1);
                        $v['type'] = 'pp';
                    }
                    $v['type'] = strtoupper ($v['type']);
                    //unset($v['content']);
                }
                shuffle ($data['data']);
                $this->json_output (array(
                    'err'      => 0,
                    'data'     => $data['data'],
                    'show_msg' => count ($data['data']) > 0 ? '更新了'.count ($data['data']).'条数据' : '',
                ));
            }
        }
    }


}