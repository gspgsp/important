<?php
/**
 * Created by PhpStorm.
 * User: $ yjy
 * Date: 2017/2/23
 * Time: 9:07
 * 物流供应商管理
 */
class supplierAction extends adminBaseAction{

    public function __init(){
        $this->debug=false;
        $this->db=M('public:common')->model('logistics_supplier');
    }

    /**
     * 供应商列表
     *
     */
    public function init(){
        $action=sget('action','s');
        if($action=='grid'){ //获取列表
            $this->_grid();exit;
        }elseif($action=='remove'){ //获取列表
            $this->_remove();exit;
        }elseif($action=='submit'){ //获取列表
            $this->_submit();exit;
        }elseif($action=='save'){ //获取列表
            $this->_save();exit;
        }

        $doact=sget('do');
        $this->assign('doact',$doact);
        $this->display('supplier.html');

    }

    public function _grid(){
        $page = sget("pageIndex",'i',0); //页码
        $size = sget("pageSize",'i',20); //每页数
//        $pt = sget("pt",'i',2);
        $sortField = sget("sortField",'s','supplier_id'); //排序字段
        $sortOrder = sget("sortOrder",'s','desc');        //排序
        $where = ' 1 ';
//        $where .= ' and `status` != 10 ';
//        // 筛选合作客户
//        if(sget('cooperation','i',0) == 1) $where .= ' and `is_sale` = 1 and `customer_manager` > 0  ';
//        // 筛选供应商
//        if(sget('supplier','i',0) == 1) $where .= ' and `is_pur` = 1  and `customer_manager` > 0';
//        //私海客户
//        if(sget('privated','i',0) == 1) $where .= ' and `is_pur` = 0 and  `is_sale` = 0  and `customer_manager` > 0 ';
//        // if($pt ==2) $where .= ' and `status` != 9 and  `status` != 8';
//        // pt主要标示黄名单客户的一些信息（8代表黄明单）
//        if($pt ==2) $where .= ' and `status` != 9 and  `status` != 8 ';
//        if($pt ==1) $where .= ' and `status` != 9 and chanel != 6 ';
//        $where .= $this->public == 0 ? ' and `customer_manager` != 0 ' : ' and `customer_manager` = 0 ';
//        $sTime = sget("sTime",'s','input_time'); //搜索时间类型
//        $where.=getTimeFilter($sTime); //时间筛选
//        $status = sget("status",'s',''); //状态
//        if($status!='') $where.=" and status='$status' ";
//        $type = sget("type",'s',''); //状态
//        if($type!='') $where.=" and type='$type' ";//type 客户类型
//        $invoice = sget("invoice",'i',''); //开票资料状态
//        if($invoice != 0) $where .=" and invoice=$invoice ";//type 客户类型
//        $level = sget("level",'s',''); //状态
//        if($level!='') $where.=" and level='$level' ";//level 客户级别
//        $identification = sget("identification",'s',''); //认证
//        if($identification!='') $where.=" and identification='$identification' ";
//        // 关键词
//        $key_type=sget('key_type','s','c_id');
//        $keyword=sget('keyword','s');
//        if(!empty($keyword)){
//            if($key_type=='c_name'){
//                $cidshare = M('user:customer')->getcidByCname($keyword);
//                $where.=" and $key_type like '%$keyword%' ";
//            }elseif($key_type=='customer_manager'){
//                $adms = join(',',M('rbac:adm')->getIdByName($keyword));
//                $where.=" and $key_type in ($adms) ";
//            }else{
//                $where.=" and $key_type='$keyword' ";
//            }
//        }
//        //接收由cid组成的字符串（1,2,3,4）
//        $cids=sget('cids','s');//
//        if($cids)  $where.=" and `c_id` in ".$cids;
        //筛选自己的客户
//        if($this->public == 0 && $this->moreChoice == 0){
//            if($_SESSION['adminid'] != 1 && $_SESSION['adminid'] > 0){
//                $sons = M('rbac:rbac')->getSons($_SESSION['adminid']);  //领导
//                $pools = M('user:customer')->getCidByPoolCus($_SESSION['adminid']); //共享客户
//                $where .= " and `customer_manager` in ($sons) ";
//                if(!empty($cidshare)){
//                    // if(empty($keyword)){
//                    $where .= " or `c_id` in ($cidshare)";
//                    // }
//                }else{
//                    if(!empty($pools)){
//                        $cids = explode(',', $pools);
//                        $where .= " or `c_id` in ($pools)";
//                    }
//                }
//            }
//        }
        $list=$this->db ->where($where)->page($page+1,$size)->order("$sortField $sortOrder")->getPage();

        // p($where);
        foreach($list['data'] as $k=>$v){
//            $list['data'][$k]['customer_manager'] = M('rbac:adm')->getUserByCol($v['customer_manager']);

              $list['data'][$k]['type']=L('supplier_type')[$v['type']];  //  供应商类型
              $list['data'][$k]['status']=L('status')[$v['status']];     // 供应商状态
              $list['data'][$k]['create_time']=date('y-m-d H:i:s',$v['create_time']);  // 创建时间
              $list['data'][$k]['update_time']=date('y-m-d H:i:s',$v['update_time']);  // 更新时间
              $list['data'][$k]['supplier_name']=$v['supplier_name'];    // 供应商名称

//            $list['data'][$k]['chanel']=L('company_chanel')[$v['chanel']];//客户渠道
//            $list['data'][$k]['level']=L('company_level')[$v['level']];
//            $list['data'][$k]['depart']=C('depart')[$v['depart']];
//            // $list['data'][$k]['identification']=L('identification')[$v['identification']];//认证
//            $list['data'][$k]['type']=L('company_type')[$v['type']];
//            $list['data'][$k]['input_time']=$v['input_time']>1000 ? date("y-m-d H:i",$v['input_time']) : '-';
//            $list['data'][$k]['update_time']=$v['update_time']>1000 ? date("y-m-d H:i",$v['update_time']) : '-';
//            $list['data'][$k]['chk'] = $this->_accessChk();
//            //获取联系人的姓名和手机号
//            $contact = $this->db->model('customer_contact')->select('name,mobile,tel')->where('user_id='.$v['contact_id'])->getRow();
//            $list['data'][$k]['name'] = in_array($v['c_id'],$cids) ? '******' : $contact['name'];
//            $list['data'][$k]['mobile'] = in_array($v['c_id'],$cids) ? '******' : $contact['mobile'];
//            $list['data'][$k]['tel'] = in_array($v['c_id'],$cids) ? '******' : $contact['tel'];
//            //获取最新一次跟踪消息
//            $message = $this->db->model('customer_follow')->select('remark')->where('c_id='.$v['c_id'])->order('input_time desc')->getOne();
//            $list['data'][$k]['remark'] = $message;
//            $list['data'][$k]['bli'] = $this->db->model('customer_billing')->select('id')->where("`c_id`={$v['c_id']}")->getOne();
//            $list['data'][$k]['invoice'] =  $v['invoice']==2 ? '是' : '否';

        }
        $this->assign('isPublic',$this->public);
        $result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>'');
        $this->json_output($result);
    }

    /**
     * 1、新增供应商按钮
     * 2、查看供应商信息
     */
    public function addSupplier(){
        $supplier_id=sget('id','i');
        $cType=sget('ctype','i'); //用户类型
        $this->assign('regionList', arrayKeyValues(M('system:region')->get_reg(),'id','name'));
        $this->assign('sex',L('sex'));    // 性别
        $this->assign('ctype',$cType);    //人员类型
        //**************************
        if($supplier_id<1){
            if($cType==1){
                $this->assign('ctype',$cType);                      //单页面新增供应商联系人
                $this->assign('page_title','新增个人联系人-1');
                $this->assign('status_1',L('status_1'));            // 供应商联系人状态
                $this->display('supplier_contact.html');
            }elseif($cType==3){                                     //新增供应商
                $this->assign('ctype',$cType);
                $this->assign('is_pur',sget('supplier'));           //添加客户的入口
                $this->assign('type',L('supplier_type'));           //供应商类型
                $this->assign('status_1',L('status_1'));            // 供应商联系人状态
                $this->assign('status',L('status'));                // 供应商状态
                $this->assign('credit_level',L('credit_level'));    //信用等级
                $this->assign('page_title','新增企业用户-2');

                $this->display('add_supplier.html');
            }
            exit;
        }
        //***************************
        //查询供应商信息
        $info=$this->db->getPk($supplier_id);
        if(empty($info)){
            $this->error('错误的公司信息');
        }
        $info['business_licence_pic'] = FILE_URL.'/upload/'.$info['business_licence_pic'];     // 营业执照图片
        $info['organization_code_pic'] = FILE_URL.'/upload/'.$info['organization_code_pic'];   // 组织机构代码证图片
        $info['tax_registration_pic'] = FILE_URL.'/upload/'.$info['tax_registration_pic'];     // 税务登记证图片
        $info['legal_person_pic_1'] = FILE_URL.'/upload/'.$info['legal_person_pic_1'];         // 身份证图片(正面)
        $info['legal_person_pic_2'] = FILE_URL.'/upload/'.$info['legal_person_pic_2'];         // 身份证图片(反面)
        $info['social_credit_code_pic'] = FILE_URL.'/upload/'.$info['social_credit_code_pic']; // 三证合一图片
        //联系人详情
        $this->assign('c_id',$supplier_id);                      //供应商id
        $this->assign('regionList', arrayKeyValues(M('system:region')->get_reg(),'id','name'));//第一级省市
        $this->assign('type',L('supplier_type'));                  //供应商类型
        $this->assign('status',L('status'));                       // 供应商状态
        $this->assign('credit_level',L('credit_level'));           //信用等级
        $this->assign('info',$info);
        $this->display('show_supplier.html');

    }

    /**
     * 提交过来的新增供应商和联系人信息
     *
     */
    public function addSubmit() {
        $this->is_ajax=true;

        $data = sdata();
        $data['supplier_name'] = trim($data['supplier_name']);
        $ctype = $data['ctype'];    // ctype   1：新增供应商联系人   3：新增供应商
//        p($ctype);
//        p($data);die;
        if($ctype==1){              //单独新增供应商联系人
            if(empty($data['mobile']) && empty($data['tel'])) $this->error('手机或者电话至少填写一个');
            //验证联系人信息
            $param=array(
                'mobile'=>$data['mobile'],
                'email'=>$data['email'],
                'qq'=>$data['qq'],
            );
        }
        // 新增供应商和联系
        $this->db->startTrans();
        try{
            if($ctype==3){
                if(empty($data['mobile_tel']) && empty($data['contact_tel'])) $this->error('手机或者电话至少填写一个');
                //验证公司信息
                $param_1=array(                                                  // logistics_supplier 表
                    'supplier_name'=>trim($data['supplier_name']),               //   供应商名称
                    'legal_person'=> trim($data['legal_person']),                //   法人姓名
                    'legal_person_code' =>trim($data['legal_person_code']),      // 法人身份证号码
                    'legal_person_pic_1' => $data['legal_person_pic_1'],         // 身份证 正面
                    'legal_person_pic_2' => $data['legal_person_pic_2'],         // 法人身份证 反面
                    'company_tel'  =>  trim($data['company_tel']),                     // 公司固话
                    'invoice_tel'  =>  trim($data['invoice_tel']),                     // 开票电话
                    'invoice_bank' =>  trim($data['invoice_bank']),                    //  开票银行
                    'invoice_account' => trim($data['invoice_account']),                  // 开票账户
                    'invoice_address' > trim($data['invoice_address']),                   // 开票地址
                    'province'   => $data['province'],                              //　省份
                    'city'      =>  $data['company_city'],                          // 城市
                    'zip_code'   => trim($data['zip_code']),                              // 邮编
                    'fund_date'  => $data['fund_date'],                             //成立时间
                    'register_capital'  => trim($data['register_capital']),               // 注册资本
                    'credit_level'  => $data['credit_level'],                       // 信用等级
                    'status'=>   $data['status'],                                   //审核状态
                    'merge_three'=>$data['cards'],                                  // 是否三证合一
                    'business_licence_code' => trim($data['business_licence_code']),      // 营业执照号码
                    'business_licence_pic'  => $data['business_licence_pic'],       // 营业执照照片
                    'organization_code'     => trim($data['organization_code']),          //组织机构代码
                    'organization_code_pic' => $data['organization_code_pic'],      // 组织机构代码照片
                    'tax_registration'      => trim($data['tax_registration']),           // 税务登记证号码
                    'tax_registration_pic'  => $data['tax_registration_pic'],       // 税务登记证照片
                    'tax_id'     => trim($data['tax_id']),                                // 纳税人识别号
                    'social_credit_code' => $data['social_credit_code'],            // 社会统一信证码
                    'social_credit_code_pic' => $data['social_credit_code_pic'],    // 三证合一照片
                    'create_time' => time(),                                        // 创建时间
                    'create_name' => trim($_SESSION['name']),                        // 创建者
                );
                $param_2= array(
                    'contact_name' => trim($data['contact_name']), // 供应商联系人name
                    'supplier_name'=>trim($data['supplier_name']),  //  供应商名称
                    'sex'   => $data['sex'],                 // 性别
                    'mobile_tel'=> trim($data['mobile_tel']),       // 联系人手机
                    'contact_tel' => trim($data['contact_tel']),  // 联系人固话
                    'qq'  =>  trim($data['qq']),                  //联系人QQ
                    'comm_fax' => trim($data['comm_fax']),        // 传真
                    'comm_email' => trim($data['comm_email']),    // 联系人邮箱
                    'default'=>1,                           // 是否默认联系人   1：是 2：否
                    'create_time' => time(),               // 创建时间
                    'create_name' => trim($_SESSION['name']),    // 创建者
                    'status'=> $data['status_1'],          // 状态
                );

            }
                    $info=$this->db->add($param_1);                                  // 返回受影响行数
                       p($info);
                    if($info==1){
                        $param['supplier_id']=$this->db->getLastID();               //  返回自增id
                        $param_2['supplier_id']=$param['supplier_id'];
                        $res=$this->db->model('logistics_contact')->add($param_2);   // 返回受影响行数
                        showTrace();
                        if($res!=1) $this->db->getError('供应商联系人新增失败');
                    }else{
                        $this->db->getError('供应商新增失败');
                    }
            $this->db->commit();
        }catch (\Exception $e){
            $this->db->rollback();
        }

        $this->success('操作成功');
    }


    /**
     *查看供应商信息
     *
     */
    public function showSupplier(){





        $this->display('supplier_info.html');
    }


    /**
     * 修改供应商信息
     *
     */
    public function editSubmit(){

    }


    /**
     *供应商查寻重复
     *
     */
    public function supplierUnique(){
        $this->is_ajax=true;
        $supplier_name=sget('supplier_name','i');
        if($supplier_name){
            $info=M('operator:logistics_supplier')->supplierUnique($supplier_name);
            if($info) $this->error("存在相同的公司名称");
            $this->success('此公司名不重复，可添加');
        }

    }


}