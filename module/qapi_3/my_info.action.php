<?php
/**
 * Created by PhpStorm.
 * User: sick
 * Date: 17-5-5
 * Time: 下午3:36
 */
class myInfoAction extends baseAction
{
    /**
     * 我的塑料圈
     * @api {get} /qapi_3/myInfo/myZone   我的塑料圈
     * @apiVersion 3.1.0
     * @apiName  myZone
     * @apiGroup myInfo
     *
     * @apiParam   {String} token  token qwre3123123121swqsq
     *
     * @apiSuccess {String}  msg   描述
     * @apiSuccess {String}  err   错误码
     * @apiSuccess {String}  s_in_count  供给
     * @apiSuccess {String}  s_out_count  求购
     * @apiSuccess {String}  points  塑豆
     * @apiSuccess {String}  leaveword  留言
     * @apiSuccess {String}  message  我的消息
     * @apiSuccess {String}  introduction  我的引荐
     * @apiSuccess {String}  myfans  我的粉丝
     * @apiSuccess {String}  myconcerns  我的关注
     * @apiSuccess {String}  data  我的信息数据
     *
     * @apiSuccessExample {json} Success-Response:
            {
            "s_in_count": "1",
            "s_out_count": 0,
            "points": "880",
            "leaveword": 0,
            "message": 0,
            "introduction": 0,
            "myfans": 0,
            "myconcerns": 3,
            "data": {
            "user_id": "40418",
            "name": "谢磊",
            "c_id": "5041",
            "mobile": "18321871909",
            "is_pass": "0",
            "thumb": "http://statics.myplas.com/myapp/img/male.jpg",
            "thumbqq": "",
            "thumbcard": "",
            "c_name": "上海中晨电子商务股份有限公司",
            "credit_level": "AAAAA",
            "credit_limit": "600.00",
            "is_credit": "1",
            "pre_credit_limit": "600.00",
            "credit_time": "1488357059",
            "sex": "0"
            }
            }
     * @apiErrorExample {json} Error-Response:
     *     {
     *       "err": 2,
     *       "msg": "没有相关数据"
     *      }
     *
     */
    public function myZone ()
    {
        $this->is_ajax = true;
        if ($_POST['token']) {
            $user_id = $this->checkAccount ();
            //我的求购
            $s_in_count = M ('qapp:plasticPersonalInfo')->getConut ($user_id, 1, 6);//purchase表
            $s_in_count = empty($s_in_count) ? 0 : $s_in_count;
            //我的报价
            $s_out_count = M ('qapp:plasticPersonalInfo')->getConut ($user_id, 2, 6);//purchase表
            $s_out_count = empty($s_out_count) ? 0 : $s_out_count;
            //我的积分
            $points = M ('qapp:pointsBill')->getUerPoints ($user_id);
            $points = empty($points) ? 0 : $points;
            //我的留言
            $leaveword = M ('qapp:plasticMsgCount')->getMsgCount ($user_id, 1);
            if (!$user_id > 0 || !$leaveword > 0) {
                $leaveword = 0;
            }
            //我的未读消息
            $message = M ('qapp:plasticMsgCount')->getRobotCount ($user_id);
            if (!$user_id > 0 || !$message > 0) {
                $message = 0;
            }
            //我的引荐
            $data         = M ('qapp:plasticIntroduction')->getqAppMyIntroduction ($user_id);
            $introduction = empty($data['count']) ? 0 : $data['count'];
            //我的粉丝
            $data   = M ('qapp:plasticMsgCount')->getMyFunsCount ($user_id, 1);
            $myfans = empty($data) ? 0 : $data;
            //我的关注
            $data       = M ('qapp:plasticMsgCount')->getMyFunsCount ($user_id, 2);
            $myconcerns = empty($data) ? 0 : $data;
            //获取我的塑料圈个人信息
            $headimgurl = '';
            $data       = M ('qapp:plasticPersonalInfo')->getMyPlastic ($user_id, $headimgurl);
            //            var_dump($user_id);
            //            var_dump($data);exit;
            if (empty($data)) {
                $this->json_output (array(
                    'err' => 2,
                    'msg' => '没有相关资料',
                ));
            }
            $data = empty($data) ? array(
                'err' => 2,
                'msg' => '没有相关资料',
            ) : $data;
            $this->json_output (array(
                's_in_count'   => $s_in_count,
                //我的求购
                's_out_count'  => $s_out_count,
                //我的供给
                'points'       => $points,
                //我的积分
                'leaveword'    => $leaveword,
                //我的留言
                'message'      => $message,
                //我的未读消息
                'introduction' => $introduction,
                //我的引荐
                'myfans'       => $myfans,
                //我的粉丝
                'myconcerns'   => $myconcerns,
                //我的关注
                'data'         => $data,
                //我的个人信息
            ));
        }
        $this->_errCode (6);
    }
    /**
     * 塑料圈联系人的-我的消息（yuepao）
     * @api {get} /qapi_3/myInfo/getZoneContactMsg   塑料圈联系人的-我的消息（yuepao）
     * @apiVersion 3.1.0
     * @apiName  getZoneContactMsg
     * @apiGroup myInfo
     *
     * @apiParam   {String} token  token qwre3123123121swqsq
     *
     * @apiSuccess {String}  msg   描述
     * @apiSuccess {String}  err   错误码
     * @apiSuccess {String}  s_in_count  供给
     * @apiSuccess {String}  s_out_count  求购
     * @apiSuccess {String}  points  塑豆
     * @apiSuccess {String}  leaveword  留言
     * @apiSuccess {String}  message  我的消息
     * @apiSuccess {String}  introduction  我的引荐
     * @apiSuccess {String}  myfans  我的粉丝
     * @apiSuccess {String}  myconcerns  我的关注
     * @apiSuccess {String}  data  我的信息数据
     *
     * @apiSuccessExample {json} Success-Response:
    {
    "s_in_count": "1",
    "s_out_count": 0,
    "points": "880",
    "leaveword": 0,
    "message": 0,
    "introduction": 0,
    "myfans": 0,
    "myconcerns": 3,
    "data": {
    "user_id": "40418",
    "name": "谢磊",
    "c_id": "5041",
    "mobile": "18321871909",
    "is_pass": "0",
    "thumb": "http://statics.myplas.com/myapp/img/male.jpg",
    "thumbqq": "",
    "thumbcard": "",
    "c_name": "上海中晨电子商务股份有限公司",
    "credit_level": "AAAAA",
    "credit_limit": "600.00",
    "is_credit": "1",
    "pre_credit_limit": "600.00",
    "credit_time": "1488357059",
    "sex": "0"
    }
    }
     * @apiErrorExample {json} Error-Response:
     *     {
     *       "err": 2,
     *       "msg": "没有相关数据"
     *      }
     *
     */
    public function getZoneContactMsg ()
    {
        $this->is_ajax = true;
        if ($_POST) {
            $user_id = $this->checkAccount ();
            $page    = sget ('page', 'i', 1);
            $size    = sget ('size', 'i', 10);
            $type    = sget ('type', 'i', 1);//1:我接受的 2:我发送的
            $data    = M ('qapp:plasticMyMsg')->getZoneContactMsg ($user_id, $type, $page, $size);
            if (empty($data['data']) && $page == 1) {
                $this->json_output (array(
                    'err' => 2,
                    'msg' => '没有相关的数据',
                ));
            }
            $this->_checkLastPage ($data['count'], $size, $page);
            $this->json_output (array(
                'err'  => 0,
                'data' => $data['data'],
            ));
        }
        $this->_errCode (6);
    }




    //关注。粉丝的头像
    public function headPicture ()
    {
        if ($_POST) {
            $user_id = $this->checkAccount ();
            //我的粉丝
            $data   = M ('plasticzone:plasticIntroduction')->getMyFuns ($user_id, 1);
            $myfans = array();
            foreach ($data['data'] as $row) {
                $myfans[] = $row['user_id']['thumb'];
                if (count ($myfans) >= 9) {
                    break;
                }
            }
            //我的关注
            $data       = M ('plasticzone:plasticIntroduction')->getMyFuns ($user_id, 2);
            $myconcerns = array();
            foreach ($data['data'] as $row) {
                $myconcerns[] = $row['focused_id']['thumb'];
                if (count ($myconcerns) >= 9) {
                    break;
                }
            }
            $this->json_output (array(
                'err'        => 0,
                'myfans'     => $myfans,
                'myconcerns' => $myconcerns,
            ));
        }
        $this->_errCode (6);
    }

    //保存我的资料
    public function saveSelfInfo ()
    {
        $this->is_ajax = true;

        if ($_POST) {
            $user_id              = $this->checkAccount ();
            $_tmpAddress          = sget ('address', 's');
            $_tmpSex              = sget ('sex', 's');
            $_tmpMajor            = sget ('major', 's');
            $_tmpConcern          = sget ('concern', 's');
            $_tmpDist             = sget ('dist', 's', 'EC');
            $_tmpType             = sget ('type', 's');
            $_tmpMonthConsum      = sget ('month_consum', 's');
            $_tmpMainProduct      = sget ('main_product', 's');
            $data['address']      = $_tmpAddress;
            $data['sex']          = $_tmpSex;
            $data['major']        = $_tmpMajor;
            $data['concern']      = $_tmpConcern;
            $data['dist']         = $_tmpDist;
            $data['month_consum'] = $_tmpMonthConsum;
            $data['type']         = $_tmpType;
            $data['main_product'] = $_tmpMainProduct;
            foreach ($data as $key => &$row) {
                if ($key == 'address') {
                    $row = $this->clearStr ($row);
                } elseif ($key == 'sex') {
                    if (!in_array ($row, array(
                        '1',
                        '0',
                    ))
                    ) {
                        $this->_errCode (6);
                    }
                    $row = (int)$row;
                } elseif ($key == 'major') {
                    $field = $this->clearStr ($row);
                    if (!is_string ($row)) {
                        $this->json_output (array(
                            'err' => 1,
                            'msg' => '格式错误',
                        ));
                    }
                    if (!empty($field)) {
                        $field = explode (",", $field);
                        $field = array_map ('strtoupper', $field);
                        foreach ($field as $key1 => $row1) {
                            if (empty($row1)) {
                                unset($field[$key1]);
                            }
                        }
                        $field = array_unique ($field);
                        //$field=explode(",",array_map('strtoupper',$field));
                        if (count ($field) > 10) {
                            $this->json_output (array(
                                'err' => 6,
                                'msg' => '牌号个数不能超过十个',
                            ));
                        }
                        $row = $field;
                    } else {
                        unset($data[$key]);
                    }
                } elseif ($key == 'concern') {
                    $row   = $this->clearStr ($row);
                    $field = preg_replace ("/(\n)|(\s)|(\t)|(\')|(')|(，)|( )|(\.)/", ',', $row);
                    //$field=explode(",",array_map('strtoupper',$field));
                    if (!is_string ($row)) {
                        $this->json_output (array(
                            'err' => 1,
                            'msg' => '格式错误',
                        ));
                    }
                    if (!empty($field)) {
                        $field = explode (",", $field);
                        $field = array_map ('strtoupper', $field);
                        foreach ($field as $key1 => $row1) {
                            if (empty($row1)) {
                                unset($field[$key1]);
                            }
                        }
                        $field = array_unique ($field);
                        //$field=explode(",",array_map('strtoupper',$field));
                        if (count ($field) > 10) {
                            $this->json_output (array(
                                'err' => 6,
                                'msg' => '牌号个数不能超过十个',
                            ));
                        }
                        $row = $field;
                    } else {
                        unset($data[$key]);
                    }
                } elseif ($key == 'dist') {
                    if (empty($row) || (!in_array ($row, array(
                            'EC',
                            'NC',
                            'SC',
                            'OT',
                        )))
                    ) {
                        $this->_errCode (6);
                    }
                } elseif ($key == 'type') {
                    if (empty($row) || (!in_array ($row, array(
                            '1',
                            '2',
                            '3',
                            '4',
                        )))
                    ) {
                        $this->_errCode (6);
                    }
                } elseif ($key == 'month_consum') {
                    $row = $this->clearStr ($row);
                    if (mb_strlen ($row) > 15) {
                        $this->json_output (array(
                            'err' => 1,
                            'msg' => '1字符过长',
                        ));
                    }
                } elseif ($key == 'main_product') {
                    $row = $this->clearStr ($row);
                    if (mb_strlen ($row) > 25) {
                        $this->json_output (array(
                            'err' => 1,
                            'msg' => '2字符过长',
                        ));
                    }
                }
            }
            $result = M ('qapp:plasticSave')->saveSelfInfo1_2 ($user_id, $data);
            if ($result['err'] > 0) {
                $this->json_output (array(
                    'err' => 2,
                    'msg' => $result['msg'],
                ));
            }
            $this->json_output (array(
                'err' => 0,
                'msg' => '保存资料成功',
            ));
        }
        $this->_errCode (6);
    }

    //查看我的资料
    public function getSelfInfo ()
    {
        $this->is_ajax = true;
        if ($_POST) {
            $user_id = $this->checkAccount ();
            $data    = M ('qapp:plasticPersonalInfo')->getSelfInfo ($user_id);
            if (empty($data)) {
                $this->json_output (array(
                    'err' => 2,
                    'msg' => '没有相关资料',
                ));
            }
            $this->json_output (array(
                'err'  => 0,
                'data' => $data,
            ));
        }
        $this->_errCode (6);
    }

    //获取我的塑料圈个人信息
    public function getMyPlastic ()
    {
        $cache         = cache::startMemcache ();
        $this->is_ajax = true;
        if ($_POST) {
            $user_id    = $this->checkAccount ();
            $headimgurl = sget ('headimgurl', 's');
            $data       = M ('qapp:plasticPersonalInfo')->getMyPlastic ($user_id, $headimgurl);
            if (empty($data)) {
                $this->json_output (array(
                    'err' => 2,
                    'msg' => '没有相关资料',
                ));
            }
            $this->json_output (array(
                'err'  => 0,
                'data' => $data,
            ));
        }
        $this->_errCode (6);
    }

    //偏好设置-发送短信
    public function favorateSet ()
    {
        $this->is_ajax = true;
        if ($_POST) {
            $user_id  = $this->checkAccount ();
            $type     = sget ('type', 'i');//0 关注 1 回复 2是否公开电话
            $is_allow = sget ('is_allow', 'i', 0);//0:允许 1:不允许
            $result   = M ('qapp:plasticAttention')->favorateSet ($user_id, $type, $is_allow);
            $this->json_output ($result);
        }
        $this->_errCode (6);
    }



    //获取我的粉丝和我的关注(数)
    public function getMyFuns ()
    {
        $this->is_ajax = true;
        if ($_POST) {
            $user_id = $this->checkAccount ();
            $type    = sget ('type', 'i');//1粉丝2关注
            $page    = sget ('page', 'i', 1);
            $size    = sget ('size', 'i', 10);
            $data    = M ('qapp:plasticIntroduction')->getMyFuns ($user_id, $type, $page, $size);
            if (empty($data['data']) && $page == 1) {
                $this->json_output (array(
                    'err'   => 2,
                    'msg'   => '没有相关的数据',
                    'count' => 0,
                ));
            }
            $this->_checkLastPage ($data['count'], $size, $page);
            $this->json_output (array(
                'err'   => 0,
                'data'  => $data['data'],
                'count' => $data['count'],
            ));
        }
        $this->_errCode (6);
    }


    //获取系统消息robot
    public function getRobotMsg ()
    {
        $this->is_ajax = true;
        if ($_POST) {
            $user_id = $this->checkAccount ();
            $page    = sget ('page', 'i', 1);
            $size    = sget ('size', 'i', 10);
            $data    = M ('qapp:robotMsg')->getRobotMsg ($user_id, $page, $size);
            if (empty($data['data']) && $page == 1) {
                $this->json_output (array(
                    'err' => 2,
                    'msg' => '没有相关的数据',
                ));
            }
            $this->_checkLastPage ($data['count'], $size, $page);
            $this->json_output (array(
                'err'  => 0,
                'data' => $data['data'],
            ));
        }
        $this->_errCode (6);
    }

    /**
     * @param $serverId jssdk文件上传返回的serverId
     *
     * @return string
     */
    public function savePicToServer ()
    {
        $this->is_ajax = true;
        $user_id       = $this->checkAccount ();
        //$this->json_output(array('err'=>1,'msg'=>'test'));
        A ('public:upload')->saveqAppCardImg ('', 2, $user_id);
    }

    //保存名片到服务器
    public function saveCardImg ()
    {
        $this->is_ajax = true; //指定为Ajax输出
        //$this->json_output(array('err'=>1,'msg'=>'test'));
        $user_id = $this->checkAccount ();
        $result  = A ('public:upload')->saveqAppCardImg ('', 1, $user_id);
    }

    //获取我的引荐(引荐数)
    public function getMyIntroduction ()
    {
        $this->is_ajax = true;
        if ($_POST['token']) {
            $user_id = $this->checkAccount ();
            $page    = sget ('page', 'i', 1);
            $size    = sget ('size', 'i', 10);
            $data    = M ('qapp:plasticIntroduction')->getqAppMyIntroduction ($user_id, $page, $size);
            if (empty($data['data']) && $page == 1) {
                $this->json_output (array(
                    'err'   => 2,
                    'msg'   => '没有相关的数据',
                    'count' => 0,
                ));
            }
            $this->_checkLastPage ($data['count'], $size, $page);
            //unset($data['data'][2]);
            //            echo '<pre>';
            //            var_dump($data);exit;
            $this->json_output (array(
                'err'   => 0,
                'data'  => $data['data'],
                'count' => $data['count'],
            ));
        }
        $this->_errCode (6);
    }


}