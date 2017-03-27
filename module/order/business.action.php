<?php

    /**
     * 实时成交数据
     */
    class businessAction extends adminBaseAction
    {
        public function __init()
        {
            $this->debug = false;
            $this->db = M('public:common')->model('sale_log');
            $this->doact = sget('do', 's');
        }

        /**
         *
         * @access public
         * @return html
         */
        public function init()
        {
            $pid = sget('p_id', 'i');
            $this->assign('p_id', $pid);
            $oid = sget('o_id', 'i');
            $this->assign('o_id', $oid);
            $doact = sget('do', 's');
            $action = sget('action', 's');
            if ($action == 'grid') { //获取列表
                $this->_grid();
                exit;
            }
            $this->assign('doact', $doact);
            $this->assign('page_title', '订单管理列表');
            $this->display('business.list.html');
        }

        /**
         * Ajax获取列表内容
         */
        public function _grid()
        {
            $page = sget("pageIndex", 'i', 0); //页码
            $size = sget("pageSize", 'i', 20); //每页数
            $roleid = M('rbac:rbac')->model('adm_role_user')->select('role_id')->where("`user_id` = {$_SESSION['adminid']}")->getOne();
            $sortField = sget("sortField", 's', 'input_time'); //排序字段
            $sortOrder = sget("sortOrder", 's', 'desc'); //排序
            //筛选
            $where .= ' where 1 ';
            //筛选时间
            $sTime = sget("sTime", 's', 'log.`input_time`'); //搜索时间类型
            $where .= getTimeFilter($sTime); //时间筛选
            //关键词搜索
            $key_type = sget('key_type', 's', 'name');
            $keyword = sget('keyword', 's');
            $model = sget('model', 's');
            $fname = sget('f_name', 's');
            if (!empty($keyword) && $key_type == 'name') {
                $where .= " and adm.`name` = '$keyword'";
            } elseif (!empty($keyword) && $key_type == 'order_sn') {
                $where .= " and o.`order_sn` = '$keyword'";
            }
            if (!empty($model)) {
                $where .= " and pro.`model` = '$model'";
            }
            if (!empty($fname)) {
                $where .= " and fac.`f_name` = '$fname'";
            }
            $pid = sget('p_id', 'i');
            if ($pid > 0) {
                $where .= " and log.`p_id` = '$pid' ";
                $page = 0;
                $size = 10;
                $limit = 10;
            }
            $oid = sget('o_id', 'i');
            if ($oid > 0) {
                $where .= " and o.`o_id` < '$oid' ";
            }
            $orderby = " order by $sortField $sortOrder";
            $where .= ' and o.order_type = 1 AND o.`order_status` = 2 AND o.`transport_status` = 2';
            //筛选过滤自己的订单信息
            // if($_SESSION['adminid'] != 1 && $_SESSION['adminid'] > 0){
            // 	if(!in_array($roleid, array('30','26','27'))){
            // 		$sons = M('rbac:rbac')->getSons($_SESSION['adminid']);
            // 		$where .= " and (`s_customer_manager` in ($sons) or `p_customer_manager` = {$_SESSION['adminid']})  ";
            // 	}
            // }
            $list = $this->db->getAll('SELECT o.`order_sn`,o.`transport_type`,o.`pickup_location`,o.`is_futures`,pro.`model`,fac.`f_name`,o.`o_id`,log.`p_id`,log.`number`,log.`unit_price`,log.`input_time`,log.`customer_manager`,adm.`name`
			FROM p2p_sale_log AS log
			LEFT JOIN `p2p_order` AS o ON o.`o_id` = log.`o_id`
			LEFT JOIN `p2p_product` AS pro ON log.`p_id` = pro.`id`
			LEFT JOIN `p2p_factory` AS fac ON pro.`f_id` = fac.`fid`
			LEFT JOIN `p2p_admin` AS adm ON adm.`admin_id` = log.`customer_manager`
			' . $where . $orderby . ' limit ' . ($page) * $size . ',' . $size);
            //showtrace();
            $list_count = $this->db->getAll('SELECT o.`order_sn`,o.`transport_type`,o.`pickup_location`,o.`is_futures`,pro.`model`,fac.`f_name`,o.`o_id`,log.`p_id`,log.`number`,log.`unit_price`,log.`input_time`,log.`customer_manager`,adm.`name`
			FROM p2p_sale_log AS log
			LEFT JOIN `p2p_order` AS o ON o.`o_id` = log.`o_id`
			LEFT JOIN `p2p_product` AS pro ON log.`p_id` = pro.`id`
			LEFT JOIN `p2p_factory` AS fac ON pro.`f_id` = fac.`fid`
			LEFT JOIN `p2p_admin` AS adm ON adm.`admin_id` = log.`customer_manager`' . $where . $limit);
            foreach ($list as &$value) {
                $value['input_time'] = $value['input_time'] > 1000 ? date("Y-m-d H:i:s", $value['input_time']) : '-';
                $value['transport_type'] = $value['transport_type'] == 1 ? '供方送到' : '需方自提';
                $value['is_futures'] = $value['is_futures'] == 1 ? '否' : '是';
            }
            $msg = "";
            $result = array('total' => count($list_count), 'data' => $list, 'msg' => $msg);
            $this->json_output($result);
        }


        /**
         * 全部销售数据走势图(不分年)
         * @param $p_id ,$model
         * @return json html
         * @Author: yumeilin
         */
        public function graph()
        {
            $date_year = sget('date_year', 'i');
            $type = sget('type', 's');

            switch ($type) {
                case 'daily':
                    $divid = 'Y-m-d';
                    break;
                case 'weekly':
                    $divid = 'W';
                    break;
                case 'monthly':
                    $divid = 'm';
                    break;
                case 'fifteen':
                    $divid = 'Y-m-d';
                    break;
                default :
                    $divid = 'Y-m-d';
                    break;
            }
            $tip_format = array(
                'daily'=>'每日',
                'monthly'=>'每月',
                'weekly'=>'每周',
            );
            $p_id = sget('p_id', 'i');
            $model = sget('model', 's');
            //启动redis缓存
            $cache = E('RedisCluster', APP_LIB . 'class');
            $graph_cache = $cache->get('GRAPH:'.$model.":".$type.":".$date_year);
            if (!empty($graph_cache) && !is_null($graph_cache)) {
                $data = json_decode($graph_cache, true);
                $d = $data['aver'];
                $a = $data['num'];
                $b = $data['date'];
                $markline = $data['markline'];

                $this->assign('p_id', $p_id);
                $this->assign('model', $model);
                if ($this->isAjax()) {
                    $this->json_output(array('tip' => $tip_format[$type].'平均价格', 'aa' => $a, 'bb' => $b, 'dd' => $d, 'markline' => $markline));
                    die();
                }
                $this->display('business.graph.html');
                die();
            }
            $where = " where 1 AND pro.model ='$model'AND o.order_type = 1 AND o.`order_status` = 2 AND o.`transport_status` = 2";
            if (!empty($date_year) && $date_year != 'all') {
                $start_day = mktime(00, 00, 00, 1, 1, $date_year);
                $end_day = mktime(00, 00, 00, 12, 31, $date_year);
                $where .= " AND log.input_time < {$end_day} AND log.input_time > {$start_day}  ";
            }
            $list = M('public:common')->model('sale_log')->order('input_time')->getAll('SELECT log.`number`,log.`unit_price`,log.`input_time`,log.`update_time`
			FROM p2p_sale_log AS log
			LEFT JOIN `p2p_order` AS o ON o.`o_id` = log.`o_id`
			LEFT JOIN `p2p_product` AS pro ON log.`p_id` = pro.`id`
			' . $where . " order by log.input_time");
            foreach ($list as $k => $v) {
                $list[$k]['time'] = date("Y-m-d", $v['input_time']);
            }
            $tmp = array();
            foreach ($list as $v) {
                $time = date($divid, $v['input_time']);
                if ($type == 'weekly' || $type == 'monthly') $time = (int)$time;
                $tmp[$time][] = $v;
            }
            unset($time);
            $time_arr = array_keys($tmp);
            sort($time_arr);
            //计算时间
            switch ($type) {
                case 'daily':
                    $start_day = strtotime($time_arr[0]);
                    $end_day = strtotime(end($time_arr));
                    $show_time = array();
                    $count = floor(($end_day - $start_day) / (24 * 60 * 60));
                    for ($i = 0; $i < $count; $i++) {
                        $day = $start_day + 24 * 60 * 60 * $i;
                        $day_info = getdate($day);
                        if ($day_info['wday'] != 0 && $day_info['wday'] != 6) {
                            $show_time[] = date($divid, $day);
                        }
                    }
                    break;
                case 'weekly':
                    $start_day = (int)$time_arr[0];
                    $end_day = (int)end($time_arr);
                    $show_time = range($start_day, $end_day, 1);
                    break;
                case 'monthly':
                    $start_day = (int)$time_arr[0];
                    $end_day = (int)end($time_arr);
                    $show_time = range($start_day, $end_day, 1);
                    break;
            }
            $diff_arr = array_diff($show_time, $time_arr);
            foreach ($diff_arr as $time0) {
                $tmp[$time0] = array(array('input_time' => time(), 'unit_price' => 0, 'number' => 0, 'time' => $time0));
            }

            unset($v);
            unset($time0);
            ksort($tmp);
            $x_ray = array();
            $price = array();
            $num = array();

            foreach ($tmp as $time => $val) {
                $tmp_price = 0;
                $tmp_num = 0;
                $tmp_val = self::my_sort($val, 'input_time');
                foreach ($tmp_val as $value) {
                    $tmp_price += (float)$value['unit_price'] / 1000;
                    $tmp_num += (float)$value['number'];
                }
                unset($value);

                $price[] = round($tmp_price / count($val),3);
                $num[] = $tmp_num;
                $x_ray[] = (string)$time;
            }

            unset($val);
            $markline = $visualmap = array();

            foreach ($price as $k => $p) {
                static $count = 0;
                if (empty($p) && empty($count) && empty($price[$k + 1])) {
                    $markline[] = array(array('name' => '无成交', 'xAxis' =>(string) $x_ray[$k], 'itemStyle' => array('normal' => array('color' => '#DDDDDD', 'opacity' => 0.8), 'emphasis' => array('color' => 'green', 'opacity' => 0.8))));
                    $count++;
                } elseif (empty($p) && !empty($count) && !empty($price[$k + 1])) {
                    $tmp = end($markline);
                    array_pop($markline);
                    $tmp[] = array('xAxis' => (string)$x_ray[$k], 'itemStyle' => array('normal' => array('color' => '#DDDDDD', 'opacity' => 0.8), 'emphasis' => array('color' => 'green', 'opacity' => 0.8)));
                    array_push($markline, $tmp);
                    $count--;
                }
            }
            unset($k, $p);

            foreach ($price as &$p) {
                if (empty($p)) $p = '-';
            }

            $cache_to = array(
                'aver'     => $price,
                'num'      => $num,
                'date'     => $x_ray,
                'markline' => $markline,
            );
            $cache->set('GRAPH:'.$model.":".$type.":".$date_year, json_encode($cache_to), 60 * 60);
            $this->assign('p_id', $p_id);
            $this->assign('model', $model);
            if ($this->isAjax()) {
                $this->json_output(array('tip' =>  $tip_format[$type].'平均价格', 'aa' => $num, 'bb' => $x_ray, 'dd' => $price, 'markline' => $markline));
                exit();
            }
            $this->display('business.graph.html');
        }

        /**
         * 固定年每15天销售走势图
         * @param $p_id
         * @return json
         * @Author: yumeilin
         */
        public function graph_h()
        {
            $date_year = sget('date_year', 's');
            $p_id = sget('p_id', 'i');
            $model = sget('model', 's');
            $cache = E('RedisCluster', APP_LIB . 'class');
            $graph_cache = $cache->get('GRAPH_H:' . $model);
            if (!empty($graph_cache) && !is_null($graph_cache)) {
                $data = json_decode($graph_cache, true);
                $d = $data['aver'];
                $a = $data['num'];
                $b = $data['date'];
                $this->json_output(array('tip' => '每15天平均价格', 'aa' => $a, 'bb' => $b, 'dd' => $d));
                die();
            }
            $where = " where 1 AND pro.model ='$model'AND o.order_type = 1 AND o.`order_status` = 2 AND o.`transport_status` = 2";
            $list = M('public:common')->model('sale_log')->order('input_time')->getAll('SELECT log.`number`,log.`unit_price`,log.`input_time`,log.`update_time`
			FROM p2p_sale_log AS log
			LEFT JOIN `p2p_order` AS o ON o.`o_id` = log.`o_id`
			LEFT JOIN `p2p_product` AS pro ON log.`p_id` = pro.`id`
			LEFT JOIN `p2p_factory` AS fac ON pro.`f_id` = fac.`fid`
			' . $where . " order by input_time");
            foreach ($list as $k => $v) {
                $list[$k]['time'] = date("Y-m-d", $v['input_time']);
                $list[$k]['time_h'] = date("Y-m", $v['input_time']);
            }
            $year_list = array();
            foreach ($list as $k => $v) {
                if (date("Y", $v['input_time']) == $date_year) {
                    array_push($year_list, $list[$k]);
                }
            }
            //获取销量数据
            $price = array();
            $time = array();
            $time_u = array();
            $le_l = count($year_list);
            for ($i = 0; $i < $le_l; $i++) {
                $time[$i] = $year_list[$i]['time_h'];
            }
            $time_a = array_unique($time);
            foreach ($time_a as $k => $v) {
                array_push($time_u, $time_a[$k]);
            }
            $le_t = count($time_u);
            $num1 = 0;
            $num2 = 0;
            $y1 = 0;
            $y2 = 0;
            $a_price1 = 0;
            $a_price2 = 0;
            for ($i = 0; $i < $le_t; $i++) {
                for ($j = 0; $j < $le_l; $j++) {
                    if (date("Y-m", $year_list[$j]['input_time']) == $time_u[$i]) {
                        if (date("d", $year_list[$j]['input_time']) <= '15') {
                            $num1 += $year_list[$j]['number'];
                            $a_price1 += $year_list[$j]['unit_price'];
                            ++$y1;
                        } else {
                            $num2 += $year_list[$j]['number'];
                            $a_price2 += $year_list[$j]['unit_price'];
                            ++$y2;
                        }
                    }
                }
                $a[2 * $i] = $num1;
                $a[2 * $i + 1] = $num2;
                $d[2 * $i] = (int)sprintf("%.0f", $a_price1 / $y1);
                $d[2 * $i + 1] = (int)sprintf("%.0f", $a_price2 / $y2);
                $num1 = 0;
                $num2 = 0;
                $a_price1 = 0;
                $a_price2 = 0;
                $y1 = 0;
                $y2 = 0;
            }
            //获取价格数据
            /* 	    $highest=0;
                    $lowest=1000000000000000000;
                    for($i=0;$i<$le_t;$i++){
                        for($j=0;$j<$le_l;$j++){
                            if($year_list[$j]['time']==$time_u[$i]){
                                array_push($price,$year_list[$j]['unit_price']);
                                if($year_list[$j]['unit_price']>=$highest){
                                    $highest=$year_list[$j]['unit_price'];
                                }
                                if($year_list[$j]['unit_price']<=$lowest){
                                    $lowest=$year_list[$j]['unit_price'];
                                }
                            }
                        }
                        $c[$i]['open']=(int)reset($price);
                        $c[$i]['close']=(int)end($price);
                        $c[$i]['lowest']=(int)$lowest;
                        $c[$i]['highest']=(int)$highest;
                        $price=array();
                        $highest=0;
                        $lowest=1000000000;
                    }
                    foreach($c as $k=>$v){
                        $c[$k]=array_values($v);
                    } */
            $b = array();
            for ($i = 0; $i < 2 * $le_t; $i++) {
                array_push($b, $i);
            }
            $cache_to = array(
                'aver' => $d,
                'num'  => $a,
                'date' => $b
            );
            $cache->set('GRAPH_H:' . $model, json_encode($cache_to), 60 * 60);
            $this->json_output(array('tip' => '每15天平均价格', 'aa' => $a, 'bb' => $b, 'dd' => $d));
        }

        /**
         * 获取年份
         * @param $model
         * @return json
         * @Author: xielei
         */
        public function chart_year()
        {
            $model = sget('model', 's');
            $cache = E('RedisCluster', APP_LIB . 'class');
            $cache_info = $cache->get('CHART_YEAR:' . $model);
            if (!empty($cache_info) && !is_null($cache_info)) {
                $year_list = json_decode($cache_info, true);
                $this->json_output($year_list);
            }
            $res = $this->db->model('product')->where(" model = '{$model}'")->getAll();
            foreach ($res as $val) {
                $product_ids[] = $val['id'];
            }
            unset($val);
            $earlist = $this->db->model('sale_log')->where(' p_id in (' . join(',', $product_ids) . ')')->order('input_time')->getRow();
            $last = $this->db->model('sale_log')->where(' p_id in (' . join(',', $product_ids) . ')')->order('input_time desc')->getRow();
            $years = range(date("Y", $earlist['input_time']), date("Y", $last['input_time']));
            $arr = array(array('value' => '0', 'key' => '全部'));
            foreach ($years as $year) {
                $arr[] = array('value' => $year, 'key' => $year);
            }
            $cache->set('CHART_YEAR:' . $model, json_encode($arr), 7 * 24 * 60 * 60);
            $this->json_output($arr);
        }


        private function my_sort($arrays, $sort_key, $sort_order = SORT_ASC, $sort_type = SORT_NUMERIC)
        {
            if (is_array($arrays)) {
                foreach ($arrays as $array) {
                    if (is_array($array)) {
                        $key_arrays[] = $array[$sort_key];
                    } else {
                        return false;
                    }
                }
            } else {
                return false;
            }
            array_multisort($key_arrays, $sort_order, $sort_type, $arrays);

            return $arrays;
        }
    }