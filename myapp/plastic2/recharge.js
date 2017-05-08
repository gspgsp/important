webpackJsonp([26],{

/***/ 143:
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _vm._m(0)
},staticRenderFns: [function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', [_c('header', {
    attrs: {
      "id": "bigCustomerHeader"
    }
  }, [_c('a', {
    staticClass: "back",
    attrs: {
      "href": "javascript:window.history.back();"
    }
  }), _vm._v("\n\t\t充值\n\t")]), _vm._v(" "), _c('img', {
    staticStyle: {
      "display": "block"
    },
    attrs: {
      "width": "100%",
      "src": "http://statics.myplas.com/myapp/img/recharge.jpg"
    }
  }), _vm._v(" "), _c('img', {
    staticStyle: {
      "display": "block",
      "float": "left"
    },
    attrs: {
      "width": "50%",
      "src": "http://statics.myplas.com/myapp/img/recharge2.jpg"
    }
  }), _vm._v(" "), _c('img', {
    staticStyle: {
      "display": "block",
      "float": "left"
    },
    attrs: {
      "width": "50%",
      "src": "http://statics.myplas.com/myapp/img/recharge3.jpg"
    }
  }), _vm._v(" "), _c('img', {
    staticStyle: {
      "display": "block"
    },
    attrs: {
      "width": "100%",
      "src": "http://statics.myplas.com/myapp/img/recharge4.jpg"
    }
  }), _vm._v(" "), _c('a', {
    attrs: {
      "href": "tel:4006129965"
    }
  }, [_c('img', {
    staticStyle: {
      "display": "block"
    },
    attrs: {
      "width": "100%",
      "src": "http://statics.myplas.com/myapp/img/recharge5.jpg"
    }
  })]), _vm._v(" "), _c('img', {
    staticStyle: {
      "display": "block"
    },
    attrs: {
      "width": "100%",
      "src": "http://statics.myplas.com/myapp/img/recharge6.jpg"
    }
  })])
}]}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-d3066938", module.exports)
  }
}

/***/ }),

/***/ 32:
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(46)(
  /* script */
  __webpack_require__(89),
  /* template */
  __webpack_require__(143),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "D:\\xampp\\htdocs\\workspace2\\www\\view\\default\\plasticzone\\src\\views\\recharge.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] recharge.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-d3066938", Component.options)
  } else {
    hotAPI.reload("data-v-d3066938", Component.options)
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

/***/ 89:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });


/* harmony default export */ __webpack_exports__["default"] = ({
	data: function data() {
		return {};
	},
	activated: function activated() {
		try {
			var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
			piwikTracker.trackPageView();
		} catch (err) {}
	}
});

/***/ })

});