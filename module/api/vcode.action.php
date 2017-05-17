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
     * @apiParam   {int} phonenum  手机号 APP端传此参数
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
        //$vcode->background = "#cccccc";
        //ini_set ('display_errors', 'On');
        $vcode->doimage ();
        $name            = 'vc_'.sget ('name', 's', 'vcode');
        $vcode->doimage ();
        $_SESSION[$name] = $vcode->get_code ();

        //p ($vcode->get_code ());
    }

    /**
     * 获取验证码--仅供APP使用
     * @api {get} /api/vcode/app 获取验证码--仅供APP使用
     * @apiVersion 3.1.0
     * @apiName  app
     * @apiGroup api
     *
     * @apiParam   {int} phonenum  手机号 APP端传此参数
     * @apiParam   {int} code  手机号 APP端传此参数
     *
     * @apiSampleRequest http://test.myplas.com/api/vcode/app
     *
     * @apiSuccess {int}  err   错误码
     * @apiSuccess {String}   msg   描述
     * @apiSuccess {String}   img  验证码图片地址
     * @apiSuccess {String}   key 验证所需KEY
     *
     * @apiSuccessExample {json} Success-Response:
             *     {
            "err": 0,
            "img": "http://static.svnonline.com/myapp/vcode/db40e452cd843b8325830d9032e05a64.png",
            "key": "db40e452cd843b8325830d9032e05a64"
            }
     */
    public function app ()
    {
        $vcode            = new vcode();
        $vcode->code_len  = 4;
        $vcode->font_size = 14;
        $vcode->width     = 80;
        $vcode->height    = 36;
        $vcode->seedtype  = 2;
        //$vcode->background = "#cccccc";
        //ini_set ('display_errors', 'On');
        $key = md5(time().rand(0,100));
        $name = FILE_URL.'/myapp/vcode/'.$key.'.png';
        $path = '../static/myapp/vcode/'.$key.'.png';

        $vcode->do_file_image ($path);
        $code = $vcode->get_code();
        if(empty($code)){
                  $this->json_output(array(
                      'err'=>99,
                      'msg'=>'系统错误',
                  ));
        }

        $cache= E('RedisCluster',APP_LIB.'class');
        $cache->set($key,$code,300);

        $this->json_output(array(
            'err'=>0,
            'img'=>$name,
            'key'=>$key
        ));

    }

    /**
     * 验证验证码
     * @api {get} api/vcode/chkVcode 验证验证码
     * @apiVersion 3.1.0
     * @apiName  chkVcode
     * @apiGroup api
     *
     * @apiSampleRequest http://test.myplas.com/api/vcode/chkVcode
     * @apiParam   {String} name  值regcode
     * @apiParam   {String} value  4322
     * @apiParam   {String} key  APP端传此参数
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
        $key   = sget('key','i',0);
        $value = strtolower ($value);
        if(empty($key)) {
            if (!chkVcode ($name, $value)) {
                $this->error ('验证码输入不正确');
            } else {
                $this->success ('验证成功');
            }
        }else{
            $cache= E('RedisCluster',APP_LIB.'class');
            $code = $cache->get($key);
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