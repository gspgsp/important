<?php
/**
 * 站内信-短信模板
*/
$sys = M('system:setting')->getsetting();
return array(
	//站内信模板
	'msg_template'=>array(
		//项目申请：通过审核
		// 'item_apply_success'=>'尊敬的用户，您的融资申请已经通过审核，融资金额：%01.2f元，签约年化利率：%01.2f%%，借款期限：%s%s。',
		// 'verify_apply_failure'=>'您的%s认证已经被驳回，失败原因：%s。',
		'offers'=>"您的%s：%s（牌号：%s，价格：%s）被 %s 用户采购，<a href='/user/myoffers/lists?id=%s'>查看详情</a>"

	),

	//短信模板
	'sms_template'=>array(
		'dynamic_code'=>'验证码：%s，切勿泄漏给他人，有效期5分钟，如非本人操作，请忽略本短信。',
	),

);
?>
