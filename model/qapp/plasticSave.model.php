<?php
/**
 *塑料圈保存个人信息-zhanpeng
 */
class plasticSaveModel extends model
{
    public function __construct() {
        parent::__construct(C('db_default'), 'customer');
    }
    public function saveSelfInfo($userid,$type,$field){//类型 1 地址 2 性别 3 主营牌号  4关注的牌号 5 所属区域
        $cus_con = M('user:customerContact')->getListByUserid($userid);
        switch ($type) {
            case 1:
                $where = "c_id=".$cus_con['c_id'];
                if($this->where($where)->update(array('address'=>$field))) return true;
                return false;
            // break;
            case 2:
                $where = "user_id=".$userid;
                $field = intval($field);
                if($this->model('customer_contact')->where($where)->update(array('sex'=>$field))) return true;
                return false;
            // break;
            case 3:
                $where = "c_id=".$cus_con['c_id'];
                if($this->where($where)->update(array('need_product'=>$field))) return true;
                return false;
            case 4:
                if(empty($field)) return false;
                $tmpArr=array();
                $tmpArr2=array();
                $tmp = $this->model('suggestion_model')->select('id,user_id,friend_id,name,type')->where("user_id=$userid and type='ME' and is_enable=1 and is_concern=1")->getAll();

                    foreach($tmp as $v){
                        if(!in_array($v['name'],$field)){
                            $tmpArr[] = $v;
                        }
                    }
                foreach($tmpArr as $v){
                    $arr2=array(
                        'is_enable'=>0,
                        'update_time'=>date("Y-m-d H:i:s"),
                    );
                    if($this->model('suggestion_model')->where("id=".$v['id'])->update($arr2)) M('suggestion:suggestion')->suggestion_model($userid,$v['name'],'*','*','D');
                }

                foreach($field as $v){
                    if(!$this->model('suggestion_model')->select('id')->where("user_id=$userid and type='ME' and name='{$v}' and is_enable=1 and is_concern=1")->getOne()){
                        $arr=array(
                            'user_id'=>$userid,
                            'name'=>$v,
                            'friend_id'=>0,
                            'type'=>'ME',
                            'create_time'=>date("Y-m-d H:i:s"),
                            'is_concern'=>1,
                        );
                        if($this->model('suggestion_model')->add($arr)) M('suggestion:suggestion')->suggestion_model($userid,$v);


                    }
                }
          return true;

            case 5:
                $where = "user_id=".$userid;
                if($this->model('contact_info')->where($where)->update(array('adistinct'=>$field))) return true;
                return false;

        }
    }
}