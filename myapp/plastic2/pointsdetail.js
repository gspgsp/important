webpackJsonp([34],{

/***/ 109:
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', [_vm._m(0), _vm._v(" "), _vm._m(1), _vm._v(" "), _c('div', {
    staticClass: "detailtitle2"
  }, [_vm._v("\n\t积分收支明细\n")]), _vm._v(" "), _c('table', {
    attrs: {
      "id": "sdTable",
      "cellpadding": "0",
      "cellspacing": "0"
    }
  }, [_vm._m(2), _vm._v(" "), _vm._l((_vm.detail), function(d) {
    return _c('tr', [_c('td', [_c('span', {
      staticStyle: {
        "color": "#ff5000"
      }
    }, [_vm._v(_vm._s(d.points))])]), _vm._v(" "), _c('td', {
      staticStyle: {
        "text-align": "left"
      }
    }, [_vm._v("今日登陆赠送10塑豆")]), _vm._v(" "), _c('td', [_vm._v(_vm._s(d.addtime))])])
  })], 2)])
},staticRenderFns: [function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('header', {
    attrs: {
      "id": "bigCustomerHeader"
    }
  }, [_c('a', {
    staticClass: "back",
    attrs: {
      "href": "javascript:window.history.back();"
    }
  }), _vm._v("\n\t我的塑豆\n")])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "detailtitle"
  }, [_vm._v("\n\t总塑豆 "), _c('span', [_vm._v("120")]), _vm._v(" "), _c('div', {
    staticStyle: {
      "float": "right"
    }
  }, [_vm._v("今日塑豆 "), _c('span', [_vm._v("20")])])])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('tr', [_c('th', {
    attrs: {
      "width": "20%"
    }
  }, [_vm._v("塑豆")]), _vm._v(" "), _c('th', {
    staticStyle: {
      "text-align": "left"
    },
    attrs: {
      "width": "50%"
    }
  }, [_vm._v("描述")]), _vm._v(" "), _c('th', {
    attrs: {
      "width": "30%"
    }
  }, [_vm._v("时间")])])
}]}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-133a02b1", module.exports)
  }
}

/***/ }),

/***/ 24:
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(46)(
  /* script */
  __webpack_require__(81),
  /* template */
  __webpack_require__(109),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "D:\\xampp\\htdocs\\workspace2\\www\\view\\default\\plasticzone\\src\\views\\pointsdetail.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] pointsdetail.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-133a02b1", Component.options)
  } else {
    hotAPI.reload("data-v-133a02b1", Component.options)
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

/***/ 81:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });


/* harmony default export */ __webpack_exports__["default"] = ({
	data: function data() {
		return {
			detail: [],
			points: 0
		};
	},
	mounted: function mounted() {
		var _this = this;
		try {
			var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
			piwikTracker.trackPageView();
		} catch (err) {}
		$.ajax({
			type: "get",
			url: "/api/qapi1/pointSupplyList",
			data: {
				token: window.localStorage.getItem("token"),
				page: 1,
				size: 50
			},
			dataType: 'JSON'
		}).then(function (res) {
			console.log(res);
			if (res.err == 0) {
				_this.detail = res.data;
				_this.points = res.pointsAll;
			} else if (res.err == 1) {
				mui.alert("", res.msg, function () {
					_this.$router.push({ name: 'login' });
				});
			}
		}, function () {});
	}
});

/***/ })

});