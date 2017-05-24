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
				<input style="margin: 0 0 0 10px;" type="tel" maxlength="11" v-model="simpleCode" placeholder="请填写验证码" />
				<i class="regIcon img"></i>
			</div>
			<img v-on:click="sendImg" v-bind:src="simpleImg" style="width: 105px; height: 44px; position: absolute; top: 0; right: 0;">
			</div>
			<div style=" padding: 0 115px 0 0; position: relative;">
			<div class="registerBox">
				<input style="margin: 0 0 0 10px;" type="tel" maxlength="11" v-model="dynamicCode" placeholder="请填写动态码" />
				<i class="regIcon code"></i>
			</div>
			<button class="dvc" v-on:click="send">{{validCode}}</button>
			</div>
		</div>
		<div class="registerBtn">
			<input type="button" v-on:click="login2" value="登录" />
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
			tabshow:true,
			simpleImg:"",
			simpleCode:"",
			key:"",
			times: 60,
			dynamicCode:"",
			validCode: "获取动态验证码"
		}
	},
	methods: {
		sendImg:function(){
			var _this=this;
			$.ajax({
				url: '/api/vcode/app',
				type: 'get',
				data: {},
				headers: {
					'X-UA': window.localStorage.getItem("XUA")
				},
				dataType: 'JSON'
			}).done(function(res) {
				if(res.err == 0) {
					_this.simpleImg=res.img;
					_this.key=res.key;
				}
			}).fail(function() {

			}).always(function() {

			});		
		},
		tab:function(n){
			var _this=this;
			if(n==1){
				this.tabshow=true;
			}else{
				this.tabshow=false;
				$.ajax({
					url: '/api/vcode/app',
					type: 'get',
					data: {},
					headers: {
						'X-UA': window.localStorage.getItem("XUA")
					},
					dataType: 'JSON'
				}).done(function(res) {
					if(res.err == 0) {
						_this.simpleImg=res.img;
						_this.key=res.key;
					}
				}).fail(function() {

				}).always(function() {

				});

			}

		},
		send:function(){
			var _this=this;
			$.ajax({
				url: '/api/vcode/chkVcode',
				type: 'post',
				data: {
					name: "regcode",
					value: _this.simpleCode,
					key: _this.key
				},
				headers: {
					'X-UA': window.localStorage.getItem("XUA")
				},
				dataType: 'JSON'				
			}).done(function(res){
				if(res.err==0){
					if(_this.mobile) {
						$.ajax({
							url: '/user/login/sendMobileMsg',
							type: 'post',
							data: {
								phonenum: _this.mobile,
								from: 'h5'
							},
							headers: {
								'X-UA': window.localStorage.getItem("XUA")
							},
							dataType: 'JSON'
						}).then(function(res) {
							if(res.err == 0) {
								weui.alert(res.msg, {
									title: '塑料圈通讯录',
									buttons: [{
										label: '确定',
										type: 'parimary',
										onClick: function() {
		
										}
									}]
								});
		
								var countStart = setInterval(function() {
									_this.validCode = _this.times-- + '秒后重发';
									if(_this.times < 0) {
										clearInterval(countStart);
										_this.validCode = "获取动态验证码";
									}
								}, 1000);
		
							} else if(res.err == 1) {
								weui.alert(res.msg, {
									title: '塑料圈通讯录',
									buttons: [{
										label: '确定',
										type: 'parimary',
										onClick: function() {
		
										}
									}]
								});
							}
						}, function() {
		
						});
					} else {
						weui.alert("请填写手机号", {
							title: '塑料圈通讯录',
							buttons: [{
								label: '确定',
								type: 'parimary',
								onClick: function() {
		
								}
							}]
						});
					}					
				}else{
					weui.alert(res.msg, {
						title: '塑料圈通讯录',
						buttons: [{
							label: '确定',
							type: 'parimary',
							onClick: function() {

							}
						}]
					});			
				}
			}).fail(function(){
				
			}).always(function(){
				
			});
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
						'X-UA': window.localStorage.getItem("XUA")
					},
					dataType: 'JSON'
				}).done(function(res) {
					if(res.err == 0) {
						window.localStorage.setItem("token", res.dataToken);
						window.localStorage.setItem("userid", res.user_id);
						window.localStorage.setItem("XUA","h5|5.5|"+window.localStorage.getItem("userid")+"|"+window.localStorage.getItem("token")+"|0|"+navigator.platform+"|"+navigator.platform+"|"+navigator.platform+"|"+navigator.appName+"|"+navigator.appCodeName+"|0|0|0");
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
		},
		login2: function() {
			var _this = this;
			if(this.mobile && this.dynamicCode && this.simpleCode) {
				$.ajax({
					url: version+'/user/simpleLogin',
					type: 'post',
					data: {
						phonenum: _this.mobile,
						regcode:_this.simpleCode,
						phonevaild: _this.dynamicCode,
						key:_this.key
					},
					headers: {
						'X-UA': window.localStorage.getItem("XUA")
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
									
								}
							}]
						});
					}
				}).fail(function() {

				}).always(function() {

				});
			} else {
				weui.alert("信息不能为空", {
					title: '塑料圈通讯录',
					buttons: [{
						label: '确定',
						type: 'parimary',
						onClick: function() {
							
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