<template>
	<div class="buyWrap" style="padding: 0 0 70px 0;">
    <header id="bigCustomerHeader">
        	我的塑料圈
        <a class="headerMenu2" href="javascript:;" v-on:click="shareshow"></a>
    </header>
    <div class="myzoneHeader">
    	<div class="myzoneInfo">
    		<div style="width: 55px; height: 55px; margin: 0; float: left; position: relative;">
    		<div class="avator">
				<img v-bind:src="thumb">
			</div>
			<i class="iconV" v-bind:class="{'v1':is_pass==1,'v2':is_pass==0}"></i>
			</div>
			<div class="myzonetxt">
				<p>{{c_name}}</p>
				<p>{{name}} {{mobile}}</p>
				<p><a v-link="{name:'myinfo'}" style="color: #6b6767;"><span style="float: left;">更改用户信息</span><i class="iconinfo edit"></i>上传名片加V认证</a></p>
			</div>
			<div class="mui-clearfix"></div>
    	</div>
    	<div class="myzonenum">
    		<span><a v-link="{name:'mysupply'}">{{supply}}<br>供给</a></span>
    		<span><a v-link="{name:'mybuy'}">{{buy}}<br>求购</a></span>
    		<span><a v-link="{name:'mymsg'}">{{msg}}<br>留言</a></span>
    		<span><a v-link="{name:'myinvite'}">{{invite}}<br>引荐</a></span>
    		<span><a v-link="{name:'myfans'}">{{fans}}<br>粉丝</a></span>
    		<span><a v-link="{name:'mypay'}">{{pay}}<br>关注</a></span>
    		<span>{{points}}<br>积分</span>
    	</div>
    </div>
    <ul class="myzoneUl">
    	<li>
    		<a v-link="{name:'mysupply'}"><i class="iconZone zone"></i>我的供给<span>{{supply}}</span>
    		<i class="icon2 rightArrow" style="right: 0; top: 8px;"></i></a>
    	</li>
    	<li>
    		<a v-link="{name:'mybuy'}"><i class="iconZone zone2"></i>我的求购<span>{{buy}}</span>
    		<i class="icon2 rightArrow" style="right: 0; top: 8px;"></i></a>
    	</li>
    </ul>
    <ul class="myzoneUl">
    	<li><a v-link="{name:'myinvite'}"><i class="iconZone zone4"></i>我的引荐<i class="icon2 rightArrow" style="right: 0; top: 8px;"></i><span>{{invite}}</span></a></li>
    	<li><a v-link="{name:'myfans'}"><i class="iconZone zone5"></i>我的粉丝<i class="icon2 rightArrow" style="right: 0; top: 8px;"></i><span>{{fans}}</span></a></li>
    	<li><a v-link="{name:'mypay'}"><i class="iconZone zone6"></i>我的关注<i class="icon2 rightArrow" style="right: 0; top: 8px;"></i><span>{{pay}}</span></a></li>
    </ul>
    <ul class="myzoneUl">
    	<li>
    		<a v-link="{name:'mymsg'}"><i class="iconZone zone3"></i>我的留言<span>未读留言{{msg}}</span>
    		<i class="icon2 rightArrow" style="right: 0; top: 8px;"></i></a>
    	</li>
    	<li>
    		<a v-link="{name:'mymsg2'}"><i class="iconZone zone9"></i>我的消息<span>未读消息{{msg2}}</span>
    		<i class="icon2 rightArrow" style="right: 0; top: 8px;"></i></a>
    	</li>    	
    </ul>
    <ul class="myzoneUl">
    	<li>
    		<a v-link="{name:'mypoints'}"><i class="iconZone zone7"></i>我的积分
    		<span>可兑换礼品,通讯录,供求信息置顶</span><i class="icon2 rightArrow" style="right: 0; top: 8px;"></i></a>
    	</li>
    </ul>
    <ul class="myzoneUl">
    	<li>
    		<a v-link="{name:'help'}"><i class="iconZone zone10"></i>帮助<span>常见问题及联系客服</span>
    		<i class="icon2 rightArrow" style="right: 0; top: 8px;"></i></a>
    	</li>
    </ul>
    <ul class="myzoneUl">
    	<li style="text-align: center; color: #ff5000;" v-on:click="logout">
    		退出登录
    	</li>
    </ul>
   	<footerbar></footerbar>
    </div>
    <div class="sharelayer" v-show="share" v-on:click="sharehide"></div>
    <div class="tip" v-show="share3"></div>
</template>
<script>
var footer=require("../components/footer");
	module.exports={
        components:{
        	'footerbar':footer
        },
        data:function () {
            return {
            	buy:"",
            	supply:"",
            	points:"",
            	fans:"",
            	pay:"",
            	invite:"",
            	msg:"",
            	msg2:"",
            	c_name:"",
            	name:"",
            	mobile:"",
            	thumb:"",
            	share:false,
            	share3:false,
            }
        },
        methods:{
        	shareshow:function(){
        		this.share=true;
        		this.share3=true;
        	},
        	sharehide:function(){
        		this.share=false;
        		this.share3=false;
        		this.share4=false;
        	},
        	logout:function(){
        		var _this=this;
	        	this.$http.post('/mobi/personalCenter/logOut',{
	       
	        	}).then(function(res){
	        		console.log(res.json());
	        		if(res.json().err==0){
		        		mui.alert("",res.json().msg,function(){
							_this.$route.router.go({name:"index"});
						});        					
	        		}
	        	},function(){
	        		
	        	});
        		
        	}
        },
        ready:function () {
        	var _this=this;
        	        	
        	this.$http.post('/plasticzone/plastic/getSaleCount',{
        		type:1
        	}).then(function(res){
        		console.log(res.json());
        		this.$set("buy",res.json().s_b_count)
        	},function(){
        		
        	});
        	
        	this.$http.post('/plasticzone/plastic/getSaleCount',{
        		type:2
        	}).then(function(res){
        		console.log(res.json());
        		this.$set("supply",res.json().s_b_count)
        	},function(){
        		
        	});
        	
        	this.$http.post('/plasticzone/plastic/getMyPoints',{
        		
        	}).then(function(res){
        		console.log(res.json());
        		this.$set("points",res.json().points)
        	},function(){
        		
        	});
        	
        	this.$http.post('/plasticzone/plastic/getMsgCount',{
        		type:1
        	}).then(function(res){
        		console.log(res.json());
        		this.$set("msg",res.json().count)
        	},function(){
        		
        	});
        	
        	this.$http.post('/plasticzone/plastic/getMsgCount',{
        		type:2
        	}).then(function(res){
        		console.log(res.json());
        		this.$set("msg2",res.json().count)
        	},function(){
        		
        	});

        	this.$http.post('/plasticzone/plastic/getMyIntroduction',{
        		
        	}).then(function(res){
        		console.log(res.json());
        		this.$set("invite",res.json().count)
        	},function(){
        		
        	});

        	this.$http.post('/plasticzone/plastic/getMyFuns',{
        		type:1
        	}).then(function(res){
        		console.log(res.json());
        		this.$set("fans",res.json().count)
        	},function(){
        		
        	});

        	this.$http.post('/plasticzone/plastic/getMyFuns',{
        		type:2
        	}).then(function(res){
        		console.log(res.json());
        		this.$set("pay",res.json().count)
        	},function(){
        		
        	});
        	
        	console.log(">>>",$("#head_img_wx").val());
        	this.$http.post('/plasticzone/plastic/getMyPlastic',{
        		headimgurl:$("#head_img_wx").val()
        	}).then(function(res){
        		console.log(res.json());
        		if(res.json().err==1){
					mui.alert("","您未登录塑料圈,无法查看企业及个人信息",function(){
						_this.$route.router.go({name:"login"});
					});        					
				}else{
	        		this.$set("name",res.json().data.name);
	        		this.$set("c_name",res.json().data.c_name);
	        		this.$set("mobile",res.json().data.mobile);
	        		this.$set("thumb",res.json().data.thumb);
	        		this.$set("is_pass",res.json().data.is_pass);
				}
        	},function(){
        		
        	});

			wx.onMenuShareTimeline({
				title: "塑料圈-塑料人的行业通讯录", 
				link: 'http://m.myplas.com/plasticzone/plastic#!/index?invite='+tel, 
				imgUrl: 'http://statics.myplas.com/myapp/img/shareLogo.png', 
				success: function () {
					
				},
				cancel: function () {
					
				}
			});
			wx.onMenuShareAppMessage({
				title: "塑料圈通讯录",
				desc: "塑料圈-塑料人的行业通讯录 认识那么久啦！ 加入塑料圈通讯录 关注我吧",
				link: 'http://m.myplas.com/plasticzone/plastic#!/index?invite='+tel, 
				imgUrl: 'http://statics.myplas.com/myapp/img/shareLogo.png', 
				type: '', 
				dataUrl: '', 
				success: function () {
					
				},
				cancel: function () {
					
				}
			});
			wx.onMenuShareQQ({
				title: "塑料圈通讯录",
				desc: "塑料圈-塑料人的行业通讯录 认识那么久啦！ 加入塑料圈通讯录 关注我吧",
				link: 'http://m.myplas.com/plasticzone/plastic#!/index?invite='+tel, 
				imgUrl: 'http://statics.myplas.com/myapp/img/shareLogo.png', 
				success: function () {
					
				},
				cancel: function () {
					
				}
			});

        }
    
	}
</script>