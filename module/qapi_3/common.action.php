<?php
/**
 * Created by PhpStorm.
 * User: sick
 * Date: 17-5-5
 * Time: 上午11:49
 */
class commonAction extends baseAction
{

    /**
     * APP检查更新接口
     * @api {get} /api/api1_2/checkVersion APP检查更新接口
     * @apiName  checkVersion
     * @apiGroup User
     *
     * @apiParam   {String} version  3.0.0
     * @apiParam   {String} platform  ios andriod h5
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
        $version  = sget ('version', 's');
        $platform = sget ('platform', 's');
        if (!in_array ($platform, array(
                'ios',
                'android',
                'h5',
            )) || empty($version)
        ) {
            $this->json_output (array(
                'err' => 4,
                'msg' => '参数错误',
            ));
        }
        $version = explode ('.', $version);
        $version = array_splice ($version, 0, 3);
        if (count ($version) != 3) {
            $this->json_output (array(
                'err' => 2,
                'msg' => '不规范的版本格式，不予支持',
            ));
        }
        $settings        = M ('system:setting')->getSetting ();
        $newest_version0 = $settings['qapp_newest_version'];
        $newest_qapp_url = $settings['qapp_newest_url'];

        if (empty($newest_version0) || empty($newest_qapp_url)) {
            $this->json_output (array(
                'err' => 3,
                'msg' => '系统错误',
            ));
        }
        $newest_version = explode ('.', $newest_version0);

        if ($version[0] < $newest_version[0]) {
            $this->json_output (array(
                'err'         => 1,
                'msg'         => '当前版本已经停止支持，请迅速更新',
                'new_version' => $newest_version0,
                'url'         => FILE_URL.$newest_qapp_url[$platform],
            ));
        } elseif ($version[1] < $newest_version[1]) {
            $this->json_output (array(
                'err'         => 1,
                'msg'         => '当前版本已经停止支持，请迅速更新',
                'new_version' => $newest_version0,
                'url'         => FILE_URL.$newest_qapp_url[$platform],
            ));
        } elseif ($version[2] < $newest_version[2]) {

            $this->json_output (array(
                'err'         => 1,
                'msg'         => '当前版本已经停止支持，请迅速更新',
                'new_version' => $newest_version0,
                'url'         => FILE_URL.$newest_qapp_url[$platform],
            ));
        } else {
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