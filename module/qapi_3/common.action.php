<?php

/**
 * Created by PhpStorm.
 * User: sick
 * Date: 17-5-5
 * Time: 上午11:49
 */
/**
 * @apiDefine UAHeader
 *
 * @apiHeader {String} x-ua 拼接顺序为 平台(ios/android/weixin/pc)|屏幕尺寸|user_id|token|uuid|包名|系统获取的操作系统名称|操作系统版本号|内核版本号|浏览器名称|浏览器版本号|设备厂商|设备名称信息
 *
 */
class commonAction extends baseAction
{
    /**
     * ua检查更新接口
     * @api {GET} /qapi_3/common/checkUA 检查ua接口
     * @apiVersion 3.2.0
     * @apiName  checkUA
     * @apiGroup Common
     * @apiUse UAHeader
     *
     * @apiSuccess {String}  err   错误码
     * @apiSuccess {String}  platform   android ios h5 pc
     * @apiSuccess {String}  user_id
     * @apiSuccess {String}  token
     * @apiSuccess {String}  uuid   设备唯一识别码
     * @apiSuccess {String}  app_version   app包版本 如 89
     * @apiSuccess {String}  os   操作系统名称  非内核
     * @apiSuccess {String}  os_version   操作系统内核
     * @apiSuccess {String}  core_version   操作系统内核版本
     * @apiSuccess {String}  navigator   浏览器名称
     * @apiSuccess {String}  navigator_version   浏览器版本
     * @apiSuccess {String}  manufacturer   设备制造商
     * @apiSuccess {String}  device_model   设备信息
     * @apiSuccess {String}  screen   屏幕尺寸 如 5.5 6.0
     *
     * @apiSuccessExample Success-Response:
     *      {
     *      "err":0
     *      "msg":"密码重置成功"
     *      }
     */
    public function checkUA ()
    {
        $arr = array(
            'err'          => 0,
            'platform'     => $this->platform,
            'screen'       => $this->screen,
            'user_id'      => $this->user_id,
            'token'        => $this->token,
            'uuid'         => $this->uuid,
            'app_version'  => $this->app_version,
            'os'           => $this->os,
            'os_version'   => $this->os_version,
            'core_version' => $this->core_version,
            'navigator'    => $this->navigator,
            'navigator_version'    => $this->navigator_version,
            'manufacturer' => $this->manufacturer,
            'device_model'  => $this->device_model,
        );
        $this->json_output ($arr);
    }

    /**
     * APP检查更新接口
     * @api {post} /qapi_3/common/checkVersion APP检查更新接口
     * @apiVersion 3.2.0
     * @apiName  checkVersion
     * @apiGroup Common
     * @apiUse UAHeader
     *
     * @apiParam   {String} version   选填 包版本 83
     * @apiParam   {String} platform  ios andriod h5 pc
     *
     * @apiSuccess {String}  msg   描述
     * @apiSuccess {String}  err   错误码
     * @apiSuccess {String}  url   apk下载地址
     *
     * @apiSuccessExample Success-Response:
     *      {
     *      "err":0
     *      "msg":"密码重置成功"
     *      }
     */
    public function checkVersion ()
    {
        $platform = $this->platform;
        $version  = $this->app_version;
        if(empty($platform))
        {
            $platform= sget ('platform', 's');
        }
        if(empty($version))
        {
            $version = sget ('version', 's');
        }

        if($version == '3.1.0')
        {
            $version = 20;
        }

        if (!in_array ($platform, array(
                'ios',
                'android',
                'weixin',
                'pc'
            )) || empty($version)
        ) {
            $this->json_output (array(
                'err' => 4,
                'msg' => '参数错误',
            ));
        }

        $settings        = M ('system:globalSetting')->getSetting ();

        if ($version < $settings['qapp_newest_package'][$platform.'_package']) {
            $this->json_output (array(
                'err'         => 1,
                'msg'         => '当前版本已经停止支持，请迅速更新',
                'new_version' => $settings['qapp_newest_package'][$platform.'_version'],
                'url'         => FILE_URL.$settings['qapp_newest_url'][$platform],
            ));
        }  else {
            $this->json_output (array(
                'err' => 0,
                'msg' => '当前版本是最新版本，棒棒哒',
            ));
        }

    }


    public function getModel ()
    {
        $keywords = sget ('keywords', 's');
        $keywords = strtoupper ($keywords);
        if (empty($keywords)) {
            $this->_errCode (6);
        }
        $_tmpModel = M ('qapp:product')->select ('model')->where ('status=1 and model like "%'.$keywords.'%"')
                                       ->limit (20)->getAll ();
        if (empty($_tmpModel)) {
            $this->_errCode (2);
        }
        $this->json_output (array(
            'err'  => 0,
            'data' => $_tmpModel,
        ));
    }

    /**
     * 获取地区
     * @access public
     * @return json对象
     */
    public function regionInit ()
    {
        $region_id = sget ('region_id', 'i');
        $data      = array();
        if ($region_id > 0) {
            $data = M ('system:region')->get_regions ($region_id);
        }
        $this->json_output ($data);
    }

    public function getRegion ()
    {
        $pid  = sget ('id', 'i', 1);
        $_tmp = unserialize ($this->cache->get ('getqappRegion'.$pid));
        if (!empty($_tmp)) {
            $this->json_output (array(
                'err'  => 0,
                'data' => $_tmp,
            ));
        }
        $_tmp    = M ('system:region')->select ('id,pid,name')->where ('pid='.$pid)->getAll ();
        $_tmpRow = '';
        foreach ($_tmp as $key => &$row) {
            if ($row['name'] == '上海' && $row['pid'] == 1) {
                unset($row['pid']);
                $_tmpRow = $row;
                unset($_tmp[$key]);
            }
            unset($row['pid']);
        }
        if (!empty($_tmpRow)) {
            array_unshift ($_tmp, $_tmpRow);
        }
        $this->cache->set ('getqappRegion'.$pid, serialize ($_tmp), $this->randomMdTime);
        $this->json_output (array(
            'err'  => 0,
            'data' => $_tmp,
        ));
    }

}