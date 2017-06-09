webpackJsonp([35],{

/***/ 148:
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
  }), _vm._v("\n\t\t塑料配资\n\t\t"), _c('a', {
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
  })]), _vm._v(" "), _vm._m(0)])
},staticRenderFns: [function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "configWrap"
  }, [_c('ul', {
    staticClass: "configUl"
  }, [_c('li', [_c('div', {
    staticClass: "configIco config1"
  }, [_vm._v("Q：什么是塑料配资？")]), _vm._v("\n\t\t\t\tA：塑料行情上涨，但企业流动资金受限，“我的塑料网”可为用户垫付资金，进行代理采购。\n\t\t\t")]), _vm._v(" "), _c('li', [_c('div', {
    staticClass: "configIco config2"
  }, [_vm._v("Q：什么是塑料配资？")]), _vm._v("\n\t\t\t\tA：塑料行情上涨，但企业流动资金受限，“我的塑料网”可为用户垫付资金，进行代理采购。\n\t\t\t")]), _vm._v(" "), _c('li', [_c('div', {
    staticClass: "configIco config3"
  }, [_vm._v("Q：什么是塑料配资？")]), _vm._v("\n\t\t\t\tA：塑料行情上涨，但企业流动资金受限，“我的塑料网”可为用户垫付资金，进行代理采购。\n\t\t\t")])])])
}]}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-3facb4f5", module.exports)
  }
}

/***/ }),

/***/ 25:
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(48)(
  /* script */
  __webpack_require__(89),
  /* template */
  __webpack_require__(148),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "D:\\xampp\\htdocs\\workspace2\\www\\view\\default\\plasticzone\\src\\views\\plasticconfig.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] plasticconfig.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-3facb4f5", Component.options)
  } else {
    hotAPI.reload("data-v-3facb4f5", Component.options)
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

/***/ 89:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });


/* harmony default export */ __webpack_exports__["default"] = ({
	data: function data() {
		return {};
	},
	methods: {
		tel: function tel() {
			weui.actionSheet([{
				label: '<a style=" color:#0091ff;" href="tel:4006129965">400-6129-965</a>',
				onClick: function onClick() {}
			}, {
				label: '<a style=" color:#0091ff;" href="tel:02161070985">021-61070985</a>',
				onClick: function onClick() {}
			}], [{
				label: '<span style=" color:#0091ff;">取消</span>',
				onClick: function onClick() {}
			}], {
				className: 'custom-classname'
			});
		}
	},
	activated: function activated() {
		var _this = this;
		try {
			var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
			piwikTracker.trackPageView();
		} catch (err) {}
	}
});

/***/ })

});