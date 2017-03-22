<template>
<div class="buyWrap" style="padding: 45px 0 70px 0;">
<header id="bigCustomerHeader" style="position: fixed; top: 0; left: 0; z-index: 10;">
塑料发现
</header>
<h3 class="plasticfind">
<div style="float: left;">塑料头条</div>
<div class="plasticSearch">
<i class="searchIcon" style="position: absolute; top: 14px; left: 5px;"></i>
<form action="javascript:;">
<input type="text" v-on:keydown.enter="search" v-model="keywords" placeholder="搜你想搜的" />
</form>
</div>
</h3>
<div class="plasticnav">
<div class="subscribe" v-on:click="subscribe"></div>
<div style="width: auto; margin: 0 40px 0 0;">
	<div class="swiper-container">
		<div class="swiper-wrapper">
			<div class="swiper-slide">
				<router-link class="on" :to="{name:'headlinelist',params:{id:999}}">
					推荐
				</router-link>
			</div>
			<div class="swiper-slide">
				<router-link :to="{name:'headlinelist',params:{id:2}}">
					塑料上游
				</router-link>
			</div>
			<div class="swiper-slide">
				<router-link :to="{name:'headlinelist',params:{id:1}}">
					早盘预报
				</router-link>
			</div>
			<div class="swiper-slide">
				<router-link :to="{name:'headlinelist',params:{id:9}}">
					企业动态
				</router-link>
			</div>
			<div class="swiper-slide">
				<router-link :to="{name:'headlinelist',params:{id:4}}">
					中晨塑说
				</router-link>
			</div>
			<div class="swiper-slide">
				<router-link :to="{name:'headlinelist',params:{id:5}}">
					美金市场
				</router-link>
			</div>
			<div class="swiper-slide">
				<router-link :to="{name:'headlinelist',params:{id:21}}">
					期货资讯
				</router-link>
			</div>
			<div class="swiper-slide">
				<router-link :to="{name:'headlinelist',params:{id:11}}">
					装置动态
				</router-link>
			</div>
			<div class="swiper-slide">
				<router-link :to="{name:'headlinelist',params:{id:13}}">
					期刊报告
				</router-link>
			</div>
			<div class="swiper-slide">
				<router-link :to="{name:'headlinelist',params:{id:22}}">
					独家解读
				</router-link>
			</div>
		</div>
	</div>
</div>
</div>

<ul class="headlineUl2">
<li v-for="i in items">
	<router-link :to="{name:'headlinedetail',params:{id:i.id}}">
		<h3 v-if="i.type!=='PUBLIC'">[{{i.type}}]{{i.title}}</h3>
		<h3 v-else>{{i.title}}</h3>
		<p>{{i.description}}</p>
		<p style="text-align: right;">{{i.input_time}}</p>
	</router-link>
</li>
</ul>
<h3 class="plasticfind">
企业信用额度
</h3>
<ul class="plasticcredit">
<li>
	<router-link :to="{name:'credit'}">
		<i class="plasticIcon picon"></i><br>查自己
	</router-link>
</li>
<li>
	<router-link :to="{name:'searchcompany'}">
		<i class="plasticIcon picon2"></i><br>查别人
	</router-link>
</li>
<li>
	<a href="javascript:;" v-on:click="sxshow">
		<i class="plasticIcon picon3"></i><br>要授信
	</a>
</li>
<li>
	<a href="javascript:;" v-on:click="levelshow">
		<i class="plasticIcon picon4"></i><br>提额度
	</a>
</li>
</ul>
<h3 class="plasticfind">
塑料配资
</h3>
<ul class="plasticcredit2">
<li>
	<a href="javascript:;" v-on:click="defineshow"><i class="plasticIcon picon5"></i><br>产品定义</a>
</li>
<li>
	<a href="javascript:;" v-on:click="rateshow"><i class="plasticIcon picon6"></i><br>费率</a>
</li>
<li>
	<a href="javascript:;" v-on:click="sqshow"><i class="plasticIcon picon7"></i><br>我要申请</a>
</li>
</ul>
<footerbar></footerbar>
<div class="subscribelayer" v-show="subscribeshow">
<h3 class="subscribetitle">
我的频道：
</h3>
<ul class="mysubscribe">
	<li v-show="mySubscribe.indexOf('2')>=0"><i class="headlineicon hicon"></i><br>塑料上游</li>
	<li v-show="mySubscribe.indexOf('1')>=0"><i class="headlineicon hicon2"></i><br>早盘预报</li>
	<li v-show="mySubscribe.indexOf('9')>=0"><i class="headlineicon hicon3"></i><br>企业动态</li>
	<li v-show="mySubscribe.indexOf('4')>=0"><i class="headlineicon hicon4"></i><br>中晨塑说</li>
	<li v-show="mySubscribe.indexOf('20')>=0"><i class="headlineicon hicon5"></i><br>美金市场</li>
	<li v-show="mySubscribe.indexOf('21')>=0"><i class="headlineicon hicon6"></i><br>期货资讯</li>
	<li v-show="mySubscribe.indexOf('11')>=0"><i class="headlineicon hicon7"></i><br>装置动态</li>
	<li v-show="mySubscribe.indexOf('13')>=0"><i class="headlineicon hicon8"></i><br>期刊报告</li>
	<li v-show="mySubscribe.indexOf('22')>=0"><i class="headlineicon hicon9"></i><br>独家解读</li>
</ul>
<h3 class="subscribetitle">
全部频道：
</h3>
<ul class="mysubscribe">
	<li><i class="headlineicon hicon"></i><br>
		<input class="subscribecheckbox" type="checkbox" disabled="disabled" v-model="subchecked" value="2" />&nbsp;塑料上游</li>
	<li><i class="headlineicon hicon2"></i><br>
		<input class="subscribecheckbox" type="checkbox" v-model="subchecked" value="1" />&nbsp;早盘预报</li>
	<li><i class="headlineicon hicon3"></i><br>
		<input class="subscribecheckbox" type="checkbox" v-model="subchecked" value="9" />&nbsp;企业动态</li>
	<li><i class="headlineicon hicon4"></i><br>
		<input class="subscribecheckbox" type="checkbox" v-model="subchecked" value="4" />&nbsp;中晨塑说</li>
	<li><i class="headlineicon hicon5"></i><br>
		<input class="subscribecheckbox" type="checkbox" disabled="disabled" v-model="subchecked" value="20" />&nbsp;美金市场</li>
	<li><i class="headlineicon hicon6"></i><br>
		<input class="subscribecheckbox" type="checkbox" disabled="disabled" v-model="subchecked" value="21" />&nbsp;期货资讯</li>
	<li><i class="headlineicon hicon7"></i><br>
		<input class="subscribecheckbox" type="checkbox" disabled="disabled" v-model="subchecked" value="11" />&nbsp;装置动态</li>
	<li><i class="headlineicon hicon8"></i><br>
		<input class="subscribecheckbox" type="checkbox" v-model="subchecked" value="13" />&nbsp;期刊报告</li>
	<li><i class="headlineicon hicon9"></i><br>
		<input class="subscribecheckbox" type="checkbox" v-model="subchecked" value="22" />&nbsp;独家解读</li>
</ul>
<div class="subscribebtn">
	<span class="subplasticbtn" v-on:click="subscribeSave">保存</span>&nbsp;&nbsp;&nbsp;
	<span class="subplasticbtn" v-on:click="subscribeClose">关闭</span>
</div>
</div>
<div class="plasticdefine" v-show="plasticdefine">
<div class="plasticdefineWrap">
	<div v-on:click="hide" style=" position: absolute; right: 13px; top: 0; width: 30px; height: 40px;"></div>
</div>
</div>
<div class="plasticsx" v-show="plasticsx">
<div class="plasticsxWrap">
	<div v-on:click="hide" style=" position: absolute; right: 13px; top: 0; width: 30px; height: 40px;"></div>
	<a style=" position: absolute; width: 75px; height: 25px; right: 20px; top: 200px;" href="tel:4006129965"></a>
	<a style=" position: absolute; width: 75px; height: 25px; right: 20px; top: 240px;" href="tel:02161070985"></a>
</div>
</div>
<div class="plasticsq" v-show="plasticsq">
<div class="plasticsqWrap">
	<div v-on:click="hide" style=" position: absolute; right: 13px; top: 0; width: 30px; height: 40px;"></div>
	<a style=" position: absolute; width: 75px; height: 25px; right: 20px; top: 200px;" href="tel:4006129965"></a>
	<a style=" position: absolute; width: 75px; height: 25px; right: 20px; top: 240px;" href="tel:02161070985"></a>
</div>
</div>
<div class="plasticrate" v-show="plasticrate">
<div class="plasticrateWrap">
	<div v-on:click="hide" style=" position: absolute; right: 13px; top: 0; width: 30px; height: 40px;"></div>
</div>
</div>
<div class="plasticlevel" v-show="plasticlevel">
<div class="plasticlevelWrap">
	<div v-on:click="hide" style=" position: absolute; right: 13px; top: 0; width: 30px; height: 40px;"></div>
</div>
</div>
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
		cate: "",
		items: [],
		mySubscribe: [],
		subscribeshow: false,
		subchecked: [],
		plasticdefine: false,
		plasticrate: false,
		plasticlevel: false,
		plasticsx: false,
		plasticsq: false,
		keywords:""
	}
},
methods: {
	hide: function() {
		this.plasticdefine = false;
		this.plasticrate = false;
		this.plasticlevel = false;
		this.plasticsx = false;
		this.plasticsq = false;
	},
	sxshow: function() {
		this.plasticsx = true;
	},
	sqshow: function() {
		this.plasticsq = true;
	},
	levelshow: function() {
		this.plasticlevel = true;
	},
	defineshow: function() {
		this.plasticdefine = true;
	},
	rateshow: function() {
		this.plasticrate = true;
	},
	subscribe: function() {
		this.subscribeshow = true;
	},
	subscribeClose: function() {
		this.subscribeshow = false;
	},
	subscribeSave: function() {
		var _this = this;
		this.subscribeshow = false;
		console.log(_this.subchecked);
		$.ajax({
			type: "post",
		url: '/api/qapi1_1/getSelectCate',
		data: {
			token: window.localStorage.getItem("token"),
			cate_id: _this.subchecked,
			type: 1
		},
		dataType: 'JSON'
	}).then(function(res) {
		if(res.err == 0) {
			$.ajax({
				type: "post",
				url: '/api/qapi1_1/getSelectCate',
				data: {
					token: window.localStorage.getItem("token"),
					type: 2
				},
				dataType: 'JSON'
			}).then(function(res) {
				if(res.err == 0) {
					_this.mySubscribe = res.data;
					_this.subchecked = res.data;
				} else {

				}
			}, function() {

			});
		} else {

		}
	}, function() {

	});
},
search: function() {
	var _this = this;
	if(this.keywords) {
		try {
			var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
			piwikTracker.trackSiteSearch(this.keywords, "keywords", 20);
		} catch(err) {}

		$.ajax({
			url: '/api/qapi1_1/getSubscribe',
			type: 'post',
			data: {
				keywords: _this.keywords,
				page: 1,
				subscribe: 1,
				token: window.localStorage.getItem("token")
			},
			dataType: 'JSON'
			}).then(function(res) {
				if(res.err == 0) {
					_this.items = res.data.slice(0, 3);
				}
			}, function() {

			});
		} else {

		}
	}
},
activated: function() {
	var _this = this;
	try {
		var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
		piwikTracker.trackPageView();
	} catch(err) {
	
	}

	$.ajax({
		type: "post",
		url: '/api/qapi1_1/getSelectCate',
		data: {
			token: window.localStorage.getItem("token"),
			type: 2
		},
		dataType: 'JSON'
	}).done(function(res){
		if(res.err == 0) {
			_this.mySubscribe = res.data;
			_this.subchecked = res.data;
		} else {
	
		}		
	}).fail(function(){
		
	}).always(function(){
		
	});

	$.ajax({
		type: "post",
		url: '/api/qapi1_1/getSubscribe',
		data: {
			token: window.localStorage.getItem("token"),
			subscribe: 2
		},
		dataType: 'JSON'
	}).done(function(res){
		if(res.err == 0) {
			_this.items = res.data.slice(0, 3);
		} else if(res.err == 1) {
			weui.alert(res.msg, {
				title: '塑料圈通讯录',
				buttons: [{
					label: '确定',
					type: 'parimary',
					onClick: function() {
						_this.$router.push({
							name: 'login'
						});
					}
				}]
			});
		}		
	}).fail(function(){
		
	}).always(function(){
		
	});
	
	this.$nextTick(function() {
	var swiper = new Swiper('.swiper-container', {
				slidesPerView: 4,
				spaceBetween: 15,
				freeMode: true
			});
	});

	}
}
</script>