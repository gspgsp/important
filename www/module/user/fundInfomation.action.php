<?php
/**
 * Created by PhpStorm.
 * User: yuanjiaye
 * Date: 2016/8/22
 * Time: 11:56
 */
class fundInfomationAction extends userBaseAction{

    private $db;
    public function __init(){
        $this->db = M('public:common')->model('collection');

    }

    /**
     * 资金账户信息
     * @Author: yuanjiaye
     */
    public function fundInfomation(){
        $this->act='fundInfomation';
        if($this->user_id<0) $this->error('账户错误');
        $where="c_id={$_SESSION['uinfo']['c_id']}";
        //筛选条件，流水号

        if($id=sget('number','s','')){
            $where.=" and id=$id";
        }
        //创建时间
        if($_GET['input_time_1'] && $_GET['input_time_2']){
            $_GET['input_time_1']=strtotime($_GET['input_time_1']);
            $_GET['input_time_2']=strtotime($_GET['input_time_2']);
            if($_GET['input_time_1']==$_GET['input_time_2']){
                //开始时间与结束时间相等
                $_GET['input_time_2']=$_GET['input_time_1']+'86399';
                $where.=" and input_time>={$_GET['input_time_1']} and input_time<{$_GET['input_time_2']}";
            }else{   //开始时间小于结束时间
                $where.=" and input_time>={$_GET['input_time_1']} and input_time<{$_GET['input_time_2']}";
            }
        }
        //交易单号
        if($order_sn=sget('order_sn','s','')){
            $where.=" and order_sn='{$order_sn}'";
        }
        $page=sget('page','i',1);
        $size=10;
        $data=$this->db->model('collection')
            ->select('id,order_sn,input_time,order_type,total_price,pay_method')
            ->where($where)
            ->order('id desc')
            ->page($page,$size)
            ->getPage();
        $this->pages=pages($data['count'],$page,$size);
        $this->assign('info',$data);
        $this->display('infomation');
    }

    public function FundDetail(){

        $this->display('funddetail');
    }


}