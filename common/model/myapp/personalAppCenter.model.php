<?php
/**
*个人中心模型-app-gsp
*/
class personalAppCenterModel extends model
{
    public function __construct() {
        parent::__construct(C('db_default'), 'purchase');
    }
    //获取我的采购或报价单的数量(1采购 2报价)
    public function getMyQuotationCount($user_id,$type){
        return count($this->model('purchase')->where("user_id=$user_id and type=$type")->getAll());
    }
    //获取我的关注的数量(产品)
    public function getMyAttentionCount($user_id){
        return count($this->model('concerned_product')->where("user_id=$user_id and status=1")->getAll());
    }
    //获取我的交易员
    public function getMyCusManager($user_id){
        $admin_id = $this->model('customer_contact')->select('customer_manager')->where('user_id='.$user_id)->getOne();
        $cus_m = $this->model('admin')->select('name')->where('admin_id='.$admin_id)->getOne();
        return $cus_m;
    }
    //获取我的报价单/采购单
    public function getMyQuotation($user_id,$type,$product_type,$shelve_type,$cargo_type,$keywords,$page=1,$size=10,$sortField='input_time',$sortOrder='desc'){
        $where = "pur.user_id=$user_id and pur.type=$type";
        if(!empty($product_type)){
            $where.=" and pro.product_type=$product_type";
        }
        if(!empty($shelve_type)){
            $where.=" and pur.shelve_type=$shelve_type";
        }
        if(!empty($cargo_type)){
            $where.=" and pur.cargo_type=$cargo_type";
        }
        if(!empty($keywords)){
            $p_types = array(1=>'HDPE',2=>'LDPE',3=>'LLDPE',4=>'均聚PP',5=>'PVC',6=>'共聚PP',7=>'ABS',8=>'PC',9=>'MABS');
            if(in_array($keywords, $p_types)){
                $keyValue = $this->_getProKey($p_types,$keywords);
            }
            $where.=" and (fa.f_name like '%{$keywords}%' or pro.model like '%{$keywords}%' or pro.product_type='{$keyValue}')";
        }
        $data = $this->model('purchase')->select('pur.id,pur.p_id,pur.number,pur.provinces,pro.model,pro.product_type,pur.unit_price,pur.store_house,pur.shelve_type,fa.f_name,pur.input_time')->from('purchase pur')
            ->join('product pro','pur.p_id=pro.id')
            ->join('factory fa','pro.f_id=fa.fid')
            ->page($page,$size)
            ->where($where)
            ->order("$sortField $sortOrder")
            ->getPage();
        foreach ($data['data'] as &$value) {
            $value['product_type'] = L('product_type')[$value['product_type']];
            $value['input_time'] = date("Y-m-d",$value['input_time']);
            //获取洽/委托洽谈谈数据
            if($type==1){
                $value['count'] = count($this->getOptions($value['id']))>0?count($this->getOptions($value['id'])):0;
            }
        }
        return $data;
    }
    //获取洽谈数据(求购信息有人报价)
    public function getOptions($id){
        $today = strtotime(date('Y-m-d',time()));
        $options = $this->model('sale_buy')->select("id,p_id,c_id,number,price,delivery_place,delivery_date,ship_type,remark")->where("p_id=$id and expiry_date>$today and status=1")->getAll();
        foreach ($options as &$value) {
            $value['c_name'] = M('user:customer')->getColByName($value['c_id']);
            $value['delivery_place'] = $this->_getProvinceById($value['delivery_place']);
            $value['delivery_date'] = date("Y-m-d",$value['delivery_date']);
            $value['ship_type'] = L('ship_type')[$value['ship_type']];
        }
        return $options;
    }
    //选定报价
    public function selectPrice($userid,$id,$price){
        $model=$this->model('sale_buy');
        if(!$data=$model->where("id=$id")->getRow()) return array('err'=>2,'msg'=>'中间表信息不存在');//根据id信息未找到
            $purModel=M('product:purchase');
            if( !$purData=$purModel->where("id={$data['p_id']} and user_id=$userid")->getRow() ) return array('err'=>3,'msg'=>'报价表信息不存在');//报价表与用户不匹配
            if($purData['last_buy_sale']) return array('err'=>4,'msg'=>'不能重复选定');
                $orderModel=M('product:unionOrder');

                $purModel->where("id={$data['p_id']} and user_id=$userid")->update(array('last_buy_sale'=>$id,'status'=>3));
                $orderSn=$this->genOrderSn(1);
                $orderData=array();
                $orderData['order_name']="联营订单";
                $orderData['order_sn']=$orderSn;
                $orderData['order_source']=3;//3 app
                $orderData['sale_id']=$purData['c_id'];//卖家客户
                $orderData['buy_id']=$data['c_id'];//买家客户
                $orderData['sale_user_id']=$purData['user_id'];//卖家客户(供货)
                $orderData['buy_user_id']=$data['user_id'];//买家客户(求购)
                $orderData['p_sale_id']=$id;//sale_buy的报价id
                $orderData['sign_time']=CORE_TIME;
                $orderData['sign_place']='网站签约';
                $orderData['deal_price']=$price;//成交价格
                $orderData['total_price']=$price*$data['number'];//总金额
                $orderData['customer_manager']=$purData['customer_manager'];//交易员
                $orderData['pickup_location']=$data['delivery_place'];//提货地点
                $orderData['delivery_location']=$data['delivery_place'];//送货地点
                $orderData['transport_type']=$data['ship_type'];//运输方式
                $orderData['deal_price']=$price;//成交价格
                $orderData['pickup_time']=$data['delivery_date'];//提货时间
                $orderData['delivery_time']=$data['delivery_date'];//送货时间
                $orderData['input_time']=CORE_TIME;//成交时间
                $orderModel->add($orderData);
                $o_id=$orderModel->getLastID();

                $orderDetail=M('product:unionOrderDetail');
                $detail_data=array(
                    'o_id'=>$o_id,
                    'p_id'=>$purData['p_id'],
                    'number'=>$data['number'],
                    'unit_price'=>$price,
                    'input_time'=>CORE_TIME,
                );
                $orderDetail->add($detail_data);

                $model->where("p_id={$data['p_id']}")->update(array('status'=>8,'update_time'=>CORE_TIME));//更新其他报价为未选中
                $model->where("id=$id")->update(array('status'=>3,'update_time'=>CORE_TIME));//更新选中状态，将选中的标为选中状态
            // $modelName=$this->db->model('product')->where("id={$purData['p_id']}")->select('model')->getOne();
            // $msg=L('msg_template.union_order');
            // $msg=sprintf($msg,$modelName,$price,$o_id);
            // M("system:sysMsg")->sendMsg($data['user_id'],$msg,5);//联营订单站内信
            return array('err'=>0,'msg'=>'操作成功');
    }
    //生成订单编号
    public function genOrderSn($type=1){
        $date=date("YmdHis");
        //日期+交易类型+时分+6为随机
        return substr($date,0,8).str_pad($type, 4, '0', STR_PAD_RIGHT).substr($date,8,4)
                .str_pad(mt_rand(1000, 999999), 6, '0', STR_PAD_LEFT);
    }
    //获取我的订单(1自营、2联营)
    public function getMyOrder($userid,$type,$keywords,$otype,$page=1,$size=10,$sortField='input_time',$sortOrder='desc'){
        if($type==1){
            $where = "user_id=$userid";
            if(!empty($otype)){
                if($otype<4){
                    $where.=" and order_status = {$otype} ";
                }elseif ($otype>3 && $otype<7) {
                    $otype-=3;
                    $where.=" and out_storage_status = {$otype} ";//出库状态
                }elseif ($otype>6) {
                    $otype-=6;
                    $where.=" and collection_status = {$otype} ";
                }
            }
            $data = $this->model('order')->select('o_id,order_name,order_sn,order_type,order_status,out_storage_status,collection_status,invoice_status,total_num,total_price,freight_price')
            ->page($page,$size)
            ->where($where)
            ->order("$sortField $sortOrder")
            ->getPage();
            foreach ($data['data'] as &$value) {
                $detail = array();//存放搜索后的数据
                $sale_log = $this->model('sale_log')->select("id,o_id,p_id,number,unit_price")->where("o_id=".$value['o_id'])->getAll();
                foreach ($sale_log as $k => $v) {
                    $where1 = "pro.id={$v['p_id']}";
                    if(!empty($keywords)){
                        if(in_array($keywords, L('product_type'))){
                            $keyValue = $this->_getProKey(L('product_type'),$keywords);
                        }
                        $where1.=" and (fa.f_name like '%{$keywords}%' or pro.model like '%{$keywords}%' or pro.product_type='{$keyValue}')";
                    }
                    $del = $this->model('product')->select('pro.id,pro.model,fa.f_name,pro.product_type')
                        ->from('product pro')
                        ->join('factory fa','pro.f_id=fa.fid')
                        ->where($where1)
                        ->getRow();
                    $del['product_type'] = L('product_type')[$del['product_type']];
                    $del['number'] = number_format($v['number'], 2, '.', '');
                    $del['unit_price'] = $v['unit_price'];
                    if(!empty($del['id'])){
                        $detail[] =$del;//新数组保存每一条信息(主要针对关键字搜索功能)
                    }
                    unset($del);
                }
                //判断状态
                $value['cur_state'] = $this->_getSelfCurrentState($value['order_status'],$value['out_storage_status'],$value['collection_status'],$value['invoice_status']);
                $value['total_num'] = number_format($value['total_num'], 2, '.', '');
                $value['detail'] = !empty($detail)?$detail:'暂无数据';//多条信息
                // $value['count'] = count($sale_log)>0?count($sale_log):0;
                $value['count'] = count($detail)>0?count($detail):0;
                $value['order_type'] = L('order_type')[$value['order_type']];
                $value['order_status'] = L('order_status')[$value['order_status']];
                $value['out_storage_status'] = L('out_storage_status')[$value['out_storage_status']];//出库状态
                $value['collection_status'] = L('collection_p_status')[$value['collection_status']];//付款状态
                $value['invoice_status'] = L('invoice_status')[$value['invoice_status']];
            }
            if(empty($detail)) return false;
            return array('err'=>0,'data'=>$data['data']);
        }elseif ($type==2) {
            $where="buy_user_id=$userid";
            if(!empty($otype)){
                if($otype<4){
                    $where.=" and order_status = {$otype} ";
                }elseif ($otype>3 && $otype<7) {
                    $otype-=3;
                    $where.=" and goods_status = {$otype} ";//发货状态
                }elseif ($otype>6) {
                    $otype-=6;
                    $where.=" and collection_status = {$otype} ";
                }
            }
            $orderList=$this->model('union_order')
                            ->select('id,type,order_name,order_sn,sale_id,total_price,freight_price,order_status,goods_status,collection_status,invoice_status')
                            ->where($where)
                            ->page($page,$size)
                            ->order("$sortField $sortOrder")
                            ->getPage();
            foreach ($orderList['data'] as &$value) {
                $detail = array();//存放搜索后的数据
                $union_order_detail = $this->model('union_order_detail')->where("o_id={$value['id']}")->select("id,o_id,p_id,unit_price,number")->getAll();
                foreach ($union_order_detail as $k => $v) {
                    $where2 = "pro.id={$v['p_id']}";
                    if(!empty($keywords)){
                        // $where2 .= " and (fa.f_name like '%{$keywords}%' or pro.model like '%{$keywords}%' or pro.product_type='{$keyValue}') ";
                        if(in_array($keywords, L('product_type'))){
                            $keyValue = $this->_getProKey(L('product_type'),$keywords);
                        }
                        $where2.=" and (fa.f_name like '%{$keywords}%' or pro.model like '%{$keywords}%' or pro.product_type='{$keyValue}')";
                        //
                    }
                    $del = $this->model('product')->select('pro.id,pro.model,fa.f_name,pro.product_type')
                        ->from('product pro')
                        ->join('factory fa','pro.f_id=fa.fid')
                        ->where($where2)
                        ->getRow();
                    $del['product_type'] = L('product_type')[$del['product_type']];
                    $del['number'] = number_format($v['number'], 2, '.', '');
                    $del['unit_price'] = $v['unit_price'];
                    if(!empty($del['id'])){
                        $detail[] =$del;//新数组保存每一条信息(主要针对关键字搜索功能)
                    }
                    unset($del);
                }
                //判断状态
                $value['cur_state'] = $this->_getUnionCurrentState($value['order_status'],$value['goods_status'],$value['collection_status'],$value['invoice_status']);
                $value['order_type'] = L('order_type')[$value['type']];//type==order_type
                $value['detail'] = !empty($detail)?$detail:'暂无数据';//多条信息
                //$value['count'] = count($union_order_detail)>0?count($union_order_detail):0;
                $value['count'] = count($detail)>0?count($detail):0;
                $value['type'] = L('order_type')[$value['type']];
                $value['order_status'] = L('order_status')[$value['order_status']];
                $value['out_storage_status'] = L('goods_status')[$value['goods_status']];//发货状态
                $value['collection_status'] = L('collection_p_status')[$value['collection_status']];//付款状态
                $value['invoice_status'] = L('invoice_status')[$value['invoice_status']];
            }
            if(empty($detail)) false;
            return array('err'=>0,'data'=>$orderList['data']);
        }
    }
    //判断状态  自营
    private function _getSelfCurrentState($order_status,$out_storage_status,$collection_status,$invoice_status){
        $oArr = L('order_status');
        $outArr = L('out_storage_status');
        $colArr = L('collection_p_status');
        // $inArr = L('invoice_status');
        $cur_state = "";
        if($order_status==1){
            $cur_state = $oArr['1'];
        }elseif ($order_status==2) {
            if($out_storage_status==1){
                $cur_state = $outArr['1'];
            }elseif ($out_storage_status==2) {
                $cur_state = $outArr['2'];
            }elseif ($out_storage_status==3) {
                if($collection_status==1){
                    $cur_state = $colArr['1'];
                }elseif ($collection_status==2) {
                    $cur_state = $colArr['2'];
                }elseif ($collection_status==3) {
                    $cur_state = "已完成";
                }
            }
        }elseif ($order_status==3) {
            $cur_state = $oArr['3'];
        }
        return $cur_state;
    }
    //判断状态  联营
    private function _getUnionCurrentState($order_status,$goods_status,$collection_status,$invoice_status){
        $oArr = L('order_status');
        $goodsArr = L('goods_status');
        $colArr = L('collection_p_status');
        // $inArr = L('invoice_status');
        $cur_state = "";
        if($order_status==1){
            $cur_state = $oArr['1'];
        }elseif ($order_status==2) {
            if($goods_status==1){
                $cur_state = $goodsArr['1'];
            }elseif ($goods_status==2) {
                $cur_state = $goodsArr['2'];
            }elseif ($goods_status==3) {
                if($collection_status==1){
                    $cur_state = $colArr['1'];
                }elseif ($collection_status==2) {
                    $cur_state = $colArr['2'];
                }elseif ($collection_status==3) {
                    $cur_state = "已完成";
                }
            }
        }elseif ($order_status==3) {
            $cur_state = $oArr['3'];
        }
        return $cur_state;
    }
    //获取自营订单详情
    public function getOrderDetail($userid,$type,$id){
        if($type==1){
            $order = $this->model('order')->select("o_id,order_name,order_sn,order_status,out_storage_status,collection_status,invoice_status,order_type,c_id,user_id,total_price,pay_method,payment_way,payment_time,freight_price,transport_type,delivery_time,sign_time")->where("o_id=$id and user_id=$userid")->getRow();
            if(!$order) return array('err'=>2,'msg'=>'自营表信息不存在');
            $sale_log = $this->model('sale_log')->from('sale_log s')
                            ->leftjoin('product p','s.p_id=p.id')
                            ->leftjoin('factory f','p.f_id=f.fid')
                            ->select('s.id,s.o_id,s.p_id,s.number,s.unit_price,s.store_id,p.model,p.product_type,f.f_name')
                            ->where("o_id={$order['o_id']}")
                            ->getAll();
            foreach ($sale_log as $key => &$value) {
                $value['store_id'] = $this->_getStore($value['store_id']);
                $value['totalPrice']=$value['number']*$value['unit_price'];
                $value['product_type'] = L('product_type')[$value['product_type']];
                $value['number'] = number_format($value['number'], 2, '.', '');
            }
            //判断状态
            $order['cur_state'] = $this->_getSelfCurrentState($order['order_status'],$order['out_storage_status'],$order['collection_status'],$order['invoice_status']);
            $order['pay_method'] = L('pay_method')[$order['pay_method']];
            $order['payment_time'] = date("Y-m-d",$order['payment_time']);
            $order['transport_type'] = L('transport_type')[$order['transport_type']];
            $order['delivery_time'] = date("Y-m-d",$order['delivery_time']);
            $order['sign_time'] = date("Y-m-d",$order['sign_time']);
            $order['user_name'] = M('user:customerContact')->getListByUserid($order['user_id'])['name'];
            $order['user_mobile'] = M('user:customerContact')->getListByUserid($order['user_id'])['mobile'];
            $order['c_id'] = M('user:customer')->getColByName($order['c_id']);//公司
            $order['sale_log'] = $sale_log;
            $order['order_type'] = L('order_type')[$order['order_type']];
            $order['order_status'] = L('order_status')[$order['order_status']];
            $order['out_storage_status'] = L('out_storage_status')[$order['out_storage_status']];//出库状态
            $order['collection_status'] = L('collection_p_status')[$order['collection_status']];//付款状态
            $order['invoice_status'] = L('invoice_status')[$order['invoice_status']];
            return array('err'=>0,'order'=>$order);
        }elseif ($type==2) {
            $order = $this->model('union_order')->select("id,order_name,order_sn,order_status,goods_status,collection_status,invoice_status,type,sale_id,buy_user_id,total_price,pay_method,payment_time,freight_price,transport_type,delivery_time,sign_time")->where("o_id=$id and user_id=$userid")->getRow();
            $order['c_name']=$this->model('customer')->where("c_id={$order['sale_id']}")->select('c_name')->getOne();
            $union_order_detail = $this->db->from('union_order_detail s')
                                        ->leftjoin('product p','s.p_id=p.id')
                                        ->leftjoin('factory f','p.f_id=f.fid')
                                        ->select('s.id,s.o_id,s.p_id,s.number,s.unit_price,s.store_id,p.model,p.product_type,f.f_name')
                                        ->where("o_id={$order['id']}")
                                        ->getAll();
            foreach ($union_order_detail as $key => &$value) {
                $value['store_id'] = $this->_getStore($value['store_id']);
                $value['totalPrice']=$value['number']*$value['unit_price'];
                $value['product_type'] = L('product_type')[$value['product_type']];
                $value['number'] = number_format($value['number'], 2, '.', '');
            }
            //判断状态
            $order['cur_state'] = $this->_getUnionCurrentState($order['order_status'],$order['goods_status'],$order['collection_status'],$order['invoice_status']);
            $order['pay_method'] = L('pay_method')[$order['pay_method']];
            $order['payment_time'] = date("Y-m-d",$order['payment_time']);
            $order['transport_type'] = L('transport_type')[$order['transport_type']];
            $order['delivery_time'] = date("Y-m-d",$order['delivery_time']);
            $order['sign_time'] = date("Y-m-d",$order['sign_time']);
            $order['user_name'] = M('user:customerContact')->getListByUserid($order['buy_user_id'])['name'];
            $order['user_mobile'] = M('user:customerContact')->getListByUserid($order['buy_user_id'])['mobile'];
            $order['union_order_detail'] = $union_order_detail;
            $order['type'] = L('order_type')[$order['type']];
            $order['order_status'] = L('order_status')[$order['order_status']];
            $order['goods_status'] = L('goods_status')[$order['goods_status']];//发货状态
            $order['collection_status'] = L('collection_p_status')[$order['collection_status']];//付款状态
            $order['invoice_status'] = L('invoice_status')[$order['invoice_status']];
            return array('err'=>0,'order'=>$order);
        }
    }
    //保存自营申请开票
    // public function saveAppliBill($userid,$o_id){
    //     $order = $this->db->model('order')->select("o_id,invoice_status,order_type,c_id,user_id,total_price,pay_method,payment_way,payment_time,freight_price,transport_type,delivery_time,sign_time")->where("o_id=$id and user_id=$userid")->getRow();
    // }
    //根据仓库id获取仓库
    private function _getStore($id){
        return $this->model('store')->select('store_name')->where('id=$id')->getOne();
    }
    //根据地区的id获得地区
    private function _getProvinceById($id){
        return $this->model('lib_region')->select('name')->where("id=$id")->getOne();
    }
    //获取我的采购(1现货/2期货)
    //获取我的关注的数据
    public function getMyAttention($user_id){
        $products = $this->model('concerned_product')->where("user_id=$user_id and status=1")->select('id,product_id,product_name,model,factory_name')->getAll();//以后可以分页
        foreach ($products as $key => $value) {
            $factory = $this->model('factory')->where("f_name='{$value['factory_name']}'")->getRow();

            $pro = $this->model('product')->where("model='{$value['model']}' and f_id={$factory['fid']}")->getRow();

            $pur = $this->model('purchase')->where('p_id='.$pro['id'])->order('input_time desc,unit_price asc')->limit('0,2')->getAll();//取最近的两条数据,实际上有多条

            $palph = $pur[1]['unit_price']==0 ? 0 : intval($pur[0]['unit_price']-$pur[1]['unit_price']);
            //价格差，涨跌
            $talph = $pur[1]['input_time']==0 ? 0 : $pur[0]['input_time']-$pur[1]['input_time'];
            //时间差，分钟以前
            $products[$key]['unit_price'] = $pur[0]['unit_price'];
            $products[$key]['floor_up'] = $palph;
            $products[$key]['time_al'] = $talph;
        }
        return $products;
    }
    //我的关注全选/不选删除操作
    public function mulDelMyAttention($ids){
        $rtn_sucess= 0;
        $rtn_fail= 0;
        $total = count($ids);
        foreach ($ids as $id) {
            if(!empty($id)){
                $result = $this->model('concerned_product')->where('id='.$id)->delete();
                if($result){
                    $rtn_sucess = $rtn_sucess +1;
                }else{
                    $rtn_fail=$rtn_fail+1;
                }
            }
        }

        if($rtn_fail>0)
        {
            return array('err'=>1,'msg'=>'共选中:'.$total.'条,成功删除:'.$rtn_sucess.'条,删除失败:'.$rtn_fail.'条');
        }else{
            return array('err'=>0,'msg'=>'共选中:'.$total.'条,成功删除:'.$rtn_sucess.'条,删除失败:'.$rtn_fail.'条');
        }
    }
    //查看是否开票
    public function checkBillInfo($userid){
        if($this->_getBill($userid)) return true;
        return false;
    }
    //获取开票资料信息
    public function getBillInfo($userid){
        return $this->_getBill($userid);
    }
    //获取开票资料信息
    private function _getBill($userid){
        $data = $this->from('customer as c')
        ->join('customer_billing as cb','c.c_id=cb.c_id')
        ->where("cb.user_id={$userid} and cb.display_status=1")
        ->select("cb.id,c.c_name,c.legal_person,cb.tax_id,cb.invoice_address,cb.invoice_tel,cb.invoice_bank,cb.invoice_account,cb.status")
        ->getRow();
        return $data;
    }
    //保存修改信息
    public function changeBill($id,$userid,$tax_id,$invoice_address,$invoice_tel,$invoice_bank,$invoice_account){
        $data = array();
            //开票id为空，新增
            if(empty($id)){
                $cus_contact = M('user:customerContact')->getListByUserid($userid);
                $data['user_id']=$userid;
                $data['c_id']=$cus_contact['c_id'];
                $data['tax_id']= $tax_id;
                $data['invoice_address'] = $invoice_address;
                $data['invoice_tel'] = $invoice_tel;
                $data['invoice_bank'] = $invoice_bank;
                $data['invoice_account'] = $invoice_account;
                $data['customer_manager']=$cus_contact['customer_manager'];
                $data['input_time']=CORE_TIME;
                $data['input_admin']=$userid;
                $data['status']=2;
                $data['display_status']=1;
                if($this->model('customer_billing')->add($data)) return array('err'=>2,'msg'=>'新增成功,等待审核');
                return array('err'=>3,'msg'=>'新增失败');
            }else{
                //开票id不为空，修改
                $status=$this->model('customer_billing')->select('status')->where('id='.$id)->getOne();
                if($status==1) return array('err'=>4,'msg'=>'审核通过的开票资料不能修改');
                $data['tax_id'] = $tax_id;//识别号码
                $data['invoice_address'] = $invoice_address;//开票地址
                $data['invoice_tel'] = $invoice_tel;//开票电话
                $data['invoice_bank'] = $invoice_bank;//开户银行
                $data['invoice_account'] = $invoice_account;//银行账号
                $data['status']=2;//状态
                if($this->model('customer_billing')->where('id='.$id)->update($data)) return array('err'=>5,'msg'=>'更新成功,等待审核');
            }
    }
    //保存意见反馈
    public function saveFeedBack($userid,$msg_type,$message,$contact_way){
        if(empty($contact_way)){
            return array('err'=>2,'msg'=>'请输入您的QQ号或手机号');
        }elseif (!is_mobile($contact_way) && !$this->_isQQ($contact_way)) {
            return array('err'=>3,'msg'=>'请输入正确格式的QQ号或手机号');
        }
        $feedArr = array(
            'user_id'=>$userid,
            'user_name'=>M('user:customerContact')->getListByUserid($userid)['name'],
            'msg_type'=>$msg_type,
            'message'=>$message,
            'email'=>strlen($contact_way)==11?'':$contact_way,
            'mobile'=>strlen($contact_way)==11?$contact_way:'',
            'input_time'=>CORE_TIME,
            'status'=>1,
            );
        if(!$this->model('customer_message')->add($feedArr)) return array('err'=>4,'msg'=>'保存留言失败');
        return array('err'=>5,'msg'=>'保存留言成功');
    }
    //QQ号验证正则
    private function _isQQ($str){
        $pattern = "/^\d{5,10}$/";
        return preg_match($pattern,$str);
    }
    //获取站内信
    public function getMsg($userid,$is_read,$type,$page=1,$size=10){
        $where="user_id=$userid and utype=0";//前台的消息
        if(!empty($is_read)) $where.=" and is_read=$is_read";//1=>'未读',2=>'已读',空 全部
        if(!empty($type)) $where.=" and type=$type";//语言包的5大类型, 空 全部
        $list=$this->model('user_msg')
            ->where($where)
            ->order("input_time desc")
            ->page($page,$size)
            ->getPage();
        return $list;
    }
    //时间戳转换为秒-分钟-小时-天
    public function  changeTime($time) {
        $int = time() - $time;
        $str = '';
        if ($int <= 2){
            $str = sprintf('刚刚', $int);
        }elseif ($int < 60){
            $str = sprintf('%d秒前', $int);
        }elseif ($int < 3600){
            $str = sprintf('%d分钟前', floor($int/60));
        }elseif ($int < 86400){
            $str = sprintf('%d小时前', floor($int/3600));
        }elseif ($int < 2592000){
            $str = sprintf('%d天前', floor($int/86400));
        }else{
            $str = date('Y-m-d H:i:s', $time);
        }
        return $str;
    }
    //获取当前类型的键
    private function _getProKey($p_types,$keywords){
        foreach ($p_types as $key => $value) {
            if($value == strtoupper($keywords))
                return $key;
        }
    }
}