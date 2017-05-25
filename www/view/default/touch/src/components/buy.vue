<template>
	<div class="buyWrap">
    <header id="bigCustomerHeader">
        	我要买货
        	<span class="appMenu" v-on:click="itemsActive"></span>
    </header>
	<div class="bigCustomerMenu" v-show="itemIndex">
		<h3 class="bigCustomerMenuTitle">
			分类
		</h3>
		<p id="fl">
			<span v-for="t in type" v-on:click="fl($index)" v-bind:class="{on:product_typeindex==$index}">
				{{t.type}}
			</span>
		</p>
		<h3 class="bigCustomerMenuTitle">
			牌号
		</h3>
		<p id="ph">
			<span v-for="t in type2" v-on:click="ph($index)" v-bind:class="{on:modelindex==$index}">
				{{t.model}}
			</span>
		</p>
		<h3 class="bigCustomerMenuTitle">
			厂家
		</h3>
		<p id="cj">
			<span v-for="t in type3" v-on:click="cj($index)" v-bind:class="{on:f_nameindex==$index}">
				{{t.f_name}}
			</span>
		</p>
		<h3 class="bigCustomerMenuTitle">
			地区
		</h3>
		<p id="dq">
			<span v-for="t in type4" v-on:click="dq($index)" v-bind:class="{on:provincesindex==$index}">
				{{t}}
			</span>
		</p>
		<h3 class="bigCustomerMenuTitle">
			货物属性
		</h3>
		<p id="hwsx">
			<span v-for="t in type5" v-on:click="ct($index)" v-bind:class="{on:cargo_typeindex==$index}">
				{{t}}
			</span>
		</p>
		<div style="text-align: center; padding: 20px 0;">
			<a id="chooseBtn" class="classifyEnter" href="javascript:;" v-on:click="screen">确定</a>
			<a class="classifyEnter" href="javascript:window.location.reload();">重置</a>
		</div>
	</div>
    <div class="supplyDemandStatus">
        <span>牌号</span>
        <span>厂商</span>
        <span>单价 元/吨</span>
        <span>更新时间</span>
    </div>
    <div class="supplyDemandUl">
        <ul id="searchWrapUl">
        	<li v-for="s in supply">
        		<a v-link="'/buydetail?id='+s.id">
        		<span>{{s.model}}</span>
        		<span>{{s.f_name}}</span>
        		<span>{{s.unit_price}}</span>
        		<span>{{s.input_time}}</span>
        		</a>
        	</li>
        	<li v-if="li" style="text-align: center;">暂无数据</li>
        </ul>
    </div>
    <div v-show="btnShow" style="text-align: center; padding: 5px 0;">
		<input class="more" type="button" v-model="moreMsg" v-on:click="more">
	</div>
	<footernav></footernav>
    </div>
</template>
<script>
import footernav from "components/footernav";
	module.exports={
        el:"#app",
        components:{
      		'footernav':footernav,
    	},
        data:function () {
            return {
            	moreMsg:"加载更多数据",
            	li:false,
            	btnShow:1,
            	itemIndex:0,
            	type:[],
            	type2:[],
            	type3:[],
            	type4:[],
            	type5:[],
            	supply:[],
            	content:"",
            	model:"",
            	modelindex:-1,
            	f_nameindex:-1,
            	f_name:"",
            	provinces:"",
            	provincesindex:-1,
            	product_type:"",
            	product_typeindex:-1,
            	cargo_type:"",
            	cargo_typeindex:-1,
            	type:2,
            	otype:3,
            	page:1
            }
        },
        methods:{
            itemsActive:function () {
                this.itemIndex=this.itemIndex ? 0 : 1;
            },
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
            },
            more:function(){
        		this.page++;
        		this.$http.post('/touch/newPage/getSupplyCondData',{
        			model:this.model,
        			f_name:this.f_name,
        			provinces:this.provinces,
        			product_type:this.product_type,
        			cargo_type:this.cargo_type,
        			type:2,
        			page:this.page,
        			ttype:1
        		}).then(function(res){
        			console.log(res.json());
        			if(res.json().err==3){
        				this.moreMsg="没有更多数据";
        			}else{
        				this.supply=this.supply.concat(res.json().data);
        			}
        			
        		},function(){
        			
        		});
        	},
        	screen:function(){
        		this.page=1;
        		this.btnShow=1;
        		this.moreMsg="加载更多数据";
        		this.itemIndex=0;
        		this.li=false;
        		this.$http.post('/touch/newPage/getSupplyCondData',{
        			model:this.model,
        			f_name:this.f_name,
        			provinces:this.provinces,
        			product_type:this.product_type,
        			cargo_type:this.cargo_type,
        			page:this.page,
        			ttype:1
        		}).then(function(res){
        			console.log(res.json());
        			if(res.json().err==3){
        				this.btnShow=0;
        				this.li=true;
        				this.$set('supply',"");
        			}else{
        				this.$set('supply',res.json().data)
        			}
        			
        		},function(){
        			
        		});
        	},
        	fl:function(index){
        		this.product_typeindex=index;
        		this.product_type=index+1;
        		console.log(this.product_type);
        	},
        	ct:function(index){
        		this.cargo_typeindex=index;
        		this.cargo_type=index+1;
        		console.log(this.cargo_type);
        	},
        	dq:function(index){
        		this.provincesindex=index;
        		this.provinces=event.target.innerText;
        		console.log(this.provinces);
        	},
        	cj:function(index){
        		this.f_nameindex=index;
        		this.f_name=event.target.innerText;
        		console.log(this.f_name);
        	},
        	ph:function(index){
        		this.modelindex=index;
        		this.model=event.target.innerText;
        		console.log(this.model);
        	}
        },
        ready:function () {
            this.$http.post('/touch/newPage/getSupplyCondData',{type:2,ttype:1}).then(function (res) {
                console.log(res.json());
                this.$set('supply',res.json().data)
            },function (res) {

            });
            
            this.$http.post('/mobi/mainPage/getSupplyCondition',{ptype:0}).then(function (res) {
                console.log(res.json());
                this.$set('type',res.json().typeData[0]);
                this.$set('type2',res.json().typeData[1]);
                this.$set('type3',res.json().typeData[2]);
                this.$set('type4',res.json().typeData[3]);
                this.$set('type5',res.json().typeData[4]);
            },function (res) {

            });

        }
    
	}
</script>