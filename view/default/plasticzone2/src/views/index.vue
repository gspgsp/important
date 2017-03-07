<template>
<div class="buyWrap" style="padding: 90px 0 60px 0;">
<div style="position: fixed; top: 0; left: 0; width: 100%; z-index: 10;">
	<header id="bigCustomerHeader">
		<a class="headerMenu4" href="http://a.app.qq.com/o/simple.jsp?pkgname=com.myplas.q"></a>
		塑料圈通讯录({{member}}人)
		<a v-on:click="toLogin" class="headerMenu"></a>
	</header>
	<div class="indexsearch">
		<div class="indexsearchwrap">
			<form action="javascript:;">
				<i class="searchIcon" v-on:click="search"></i><input v-on:keydown.enter="search" type="text" placeholder="请输入公司、姓名、牌号查询" v-model="keywords" />
			</form>
		</div>
		<span class="filter" v-on:click="filterShow">{{txt}}<i class="downarrow"></i></span>
	</div>
</div>
<a href="http://x.eqxiu.com/s/NSganpY2?eqrcode=1&winzoom=1&from=groupmessage&isappinstalled=0">
<img style="width: 100%; margin: 5px 0 0 0;" src="http://static.myplas.com/myapp/img/slqbanner.jpg">
</a>
<!--<div class="payfans">
	<router-link :to="{name:'mypay'}">
		<div style=" display: inline-block; margin: 4px 0 0 0;">
			<div class="payfansImg"></div><span>我关注的人</span>
		</div>
	</router-link>
	<router-link :to="{name:'myfans'}">
		<div style=" display: inline-block; margin: 4px 0 0 0;">
			<div class="payfansImg2"></div><span>关注我的人</span>
		</div>
	</router-link>
</div>-->
<ul id="nameUl">
	<li v-show="condition" v-for="n in name">
		<div style="width: 55px; height: 55px; float: left; position: relative;">
			<div class="avator">
				<img v-bind:src="n.thumb">
			</div>
			<i class="iconV" v-bind:class="{'v1':n.is_pass==1,'v2':n.is_pass==0}"></i>
		</div>
		<div class="nameinfo">
			<router-link :to="{name:'personinfo',params:{id:n.user_id}}">
				<p class="first"><i class="icon wxGs"></i><span v-html="n.c_name"></span><i class="icon wxName"></i><span v-html="n.name"></span>&nbsp;{{n.sex}}</p>
				<p class="second"><span>供给:{{n.sale_count}} 求购:{{n.buy_count}}</span>&nbsp;<span>粉丝:{{n.fans}} 等级:{{n.member_level}}</span></p>
				<p class="second">主营：<span style="color: #666666;" v-html="n.need_product"></span></p>
				<i class="icon2 rightArrow"></i>
			</router-link>
		</div>
	</li>
	<li v-show="!condition" style="text-align: center;">
		没有相关数据
	</li>
</ul>
<div style="text-align: center; padding: 5px 0 15px 0;">
	<input class="more" v-on:click="more" type="button" v-model="moreTxt">
</div>
<footerbar></footerbar>
<div class="refresh" v-bind:class="{circle:isCircle}" v-on:click="circle"></div>
<div class="arrow" v-show="isArrow" v-on:click="arrow"></div>
<div class="filterwrap" v-show="filtershow">
	<i class="right" v-bind:class="{on:on1}"></i><span v-on:click="sortfiled" v-bind:class="{on:on1}">综合排序</span>
	<i class="right" v-bind:class="{on:on2}"></i><span v-on:click="sortorder" v-bind:class="{on:on2}">最近注册</span>
</div>
<div class="indexlayer" v-on:click="layershow" v-show="filtershow"></div>
</div>
</template>
<script>
import footer from "../components/footer";
module.exports = {
components:{
	'footerbar':footer
},
data: function() {
	return {
		name: [],
		keywords: "",
		page: 1,
		condition: true,
		member: "",
		picarr: [],
		fans: [],
		isCircle: false,
		isArrow: false,
		filtershow: false,
		sortField: "default",
		sortOrder: "desc",
		txt: "综合排序",
		on1:true,
		on2:false,
		moreTxt:"加载更多数据",
		isIndex: false,
		isRelease: false,
		isMyzone: false,
		isHeadline: false,
		isReleaseshow: false,
		buy: [],
		supply: []
	}
},
methods: {
	toLogin:function(){
		if (window.localStorage.getItem("token")) {
			mui.alert("", "你已登录塑料圈", function() {
				
			});
		} else{
			this.$router.push({name: 'login'});
		}
	},
	layershow:function(){
		this.filtershow = false;
	},
	sortfiled: function() {
		var _this = this;
		this.sortField = "default";
		this.filtershow = false;
		this.on1=true;
		this.on2=false;
		this.txt = "综合排序";
		$.ajax({
			type: "get",
			url: "/api/qapi1/getPlasticPerson",
			data: {
				keywords: "",
				page: 1,
				token: window.localStorage.getItem("token"),
				size: 10,
				sortField: _this.sortField
			},
			dataType: 'JSON'
		}).then(function(res) {
			console.log(res);
			if(res.err == 0) {
				_this.condition = true;
				_this.member = res.member;
				_this.name = res.persons; 
			} else if(res.err == 2) {
				_this.condition = false;
			}
		}, function() {

		});
	},
	sortorder: function() {
		var _this = this;
		this.sortField = "input_time";
		this.sortOrder = "desc";
		this.filtershow = false;
		this.on1=false;
		this.on2=true;
		this.txt = "最近注册";
		$.ajax({
			type: "get",
			url: "/api/qapi1/getPlasticPerson",
			data: {
				keywords: "",
				page: 1,
				token: window.localStorage.getItem("token"),
				size: 10,
				sortField: _this.sortField,
				sortOrder: _this.sortOrder
			},
			dataType: 'JSON'
		}).then(function(res) {
			console.log(res);
			if(res.err == 0) {
				_this.condition = true;
				_this.member = res.member;
				_this.name = res.persons;
			} else if(res.err == 2) {
				_this.condition = false;
			}
		}, function() {

		});

	},
	filterShow: function() {
		this.filtershow = true;
	},
	arrow: function() {
		window.scrollTo(0, 0);
	},
	circle: function() {
		var _this = this;
		this.isCircle = true;
		$.ajax({
			type: "get",
			url: "/api/qapi1/getPlasticPerson",
			data: {
				keywords: "",
				page: 1,
				token: window.localStorage.getItem("token"),
				size: 10
			},
			dataType: 'JSON'
		}).then(function(res) {
			console.log(res);
			if(res.err == 0) {
				_this.condition = true;
				_this.member = res.member;
				_this.name = res.persons;
				_this.isCircle = false;
				window.scrollTo(0, 0);
				mui.toast('更新成功', {
					duration: 'long',
					type: 'div'
				});
			} else if(res.err == 2) {
				_this.condition = false;
			}
		}, function() {

		});
	},
	search: function() {
		var _this = this;
		_this.page = 1;
		if(this.keywords) {
			try {
			    var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
			    piwikTracker.trackSiteSearch(this.keywords, "keywords", 20);
			} catch( err ) {}
			
			$.ajax({
				url: '/api/qapi1/getPlasticPerson',
				type: 'get',
				data: {
					keywords: _this.keywords.toLocaleUpperCase(),
					page: _this.page,
					token: window.localStorage.getItem("token"),
					size: 10
				},
				dataType: 'JSON'
			}).then(function(res) {
				if(res.err == 0) {
					_this.condition = true;
					_this.name = res.persons;
				} else if(res.err == 2) {
					_this.condition = false;
				}
			}, function() {

			});
		} else {
			window.location.reload();
		}
	},
	more:function(){
		var _this=this;
			_this.page++;
			_this.moreTxt="加载中...";
			$.ajax({
				type: "get",
				url: "/api/qapi1/getPlasticPerson",
				data: {
					chanel: 6,
					quan_type: 0,
					keywords: _this.keywords.toLocaleUpperCase(),
					page: _this.page,
					token: window.localStorage.getItem("token"),
					size: 10
				},
				dataType: 'JSON'
			}).then(function(res) {
				console.log(res);
				if(res.err == 0) {
					_this.condition = true;
					_this.name = _this.name.concat(res.persons);
					_this.moreTxt="加载更多数据";
				} else if(res.err == 1) {
					_this.moreTxt="加载更多数据";
					mui.alert("", res.msg, function() {
						_this.$router.push({
							name: 'login'
						});
					});
				} else if(res.err == 2) {
					_this.condition = false;
				}
			}, function() {

			});
	}
},
beforeRouteEnter:function(to,from,next){
	next(function(){
		$(window).scrollTop(window.localStorage.getItem("scrollTop"));
		console.log("enter",$(window).scrollTop());		
	});
},
beforeRouteLeave:function(to,from,next){
	next(function(){
		
	});
	window.localStorage.setItem("scrollTop",$(window).scrollTop());
	console.log("leave",$(window).scrollTop());
},
mounted: function() {
	var _this = this;
	try {
	    var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
	    piwikTracker.trackPageView();
	    
	} catch( err ) {
		
	}

	$.ajax({
		type: "get",
		url: "/api/qapi1/supplyDemandList",
		data: {
			page: 1,
			token: window.localStorage.getItem("token"),
			size: 5,
			type: 1
		},  
		dataType: 'JSON'
	}).then(function(res) {
		console.log(res);
		if(res.err == 0) {
			_this.buy = res.data;
		} else if(res.err == 1) {
			_this.buy = [];
		} else if(res.err == 2) {
			_this.buy = [];
		}
	}, function() {

	});

	$.ajax({
		type: "get",
		url: "/api/qapi1/supplyDemandList",
		data: {
			page: 1,
			token: window.localStorage.getItem("token"),
			size: 5,
			type: 2
		},
		dataType: 'JSON'
	}).then(function(res) {
		console.log(res);
		if(res.err == 0) {
			_this.supply = res.data;
		} else if(res.err == 1) {
			_this.supply = [];
		} else if(res.err == 2) {
			_this.supply = [];
		}
	}, function() {

	});

	$.ajax({
		type: "get",
		url: "/api/qapi1/getPlasticPerson",
		data: {
			keywords: "",
			page: 1,
			token: window.localStorage.getItem("token"),
			size: 10
		},
		dataType: 'JSON'
	}).then(function(res) {
		console.log(res);
		if(res.err == 0) {
			_this.condition = true;
			_this.member = res.member;
			_this.name = res.persons;
		} else if(res.err == 2) {
			_this.condition = false;
		}
	}, function() {

	});

	$(window).scroll(function() {
		var scrollTop = $(this).scrollTop();
		var scrollHeight = $(document).height();
		var windowHeight = $(this).height();
		if(scrollTop > 600) {
			_this.isArrow = true;
		} else {
			_this.isArrow = false;
		}
	});
	window.localStorage.invite = this.$route.query.invite;
}
}
</script>