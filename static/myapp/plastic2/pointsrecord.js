webpackJsonp([33],{

/***/ 133:
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', [_vm._m(0), _vm._v(" "), _c('ul', {
    attrs: {
      "id": "pointsrecord"
    }
  }, _vm._l((_vm.record), function(r) {
    return _c('li', [_c('div', {
      staticClass: "record"
    }, [_c('h3', {
      staticClass: "recordtitle"
    }, [_vm._v("\n\t\t\t\t兑换单号：" + _vm._s(r.order_id)), _c('br'), _vm._v("兑换时间：" + _vm._s(r.create_time) + "\n\t\t\t\t"), _c('span', [_vm._v(_vm._s(r.status))])])]), _vm._v(" "), _c('div', {
      staticClass: "recordwrap"
    }, [_c('img', {
      attrs: {
        "src": r.thumb
      }
    }), _vm._v(" "), _c('div', {
      staticClass: "recordinfo"
    }, [_vm._v("\n\t\t\t\t" + _vm._s(r.name) + "\n\t\t\t")])]), _vm._v(" "), _c('div', {
      staticClass: "recordstatus"
    }, [_vm._v("\n\t\t\t更新时间:" + _vm._s(r.update_time) + "\n\t\t\t"), _c('span', [_vm._v("兑换使用积分："), _c('b', [_vm._v(_vm._s(r.usepoints))]), _vm._v("积分")])])])
  }))])
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
  }), _vm._v("\n\t兑换记录\n")])
}]}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-7ccc56b1", module.exports)
  }
}

/***/ }),

/***/ 25:
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(46)(
  /* script */
  __webpack_require__(82),
  /* template */
  __webpack_require__(133),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "D:\\xampp\\htdocs\\workspace2\\www\\view\\default\\plasticzone\\src\\views\\pointsrecord.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] pointsrecord.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-7ccc56b1", Component.options)
  } else {
    hotAPI.reload("data-v-7ccc56b1", Component.options)
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

/***/ 82:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });


/* harmony default export */ __webpack_exports__["default"] = ({
	data: function data() {
		return {
			record: []
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
			url: "/api/qapi1/exchangeList",
			data: {
				token: window.localStorage.getItem("token"),
				page: 1,
				size: 10
			},
			dataType: 'JSON'
		}).then(function (res) {
			console.log(res);
			if (res.err == 0) {
				_this.record = res.info;
			} else if (res.err == 1) {
				mui.alert("", res.msg, function () {
					_this.$router.push({ path: 'login' });
				});
			}
		}, function () {});
	}
});

/***/ })

});