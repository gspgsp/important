<template>
	<div class="buyWrap">
    <header id="bigCustomerHeader">
    	<a class="back" href="javascript:window.history.back();"></a>
    	我的信用信息
    	<a class="detailShare" href="javascript:;" v-on:click="shareshow"></a>
    </header>
    <div v-show="creditshow">
    <div class="creditwrap">
    	<div class="notice"></div>
    	<h3 style=" font-size: 18px; color: #333333; text-align: center; margin: 10px 0;">热烈祝贺{{c_name}}</h3>
    	<h3 style=" font-size: 13px; color: #333333; text-align: center;">
    		获得信用<span style="color: #ff5000;">{{credit_level}}</span>
    		级客户称号/<span v-show="is_credit==='2'">预计</span>获得<span style="color: #ff5000;">{{credit_limit}}</span>万授信额度</h3>
    	<p style="color: #666666; font-size: 12px;">
    		经“我的塑料网”塑料电商交易平台信用认证，贵司企业信用良好，为{{credit_level}}级，<span v-show="is_credit==='2'">预计</span>授信额度：{{credit_limit}}万元人民币，特发此证！
    	</p>
    </div>
    <div style="text-align: center; margin: 30px 0;">
    	<div class="creditbg">
    		<div class="creditname">{{c_name}}</div>
    		<div class="credittxt">经我司评定，确认贵单位为二○一七年度信用{{credit_level}}级客户，授信额度{{credit_limit}}万人民币，有效期一年。</div>
    	</div>
    </div>
    <div class="creditbtn">
		<span class="orange" v-on:click="shareshow">分享给别人</span>&nbsp;&nbsp;&nbsp;
		<span class="green" v-on:click="toCreditintro">?授信说明</span>
	</div>
	</div>
	<div v-show="!creditshow" style="text-align: center; padding: 20px;">
    	{{msg}}
   </div>
	<div class="sharelayer" v-show="share" v-on:click="sharehide"></div>
	<div class="tip" v-show="share3"></div>
    </div>
</template>
<script>
	module.exports={
        data:function () {
            return {
            	c_name:"",
            	credit_level:"",
            	credit_limit:"",
            	user_id:"",
            	share: false,
				share3: false,
				is_credit:"",
				creditshow:true,
            	msg:""
            }
        },
        methods:{
			shareshow: function() {
				this.share = true;
				this.share3 = true;
			},
			sharehide: function() {
				this.share = false;
				this.share3 = false;
			},
			toCreditintro:function(){
				this.$router.push({name:"creditintro"});
			}
        },
        activated:function () {
        	var _this=this;
		    try {
			    var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
			    piwikTracker.trackPageView();
			} catch( err ) {
				
			}
			$.ajax({
				type: "get",
				url: "/api/qapi1_1/creditCertificate",
				data: {
					token: window.localStorage.getItem("token")
				},
				dataType: 'JSON'
			}).then(function(res) {
				if(res.err==0){
					_this.c_name=res.data.c_name;
	            	_this.credit_level=res.data.credit_level;
	            	_this.credit_limit=res.data.credit_limit/10000;
	            	_this.user_id=res.data.user_id;
	            	_this.is_credit=res.data.is_credit;
		            $.ajax({
						type:"post",
						url:"/mobi/wxShare/getSignPackage",
						data:{
							targetUrl:window.location.href,
							random:Math.random()
						},
						dataType: 'JSON'
					}).then(function(res){
						wx.config({
						debug: false,
						appId: res.signPackage.appId,
						timestamp: res.signPackage.timestamp,
						nonceStr: res.signPackage.noncestr,
						signature: res.signPackage.signature,
						jsApiList: [
							'showOptionMenu',
							'onMenuShareTimeline',
							'onMenuShareAppMessage'
						]
						});
						wx.ready(function(){
							wx.onMenuShareTimeline({
								title: "热烈祝贺"+_this.c_name+"获得企业信用等级证书"+_this.credit_limit+"万",
								link: 'http://q.myplas.com/#/credit2/' + _this.user_id,
								imgUrl: 'http://statics.myplas.com/myapp/img/shareLogo.png',
								success: function() {
			
								},
								cancel: function() {
				
								}
							});
							wx.onMenuShareAppMessage({
								title: "热烈祝贺"+_this.c_name+"获得企业信用等级证书"+_this.credit_limit+"万",
								desc: "塑料圈通讯录",
								link: 'http://q.myplas.com/#/credit2/' + _this.user_id,
								imgUrl: 'http://statics.myplas.com/myapp/img/shareLogo.png',
								type: '',
								dataUrl: '',
								success: function() {
				
								},
								cancel: function() {
				
								}
							});
						});	
						},function(){
							
						});
				}else if(res.err==2){
					_this.creditshow=false;
					_this.msg=res.msg;
					
				}
			}, function() {
		
			});
        	
        }    
	}
</script>