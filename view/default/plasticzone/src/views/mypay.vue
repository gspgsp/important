<template>
<div class="buyWrap" style="padding: 0 0 70px 0;">
	<header id="bigCustomerHeader">
		<a class="back" href="javascript:window.history.back();"></a>
		我的关注
	</header>
	<myrelation v-bind:name="name" v-bind:condition="condition"></myrelation>
	<footerbar></footerbar>
</div>
</template>
<script>
import footer from "../components/footer";
import myrelation from "../components/myrelation";
export default{
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
			url: version+"/myInfo/getMyFuns",
			type: 'post',
			data: {
				page: _this.page,
				size: 100,
				type: 2,
				token: window.localStorage.getItem("token")
			},
			headers: {
				'X-UA': window.localStorage.getItem("XUA")
			},
			dataType: 'JSON'
		}).then(function(res) {
			if(res.err == 2) {
				_this.condition = false;
			} else if(res.err == 1) {
				weui.alert(res.msg, {
					title: '塑料圈通讯录',
					buttons: [{
						label: '确定',
						type: 'parimary',
						onClick: function() {
							_this.$router.push({
								name: 'login'
							});
						}
					}]
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