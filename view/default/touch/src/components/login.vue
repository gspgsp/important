<template>
    <header id="meHeader">
        <a class="back" href="/mobi/mainPage/enMainPage"></a>
        登录
        <a class="right" href="/mobi/register/init">注册</a>
    </header>
    <h3 class="meTitle">欢迎来到我的塑料网</h3>
    <div class="registerWrap">
        <div class="registerBox">
            <img src="__MYAPP__/img/username.jpg">
            <input id="username" class="registerInput" maxlength="11" type="tel" v-model="mobi" placeholder="请输入手机号码">
        </div>
        <div class="registerBox">
            <img src="__MYAPP__/img/pwd.jpg">
            <input id="password" class="registerInput" maxlength="20" type="password" v-model="pwd" placeholder="请输入密码">
        </div>
    </div>
    <div style="margin: 0 20px;">
        <div class="registerBtn" @click="enterLogin()">登录</div>
        <div class="loginLink">
            <a href="/mobi/findPwd/init" style="float: right;">忘记密码?</a>
        </div>
    </div>
</template>
<script>
	module.exports={
        el:"#login",
        data:function () {
            return {
                mobi:"",
                pwd:""
            }
        },
        methods:{
            enterLogin:function(){
                console.log(">>>",this.mobi);
                this.$http({url:'/mobi/login/login',method:"POST",data:{username:this.mobi,password:this.pwd}}).then(function (res) {
                    console.log(res);
                    if(res.data.err==0){
                        router.go('/meLogged');
                    }else if(res.data.err==1){
                        mui.alert('',res.data.msg,function(){});
                    }

                },function () {

                })
            }
        }
	}
</script>