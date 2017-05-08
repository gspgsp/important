webpackJsonp([8],{

/***/ 104:
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "buyWrap",
    staticStyle: {
      "padding": "45px 0 70px 0"
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
    staticClass: "back",
    attrs: {
      "href": "javascript:window.history.back();"
    }
  }), _vm._v("\n\t\t\t我的资料\n\t\t\t"), (_vm.isDisabled) ? _c('span', {
    staticStyle: {
      "position": "absolute",
      "right": "10px",
      "font-size": "14px"
    },
    on: {
      "click": _vm.editor
    }
  }, [_vm._v("编辑")]) : _vm._e(), _vm._v(" "), (!_vm.isDisabled) ? _c('span', {
    staticStyle: {
      "position": "absolute",
      "right": "10px",
      "font-size": "14px"
    },
    on: {
      "click": _vm.save
    }
  }, [_vm._v("保存")]) : _vm._e()])]), _vm._v(" "), _c('div', {
    staticClass: "personInfo"
  }, [_c('div', {
    staticStyle: {
      "width": "80px",
      "height": "80px",
      "position": "relative",
      "float": "left"
    }
  }, [_c('div', {
    staticClass: "personAvator",
    attrs: {
      "id": "uploader"
    }
  }, [_c('input', {
    staticStyle: {
      "width": "80px",
      "height": "80px",
      "opacity": "0",
      "position": "absolute",
      "top": "0",
      "left": "0"
    },
    attrs: {
      "type": "file",
      "accept": "image/*",
      "capture": "camera",
      "multiple": ""
    }
  }), _vm._v(" "), _c('img', {
    attrs: {
      "width": "80",
      "height": "80",
      "src": _vm.thumb
    }
  })]), _vm._v(" "), _c('i', {
    staticClass: "photo"
  })]), _vm._v(" "), _c('div', {
    staticClass: "personName"
  }, [_vm._v(_vm._s(_vm.name) + " "), _c('span', {
    staticStyle: {
      "font-size": "12px",
      "color": "#63769d"
    }
  }, [_vm._v("等级:" + _vm._s(_vm.level))])]), _vm._v(" "), _c('div', {
    staticClass: "personNum"
  }, [_vm._v(_vm._s(_vm.c_name))]), _vm._v(" "), _c('div', {
    staticClass: "personNum"
  }, [_vm._v("电话：" + _vm._s(_vm.mobile))]), _vm._v(" "), _c('div', {
    staticClass: "personNum"
  }, [_vm._v("\n\t\t\t发布供给："), _c('span', {
    staticStyle: {
      "color": "#63769d"
    }
  }, [_vm._v(_vm._s(_vm.sale) + "条")]), _vm._v(" 发布需求："), _c('span', {
    staticStyle: {
      "color": "#63769d"
    }
  }, [_vm._v(_vm._s(_vm.buy) + "条")])]), _vm._v(" "), _c('table', {
    staticClass: "myinfotb",
    attrs: {
      "cellpadding": "0",
      "cellspacing": "0"
    }
  }, [_c('tr', [_c('td', {
    staticStyle: {
      "padding": "0 0 0 15px"
    },
    attrs: {
      "width": "30%"
    }
  }, [_vm._v("地址：")]), _vm._v(" "), _c('td', {
    staticStyle: {
      "padding": "0 15px 0 0"
    }
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.address),
      expression: "address"
    }],
    attrs: {
      "disabled": _vm.isDisabled,
      "type": "text"
    },
    domProps: {
      "value": (_vm.address)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.address = $event.target.value
      }
    }
  })])]), _vm._v(" "), _c('tr', [_c('td', {
    staticStyle: {
      "padding": "0 0 0 15px"
    }
  }, [_vm._v("性别：")]), _vm._v(" "), _c('td', {
    staticStyle: {
      "padding": "0 15px 0 7px"
    }
  }, [(_vm.isDisabled) ? _c('span', [_vm._v(_vm._s(_vm.sex))]) : _vm._e(), _vm._v(" "), (!_vm.isDisabled) ? _c('span', [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.sexradio),
      expression: "sexradio"
    }],
    attrs: {
      "type": "radio",
      "value": "0"
    },
    domProps: {
      "checked": _vm._q(_vm.sexradio, "0")
    },
    on: {
      "__c": function($event) {
        _vm.sexradio = "0"
      }
    }
  }), _vm._v(" 男 \n\t\t\t\t\t\t"), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.sexradio),
      expression: "sexradio"
    }],
    attrs: {
      "type": "radio",
      "value": "1"
    },
    domProps: {
      "checked": _vm._q(_vm.sexradio, "1")
    },
    on: {
      "__c": function($event) {
        _vm.sexradio = "1"
      }
    }
  }), _vm._v(" 女\n\t\t\t\t\t")]) : _vm._e()])]), _vm._v(" "), _c('tr', [_c('td', {
    staticStyle: {
      "padding": "0 0 0 15px"
    }
  }, [_vm._v("所属地区：")]), _vm._v(" "), _c('td', {
    staticStyle: {
      "padding": "0 15px 0 7px"
    }
  }, [(_vm.isDisabled) ? _c('span', [_vm._v(_vm._s(_vm.adistinct))]) : _vm._e(), _vm._v(" "), (!_vm.isDisabled) ? _c('span', [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.distinctradio),
      expression: "distinctradio"
    }],
    attrs: {
      "type": "radio",
      "value": "EC"
    },
    domProps: {
      "checked": _vm._q(_vm.distinctradio, "EC")
    },
    on: {
      "__c": function($event) {
        _vm.distinctradio = "EC"
      }
    }
  }), _vm._v(" 华东 \n\t\t\t\t\t\t"), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.distinctradio),
      expression: "distinctradio"
    }],
    attrs: {
      "type": "radio",
      "value": "NC"
    },
    domProps: {
      "checked": _vm._q(_vm.distinctradio, "NC")
    },
    on: {
      "__c": function($event) {
        _vm.distinctradio = "NC"
      }
    }
  }), _vm._v(" 华北 \n\t\t\t\t\t\t"), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.distinctradio),
      expression: "distinctradio"
    }],
    attrs: {
      "type": "radio",
      "value": "SC"
    },
    domProps: {
      "checked": _vm._q(_vm.distinctradio, "SC")
    },
    on: {
      "__c": function($event) {
        _vm.distinctradio = "SC"
      }
    }
  }), _vm._v(" 华南 \n\t\t\t\t\t\t"), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.distinctradio),
      expression: "distinctradio"
    }],
    attrs: {
      "type": "radio",
      "value": "OT"
    },
    domProps: {
      "checked": _vm._q(_vm.distinctradio, "OT")
    },
    on: {
      "__c": function($event) {
        _vm.distinctradio = "OT"
      }
    }
  }), _vm._v(" 其他 \n\t\t\t\t\t")]) : _vm._e()])]), _vm._v(" "), _c('tr', [_c('td', {
    staticStyle: {
      "padding": "0 0 0 15px"
    }
  }, [_vm._v("企业类型：")]), _vm._v(" "), _c('td', {
    staticStyle: {
      "padding": "0 15px 0 7px"
    }
  }, [(_vm.isDisabled) ? _c('span', [_vm._v("\n\t\t\t\t\t\t" + _vm._s(_vm.c_nametype) + "\n\t\t\t\t\t")]) : _vm._e(), _vm._v(" "), (!_vm.isDisabled) ? _c('span', [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.c_type),
      expression: "c_type"
    }],
    attrs: {
      "type": "radio",
      "value": "1"
    },
    domProps: {
      "checked": _vm._q(_vm.c_type, "1")
    },
    on: {
      "change": _vm.ctypeShow,
      "__c": function($event) {
        _vm.c_type = "1"
      }
    }
  }), _vm._v(" 塑料制品企业 \n\t\t\t\t\t\t"), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.c_type),
      expression: "c_type"
    }],
    attrs: {
      "type": "radio",
      "value": "2"
    },
    domProps: {
      "checked": _vm._q(_vm.c_type, "2")
    },
    on: {
      "change": _vm.ctypeShow,
      "__c": function($event) {
        _vm.c_type = "2"
      }
    }
  }), _vm._v(" 原料供应商 \n\t\t\t\t\t\t"), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.c_type),
      expression: "c_type"
    }],
    attrs: {
      "type": "radio",
      "value": "4"
    },
    domProps: {
      "checked": _vm._q(_vm.c_type, "4")
    },
    on: {
      "change": _vm.ctypeShow,
      "__c": function($event) {
        _vm.c_type = "4"
      }
    }
  }), _vm._v(" 物流服务商 \n\t\t\t\t\t")]) : _vm._e()])]), _vm._v(" "), (_vm.isType) ? _c('tr', [_c('td', {
    staticStyle: {
      "padding": "0 0 0 15px"
    }
  }, [_vm._v("产品：")]), _vm._v(" "), _c('td', {
    staticStyle: {
      "padding": "0 15px 0 7px"
    }
  }, [(_vm.isDisabled) ? _c('span', [_vm._v(_vm._s(_vm.main_product))]) : _vm._e(), _vm._v(" "), (!_vm.isDisabled) ? _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.main_product),
      expression: "main_product"
    }],
    attrs: {
      "disabled": _vm.isDisabled,
      "type": "text"
    },
    domProps: {
      "value": (_vm.main_product)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.main_product = $event.target.value
      }
    }
  }) : _vm._e()])]) : _vm._e(), _vm._v(" "), (_vm.isType) ? _c('tr', [_c('td', {
    staticStyle: {
      "padding": "0 0 0 15px"
    }
  }, [_vm._v("月用量：")]), _vm._v(" "), _c('td', {
    staticStyle: {
      "padding": "0 15px 0 7px"
    }
  }, [(_vm.isDisabled) ? _c('span', [_vm._v(_vm._s(_vm.month_consum))]) : _vm._e(), _vm._v(" "), (!_vm.isDisabled) ? _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.month_consum),
      expression: "month_consum"
    }],
    attrs: {
      "disabled": _vm.isDisabled,
      "type": "text"
    },
    domProps: {
      "value": (_vm.month_consum)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.month_consum = $event.target.value
      }
    }
  }) : _vm._e()])]) : _vm._e(), _vm._v(" "), _c('tr', [_c('td', {
    staticStyle: {
      "padding": "0 0 0 15px"
    }
  }, [_vm._v("我的主营：")]), _vm._v(" "), _c('td', {
    staticStyle: {
      "padding": "0 15px 0 0"
    }
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.need_product),
      expression: "need_product"
    }],
    attrs: {
      "disabled": _vm.isDisabled,
      "type": "text"
    },
    domProps: {
      "value": (_vm.need_product)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.need_product = $event.target.value
      }
    }
  })])]), _vm._v(" "), _c('tr', [_c('td', {
    staticStyle: {
      "padding": "0 0 0 15px"
    }
  }, [_vm._v("关注的牌号：")]), _vm._v(" "), _c('td', {
    staticStyle: {
      "padding": "0 15px 0 0"
    }
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.need_ph),
      expression: "need_ph"
    }],
    attrs: {
      "disabled": _vm.isDisabled,
      "type": "text"
    },
    domProps: {
      "value": (_vm.need_ph)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.need_ph = $event.target.value
      }
    }
  })])])]), _vm._v(" "), _c('div', {
    staticClass: "mui-content"
  }, [_c('ul', {
    staticClass: "mui-table-view",
    attrs: {
      "id": "shortmsg"
    }
  }, [_vm._m(0), _vm._v(" "), _c('li', {
    staticClass: "mui-table-view-cell"
  }, [_vm._v("\n\t\t\t\t\t是否公开\n\t\t\t\t\t"), _c('div', {
    staticClass: "mui-switch mui-switch-mini",
    class: {
      'mui-active': !_vm.active3
    },
    on: {
      "click": _vm.msgActive3
    }
  }, [_c('div', {
    staticClass: "mui-switch-handle"
  })])])])]), _vm._v(" "), _c('div', {
    staticClass: "mui-content"
  }, [_c('ul', {
    staticClass: "mui-table-view",
    attrs: {
      "id": "shortmsg"
    }
  }, [_vm._m(1), _vm._v(" "), _c('li', {
    staticClass: "mui-table-view-cell"
  }, [_vm._v("\n\t\t\t\t\t有人关注我，手机短信提醒\n\t\t\t\t\t"), _c('div', {
    staticClass: "mui-switch mui-switch-mini",
    class: {
      'mui-active': !_vm.active
    },
    on: {
      "click": _vm.msgActive
    }
  }, [_c('div', {
    staticClass: "mui-switch-handle"
  })])]), _vm._v(" "), _c('li', {
    staticClass: "mui-table-view-cell"
  }, [_vm._v("\n\t\t\t\t\t有人回复我的供求，手机短信提醒\n\t\t\t\t\t"), _c('div', {
    staticClass: "mui-switch mui-switch-mini",
    class: {
      'mui-active': !_vm.active2
    },
    on: {
      "click": _vm.msgActive2
    }
  }, [_c('div', {
    staticClass: "mui-switch-handle"
  })])])])]), _vm._v(" "), _c('div', {
    staticClass: "registerBox",
    staticStyle: {
      "height": "auto",
      "background": "#FFFFFF",
      "padding": "10px 0",
      "margin": "10px 0 0 0",
      "line-height": "0",
      "text-align": "center"
    }
  }, [_c('div', {
    staticClass: "card"
  }, [_c('img', {
    attrs: {
      "src": _vm.cardImg
    }
  })]), _vm._v(" "), _c('div', {
    staticClass: "card2",
    attrs: {
      "id": "uploaderCard"
    }
  }, [_c('input', {
    staticStyle: {
      "width": "133px",
      "height": "73px",
      "opacity": "0",
      "position": "absolute",
      "top": "0",
      "left": "0"
    },
    attrs: {
      "type": "file",
      "name": "upFile"
    }
  }), _vm._v(" "), _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (!_vm.cardshow),
      expression: "!cardshow"
    }],
    staticClass: "card2upload"
  }), _vm._v(" "), _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.cardshow),
      expression: "cardshow"
    }],
    staticClass: "card2success"
  })]), _vm._v(" "), _c('div', {
    staticClass: "personInfoList2"
  }, [_c('div', {
    staticStyle: {
      "font-size": "13px",
      "color": "#8f8f94",
      "line-height": "20px",
      "text-align": "left",
      "border-top": "1px solid #d1d1d1",
      "padding": "10px 15px 0 15px"
    }
  }, [_vm._v("通讯录排序:您目前排在通讯录的第" + _vm._s(_vm.rank) + "位，共" + _vm._s(_vm.total) + "人，按照粉丝数量、发布求购数量、发布供给数量进行排序")])])])]), _vm._v(" "), _c('loadingPage', {
    attrs: {
      "loading": _vm.loadingShow
    }
  }), _vm._v(" "), _c('footerbar')], 1)
},staticRenderFns: [function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('li', {
    staticClass: "mui-table-view-cell"
  }, [_c('span', {
    staticStyle: {
      "color": "#333333"
    }
  }, [_vm._v("公开手机号码")])])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('li', {
    staticClass: "mui-table-view-cell"
  }, [_c('span', {
    staticStyle: {
      "color": "#333333"
    }
  }, [_vm._v("手机短信设置")])])
}]}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-016fc652", module.exports)
  }
}

/***/ }),

/***/ 15:
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(46)(
  /* script */
  __webpack_require__(72),
  /* template */
  __webpack_require__(104),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "D:\\xampp\\htdocs\\workspace2\\www\\view\\default\\plasticzone\\src\\views\\myinfo.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] myinfo.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-016fc652", Component.options)
  } else {
    hotAPI.reload("data-v-016fc652", Component.options)
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

/***/ 72:
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
			c_type: "",
			c_nametype: "",
			mobile: "",
			address: "",
			sex: "",
			status: "",
			thumb: "",
			concern_model: "",
			need_product: "",
			main_product: "",
			month_consum: "",
			isType: "",
			need_ph: "",
			rank: "",
			total: "",
			sexradio: "",
			distinctradio: "EC",
			cardImg: "",
			active: "",
			active2: "",
			active3: "",
			level: "",
			distinct: "",
			loadingShow: "",
			isDisabled: true
		};
	},
	beforeRouteEnter: function beforeRouteEnter(to, from, next) {
		next(function (vm) {
			vm.loadingShow = true;
		});
	},
	methods: {
		editor: function editor() {
			this.isDisabled = false;
		},
		ctypeShow: function ctypeShow() {
			if (this.c_type == "1") {
				this.isType = true;
			} else {
				this.isType = false;
			}
		},
		save: function save() {
			var _this = this;
			this.isDisabled = true;
			$.ajax({
				url: '/api/qapi1_2/saveSelfInfo',
				type: 'post',
				data: {
					token: window.localStorage.getItem("token"),
					address: _this.address,
					sex: _this.sexradio,
					major: _this.need_product,
					concern: _this.need_ph,
					dist: _this.distinctradio,
					type: _this.c_type,
					month_consum: _this.month_consum,
					main_product: _this.main_product
				},
				dataType: 'JSON'
			}).then(function (res) {
				weui.alert(res.msg, {
					title: '塑料圈通讯录',
					buttons: [{
						label: '确定',
						type: 'parimary',
						onClick: function onClick() {
							window.location.reload();
						}
					}]
				});
			}, function () {});
		},
		msgActive: function msgActive() {
			var _this = this;
			this.active == 0 ? this.active = 1 : this.active = 0;
			$.ajax({
				url: '/api/qapi1_2/favorateSet',
				type: 'post',
				data: {
					type: 0,
					is_allow: _this.active,
					token: window.localStorage.getItem("token")
				},
				dataType: 'JSON'
			}).then(function (res) {}, function () {});
		},
		msgActive2: function msgActive2() {
			var _this = this;
			this.active2 == 0 ? this.active2 = 1 : this.active2 = 0;
			$.ajax({
				url: '/api/qapi1_2/favorateSet',
				type: 'post',
				data: {
					type: 1,
					is_allow: _this.active2,
					token: window.localStorage.getItem("token")
				},
				dataType: 'JSON'
			}).then(function (res) {}, function () {});
		},
		msgActive3: function msgActive3() {
			var _this = this;
			this.active3 == 0 ? this.active3 = 1 : this.active3 = 0;
			$.ajax({
				url: '/api/qapi1_2/favorateSet',
				type: 'post',
				data: {
					type: 2,
					is_allow: _this.active3,
					token: window.localStorage.getItem("token")
				},
				dataType: 'JSON'
			}).then(function (res) {}, function () {});
		}
	},
	activated: function activated() {
		var _this = this;
		window.scrollTo(0, 0);
		try {
			var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
			piwikTracker.trackPageView();
		} catch (err) {}

		weui.uploader('#uploaderCard', {
			url: '/api/qapi1/saveCardImg',
			auto: true,
			type: 'file',
			fileVal: 'fileVal',
			data: {
				token: window.localStorage.getItem("token")
			},
			compress: {
				width: 500,
				height: 500,
				quality: .5
			},
			onBeforeQueued: function onBeforeQueued(files) {
				if (["image/jpg", "image/jpeg", "image/png", "image/gif"].indexOf(this.type) < 0) {
					weui.alert('请上传图片');
					return false;
				}
				if (this.size > 5 * 1024 * 1024) {
					weui.alert('请上传不超过5M的图片');
					return false;
				}
			},
			onQueued: function onQueued() {},
			onSuccess: function onSuccess(res) {
				if (res.err == 0) {
					_this.cardImg = res.url;
				}
			},
			onError: function onError(err) {
				weui.alert("上传失败", {
					title: '塑料圈通讯录',
					buttons: [{
						label: '确定',
						type: 'parimary',
						onClick: function onClick() {}
					}]
				});
			}
		});

		weui.uploader('#uploader', {
			url: '/api/qapi1/savePicToServer',
			auto: true,
			type: 'file',
			fileVal: 'fileVal',
			data: {
				token: window.localStorage.getItem("token")
			},
			compress: {
				width: 500,
				height: 500,
				quality: .5
			},
			onBeforeQueued: function onBeforeQueued(files) {
				if (["image/jpg", "image/jpeg", "image/png", "image/gif"].indexOf(this.type) < 0) {
					weui.alert('请上传图片');
					return false;
				}
				if (this.size > 5 * 1024 * 1024) {
					weui.alert('请上传不超过5M的图片');
					return false;
				}
			},
			onQueued: function onQueued() {},
			onSuccess: function onSuccess(res) {
				window.location.reload();
			},
			onError: function onError(err) {
				weui.alert("上传失败", {
					title: '塑料圈通讯录',
					buttons: [{
						label: '确定',
						type: 'parimary',
						onClick: function onClick() {}
					}]
				});
			}
		});

		$.ajax({
			url: '/api/qapi1_2/getSelfInfo',
			type: 'post',
			data: {
				token: window.localStorage.getItem("token")
			},
			dataType: 'JSON'
		}).done(function (res) {
			if (res.err == 0) {
				_this.name = res.data.name;
				_this.c_name = res.data.c_name;
				_this.address = res.data.address;
				_this.mobile = res.data.mobile;
				_this.need_ph = res.data.concern_model;
				_this.need_product = res.data.need_product;
				_this.main_product = res.data.main_product;
				_this.month_consum = res.data.month_consum;
				_this.status = res.data.status;
				_this.concern_model = res.data.concern_model;
				_this.thumb = res.data.thumb;
				_this.buy = res.data.buy;
				_this.sale = res.data.sale;
				_this.sex = res.data.sex;
				_this.rank = res.data.rank;
				_this.total = res.data.total;
				_this.cardImg = res.data.thumbcard;
				_this.active = res.data.allow_send.focus;
				_this.active2 = res.data.allow_send.repeat;
				_this.active3 = res.data.allow_send.show;
				_this.level = res.data.member_level;
				_this.adistinct = res.data.adistinct;
				_this.c_type = res.data.type;
				if (_this.sex == "男") {
					_this.sexradio = 0;
				} else {
					_this.sexradio = 1;
				}
				if (_this.c_type == "2") {
					_this.c_nametype = "原料供应商 ";
				} else if (_this.c_type == "1") {
					_this.c_nametype = "塑料制品企业";
					_this.isType = true;
				} else if (_this.c_type == "4") {
					_this.c_nametype = "物流服务商";
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
		}).fail(function () {}).always(function () {
			_this.loadingShow = false;
		});
	}

});

/***/ })

});