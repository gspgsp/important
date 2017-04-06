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
				<input type="text" placeholder="请输入您的姓名" />
			</div>
			<div class="registerBox">
				<strong><span>*</span>公司名称:</strong>
				<input type="text" placeholder="请输入您的公司全称" />
			</div>
			<div class="registerBox">
				<strong><span>*</span>公司所在地:</strong>
				<select v-model="selected" v-on:change="chooseReg">
					<option value="0">请选择</option>
					<option v-for="p in province" v-bind:value="p.id">
						{{p.name}}
					</option>
				</select>
				<select v-model="selected2">
					<option value="0">请选择</option>
					<option v-for="c in city" v-bind:value="c.id">
						{{c.name}}
					</option>
				</select>
			</div>
			<div class="registerBox">
				<strong><span>*</span>主营品种:</strong>
				<input type="text" placeholder="请输入您的主营品种进行匹配" v-model="model" v-on:input="suggestTxt" />
				<div class="suggest" v-show="suggestShow">
					<ul>
						<li v-for="m in modelArr">{{m.model}}</li>
					</ul>
				</div>
			</div>
			<div class="registerBox">
				<strong><span>*</span>企业类型:</strong>
				<input name="firm" type="radio" value="1" v-model="c_type" /><label>贸易商</label>
				<input name="firm" type="radio" value="2" v-model="c_type" /><label>工厂</label>
				<input name="firm" type="radio" value="3" v-model="c_type" /><label>工贸一体</label>
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
			province: [],
			city: [],
			validCode: "获取验证码",
			checked: true,
			selected: 0,
			selected2: 0,
			model: "",
			modelArr: [],
			suggestShow:false
		}
	},
	methods: {
		suggestTxt:function(){
			var _this = this;
			if (_this.model==0) {
				_this.suggestShow=false;
			} else{
				$.ajax({
					url: '/api/qapi1_2/getModel',
					type: 'post',
					data: {
						keywords: _this.model
					},
					dataType: 'JSON'
				}).done(function(res) {
					if(res.err == 0) {
						_this.suggestShow=true;
						_this.modelArr = res.data;
					}else if(res.err==2){
						_this.modelArr=[{model: "未搜到您所输入的品种"}];
					}
				}).fail(function() {
	
				}).always(function() {
	
				});					
			}
		
		},
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
						mui.alert('', res.msg, function() {

						});

						var countStart = setInterval(function() {
							_this.validCode = _this.times-- + '秒后重发';
							if(_this.times < 0) {
								clearInterval(countStart);
								_this.validCode = "获取验证码";
							}
						}, 1000);

					} else if(res.err == 1) {
						mui.alert('', res.msg, function() {

						});
					}
				}, function() {

				});
			} else {
				mui.alert('', "请填写手机号", function() {

				});
			}
		},
		reg: function() {
			var _this = this;
			if(this.checked) {
				$.ajax({
					url: '/api/qapi1_2/register',
					type: 'post',
					data: {
						mobile: _this.mobile,
						password: _this.password,
						code: _this.code,
						name: "",
						c_name: "",
						chanel: 6,
						quan_type: 0,
						parent_mobile: window.localStorage.invite,
						origin: ['11', '11'],
						model: ['231']
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
			} else {
				mui.alert('', "请先同意用户服务协议", function() {

				});
			}
		},
		chooseReg: function() {
			var _this = this;
			$.ajax({
				url: '/api/qapi1_2/getRegion',
				type: 'post',
				data: {
					id: _this.selected
				},
				dataType: 'JSON'
			}).done(function(res) {
				if(res.err == 0) {
					_this.city = res.data;
				}
			}).fail(function() {

			}).always(function() {

			});
		}
	},
	mounted: function() {
		try {
			var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
			piwikTracker.trackPageView();
		} catch(err) {

		}

		var _this = this;

		$.ajax({
			url: '/api/qapi1_2/getRegion',
			type: 'post',
			data: {},
			dataType: 'JSON'
		}).done(function(res) {
			if(res.err == 0) {
				_this.province = res.data;
			}
		}).fail(function() {

		}).always(function() {

		});

	}

}
</script>