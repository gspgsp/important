webpackJsonp([6],{

/***/ 110:
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "buyWrap",
    staticStyle: {
      "padding": "45px 0 70px 0"
    }
  }, [_vm._m(0), _vm._v(" "), _c('div', {
    staticClass: "personInfo"
  }, [_c('div', {
    staticStyle: {
      "float": "left",
      "width": "100%",
      "margin": "0 0 17px 0"
    }
  }, [_c('div', {
    staticStyle: {
      "width": "80px",
      "height": "80px",
      "margin": "0 15px 0 0",
      "position": "relative",
      "float": "left"
    }
  }, [_c('div', {
    staticClass: "personAvator",
    on: {
      "click": _vm.check
    }
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
    staticClass: "personName",
    staticStyle: {
      "margin": "20px 0 0 0"
    }
  }, [_vm._v("\n\t\t\t\t" + _vm._s(_vm.name) + " " + _vm._s(_vm.sex) + "\n\t\t\t\t"), _c('span', {
    staticClass: "orange",
    on: {
      "click": _vm.pay
    }
  }, [_vm._v(_vm._s(_vm.status))])]), _vm._v(" "), _c('div', {
    staticClass: "personNum",
    staticStyle: {
      "margin": "5px 0 0 0"
    }
  }, [_c('span', [_vm._v("发布供给："), _c('span', {
    staticStyle: {
      "color": "#63769d"
    }
  }, [_vm._v(_vm._s(_vm.sale) + "条")])]), _vm._v(" "), _c('span', [_vm._v("发布需求："), _c('span', {
    staticStyle: {
      "color": "#63769d"
    }
  }, [_vm._v(_vm._s(_vm.buy) + "条")])])])]), _vm._v(" "), _c('div', {
    staticClass: "personInfoList"
  }, [_c('p', [_vm._v("公司：" + _vm._s(_vm.c_name))]), _vm._v(" "), _c('p', [_vm._v("地址：" + _vm._s(_vm.address))]), _vm._v(" "), _c('p', [_vm._v("联系电话：" + _vm._s(_vm.mobile) + "\n\t\t\t\t"), _c('a', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.isMobile),
      expression: "isMobile"
    }],
    staticClass: "telephone",
    attrs: {
      "href": _vm.mobile2
    }
  })]), _vm._v(" "), (_vm.type === '0' || _vm.type === '2') ? _c('p', {
    staticStyle: {
      "border-bottom": "1px solid #D1D1D1"
    }
  }, [_vm._v("我的主营：" + _vm._s(_vm.need_product))]) : _vm._e(), _vm._v(" "), (_vm.type === '3' || _vm.type === '1') ? _c('p', {
    staticStyle: {
      "border-bottom": "1px solid #D1D1D1"
    }
  }, [_vm._v("我的需求：" + _vm._s(_vm.need_product))]) : _vm._e(), _vm._v(" "), (_vm.type === '3' || _vm.type === '1') ? _c('p', {
    staticStyle: {
      "border-bottom": "1px solid #D1D1D1"
    }
  }, [_vm._v("生产产品：" + _vm._s(_vm.main_product))]) : _vm._e(), _vm._v(" "), (_vm.type === '3' || _vm.type === '1') ? _c('p', {
    staticStyle: {
      "border-bottom": "1px solid #D1D1D1"
    }
  }, [_vm._v("月用量：" + _vm._s(_vm.month_consum))]) : _vm._e(), _vm._v(" "), _c('div', {
    staticClass: "registerBox",
    staticStyle: {
      "height": "auto",
      "padding": "10px 0",
      "margin": "0",
      "line-height": "0",
      "text-align": "center"
    }
  }, [_c('div', {
    staticClass: "card",
    on: {
      "click": _vm.cardcheck
    }
  }, [_c('img', {
    attrs: {
      "src": _vm.cardImg
    }
  })])])]), _vm._v(" "), _c('div', {
    staticClass: "personInfoList"
  }, [_c('h3', {
    staticClass: "supplydemandtitle"
  }, [_vm._v("\n\t\t\t\t最近求购信息"), _c('router-link', {
    staticStyle: {
      "color": "#ff4f00"
    },
    attrs: {
      "to": {
        name: 'releasebuy',
        params: {
          id: _vm.$route.params.id
        }
      }
    }
  }, [_vm._v("查看更多>>")])], 1), _vm._v(" "), _c('ul', {
    staticClass: "supplydemandul"
  }, _vm._l((_vm.buylist), function(b) {
    return _c('li', [_c('span', {
      staticStyle: {
        "color": "#999999"
      }
    }, [_vm._v(_vm._s(b.input_time))]), _c('br'), _vm._v(" "), _c('span', {
      staticStyle: {
        "color": "#ec8000"
      }
    }, [_vm._v("求购")]), _vm._v(":" + _vm._s(b.contents) + "\n\t\t\t\t")])
  }))]), _vm._v(" "), _c('div', {
    staticClass: "personInfoList"
  }, [_c('h3', {
    staticClass: "supplydemandtitle",
    staticStyle: {
      "background": "#b8d2e3"
    }
  }, [_vm._v("\n\t\t\t\t最近供给信息"), _c('router-link', {
    staticStyle: {
      "color": "#267bd3"
    },
    attrs: {
      "to": {
        name: 'releasesupply',
        params: {
          id: _vm.$route.params.id
        }
      }
    }
  }, [_vm._v("查看更多>>")])], 1), _vm._v(" "), _c('ul', {
    staticClass: "supplydemandul"
  }, _vm._l((_vm.supplylist), function(s) {
    return _c('li', [_c('span', {
      staticStyle: {
        "color": "#999999"
      }
    }, [_vm._v(_vm._s(s.input_time))]), _c('br'), _vm._v(" "), _c('span', {
      staticStyle: {
        "color": "#63769d"
      }
    }, [_vm._v("供给")]), _vm._v(":" + _vm._s(s.contents) + "\n\t\t\t\t")])
  }))])]), _vm._v(" "), _c('loadingPage', {
    attrs: {
      "loading": _vm.loadingShow
    }
  }), _vm._v(" "), _c('footerbar'), _vm._v(" "), _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.avatorCheck),
      expression: "avatorCheck"
    }],
    staticClass: "imgLayer",
    on: {
      "click": _vm.check
    }
  }, [_c('div', {
    staticClass: "avatorCheck",
    style: ({
      backgroundImage: 'url(' + _vm.thumb + ')'
    })
  })]), _vm._v(" "), _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.cardCheck),
      expression: "cardCheck"
    }],
    staticClass: "imgLayer",
    on: {
      "click": _vm.cardcheck
    }
  }, [_c('div', {
    staticClass: "avatorCheck",
    style: ({
      backgroundImage: 'url(' + _vm.cardImg + ')'
    })
  })])], 1)
},staticRenderFns: [function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticStyle: {
      "position": "fixed",
      "top": "0",
      "left": "0",
      "width": "100%",
      "z-index": "10"
    }
  }, [_c('header', {
    attrs: {
      "id": "bigCustomerHeader"
    }
  }, [_c('a', {
    staticClass: "back",
    attrs: {
      "href": "javascript:window.history.back();"
    }
  }), _vm._v("\n\t\t\t查看个人信息\n\t\t")])])
}]}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-15d1f700", module.exports)
  }
}

/***/ }),

/***/ 23:
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(46)(
  /* script */
  __webpack_require__(80),
  /* template */
  __webpack_require__(110),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "D:\\xampp\\htdocs\\workspace2\\www\\view\\default\\plasticzone\\src\\views\\personinfo.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] personinfo.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-15d1f700", Component.options)
  } else {
    hotAPI.reload("data-v-15d1f700", Component.options)
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

/***/ 80:
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
			user_id: "",
			content: "",
			is_pass: "",
			cardImg: "",
			mobile2: "",
			type: "",
			main_product: "",
			month_consum: "",
			buylist: [],
			supplylist: [],
			loadingShow: ""
		};
	},
	methods: {
		cancel: function cancel() {
			this.show = false;
		},
		check: function check() {
			this.avatorCheck == true ? this.avatorCheck = false : this.avatorCheck = true;
		},
		cardcheck: function cardcheck() {
			this.cardCheck == true ? this.cardCheck = false : this.cardCheck = true;
		},
		pay: function pay() {
			var _this = this;
			$.ajax({
				url: '/api/qapi1/focusOrCancel',
				type: 'get',
				data: {
					focused_id: _this.$route.params.id,
					token: window.localStorage.getItem("token")
				},
				dataType: 'JSON'
			}).then(function (res) {
				window.location.reload();
			}, function () {});
		}
	},
	beforeRouteEnter: function beforeRouteEnter(to, from, next) {
		next(function (vm) {
			vm.loadingShow = true;
		});
	},
	activated: function activated() {
		var _this = this;
		window.scrollTo(0, 0);
		try {
			var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
			piwikTracker.trackPageView();
		} catch (err) {}
		$.ajax({
			url: '/api/qapi1_2/getZoneFriend',
			type: 'post',
			data: {
				userid: _this.$route.params.id,
				token: window.localStorage.getItem("token")
			},
			dataType: 'JSON'
		}).done(function (res) {
			if (res.err == 0) {
				_this.name = res.data.name;
				_this.c_name = res.data.c_name;
				_this.address = res.data.address;
				_this.mobile = res.data.mobile;
				_this.mobile2 = "tel:" + res.data.mobile;
				_this.need_product = res.data.need_product;
				_this.status = res.data.status;
				_this.thumb = res.data.thumb;
				_this.buy = res.data.buy;
				_this.sale = res.data.sale;
				_this.sex = res.data.sex;
				_this.id = res.data.user_id;
				_this.is_pass = res.data.is_pass;
				_this.cardImg = res.data.thumbcard;
				_this.type = res.data.type;
				_this.main_product = res.data.main_product;
				_this.month_consum = res.data.month_consum;
				if (_this.mobile.indexOf("*") == "-1") {
					_this.isMobile = true;
				} else {
					_this.isMobile = false;
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
			} else if (res.err == 99) {
				weui.confirm(res.msg, function () {
					$.ajax({
						url: '/api/score/decScore',
						type: 'post',
						data: {
							type: 2,
							other_id: _this.$route.params.id,
							token: window.localStorage.getItem("token")
						},
						dataType: 'JSON'
					}).then(function (res) {
						if (res.err == 0) {
							$.ajax({
								url: '/api/qapi1_2/getZoneFriend',
								type: 'post',
								data: {
									userid: _this.$route.params.id,
									token: window.localStorage.getItem("token")
								},
								dataType: 'JSON'
							}).then(function (res) {
								console.log(res);
								if (res.err == 0) {
									_this.name = res.data.name;
									_this.c_name = res.data.c_name;
									_this.address = res.data.address;
									_this.mobile = res.data.mobile;
									_this.mobile2 = "tel:" + res.data.mobile;
									_this.need_product = res.data.need_product;
									_this.status = res.data.status;
									_this.thumb = res.data.thumb;
									_this.buy = res.data.buy;
									_this.sale = res.data.sale;
									_this.sex = res.data.sex;
									_this.id = res.data.user_id;
									_this.is_pass = res.data.is_pass;
									_this.type = res.data.type;
									_this.main_product = res.data.main_product;
									_this.month_consum = res.data.month_consum;
									_this.cardImg = res.data.thumbcard;
									if (_this.mobile.indexOf("*") == "-1") {
										_this.isMobile = true;
									} else {
										_this.isMobile = false;
									}
								}
							}, function () {});
						} else if (res.err == 100) {
							weui.alert(res.msg, {
								title: '塑料圈通讯录',
								buttons: [{
									label: '确定',
									type: 'parimary',
									onClick: function onClick() {
										_this.$router.push({
											name: 'pointsrule'
										});
									}
								}]
							});
						}
					}, function () {});
				}, function () {
					window.history.back();
				}, {
					title: '塑料圈通讯录'
				});
			}
		}).fail(function () {}).always(function () {
			_this.loadingShow = false;
		});

		$.ajax({
			url: '/api/qapi1/getTaPur',
			type: 'get',
			data: {
				userid: _this.$route.params.id,
				page: 1,
				size: 5,
				type: 1,
				token: window.localStorage.getItem("token")
			},
			dataType: 'JSON'
		}).then(function (res) {
			if (res.err == 0) {
				_this.buylist = res.data;
			} else if (res.err == 1) {} else if (res.err == 2) {
				_this.buylist = [];
			}
		}, function () {});

		$.ajax({
			url: '/api/qapi1/getTaPur',
			type: 'get',
			data: {
				userid: _this.$route.params.id,
				page: 1,
				size: 5,
				type: 2,
				token: window.localStorage.getItem("token")
			},
			dataType: 'JSON'
		}).then(function (res) {
			if (res.err == 0) {
				_this.supplylist = res.data;
			} else if (res.err == 1) {} else if (res.err == 2) {
				_this.supplylist = [];
			}
		}, function () {});
	}
});

/***/ })

});