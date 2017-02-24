<?php
/**
 * Created by PhpStorm.
 * User: yuanjiaye
 * Date: 2016/11/23
 * Time: 11:17
 */


/**
 *大客户报价
 */
class pricesAction extends adminBaseAction
{
    protected $offerModel;
    public function __init(){
        $this->db=M('public:common')->model('big_offers');
        // $this->offerModel=M('public:common')->model('big_offers');
    }

    public function init(){
        $action=sget('action');
        if($action=='grid'){
            $where=1;
            $page = sget("pageIndex",'i',0); //页码
            $size = sget("pageSize",'i',20); //每页数
            $sortField = sget("sortField",'s','fid'); //排序字段
            $sortOrder = sget("sortOrder",'s','desc'); //排序
            $list=$this->db->where($where)
                ->page($page+1,$size)
                ->getPage();
            foreach($list['data'] as $k=>$v){
                $list['data'][$k]['input_time']=$v['input_time']>1000 ? date("Y-m-d H:i:s",$v['input_time']) : '-';
            }
            $result=array('total'=>$list['count'],'data'=>$list['data'],'msg'=>'');
            $this->json_output($result);

        }

        $this->display('clients_prices.list.html');
    }


}