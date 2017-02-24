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
		'/^wrong$/'=>'page/mdownload/wrong',//404错误页
		'/^physical\/key\/([0-9A-Za-z-]+)$/'=>'physical/index/search?keyword=$1',//物性表搜索路由
		'/^physical\/([0-9]+)$/'=>'physical/index/content?id=$1',//物性表路由
		'/^company\/([0-9]+)\/([null]+)$/'=>'mycompany/index/init?id=$1&type=$2',//我的网站子网站路由
		'/^(pe|pp|pvc|vip)$/'=>'news/index/init?type=$1',	//分类首页
		'/^(public|pe|pp|pvc|vip)([A-Za-z]{2,7})$/'=>'news/index/lst?type=$1&cate=$2',
		'/^(public|pe|pp|pvc|vip)\/([A-Za-z]{2,7})\/([0-9]+)$/'=>'news/index/detail?type=$1&cate=$2&id=$3',
		'/^newsXML$/'=>'news/index/newsXML',
                        //	'/^article\/show\/id\/([0-9]+)$/'=>'article/index/info?id=$1',//死链接文章详情处理
		'/^home\/([0-9]+)$/'=>'home/index/agreement',//注册协议
	),

	//子(泛)域名路由规则
	'SUB_DOMAIN'	   => true,
	'SUB_DOMAIN_NAME'  => '.myplas.com', //默认域名后缀
	//子域名部署规则
	'SUB_DOMAIN_RULES' => array(
		//'m'=>array('touch'),
		//'*'=>array('member/user','name=*'),
	), 
	'SUB_DOMAIN_ROUTE' => array(
		'm'=>'mobi/mainPage/enMainPage',
		'56'=>'ship/index/init',
		'vip'=>'pointshop/index/init',
	), 
);
?>
