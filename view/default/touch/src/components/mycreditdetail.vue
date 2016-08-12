<template>
<header id="meHeader">
	<a class="back" href="javascript:window.history.back();"></a>
	我的积分
</header>
<div class="meCreditBanner">
	<img src="../../img/temp5.jpg">
</div>
<div class="shopHeader">
	<img style=" float: left; margin: 10px 0 0 22px;" width="24" src="../../img/myCredit.png">
	<h3 class="myCredit">我的积分 <span id="credit">{{points}}</span><br>
		<a href="/mobi/personalCenter/creditDetail">积分明细</a>&nbsp;<a href="/mobi/personalCenter/creditRecord">兑换记录</a>
	</h3>
	<div class="creditBtn" @click="signOn()">签到</div>
</div>
<div class="shopClassify">
	<span @click="changeType()">
		<img src="../../img/credit_classify.png"><br>全部
	</span>
	<span @click="changeType(1)">
		<img src="../../img/credit_classify2.png"><br>家居
	</span>
	<span @click="changeType(2)">
		<img src="../../img/credit_classify3.png"><br>数码
	</span>
</div>
<div class="shopRecommand">商品推荐</div>
<ul class="shopUl">
	<li v-for="good in goods">
		<div style='width:100%; text-align:center'>
			<a v-link={path:'/shopdetail?id='+good.id}><img v-bind:src=good.thumb></a>
		</div><h3>{{good.name}}</h3><p><span>{{good.points}}</span>&nbsp;积分</p>
	</li>
</ul>
</template>
<script>
module.exports={
	el:"#app",
	data:function(){
		return {
			points:"",
			gtype:"",
			goods:[]
		}
	},
	ready:function(){
		this.$http.post("/mobi/personalCenter/getMyPoints",{gtype:this.gtype}).then(function(res){
			console.log(res.json());
			if(res.json().err==1){
				this.$route.router.go("/login");
			}else{
				this.$set("goods",res.json().shop);
				this.$set("points",res.json().points);
			}
		},function(){
			
		});
	},
	methods:{
		changeType:function(goodstype){
			if(goodstype==""){
				this.gtype="";
			}else{
				this.gtype=goodstype;
			}
			this.$http.post("/mobi/personalCenter/getMyPoints",{gtype:this.gtype}).then(function(res){
				console.log(res.json());
				this.$set("goods",res.json().shop);
			},function(){
				
			});
		},
		signOn:function(){
			this.$http.get("/mobi/sign/signOn").then(function(res){
				console.log(res.json());
				if(res.json().err==1){
					this.$route.router.go("/login");
				}else{
					mui.alert('',res.json().msg,function(){
						window.location.reload();
					});
				}
			},function(){
				
			});
		}
	}
}
//	$(function () {
//		var creditshopLi="";
//		var gtype="";
//		commonAjax(
//				"post",
//				"/mobi/personalCenter/getMyPoints",
//				{gtype:gtype}
//		).then(function (res) {
//			console.log(">>>",res);
//			if(res.err==1){
//				window.location.href="/mobi/login";
//			}else{
//				$("#credit").html(res.points);
//				if(!res.shop){
//					$(".shopUl").html("<li style='width: 100%; background: none; text-align: center; line-height: 60px;'>暂无数据</li>");
//				}else{
//					res.shop.forEach(function (v,i,a) {
//						creditshopLi+="<li data-id='"+ v.id +"'><div style='width:100%; text-align:center'>" +
//								"<img src='"+ v.thumb +"'></div>" +
//								"<h3>"+ v.name +"</h3>" +
//								"<p><span>"+ v.points +"</span>&nbsp;积分</p></li>";
//					});
//					$(".shopUl").html(creditshopLi);
//					$(".shopUl li").on("click",function () {
//						var id=$(this).data("id");
//						window.location.href='/mobi/personalCenter/shopDetail?id='+id;
//					});
//				}
//			}
//		},function () {
//
//		});
//
//		$(".shopClassify span").on("click",function () {
//			gtype=$(this).data("gtype");
//			creditshopLi="";
//			commonAjax(
//					"post",
//					"/mobi/personalCenter/getMyPoints",
//					{gtype:gtype}
//			).then(function (res) {
//				console.log(">>>",res);
//				if(res.err==1){
//					window.location.href="/mobi/login";
//				}else{
//					$("#credit").html(res.points);
//					if(!res.shop){
//						$(".shopUl").html("<li style='width: 100%; background: none; text-align: center; line-height: 60px;'>暂无数据</li>");
//					}else{
//						res.shop.forEach(function (v,i,a) {
//							creditshopLi+="<li data-id='"+ v.id +"'><div style='width:100%; text-align:center'>" +
//									"<img src='"+ v.thumb +"'></div>" +
//									"<h3>"+ v.name +"</h3>" +
//									"<p><span>"+ v.points +"</span>&nbsp;积分</p></li>";
//						});
//						$(".shopUl").html(creditshopLi);
//						$(".shopUl li").on("click",function () {
//							var id=$(this).data("id");
//							window.location.href='/mobi/personalCenter/shopDetail?id='+id;
//						});
//					}
//				}
//			},function () {
//
//			});
//		});
//
//		$(".creditBtn").on("click",function () {
//			commonAjax(
//					"get",
//					"/mobi/sign/signOn"
//			).then(function (res) {
//				if(res.err==1){
//					window.location.href="/mobi/login";
//				}else {
//					mui.alert('',res.msg,function(){
//						window.location.reload();
//					});
//				}
//			},function () {
//
//			});
//		});
//
//	})
</script>
