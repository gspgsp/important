<?php
/**
 * 站内信-短信模板
*/
$sys = M('system:setting')->getsetting();
return array(
	//站内信模板
	'msg_template'=>array(
		//注册成功
		'register'=>"您已注册成功，欢迎来到我的塑料网！",
		//有报价信息
//		'offers'=>"您的%s：%s（牌号：%s，价格：%s）被 %s 用户采购，<a href='/user/myoffers/msgToList?id=%s&type=%s'>查看详情</a>",
		'offers'=>"您的%s：%s（牌号：%s，价格：%s）被 %s 用户采购,<a href='/user/%s/lists'>查看详情<a/>",
		//联营订单
//		'union_order'=>"您的供货：%s，%s 已经被选中并生成联营订单，交易员审核后，将会生成正式订单，<a href='/user/unionorder/detail/id/%s'>查看详情</a>",
		'union_order'=>"您的供货：%s，%s 已经被选中生成联营订单，将会生成正式订单",
		//下自营订单
		'order'=>"您已选购：牌号：%s 价格：%s ，生成自营订单，交易员审核后，将会生成正式订单，<a href='/user/selforder/detail/id/%s'>查看详情</a>"
	),

	//短信模板
	'sms_template'=>array(
		'dynamic_code'=>'验证码：%s，切勿泄漏给他人，有效期30分钟，如非本人操作，请忽略本短信。',
		'passwd_edit_success'=>'尊敬的用户，您的新登录密码为%s，请及时登录系统并重新修改,感谢您对我的塑料网的支持。',
	),

	//推送Android 下载APP短信模板
	'sms_push'=>array(
		'push_code'=>' 感谢您下载我的塑料网手机客户端，请点击一下链接进行下载:%s',
	),
	//报价推送短信模板
	'offers_sms'=>array(
		'offers'=>'每日报价，您需要的牌号“%s”有优惠货源，自提报价为“%s”，价格有效期为%s。请与交易员%s（%s）实盘谈。',
	),
	//新增客户短信提醒
	'customer_add_msg'=>array(
		'tips'=>'感谢您成为中晨电商（股票代码：837297）旗下“我的塑料网”的优质会员，作为华东最大的塑料原料电商交易平台，我们将为您提供安全便捷的交易服务。您的专属客服：%s（%s）',
	),
);