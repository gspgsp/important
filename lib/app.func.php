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
	$_ref=sget('ref','s');
	if(!empty($_ref)){
		$chanel_id=M('system:chanel')->getChanelID($_ref);
		if($chanel_id>0){
			cookie::set('_chanel',$chanel_id,86400*30); //30天内有效
			$_SESSION['_chanel']=$chanel_id;
			M('public:mlog')->chanel($chanel_id,$user_id); //统计用户访问
		}
		$href=str_replace(array('?ref='.$_ref,'&ref='.$_ref),'',__SELF__);
		header("HTTP/1.1 301 Moved Permanently"); 
		header("Location:".$href); exit;
	}
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