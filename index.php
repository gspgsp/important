<?php
//定义项目路径
define('APP_NAME', 'online/branches/www');
define('ROOT_PATH', dirname(__FILE__).'/');
define('APP_LIB', ROOT_PATH.'../common/'); #项目共用库
require(APP_LIB . 'conf/constants.php');

//入口文件
require(ROOT_PATH."../../../frame/load.php");
load::run();
?>
