<?php

/**
 * 智能推荐分析功能
 * Class analyzeAction
 */
class analyzeAction extends null2Action
{
    protected $db, $err, $cates,$pointsType,$orderStatus,$rePoints;

    public function __init()
    {
    }

    /**
     * 获取用户当前的sessionID字符串，用于在cookie关闭模式
     */
    public function getSession(){
        $sessionQuery = M('qapp:appSession')->getSessionAppend();
        $this->json_output(array('err' => 0, 'msg' => '获取成功', 'sessionQuery' => $sessionQuery));
    }

    /**
     * 输出推荐列表
     */
    public function getSuggestion()
    {
        $this->is_ajax = true;

        $user_id = $this->checkAccount();
        $page = sget('page', 'i', 1);
        $size = sget('size', 'i', 10);
        $data = M('suggestion:suggestion')->getSuggestList($user_id, $page, $size);
        if (empty($data['data']) && $page == 1) {
            $this->json_output(array('err' => 2, 'msg' => '没有相关的数据'));
        }
        else{
            $this->json_output(array('err' => 0, 'data' => $data));
        }
    }
}