<template>
<header id="meHeader">
	<a class="back" href="javascript:window.history.back();"></a>
	邮箱
</header>
<div class="meSetInput">
	<span>邮箱</span>
	<input id="email" type="email" v-model="email" placeholder="请输入新邮箱" />
</div>
<a class="me_enter" @click="enter()" href="javascript:;">确定</a>
</template>
<script>
module.exports={
	el:"#app",
	data:function(){
		return {
			email:""
		}
	},
	ready:function(){
		
	},
	methods:{
		enter:function(){
			var _this=this;
			this.$http.post("/mobi/personalCenter/saveEmail",{email:this.email}).then(function(res){
				console.log(res.json());
				if(res.json().err==1){
					this.$route.router.go("/login");
				}else{
					mui.alert('',res.json().msg,function(){
						_this.$route.router.go("/melogged");
					});
				}
			},function(){
				
			});
		}		
	}
}
</script>
