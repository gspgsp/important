<template>
<div>
<header id="bigCustomerHeader">
	<a class="back" href="javascript:window.history.back();"></a>
	塑豆商城
</header>
<div class="shopBanner">
	<img width="100%" src="http://statics.myplas.com/myapp/img/shopBanner.jpg">
</div>
<div class="mypoints">
	<a style="width: 50%;" href="javascript:;"><i class="shopIcon iconPoints"></i><span>{{points}}</span>塑豆</a>
	<!--<router-link :to="{name:'pointsrecord'}"><i class="shopIcon iconRecord"></i>兑换记录</router-link>-->
	<router-link style="width: 50%;" :to="{name:'pointsrule'}"><i class="shopIcon iconIntro"></i>如何赚塑豆</router-link>
</div>
<div class="pointsWrap">
	<div class="pointsTitle">商品信息</div>
	<ul id="productUl">
		<li>
			<div style="overflow: hidden; padding: 10px; position: relative;">
				<img v-bind:src="p1.thumb" style=" margin: 0 10px 0 0;">
				<div class="proTxt">{{p1.name}}<br>所需塑豆<span>{{p1.points}}</span>塑豆</div>
				<div class="proAmount">x{{pro.num}}</div>
			</div>			
			<div class="productNum">
				<span>*</span>请选择兑换数量：
				<div class="proSelect">
					<strong v-on:click="proMin">-</strong>
					<strong>{{pro.num}}</strong>
					<strong v-on:click="proAdd">+</strong>
				</div>
			</div>	
			<div class="productCost">共<span>{{pro.num}}</span>件
				<div class="exchange" v-on:click="proExchange">提交兑换</div>
				<div class="cost">总塑豆：{{pro.cost}}</div>
			</div>
		</li>
		<li>
			<div style="overflow: hidden; padding: 10px; position: relative;">
				<img v-bind:src="p2.thumb" style=" margin: 0 10px 0 0;">
				<div class="proTxt">{{p2.name}}<br>所需塑豆<span>{{p2.points}}</span>塑豆</div>
				<div class="proAmount">x{{pro2.num}}</div>
			</div>			
			<div class="productNum">
				<span>*</span>请选择兑换数量：
				<div class="proSelect">
					<strong v-on:click="proMin2">-</strong>
					<strong>{{pro2.num}}</strong>
					<strong v-on:click="proAdd2">+</strong>
				</div>
			</div>
			<div class="productMsg">
				<span>*</span>请选择要置顶的供求信息（限选一条）：
			</div>
			<div class="proMsgLi">
				<div v-for="m in p2.myMsg">
					<input type="radio" name="msg" v-bind:value="m.id" v-model="selected">
					{{m.input_time}}<br>
					供求：<span>{{m.contents}}</span>
				</div>
			</div>		
			<div class="productCost">共<span>{{pro2.num}}</span>件
				<div class="exchange" v-on:click="proExchange2">提交兑换</div>
				<div class="cost">总塑豆：{{pro2.cost}}</div>
			</div>
		</li>
	</ul>	
</div>

</div>
</template>
<script>
module.exports = {
	data: function() {
		return {
			p1:"",
			p2:"",
			points:0,
			pro:{
				id:"",
				cost:100,
				num:1,
				price:0
			},
			pro2:{
				id:"",
				cost:100,
				num:1,
				price:0
			},
			selected:""
		}
	},
	methods:{
		proAdd:function(){
			this.pro.num++;
			this.pro.cost=this.pro.num*this.pro.price;
		},
		proMin:function(){
			if(this.pro.num<2){
				return false;
			}else{
				this.pro.num--;
				this.pro.cost=this.pro.num*this.pro.price;
			}
		},
		proExchange:function(){
			var _this=this;
			$.ajax({
	    		type:"post",
	    		url:"/api/qapi1_2/new_exchangeSupplyOrDemand",
	    		data:{
	    			token: window.localStorage.getItem("token"),
	    			goods_id:_this.pro.id,
	    			num:_this.pro.num,
	    			pur_id:""
	    		},
	    		dataType: 'JSON'
		    }).then(function(res){
			    if(res.err==0){
					$.ajax({
			    		type:"post",
			    		url:"/api/score/decScore",
			    		data:{
			    			token: window.localStorage.getItem("token"),
			    			type:2,
			    			points:_this.pro.cost,
			    			gid:_this.pro.id
			    		},
			    		dataType: 'JSON'
			    	}).then(function(res){

			    	},function(){
			    		
			    	});
					weui.alert("兑换成功", {
						title: '塑料圈通讯录',
						buttons: [{
							label: '确定',
							type: 'parimary',
							onClick: function() {
								window.location.reload();
							}
						}]
					});       					
				}else{
					weui.alert(res.msg, {
						title: '塑料圈通讯录',
						buttons: [{
							label: '确定',
							type: 'parimary',
							onClick: function() {
							
							}
						}]
					}); 					
				}
		    	},function(){
		    		
		    	});		
		},
		proAdd2:function(){
			this.pro2.num++;
			this.pro2.cost=this.pro2.num*this.pro2.price;
		},
		proMin2:function(){
			if(this.pro2.num<2){
				return false;
			}else{
				this.pro2.num--;
				this.pro2.cost=this.pro2.num*this.pro2.price;
			}
		},
		proExchange2:function(){
			var _this=this;
			$.ajax({
	    		type:"post",
	    		url:"/api/qapi1_2/new_exchangeSupplyOrDemand",
	    		data:{
	    			token: window.localStorage.getItem("token"),
	    			goods_id:_this.pro2.id,
	    			num:_this.pro.num,
	    			pur_id:_this.selected
	    		},
	    		dataType: 'JSON'
		    }).then(function(res){
			    if(res.err==0){
					$.ajax({
			    		type:"post",
			    		url:"/api/score/decScore",
			    		data:{
			    			token: window.localStorage.getItem("token"),
			    			type:1,
			    			points:_this.pro.cost,
			    			gid:_this.pro2.id
			    		},
			    		dataType: 'JSON'
			    	}).then(function(res){

			    	},function(){
			    		
			    	});
					weui.alert("兑换成功", {
						title: '塑料圈通讯录',
						buttons: [{
							label: '确定',
							type: 'parimary',
							onClick: function() {
								window.location.reload();
							}
						}]
					});       					
				}else{
					weui.alert(res.msg, {
						title: '塑料圈通讯录',
						buttons: [{
							label: '确定',
							type: 'parimary',
							onClick: function() {
							
							}
						}]
					}); 					
				}
		    	},function(){
		    		
		    	});		
		}
	},
	activated: function() {
		var _this = this;
		window.scrollTo(0,0);
		try {
		    var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
		    piwikTracker.trackPageView();
		} catch( err ) {
			
		}
		$.ajax({
    		type:"post",
    		url:"/api/qapi1_2/getProductList",
    		data:{
    			token: window.localStorage.getItem("token"),
    			page:1,
    			size:10
    		},
    		dataType: 'JSON'
	    }).then(function(res){
			    if(res.err==1){
		    		weui.alert(res.msg, {
						title: '塑料圈通讯录',
						buttons: [{
							label: '确定',
							type: 'parimary',
							onClick: function() {
								_this.$router.push({ name: 'login' });
							}
						}]
					});       					
				}else{
					_this.p1=res.info[0];
					_this.p2=res.info[1];
					_this.pro.price=res.info[0].points;
					_this.pro2.price=res.info[1].points;
					_this.pro.id=res.info[0].id;
					_this.pro2.id=res.info[1].id;
					_this.points=res.pointsAll;
				}
	    	},function(){
	    		
	    	});	
	}
}
</script>