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
//            $mcrypt = E('mcrypt', APP_LIB . 'class');
//
//            $param = $mcrypt->decrypt($data);

            $this->param = json_decode($data, true);
        }
    }

//    public function get_score_config()
//    {
//        $token         = $this->param['token'];
//        if (empty($token)) {
//            $this->json_output(array('ok'=>1,'msg'=>'用户尚未登录'));
//
//        } else {
//            $user_id = M ('qapp:appToken')->chkToken ($token);
//            if (empty($user_id)) {
//                $this->json_output(array('ok'=>1,'msg'=>'登录信息错误'));
//            }
//
//            $login_count = M('user:passport')->get_log_login($user_id);
//
//        }
//
//        $settings=M('system:setting')->getSetting();
//        $score_settings= array_flip(array('score_per_day','score_login','score_recommend'));
//
//        foreach($score_settings as $key => $value)
//        {
//
//            $score_settings[$key] = $settings[$key];
//        }
//        $score_settings['login_today'] = $login_count['today'];
//
//        $this->json_output($score_settings);
//    }

    /**
     * 2017-4-5 11:48:04
     * created by zp
     */
    public function  addScore(){
        $points = M ('system:setting')->get ('points')['points']; //这个是加了缓存的
        $token = $this->param['token'];
        $_POST['token'] = $token;
        $user_id = A("qapp:qapi1_2")->checkAccount();
        $type  = $this->param['type'];
        $_today= strtotime(date("Y-m-d"));
        M ("qapp:pointsBill")->startTrans();
        switch($type){
            case 1://拉新注册登录
                $parent_mobile = $this->param['parent_mobile'];
                $focused_id = M("public:common")->from ('customer_contact')
                    ->select ('user_id')
                    ->where ('mobile='.$parent_mobile)
                    ->getOne ();
                if($focused_id>0){//引荐
                    if(!M ("qapp:pointsBill")->addPoints ($points['ref'], $focused_id, 12)){
                        M ("qapp:pointsBill")->rollback();
                        $this->json_output (array( 'err' => 101, 'msg' => '系统错误' ));
                    }
                }
                //注册
                if(!M ("qapp:pointsBill")->addPoints ($points['register'], $user_id, 7)){
                    M ("qapp:pointsBill")->rollback();
                    $this->json_output (array( 'err' => 101, 'msg' => '系统错误' ));
                }
                //登录
                if(!M ("qapp:pointsBill")->addPoints ($points['login'], $user_id, 2)){
                    M ("qapp:pointsBill")->rollback();
                    $this->json_output (array( 'err' => 101, 'msg' => '系统错误' ));
                }
                M ("qapp:pointsBill")->commit();
                $this->json_output (array( 'err' => 0, 'msg' => '积分添加成功' ));
                break;
            case 2://注册登录
                //注册
                if(!M ("qapp:pointsBill")->addPoints ($points['register'], $user_id, 7)){
                    M ("qapp:pointsBill")->rollback();
                    $this->json_output (array( 'err' => 101, 'msg' => '系统错误' ));
                }
                //登录
                if(!M ("qapp:pointsBill")->addPoints ($points['login'], $user_id, 2)){
                    M ("qapp:pointsBill")->rollback();
                    $this->json_output (array( 'err' => 101, 'msg' => '系统错误' ));
                }
                M ("qapp:pointsBill")->commit();
                $this->json_output (array( 'err' => 0, 'msg' => '积分添加成功' ));
                break;
            case 3:
                //登录
                $_tmp = M ("qapp:pointsBill")->select('id ,addtime')->where("uid = $user_id and type =2")->order("id desc")->limit(4)->getAll();
                $size = 1;
                foreach($_tmp as $key=>$row){
                    if($row['addtime']>(strtotime(date("Y-m-d"))-86400*($key+1))&&$row['addtime']<(strtotime(date("Y-m-d"))-86400*$key)) $size++;
                }
                if(!M ("qapp:pointsBill")->addPoints ($points['login']*$size, $user_id, 2)){
                    M ("qapp:pointsBill")->rollback();
                    $this->json_output (array( 'err' => 101, 'msg' => '系统错误' ));
                }
                M ("qapp:pointsBill")->commit();
                $this->json_output (array( 'err' => 0, 'msg' => '积分添加成功' ));
                break;
            case 4://分享求购
                $pur_type = $this->param['pur_type'];
                $_today= strtotime(date("Y-m-d"));
                $_tmp = M ("qapp:pointsBill")->select('id ,addtime')->where("uid = $user_id and type =13 and share_type=1 and addtime>$_today")->order("id desc")->getAll();
                if(count($_tmp)>=5) $this->json_output(array('err'=>0,'msg'=>'今天求购分享次数>5，不加积分'));
                if(!in_array($pur_type,array('1','2'))) $this->json_output(array('err'=>6,'msg'=>'参数错误'));
                if(!M ("qapp:pointsBill")->addPoints ($points['share_pur'], $user_id, 13,1)){
                    M ("qapp:pointsBill")->rollback();
                    $this->json_output (array( 'err' => 101, 'msg' => '系统错误' ));
                }
                M ("qapp:pointsBill")->commit();
                $this->json_output (array( 'err' => 0, 'msg' => '积分添加成功' ));
                break;
            case 5://分享供给
                $pur_type = $this->param['pur_type'];
                $_today= strtotime(date("Y-m-d"));
                $_tmp = M ("qapp:pointsBill")->select('id ,addtime')->where("uid = $user_id and type =13 and share_type=2 and addtime>$_today")->order("id desc")->getAll();
                if(count($_tmp)>=5) $this->json_output(array('err'=>0,'msg'=>'今天供给分享次数>5，不加积分'));
                if(!in_array($pur_type,array('1','2'))) $this->json_output(array('err'=>6,'msg'=>'参数错误'));
                if(!M ("qapp:pointsBill")->addPoints ($points['share_pur'], $user_id, 13,2)){
                    M ("qapp:pointsBill")->rollback();
                    $this->json_output (array( 'err' => 101, 'msg' => '系统错误' ));
                }
                M ("qapp:pointsBill")->commit();
                $this->json_output (array( 'err' => 0, 'msg' => '积分添加成功' ));
                break;
            case 6://分享头条
                $_tmp = M ("qapp:pointsBill")->select('id ,addtime')->where("uid = $user_id and type =13 and share_type=3 and addtime>$_today")->order("id desc")->getAll();
                if(count($_tmp)>=5) $this->json_output(array('err'=>0,'msg'=>'今天塑料头条分享次数>5，不加积分'));
                if(!M ("qapp:pointsBill")->addPoints ($points['share_news'], $user_id, 13,3)){
                    M ("qapp:pointsBill")->rollback();
                    $this->json_output (array( 'err' => 101, 'msg' => '系统错误' ));
                }
                M ("qapp:pointsBill")->commit();
                $this->json_output (array( 'err' => 0, 'msg' => '积分添加成功' ));
                break;
            case 7://发布求购
                $_tmp = M ("qapp:pointsBill")->select('id ,addtime')->where("uid = $user_id and type =6 and addtime>$_today")->order("id desc")->getAll();
                if(count($_tmp)>=5) $this->json_output(array('err'=>0,'msg'=>'今天发布求购次数>5，不加积分'));
                if(!M ("qapp:pointsBill")->addPoints ($points['pur'], $user_id, 6)){
                    M ("qapp:pointsBill")->rollback();
                    $this->json_output (array( 'err' => 101, 'msg' => '系统错误' ));
                }
                M ("qapp:pointsBill")->commit();
                $this->json_output (array( 'err' => 0, 'msg' => '积分添加成功' ));
                break;
            case 8://发布供给（报价）
                $_tmp = M ("qapp:pointsBill")->select('id ,addtime')->where("uid = $user_id and type =3 and addtime>$_today")->order("id desc")->getAll();
                if(count($_tmp)>=5) $this->json_output(array('err'=>0,'msg'=>'今天发布供给次数>5，不加积分'));
                if(!M ("qapp:pointsBill")->addPoints ($points['sale'], $user_id, 3)){
                    M ("qapp:pointsBill")->rollback();
                    $this->json_output (array( 'err' => 101, 'msg' => '系统错误' ));
                }
                M ("qapp:pointsBill")->commit();
                $this->json_output (array( 'err' => 0, 'msg' => '积分添加成功' ));
                break;
            case 9://充值加积分
                M ("qapp:pointsBill")->commit();
                $this->json_output (array( 'err' => 5, 'msg' => '不定时上线,敬请期待！' ));
                break;

        }
    }

    public function decScore(){
        $token = $this->param['token'];
        $_POST['token'] = $token;
        $user_id = A("qapp:qapi1_2")->checkAccount();
        $type  = $this->param['type'];
        $_today= strtotime(date("Y-m-d"));
        M ("qapp:pointsBill")->startTrans();
        switch($type) {
            case 1://兑换商品
                $points = $this->param['points'];
                $gid = $this->param['gid'];
                if(empty($gid)) $this->json_output(array('err'=>6,'msg'=>'参数错误'));
                //decPoints ($num = 0, $uid = 0, $type = 0, $gid = 0)
                if (!M("qapp:pointsBill")->decPoints($points, $user_id, 5 ,$gid)) {
                    M("qapp:pointsBill")->rollback();
                    $this->json_output(array('err' => 101, 'msg' => '系统错误'));
                }
                M("qapp:pointsBill")->commit();
                $this->json_output(array('err' => 0, 'msg' => '积分减少成功'));
                break;
            case 2://查看通讯录
                $points = M ('system:setting')->get ('points')['points']; //这个是加了缓存的
                $points = $points['see_list'];
                $other_id = $this->param['other_id'];
                if(!M("qapp:infoList")->add(array('user_id'=>$user_id,'other_id'=>$other_id,'input_time'=>CORE_TIME))){
                    M("qapp:pointsBill")->rollback();
                    $this->json_output(array('err' => 101, 'msg' => '系统错误'));
                }
                if (!M("qapp:pointsBill")->decPoints($points, $user_id, 14)) {
                    M("qapp:pointsBill")->rollback();
                    $this->json_output(array('err' => 101, 'msg' => '系统错误'));
                }
                M("qapp:pointsBill")->commit();
                $this->json_output(array('err' => 0, 'msg' => '积分减少成功'));
                break;
            case 3://查看文章
                M ("qapp:pointsBill")->commit();
                $this->json_output (array( 'err' => 5, 'msg' => '不定时上线,敬请期待！' ));
                break;
        }
    }



}
