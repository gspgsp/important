<template>
<div style="padding: 88px 0 70px 0;">
	<header id="bigCustomerHeader" style="position: fixed; top: 0; left: 0; z-index: 100;">
		<div class="plasticSearch">
			<i class="searchIcon" style="position: absolute; top: 8px; left: 5px; margin: 0;"></i>
			<input type="text" v-on:keydown.enter="search" v-model="keywords" placeholder="搜你想搜的" />
		</div>
		<div v-on:click="search" style="width: 50px; border-radius: 0 3px 3px 0; height: 30px; line-height: 30px; font-size: 12px; font-weight: normal; background: #802800; color: #FFFFFF; position: absolute; top: 9px; right: 10px; text-align: center;">搜索</div>

		<div class="plasticnav" style="margin: 5px 0 0 0;">
			<div class="subscribe" v-on:click="subscribeClick"></div>
			<div style="width: auto; margin: 0 40px 0 0;">
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
		</div>

	</header>
	<loadingPage :loading="loadingShow"></loadingPage>
	<errorPage :loading="loadingHide"></errorPage>
	<ul class="headlineUl3">
		<li v-for="i in items">
			<router-link :to="{name:'headlinedetail',params:{id:i.id}}">
				<h3>{{i.type}}{{i.title}}</h3>
				<p>{{i.description}}</p>
				<p style="text-align: right; margin: 5px 0 0 0;">
					{{i.author}}<i class="headicon"></i>{{i.input_time}}
					<i class="headicon2"></i><span style=" color: #ff5000;">{{i.pv}}</span>
				</p>
			</router-link>
		</li>
	</ul>
	<footerbar></footerbar>

	<div class="subscribelayer" v-show="subscribeshow">
		<h3 class="subscribetitle" style="position: relative;">订阅栏目：<div class="subscribebtn" v-on:click="subscribeClose"></div></h3>
		<ul class="mysubscribe" style="border-bottom: 1px solid #D1D1D1;">
			<li v-on:click="chooseCate(2)" v-bind:class="{on:subscribe.indexOf(2)!==-1,disabled:unsubscribe.indexOf(2)!==-1}"><i class="headlineicon hicon"></i><i class="cateCheckbox"></i></li>
			<li v-on:click="chooseCate(1)" v-bind:class="{on:subscribe.indexOf(1)!==-1,disabled:unsubscribe.indexOf(1)!==-1}"><i class="headlineicon hicon2"></i><i class="cateCheckbox"></i></li>
			<li v-on:click="chooseCate(9)" v-bind:class="{on:subscribe.indexOf(9)!==-1,disabled:unsubscribe.indexOf(9)!==-1}"><i class="headlineicon hicon3"></i><i class="cateCheckbox"></i></li>
			<li v-on:click="chooseCate(4)" v-bind:class="{on:subscribe.indexOf(4)!==-1,disabled:unsubscribe.indexOf(4)!==-1}"><i class="headlineicon hicon4"></i><i class="cateCheckbox"></i></li>
			<li v-on:click="chooseCate(20)" v-bind:class="{on:subscribe.indexOf(20)!==-1,disabled:unsubscribe.indexOf(20)!==-1}"><i class="headlineicon hicon5"></i><i class="cateCheckbox"></i></li>
			<li v-on:click="chooseCate(21)" v-bind:class="{on:subscribe.indexOf(21)!==-1,disabled:unsubscribe.indexOf(21)!==-1}"><i class="headlineicon hicon6"></i><i class="cateCheckbox"></i></li>
			<li v-on:click="chooseCate(11)" v-bind:class="{on:subscribe.indexOf(11)!==-1,disabled:unsubscribe.indexOf(11)!==-1}"><i class="headlineicon hicon7"></i><i class="cateCheckbox"></i></li>
			<li v-on:click="chooseCate(13)" v-bind:class="{on:subscribe.indexOf(13)!==-1,disabled:unsubscribe.indexOf(13)!==-1}"><i class="headlineicon hicon8"></i><i class="cateCheckbox"></i></li>
			<li v-on:click="chooseCate(22)" v-bind:class="{on:subscribe.indexOf(22)!==-1,disabled:unsubscribe.indexOf(22)!==-1}"><i class="headlineicon hicon9"></i><i class="cateCheckbox"></i></li>
			<div style="width: 100%; clear: both;"></div>
		</ul>
		<h3 class="subscribetitle">制品分类：(推送至推荐栏目下)</h3>
		<ul class="mysubscribe">
			<li v-on:click="chooseCate2(1)" v-bind:class="{on:property.indexOf(1)!==-1}"><i class="propertyicon hicon"></i><i class="cateCheckbox"></i></li>
			<li v-on:click="chooseCate2(2)" v-bind:class="{on:property.indexOf(2)!==-1}"><i class="propertyicon hicon2"></i><i class="cateCheckbox"></i></li>
			<li v-on:click="chooseCate2(3)" v-bind:class="{on:property.indexOf(3)!==-1}"><i class="propertyicon hicon3"></i><i class="cateCheckbox"></i></li>
			<li v-on:click="chooseCate2(4)" v-bind:class="{on:property.indexOf(4)!==-1}"><i class="propertyicon hicon4"></i><i class="cateCheckbox"></i></li>
			<li v-on:click="chooseCate2(5)" v-bind:class="{on:property.indexOf(5)!==-1}"><i class="propertyicon hicon5"></i><i class="cateCheckbox"></i></li>
			<li v-on:click="chooseCate2(6)" v-bind:class="{on:property.indexOf(6)!==-1}"><i class="propertyicon hicon6"></i><i class="cateCheckbox"></i></li>
			<li v-on:click="chooseCate2(7)" v-bind:class="{on:property.indexOf(7)!==-1}"><i class="propertyicon hicon7"></i><i class="cateCheckbox"></i></li>
			<li v-on:click="chooseCate2(8)" v-bind:class="{on:property.indexOf(8)!==-1}"><i class="propertyicon hicon8"></i><i class="cateCheckbox"></i></li>
			<li v-on:click="chooseCate2(9)" v-bind:class="{on:property.indexOf(9)!==-1}"><i class="propertyicon hicon9"></i><i class="cateCheckbox"></i></li>
			<li v-on:click="chooseCate2(10)" v-bind:class="{on:property.indexOf(10)!==-1}"><i class="propertyicon hicon10"></i><i class="cateCheckbox"></i></li>
			<li v-on:click="chooseCate2(11)" v-bind:class="{on:property.indexOf(11)!==-1}"><i class="propertyicon hicon11"></i><i class="cateCheckbox"></i></li>
			<li v-on:click="chooseCate2(12)" v-bind:class="{on:property.indexOf(12)!==-1}"><i class="propertyicon hicon12"></i><i class="cateCheckbox"></i></li>
			<li v-on:click="chooseCate2(13)" v-bind:class="{on:property.indexOf(13)!==-1}"><i class="propertyicon hicon13"></i><i class="cateCheckbox"></i></li>
		</ul>

	</div>

	<div class="refresh" v-bind:class="{circle:isCircle}" v-on:click="circle"></div>
	<div class="arrow" v-show="isArrow" v-on:click="arrow"></div>
</div>
</template>
<script>
import footer from "../components/footer";
import loadingPage from "../components/loadingPage";
import errorPage from "../components/errorPage";
export default {
	components: {
		'footerbar': footer,
		'loadingPage': loadingPage,
		'errorPage': errorPage
	},
	data: function() {
		return {
			items: [],
			cate: "",
			cateid: "",
			page: 1,
			isCircle: false,
			isArrow: false,
			keywords: "",
			loadingShow: "",
			loadingHide: "",
			subscribeshow: false,
			subscribe: [],
			property: [],
			unsubscribe: []
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
	methods: {
		chooseCate: function(id) {
			var _this = this;
			if(this.subscribe.indexOf(id) == -1) {
				this.subscribe.push(id);
			} else {
				var index = _this.subscribe.indexOf(id);
				this.subscribe.splice(index, 1);
			}
		},
		chooseCate2: function(id) {
			var _this = this;
			if(this.property.indexOf(id) == -1) {
				this.property.push(id);
			} else {
				var index = _this.property.indexOf(id);
				this.property.splice(index, 1);
			}
		},
		arrow: function() {
			window.scrollTo(0, 0);
		},
		subscribeClick: function() {
			var _this=this;
			this.subscribeshow = true;
			$.ajax({
				type: "post",
				url: version + '/toutiao/getSelectCate',
				data: {
					token: window.localStorage.getItem("token"),
					type: 2
				},
				headers: {
					'X-UA': window.localStorage.getItem("XUA")
				},
				dataType: 'JSON'
			}).done(function(res) {
				if(res.err == 0) {
					_this.subscribe = res.data.subscribe;
					_this.property = res.data.property;
					_this.unsubscribe = res.data.unconcealed_subscribe;
				} else {
	
				}
			}).fail(function() {
	
			}).always(function() {
	
			});

		},
		subscribeClose: function() {
			var _this = this;
			this.subscribeshow = false;
			$.ajax({
				type: "post",
				url: version + '/toutiao/getSelectCate',
				data: {
					token: window.localStorage.getItem("token"),
					cate_id: _this.subscribe.join(),
					prop_id: _this.property.join(),
					type: 1
				},
				headers: {
					'X-UA': window.localStorage.getItem("XUA")
				},
				dataType: 'JSON'
			}).then(function(res) {

			}, function() {

			});
		},
		getList: function(id) {
			var _this = this;
			this.cateid = id;
			if(id == 999) {
				$.ajax({
					type: "post",
					url: version + '/toutiao/getSubscribe',
					data: {
						token: window.localStorage.getItem("token"),
						subscribe: 2
					},
					headers: {
						'X-UA': window.localStorage.getItem("XUA")
					},
					dataType: 'JSON'
				}).done(function(res) {
					if(res.err == 0) {
						_this.items = res.data;
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

				}).always(function() {

				});
			} else {
				$.ajax({
					type: "post",
					url: version + '/toutiao/getCateList',
					data: {
						page: 1,
						size: 10,
						cate_id: id,
						token: window.localStorage.getItem("token")
					},
					headers: {
						'X-UA': window.localStorage.getItem("XUA")
					},
					dataType: 'JSON'
				}).done(function(res) {
					if(res.err == 0) {
						_this.items = res.info;
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

				}).always(function() {

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
					url: version + '/toutiao/getSubscribe',
					type: 'post',
					data: {
						keywords: _this.keywords,
						page: 1,
						subscribe: 1,
						token: window.localStorage.getItem("token")
					},
					headers: {
						'X-UA': window.localStorage.getItem("XUA")
					},
					dataType: 'JSON'
				}).done(function(res) {
					if(res.err == 0) {
						_this.items = res.data;
					}
				}).fail(function() {

				}).always(function() {

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
					url: version + '/toutiao/getSubscribe',
					data: {
						token: window.localStorage.getItem("token"),
						subscribe: 2
					},
					headers: {
						'X-UA': window.localStorage.getItem("XUA")
					},
					dataType: 'JSON'
				}).done(function(res) {
					if(res.err == 0) {
						_this.items = res.data;
						_this.isCircle = false;
						window.scrollTo(0, 0);
						if(res.show_msg) {
							weui.topTips(res.show_msg, 3000);
						}
					} else {

					}
				}).fail(function() {

				}).always(function() {

				});

			} else {
				$.ajax({
					type: "post",
					url: version + '/toutiao/getCateList',
					data: {
						page: 1,
						size: 10,
						cate_id: _this.$route.params.id,
						token: window.localStorage.getItem("token")
					},
					headers: {
						'X-UA': window.localStorage.getItem("XUA")
					},
					dataType: 'JSON'
				}).done(function(res) {
					if(res.err == 0) {
						_this.items = res.info;
						_this.isCircle = false;
						window.scrollTo(0, 0);
						weui.topTips('更新成功', 3000);
					}

				}).fail(function() {

				}).always(function() {

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


		this.cateid = 999;

		$.ajax({
			type: "post",
			url: version + '/toutiao/getSubscribe',
			timeout: 15000,
			data: {
				token: window.localStorage.getItem("token"),
				subscribe: 2
			},
			headers: {
				'X-UA': window.localStorage.getItem("XUA")
			},
			dataType: 'JSON'
		}).done(function(res) {
			if(res.err == 0) {
				_this.items = res.data
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
					type: "post",
					url: version + "/toutiao/getCateList",
					data: {
						page: _this.page,
						size: 10,
						cate_id: _this.$route.params.id,
						token: window.localStorage.getItem("token")
					},
					headers: {
						'X-UA': window.localStorage.getItem("XUA")
					},
					dataType: 'JSON'
				}).then(function(res) {
					console.log(res);
					if(res.err == 0) {
						_this.condition = true;
						_this.items = _this.items.concat(res.info);
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
					} else if(res.err == 2) {
						_this.condition = false;
					} else if(res.err == 3) {
						weui.topTips(res.msg, 3000);
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