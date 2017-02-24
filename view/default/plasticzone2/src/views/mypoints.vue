<template>
<div>
<header id="bigCustomerHeader">
	<a class="back" href="javascript:window.history.back();"></a>
	我的积分
</header>
<div class="mypoints">
	<i class="iconpoints"></i><span class="points">我的积分</span><span class="pointsnum">{{points}}</span><br>
	<router-link :to="{name:'pointsrecord'}">兑换记录</router-link>
	<router-link :to="{name:'pointsrule'}">积分规则</router-link>
	<router-link :to="{name:'pointsdetail'}">积分明细</router-link>
</div>
<h3 class="pointstitle">
	商品推荐
</h3>
<ul id="productUl">
	<li v-for="p in products">
		<router-link :to="{name:'productdetail',params:{id:p.id}}">
			<img v-bind:src="p.thumb">
			<h3>{{p.name}}</h3>
			<p><span class="red">{{p.points}}</span>积分</p>
		</router-link>
	</li>
</ul>
</div>
</template>
<script>
module.exports = {
	data: function() {
		return {
			products:"",
			points:0
		}
	},
	mounted: function() {
		var _this = this;
			try {
	    var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
	    piwikTracker.trackPageView();
	} catch( err ) {
		
	}
		$.ajax({
    		type:"get",
    		url:"/api/qapi1/getProductList",
    		data:{
    			token: window.localStorage.getItem("token"),
    			page:1,
    			size:10
    		},
    		dataType: 'JSON'
    	}).then(function(res){
    		console.log(res);
		    if(res.err==1){
				mui.alert("",res.msg,function(){
					_this.$router.push({ name: 'login' });
				});        					
			}else{
				_this.products=res.info;
				_this.points=res.pointsAll;
			}
    	},function(){
    		
    	});	
	}
}
</script>