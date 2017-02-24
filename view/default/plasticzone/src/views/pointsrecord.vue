<template>
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
</template>
<script>
module.exports = {
	data: function() {
		return {
			record:[]
		}
	},
	methods: {

	},
	ready: function() {
		var _this = this;
		$.ajax({
    		type:"post",
    		url:"/plasticzone/plastic/getCreditRecord",
    		data:{
    			
    		},
    		dataType: 'JSON'
    	}).then(function(res){
    		console.log(res);
		    if(res.err==1){
				mui.alert("","您未登录塑料圈,无法查看企业及个人信息",function(){
					_this.$route.router.go({name:"login"});
				});        					
			}else if(res.err==0){
				_this.$set("record",res.record);
			}else if(res.err==2){
				_this.record=[];
			}
    	},function(){
    		
    	});
		
	}
}
</script>