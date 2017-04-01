<?php

/**
 *保存消息回复-zhanpeng
 */
class plasticRepeatModel extends model
{
    public function __construct ()
    {
        parent::__construct (C ('db_default'), 'weixin_plasticzone');
    }

    public function saveMsg ($user_id, $pur_id, $send_id, $content)
    {
        // $type = $this->model('purchase')->select('type')->where('id='.$pur_id)->getOne();//1采购 2报价
        $_data = array(
            'pur_id'     => $pur_id,
            'send_id'    => $send_id,
            'user_id'    => $user_id,
            'content'    => $content,
            'status'     => 0,
            'is_read'    => 0,
            'input_time' => CORE_TIME,
        );
        if ($this->model ('weixin_plasticzone')->add ($_data)) {
            // $this->_sendMsg($send_id,'你有新的消息,请注意查收');
            $start = strtotime (date ('Y-m-d', time ()));
            $end = $start + 86400;
            $count = $this->select ('count(id)')->where ("send_id=$send_id and input_time > $start and input_time < $end")->getOne ();
            $allow_send = M ('plasticzone:plasticAttention')->getUserSet ($send_id);
            if (empty($allow_send)) $allow_send = array('focus' => 0, 'repeat' => 0);
            if ($allow_send['repeat'] == 0 && $count < 2) {
                $pub_time = $this->model ('purchase')->where ("id=$pur_id")->select ('input_time')->getOne ();
                M ('plasticzone:plasticAttention')->sendRemindMsg ($user_id, $send_id, 2, $pub_time);
            }

            //
            return true;
        }

        return false;
    }

    //塑料圈联系人的-发送消息
    public function saveZoneMsg ($send_id, $userId, $content)
    {
        $_data = array(
            'send_id'    => $send_id,
            'user_id'    => $userId,//接受消息人的id
            'content'    => $content,
            'status'     => 0,
            'input_time' => CORE_TIME,
            'is_read'    => 0,
        );
        if ($this->model ('weixin_msg')->add ($_data)) {
            $this->_sendMsg ($userId, '你有新的消息,请注意查收');

            return true;
        }

        return false;
    }

    //socket
    private function _sendMsg ($send_id, $content)
    {
        // 指明给谁推送，为空表示向所有在线用户推送
        //$to_uid = $send_id;
        // 推送的url地址，上线时改成自己的服务器地址
        $push_api_url = "http://www.myplas.com:2121/";
        $post_data = array(
            'type'    => 'weixin',//微信端:weixin pc端:pc app端:app 全平台:publish
            'content' => $content,
            'to'      => $send_id,
        );
        $this->_httpRequest ($push_api_url, $post_data);
    }

    private function _httpRequest ($push_api_url, $post_data)
    {
        $ch = curl_init ();
        curl_setopt ($ch, CURLOPT_URL, $push_api_url);
        curl_setopt ($ch, CURLOPT_POST, 1);
        curl_setopt ($ch, CURLOPT_HEADER, 0);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_POSTFIELDS, $post_data);
        $return = curl_exec ($ch);
        curl_close ($ch);
    }
}