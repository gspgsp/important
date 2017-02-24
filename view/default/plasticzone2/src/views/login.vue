<template>
<div class="buyWrap">
	<header id="bigCustomerHeader">
		<router-link :to="{name:'index'}" class="back"></router-link>
		登录
	</header>
	<div class="registerWrap">
		<div class="registerBox">
			<input id="username" class="registerInput" maxlength="11" type="tel" v-model="mobile" placeholder="请输入手机号码">
		</div>
		<div class="registerBox">
			<input id="password" class="registerInput" maxlength="20" type="password" v-model="pwd" placeholder="请输入密码">
		</div>
	</div>
	<div style=" padding: 0 20px">
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
				url: '/api/qapi1/login',
				type: 'get',
				data: {
					username: _this.mobile,
					password: _this.pwd
				},
				dataType: 'JSON'
			}).then(function(res) {
				if(res.err == 0) {
					window.localStorage.setItem("token", res.dataToken);
					window.localStorage.setItem("userid", res.user_id);
					_this.$router.push({
						name: 'index'
					});
				} else {
					mui.alert("", res.msg, function() {

					});
				}
			}, function() {

			});

		}
	},
	mounted: function() {
			try {
	    var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
	    piwikTracker.trackPageView();
	} catch( err ) {
		
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