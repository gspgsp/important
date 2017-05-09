<template>
	<div class="buyWrap" style="padding: 45px 0 0 0;">
	<div style="position: fixed; top: 0; left: 0; width: 100%; z-index: 10;">
    <header id="bigCustomerHeader">
    	<a class="back" href="javascript:window.history.back();"></a>
        	我的消息
    </header>
	</div>
	<div class="mymsg2choose">
		<span v-bind:class="{'on':0==tabIndex}" v-on:click="toggleTab(0)">我接收的消息</span>
		<span v-bind:class="{'on':1==tabIndex}" v-on:click="toggleTab(1)">我发送的消息</span>
	</div>
	<ul class="mymsg2ul" v-show="0==tabIndex">
		<li v-for="r in resMsg">
			<div class="myreleaseInfo">
	    		<div class="avator">
					<img v-bind:src="r.thumb">
				</div>
				<div class="myreleasetxt3">
					<p>{{r.send_name}}<br>{{r.c_name}}<span>{{r.input_time}}</span></p>
					<div class="mymsgwrap">
						<div class="triangle-left"></div>
						{{r.content}}
						<div style="overflow: hidden;">
							<span style="float: right;" v-on:click="reply(r.send_id)"><i class="myicon3 iconSupply5"></i>回复</span>
						</div>				
					</div>
				</div>
	    	</div>
		</li>
	</ul>
	<ul class="mymsg2ul" v-show="1==tabIndex">
		<li v-for="s in sendMsg">
			<div class="myreleaseInfo">
	    		<div class="avator">
					<img v-bind:src="s.thumb">
				</div>
				<div class="myreleasetxt3">
					<p>&nbsp;<span>{{s.input_time}}</span></p>
					<div class="mymsgwrap">
						<div class="triangle-left"></div>
						{{s.content}}
					</div>
				</div>
	    	</div>
		</li>
	</ul>
    </div>
    
    <div class="releasebox" v-show="show">
    	<div style="padding: 10px;">
    		<textarea placeholder="请填写回复内容" v-model="content"></textarea>
    		<div style="text-align: center;">
	    		<input type="button" v-on:click="replyMag" value="回复" />
	    		<input type="button" v-on:click="cancel" value="取消" />
    		</div>
    	</div>
    </div>
    <div class="layer" v-show="show"></div>
</template>
<script>
	module.exports={
        el:"#app",
        data:function () {
            return {
            	revMsg:[],
            	sendMsg:[],
            	tabIndex:0,
            	show:false,
            	user_id:"",
            	content:""
            }
        },
        methods:{
        	toggleTab:function(index){
        		this.tabIndex=index;
        	},
        	reply:function(userid){
        		this.show=true;
	        	this.user_id=userid;        			
        		console.log(this.user_id);
        	},
        	replyMag:function(){
        		var _this=this;
        		if (this.content) {
        			this.$http.post('/plasticzone/plastic/sendZoneContactMsg',{
        				content:this.content,
        				userId:this.user_id
        			}).then(function(res){
        				console.log(res.json());
        				if (res.json().err==0) {
        					mui.alert("",res.json().msg,function(){
        						window.location.reload();
        					});        					
        				} else if(res.json().err==1){
        					mui.alert("","您未登录塑料圈,无法查看企业及个人信息",function(){
        						_this.$route.router.go({name:"login"});
        					});        					
        				}else{
        					mui.alert("",res.json().msg,function(){
								window.location.reload();								
        					});
        				}
        			},function(){
        				
        			});
        		} else{
        			mui.alert("","请填写回复内容",function(){
        				
        			});
        		}
        	},
        	cancel:function(){
        		this.show=false;
        	}
        },
        ready:function () {

        	this.$http.post('/plasticzone/plastic/getZoneContactMsg',{
        		type:1,page:1,size:100
        	}).then(function(res){
        		console.log(res.json());
        		this.$set("resMsg",res.json().data);
        	},function(res){
        		
        	});
        	
        	this.$http.post('/plasticzone/plastic/getZoneContactMsg',{
        		type:2,page:1,size:100
        	}).then(function(res){
        		console.log(res.json());
        		this.$set("sendMsg",res.json().data);
        	},function(res){
        		
        	});

        }
	}
</script>