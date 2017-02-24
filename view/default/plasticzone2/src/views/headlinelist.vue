<template>
<div style="padding: 45px 0 70px 0;">
<header id="bigCustomerHeader" style="position: fixed; top: 0; left: 0;">
    <a class="back" href="javascript:window.history.back();"></a>
    	{{cate}}
</header>
<ul class="headlineUl3">
	<li v-for="i in items">
		<router-link :to="{name:'headlinedetail',params:{id:i.id}}">
			<h3>{{i.type}}{{i.title}}</h3>
			<p>{{i.description}}</p>
			<p style="text-align: right;">{{i.input_time}}</p>
		</router-link>
	</li>
</ul>
<footerbar></footerbar>
<div class="refresh" v-bind:class="{circle:isCircle}" v-on:click="circle"></div>
<div class="arrow" v-show="isArrow" v-on:click="arrow"></div>
</div>
</template>
<script>
import footer from "../components/footer";
module.exports={
	components:{
    	'footerbar':footer
    },
    data:function () {
        return {
        	items:[],
        	cate:"",
        	page:1,
        	isCircle:false,
			isArrow:false
        }
    },
    methods:{
    	arrow:function(){
			window.scrollTo(0,0);
		},
		circle:function(){
			var _this=this;
			this.isCircle=true;
			if(this.$route.params.id==999){
				$.ajax({
					type: "post",
					url: '/api/qapi1_1/getSubscribe',
					data: {
						token: window.localStorage.getItem("token"),
						subscribe:2
					},
					dataType: 'JSON'
				}).then(function(res) {
					console.log(res.data);
					if(res.err==0){
						_this.items=res.data;
						_this.isCircle=false;
						window.scrollTo(0,0);
						mui.toast('刷新成功',{
						    duration:'long',
						    type:'div' 
						}) ;

					}else{
						
					}
				}, function() {
		
				});
				
			}else{
				$.ajax({
					type: "get",
					url: '/api/qapi1/getCateList',
					data: {
						page: 1,
						size: 10,
						cate_id: _this.$route.params.id,
						token: window.localStorage.getItem("token")
					},
					dataType: 'JSON'
				}).then(function(res) {
					console.log(res);
					if(res.err==0){
						_this.items=res.info;
						_this.isCircle=false;
						window.scrollTo(0,0);
						mui.toast('刷新成功',{
						    duration:'long',
						    type:'div' 
						}) ;
					}
					
				}, function() {
		
				});				
			}

		}
    },
    activated:function () {
    	var _this=this;
		try {
			    var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
			    piwikTracker.trackPageView();
			} catch( err ) {
				
			}
    	switch(this.$route.params.id){
    		case 1:
    			this.cate="早盘预测";
    		break;
    		case 2:
    			this.cate="塑料上游";
    		break;
    		case 4:
    			this.cate="中晨塑说";
    		break;
    		case 5:
    			this.cate="美金市场";
    		break;
    		case 9:
    			this.cate="企业动态";
    		break;
    		case 11:
    			this.cate="装置动态";
    		break;
    		case 13:
    			this.cate="期刊报告";
    		break;
    		case 21:
    			this.cate="期货资讯";
    		break;
    		case 22:
    			this.cate="独家解读";
    		break;
    		case 999:
    			this.cate="推荐";
    		break;
    	}
    	
    	if(this.$route.params.id==999){
			$.ajax({
				type: "post",
				url: '/api/qapi1_1/getSubscribe',
				data: {
					token: window.localStorage.getItem("token"),
					subscribe:2
				},
				dataType: 'JSON'
			}).then(function(res) {
				console.log(res.data);
				if(res.err==0){
					_this.items=res.data
				}else{
					
				}
			}, function() {
	
			});		
    	}else{
			$.ajax({
				type: "get",
				url: '/api/qapi1/getCateList',
				data: {
					page: 1,
					size: 10,
					cate_id: _this.$route.params.id,
					token: window.localStorage.getItem("token")
				},
				dataType: 'JSON'
			}).then(function(res) {
				console.log(res);
				if(res.err==0){
					_this.items=res.info;
				}else if(res.err==1){
					mui.alert("",res.msg,function(){
						_this.$router.push({ name: 'login' });
					});			
				}
			}, function() {
	
			});
    		
    	}
    			
		$(window).scroll(function() {
            var scrollTop = $(this).scrollTop();
            var scrollHeight = $(document).height();
            var windowHeight = $(this).height();
            if(scrollTop>600){
            	_this.isArrow=true;
            }else{
            	_this.isArrow=false;
            }
            if (scrollTop + windowHeight >= scrollHeight) {
            	_this.page++;
		        $.ajax({
					type: "get",
					url: "/api/qapi1/getCateList",
					data: {
						page: _this.page,
						size: 10,
						cate_id: _this.$route.params.id,
						token: window.localStorage.getItem("token")
					},
					dataType: 'JSON'
				}).then(function(res) {
					console.log(res);
					if(res.err == 0) {
						_this.condition = true;
						_this.items=_this.items.concat(res.info);
					} else if(res.err==1){
						mui.alert("",res.msg,function(){
							_this.$router.push({ name: 'login' });
						});
					}else if(res.err == 2) {
						_this.condition = false;
					}else if(res.err==3){
						mui.toast(res.msg,{
						    duration:'long',
						    type:'div' 
						}) ;
					}
				}, function() {
		
				});
            }
    	}); 
   },
   deactivated:function(){
    	$(window).unbind('scroll');
    }

}
</script>