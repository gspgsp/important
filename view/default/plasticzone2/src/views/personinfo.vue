<template>
<div class="buyWrap" style="padding: 45px 0 70px 0;">
	<div style="position: fixed; top: 0; left: 0; width: 100%; z-index: 10;">
		<header id="bigCustomerHeader">
			<a class="back" href="javascript:window.history.back();"></a>
			查看个人信息
		</header>
	</div>
	<div class="personInfo">
		<div style="float: left; width: 100%; margin: 0 0 17px 0;">
			<div style=" width: 80px; height: 80px; margin: 0 15px 0 0; position: relative; float: left;">
				<div class="personAvator" v-on:click="check">
					<img v-bind:src="thumb">
				</div>
				<i class="iconV" v-bind:class="{'v1':is_pass==1,'v2':is_pass==0}"></i>
			</div>
			<div class="personName" style="margin: 20px 0 0 0;">
				{{name}}&nbsp;{{sex}}
				<span class="orange" v-on:click="pay">{{status}}</span>
			</div>
			<div class="personNum" style="margin: 5px 0 0 0;">
				<span>发布供给：<span style=" color: #63769d;">{{sale}}条</span></span>
				<span>发布需求：<span style=" color: #63769d;">{{buy}}条</span></span>
			</div>
		</div>
		<div class="personInfoList">
			<p>公司：{{c_name}}</p>
			<p>地址：{{address}}</p>
			<p>联系电话：{{mobile}}
				<a v-show="isMobile" class="telephone" v-bind:href="mobile2"></a>
			</p>
			<p style="border-bottom: 1px solid #D1D1D1;">我的主营：{{need_product}}</p>
			<div class="registerBox" style="height: auto; padding: 10px 0; margin: 0; line-height: 0; text-align: center;">
				<div class="card" v-on:click="cardcheck">
					<img v-bind:src="cardImg">
				</div>
			</div>
		</div>
		<div class="personInfoList">
			<h3 class="supplydemandtitle">
				最近求购信息<router-link :to="{name:'releasebuy',params:{id:$route.params.id}}" style="color: #ff4f00;">查看更多>></router-link>
			</h3>
			<ul class="supplydemandul">
				<li v-for="b in buylist">
					<span style="color: #999999;">{{b.input_time}}</span><br>
					<span style="color: #ec8000;">求购</span>:{{b.contents}}
				</li>
			</ul>
		</div>
		<div class="personInfoList">
			<h3 class="supplydemandtitle" style="background: #b8d2e3;">
				最近供给信息<router-link :to="{name:'releasesupply',params:{id:$route.params.id}}" style="color: #267bd3;">查看更多>></router-link>
			</h3>
			<ul class="supplydemandul">
				<li v-for="s in supplylist">
					<span style="color: #999999;">{{s.input_time}}</span><br>
					<span style="color: #63769d;">供给</span>:{{s.contents}}
				</li>
			</ul>
		</div>
	</div>
	<footerbar></footerbar>
	<div class="imgLayer" v-show="avatorCheck" v-on:click="check">
		<div class="avatorCheck" :style="{background: 'url(' + thumb + ') no-repeat center'}" style=" background-size: contain;"></div>
	</div>
	<div class="imgLayer" v-show="cardCheck" v-on:click="cardcheck">
		<div class="avatorCheck">
			<img v-bind:src="cardImg">
		</div>
	</div>
	<div class="layer" v-show="show"></div>
</div>
</template>
<script>
import footer from "../components/footer";
module.exports = {
	components: {
		'footerbar': footer
	},
	data: function() {
		return {
			name: "",
			buy: "",
			sale: "",
			c_name: "",
			mobile: "",
			address: "",
			sex: "",
			status: "",
			thumb: "",
			need_product: "",
			id: "",
			avatorCheck: false,
			cardCheck: false,
			show: false,
			user_id: "",
			content: "",
			is_pass: "",
			cardImg: "",
			mobile2:"",
			buylist:[],
			supplylist:[]
		}
	},
	methods: {
		cancel: function() {
			this.show = false;
		},
		check: function() {
			this.avatorCheck == true ? this.avatorCheck = false : this.avatorCheck = true;
		},
		cardcheck: function() {
			this.cardCheck == true ? this.cardCheck = false : this.cardCheck = true;
		},
		pay: function() {
			var _this=this;
			$.ajax({
				url: '/api/qapi1/focusOrCancel',
				type: 'get',
				data: {
					focused_id: _this.$route.params.id,
					token:window.localStorage.getItem("token")
				},
				dataType: 'JSON'
			}).then(function(res) {
					console.log(">>>",res.msg);
	            	window.location.reload();
			}, function() {

			});
		}
	},
	activated: function() {
		var _this = this;
		$(window).scrollTop(0);
	try {
	    var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
	    piwikTracker.trackPageView();
	} catch( err ) {
		
	}
		$.ajax({
			url: '/api/qapi1/getZoneFriend',
			type: 'get',
			data: {
				userid: this.$route.params.id,
				token: window.localStorage.getItem("token")
			},
			dataType: 'JSON'
		}).then(function(res) {
			console.log(res);
			if(res.err == 0) {
				_this.name = res.data.name;
				_this.c_name = res.data.c_name;
				_this.address = res.data.address;
				_this.mobile = res.data.mobile;
				_this.mobile2 = "tel:"+res.data.mobile;
				_this.need_product = res.data.need_product;
				_this.status = res.data.status;
				_this.thumb = res.data.thumb;
				_this.buy = res.data.buy;
				_this.sale = res.data.sale;
				_this.sex = res.data.sex;
				_this.id = res.data.user_id;
				_this.is_pass = res.data.is_pass;
				_this.cardImg=res.data.thumbcard;
				if (_this.mobile.indexOf("*")=="-1") {
					_this.isMobile=true;
				} else{
					_this.isMobile=false;
				}
			} else if(res.err == 1) {
				mui.alert("", res.msg, function() {
					_this.$router.push({ name: 'login' });
				});
			}
		}, function() {

		});
		
		$.ajax({
			url: '/api/qapi1/getTaPur',
			type: 'get',
			data: {
				userid: this.$route.params.id,
				page:1,
				size:5,
				type:1,
				token: window.localStorage.getItem("token")
			},
			dataType: 'JSON'
		}).then(function(res) {
			console.log(res);
			if(res.err == 0) {
				_this.buylist=res.data;
			} else if(res.err == 1) {
				mui.alert("", res.msg, function() {
					_this.$router.push({ name: 'login' });
				});
			}else if(res.err == 2){
				_this.buylist=[];
			}
		}, function() {

		});

		$.ajax({
			url: '/api/qapi1/getTaPur',
			type: 'get',
			data: {
				userid: this.$route.params.id,
				page:1,
				size:5,
				type:2,
				token: window.localStorage.getItem("token")
			},
			dataType: 'JSON'
		}).then(function(res) {
			console.log(res);
			if(res.err == 0) {
				_this.supplylist=res.data;
			} else if(res.err == 1) {
				mui.alert("", res.msg, function() {
					_this.$router.push({ name: 'login' });
				});
			}else if(res.err == 2){
				_this.supplylist=[];
			}
		}, function() {

		});
	}
}
</script>