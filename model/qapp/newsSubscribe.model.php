<?php
class newsSubscribeModel extends model
{
    //初始化模型
    public function __construct()
    {
        parent::__construct(C('db_default'), 'news_subscribe');
    }

    public function getSubscribeByUserid($user_id=0){
        if($user_id<1) return ;
        $tmp=$this->select('news_cate_id')->where("user_id=$user_id and is_enable=1")->getCol();
        return $tmp;
    }

    public function setSubscribeByUserid($user_id=0,$cate_id=array()){
        if($user_id<1) return false;
        if(empty($cate_id)) return false;
        $tmp=$this->getSubscribeByUserid($user_id);//var_dump($cate_id);exit;  21 20 2  11 13 4       21 20  2 11  13
        //这里面其实有点小问题，就是我出现了 in_array() 里面的一个是数字一个是字符串，但是就会出现全部改变的
        if(!empty($tmp)){
            foreach($tmp as $row){
                if(!in_array($row,$cate_id)){
                    $arr=array(
                        'update_time'=>CORE_TIME,
                        'is_enable'=>0,
                    );
                    $this->where("user_id=$user_id and is_enable=1")->update($arr);
                }
            }
        }

        foreach($cate_id as $row){
            if(!$this->where("user_id=$user_id and is_enable=1 and news_cate_id=$row")->getOne()){
                $arr2=array(
                    'user_id'=>$user_id,
                    'news_cate_id'=>$row,
                    'input_time'=>CORE_TIME,
                    'is_enable'=>1,
                );
                $this->add($arr2);
            }
        }
        return true;
    }
}
?>