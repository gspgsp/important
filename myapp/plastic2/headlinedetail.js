webpackJsonp([4],{

/***/ 147:
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "buyWrap",
    staticStyle: {
      "padding": "45px 0 60px 0"
    }
  }, [_c('header', {
    staticStyle: {
      "position": "fixed",
      "top": "0",
      "left": "0"
    },
    attrs: {
      "id": "bigCustomerHeader"
    }
  }, [_c('a', {
    staticClass: "back",
    attrs: {
      "href": "javascript:window.history.back();"
    }
  }), _vm._v("\n\t" + _vm._s(_vm.cate) + "\n\t"), _c('a', {
    staticClass: "detailShare",
    attrs: {
      "href": "javascript:;"
    },
    on: {
      "click": function($event) {
        _vm.shareshow()
      }
    }
  })]), _vm._v(" "), _c('div', {
    staticClass: "headlinecontent",
    staticStyle: {
      "overflow": "hidden",
      "background": "#FFFFFF"
    }
  }, [_c('div', {
    staticClass: "headlinetitle"
  }, [_c('h3', [_vm._v(_vm._s(_vm.type) + " " + _vm._s(_vm.title))]), _vm._v(" "), _c('p', [_vm._v("作者:" + _vm._s(_vm.author) + " 阅读数量:"), _c('span', {
    staticStyle: {
      "color": "#ff5000"
    }
  }, [_vm._v(_vm._s(_vm.pv))]), _vm._v(" 发布时间：" + _vm._s(_vm.time))])]), _vm._v(" "), _c('div', {
    staticClass: "headlinetxt"
  }, [_c('div', {
    domProps: {
      "innerHTML": _vm._s(_vm.content)
    }
  })]), _vm._v(" "), _c('div', {
    staticStyle: {
      "overflow": "hidden"
    }
  }, [_c('span', {
    staticClass: "pre",
    on: {
      "click": function($event) {
        _vm.toPage(_vm.lastOne)
      }
    }
  }), _vm._v(" "), _c('span', {
    staticClass: "nex",
    on: {
      "click": function($event) {
        _vm.toPage(_vm.nextOne)
      }
    }
  })]), _vm._v(" "), _c('div', {
    staticStyle: {
      "width": "61px",
      "height": "23px",
      "text-align": "center",
      "line-height": "23px",
      "color": "#999",
      "border": "1px solid #999",
      "border-radius": "2px",
      "clear": "both",
      "font-size": "12px",
      "margin": "10px 0"
    }
  }, [_vm._v("\n\t\t热门追踪\n\t")]), _vm._v(" "), _c('ul', {
    staticClass: "searchli",
    staticStyle: {
      "padding": "0",
      "color": "#999"
    },
    attrs: {
      "id": "tj"
    }
  }, _vm._l((_vm.subscribe), function(s) {
    return _c('li', [_c('p', [_c('a', {
      attrs: {
        "href": "javascript:;"
      },
      on: {
        "click": function($event) {
          _vm.toPage(s.id)
        }
      }
    }, [_vm._v("\n\t\t\t\t\t[" + _vm._s(s.cate_name) + "] " + _vm._s(s.title) + "\n\t\t\t\t")])]), _vm._v(" "), _c('span', [_vm._v(_vm._s(s.input_time))])])
  }))]), _vm._v(" "), _vm._m(0), _vm._v(" "), _c('loadingPage', {
    attrs: {
      "loading": _vm.loadingShow
    }
  }), _vm._v(" "), _c('errorPage', {
    attrs: {
      "loading": _vm.loadingHide
    }
  }), _vm._v(" "), _c('footerbar'), _vm._v(" "), _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.share),
      expression: "share"
    }],
    staticClass: "sharelayer",
    on: {
      "click": _vm.sharehide
    }
  }), _vm._v(" "), _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.share3),
      expression: "share3"
    }],
    staticClass: "tip"
  })], 1)
},staticRenderFns: [function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticStyle: {
      "padding": "10px 0",
      "background": "#FFFFFF"
    }
  }, [_c('a', {
    staticClass: "downloadApp",
    attrs: {
      "href": "http://a.app.qq.com/o/simple.jsp?pkgname=com.myplas.q"
    }
  })])
}]}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-3ecd75bc", module.exports)
  }
}

/***/ }),

/***/ 48:
/***/ (function(module, exports) {

// this module is a runtime utility for cleaner component module output and will
// be included in the final webpack user bundle

module.exports = function normalizeComponent (
  rawScriptExports,
  compiledTemplate,
  scopeId,
  cssModules
) {
  var esModule
  var scriptExports = rawScriptExports = rawScriptExports || {}

  // ES6 modules interop
  var type = typeof rawScriptExports.default
  if (type === 'object' || type === 'function') {
    esModule = rawScriptExports
    scriptExports = rawScriptExports.default
  }

  // Vue.extend constructor export interop
  var options = typeof scriptExports === 'function'
    ? scriptExports.options
    : scriptExports

  // render functions
  if (compiledTemplate) {
    options.render = compiledTemplate.render
    options.staticRenderFns = compiledTemplate.staticRenderFns
  }

  // scopedId
  if (scopeId) {
    options._scopeId = scopeId
  }

  // inject cssModules
  if (cssModules) {
    var computed = Object.create(options.computed || null)
    Object.keys(cssModules).forEach(function (key) {
      var module = cssModules[key]
      computed[key] = function () { return module }
    })
    options.computed = computed
  }

  return {
    esModule: esModule,
    exports: scriptExports,
    options: options
  }
}


/***/ }),

/***/ 49:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });


/* harmony default export */ __webpack_exports__["default"] = ({
	data: function data() {
		return {
			isIndex: false,
			isRelease: false,
			isMyzone: false,
			isHeadline: false
		};
	},
	methods: {
		toQuickRelease: function toQuickRelease() {
			var _this = this;
			if (window.localStorage.getItem("token")) {
				_this.$router.push({
					name: 'quickrelease'
				});
			} else {
				weui.alert('您未登录塑料圈,无法查看企业及个人信息', {
					title: '塑料圈通讯录',
					buttons: [{
						label: '确定',
						type: 'parimary',
						onClick: function onClick() {
							_this.$router.push({
								name: 'login'
							});
						}
					}]
				});
			}
		},
		toRelease: function toRelease() {
			var _this = this;
			if (window.localStorage.getItem("token")) {
				_this.$router.push({
					name: 'release'
				});
			} else {
				weui.confirm('您未登录塑料圈,无法查看企业及个人信息', function () {
					_this.$router.push({
						name: 'login'
					});
				}, function () {}, {
					title: '塑料圈通讯录'
				});
			}
		},
		toMyzone: function toMyzone() {
			var _this = this;
			if (window.localStorage.getItem("token")) {
				_this.$router.push({
					name: 'myzone'
				});
			} else {
				weui.confirm('您未登录塑料圈,无法查看企业及个人信息', function () {
					_this.$router.push({
						name: 'login'
					});
				}, function () {}, {
					title: '塑料圈通讯录'
				});
			}
		},
		toHeadline: function toHeadline() {
			var _this = this;
			if (window.localStorage.getItem("token")) {
				_this.$router.push({
					name: 'headlinelist'
				});
			} else {
				weui.confirm('您未登录塑料圈,无法查看企业及个人信息', function () {
					_this.$router.push({
						name: 'login'
					});
				}, function () {}, {
					title: '塑料圈通讯录'
				});
			}
		}

	},
	mounted: function mounted() {
		var _this = this;
		var uri = this.$route.name;
		switch (uri) {
			case 'index':
				this.isIndex = true;
				this.isRelease = false;
				this.isMyzone = false;
				this.isHeadline = false;
				break;
			case 'release':
				this.isIndex = false;
				this.isRelease = true;
				this.isMyzone = false;
				this.isHeadline = false;
				break;
			case 'myzone':
			case 'mysupply':
			case 'mybuy':
			case 'myinvite':
			case 'myfans':
			case 'mypay':
			case 'mymsg':
			case 'mymsg2':
			case 'myinfo':
				this.isIndex = false;
				this.isRelease = false;
				this.isMyzone = true;
				this.isHeadline = false;
				break;
			case 'headline':
			case 'headlinedetail':
			case 'headlinelist':
				this.isIndex = false;
				this.isRelease = false;
				this.isMyzone = false;
				this.isHeadline = true;
				break;
		}
	}
});

/***/ }),

/***/ 50:
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(48)(
  /* script */
  __webpack_require__(49),
  /* template */
  __webpack_require__(51),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "D:\\xampp\\htdocs\\workspace2\\www\\view\\default\\plasticzone\\src\\components\\footer.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] footer.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-3efe2928", Component.options)
  } else {
    hotAPI.reload("data-v-3efe2928", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),

/***/ 51:
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', [_c('footer', {
    attrs: {
      "id": "footer"
    }
  }, [_c('ul', [_c('li', [_c('a', {
    class: {
      'footerOn': _vm.isHeadline
    },
    attrs: {
      "href": "javascript:;"
    },
    on: {
      "click": _vm.toHeadline
    }
  }, [_c('i', {
    staticClass: "foot5"
  }), _c('br'), _vm._v("头条")])]), _vm._v(" "), _c('li', [_c('router-link', {
    class: {
      'footerOn': _vm.isIndex
    },
    attrs: {
      "to": {
        name: 'index'
      }
    }
  }, [_c('i', {
    staticClass: "foot2"
  }), _c('br'), _vm._v("通讯录")])], 1), _vm._v(" "), _c('li', [_c('i', {
    staticClass: "releaseicon",
    on: {
      "click": _vm.toQuickRelease
    }
  })]), _vm._v(" "), _c('li', [_c('a', {
    class: {
      'footerOn': _vm.isRelease
    },
    attrs: {
      "href": "javascript:;"
    },
    on: {
      "click": _vm.toRelease
    }
  }, [_c('i', {
    staticClass: "foot3"
  }), _c('br'), _vm._v("供求")])]), _vm._v(" "), _c('li', [_c('a', {
    class: {
      'footerOn': _vm.isMyzone
    },
    attrs: {
      "href": "javascript:;"
    },
    on: {
      "click": _vm.toMyzone
    }
  }, [_c('i', {
    staticClass: "foot4"
  }), _c('br'), _vm._v("我的")])])])])])
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-3efe2928", module.exports)
  }
}

/***/ }),

/***/ 52:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });


/* harmony default export */ __webpack_exports__["default"] = ({
	props: ['loading']
});

/***/ }),

/***/ 53:
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(48)(
  /* script */
  __webpack_require__(52),
  /* template */
  __webpack_require__(54),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "D:\\xampp\\htdocs\\workspace2\\www\\view\\default\\plasticzone\\src\\components\\loadingPage.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] loadingPage.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-40e539ae", Component.options)
  } else {
    hotAPI.reload("data-v-40e539ae", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),

/***/ 54:
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.loading),
      expression: "loading"
    }],
    staticClass: "loadingPage"
  }, [_vm._m(0)])
},staticRenderFns: [function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "loadingWrap"
  }, [_c('div', {
    staticClass: "slqLoading"
  }), _vm._v(" "), _c('div', {
    staticClass: "slqLoadingTxt"
  }, [_vm._v("数据加载中,请稍候...")])])
}]}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-40e539ae", module.exports)
  }
}

/***/ }),

/***/ 58:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });


/* harmony default export */ __webpack_exports__["default"] = ({
	props: ['loading']
});

/***/ }),

/***/ 62:
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(48)(
  /* script */
  __webpack_require__(58),
  /* template */
  __webpack_require__(63),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "D:\\xampp\\htdocs\\workspace2\\www\\view\\default\\plasticzone\\src\\components\\errorPage.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] errorPage.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-50b3658c", Component.options)
  } else {
    hotAPI.reload("data-v-50b3658c", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),

/***/ 63:
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.loading),
      expression: "loading"
    }],
    staticClass: "errorPage"
  }, [_c('div', {
    staticClass: "errorWrap"
  }), _vm._v(" "), _vm._m(0)])
},staticRenderFns: [function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "errorTxt"
  }, [_c('a', {
    staticClass: "blue",
    attrs: {
      "href": "javascript:window.location.reload();"
    }
  }, [_vm._v("重新刷新")]), _vm._v("    \n\t\t"), _c('a', {
    staticClass: "orange",
    attrs: {
      "href": "http://q.myplas.com/"
    }
  }, [_vm._v("返回首页")])])
}]}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-50b3658c", module.exports)
  }
}

/***/ }),

/***/ 8:
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(48)(
  /* script */
  __webpack_require__(85),
  /* template */
  __webpack_require__(147),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "D:\\xampp\\htdocs\\workspace2\\www\\view\\default\\plasticzone\\src\\views\\headlinedetail.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] headlinedetail.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-3ecd75bc", Component.options)
  } else {
    hotAPI.reload("data-v-3ecd75bc", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),

/***/ 85:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__components_footer__ = __webpack_require__(50);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__components_footer___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__components_footer__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__components_loadingPage__ = __webpack_require__(53);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__components_loadingPage___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1__components_loadingPage__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__components_errorPage__ = __webpack_require__(62);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__components_errorPage___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_2__components_errorPage__);





/* harmony default export */ __webpack_exports__["default"] = ({
	components: {
		'footerbar': __WEBPACK_IMPORTED_MODULE_0__components_footer___default.a,
		'loadingPage': __WEBPACK_IMPORTED_MODULE_1__components_loadingPage___default.a,
		'errorPage': __WEBPACK_IMPORTED_MODULE_2__components_errorPage___default.a
	},
	data: function data() {
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
		};
	},
	beforeRouteEnter: function beforeRouteEnter(to, from, next) {
		next(function (vm) {
			vm.loadingShow = true;
		});
	},
	beforeRouteLeave: function beforeRouteLeave(to, from, next) {
		next(function () {});
		this.loadingHide = false;
	},
	watch: {
		title: function title() {
			var _this = this;
			if (this.type == "PUBLIC") {
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
			}).then(function (res) {
				wx.config({
					debug: false,
					appId: res.signPackage.appId,
					timestamp: res.signPackage.timestamp,
					nonceStr: res.signPackage.noncestr,
					signature: res.signPackage.signature,
					jsApiList: ['showOptionMenu', 'onMenuShareTimeline', 'onMenuShareAppMessage']
				});
				wx.ready(function () {
					wx.onMenuShareTimeline({
						title: _this.type + _this.title,
						link: "http://q.myplas.com/#/headlinedetail/" + _this.id,
						imgUrl: 'http://statics.myplas.com/myapp/img/shareLogo.png',
						success: function success() {
							$.ajax({
								type: "post",
								url: version + "/wechat/saveShareLog",
								data: {
									token: window.localStorage.getItem("token"),
									type: 3,
									id: _this.id
								},
								headers: {
									'X-UA': window.localStorage.getItem("XUA")
								},
								dataType: 'JSON'
							}).done(function (res) {}).fail(function () {});
						},
						cancel: function cancel() {}
					});
					wx.onMenuShareAppMessage({
						title: _this.type + _this.title,
						desc: "我的塑料网-塑料圈通讯录",
						link: "http://q.myplas.com/#/headlinedetail/" + _this.id,
						imgUrl: 'http://statics.myplas.com/myapp/img/shareLogo.png',
						type: '',
						dataUrl: '',
						success: function success() {
							$.ajax({
								type: "post",
								url: version + "/wechat/saveShareLog",
								data: {
									token: window.localStorage.getItem("token"),
									type: 3,
									id: _this.id
								},
								headers: {
									'X-UA': window.localStorage.getItem("XUA")
								},
								dataType: 'JSON'
							}).done(function (res) {}).fail(function () {});
						},
						cancel: function cancel() {}
					});
				});
			}, function () {});
		}
	},
	methods: {
		shareshow: function shareshow() {
			this.share = true;
			this.share3 = true;
		},
		sharehide: function sharehide() {
			this.share = false;
			this.share3 = false;
			this.share4 = false;
		},
		toPage: function toPage(id) {
			var _this = this;
			window.scrollTo(0, 0);
			if (id) {
				var loading = weui.loading('加载中', {
					className: 'custom-classname'
				});
				$.ajax({
					type: "post",
					url: version + "/toutiao/getDetailInfo",
					timeout: 15000,
					data: {
						token: window.localStorage.getItem("token"),
						id: id
					},
					headers: {
						'X-UA': window.localStorage.getItem("XUA")
					},
					dataType: 'JSON'
				}).done(function (res) {
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
					_this.subscribe = res.info.subscribe ? res.info.subscribe.slice(0, 8) : res.info.subscribe;
					switch (_this.cate_id) {
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
				}).fail(function () {
					_this.loadingHide = true;
				}).always(function () {
					loading.hide(function () {});
				});
			} else {
				weui.alert("没有相关文章", {
					title: '塑料圈通讯录',
					buttons: [{
						label: '确定',
						type: 'parimary',
						onClick: function onClick() {}
					}]
				});
			}
		}
	},
	activated: function activated() {
		var _this = this;
		try {
			var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
			piwikTracker.trackPageView();
		} catch (err) {}

		window.scrollTo(0, 0);
		$.ajax({
			type: "post",
			url: version + "/toutiao/getDetailInfo",
			timeout: 15000,
			data: {
				id: _this.$route.params.id,
				token: window.localStorage.getItem("token")
			},
			headers: {
				'X-UA': window.localStorage.getItem("XUA")
			},
			dataType: 'JSON',
			async: true
		}).done(function (res) {
			if (res.err == 0) {
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
				_this.subscribe = res.info.subscribe ? res.info.subscribe.slice(0, 8) : res.info.subscribe;
				_this.$nextTick(function () {
					if (_this.$el.getElementsByTagName('table').length) {
						_this.$el.getElementsByTagName('table')[0].style.width = "100%";
					}
				});
				switch (_this.cate_id) {
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
			} else if (res.err == 1) {
				weui.alert(res.msg, {
					title: '塑料圈通讯录',
					buttons: [{
						label: '确定',
						type: 'parimary',
						onClick: function onClick() {
							_this.$router.push({
								name: 'login'
							});
						}
					}]
				});
			}
		}).fail(function () {
			_this.loadingHide = true;
		}).always(function () {
			_this.loadingShow = false;
		});
	}
});

/***/ })

});