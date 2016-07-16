<?php
/**
 * Created by PhpStorm.
 * User: yuanjiaye
 * Date: 2016/7/15
 * Time: 17:02
 */
class testAction extends homeBaseAction{

    public function __init(){
        $this->db = M('public:common')->model('temp');
    }

    public function test(){

        $info=$this->db->model('temp')->select('id,name')->getAll();
//       p($info);
        $arr=array();
        foreach($info as $k=>$v){
            $arr['id']=$v['id'];
            $arr['name']=$v['name'];
             $data= $this->db->model('temps as t')->select('t.c_id,t.c_name,t.contact_id')
                    ->where("t.c_name=".$arr['name'])
                    ->getAll();
//            p($data);
             $data=array_filter($data) ;
                foreach($data as $k=>$v){
                    $array['name']=$v['c_name'];
                    $array['customer_id']=$v['c_id'];
                    $array['contact_id']=$v['contact_id'];
                    $info=$this->db->model('temp')->where('temp.name='.$array['name'])->update($array);
//                    showTrace();
                }

        }

    }

}
