<template>
<div>
	<header id="bigCustomerHeader">
		<a class="back" href="javascript:window.history.back();"></a>
		充值
	</header>
	<img style="display: block;" width="100%" src="http://statics.myplas.com/myapp/img/recharge.jpg">
	<img style="display: block; float: left;" width="50%" src="http://statics.myplas.com/myapp/img/recharge2.jpg">
	<img style="display: block; float: left;" width="50%" src="http://statics.myplas.com/myapp/img/recharge3.jpg">
	<img style="display: block;" width="100%" src="http://statics.myplas.com/myapp/img/recharge4.jpg">
	<a href="tel:4006129965">
		<img style="display: block;" width="100%" src="http://statics.myplas.com/myapp/img/recharge5.jpg">
	</a>
	<img style="display: block;" width="100%" src="http://statics.myplas.com/myapp/img/recharge6.jpg">
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
		} catch(err) {

		}

	}
}
</script>