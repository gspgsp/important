<template>
<div>
<header id="bigCustomerHeader">
	<a class="back" href="javascript:window.history.back();"></a>
	积分明细
</header>
<div class="detailtitle">
	<span>{{points}}</span>积分<router-link :to="{name:'mypoints'}" class="topoints">兑换</router-link>
</div>
<div class="detailtitle2">
	积分收支明细
</div>
<ul id="detailul">
	<li v-for="d in detail">
		<span>{{d.addtime}}</span><br><b>{{d.typename}}</b>
		<strong v-bind:class="d.points>=0?'green':'red'">{{d.points}}</strong>
	</li>
</ul>
</div>
</template>
<script>
module.exports = {
	data: function() {
		return {
			detail:[],
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
    		url:"/api/qapi1/pointSupplyList",
    		data:{
    			token: window.localStorage.getItem("token"),
    			page:1,
    			size:10
    		},
    		dataType: 'JSON'
    	}).then(function(res){
    		console.log(res);
    		if(res.err==0){
 				_this.detail=res.data;
				_this.points=res.pointsAll;   			
    		}else if(res.err==1){
				mui.alert("",res.msg,function(){
					_this.$router.push({ name: 'login' });
				});        					
			}
    	},function(){
    		
    	});	
	}
}
</script>