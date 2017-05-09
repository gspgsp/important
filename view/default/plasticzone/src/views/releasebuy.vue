<template>
	<div class="buyWrap" style="padding: 45px 0 70px 0;">
	<div style="position: fixed; top: 0; left: 0; width: 100%; z-index: 6;">
    <header id="bigCustomerHeader">
    	<a class="back" href="javascript:window.history.back();"></a>
        	查看Ta的求购
    </header>
    </div>
    <div class="releaseHead">
    	<div style="width: 80px; height: 80px; float: left; position: relative;">
    	<div class="avator2">
			<img v-bind:src="thumb">
		</div>
		<i class="iconV" v-bind:class="{'v1':is_pass==1,'v2':is_pass==0}"></i>
		</div>
		<div class="nameinfo2">
			<p class="first">{{name}}&nbsp;{{sex}}&nbsp;</p>
			<p class="second">{{c_name}}</p>
			<p class="second">电话：{{mobile}}</p>
		</div>
    </div>
    <ul class="releaseTa">
    	<li v-show="condition" v-for="r in release">
    		<font style="color: #999999;">{{r.input_time}}</font><br>
    		<font v-if="r.type==2"><i class="myicon2 iconSupply"></i>供给</font>
    		<font v-else>求购</font>
    		<font style="color: #333333; line-height: 23px;">{{r.contents}}</font>
    	</li>
    	<li v-show="!condition" style="text-align: center; line-height: 50px;">
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
            	page:1,            	
            	type:"",
            	show:false,
            	id:"",
            	user_id:"",
            	countShow:false,
            	count:"",
            	name:"",
            	c_name:"",
            	mobile:"",
            	thumb:"",
            	sex:"",
            	release:[],
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
		           	_this.$http.post('/plasticzone/plastic/getTaPur',{
		           		userid:_this.$route.params.id,page:_this.page,size:10,type:2
	        		}).then(function(res){
	        			console.log(res.json());
	        			if(res.json().err==3){
	        				mui.toast(res.json().msg);
	        			}else if(res.json().err==1){
	        				mui.alert("",res.json().msg,function(){
	        					_this.$route.router.go({name:"login"});
	        				});
	        			}else{
	        				_this.release=_this.release.concat(res.json().data);
	        			}
	        			
	        		},function(){
	        			
	        		});

	            }
        	});      	

        	
        	this.$http.post('/plasticzone/plastic/getMsgCount',{}).then(function(res){
        		console.log(res.json());
        		if(res.json().count==0){
        			this.countShow=false;
        		}else{
        			this.countShow=true;
        			this.count=res.json().count;
        			
        		}
        	},function(res){
        		
        	});
        	
        	console.log(this.$route.params.id);
        	this.$http.post('/plasticzone/plastic/getTaPur', {
        		userid:this.$route.params.id,page:this.page,size:10,type:1
        	}).then(function(res){
        		console.log(res.json());
        		if(res.json().err==1){
        			_this.$route.router.go({name:"login"});
        		}else if(res.json().err==2){
        			this.condition=false;
        		}else{
        			this.condition=true;
        			this.$set('release',res.json().data);
        		}       		
        	},function(res){
        		
        	});
        	       	       
            this.$http.post('/plasticzone/plastic/getZoneFriend',{
            	userid:this.$route.params.id
            }).then(function (res) {
            	console.log(res.json());
            	if(res.json().err==1){
        			mui.alert("","您未登录塑料圈,无法查看企业及个人信息",function(){
        				_this.$route.router.go({name:"login"});
        			});        					
        		}else{
	            	this.$set('name',res.json().data.name);
	            	this.$set('c_name',res.json().data.c_name);
	            	this.$set('mobile',res.json().data.mobile);
	            	this.$set('thumb',res.json().data.thumb);
	            	this.$set('sex',res.json().data.sex);
	            	this.$set('is_pass',res.json().data.is_pass);
        		}
            },function (res) {

            });

        	
        },
        destroyed:function(){
        	$(window).unbind('scroll');
        }  

	}
</script>
