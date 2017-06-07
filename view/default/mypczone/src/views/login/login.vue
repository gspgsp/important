<template>
  <div id="app">
  		<Leftmodel></Leftmodel>
  		<!--right begin-->
  		<div class="right">
					<!--back begin-->
					<div class="form-back">登录</div>
					<!--back end-->
					<!--login begin-->
					<div class="form-wrap login">
				    		<!--error begin-->
								<div class="error">
										<p class="icon-alert"></p>
								</div>
								<!--error end-->
				        <div class="form-tr">
				        	<div class="name name-text">手机号</div>
				            <div class="val val-text"><input type="text" placeholder="请输入手机号" v-model="mobile" name="mobilenumber"/></div>
				        </div>
				        <div class="form-tr">
				        	<div class="name name-text">密<span></span>码</div>
				            <div class="val val-text"><input type="password" placeholder="请输入密码" v-model="pwd" name="setpwd"/></div>
				        </div>
				        <div class="form-tr check">
				        	<input type="checkbox" id="rem"/>
				            <label for="rem">记住密码</label>
				        </div>
				        <p class="go-reg">还没有账号？去<a href="javascript:;" v-on:click="register">免费注册</a></p>
				        <div class="form-tr">
				        	<button class="btn1" v-on:click="login()">登录</button>
				        </div>
				        <p class="forget frt"><a href="javascript:;" v-on:click="findpwd">忘记密码？</a></p>
					</div>
					<!--login end-->
			</div>
			<!--right end-->
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
			pwd: "",
			checked: false
		}
	},
	//methods begin
	methods : {
			login : function () {
					var _this = this,
							errorMsg = $( ".error p" );
					if(this.mobile && this.pwd) {
							$.ajax({
									url: '/api/qapi1_2/login',
									type: 'post',
									data: {
										username: _this.mobile,
										password: _this.pwd
									},
									dataType: 'JSON'
							}).done( function ( res ) {
									//res.err == 0 begin 登录成功
									if(res.err == 0) {
											window.localStorage.setItem("token", res.dataToken);
											window.localStorage.setItem("userid", res.user_id);
											errorMsg.html( res.msg );
									}
									//res.err == 0 end
									//res.err == 1 begin 手机号未注册
									else {
											errorMsg.html( res.msg );
									}
									//res.err == 1 end 手机号未注册
							} );
					} else { 
							errorMsg.html( "手机号和密码不能为空");
					}
			},
			register : function () {
					location.href = "register";
			},
			findpwd : function () {
					location.href = "findpwd";
			}
	}
	//methods end
}
</script>