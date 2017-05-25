<template>
	<div class="buyWrap">
    <header id="bigCustomerHeader">
        	{{time}}
    </header>
    <div class="supplyDemandStatus2">
        <span>牌号</span>
        <span>厂商</span>
        <span>单价 元/吨</span>
        <span>更新时间</span>
    </div>
    <div class="supplyDemandUl2">
        <ul>
        	<li v-for="s in supply">
        		<span>{{s.model}}</span>
        		<span>{{s.f_name}}</span>
        		<span>{{s.unit_price}}</span>
        		<span>{{s.input_time}}</span>
        	</li>
        </ul>
    </div>
    <div style="text-align: center; padding: 5px 0;">
		<input class="more" type="button" v-on:click="more" v-model="moreMsg">
	</div>
    <footer id="footer">
    	<div class="releaseSale">
    		<textarea placeholder="写下您真实的需求,包括牌号，吨数等，收到后我们会立即给您电话确认，剩下的交给我们吧"></textarea>
    	</div>
    	<div class="releaseBtn" v-on:click="sendMsg">免费<br>委托<br>发布</div>
    	<div class="border-bottom"></div>
        <ul>
            <li><a v-link="{name:'buy'}"><i class="foot"></i><br>我要买货</a></li>
            <li><a v-link="{name:'sale'}"><i class="foot2"></i><br>我要卖货</a></li>
            <li><a v-link="{name:'price'}" class="footerOn"><i class="foot3"></i><br>实时价格</a></li>
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
            	supply:[],
            	time:"",
            	page:1,
            	moreMsg:"加载更多数据"
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
            },
        	more:function(){
        		this.page++;
        		this.$http.post('/touch/newPage/getRealPrice',{page:this.page}).then(function(res){
        			console.log(res.json());
        			if(res.json().err==3){
        				this.moreMsg="没有更多数据"
        			}else{
        				this.supply=this.supply.concat(res.json().data);
        			}
        			
        		},function(){
        			
        		});
        	}
        },
        ready:function () {
            this.$http.post('/touch/newPage/getRealPrice',{}).then(function (res) {
                console.log(res.json());
                this.$set('supply',res.json().data)
                this.$set('time',res.json().data[0].cur_time)
            },function (res) {

            });
        }
    
	}
</script>