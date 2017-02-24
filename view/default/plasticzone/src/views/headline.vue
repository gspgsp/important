<template>
<div class="buyWrap" style="padding: 0 0 70px 0;">
<header id="bigCustomerHeader">
    	塑料头条
</header>
<ul class="headlineUl">
	<li><a v-link="{name:'headlinelist',params:{id:2}}"><i class="headlineicon hicon"></i><br>塑料上游</a></li>
	<li><a v-link="{name:'headlinelist',params:{id:1}}"><i class="headlineicon hicon2"></i><br>早盘预报</a></li>
	<li><a v-link="{name:'headlinelist',params:{id:9}}"><i class="headlineicon hicon3"></i><br>企业动态</a></li>
	<li><a v-link="{name:'headlinelist',params:{id:4}}"><i class="headlineicon hicon4"></i><br>中晨塑说</a></li>
	<li><a v-link="{name:'headlinelist',params:{id:5}}"><i class="headlineicon hicon5"></i><br>外盘快递</a></li>
	<li><a v-link="{name:'headlinelist',params:{id:21}}"><i class="headlineicon hicon6"></i><br>期货资讯</a></li>
	<li><a v-link="{name:'headlinelist',params:{id:11}}"><i class="headlineicon hicon7"></i><br>装置动态</a></li>
	<li><a v-link="{name:'headlinelist',params:{id:13}}"><i class="headlineicon hicon8"></i><br>期刊报告</a></li>
	<li><a v-link="{name:'headlinelist',params:{id:22}}"><i class="headlineicon hicon9"></i><br>独家解读</a></li>
</ul>
<ul class="headlineUl2">
	<li v-for="i in items">
		<a v-link="{name:'headlinedetail',params:{id:i.id}}">
			<h3 v-if="i.type!=='PUBLIC'">[{{i.type}}]{{i.title}}</h3>
			<h3 v-else>{{i.title}}</h3>
			<p>{{i.description}}</p>
			<p style="text-align: right;">{{i.input_time}}</p>
		</a>
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
        	cate:"",
        	items:[]
        }
    },
    methods:{

    },
    ready:function () {
    	var _this=this;
    	$.ajax({
    		type:"post",
    		url:"/plasticzone/plastic/getPlasticNews",
    		data:{
    			extendType:1
    		},
    		dataType: 'JSON'
    	}).then(function(res){
    		console.log(res.data);
    		_this.$set("cate",res.data.cate);
    		_this.$set("items",res.data.items);
    	},function(){
    		
    	});
    }
}
</script>