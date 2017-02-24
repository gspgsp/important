<template>
	<div class="buyWrap" style="padding: 90px 0 60px 0;">
	<div style="position: fixed; top: 0; left: 0; width: 100%; z-index: 10;">
    <header id="bigCustomerHeader">
    	<a class="headerMenu4" href="http://a.app.qq.com/o/simple.jsp?pkgname=com.myplas.q"></a>
        	塑料圈通讯录({{num}}人)
        <a class="headerMenu" v-link="{name:'login'}"></a>
    </header>
    <div class="indexsearch">
    	<div class="indexsearchwrap">
    		<form>
    		<i class="searchIcon"></i><input v-on:keydown.enter="search" type="text" placeholder="请输入公司、姓名、牌号查询" v-model="keywords"/>
    		</form>
    	</div> 	
    </div>
	</div>
	<ul id="nameUl">
		<li v-show="condition" v-for="n in name">
			<div style="width: 55px; height: 55px; float: left; position: relative;">
				<div class="avator">
					<img v-bind:src="n.thumb">
				</div>
				<i class="iconV" v-bind:class="{'v1':n.is_pass==1,'v2':n.is_pass==0}"></i>
			</div>
			<div class="nameinfo">
				<a v-link="{name:'personinfo',params:{id:n.user_id}}">
					<p class="first"><i class="icon wxGs"></i>{{n.c_name}}</p>
					<p class="second"><i class="icon wxName"></i>{{n.name}}&nbsp;{{n.sex}}&nbsp;<span>供给:{{n.sale_count}} 求购:{{n.buy_count}}</span>&nbsp;<span>粉丝:{{n.fans}} 等级:{{n.member_level}}</span></p>
					<p class="second">主营：{{n.need_product}}</p>
					<i class="icon2 rightArrow"></i>
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
        el:"#app",
        components:{
        	'footerbar':footer
        },
        data:function () {
            return {
            	name:[],
            	letter:"",
            	keywords:"",
            	page:1,
            	condition:true,
            	countShow:false,
            	num:""
            }
        },
        methods:{
        	send:function(n){
        		this.page=1;
        		this.keywords="";
        		this.letter=n;
        		this.$http.post('/plasticzone/plastic/getPlasticPerson',{
            		letter:this.letter,keywords:this.keywords.toLocaleUpperCase(),page:this.page,size:10
	            }).then(function (res) {
	            	console.log(res.json());
	            	if (res.json().err==2) {
	            		this.condition=false;
	            	} else{
	            		this.condition=true;
	            		this.$set('name',res.json().persons);
	            	}
	            },function (res) {
	
	            });
        	},
        	search:function(){
        		this.letter="";
        		this.page=1;
        		if (this.keywords) {
	        		this.$http.post('/plasticzone/plastic/getPlasticPerson',{
	            		letter:this.letter,keywords:this.keywords.toLocaleUpperCase(),page:this.page,size:10
		            }).then(function (res) {
		            	console.log(res.json());
		            	this.$set('name',res.json().persons);
		            },function (res) {
		
		            });        		       			
        		} else{
        			window.location.reload();

        		}
        	}
        },
        ready:function () {
        	var _this=this;
        	$(window).scroll(function() {
	            var scrollTop = $(this).scrollTop();
	            var scrollHeight = $(document).height();
	            var windowHeight = $(this).height();
	            if (scrollTop + windowHeight == scrollHeight) {
	            	_this.page++;
		           	_this.$http.post('/plasticzone/plastic/getPlasticPerson',{
	        			letter:_this.letter,keywords:_this.keywords.toLocaleUpperCase(),page:_this.page,size:10
	        		}).then(function(res){
	        			console.log(res.json());
	        			if(res.json().err==3){
	        				mui.toast(res.json().msg);
	        			}else if(res.json().err==1){
	        				mui.alert("",res.json().msg,function(){
	        					_this.$route.router.go({name:"login"});
	        				});
	        			}else{
	        				_this.name=_this.name.concat(res.json().persons);
	        			}
	        			
	        		},function(){
	        			
	        		});

	            }
        	});      	
        	
        	this.$http.post('/plasticzone/plastic/getAllMembers',{}).then(function(res){
        		console.log(res.json());
        		this.$set('num',res.json().count);
        	},function(res){
        		
        	});

        	
            this.$http.post('/plasticzone/plastic/getPlasticPerson',{
            	letter:this.letter,keywords:this.keywords.toLocaleUpperCase(),page:this.page,size:10
            }).then(function (res) {
            	console.log(res.json());
            	if (res.json().err==2) {
            		this.condition=false;
            	} else{
            		this.condition=true;
            		this.$set('name',res.json().persons);
            	}
            },function (res) {

            });
			window.localStorage.invite=this.$route.query.invite;			
        },
        destroyed:function(){
        	$(window).unbind('scroll');
        }  
	}
</script>