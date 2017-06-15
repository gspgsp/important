webpackJsonp([33],{

/***/ 104:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });


/* harmony default export */ __webpack_exports__["default"] = ({
	data: function data() {
		return {
			record: [],
			condition: true
		};
	},
	activated: function activated() {
		var _this = this;
		try {
			var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
			piwikTracker.trackPageView();
		} catch (err) {}
		$.ajax({
			type: "post",
			url: version + "/product/getPurchaseRecord",
			data: {
				token: window.localStorage.getItem("token"),
				page: 1,
				size: 30
			},
			headers: {
				'X-UA': window.localStorage.getItem("XUA")
			},
			dataType: 'JSON'
		}).then(function (res) {
			console.log(res);
			if (res.err == 0) {
				_this.condition = true;
				_this.record = res.data;
			} else if (res.err == 2) {
				_this.condition = false;
			}
		}, function () {});
	}
});

/***/ }),

/***/ 156:
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', [_vm._m(0), _vm._v(" "), (_vm.condition) ? _c('ul', {
    attrs: {
      "id": "pointsrecord"
    }
  }, _vm._l((_vm.record), function(r) {
    return _c('li', [_c('div', {
      staticClass: "recordwrap"
    }, [_c('img', {
      attrs: {
        "src": r.thumb
      }
    }), _vm._v(" "), _c('div', {
      staticClass: "recordinfo"
    }, [_c('p', [_vm._v(_vm._s(r.remark) + " " + _vm._s(r.num) + "张")]), _vm._v(" "), _c('p', {
      staticStyle: {
        "font-size": "12px",
        "color": "#999999"
      }
    }, [_vm._v("购买日期:" + _vm._s(r.create_time))]), _vm._v(" "), _c('p', {
      staticStyle: {
        "height": "19px",
        "font-size": "12px",
        "color": "#999999",
        "overflow": "hidden"
      }
    }, [_vm._v("使用日期:" + _vm._s(r.address))]), _vm._v(" "), (r.type == 2) ? _c('p', {
      staticStyle: {
        "height": "19px",
        "font-size": "12px",
        "color": "#999999",
        "overflow": "hidden"
      }
    }, [_vm._v("置顶人：" + _vm._s(r.name))]) : _vm._e(), _vm._v(" "), (r.type == 1) ? _c('p', {
      staticStyle: {
        "height": "19px",
        "font-size": "12px",
        "color": "#999999",
        "overflow": "hidden"
      }
    }, [_vm._v("置顶供求信息：" + _vm._s(r.contents))]) : _vm._e()])]), _vm._v(" "), _c('div', {
      staticClass: "recordstatus"
    }, [_c('span', [_vm._v("总计："), _c('b', {
      staticStyle: {
        "font-weight": "normal",
        "font-size": "12px",
        "color": "#ff5000"
      }
    }, [_vm._v(_vm._s(r.usepoints) + "塑豆")])])])])
  })) : _c('div', {
    staticClass: "releaseMsg"
  }, [_c('div', {
    staticClass: "releaseMsgHead2"
  }), _vm._v(" "), _c('div', {
    staticClass: "releaseTxt"
  }, [_vm._v("没有相关数据")])])])
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
  }), _vm._v("\n\t购买记录\n")])
}]}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-7ccc56b1", module.exports)
  }
}

/***/ }),

/***/ 27:
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(48)(
  /* script */
  __webpack_require__(104),
  /* template */
  __webpack_require__(156),
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