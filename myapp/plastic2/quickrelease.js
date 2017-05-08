webpackJsonp([27],{

/***/ 112:
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "toRelease"
  }, [_c('div', {
    staticClass: "toReleaseWrap"
  }, [_c('div', {
    staticClass: "toReleaseUl"
  }, [_c('ul', {
    staticClass: "toReleaseli",
    staticStyle: {
      "padding": "0 10px 0 0",
      "border-right": "1px solid #D9D9D9"
    }
  }, _vm._l((_vm.buy), function(b) {
    return _c('li', [_c('div', [_c('span', {
      staticStyle: {
        "color": "#999999"
      }
    }, [_vm._v(_vm._s(b.input_time))]), _vm._v(" "), _c('a', {
      staticStyle: {
        "float": "right",
        "color": "#ff5000"
      },
      on: {
        "click": function($event) {
          _vm.toReleasebsbuy2(b.p_id)
        }
      }
    }, [_vm._v("重发")])]), _vm._v(" "), _c('p', [_vm._v(_vm._s(b.content))])])
  })), _vm._v(" "), _c('ul', {
    staticClass: "toReleaseli",
    staticStyle: {
      "padding": "0 0 0 10px",
      "border-left": "1px solid #FFFFFF"
    }
  }, _vm._l((_vm.supply), function(s) {
    return _c('li', [_c('div', [_c('span', {
      staticStyle: {
        "color": "#999999"
      }
    }, [_vm._v(_vm._s(s.input_time))]), _vm._v(" "), _c('a', {
      staticStyle: {
        "float": "right",
        "color": "#ff5000"
      },
      on: {
        "click": function($event) {
          _vm.toReleasebssupply2(s.p_id)
        }
      }
    }, [_vm._v("重发")])]), _vm._v(" "), _c('p', [_vm._v(_vm._s(s.content))])])
  }))]), _vm._v(" "), _c('div', {
    staticClass: "toReleaselink"
  }, [_c('a', {
    on: {
      "click": _vm.toReleasebsbuy
    }
  }, [_c('i', {
    staticClass: "toReleasebuy"
  }), _c('br'), _vm._v("发布求购")]), _vm._v(" "), _c('a', {
    on: {
      "click": _vm.toReleasebssupply
    }
  }, [_c('i', {
    staticClass: "toReleasesupply"
  }), _c('br'), _vm._v("发布供给")])])]), _vm._v(" "), _c('div', {
    staticClass: "toReleasefooter"
  }, [_c('i', {
    staticClass: "toReleaseclose",
    on: {
      "click": _vm.toReleasehidden
    }
  })])])
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-1a5f8392", module.exports)
  }
}

/***/ }),

/***/ 31:
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(46)(
  /* script */
  __webpack_require__(88),
  /* template */
  __webpack_require__(112),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "D:\\xampp\\htdocs\\workspace2\\www\\view\\default\\plasticzone\\src\\views\\quickrelease.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] quickrelease.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-1a5f8392", Component.options)
  } else {
    hotAPI.reload("data-v-1a5f8392", Component.options)
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

/***/ 88:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });


/* harmony default export */ __webpack_exports__["default"] = ({
	data: function data() {
		return {
			buy: [],
			supply: []
		};
	},
	methods: {
		toReleasehidden: function toReleasehidden() {
			window.history.back();
		},
		toReleasebsbuy2: function toReleasebsbuy2(pid) {
			var _this = this;
			if (window.localStorage.getItem("token")) {
				_this.isReleaseshow = false;
				_this.$router.push({
					name: 'releasebsbuy',
					query: { id: pid }
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
		toReleasebssupply2: function toReleasebssupply2(pid) {
			var _this = this;
			if (window.localStorage.getItem("token")) {
				_this.isReleaseshow = false;
				_this.$router.push({
					name: 'releasebssupply',
					query: { id: pid }
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
		toReleasebsbuy: function toReleasebsbuy() {
			var _this = this;
			if (window.localStorage.getItem("token")) {
				_this.isReleaseshow = false;
				_this.$router.push({
					name: 'releasebsbuy'
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
		toReleasebssupply: function toReleasebssupply() {
			var _this = this;
			if (window.localStorage.getItem("token")) {
				_this.isReleaseshow = false;
				_this.$router.push({
					name: 'releasebssupply'
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
	activated: function activated() {
		var _this = this;
		try {
			var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
			piwikTracker.trackPageView();
		} catch (err) {}

		$.ajax({
			type: "get",
			url: "/api/qapi1/supplyDemandList",
			data: {
				page: 1,
				token: window.localStorage.getItem("token"),
				size: 5,
				type: 1
			},
			dataType: 'JSON'
		}).then(function (res) {
			if (res.err == 0) {
				_this.buy = res.data;
			} else if (res.err == 1) {
				_this.buy = [];
			} else if (res.err == 2) {
				_this.buy = [];
			}
		}, function () {});

		$.ajax({
			type: "get",
			url: "/api/qapi1/supplyDemandList",
			data: {
				page: 1,
				token: window.localStorage.getItem("token"),
				size: 5,
				type: 2
			},
			dataType: 'JSON'
		}).then(function (res) {
			if (res.err == 0) {
				_this.supply = res.data;
			} else if (res.err == 1) {
				_this.supply = [];
			} else if (res.err == 2) {
				_this.supply = [];
			}
		}, function () {});
	}
});

/***/ })

});