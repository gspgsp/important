<?php
/**
*web-app委托洽谈的模型
*/
class myDelegateModel extends model
{
	public function __construct() {
        parent::__construct(C('db_default'), 'purchase');
    }
    //获取点击查看/委托洽谈
    public function getCheckDelegate($otype,$id,$userId){
        $where = "pur.id=$id";
        $chDeRes = array();
        $data = $this->from('purchase pur')->select('pur.id,pur.p_id,pur.number,pro.model,pro.product_type,pur.unit_price,fa.f_name,pur.provinces,pur.store_house,pur.c_id,pur.input_time,pur.cargo_type,pur.user_id')
            ->join('product pro','pur.p_id=pro.id')
            ->join('factory fa','pro.f_id=fa.fid')
            ->where($where)
            ->getRow();
            $contact = M('user:customerContact')->getListByUserid($data['user_id']);
            $own = M('user:customerContact')->getListByUserid($userId);
        if($otype == 1){
            //产品信息
            $chDeRes['product_type'] = L('product_type')[$data['product_type']];
            $chDeRes['model'] = $data['model'];
            $chDeRes['unit_price'] = $data['unit_price'];
            $chDeRes['number'] = $data['number'];
            $chDeRes['f_name'] = $data['f_name'];
            $chDeRes['store_house'] = $data['store_house'];
            $chDeRes['provinces'] = $this->model('lib_region')->select('name')->where('id='.$data['provinces'])->getOne();//交货地,汉字型
            $chDeRes['delivery_place'] = $data['provinces'];//交货地,数字型
            $chDeRes['c_name'] = M('user:customer')->getCinfoById($data['c_id'])['c_name'];//公司名
            $chDeRes['input_time'] = date("Y-m-d",$data['input_time']);//发布时间
            $chDeRes['delivertime'] = $data['cargo_type'] ==1 ? '现货':'期货';//交货时间
            //交易员
            $chDeRes['cus_manager'] = $this->model('admin')->where('admin_id='.$contact['customer_manager'])->select('name')->getOne();//交易员
            $chDeRes['cus_mobile'] = $this->model('admin')->where('admin_id='.$contact['customer_manager'])->select('mobile')->getOne();//交易员联系方式
            //联系人
            $chDeRes['con_name'] = $contact['name'];
            $chDeRes['mobile'] = $contact['mobile'];
            $chDeRes['qq'] = $contact['qq'];
        }elseif ($otype == 2) {
            //委托交易
            $chDeRes['product_type'] = L('product_type')[$data['product_type']];
            $chDeRes['model'] = $data['model'];
            $chDeRes['unit_price'] = $data['unit_price'];
            $chDeRes['p_id'] = $data['p_id'];
            $chDeRes['c_id'] = $data['c_id'];
            $chDeRes['number'] = $data['number'];
            $chDeRes['f_name'] = $data['f_name'];
            $chDeRes['store_house'] = $data['store_house'];
            $chDeRes['provinces'] = $this->model('lib_region')->select('name')->where('id='.$data['provinces'])->getOne();//交货地,汉字型
            $chDeRes['delivery_place'] = $data['provinces'];//交货地,数字型
            $chDeRes['delivertime'] = $data['cargo_type'] ==1 ? '现货':'期货';//采购方式
            //我的信息(当前用户自己)
            $chDeRes['con_name'] = $own['name'];
            $chDeRes['mobile'] = $own['mobile'];
            $chDeRes['c_name'] = M('user:customer')->getCinfoById($own['c_id'])['c_name'];
        }
        return $chDeRes;
    }
}