<template>
	<div class="buyWrap">
    <header id="bigCustomerHeader">
    	<a class="back" href="javascript:window.history.back();"></a>
    	重置密码
    </header>
	<div class="registerWrap">
		<div class="registerBox">
			<input id="username" class="registerInput" maxlength="11" v-model="mobile" type="tel" placeholder="请输入手机号码">
		</div>
		<div class="registerBox">
			<input id="password" class="registerInput" maxlength="20" v-model="password" type="password" placeholder="请输入新密码">
		</div>
		<div class="registerBox">
			<input id="regcode" class="registerInput" maxlength="6" v-model="code" type="tel" placeholder="请输入6位验证码">
			<button class="validCode" v-on:click="sendCode">{{validCode}}</button>
		</div>
		</div>
		<div class="registerBtn">
			<input type="button" v-on:click="resetPwd" value="重置" />
		</div>
	</div>
</template>
<script>
	module.exports={
        el:"#app",
        data:function () {
            return {
            	mobile:"",
            	password:"",
            	code:"",
            	times:60,
            	validCode:"获取验证码"
            }
        },
        methods:{
        	sendCode:function(){
        		var _this=this;
        		if (this.mobile&&this.password) {
	            	this.$http.post('/mobi/findPwd/sendmsg',{
		            	mobile:this.mobile,password:this.password
		            }).then(function (res) {
						console.log(">>>",res);
						if(res.json().err==0){
							mui.alert('',res.json().msg,function(){});
							var countStart=setInterval(function(){
								_this.validCode=_this.times-- +'秒后重发';
								console.log(">>>",_this.times);
								//$(".validCode").attr("disabled",true);
								if(_this.times<0){
									clearInterval(countStart);
									_this.validCode="获取验证码";
									//$(".validCode").attr("disabled",false);
								}
							},1000);
						}else if(res.err==1){
							mui.alert('',res.json().msg,function(){});
						}
		            },function (res) {
		
		            });
        			
        		} else{
        			mui.alert("","请填写手机号和密码",function(){
        				
        			});
        		}
        		        		
        	},
            resetPwd:function(){
            	var _this=this;
            	if (this.mobile&&this.password&&this.code) {
            		
	            	this.$http.post('/mobi/findPwd/finfMyPwd',{
		            	mobile:this.mobile,password:this.password,code:this.code
		            }).then(function (res) {
		            	console.log(res.json());
						if(res.json().err==0){
							mui.alert('',res.json().msg,function(){
								_this.$route.router.go({name:"login"});
							});							
						}else if(res.json().err==1){
							mui.alert('',res.json().msg,function(){
								
							});
						}	            	
		            },function (res) {
		
		            });
            		
            	} else{
            		mui.alert("","请填写手机号、密码和验证码",function(){
            			
            		});
            	}

            }
        },
        ready:function () {
            
        }
    
	}
</script>