<template>
<div>
	<footer id="footer">
		<ul>
			<li>
				<a href="javascript:;" v-on:click="toRelease" :class="{'footerOn':isRelease}"><i class="foot3"></i><br>供求</a>
			</li>
			<li>
				<router-link :to="{name:'index'}" :class="{'footerOn':isIndex}"><i class="foot2"></i><br>通讯录</router-link>
			</li>
			<li><i class="releaseicon" v-on:click="toQuickRelease"></i></li>
			<li>
				<a href="javascript:;" v-on:click="toHeadline" :class="{'footerOn':isHeadline}"><i class="foot5"></i><br>发现</a>
			</li>
			<li>
				<a href="javascript:;" v-on:click="toMyzone" :class="{'footerOn':isMyzone}"><i class="foot4"></i><br>我的</a>
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
			isHeadline: false
		}
	},
	methods: {
		toQuickRelease:function(){
			var _this=this;
			if (window.localStorage.getItem("token")) {
					_this.$router.push({
						name: 'quickrelease'
					});				
			} else{
				weui.alert('您未登录塑料圈,无法查看企业及个人信息', {
				    title: '塑料圈通讯录',
				    buttons: [{
				        label: '确定',
				        type: 'parimary',
				        onClick: function(){
							_this.$router.push({
								name: 'login'
							});				        	
				        }
				    }]
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
				weui.alert('您未登录塑料圈,无法查看企业及个人信息', {
				    title: '塑料圈通讯录',
				    buttons: [{
				        label: '确定',
				        type: 'parimary',
				        onClick: function(){
							_this.$router.push({
								name: 'login'
							});				        	
				        }
				    }]
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
				weui.alert('您未登录塑料圈,无法查看企业及个人信息', {
				    title: '塑料圈通讯录',
				    buttons: [{
				        label: '确定',
				        type: 'parimary',
				        onClick: function(){
							_this.$router.push({
								name: 'login'
							});				        	
				        }
				    }]
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
				weui.alert('您未登录塑料圈,无法查看企业及个人信息', {
				    title: '塑料圈通讯录',
				    buttons: [{
				        label: '确定',
				        type: 'parimary',
				        onClick: function(){
							_this.$router.push({
								name: 'login'
							});				        	
				        }
				    }]
				});				
			}
		}

	},
	mounted: function() {
		var _this = this;
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