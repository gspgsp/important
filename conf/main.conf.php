<?php
/**
 * 项目应用配置文件
*/
return array(
	//Cookie设置
	'COOKIE_DOMAIN'    => '',      //Cookie有效域名
	'COOKIE_PATH'      => '/',     //Cookie路径
	'COOKIE_PRE'       => 'cSsR_',  //Cookie前缀 避免冲突
	'COOKIE_TTL'       => 3600,    //Coodie有效期
	'COOKIE_SECURE'    => false,    //ssl
	//======upload=======
	'upload_type'  => 'local', //local或remote
	'upload_local' => array(
	//本地(不跨服务器)上传配置
	'path' => ROOT_PATH.'../static/upload/',	 //本地存储路径	 
	),
	'upload_remote' => array(
	//远程上传配置
	'path' => CACHE_PATH.'tmp/',	 //远程上传时本地零时路径		 
	'url' => '', //远程
	),
	//模板替换内容（排除__M__，__C__，__A__，__U__）
	'TEMP_REPLACE'	   => array(
		'__URL__' => APP_URL,
		'__JS__' => FILE_URL.'/js',
		'__MYAPP__' => FILE_URL.'/myapp',
		'__IMG__' => FILE_URL.'/images',
		'__CSS__' => FILE_URL.'/css',
		'__UPLOAD__' => FILE_URL.'/upload',
	),
	'SSRONG'	=> array(
		'api_url' => '',
		'code' => '',
		'secret' => '',
	),
    //东方付通相关配置
    'DFFTPAYMENT_CONFIG'	   => array(
        'mallID' => '000106',
        'certificatepassword' => '999999',
        'certificatepath' => APP_URL.'/Javatest/Java/hbtest2.pfx',
        'callbackpay' => APP_URL.'/',
    ),
	
);
?>
