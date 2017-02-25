<?php
/**
 *塑料圈好友关注-zhanpeng
 */
class plasticAttentionModel extends model
{
    public function __construct() {
        parent::__construct(C('db_default'), 'weixin_fans');
    }
    public function getAttention($userid,$focused_id){
        $result = $this->select('id,status')->where("user_id=$userid and focused_id=$focused_id")->getRow();
        if(empty($result)){
            $_data = array(
                'user_id'=>$userid,
                'focused_id'=>$focused_id,
                'status'=>1,
                'input_time'=>CORE_TIME,
            );
            //发送短信
            $start = strtotime(date('Y-m-d',time()));
            $end = $start + 86400;
            $count = $this->select('count(id)')->where("focused_id=$focused_id and input_time > $start and input_time < $end")->getOne();
            $allow_send = $this->getUserSet($focused_id);
            if(empty($allow_send)) $allow_send = array('focus'=>0,'repeat'=>0);
            //开启事务获得id
            $this->model('weixin_fans')->startTrans();
            if($allow_send['focus']==0 && $count<1){
                $this->sendRemindMsg($userid,$focused_id,1);
            }
            if($this->model('weixin_fans')->add($_data)){
                $fans_id = $this->model('weixin_fans')->getLastID();
                //M('suggestion:suggestion')->suggestion_fans($userid,$fans_id);
                $this->model('weixin_fans')->commit();
                return array('err'=>0,'msg'=>'关注成功');
            } else{
                $this->model('weixin_fans')->rollback();
                return array('err'=>2,'msg'=>'关注失败');
            }
        }elseif (!empty($result)) {
            $_data = array(
                'status'=>$result['status']==1?2:1,
                'update_time'=>CORE_TIME,
            );
            //开启事务获得id
            $this->model('weixin_fans')->startTrans();
            if($bools=$this->model('weixin_fans')->where("user_id=$userid and focused_id=$focused_id")->update($_data)){
                //$fans_id = $this->model('weixin_fans')->select('id')->where("user_id=$userid and focused_id=$focused_id")->getOne();
                $this->model('weixin_fans')->commit();
                if($result['status']==2){
                    //M('suggestion:suggestion')->suggestion_fans($userid,$fans_id);
                    return array('err'=>0,'msg'=>'关注成功');
                }elseif($result['status']==1){
                    //M('suggestion:suggestion')->suggestion_fans($userid,$fans_id,'D');
                    return array('err'=>0,'msg'=>'取消关注成功');
                }
            }
            $this->model('weixin_fans')->rollback();
            return array('err'=>2,'msg'=>'失败');
        }
    }
    //发送提醒短信
    public function sendRemindMsg($userid,$focused_id,$from,$pub_time){
        $sms=M('system:sysSMS');
        $sender = $this->getZoneUserInfo($userid)['name'];
        $recevier = $this->getZoneUserInfo($focused_id)['name'];
        $mobile = $this->getZoneUserInfo($focused_id)['mobile'];
        $msg = '';
        switch ($from) {
            case 1:
                $msg=$recevier.'您好，您在塑料圈很受欢迎，'.$sender.'刚刚关注您了，您发布的供求信息他会去查看！【塑料圈通讯录 q.myplas.com 】';//关注短信
                break;
            case 2:
                $msg=$recevier.'您好，您在塑料圈'.date("H:i",$pub_time).'发布的供给/求购信息，有人在关注，给您留言了，请前往查看！【塑料圈通讯录 q.myplas.com 】';//回复短信
                break;
        }
        $sms->send($focused_id,$mobile,$msg,8);//发送手机动态码
    }



    public function getZoneUserInfo($userid){
        return M('user:customerContact')->getListByUserid($userid);
    }
    //偏好设置
    public function favorateSet($userid,$type,$is_allow){
        $allow_send = $this->getUserSet($userid);
        if($type==0){
            switch ($is_allow) {
                case 0:
                    return $this->userFavorateSet($userid,$allow_send,'focus',$is_allow);
                case 1:
                    return $this->userFavorateSet($userid,$allow_send,'focus',$is_allow);
            }
        }elseif ($type==1) {
            switch ($is_allow) {
                case 0:
                    return $this->userFavorateSet($userid,$allow_send,'repeat',$is_allow);
                case 1:
                    return $this->userFavorateSet($userid,$allow_send,'repeat',$is_allow);
            }
        }elseif($type==2){
            switch ($is_allow) {
                case 0:
                    return $this->userFavorateSet($userid,$allow_send,'show',$is_allow);
                case 1:
                    return $this->userFavorateSet($userid,$allow_send,'show',$is_allow);
            }
        }
    }
    //用户偏好设置
    public function userFavorateSet($userid,$allow_send,$type,$value){
        $where = " user_id=$userid ";
        if(empty($allow_send)) $allow_send = array('focus'=>0,'repeat'=>0);
        $allow_send[$type]=$value;
        $allow_send = json_encode($allow_send);
        $result = $this->model('contact_info')->where($where)->update(array('allow_send'=>$allow_send));
        return empty($result)?array('err'=>2,'msg'=>'偏好设置失败'):array('err'=>0,'msg'=>'偏好设置成功');
    }
    //判断用户当前的设置
    public function getUserSet($userid){
        $where = " user_id=$userid ";
        $allow_send = $this->model('contact_info')->select('allow_send')->where($where)->getOne();
        $allow_send = json_decode($allow_send,true);//数组
        return $allow_send;
    }
}