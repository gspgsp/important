<template>
	<div class="buyWrap" style="padding: 45px 0 70px 0;">
	<div style="position: fixed; top: 0; left: 0; width: 100%; z-index: 10;">
    <header id="bigCustomerHeader">
    	<a class="back" href="javascript:window.history.back();"></a>
        	查看个人信息
    </header>
	</div>
	<div class="personInfo">
		<div style="float: left; width: 100%; margin: 0 0 17px 0;">
		<div style=" width: 80px; height: 80px; margin: 0 15px 0 0; position: relative; float: left;">
		<div class="personAvator" v-on:click="check">
			<img v-bind:src="thumb">
		</div>
		<i class="iconV" v-bind:class="{'v1':is_pass==1,'v2':is_pass==0}"></i>
		</div>
		<div class="personName" style="margin: 20px 0 0 0;">
			{{name}}&nbsp;{{sex}}
			<span class="orange" v-on:click="pay">{{status}}</span>
			<span class="red" v-on:click="reply(id)">发消息</span>
		</div>
		<div class="personNum" style="margin: 5px 0 0 0;">
			<span>发布供给：<span style=" color: #63769d;">{{sale}}条</span></span>
			<span>发布需求：<span style=" color: #63769d;">{{buy}}条</span></span>
		</div>
		</div>
		<div class="personInfoList">
			<p>公司：{{c_name}}</p>
			<p>地址：{{address}}</p>
			<p>联系电话：{{mobile}}<a class="telephone" href="tel:{{mobile}}"></a></p>
			<p style="border-bottom: 1px solid #D1D1D1;">我的主营：{{need_product}}</p>
			<div class="registerBox" style="height: auto; padding: 10px 0; margin: 0; line-height: 0; text-align: center;">
				<div class="card" v-on:click="cardcheck">
					<img v-bind:src="cardImg">
				</div>
			</div>
		</div>
	</div>
    <footerbar></footerbar>
    </div>
    <div class="imgLayer" v-show="avatorCheck" v-on:click="check">
    	<div class="avatorCheck" style="background: url('{{thumb}}') no-repeat center; background-size: contain;"></div>
    </div>
    <div class="imgLayer" v-show="cardCheck" v-on:click="cardcheck">
    	<div class="avatorCheck" style="background: url('{{cardImg}}') no-repeat center; background-size: contain;"></div>
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
var footer=require("../components/footer");
	module.exports={
        components:{
        	'footerbar':footer
        },
        data:function () {
            return {
            	name:"",
            	buy:"",
            	sale:"",
            	c_name:"",
            	mobile:"",
            	address:"",
            	sex:"",
            	status:"",
            	thumb:"",
            	need_product:"",
            	id:"",
            	avatorCheck:false,
            	cardCheck:false,
            	show:false,
            	user_id:"",
            	content:"",
            	is_pass:"",
            	cardImg:""
            }
        },
        methods:{
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
        	},
        	check:function(){
        		this.avatorCheck==true?this.avatorCheck=false:this.avatorCheck=true;
        	},
        	cardcheck:function(){
        		this.cardCheck==true?this.cardCheck=false:this.cardCheck=true;
        	},
        	pay:function(){
	            this.$http.post('/plasticzone/plastic/focusOrCancel',{
	            	focused_id:this.$route.params.id
	            }).then(function (res) {
	            	console.log(res.json());
	            	if(res.json().err==1){
	        			mui.alert("",res.json().msg,function(){
	        				_this.$route.router.go({name:"login"});
	        			});        					
	        		}else{
		            	window.location.reload();
	        		}
	            },function (res) {
	
	            });
        		
        	}
        },
        ready:function () {
        	var _this=this;
            this.$http.post('/plasticzone/plastic/getZoneFriend',{
            	userid:this.$route.params.id
            }).then(function (res) {
            	console.log(res.json());
            	if(res.json().err==1){
        			mui.alert("","您未登录塑料圈,无法查看企业及个人信息",function(){
        				_this.$route.router.go({name:"login"});
        			});        					
        		}else{
	            	this.$set('name',res.json().data.name);
	            	this.$set('c_name',res.json().data.c_name);
	            	this.$set('address',res.json().data.address);
	            	this.$set('mobile',res.json().data.mobile);
	            	this.$set('need_product',res.json().data.need_product);
	            	this.$set('status',res.json().data.status);
	            	this.$set('thumb',res.json().data.thumb);
	            	this.$set('buy',res.json().data.buy);
	            	this.$set('sale',res.json().data.sale);
	            	this.$set('sex',res.json().data.sex);
	            	this.$set('id',res.json().data.user_id);
	            	this.$set('is_pass',res.json().data.is_pass);
	            	this.$set('cardImg',res.json().data.thumbcard);
        		}
            },function (res) {

            });

        }
    
	}
</script>