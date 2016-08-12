<template>
<header id="meHeader">
	<a class="back" href="javascript:window.history.back();"></a>
	设置
</header>
<ul class="meUl2">
	<li>姓名<span id="name">{{name}}</span></li>
	<li>公司<span id="address">{{c_name}}</span></li>
</ul>
<ul class="meUl2">
	<li><a href="/mobi/personalCenter/enPassword">修改密码<img src="../../img/classifyBack.png"></a></li>
	<li><a href="/mobi/personalCenter/enEmail">电子邮箱<img src="../../img/classifyBack.png"><span id="email">{{email}}</span></a></li>
	<li><a href="/mobi/personalCenter/enQq">QQ号码<img src="../../img/classifyBack.png"><span id="qq">{{qq}}</span></a></li>
</ul>
<ul class="meUl2">
	<li>当前版本<span>1.10</span></li>
</ul>
</template>
<script>
module.exports={
	el:"#app",
	data:function(){
		return {
			name:"",
			email:"",
			qq:"",
			c_name:""
		}
	},
	ready:function(){
		this.$http.post('/mobi/personalCenter/getMySet',{}).then(function(res){
			console.log(res.json());
			this.name=res.json().data.name;
			this.email=res.json().data.email;
			this.qq=res.json().data.qq;
			this.c_name=res.json().data.c_name;
		},function(){
			
		});
	}
}
</script>
