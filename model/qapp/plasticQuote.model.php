<?php
/**
 *塑料圈出价-zhanpeng
 */
class plasticQuoteModel extends model
{
    public function __construct(){
        parent::__construct(C('db_default'), 'plasticzone_quote');
    }
    public function setPurchasePrice($arr){
        if($this->add($arr)) return true;
        return false;
    }

    public function getPurchasePrice($pur_id=0,$user_id=0,$page=1,$size=10){
        $where="pur_id = $pur_id and user_id=$user_id";
        $data2=$this->page($page,$size)
                    ->where($where)
                    ->order('input_time desc')
                    ->getPage();
        foreach($data2['data'] as $key=>&$value){
            $timeSub = time() - $value['input_time'];
            if ((int)($timeSub / (3600 * 24)) > 0) {
                $value['input_time'] = (int)($timeSub / (3600 * 24)) . '天前';
            } elseif ((int)($timeSub / (3600)) > 0) {
                $value['input_time'] = (int)($timeSub / (3600)) . '小时前';
            } elseif ((int)($timeSub / (60)) > 0) {
                $value['input_time'] = (int)($timeSub / (60)) . '分钟前';
            } else {
                $value['input_time'] = $timeSub . '秒前';
            }
            $data = $this->select('con.user_id,con.name,con.c_id,con.is_pass,con.mobile,con.sex,info.thumb,info.thumbqq,info.thumbcard,cus.c_name,cus.need_product,cus.address')
                ->from('customer_contact con')
                ->leftjoin('contact_info info', 'con.user_id=info.user_id')
                ->leftjoin('customer cus', 'con.c_id=cus.c_id')
                ->where("con.user_id=" . $value['send_id'])
                ->getRow();
            if(!A("api:qapi1")->checkPhoneShow($data['user_id'])){
                $data['mobile']=substr($data['mobile'],0,7)."****";
            }
            if (empty($data['thumbqq'])) {
                if (strstr($data['thumb'], 'http')) {
                    $data['thumb'] = $data['thumb'];
                } else {
                    if (empty($data['thumb'])) {
                        $data['thumb'] = "http://statics.myplas.com/upload/16/09/02/logos.jpg";
                    } else {
                        $data['thumb'] = FILE_URL . "/upload/" . $data['thumb'];
                    }
                }
            } else {
                $data['thumb'] = $data['thumbqq'];
            }
            $data['sex'] = L('sex')[$data['sex']];
            $value['info'] = $data;
        }
        return $data2;

    }

}


















?>