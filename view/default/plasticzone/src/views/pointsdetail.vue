<template>
<div>
<header id="bigCustomerHeader">
	<a class="back" href="javascript:window.history.back();"></a>
	我的塑豆
</header>
<div class="detailtitle">
	总塑豆 <span>{{points}}</span>
</div>
<div class="detailtitle2">
	塑豆收支明细
</div>
<table id="sdTable" cellpadding="0" cellspacing="0">
	<tr>
		<th width="20%">塑豆</th>
		<th width="50%" style="text-align: left;">描述</th>
		<th width="30%">时间</th>
	</tr>
	<tr v-for="d in detail">
		<td><span style=" color:#ff5000;">{{d.points}}</span></td>
		<td style="text-align: left;">{{d.typename}}</td>
		<td>{{d.addtime}}</td>
	</tr>
</table>
</div>
</template>
<script>
export default{
	data: function() {
		return {
			detail:[],
			points:0
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
    		url:version+"/score/scoreRecord",
    		data:{
    			token: window.localStorage.getItem("token"),
    			page:1,
    			size:50
    		},
			headers: {
				'X-UA': window.localStorage.getItem("XUA")
			},
    		dataType: 'JSON'
    	}).then(function(res){
    		console.log(res);
    		if(res.err==0){
 				_this.detail=res.data;
				_this.points=res.pointsAll;   			
    		}else if(res.err==1){
				weui.alert(res.msg, {
					title: '塑料圈通讯录',
					buttons: [{
						label: '确定',
						type: 'parimary',
						onClick: function() {
							_this.$router.push({ name: 'login' });
						}
					}]
				});       					      					
			}
    	},function(){
    		
    	});	
	}
}
</script>