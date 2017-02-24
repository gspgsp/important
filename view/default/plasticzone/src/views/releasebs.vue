<template>
	<div class="buyWrap" style="padding: 45px 0 50px 0;">
	<div style="position: fixed; top: 0; left: 0; width: 100%; z-index: 6;">
	    <header id="bigCustomerHeader">
	    	<a class="back" href="javascript:window.history.back();"></a>
	        	供求发布
	    </header>
	</div>
    <div class="releaseWrap2">
		<div style="text-align: center; padding: 20px 0;">
    		<input id="supply" type="radio" v-model="type" value="2" checked="checked">&nbsp;<label for="supply">我要<span style="color: #ff5000;">供给</span></label>&nbsp;&nbsp;&nbsp;
    		<input id="buy" type="radio" v-model="type" value="1">&nbsp;<label for="buy">我要<span style="color: #ff5000;">求购</span></label>
		</div>
		<p style="width: 100%;">
    		<label style="width: 12%; line-height: 20px;">快速录入供求</label><textarea style="width: 88%;" placeholder="在此文本框内，可快速复制粘贴供求信息，限制100字以内！" maxlength="100" v-model="remark"></textarea>
    	</p>
		<p>
    		<label>牌号</label><input type="text" v-model="model"/>
    	</p>
    	<p>
    		<label>厂家</label><input type="text" v-model="f_name" />
    	</p>
		<p>
    		<label>价格</label><input type="number" v-model="price" v-on:blur="checkNum"/>
    	</p>
    	<p>
    		<label>交货地</label><input type="text" v-model="store_house" />
    	</p>
	</div>
	<div class="footrelease">
		<input type="button" v-on:click="sale" style="border: none; border-bottom: 1px solid #b33901;" v-bind:disabled="isDisable" value="发布" />
	</div>
	<div style="padding: 0 10px;">
	<h3 style="font-weight: normal; font-size: 12px; color: #ff5000; margin: 20px 0 5px 0;">信息发布提示：</h3>
	<p style=" font-size: 12px; color: #333; margin: 0;">1、填写“牌号、厂家、价格、交货地”，能参与比价，系统会智能匹配此牌号的供求信息；</p>
	<p style=" font-size: 12px; color: #333; margin: 0;">2、您发布的供求信息，将会对“塑料圈通讯录”中所有的人公开；</p>
	<p style=" font-size: 12px; color: #333; margin: 0;">3、请勿发布虚假信息，若因发布虚假信息而产生不良后果，您自行承担责任；</p>
    </div>
    </div>
</template>
<script>
	module.exports={
        el:"#app",
        data:function () {
            return {
            	type:2,
            	store_house:"",
            	model:"",
            	f_name:"",
            	price:"",
            	remark:"",
            	show:false,
            	content:"",
            	id:"",
            	user_id:"",
            	isDisable:false
            }
        },
        methods:{
        	checkNum:function(){
        		if(this.price<1000||this.price>30000){
        			mui.alert("","输入的价格不合理",function(){
        				
        			});
        		}
        	},
        	sale:function(){
        		var _this=this;
        		this.isDisable=true;
    			var data=[];
				var arr={
					'model':this.model.toUpperCase(),
					'f_name':this.f_name,
					'store_house':this.store_house,
					'price':this.price,
					'type':this.type,
					'pt':1,
					'content':this.remark
				};
				data.push(arr);

        		if(this.type&&this.store_house&&this.model&&this.f_name&&this.price || this.remark){
        			
        			this.$http.post('/mobi/mainPage/pub', {
		        		data:data
		        	}).then(function(res){
		        		console.log(res.json());
		        		if (res.json().err==0) {
		        			_this.$route.router.go({name:"release"});
		        		}else{
		        			mui.alert("",res.json().msg,function(){
		        				window.location.reload();
		        			});
		        		}
		        	},function(res){
		        		
		        	});
		        	
        		}else{
        			mui.alert("","请把信息填写完整",function(){
        				
        			});
        		}
        	}
        },
        ready:function () {

        }
	}
</script>
