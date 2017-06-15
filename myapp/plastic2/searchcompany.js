webpackJsonp([19],{

/***/ 121:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });


/* harmony default export */ __webpack_exports__["default"] = ({
	data: function data() {
		return {
			fname: "",
			creditli: [],
			c_name: "",
			configLi: [],
			maskShow: false
		};
	},
	methods: {
		mask: function mask() {
			this.maskShow = false;
		},
		tel: function tel() {
			weui.actionSheet([{
				label: '<a style=" color:#0091ff; display:block;" href="tel:4006129965">400-6129-965</a>',
				onClick: function onClick() {}
			}, {
				label: '<a style=" color:#0091ff; display:block;" href="tel:02161070985">021-61070985</a>',
				onClick: function onClick() {}
			}], [{
				label: '<span style=" color:#0091ff;">取消</span>',
				onClick: function onClick() {}
			}], {
				className: 'custom-classname'
			});
		},
		search: function search() {
			var _this = this;
			$.ajax({
				type: "post",
				url: version + '/credit/creditCertificate',
				data: {
					token: window.localStorage.getItem("token"),
					type: 2,
					page: 1,
					fname: _this.fname
				},
				headers: {
					'X-UA': window.localStorage.getItem("XUA")
				},
				dataType: 'JSON'
			}).then(function (res) {
				if (res.err == 0) {
					_this.maskShow = true;
					_this.creditli = res.data;
				} else {
					weui.alert(res.msg, {
						title: '塑料圈通讯录',
						buttons: [{
							label: '确定',
							type: 'parimary',
							onClick: function onClick() {}
						}]
					});
				}
			}, function () {});
		}
	},
	activated: function activated() {
		try {
			var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
			piwikTracker.trackPageView();
		} catch (err) {}
		var _this = this;
		$.ajax({
			type: "post",
			url: version + '/credit/creditPage',
			data: {},
			headers: {
				'X-UA': window.localStorage.getItem("XUA")
			},
			dataType: 'JSON'
		}).then(function (res) {
			if (res.err == 0) {
				_this.configLi = res.data;
				_this.c_name = res.c_name;
			} else {
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
		}, function () {});
	}
});

/***/ }),

/***/ 154:
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "buyWrap",
    staticStyle: {
      "padding": "0"
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
  }), _vm._v("\n\t\t\t企业信用额度\n\t\t"), _c('a', {
    staticClass: "configSobot",
    attrs: {
      "href": "https://www.sobot.com/chat/h5/index.html?sysNum=137f8799efcb49fea05534057318dde0"
    }
  }), _vm._v(" "), _c('a', {
    staticClass: "configTel",
    attrs: {
      "href": "javascript:;"
    },
    on: {
      "click": _vm.tel
    }
  })]), _vm._v(" "), _c('div', {
    staticClass: "searchWrap"
  }, [_c('div', {
    staticClass: "searchWrapTitle"
  }, [_vm._v("信用额度查询：")]), _vm._v(" "), _c('div', {
    staticClass: "searchfname"
  }, [_c('div', {
    staticClass: "searchfnameWrap"
  }, [_c('div', {
    staticStyle: {
      "width": "auto",
      "margin-right": "80px"
    }
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.fname),
      expression: "fname"
    }],
    staticStyle: {
      "width": "100%",
      "line-height": "22px",
      "float": "left",
      "border": "none",
      "padding": "5px 7px",
      "background": "none",
      "font-size": "12px"
    },
    attrs: {
      "type": "text",
      "placeholder": "请输入企业名称"
    },
    domProps: {
      "value": (_vm.fname)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.fname = $event.target.value
      }
    }
  }), _vm._v(" "), _c('div', {
    staticClass: "searchbtn",
    on: {
      "click": _vm.search
    }
  }, [_vm._v("查询")])])])]), _vm._v(" "), _c('div', {
    staticClass: "belongFirm"
  }, [_vm._v("\n\t\t\t您所属企业："), _c('router-link', {
    attrs: {
      "to": {
        name: 'credit'
      }
    }
  }, [_vm._v(_vm._s(_vm.c_name))])], 1)]), _vm._v(" "), _c('div', {
    staticClass: "configWrap"
  }, [_c('ul', {
    staticClass: "configUl"
  }, _vm._l((_vm.configLi), function(c, index) {
    return _c('li', [_c('div', {
      staticClass: "configIco",
      class: {
        config1: 0 == index % 3, config2: 1 == index % 3, config3: 2 == index % 3
      }
    }, [_vm._v("Q:"), _c('span', {
      domProps: {
        "innerHTML": _vm._s(c.q)
      }
    })]), _vm._v("\n\t\t\t\tA:"), _c('span', {
      domProps: {
        "innerHTML": _vm._s(c.a)
      }
    })])
  }))]), _vm._v(" "), (_vm.maskShow) ? _c('div', {
    staticClass: "searchComMask",
    on: {
      "click": _vm.mask
    }
  }, [_c('div', {
    staticClass: "searchComWrap"
  }, [_c('ul', _vm._l((_vm.creditli), function(c) {
    return _c('li', [_c('router-link', {
      attrs: {
        "to": {
          name: 'credit2',
          params: {
            id: c.contact_id
          }
        }
      }
    }, [_vm._v(_vm._s(c.c_name))])], 1)
  }))])]) : _vm._e()])
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-621be368", module.exports)
  }
}

/***/ }),

/***/ 44:
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(48)(
  /* script */
  __webpack_require__(121),
  /* template */
  __webpack_require__(154),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "D:\\xampp\\htdocs\\workspace2\\www\\view\\default\\plasticzone\\src\\views\\searchcompany.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] searchcompany.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-621be368", Component.options)
  } else {
    hotAPI.reload("data-v-621be368", Component.options)
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


/***/ })

});