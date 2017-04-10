<template>
<div>
<header id="bigCustomerHeader">
	<a class="back" href="javascript:window.history.back();"></a>
	积分规则
</header>
<div class="shopBanner">
	<img width="100%" src="http://statics.myplas.com/myapp/img/shopBanner.jpg">
</div>
<div class="rule">
	<div class="ruleTitle">塑豆规则说明</div>
	<p>如何赚塑豆？</p>
	<p>1、注册类</p>
	<p>（1）注册成功：100塑豆；</p>
	<p>（2）完善信息：100塑豆；</p>
	<p>2、分享推广类：</p>
	<p>（1）分享供求信息：30塑豆/每条，上限每日150塑豆</p>
	<p>（2）分享塑料头条：30塑豆/每条；</p>
	<p>（3）发布求购：20塑豆/每条；</p>
	<p>（4）发布供给：10塑豆/每条；</p>
	<p>（5）每日登录：10*N（N为连续登陆天数，中断重新开始），连续登陆五天以上，每日送50塑豆</p>
	<p>3、拉新类：</p>
	<p>拉新一个：50塑豆。</p>
	<p>4、充值：</p>
	<p>充值10元：100塑豆。（1元=10塑豆）。</p>
	<p>说明：分享推广，5个封顶，每日登陆也5天封顶（若终端，从10开始记，50封顶）。</p>
	<p>如何用塑豆？</p>
	<p>1、查看通讯录：</p>
	<p>每条通讯录：10塑豆；</p>
	<p>2、供求信息置顶：</p>
	<p>每条信息：100塑豆/24小时；</p>
	<p>3、通讯录置顶：</p>
	<p>每条通讯录：100塑豆/24小时。</p>
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
</div>
</div>
</template>
<script>
module.exports = {
	data: function() {
		return {

		}
	},
	mounted: function() {
		try {
		    var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
		    piwikTracker.trackPageView();
		} catch( err ) {
			
		}	
	}
}
</script>