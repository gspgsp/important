<?php
/**
 * 每分钟检查Cron进程
 */
#*/1 0 * * * /usr/bin/php
 
require_once 'config.php';

$file=array(
	'cron.sms.php',		
	'cron.minute.php',		
	'cron.event.php',		
);

//查看当前进程
$cmd = popen('ps -ef | grep "'.ROOT_PATH.'" | grep -v grep', 'r');
$line = fread($cmd, 512);
pclose($cmd);

$log=array();
$log[]=date("Y-m-d H:i:s").' Cron Shell ';
foreach($file as $f){
	if(!strstr($line,$f)){ //进程不存在，则启动进程
		$out = popen('/usr/local/php/bin/php '.ROOT_PATH.$f.' &', 'r');
		pclose($out);
		$log[]=$f.'(on)';
	}	
}
wlog(CACHE_PATH.'log/cron/shell.log',join('; ',$log)."\r\n");
?>