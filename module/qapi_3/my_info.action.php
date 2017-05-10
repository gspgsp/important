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

    /**
     *  关注/粉丝的头像
     * @api {get} /qapi_3/myInfo/headPicture   关注/粉丝的头像
     * @apiVersion 3.1.0
     * @apiName  headPicture
     * @apiGroup myInfo
     *
     * @apiParam   {String} token  token qwre3123123121swqsq
     *
     * @apiSuccess {int}  err   描述
     * @apiSuccess {json}  myfans   错误码
     * @apiSuccess {json}  myconcerns  供给
     *
     * @apiErrorExample {json} Error-Response:
     *     {
     *       "err": 2,
     *       "msg": "没有相关数据"
     *      }
     *
     */
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
    /**
     * 保存我的资料
     * @api {post} /qapi_3/myInfo/saveSelfInfo   保存我的资料
     * @apiVersion 3.1.0
     * @apiName  saveSelfInfo
     * @apiGroup myInfo
     *
     * @apiParam   {String} token  token qwre3123123121swqsq
     * @apiParam   {String} address  地址
     * @apiParam   {String} sex     性别 0男1女
     * @apiParam   {String} major  所需牌号  对应need_product
     * @apiParam   {String} concern
     * @apiParam   {String} dist
     * @apiParam   {String} type
     * @apiParam   {String} month_consum  月用量
     * @apiParam   {String} main_product  工厂的主营产品
     *
     * @apiSuccess {int}  err   错误码
     * @apiSuccess {String}   msg   描述
     *
     * @apiSuccessExample {json} Success-Response:
         *{
        "err": 0,
        "msg": "保存资料成功"
        }
     * @apiErrorExample {json} Error-Response:
     *     {
     *       "err": 2,
     *       "msg": "没有相关数据"
     *      }
     *
     */
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
    /**
     * 查看我的资料
     * @api {post} /qapi_3/myInfo/saveSelfInfo   查看我的资料
     * @apiVersion 3.1.0
     * @apiName  saveSelfInfo
     * @apiGroup myInfo
     *
     * @apiParam   {String} token  token qwre3123123121swqsq
     *
     * @apiSuccess {int}  err   错误码
     * @apiSuccess {String}   msg   描述
     * @apiSuccess {String}   data  个人信息数据
     * @apiSuccess {String}   c_name   描述
     * @apiSuccess {String}   address   描述
     * @apiSuccess {String}   type   描述
     * @apiSuccess {String}   month_consum   描述
     * @apiSuccess {String}   main_product   描述
     * @apiSuccess {String}   buy   描述
     * @apiSuccess {String}   sale   描述
     * @apiSuccess {String}   total   描述
     * @apiSuccess {String}   rank   描述
     * @apiSuccess {String}   fans   描述
     * @apiSuccess {String}   concern_model   描述
     *
     * @apiSuccessExample {json} Success-Response:
             * {
            "err": 0,
            "data": {
            "user_id": "40418",
            "name": "谢磊",
            "c_id": "5041",
            "mobile": "18321871909",
            "adistinct": "华东",
            "sex": "男",
            "member_level": "列兵",
            "thumb": "http://statics.myplas.com/myapp/img/male.jpg",
            "thumbqq": "",
            "thumbcard": "",
            "allow_send": {
            "focus": 0,
            "repeat": 0,
            "show": 0
            },
            "c_name": "上海中晨电子商务股份有限公司",
            "need_product": "2102TX00|2119.2420D.1810D.S030.3003",
            "address": "上海",
            "type": "1",
            "month_consum": "123",
            "main_product": "pp",
            "buy": "1",
            "sale": 0,
            "total": 16914,
            "rank": "8723",
            "fans": "0",
            "concern_model": ""
            }
            }
     * @apiErrorExample {json} Error-Response:
     *     {
     *       "err": 2,
     *       "msg": "没有相关资料"
     *      }
     *
     */
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
    /**
     * 偏好设置-发送短信
     * @api {post} /qapi_3/myInfo/favorateSet   偏好设置-发送短信
     * @apiVersion 3.1.0
     * @apiName  favorateSet
     * @apiGroup myInfo
     *
     * @apiParam   {String} token  token qwre3123123121swqsq
     * @apiParam   {String} type  0 关注 1 回复 2是否公开电话
     * @apiParam   {String} is_allow  0:允许 1:不允许
     *
     * @apiSuccess {int}  err   错误码
     * @apiSuccess {String}   msg   描述
     *
     * @apiSuccessExample {json} Success-Response:
            {
            "err": 0,
            "msg": "偏好设置成功"
            }
     *
     *
     */
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

    /**
     * 获取我的粉丝和我的关注(数)
     * @api {post} /qapi_3/myInfo/getMyFuns   获取我的粉丝和我的关注(数)
     * @apiVersion 3.1.0
     * @apiName  getMyFuns
     * @apiGroup myInfo
     *
     * @apiParam   {String} type  1粉丝2关注
     * @apiParam   {String} page  页码
     * @apiParam   {String} size  每页数据量
     *
     * @apiSuccess {int}  err   错误码
     * @apiSuccess {String}   msg   描述
     * @apiSuccess {String}   data   数据
     * @apiSuccess {Int}   count   总共
     *
     * @apiSuccessExample {json} Success-Response:
                {
                "err": 0,
                "data": [
                {
                "user_id": {
                "user_id": "39453",
                "name": "崔永建",
                "mobile": "1511716****",
                "is_pass": "0",
                "c_name": "甘肃龙昌石化集团有限公司",
                "thumb": "http://statics.myplas.com/upload/17/02/08/589b2562a6552.JPG",
                "thumbqq": "http://statics.myplas.com/upload/17/02/08/589b2562a6552.JPG",
                "sex": "0",
                "buy": "0",
                "sale": "3"
                },
                "focused_id": "45782"
                },
                {
                "user_id": {
                "user_id": "53991",
                "name": "王铭",
                "mobile": "1881111****",
                "is_pass": "0",
                "c_name": "上海梓晨实业有限公司",
                "thumb": "http://statics.myplas.com/upload/17/04/27/5901ccc540ba6.jpg",
                "thumbqq": "http://statics.myplas.com/upload/17/04/27/5901ccc540ba6.jpg",
                "sex": "0",
                "buy": "0",
                "sale": "0"
                },
                "focused_id": "45782"
                },
                {
                "user_id": {
                "user_id": "41497",
                "name": "黄双",
                "mobile": "1537841****",
                "is_pass": "0",
                "c_name": "上海中晨电子商务股份有限公司",
                "thumb": "http://statics.myplas.com/upload/17/05/06/590d52b93c5d6.jpg",
                "thumbqq": "http://statics.myplas.com/upload/17/05/06/590d52b93c5d6.jpg",
                "sex": "0",
                "buy": "0",
                "sale": "0"
                },
                "focused_id": "45782"
                }
                ],
                "count": "3"
                }
     *
     *
     */

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

    /**
     * 获取系统消息robot
     * @api {post} /qapi_3/myInfo/getRobotMsg   获取系统消息robot
     * @apiVersion 3.1.0
     * @apiName  getRobotMsg
     * @apiGroup myInfo
     *
     * @apiParam   {String} page  页码
     * @apiParam   {String} size  每页数据量
     *
     * @apiSuccess {int}  err   错误码
     * @apiSuccess {String}   msg   描述
     * @apiSuccess {String}   data   数据
     *
     * @apiSuccessExample {json} Success-Response:
             *{
            "err": 0,
            "data": [
            {
            "id": "25625",
            "pur_id": "95813",
            "pur_user_id": "55355",
            "user_id": "40418",
            "content": "您关注的肖生发布了1条供给信息，信息内容为:（56-16）不义气，无成功！『义成塑胶』专注ABS   PC/ABS   475  PC改性/配色/抽粒。现金求购：ABS  475   PC/ABS    PC系列一次水口，工厂余料。电询137 6321 ",
            "input_time": "05-06 12:49:46",
            "is_read": "0",
            "type": "1"
            },
            {
            "id": "25404",
            "pur_id": "95570",
            "pur_user_id": "55355",
            "user_id": "40418",
            "content": "您关注的肖生发布了1条供给信息，信息内容为:供应尼龙黑色基材料，台湾大白料，尼龙增强，增韧，耐寒，耐高温等改性料。联系电话13682440429",
            "input_time": "05-05 12:42:58",
            "is_read": "0",
            "type": "1"
            },
            {
            "id": "24344",
            "pur_id": "94714",
            "pur_user_id": "30017",
            "user_id": "40418",
            "content": "您关注的张贝贝发布了1条供给信息，信息内容为:上海出现货：\rN210 N220 Q281 Q281D  Q210  Q400\rT300 M1200HS Y2600T M250E GM160E M800E GM1600E M180R M700R M2600R F800E F800EDF H2800 \rMH602 YGH041 金菲TR480 5502 50100\r电话 021-3790",
            "input_time": "05-02 13:02:48",
            "is_read": "1",
            "type": "1"
            },
            {
            "id": "18898",
            "pur_id": "86779",
            "pur_user_id": "40418",
            "user_id": "40418",
            "content": "您于 04-08 11:44 发布的求购信息:价格8000.00元左右/7000F/上海/上海收到一条回复:你大约需要多少",
            "input_time": "04-08 12:14:20",
            "is_read": "1",
            "type": "2"
            }
            ]
            }
     *
     */
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
    /**
     * 保存名片到服务器
     * @api {post} /qapi_3/myInfo/saveCardImg   保存名片到服务器
     * @apiVersion 3.1.0
     * @apiName  saveCardImg
     * @apiGroup myInfo
     *
     * @apiParam   {String} token  token qwre3123123121swqsq
     *
     * @apiSuccess {int}  err   错误码
     * @apiSuccess {String}   msg   描述
     * @apiSuccess {String}   url   上传文件全路径
     *
     * @apiSuccessExample {json} Success-Response:
             {
            "err": 0,
            "url": "http://statics.myplas.com/upload/17/05/10/5912d3e082152.jpg"
            }
     *
     */
    public function saveCardImg ()
    {
        $this->is_ajax = true; //指定为Ajax输出
        //$this->json_output(array('err'=>1,'msg'=>'test'));
        $user_id = $this->checkAccount ();
        $result  = A ('public:upload')->saveqAppCardImg ('', 1, $user_id);
    }


    /**
     *  获取我的引荐(引荐数)
     * @api {post} /qapi_3/myInfo/getMyIntroduction   获取我的引荐(引荐数)
     * @apiVersion 3.1.0
     * @apiName  getMyIntroduction
     * @apiGroup myInfo
     *
     * @apiParam   {String} page  页码
     * @apiParam   {String} size  每页数据量
     *
     * @apiSuccess {int}  err   错误码
     * @apiSuccess {String}   msg   描述
     * @apiSuccess {String}   data   数据
     *
     * @apiSuccessExample {json} Success-Response:
             *{
            "err": 0,
            "data": [
            {
            "user_id": "35942",
            "name": "顾晓懿",
            "mobile": "1377682****",
            "is_pass": "0",
            "c_name": "上海中晨电子商务股份有限公司",
            "thumb": "http://statics.myplas.com/myapp/img/male.jpg",
            "buy": "0",
            "sale": "0"
            },
            {
            "user_id": "35934",
            "name": "朱彼德",
            "mobile": "1505841****",
            "is_pass": "0",
            "c_name": "蓝鲸产品厂家直销",
            "thumb": "http://statics.myplas.com/myapp/img/male.jpg",
            "buy": "0",
            "sale": "0"
            },
            {
            "user_id": "35913",
            "name": "张YAN",
            "mobile": "1381021****",
            "is_pass": "0",
            "c_name": "群星集团公司",
            "thumb": "http://statics.myplas.com/myapp/img/male.jpg",
            "buy": "0",
            "sale": "0"
            },
            {
            "user_id": "35911",
            "name": "上官",
            "mobile": "1865390****",
            "is_pass": "0",
            "c_name": "临沂裕田塑料制品厂",
            "thumb": "http://statics.myplas.com/myapp/img/male.jpg",
            "buy": "0",
            "sale": "0"
            },
            {
            "user_id": "35905",
            "name": "小段",
            "mobile": "1370178****",
            "is_pass": "0",
            "c_name": "上海瑞藩实业有限公司",
            "thumb": "http://statics.myplas.com/myapp/img/male.jpg",
            "buy": "0",
            "sale": "0"
            },
            {
            "user_id": "35903",
            "name": "孙永征",
            "mobile": "1390539****",
            "is_pass": "0",
            "c_name": "山东临沂永征进出口有限公司",
            "thumb": "http://statics.myplas.com/myapp/img/male.jpg",
            "buy": "0",
            "sale": "0"
            },
            {
            "user_id": "35902",
            "name": "罗立成",
            "mobile": "1391325****",
            "is_pass": "1",
            "c_name": "江苏金塑德贸易有限公司",
            "thumb": "http://statics.myplas.com/myapp/img/male.jpg",
            "buy": "0",
            "sale": "0"
            },
            {
            "user_id": "35897",
            "name": "谢坤衡",
            "mobile": "1390633****",
            "is_pass": "0",
            "c_name": "莒县永达工贸有限公司",
            "thumb": "http://statics.myplas.com/myapp/img/male.jpg",
            "buy": "3",
            "sale": "0"
            },
            {
            "user_id": "35795",
            "name": "PK",
            "mobile": "1861633****",
            "is_pass": "0",
            "c_name": "中泰证券研究所",
            "thumb": "http://statics.myplas.com/myapp/img/male.jpg",
            "buy": "0",
            "sale": "0"
            },
            {
            "user_id": "35584",
            "name": "周思宇",
            "mobile": "1361087****",
            "is_pass": "0",
            "c_name": "盘锦鑫宇盈塑料有限公司",
            "thumb": "http://statics.myplas.com/myapp/img/male.jpg",
            "buy": "0",
            "sale": "0"
            }
            ],
            "count": "26"
            }
     *
     */
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