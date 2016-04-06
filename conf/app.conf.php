<?php
/**
 * 项目应用配置文件
*/
return array(
	//默认设定
	'DEFAULT_M'        => 'home',  //默认模块名称
	'DEFAULT_C'        => 'index',  //默认控制器
	'DEFAULT_A'        => 'init',   //默认操作	
	'HTTPS_ON'        => false,   //https是否开启	
	'HTTPS_MODULE'    => 'user,my,pay,list',   //需要开启https的模块
	'ERROR_PAGE'    => '/',   //404错误页面
    	//SESSION设置
    	'SESSION_START'    => true,    //是否自动开启Session
   	'SESSION_TTL'      => 480,  //session有效期
    	'SESSION_TYPE'     => 'memcache', //mysql或memcache
    	'SESSION_NAME'     => C('COOKIE_PRE').'SSID', //名称
    	'SESSION_TOKEN'    => C('COOKIE_PRE').'token', //名称
	'DES_PASSCODE'	=> '89759958',
	'TMPL_ERROR'     => 'jump.html', //默认错误跳转模板
    	'TMPL_SUCCESS'   => 'jump.html', //默认成功跳转模板	
);
?>
