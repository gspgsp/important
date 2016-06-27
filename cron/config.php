<?php
set_time_limit(0);
//定义项目路径
define('APP_NAME', 'wdsln.com/branches/www');
define('ROOT_PATH', dirname(__FILE__).'/');
define('APP_LIB', ROOT_PATH.'../'); 
require(APP_LIB . 'conf/constants.php');

//入口文件
require(ROOT_PATH."../../../../frame/simple.php");
?>
