<template>
<div class="buyWrap">
	<header id="bigCustomerHeader">
		<a class="back" href="javascript:window.history.back();"></a>
		完善信息
	</header>
	<div class="registerWrap">
		<div class="registerBox">
			<input type="text" v-model="name" placeholder="请输入姓名" />
		</div>
		<div class="registerBox">
			<input type="text" v-model="c_name" placeholder="请输入公司名(全称)" />
		</div>
		<div class="registerBox">
			<input type="tel" v-model="qq" placeholder="请输入QQ号码" />
		</div>
		<div class="registerBox" style="padding: 0 10px; color: #a9a9a9">
			性别：
			<input type="radio" value="0" v-model="sexradio" />&nbsp;男&nbsp;
			<input type="radio" value="1" v-model="sexradio" />&nbsp;女
		</div>
	</div>
	<div class="registerBtn">
		<input type="button" v-bind:disabled="isDisable" v-on:click="complete" value="提交" />
	</div>
</div>
</template>
<script>
module.exports = {
	data: function() {
		return {
			name: "",
			c_name: "",
			qq: "",
			isDisable: false,
			sexradio: 0
		}
	},
	methods: {
		complete: function() {
			var _this = this;
			var region = "";
			this.isDisable = true;
			if(this.c_name.length < 6) {
				weui.alert("公司名至少填写6个字符", {
					title: '塑料圈通讯录',
					buttons: [{
						label: '确定',
						type: 'parimary',
						onClick: function() {
							_this.isDisable = false;
						}
					}]
				});
			} else if(this.name.length > 4) {
				weui.alert("姓名不能超过4个字符", {
					title: '塑料圈通讯录',
					buttons: [{
						label: '确定',
						type: 'parimary',
						onClick: function() {
							_this.isDisable = false;
						}
					}]
				});
			} else {
				$.ajax({
					url: '/api/qapi1/reginfo',
					type: 'get',
					data: {
						mobile: window.localStorage.getItem("mobile"),
						name: _this.name,
						c_name: _this.c_name,
						qq: _this.qq,
						chanel: 6,
						sex: _this.sexradio,
						parent_mobile: window.localStorage.invite,
						quan_type: 0,
						region: "wx"
					},
					dataType: 'JSON'
				}).done(function(res){
					if(res.err == 0) {
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
									_this.isDisable = false;
								}
							}]
						});
					}					
				}).fail(function(){
					
				}).always(function(){
					
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