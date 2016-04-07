<?php
/**
 * 项目应用配置文件
*/
return array(
	//'配置项'=>'配置值'
	'db_default' => array (
		'db_separate'	=> 0,
		'master_num'  => 1,
		'database' => 'suliao',
		'host' => '139.196.205.19',
		'user' => 'test',
		'password' => 'test',
		'table_pre' => 'p2p_',
		'charset' => 'utf8',
		'type' => 'mysql',
		'debug' => true,
		'pconnect' => 0,
	),
	//数据库设置
	'DB_FIELD_CACHE'   => false,        // 启用字段缓存
    	//SESSION设置
	'SESSION_MEMCACHE' => '127.0.0.1:11211',
	'cache_memcache' => array (
		'host' => '127.0.0.1:11211',
		'pconnect' => false,
		'prefix' => 'p2p_',
		'expire' => 3600, //数据缓存有效期 3600 秒
	),

);