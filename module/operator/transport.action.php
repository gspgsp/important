<?php

/**
 * 订单查询管理
 */
class transportAction extends adminBaseAction
{
    public function __init()
    {
        $this->debug = false;
        $this->db = M('public:common')->model('order');
        $this->doact = sget('do', 's');
        $this->assign('order_source', L('order_source')); //订单来源
        $this->assign('pay_method', L('pay_method')); //付款方式
        $this->assign('transport_type', L('transport_type')); //运输方式
        $this->assign('business_model', L('business_model')); //业务模式
        $this->assign('financial_records', L('financial_records')); //财务记录
        $this->assign('order_status', L('order_status')); //订单审核
        $this->assign('transport_status', L('transport_status')); //物流审核
        $this->assign('out_storage_status', L('out_storage_status')); //发货状态
        $this->assign('invoice_status', L('invoice_status'));        //开票状态
        $this->assign('invoice_one_status', L('invoice_one_status'));    //单笔明细开票状态
        $this->assign('price_type', L('price_type')); //价格单位
        $this->assign('in_storage_status', L('in_storage_status')); //入库状态
        $this->assign('order_type', L('order_type')); //订单类型：
        $this->assign('company_account', L('company_account')); //交易公司账户
        $this->assign('sales_type', L('sales_type')); //销售类型
        $this->assign('purchase_type', L('purchase_type')); //采购类型
        $this->assign('bile_type', L('bile_type'));                //票据类型
        $this->assign('billing_type', L('billing_type'));        //开票类型
        $this->assign('c_fax', L('c_fax'));  //联系传真
    }

    /**
     * 初始页面
     * @access public
     * @return html
     */
    public function init()
    {
        // var_dump($_SESSION);
        $doact = sget('do', 's');
        $action = sget('action', 's');
        $order_type = sget('order_type', 's');
        //$action='grid';
        if ($action == 'grid') { //获取列表
            $this->_grid();
            exit;
        }
        $team = $this->db = M('public:common')->model('adm_role')->where('pid=22')->getAll();
        $this->assign('team', $team);
        //$this->assign('order_type',1);
        $this->assign('doact', $doact);
        $this->assign('page_title', '订单管理列表');
        $this->display('order.list.html');
    }

    /**
     *物流合同增加有页面
     * @access public
     * @return html
     */
    public function add()
    {
        $order_id = sget('order_id', 'i');
        $customer = M("operator:logisticsSupplier")->select('supplier_id as id,supplier_name as name')->getAll();

        if (!empty($order_id)) {
            $this->db = M('public:common')->model('order');
            $order_info = M('public:common')->model('order')->where('o_id=' . $order_id)->getRow();
            $order_info_new = M('public:common')->model('sale_log slg')->leftjoin("product p", "p.id=slg.p_id")->where('slg.o_id=' . $order_id)->getRow();
            $model = M('public:common')->getLastSql();
            $v['model']=strtoupper(M("product:product")->getModelById($v['p_id']));
            file_put_contents('/tmp/xielei.txt',print_r($model,true),FILE_APPEND);

            $this->assign('page_title', '添加物流合同');
            $order_info['sign_time'] = date('Y-m-d');
            $this->assign('info', $order_info);
            $this->assign('order_info', $order_info_new);
        }
        $this->assign('customer_info', json_encode($customer));

        $this->display('transport_contract.add.html');

    }

    /**
     *物流合同编辑页面
     * @access public
     * @return html
     */
    public function edit()
    {
        $lc_id = sget('lc_id', 's');

        if (!empty($lc_id)) {
            $info = M('public:common')->model('transport_contract')->where('logistics_contract_id=' . $lc_id)->getRow();
            $this->db = M('public:common')->model('order');
            $order_info = M('public:common')->model('order')->where('o_id=' . $order_id)->getRow();
            $customer = M("operator:logisticsSupplier")->select('supplier_id as id,supplier_name as name')->getAll();
            $order_info_new = M('public:common')->model('sale_log slg')->leftjoin("product p", "p.id=slg.p_id")->where('slg.o_id=' . $order_id)->getRow();
            //var_dump($order_info_new);
            $model = M('public:common');
            $sql = $model->getLastSql();

            $this->assign('page_title', '编辑物流合同');
            $order_info['sign_time'] = date('Y-m-d');
            $this->assign('info', $order_info);
            $this->assign('order_info', $order_info_new);
            $this->assign('customer_info', json_encode($customer));
            $this->assign('infoq', $info);
            $this->display('transport_contract.edit.html');

        } else {
            $arr = ['err' => 1, 'msg' => '合同信息错误'];
            $this->json_output($arr);
        }

    }

    /**
     * 获得物流公司联系人列表数据详情
     * @access public
     * @return html
     */
    public function get_contact_list()
    {
        $company_id = sget('company_id', 's');
        $contacts = M("operator:logisticsContact")->where("supplier_id=" . $company_id)->getAll();
        //$order_info_new = M('public:common')->model('sale_log slg')->leftjoin("purchase p", "p.id=slg.purchase_id")->where('slg.o_id=' . $order_id)->getAll();
        //var_dump($order_info_new);
        foreach ($contacts as $contact) {
            $contact_name_info[] = array('id' => $contact['id'], 'name' => $contact['contact_name'], 'tel' => $contact['contact_tel'], 'fax' => $contact['comm_fax']);
        }

        $this->json_output($contact_name_info);

    }

    /**
     * 获得物流公司联系人详情数据详情
     * @access public
     * @return html
     */
    public function addSubmit()
    {
        $data = $_POST;
        if (!empty($data['logistics_contract_id'])) {
            $arr = ['err' => 1, 'msg' => '物流公司信息错误'];
            $this->json_output($arr);
        }
        if ($data['second_part_company_id'] < 1) {
            $arr = ['err' => 1, 'msg' => '物流公司信息错误'];
            $this->json_output($arr);
        }
        /*if(empty($data['second_part_contact_id'])||empty($data['contact_time']))
        {
            $arr = ['err'=>1,'msg'=>'必填项目未填'];
            $this->json_output($arr);
        }*/

        $data['status'] = 1;
        $data['create_time'] = time();
        $data['created_by'] = $this->admin_id;
        $res = M('public:common')->model('transport_contract')->add($data);

        if ($res) {
            $arr = ['err' => 0, 'msg' => '合同生效'];
        } else {
            $arr = ['err' => 0, 'msg' => '程序错误'];
        }
        $this->json_output($arr);
    }


    /**
     * 获得物流公司联系人详情数据详情
     * @access public
     * @return html
     */
    public function editSubmit()
    {
        $data = $_POST;
        if (empty($data['logistics_contract_id'])) {
            $arr = ['err' => 1, 'msg' => '物流公司信息错误'];
            $this->json_output($arr);
        }
        if ($data['second_part_company_id'] < 1) {
            $arr = ['err' => 1, 'msg' => '物流公司信息错误'];
            $this->json_output($arr);
        }

        $data['status'] = 1;
        $data['update_time'] = time();
        $data['last_edited_by'] = $this->admin_id;
        $res = M('public:common')->model('transport_contract')->where('logistics_contract_id=' . $data['logistics_contract_id'])->update($data);

        if ($res) {
            $arr = ['err' => 0, 'msg' => '合同生效'];
        } else {
            $arr = ['err' => 1, 'msg' => '程序错误'];
        }
        $this->json_output($arr);
    }

    /**
     * 获得物流公司联系人详情数据详情
     * @access public
     * @return json
     */
    public function get_contact_info()
    {

        $contact_id = sget('contact_id', 's');

        $contact = M("operator:logisticsContact")->where("id=" . $contact_id)->getRow();

        $this->json_output($contact);
    }
    /**
     * 获得物流公司详情数据详情
     * @access public
     * @return json
     */
    public function get_supplier_info()
    {

        $supplier_id = sget('supplier_id', 'i');

        $supplier = M("operator:logisticsSupplier")->where("supplier_id=" . $supplier_id)->getRow();

        $this->json_output($supplier);
    }

    /**
     * 生成PDF合同
     * @access public
     * @return html
     */
    public function pdfView()
    {
        $lc_id = sget('lc_id', 's');

        if (!empty($lc_id)) {
            $info = M('public:common')->model('transport_contract lc')->leftJoin('logistics_supplier ls', 'lc.second_part_company_id = ls.supplier_id')->where('logistics_contract_id=' . $lc_id)->getRow();
            $model = M('public:common');
            $sql = $model->getLastSql();
            //$this->db = M('public:common')->model('order');
            //$customer = M("operator:logisticsSupplier")->select('supplier_id as id,supplier_name as name')->getAll();
            //$order_info_new = M('public:common')->model('sale_log slg')->leftjoin("product p", "p.id=slg.p_id")->where('slg.o_id=' . $order_id)->getRow();
            //var_dump($order_info_new);

            $this->assign('infoq', $info);
            return $this->fetch('transport_contract.pdf.html');

        } else {
            $arr = ['err' => 1, 'msg' => '合同信息错误'];
            $this->json_output($arr);
        }
        //$contact = M("operator:logisticsContact")->where("id=" . $contact_id)->getRow();

        //$this->json_output($contact);
    }

    /**
     * 生成PDF合同
     * @access public
     * @return html
     */
    public function pdfRender()
    {
        $lc_id = sget('lc_id', 'i');
        if (!empty($lc_id)) {
            $info = M('public:common')->model('transport_contract lc')->leftJoin('logistics_supplier ls', 'lc.second_part_company_id = ls.supplier_id')->where('logistics_contract_id=' . $lc_id)->getRow();

            $this->assign('infoq', $info);
            $str = $this->fetch('transport_contract.pdf.html');

        } else {
            $arr = ['err' => 1, 'msg' => '合同信息错误'];
            $this->json_output($arr);
        }

        E('TCPdf', APP_LIB . 'extend');
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetTitle('上海中晨物流合同');
        $pdf->SetHeaderData('config/pdflogo.jpg', 180, '', '', array(0, 33, 43), array(0, 64, 128));
        // 设置默认等宽
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        // 设置间距
        $pdf->SetMargins(15, 20, 15);
        $pdf->SetHeaderMargin(3);
        $pdf->SetFooterMargin(10);
        // 设置分页
        $pdf->SetAutoPageBreak(TRUE, 25);
        $pdf->setImageScale(1.25);
        $pdf->setFontSubsetting(true);
        // 设置中文字体
        $pdf->SetFont('stsongstdlight', '', 10);
        $pdf->AddPage();
        $pdf->writeHTMLCell(0, 0, '', '', $str, 0, 1, 0, true, '', true);
        // 输出pdf
        $pdf->Output("pdf_{$lc_id}.pdf", 'I');
        //$contact = M("operator:logisticsContact")->where("id=" . $contact_id)->getRow();

        //$this->json_output($contact);
    }

    /**
     * 导出报表
     * @access public
     * @return html
     */
    public function download()
    {
        $roleid = M('rbac:rbac')->model('adm_role_user')->select('role_id')->where("`user_id` = {$_SESSION['adminid']}")->getOne();
        if (in_array($roleid, array('30', '26', '27'))) {
            $sortField = sget("sortField", 's', 'update_time'); //排序字段
        } else {
            $sortField = sget("sortField", 's', 'input_time'); //排序字段
        }
        $sortOrder = sget("sortOrder", 's', 'desc'); //排序
        //筛选
        $where .= 1;
        //筛选状态
        if (sget('type', 'i', 0) != 0) $order_type = sget('type', 'i', 0);//订单类型
        if (sget('order_type', 'i', 0) != 0) $order_type = sget('order_type', 'i', 0);
        if ($order_type != 0) $where .= " and `order_type` =" . $order_type;
        $financial_records = sget('financial_records', 's');//抬头
        if ($financial_records != '') $where .= " and `financial_records` = " . $financial_records;
        $order_source = sget('order_source', 'i', 0);//订单来源
        if ($order_source != 0) $where .= " and `order_source` =" . $order_source;
        $pay_method = sget('pay_method', 'i', 0);//付款方式
        if ($pay_method != 0) $where .= " and `pay_method` =" . $pay_method;
        $transport_type = sget('transport_type', 'i', 0);//运输方式
        if ($transport_type != 0) $where .= " and `transport_type` =" . $transport_type;
        $business_model = sget('business_model', 'i', 0);//业务模式
        if ($business_model != 0) $where .= " and `business_model` =" . $business_model;
        $order_status = sget('order_status', 'i', 0);//订单审核,默认订单取消的不做统计范围
        if ($order_status != 0) {
            $where .= " and `order_status` =" . $order_status;
        } else {
            $where .= " and `order_status` <> 3";
        }
        $transport_status = sget('transport_status', 'i', 0);//物流审核
        if ($transport_status != 0) $where .= " and `transport_status` =" . $transport_status;
        $out_storage_status = sget('out_storage_status', 'i', 0);//发货状态
        if ($out_storage_status != 0) $where .= " and `out_storage_status` =" . $out_storage_status;
        //筛选时间
        $sTime = sget("sTime", 's', 'input_time'); //搜索时间类型
        $where .= getTimeFilter($sTime); //时间筛选
        //关键词搜索
        $key_type = sget('key_type', 's', 'order_sn');
        $keyword = sget('keyword', 's');
        if (!empty($keyword) && $key_type == 'input_admin') {
            $admin_id = M('rbac:adm')->getAdmin_Id($keyword);
            $where .= " and `customer_manager` = '$admin_id'";
        } elseif (!empty($keyword) && $key_type == 'c_id') {
            $keyword = M('product:order')->getOidByCname($keyword);
            $where .= " and `$key_type` in ('$keyword') ";
        } elseif (!empty($keyword)) {
            $where .= " and `$key_type`  = '$keyword' ";
        }
        $orderby = "$sortField $sortOrder";
        //筛选过滤自己的订单信息
        if ($_SESSION['adminid'] != 1 && $_SESSION['adminid'] > 0) {
            $sons = M('rbac:rbac')->getSons($_SESSION['adminid']);
            $where .= " and (`customer_manager` in ($sons) or `partner` = {$_SESSION['adminid']})  ";
            //筛选财务
            if (in_array($roleid, array('30', '26', '27'))) {
                $where .= " and `order_status` = 2 and `transport_status` = 2 ";
            }
        }
        //团队处理
        $team_id = sget("team", 's', ''); //team
        if (!empty($team_id)) {
            $team_user_arr = M('rbac:role')->model('adm_role_user')
                ->where('role_id=' . $team_id)
                ->select('user_id')
                ->getAll();
            $str = ',';
            foreach ($team_user_arr as $key => $value) {
                $string .= $str . $value['user_id'];
            }
            $in_string = trim($string, ',');
            $where .= " and `customer_manager` in (" . $in_string . " )";
        }
        $list = $this->db->where($where)->order($orderby)->getAll();
        foreach ($list as &$v) {
            $v['c_name'] = $v['partner'] == $_SESSION['adminid'] ? '*******' : M("user:customer")->getColByName($v['c_id']);//根据cid取客户名
            $v['input_time'] = $v['input_time'] > 1000 ? date("Y-m-d H:i:s", $v['input_time']) : '-';
            $v['update_time'] = $v['update_time'] > 1000 ? date("Y-m-d H:i:s", $v['update_time']) : '-';
            $v['sign_time'] = $v['sign_time'] > 1000 ? date("Y-m-d H:i:s", $v['sign_time']) : '-';
            $v['order_source'] = L('order_source')[$v['order_source']];
            $v['order_name'] = L('company_account')[$v['order_name']];
            $v['pay_method'] = L('pay_method')[$v['pay_method']];
            $v['in_storage_status'] = L('in_storage_status')[$v['in_storage_status']];
            $v['transport_type'] = L('transport_type')[$v['transport_type']];
            $v['business_model'] = L('business_model')[$v['business_model']];
            $v['financial_records'] = L('financial_records')[$v['financial_records']];
            $v['partner'] = M('rbac:adm')->getUserByCol($v['partner']);
            //订单收付款状态
            $v['payments_status'] = ($v['order_type'] == '1' ? L('collection_g_status')[$v['collection_status']] : L('collection_p_status')[$v['collection_status']]);
            $v['order_type'] = L('order_type')[$v['order_type']];
            $v['out_storage_status'] = L('out_storage_status')[$v['out_storage_status']];
            $v['invoice_status'] = L('invoice_status')[$v['invoice_status']];
            $v['type_status'] = L('order_status')[$v['order_status']] . '|' . L('transport_status')[$v['transport_status']];
            $v['node_flow'] = $this->_accessChk($this->db->model('order')->select('node_flow')->where("`o_id` ={$v['o_id']} ")->getOne());
            $v['cmanager'] = M('rbac:adm')->getUserByCol($v['customer_manager']);
            //获取采购订单开票状态
            if (!empty($v['store_o_id'])) {
                $v['newstatus'] = M("product:order")->getColByName($value = $v['store_o_id'], $col = 'invoice_status', $condition = 'o_id');
            }
            if (!empty($v['join_id'])) {
                $v['newstatus'] = M("product:order")->getColByName($value = $v['join_id'], $col = 'invoice_status', $condition = 'o_id');
            }
            $v['see'] = ($v['customer_manager'] == $_SESSION['adminid'] || in_array($v['customer_manager'], explode(',', $sons)) || $_SESSION['adminid'] == '1') ? '1' : '0';
            //获取单笔订单收付款状态
            $m = M("product:collection")->getLastInfo($name = 'o_id', $value = $v['o_id']);
            $v['one_c_status'] = $m[0]['collection_status'];
            $v['one_b_status'] = M("product:billing")->where("o_id={$v['o_id']} and invoice_status=1")->select('invoice_status')->getOne();

        }
        //销售订单和采购订单 导出的字段不同，要区分开来
        $str = '<meta http-equiv="Content-Type" content="text/html; charset=utf8" /><table width="100%" border="1" cellspacing="0">';
        if ($order_type == 1) {
            $str .= '<tr><td>订单号</td><td>抬头</td><td>来源</td><td>客户名称</td>
						<td>总数</td><td>总金额</td><td>协作者</td><td>出库状态</td>
						<td>收款状态</td><td>开票状态</td><td>备注</td><td>销售|物流</td>
						<td>财务记录</td><td>创建时间</td><td>更新时间</td><td>交易员</td>
					</tr>';
            foreach ($list as $k => &$v) {
                $str .= "<tr><td style='vnd.ms-excel.numberformat:@'>" . $v['order_sn'] . "</td><td>" . $v['order_name'] . "</td><td>" . $v['order_source'] . "</td><td>" . $v['c_name'] . "</td>
							<td>" . $v['total_num'] . "</td><td>" . $v['total_price'] . "</td><td>" . $v['partner'] . "</td><td>" . $v['out_storage_status'] . "</td>
							<td>" . $v['payments_status'] . "</td><td>" . $v['invoice_status'] . "</td><td>" . $v['remark'] . "</td><td>" . $v['type_status'] . "</td>
							<td>" . $v['financial_records'] . "</td><td style='vnd.ms-excel.numberformat:@'>" . $v['input_time'] . "</td><td style='vnd.ms-excel.numberformat:@'>" . $v['update_time'] . "</td><td>" . $v['cmanager'] . "</td>
						</tr>";
            }
        } elseif ($order_type == 2) {
            $str .= '<tr><td>订单号</td><td>抬头</td><td>来源</td><td>客户名称</td>
						<td>总数</td><td>总金额</td><td>入库状态</td><td>付款状态</td>
						<td>开票状态</td><td>备注</td><td>采购|物流</td><td>财务记录</td>
						<td>创建时间</td><td>更新时间</td><td>交易员</td>
					</tr>';
            foreach ($list as $k => &$v) {
                $str .= "<tr><td style='vnd.ms-excel.numberformat:@'>" . $v['order_sn'] . "</td><td>" . $v['order_name'] . "</td><td>" . $v['order_source'] . "</td><td>" . $v['c_name'] . "</td>
				<td>" . $v['total_num'] . "</td><td>" . $v['total_price'] . "</td><td>" . $v['in_storage_status'] . "</td><td>" . $v['payments_status'] . "</td>
				<td>" . $v['invoice_status'] . "</td><td>" . $v['remark'] . "</td><td>" . $v['type_status'] . "</td><td>" . $v['financial_records'] . "</td>
				<td style='vnd.ms-excel.numberformat:@'>" . $v['input_time'] . "</td><td style='vnd.ms-excel.numberformat:@'>" . $v['update_time'] . "</td><td>" . $v['cmanager'] . "</td></tr>";
            }
        }

        $str .= '</table>';
        $filename = 'accountMpay.' . date("Y-m-d");
        header("Content-type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=$filename.xls");
        echo $str;
        exit;
    }

    /**
     * @access public
     * @return html
     * 默认是采购订单
     **/
    public function purchase()
    {
        $doact = sget('do', 's');
        $action = sget('action', 's');
        $order_type = sget('order_type', 's');
        if ($action == 'grid') { //获取列表
            $this->_grid();
            exit;
        }
        $team = $this->db = M('public:common')->model('adm_role')->where('pid=22')->getAll();
        $this->assign('team', $team);
        $this->assign('order_type', 2);
        $this->assign('doact', $doact);
        $this->assign('page_title', '订单管理列表');
        $this->display('order.list.html');
    }

    /**
     * Ajax获取列表内容
     */
    public function _grid()
    {

        $page = sget("pageIndex", 'i', 0); //页码
        $size = sget("pageSize", 'i', 20); //每页数
        $roleid = M('rbac:rbac')->model('adm_role_user')->select('role_id')->where("`user_id` = {$_SESSION['adminid']}")->getOne();
        if (in_array($roleid, array('30', '26', '27'))) {
            $sortField = sget("sortField", 's', 'update_time'); //排序字段
        } else {
            $sortField = sget("sortField", 's', 'input_time'); //排序字段
        }
        $sortOrder = sget("sortOrder", 's', 'desc'); //排序
        //筛选
        $where .= 1;
        //筛选状态
        if (sget('type', 'i', 0) != 0) $order_type = sget('type', 'i', 0);//订单类型
        if (sget('order_type', 'i', 0) != 0) $order_type = sget('order_type', 'i', 0);
        if ($order_type != 0) $where .= " and `order_type` =" . $order_type;
        $financial_records = sget('financial_records', 's');//抬头
        if ($financial_records != '') $where .= " and `financial_records` = " . $financial_records;
        $order_source = sget('order_source', 'i', 0);//订单来源
        if ($order_source != 0) $where .= " and `order_source` =" . $order_source;
        $pay_method = sget('pay_method', 'i', 0);//付款方式
        if ($pay_method != 0) $where .= " and `pay_method` =" . $pay_method;
        $transport_type = sget('transport_type', 'i', 0);//运输方式
        if ($transport_type != 0) $where .= " and `transport_type` =" . $transport_type;
        $business_model = sget('business_model', 'i', 0);//业务模式
        if ($business_model != 0) $where .= " and `business_model` =" . $business_model;
        $order_status = sget('order_status', 'i', 0);//订单审核,默认订单取消的不做统计范围
        if ($order_status != 0) {
            $where .= " and `order_status` =" . $order_status;
        } else {
            $where .= " and `order_status` <> 3";
        }
        $transport_status = sget('transport_status', 'i', 0);//物流审核
        if ($transport_status != 0) $where .= " and `transport_status` =" . $transport_status;
        $out_storage_status = sget('out_storage_status', 'i', 0);//发货状态
        if ($out_storage_status != 0) $where .= " and `out_storage_status` =" . $out_storage_status;
        //筛选时间
        $sTime = sget("sTime", 's', 'input_time'); //搜索时间类型
        $where .= getTimeFilter($sTime); //时间筛选
        //关键词搜索
        $key_type = sget('key_type', 's', 'order_sn');
        $keyword = sget('keyword', 's');
        if (!empty($keyword) && $key_type == 'input_admin') {
            $admin_id = M('rbac:adm')->getAdmin_Id($keyword);
            $where .= " and `customer_manager` = '$admin_id'";
        } elseif (!empty($keyword) && $key_type == 'c_id') {
            $keyword = M('product:order')->getOidByCname($keyword);
            $where .= " and `$key_type` in ('$keyword') ";
        } elseif (!empty($keyword)) {
            $where .= " and `$key_type`  = '$keyword' ";
        }
        $orderby = "$sortField $sortOrder";
        //筛选过滤自己的订单信息
        if ($_SESSION['adminid'] != 1 && $_SESSION['adminid'] > 0) {
            $sons = M('rbac:rbac')->getSons($_SESSION['adminid']);
            $where .= " and (`customer_manager` in ($sons) or `partner` = {$_SESSION['adminid']})  ";
            //筛选财务
            if (in_array($roleid, array('30', '26', '27'))) {
                $where .= " and `order_status` = 2 and `transport_status` = 2 ";
            }
        }
        //团队处理
        $team_id = sget("team", 's', ''); //team
        if (!empty($team_id)) {
            $team_user_arr = M('rbac:role')->model('adm_role_user')
                ->where('role_id=' . $team_id)
                ->select('user_id')
                ->getAll();
            $str = ',';
            foreach ($team_user_arr as $key => $value) {
                $string .= $str . $value['user_id'];
            }
            $in_string = trim($string, ',');
            $where .= " and `customer_manager` in (" . $in_string . " )";
        }
//p($where);
        $list = $this->db->where($where)->page($page + 1, $size)->order($orderby)->getPage();
        foreach ($list['data'] as &$v) {
            $v['c_name'] = $v['partner'] == $_SESSION['adminid'] ? '*******' : M("user:customer")->getColByName($v['c_id']);//根据cid取客户名
            $v['input_time'] = $v['input_time'] > 1000 ? date("Y-m-d H:i:s", $v['input_time']) : '-';
            $v['update_time'] = $v['update_time'] > 1000 ? date("Y-m-d H:i:s", $v['update_time']) : '-';
            $v['sign_time'] = $v['sign_time'] > 1000 ? date("Y-m-d H:i:s", $v['sign_time']) : '-';
            $v['order_source'] = L('order_source')[$v['order_source']];
            $v['order_name'] = L('company_account')[$v['order_name']];
            $v['pay_method'] = L('pay_method')[$v['pay_method']];
            $v['in_storage_status'] = L('in_storage_status')[$v['in_storage_status']];
            $v['transport_type'] = L('transport_type')[$v['transport_type']];
            $v['business_model'] = L('business_model')[$v['business_model']];
            $v['financial_records'] = L('financial_records')[$v['financial_records']];
            $v['partner'] = M('rbac:adm')->getUserByCol($v['partner']);
            //订单收付款状态
            $v['payments_status'] = ($v['order_type'] == '1' ? L('collection_g_status')[$v['collection_status']] : L('collection_p_status')[$v['collection_status']]);
            $v['order_type'] = L('order_type')[$v['order_type']];
            $v['out_storage_status'] = L('out_storage_status')[$v['out_storage_status']];
            $v['invoice_status'] = L('invoice_status')[$v['invoice_status']];
            $v['type_status'] = L('order_status')[$v['order_status']] . '|' . L('transport_status')[$v['transport_status']];
            $v['node_flow'] = $this->_accessChk($this->db->model('order')->select('node_flow')->where("`o_id` ={$v['o_id']} ")->getOne());
            $v['cmanager'] = M('rbac:adm')->getUserByCol($v['customer_manager']);
            //获取采购订单开票状态
            if (!empty($v['store_o_id'])) {
                $v['newstatus'] = M("product:order")->getColByName($value = $v['store_o_id'], $col = 'invoice_status', $condition = 'o_id');
            }
            if (!empty($v['join_id'])) {
                $v['newstatus'] = M("product:order")->getColByName($value = $v['join_id'], $col = 'invoice_status', $condition = 'o_id');
            }
            $v['see'] = ($v['customer_manager'] == $_SESSION['adminid'] || in_array($v['customer_manager'], explode(',', $sons)) || $_SESSION['adminid'] == '1') ? '1' : '0';
            //获取单笔订单收付款状态
            $m = M("product:collection")->getLastInfo($name = 'o_id', $value = $v['o_id']);
            $v['one_c_status'] = $m[0]['collection_status'];
            //获取单笔订单开票状态
            // $n = M("product:billing")->getLastInfo($name='o_id',$value=$v['o_id']);
            // $v['one_b_status'] =$n[0]['invoice_status'];
            $v['one_b_status'] = M("product:billing")->where("o_id={$v['o_id']} and invoice_status=1")->select('invoice_status')->getOne();

        }
        $msg = "";
        if ($list['count'] > 0) {
            $sum = $this->db->select("sum(total_num) as wsum, sum(total_price) as msum")->where($where)->getRow();
            $collection = M('product:order')->get_collection($where);
            $order_type == '1' ? $collection_name = '收款' : $collection_name = '付款';
            $msg = "[筛选结果]总额:【" . price_format($sum['msum']) . "】总吨:【" . $sum['wsum'] . "】" . $collection_name . ":【" . price_format($collection) . "】";
        }
        $result = array('total' => $list['count'], 'data' => $list['data'], 'msg' => $msg);
        $this->json_output($result);
    }

    /**
     * 订单信息
     * @access public
     */
    public function info()
    {
        $o_id = sget('oid', 'i', 0);
        $sale = sget('sale', 'i', '0');
        if ($sale == 1) {//查看订单对应得销售订单信息
            if ($o_id > 0) {
                $o_id = M("product:order")->where('join_id=' . $o_id . ' or store_o_id=' . $o_id)->select('o_id')->getOne();
            }
        }
        $change_id = sget('change_id', i, 0); //接收不销库存的o_id 用于生成采购
        $order_type = sget('order_type', 'i', 0); //用于区分销售还是采购
        $o_type = sget('o_type', 'i', 0);//用于双击弹出查看时，区分销售还是采购
        if ($o_id < 1) {
            if ($order_type == 1) {
                $order_sn = 'SO' . genOrderSn();
            } else {
                $order_sn = 'PO' . genOrderSn();
            }
            $this->assign('input_admin', $_SESSION['name']); //用于把
            $this->assign('order_sn', $order_sn);
            $this->assign('otype', 'addopus'); //新增订单关联前台显示
            $info['sign_place'] = "上海";
            $info['delivery_location'] = "上海";
            $info['pickup_location'] = "上海";
            $info['sign_time'] = date("Y-m-d", CORE_TIME);
            $this->assign('info', $info);
            $this->assign('order_type', $order_type);
            $this->display('order.edit.html');
            exit;
        }
        $info = $this->db->getPk($o_id); //查询订单信息
        //关联的交易员id
        // $join_manager=$this->db->select('customer_manager as cmer')->where("`o_id` = {$info['join_id']}")->getOne();
        if (empty($info)) $this->error('错误的订单信息');
        if ($info['c_id'] > 0) {
            $roleid = M('rbac:rbac')->model('adm_role_user')->select('role_id')->where("`user_id` = {$_SESSION['adminid']}")->getOne();
            //如果是财务部屏蔽
            $exits = in_array($roleid, array('30', '26', '27')) ? '1' : '0';
            if (($info['partner'] == $_SESSION['adminid'] || $info['customer_manager'] != $_SESSION['adminid']) && $_SESSION['adminid'] != 1 && $exits != '1') {
                $c_name = '*******';
            } else {
                $c_name = M("user:customer")->getColByName($info['c_id'], "c_name");//根据cid取客户名
            }
        }
        $info['order_name'] = L('company_account')[$info['order_name']];
        $info['sign_time'] = date("Y-m-d", $info['sign_time']);
        $info['pickup_time'] = date("Y-m-d", $info['pickup_time']);
        $info['delivery_time'] = date("Y-m-d", $info['delivery_time']);
        $info['payment_time'] = date("Y-m-d", $info['payment_time']);
        $info['partner'] = M('rbac:adm')->getUserByCol($info['partner']);
        $info['creater'] = M('rbac:adm')->getUserByCol($info['customer_manager']);
        $this->assign('c_name', $c_name);
        $this->assign('info', $info);//分配订单信息
        if ($o_type == 1) {
            $this->assign('collection_status', L('gatheringt_status'));//订单收款状态
        } else {
            $this->assign('collection_status', L('payment_status'));//订单付款状态
        }
        $this->assign('type', $o_type);
        $order_type = $info['order_type'] == 1 ? 'saleLog' : 'purchaseLog';
        $this->assign('order_type', $order_type);
        $this->assign('o_id', $o_id);
        $this->display('order.viewInfo.html');
    }

    /**
     * 订单信息
     * @access public
     */
    public function viewInfo()
    {
        $o_id = sget('oid', 'i', 0);
        $sale = sget('sale', 'i', '0');

        if ($sale == 1) {//查看订单对应得销售订单信息
            if ($o_id > 0) {
                $o_id = M("product:order")->where('join_id=' . $o_id . ' or store_o_id=' . $o_id)->select('o_id')->getOne();
            }
        }

        $change_id = sget('change_id', i, 0); //接收不销库存的o_id 用于生成采购
        $order_type = sget('order_type', 'i', 0); //用于区分销售还是采购
        $o_type = sget('o_type', 'i', 0);//用于双击弹出查看时，区分销售还是采购
        if ($o_id < 1) {
            $this->error("没用对应的销售订单");
        }
        $info = $this->db->getPk($o_id); //查询订单信息
        //关联的交易员id
        // $join_manager=$this->db->select('customer_manager as cmer')->where("`o_id` = {$info['join_id']}")->getOne();
        if (empty($info)) $this->error('错误的订单信息');
        if ($info['c_id'] > 0) {
            $roleid = M('rbac:rbac')->model('adm_role_user')->select('role_id')->where("`user_id` = {$_SESSION['adminid']}")->getOne();
            //如果是财务部屏蔽
            $exits = in_array($roleid, array('30', '26', '27')) ? '1' : '0';
            if (($info['partner'] == $_SESSION['adminid'] || $info['customer_manager'] != $_SESSION['adminid']) && $_SESSION['adminid'] != 1 && $exits != '1') {
                $c_name = '*******';
            } else {
                $c_name = M("user:customer")->getColByName($info['c_id'], "c_name");//根据cid取客户名
            }
        }
        $info['order_name'] = L('company_account')[$info['order_name']];
        $info['sign_time'] = date("Y-m-d", $info['sign_time']);
        $info['pickup_time'] = date("Y-m-d", $info['pickup_time']);
        $info['delivery_time'] = date("Y-m-d", $info['delivery_time']);
        $info['payment_time'] = date("Y-m-d", $info['payment_time']);
        $info['partner'] = M('rbac:adm')->getUserByCol($info['partner']);
        $info['creater'] = M('rbac:adm')->getUserByCol($info['customer_manager']);
        $this->assign('c_name', $c_name);
        $this->assign('info', $info);//分配订单信息
        if ($o_type == 1) {
            $this->assign('collection_status', L('gatheringt_status'));//订单收款状态
        } else {
            $this->assign('collection_status', L('payment_status'));//订单付款状态
        }
        $this->assign('type', $o_type);
        $order_type = $info['order_type'] == 1 ? 'saleLog' : 'purchaseLog';
        $this->assign('order_type', $order_type);
        $this->assign('o_id', $o_id);
        $this->display('order.viewInfo.html');
    }
}