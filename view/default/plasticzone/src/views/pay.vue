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
			<li v-bind:class="{on:index==eq?true:false}" v-for="(p,index) in pay" v-on:click="paySelect(p.money,index)">
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
			plasticBean: ""
	}
},
methods: {
	payMoney: function() {
		var _this = this;
		var jsApiParameters={};
		function onBridgeReady() {
			console.log("11111");
			WeixinJSBridge.invoke(
				'getBrandWCPayRequest',
				jsApiParameters,
				function(res) {
					if(res.err_msg == "get_brand_wcpay_request:ok") {
						alert(ok)
					} // 使用以上方式判断前端返回,微信团队郑重提示：res.err_msg将在用户支付成功后返回    ok，但并不保证它绝对可靠。
				}
			);
		}


		$.ajax({
			url: version + '/pay/getPrePayOrder',
			type: 'post',
			data: {
				type: 1,
				goods_id: "99",
				total_fee: "0.01",
				goods_num: "1",
				open_id: window.localStorage.getItem("openid")
			},
			headers: {
				'X-UA': window.localStorage.getItem("XUA")
			},
			dataType: 'JSON'
		}).done(function(res) {
			if(res.err == 0) {
				jsApiParameters={
					JSON.parse(res.data)
				};
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
			}

		}).fail(function() {

		});
	},
	paySelect: function(num, i) {
		this.money = num;
		this.eq = i;
		this.inputMoney = null;
		this.plasticBean = null;
		console.log(this.money);
		console.log(this.eq);
	},
	payInput: function() {
		var _this = this;
		this.eq = null;
		if(this.inputMoney && this.inputMoney <= 10000) {
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
				}

			}, function() {

			});
		} else {
			this.plasticBean = "";
			this.paySelect(10, 0);
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
				_this.paySelect(res.data[0].money, 0);
			}

		}, function() {

		});
	}
}
</script>