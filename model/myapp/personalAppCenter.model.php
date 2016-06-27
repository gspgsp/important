<?php
/**
*个人中心模型-app
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
    //获取我的报价单(1现货/2期货)
    public function getMyQuotation($user_id,$cargo_type=1){
        $pdata = array();
        $result = array();

        $purchase = $this->model('purchase')->select('id,unit_price,number,store_house,input_time,p_id,user_id,shelve_type')->where("user_id=$user_id and cargo_type=$cargo_type and type=2")->getAll();
        foreach ($purchase as $value1) {
            $product = $this->model('product')->select('model,f_id,product_type')->where('id='.$value1['p_id'])->getRow();
            $f_name = $this->model('factory')->select('f_name')->where('fid='.$product['f_id'])->getRow();
            $pdata['id'] =$value1['id'];
            $pdata['p_id'] =$value1['p_id'];
            $pdata['model'] =$product['model'];
            $pdata['product_type'] =L('product_type')[$product['product_type']];
            $pdata['unit_price'] =$value1['unit_price'];
            $pdata['f_name'] =$f_name['f_name'];
            $pdata['number'] =$value1['number'];
            $pdata['store_house'] =$value1['store_house'];
            $pdata['input_time'] =$value1['input_time'];
            $pdata['shelve_type'] =$value1['shelve_type'];
            $result[] = $pdata;
            unset($pdata);
        }
        //二维数组
        return $result;
    }
    //获取我的采购(1现货/2期货)
    public function getMyPurchase($user_id,$cargo_type=1){
        $pdata = array();
        $result = array();

        $purchase = $this->model('purchase')->select('id,unit_price,number,supply_count,store_house,input_time,p_id,user_id,status')->where("user_id=$user_id and cargo_type=$cargo_type and type=1")->getAll();
        foreach ($purchase as $value1) {
            $product = $this->model('product')->select('model,f_id,product_type')->where('id='.$value1['p_id'])->getRow();
            $f_name = $this->model('factory')->select('f_name')->where('fid='.$product['f_id'])->getRow();
            $pdata['id'] =$value1['id'];
            $pdata['p_id'] =$value1['p_id'];
            $pdata['model'] =$product['model'];
            $pdata['product_type'] =L('product_type')[$product['product_type']];;
            $pdata['unit_price'] =$value1['unit_price'];
            $pdata['f_name'] =$f_name['f_name'];
            $pdata['number'] =$value1['number'];
            $pdata['store_house'] =$value1['store_house'];
            $pdata['input_time'] =$value1['input_time'];
            $pdata['status'] =$value1['status'];
            $pdata['supply_count'] =$value1['supply_count'];
            $result[] = $pdata;
            unset($pdata);
        }
        //二维数组
        return $result;
    }
    //获取我的关注的数据
    public function getMyAttention($user_id){
        $products = $this->model('concerned_product')->where("user_id=$user_id and status=1")->select('id,product_id,product_name,model,factory_name')->getAll();//以后可以分页
        foreach ($products as $key => $value) {
            $factory = $this->model('factory')->where("f_name='{$value['factory_name']}'")->getRow();

            $pro = $this->model('product')->where("model='{$value['model']}' and f_id={$factory['fid']}")->getRow();

            $pur = $this->model('purchase')->where('p_id='.$pro['id'])->order('input_time desc,unit_price asc')->limit('0,2')->getAll();//取最近的两条数据,实际上有多条

            $palph = $pur[1]['unit_price']==0 ? 0 : intval($pur[1]['unit_price']-$pur[0]['unit_price']);
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
        foreach ($ids as $id) {
            if(!empty($id)){
                $result = $this->model('concerned_product')->where('id='.$id)->delete();
            }
        }
        if($result){
                return array('err'=>0,'msg'=>'批量删除成功');
            }else{
                return array('err'=>2,'msg'=>'批量删除失败');
            }
    }
}