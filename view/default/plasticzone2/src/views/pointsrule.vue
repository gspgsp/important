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
	<img width="100%" src="http://statics.myplas.com/myapp/img/rule1.jpg" />
	<img width="100%" src="http://statics.myplas.com/myapp/img/rule2.jpg" />
</div>
<div class="rule" style=" padding-bottom: 50px;">
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
<div class="rechargeFooter">
	<router-link :to="{name:'recharge'}">去充值</router-link>
</div>
</div>
</template>
<script>
module.exports = {
	data: function() {
		return {

		}
	},
	beforeRouteEnter: function(to, from, next) {
		next(function(vm) {

		});
		document.title = "积分规则";
	},
	beforeRouteLeave: function(to, from, next) {
		next(function() {

		});
		document.title = "塑料圈通讯录";
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