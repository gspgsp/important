<template>
	<div class="buyWrap" style="padding: 45px 0 70px 0;">
	<div style="position: fixed; top: 0; left: 0; width: 100%; z-index: 10;">
    <header id="bigCustomerHeader">
    	<a class="back" href="javascript:window.history.back();"></a>
        	我的资料
    </header>
	</div>
	<div class="personInfo">
		<div style="width: 80px; height: 80px; position: relative; float: left;">
		<div class="personAvator">
			<input id="upAvatorId" type="file" name="upFile" style="width:80px; height: 80px; opacity: 0; position: absolute; top: 0; left: 0;" v-on:change="editAvator">
			<img width="80" height="80" v-bind:src="thumb">
		</div>
		<i class="photo"></i>
		</div>
		<div class="personName">{{name}}&nbsp;<span style="font-size: 12px; color: #63769d;">等级:{{level}}</span></div>
		<div class="personNum">{{c_name}}</div>
		<div class="personNum">电话：{{mobile}}</div>
		<div class="personNum">
			发布供给：<span style=" color: #63769d;">{{sale}}条</span> 发布需求：<span style=" color: #63769d;">{{buy}}条</span>
		</div>
		<div class="personInfoList2" style="margin: 20px 0 0 0;">
			<p><span style="color: #333333; font-size: 12px;">地址：</span>
				<font v-show="addressshow">{{address}}</font>
				<strong v-show="!addressshow" class="address">
					<input type="text" v-model="address" />
					<input type="button" value="提交" style="position: absolute; top: 3px;" v-on:click="addresssubmit" />
				</strong>
				<i class="editicon" v-on:click="edit" v-show="addressshow"></i>
			</p>
			<p><span style="color: #333333; font-size: 12px;">性别：</span>
				<font v-show="sexshow">{{sex}}</font>
				<font v-show="!sexshow">
					<input type="radio" value="0" v-model="sexradio" checked="checked" />&nbsp;男&nbsp;
					<input type="radio" value="1" v-model="sexradio" />&nbsp;女
					<input type="button" value="提交" v-on:click="sexsubmit" />
				</font>					
				<i class="editicon" v-on:click="edit2" v-show="sexshow"></i>
			</p>
			<p><span style="color: #333333; font-size: 12px;">我的主营：</span>
				<font v-show="zyshow">{{need_product}}</font>
				<strong v-show="!zyshow" class="address">
					<input type="text" v-model="need_product" />
					<input type="button" value="提交" style="position: absolute; top: 3px;" v-on:click="zysubmit" />
				</strong>
				<i class="editicon" v-show="zyshow" v-on:click="edit3"></i>
			</p>
			<p><span style="color: #333333; font-size: 12px;">我关注的牌号：</span>
				<font v-show="zyshow">{{need_product}}</font>
				<strong v-show="!zyshow" class="address">
					<input type="text" v-model="need_product" />
					<input type="button" value="提交" style="position: absolute; top: 3px;" v-on:click="zysubmit" />
				</strong>
				<i class="editicon" v-show="zyshow" v-on:click="edit3"></i>
			</p>
		</div>
		<div class="mui-content">
			<ul id="shortmsg" class="mui-table-view">
				<li class="mui-table-view-cell">
					<span style="color: #333333;">手机短信设置</span>
				</li>
				<li class="mui-table-view-cell">
					有人关注我，手机短信提醒
					<div class="mui-switch mui-switch-mini" v-on:click="msgActive" v-bind:class="{'mui-active':!active}">
						<div class="mui-switch-handle"></div>
					</div>
				</li>
				<li class="mui-table-view-cell">
					有人回复我的供求，手机短信提醒
					<div class="mui-switch mui-switch-mini" v-on:click="msgActive2" v-bind:class="{'mui-active':!active2}">
						<div class="mui-switch-handle"></div>
					</div>
				</li>
			</ul>
		</div>
		<div class="registerBox" style="height: auto; padding: 10px 0; margin: 10px 0 0 0; line-height: 0; text-align: center;">
			<div class="card">
				<img v-bind:src="cardImg">
			</div>
			<div class="card2">
				<input id="upfileId" type="file" name="upFile" style="width:133px; height: 73px; opacity: 0; position: absolute; top: 0; left: 0;" v-on:change="uploadCard">
				<div class="card2upload" v-show="!cardshow"></div>
				<div class="card2success" v-show="cardshow"></div>
			</div>
			<div class="personInfoList2">
				<div style=" font-size: 13px; color: #8f8f94; line-height: 20px; text-align: left; border-top: 1px solid #d1d1d1; padding: 10px 15px 0 15px;">通讯录排序:您目前排在通讯录的第{{rank}}位，共{{total}}人，按照粉丝数量、发布求购数量、发布供给数量进行排序</div>
			</div>
		</div>
	</div>
    <footerbar></footerbar>
   </div>
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
            	rank:"",
            	total:"",
            	sexshow:true,
            	addressshow:true,
            	zyshow:true,
            	sexradio:0,
            	cardshow:false,
            	cardImg:"",
            	active:"",
            	active2:"",
            	level:""
            }
        },
        methods:{
        	msgActive:function(){
        		this.active==0?this.active=1:this.active=0;
        		
        		this.$http.post('/plasticzone/plastic/favorateSet',{
					type:0,is_allow:this.active
				}).then(function(res){
					console.log(res.json());
					mui.alert("",res.json().msg,function(){
						
					});        										
				},function(res){
					
				});        		

        	},
        	msgActive2:function(){
        		this.active2==0?this.active2=1:this.active2=0;
        		
        		this.$http.post('/plasticzone/plastic/favorateSet',{
					type:1,is_allow:this.active2
				}).then(function(res){
					console.log(res.json());
					mui.alert("",res.json().msg,function(){

					});        										
				},function(res){
					
				}); 
        	},
        	edit:function(){
        		this.addressshow=false;
        	},
        	edit2:function(){
        		this.sexshow=false;
        	},
        	addresssubmit:function(){
				this.$http.post('/plasticzone/plastic/saveSelfInfo',{
					type:1,field:this.address
				}).then(function(res){
					console.log(res.json());
					mui.alert("",res.json().msg,function(){
						window.location.reload();
					});        										
				},function(res){
					
				});        		
        	},
        	sexsubmit:function(){
        		this.$http.post('/plasticzone/plastic/saveSelfInfo',{
        			type:2,field:this.sexradio
        		}).then(function(res){
        			console.log(res.json());
        			mui.alert("",res.json().msg,function(){
	        			window.location.reload();
	        		});        					
        		},function(){
        			
        		});
        	},
        	zysubmit:function(){
				this.$http.post('/plasticzone/plastic/saveSelfInfo',{
					type:3,field:this.need_product
				}).then(function(res){
        			mui.alert("",res.json().msg,function(){
	        			window.location.reload();
	        		});        					
				},function(res){
					
				});        		
        	},
        	edit3:function(){
        		this.zyshow=false;
        	},
        	editAvator:function(){
        		var _this=this;
        		$.ajaxFileUpload({
					url:'/plasticzone/plastic/savePicToServer',
						secureuri:false,
						fileElementId:'upAvatorId',
						dataType:'json',
						success: function (res) {
							console.log(res);
		                	if(res.err==0){
								mui.alert("","上传成功",function(){
									window.location.reload();
			                	});
		                		
		                	}else{
								mui.alert("","上传失败",function(){
									
			                });		                		
		                	}
							
						},
						error: function (data,status,e){
							
						}
				});
        },
        uploadCard:function(){
            	var _this=this;
        		$.ajaxFileUpload({
					url:'/plasticzone/plastic/saveCardImg',
						secureuri:false,
						fileElementId:'upfileId',
						dataType:'json',
						success: function (res) {
							console.log(res);
		                	if(res.err==0){
								mui.alert("","上传成功",function(){
									_this.cardImg=res.url;
			                	});
		                		
		                	}else{
								mui.alert("","上传失败",function(){
									
			                });		                		
		                	}
							
						},
						error: function (data,status,e){
							
						}
				});
            }

        },
        ready:function () {
        	var _this=this;
            this.$http.post('/plasticzone/plastic/getSelfInfo',{

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
	            	this.$set('rank',res.json().data.rank);
	            	this.$set('total',res.json().data.total);
	            	this.$set('cardImg',res.json().data.thumbcard);
	            	this.$set('active',res.json().data.allow_send.focus);
	            	this.$set('active2',res.json().data.allow_send.repeat);
	            	this.$set('level',res.json().data.member_level);
        		}
            },function (res) {

            });
            
        }
    
	}
</script>