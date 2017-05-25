<?php
/**
 * 项目应用程序
*/

//后台启动session
function startAdminSession(){
	if(C('SESSION_ADMIN')){
		if(require_file(CORE_PATH.'class/session.class.php')){
			$class='session'.ucwords(C('SESSION_TYPE'));
			$GLOBALS['CORE_SESS']=new $class;
			define('SESS_ID', $GLOBALS['CORE_SESS']->getSid());
		}
	}
}
?>
