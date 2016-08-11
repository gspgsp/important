<template>
	<header id="meHeader">
		<a class="back" href="javascript:window.history.back();"></a>
		商品详情
	</header>
	<div class="proDetail">
		<img v-bind:src=thumb>
		<h3 class="proTitle"><span id="name"></span><span>{{points}}</span>积分</h3>
		<div class="proChoose">
			<span :class="{'on':0==itemIndex}" @click="itemsActive(0)">商品详情</span>
			<span :class="{'on':1==itemIndex}" @click="itemsActive(1)">退换货规定</span>
		</div>
	</div>
	<div class="proContent proPic" v-show="0==itemIndex">
		<img v-bind:src=image>
	</div>
	<div class="proContent proTxt" v-show="1==itemIndex">
		<p>兑换商品若出现以下情况，我的塑料网允许退换货：</p>
		<p>1）商品本身有质量问题，影响使用</p>
		<p>2）兑换的商品在运输过程中出现损毁</p>
		<p>用户可在签收后7日内拨打我的塑料网客服热线400-6129-965，</p>
		<p>申请退换货，退回时，请务必将原包装、内附赠品及说明书和相关文件一并寄回。</p>
		<br>
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
		<br>
		<p>注：商品图片及文字仅供参考，具体以实物为准。</p>
	</div>
	<div class="proExchange" @click="exchange()">立即兑换</div>
	<div class="proLayer" v-show="layerShow">
		<h3>温馨提示</h3>
		<p>此次兑换将使用<span id="proPoints2">{{points}}</span>积分，确定兑换码？</p>
		<p style="text-align: center">
			<button class="cancel" @click="cancel()" style="padding: 0; background: #999999;">取消</button>
			<button class="confirm" @click="confirm()" style="padding: 0;">确定</button>
		</p>
	</div>
	<div class="proInput" v-show="layerShow2">
		<p><span>收件人:</span><input id="receiver" class="proText" v-model="username" type="text"></p>
		<p><span>联系电话:</span><input id="phone" class="proText" v-model="mobi" type="number"></p>
		<p><span>联系地址:</span><input id="address" class="proText" type="text"></p>
		<p style="position: relative;">
			<span>验证码:</span><input id="code" style="font-size: 12px; width: 100px;" class="proText2" type="text" placeholder="输入验证码">
			<button class="validCode" style="width: 72px; border-radius: 0; background: #ff5000; height: 25px; right: 25px; line-height: 25px; position: absolute; top: 0;">获取验证码</button>
		</p>
		<p style="text-align: center; margin: 20px 0 0 0;">
			<input type="button" class="cancel2" @click="cancel()" value="取消">
			<input type="button" class="confirm2" style="background: #ff5000;" value="确定">
		</p>
	</div>
	<div class="layer" v-show="layerShow"></div>
</template>	
<script>
module.exports={
	data:function(){
		return {
			itemIndex:0,
			thumb:"",
			image:"",
			name:"",
			points:"",
			layerShow:false,
			layerShow2:false,
			username:"",
			mobi:""
		}
	},
	ready:function(){
		this.$http.post('/mobi/personalCenter/get_shopDetail',{gid:this.$route.query.id}).then(function(res){
			console.log(res.json());
			if(res.json().err==1){
				this.$route.router.go("/login");
			}else{
				this.$set("thumb",res.json().thumb);
				this.$set("image",res.json().image);
				this.$set("name",res.json().name);
				this.$set("points",res.json().points);
			}
		},function(){
			
		});
	},
	methods:{
		itemsActive:function (index) {
            this.itemIndex=index ? index : 0;
        },
        cancel:function(){
        	this.layerShow=false;
        	this.layerShow2=false;
        },
        exchange:function(){
        	this.layerShow=true;
        },
        confirm:function(){
        	this.layerShow2=true;
       		this.$http.post('/touch/creditshop/getUserProduct',{}).then(function(res){
       			console.log(res.json());
       			if(res.json().err==1){
       				this.$route.router.go("/login");
       			}else{
       				this.username=res.json().userInfo.name;
       				this.mobi=res.json().userInfo.mobile;
       			}
       		},function(){
       			
       		});        	
        }
	}
}
//		var gid=getUrlParam("id");
//		commonAjax(
//				"post",
//				"/mobi/personalCenter/get_shopDetail",
//				{gid:gid}
//		).then(function (res) {
//			if(res.err==1){
//				window.location.href="/mobi/login";
//			}else{
//			$("#thumbImg").attr("src",res.thumb);
//			$("#proImg").attr("src",res.image);
//			$("#name").text(res.name);
//			$("#proPoints").text(res.points);
//			$("#proPoints2").text(res.points);
//			}
//		},function () {
//
//		});
//
//		$(".validCode").on("click",function () {
//			if($("#phone").val()){
//				sendMsg();
//			}else{
//				mui.alert("","请先输入手机号码",function () {});
//			}
//		});
//
//		$(".proChoose span").on("click",function () {
//			var index=$(this).index();
//			$(this).addClass("on").siblings("span").removeClass("on");
//			$(".proContent").eq(index).show().siblings(".proContent").hide();
//		});
//
//		$(".cancel").on("click",function () {
//			$(".proLayer").hide();
//			$(".layer").hide();
//		});
//
//		$(".cancel2").on("click",function () {
//			$(".proInput").hide();
//			$(".layer").hide();
//		});
//
//		$(".confirm").on("click",function () {
//			$(".proLayer").hide();
//			$(".proInput").show();
//		});
//
//		$(".confirm2").on("click",function () {
//			var receiver=$("#receiver").val();
//			var phone=$("#phone").val();
//			var address=$("#address").val();
//			var vcode=$("#code").val();
//
//			commonAjax(
//					"post",
//					"/touch/sign/addOrder",
//					{gid:gid,receiver:receiver,phone:phone,address:address,vcode:vcode}
//			).then(function (res) {
//				if(res.err==0){
//					mui.alert("",res.msg,function () {
//						window.location.href="/mobi/personalCenter/enMyPoints";
//					});
//				}else if(res.err==1){
//					mui.alert("",res.msg,function () {
//						window.location.href="/mobi/login";
//					});
//				}else if(res.err==2){
//					mui.alert("",res.msg,function () {});
//				}else if(res.err==3){
//					mui.alert("",res.msg,function () {});
//				}else if(res.err==4){
//					mui.alert("",res.msg,function () {});
//				}else if(res.err==5){
//					mui.alert("",res.msg,function () {});
//				}else if(res.err==6){
//					mui.alert("",res.msg,function () {});
//				}else if(res.err==7){
//					mui.alert("",res.msg,function () {});
//				}else if(res.err==8){
//					mui.alert("",res.msg,function () {});
//				}
//				console.log(">>>",res);
//
//			},function () {
//				mui.alert("","系统错误",function () {
//					window.location.href="/mobi/login";
//				});
//			});
//		});
//
//		$(".proExchange").on("click",function () {
//			$(".proLayer").show();
//			$(".layer").show();
//			$.ajax({
//				type:"post",
//				url:"/touch/creditshop/getUserProduct",
//				dataType:'json',
//				success:function (res) {
//					if(res.err==1){
//						window.location.href="/mypp/login";
//					}else {
//						$("#receiver").val(res.userInfo.name);
//						$("#phone").val(res.userInfo.mobi);
//					}
//				},
//				error:function () {
//
//				}
//			});
//		});
</script>
