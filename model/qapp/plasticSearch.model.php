<?php
/**
 *塑料圈搜索记录-zhanpeng
 */
class plasticSearchModel extends model
{
    public function __construct() {
        parent::__construct(C('db_default'), 'plasticzone_search');
    }

    public function addSearch($arr){
        if($id = $this->checkSearch($arr)){
            if($this->where('id='.$id)->update('search_times = search_times+1')&&$this->where('id='.$id)->update('update_time='.CORE_TIME)) return true;
            return false;
        }else{
            if($this->add($arr)) return true;
            return false;
        }
    }

    public function checkSearch($arr=array('user_id'=>0,'sort_field'=>'DEFAULT','sort_order'=>'ALL')){
        //var_dump($arr);
        $where = 'user_id='. $arr['user_id'].'
        and sort_field=';
        if(is_string($arr['sort_field'])){
            $where.='"'.$arr['sort_field'].'"';
        }else{
            $where.=$arr['sort_field'];
        }
        $where.='  and sort_order="'.$arr['sort_order'].'"  and content="'.$arr['content'].'"';
        $id=$this->select('id')->where($where)->getOne();
        if($id) return $id;
        return false;
    }
}