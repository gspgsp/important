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
     * @apiSuccess {json}    data   数据
     *
     * @apiSuccessExample {json} Success-Response:
     *      {
        "err": 0,
        "data": {
        "id": "3",
        "name": "上海中晨电子商务股份有限公司",
        "register_no": "",
        "belong_org": "上海市工商局",
        "oper_name": "李铁道",
        "start_date": "2007-04-11",
        "end_date": "0",
        "status": "存续（在营、开业、在册）",
        "province": "SH",
        "update_date": "2017-06-08",
        "credit_code": "91310000660746422P",
        "register_capi": "3456.620900",
        "econkind": "股份有限公司（非上市、自然人投资或控股）",
        "industry": "批发和零售业",
        "sub_industry": "批发业",
        "address": "上海市广灵二路122号415室",
        "scope": "以电子商务方式从事塑料材料、金属材料及制品、化工原料（除危险品）、建筑装潢材料及制品、橡胶制品、木材、五金交电的销售，计算机软件、网络技术的开发，网络系统集成，广告的设计，利用自有媒体发布广告，会展服务，市场信息咨询与调查（不得从事是社会调查、社会调研、民意调查、民意测验），从事货物及技术的进出口业务，企业管理咨询，投资咨询，企业形象策划，市场营销策划，电信业务（第二类增值电信业务中的信息服务业务（仅限互联网信息服务））。\r\n【依法须经批准的项目，经相关部门批准后方可开展经营活动】",
        "term_start": "2007-04-11",
        "term_end": "0",
        "check_date": "2007-04-11",
        "phone_number": "021-60295176",
        "email": "83080667@qq.com",
        "website_name": "",
        "website_url": "www.myplas.com"
        }
        }
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
     * @apiSuccessExample {json} Success-Response:
             * {
            "err": 0,
            "data": {
            "user_id": "40418",
            "c_name": "上海中晨电子商务股份有限公司",
            "credit_level": "AAAAA",
            "credit_limit": 6000000,
            "is_credit": "1",
            "pre_credit_limit": 6000000,
            "credit_time": "1488357059"
            }
            }
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