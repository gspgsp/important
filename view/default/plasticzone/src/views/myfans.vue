<template>
	<div class="buyWrap" style="padding: 45px 0 70px 0;">
	<div style="position: fixed; top: 0; left: 0; width: 100%; z-index: 10;">
    <header id="bigCustomerHeader">
    	<a class="back" href="javascript:window.history.back();"></a>
        	我的粉丝
    </header>
	</div>
	    <ul id="nameUl">
		<li v-show="condition" v-for="n in name">
			<div style="width: 55px; height: 55px; float: left; position: relative;">
				<div class="avator">
					<img v-bind:src="n.user_id.thumb">
				</div>
				<i class="iconV" v-bind:class="{'v1':n.user_id.is_pass==1,'v2':n.user_id.is_pass==0}"></i>
			</div>
			<div class="nameinfo">
				<a v-link="{name:'personinfo',params:{id:n.user_id.user_id}}">
					<p class="first"><i class="icon wxGs"></i>{{n.user_id.c_name}}</p>
					<p class="second"><i class="icon wxName"></i>{{n.user_id.name}}<i class="icon wxMobile"></i>{{n.user_id.mobile}}</p>
					<p class="second">&nbsp;发布供给：<span>{{n.user_id.sale}}</span>条    发布求购：<span>{{n.user_id.buy}}</span>条</p>
				</a>
			</div>
		</li>
		<li v-show="!condition" style="text-align: center;">
			没有相关数据
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
	        			type:1,page:_this.page,size:10
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
            	type:1,page:_this.page,size:10
            }).then(function (res) {
            	console.log(res.json());
            	if (res.json().err==2) {
            		this.condition=false;
            	} else{
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