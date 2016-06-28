<?php


class indexAction extends userBaseAction{


    /**
     * 个人中心首页
     *
     */
    public function init()
	{
		$this->act='index';
        $userid=$this->user_id;
        //个人信息
        $list=M('user:customerContact')->getCustomerInFoById($userid);
        //我的关注列表
        $where="cp.user_id=$this->user_id and cp.status=1";
        $data = M('product:concernedProduct')->getConcernedList($where);
//
//        //关注产品的价格浮动
//        $arr=array();
//        foreach($data as $v){
//
//           $arr['pid']=$data['pid'];
//            $price=M('product:purchase')->footPrice($arr);
//            }

        //今日报价发布总数
        $date1=strtotime(date('Ymd'));
        $count1=M('resourcelib:resourcelib')->getTotalOne($date1);
        //p($count1);
        //今日采购发布总数
        $count2=M('resourcelib:resourcelib')->getTotalTow($date1);
        //近三个月的订单信息(自营)
        $date= date(strtotime('-90 day'));
        $uid=$this->user_id;
        $res1=M('product:order')->getOrderStatusInFo($uid,$date);//待审核
        $res2=M('product:order')->getOrder($uid,$date);          //代付款
        $res3=M('product:order')->getInvoice($uid,$date);        //带开票
        $res4=M('product:order')->getOrderCancel($uid,$date);    //已取消
        //近三个月的订单信息(联营)
        $info1=M('product:unionOrder')->getOrderStatusInFo($uid,$date);//待审核
        $info2=M('product:unionOrder')->getOrder($uid,$date);          //代付款
        $info3=M('product:unionOrder')->getInvoice($uid,$date);        //带开票
        $info4=M('product:unionOrder')->getOrderCancel($uid,$date);    //已取消
        $this->assign('data1',$res1);
        $this->assign('data2',$res2);
        $this->assign('data3',$res3);
        $this->assign('data4',$res4);
        $this->assign('info1',$info1);
        $this->assign('info2',$info2);
        $this->assign('info3',$info3);
        $this->assign('info4',$info4);
        $this->assign('data',$list);
        $this->assign('res',$data);
        $this->assign('count1',$count1);
        $this->assign('count2',$count2);
        $this->display('index');
	}

}