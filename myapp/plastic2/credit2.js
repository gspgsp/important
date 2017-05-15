webpackJsonp([17],{

/***/ 136:
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "buyWrap",
    staticStyle: {
      "padding-bottom": "70px"
    }
  }, [_vm._m(0), _vm._v(" "), _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.creditshow),
      expression: "creditshow"
    }]
  }, [_c('div', {
    staticClass: "creditwrap"
  }, [_c('div', {
    staticClass: "notice"
  }), _vm._v(" "), _c('h3', {
    staticStyle: {
      "font-size": "18px",
      "color": "#333333",
      "text-align": "center",
      "margin": "10px 0"
    }
  }, [_vm._v("热烈祝贺" + _vm._s(_vm.c_name))]), _vm._v(" "), _c('h3', {
    staticStyle: {
      "font-size": "13px",
      "color": "#333333",
      "text-align": "center"
    }
  }, [_vm._v("\r\n\t\t获得信用"), _c('span', {
    staticStyle: {
      "color": "#ff5000"
    }
  }, [_vm._v(_vm._s(_vm.credit_level))]), _vm._v("\r\n\t\t级客户称号/"), _c('span', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.is_credit === '2'),
      expression: "is_credit==='2'"
    }]
  }, [_vm._v("预计")]), _vm._v("获得"), _c('span', {
    staticStyle: {
      "color": "#ff5000"
    }
  }, [_vm._v(_vm._s(_vm.credit_limit))]), _vm._v("万授信额度")]), _vm._v(" "), _c('p', [_vm._v("\r\n\t\t\t\t经“我的塑料网”塑料电商交易平台信用认证，贵司企业信用良好，为" + _vm._s(_vm.credit_level) + "级，"), _c('span', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.is_credit === '2'),
      expression: "is_credit==='2'"
    }]
  }, [_vm._v("预计")]), _vm._v("授信额度："), _c('span', [_vm._v(_vm._s(_vm.credit_limit) + "万")]), _vm._v("元人民币，特发此证！\r\n\t\t\t")])]), _vm._v(" "), _c('div', {
    staticStyle: {
      "text-align": "center",
      "margin": "50px 0"
    }
  }, [_c('div', {
    staticClass: "creditbg"
  }, [_c('div', {
    staticClass: "creditname"
  }, [_vm._v(_vm._s(_vm.c_name))]), _vm._v(" "), _c('div', {
    staticClass: "credittxt"
  }, [_vm._v("经我司评定，确认贵单位为二○一七年度信用" + _vm._s(_vm.credit_level) + "级客户，授信额度" + _vm._s(_vm.credit_limit) + "万人民币，有效期一年。")])])]), _vm._v(" "), _c('div', {
    staticClass: "creditbtn"
  }, [_c('span', {
    staticClass: "green",
    on: {
      "click": _vm.toCreditintro
    }
  }, [_vm._v("?授信说明")])])]), _vm._v(" "), _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (!_vm.creditshow),
      expression: "!creditshow"
    }],
    staticStyle: {
      "text-align": "center",
      "padding": "20px"
    }
  }, [_vm._v("\r\n\t\t" + _vm._s(_vm.msg) + "\r\n\t")]), _vm._v(" "), _c('footerbar')], 1)
},staticRenderFns: [function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('header', {
    attrs: {
      "id": "bigCustomerHeader"
    }
  }, [_c('a', {
    staticStyle: {
      "position": "absolute",
      "top": "9px",
      "left": "10px",
      "width": "30px",
      "height": "30px",
      "line-height": "30px",
      "color": "#ffffff",
      "font-size": "13px"
    },
    attrs: {
      "href": "http://q.myplas.com/"
    }
  }, [_vm._v("首页")]), _vm._v("\r\n\t\t企业信用信息\r\n\t")])
}]}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-9b80f7a8", module.exports)
  }
}

/***/ }),

/***/ 4:
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(46)(
  /* script */
  __webpack_require__(61),
  /* template */
  __webpack_require__(136),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "D:\\xampp\\htdocs\\workspace2\\www\\view\\default\\plasticzone\\src\\views\\credit2.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] credit2.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-9b80f7a8", Component.options)
  } else {
    hotAPI.reload("data-v-9b80f7a8", Component.options)
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

/***/ 61:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__components_footer__ = __webpack_require__(48);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__components_footer___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__components_footer__);



/* harmony default export */ __webpack_exports__["default"] = ({
	components: {
		'footerbar': __WEBPACK_IMPORTED_MODULE_0__components_footer___default.a
	},
	data: function data() {
		return {
			c_name: "",
			credit_level: "",
			credit_limit: "",
			user_id: "",
			is_credit: "",
			creditshow: true,
			msg: ""
		};
	},
	methods: {
		toCreditintro: function toCreditintro() {
			this.$router.push({ name: "creditintro" });
		}
	},
	activated: function activated() {
		var _this = this;
		try {
			var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
			piwikTracker.trackPageView();
		} catch (err) {}
		$.ajax({
			type: "post",
			url: "/api/qapi1_1/creditCertificate",
			data: {
				link_id: _this.$route.params.id
			},
			dataType: 'JSON'
		}).done(function (res) {
			if (res.err == 0) {
				_this.creditshow = true;
				_this.c_name = res.data.c_name;
				_this.credit_level = res.data.credit_level;
				_this.credit_limit = res.data.credit_limit / 10000;
				_this.user_id = res.data.user_id;
				_this.is_credit = res.data.is_credit;
				$.ajax({
					type: "post",
					url: "/mobi/wxShare/getSignPackage",
					data: {
						targetUrl: window.location.href,
						random: Math.random()
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
							title: "热烈祝贺" + _this.c_name + "获得企业信用等级证书" + _this.credit_limit + "万",
							link: 'http://q.myplas.com/#/credit2/' + _this.user_id,
							imgUrl: 'http://statics.myplas.com/myapp/img/shareLogo.png',
							success: function success() {},
							cancel: function cancel() {}
						});
						wx.onMenuShareAppMessage({
							title: "热烈祝贺" + _this.c_name + "获得企业信用等级证书" + _this.credit_limit + "万",
							desc: "塑料圈通讯录",
							link: 'http://q.myplas.com/#/credit2/' + _this.user_id,
							imgUrl: 'http://statics.myplas.com/myapp/img/shareLogo.png',
							type: '',
							dataUrl: '',
							success: function success() {},
							cancel: function cancel() {}
						});
					});
				}, function () {});
			} else if (res.err == 2) {
				_this.creditshow = false;
				_this.msg = res.msg;
			}
		}).fail(function () {}).always(function () {});
	}
});

/***/ })

});