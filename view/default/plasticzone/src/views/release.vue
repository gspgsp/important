<template>
	<div style=" padding: 45px 0 70px 0;">
	<div style="position: fixed; top: 0; left: 0; width: 100%; z-index: 6;">
	    <header id="bigCustomerHeader">
	        	供求信息
	        <a class="rightMenu2" v-on:click="showSearch"></a>
	        <a class="rightMenu" v-link="{name:'releasebs'}"></a>
	    </header>
	</div>
		<div id="searchbox" v-show="searchShow" style="padding: 10px; background: #eeeeee; position: relative;">
			<div class="searchSearch2">
				<form v-on:submit="search">
					<input id="search" type="text" placeholder="请输入厂家或牌号" v-model="keywords">
					<a class="appQuery" href="javascript:;" v-on:click="search"></a>
				</form>
			</div>
			<select v-model="selected">
				<option selected v-bind:value="">全部</option>
				<option v-bind:value="2">供给</option>
				<option v-bind:value="1">求购</option>
			</select>
		</div>

    <ul id="releaseUl">
    	<li v-for="r in release">
    		<a v-link="{name:'personinfo',params:{id:r.user_id}}">
	    	<div class="myreleaseInfo">
	    		<div style="width: 40px; height: 40px; float: left; position: relative;">
	    		<div class="avator">
					<img v-bind:src="r.thumb">
				</div>
				<i class="iconV" v-bind:class="{'v1':r.is_pass==1,'v2':r.is_pass==0}"></i>
				</div>
				<div class="myreleasetxt">
					<p><b>{{r.name}}</b><font></font></p>
					<p>{{r.c_name}}</p>
				</div>
	    	</div>
			</a>
	    	<div class="myreleasetxt2">
	    		<p>
		    		<font v-if="r.type==2" style=" color: #63769d;">
		    			<i class="iconSale"></i>供给</font>
		    		<font v-else style="color: #ea8010;">
		    			<i class="iconBuy"></i>
		    			求购</font>
		    		<font>{{{r.contents}}}</font>	    			
	    		</p>
	    		<p style="margin: 0;">
	    			<b>{{r.input_time}}</b>
	    			<span v-on:click="reply2(r.id,r.user_id)"><i class="myicon3 iconSupply5"></i>回复</span>
	    			<div class="triangle-up" v-if="r.says.length!==0"></div>
	    		</p>
	    		<div class="replyRelease" v-if="r.says.length!==0">
	    			<p v-for="s in r.says" v-if="r.user_id==s.rev_id">
	    				<a v-link="{name:'personinfo',params:{id:s.user_id}}">{{s.user_name}}</a>:<strong v-on:click="reply(r.id,s.user_id,s.id)">{{s.content}}</strong>
	    			</p>
	    			<p v-for="s in r.says" v-if="r.user_id!==s.rev_id">
	    				<a v-link="{name:'personinfo',params:{id:s.user_id}}">{{s.user_name}}</a>回复<a v-link="{name:'personinfo',params:{id:s.rev_id}}">{{s.rev_name}}</a>:<strong v-on:click="reply(r.id,s.user_id,s.id)">{{s.content}}</strong>
	    			</p>	    			
	    		</div>
	    	</div>
	    	
    	</li>
    </ul>
    <footerbar></footerbar> 
    <div class="releasebox" v-show="show">
    	<div style="padding: 10px;">
    		<textarea placeholder="请填写回复内容" v-model="content"></textarea>
    		<div style="text-align: center;">
	    		<input type="button" v-on:click="replyMag" value="回复" />
	    		<input type="button" v-on:click="cancel" value="取消" />
    		</div>
    	</div>
    </div>
    <div class="layer" v-show="show"></div>
</template>
<script>
var footer=require("../components/footer");
	module.exports={
		components:{
        	'footerbar':footer
        },
        data:function () {
            return {
            	keywords:"",
            	page:1,
            	release:[],
            	type:2,
            	store_house:"",
            	model:"",
            	f_name:"",
            	deal_price:"",
            	remark:"",
            	show:false,
            	content:"",
            	id:"",
            	user_id:"",
            	countShow:false,
            	count:"",
            	selected:"",
            	searchShow:false
            }
        },
        methods:{
        	showSearch:function(){
        		this.menushow=false;
        		this.searchShow=true;        		
        	},
        	reply:function(id,userid,delid){
        		var _this=this;
        		if (userid==localStorage.getItem("userid")) {
        			mui.confirm('', '确定删除此信息？', ['取消', '确定'], function(e) {
	                    if (e.index == 1) {
		                    _this.$http.post('/plasticzone/plastic/deleteRepeat',{
			        			id:delid
			        		}).then(function(res){
			        			console.log(res.json());
			        			if(res.json().err==0){
		        					window.location.reload();        				
			        			}else if(res.json().err==1){
			        				_this.$route.router.go({name:"login"});
			        			}else{
			        				mui.alert("","删除失败",function(){
		        					
		        					});
			        			}		        			
			        		},function(){
			        			
			        		});
	                    } else {
	                        
	                    }
                });

        		} else{
	        		this.show=true;
        		}
        		this.id=id;
	        	this.user_id=userid;        			
        		console.log(this.id);
        		console.log(this.user_id);
        	},
        	reply2:function(id,userid){
        		this.show=true;
        		this.id=id;
	        	this.user_id=userid;
        	},
        	replyMag:function(){
        		var _this=this;
        		this.show=false;
        		if (this.content) {
        			this.$http.post('/plasticzone/plastic/saveMsg',{
        				pur_id:this.id,
        				content:this.content,
        				send_id:this.user_id
        			}).then(function(res){
        				console.log(res.json());
        				if (res.json().err==0) {
        					mui.alert("",res.json().msg,function(){
        						window.location.reload();
        					});        					
        				} else if(res.json().err==1){
        					mui.alert("","您未登录塑料圈,无法查看企业及个人信息",function(){
        						_this.$route.router.go({name:"login"});
        					});        					
        				}else{
        					mui.alert("",res.json().msg,function(){
								window.location.reload();								
        					});
        				}
        			},function(){
        				
        			});
        		} else{
        			mui.alert("","请填写回复内容",function(){
        				
        			});
        		}
        	},
        	cancel:function(){
        		this.show=false;
        	},
        	search:function(){
        		this.page=1;
		        	this.$http.post('/plasticzone/plastic/getReleaseMsg', {
		        		keywords:this.keywords.toUpperCase(),type:this.selected,page:this.page,size:10
		        	}).then(function(res){
		        		console.log(res.json());
		        		this.$set('release',res.json().data);
		        	},function(res){
		        		_this.$route.router.go({name:"login"});
		        	})
        	}
        },
        ready:function () {
        	var _this=this;
        	console.log("userid",localStorage.getItem("userid"));
        	$(window).scroll(function() {
        		_this.menushow=false;
	            var scrollTop = $(this).scrollTop();
	            var scrollHeight = $(document).height();
	            var windowHeight = $(this).height();
	            if (scrollTop + windowHeight == scrollHeight) {
	            	_this.page++;
		           	_this.$http.post('/plasticzone/plastic/getReleaseMsg',{
		           		keywords:_this.keywords.toUpperCase(),type:_this.selected,page:_this.page,size:10
	        		}).then(function(res){
	        			console.log(res.json());
	        			if(res.json().err==3){
	        				mui.toast(res.json().msg);
	        			}else if(res.json().err==1){
	        				mui.alert("","您未登录塑料圈,无法查看企业及个人信息",function(){
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
        	
        	console.log(this.type);
        	var _this=this;
        	this.$http.post('/plasticzone/plastic/getReleaseMsg', {
        		keywords:this.keywords,page:this.page,size:10
        	}).then(function(res){
        		console.log(res.json());
        		if(res.json().err==1){
        			mui.alert("","您未登录塑料圈,无法查看企业及个人信息",function(){
						_this.$route.router.go({name:"login"});
					});        					
        		}else{
        			this.$set('release',res.json().data);
        		}       		
        	},function(res){
        		
        	})
        },
        destroyed:function(){
        	$(window).unbind('scroll');
        }  

	}
</script>
