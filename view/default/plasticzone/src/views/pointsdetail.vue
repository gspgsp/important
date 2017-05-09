<template>
<header id="bigCustomerHeader">
	<a class="back" href="javascript:window.history.back();"></a>
	积分明细
</header>
<div class="detailtitle">
	<span>{{points}}</span>积分<a v-link="{name:'mypoints'}" class="topoints">兑换</a>
</div>
<div class="detailtitle2">
	积分收支明细
</div>
<ul id="detailul">
	<li v-for="d in detail">
		<span>{{d.addtime}}</span><br><b>{{d.type}}</b>
		<strong v-bind:class="d.points>=0?'green':'red'">{{d.points}}</strong>
	</li>
</ul>
</template>
<script>
module.exports = {
	data: function() {
		return {
			detail:[],
			points:0
		}
	},
	methods: {

	},
	ready: function() {
		var _this = this;
		$.ajax({
    		type:"post",
    		url:"/plasticzone/plastic/getPlasticCreditDetail",
    		data:{
    			
    		},
    		dataType: 'JSON'
    	}).then(function(res){
    		console.log(res);
		    if(res.err==1){
				mui.alert("","您未登录塑料圈,无法查看企业及个人信息",function(){
					_this.$route.router.go({name:"login"});
				});        					
			}else{
				_this.$set("detail",res.detail);
				_this.$set("points",res.points);
			}
    	},function(){
    		
    	});
	}
}
</script>