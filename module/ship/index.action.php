<?php

class indexAction extends homeBaseAction
{

    public function init()
    {

        $this->seo = array(
            'title'=>'中晨物流',
            'keywords'=>'中晨物流，塑料物流，大宗商品物流，我的塑料网物流',
            'description'=>'中晨物流，安全可靠！交易安全：全程跟踪物流状态确保安全抵达。合作物流：全部与大型物流公司合作，车队资源一应俱全。真人找车：所有委托全部真人全程跟进，放心省事服务好',
            'status'=>5
        );
        $this->display('index.html');
    }

    public function get_price()
    {
        if ($_POST) {
            $starting = sget('s', 's', '');
            $ending = sget('e', 's', '');
            $weight = sget('w', 'i', 0);
            $province = sget('p', 's', '');
            if (empty($starting)) die(json_encode(array('err' => '1', 'msg' => '发货起始地不能为空！')));
            if (empty($ending)) die(json_encode(array('err' => '1', 'msg' => '卸货地不能为空！')));
            if (empty($weight)) die(json_encode(array('err' => '1', 'msg' => '请输入货物重量！')));
            if (empty($province)) die(json_encode(array('err' => '1', 'msg' => '送达省不能为空！')));
            if ($weight < 5) die(json_encode(array('err' => '1', 'msg' => '请输入正确的货物重量')));
            $Ship = M('operator:ship_price');
            if (!$Ship->where("`start`='$starting'")->getRow()) die(json_encode(array('err' => '1', 'msg' => '您输入的发货地暂时不在受理范围！')));
            if (!$Ship->where("`end`='$ending'")->getRow()) die(json_encode(array('err' => '1', 'msg' => '您输入的卸货地暂时不在受理范围哦！')));
            if ($weight < 5) die(json_encode(array('err' => '1', 'msg' => '您的货物小于5吨，请联系客服电话协商')));
            if ($weight >= 5) $key_type = '5to10';
            if ($weight >= 10) $key_type = '10to15';
            if ($weight >= 15) $key_type = '15to20';
            if ($weight >= 20) $key_type = '20to25';
            if ($weight >= 25) $key_type = '25to30';
            if ($weight > 30) $key_type = '30plus';
            $shop_info = $Ship->where(" 1 AND `start`='$starting' AND `end` = '$ending' AND `cities` = '$province'")->getRow();
            if (empty($shop_info)) die(json_encode(array('err' => '1', 'msg' => '您查找的信息不存在')));
            $price = $shop_info['addition'] == 0 ? $shop_info["$key_type"] * $weight :($shop_info["$key_type"] + $shop_info["addition"]) * $weight;
            die(json_encode(array('err' => '0', 'msg' => $price)));
        }

    }

    //物流入库等待后台分配物流公司
    public function collect(){
        if($_POST){
            $id=sget('id','s','0');
            $starting = sget('s', 's', '');
            $ending = sget('e', 's', '');
            $weight = sget('w', 'i', 0);
            $price = sget('p', 's', '');
            $status=0;

            $shipCollect = M('operator:ship_collect');
            $ship_collect=$shipCollect->query("insert into p2p_ship_collect (`input_time`,`user_id`,`ending`,`weight`,`price`,`status`,`starting`) VALUES (".time().", $id, '$ending', $weight, $price, $status, '$starting')");
            if($ship_collect){
                die(json_encode(array('err' => '0')));
            }else{
                die(json_decode(array('err'=> '2')));
            }

        }
    }

    public function test()
    {
        echo 11111;
        $this->display('test');
    }

}


