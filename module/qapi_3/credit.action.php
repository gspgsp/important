<?php
/**
 * Created by PhpStorm.
 * User: sick
 * Date: 17-5-5
 * Time: 上午11:49
 */
class creditAction extends baseAction
{

    /**
     * 获取企查查的接口
     * @api {get} /qapi_3/credit/getQiChaCha 获取企查查的接口
     * @apiVersion 3.1.0
     * @apiName  getQiChaCha
     * @apiGroup Credit
     *
     * @apiParam   {String} token  token qwre3123123121swqsq
     * @apiParam   {String} name   企业名称 上海中辰电子商务有限公司
     *
     * @apiSuccess {String}  msg   描述
     * @apiSuccess {String}  err   错误码
     * @apiSuccess {String}  url   apk下载地址
     *
     * @apiSuccessExample {json} Success-Response:
     *      {
     *      "err":0
     *      "msg":"密码重置成功"
     *      }
     * @apiErrorExample {json} Error-Response:
     *     {
     *       "err": 2,
     *       "msg": "没有相关数据"
     *      }
     */
    public function getQiChaCha ()
    {
        if ($_POST['token'] && $_POST['name']) {
            $user_id = $this->checkAccount ();
            $name    = sget ('name', 's');
            $name    = $this->clearStr ($name);
            if (empty($name)) {
                $this->_errCode (6);
            }
            $tmp = M ('qapp:customerBase')->selectOrNot ($name);
            $this->json_output ($tmp);
        }
        $this->_errCode (6);
    }

    /**
     * 获取证书
     * @api {get} /qapi_3/credit/creditCertificate 获取证书
     * @apiVersion 3.1.0
     * @apiName  creditCertificate
     * @apiGroup Credit
     *
     * @apiParam   {String} fname  token qwre3123123121swqsq
     * @apiParam   {Number} link_id  token qwre3123123121swqsq
     * @apiParam   {Number} page   页码
     * @apiParam   {Number} type   1 精确  2 模糊
     * @apiParam   {String} name   企业名称 上海中辰电子商务有限公司
     *
     */
    public function creditCertificate ()
    {
        $user_id = $this->checkAccount (0);
        $link_id = sget ('link_id', 'i');
        $fname   = sget ('fname', 's');
        $type    = sget ('type', 's');  //   1 精确  2 模糊
        $page    = sget ('page', 'i');
        //模糊查询没有了
        if (!empty($fname) && empty($link_id)) { //获取别人
            if (empty($type)) {
                $this->_errCode (6);
            }
            if ($type == 2 && empty($page)) {
                $this->_errCode (6);
            } //模糊查询没有page，报错
            $data = M ("qapp:plasticPersonalInfo")->getCompanyCredit ($fname, $type, $page);
            if (empty($data)) {
                $this->json_output (array(
                    'err' => 2,
                    'msg' => '没有此公司或此公司尚未被授信！',
                ));
            }
            $this->json_output (array(
                'err'  => 0,
                'data' => $data,
            ));
        }
        if (empty($link_id) || $link_id == 'undefined') {
            if ($user_id <= 0) {
                $this->_errCode (6);
            }//找不到用户
        } else {
            $user_id = $link_id;//获取微信用户分享人的id
        }
        //获取自己的
        $data = M ("qapp:plasticPersonalInfo")->getMyCredit ($user_id);
        if (empty($data)) {
            $this->json_output (array(
                'err' => 2,
                'msg' => '没有此公司或此公司尚未被授信！',
            ));
        }
        $this->json_output (array(
            'err'  => 0,
            'data' => $data,
        ));
    }

}