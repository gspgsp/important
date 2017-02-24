<?php
/**
 * Created by PhpStorm.
 * User: yuanjiaye
 * Date: 2016/7/15
 * Time: 17:02
 */
class testAction extends homeBaseAction{

    public function __init(){
        $this->db = M('public:common')->model('resourcelib');
    }

    public function test(){
        $potentialCustomers=M('resourcelib:potentialCustomers');
        $middlePotentialCustomers=M('resourcelib:middlePotentialCustomers');
        $content=$this->db->select('content')->where("`content` REGEXP'.*1[0-9]{10}.*' ")->limit(100)->getCol();

        $arr = array();
        foreach($content as $k=>$v){
              preg_match("/[1][0-9]{10}/", $v,$arr[]);
        }
        foreach($arr as $k=>$v){
            $arrs['phone_number']=$v[0];
            $array=array_unique($arrs);
            $middlePotentialCustomers->add($array);
        }
        $sql="select phone_number from p2p_middle_potential_customers
 where   phone_number not in (select mobile from p2p_customer_contact where chanel=6) and status=0";
       $list =$this->db->getAll($sql);
        foreach($list as $k=>$v ) {
            $potentialCustomers->add($v);
        }

        $this->display('test.html');
    }


    public function user(){

    }

}
