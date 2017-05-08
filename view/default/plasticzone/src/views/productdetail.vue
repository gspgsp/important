<template>
<div>	
<header id="bigCustomerHeader">
	<a class="back" href="javascript:window.history.back();"></a>
	商品详情
</header>
<div class="productwrap">
	<img v-bind:src="thumb">
</div>
<div class="producttitle">
	{{name}}<br><span>{{points}}</span>积分
</div>
<div class="productchoose">
	<span v-on:click="isShow(1)" v-bind:class="{on:show}">商品详情</span>
	<span v-on:click="isShow(2)" v-bind:class="{on:!show}">退换货规定</span>
</div>
<div class="product1" v-show="show">
	<img v-bind:src="img">
</div>
<div class="product2" v-show="!show">
<p>兑换商品若出现以下情况，我的塑料网允许退换货：</p>
<p>1）商品本身有质量问题，影响使用</p>
<p>2）兑换的商品在运输过程中出现损毁</p>
<p>用户可在签收后7日内拨打我的塑料网客服热线400-6129-965，</p>
<p>申请退换货，退回时，请务必将原包装、内附赠品及说明书和相关文件一并寄回。</p>
<p>若出现以下情况，我的塑料网有权不予进行商品退换货：</p>
<p>1)非我的塑料网积分商城的兑换商品</p>
<p>2)不正常使用商品造成的质量问题</p>
<p>3)超过我的塑料网积分商城承诺的7天退换货有效时间</p>
<p>4)将商品存储、暴露在不适宜环境中造成的损坏</p>
<p>5)因未经授权的修理、改动、不正确的安装造成损坏</p>
<p>6)不可抗力导致礼品损坏</p>
<p>7)商品的正常磨损</p>
<p>8)在退换货之前未与我的塑料网客服取得联系，进行过退换货登记</p>
<p>9)退回商品包装或其他附属物不完整或有毁损</p>
<p>注：商品图片及文字仅供参考，具体以实物为准。</p>
</div>
<div class="proExchange" v-on:click="exchange">立即兑换</div>

<div class="proInput" v-show="proinputshow">
<p><span>收件人:</span><input id="receiver" class="proText" type="text" v-model="receiver"></p>
<p><span>手机号:</span><input id="phone" class="proText" type="number" v-model="phone"></p>
<p><span>联系地址:</span><input id="address" class="proText" type="text" v-model="address"></p> 
<p style="text-align: center; margin: 20px 0 0 0;">
	<input type="button" class="cancel2" v-on:click="cancel" value="取消">
	<input type="button" class="confirm2" v-on:click="cargosubmit" style="background: #ff5000;" value="确定">
</p>
</div>
<div class="layer" v-show="layershow"></div>
</div>
</template>
<script>
export default{
	data: function() {
		return {
			thumb:"",
			points:0,
			name:"",
			show:true,
			img:"",
			ordertype:"",
			proinputshow:false,
			layershow:false,
            times:60,
            validCode:"获取验证码",
            phone:"",
            receiver:"",
            address:"",
            vcode:""
		}
	},
	methods: {
		isShow:function(i){
			i==1?this.show=true:this.show=false;
		},
		cancel:function(){
			this.proinputshow=false;
			this.layershow=false;
		},
		exchange:function(){
			var _this = this;
			mui.confirm("此次兑换将使用"+_this.points+"积分，确定兑换码？","温馨提示",['取消', '确定'],function(e){
				if(e.index==1){
					if (_this.type==0) {
						_this.proinputshow=true;
						_this.layershow=true;
					}
				}else{
					
				}
			});
		},
		cargosubmit:function(){
			var _this=this;
			$.ajax({
	    		type:"get",
	    		url:"/api/qapi1/exchangeSupplyOrDemand",
	    		data:{
	    			type:0,
    				goods_id:_this.gid,
    				receiver:_this.receiver,
    				phone:_this.phone,
    				address:_this.address,
    				token: window.localStorage.getItem("token")
	    		},
	    		dataType: 'JSON'
	    	}).then(function(res){
	    		console.log(res.err);
			    if(res.err==0){
					mui.alert("",res.msg,function(){
						window.location.reload();
					});				
				}else if(res.err==1){
					mui.alert("",res.msg,function(){
						_this.$router.push({ name: 'login' });
					});        					
				}else{
					mui.alert("",res.msg,function(){
						window.location.reload();
					});				
				}
	    	},function(){
	    		
	    	});	
		}
	},
	mounted: function() {
		var _this = this;
		try {
		    var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
		    piwikTracker.trackPageView();
		} catch( err ) {
			
		}
		$.ajax({
    		type:"get",
    		url:"/api/qapi1/getProductInfo",
    		data:{
    			id: _this.$route.params.id,
	    		token: window.localStorage.getItem("token")
    		},
    		dataType: 'JSON'
    	}).then(function(res){
    		console.log(res);
		    if(res.err==1){
				mui.alert("",res.msg,function(){
					_this.$router.push({ name: 'login' });
				});        					
			}else{
				_this.thumb=res.info.thumb;
				_this.img=res.info.image;
				_this.points=res.info.points;
				_this.name=res.info.name;
				_this.ordertype=res.info.cate_id;
				_this.type=res.info.type;
				_this.gid=res.info.id;
			}
    	},function(){
    		
    	});
	}
}
</script>