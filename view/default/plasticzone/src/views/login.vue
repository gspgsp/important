<template>
<div class="buyWrap" style="background: #FFFFFF; padding: 0; position: absolute">
	<header id="bigCustomerHeader">
		<router-link :to="{name:'index'}" class="back"></router-link>
		登录
	</header>
	<div class="registerHeader">
		<span v-bind:class="{on:tabshow}" v-on:click="tab(1)">普通登录</span>
		<span v-bind:class="{on:!tabshow}" v-on:click="tab(2)">手机动态密码登录</span>
	</div>
	<div v-show="tabshow">
		<div class="registerInput">
			<div class="registerBox">
				<input style="margin: 0 0 0 10px;" type="tel" maxlength="11" v-model="mobile" placeholder="请填写注册时手机号" />
				<i class="regIcon username"></i>
			</div>
			<div class="registerBox">
				<input style="margin: 0 0 0 10px;" type="password" maxlength="20" v-model="pwd" placeholder="请输入密码" />
				<i class="regIcon password"></i>
			</div>
		</div>
		<div style=" padding: 0 20px;">
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
	<div v-show="!tabshow">
		<div class="registerInput">
			<div class="registerBox">
				<input style="margin: 0 0 0 10px;" type="tel" maxlength="11" v-model="mobile" placeholder="请填写注册时手机号" />
				<i class="regIcon username"></i>
			</div>
			<div style=" padding: 0 115px 0 0; position: relative;">
			<div class="registerBox">
				<input style="margin: 0 0 0 10px;" type="tel" maxlength="11" v-model="mobile" placeholder="请填写验证码" />
				<i class="regIcon img"></i>
			</div>
			<img style="width: 105px; height: 44px; position: absolute; top: 0; right: 0;">
			</div>
			<div style=" padding: 0 115px 0 0; position: relative;">
			<div class="registerBox">
				<input style="margin: 0 0 0 10px;" type="tel" maxlength="11" v-model="mobile" placeholder="请填写动态码" />
				<i class="regIcon code"></i>
			</div>
			<input class="dvc" type="button" value="获取动态验证码" />
			</div>
		</div>
		<div class="registerBtn">
			<input type="button" v-on:click="login" value="登录" />
		</div>
	</div>
</div>
</template>
<script>
export default {
	data: function() {
		return {
			mobile: "",
			pwd: "",
			checked: false,
			tabshow:true
		}
	},
	methods: {
		tab:function(n){
			if(n==1){
				this.tabshow=true;
			}else{
				this.tabshow=false;
			}
		},
		login: function() {
			var _this = this;
			if(this.checked) {
				window.localStorage.setItem("username", this.mobile);
				window.localStorage.setItem("password", this.pwd);
			} else {
				window.localStorage.setItem("username", "");
				window.localStorage.setItem("password", "");
			}
			if(this.mobile && this.pwd) {
				$.ajax({
					url: version+'/user/login',
					type: 'post',
					data: {
						username: _this.mobile,
						password: _this.pwd
					},
					headers: {
						'X-UA': headers
					},
					dataType: 'JSON'
				}).done(function(res) {
					if(res.err == 0) {
						window.localStorage.setItem("token", res.dataToken);
						window.localStorage.setItem("userid", res.user_id);
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
				}).fail(function() {

				}).always(function() {

				});
			} else {
				weui.alert("手机号和密码不能为空", {
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