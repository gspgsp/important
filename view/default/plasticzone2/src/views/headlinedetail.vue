<template>
<div class="buyWrap" style="padding: 45px 0 60px 0;">
<header id="bigCustomerHeader" style="position: fixed; top: 0; left: 0;">
	<a class="back" href="javascript:window.history.back();"></a>
	{{cate}}
	<a class="detailShare" href="javascript:;" v-on:click="shareshow()"></a>
</header>

<div class="headlinecontent" style="overflow: hidden; background: #FFFFFF;">
	<div class="headlinetitle">
		<h3>{{type}} {{title}}</h3>
		<p>作者:{{author}}&nbsp;&nbsp;&nbsp;阅读数量:{{pv}}&nbsp;发布时间：{{time}}</p>
	</div>
	<div class="headlinetxt">
		<div v-html="content"></div>
	</div>
	<div style="overflow: hidden;">
		<span class="pre" v-on:click="toPage(lastOne)"></span>
		<span class="nex" v-on:click="toPage(nextOne)"></span>
	</div>
	<div style="width: 61px; height: 23px; text-align: center; line-height: 23px; color: #999; border: 1px solid #999; border-radius: 2px; clear: both; font-size: 12px; margin: 10px 0;">
		热门追踪
	</div>
	<ul id="tj" class="searchli" style="padding: 0; color: #999;">
		<li v-for="s in subscribe">
			<p>
				<a href="javascript:;" v-on:click="toPage(s.id)">
					[{{s.cate_name}}]&nbsp;{{s.title}}
				</a>
			</p>
			<span>{{s.input_time}}</span>
			<li>
	</ul>
</div>
<div style="padding: 10px 0; background: #FFFFFF;">
	<a href="http://a.app.qq.com/o/simple.jsp?pkgname=com.myplas.q">
		<img width="100%" src="../assets/download.png">
	</a>
</div>

<loadingPage :loading="loadingShow"></loadingPage>
<errorPage :loading="loadingHide"></errorPage>
<footerbar></footerbar>

<div class="sharelayer" v-show="share" v-on:click="sharehide"></div>
<div class="tip" v-show="share3"></div>
</div>
</template>
<script>
import footer from "../components/footer";
import loadingPage from "../components/loadingPage";
import errorPage from "../components/errorPage";
module.exports = {
	components: {
		'footerbar': footer,
		'loadingPage': loadingPage,
		'errorPage':errorPage
},
data: function() {
	return {
		title: "",
		content: "",
		cate: "",
		id: "",
		cate_id: "",
		author: "",
		time: "",
		pv: "",
		type: "",
		subscribe: [],
		share: false,
		share3: false,
		share4: false,
		loadingShow: "",
		loadingHide: ""
	}
},
beforeRouteEnter: function(to, from, next) {
	next(function(vm) {
		vm.loadingShow = true;
	});
},
beforeRouteLeave: function(to, from, next) {
	next(function() {});
	this.loadingHide = false;
},
watch: {
	title: function() {
		var _this = this;
		if(this.type == "PUBLIC") {
			this.type = "";
		} else {
			this.type = '[' + this.type + ']';
		}

		$.ajax({
			type: "post",
			url: "/mobi/wxShare/getSignPackage",
			data: {
				targetUrl: window.location.href
			},
			dataType: 'JSON'
		}).then(function(res) {
			wx.config({
				debug: false,
				appId: res.signPackage.appId,
				timestamp: res.signPackage.timestamp,
				nonceStr: res.signPackage.noncestr,
				signature: res.signPackage.signature,
				jsApiList: [
					'showOptionMenu',
					'onMenuShareTimeline',
					'onMenuShareAppMessage'
				]
			});
			wx.ready(function() {
				wx.onMenuShareTimeline({
					title: _this.type + _this.title,
					link: "http://q.myplas.com/#/headlinedetail/" + _this.id,
					imgUrl: 'http://statics.myplas.com/myapp/img/shareLogo.png',
					success: function() {

					},
					cancel: function() {

					}
				});
				wx.onMenuShareAppMessage({
					title: _this.type + _this.title,
					desc: "我的塑料网-塑料圈通讯录",
					link: "http://q.myplas.com/#/headlinedetail/" + _this.id,
					imgUrl: 'http://statics.myplas.com/myapp/img/shareLogo.png',
					type: '',
					dataUrl: '',
					success: function() {

					},
					cancel: function() {

					}
				});
			});
		}, function() {

		});
	}
},
methods: {
	shareshow: function() {
		this.share = true;
		this.share3 = true;
	},
	sharehide: function() {
		this.share = false;
		this.share3 = false;
		this.share4 = false;
	},
	toPage: function(id) {
		var _this = this;
		window.scrollTo(0, 0);
		if(id) {
			var loading = weui.loading('加载中', {
				className: 'custom-classname'
			});
			$.ajax({
				type: "post",
				url: '/api/qapi1_1/getDetailInfo',
				timeout: 15000,
				data: {
					token: window.localStorage.getItem("token"),
					id: id
				},
				dataType: 'JSON'
			}).done(function(res) {
				_this.id = res.info.id;
				_this.title = res.info.title;
				_this.cate_id = res.info.cate_id;
				_this.content = res.info.content;
				_this.time = res.info.input_time;
				_this.type = res.info.type;
				_this.pv = res.info.pv;
				_this.author = res.info.author;
				_this.lastOne = res.info.lastOne;
				_this.nextOne = res.info.nextOne;
				_this.subscribe = res.info.subscribe?res.info.subscribe.slice(0, 8):res.info.subscribe;
				switch(_this.cate_id) {
					case "1":
						_this.cate = "早盘预测";
						break;
					case "2":
						_this.cate = "塑料上游";
						break;
					case "4":
						_this.cate = "中晨塑说";
						break;
					case "5":
						_this.cate = "美金市场";
						break;
					case "9":
						_this.cate = "企业动态";
						break;
					case "11":
						_this.cate = "装置动态";
						break;
					case "13":
						_this.cate = "期刊报告";
						break;
					case "21":
						_this.cate = "期货资讯";
						break;
					case "22":
						_this.cate = "独家解读";
						break;
					default:
						_this.cate = "塑料发现";
						break;
				}
			}).fail(function() {
				_this.loadingHide = true;
			}).always(function() {
				loading.hide(function() {});
			})
		} else {
			weui.alert("没有相关文章", {
				title: '塑料圈通讯录',
				buttons: [{
					label: '确定',
					type: 'parimary',
					onClick: function() {

					}
				}]
			});
		}
	},
},
activated: function() {
	var _this = this;
	try {
		var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
		piwikTracker.trackPageView();
	} catch(err) {

	}

	window.scrollTo(0, 0);
	$.ajax({
		type: "post",
		url: "/api/qapi1_1/getDetailInfo",
		timeout: 15000,
		data: {
			id: _this.$route.params.id,
			token: window.localStorage.getItem("token")
		},
		dataType: 'JSON',
		async:true
	}).done(function(res) {
		if(res.err == 0) {
			_this.id = res.info.id;
			_this.title = res.info.title;
			_this.cate_id = res.info.cate_id;
			_this.content = res.info.content;
			_this.time = res.info.input_time;
			_this.type = res.info.type;
			_this.pv = res.info.pv;
			_this.author = res.info.author;
			_this.lastOne = res.info.lastOne;
			_this.nextOne = res.info.nextOne;
			_this.subscribe = res.info.subscribe?res.info.subscribe.slice(0, 8):res.info.subscribe;
			_this.$nextTick(function() {
				if(_this.$el.getElementsByTagName('table').length) {
					_this.$el.getElementsByTagName('table')[0].style.width = "100%";
				}
			});
			switch(_this.cate_id) {
				case "1":
					_this.cate = "早盘预测";
					break;
				case "2":
					_this.cate = "塑料上游";
					break;
				case "4":
					_this.cate = "中晨塑说";
					break;
				case "5":
					_this.cate = "美金市场";
					break;
				case "9":
					_this.cate = "企业动态";
					break;
				case "11":
					_this.cate = "装置动态";
					break;
				case "13":
					_this.cate = "期刊报告";
					break;
				case "21":
					_this.cate = "期货资讯";
					break;
				case "22":
					_this.cate = "独家解读";
					break;
				default:
					_this.cate = "塑料发现";
					break;
			}
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
		}).fail(function() {
			_this.loadingHide = true;
		}).always(function() {
			_this.loadingShow = false;
		});
	}
}
</script>