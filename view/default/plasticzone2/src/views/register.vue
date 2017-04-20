<template>
<div class="buyWrap">
	<header id="bigCustomerHeader">
		<a class="back" href="javascript:window.history.back();"></a>
		注册
	</header>
	<div class="registerWrap">
		<div class="registerTitle">
			<i class="arrowLeft"></i>帐号信息
		</div>
		<div class="registerInput">
			<div class="registerBox">
				<strong><span>*</span>手机号码:</strong>
				<input type="tel" maxlength="11" v-model="mobile" placeholder="请输入您的手机号码" />
			</div>
			<div class="registerBox">
				<strong><span>*</span>设置密码:</strong>
				<input type="password" maxlength="20" v-model="password" placeholder="请输入密码" />
			</div>
			<div class="registerBox">
				<strong><span>*</span>手机验证码:</strong>
				<input maxlength="6" v-model="code" type="tel" placeholder="请输入收到的验证码" />
				<button class="validCode" v-on:click="sendCode">{{validCode}}</button>
			</div>
		</div>
		<div class="registerTitle">
			<i class="arrowLeft"></i>更多信息
		</div>
		<div class="registerInput">
			<div class="registerBox">
				<strong><span>*</span>姓名:</strong>
				<input type="text" v-model="name" placeholder="请输入您的姓名" />
			</div>
			<div class="registerBox">
				<strong><span>*</span>公司名称:</strong>
				<input type="text" v-model="c_name" placeholder="请输入您的公司全称" />
			</div>
			<div class="registerBox">
				<strong><span>*</span>企业类型:</strong>
				<input name="firm" type="radio" value="1" v-model="c_type" /><label>塑料制品企业</label>
				<input name="firm" type="radio" value="2" v-model="c_type" /><label>原料供应商</label>
				<input name="firm" type="radio" value="4" v-model="c_type" /><label>服务商</label>
			</div>
		</div>
	</div>

	<div style=" padding: 40px 20px 0 20px; font-size: 14px;">
		<input type="checkbox" v-model="checked" />&nbsp;<label style="color: #999999;">已阅读</label>
		<router-link :to="{name:'protocol'}">《塑料圈网用户服务协议》</router-link>
	</div>
	<div class="registerBtn">
		<input type="button" v-on:click="reg" value="提交注册信息" />
	</div>
</div>
</template>
<script>
module.exports = {
	data: function() {
		return {
			mobile: "",
			password: "",
			code: "",
			name: "",
			c_name: "",
			c_type: 1,
			times: 60,
			validCode: "获取验证码",
			checked: true
		}
	},
	methods: {
		sendCode: function() {
			var _this = this;
			if(this.mobile) {
				$.ajax({
					url: '/api/qapi1/sendmsg',
					type: 'get',
					data: {
						mobile: _this.mobile,
						type: 0
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
								_this.validCode = "获取验证码";
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
		},
		reg: function() {
			var _this = this;
			if(this.checked&&this.password&&this.name&&this.c_name) {
				$.ajax({
					url: '/api/qapi1_2/register',
					type: 'post',
					data: {
						mobile: _this.mobile,
						password: _this.password,
						code: _this.code,
						name: _this.name,
						c_name: _this.c_name,
						chanel: 6,
						quan_type: 0,
						parent_mobile: window.localStorage.invite,
						c_type: _this.c_type
					},
					dataType: 'JSON'
				}).then(function(res) {
					if(res.err == 0) {
						if(window.localStorage.getItem("invite") != "undefined") {
							window.localStorage.setItem("inviteReg", 1)
						} else {
							window.localStorage.setItem("commReg", 2)
						}
						weui.alert(res.msg, {
							title: '塑料圈通讯录',
							buttons: [{
								label: '确定',
								type: 'parimary',
								onClick: function() {
									_this.$router.push({ name: 'login' });
								}
							}]
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
				}, function() {

				});
			} else {
				weui.alert("请把信息填写完整", {
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
	}

}
</script>