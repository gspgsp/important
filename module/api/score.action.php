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

    public function __init ()
    {
        $data = spost ('data', 's');
        if (!empty($data)) {
            $mcrypt = E ('mcrypt', APP_LIB.'class');

            $param = $mcrypt->decrypt ($data);

            $this->param = json_decode ($param, true);
        }
    }

    public function get_score_config ()
    {
        $token = $this->param['token'];
        if (empty($token)) {
            $this->json_output (array( 'ok' => 1, 'msg' => '用户尚未登录' ));

        } else {
            $user_id = M ('qapp:appToken')->chkToken ($token);
            if (empty($user_id)) {
                $this->json_output (array( 'ok' => 1, 'msg' => '登录信息错误' ));
            }

            $login_count = M ('user:passport')->get_log_login ($user_id);

        }

        $settings       = M ('system:setting')->getSetting ();
        $score_settings = array_flip (array( 'reg_succ','reginfo_succ','share_supply_or_demand','share_toutiao','publish_supply','publish_demand','login_daily','newbee_recommend','charge','', 'score_login', 'score_recommend', ));

        foreach ($score_settings as $key => $value) {

            $score_settings[$key] = $settings[$key];
        }
        $score_settings['login_today'] = $login_count['today'];

        $this->json_output ($score_settings);
    }

    public function add_my_score ()
    {
        $token = $this->param['token'];
        if (empty($token)) {
            $this->json_output (array( 'ok' => 1, 'msg' => '用户尚未登录' ));

        } else {
            $user_id = M ('qapp:appToken')->chkToken ($token);
            if (empty($user_id)) {
                $this->json_output (array( 'ok' => 1, 'msg' => '登录信息错误' ));
            }
        }
        $score = M ("suliaoquan:score")->getScore ($user_id);

        if (empty($this->param['score'])) {
            $this->json_output (array( 'ok' => 1, 'msg' => '参数错误' ));
        }

        if ($score === false) {
            $data = array(
                'user_id'     => $user_id,
                'score'       => $score,
                'input_time'  => time (),
                'update_time' => time ()
            );
            M ("suliaoquan:score")->add ($data);
        } else {

            $score = $score + $this->param['score'];
            M ("suliaoquan:score")
                ->wherePk ($user_id)
                ->update (array( 'score' => $score ));

        }

        $this->json_output (array( 'ok' => 0, 'msg' => '积分添加成功' ));


    }

    public function minus_my_score ()
    {
        $token = $this->param['token'];
        if (empty($token)) {
            $this->json_output (array( 'ok' => 1, 'msg' => '用户尚未登录' ));

        } else {
            $user_id = M ('qapp:appToken')->chkToken ($token);
            if (empty($user_id)) {
                $this->json_output (array( 'ok' => 1, 'msg' => '登录信息错误' ));
            }
        }
        $score = M ("suliaoquan:score")->getScore ($user_id);

        if (empty($this->param['score'])) {
            $this->json_output (array( 'ok' => 1, 'msg' => '参数错误' ));
        }

        if ($score === false) {
            $data = array(
                'user_id'     => $user_id,
                'score'       => $score,
                'input_time'  => time (),
                'update_time' => time ()
            );
            M ("suliaoquan:score")->add ($data);
        } else {

            $score = $score - $this->param['score'];
            if ($score < 0) {
                $this->json_output (array( 'ok' => 1, 'msg' => '积分不足' ));
            }
            M ("suliaoquan:score")
                ->wherePk ($user_id)
                ->update (array( 'score' => $score ));

        }

        $this->json_output (array( 'ok' => 0, 'msg' => '积分消费成功' ));

    }

    public function test()
    {
        if(empty($this->param))
        {
            $this->json_output (array( 'ok' => 1, 'msg' => '解密失败' ));
        }else{
            $ret = array_merge(array('ok'=>0),$this->param);
            $this->json_output ($ret);
        }

    }

}
