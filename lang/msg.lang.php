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
		'offers'=>"您的%s：%s（牌号：%s，价格：%s）被 %s 用户采购，<a href='/user/myoffers/msgToList?id=%s&type=%s'>查看详情</a>",
		//下自营订单
		'order'=>"您已选购：牌号：%s 价格：%s ，生成自营订单，交易员审核后，将会生成正式订单，<a href='/user/selforder/detail/id/%s'>查看详情</a>",
		//联营订单
		'union_order'=>"您的供货：%s，%s 已经被选中并生成联营订单，交易员审核后，将会生成正式订单，<a href='/user/unionorder/detail/id/%s'>查看详情</a>",
	),

	//短信模板
	'sms_template'=>array(
		'dynamic_code'=>'验证码：%s，切勿泄漏给他人，有效期5分钟，如非本人操作，请忽略本短信。',
	),

);
?>
