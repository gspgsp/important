<?php

/**
 * Created by PhpStorm.
 * User: root
 * Date: 4/2/17
 * Time: 1:49 AM
 */
class scoreAction extends null2Action
{
    private $param;

    public function __init()
    {
        $data = spost('data', 's');
        if (!empty($data)) {
            $mcrypt = E('mcrypt', APP_LIB . 'class');

            $param = $mcrypt->decrypt($data);

            $this->param = json_decode($param, true);
        }
    }

    public function get_score_config()
    {
        $token         = sget ('token', 's');
        if (empty($token)) {
            $this->json_output(array('ok'=>1,'msg'=>'用户尚未登录'));

        } else {
            $user_id = M ('qapp:appToken')->chkToken ($token);
            if (empty($user_id)) {
                $this->json_output(array('ok'=>1,'msg'=>'登录信息错误'));
            }

            $login_count = M('user:passport')->get_log_login($user_id);

        }

        $settings=M('system:setting')->getSetting();

        $score_settings= array_flip(array('score_per_day','score_login','score_recommend'));

        foreach($score_settings as $key => $value)
        {

            $score_settings[$key] = $settings[$key];
        }

        $this->json_output($score_settings);
    }


}
