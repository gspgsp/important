webpackJsonp([19],{

/***/ 131:
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "buyWrap",
    staticStyle: {
      "padding": "45px 0 0 0"
    }
  }, [_vm._m(0), _vm._v(" "), _c('div', {
    staticClass: "searchcompany"
  }), _vm._v(" "), _c('h2', {
    staticStyle: {
      "text-align": "center",
      "font-size": "30px",
      "color": "#333333",
      "margin": "15px 0"
    }
  }, [_vm._v("精准查询")]), _vm._v(" "), _vm._m(1), _vm._v(" "), _c('div', {
    staticClass: "searchfname"
  }, [_c('div', {
    staticClass: "searchfnameWrap",
    staticStyle: {
      "margin": "0"
    }
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
      "float": "left",
      "border": "none",
      "padding": "5px 7px",
      "background": "none",
      "font-size": "12px"
    },
    attrs: {
      "type": "text",
      "placeholder": "请输入企业全称"
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
  }, [_vm._v("查授信额度")])])])]), _vm._v(" "), _c('ul', {
    staticClass: "searchli"
  }, _vm._l((_vm.creditli), function(c) {
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
  }))])
},staticRenderFns: [function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
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
  }), _vm._v("\n\t\t\t查别人\n\t\t")])])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('p', {
    staticStyle: {
      "text-align": "center"
    }
  }, [_vm._v("企业名称查询"), _c('br'), _vm._v("自动关联企业相关数据")])
}]}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-621be368", module.exports)
  }
}

/***/ }),

/***/ 42:
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(46)(
  /* script */
  __webpack_require__(99),
  /* template */
  __webpack_require__(131),
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

/***/ 99:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });


/* harmony default export */ __webpack_exports__["default"] = ({
	data: function data() {
		return {
			fname: "",
			creditli: []
		};
	},
	methods: {
		search: function search() {
			var _this = this;
			$.ajax({
				type: "post",
				url: '/api/qapi1_1/creditCertificate',
				data: {
					token: window.localStorage.getItem("token"),
					type: 2,
					page: 1,
					fname: _this.fname
				},
				dataType: 'JSON'
			}).then(function (res) {
				if (res.err == 0) {
					_this.creditli = res.data;
				} else {
					mui.alert("", res.msg, function () {});
				}
			}, function () {});
		}
	},
	mounted: function mounted() {
		this.creditli = [];
		this.fname = "";
		try {
			var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
			piwikTracker.trackPageView();
		} catch (err) {}
	}
});

/***/ })

});