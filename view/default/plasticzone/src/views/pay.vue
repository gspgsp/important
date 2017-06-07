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
export default{
	data: function() {
		return {
			pay:[],
			money:null,
			eq:null,
			inputMoney:null,
			plasticBean:""
		}
	},
	methods:{
		onBridgeReady:function(timeStamp,nonceStr,prepay_id,paySign){
		   WeixinJSBridge.invoke(
		       'getBrandWCPayRequest', {
		           "appId":"wxbe66e37905d73815",     //公众号名称，由商户传入     
		           "timeStamp":"1324710901",         //时间戳，自1970年以来的秒数     
		           "nonceStr":"97bimlpPRfS6X8mV", //随机串     
		           "package":"prepay_id=wx20170607141219e84645f7df0381665882 ",     
		           "signType":"MD5",         //微信签名方式：     
		           "paySign":"787361706D8804C9E884958415EB7B23" //微信签名 
		       },
		       function(res){     
		           if(res.err_msg == "get_brand_wcpay_request:ok" ) {
		           	alert(ok)
		           }     // 使用以上方式判断前端返回,微信团队郑重提示：res.err_msg将在用户支付成功后返回    ok，但并不保证它绝对可靠。
		       }
		   ); 
		},
		payMoney:function(){
			var _this=this;
			_this.onBridgeReady(res.data.timestamp,res.data.noncestr,res.data.prepayid,res.data.sign);
//			$.ajax({
//				url:version+'/pay/getPrePayOrder',
//				type:'post',
//				data:{
//					type:1,
//					goods_id:"99",
//					total_fee:"0.01",
//					goods_num:"1"
//				},
//				headers: {
//					'X-UA': window.localStorage.getItem("XUA")
//				},
//				dataType: 'JSON'
//			}).done(function(res){
//				console.log(res);
//				if(res.err==0){
//					
//				}
////				if(res.err==0){
////					wx.chooseWXPay({
////					    timestamp: res.data.timestamp,
////					    nonceStr: res.data.noncestr,
////					    package: "prepay_id="+res.data.prepayid,
////					    signType: 'MD5',
////					    paySign: res.data.sign,
////					    success: function (data) {
////					        console.log(">>>",data);
////					    }
////					});
////				}
//			}).fail(function(){
//				
//			});
		},
		paySelect:function(num,i){
			this.money=num;
			this.eq=i;
			this.inputMoney=null;
			this.plasticBean=null;
			console.log(this.money);
			console.log(this.eq);
		},
		payInput:function(){
			var _this=this;
			this.eq=null;
			if(this.inputMoney&&this.inputMoney<=10000){
				$.ajax({
					type: "post",
					url: version + "/pay/getExactAmount",
					data: {
						money:_this.inputMoney
					},
					headers: {
						'X-UA': window.localStorage.getItem("XUA")
					},
					dataType: 'JSON'
				}).then(function(res) {
					if(res.err==0){
						_this.plasticBean=res.plasticBean+"塑豆";
					}
					
				}, function() {
		
				});				
			}else if(this.inputMoney>10000){
				this.inputMoney=10000;
				$.ajax({
					type: "post",
					url: version + "/pay/getExactAmount",
					data: {
						money:_this.inputMoney
					},
					headers: {
						'X-UA': window.localStorage.getItem("XUA")
					},
					dataType: 'JSON'
				}).then(function(res) {
					if(res.err==0){
						_this.plasticBean=res.plasticBean+"塑豆";
					}
					
				}, function() {
		
				});				
			}else{
				this.plasticBean="";
				this.paySelect(10,0);
			}
		
		}
	},
	activated: function() {
		try {
			var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
			piwikTracker.trackPageView();
		} catch(err) {

		}
		var _this=this;

	
		

		if (typeof WeixinJSBridge == "undefined"){
		   if( document.addEventListener ){
		       document.addEventListener('WeixinJSBridgeReady', _this.onBridgeReady, false);
		   }else if (document.attachEvent){
		       document.attachEvent('WeixinJSBridgeReady', _this.onBridgeReady); 
		       document.attachEvent('onWeixinJSBridgeReady', _this.onBridgeReady);
		   }
		}else{
		   onBridgeReady();
		}
		
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
			if(res.err==0){
				_this.pay=res.data;	
				_this.paySelect(res.data[0].money,0);
			}
			
		}, function() {

		});
	}
}
</script>