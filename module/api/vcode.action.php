<?php

/*
 * 前台验证码
*/

class vcodeAction extends homeBaseAction
{
    public function __init ()
    {
    }
    /**
     * 获取验证
     * @api {get} /api/vcode 获取验证 供我的塑料网前端与塑料圈H5 PC端使用
     * @apiVersion 3.1.0
     * @apiName  vcode
     * @apiGroup api
     *
     * @apiSampleRequest http://test.myplas.com/api/vcode
     */
    public function init ()
    {
        $vcode            = new vcode();
        $vcode->code_len  = 4;
        $vcode->font_size = 14;
        $vcode->width     = 80;
        $vcode->height    = 36;
        $vcode->seedtype  = 2;
        #$vcode->background = "#cccccc";
        ini_set ('display_errors', 'On');
        $vcode->doimage ();
        $name            = 'vc_'.sget ('name', 's', 'vcode');
        $_SESSION[$name] = $vcode->get_code ();
        p ($vcode->get_code ());
    }

    /**
     * 验证验证码
     * @api {get} api/vcode/chkVcode 验证验证码 供我的塑料网前端与塑料圈H5 PC端使用
     * @apiVersion 3.1.0
     * @apiName  chkVcode
     * @apiGroup api
     *
     * @apiSampleRequest http://test.myplas.com/api/vcode/chkVcode
     * @apiParam   {String} name  值regcode
     * @apiParam   {String} value  4322
     *
     * @apiSuccess {int}  err   错误码
     * @apiSuccess {String}   msg   描述
     *
     * @apiSuccessExample {json} Success-Response:
     *      {
            "err": 0,
            "msg": "验证成功"
            }
     * @apiErrorExample {json} Error-Response:
     *     {
     *       "err": 1,
     *       "msg": "验证码输入不正确"
     *      }
     */
    public function chkVcode ()
    {
        $name  = sget ('name', 's');
        $value = sget ('value', 's');
        $value = strtolower ($value);

        if (!chkVcode ($name, $value)) {
            $this->error ('验证码输入不正确');
        } else {
            $this->success ('验证成功');
        }
    }
}

?>