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
     * 企业额度信用页面接口
     * @api {post} /qapi_3/credit/creditPage 企业额度信用页面接口
     * @apiVersion 3.2.0
     * @apiName  creditPage
     * @apiGroup Credit
     * @apiUse UAHeader
     *
     * @apiSuccess {String}  msg   描述
     * @apiSuccess {String}  err   错误码
     * @apiSuccess {String}  c_name   公司名称
     * @apiSuccess {String}  data
     *
     * @apiSuccessExample {json} Success-Response:
             * {
            "err": 0,
            "c_name": "上海晨达物流有限公司",
            "data": [
            {
            "q": "<span>如何获得授信？</span>",
            "a": "<span>你可以根据以下方式与我们联系：</span><br/><span>邮箱：info@myplas.com</span><br/><span>客服热线：400-6129-965</span><br/><span>前台热线：021-61070985</span>"
            },
            {
            "q": "<span>如何提升额度？</span>",
            "a": "<span>一，在我的塑料网上形成交易，并确保交易信用良好</span><br/><span>二，在塑料圈通讯录上多发布供求信息</span><br><span>为塑料圈通讯录引入新的塑料交易人员</span>"
            }
            ]
            }
     *
     *
     */
    public function creditPage()
    {
        $user_id  = $this->checkAccount (0);

        $c_name = $this->db->model('customer cus')->leftjoin('customer_contact con','con.c_id = cus.c_id')->select('cus.c_name')->where("con.user_id=$user_id")->getOne();

        $arr = array(
            array(
                'q'=>'<span>如何获得授信？</span>',
                'a'=>'<span>你可以根据以下方式与我们联系：</span><br/><span>邮箱：info@myplas.com</span><br/><span>客服热线：400-6129-965</span><br/><span>前台热线：021-61070985</span>'
            ),
            array(
                'q'=>'<span>如何提升额度？</span>',
                'a'=>'<span>一，在我的塑料网上形成交易，并确保交易信用良好</span><br/><span>二，在塑料圈通讯录上多发布供求信息</span><br><span>为塑料圈通讯录引入新的塑料交易人员</span>'
            ),
        );
        $this->json_output (array(
            'err'  => 0,
            'c_name'=>$c_name,
            'data' => $arr,
        ));

    }

    /**
     * 企业额度信用页面接口
     * @api {post} /qapi_3/credit/creditLimitPage 企业额度信用页面接口
     * @apiVersion 3.2.0
     * @apiName  creditLimitPage
     * @apiGroup Credit
     * @apiUse UAHeader
     *
     * @apiSuccess {String}  msg   描述
     * @apiSuccess {String}  err   错误码
     * @apiSuccess {String}  data
     *
     * @apiSuccessExample {json} Success-Response:
     *      {
            "err": 0,
            "data": [
            {
            "q": "<span>什么是塑料配资？</span>",
            "a": "<span>塑料行情上涨，但企业流动资金受限，我的塑料网可为用户垫付资金，进行代理采购</span>"
            },
            {
            "q": "<span>费率是多少？</span>",
            "a": "<span>方案一</span><br><span>：1.手续费：(交易金额-保证金)×1%</span><br/><span>2.资金使用率：（交易金额-保证金）×0.033%/天数，手续费按笔计算，资金使用费按天计算。</span><br/><span>方案二：按天计算，每天收取塑料交易单价的千分之一，10元起算</span>"
            },
            {
            "q": "<span>如何申请？</span>",
            "a": "<span>你可以根据以下方式与我们联系：</span><br/><span>邮箱：info@myplas.com</span><br/><span>客服热线：400-6129-965</span><br/><span>前台热线：021-61070985</span>"
            }
            ]
            }
     *
     *
     */
    public function creditLimitPage()
    {
        $arr = array(
            array(
                'q'=>'<span>什么是塑料配资？</span>',
                'a'=>'<span>塑料行情上涨，但企业流动资金受限，我的塑料网可为用户垫付资金，进行代理采购</span>'
            ),
            array(
                'q'=>'<span>费率是多少？</span>',
                'a'=>'<span>方案一</span><br><span>：1.手续费：(交易金额-保证金)×1%</span><br/><span>2.资金使用率：（交易金额-保证金）×0.033%/天数，手续费按笔计算，资金使用费按天计算。</span><br/><span>方案二：按天计算，每天收取塑料交易单价的千分之一，10元起算</span>'
            ),
            array(
                'q'=>'<span>如何申请？</span>',
                'a'=>'<span>你可以根据以下方式与我们联系：</span><br/><span>邮箱：info@myplas.com</span><br/><span>客服热线：400-6129-965</span><br/><span>前台热线：021-61070985</span>'
            )
        );
        $this->json_output (array(
            'err'  => 0,
            'data' => $arr,
        ));

    }

    /**
     * 获取企查查的接口
     * @api {post} /qapi_3/credit/getQiChaCha 获取企查查的接口
     * @apiVersion 3.2.0
     * @apiName  getQiChaCha
     * @apiGroup Credit
     * @apiUse UAHeader
     *
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
        $user_id = $this->checkAccount ();
        if ($user_id&&$_POST['name']) {

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
     * 获取授信额度
     * @api {post} /qapi_3/credit/creditCertificate 获取授信额度
     * @apiVersion 3.2.0
     * @apiName  creditCertificate
     * @apiGroup Credit
     * @apiUse UAHeader
     *
     * @apiParam   {String} fname  公司名称
     * @apiParam   {Number} link_id 自己的USER_ID
     * @apiParam   {Number} page   页码
     * @apiParam   {Number} type   1 精确  2 模糊
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