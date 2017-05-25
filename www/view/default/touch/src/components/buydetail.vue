<template>
	<div class="buyWrap">
    <header id="bigCustomerHeader">
        <a class="back" href="javascript:window.history.back();"></a>
        	我要买货
    </header>
    <h3 class="buyTitle">
    	{{product_type}} {{model}}
    </h3>
    <div class="buyDetail">
    	<p><i>￥{{unit_price}}元/吨</i></p>
    	<p><span>数量:{{number}}吨</span><span>厂商:{{f_name}}</span></p>
    	<p><span>交货地点:{{provinces}}</span><span>发布时间:{{input_time}}</span></p>
    	<p><span>交货时间:{{delivertime}}</span></p>
    </div>
    <h3 class="buyTitle">
    	联系方式
    </h3>
    <div class="buyDetail2">
    	<p><img src="../../img/buydetail.png"><span>{{c_name}}</span></p>
    	<p><img src="../../img/buydetail2.png"><span>{{con_name}}{{mobile}}</span></p>
    	<p><img src="../../img/buydetail3.png"><span>{{qq}}</span></p>
    	<a href="tel:{{mobile}}">联系客户经理</a>
    </div>	
    <footer id="footer">
    	<div class="releaseSale">
    		<textarea placeholder="写下您真实的需求,包括牌号，吨数等，收到后我们会立即给您电话确认，剩下的交给我们吧"></textarea>
    	</div>
    	<div class="releaseBtn" v-on:click="sendMsg">免费<br>委托<br>发布</div>
    	<div class="border-bottom"></div>
        <ul>
            <li><a v-link="{name:'buy'}" class="footerOn"><i class="foot"></i><br>我要买货</a></li>
            <li><a v-link="{name:'sale'}"><i class="foot2"></i><br>我要卖货</a></li>
            <li><a v-link="{name:'price'}"><i class="foot3"></i><br>实时价格</a></li>
            <li><a v-link="{name:'todayindex'}"><i class="foot4"></i><br>行情指数</a></li>
        </ul>
    </footer>
    </div>
</template>
<script>
	module.exports={
        el:"#app",
        data:function () {
            return {
            	c_name:"",
				con_name:"",
				delivertime:"",
				delivery_place:"",
				f_name:"",
				input_time:"",
				mobile:"",
				model:"",
				number:"",
				product_type:"",
				provinces:"",
				qq:"",
				store_house:"",
				unit_price:""
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
        	console.log(this.$route.query.id);
        	this.$http.post('/mobi/mainPage/getCheckDelegate',{otype:1,id:this.$route.query.id}).then(function(res){
        		console.log(res.json());
        		this.$set('c_name',res.json().chDeRes.c_name);
        		this.$set('con_name',res.json().chDeRes.con_name);
        		this.$set('delivertime',res.json().chDeRes.delivertime);
        		this.$set('delivery_place',res.json().chDeRes.delivery_place);
        		this.$set('f_name',res.json().chDeRes.f_name);
        		this.$set('input_time',res.json().chDeRes.input_time);
        		this.$set('mobile',res.json().chDeRes.mobile);
        		this.$set('model',res.json().chDeRes.model);
        		this.$set('number',res.json().chDeRes.number);
        		this.$set('product_type',res.json().chDeRes.product_type);
        		this.$set('provinces',res.json().chDeRes.provinces);
        		this.$set('qq',res.json().chDeRes.qq);
        		this.$set('store_house',res.json().chDeRes.store_house);
        		this.$set('unit_price',res.json().chDeRes.unit_price);
        	},function(res){});
        }
	}
</script>
