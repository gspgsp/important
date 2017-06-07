<template>
  <div id="app">
  		<Leftmodel></Leftmodel>
  		<!--right begin-->
  		<div class="right">
					<!--back begin-->
					<div class="form-back"><a href="javascript:;" v-on:click="login"></a>注册</div>
					<!--back end-->
					<!--reg begin-->
					<div class="reg">
						<!--error begin-->
						<div class="error">
								<p class="icon-alert"></p>
						</div>
						<!--error end-->
						<form name="" method="post" action="" id="reg-form">
						    <h3>账号信息</h3>
					        <!--tr begin-->
					        <div class="tr">
					            <div class="name">手机号码：<span>*</span></div>
					            <div class="val">
					                <input type="text" placeholder="手机号码" name="mobilenumber" v-model="mobile" value=""/>
					            </div>
					            <div class="error-msg"></div>
					        </div>
					        <!--tr end-->
					 		<!--tr begin-->
					        <div class="tr">
					            <div class="name">设置密码：<span>*</span></div>
					            <div class="val">
					                <input type="password" placeholder="请输入密码" id="set-pwd1" name="setpwd" v-model="password" value=""/>
					            </div>
					            <div class="error-msg"></div>
					        </div>
					        <!--tr end-->
					       	<!--tr begin-->
					        <div class="tr">
					            <div class="name">手机校验码：<span>*</span></div>
					            <div class="val code"><input type="text" placeholder="请输入验证码" name="jyCode" v-model="code" value=""/></div>
					            <div class="get-code enable" data-bool="true" v-on:click="sendCode">{{validCode}}</div>
					            <div class="error-msg"></div>
					        </div>
					        <!--tr end-->
					        <h3>更多信息</h3>
					        <!--tr begin-->
					        <div class="tr">
					            <div class="name">姓名：<span>*</span></div>
					            <div class="val"><input type="text" placeholder="请输入您的姓名" name="userName" v-model="name" value=""/></div>
					            <div class="error-msg"></div>
					        </div>
					        <!--tr end-->
					        <!--tr begin-->
					        <div class="tr">
					            <div class="name">公司名称：<span>*</span></div>
					            <div class="val"><input type="text" placeholder="请输入您的公司全称" value="" name="companyName" v-model="c_name"/></div>
					            <div class="error-msg"></div>
					        </div>
					        <!--tr end-->
					        <!--tr begin-->
					        <div class="tr">
					            <div class="name">企业类型：<span>*</span></div>
					            <div class="radio">
					                <input type="radio" id="radio1" name="enterprise" value="1" v-model="c_type"/><label for="radio1">塑料制品厂</label>
					                <input type="radio" id="radio2" name="enterprise" value="2" v-model="c_type"/><label for="radio2">原料供应商</label>
					                <input type="radio" id="radio3" name="enterprise" value="4" v-model="c_type"/><label for="radio3">物流服务商</label>
					            </div>
					            <div class="error-msg"></div>
					        </div>
					        <!--tr end-->
					        <!--reg-agreement begin-->
					        <div class="tr reg-agreement">
					            <div class="check">
					                <input type="checkbox" name="protocol" id="check1" v-model="checked"/>
					                <label for="check1">阅读并接受</label>
					                <a href="javascript:;" v-on:click="agreement">《我的塑料网用户服务协议》</a>
					            </div>
					            <div class="error-msg"></div>
					        </div>
					        <!--reg-agreement end-->
					        <input type="button" class="submit" value="提交注册信息" v-on:click="reg"/>
					    </form>
					</div>
					<!--reg end-->
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
				mobile: "17717531604",
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
	//methods begin
	methods : {
			login : function () {
					location.href = "login";
			},
			agreement : function () {
					location.href = "agreement";
			},
			//sendCode begin
			sendCode: function() {
					var _this = this,
							errMsg = $( ".error p" );	
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
											errMsg.html( res.msg );
									} else if(res.err == 1) { 
											errMsg.html( res.msg );
									}
							}, function () {
									
							})
					} else { 
							errMsg.html( "请填写手机号" );
					}
			},
			//sendCode end
			//reg begin
			reg: function() {
					var _this = this,
							errMsg = $( ".error p" );
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
											errMsg.html( res.msg );
									} else if(res.err == 1) {
											errMsg.html( res.msg );
									}
							}, function () {
									
							});
					} else {
							errMsg.html( "请把信息填写完整" );
					}
			}
			//reg end
	}
	//methods end
}
</script>