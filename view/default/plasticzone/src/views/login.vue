<template>
	<div class="buyWrap">
    <header id="bigCustomerHeader">
    	<a class="back" href="javascript:window.history.back();"></a>
    	登录
    </header>
    <div class="registerWrap">
			<div class="registerBox">
				<input id="username" class="registerInput" maxlength="11" type="tel" v-model="mobile" placeholder="请输入手机号码">
			</div>
			<div class="registerBox">
				<input id="password" class="registerInput" maxlength="20" type="password" v-model="pwd" placeholder="请输入密码">
			</div>
	</div>
	<div style=" padding: 0 20px">
		<input type="checkbox" v-model="checked" />&nbsp;<label style="color: #999999;">记住密码</label>
	</div>
		<div class="registerBtn">
			<input type="button" v-on:click="login" value="登录" />
		</div>
		<div class="loginLink" style="margin: 0 20px;">
			<a v-link="{name:'register'}" style="float: left;">注册</a>
			<a v-link="{name:'resetpwd'}" style="float: right;">忘记密码</a>
		</div>
    </div>
</template>
<script>
	module.exports={
        el:"#app",
        data:function () {
            return {
            	mobile:"",
            	pwd:"",
            	checked:false
            }
        },
        methods:{
            login:function () {
            	var _this=this;
            	
    		    if (this.checked) {
    		    	window.localStorage.setItem("username",this.mobile);
    		    	window.localStorage.setItem("password",this.pwd);
            	} else{
    		    	window.localStorage.setItem("username","");
    		    	window.localStorage.setItem("password","");            		
            	}

	            this.$http.post('/plasticzone/login/login',{username:this.mobile,password:this.pwd}).then(function (res) {
	                console.log(res.json());
	                if (res.json().err==0) {
	                	window.localStorage.setItem("userid",res.json().user_id);	
						this.$http.post('/plasticzone/plastic/getMemberLevel',{}).then(function (res) {
				            console.log(res.json());
				        },function (res) {
				
				        });	
			        	window.localStorage.tel="";
			        	window.localStorage.tel = res.json().mobile;
	                	_this.$route.router.go({name:"index"});
	                }else if(res.json().err==6){
	                	mui.alert("","您未登录塑料圈,无法查看企业及个人信息",function(){
	                		_this.$route.router.go({name:"register"});
	                	});	                	
	                }else{
	                	mui.alert("",res.json().msg,function(){
	                		
	                	});
	                }
	            },function (res) {
	
	            });
            }
        },
        ready:function () {
        	var lousername=window.localStorage.getItem("username")||"";
        	var lopassword=window.localStorage.getItem("password")||"";
        	this.mobile=lousername;
        	this.pwd=lopassword
        		if(lousername!==""&&lopassword!==""){
        			this.checked=true;
        		}
	            this.$http.post('/plasticzone/plastic/isUserLogin',{}).then(function (res) {
	                console.log(res.json());
	                if (res.json().err==0) {
	                	this.$route.router.go({name:"index"});
	                }
	            },function (res) {
	
	            });
        }
	}
</script>