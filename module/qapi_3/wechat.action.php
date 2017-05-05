<?php
/**
 * Created by PhpStorm.
 * User: sick
 * Date: 17-5-5
 * Time: 下午4:08
 */
class wechatAction extends baseAction
{
    public function getSignPackage ()
    {
        $jsapiTicket = $this->getJsApiTicket ();

        // 注意 URL 一定要动态获取，不能 hardcode.
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url      = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $timestamp = time ();
        $nonceStr  = $this->createNonceStr ();

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

        $signature = sha1 ($string);

        $signPackage = array(
            "appId"     => $this->AppID,
            "nonceStr"  => $nonceStr,
            "timestamp" => $timestamp,
            "url"       => $url,
            "signature" => $signature,
            "rawString" => $string,
        );

        return $signPackage;
    }


    //获取票据
    protected function get_jsapi_ticket ()
    {
        $_key   = 'weixin_jsapi_ticket';
        $cache  = cache::startMemcache ();
        $ticket = $cache->get ($_key);
        if (empty($ticket)) {
            $access_token = $this->wx_get_token ();
            $url          = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token={$access_token}";
            $result       = json_decode ($this->http ($url), true);
            if (isset($result['ticket'])) {
                $ticket = $result['ticket'];
                $cache->set ($_key, $ticket, 7000);

                return $ticket;
            } else {
                return false;
            }
        } else {
            return $ticket;
        }
    }

    /**
     *分享
     */
    //获取token
    protected function wx_get_token ()
    {
        $tokenFile = "access_token.txt";//缓存文件名
        $data      = json_decode (file_get_contents ($tokenFile), true);
        if ($data['expire_time'] < CORE_TIME) {
            $url          = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->AppID}&secret={$this->AppSecret}";
            $res          = json_decode ($this->http ($url), true);
            $access_token = $res['access_token'];
            if ($access_token) {
                $data['expire_time']  = CORE_TIME + 7000;
                $data['access_token'] = $access_token;
                $fp                   = fopen ($tokenFile, "w");
                fwrite ($fp, json_encode ($data));
                fclose ($fp);
            }

            return $access_token;
        } else {
            return $data['access_token'];
        }
    }


    //获取票据
    protected function getJsApiTicket ()
    {
        $_key   = 'weixin_jsapi_ticket';
        $cache  = cache::startMemcache ();
        $ticket = $cache->get ($_key);
        if (empty($ticket)) {
            $access_token = $this->wx_get_token ();
            $url          = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token={$access_token}";
            $result       = json_decode ($this->http ($url), true);
            if (isset($result['ticket'])) {
                $ticket = $result['ticket'];
                $cache->set ($_key, $ticket, 7000);

                return $ticket;
            } else {
                return false;
            }
        } else {
            return $ticket;
        }
    }

    //通过回调方法获取用户的code
    protected function get_authorize_url ($redirect_uri = '', $state = '')
    {
        $redirect_uri = urlencode ($redirect_uri);
        $url          = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->AppID}&redirect_uri={$redirect_uri}&response_type=code&scope=snsapi_base&state={$state}#wechat_redirect";
        echo "<script language='javascript' type='text/javascript'>";
        echo "window.location.href='$url'";
        echo "</script>";
    }


    //分享我的供给或其求购
    public function shareMyPur ()
    {
        $this->is_ajax = true;
        $user_id       = $this->checkAccount (0);
        $id            = sget ('id', 'i');
        if ($id < 1) {
            $this->_errCode (6);
        }
        $data = M ('qapp:plasticShare')->getMySharePur ($id);
        $info = M ("product:purchase")->getInfoById ($id);
        //p($info);exit;
        //获取我的塑料圈个人信息
        $headimgurl = '';
        $info       = M ('qapp:plasticPersonalInfo')->getMyPlastic ($info['user_id'], $headimgurl);
        if (empty($data)) {
            $this->json_output (array(
                'err' => 2,
                'msg' => '没有相关的数据',
            ));
        }
        $this->json_output (array(
            'err'  => 0,
            'data' => $data,
            'info' => $info,
        ));
    }

    //验证是否分享成功日志
    public function saveShareLog ()
    {
        if ($_POST) {
            $share_id = sget ('id', 'i');
            $type     = sget ('type', 'i', 1);//分享类容类型  1采购 2报价 3 文章
            $user_id  = $this->checkAccount ();//分享人的id
            $share    = '';
            if (!M ('qapp:plasticShare')->saveShareLog ($share_id, $type, $user_id, $share)) {
                $this->json_output (array(
                    'err' => 6,
                    'msg' => '分享失败',
                ));
            }
            $this->json_output (array(
                'err' => 0,
                'msg' => '分享成功',
            ));
        }
        $this->_errCode (6);
    }

}