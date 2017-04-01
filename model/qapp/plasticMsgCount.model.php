<?php

/**
 *消息数量-zhanpeng
 */
class plasticMsgCountModel extends model
{

    public function __construct ()
    {
        parent::__construct (C ('db_default'), 'weixin_plasticzone');
    }
    //获取私信（约炮）数量
    //获取供求消息回复的数量
    public function getMsgCount ($user_id, $type)
    {
        $where = " is_read = 0 ";
        switch ($type) {
            case 1:
                $model = "weixin_plasticzone";
                $where .= " and send_id = $user_id ";

                return $this->_getModelData ($model, $where);
            case 2:
                $model = "weixin_msg";
                $where .= " and user_id = $user_id ";

                return $this->_getModelData ($model, $where);
        }
    }

    //获取系统消息数量
    public function getRobotCount ($user_id)
    {
        $where = "is_read=0 and user_id=$user_id";
        $model = "robot_msg";

        return $this->_getModelData ($model, $where);
    }

    //获取关注或者粉丝的数量
    public function getMyFunsCount ($user_id, $type)
    {
        $where = "status=1";
        $model = "weixin_fans";
        switch ($type) {//1粉丝2关注
            case 1:
                $where .= " and focused_id=$user_id ";
                break;
            case 2:
                $where .= " and user_id=$user_id ";
                break;
        }

        return $this->_getModelData ($model, $where);
    }

    private function _getModelData ($model, $where)
    {
        return count ($this->model ($model)->where ($where)->getAll ());
    }
}