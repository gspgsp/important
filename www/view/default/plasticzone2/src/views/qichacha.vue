<template>
	<div class="buyWrap">
    <header id="bigCustomerHeader">
    	<a class="back" href="javascript:window.history.back();"></a>
    	查询企业信息
    </header>
    <img width="100%" src="http://statics.myplas.com/myapp/img/plasticzonecha.jpg">
    <div class="slqBanner">
    	<img width="123" height="26" style="float: left; margin: 46px 0 0 0;" src="http://statics.myplas.com/myapp/img/slqCompanyBanner.jpg" />
    	<img width="138" height="69" style="float: right;" src="http://statics.myplas.com/myapp/img/slqQueryBanner.jpg" />
    </div>
    <div class="slqCompanySearch">
		<div style=" margin: 0 60px 0 0;">
    	<input type="text" v-model="fname" placeholder="请输入公司名称" />
    	</div>
    	<div class="slqSearchBtn" v-on:click="search">查一下</div>
    </div>
    <ul class="searchli">
		<li v-for="c in creditli">
			<router-link :to="{name:'qccresult',query:{cname:c.c_name}}">{{c.c_name}}</router-link></li>
		<li>	
	</ul>
    </div>
</template>
<script>
	module.exports={
        data:function () {
            return {
				fname:"",
				creditli:[]            	
            }
        },
        methods:{
			search:function(){
				var _this=this;
				$.ajax({
					type: "post",
					url: '/api/qapi1_1/creditCertificate',
					data: {
						token: window.localStorage.getItem("token"),
						type:2,
						page:1,
						fname:_this.fname
					},
					dataType: 'JSON'
				}).then(function(res) {
					if(res.err==0){
						_this.creditli=res.data;			
					}else{
						mui.alert("", res.msg, function() {
		
						});					
					}
				}, function() {
		
				});
			}
        },
        mounted:function () {

        }    
	}
</script>