<template>
<div class="buyWrap">
	<header id="bigCustomerHeader">
		<router-link :to="{name:'index'}" class="back"></router-link>
		登录
	</header>
	<div class="registerInput">
		<div class="registerBox">
			<strong>手机号码:</strong>
			<input type="tel" maxlength="11" v-model="mobile" placeholder="请输入您的手机号码" />
		</div>	
		<div class="registerBox">
			<strong>密码:</strong>
			<input type="password" maxlength="20" v-model="pwd" placeholder="请输入密码" />
		</div>
	</div>
	<div style=" padding: 0 20px; margin: 10px 0 0 0;">
		<input type="checkbox" v-model="checked" />&nbsp;<label style="color: #999999;">记住密码</label>
	</div>
	<div class="registerBtn">
		<input type="button" v-on:click="login" value="登录" />
	</div>
	<div class="loginLink" style="margin: 0 20px;">
		<router-link :to="{name:'register'}" style="float: left;">注册</router-link>
		<router-link :to="{name:'resetpwd'}" style="float: right;">忘记密码</router-link>
	</div>
</div>
</template>
<script>
module.exports = {
	data: function() {
		return {
			mobile: "",
			pwd: "",
			checked: false
		}
	},
	methods: {
		login: function() {
			var _this = this;
			if(this.checked) {
				window.localStorage.setItem("username", this.mobile);
				window.localStorage.setItem("password", this.pwd);
			} else {
				window.localStorage.setItem("username", "");
				window.localStorage.setItem("password", "");
			}
			$.ajax({
				url: '/api/qapi1_2/login',
				type: 'post',
				data: {
					username: _this.mobile,
					password: _this.pwd
				},
				dataType: 'JSON'
			}).done(function(res) {
				if(res.err == 0) {
					window.localStorage.setItem("token", res.dataToken);
					window.localStorage.setItem("userid", res.user_id);
					console.log(window.localStorage.invite);
					if(window.localStorage.invite!=="undefined"){
						$.ajax({
							type:"post",
							url:"/api/score/addScore",
							data:{
								token:window.localStorage.getItem("token"),
								parent_mobile:window.localStorage.invite,
								type:'1'
							},
							dataType: 'JSON'
						}).done(function(res){
							
						}).fail(function(){
							
						});						
					}else{
						$.ajax({
							type:"post",
							url:"/api/score/addScore",
							data:{
								token:window.localStorage.getItem("token"),
								type:'3'
							},
							dataType: 'JSON'
						}).done(function(res){
							
						}).fail(function(){
							
						});						
					}
					_this.$router.push({
						name: 'index'
					});
				} else {
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
				}
			}).fail(function(){
				
			}).always(function(){
				
			});

		}
	},
	mounted: function() {
		try {
			var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
			piwikTracker.trackPageView();
		} catch(err) {

		}
		var lousername = window.localStorage.getItem("username") || "";
		var lopassword = window.localStorage.getItem("password") || "";
		this.mobile = lousername;
		this.pwd = lopassword
		if(lousername !== "" && lopassword !== "") {
			this.checked = true;
		}

	}
}
</script>