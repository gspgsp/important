webpackJsonp([9],{

/***/ 101:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__components_loadingPage__ = __webpack_require__(53);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__components_loadingPage___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__components_loadingPage__);



/* harmony default export */ __webpack_exports__["default"] = ({
	components: {
		'loadingPage': __WEBPACK_IMPORTED_MODULE_0__components_loadingPage___default.a
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
				url: version + '/friend/focusOrCancel',
				type: 'post',
				data: {
					focused_id: _this.$route.params.id,
					token: window.localStorage.getItem("token")
				},
				headers: {
					'X-UA': window.localStorage.getItem("XUA")
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
			url: version + '/friend/getZoneFriend',
			type: 'post',
			data: {
				user_id: _this.$route.params.id,
				showType: 1,
				token: window.localStorage.getItem("token")
			},
			headers: {
				'X-UA': window.localStorage.getItem("XUA")
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
				var flag = false;
				var dialog = weui.dialog({
					title: '塑料圈通讯录',
					content: res.msg,
					className: 'custom-classname',
					buttons: [{
						label: '取消',
						type: 'default',
						onClick: function onClick() {
							dialog.hide(function () {
								window.history.back();
							});
						}
					}, {
						label: '确定',
						type: 'primary',
						onClick: function onClick() {
							$.ajax({
								url: version + '/friend/getZoneFriend',
								type: 'post',
								data: {
									user_id: _this.$route.params.id,
									showType: 5,
									token: window.localStorage.getItem("token")
								},
								headers: {
									'X-UA': window.localStorage.getItem("XUA")
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
									_this.type = res.data.type;
									_this.main_product = res.data.main_product;
									_this.month_consum = res.data.month_consum;
									_this.cardImg = res.data.thumbcard;
									if (_this.mobile.indexOf("*") == "-1") {
										_this.isMobile = true;
									} else {
										_this.isMobile = false;
									}
								} else if (res.err == 100) {
									_this.$router.push({
										name: 'pointsrule'
									});
								}
							}).fail(function () {}).always(function () {});
						}
					}]
				});
			}
		}).fail(function () {}).always(function () {
			_this.loadingShow = false;
		});

		$.ajax({
			url: version + '/friend/getTaPur',
			type: 'post',
			data: {
				userid: _this.$route.params.id,
				page: 1,
				size: 5,
				type: 1,
				token: window.localStorage.getItem("token")
			},
			headers: {
				'X-UA': window.localStorage.getItem("XUA")
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
			url: version + '/friend/getTaPur',
			type: 'post',
			data: {
				userid: _this.$route.params.id,
				page: 1,
				size: 5,
				type: 2,
				token: window.localStorage.getItem("token")
			},
			headers: {
				'X-UA': window.localStorage.getItem("XUA")
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

/***/ }),

/***/ 131:
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
  }, [_vm._v("\n\t\t\t最近求购信息"), _c('router-link', {
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
  }, [_vm._l((_vm.buylist), function(b) {
    return _c('li', [_c('span', {
      staticStyle: {
        "color": "#999999"
      }
    }, [_vm._v(_vm._s(b.input_time))]), _c('br'), _vm._v(" "), _c('span', {
      staticStyle: {
        "color": "#ec8000"
      }
    }, [_vm._v("求购")]), _vm._v(":" + _vm._s(b.contents) + "\n\t\t\t\t")])
  }), _vm._v(" "), (_vm.buylist.length == 0) ? _c('li', {
    staticStyle: {
      "line-height": "30px",
      "text-align": "center"
    }
  }, [_vm._v("\n\t\t\t\t\t没有更多求购信息\n\t\t\t\t")]) : _vm._e()], 2)]), _vm._v(" "), _c('div', {
    staticClass: "personInfoList"
  }, [_c('h3', {
    staticClass: "supplydemandtitle",
    staticStyle: {
      "background": "#b8d2e3"
    }
  }, [_vm._v("\n\t\t\t最近供给信息"), _c('router-link', {
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
  }, [_vm._l((_vm.supplylist), function(s) {
    return _c('li', [_c('span', {
      staticStyle: {
        "color": "#999999"
      }
    }, [_vm._v(_vm._s(s.input_time))]), _c('br'), _vm._v(" "), _c('span', {
      staticStyle: {
        "color": "#63769d"
      }
    }, [_vm._v("供给")]), _vm._v(":" + _vm._s(s.contents) + "\n\t\t\t\t")])
  }), _vm._v(" "), (_vm.supplylist.length == 0) ? _c('li', {
    staticStyle: {
      "line-height": "30px",
      "text-align": "center"
    }
  }, [_vm._v("\n\t\t\t\t\t没有更多供给信息\n\t\t\t\t")]) : _vm._e()], 2)])]), _vm._v(" "), _c('loadingPage', {
    attrs: {
      "loading": _vm.loadingShow
    }
  }), _vm._v(" "), _c('div', {
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

/***/ 24:
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(48)(
  /* script */
  __webpack_require__(101),
  /* template */
  __webpack_require__(131),
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

/***/ })

});