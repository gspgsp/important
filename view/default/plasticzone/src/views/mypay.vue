<template>
	<div class="buyWrap" style="padding: 45px 0 70px 0;">
	<div style="position: fixed; top: 0; left: 0; width: 100%; z-index: 10;">
    <header id="bigCustomerHeader">
    	<a class="back" href="javascript:window.history.back();"></a>
        	我的关注
    </header>
	</div>
	<myrelation v-bind:name="name" v-bind:condition="condition"></myrelation>
    <footerbar></footerbar>
    </div>
</template>
<script>
var footer=require("../components/footer");
var myrelation=require("../components/myrelation");
	module.exports={
        components:{
        	'footerbar':footer,
        	'myrelation':myrelation
        },
        data:function () {
            return {
            	name:[],
            	page:1,
            	condition:true
            }
        },
        methods:{

        },
        ready:function () {
        	var _this=this;
        	$(window).scroll(function() {
	            var scrollTop = $(this).scrollTop();
	            var scrollHeight = $(document).height();
	            var windowHeight = $(this).height();
	            
	            if (scrollTop + windowHeight == scrollHeight) {
	            	_this.page++;
		           	_this.$http.post('/plasticzone/plastic/getMyFuns',{
	        			type:2,page:_this.page,size:10
	        		}).then(function(res){
	        			console.log(res.json());
	        			if(res.json().err==3){
	        				mui.toast(res.json().msg);
	        			}else if(res.json().err==1){
	        				mui.alert("",res.json().msg,function(){
	        					_this.$route.router.go({name:"login"});
	        				});
	        			}else{
	        				_this.name=_this.name.concat(res.json().data);
	        			}
	        			
	        		},function(){
	        			
	        		});

	            }
        	});      	
        	
            this.$http.post('/plasticzone/plastic/getMyFuns',{
            	type:2,page:this.page,size:10
            }).then(function (res) {
            	console.log(res.json());
            	if (res.json().err==2) {
            		this.condition=false;
            	}else if(res.json().err==1){
    				mui.alert("","请先登录",function(){
    					_this.$route.router.go({name:"login"});
    				});
            	}else{
            		this.condition=true;
            		this.$set('name',res.json().data);
            	}
            },function (res) {

            });
            
        },
        destroyed:function(){
        	$(window).unbind('scroll');
        }  
	}
</script>