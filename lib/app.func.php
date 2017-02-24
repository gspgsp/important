<?php
/**
 * 项目应用程序
*/

//前台启动session
function startHomeSession(){
	if(require_file(CORE_PATH.'class/session.class.php')){
		$class='session'.ucwords(C('SESSION_TYPE'));
		$GLOBALS['CORE_SESS']=new $class;
		define('SESS_ID', $GLOBALS['CORE_SESS']->getSid());
	}
}

//获取用户当前uv
function getUV(){
	return cookie::get('_uv');		
}
//产生当前uv
function genUV(){
	return sprintf('%08x', crc32((!empty($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '').get_ip()));		
}

//设置用户访问来源
function setReferer($user_id=0){
	if(!isset($_SESSION['_sRef'])){		
		$referer=$_SERVER['HTTP_REFERER'];
		if(strstr($referer,'ssrong.com')){ //内网来的可以不计
			$referer='';
		}
		if(!empty($referer)){ //3个月
			cookie::set('_sRef',$referer,90*86400);	
		}
		$_SESSION['_sRef']=1;

		//用户邀请码
		$inviteCode = sget('i','s');
		if(!empty($inviteCode) && ROUTE_A!='receiveRecomment'){
			cookie::set('recommentData',serialize(array('userId'=>$inviteCode)),30*86400);
		}
	}
	
	//渠道来源跟踪
	//$_ref=sget('ref','s');
	$arr_platform=get_platform();
	$_ref=$arr_platform['channel_name'];
	if(!empty($_ref)){
		$chanel_id=M('system:chanel')->getChanelID($_ref);
		if($chanel_id>0){
			cookie::set('_chanel',$chanel_id,86400*30); //30天内有效
			$_SESSION['_chanel']=$chanel_id;
			M('public:mlog')->chanel($chanel_id,$user_id,$arr_platform['platform']); //统计用户访问
		}
// 		$href=str_replace(array('?ref='.$_ref,'&ref='.$_ref),'',__SELF__);
// 		header("HTTP/1.1 301 Moved Permanently"); 
// 		header("Location:".$href); exit;
	}
}

/**
 * 获取当前请求访问类型
 * @return json 
 */
function get_platform(){
    if (empty($_SERVER['HTTP_USER_AGENT'])){    //当浏览器没有发送访问者的信息的时候
        return array('channel_name'=>'unknow','platform'=>'unknow');
    }
    //判断塑料圈
    if  ((strpos($_SERVER['HTTP_REFERER'], 'plasticzone') !== false)||((strpos(get_url(), 'qapi1') !== false)||(strpos(get_url(), 'plasticzone') !== false))||(preg_match('/(plastic)/i', strtolower($_SERVER['HTTP_USER_AGENT'])))) {
        //判断微信
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
            return array('channel_name'=>'plastic','platform'=>'weixin');
        }
        //判断ios
        if(preg_match('/(iphone|ipad|ipod)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
            return array('channel_name'=>'plastic','platform'=>'ios');
        }
        //判断android
        if(preg_match('/(android)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
            return array('channel_name'=>'plastic','platform'=>'android');
        }
        //判断其他则为pc端口
        return array('channel_name'=>'plastic','platform'=>'pc');
    }
    //判断微信
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
        return array('channel_name'=>'weixin','platform'=>'weixin');
    }
    //判断wap
    if(preg_match('/(wap)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
        return array('channel_name'=>'wap','platform'=>'wap');
    }
    //判断ios
    if(preg_match('/(iphone|ipad|ipod)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
        return array('channel_name'=>'app','platform'=>'ios');
    }
    //判断android
    if(preg_match('/(android)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
        return array('channel_name'=>'app','platform'=>'android');
    }
    //如果是pc web就得返回浏览器类型
    if (M('public:common')->is_mobile_request() == false) {
        return array('channel_name'=>'web','platform'=>'pc');
    }
}

/**
 * 获取浏览器版本
 * @return string
 */
 function getBroswer()
{
    $sys = $_SERVER['HTTP_USER_AGENT'];  //获取用户代理字符串
    if (stripos($sys, "Firefox/") > 0) {
        preg_match("/Firefox\/([^;)]+)+/i", $sys, $b);
        $exp[0] = "Firefox";
        $exp[1] = $b[1];  //获取火狐浏览器的版本号
    } elseif (stripos($sys, "Maxthon") > 0) {
        preg_match("/Maxthon\/([\d\.]+)/", $sys, $aoyou);
        $exp[0] = "傲游";
        $exp[1] = $aoyou[1];
    } elseif (stripos($sys, "MSIE") > 0) {
        preg_match("/MSIE\s+([^;)]+)+/i", $sys, $ie);
        $exp[0] = "IE";
        $exp[1] = $ie[1];  //获取IE的版本号
    } elseif (stripos($sys, "OPR") > 0) {
        preg_match("/OPR\/([\d\.]+)/", $sys, $opera);
        $exp[0] = "Opera";
        $exp[1] = $opera[1];
    } elseif (stripos($sys, "Edge") > 0) {
        //win10 Edge浏览器 添加了chrome内核标记 在判断Chrome之前匹配
        preg_match("/Edge\/([\d\.]+)/", $sys, $Edge);
        $exp[0] = "Edge";
        $exp[1] = $Edge[1];
    } elseif (stripos($sys, "Chrome") > 0) {
        preg_match("/Chrome\/([\d\.]+)/", $sys, $google);
        $exp[0] = "Chrome";
        $exp[1] = $google[1];  //获取google chrome的版本号
    } elseif (stripos($sys, 'rv:') > 0 && stripos($sys, 'Gecko') > 0) {
        preg_match("/rv:([\d\.]+)/", $sys, $IE);
        $exp[0] = "IE";
        $exp[1] = $IE[1];
    } elseif (stripos($sys, 'Safari') > 0) {
        preg_match("/safari\/([^\s]+)/i", $sys, $safari);
        $exp[0] = "Safari";
        $exp[1] = $safari[1];
    } else {
        $exp[0] = "未知浏览器";
        $exp[1] = "";
    }
    return $exp[0] . '(' . $exp[1] . ')';
}

/**
 * 验证qqhao
 * @return bool
 */
function is_qq($str) {
	$pattern = "/^[1-9]\d{4,11}$/";//5-12位
	return preg_match($pattern,$str);
}


//获取用户注册来源
function getReferer(){
	//ref,chanel_id
	$ref=cookie::get('_sRef');
	if(isset($_SESSION['_chanel'])){
		$chanel_id=$_SESSION['_chanel'];
	}else{
		$chanel_id=cookie::get('_chanel');
	}
	return array('ref'=>$ref,'chanel_id'=>(int)$chanel_id);		
}

//格式化金额
function format_money($amount,$show_symbol=FALSE){
	return ($show_symbol?'￥':'').number_format((float)$amount,2);
}

//IOS解密处理
function desDecrypt_IOS($data){
	return rtrim(desDecrypt(bin2hex(base64_decode($data))),'');
}

function witchType($id=1){
	$product_type=L('product_type');
	return $product_type[$id];
}

function setOption($type='',$id=1){
	$data=L($type);
	return $data[$id];
}
?>