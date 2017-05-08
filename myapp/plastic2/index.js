webpackJsonp([2],{

/***/ 11:
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(46)(
  /* script */
  __webpack_require__(68),
  /* template */
  __webpack_require__(126),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "D:\\xampp\\htdocs\\workspace2\\www\\view\\default\\plasticzone\\src\\views\\index.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] index.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-419d7236", Component.options)
  } else {
    hotAPI.reload("data-v-419d7236", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),

/***/ 126:
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "buyWrap",
    staticStyle: {
      "padding": "90px 0 60px 0"
    }
  }, [_c('div', {
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
    staticClass: "headerMenu4",
    attrs: {
      "href": "http://a.app.qq.com/o/simple.jsp?pkgname=com.myplas.q"
    }
  }), _vm._v("\n\t\t\t塑料圈通讯录(" + _vm._s(_vm.member) + "人)\n\t\t\t"), _c('a', {
    staticClass: "headerMenu",
    on: {
      "click": _vm.toLogin
    }
  })]), _vm._v(" "), _c('div', {
    staticClass: "indexsearch"
  }, [_c('div', {
    staticClass: "indexsearchwrap"
  }, [_c('form', {
    attrs: {
      "action": "javascript:;"
    }
  }, [_c('i', {
    staticClass: "searchIcon",
    on: {
      "click": _vm.search
    }
  }), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.keywords),
      expression: "keywords"
    }],
    attrs: {
      "type": "text",
      "placeholder": "请输入公司、姓名、牌号查询"
    },
    domProps: {
      "value": (_vm.keywords)
    },
    on: {
      "keydown": function($event) {
        if (!('button' in $event) && _vm._k($event.keyCode, "enter", 13)) { return null; }
        _vm.search($event)
      },
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.keywords = $event.target.value
      }
    }
  })])]), _vm._v(" "), _c('span', {
    staticClass: "filter",
    staticStyle: {
      "right": "76px"
    },
    on: {
      "click": _vm.filterShow2
    }
  }, [_vm._v(_vm._s(_vm.txt2)), _c('i', {
    staticClass: "downarrow"
  })]), _vm._v(" "), _c('span', {
    staticClass: "filter",
    on: {
      "click": _vm.filterShow
    }
  }, [_vm._v(_vm._s(_vm.txt)), _c('i', {
    staticClass: "downarrow"
  })])])]), _vm._v(" "), (_vm.isFocus) ? _c('div', {
    staticClass: "payfans"
  }, [_c('router-link', {
    attrs: {
      "to": {
        name: 'mypay'
      }
    }
  }, [_c('div', {
    staticStyle: {
      "display": "inline-block",
      "margin": "4px 0 0 0"
    }
  }, [_c('div', {
    staticClass: "payfansImg"
  }), _c('span', [_vm._v("我关注的人")])])]), _vm._v(" "), _c('router-link', {
    attrs: {
      "to": {
        name: 'myfans'
      }
    }
  }, [_c('div', {
    staticStyle: {
      "display": "inline-block",
      "margin": "4px 0 0 0"
    }
  }, [_c('div', {
    staticClass: "payfansImg2"
  }), _c('span', [_vm._v("关注我的人")])])])], 1) : _c('div', {
    staticClass: "payfans",
    staticStyle: {
      "background": "#ff854d"
    }
  }, [_c('a', {
    staticStyle: {
      "width": "100%"
    },
    attrs: {
      "href": "http://q.myplas.com/#/pointsrule"
    }
  }, [_c('img', {
    attrs: {
      "width": "100%",
      "src": _vm.bannerImg
    }
  })])]), _vm._v(" "), _c('ul', {
    attrs: {
      "id": "nameUl"
    }
  }, [(_vm.top) ? _c('li', {
    attrs: {
      "id": "top"
    }
  }, [_c('div', {
    staticStyle: {
      "width": "100%",
      "position": "relative"
    }
  }, [_c('div', {
    staticStyle: {
      "width": "55px",
      "height": "55px",
      "float": "left",
      "position": "relative"
    }
  }, [_c('div', {
    staticClass: "avator"
  }, [_c('img', {
    attrs: {
      "src": _vm.top.thumb
    }
  })]), _vm._v(" "), _c('i', {
    staticClass: "iconV",
    class: {
      'v1': _vm.top.is_pass == 1, 'v2': _vm.top.is_pass == 0
    }
  })]), _vm._v(" "), _c('div', {
    staticClass: "nameinfo"
  }, [_c('router-link', {
    attrs: {
      "to": {
        name: 'personinfo',
        params: {
          id: _vm.top.user_id
        }
      }
    }
  }, [_c('p', {
    staticClass: "first"
  }, [_c('i', {
    staticClass: "icon wxGs"
  }), _c('span', {
    domProps: {
      "innerHTML": _vm._s(_vm.top.c_name)
    }
  }), _c('i', {
    staticClass: "icon wxName"
  }), _c('span', {
    domProps: {
      "innerHTML": _vm._s(_vm.top.name)
    }
  }), _vm._v(" " + _vm._s(_vm.top.sex))]), _vm._v(" "), _c('p', {
    staticClass: "second"
  }, [(_vm.top.type === '3' || _vm.top.type === '1') ? _c('span', [_vm._v("产品:" + _vm._s(_vm.top.main_product) + " 月用量:" + _vm._s(_vm.top.month_consum))]) : _vm._e()]), _vm._v(" "), (_vm.top.type === '3' || _vm.top.type === '1') ? _c('p', {
    staticClass: "second"
  }, [_vm._v("\n\t\t\t\t\t\t\t供:" + _vm._s(_vm.top.sale_count) + " 求:" + _vm._s(_vm.top.buy_count) + " 需求：\n\t\t\t\t\t\t\t"), _c('b', {
    staticStyle: {
      "color": "#666666",
      "font-weight": "normal"
    },
    domProps: {
      "innerHTML": _vm._s(_vm.top.need_product)
    }
  })]) : _vm._e(), _vm._v(" "), (_vm.top.type === '0' || _vm.top.type === '2') ? _c('p', {
    staticClass: "second"
  }, [_vm._v("\n\t\t\t\t\t\t\t供:" + _vm._s(_vm.top.sale_count) + " 求:" + _vm._s(_vm.top.buy_count) + " 主营：\n\t\t\t\t\t\t\t"), _c('b', {
    staticStyle: {
      "color": "#666666",
      "font-weight": "normal"
    },
    domProps: {
      "innerHTML": _vm._s(_vm.top.need_product)
    }
  })]) : _vm._e(), _vm._v(" "), _c('i', {
    staticClass: "icon2 rightArrow"
  })])], 1), _vm._v(" "), _c('span', {
    staticClass: "toFixed"
  }, [_vm._v("已置顶")])])]) : _vm._e(), _vm._v(" "), _vm._l((_vm.name), function(n) {
    return _c('li', {
      directives: [{
        name: "show",
        rawName: "v-show",
        value: (_vm.condition),
        expression: "condition"
      }],
      staticClass: "static"
    }, [_c('div', {
      staticStyle: {
        "width": "55px",
        "height": "55px",
        "float": "left",
        "position": "relative"
      }
    }, [_c('div', {
      staticClass: "avator"
    }, [_c('img', {
      attrs: {
        "src": n.thumb
      }
    })]), _vm._v(" "), _c('i', {
      staticClass: "iconV",
      class: {
        'v1': n.is_pass == 1, 'v2': n.is_pass == 0
      }
    })]), _vm._v(" "), _c('div', {
      staticClass: "nameinfo"
    }, [_c('router-link', {
      attrs: {
        "to": {
          name: 'personinfo',
          params: {
            id: n.user_id
          }
        }
      }
    }, [_c('p', {
      staticClass: "first"
    }, [_c('i', {
      staticClass: "icon wxGs"
    }), _c('span', {
      domProps: {
        "innerHTML": _vm._s(n.c_name)
      }
    }), _c('i', {
      staticClass: "icon wxName"
    }), _c('span', {
      domProps: {
        "innerHTML": _vm._s(n.name)
      }
    }), _vm._v(" " + _vm._s(n.sex))]), _vm._v(" "), _c('p', {
      staticClass: "second"
    }, [(n.type === '1') ? _c('span', [_vm._v("产品:" + _vm._s(n.main_product))]) : _vm._e(), _vm._v(" "), (n.type === '1') ? _c('span', [_vm._v("月用量:" + _vm._s(n.month_consum))]) : _vm._e()]), _vm._v(" "), (n.type == '1') ? _c('p', {
      staticClass: "second"
    }, [_vm._v("\n\t\t\t\t\t\t供:" + _vm._s(n.sale_count) + " 求:" + _vm._s(n.buy_count) + " 需求：\n\t\t\t\t\t\t"), _c('b', {
      staticStyle: {
        "color": "#666666",
        "font-weight": "normal"
      },
      domProps: {
        "innerHTML": _vm._s(n.need_product)
      }
    })]) : _vm._e(), _vm._v(" "), (n.type === '2') ? _c('p', {
      staticClass: "second"
    }, [_vm._v("\n\t\t\t\t\t\t供:" + _vm._s(n.sale_count) + " 求:" + _vm._s(n.buy_count) + " 主营：\n\t\t\t\t\t\t"), _c('b', {
      staticStyle: {
        "color": "#666666",
        "font-weight": "normal"
      },
      domProps: {
        "innerHTML": _vm._s(n.need_product)
      }
    })]) : _vm._e(), _vm._v(" "), (n.type === '4') ? _c('p', {
      staticStyle: {
        "color": "#666666"
      }
    }, [_vm._v("\n\t\t\t\t\t\t主营产品："), _c('b', {
      staticStyle: {
        "color": "#666666",
        "font-weight": "normal"
      },
      domProps: {
        "innerHTML": _vm._s(n.main_product)
      }
    })]) : _vm._e(), _vm._v(" "), _c('i', {
      staticClass: "icon2 rightArrow"
    })])], 1)])
  }), _vm._v(" "), _c('li', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (!_vm.condition),
      expression: "!condition"
    }],
    staticStyle: {
      "text-align": "center"
    }
  }, [_vm._v("\n\t\t\t没有相关数据\n\t\t")])], 2), _vm._v(" "), _c('loadingPage', {
    attrs: {
      "loading": _vm.loadingShow
    }
  }), _vm._v(" "), _c('footerbar'), _vm._v(" "), _c('div', {
    staticClass: "refresh",
    class: {
      circle: _vm.isCircle
    },
    on: {
      "click": _vm.circle
    }
  }), _vm._v(" "), _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.isArrow),
      expression: "isArrow"
    }],
    staticClass: "arrow",
    on: {
      "click": _vm.arrow
    }
  })], 1)
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-419d7236", module.exports)
  }
}

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

/***/ 56:
/***/ (function(module, exports, __webpack_require__) {

module.exports = { "default": __webpack_require__(57), __esModule: true };

/***/ }),

/***/ 57:
/***/ (function(module, exports, __webpack_require__) {

var core  = __webpack_require__(58)
  , $JSON = core.JSON || (core.JSON = {stringify: JSON.stringify});
module.exports = function stringify(it){ // eslint-disable-line no-unused-vars
  return $JSON.stringify.apply($JSON, arguments);
};

/***/ }),

/***/ 58:
/***/ (function(module, exports) {

var core = module.exports = {version: '2.4.0'};
if(typeof __e == 'number')__e = core; // eslint-disable-line no-undef

/***/ }),

/***/ 68:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_babel_runtime_core_js_json_stringify__ = __webpack_require__(56);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_babel_runtime_core_js_json_stringify___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_babel_runtime_core_js_json_stringify__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__components_footer__ = __webpack_require__(48);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__components_footer___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1__components_footer__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__components_loadingPage__ = __webpack_require__(51);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__components_loadingPage___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_2__components_loadingPage__);





/* harmony default export */ __webpack_exports__["default"] = ({
	components: {
		'footerbar': __WEBPACK_IMPORTED_MODULE_1__components_footer___default.a,
		'loadingPage': __WEBPACK_IMPORTED_MODULE_2__components_loadingPage___default.a
	},
	data: function data() {
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
			top: "",
			isFocus: true,
			bannerLink: "",
			bannerImg: ""
		};
	},
	methods: {
		toLogin: function toLogin() {
			if (window.localStorage.getItem("token")) {
				weui.alert("你已登录塑料圈", {
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
				this.$router.push({
					name: 'login'
				});
			}
		},
		filterShow: function filterShow() {
			var _this = this;
			weui.actionSheet([{
				label: '所有分类',
				onClick: function onClick() {
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
					}).done(function (res) {
						if (res.err == 0) {
							_this.condition = true;
							_this.member = res.member;
							_this.name = res.persons;
						} else if (res.err == 2) {
							_this.condition = false;
						}
					}).fail(function () {}).always(function () {});
				}
			}, {
				label: '塑料制品企业',
				onClick: function onClick() {
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
					}).done(function (res) {
						if (res.err == 0) {
							_this.condition = true;
							_this.member = res.member;
							_this.name = res.persons;
						} else if (res.err == 2) {
							_this.condition = false;
						}
					}).fail(function () {}).always(function () {});
				}
			}, {
				label: '原料供应商',
				onClick: function onClick() {
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
					}).done(function (res) {
						if (res.err == 0) {
							_this.condition = true;
							_this.member = res.member;
							_this.name = res.persons;
						} else if (res.err == 2) {
							_this.condition = false;
						}
					}).fail(function () {}).always(function () {});
				}
			}, {
				label: '物流服务商',
				onClick: function onClick() {
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
					}).done(function (res) {
						if (res.err == 0) {
							_this.condition = true;
							_this.member = res.member;
							_this.name = res.persons;
						} else if (res.err == 2) {
							_this.condition = false;
						}
					}).fail(function () {}).always(function () {});
				}
			}, {
				label: '其他',
				onClick: function onClick() {
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
					}).done(function (res) {
						if (res.err == 0) {
							_this.condition = true;
							_this.member = res.member;
							_this.name = res.persons;
						} else if (res.err == 2) {
							_this.condition = false;
						}
					}).fail(function () {}).always(function () {});
				}
			}], [{
				label: '取消',
				onClick: function onClick() {}
			}], {
				className: 'custom-classname'
			});
		},
		filterShow2: function filterShow2() {
			var _this = this;
			weui.actionSheet([{
				label: '全国站',
				onClick: function onClick() {
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
					}).done(function (res) {
						if (res.err == 0) {
							_this.condition = true;
							_this.member = res.member;
							_this.name = res.persons;
						} else if (res.err == 2) {
							_this.condition = false;
						}
					}).fail(function () {}).always(function () {});
				}
			}, {
				label: '华东',
				onClick: function onClick() {
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
					}).done(function (res) {
						if (res.err == 0) {
							_this.condition = true;
							_this.member = res.member;
							_this.name = res.persons;
						} else if (res.err == 2) {
							_this.condition = false;
						}
					}).fail(function () {}).always(function () {});
				}
			}, {
				label: '华北',
				onClick: function onClick() {
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
					}).done(function (res) {
						if (res.err == 0) {
							_this.condition = true;
							_this.member = res.member;
							_this.name = res.persons;
						} else if (res.err == 2) {
							_this.condition = false;
						}
					}).fail(function () {}).always(function () {});
				}
			}, {
				label: '华南',
				onClick: function onClick() {
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
					}).done(function (res) {
						if (res.err == 0) {
							_this.condition = true;
							_this.member = res.member;
							_this.name = res.persons;
						} else if (res.err == 2) {
							_this.condition = false;
						}
					}).fail(function () {}).always(function () {});
				}
			}, {
				label: '其他',
				onClick: function onClick() {
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
					}).done(function (res) {
						if (res.err == 0) {
							_this.condition = true;
							_this.member = res.member;
							_this.name = res.persons;
						} else if (res.err == 2) {
							_this.condition = false;
						}
					}).fail(function () {}).always(function () {});
				}
			}], [{
				label: '取消',
				onClick: function onClick() {}
			}], {
				className: 'custom-classname'
			});
		},
		arrow: function arrow() {
			window.scrollTo(0, 0);
		},
		circle: function circle() {
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
			}).then(function (res) {
				console.log(res);
				if (res.err == 0) {
					_this.condition = true;
					_this.member = res.member;
					_this.name = res.persons;
					_this.isCircle = false;
					window.scrollTo(0, 0);
					weui.topTips('更新成功', 3000);
				} else if (res.err == 2) {
					_this.condition = false;
				}
			}, function () {});
		},
		search: function search() {
			var _this = this;
			_this.page = 1;
			if (this.keywords) {
				try {
					var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
					piwikTracker.trackSiteSearch(this.keywords, "keywords", 20);
				} catch (err) {}

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
				}).done(function (res) {
					if (res.err == 0) {
						_this.condition = true;
						_this.name = res.persons;
					} else if (res.err == 2) {
						_this.condition = false;
					}
				}).fail(function () {}).always(function () {});
			} else {
				window.location.reload();
			}
		},
		loadingMore: function loadingMore() {
			var _this = this;
			var scrollTop = $(window).scrollTop();
			var scrollHeight = $(document).height();
			var windowHeight = $(window).height();
			if (scrollTop + windowHeight == scrollHeight) {
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
				}).then(function (res) {
					if (res.err == 0) {
						_this.condition = true;
						_this.name = _this.name.concat(res.persons);
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
					} else if (res.err == 2) {
						_this.condition = false;
					} else if (res.err == 3) {
						weui.topTips(res.msg, 3000);
					}
				}, function () {});
			}
		}
	},
	beforeRouteEnter: function beforeRouteEnter(to, from, next) {
		var _this = this;
		next(function (vm) {
			if (from.name == "login") {
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
				}).done(function (res) {
					if (res.err == 0) {
						vm.condition = true;
						vm.member = res.member;
						vm.name = res.persons;
						vm.c_type = res.show_ctype;
						if (vm.c_type == 0) {
							vm.txt = "所有分类";
						} else if (vm.c_type == 1) {
							vm.txt = "塑料制品企业";
						} else if (vm.c_type == 2) {
							vm.txt = "原料供应商";
						} else if (vm.c_type == 4) {
							vm.txt = "物流服务商";
						} else if (vm.c_type == 5) {
							vm.txt = "其他";
						}
						if (__WEBPACK_IMPORTED_MODULE_0_babel_runtime_core_js_json_stringify___default()(res.top) == '{}') {
							vm.top = null;
						} else {
							vm.top = res.top;
						}
					} else if (res.err == 2) {
						vm.condition = false;
					}
				}).fail(function () {}).always(function () {});
			}
			$(window).on('scroll', function () {
				vm.loadingMore();
				var scrollTop = $(this).scrollTop();
				var liWidth = $(".static").width();
				if (scrollTop > 90) {
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
	beforeRouteLeave: function beforeRouteLeave(to, from, next) {
		var _this = this;
		next(function () {});
		$(window).off('scroll');
		window.localStorage.setItem("scrollTop", $(window).scrollTop());
	},
	mounted: function mounted() {
		var _this = this;
		this.loadingShow = true;
		try {
			var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
			piwikTracker.trackPageView();
		} catch (err) {}

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
		}).done(function (res) {
			if (res.err == 0) {
				_this.isFocus = res.is_show_focus;
				_this.bannerLink = res.banner_jump_url;
				_this.bannerImg = res.banner_url;
				_this.condition = true;
				_this.member = res.member;
				_this.name = res.persons;
				_this.c_type = res.show_ctype;
				if (_this.c_type == 0) {
					_this.txt = "所有分类";
				} else if (_this.c_type == 1) {
					_this.txt = "塑料制品企业";
				} else if (_this.c_type == 2) {
					_this.txt = "原料供应商";
				} else if (_this.c_type == 4) {
					_this.txt = "物流服务商";
				} else if (_this.c_type == 5) {
					_this.txt = "其他";
				}
				if (__WEBPACK_IMPORTED_MODULE_0_babel_runtime_core_js_json_stringify___default()(res.top) == '{}') {
					_this.top = null;
				} else {
					_this.top = res.top;
				}
			} else if (res.err == 2) {
				_this.condition = false;
			}
		}).fail(function () {}).always(function () {
			_this.loadingShow = false;
		});

		$(window).scroll(function () {
			var scrollTop = $(this).scrollTop();
			var scrollHeight = $(document).height();
			var windowHeight = $(this).height();

			if (scrollTop > 600) {
				_this.isArrow = true;
			} else {
				_this.isArrow = false;
			}
		});
		window.localStorage.invite = this.$route.query.invite;
	}
});

/***/ })

});