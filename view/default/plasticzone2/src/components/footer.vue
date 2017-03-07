<template>
<div>
	<div class="toRelease" v-show="isReleaseshow">
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
	<footer id="footer">
		<ul>
			<li>
				<!--<router-link :to="{name:'release'}" :class="{'footerOn':isRelease}"><i class="foot3"></i><br>供求</router-link>-->
				<a v-on:click="toRelease" :class="{'footerOn':isRelease}"><i class="foot3"></i><br>供求</a>
			</li>
			<li>
				<router-link :to="{name:'index'}" :class="{'footerOn':isIndex}"><i class="foot2"></i><br>通讯录</router-link>
			</li>
			<li><i class="releaseicon" v-on:click="toReleaseshow"></i></li>
			<li>
				<a v-on:click="toHeadline" :class="{'footerOn':isHeadline}"><i class="foot5"></i><br>发现</a>
			</li>
			<li>
				<!--<router-link :to="{name:'myzone'}" :class="{'footerOn':isMyzone}"><i class="foot4"></i><br>我的</router-link>-->
				<a v-on:click="toMyzone" :class="{'footerOn':isMyzone}"><i class="foot4"></i><br>我的</a>
			</li>
		</ul>
	</footer>
</div>
</template>
<script>
module.exports = {
	data: function() {
		return {
			isIndex: false,
			isRelease: false,
			isMyzone: false,
			isHeadline: false,
			isReleaseshow: false,
			buy: [],
			supply: []
		}
	},
	methods: {
		toReleaseshow: function() {
			this.isReleaseshow = true;
		},
		toReleasehidden: function() {
			this.isReleaseshow = false;
		},
		toReleasebsbuy2:function(pid){
			var _this=this;
			if (window.localStorage.getItem("token")) {
					_this.isReleaseshow = false;
					_this.$router.push({
						name: 'releasebsbuy',
						query: {id: pid}
					});				
			} else{
				mui.alert("", "您未登录塑料圈,无法查看企业及个人信息", function() {
					_this.$router.push({
						name: 'login'
					});
				});				
			}	
		},
		toReleasebssupply2:function(pid){
			var _this=this;
			if (window.localStorage.getItem("token")) {
					_this.isReleaseshow = false;
					_this.$router.push({
						name: 'releasebssupply',
						query: {id: pid}
					});				
			} else{
				mui.alert("", "您未登录塑料圈,无法查看企业及个人信息", function() {
					_this.$router.push({
						name: 'login'
					});
				});				
			}	
		},
		toReleasebsbuy:function(){
			var _this=this;
			if (window.localStorage.getItem("token")) {
					_this.isReleaseshow = false;
					_this.$router.push({
						name: 'releasebsbuy'
					});				
			} else{
				mui.alert("", "您未登录塑料圈,无法查看企业及个人信息", function() {
					_this.$router.push({
						name: 'login'
					});
				});				
			}	
		},
		toReleasebssupply:function(){
			var _this=this;
			if (window.localStorage.getItem("token")) {
					_this.isReleaseshow = false;
					_this.$router.push({
						name: 'releasebssupply'
					});				
			} else{
				mui.alert("", "您未登录塑料圈,无法查看企业及个人信息", function() {
					_this.$router.push({
						name: 'login'
					});
				});				
			}			
		},
		toRelease:function(){
			var _this=this;
			if (window.localStorage.getItem("token")) {
					_this.$router.push({
						name: 'release'
					});				
			} else{
				mui.alert("", "您未登录塑料圈,无法查看企业及个人信息", function() {
					_this.$router.push({
						name: 'login'
					});
				});				
			}
		},
		toMyzone:function(){
			var _this=this;
			if (window.localStorage.getItem("token")) {
					_this.$router.push({
						name: 'myzone'
					});				
			} else{
				mui.alert("", "您未登录塑料圈,无法查看企业及个人信息", function() {
					_this.$router.push({
						name: 'login'
					});
				});				
			}
		},
		toHeadline:function(){
			var _this=this;
			if (window.localStorage.getItem("token")) {
					_this.$router.push({
						name: 'headline'
					});				
			} else{
				mui.alert("", "您未登录塑料圈,无法查看企业及个人信息", function() {
					_this.$router.push({
						name: 'login'
					});
				});				
			}
		}

	},
	mounted: function() {
		var _this = this;
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


		var uri = this.$route.name;
		switch(uri) {
			case 'index':
				this.isIndex = true;
				this.isRelease = false;
				this.isMyzone = false;
				this.isHeadline = false;
				break;
			case 'release':
				this.isIndex = false;
				this.isRelease = true;
				this.isMyzone = false;
				this.isHeadline = false;
				break;
			case 'myzone':
			case 'mysupply':
			case 'mybuy':
			case 'myinvite':
			case 'myfans':
			case 'mypay':
			case 'mymsg':
			case 'mymsg2':
			case 'myinfo':
				this.isIndex = false;
				this.isRelease = false;
				this.isMyzone = true;
				this.isHeadline = false;
				break;
			case 'headline':
			case 'headlinedetail':
			case 'headlinelist':
				this.isIndex = false;
				this.isRelease = false;
				this.isMyzone = false;
				this.isHeadline = true;
				break;
		}
	}
}
</script>