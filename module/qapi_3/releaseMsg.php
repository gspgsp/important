<?php
/**
 * Created by PhpStorm.
 * User: sick
 * Date: 17-5-3
 * Time: 上午10:48
 */

class releaseMsgAction extends baseAction
{

    //(中间供求信息)获取供求发布和消息回复
    public function getReleaseMsg ()
    {
        $this->is_ajax = true;
        if ($_POST) {
            $user_id = $this->checkAccount ();
            //筛选条件
            $keywords   = sget ('keywords', 's');
            $keywords   = $this->clearStr ($keywords);
            $type       = sget ('type', 'i', 0);//0 全部 1 求购 2 供给
            $sortField1 = strtoupper (sget ('sortField1', 's'));
            $sortField2 = strtoupper (sget ('sortField2', 's'));
            $version    = sget ('version', 's');//版本号
            $platform   = $this->checkPlatform ()['platform'];
            //$sortField =array('AUTO','NC');
            $fieldNum = '';
            $fieldNum .= $sortField1;
            if (empty($sortField1)) {
                if (empty($sortField2)) {
                    $fieldNum = '';
                } else {
                    $fieldNum = $sortField2;
                }
            } else {
                if (!empty($sortField2)) {
                    $fieldNum .= ','.$sortField2;
                }
            }


            if ($type == 0) {
                $sortOrder = 'All';
            } elseif ($type == 1) {
                $sortOrder = 'BUY';
            } elseif ($type == 2) {
                $sortOrder = 'SALE';
            }
            /**
             * 加上搜索记录
             */
            $arr = array(
                'user_id'    => $user_id,
                'sort_field' => $fieldNum,
                'sort_order' => $sortOrder,
                'content'    => $keywords,
                'version'    => $version,
                'ip'         => get_ip (),
                'chanel'     => $platform,
                'input_time' => CORE_TIME,
            );
            M ('qapp:plasticSearch')->add ($arr);


            //普通条件
            $page = sget ('page', 'i', 1);
            $size = sget ('size', 'i', 10);
            // 检测是否有标准格式供求

            if ($page == 1 && $sortField2 == 'AUTO' && empty($keywords)) {
                $has_purchase = M ('qapp:plasticRelease')->checkPurchase ($user_id,2);
                if(empty($has_purchase))
                {
                    $this->json_output (array(
                        'err' => 7,
                        'msg' => '您最近5天内未发布供求信息,暂无推荐！赶紧点击按钮去发布哦～',
                    ));
                }
                $has_standard = M ('qapp:plasticRelease')->checkPurchase ($user_id,1);
                if (empty($has_standard)) {
                    $this->json_output (array(
                        'err' => 7,
                        'msg' => '您最近5天内未发布标准格式供求信息,暂无推荐！赶紧点击按钮去发布哦～',
                    ));
                }
            }
            //检测是否有塑料圈关注的人
            if ($page == 1 && $sortField2 == 'CONCERN') {
                $has_concern = M ('qapp:plasticIntroduction')->getMyFuns ($user_id, 2, 1, $size);
                if (empty($has_concern['data'])) {
                    $this->json_output (array(
                        'err' => 9,
                        'msg' => '您尚未在系统中关注任何用户，暂无推荐！赶紧点击“通讯录-查看个人信息”页面-【关注】按钮去关注吧！',
                    ));
                }
            }
            //获取供求详细数据
            $data = M ('qapp:plasticRelease')->getReleaseMsg ($keywords, $page, $size, $type, $sortField1, $sortField2, $user_id);

            if ($data == 'tempErr') {
                $this->_errCode (5);
            }
            if (empty($data['data']) && $page == 1 && $sortField2 == 'AUTO' && empty($keywords)) {
                $this->json_output (array(
                    'err' => 4,
                    'msg' => '系统暂未为您匹配到相应的牌号，暂无推荐！',
                ));
            }
            if (empty($data['data']) && $page == 1 && $sortField2 == 'CONCERN') {
                $this->json_output (array(
                    'err' => 6,
                    'msg' => '您关注的塑料圈用户暂无供求信息！',
                ));
            }
            if (empty($data['data']) && $page == 1 && $sortField2 == 'DEMANDORSUPPLY') {
                $this->json_output (array(
                    'err' => 8,
                    'msg' => '您未发布任何供求信息！',
                ));
            }
            if (empty($data['data']) && $page == 1) {
                $this->json_output (array(
                    'err' => 2,
                    'msg' => '没有相关数据',
                ));
            }
            if (empty($data['data'])) {
                $this->json_output (array(
                    'err' => 3,
                    'msg' => '没有更多数据',
                ));
            }
            $this->_checkLastPage ($data['count'], $size, $page);
            $goods_id    = $this->db->model ("points_goods")->select ('id')->where (" type =1 and status =1")
                                    ->getOne ();
            $pointsOrder = M ("points:pointsOrder");

            $info0 = $pointsOrder->get_supply_demand_top ($goods_id);

            //只有在有置顶头条并且页面是首页或者智能推荐时候有效
            if ($info0 && ($sortField1 == 'ALL' || $sortField2 == 'AUTO')) {

                $top      = M ('qapp:plasticRelease')->getReleaseMsgDetail ($info0['pur_id'],$info0['uid']);
                $personal = M ('qapp:plasticPersonalInfo')->getMyOwnInfo ($info0['uid']);
                $_tmp     = $top['info'];
                unset($top['info']);
                $top = array_merge ($top, $_tmp, $personal);

                foreach ($data['data'] as $key => &$val) {
                    if ($val['id'] == $top['id']) {
                        unset($data['data'][$key]);
                        $data['data'] = array_values ($data['data']);
                    }
                }
                unset($val);

                $arr = array(
                    'err'  => 0,
                    'data' => $data['data'],
                    'top'  => $top,
                );
            } else {
                $top = (object)array();

                $arr = array(
                    'err'  => 0,
                    'data' => $data['data'],
                    'top'  => $top,
                );

            }

            $this->json_output ($arr);
        }
        $this->_errCode (6);
    }


    /**
     * (中间供求信息)获取供求发布(详情)
     */
    public function getReleaseMsgDetail ()
    {
        if ($_POST['token']) {
            $user_id = $this->checkAccount ();
            $id      = sget ('id', 'i');
            $own_id  = sget ('user_id', 'i');
            if ($id < 1 || $user_id < 1) {
                $this->_errCode (6);
            }
            $data = M ('qapp:plasticRelease')->getReleaseMsgDetail ($id, $own_id, $user_id);
            $this->_errCode (0, $data);
        }
        $this->_errCode (6);
    }

    /**
     * (中间供求信息)获取供求发布(详情)的消息回复
     */
    public function getReleaseMsgDetailReply ()
    {
        if ($_POST['token']) {
            $this->checkAccount ();
            $id      = sget ('id', 'i');
            $user_id = sget ('user_id', 'i');
            $page    = sget ('page', 'i', 1);
            $size    = sget ('size', 'i', 5);
            if ($id < 1 || $user_id < 1) {
                $this->_errCode (6);
            }
            $data = M ('qapp:plasticRelease')->getReleaseMsgDetailReply ($id, $user_id, $page, $size);
            if (empty($data['data']) && $page == 1) {
                $this->json_output (array(
                    'err' => 2,
                    'msg' => '没有相关的数据',
                ));
            }
            $this->_checkLastPage ($data['count'], $size, $page);
            $this->_errCode (0, $data);
        }
        $this->_errCode (6);
    }


    /**
     * 供求消息中的出价
     */
    public function deliverPrice ()
    {
        if ($_POST['token']) {
            $user_id = $this->checkAccount ();
            $id      = sget ('id', 'i');//对应purchase表的id
            $rev_id  = sget ('rev_id', 'i');//接收报价的人
            $type    = sget ('type', 'i');// 1 求购 2 供给
            $price   = sget ('price', 'f');
            if ($price <= 0 || $price > 50000) {
                $this->json_output (array(
                    'err' => 6,
                    'msg' => '价格输入不正常，请重新输入',
                ));
            }
            $price = sprintf ("%.2f", $price);
            $arr   = array(
                'pur_id'     => $id,
                'send_id'    => $user_id,
                'user_id'    => $rev_id,
                'type'       => $type,
                'price'      => $price,
                'input_time' => CORE_TIME,
            );
            if (M ('qapp:plasticQuote')->setPurchasePrice ($arr)) {
                $this->json_output (array(
                    'err' => 0,
                    'msg' => '发布成功',
                ));
            }
            $this->json_output (array(
                'err' => 5,
                'msg' => '发布失败',
            ));
        }
        $this->_errCode (6);
    }

    /**
     * 获取供求消息的出价
     */
    public function getDeliverPrice ()
    {
        if ($_POST) {
            $this->checkAccount ();
            $id     = sget ('id', 'i');//对应purchase表的id
            $rev_id = sget ('rev_id', 'i');//接收出价的人(即发布purchase报价消息的人)
            $page   = sget ('page', 'i', 1);
            $size   = sget ('size', 'i', 5);
            $data   = M ('qapp:plasticQuote')->getPurchasePrice ($id, $rev_id, $page, $size);
            $this->_errCode (0, $data);
            if (empty($data['data']) && $page == 1) {
                $this->json_output (array(
                    'err' => 2,
                    'msg' => '没有相关的数据',
                ));
            }
            $this->_checkLastPage ($data['count'], $size, $page);
        }
        $this->_errCode (6);
    }





    //判断提交的发布报价(采购1、报价2)数据/user/mypurchase/pub(现已修改到下面的方法)
    /**
     *      data[0][model]:2119
     * data[0][f_name]:陶氏
     * data[0][store_house]:上海
     * data[0][price]:8888.00
     * data[0][type]:1
     * data[0][pt]:1
     * data[0][content]:
     */
    public function pub ()
    {
        $this->is_ajax = true;
        if ($data = $_POST['data']) {
            $user_id = $this->checkAccount ();
            $data    = saddslashes ($data);
            $content = $data[0]['content'];//客户直接填写的求购内容，无格式
            //$cargo_type = $data[0]['cargo_type'];//现货、期货
            $type      = $data[0]['type'];//采购1、报价2
            $quan_type = sget ('quan_type', 'i');
            $pur_model = M ('product:purchase');
            $fac_model = M ('product:factory');
            $pro_model = M ('product:product');
            $soms      = M ('plasticzone:plasticPerson')->select ('c_id,customer_manager')->where ('user_id='.$user_id)
                                                        ->getRow ();
            $content   = str_replace (PHP_EOL, '', $content);
            //判断是否只有content
            if (empty($data[0]['model']) && empty($data[0]['f_name']) && empty($data[0]['store_house'])) {
                $_content = array(
                    'user_id'    => $user_id,
                    //用户id
                    'c_id'       => $soms['c_id'],
                    //客户id
                    'status'     => $type == 1 ? 1 : 2,
                    //状态，报价不需要审核，采购需要审核
                    'sync'       => 6,
                    //报价来源平台
                    'type'       => $type,
                    //采购、报价
                    'quan_type'  => $quan_type,
                    'content'    => $content,
                    //客户直接填写的求购内容
                    'input_time' => CORE_TIME,
                    //创建时间
                );
                $pur_model->startTrans ();
                try {
                    if (!$pur_model->add ($_content)) {
                        throw new Exception("系统错误 pubpur:3");
                    }
                    $pur_id = $pur_model->getLastID ();
                    //                    if ($type == 2) {//报价
                    //                        $spoints = intval (M ('system:setting')->get ('points')['points']['sale']);
                    //                        if (!M ("qapp:pointsBill")
                    //                            ->select ('id')
                    //                            ->where ("addtime >".strtotime (date ("Y-m-d"))."  and type=3 and uid=".$user_id)
                    //                            ->order ("id desc")
                    //                            ->getOne ()
                    //                        ) {
                    //                            if (!$arr = M ("qapp:pointsBill")->addPoints ($spoints, $user_id, 3)) {
                    //                                $this->json_output (array( 'err' => 5, 'msg' => "系统错误 pubpur:103" ));
                    //                            }
                    //                        }
                    //                    } elseif ($type == 1) {//采购
                    //                        $spoints = intval (M ('system:setting')->get ('points')['points']['pur']);
                    //                        if (!M ("qapp:pointsBill")
                    //                            ->select ('id')
                    //                            ->where ("addtime >".strtotime (date ("Y-m-d"))." and type=6 and uid=".$user_id)
                    //                            ->order ("id desc")
                    //                            ->getOne ()
                    //                        ) {
                    //                            if (!$arr = M ("qapp:pointsBill")->addPoints ($spoints, $user_id, 6)) {
                    //                                $this->json_output (array( 'err' => 5, 'msg' => "系统错误 pubpur:103" ));
                    //                            }
                    //                        }
                    //                    }
                } catch (Exception $e) {
                    $pur_model->rollback ();
                    $this->json_output (array(
                        'err' => 3,
                        'msg' => '插入数据失败',
                    ));
                }
                $pur_model->commit ();

                //robot表插入消息
                $tmpFuns    = M ("qapp:plasticIntroduction")->getMyFunsId ($user_id, 1);
                $tmpContent = "您关注的";
                $tmpContent .= M ("public:common")->model ('customer_contact')->select ('name')
                                                  ->where ("user_id=".$user_id)->getOne ();
                $tmpContent .= "发布了1条";
                if ($type == 1) {
                    $tmpContent .= "求购";
                } elseif ($type == 2) {
                    $tmpContent .= "供给";
                }
                $tmpContent .= "信息，信息内容为:".$content;
                if (!empty($tmpFuns)) {
                    foreach ($tmpFuns as $v) {
                        M ("qapp:robotMsg")->saveRobotMsg ($pur_id, $user_id, $v['user_id'], $tmpContent, $type = 1);
                        usleep (10);
                    }
                }
                $this->success ('提交成功');
            }
            //
            foreach ($data as $key => $value) {
                //是否已有该产品
                $model = $this->db->from ('product p')->join ('factory f', 'p.f_id=f.fid');
                //                 $where="p.model='{$value['model']}' and p.product_type={$value['product_type']} and f.f_name='{$value['f_name']}'";
                $where          = "p.model='{$value['model']}'  and f.f_name='{$value['f_name']}'";
                $pid            = $model->where ($where)->select ('p.id')->getOne ();
                $value['price'] = sprintf ("%.2f", $value['price']);
                $value['model'] = $this->clearStr ($value['model']);
                $value['model'] = trim ($value['model']);
                $value['model'] = strtoupper ($value['model']);
                if (empty($value['model'])) {
                    $value['model'] = '';
                }
                $_data = array(
                    'user_id'          => $user_id,
                    //用户id
                    'c_id'             => $soms['c_id'],
                    //客户id
                    'model'            => $value['model'],
                    //牌号
                    'customer_manager' => $soms['customer_manager'],
                    //交易员
                    //'number' => $value['number'],//吨数
                    'unit_price'       => $value['price'],
                    //单价
                    'provinces'        => $value['provinces'],
                    //省份id
                    'store_house'      => $value['store_house'],
                    //仓库
                    //'period' => $value['period'],//期货周期
                    //'bargain' => $value['bargain'],//是否实价
                    'type'             => $type,
                    //采购、报价
                    'sync'             => 6,
                    //报价来源平台
                    'quan_type'        => $quan_type,
                    'status'           => $type == 1 ? 1 : 2,
                    //状态，报价不需要审核，采购需要审核
                    'input_time'       => CORE_TIME,
                    //创建时间
                    // 'remark' => $remark,//备注
                    'content'          => $content,
                    //客户直接填写的求购内容
                );

                if ($pid) {
                    //已有产品直接添加采购信息
                    $_data['p_id'] = $pid;//产品id
                    $pur_model->startTrans ();
                    if ($pur_model->add ($_data)) {
                        $pur_id = $pur_model->getLastID ();
                        $pur_model->commit ();
                    } else {
                        $pur_model->rollback ();
                    }
                } else {
                    //没有产品则新增一个产品
                    $pur_model->startTrans ();
                    try {
                        // 是否已有厂家
                        $f_id = $fac_model->where ("f_name='{$value['f_name']}'")->select ('fid')->getOne ();
                        if (!$f_id) {
                            //创建新厂家
                            $_factory = array(
                                'f_name'     => $value['f_name'],
                                //厂家名称
                                'input_time' => CORE_TIME,
                                //创建时间
                            );
                            if (!$fac_model->add ($_factory)) {
                                throw new Exception("系统错误 pubpur:101");
                            }
                            $f_id = $fac_model->getLastID ();
                        }
                        $_product = array(
                            'model'        => $value['model'],
                            //牌号
                            'product_type' => $value['product_type'],
                            //产品类型
                            'process_type' => $value['process_level'],
                            //加工级别
                            'input_time'   => CORE_TIME,
                            //创建时间
                            'f_id'         => $f_id,
                            //厂家id
                            'status'       => 3,
                            //审核状态
                        );
                        if (!$pro_model->add ($_product)) {
                            throw new Exception("系统错误 pubpur:102");
                        }
                        $pid           = $pro_model->getLastID ();
                        $_data['p_id'] = $pid;
                        if (!$pur_model->add ($_data)) {
                            throw new Exception("系统错误 pubpur:103");
                        }
                        $pur_id = $pur_model->getLastID ();
                    } catch (Exception $e) {
                        $pur_model->rollback ();
                        $this->error ($e->getMessage ());
                    }
                    $pur_model->commit ();
                }
                //                if ($type == 2) {//报价
                //                    $spoints = intval (M ('system:setting')->get ('points')['points']['sale']);
                //                    if (!M ("qapp:pointsBill")
                //                        ->select ('id')
                //                        ->where ("addtime >".strtotime (date ("Y-m-d"))."  and type=3 and uid=".$user_id)
                //                        ->order ("id desc")
                //                        ->getOne ()
                //                    ) {
                //                        if (!$arr = M ("qapp:pointsBill")->addPoints ($spoints, $user_id, 3)) {
                //                            $this->json_output (array( 'err' => 5, 'msg' => "系统错误 pubpur:103" ));
                //                        }
                //                    }
                //                } elseif ($type == 1) {//采购
                //                    $spoints = intval (M ('system:setting')->get ('points')['points']['pur']);
                //                    if (!M ("qapp:pointsBill")
                //                        ->select ('id')
                //                        ->where ("addtime >".strtotime (date ("Y-m-d"))." and type=6 and uid=".$user_id)
                //                        ->order ("id desc")
                //                        ->getOne ()
                //                    ) {
                //                        if (!$arr = M ("qapp:pointsBill")->addPoints ($spoints, $user_id, 6)) {
                //                            $this->json_output (array( 'err' => 5, 'msg' => "系统错误 pubpur:103" ));
                //                        }
                //                    }
                //                }

                //robot表插入消息
                $tmpFuns    = M ("qapp:plasticIntroduction")->getMyFunsId ($user_id, 1);
                $tmpContent = "您关注的";
                $tmpContent .= M ("public:common")->model ('customer_contact')->select ('name')
                                                  ->where ("user_id=".$user_id)->getOne ();
                $tmpContent .= "发布了1条";
                if ($type == 1) {
                    $tmpContent .= "求购";
                } elseif ($type == 2) {
                    $tmpContent .= "供给";
                }
                $tmpContent .= "信息，信息内容为:";
                $tmpContent .= '价格'.$value['price'].'元左右/'.$value['model'].'/'.$value['f_name'].'/'.$value['store_house'];
                if (!empty($tmpFuns)) {
                    foreach ($tmpFuns as $v) {
                        M ("qapp:robotMsg")->saveRobotMsg ($pur_id, $user_id, $v['user_id'], $tmpContent, $type = 1);
                        usleep (10);
                    }
                }
            }
            /**
             *  添加到redis里面
             */
            // M("suggestion:suggestion")->suggestion_purchase($user_id,$pur_id);
            /**
             * 把供求需要的牌号添加到suggestion_model中
             */
            if (!empty($data[0]['model'])) {
                $suggestion_model_arr = array(
                    'user_id'     => $user_id,
                    'name'        => $data[0]['model'],
                    'type'        => 'ME',
                    'create_time' => date ("Y-m-d H:i:s"),
                    'pur_type'    => $data[0]['type'],
                );
                M ("public:common")->model ('suggestion_model')->add ($suggestion_model_arr);
            }


            $this->success ('提交成功');
        }
        $this->_errCode (6);
    }

    public function deleteRepeat ()
    {
        $this->is_ajax = true;
        if ($_POST) {
            $this->checkAccount ();
            $id     = sget ('id', 'i', 0);
            $result = M ('plasticzone:plasticMyMsg')->deleteRepeat ($id);
            $this->json_output ($result);
        }
        $this->_errCode (6);
    }

    //回复供求消息
    public function saveMsg ()
    {
        $this->is_ajax = true;
        if ($_POST) {
            $user_id = $this->checkAccount ();
            // $user_id = sget('user_id','i',0)//回复人id
            $pur_id  = sget ('pur_id', 'i', 0);//purchase表的消息id
            $send_id = sget ('send_id', 'i', 0);//purchase表发报价或采购人的(pur.user_id)
            $content = sget ('content', 's');//回复的内容
            if (empty($content)) {
                $this->json_output (array(
                    'err' => 6,
                    'msg' => '回复内容不能为空',
                ));
            }

            //robot表插入消息
            $where  = "pur.id=$pur_id";
            $detail = M ('product:purchase')->getPurchaseLeftById ($where, null, null);//p($detail);showTrace();exit;
            $value  = $detail;
            if (empty($value['content'])) {
                if ($value['unit_price'] == 0.00 && empty($value['model']) && empty($value['f_name']) && empty($value['store_house'])) {
                    $value['contents'] = '';
                } else {
                    $value['contents'] = '价格'.$value['unit_price'].'元左右/'.$value['model'].'/'.$value['f_name'].'/'.$value['store_house'];
                }
            } elseif (!empty($value['content'])) {
                if ($value['unit_price'] == 0.00 && empty($value['model']) && empty($value['f_name']) && empty($value['store_house'])) {
                    $value['contents'] = $value['content'];
                } else {
                    $value['contents'] = '价格'.$value['unit_price'].'元左右/'.$value['model'].'/'.$value['f_name'].'/'.$value['store_house'].'/'.$value['content'];
                }
            }
            $value['input_time'] = date ("m-d H:i", $value['input_time']);
            $tmpContent          = "您于 ".$value['input_time']." 发布的";
            if ($value['type'] == 1) {
                $tmpContent .= "求购";
            } elseif ($value['type'] == 2) {
                $tmpContent .= "供给";
            }
            $tmpContent .= "信息:".$value['contents']."收到一条回复:$content";
            M ("qapp:robotMsg")->saveRobotMsg ($pur_id, $send_id, $send_id, $tmpContent, $type = 2);
            //$tmpContent.=M("public:common")->model('customer_contact')->select('name')->where("user_id=".$user_id)->getOne();
            $result = M ('qapp:plasticRepeat')->saveMsg ($user_id, $pur_id, $send_id, $content);
            if ($result) {
                $this->json_output (array(
                    'err' => 0,
                    'msg' => '回复消息保存成功',
                ));
            }
        }
        $this->_errCode (6);
    }

    //获取我的供给或求购
    public function getMyMsg ()
    {
        $this->is_ajax = true;
        if ($_POST) {
            $user_id = $this->checkAccount ();
            $page    = sget ('page', 'i', 1);
            $size    = sget ('size', 'i', 10);
            $type    = sget ('type', 'i');//1采购 2报价
            $data    = M ('qapp:plasticMyMsg')->getMyMsg ($user_id, $page, $size, $type);
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

    //删除我的供给或求购
    public function deleteMyMsg ()
    {
        $this->is_ajax = true;
        if ($_POST) {
            $this->checkAccount ();
            $id     = sget ('id', 'i');//当前我的报价或采购的id
            $result = M ('plasticzone:plasticMyMsg')->deleteMyMsg ($id);
            $this->json_output ($result);
        }
        $this->_errCode (6);
    }


    //获取我的(留言)
    public function getMyComment ()
    {
        $this->is_ajax = true;
        if ($_POST) {
            $user_id = $this->checkAccount ();
            $page    = sget ('page', 'i', 1);
            $size    = sget ('size', 'i', 10);
            $data    = M ('qapp:plasticMyMsg')->getMyComment ($user_id, $page, $size, 6);//塑料圈app
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


    /*
     * 二次发布
     */
    public function secondPub ()
    {
        if ($_POST) {
            $id = sget ('id', 'i');
            if (empty($id)) {
                $this->_errCode (6);
            }
            $this->checkAccount ();
            $where = " pur.sync = 6 and pur.id=$id ";
            $data  = M ("product:purchase")->getPurchaseLeftById ($where);
            if (empty($data['content'])) {
                if ($data['unit_price'] == 0.00 && empty($data['model']) && empty($data['f_name']) && empty($data['store_house'])) {
                    $this->json_output (array(
                        'err' => 1,
                        'msg' => '此记录输入有误，请手动补充',
                    ));
                }
                $data['f_type'] = 1;//格式化输出
            } elseif (!empty($data['content'])) {
                if ($data['unit_price'] == 0.00 || empty($data['model']) || empty($data['f_name']) || empty($data['store_house'])) {
                    $data['f_type'] = 2;//未格式化输出
                } else {
                    $data['f_type'] = 1;//格式化输出
                }
            }
            if (empty($data)) {
                $this->_errCode (2);
            }
            $this->_errCode (0, $data);
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
}