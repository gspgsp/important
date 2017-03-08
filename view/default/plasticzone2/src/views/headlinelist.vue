<template>
<div style="padding: 45px 0 70px 0;">
<header id="bigCustomerHeader" style="position: fixed; top: 0; left: 0;">
	<a class="back" href="javascript:window.history.back();"></a>
	{{cate}}
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
	<div class="swiper-container">
		<div class="swiper-wrapper">
			<div class="swiper-slide" v-on:click="changeCate(999)">
				<a v-bind:class="{on:cateid==999}">
					推荐
				</a>
			</div>
			<div class="swiper-slide" v-on:click="changeCate(2)">
				<a v-bind:class="{on:cateid==2}">
					塑料上游
				</a>
			</div>
			<div class="swiper-slide" v-on:click="changeCate(1)">
				<a v-bind:class="{on:cateid==1}">
					早盘预报
				</a>
			</div>
			<div class="swiper-slide" v-on:click="changeCate(9)">
				<a v-bind:class="{on:cateid==9}">
					企业动态
				</a>
			</div>
			<div class="swiper-slide" v-on:click="changeCate(4)">
				<a v-bind:class="{on:cateid==4}">
					中晨塑说
				</a>
			</div>
			<div class="swiper-slide" v-on:click="changeCate(5)">
				<a v-bind:class="{on:cateid==5}">
					美金市场
				</a>
			</div>
			<div class="swiper-slide" v-on:click="changeCate(21)">
				<a v-bind:class="{on:cateid==21}">
					期货资讯
				</a>
			</div>
			<div class="swiper-slide" v-on:click="changeCate(11)">
				<a v-bind:class="{on:cateid==11}">
					装置动态
				</a>
			</div>
			<div class="swiper-slide" v-on:click="changeCate(13)">
				<a v-bind:class="{on:cateid==13}">
					期刊报告
				</a>
			</div>
			<div class="swiper-slide" v-on:click="changeCate(22)">
				<a v-bind:class="{on:cateid==22}">
					独家解读
				</a>
			</div>
		</div>
	</div>
</div>
<ul class="headlineUl3">
	<li v-for="i in items">
		<router-link :to="{name:'headlinedetail',params:{id:i.id}}">
			<h3>{{i.type}}{{i.title}}</h3>
			<p>{{i.description}}</p>
			<p style="text-align: right;">{{i.input_time}}</p>
		</router-link>
	</li>
</ul>
<footerbar></footerbar>
<div class="refresh" v-bind:class="{circle:isCircle}" v-on:click="circle"></div>
<div class="arrow" v-show="isArrow" v-on:click="arrow"></div>
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
		items: [],
		cate: "",
		cateid: "",
		page: 1,
		isCircle: false,
		isArrow: false,
		keywords:""
	}
},
methods: {
	arrow: function() {
		window.scrollTo(0, 0);
	},
	getList: function(id) {
		var _this = this;
		this.cateid = id;
		if (id==999) {
			$.ajax({
				type: "post",
				url: '/api/qapi1_1/getSubscribe',
				data: {
					token: window.localStorage.getItem("token"),
					subscribe: 2
				},
				dataType: 'JSON'
			}).then(function(res) {
				if(res.err == 0) {
					_this.items = res.info;
				} else if(res.err == 1) {
					mui.alert("", res.msg, function() {
						_this.$router.push({ name: 'login' });
					});
				}
			}, function() {
	
			});			
		} else{
			$.ajax({
				type: "get",
				url: '/api/qapi1/getCateList',
				data: {
					page: 1,
					size: 10,
					cate_id: id,
					token: window.localStorage.getItem("token")
				},
				dataType: 'JSON'
			}).then(function(res) {
				console.log(res);
				if(res.err == 0) {
					_this.items = res.info;
				} else if(res.err == 1) {
					mui.alert("", res.msg, function() {
						_this.$router.push({ name: 'login' });
					});
				}
			}, function() {
	
			});
			
		}

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
					_this.items = res.data; 
				}
			}, function() {

			});
		} else {

		}
	},
	changeCate: function(id) {
		switch(id) {
			case 1:
				this.cate = "早盘预报";
				this.getList(id);
				this.$nextTick(function() {
					var swiper = new Swiper('.swiper-container', {
						slidesPerView: 4,
						spaceBetween: 15,
						freeMode: true
					});
					swiper.slideTo(2, 1000, false);
				});
				break;
			case 2:
				this.cate = "塑料上游";
				this.getList(id);
				this.$nextTick(function() {
					var swiper = new Swiper('.swiper-container', {
						slidesPerView: 4,
						spaceBetween: 15,
						freeMode: true
					});
					swiper.slideTo(1, 1000, false);
				});
				break;
			case 4:
				this.getList(id);
				this.$nextTick(function() {
					var swiper = new Swiper('.swiper-container', {
						slidesPerView: 4,
						spaceBetween: 15,
						freeMode: true
					});
					swiper.slideTo(4, 1000, false);
				});
				this.cate = "中晨塑说";
				break;
			case 5:
				this.getList(id);
				this.$nextTick(function() {
					var swiper = new Swiper('.swiper-container', {
						slidesPerView: 4,
						spaceBetween: 15,
						freeMode: true
					});
					swiper.slideTo(5, 1000, false);
				});
				this.cate = "美金市场";
				break;
			case 9:
				this.getList(id);
				this.$nextTick(function() {
					var swiper = new Swiper('.swiper-container', {
						slidesPerView: 4,
						spaceBetween: 15,
						freeMode: true
					});
					swiper.slideTo(3, 1000, false);
				});
				this.cate = "企业动态";
				break;
			case 11:
				this.getList(id);
				this.$nextTick(function() {
					var swiper = new Swiper('.swiper-container', {
						slidesPerView: 4,
						spaceBetween: 15,
						freeMode: true
					});
					swiper.slideTo(7, 1000, false);
				});
				this.cate = "装置动态";
				break;
			case 13:
				this.getList(id);
				this.$nextTick(function() {
					var swiper = new Swiper('.swiper-container', {
						slidesPerView: 4,
						spaceBetween: 15,
						freeMode: true
					});
					swiper.slideTo(8, 1000, false);
				});
				this.cate = "期刊报告";
				break;
			case 21:
				this.getList(id);
				this.$nextTick(function() {
					var swiper = new Swiper('.swiper-container', {
						slidesPerView: 4,
						spaceBetween: 15,
						freeMode: true
					});
					swiper.slideTo(6, 1000, false);
				});
				this.cate = "期货资讯";
				break;
			case 22:
				this.getList(id);
				this.$nextTick(function() {
					var swiper = new Swiper('.swiper-container', {
						slidesPerView: 4,
						spaceBetween: 15,
						freeMode: true
					});
					swiper.slideTo(9, 1000, false);
				});
				this.cate = "独家解读";
				break;
			case 999:
				this.getList(id);
				this.$nextTick(function() {
					var swiper = new Swiper('.swiper-container', {
						slidesPerView: 4,
						spaceBetween: 15,
						freeMode: true
					});
					swiper.slideTo(0, 1000, false);
				});
				this.cate = "推荐";
				break;
		}

	},
	circle: function() {
		var _this = this;
		this.isCircle = true;
		if(this.$route.params.id == 999) {
			$.ajax({
				type: "post",
				url: '/api/qapi1_1/getSubscribe',
				data: {
					token: window.localStorage.getItem("token"),
					subscribe: 2
				},
				dataType: 'JSON'
			}).then(function(res) {
				console.log(res.data);
				if(res.err == 0) {
					_this.items = res.data;
					_this.isCircle = false;
					window.scrollTo(0, 0);
					mui.toast('刷新成功', {
						duration: 'long',
						type: 'div'
					});

				} else {

				}
			}, function() {

			});

		} else {
			$.ajax({
				type: "get",
				url: '/api/qapi1/getCateList',
				data: {
					page: 1,
					size: 10,
					cate_id: _this.$route.params.id,
					token: window.localStorage.getItem("token")
				},
				dataType: 'JSON'
			}).then(function(res) {
				if(res.err == 0) {
					_this.items = res.info;
					_this.isCircle = false;
					window.scrollTo(0, 0);
					mui.toast('刷新成功', {
						duration: 'long',
						type: 'div'
					});
				}

			}, function() {

			});
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

	switch(this.$route.params.id) {
		case 1:
			this.cateid = 1;
			this.cate = "早盘预测";
			break;
		case 2:
			this.cateid = 2;
			this.cate = "塑料上游";
			break;
		case 4:
			this.cateid = 4;
			this.cate = "中晨塑说";
			break;
		case 5:
			this.cateid = 5;
			this.cate = "美金市场";
			break;
		case 9:
			this.cateid = 9;
			this.cate = "企业动态";
			break;
		case 11:
			this.cateid = 11;
			this.cate = "装置动态";
			break;
		case 13:
			this.cateid = 13;
			this.cate = "期刊报告";
			break;
		case 21:
			this.cateid = 21;
			this.cate = "期货资讯";
			break;
		case 22:
			this.cateid = 22;
			this.cate = "独家解读";
			break;
		case 999:
			this.cateid = 999;
			this.cate = "推荐";
			break;
	}
	if(this.$route.params.id == 999) {
		$.ajax({
			type: "post",
			url: '/api/qapi1_1/getSubscribe',
			data: {
				token: window.localStorage.getItem("token"),
				subscribe: 2
			},
			dataType: 'JSON'
		}).then(function(res) {
			console.log(res.data);
			if(res.err == 0) {
				_this.items = res.data
			} else {

			}
		}, function() {

		});
	} else {
		$.ajax({
			type: "get",
			url: '/api/qapi1/getCateList',
			data: {
				page: 1,
				size: 10,
				cate_id: _this.$route.params.id,
				token: window.localStorage.getItem("token")
			},
			dataType: 'JSON'
		}).then(function(res) {
			console.log(res);
			if(res.err == 0) {
				_this.items = res.info;
			} else if(res.err == 1) {
				mui.alert("", res.msg, function() {
					_this.$router.push({ name: 'login' });
				});
			}
		}, function() {

		});

	}

	this.$nextTick(function() {
		var swiper = new Swiper('.swiper-container', {
			slidesPerView: 4,
			spaceBetween: 15,
			freeMode: true
		});

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
		if(scrollTop + windowHeight >= scrollHeight) {
			_this.page++;
			$.ajax({
				type: "get",
				url: "/api/qapi1/getCateList",
				data: {
					page: _this.page,
					size: 10,
					cate_id: _this.$route.params.id,
					token: window.localStorage.getItem("token")
				},
				dataType: 'JSON'
			}).then(function(res) {
				console.log(res);
				if(res.err == 0) {
					_this.condition = true;
					_this.items = _this.items.concat(res.info);
				} else if(res.err == 1) {
					mui.alert("", res.msg, function() {
						_this.$router.push({ name: 'login' });
					});
				} else if(res.err == 2) {
					_this.condition = false;
				} else if(res.err == 3) {
					mui.toast(res.msg, {
						duration: 'long',
						type: 'div'
					});
				}
			}, function() {

			});
		}
	});
},
deactivated: function() {
	$(window).unbind('scroll');
	}

}
</script>