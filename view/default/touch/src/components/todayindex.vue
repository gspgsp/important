<template>
	<div class="buyWrap">
    <header id="bigCustomerHeader">
    	行情指数
    </header>
    <h3 class="buyTitle">
    	{{time}}
    </h3>
    <ul class="indexUl">
    	<li v-for="var in variety">
    		<span style="width: 60%;">{{var.cid}}</span>
    		<span style="width: 22%; text-align: center;">{{var.price}}</span>
    		<span class="{{ var.rof|upDown }}" style="width: 18%; text-align: center;">{{var.rof|plusMinus}}{{var.rof}}</span>
    	</li>
   	</ul>
    <ul class="indexUl">
    	<li v-for="o in oil">
    		<span style="width: 60%;">{{o.type}}</span>
    		<span style="width: 22%; text-align: center;">{{o.price}}</span>
    		<span class="{{ o.float|upDown }}" style="width: 18%; text-align: center;">{{o.float|plusMinus}}{{o.ups_downs}}</span>
    	</li>
    </ul>
    <h3 class="buyTitle" style="text-align: center; color: #ff5000;">
    	中晨交易指数
    </h3>
    <div class="siteIndex">
    	<span>供应总量<br><i>{{service0}}</i>吨</span>
    	<span>需求总量<br><i>{{service1}}</i>吨</span>
    </div>
    <p class="siteIndex2">交易总额<br><i>{{service2}}</i>元</p>
    <footer id="footer">
    	<div class="releaseSale">
    		<textarea placeholder="写下您真实的需求,包括牌号，吨数等，收到后我们会立即给您电话确认，剩下的交给我们吧"></textarea>
    	</div>
    	<div class="releaseBtn" v-on:click="sendMsg">免费<br>委托<br>发布</div>
    	<div class="border-bottom"></div>
        <ul>
            <li><a v-link="{name:'buy'}"><i class="foot"></i><br>我要买货</a></li>
            <li><a v-link="{name:'sale'}"><i class="foot2"></i><br>我要卖货</a></li>
            <li><a v-link="{name:'price'}"><i class="foot3"></i><br>实时价格</a></li>
            <li><a v-link="{name:'todayindex'}" class="footerOn"><i class="foot4"></i><br>行情指数</a></li>
        </ul>
    </footer>
    </div>
</template>
<script>
	module.exports={
        el:"#app",
        data:function () {
            return {
            	variety:[],
            	oil:[],
            	time:"",
            	service0:"",
            	service1:"",
            	service2:""
            }
        },
        methods:{
        	sendMsg:function(){
            	if(!this.content){
            		mui.alert("","请填写委托内容",function(){
            			
            		});
            	}else{
	            	this.$http.post('/touch/newPage/release',{content:this.content}).then(function(res){
	            		console.log(res.json());
	            		mui.alert("",res.json().msg,function(){
            				window.location.reload();
            			});
	            	},function(){
	            		
	            	});
            	}
            }
        },
        ready:function () {
        	this.$http.post('/touch/newPage/todayIndex', {}).then(function(res){
        		console.log(res.json());
        		this.$set('oil',res.json().oilPrice);
        		this.$set('variety',res.json().market);
        		this.$set('time',res.json().today);
        		this.$set('service0',res.json().service[0]);
        		this.$set('service1',res.json().service[1]);
        		this.$set('service2',res.json().service[2]);
        	},function(res){
        		
        	})
        }
	}
</script>
