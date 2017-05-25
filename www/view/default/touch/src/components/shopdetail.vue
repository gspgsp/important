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
		<p><span>联系地址:</span><input id="address" class="proText" v-model="address" type="text"></p>
		<p style="position: relative;">
			<span>验证码:</span><input id="code" style="font-size: 12px; width: 100px;" class="proText2" type="text" v-model="code" placeholder="输入验证码">
			<input type="button" class="validCode" @click='sendMsg()'  v-model="btnVal" style="width: 72px; border-radius: 0; background: #ff5000; height: 25px; right: 25px; line-height: 25px; position: absolute; top: 0;">
		</p>
		<p style="text-align: center; margin: 20px 0 0 0;">
			<input type="button" class="cancel2" @click="cancel()" value="取消">
			<input type="button" class="confirm2" @click="confirm2()" style="background: #ff5000;" value="确定">
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
			mobi:"",
			btnVal:"获取验证码",
			times:60,
			status:false,
			code:"",
			address:""
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
        },
        sendMsg:function(){
        	var _this=this;        	
        	this.$http.post('/touch/sign/sendmsg',{'mobile':this.mobi}).then(function(res){
        		console.log(res.json());
        		if(res.json().err!=0){
        			mui.alert("",res.json().msg,function () {
	
					});
        		}else{
        			var countStart=setInterval(function(){
						_this.btnVal=_this.times--+'秒后重发';
						if(_this.times<0){
							clearInterval(countStart);
							_this.btnVal='获取验证码';
							_this.times=60;
						}
					},1000); 
        		}
        	},function(){
        		
        	});
        },
        confirm2:function(){
        	this.$http.post('/touch/sign/addOrder',{'gid':this.$route.query.id,'receiver':this.username,'phone':this.mobi,'address':this.address,'vcode':this.code}).then(function(res){
				console.log(res.json());
				if(res.err==0){
					mui.alert("",res.json().msg,function () {
						this.$route.router.go('/mycreditdetail');
					});
				}else if(res.err==1){
					mui.alert("",res.json().msg,function () {
						this.$route.router.go('/login');
					});
				}else{
					mui.alert("",res.json().msg,function () {
						window.location.reload();
					});
				}          		
        	},function(){
        		
        	});
        }
	}
}
</script>
