<template>
<div>
<header id="bigCustomerHeader">
	<a class="back" href="javascript:window.history.back();"></a>
	积分规则
</header>
<div class="rule">
	<p>1．登录+10分（每日只增加1次）</p>
	<p>2．发布报价+10分（每日只增加1次）</p>
	<p>3．发布求购+20分（每日只增加1次）</p>
	<p>4．引荐新人注册+50分（无上限）</p>
	<p>5．分享供求至朋友圈+30分（每日只增加1次）</p>
	<p>6．塑料圈积分和“我的塑料网”网站积分不通用</p>
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