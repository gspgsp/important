<template>
<div>
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
	activated: function() {
		try {
			var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
			piwikTracker.trackPageView();
		} catch(err) {

		}

	}
}
</script>