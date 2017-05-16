<template>
<div class="buyWrap" style="padding: 45px 0 70px 0;">
	<div style="position: fixed; top: 0; left: 0; width: 100%; z-index: 10;">
		<header id="bigCustomerHeader">
			<a class="back" href="javascript:window.history.back();"></a>
			我的供求
			<a class="headerMenu2" href="javascript:;" v-on:click="shareshow"></a>
		</header>
	</div>
	<div class="supplytitle" style="background: #FFFFFF;">
		<h3>{{input_time}}</h3>
		<p><i class="myicon iconSupply"></i>我的供求:{{contents}}</p>
	</div>
	<div class="sharelayer" v-show="share" v-on:click="sharehide"></div>
	<div class="tip" v-show="share3"></div>
	<div class="tip2" v-show="share4"></div>
</div>
</template>
<script>
export default{
	data: function() {
		return {
			input_time: "",
			contents: "",
			id: "",
			type: "",
			user_id: "",
			share: false,
			share3: false,
			share4: false
		}
	},
	methods: {
		shareshow: function() {
			this.share = true;
			this.share3 = true;
		},
		sharehide: function() {
			this.share = false;
			this.share3 = false;
			this.share4 = false;
		},
		shareshow3: function() {
			this.share = true;
			this.share4 = true;
		}
	},
	watch: {
		contents: function() {
			var _this = this;
			wx.onMenuShareTimeline({
				title: _this.contents,
				link: 'http://q.myplas.com/#/supplybuy/' + _this.id + '?invite=' + tel,
				imgUrl: 'http://statics.myplas.com/myapp/img/shareLogo.png',
				success: function() {
					$.ajax({
						type:"post",
						url:version+"/wechat/saveShareLog",
						data:{
							token:window.localStorage.getItem("token"),
							type:1,
							id:_this.id
						},
						headers: {
							'X-UA': headers
						},
						dataType: 'JSON'
					}).done(function(res){
						
					}).fail(function(){
						
					});
				},
				cancel: function() {

				}
			});
			wx.onMenuShareAppMessage({
				title: _this.contents,
				desc: "我的塑料网-塑料圈通讯录",
				link: 'http://q.myplas.com/#/supplybuy/' + _this.id + '?invite=' + tel,
				imgUrl: 'http://statics.myplas.com/myapp/img/shareLogo.png',
				type: '',
				dataUrl: '',
				success: function() {
					$.ajax({
						type:"post",
						url:version+"/wechat/saveShareLog",
						data:{
							token:window.localStorage.getItem("token"),
							type:2,
							id:_this.id
						},
						headers: {
							'X-UA': headers
						},
						dataType: 'JSON'
					}).done(function(res){
						
					}).fail(function(){
						
					});
				},
				cancel: function() {

				}
			});
		}
	},
	activated: function() {
		var _this = this;
		try {
		    var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
		    piwikTracker.trackPageView();
		} catch( err ) {
			
		}
		$.ajax({
			type: "post",
			url: version+"/wechat/shareMyPur",
			data: {
				id: _this.$route.params.id,
				token: window.localStorage.getItem("token")
			},
			headers: {
				'X-UA': headers
			},
			dataType: 'JSON'
		}).then(function(res) {
			console.log(res);
			if(res.err == 1) {
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
				_this.input_time=res.data.input_time;
				_this.contents=res.data.contents || res.data.content;
				_this.id=_this.$route.params.id;
				_this.type=res.data.type;
				_this.user_id=res.data.user_id;
			}
		}, function() {

		});
	}
}
</script>