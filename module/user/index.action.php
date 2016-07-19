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
        $arr=array();
        foreach($data as $k=>$v){
            $arr['p_id']=$v['product_id'];
            $arr['id']=$v['id'];
            $arr['model']=$v['model'];
            $arr['f_name']=$v['factory_name'];
            $price=M('product:purchase')->footPrice($arr);
            $price[0]=!empty($price[0])?$price[0]:array('unit_price'=>'暂无报价','input_time'=>'-');
            $arrays=array_merge($arr,$price[0]);
            $prices['price']=$price[0]['unit_price']-$price[1]['unit_price'];
            $prices['p_id']=$price[0]['p_id'];
            $prices['absprice']=abs($prices['price']);
            $array[]=array_merge($arrays,$prices);
        }
        $date1=strtotime(date('Ymd'));
        $this->count1=M('resourcelib:resourcelib')->getTotalOne($date1);
        //今日采购发布总数
        $this->count2=M('resourcelib:resourcelib')->getTotalTow($date1);
        //实时资讯
        $this->ref=M('resourcelib:resourcelib')->getNew();
        //最新正在洽谈的求购信息
        $rest=M('product:purchase')->getInfo();
        //积分商品banner
        $points=M('points:pointsGoods')->getGoods();
        //近三个月的订单信息(自营)
        $date= date(strtotime('-90 day'));
        $uid=$this->user_id;
        $this->res1=M('product:order')->getOrderStatusInFo($uid,$date);//待审核
        $this->res2=M('product:order')->getOrder($uid,$date);          //代付款
        $this->res3=M('product:order')->getInvoice($uid,$date);        //带开票
        $this->res4=M('product:order')->getOrderCancel($uid,$date);    //已取消
        //近三个月的订单信息(联营)
        $this->info1=M('product:unionOrder')->getOrderStatusInFo($uid,$date);//待审核
        $this->info2=M('product:unionOrder')->getOrder($uid,$date);          //代付款
        $this->info3=M('product:unionOrder')->getInvoice($uid,$date);        //带开票
        $this->info4=M('product:unionOrder')->getOrderCancel($uid,$date);    //已取消
        $this->assign('data',$list);
//        $this->assign('res',$data);
        $this->assign('rest',$rest);
        $this->assign('points',$points);
        $this->assign('prices',$array);
        $this->display('index');
	}

    /**
     * 实时资源
     *
     */
    public function ajax(){
        $ref=M('resourcelib:resourcelib')->getNew();
        json_output($ref);

    }


}