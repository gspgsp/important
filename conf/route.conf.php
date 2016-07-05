<?php
//路由配置文件
return array(
	//路由别名
	'ROUTE'=>array(
		//别名=>模块/控制器/操作
	),
	
	//正则路由:是否开启
	'ROUTE_REGX'=>true,
	//正则路由:规则
	'ROUTE_RULE'=>array(
		//正则表达式=> [模块/控制器/操作?]参数1=值1&参数2=值2...
		// '/^item\/([0-9]+)$/' => 'list/item/detail?id=$1',
		// '/^info\/([0-9]+)$/' => 'list/info/show?id=$1',
		// '/^(about|contact|mobile|safe|agreement|help)\/*(\w*)$/' => 'home/page/$1?id=$2',
		// '/^(index|ajax)(\/\w+)$/' => 'home/$1$2',
		// '/^(android|ios|wp|weixin)\/(\w+)\/(\w+)$/' => 'app/$2/$3?chanel=$1',
		// '/^(\w+\/\w+\/)(\w+)\-(\w+)/' => '$1$2$3',
		'/^mycompany\/([0-9]+)$/'=>'mycompany/index/init?id=$1',//我的网站子网站路由
		'/^article\/show\/([0-9]+)$/'=>'article/index/info?id=$1',
	),

	//子(泛)域名路由规则
	'SUB_DOMAIN'	   => true,
	'SUB_DOMAIN_NAME'  => '.wdsln.com', //默认域名后缀
	//子域名部署规则
	'SUB_DOMAIN_RULES' => array(
		//'m'=>array('touch'),
		//'*'=>array('member/user','name=*'),
	), 
	'SUB_DOMAIN_ROUTE' => array(
		'm'=>'touch/index/init',
	), 
);
?>
