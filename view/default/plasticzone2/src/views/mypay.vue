<template>
<div class="buyWrap" style="padding: 45px 0 70px 0;">
	<div style="position: fixed; top: 0; left: 0; width: 100%; z-index: 10;">
		<header id="bigCustomerHeader">
			<a class="back" href="javascript:window.history.back();"></a>
			我的关注
		</header>
	</div>
	<myrelation v-bind:name="name" v-bind:condition="condition"></myrelation>
	<footerbar></footerbar>
</div>
</template>
<script>
import footer from "../components/footer";
import myrelation from "../components/myrelation";
module.exports = {
	components: {
		'footerbar': footer,
		'myrelation': myrelation
	},
	data: function() {
		return {
			name: [],
			page: 1,
			condition: true
		}
	},
	mounted: function() {
		var _this = this;
			try {
	    var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
	    piwikTracker.trackPageView();
	} catch( err ) {
		
	}
		$.ajax({
			url: '/api/qapi1/getMyFuns',
			type: 'get',
			data: {
				page: _this.page,
				size: 100,
				type: 2,
				token: window.localStorage.getItem("token")
			},
			dataType: 'JSON'
		}).then(function(res) {
			if(res.err == 2) {
				_this.condition = false;
			} else if(res.err == 1) {
				mui.alert("", res.msg, function() {
					_this.$router.push({
						name: 'login'
					});
				});
			} else {
				_this.condition = true;
				_this.name = res.data;
			}
		}, function() {

		});
	}
}
</script>