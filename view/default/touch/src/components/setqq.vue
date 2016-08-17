<template>
<header id="meHeader">
	<a class="back" href="javascript:window.history.back();"></a>
	QQ
</header>
<div class="meSetInput">
	<span>QQ</span>
	<input id="qq" type="tel" v-model="qq" placeholder="请输入QQ号" />
</div>
<a class="me_enter" @click="enter()" href="javascript:;">确定</a>
</template>
<script>
module.exports={
	el:"#app",
	data:function(){
		return {
			qq:""
		}
	},
	ready:function(){

	},
	methods:{
		enter:function(){
			var _this=this;
			this.$http.post("/mobi/personalCenter/saveQq",{qq:this.qq}).then(function(res){
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
