<template>
  <div id="app">
  		<Leftmodel></Leftmodel>
			<!--back begin-->
			<div class="form-back"><a href="javascript:;" v-on:click="login"></a>忘记密码</div>
			<!--back end-->
			<!--find-pwd begin-->
			<div class="find-pwd form-wrap">
		    		<!--error begin-->
		    		<div class="error">
		    				<p class="icon-alert"></p>
		    		</div>
		    		<!--error end-->
		        <div class="form-tr mobile">
		            <div class="name name-icon"></div>
		            <div class="val val-icon"><input type="text" placeholder="请输入手机号码" name="mobilenumber" v-model="mobile"/></div>
		        </div>
		        <div class="form-tr pwd">
		            <div class="name name-icon"></div>
		            <div class="val val-icon"><input type="password" placeholder="请输入新密码" name="setpwd" v-model="password"/></div>
		        </div>
		        <div class="form-tr code">
		            <div class="name name-icon"></div>
		            <div class="val val-icon"><input type="text" placeholder="请输入验证码" name="jyCode" v-model="code"/></div>
		            <div class="get-code enable"  data-bool="true" v-on:click="sendCode">{{validCode}}</div>
		        </div>
		        <div class="form-tr"></div>
		        <div class="form-tr">
		            <button type="button" class="btn1" v-on:click="resetPwd">重置</button>
		        </div>
			</div>
			<!--find-pwd end-->
  </div>
</template>
<script>
import Leftmodel from "../../components/Leftmodel";
export default {
  name: 'app',
  components: {
		'Leftmodel': Leftmodel
	},
  data: function() {
		return {
				mobile: "",
				password: "",
				code: "",
				times: 60,
				validCode: "获取验证码"
		}
	},
	//methods begin
	methods : {
			login : function () {
					location.href = "login";
			},
			//sendCode begin
			sendCode : function () {
					var _this = this,
							errorMsg = $( ".error p" );
					if(this.mobile) {
						$.ajax({
							url: '/api/qapi1/sendmsg',
							type: 'get',
							data: {
								mobile: _this.mobile,
								type: 1
							},
							dataType: 'JSON'
						}).then(function(res) {
							//alert( res.err + " " + res.msg );
							if(res.err == 0) {
									errorMsg.html( res.msg );
								/*var countStart = setInterval(function() {
									_this.validCode = _this.times-- + '秒后重发';
									if(_this.times < 0) {
										clearInterval(countStart);
										_this.validCode = "获取验证码";
									}
								}, 1000);*/
							} else if(res.err == 1) {
										errorMsg.html( res.msg );
							}
						}, function() {
		
						});
					} else {
							errorMsg.html( "请填写手机号和密码" );
					}
			},
			//sendCode end
			//resetPwd begin
			resetPwd: function() {
					var _this = this,
							errorMsg = $( ".error p" );
					if(this.mobile && this.password && this.code) {
						$.ajax({
							url: '/api/qapi1/finfMyPwd',
							type: 'get',
							data: {
								mobile: _this.mobile,
								password: _this.password,
								code: _this.code
							},
							dataType: 'JSON'
						}).then(function(res) {
							//alert( res.err + " " + res.msg );
							if(res.err == 0) {
									errorMsg.html( res.msg );
							} else if(res.err == 1) {
									errorMsg.html( res.msg );
							}
						}, function() {
		
						});
					} else {
							errorMsg.html( "请把信息填写完整" );
					}
			}
			//resetPwd begin
			
	}
	//methods end
}
</script>