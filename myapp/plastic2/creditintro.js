webpackJsonp([40],{

/***/ 119:
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _vm._m(0)
},staticRenderFns: [function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "buyWrap",
    staticStyle: {
      "padding": "45px 0 0 0"
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
  }), _vm._v("\n\t\t\t企业授信说明\n\t\t")])]), _vm._v(" "), _c('div', {
    staticStyle: {
      "padding": "10px",
      "background": "#FFFFFF",
      "font-size": "12px"
    }
  }, [_c('p', [_vm._v("授信说明:")]), _vm._v(" "), _c('p', [_vm._v("本授信是[我的塑料网]针对网站的优质客户，提供的供应链金融业务授信，客户可以在授信额度范围内使用[我的塑料网]提供的供应链金融产品，满足企业的融资需求。")]), _vm._v(" "), _c('p', [_vm._v("特点:")]), _vm._v(" "), _c('p', [_vm._v("1.以便捷、高效的整体合作模式，使客户迅速得到全面和规范的供应链金融服务；")]), _vm._v(" "), _c('p', [_vm._v("2.节省客户的用款成本，简化工作手续，提高融资效率；")]), _vm._v(" "), _c('p', [_vm._v("3.基于真实订单需求，在整体信用额度的前提下，随借随用，即时到账；")]), _vm._v(" "), _c('p', [_vm._v("4.低利息、低费用。")]), _vm._v(" "), _c('p', [_vm._v("适用客户:")]), _vm._v(" "), _c('p', [_vm._v("企业信用良好、与[我的塑料网]订单成交三笔以上以及其他必要条件。")]), _vm._v(" "), _c('p', [_vm._v("注：授信解释权归[我的塑料网]所有。")])])])
}]}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-26013c66", module.exports)
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

/***/ 5:
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(46)(
  /* script */
  __webpack_require__(62),
  /* template */
  __webpack_require__(119),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "D:\\xampp\\htdocs\\workspace2\\www\\view\\default\\plasticzone\\src\\views\\creditintro.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] creditintro.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-26013c66", Component.options)
  } else {
    hotAPI.reload("data-v-26013c66", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),

/***/ 62:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });


/* harmony default export */ __webpack_exports__["default"] = ({
	data: function data() {
		return {};
	},
	mounted: function mounted() {
		try {
			var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
			piwikTracker.trackPageView();
		} catch (err) {}
	}
});

/***/ })

});