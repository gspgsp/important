<template>
<div>
<header id="bigCustomerHeader">
	<a class="back" href="javascript:window.history.back();"></a>
	购买记录
</header>
<ul v-if="condition" id="pointsrecord">
	<li v-for="r in record">
		<div class="recordwrap">
			<img v-bind:src="r.thumb">
			<div class="recordinfo">
				<p>{{r.remark}} {{r.num}}张</p>
				<p style="font-size: 12px; color: #999999;">购买日期:{{r.create_time}}</p>
				<p style=" height:19px; font-size: 12px; color: #999999; overflow: hidden;">使用日期:{{r.address}}</p>
				<p v-if="r.type==2" style=" height:19px; font-size: 12px; color: #999999; overflow: hidden;">置顶人：{{r.name}}</p>
				<p v-if="r.type==1" style=" height:19px; font-size: 12px; color: #999999; overflow: hidden;">置顶供求信息：{{r.contents}}</p>
			</div>
		</div>
		<div class="recordstatus">
			<span>总计：<b style="font-weight: normal; font-size: 12px; color: #ff5000;">{{r.usepoints}}塑豆</b></span>
		</div>
	</li>
</ul>
<div v-else class="releaseMsg">
	<div class="releaseMsgHead2"></div>
	<div class="releaseTxt">没有相关数据</div>
</div>
</div>
</template>
<script>
export default{
	data: function() {
		return {
			record:[],
			condition:true
		}
	},
	activated: function() {
		var _this = this;
		try {
		    var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
		    piwikTracker.trackPageView();
		} catch( err ) {
			
		}
		$.ajax({
    		type:"post",
    		url:version+"/product/getPurchaseRecord",
    		data:{
    			token: window.localStorage.getItem("token"),
    			page:1,
    			size:30    			
    		},
			headers: {
				'X-UA': window.localStorage.getItem("XUA")
			},
    		dataType: 'JSON'
    	}).then(function(res){
    		console.log(res);
		    if(res.err==0){
		    	_this.condition=true;
		    	_this.record=res.data;
			}else if(res.err==2){
				_this.condition=false;
			}
    	},function(){
    		
    	});
		
	}
}
</script>