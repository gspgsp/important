<template>
<header id="bigCustomerHeader">
    <a class="back" href="javascript:window.history.back();"></a>
    	{{cate}}
</header>
<div style="padding: 0 0 70px 0;">
<ul class="headlineUl3">
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
        	items:[],
        	cate:"",
        	page:1
        }
    },
    methods:{

    },
    ready:function () {
    	var _this=this;
    	switch(this.$route.params.id){
    		case "1":
    			this.cate="早盘预测";
    		break;
    		case "2":
    			this.cate="塑料上游";
    		break;
    		case "4":
    			this.cate="中晨塑说";
    		break;
    		case "5":
    			this.cate="外盘快递";
    		break;
    		case "9":
    			this.cate="企业动态";
    		break;
    		case "11":
    			this.cate="装置动态";
    		break;
    		case "13":
    			this.cate="期刊报告";
    		break;
    		case "21":
    			this.cate="期货资讯";
    		break;
    		case "22":
    			this.cate="独家解读";
    		break;
    	}
    	$.ajax({
    		type:"post",
    		url:"/plasticzone/plastic/getPlasticNews",
    		data:{
    			extendType:1,
    			page:1,
    			size:10,
    			cateId:_this.$route.params.id
    		},
    		dataType: 'JSON'
    	}).then(function(res){
    		console.log(res);
  			_this.$set("items",res.data);
    	},function(){
    		
    	});
    	
    	$(window).scroll(function() {
	            var scrollTop = $(this).scrollTop();
	            var scrollHeight = $(document).height();
	            var windowHeight = $(this).height();
	            if (scrollTop + windowHeight == scrollHeight) {
	            	_this.page++;
				    $.ajax({
			    		type:"post",
			    		url:"/plasticzone/plastic/getPlasticNews",
			    		data:{
			    			extendType:1,
			    			page:_this.page,
			    			size:10,
			    			cateId:_this.$route.params.id
			    		},
			    		dataType: 'JSON'
			    	}).then(function(res){
			    		console.log(res);
			  			if(res.err==3){
	        				mui.toast(res.msg);
	        			}else{
	        				_this.items=_this.items.concat(res.data);
	        			}
			    	},function(){
			    		
			    	});
	            }
        });      	
    },
    destroyed:function(){
    	$(window).unbind('scroll');
    }  

}
</script>