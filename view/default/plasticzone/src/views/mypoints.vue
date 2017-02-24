<template>
<header id="bigCustomerHeader">
	<a class="back" href="javascript:window.history.back();"></a>
	我的积分
</header>
<div class="mypoints">
	<i class="iconpoints"></i><span class="points">我的积分</span><span class="pointsnum">{{points}}</span><br>
	<a v-link="{name:'pointsrecord'}">兑换记录</a><a v-link="{name:'pointsrule'}">积分规则</a><a v-link="{name:'pointsdetail'}">积分明细</a>
</div>
<h3 class="pointstitle">
	商品推荐
</h3>
<ul id="productUl">
	<li v-for="p in products">
		<a v-link="{name:'productdetail',params:{id:p.id}}">
			<img v-bind:src="p.thumb">
			<h3>{{p.name}}</h3>
			<p><span class="red">{{p.points}}</span>积分</p>
		</a>
	</li>
</ul>
</template>
<script>
module.exports = {
	data: function() {
		return {
			products:"",
			points:0
		}
	},
	methods: {

	},
	ready: function() {
		var _this = this;
		$.ajax({
    		type:"post",
    		url:"/plasticzone/plastic/getMyPointsDetail",
    		data:{
    			gtype:""
    		},
    		dataType: 'JSON'
    	}).then(function(res){
    		console.log(res);
		    if(res.err==1){
				mui.alert("","您未登录塑料圈,无法查看企业及个人信息",function(){
					_this.$route.router.go({name:"login"});
				});        					
			}else{
				_this.$set("products",res.shop.reverse());
				_this.$set("points",res.points);
			}
    	},function(){
    		
    	});
	}
}
</script>