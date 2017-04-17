<template>
<div class="buyWrap" style="padding: 45px 0 0 0;">
	<div style="position: fixed; top: 0; left: 0; width: 100%; z-index: 10;">
		<header id="bigCustomerHeader">
			<a class="back" href="javascript:window.history.back();"></a>
			充值
		</header>
	</div>
	<img style="display: block;" width="100%" src="http://statics.myplas.com/myapp/img/recharge1.jpg">
	<img style="display: block;" width="100%" src="http://statics.myplas.com/myapp/img/recharge2.jpg">
	<a href="tel:4006129965"><img style="display: block;" width="100%" src="http://statics.myplas.com/myapp/img/recharge3.jpg"></a>
	<img style="display: block;" width="100%" src="http://statics.myplas.com/myapp/img/recharge4.jpg">
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
		document.title = "充值";
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
		} catch(err) {

		}

	}
}
</script>