<template>
	<div class="buyWrap">
    <header id="bigCustomerHeader">
    	<a class="back" href="javascript:window.history.back();"></a>
    	完善信息
    </header>
    <div class="registerWrap">
		<div class="registerBox">
			<input type="text" v-model="name" placeholder="请输入姓名" />
		</div>
		<div class="registerBox">
			<input type="text" v-model="c_name" placeholder="请输入公司名(全称)" />
		</div>
		<div class="registerBox">
			<input type="tel" v-model="qq" placeholder="请输入QQ号码" />
		</div>
		<div class="registerBox" style="padding: 0 10px; color: #a9a9a9">
			性别：
			<input type="radio" value="0" v-model="sexradio" checked="checked" />&nbsp;男&nbsp;
			<input type="radio" value="1" v-model="sexradio" />&nbsp;女
		</div>
		<div class="registerBox" style="height: auto; padding: 10px 0; line-height: 0; text-align: center;">
			<div class="card">
				<img v-bind:src="cardImg">
			</div>
			<div class="card2">
				<input id="upfileId" type="file" name="upFile" style="width:133px; height: 73px; opacity: 0; position: absolute; top: 0; left: 0;" v-on:change="uploadCard">
				<div class="card2upload" v-show="!cardshow"></div>
				<div class="card2success" v-show="cardshow"></div>
			</div>
		</div>

	</div>
		<div class="registerBtn">
			<input type="button" v-bind:disabled="isDisable" v-on:click="complete" value="提交" />
		</div>
    </div>
</template>
<script>
	module.exports={
        el:"#app",
        data:function () {
            return {
            	name:"",
            	c_name:"",
            	qq:"",
            	isDisable:false,
            	sexradio:0,
            	cardshow:false,
            	cardImg:""
            }
        },
        methods:{
            complete:function () {
            	var _this=this;
            	var region="";
            	this.isDisable=true;
            	//alert(window.localStorage.invite);
            	var ua = navigator.userAgent.toLowerCase();           	
				if(ua.match(/MicroMessenger/i)=="micromessenger") {
					region="wx";
				} else if(ua.match(/qq/i)=="qq") {
					region="qq";
				}else{
					region="other";
				}
				if (this.c_name.length<6) {
					mui.alert("","公司名至少填写6个字符",function(){
						_this.isDisable=false;
					});
				} else{
		            this.$http.post('/plasticzone/template/reginfo',{
		            	name:this.name,
		            	c_name:this.c_name,
		            	qq:this.qq,
		            	chanel:6,
		            	sex:this.sexradio,
		            	parent_mobile:window.localStorage.invite,
		            	region:region
		            }).then(function (res) {
		                console.log(res.json());
		                if (res.json().err==0) {
		                	mui.alert("",res.json().msg,function(){
		                		_this.$route.router.go({name:"login"});
		                	});
		                } else{
		                	mui.alert("",res.json().msg,function(){
		                		_this.isDisable=false;
		                	});
		                }
		            },function (res) {
		
		            });
                }
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
        	console.log("complete invite",window.localStorage.invite);
        }    
	}
</script>