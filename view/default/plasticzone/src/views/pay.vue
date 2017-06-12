<template>
<div>
	<header id="bigCustomerHeader">
		<a class="back" href="javascript:window.history.back();"></a>
		充值中心
	</header>
	<div class="payWrap">
		<div style="padding: 0 12px;">
			<h3 class="payTitle">充值金额:</h3>
			<ul class="payList">
				<li v-bind:class="{on:index==eq?true:false}" v-for="(p,index) in pay" v-on:click="paySelect(p.plasticBean,p.money,index)">
					<div class="payBox">
						<span>{{p.money}}元</span><br>{{p.plasticBean}}塑豆
					</div>
				</li>
			</ul>
			<div class="payOther">
				<span>其他金额：</span>
				<input class="payNum" type="tel" v-model="inputMoney" v-on:input="payInput" placeholder="15" />
				<b>{{plasticBean}}</b>
			</div>
		</div>
		<div class="payWay">
			<h3>选择支付方式:</h3>
			<div class="wxPay">
				<i class="iconWxPay"></i>微信支付
				<i class="iconWxRight"></i>
			</div>
		</div>
		<div style="padding: 0 15px; margin: 30px 0 0 0;">
			<div class="wxPayBtn" v-on:click="payMoney">支付</div>
		</div>
	</div>
</div>
</template>
<script>
export default {
	data: function() {
		return {
			pay: [],
			money: null,
			eq: null,
			inputMoney: null,
			plasticBean: "",
			order_id: "",
			beanOrder: {
				bean: "",
				money: ""
			}
		}
	},
	methods: {
		payMoney: function() {
			var _this = this;
			var jsApiParameters = {};
			function onBridgeReady() {
				WeixinJSBridge.invoke(
					'getBrandWCPayRequest',
					jsApiParameters,
					function(res) {
						if(res.err_msg == "get_brand_wcpay_request:ok") {
							$.ajax({
								type: "post",
								url: version + '/pay/updateOrderStatus',
								data: {
									type: 1,
									order_id: _this.order_id,
									status: "4"
								},
								headers: {
									'X-UA': window.localStorage.getItem("XUA")
								},
								dataType: 'JSON'
							}).then(function(res) {
								if(res.err==0){
									weui.alert('您支付成功', {
										title: '支付成功',
										buttons: [{
											label: '确定',
											type: 'primary',
											onClick: function() {
												
											}
										}]
									});
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

							}, function() {

							});
						}
						if(res.err_msg == "get_brand_wcpay_request:cancel") {
							$.ajax({
								type: "post",
								url: version + '/pay/updateOrderStatus',
								data: {
									type: 1,
									order_id: _this.order_id,
									status: "-3"
								},
								headers: {
									'X-UA': window.localStorage.getItem("XUA")
								},
								dataType: 'JSON'
							}).then(function(res) {
								if(res.err==0){
									weui.alert('您已取消支付', {
										title: '支付取消',
										buttons: [{
											label: '确定',
											type: 'primary',
											onClick: function() {
												
											}
										}]
									});
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
							}, function() {

							});
						}
						if(res.err_msg == "get_brand_wcpay_request:fail") {
							$.ajax({
								type: "post",
								url: version + '/pay/updateOrderStatus',
								data: {
									type: 1,
									order_id: _this.order_id,
									status: "-4"
								},
								headers: {
									'X-UA': window.localStorage.getItem("XUA")
								},
								dataType: 'JSON'
							}).then(function(res) {
								if(res.err==0){
									weui.alert('请重新充值', {
										title: '支付失败',
										buttons: [{
											label: '确定',
											type: 'primary',
											onClick: function() {
												
											}
										}]
									});
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
							}, function() {

							});
						}
					}
				);
			}

			$.ajax({
				url: version + '/pay/getPrePayOrder',
				type: 'post',
				data: {
					type: 1,
					goods_id: "99",
					total_fee: _this.beanOrder.money,
					goods_num: _this.beanOrder.bean,
					open_id: window.localStorage.getItem("openid")
				},
				headers: {
					'X-UA': window.localStorage.getItem("XUA")
				},
				dataType: 'JSON'
			}).done(function(res) {
				if(res.err == 0) {
					_this.order_id = res.order_id;
					jsApiParameters = JSON.parse(res.data);
					if(typeof WeixinJSBridge == "undefined") {
						if(document.addEventListener) {
							document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
						} else if(document.attachEvent) {
							document.attachEvent('WeixinJSBridgeReady', onBridgeReady);
							document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
						}
					} else {
						onBridgeReady();
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
			}).fail(function() {

			});
		},
		paySelect: function(num, money, i) {
			this.beanOrder.bean = num;
			this.beanOrder.money = money;
			this.money = money;
			this.eq = i;
			this.inputMoney = null;
			this.plasticBean = null;

		},
		payInput: function() {
			var _this = this;
			this.eq = null;
			if(this.inputMoney==0){
				this.inputMoney="";
				this.plasticBean = "";
				this.paySelect(100, 10, 0);
			}else if(this.inputMoney>0 && this.inputMoney <= 10000) {
				$.ajax({
					type: "post",
					url: version + "/pay/getExactAmount",
					data: {
						money: _this.inputMoney
					},
					headers: {
						'X-UA': window.localStorage.getItem("XUA")
					},
					dataType: 'JSON'
				}).then(function(res) {
					if(res.err == 0) {
						_this.beanOrder.bean = res.plasticBean;
						_this.beanOrder.money = _this.inputMoney;
						_this.plasticBean = res.plasticBean + "塑豆";
					}

				}, function() {

				});
			} else if(this.inputMoney > 10000) {
				this.inputMoney = 10000;
				$.ajax({
					type: "post",
					url: version + "/pay/getExactAmount",
					data: {
						money: _this.inputMoney
					},
					headers: {
						'X-UA': window.localStorage.getItem("XUA")
					},
					dataType: 'JSON'
				}).then(function(res) {
					if(res.err == 0) {
						_this.plasticBean = res.plasticBean + "塑豆";
						_this.beanOrder.bean = res.plasticBean;
						_this.beanOrder.money = _this.inputMoney;

					}

				}, function() {

				});
			} else {
				this.plasticBean = "";
				this.paySelect(100, 10, 0);
			}

		}
	},
	activated: function() {
		try {
			var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
			piwikTracker.trackPageView();
		} catch(err) {

		}
		var _this = this;

		$.ajax({
			type: "post",
			url: version + "/pay/getPayConfig",
			data: {

			},
			headers: {
				'X-UA': window.localStorage.getItem("XUA")
			},
			dataType: 'JSON'
		}).then(function(res) {
			if(res.err == 0) {
				_this.pay = res.data;
				_this.paySelect(res.data[0].plasticBean, res.data[0].money, 0);
			}else if(res.err==1){
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
		}, function() {

		});
	}
}
</script>