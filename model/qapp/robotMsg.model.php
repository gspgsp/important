<?php
/**
*保存消息回复-zhanpeng
*/
class robotMsgModel extends model
{
    public function __construct() {
        parent::__construct(C('db_default'), 'robot_msg');
    }

    //塑料圈系统消息
    /**
     * @param $pur_id
     * @param $pur_user_id
     * @param $user_id
     * @param string $content
     * @param int $type
     * 目前的规则是  关注  一天向一个用户至多发送两条信息
     *              回复   一天向一个用户至多发送两条信息
     *              推荐   按发布的牌号来发送信息
     */
    public function saveRobotMsg($pur_id,$pur_user_id,$user_id,$content='',$type=0){
        $_data = array(
            'pur_id'=>$pur_id,
            'pur_user_id'=>$pur_user_id,
            'user_id'=>$user_id,
            'content'=>$content,
            'is_read'=>0,
            'input_time'=>CORE_TIME,
            'type'=>$type,
        );
        $start = strtotime(date('Y-m-d',time()));
        $end = $start + 86400;
        $count = $this->select('count(id)')->where("user_id=$user_id and type=$type and input_time > $start and input_time < $end")->getOne();

        //p($_data);var_dump($count);showTrace();exit;
        switch($type){
            case 1:
                if($count<=2) {
                   if($this->add($_data)){
                       return true;
                   } else{
                       return false;
                   }
                }
                //$content="您关注的人,有新的供求";
                break;
            case 2:
                if($count<=2) {
                    if($this->add($_data)){
                        return true;
                    } else{
                        return false;
                    }
                }
                //$content="关于您刚刚发布的求购/供给，您发布的信息收到一条回复";
                break;
            case 3:
                if($this->add($_data)) return true;
                return false;
                //$content="关于您刚刚发布的求购/供给，我们已为您推荐了相关信息";
                break;
        }
    }

    public function getRobotMsg($user_id,$page=1,$size=10){
        $where="user_id=$user_id ";
        $data=$this->where($where)
            ->order('input_time desc')
            ->page($page,$size)
            ->getPage();
        foreach($data['data'] as $key=>&$value){
            $value['input_time']=date("m-d H:i:s",$value['input_time']);
            $this->model('robot_msg')->where("user_id=".$value['user_id'])->update(array('is_read'=>1));
        }
        return $data;
    }

}

?>