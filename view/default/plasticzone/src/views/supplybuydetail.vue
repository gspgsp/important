<template>
	<div class="buyWrap" style="padding: 45px 0 70px 0;">
	<div style="position: fixed; top: 0; left: 0; width: 100%; z-index: 6;">
    <header id="bigCustomerHeader">
    	<a class="headerMenu" v-link="{name:'index'}"></a>
        	Ta的报价
    </header>
    </div>
    <div class="releaseHead">
    	<div style="width: 80px; height: 80px; float: left; position: relative;">
    	<div class="avator2">
			<img v-bind:src="thumb">
		</div>
		</div>
		<div class="nameinfo2">
			<p class="first">{{name}}</p>
			<p class="second">{{c_name}}</p>
			<p class="second">电话：{{mobile}}</p>
		</div>
    </div>
    <ul class="releaseTa">
    	<li>
    		<font style="color: #999999;">{{time}}</font><br>
    		<font><i class="myicon2 iconSupply"></i>报价</font>
    		<font style="color: #333333; line-height: 23px;">{{contents}}</font>
    	</li>
    </ul>
    <footerbar></footerbar>
    </div>
</template>
<script>
var footer=require("../components/footer");
	module.exports={
        components:{
        	'footerbar':footer
        },
        data:function () {
            return {          	
            	name:"",
            	c_name:"",
            	mobile:"",
            	thumb:"",
            	time:"",
            	contents:""
            }
        },
        ready:function () {
        	var _this=this; 
        	console.log(this.$route.params.id);
        	$.ajax({
	    		type:"post",
	    		url:"/plasticzone/plastic/shareMyPur",
	    		data:{
	    			id:_this.$route.params.id
	    		},
	    		dataType: 'JSON'
	    	}).then(function(res){
	    		console.log(res);
			    if(res.err==1){
					mui.alert("","您未登录塑料圈,无法查看企业及个人信息",function(){
						_this.$route.router.go({name:"login"});
					});        					
				}else{
					_this.$set("name",res.data.name);
					_this.$set("c_name",res.data.c_name);
					_this.$set("thumb",res.data.thumb);
					_this.$set("mobile",res.data.mobile);
					_this.$set("time",res.data.input_time);
					_this.$set("contents",res.data.content||res.data.contents);
				}
	    	},function(){
	    		
	    	});
	    	
        }
	}
</script>
