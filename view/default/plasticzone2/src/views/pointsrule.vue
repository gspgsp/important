<template>
<div>
<header id="bigCustomerHeader">
	<a class="back" href="javascript:window.history.back();"></a>
	积分规则
</header>
<div class="shopBanner">
	<img width="100%" src="http://statics.myplas.com/myapp/img/shopBanner2.jpg">
</div>
<div class="rule" style="padding: 0 20px 10px 20px;">
	<div class="ruleTitle2">塑豆规则</div>
	<table class="ruleTb" cellpadding="0" cellspacing="0">
		<div class="ruleBanner">赚塑豆</div>
		<tr>
			<td colspan="3"></td>
		</tr>
		<tr>
			<td style="padding-top: 10px;" width="33.3333%" rowspan="2">1、注册类</td>
			<td style="padding-top: 10px;" width="33.3333%">注册成功</td>
			<td style="padding-top: 10px;" width="33.3333%">100塑豆</td>
		</tr>
		<tr>
			<td>完善信息</td>
			<td>100塑豆</td>
		</tr>
		<tr>
			<td rowspan="7">2、分享推广类</td>
			<td style="background: #ffdecc; color: #ff0000;">分享供求信息至微信朋友圈</td>
			<td style="background: #ffdecc; color: #ff0000;">30塑豆</td>
		</tr>
		<tr>
			<td style="background: #ffdecc; color: #ff0000;">分享塑料头条至微信朋友圈</td>
			<td style="background: #ffdecc; color: #ff0000;">10塑豆</td>
		</tr>
		<tr>
			<td>发布求购</td>
			<td>10塑豆</td>
		</tr>
		<tr>
			<td>发布标准求购</td>
			<td>20塑豆</td>
		</tr>
		<tr>
			<td>发布供给</td>
			<td>5塑豆</td>
		</tr>
		<tr>
			<td>发布标准供给</td>
			<td>10塑豆</td>
		</tr>
		<tr>
			<td>每日登录</td>
			<td>10塑豆</td>
		</tr>
		<tr>
			<td>3、拉新类</td>
			<td>拉新1个</td>
			<td>50塑豆</td>
		</tr>
		<tr>
			<td>4、充值类</td>
			<td>10元</td>
			<td>100塑豆</td>
		</tr>
		<tr>
			<td colspan="3">分享推广、拉新，5个封顶，每日登陆也5天封顶（若终端，从10开始记，50封顶。）</td>
		</tr>
	</table>
	<table class="ruleTb" style="margin: 10px 0 0 0;" cellpadding="0" cellspacing="0">
		<div class="ruleBanner2">用塑豆</div>
		<tr>
			<td colspan="3"></td>
		</tr>
		<tr>
			<td style="padding-top: 10px;" width="33.3333%">1、查看通讯录</td>
			<td style="padding-top: 10px;" width="33.3333%">每条通讯录</td>
			<td style="padding-top: 10px;" width="33.3333%">20塑豆</td>
		</tr>
		<tr>
			<td>2、信息置顶</td>
			<td>每条信息</td>
			<td>100塑豆/24小时</td>
		</tr>
		<tr>
			<td>3、通讯录置顶</td>
			<td>每条通讯录</td>
			<td>100塑豆/24小时</td>
		</tr>
		<tr>
			<td colspan="3">限制每用户每天查看200条通讯录</td>
		</tr>
	</table>
</div>
<div class="rule">
	<div class="ruleTitle">充值规则说明</div>
	<p>充值平台，即可有机会每天得500元塑豆</p>
	<p>参与条件：塑料圈注册用户，且信息完整，通过平台审核的用户。塑豆：塑料圈专用，可用于充值平台内活动（供求匹配、信息置顶、客户详情查看、塑料头条阅读等），具体兑换比例如下：1元人民币=10塑豆</p>
	<p>活动内容：</p>
	<p>1、充值每天最高奖励500塑豆</p>
	<p>2、平台不定时举办各种促销优惠活动，用户可根据实际自身情况进行相应充值，换取相应的塑豆和由塑豆兑换的礼品。 充值方式： </p>
	<p>充值方式：</p>
	<p>1、支付宝/微信，支付完成后请联系我的塑料网客服（客服热线：400-6129-965），完成塑豆发放确认。</p>
	<p>2、充值金额人民币10元起，以10的倍数如20、30、40……..</p>
	<p>3、所有塑豆均不能兑换现金，不能退换，只能用于塑料圈或者我的塑料网消费。</p>
	<p style="color: #ff5000;">4、微信浏览器内不支持支付宝转账。</p>
</div>
</div>
</template>
<script>
module.exports = {
	data: function() {
		return {

		}
	},
	activated: function() {
		try {
		    var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
		    piwikTracker.trackPageView();
		} catch( err ) {
			
		}	
	}
}
</script>