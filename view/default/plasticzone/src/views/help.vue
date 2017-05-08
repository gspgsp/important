<template>
<div class="buyWrap" style="padding: 45px 0 0 0;">
	<div style="position: fixed; top: 0; left: 0; width: 100%; z-index: 10;">
		<header id="bigCustomerHeader">
			<a class="back" href="javascript:window.history.back();"></a>
			帮助
			<a class="headerMenu3" href="tel:4006129965"></a>
		</header>
	</div>
	<div class="help">
		<div class="h1"></div>
		<div class="h2"></div>
		<div class="h3"></div>
		<div class="h4"></div>
		<div class="h5"></div>
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