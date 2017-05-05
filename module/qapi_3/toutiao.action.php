<?php
/**
 * Created by PhpStorm.
 * User: sick
 * Date: 17-5-3
 * Time: 上午10:33
 */
class toutiaoAction extends baseAction
{

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
        if ($_POST) {
            $this->is_ajax = true;
            $id            = sget ('id', i);
            $this->checkAccount (0);
            if (empty($id)) {
                $this->error (array( 'err' => 5, 'msg' => '参数错误，请稍后再试' ));
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
                    if (empty($_tmp)) {
                        continue;
                    }
                    //                    if(empty($_tmp)) $_tmp = M("qapp:news")->getNewsOrderByPv('', $key, '', 1, 3)[0];
                    //                    if(empty($_tmp)) $_tmp = M("qapp:news")->getNewsOrderByPv('', $key, '', 1, 7)[0];
                    //                    if(empty($_tmp)) $_tmp = M("qapp:news")->getNewsOrderByPv('', $key, '', 1, 0)[0];
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
                    $arr          = array( 'pe', 'pp', 'pvc' );
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
            $cache->set ('qcateDetailInfo'.'_'.$id, $data, 1800);
            $this->json_output (array( 'err' => 0, 'info' => $data ));
        }
        $this->_errCode (6);
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
        if ($_POST) {
            $this->is_ajax = true;
            $user_id       = $this->checkAccount ();
            $cache         = cache::startMemcache ();
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
                    $this->json_output (array( 'err' => 2, 'msg' => '没有相关数据' ));
                }
                $this->_checkLastPage ($data['count'], $page_size, $page);
                $this->json_output (array( 'err' => 0, 'data' => $data['data'] ));
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
                                $stmp = array( $stmp );
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
                        $stmp = array( $stmp );
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
                    $this->json_output (array( 'err' => 2, 'msg' => '没有相关数据' ));
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
                        $arr       = array( 'pe', 'pp', 'pvc' );
                        $tmp       = array_rand ($arr, 1);
                        $v['type'] = 'pp';
                    }
                    $v['type'] = strtoupper ($v['type']);
                    //unset($v['content']);
                }
                shuffle ($data['data']);
                $this->json_output (array( 'err' => 0, 'data' => $data['data'] ));
            }
        }
    }


}