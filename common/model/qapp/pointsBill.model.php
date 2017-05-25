<?php

/**
 * 积分表单
 */
class pointsBillModel extends Model
{
    protected $is_mobile   =  false;

    public function __construct ()
    {
        parent::__construct (C ('db_default'), 'points_bill');
    }


    // 减少积分 type 来源 5 积分兑换
    public function decPoints ($num = 0, $uid = 0, $type = 0, $gid = 0)
    {
        $user = M ('public:common')->model ('contact_info');
        if ($info = $user->where ("user_id=$uid")->getRow ()) {
            if (($info['quan_points'] - $num) < 0) return false;
            if (!$user->where ("user_id=$uid")->update ("quan_points=quan_points-$num")) return false;
            if($this->is_mobile){
                if (!$this->add (array('addtime' => time (), 'uid' => $uid, 'points' => -$num, 'type' => $type, 'gid' => $gid,'is_mobile'=>1))) return false;
            }else{
                if (!$this->add (array('addtime' => time (), 'uid' => $uid, 'points' => -$num, 'type' => $type, 'gid' => $gid))) return false;
            }

            return true;
        } else {
            return false;
        }
    }

    // 增加积分 type 来源 5 积分兑换
    public function addPoints ($num = 0, $uid = 0, $type = 0 ,$share_type = 0)
    {
        $user = M ('public:common')->model ('contact_info');
        if ($info = $user->where ("user_id=$uid")->getRow ()) {
            if (!$user->where ("user_id=$uid")->update ("quan_points=quan_points+$num")) return false;
            if($this->is_mobile){
                if (!$this->add (array('addtime' => time (), 'uid' => $uid, 'points' => $num, 'type' => $type ,'share_type'=>$share_type,'is_mobile'=>1))) return false;
            }else{
                if (!$this->add (array('addtime' => time (), 'uid' => $uid, 'points' => $num, 'type' => $type ,'share_type'=>$share_type))) return false;
            }

            return true;
        } else {
            return false;
        }
    }

    //获取用户总的积分
    public function getUerPoints ($uid)
    {
        $list = $this->model ('contact_info')->select ('quan_points')->where ('user_id=' . $uid)->getOne ();

        return $list;
    }


    public function setMoblie($bool){
        $this->is_mobile = (bool)$bool;
        return true;
    }
}