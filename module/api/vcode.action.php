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
     * 获取验证码
     * @api {get} /api/vcode 获取验证码 供我的塑料网前端与塑料圈H5 PC端使用
     * @apiVersion 3.1.0
     * @apiName  vcode
     * @apiGroup api
     *
     * @apiParam   {int} phonenum  手机号
     * @apiSampleRequest http://test.myplas.com/api/vcode
     */
    public function init ()
    {
        $phonenum= sget('phonenum','i',0);
        $vcode            = new vcode();
        $vcode->code_len  = 4;
        $vcode->font_size = 14;
        $vcode->width     = 80;
        $vcode->height    = 36;
        $vcode->seedtype  = 2;
        //$vcode->background = "#cccccc";
        ini_set ('display_errors', 'On');
        $vcode->doimage ();
        $name            = 'vc_'.sget ('name', 's', 'vcode');
        if(empty($phonenum)) {
            $_SESSION[$name] = $vcode->get_code ();
        }else{
            $cache= E('RedisCluster',APP_LIB.'class');
            $cache->set($phonenum.'_'.$name,$vcode->get_code(),300);
        }
        //p ($vcode->get_code ());
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
     * @apiParam   {int} phonenum  手机号
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
        $phonenum= sget('phonenum','i',0);
        $value = strtolower ($value);
        if(empty($phonenum)) {
            if (!chkVcode ($name, $value)) {
                $this->error ('验证码输入不正确');
            } else {
                $this->success ('验证成功');
            }
        }else{
            $cache= E('RedisCluster',APP_LIB.'class');
            $code = $cache->get($phonenum.'_'.$name);
            if(empty($code)||$code!=$value)
            {
                $this->error ('验证码输入不正确');
            }else {
                $this->success ('验证成功');
            }
        }
    }
}

?>