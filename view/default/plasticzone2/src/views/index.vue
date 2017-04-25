<template>
<div class="buyWrap" style="padding: 90px 0 60px 0;">
	<div style="position: fixed; top: 0; left: 0; width: 100%; z-index: 10;">
		<header id="bigCustomerHeader">
			<!--<a class="headerMenu4" href="http://a.app.qq.com/o/simple.jsp?pkgname=com.myplas.q"></a>-->
			塑料圈通讯录({{member}}人)
			<a v-on:click="toLogin" class="headerMenu"></a>
		</header>
		<div class="indexsearch">
			<div class="indexsearchwrap">
				<form action="javascript:;">
					<i class="searchIcon" v-on:click="search"></i><input v-on:keydown.enter="search" type="text" placeholder="请输入公司、姓名、牌号查询" v-model="keywords" />
				</form>
			</div>
			<span class="filter" style="right: 76px;" v-on:click="filterShow2">{{txt2}}<i class="downarrow"></i></span>
			<span class="filter" v-on:click="filterShow">{{txt}}<i class="downarrow"></i></span>
		</div>
	</div>
	<div class="payfans" style="background: #ff854d;">
		<router-link style="width: 100%;" :to="{name:'mypoints'}">
			<img width="100%" src="http://statics.myplas.com/myapp/img/toShop.jpg" />
		</router-link>
		<!--<router-link :to="{name:'mypay'}">
	<div style=" display: inline-block; margin: 4px 0 0 0;">
		<div class="payfansImg"></div><span>我关注的人</span>
	</div>
</router-link>
<router-link :to="{name:'myfans'}">
	<div style=" display: inline-block; margin: 4px 0 0 0;">
		<div class="payfansImg2"></div><span>关注我的人</span>
	</div>
</router-link>-->
	</div>
	<ul id="nameUl">
		<li id="top" v-if="top">
			<div style=" width: 100%; position: relative;">
				<div style="width: 55px; height: 55px; float: left; position: relative;">
					<div class="avator">
						<img v-bind:src="top.thumb">
					</div>
					<i class="iconV" v-bind:class="{'v1':top.is_pass==1,'v2':top.is_pass==0}"></i>
				</div>
				<div class="nameinfo">
					<router-link :to="{name:'personinfo',params:{id:top.user_id}}">
						<p class="first"><i class="icon wxGs"></i><span v-html="top.c_name"></span><i class="icon wxName"></i><span v-html="top.name"></span>&nbsp;{{top.sex}}</p>
						<p class="second">
							<span v-if="top.type==='3'||top.type==='1'">产品:{{top.main_product}} 月用量:{{top.month_consum}}</span>
						</p>
						<p v-if="top.type==='3'||top.type==='1'" class="second">
							供:{{top.sale_count}} 求:{{top.buy_count}} 需求：
							<span style="color: #666666;" v-html="top.need_product"></span>
						</p>
						<p v-if="top.type==='0'||top.type==='2'" class="second">
							供:{{top.sale_count}} 求:{{top.buy_count}} 主营：
							<span style="color: #666666;" v-html="top.need_product"></span>
						</p>
						<i class="icon2 rightArrow"></i>
					</router-link>
				</div>
				<span class="toFixed">已置顶</span>
			</div>
		</li>
		<li class="static" v-show="condition" v-for="n in name">
			<div style="width: 55px; height: 55px; float: left; position: relative;">
				<div class="avator">
					<img v-bind:src="n.thumb">
				</div>
				<i class="iconV" v-bind:class="{'v1':n.is_pass==1,'v2':n.is_pass==0}"></i>
			</div>
			<div class="nameinfo">
				<router-link :to="{name:'personinfo',params:{id:n.user_id}}">
					<p class="first"><i class="icon wxGs"></i><span v-html="n.c_name"></span><i class="icon wxName"></i><span v-html="n.name"></span>&nbsp;{{n.sex}}</p>
					<p class="second">
						<span v-if="n.type==='1'">产品:{{n.main_product}} 月用量:{{n.month_consum}}</span>
					</p>
					<p v-if="n.type=='1'" class="second">
						供:{{n.sale_count}} 求:{{n.buy_count}} 需求：
						<span style="color: #666666;" v-html="n.need_product"></span>
					</p>
					<p v-if="n.type==='2'" class="second">
						供:{{n.sale_count}} 求:{{n.buy_count}} 主营：
						<span style="color: #666666;" v-html="n.need_product"></span>
					</p>
					<p v-if="n.type==='4'">
						主营产品：<span style="color: #666666;" v-html="n.main_product"></span>
					</p>
					<i class="icon2 rightArrow"></i>
				</router-link>
			</div>
		</li>
		<li v-show="!condition" style="text-align: center;">
			没有相关数据
		</li>
	</ul>
	<loadingPage :loading="loadingShow"></loadingPage>
	<footerbar></footerbar>
	<div class="refresh" v-bind:class="{circle:isCircle}" v-on:click="circle"></div>
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
			name: [],
			keywords: "",
			page: 1,
			condition: true,
			member: "",
			picarr: [],
			fans: [],
			isCircle: false,
			isArrow: false,
			region: 0,
			c_type: 0,
			txt: "所有分类",
			txt2: "全国站",
			loadingShow: "",
			top: ""
		}
	},
	methods: {
		toLogin: function() {
			if(window.localStorage.getItem("token")) {
				weui.alert("你已登录塑料圈", {
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
			} else {
				this.$router.push({
					name: 'login'
				});
			}
		},
		filterShow: function() {
			var _this = this;
			weui.actionSheet([{
				label: '所有分类',
				onClick: function() {
					_this.c_type = 0;
					_this.txt = "所有分类";
					_this.page = 1;
					$.ajax({
						type: "post",
						url: "/api/qapi1_2/getPlasticPerson",
						data: {
							keywords: "",
							page: _this.page,
							token: window.localStorage.getItem("token"),
							size: 10,
							region: _this.region,
							c_type: _this.c_type
						},
						dataType: 'JSON'
					}).done(function(res) {
						if(res.err == 0) {
							_this.condition = true;
							_this.member = res.member;
							_this.name = res.persons;
						} else if(res.err == 2) {
							_this.condition = false;
						}
					}).fail(function() {

					}).always(function() {

					});
				}
			}, {
				label: '塑料制品企业',
				onClick: function() {
					_this.c_type = 1;
					_this.txt = "塑料制品企业";
					_this.page = 1;
					$.ajax({
						type: "post",
						url: "/api/qapi1_2/getPlasticPerson",
						data: {
							keywords: "",
							page: _this.page,
							token: window.localStorage.getItem("token"),
							size: 10,
							region: _this.region,
							c_type: _this.c_type
						},
						dataType: 'JSON'
					}).done(function(res) {
						if(res.err == 0) {
							_this.condition = true;
							_this.member = res.member;
							_this.name = res.persons;
						} else if(res.err == 2) {
							_this.condition = false;
						}
					}).fail(function() {

					}).always(function() {

					});
				}
			}, {
				label: '原料供应商',
				onClick: function() {
					_this.c_type = 2;
					_this.txt = "原料供应商";
					_this.page = 1;
					$.ajax({
						type: "post",
						url: "/api/qapi1_2/getPlasticPerson",
						data: {
							keywords: "",
							page: _this.page,
							token: window.localStorage.getItem("token"),
							size: 10,
							region: _this.region,
							c_type: _this.c_type
						},
						dataType: 'JSON'
					}).done(function(res) {
						if(res.err == 0) {
							_this.condition = true;
							_this.member = res.member;
							_this.name = res.persons;
						} else if(res.err == 2) {
							_this.condition = false;
						}
					}).fail(function() {

					}).always(function() {

					});
				}
			}, {
				label: '物流服务商',
				onClick: function() {
					_this.c_type = 4;
					_this.txt = "物流服务商";
					_this.page = 1;
					$.ajax({
						type: "post",
						url: "/api/qapi1_2/getPlasticPerson",
						data: {
							keywords: "",
							page: _this.page,
							token: window.localStorage.getItem("token"),
							size: 10,
							region: _this.region,
							c_type: _this.c_type
						},
						dataType: 'JSON'
					}).done(function(res) {
						if(res.err == 0) {
							_this.condition = true;
							_this.member = res.member;
							_this.name = res.persons;
						} else if(res.err == 2) {
							_this.condition = false;
						}
					}).fail(function() {

					}).always(function() {

					});
				}
			}, {
				label: '其他',
				onClick: function() {
					_this.c_type = 5;
					_this.txt = "其他";
					_this.page = 1;
					$.ajax({
						type: "post",
						url: "/api/qapi1_2/getPlasticPerson",
						data: {
							keywords: "",
							page: _this.page,
							token: window.localStorage.getItem("token"),
							size: 10,
							region: _this.region,
							c_type: _this.c_type
						},
						dataType: 'JSON'
					}).done(function(res) {
						if(res.err == 0) {
							_this.condition = true;
							_this.member = res.member;
							_this.name = res.persons;
						} else if(res.err == 2) {
							_this.condition = false;
						}
					}).fail(function() {

					}).always(function() {

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
		filterShow2: function() {
			var _this = this;
			weui.actionSheet([{
				label: '全国站',
				onClick: function() {
					_this.region = 0;
					_this.txt2 = "全国站";
					_this.page = 1;
					$.ajax({
						type: "post",
						url: "/api/qapi1_2/getPlasticPerson",
						data: {
							keywords: "",
							page: _this.page,
							token: window.localStorage.getItem("token"),
							size: 10,
							region: _this.region,
							c_type: _this.c_type
						},
						dataType: 'JSON'
					}).done(function(res) {
						if(res.err == 0) {
							_this.condition = true;
							_this.member = res.member;
							_this.name = res.persons;
						} else if(res.err == 2) {
							_this.condition = false;
						}
					}).fail(function() {

					}).always(function() {

					});
				}
			}, {
				label: '华东',
				onClick: function() {
					_this.region = 1;
					_this.txt2 = "华东";
					_this.page = 1;
					$.ajax({
						type: "post",
						url: "/api/qapi1_2/getPlasticPerson",
						data: {
							keywords: "",
							page: _this.page,
							token: window.localStorage.getItem("token"),
							size: 10,
							region: _this.region,
							c_type: _this.c_type
						},
						dataType: 'JSON'
					}).done(function(res) {
						if(res.err == 0) {
							_this.condition = true;
							_this.member = res.member;
							_this.name = res.persons;
						} else if(res.err == 2) {
							_this.condition = false;
						}
					}).fail(function() {

					}).always(function() {

					});
				}
			}, {
				label: '华北',
				onClick: function() {
					_this.region = 2;
					_this.txt2 = "华北";
					_this.page = 1;
					$.ajax({
						type: "post",
						url: "/api/qapi1_2/getPlasticPerson",
						data: {
							keywords: "",
							page: _this.page,
							token: window.localStorage.getItem("token"),
							size: 10,
							region: _this.region,
							c_type: _this.c_type
						},
						dataType: 'JSON'
					}).done(function(res) {
						if(res.err == 0) {
							_this.condition = true;
							_this.member = res.member;
							_this.name = res.persons;
						} else if(res.err == 2) {
							_this.condition = false;
						}
					}).fail(function() {

					}).always(function() {

					});
				}
			}, {
				label: '华南',
				onClick: function() {
					_this.region = 3;
					_this.txt2 = "华南";
					_this.page = 1;
					$.ajax({
						type: "post",
						url: "/api/qapi1_2/getPlasticPerson",
						data: {
							keywords: "",
							page: _this.page,
							token: window.localStorage.getItem("token"),
							size: 10,
							region: _this.region,
							c_type: _this.c_type
						},
						dataType: 'JSON'
					}).done(function(res) {
						if(res.err == 0) {
							_this.condition = true;
							_this.member = res.member;
							_this.name = res.persons;
						} else if(res.err == 2) {
							_this.condition = false;
						}
					}).fail(function() {

					}).always(function() {

					});
				}
			}, {
				label: '其他',
				onClick: function() {
					_this.region = 4;
					_this.txt2 = "其他";
					_this.page = 1;
					$.ajax({
						type: "post",
						url: "/api/qapi1_2/getPlasticPerson",
						data: {
							keywords: "",
							page: _this.page,
							token: window.localStorage.getItem("token"),
							size: 10,
							region: _this.region,
							c_type: _this.c_type
						},
						dataType: 'JSON'
					}).done(function(res) {
						if(res.err == 0) {
							_this.condition = true;
							_this.member = res.member;
							_this.name = res.persons;
						} else if(res.err == 2) {
							_this.condition = false;
						}
					}).fail(function() {

					}).always(function() {

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
		arrow: function() {
			window.scrollTo(0, 0);
		},
		circle: function() {
			var _this = this;
			this.isCircle = true;
			$.ajax({
				type: "get",
				url: "/api/qapi1_2/getPlasticPerson",
				data: {
					keywords: "",
					page: 1,
					token: window.localStorage.getItem("token"),
					size: 10,
					region: _this.region,
					c_type: _this.c_type
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
					weui.topTips('更新成功', 3000);
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
				} catch(err) {

				}

				$.ajax({
					url: '/api/qapi1_2/getPlasticPerson',
					type: 'post',
					data: {
						keywords: _this.keywords.toLocaleUpperCase(),
						page: _this.page,
						token: window.localStorage.getItem("token"),
						size: 10,
						region: _this.region,
						c_type: _this.c_type
					},
					dataType: 'JSON'
				}).done(function(res) {
					if(res.err == 0) {
						_this.condition = true;
						_this.name = res.persons;
					} else if(res.err == 2) {
						_this.condition = false;
					}
				}).fail(function() {

				}).always(function() {

				});
			} else {
				window.location.reload();
			}
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
					url: "/api/qapi1_2/getPlasticPerson",
					data: {
						sortField: _this.sortField,
						sortOrder: _this.sortOrder,
						keywords: _this.keywords.toLocaleUpperCase(),
						page: _this.page,
						region: _this.region,
						token: window.localStorage.getItem("token"),
						c_type: _this.c_type,
						size: 10
					},
					dataType: 'JSON'
				}).then(function(res) {
					if(res.err == 0) {
						_this.condition = true;
						_this.name = _this.name.concat(res.persons);
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
					} else if(res.err == 3){
						weui.topTips(res.msg, 3000);
					}
				}, function() {

				});

			}
		}
	},
	beforeRouteEnter: function(to, from, next) {
		var _this=this;
		next(function(vm) {
			if(from.name == "login") {
				console.log("login");
				$.ajax({
					type: "get",
					url: "/api/qapi1_2/getPlasticPerson",
					data: {
						keywords: "",
						page: 1,
						token: window.localStorage.getItem("token"),
						size: 10
					},
					dataType: 'JSON'
				}).done(function(res) {
					if(res.err == 0) {
						vm.condition = true;
						vm.member = res.member;
						vm.name = res.persons;
						vm.c_type = res.show_ctype;
						if(vm.c_type == 0) {
							vm.txt = "所有分类";
						} else if(vm.c_type == 1) {
							vm.txt = "塑料制品企业";
						} else if(vm.c_type == 2) {
							vm.txt = "原料供应商";
						} else if(vm.c_type == 4) {
							vm.txt = "物流服务商";
						} else if(vm.c_type == 5) {
							vm.txt = "其他";
						}
						if(JSON.stringify(res.top) == '{}') {
							vm.top = null
						} else {
							vm.top = res.top;
						}
					} else if(res.err == 2) {
						vm.condition = false;
					}
				}).fail(function() {

				}).always(function() {
					
				});
			}
			$(window).on('scroll', function() {
				vm.loadingMore();
				var scrollTop = $(this).scrollTop();
				var liWidth = $(".static").width();
				if(scrollTop > 90) {
					$("#top").css({
						'position': 'fixed',
						'top': '90px',
						'width': liWidth + 'px'
					});
				} else {
					$("#top").css({
						'position': 'static',
						'top': '0'
					});
				}
			});
			$(window).scrollTop(window.localStorage.getItem("scrollTop"));
		});
	},
	beforeRouteLeave: function(to, from, next) {
		var _this = this;
		next(function() {

		});
		$(window).off('scroll');
		window.localStorage.setItem("scrollTop", $(window).scrollTop());
	},
	mounted: function() {
		var _this = this;
		this.loadingShow = true;
		try {
			var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
			piwikTracker.trackPageView();
		} catch(err) {

		}

		$.ajax({
			type: "get",
			url: "/api/qapi1_2/getPlasticPerson",
			data: {
				keywords: "",
				page: 1,
				token: window.localStorage.getItem("token"),
				size: 10,
				region: _this.region
			},
			dataType: 'JSON'
		}).done(function(res) {
			if(res.err == 0) {
				_this.condition = true;
				_this.member = res.member;
				_this.name = res.persons;
				_this.c_type = res.show_ctype;
				if(_this.c_type == 0) {
					_this.txt = "所有分类";
				} else if(_this.c_type == 1) {
					_this.txt = "塑料制品企业";
				} else if(_this.c_type == 2) {
					_this.txt = "原料供应商";
				} else if(_this.c_type == 4) {
					_this.txt = "物流服务商";
				} else if(_this.c_type == 5) {
					_this.txt = "其他";
				}
				if(JSON.stringify(res.top) == '{}') {
					_this.top = null
				} else {
					_this.top = res.top;
				}
			} else if(res.err == 2) {
				_this.condition = false;
			}
		}).fail(function() {

		}).always(function() {
			_this.loadingShow = false;
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