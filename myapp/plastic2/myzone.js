webpackJsonp([7],{

/***/ 120:
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', [_c('header', {
    staticStyle: {
      "position": "fixed",
      "top": "0",
      "left": "0",
      "z-index": "5"
    },
    attrs: {
      "id": "bigCustomerHeader"
    }
  }, [_vm._v("\r\n\t\t我的塑料圈\r\n\t\t"), _c('a', {
    staticClass: "detailShare",
    attrs: {
      "href": "javascript:;"
    },
    on: {
      "click": _vm.shareshow
    }
  })]), _vm._v(" "), _c('div', {
    staticStyle: {
      "padding": "45px 0 70px 0"
    }
  }, [_c('div', {
    staticClass: "myzoneHeader"
  }, [_c('div', {
    staticClass: "myzoneInfo"
  }, [_c('div', {
    staticStyle: {
      "width": "55px",
      "height": "55px",
      "margin": "0",
      "float": "left",
      "position": "relative"
    }
  }, [_c('div', {
    staticClass: "avator"
  }, [_c('img', {
    attrs: {
      "src": _vm.thumb
    }
  })]), _vm._v(" "), _c('i', {
    staticClass: "iconV",
    class: {
      'v1': _vm.is_pass == 1, 'v2': _vm.is_pass == 0
    }
  })]), _vm._v(" "), _c('div', {
    staticClass: "myzonetxt"
  }, [_c('p', [_vm._v(_vm._s(_vm.c_name))]), _vm._v(" "), _c('p', [_vm._v(_vm._s(_vm.name) + " " + _vm._s(_vm.mobile))]), _vm._v(" "), _c('p', [_c('router-link', {
    staticStyle: {
      "color": "#6b6767"
    },
    attrs: {
      "to": {
        name: 'myinfo'
      }
    }
  }, [_c('span', {
    staticStyle: {
      "float": "left"
    }
  }, [_vm._v("更改用户信息")]), _c('i', {
    staticClass: "iconinfo edit"
  }), _vm._v("上传名片加V认证")])], 1)]), _vm._v(" "), _c('div', {
    staticClass: "mui-clearfix"
  })]), _vm._v(" "), _c('div', {
    staticClass: "myzonenum"
  }, [_c('span', [_c('router-link', {
    attrs: {
      "to": {
        name: 'mysupply'
      }
    }
  }, [_vm._v(_vm._s(_vm.supply)), _c('br'), _vm._v("供给")])], 1), _vm._v(" "), _c('span', [_c('router-link', {
    attrs: {
      "to": {
        name: 'mybuy'
      }
    }
  }, [_vm._v(_vm._s(_vm.buy)), _c('br'), _vm._v("求购")])], 1), _vm._v(" "), _c('span', [_c('router-link', {
    attrs: {
      "to": {
        name: 'mymsg'
      }
    }
  }, [_vm._v(_vm._s(_vm.msg)), _c('br'), _vm._v("留言")])], 1), _vm._v(" "), _c('span', [_c('router-link', {
    attrs: {
      "to": {
        name: 'myinvite'
      }
    }
  }, [_vm._v(_vm._s(_vm.invite)), _c('br'), _vm._v("引荐")])], 1), _vm._v(" "), _c('span', [_c('router-link', {
    attrs: {
      "to": {
        name: 'myfans'
      }
    }
  }, [_vm._v(_vm._s(_vm.fans)), _c('br'), _vm._v("粉丝")])], 1), _vm._v(" "), _c('span', [_c('router-link', {
    attrs: {
      "to": {
        name: 'mypay'
      }
    }
  }, [_vm._v(_vm._s(_vm.pay)), _c('br'), _vm._v("关注")])], 1), _vm._v(" "), _c('span', [_c('router-link', {
    attrs: {
      "to": {
        name: 'mypoints'
      }
    }
  }, [_vm._v(_vm._s(_vm.points)), _c('br'), _vm._v("塑豆")])], 1)])]), _vm._v(" "), _c('ul', {
    staticClass: "myzoneUl"
  }, [_c('li', [_c('router-link', {
    attrs: {
      "to": {
        name: 'mysupply'
      }
    }
  }, [_c('i', {
    staticClass: "iconZone zone"
  }), _vm._v("我的供给"), _c('span', [_vm._v(_vm._s(_vm.supply))]), _vm._v(" "), _c('i', {
    staticClass: "icon2 rightArrow",
    staticStyle: {
      "right": "0",
      "top": "8px"
    }
  })])], 1), _vm._v(" "), _c('li', [_c('router-link', {
    attrs: {
      "to": {
        name: 'mybuy'
      }
    }
  }, [_c('i', {
    staticClass: "iconZone zone2"
  }), _vm._v("我的求购"), _c('span', [_vm._v(_vm._s(_vm.buy))]), _vm._v(" "), _c('i', {
    staticClass: "icon2 rightArrow",
    staticStyle: {
      "right": "0",
      "top": "8px"
    }
  })])], 1)]), _vm._v(" "), _c('ul', {
    staticClass: "myzoneUl"
  }, [_c('li', [_c('router-link', {
    attrs: {
      "to": {
        name: 'myinvite'
      }
    }
  }, [_c('i', {
    staticClass: "iconZone zone4"
  }), _vm._v("我的引荐"), _c('i', {
    staticClass: "icon2 rightArrow",
    staticStyle: {
      "right": "0",
      "top": "8px"
    }
  }), _c('span', [_vm._v(_vm._s(_vm.invite))])])], 1), _vm._v(" "), _c('li', [_c('router-link', {
    attrs: {
      "to": {
        name: 'myfans'
      }
    }
  }, [_c('i', {
    staticClass: "iconZone zone5"
  }), _vm._v("我的粉丝"), _c('i', {
    staticClass: "icon2 rightArrow",
    staticStyle: {
      "right": "0",
      "top": "8px"
    }
  }), _c('span', [_vm._v(_vm._s(_vm.fans))])])], 1), _vm._v(" "), _c('li', [_c('router-link', {
    attrs: {
      "to": {
        name: 'mypay'
      }
    }
  }, [_c('i', {
    staticClass: "iconZone zone6"
  }), _vm._v("我的关注"), _c('i', {
    staticClass: "icon2 rightArrow",
    staticStyle: {
      "right": "0",
      "top": "8px"
    }
  }), _c('span', [_vm._v(_vm._s(_vm.pay))])])], 1)]), _vm._v(" "), _c('ul', {
    staticClass: "myzoneUl"
  }, [_c('li', [_c('router-link', {
    attrs: {
      "to": {
        name: 'mymsg'
      }
    }
  }, [_c('i', {
    staticClass: "iconZone zone3"
  }), _vm._v("我的留言"), _c('span', [_vm._v("未读留言" + _vm._s(_vm.msg))]), _vm._v(" "), _c('i', {
    staticClass: "icon2 rightArrow",
    staticStyle: {
      "right": "0",
      "top": "8px"
    }
  })])], 1), _vm._v(" "), _c('li', [_c('router-link', {
    attrs: {
      "to": {
        name: 'mymsg2'
      }
    }
  }, [_c('i', {
    staticClass: "iconZone zone9"
  }), _vm._v("我的消息"), _c('span', [_vm._v("未读消息" + _vm._s(_vm.msg2))]), _vm._v(" "), _c('i', {
    staticClass: "icon2 rightArrow",
    staticStyle: {
      "right": "0",
      "top": "8px"
    }
  })])], 1)]), _vm._v(" "), _c('ul', {
    staticClass: "myzoneUl"
  }, [_c('li', [_c('router-link', {
    attrs: {
      "to": {
        name: 'mypoints'
      }
    }
  }, [_c('i', {
    staticClass: "iconZone zone7"
  }), _vm._v("我的塑豆"), _c('strong', {
    staticStyle: {
      "font-weight": "normal",
      "color": "#FF0000"
    }
  }, [_vm._v("HOT")]), _vm._v(" "), _c('span', [_vm._v("可兑换礼品")]), _c('i', {
    staticClass: "icon2 rightArrow",
    staticStyle: {
      "right": "0",
      "top": "8px"
    }
  })])], 1)]), _vm._v(" "), _c('ul', {
    staticClass: "myzoneUl"
  }, [_c('li', [_c('router-link', {
    attrs: {
      "to": {
        name: 'help'
      }
    }
  }, [_c('i', {
    staticClass: "iconZone zone10"
  }), _vm._v("帮助"), _c('span', [_vm._v("常见问题及联系客服")]), _vm._v(" "), _c('i', {
    staticClass: "icon2 rightArrow",
    staticStyle: {
      "right": "0",
      "top": "8px"
    }
  })])], 1)]), _vm._v(" "), _c('ul', {
    staticClass: "myzoneUl"
  }, [_c('li', {
    staticStyle: {
      "text-align": "center",
      "color": "#ff5000"
    },
    on: {
      "click": _vm.logout
    }
  }, [_vm._v("\r\n\t\t\t退出登录\r\n\t\t")])])]), _vm._v(" "), _c('footerbar'), _vm._v(" "), _c('loadingPage', {
    attrs: {
      "loading": _vm.loadingShow
    }
  }), _vm._v(" "), _c('div', {
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
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-273c2cd5", module.exports)
  }
}

/***/ }),

/***/ 22:
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(46)(
  /* script */
  __webpack_require__(79),
  /* template */
  __webpack_require__(120),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "D:\\xampp\\htdocs\\workspace2\\www\\view\\default\\plasticzone\\src\\views\\myzone.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] myzone.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-273c2cd5", Component.options)
  } else {
    hotAPI.reload("data-v-273c2cd5", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),

/***/ 46:
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

/***/ 47:
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
		toMyzone: function toMyzone() {
			var _this = this;
			if (window.localStorage.getItem("token")) {
				_this.$router.push({
					name: 'myzone'
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
		toHeadline: function toHeadline() {
			var _this = this;
			if (window.localStorage.getItem("token")) {
				_this.$router.push({
					name: 'headline'
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

/***/ 48:
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(46)(
  /* script */
  __webpack_require__(47),
  /* template */
  __webpack_require__(49),
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

/***/ 49:
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', [_c('footer', {
    attrs: {
      "id": "footer"
    }
  }, [_c('ul', [_c('li', [_c('a', {
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
  }), _c('br'), _vm._v("供求")])]), _vm._v(" "), _c('li', [_c('router-link', {
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
  }), _c('br'), _vm._v("发现")])]), _vm._v(" "), _c('li', [_c('a', {
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

/***/ 50:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });


/* harmony default export */ __webpack_exports__["default"] = ({
	props: ['loading']
});

/***/ }),

/***/ 51:
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(46)(
  /* script */
  __webpack_require__(50),
  /* template */
  __webpack_require__(52),
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

/***/ 52:
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

/***/ 79:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__components_footer__ = __webpack_require__(48);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__components_footer___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__components_footer__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__components_loadingPage__ = __webpack_require__(51);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__components_loadingPage___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1__components_loadingPage__);




/* harmony default export */ __webpack_exports__["default"] = ({
	components: {
		'footerbar': __WEBPACK_IMPORTED_MODULE_0__components_footer___default.a,
		'loadingPage': __WEBPACK_IMPORTED_MODULE_1__components_loadingPage___default.a
	},
	data: function data() {
		return {
			buy: "",
			supply: "",
			points: "",
			fans: "",
			pay: "",
			invite: "",
			msg: "",
			msg2: "",
			c_name: "",
			name: "",
			mobile: "",
			mobile2: "",
			thumb: "",
			is_pass: "",
			share: false,
			share3: false,
			loadingShow: ""
		};
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
		logout: function logout() {
			var _this = this;
			$.ajax({
				url: '/api/qapi1/logOut',
				type: 'get',
				data: {
					token: window.localStorage.getItem("token")
				},
				dataType: 'JSON'
			}).then(function (res) {
				console.log(res.err);
				if (res.err == 0) {
					window.localStorage.setItem("token", "");
					weui.alert(res.msg, {
						title: '塑料圈通讯录',
						buttons: [{
							label: '确定',
							type: 'parimary',
							onClick: function onClick() {
								_this.$router.push({
									name: 'index'
								});
							}
						}]
					});
				} else {
					window.localStorage.setItem("token", "");
				}
			}, function () {});
		}
	},
	activated: function activated() {
		var _this = this;
		window.scrollTo(0, 0);
		this.loadingShow = true;
		try {
			var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
			piwikTracker.trackPageView();
		} catch (err) {}

		$.ajax({
			url: '/api/qapi1/myZone',
			type: 'get',
			data: {
				token: window.localStorage.getItem("token")
			},
			dataType: 'JSON'
		}).done(function (res) {
			if (res.err == 1) {
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
			} else {
				_this.name = res.data.name;
				_this.c_name = res.data.c_name;
				_this.mobile = res.data.mobile;
				_this.mobile2 = res.data.mobile;
				_this.thumb = res.data.thumb;
				_this.is_pass = res.data.is_pass;
				_this.buy = res.s_in_count;
				_this.supply = res.s_out_count;
				_this.points = res.points;
				_this.msg = res.leaveword;
				_this.msg2 = res.message;
				_this.invite = res.introduction;
				_this.fans = res.myfans;
				_this.pay = res.myconcerns;
			}
		}).fail(function () {}).always(function () {
			_this.loadingShow = false;
		});
	}
});

/***/ })

});