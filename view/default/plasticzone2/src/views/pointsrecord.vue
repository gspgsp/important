<template>
<div>
<header id="bigCustomerHeader">
	<a class="back" href="javascript:window.history.back();"></a>
	兑换记录
</header>
<ul id="pointsrecord">
	<li v-for="r in record">
		<div class="record">
			<h3 class="recordtitle">
				兑换单号：{{r.order_id}}<br>兑换时间：{{r.create_time}}
				<span>{{r.status}}</span>
			</h3>
		</div>
		<div class="recordwrap">
			<img v-bind:src="r.thumb">
			<div class="recordinfo">
				{{r.name}}
			</div>
		</div>
		<div class="recordstatus">
			更新时间:{{r.update_time}}
			<span>兑换使用积分：<b>{{r.usepoints}}</b>积分</span>
		</div>
	</li>
</ul>
</div>
</template>
<script>
module.exports = {
	data: function() {
		return {
			record:[]
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
    		url:"/api/qapi1/exchangeList",
    		data:{
    			token: window.localStorage.getItem("token"),
    			page:1,
    			size:10    			
    		},
    		dataType: 'JSON'
    	}).then(function(res){
    		console.log(res);
		    if(res.err==0){
		    	_this.record=res.info;
			}else if(res.err==1){
				mui.alert("",res.msg,function(){
					_this.$router.push({ path: 'login' });
				});        									
			}
    	},function(){
    		
    	});
		
	}
}
</script>