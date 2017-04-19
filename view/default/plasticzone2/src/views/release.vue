<template>
<div style=" padding: 90px 0 60px 0;">
	<div style="position: fixed; top: 0; left: 0; width: 100%; z-index: 6;">
		<header id="bigCustomerHeader">
			<span class="releaseinfo">供求信息</span>
			<div style="padding: 7px 0 0 0;">
				<div class="releasesearch" style="text-align: left;">
					<form action="javascript:;">
						<input v-on:keydown.enter="search" type="text" placeholder="请输入厂家或牌号" v-model="keywords" />
					</form>
				</div>
			</div>
			<div id="searchbox" v-on:click="selectAll" style=" font-size: 14px; background: #FFFFFF; border-radius: 3px;">
				{{txt2}}
			</div>
		</header>
		<div class="releasefilter">
			<span v-bind:class="{'on':filter1}" v-on:click="getRelease('all')">全部</span>
			<span v-bind:class="{'on':filter2}" v-on:click="getRelease('recommend')">智能推荐</span>
			<span v-bind:class="{'on':filter3}" v-on:click="getRelease('attention')">我的关注</span>
			<span v-bind:class="{'on':filter4}" v-on:click="getRelease('supplydemand')">我的供求</span>
		</div>
	</div>
	<ul id="releaseUl">
		<li id="releaseTop" v-if="top">
			<div style=" width: 100%; overflow: hidden; position: relative; background: #FFFFFF;">
				<span class="releaseFix"></span>
				<router-link :to="{name:'personinfo',params:{id:top.user_id}}" style="display: block; height: 35px; overflow: hidden;">
					<div class="myreleaseInfo">
						<div style="width: 30px; height: 30px; float: left; position: relative;">
							<div class="avator">
								<img v-bind:src="top.thumb">
							</div>
							<i class="iconV" v-bind:class="{'v1':top.is_pass==null||top.is_pass==1,'v2':top.is_pass==0}"></i>
						</div>
						<div class="myreleasetxt">
							<p style="line-height: 30px;">{{top.c_name}}&nbsp;{{top.name}}</p>
						</div>
					</div>
				</router-link>
				<div class="myreleasetxt2">
					<router-link :to="{name:'releasedetail', query:{id: top.id,userid:top.user_id}}">
						<p>
							<strong v-if="top.type==2" style=" color: #63769d;">
				<i class="iconSale"></i>供给</strong>
							<strong v-else style="color: #ea8010;">
				<i class="iconBuy"></i>求购
			</strong>
							<strong v-html="top.contents"></strong>
						</p>
					</router-link>
					<p style="color: #999999;">
						{{top.input_time}}
						<span style="margin: 0 0 0 3px; color: #999999;">
	<router-link style=" color: #999999;" :to="{name:'releasedetail', query:{id: top.id,userid:top.user_id,tab:2}}">
	<i class="releasereplyicon"></i>回复<i style="color: #63769d; font-style: normal;">({{top.saysCount}})</i>
	</router-link>
</span>
						<span style=" color: #999999;">
	<router-link style=" color: #999999;" :to="{name:'releasedetail', query:{id: top.id,userid:top.user_id,tab:1}}">
	<i class="releasesaleicon"></i>出价<i style="color: #63769d; font-style: normal;">({{top.deliverPriceCount}})</i>
	</router-link>
</span>
					</p>
				</div>
			</div>
		</li>
		<li v-for="r in release">
			<router-link :to="{name:'personinfo',params:{id:r.user_id}}" style="display: block; height: 35px; overflow: hidden;">
				<div class="myreleaseInfo">
					<div style="width: 30px; height: 30px; float: left; position: relative;">
						<div class="avator">
							<img v-bind:src="r.thumb">
						</div>
						<i class="iconV" v-bind:class="{'v1':r.is_pass==null||r.is_pass==1,'v2':r.is_pass==0}"></i>
					</div>
					<div class="myreleasetxt">
						<p style="line-height: 30px;">{{r.c_name}}&nbsp;{{r.name}}</p>
					</div>
				</div>
			</router-link>
			<div class="myreleasetxt2">
				<router-link :to="{name:'releasedetail', query:{id: r.id,userid:r.user_id}}">
					<p>
						<strong v-if="r.type==2" style=" color: #63769d;">
	<i class="iconSale"></i>供给</strong>
						<strong v-else style="color: #ea8010;">
	<i class="iconBuy"></i>求购
</strong>
						<strong v-html="r.contents"></strong>
					</p>
				</router-link>
				<p style="color: #999999;">
					{{r.input_time}}
					<span v-show="mine" style="margin: 0 0 0 3px; color: #999999;">
	<router-link style=" color: #999999;" :to="{name:'supplybuy',params:{id:r.id}}">
	<i class="releaseshareicon"></i>分享
	</router-link>
</span>
					<span v-show="mine" style="margin: 0 0 0 3px; color: #999999;">
	<router-link v-show="r.type==1" style=" color: #999999;" :to="{name:'releasebsbuy',query: { id: r.id}}">
	<i class="releaseresendicon"></i>重发
	</router-link>
	<router-link v-show="r.type==2" style=" color: #999999;" :to="{name:'releasebssupply',query: { id: r.id}}">
	<i class="releaseresendicon"></i>重发
	</router-link>
</span>
					<span style="margin: 0 0 0 3px; color: #999999;">
	<router-link v-show="!mine" style=" color: #999999;" :to="{name:'releasedetail', query:{id: r.id,userid:r.user_id,tab:2}}">
	<i class="releasereplyicon"></i>回复<i style="color: #63769d; font-style: normal;">({{r.saysCount}})</i>
	</router-link>
	<router-link v-show="mine" style=" color: #999999;" :to="{name:'releasedetail', query:{id: r.id,userid:r.user_id,tab:2}}">
	<i class="releasereplyicon"></i>看回复<i style="color: #63769d; font-style: normal;">({{r.saysCount}})</i>
	</router-link>
</span>
					<span style=" color: #999999;">
	<router-link v-show="!mine" style=" color: #999999;" :to="{name:'releasedetail', query:{id: r.id,userid:r.user_id,tab:1}}">
	<i class="releasesaleicon"></i>出价<i style="color: #63769d; font-style: normal;">({{r.deliverPriceCount}})</i>
	</router-link>
	<router-link v-show="mine" style=" color: #999999;" :to="{name:'releasedetail', query:{id: r.id,userid:r.user_id,tab:1}}">
	<i class="releasesaleicon"></i>看出价<i style="color: #63769d; font-style: normal;">({{r.deliverPriceCount}})</i>
	</router-link>
</span>
				</p>
			</div>
		</li>
	</ul>
	<div class="releaseMsg" v-if="condition==7">
		<div class="releaseMsgHead"></div>
		<div class="releaseTxt">{{errmsg}}</div>
		<router-link :to="{name:'quickrelease'}">去发布</router-link>
		<div class="releaseMsgIntro"></div>
	</div>
	<div class="releaseMsg" v-if="condition==8">
		<div class="releaseMsgHead"></div>
		<div class="releaseTxt">{{errmsg}}</div>
		<router-link :to="{name:'quickrelease'}">去发布</router-link>
	</div>
	<div class="releaseMsg" v-if="condition==2">
		<div class="releaseMsgHead2"></div>
		<div class="releaseTxt">{{errmsg}}</div>
	</div>
	<div class="releaseMsg" v-if="condition==6">
		<div class="releaseMsgHead2"></div>
		<div class="releaseTxt">{{errmsg}}</div>
	</div>
	<div class="releaseMsg" v-if="condition==9">
		<div class="releaseMsgHead3"></div>
		<div class="releaseTxt">{{errmsg}}</div>
		<router-link :to="{name:'index'}">去关注</router-link>
	</div>
	<div class="releaseMsg" v-if="condition==4">
		<div class="releaseMsgHead2"></div>
		<div class="releaseTxt">{{errmsg}}</div>
	</div>
	<loadingPage :loading="loadingShow"></loadingPage>
	<footerbar></footerbar>
	<div class="arrow" v-show="isArrow" v-on:click="arrow"></div>
</div>
</template>
<script>
import footer from "../components/footer";
import loadingPage from "../components/loadingPage";
module.exports = {
	components: {
		'footerbar': footer,
		'loadingPage': loadingPage
	},
	data: function() {
		return {
			keywords: "",
			page: 1,
			release: [],
			type: 0,
			store_house: "",
			model: "",
			f_name: "",
			deal_price: "",
			remark: "",
			show: false,
			content: "",
			id: "",
			user_id: "",
			selected: "",
			isArrow: false,
			sortfield1: "ALL",
			sortfield2: "AUTO",
			filter1: false,
			filter2: true,
			filter3: false,
			filter4: false,
			condition: null,
			txt2: "全部",
			filtershow: false,
			mine: false,
			on1: true,
			errmsg: "",
			loadingShow: "",
			top: ""
		}
	},
	beforeRouteEnter: function(to, from, next) {
		next(function(vm) {
			$(window).on('scroll', function() {
				vm.loadingMore();
				var scrollTop = $(this).scrollTop();
				var reliWidth = $("body").width();
				console.log(reliWidth);
				if(scrollTop > 90) {
					$("#releaseTop").css({
						'position': 'fixed',
						'top': '90px',
						'width': reliWidth + 'px'
					});
				} else {
					$("#releaseTop").css({
						'position': 'static',
						'top': '0'
					});
				}

			});
		});
	},
	beforeRouteLeave: function(to, from, next) {
		var _this = this;
		next(function() {

		});
		$(window).off('scroll');
	},
	methods: {
		selectAll: function() {
			var _this = this;
			weui.actionSheet([{
				label: '全部',
				onClick: function() {
					_this.txt2 = '全部';
					_this.selected = 0;
					$.ajax({
						url: '/api/qapi1_2/getReleaseMsg',
						type: 'post',
						data: {
							keywords: _this.keywords.toLocaleUpperCase(),
							page: _this.page,
							type: _this.selected,
							sortField1: _this.sortfield1,
							sortField2: _this.sortfield2,
							token: window.localStorage.getItem("token"),
							size: 10
						},
						dataType: 'JSON'
					}).then(function(res) {
						console.log(res)
						if(res.err == 0) {
							_this.release = res.data;
						} else if(res.err == 2) {
							_this.condition = res.err;
							_this.errmsg = res.msg;
							_this.release = [];
							_this.top = null;
						} else if(res.err == 4) {
							_this.condition = res.err;
							_this.errmsg = res.msg;
							_this.release = [];
							_this.top = null;
						} else if(res.err == 9) {
							_this.condition = res.err;
							_this.errmsg = res.msg;
							_this.release = [];
							_this.top = null;
						} else if(res.err == 6) {
							_this.condition = res.err;
							_this.errmsg = res.msg;
							_this.release = [];
							_this.top = null;
						} else if(res.err == 7) {
							_this.condition = res.err;
							_this.errmsg = res.msg;
							_this.release = [];
							_this.top = null;
						} else if(res.err == 8) {
							_this.condition = res.err;
							_this.errmsg = res.msg;
							_this.release = [];
							_this.top = null;
						}
					}, function() {

					});
				}
			}, {
				label: '供给',
				onClick: function() {
					_this.txt2 = '供给';
					_this.selected = 2;
					$.ajax({
						url: '/api/qapi1_2/getReleaseMsg',
						type: 'post',
						data: {
							keywords: _this.keywords.toLocaleUpperCase(),
							page: _this.page,
							type: _this.selected,
							sortField1: _this.sortfield1,
							sortField2: _this.sortfield2,
							token: window.localStorage.getItem("token"),
							size: 10
						},
						dataType: 'JSON'
					}).then(function(res) {
						if(res.err == 0) {
							_this.release = res.data;
						} else if(res.err == 2) {
							_this.condition = res.err;
							_this.errmsg = res.msg;
							_this.release = [];
							_this.top = null;
						} else if(res.err == 4) {
							_this.condition = res.err;
							_this.errmsg = res.msg;
							_this.release = [];
							_this.top = null;
						} else if(res.err == 9) {
							_this.condition = res.err;
							_this.errmsg = res.msg;
							_this.release = [];
							_this.top = null;
						} else if(res.err == 6) {
							_this.condition = res.err;
							_this.errmsg = res.msg;
							_this.release = [];
							_this.top = null;
						} else if(res.err == 7) {
							_this.condition = res.err;
							_this.errmsg = res.msg;
							_this.release = [];
							_this.top = null;
						} else if(res.err == 8) {
							_this.condition = res.err;
							_this.errmsg = res.msg;
							_this.release = [];
							_this.top = null;
						}
					}, function() {

					});
				}
			}, {
				label: '求购',
				onClick: function() {
					_this.txt2 = '求购';
					_this.selected = 1;
					$.ajax({
						url: '/api/qapi1_2/getReleaseMsg',
						type: 'post',
						data: {
							keywords: _this.keywords.toLocaleUpperCase(),
							page: _this.page,
							type: _this.selected,
							sortField1: _this.sortfield1,
							sortField2: _this.sortfield2,
							token: window.localStorage.getItem("token"),
							size: 10
						},
						dataType: 'JSON'
					}).then(function(res) {
						if(res.err == 0) {
							_this.release = res.data;
						} else if(res.err == 2) {
							_this.condition = res.err;
							_this.errmsg = res.msg;
							_this.release = [];
							_this.top = null;
						} else if(res.err == 4) {
							_this.condition = res.err;
							_this.errmsg = res.msg;
							_this.release = [];
							_this.top = null;
						} else if(res.err == 9) {
							_this.condition = res.err;
							_this.errmsg = res.msg;
							_this.release = [];
							_this.top = null;
						} else if(res.err == 6) {
							_this.condition = res.err;
							_this.errmsg = res.msg;
							_this.release = [];
							_this.top = null;
						} else if(res.err == 7) {
							_this.condition = res.err;
							_this.errmsg = res.msg;
							_this.release = [];
							_this.top = null;
						} else if(res.err == 8) {
							_this.condition = res.err;
							_this.errmsg = res.msg;
							_this.release = [];
							_this.top = null;
						}
					}, function() {

					});
				}
			}], [{
				label: '取消',
				onClick: function() {

				}
			}], {
				className: 'custom-classname'
			});
		},
		getRelease: function(cate) {
			window.scrollTo(0, 0);
			var _this = this;
			var loading = weui.loading('加载中', {
				className: 'custom-classname'
			});
			switch(cate) {
				case 'all':
					_this.filter1 = true;
					_this.filter2 = false;
					_this.filter3 = false;
					_this.filter4 = false;
					_this.mine = false;
					_this.sortfield1 = "ALL";
					_this.sortfield2 = "";
					_this.condition = true;
					_this.page = 1;
					break;
				case 'recommend':
					_this.filter1 = false;
					_this.filter2 = true;
					_this.filter3 = false;
					_this.filter4 = false;
					_this.mine = false;
					_this.sortfield1 = "";
					_this.sortfield2 = "AUTO";
					_this.condition = true;
					_this.page = 1;
					break;
				case 'attention':
					_this.filter1 = false;
					_this.filter2 = false;
					_this.filter3 = true;
					_this.filter4 = false;
					_this.mine = false;
					_this.sortfield1 = "";
					_this.sortfield2 = "CONCERN";
					_this.condition = true;
					_this.page = 1;
					break;
				case 'supplydemand':
					_this.filter1 = false;
					_this.filter2 = false;
					_this.filter3 = false;
					_this.filter4 = true;
					_this.mine = true;
					_this.sortfield1 = "";
					_this.sortfield2 = "DEMANDORSUPPLY";
					_this.condition = true;
					_this.page = 1;
				default:
					break;
			}
			$.ajax({
				url: '/api/qapi1_2/getReleaseMsg',
				type: 'post',
				data: {
					keywords: _this.keywords.toLocaleUpperCase(),
					page: _this.page,
					type: _this.selected,
					sortField1: _this.sortfield1,
					sortField2: _this.sortfield2,
					token: window.localStorage.getItem("token"),
					size: 10
				},
				dataType: 'JSON'
			}).done(function(res) {
				if(res.err == 0) {
					_this.release = res.data;
					if(JSON.stringify(res.top) == '{}') {
						_this.top = null
					} else {
						_this.top = res.top;
					}
				} else if(res.err == 2) {
					_this.condition = res.err;
					_this.errmsg = res.msg;
					_this.release = [];
					_this.top = null;
				} else if(res.err == 4) {
					_this.condition = res.err;
					_this.errmsg = res.msg;
					_this.release = [];
					_this.top = null;
				} else if(res.err == 9) {
					_this.condition = res.err;
					_this.errmsg = res.msg;
					_this.release = [];
					_this.top = null;
				} else if(res.err == 6) {
					_this.condition = res.err;
					_this.errmsg = res.msg;
					_this.release = [];
					_this.top = null;
				} else if(res.err == 7) {
					_this.condition = res.err;
					_this.errmsg = res.msg;
					_this.release = [];
					_this.top = null;
				} else if(res.err == 8) {
					_this.condition = res.err;
					_this.errmsg = res.msg;
					_this.release = [];
					_this.top = null;
				}
			}).fail(function() {

			}).always(function() {
				loading.hide(function() {});
			});
		},
		search: function() {
			var _this = this;
			this.condition = true;
			_this.filtershow = false;
			_this.filtershow2 = false;
			this.page = 1;
			try {
				var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
				piwikTracker.trackSiteSearch(this.keywords, "keywords", 20);
			} catch(err) {}

			$.ajax({
				url: '/api/qapi1_2/getReleaseMsg',
				type: 'post',
				data: {
					keywords: _this.keywords.toLocaleUpperCase(),
					page: _this.page,
					type: _this.selected,
					sortField1: _this.sortfield1,
					sortField2: _this.sortfield2,
					token: window.localStorage.getItem("token"),
					size: 10
				},
				dataType: 'JSON'
			}).then(function(res) {
				console.log(res)
				if(res.err == 0) {
					_this.release = res.data;
				} else if(res.err == 1) {
					mui.alert("", res.msg, function() {
						_this.$router.push({
							name: 'login'
						});
					});
				} else if(res.err == 2) {
					_this.condition = res.err;
					_this.errmsg = res.msg;
					_this.release = [];
					_this.top = null;
				} else if(res.err == 4) {
					_this.condition = res.err;
					_this.errmsg = res.msg;
					_this.release = [];
					_this.top = null;
				} else if(res.err == 9) {
					_this.condition = res.err;
					_this.errmsg = res.msg;
					_this.release = [];
					_this.top = null;
				} else if(res.err == 6) {
					_this.condition = res.err;
					_this.errmsg = res.msg;
					_this.release = [];
					_this.top = null;
				} else if(res.err == 7) {
					_this.condition = res.err;
					_this.errmsg = res.msg;
					_this.release = [];
					_this.top = null;
				} else if(res.err == 8) {
					_this.condition = res.err;
					_this.errmsg = res.msg;
					_this.release = [];
					_this.top = null;
				}
			}, function() {

			});
		},
		arrow: function() {
			window.scrollTo(0, 0);
		},
		loadingMore: function() {
			var _this = this;
			var scrollTop = $(window).scrollTop();
			var scrollHeight = $(document).height();
			var windowHeight = $(window).height();
			if(scrollTop + windowHeight == scrollHeight) {
				_this.page++;
				$.ajax({
					type: "post",
					url: "/api/qapi1_2/getReleaseMsg",
					data: {
						keywords: _this.keywords.toLocaleUpperCase(),
						page: _this.page,
						type: _this.selected,
						sortField1: _this.sortfield1,
						sortField2: _this.sortfield2,
						token: window.localStorage.getItem("token"),
						size: 10
					},
					dataType: 'JSON'
				}).then(function(res) {
					if(res.err == 0) {
						_this.release = _this.release.concat(res.data);
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
					} else if(res.err == 3) {
						mui.toast(res.msg, {
							duration: 'long',
							type: 'div'
						});
					}
				}, function() {

				});

			}
		}
	},
	mounted: function() {
		var _this = this;
		try {
			var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
			piwikTracker.trackPageView();
		} catch(err) {

		}
		$(window).scroll(function() {
			var scrollTop = $(this).scrollTop();
			if(scrollTop > 600) {
				_this.isArrow = true;
			} else {
				_this.isArrow = false;
			}
		});

		_this.loadingShow = true;
		$.ajax({
			url: '/api/qapi1_2/getReleaseMsg',
			type: 'post',
			data: {
				keywords: _this.keywords.toLocaleUpperCase(),
				page: _this.page,
				type: _this.selected,
				sortField1: _this.sortfield1,
				sortField2: _this.sortfield2,
				token: window.localStorage.getItem("token"),
				size: 10
			},
			dataType: 'JSON'
		}).done(function(res) {
			if(res.err == 0) {
				_this.release = res.data;
				_this.top = res.top;
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
				_this.condition = res.err;
				_this.errmsg = res.msg;
				_this.top = null;
			} else if(res.err == 4) {
				_this.condition = res.err;
				_this.errmsg = res.msg;
				_this.top = null;
			} else if(res.err == 5) {
				_this.condition = res.err;
				_this.errmsg = res.msg;
				_this.top = null;
			} else if(res.err == 6) {
				_this.condition = res.err;
				_this.errmsg = res.msg;
				_this.top = null;
			} else if(res.err == 7) {
				_this.condition = res.err;
				_this.errmsg = res.msg;
				_this.top = null;
			} else if(res.err == 8) {
				_this.condition = res.err;
				_this.errmsg = res.msg;
				_this.release = [];
				_this.top = null;
			}
		}).fail(function() {

		}).always(function() {
			_this.loadingShow = false;
		});

	}
}
</script>