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
        $where="user_id=$this->user_id and ";
        $data = M('product:concernedProduct')->getConcernedList($where);

        $this->assign('data',$list);
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

        $this->display('index');
	}

//    //获取关注的列表
//    private function getAttentionvalue(){
//        $list = $this->db->model('concerned_product')->where('user_id='.$this->user_id)
//            ->order("input_time desc")
//            ->limit('6');
//        foreach ($list['data'] as $key => $value) {
//            $list['data'][$key]['status'] = L('attention_status')[$value['status']];
//            $list['data'][$key]['operate'] = L('operate')[$value['operate']];
//            $list['data'][$key]['input_time'] = $value['input_time']>1000 ? date("Y-m-d H:i:s",$value['input_time']):'-';
//            $list['data'][$key]['update_time'] = $value['update_time']>1000 ? date("Y-m-d H:i:s",$value['update_time']):'-';
//        }
//        return array('detail'=>$list['data']);
//    }
}