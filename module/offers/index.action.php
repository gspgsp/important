<?php

    class indexAction extends homeBaseAction
    {

        protected $db;
        protected $cartKey;

        public function __init()
        {
            E('Cart', APP_LIB . 'extend');
            $this->cartKey = "cartList";
            $this->db = M('public:common');
        }

        public function init()
        {
            $this->act = 'offers';
            //购物车
            $this->cartList = Cart::getGoods();
            $cityWhere = 'pid=1';
            $factoryWhere = 1;
            $where = "pur.type=2 and pur.shelve_type=1 and pur.status in (2,3,4) and pur.sync in(0,1,2,7) and pur.last_buy_sale=''";
            if ($keywords = sget('keywords', 's', '')) {
                $where .= " and (pro.model like '%{$keywords}%' or fa.f_name like '%{$keywords}%')";
            }

            if ($type = sget('type', 'i', 0)) {
                $seotype = L('product_type')[$type];
                $this->assign('type', $type);
                //地区筛选
                $area_ids = $this->db->from('purchase pur')
                    ->join('product pro', 'pur.p_id=pro.id')
                    ->select('pur.provinces')
                    ->group('pur.provinces')
                    ->where("pro.product_type=$type")
                    ->getCol();
                $area_ids = array_unique($area_ids);
                $cityWhere = "id in (" . implode(',', $area_ids) . ")";
                //厂家晒讯
                $factory_ids = $this->db->from('product')->select('f_id')->where("product_type=$type")->group('f_id')->getCol();
                $factoryWhere = "fid in (" . implode(',', $factory_ids) . ")";
                $where .= " and pro.product_type=$type";
                //应用筛选
                $process_ids = $this->db->from('product')->select('process_type')->where("product_type=$type")->group('process_type')->getCol();
            }
            //筛选条件
            if ($process = sget('process', 'i', 0)) {
                $seoprocess = ' ' . L('process_level')[$process];
                $this->assign('process', $process);
                $where .= " and pro.process_type=$process";
            }

            if ($ct = sget('ct', 'i', 0)) {
                $seotitle = ' ' . $this->db->model('lib_region')->select('name')->where("id = $ct")->getOne();
                $this->assign('ct', $ct);
                $where .= " and pur.provinces=$ct";

            }

            if ($fa = sget('fa', 'i', 0)) {
                $this->assign('fa', $fa);
                $where .= " and pro.f_id=$fa";
                $seofa = ' ' . $this->db->model('factory')->select('f_name')->where("fid = $fa")->getOne();
            }

            if ($key_model = sget('key_model', 's', '')) {
                $this->assign('key_model', $key_model);
                $where .= " and pro.model like '%{$key_model}%'";
            }
            if ($key_name = sget('key_name', 's', '')) {
                $this->assign('key_name', $key_name);
                $cus_ids = $this->db->from('customer cus')
                    ->join('customer_contact con', 'cus.c_id=con.c_id')
                    ->where("cus.c_name like '%{$key_name}%'")
                    ->select('con.user_id')
                    ->getCol();
                $where .= " and pur.user_id in (" . implode(',', $cus_ids) . ")";
            }
            if ($cycle = sget('cycle', 'i', 0)) {
                $this->assign('cycle', $cycle);
                $where .= " and pur.period=$cycle";
            }
            if ($cargo_type = sget('cargo_type', 'i', 0)) {
                $this->assign('cargotype', $cargo_type);
                $where .= " and pur.cargo_type=$cargo_type";
            }
            if ($union = sget('union', 'i', 0)) {
                $this->assign('union', $union);
                if ($union == 1) {
                    $where .= " and pur.is_union=1";
                } else {
                    $where .= " and pur.is_union=0";
                }
            }
            //筛选条件结束
            //报价周期
            $period = L('period');
            $this->assign('period', $period);
            //产品类型
            $product_type = L('product_type');
            $this->assign('product_type', $product_type);
            //产品应用
            $process_level = L('process_level');
            if ($process_ids) {
                foreach ($process_ids as $key => $value) {
                    if (!$value) continue;
                    $processList[$value] = $process_level[$value];
                }
            } else {
                $processList = $process_level;
            }
            $this->assign('processList', $processList);
            //地区
            $provinces = $this->db->model('lib_region')->where($cityWhere)->order('sort desc')->getAll();
            $this->assign('provinces', $provinces);
            $belong_area = L('belong_area');

            foreach ($provinces as $key => $value) {
                $area[$value['area']]['name'] = $belong_area[$value['area']];
                $area[$value['area']]['arr'][] = $value;
            }
            ksort($area);
            $this->assign('area', $area);

            //厂家
            $factoryList = $this->db->model('factory')->where($factoryWhere)->limit(28)->getAll();
            $this->assign('factoryList', $factoryList);

            $page = sget('page', 'i', 1);
            $pageSize = 20;//分页

            $list = M('product:purchase')->getPurPage($where, $page, $pageSize);
            foreach ($list['data'] as $key => $value) {
                $list['data'][$key]['city'] = (!empty($value['region_name'])) ? $value['region_name'] : $value['store_house'];
                $str = mb_strlen($value['store_house'], 'utf-8');
                if ($str > 4) {
                    $value['store_house'] = (mb_substr($value['store_house'], 0, 3, 'utf-8')) . '...';
                    $list['data'][$key] = $value;
                }
                if ($value['number'] == '0.0000') {
                    $list['data'][$key]['number'] = "";
                }

                //交易员
                $cus_man = $this->db->from('customer_contact')
                    ->select('customer_manager')
                    ->where('user_id=' . $value['user_id'])
                    ->getOne();
                if (!$cus_man) {
                    $list['data'][$key]['cus_man'] = '0';
                } else {
                    $list['data'][$key]['cus_man'] = $cus_man;
                }
            }
            $this->pages = pages($list['count'], $page, $pageSize);
            $list = $list['data'];

            foreach ($list as $key => $value) {
                if ($value['is_union'] == 0) {
                    $uids[] = $value['user_id'];
                }
            }
            $uids = array_unique($uids);
            //获取用户信息
            $contactList = M("user:customerContact")->getContactByUserId($uids);

            foreach ($contactList as $key => $value) {
                $customerTemp[$value['user_id']] = $value;
            }
            foreach ($list as $key => $value) {
                if ($value['is_union'] == 0) {
                    $list[$key]['customer'] = $customerTemp[$value['user_id']];
                } else {
                    $list[$key]['customer'] = $this->db->from('purchase pur')
                            ->join('admin as ad', 'pur.customer_manager=ad.admin_id')
                            ->select('ad.name,ad.mobile')
                            ->where("pur.shelve_type=1 and pur.status in (2,3,4) and pur.sync in(0,1,2,7)")
                            ->getRow() + array('c_name' => '商城自营');
                }
            }
            $this->seo = array(
                'title'       => $seotype . $seoprocess . $seotitle . $seofa . $keywords . $key_model . $key_name . ' 商城报价',
                'keywords'    => '塑料报价，塑料原料报价，塑料原料价格，塑料价格，聚乙烯价格，聚丙烯价格，聚氯乙烯价格，PVC塑料，PE塑料，PP塑料',
                'description' => '我的塑料网商城报价栏目拥有海量塑料原料现货或期货报价，免费提供塑料原料价格查询，资深塑料交易员全程为您服务',
                'status'      => 1);
            $this->assign('list', $list);
            $this->display('index');
        }

        //添加购物车
        public function addCart()
        {
            if ($_POST) {
                $this->is_ajax = true;
                $id = sget('id', 'i', 0);
                $goods = Cart::getGoods();
                if (count($goods) >= 5) $this->error('最多只能选购5个商品');
                foreach ($goods as $key => $value) {
                    if ($value['id'] == $id) $this->error('该商品已选购');
                }
                if (!$data = M('product:purchase')->getPurchaseInfo($id)) $this->error('信息错误');
                // 获取产品类型
                $data['product_type'] = witchType($data['product_type']);
                //获取发货地区信息
                $data['city'] = $this->db->model('lib_region')->where("id={$data['provinces']}")->select('name')->getOne();
                $sid = $this->addCol($data);
                $data['sid'] = $sid;
                $this->success($data);

            }
        }

        //移除购物车
        public function delCart()
        {
            if ($_POST) {
                $this->is_ajax = true;
                $sid = sget('id', 's', '');
                Cart::del($sid);
                $this->success('删除成功');
            }
        }

        protected function addCol($data)
        {
            $arr = array(
                'id'      => $data['id'],
                'name'    => $data['model'],
                'num'     => 1,
                'number'  => $data['number'],
                'price'   => $data['unit_price'],
                'options' => array(
                    'p_id'         => $data['p_id'],
                    'product_type' => $data['product_type'],
                    'model'        => $data['model'],
                    'f_name'       => $data['f_name'],
                    'unit_price'   => $data['unit_price'],
                    'city'         => $data['city'],
                ),
            );

            return Cart::add($arr);
        }

        /**
         * 公司报价产品目录页
         * @Author: yuanjiaye
         */
        public function companyDetails()
        {

            $c_id = sget('c_id', 's', '');
            if ($c_id) {
                $info = $this->db->model('customer as cus')
                    ->join('customer_contact as ct', 'cus.c_id=ct.c_id')
                    ->select('cus.c_id,ct.user_id,cus.c_name,cus.download,cus.`com_intro`,ct.mobile,ct.is_default')
                    ->where("cus.c_id={$c_id} and ct.is_default=1")
                    ->getAll();
            }
            $str = mb_strlen($info[0]['com_intro'], 'utf-8');
            if ($str < 10) {
                $info[0]['com_intro'] = empty($info[0]['com_intro']) ? '暂无描述' : $info[0]['com_intro'];
            }
            if ($str > 10) {
                $info[0]['com_intro'] = (mb_substr($info[0]['com_intro'], 0, 10, 'utf-8')) . '...';
            }
            $page = sget('page', 'i', 1);
            $pageSize = 10;
            $where = "pur.c_id={$c_id} and pur.shelve_type=1 and pur.type=2";
            $list = M('product:purchase')->getPurchaseByUserId($where, $page, $pageSize);

//		foreach($list['data'] as $key => $value ){
//			if(!$value['model'] || !$value['product_type'] || !$value['f_name']){
//				unset($list['data'][$key]);
//			}
//		}
            foreach ($list['data'] as $key => $value) {
                $list['data'][$key]['number'] = ($value['number'] == 0.00) ? '' : $value['number'];

            }
            $this->pages = pages($list['count'], $page, $pageSize);
            $this->assign('list', $list);
            $this->assign('info', $info);
            $this->display('details');
        }

        /**
         * 下载公司报价信息
         * @Author: yuanjiaye
         */
        public function downLoad()
        {

            $cid = sget('cid', 's', '');  //接收传递公司c_id
            $url = sget('urls', 's', ''); //接收页面url
            $res = $this->db->model('customer')->select('c_id,c_name,download')->where("c_id={$cid}")->getAll();
            $data_url = "$url";
            $html = file_get_contents($data_url);
            preg_match('/<div[^>]*class="detail-con"[^>]*>(.*?)<\/div>/si', $html, $match);// 获取某个页面div class属性下的数据
            $ma = strip_tags($match[0]);
            $aa = $this->_trimall($ma);
            $arr = explode("\n", trim($aa));
            $arr1 = array_filter(($arr));
            $arr2 = array_values($arr1);
            array_pop($arr2);

            $coins = $this->_arr_oper($arr2, 9);
            if (count($coins) < 2) $this->error('没有可供下载的数据');
            $_data = array(
                'download' => $res[0]['download'] + 1,
            );
            $this->db->model('customer')->where("c_id={$cid}")->update($_data);


            $resultPHPExcel = E('PHPExcel', APP_LIB . 'extend');
            $resultPHPExcel->getActiveSheet()->setCellValue('A1', '品种');
            $resultPHPExcel->getActiveSheet()->setCellValue('B1', '牌号');
            $resultPHPExcel->getActiveSheet()->setCellValue('C1', '厂家');
            $resultPHPExcel->getActiveSheet()->setCellValue('D1', '数量(吨)');
            $resultPHPExcel->getActiveSheet()->setCellValue('E1', '价格(元)/吨');
            $resultPHPExcel->getActiveSheet()->setCellValue('F1', '交货地区');
            $resultPHPExcel->getActiveSheet()->setCellValue('G1', '仓库');
            $resultPHPExcel->getActiveSheet()->setCellValue('H1', '现期货');
            $resultPHPExcel->getActiveSheet()->setCellValue('I1', '发布时间');
            $i = 1;

            foreach ($coins as $item) {
                $item[8] = mb_substr($item[8], 0, 5, 'utf-8');
                //赋值
                $resultPHPExcel->getActiveSheet()->setCellValueExplicit('A' . $i, $item[0], PHPExcel_Cell_DataType::TYPE_STRING);
                $resultPHPExcel->getActiveSheet()->setCellValue('B' . $i, $item[1]);
                $resultPHPExcel->getActiveSheet()->setCellValue('C' . $i, $item[2]);
                $resultPHPExcel->getActiveSheet()->setCellValue('D' . $i, $item[3]);
                $resultPHPExcel->getActiveSheet()->setCellValue('E' . $i, $item[4]);
                $resultPHPExcel->getActiveSheet()->setCellValue('F' . $i, $item[5]);
                $resultPHPExcel->getActiveSheet()->setCellValue('G' . $i, $item[6]);
                $resultPHPExcel->getActiveSheet()->setCellValue('H' . $i, $item[7]);
                $resultPHPExcel->getActiveSheet()->setCellValue('I' . $i, $item[8]);
                $i++;
            }
            //设置列宽
            $resultPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
            $resultPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
            $resultPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
            $resultPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
            $resultPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
            $resultPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
            $resultPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);

            $outputFileName = $res[0]['c_name'] . '-' . '来自我的塑料网' . '.xls';
            $xlsWriter = new PHPExcel_Writer_Excel5($resultPHPExcel);  //创建文件格式写入对象实例
            header("Content-Type: application/octet-stream");
            header('Content-Disposition:inline;filename="' . $outputFileName . '"');
            header("Content-Transfer-Encoding: binary");
//		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Pragma: no-cache");
            $xlsWriter->save("php://output");

        }

        /**
         * 遍历拆分数组
         * @param $arr
         * @param $num
         * @return array
         * @Author: yuanjiaye
         */
        private function _arr_oper($arr, $num)
        {
            $count = count($arr);
            $return_arr = array();
            for ($i = 0; $i < $count / $num; $i++) {
                $return_arr[$i] = array_slice($arr, $num * $i, $num);
            }

            return $return_arr;
        }

        private function _trimall($str)
        {
            $qian = array(" ", "　", "\t", "\r");
            $hou = array("", "", "", "");

            return str_replace($qian, $hou, $str);
        }

        /**
         * 价格行情图页面
         * @param $arr
         * @param $num
         * @return array
         * @Author:xielei
         */
        public function price_charts()
        {

            $model = sget('model', 's');
            $f_id = sget('f_id', 'i');
            $cache = E('RedisCluster', APP_LIB . 'class');
            $cache_info = $cache->get('PRICE_CHART:' . $model . '_' . $f_id);

            if (!empty($cache_info) && !is_null($cache_info)) {
                $data = json_decode($cache_info, true);
                if ($data['is_empty']) {
                    $this->assign('type', array('model' => $model, 'f_id' => $f_id));
                    $this->assign('x_ray', json_encode(array(date("Y-m-d"))));
                    $this->assign('price', json_encode(array()));
                    $this->assign('num', json_encode(array()));
                    $this->assign('price_col', array('top' => 12, 'bottom' => 0, 'interval' => 3));
                    $this->assign('num_col', array('top' => 1000, 'bottom' => 0, 'interval' => 500));
                    $this->assign('valid', 'false');
                    $this->display('price_charts');
                    die();

                } else {
                    $this->assign('list', $data['list']);
                    $this->assign('x_ray', json_encode($data['x_ray']));
                    $this->assign('price', json_encode($data['price']));
                    $this->assign('num', json_encode($data['num']));
                    $this->assign('valid', 'true');
                    $this->assign('price_col', array('top' => $data['top'], 'bottom' => $data['bottom'], 'interval' => $data['interval']));
                    $this->assign('num_col', array('top' => $data['top0'], 'bottom' => 0, 'interval' => $data['interval0']));
                    $this->assign('type', array('model' => $model, 'f_id' => $f_id));
                    $this->display('price_charts');
                    die();
                }
            }
            $res = $this->db->model('product')->where(" model = '{$model}' and f_id = {$f_id}")->getAll();
            if (empty($res)) {
                $cache->set('PRICE_CHART:' . $model . '_' . $f_id, json_encode(array('is_empty' => 1)), 60 * 60);
                $this->assign('type', array('model' => $model, 'f_id' => $f_id));
                $this->assign('x_ray', json_encode(array(date("Y-m-d"))));
                $this->assign('price', json_encode(array()));
                $this->assign('num', json_encode(array()));
                $this->assign('price_col', array('top' => 12, 'bottom' => 0, 'interval' => 3));
                $this->assign('num_col', array('top' => 1000, 'bottom' => 0, 'interval' => 500));
                $this->assign('valid', 'false');
                $this->display('price_charts');
                die();
            }
            foreach ($res as $val) {
                $product_ids[] = $val['id'];
            }
            unset($val);
            //$product_ids = array_column($res,'id');
            $res = $this->db->model('sale_log')->where(' p_id in (' . join(',', $product_ids) . ')')->order('unit_price', 'desc')->getAll();
            if (empty($res)) {
                $cache->set('PRICE_CHART:' . $model . '_' . $f_id, json_encode(array('is_empty' => true)), 60 * 60);
                $this->assign('type', array('model' => $model, 'f_id' => $f_id));
                $this->assign('x_ray', json_encode(array(date("Y-m-d"))));
                $this->assign('price', json_encode(array()));
                $this->assign('num', json_encode(array()));
                $this->assign('price_col', array('top' => 12, 'bottom' => 0, 'interval' => 3));
                $this->assign('num_col', array('top' => 1000, 'bottom' => 0, 'interval' => 500));
                $this->assign('valid', 'false');
                $this->display('price_charts');
                die();
            }
            $bottom_price = $res[0];
            $bottom = round($bottom_price['unit_price'] / 1000 - 1);

            $top_price = end($res);
            $top = round($top_price['unit_price'] / 1000 + 1);

            $interval = round(($top - $bottom) / 5);

            $tmp = array();
            foreach ($res as $v) {
                $time = date('Y-m-d', $v['input_time']);
                $tmp[$time][] = $v;
            }
            unset($time);
            $time_arr = array_keys($tmp);
            sort($time_arr);
            //计算时间
            $start_day = strtotime($time_arr[0]);
            $end_day = strtotime(end($time_arr));
            $show_time = array();
            $count = floor(($end_day - $start_day) / (24 * 60 * 60));
            for ($i = 0; $i < $count; $i++) {
                $day = $start_day + 24 * 60 * 60 * $i;
                $day_info = getdate($day);
                if ($day_info['wday'] != 0 && $day_info['wday'] != 6) {
                    $show_time[] = date("Y-m-d", $day);
                }
            }
            $diff_arr = array_diff($show_time, $time_arr);
            foreach ($diff_arr as $time0) {
                $tmp[$time0] = array(array('input_time' => time(), 'unit_price' => 0, 'number' => 0));
            }

            unset($v);
            unset($time0);
            ksort($tmp);
            $x_ray = array();
            $price = array();
            $num = array();
            foreach ($tmp as $time => $val) {
                $tmp_price = array();
                $tmp_num = 0;
                $tmp_val = self::my_sort($val, 'input_time');
                foreach ($tmp_val as $value) {
                    $tmp_price[] = (float)$value['unit_price'] / 1000;
                    $tmp_num += (float)$value['number'];
                }
                unset($value);

                $format_arr = array();
                $format_arr[0] = $tmp_price[0];
                $format_arr[1] = end($tmp_price);
                sort($tmp_price);
                $format_arr[2] = $tmp_price[0];
                $format_arr[3] = end($tmp_price);

                $x_ray[] = $time;
                $price[] = $format_arr;
                $num[] = $tmp_num;
            }
            unset($val);

            $top0 = ceil(max($num));
            $interval0 = round(($top0) / 3);
            $cache_arr = array(
                'list'      => $res,
                'x_ray'     => $x_ray,
                'price'     => $price,
                'num'       => $num,
                'top'       => $top,
                'bottom'    => $bottom,
                'interval'  => $interval,
                'top0'      => $top0,
                'interval0' => $interval0,
                'is_empty'  => false
            );

            $cache->set('PRICE_CHART:' . $model . '_' . $f_id, json_encode($cache_arr), 60 * 60);
            $this->assign('list', $res);
            $this->assign('x_ray', json_encode($x_ray));
            $this->assign('price', json_encode($price));
            $this->assign('num', json_encode($num));
            $this->assign('valid', 'true');
            $this->assign('price_col', array('top' => $top, 'bottom' => $bottom, 'interval' => $interval));
            $this->assign('num_col', array('top' => $top0, 'bottom' => 0, 'interval' => $interval0));
            $this->assign('type', array('model' => $model, 'f_id' => $f_id));
            $this->display('price_charts');
        }

        public function is_price_data_exist()
        {
            $model = sget('model', 's');
            $f_id = sget('f_id', 'i');

            $res = $this->db->model('product')->where(" model = '{$model}' and f_id = {$f_id}")->getAll();
            if (empty($res)) {
                $arr = array('err' => 1, 'msg' => '价格数据记录为空');
                $this->json_output($arr);
                die();
            }
            foreach ($res as $val) {
                $product_ids[] = $val['id'];
            }
            unset($val);
            //$product_ids = array_column($res,'id');
            $res = $this->db->model('sale_log')->where(' p_id in (' . join(',', $product_ids) . ')')->order('unit_price', 'desc')->getAll();
            if (empty($res)) {
                $arr = array('err' => 1, 'msg' => '价格数据记录为空');
            } else {
                $arr = array('err' => 0, 'msg' => '价格数据记录正确');
            }
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