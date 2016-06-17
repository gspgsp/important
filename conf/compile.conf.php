<?php
/**
 * 加入编译文件的配置文件
 * 文件路径为绝对路径
*/
return array(
	//类文件
	'class' => array(
		//CORE_PATH.'extend/demo.class.php',
	),

	//函数库文件
	'func' => array(
		APP_LIB.'func/lib.func.php',
	),

	//配置文件
	'conf' => array(
		APP_LIB.'conf/main.conf.php',
		APP_LIB.'conf/db.conf.php',
		APP_LIB.'conf/autoload.conf.php',
	),
	
	//语言文件
	'lang' => array(
		APP_LIB.'lang/status.lang.php',
		APP_LIB.'lang/msg.lang.php',
		APP_LIB.'lang/sys.lang.php',
		APP_LIB.'lang/node.lang.php',
	),
);
?>
