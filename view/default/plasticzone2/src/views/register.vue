<template>
<div class="buyWrap">
	<header id="bigCustomerHeader">
		<a class="back" href="javascript:window.history.back();"></a>
		注册
	</header>
	<div class="registerWrap">
		<div class="registerBox">
			<input id="username" class="registerInput" maxlength="11" v-model="mobile" type="tel" placeholder="请输入手机号码">
		</div>
		<div class="registerBox">
			<input id="password" class="registerInput" maxlength="20" v-model="password" type="password" placeholder="请设定密码">
		</div>
		<div class="registerBox">
			<input id="regcode" class="registerInput" maxlength="6" v-model="code" type="tel" placeholder="请输入6位验证码">
			<button class="validCode" v-on:click="sendCode">{{validCode}}</button>
		</div>
		<div style=" padding: 0 10px; font-size: 14px;">
			<input type="checkbox" v-model="checked" />&nbsp;<label style="color: #999999;">已阅读</label>
			<router-link :to="{name:'protocol'}">《塑料圈网用户服务协议》</router-link>
		</div>
	</div>
	<div class="registerBtn">
		<input type="button" v-on:click="reg" value="下一步" />
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
			times: 60,
			validCode: "获取验证码",
			checked: true
		}
	},
	methods: {
		sendCode: function() {
			var _this = this;
		    if (this.mobile) {	
        		$.ajax({
					url: '/api/qapi1/sendmsg',
					type: 'get',
					data: {
						mobile:_this.mobile,
						type:0
					},
					dataType: 'JSON'
				}).then(function(res) {
					if(res.err == 0) {
						mui.alert('', res.msg, function() {
							
						});

						var countStart=setInterval(function(){
							_this.validCode=_this.times-- +'秒后重发';
							console.log(">>>",_this.times);
							if(_this.times<0){
								clearInterval(countStart);
								_this.validCode="获取验证码";
							}
						},1000);

					} else if(res.err==1){
						mui.alert('', res.msg, function() {
							
						});
					}
				}, function() {

				}); 			
    		} else{
					mui.alert('', "请填写手机号", function() {
						
					});
    		}  
		},
		reg: function() {
			var _this = this;
		    if(this.checked){
        		$.ajax({
					url: '/api/qapi1/register',
					type: 'get',
					data: {
						mobile:_this.mobile,
						password:_this.password,
						code:_this.code
					},
					dataType: 'JSON'
				}).then(function(res) {
					if(res.err == 0) {
						_this.$router.push({ name: 'completeinfo' });
						window.localStorage.setItem("mobile", _this.mobile);
					} else {
						mui.alert('', res.msg, function() {
		
						});
					}
				}, function() {

				});     			
    		}else{
    			mui.alert('', "请先同意用户服务协议", function() {

				});
    		}
		}
	},
	mounted: function() {
			try {
	    var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
	    piwikTracker.trackPageView();
	} catch( err ) {
		
	}
	}

}
</script>