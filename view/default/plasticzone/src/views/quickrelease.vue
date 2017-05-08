<template>
<div class="toRelease">
	<div class="toReleaseWrap">
		<div class="toReleaseUl">
			<ul class="toReleaseli" style=" padding: 0 10px 0 0; border-right: 1px solid #D9D9D9;">
				<li v-for="b in buy">
					<div><span style="color: #999999;">{{b.input_time}}</span>
						<a v-on:click="toReleasebsbuy2(b.p_id)" style=" float: right; color: #ff5000;">重发</a>
					</div>
					<p>{{b.content}}</p>
				</li>
			</ul>
			<ul class="toReleaseli" style=" padding: 0 0 0 10px; border-left: 1px solid #FFFFFF;">
				<li v-for="s in supply">
					<div><span style="color: #999999;">{{s.input_time}}</span>
						<a v-on:click="toReleasebssupply2(s.p_id)" style=" float: right; color: #ff5000;">重发</a>
					</div>
					<p>{{s.content}}</p>
				</li>
			</ul>
		</div>
		<div class="toReleaselink">
			<a v-on:click="toReleasebsbuy"><i class="toReleasebuy"></i><br>发布求购</a>
			<a v-on:click="toReleasebssupply"><i class="toReleasesupply"></i><br>发布供给</a>
		</div>
	</div>
	<div class="toReleasefooter">
		<i class="toReleaseclose" v-on:click="toReleasehidden"></i>
	</div>
</div>
</template>
<script>
export default{
	data: function() {
		return {
			buy: [],
			supply: []
		}
	},
	methods: {
		toReleasehidden:function(){
			window.history.back();
		},
		toReleasebsbuy2: function(pid) {
			var _this = this;
			if(window.localStorage.getItem("token")) {
				_this.isReleaseshow = false;
				_this.$router.push({
					name: 'releasebsbuy',
					query: { id: pid }
				});
			} else {
				weui.alert('您未登录塑料圈,无法查看企业及个人信息', {
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
			}
		},
		toReleasebssupply2: function(pid) {
			var _this = this;
			if(window.localStorage.getItem("token")) {
				_this.isReleaseshow = false;
				_this.$router.push({
					name: 'releasebssupply',
					query: { id: pid }
				});
			} else {
				weui.alert('您未登录塑料圈,无法查看企业及个人信息', {
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
			}
		},
		toReleasebsbuy: function() {
			var _this = this;
			if(window.localStorage.getItem("token")) {
				_this.isReleaseshow = false;
				_this.$router.push({
					name: 'releasebsbuy'
				});
			} else {
				weui.alert('您未登录塑料圈,无法查看企业及个人信息', {
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
			}
		},
		toReleasebssupply: function() {
			var _this = this;
			if(window.localStorage.getItem("token")) {
				_this.isReleaseshow = false;
				_this.$router.push({
					name: 'releasebssupply'
				});
			} else {
				weui.alert('您未登录塑料圈,无法查看企业及个人信息', {
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
			}
		},
	},
	activated: function() {
		var _this = this;
		try {
			var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
			piwikTracker.trackPageView();
		} catch(err) {

		}

		$.ajax({
			type: "get",
			url: "/api/qapi1/supplyDemandList",
			data: {
				page: 1,
				token: window.localStorage.getItem("token"),
				size: 5,
				type: 1
			},
			dataType: 'JSON'
		}).then(function(res) {
			if(res.err == 0) {
				_this.buy = res.data;
			} else if(res.err == 1) {
				_this.buy = [];
			} else if(res.err == 2) {
				_this.buy = [];
			}
		}, function() {

		});

		$.ajax({
			type: "get",
			url: "/api/qapi1/supplyDemandList",
			data: {
				page: 1,
				token: window.localStorage.getItem("token"),
				size: 5,
				type: 2
			},
			dataType: 'JSON'
		}).then(function(res) {
			if(res.err == 0) {
				_this.supply = res.data;
			} else if(res.err == 1) {
				_this.supply = [];
			} else if(res.err == 2) {
				_this.supply = [];
			}
		}, function() {

		});

	}
}
</script>