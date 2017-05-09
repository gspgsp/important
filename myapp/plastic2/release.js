webpackJsonp([1],{

/***/ 130:
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticStyle: {
      "padding": "90px 0 60px 0"
    }
  }, [_c('div', {
    staticStyle: {
      "position": "fixed",
      "top": "0",
      "left": "0",
      "width": "100%",
      "z-index": "6"
    }
  }, [_c('header', {
    attrs: {
      "id": "bigCustomerHeader"
    }
  }, [_c('span', {
    staticClass: "releaseinfo"
  }, [_vm._v("供求信息")]), _vm._v(" "), _c('div', {
    staticStyle: {
      "padding": "7px 0 0 0"
    }
  }, [_c('div', {
    staticClass: "releasesearch",
    staticStyle: {
      "text-align": "left"
    }
  }, [_c('form', {
    attrs: {
      "action": "javascript:;"
    }
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.keywords),
      expression: "keywords"
    }],
    attrs: {
      "type": "text",
      "placeholder": "请输入厂家或牌号"
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
  })])])]), _vm._v(" "), _c('div', {
    staticStyle: {
      "font-size": "14px",
      "background": "#FFFFFF",
      "border-radius": "3px"
    },
    attrs: {
      "id": "searchbox"
    },
    on: {
      "click": _vm.selectAll
    }
  }, [_vm._v("\n\t\t\t\t" + _vm._s(_vm.txt2) + "\n\t\t\t")])]), _vm._v(" "), _c('div', {
    staticClass: "releasefilter"
  }, [_c('span', {
    class: {
      'on': _vm.filter1
    },
    on: {
      "click": function($event) {
        _vm.getRelease('all')
      }
    }
  }, [_vm._v("全部")]), _vm._v(" "), _c('span', {
    class: {
      'on': _vm.filter2
    },
    on: {
      "click": function($event) {
        _vm.getRelease('recommend')
      }
    }
  }, [_vm._v("智能推荐")]), _vm._v(" "), _c('span', {
    class: {
      'on': _vm.filter3
    },
    on: {
      "click": function($event) {
        _vm.getRelease('attention')
      }
    }
  }, [_vm._v("我的关注")]), _vm._v(" "), _c('span', {
    class: {
      'on': _vm.filter4
    },
    on: {
      "click": function($event) {
        _vm.getRelease('supplydemand')
      }
    }
  }, [_vm._v("我的供求")])])]), _vm._v(" "), _c('ul', {
    attrs: {
      "id": "releaseUl"
    }
  }, [(_vm.top) ? _c('li', {
    attrs: {
      "id": "releaseTop"
    }
  }, [_c('div', {
    staticStyle: {
      "width": "100%",
      "overflow": "hidden",
      "position": "relative",
      "background": "#FFFFFF"
    }
  }, [_c('span', {
    staticClass: "releaseFix"
  }), _vm._v(" "), _c('router-link', {
    staticStyle: {
      "display": "block",
      "height": "35px",
      "overflow": "hidden"
    },
    attrs: {
      "to": {
        name: 'personinfo',
        params: {
          id: _vm.top.user_id
        }
      }
    }
  }, [_c('div', {
    staticClass: "myreleaseInfo"
  }, [_c('div', {
    staticStyle: {
      "width": "30px",
      "height": "30px",
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
      'v1': _vm.top.is_pass == null || _vm.top.is_pass == 1, 'v2': _vm.top.is_pass == 0
    }
  })]), _vm._v(" "), _c('div', {
    staticClass: "myreleasetxt"
  }, [_c('p', {
    staticStyle: {
      "line-height": "30px"
    }
  }, [_vm._v(_vm._s(_vm.top.c_name) + " " + _vm._s(_vm.top.name))])])])]), _vm._v(" "), _c('div', {
    staticClass: "myreleasetxt2"
  }, [_c('router-link', {
    attrs: {
      "to": {
        name: 'releasedetail',
        query: {
          id: _vm.top.id,
          userid: _vm.top.user_id
        }
      }
    }
  }, [_c('p', [(_vm.top.type == 2) ? _c('strong', {
    staticStyle: {
      "color": "#63769d"
    }
  }, [_c('i', {
    staticClass: "iconSale"
  }), _vm._v("供给")]) : _c('strong', {
    staticStyle: {
      "color": "#ea8010"
    }
  }, [_c('i', {
    staticClass: "iconBuy"
  }), _vm._v("求购\n\t\t\t")]), _vm._v(" "), _c('strong', {
    domProps: {
      "innerHTML": _vm._s(_vm.top.contents)
    }
  })])]), _vm._v(" "), _c('p', {
    staticStyle: {
      "color": "#999999"
    }
  }, [_vm._v("\n\t\t\t\t\t\t" + _vm._s(_vm.top.input_time) + "\n\t\t\t\t\t\t"), _c('span', {
    staticStyle: {
      "margin": "0 0 0 3px",
      "color": "#999999"
    }
  }, [_c('router-link', {
    staticStyle: {
      "color": "#999999"
    },
    attrs: {
      "to": {
        name: 'releasedetail',
        query: {
          id: _vm.top.id,
          userid: _vm.top.user_id,
          tab: 2
        }
      }
    }
  }, [_c('i', {
    staticClass: "releasereplyicon"
  }), _vm._v("回复"), _c('i', {
    staticStyle: {
      "color": "#63769d",
      "font-style": "normal"
    }
  }, [_vm._v("(" + _vm._s(_vm.top.saysCount) + ")")])])], 1), _vm._v(" "), _c('span', {
    staticStyle: {
      "color": "#999999"
    }
  }, [_c('router-link', {
    staticStyle: {
      "color": "#999999"
    },
    attrs: {
      "to": {
        name: 'releasedetail',
        query: {
          id: _vm.top.id,
          userid: _vm.top.user_id,
          tab: 1
        }
      }
    }
  }, [_c('i', {
    staticClass: "releasesaleicon"
  }), _vm._v("出价"), _c('i', {
    staticStyle: {
      "color": "#63769d",
      "font-style": "normal"
    }
  }, [_vm._v("(" + _vm._s(_vm.top.deliverPriceCount) + ")")])])], 1)])], 1)], 1)]) : _vm._e(), _vm._v(" "), _vm._l((_vm.release), function(r) {
    return _c('li', [_c('router-link', {
      staticStyle: {
        "display": "block",
        "height": "35px",
        "overflow": "hidden"
      },
      attrs: {
        "to": {
          name: 'personinfo',
          params: {
            id: r.user_id
          }
        }
      }
    }, [_c('div', {
      staticClass: "myreleaseInfo"
    }, [_c('div', {
      staticStyle: {
        "width": "30px",
        "height": "30px",
        "float": "left",
        "position": "relative"
      }
    }, [_c('div', {
      staticClass: "avator"
    }, [_c('img', {
      attrs: {
        "src": r.thumb
      }
    })]), _vm._v(" "), _c('i', {
      staticClass: "iconV",
      class: {
        'v1': r.is_pass == null || r.is_pass == 1, 'v2': r.is_pass == 0
      }
    })]), _vm._v(" "), _c('div', {
      staticClass: "myreleasetxt"
    }, [_c('p', {
      staticStyle: {
        "line-height": "30px"
      }
    }, [_vm._v(_vm._s(r.c_name) + " " + _vm._s(r.name))])])])]), _vm._v(" "), _c('div', {
      staticClass: "myreleasetxt2"
    }, [_c('router-link', {
      attrs: {
        "to": {
          name: 'releasedetail',
          query: {
            id: r.id,
            userid: r.user_id
          }
        }
      }
    }, [_c('p', [(r.type == 2) ? _c('strong', {
      staticStyle: {
        "color": "#63769d"
      }
    }, [_c('i', {
      staticClass: "iconSale"
    }), _vm._v("供给")]) : _c('strong', {
      staticStyle: {
        "color": "#ea8010"
      }
    }, [_c('i', {
      staticClass: "iconBuy"
    }), _vm._v("求购\n")]), _vm._v(" "), _c('strong', {
      domProps: {
        "innerHTML": _vm._s(r.contents)
      }
    })])]), _vm._v(" "), _c('p', {
      staticStyle: {
        "color": "#999999"
      }
    }, [_vm._v("\n\t\t\t\t\t" + _vm._s(r.input_time) + "\n\t\t\t\t\t"), _c('span', {
      directives: [{
        name: "show",
        rawName: "v-show",
        value: (_vm.mine),
        expression: "mine"
      }],
      staticStyle: {
        "margin": "0 0 0 3px",
        "color": "#999999"
      }
    }, [_c('router-link', {
      staticStyle: {
        "color": "#999999"
      },
      attrs: {
        "to": {
          name: 'supplybuy',
          params: {
            id: r.id
          }
        }
      }
    }, [_c('i', {
      staticClass: "releaseshareicon"
    }), _vm._v("分享\n\t")])], 1), _vm._v(" "), _c('span', {
      directives: [{
        name: "show",
        rawName: "v-show",
        value: (_vm.mine),
        expression: "mine"
      }],
      staticStyle: {
        "margin": "0 0 0 3px",
        "color": "#999999"
      }
    }, [_c('router-link', {
      directives: [{
        name: "show",
        rawName: "v-show",
        value: (r.type == 1),
        expression: "r.type==1"
      }],
      staticStyle: {
        "color": "#999999"
      },
      attrs: {
        "to": {
          name: 'releasebsbuy',
          query: {
            id: r.id
          }
        }
      }
    }, [_c('i', {
      staticClass: "releaseresendicon"
    }), _vm._v("重发\n\t")]), _vm._v(" "), _c('router-link', {
      directives: [{
        name: "show",
        rawName: "v-show",
        value: (r.type == 2),
        expression: "r.type==2"
      }],
      staticStyle: {
        "color": "#999999"
      },
      attrs: {
        "to": {
          name: 'releasebssupply',
          query: {
            id: r.id
          }
        }
      }
    }, [_c('i', {
      staticClass: "releaseresendicon"
    }), _vm._v("重发\n\t")])], 1), _vm._v(" "), _c('span', {
      staticStyle: {
        "margin": "0 0 0 3px",
        "color": "#999999"
      }
    }, [_c('router-link', {
      directives: [{
        name: "show",
        rawName: "v-show",
        value: (!_vm.mine),
        expression: "!mine"
      }],
      staticStyle: {
        "color": "#999999"
      },
      attrs: {
        "to": {
          name: 'releasedetail',
          query: {
            id: r.id,
            userid: r.user_id,
            tab: 2
          }
        }
      }
    }, [_c('i', {
      staticClass: "releasereplyicon"
    }), _vm._v("回复"), _c('i', {
      staticStyle: {
        "color": "#63769d",
        "font-style": "normal"
      }
    }, [_vm._v("(" + _vm._s(r.saysCount) + ")")])]), _vm._v(" "), _c('router-link', {
      directives: [{
        name: "show",
        rawName: "v-show",
        value: (_vm.mine),
        expression: "mine"
      }],
      staticStyle: {
        "color": "#999999"
      },
      attrs: {
        "to": {
          name: 'releasedetail',
          query: {
            id: r.id,
            userid: r.user_id,
            tab: 2
          }
        }
      }
    }, [_c('i', {
      staticClass: "releasereplyicon"
    }), _vm._v("看回复"), _c('i', {
      staticStyle: {
        "color": "#63769d",
        "font-style": "normal"
      }
    }, [_vm._v("(" + _vm._s(r.saysCount) + ")")])])], 1), _vm._v(" "), _c('span', {
      staticStyle: {
        "color": "#999999"
      }
    }, [_c('router-link', {
      directives: [{
        name: "show",
        rawName: "v-show",
        value: (!_vm.mine),
        expression: "!mine"
      }],
      staticStyle: {
        "color": "#999999"
      },
      attrs: {
        "to": {
          name: 'releasedetail',
          query: {
            id: r.id,
            userid: r.user_id,
            tab: 1
          }
        }
      }
    }, [_c('i', {
      staticClass: "releasesaleicon"
    }), _vm._v("出价"), _c('i', {
      staticStyle: {
        "color": "#63769d",
        "font-style": "normal"
      }
    }, [_vm._v("(" + _vm._s(r.deliverPriceCount) + ")")])]), _vm._v(" "), _c('router-link', {
      directives: [{
        name: "show",
        rawName: "v-show",
        value: (_vm.mine),
        expression: "mine"
      }],
      staticStyle: {
        "color": "#999999"
      },
      attrs: {
        "to": {
          name: 'releasedetail',
          query: {
            id: r.id,
            userid: r.user_id,
            tab: 1
          }
        }
      }
    }, [_c('i', {
      staticClass: "releasesaleicon"
    }), _vm._v("看出价"), _c('i', {
      staticStyle: {
        "color": "#63769d",
        "font-style": "normal"
      }
    }, [_vm._v("(" + _vm._s(r.deliverPriceCount) + ")")])])], 1)])], 1)], 1)
  })], 2), _vm._v(" "), (_vm.condition == 7) ? _c('div', {
    staticClass: "releaseMsg"
  }, [_c('div', {
    staticClass: "releaseMsgHead"
  }), _vm._v(" "), _c('div', {
    staticClass: "releaseTxt"
  }, [_vm._v(_vm._s(_vm.errmsg))]), _vm._v(" "), _c('router-link', {
    attrs: {
      "to": {
        name: 'quickrelease'
      }
    }
  }, [_vm._v("去发布")]), _vm._v(" "), _c('div', {
    staticClass: "releaseMsgIntro"
  })], 1) : _vm._e(), _vm._v(" "), (_vm.condition == 8) ? _c('div', {
    staticClass: "releaseMsg"
  }, [_c('div', {
    staticClass: "releaseMsgHead"
  }), _vm._v(" "), _c('div', {
    staticClass: "releaseTxt"
  }, [_vm._v(_vm._s(_vm.errmsg))]), _vm._v(" "), _c('router-link', {
    attrs: {
      "to": {
        name: 'quickrelease'
      }
    }
  }, [_vm._v("去发布")])], 1) : _vm._e(), _vm._v(" "), (_vm.condition == 2) ? _c('div', {
    staticClass: "releaseMsg"
  }, [_c('div', {
    staticClass: "releaseMsgHead2"
  }), _vm._v(" "), _c('div', {
    staticClass: "releaseTxt"
  }, [_vm._v(_vm._s(_vm.errmsg))])]) : _vm._e(), _vm._v(" "), (_vm.condition == 6) ? _c('div', {
    staticClass: "releaseMsg"
  }, [_c('div', {
    staticClass: "releaseMsgHead2"
  }), _vm._v(" "), _c('div', {
    staticClass: "releaseTxt"
  }, [_vm._v(_vm._s(_vm.errmsg))])]) : _vm._e(), _vm._v(" "), (_vm.condition == 9) ? _c('div', {
    staticClass: "releaseMsg"
  }, [_c('div', {
    staticClass: "releaseMsgHead3"
  }), _vm._v(" "), _c('div', {
    staticClass: "releaseTxt"
  }, [_vm._v(_vm._s(_vm.errmsg))]), _vm._v(" "), _c('router-link', {
    attrs: {
      "to": {
        name: 'index'
      }
    }
  }, [_vm._v("去关注")])], 1) : _vm._e(), _vm._v(" "), (_vm.condition == 4) ? _c('div', {
    staticClass: "releaseMsg"
  }, [_c('div', {
    staticClass: "releaseMsgHead2"
  }), _vm._v(" "), _c('div', {
    staticClass: "releaseTxt"
  }, [_vm._v(_vm._s(_vm.errmsg))]), _vm._v(" "), _c('div', {
    staticClass: "releaseMsgIntro"
  })]) : _vm._e(), _vm._v(" "), _c('loadingPage', {
    attrs: {
      "loading": _vm.loadingShow
    }
  }), _vm._v(" "), _c('footerbar'), _vm._v(" "), _c('div', {
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
     require("vue-hot-reload-api").rerender("data-v-58d91e4c", module.exports)
  }
}

/***/ }),

/***/ 35:
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(46)(
  /* script */
  __webpack_require__(92),
  /* template */
  __webpack_require__(130),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "D:\\xampp\\htdocs\\workspace2\\www\\view\\default\\plasticzone\\src\\views\\release.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] release.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-58d91e4c", Component.options)
  } else {
    hotAPI.reload("data-v-58d91e4c", Component.options)
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

/***/ 92:
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
		};
	},
	beforeRouteEnter: function beforeRouteEnter(to, from, next) {
		next(function (vm) {
			$(window).on('scroll', function () {
				vm.loadingMore();
				var scrollTop = $(this).scrollTop();
				var reliWidth = $("body").width();
				console.log(reliWidth);
				if (scrollTop > 90) {
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
	beforeRouteLeave: function beforeRouteLeave(to, from, next) {
		var _this = this;
		next(function () {});
		$(window).off('scroll');
	},
	methods: {
		selectAll: function selectAll() {
			var _this = this;
			weui.actionSheet([{
				label: '全部',
				onClick: function onClick() {
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
					}).then(function (res) {
						console.log(res);
						if (res.err == 0) {
							_this.release = res.data;
						} else if (res.err == 2) {
							_this.condition = res.err;
							_this.errmsg = res.msg;
							_this.release = [];
							_this.top = null;
						} else if (res.err == 4) {
							_this.condition = res.err;
							_this.errmsg = res.msg;
							_this.release = [];
							_this.top = null;
						} else if (res.err == 9) {
							_this.condition = res.err;
							_this.errmsg = res.msg;
							_this.release = [];
							_this.top = null;
						} else if (res.err == 6) {
							_this.condition = res.err;
							_this.errmsg = res.msg;
							_this.release = [];
							_this.top = null;
						} else if (res.err == 7) {
							_this.condition = res.err;
							_this.errmsg = res.msg;
							_this.release = [];
							_this.top = null;
						} else if (res.err == 8) {
							_this.condition = res.err;
							_this.errmsg = res.msg;
							_this.release = [];
							_this.top = null;
						}
					}, function () {});
				}
			}, {
				label: '供给',
				onClick: function onClick() {
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
					}).then(function (res) {
						if (res.err == 0) {
							_this.release = res.data;
						} else if (res.err == 2) {
							_this.condition = res.err;
							_this.errmsg = res.msg;
							_this.release = [];
							_this.top = null;
						} else if (res.err == 4) {
							_this.condition = res.err;
							_this.errmsg = res.msg;
							_this.release = [];
							_this.top = null;
						} else if (res.err == 9) {
							_this.condition = res.err;
							_this.errmsg = res.msg;
							_this.release = [];
							_this.top = null;
						} else if (res.err == 6) {
							_this.condition = res.err;
							_this.errmsg = res.msg;
							_this.release = [];
							_this.top = null;
						} else if (res.err == 7) {
							_this.condition = res.err;
							_this.errmsg = res.msg;
							_this.release = [];
							_this.top = null;
						} else if (res.err == 8) {
							_this.condition = res.err;
							_this.errmsg = res.msg;
							_this.release = [];
							_this.top = null;
						}
					}, function () {});
				}
			}, {
				label: '求购',
				onClick: function onClick() {
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
					}).then(function (res) {
						if (res.err == 0) {
							_this.release = res.data;
						} else if (res.err == 2) {
							_this.condition = res.err;
							_this.errmsg = res.msg;
							_this.release = [];
							_this.top = null;
						} else if (res.err == 4) {
							_this.condition = res.err;
							_this.errmsg = res.msg;
							_this.release = [];
							_this.top = null;
						} else if (res.err == 9) {
							_this.condition = res.err;
							_this.errmsg = res.msg;
							_this.release = [];
							_this.top = null;
						} else if (res.err == 6) {
							_this.condition = res.err;
							_this.errmsg = res.msg;
							_this.release = [];
							_this.top = null;
						} else if (res.err == 7) {
							_this.condition = res.err;
							_this.errmsg = res.msg;
							_this.release = [];
							_this.top = null;
						} else if (res.err == 8) {
							_this.condition = res.err;
							_this.errmsg = res.msg;
							_this.release = [];
							_this.top = null;
						}
					}, function () {});
				}
			}], [{
				label: '取消',
				onClick: function onClick() {}
			}], {
				className: 'custom-classname'
			});
		},
		getRelease: function getRelease(cate) {
			window.scrollTo(0, 0);
			var _this = this;
			var loading = weui.loading('加载中', {
				className: 'custom-classname'
			});
			switch (cate) {
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
			}).done(function (res) {
				if (res.err == 0) {
					_this.release = res.data;
					if (__WEBPACK_IMPORTED_MODULE_0_babel_runtime_core_js_json_stringify___default()(res.top) == '{}') {
						_this.top = null;
					} else {
						_this.top = res.top;
					}
				} else if (res.err == 2) {
					_this.condition = res.err;
					_this.errmsg = res.msg;
					_this.release = [];
					_this.top = null;
				} else if (res.err == 4) {
					_this.condition = res.err;
					_this.errmsg = res.msg;
					_this.release = [];
					_this.top = null;
				} else if (res.err == 9) {
					_this.condition = res.err;
					_this.errmsg = res.msg;
					_this.release = [];
					_this.top = null;
				} else if (res.err == 6) {
					_this.condition = res.err;
					_this.errmsg = res.msg;
					_this.release = [];
					_this.top = null;
				} else if (res.err == 7) {
					_this.condition = res.err;
					_this.errmsg = res.msg;
					_this.release = [];
					_this.top = null;
				} else if (res.err == 8) {
					_this.condition = res.err;
					_this.errmsg = res.msg;
					_this.release = [];
					_this.top = null;
				}
			}).fail(function () {}).always(function () {
				loading.hide(function () {});
			});
		},
		search: function search() {
			var _this = this;
			this.condition = true;
			_this.filtershow = false;
			_this.filtershow2 = false;
			this.page = 1;
			try {
				var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
				piwikTracker.trackSiteSearch(this.keywords, "keywords", 20);
			} catch (err) {}

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
			}).then(function (res) {
				console.log(res);
				if (res.err == 0) {
					_this.release = res.data;
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
					_this.condition = res.err;
					_this.errmsg = res.msg;
					_this.release = [];
					_this.top = null;
				} else if (res.err == 4) {
					_this.condition = res.err;
					_this.errmsg = res.msg;
					_this.release = [];
					_this.top = null;
				} else if (res.err == 9) {
					_this.condition = res.err;
					_this.errmsg = res.msg;
					_this.release = [];
					_this.top = null;
				} else if (res.err == 6) {
					_this.condition = res.err;
					_this.errmsg = res.msg;
					_this.release = [];
					_this.top = null;
				} else if (res.err == 7) {
					_this.condition = res.err;
					_this.errmsg = res.msg;
					_this.release = [];
					_this.top = null;
				} else if (res.err == 8) {
					_this.condition = res.err;
					_this.errmsg = res.msg;
					_this.release = [];
					_this.top = null;
				}
			}, function () {});
		},
		arrow: function arrow() {
			window.scrollTo(0, 0);
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
				}).then(function (res) {
					if (res.err == 0) {
						_this.release = _this.release.concat(res.data);
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
					} else if (res.err == 3) {
						weui.topTips(res.msg, 3000);
					}
				}, function () {});
			}
		}
	},
	mounted: function mounted() {
		var _this = this;
		try {
			var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
			piwikTracker.trackPageView();
		} catch (err) {}
		$(window).scroll(function () {
			var scrollTop = $(this).scrollTop();
			if (scrollTop > 600) {
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
		}).done(function (res) {
			if (res.err == 0) {
				_this.release = res.data;
				_this.top = res.top;
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
				_this.condition = res.err;
				_this.errmsg = res.msg;
				_this.top = null;
			} else if (res.err == 4) {
				_this.condition = res.err;
				_this.errmsg = res.msg;
				_this.top = null;
			} else if (res.err == 5) {
				_this.condition = res.err;
				_this.errmsg = res.msg;
				_this.top = null;
			} else if (res.err == 6) {
				_this.condition = res.err;
				_this.errmsg = res.msg;
				_this.top = null;
			} else if (res.err == 7) {
				_this.condition = res.err;
				_this.errmsg = res.msg;
				_this.top = null;
			} else if (res.err == 8) {
				_this.condition = res.err;
				_this.errmsg = res.msg;
				_this.release = [];
				_this.top = null;
			}
		}).fail(function () {}).always(function () {
			_this.loadingShow = false;
		});
	}
});

/***/ })

});