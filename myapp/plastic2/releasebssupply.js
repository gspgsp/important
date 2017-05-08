webpackJsonp([22],{

/***/ 127:
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "buyWrap",
    staticStyle: {
      "padding": "45px 0 50px 0"
    }
  }, [_vm._m(0), _vm._v(" "), _c('div', {
    staticClass: "releaseWrap2"
  }, [_c('div', {
    staticStyle: {
      "text-align": "center",
      "padding": "20px 0"
    }
  }, [_c('div', {
    staticStyle: {
      "width": "100%",
      "text-align": "center"
    }
  }, [_c('div', {
    staticClass: "releasebschoose"
  }, [_c('span', {
    class: {
      releasebson: _vm.show1
    },
    on: {
      "click": _vm.spanshow1
    }
  }, [_vm._v("快速发布")]), _vm._v(" "), _c('span', {
    class: {
      releasebson: _vm.show2
    },
    on: {
      "click": _vm.spanshow2
    }
  }, [_vm._v("精准发布")])])])]), _vm._v(" "), _vm._m(1), _vm._v(" "), _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.show1),
      expression: "show1"
    }]
  }, [_c('p', {
    staticStyle: {
      "width": "100%",
      "margin": "20px 0 0 0"
    }
  }, [_c('textarea', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.remark),
      expression: "remark"
    }],
    staticStyle: {
      "width": "100%"
    },
    attrs: {
      "autofocus": "",
      "placeholder": "在此文本框内，可快速复制粘贴供求信息，限制100字以内！",
      "maxlength": "100"
    },
    domProps: {
      "value": (_vm.remark)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.remark = $event.target.value
      }
    }
  })])]), _vm._v(" "), _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.show2),
      expression: "show2"
    }],
    staticStyle: {
      "width": "100%",
      "margin": "20px 0 0 0"
    }
  }, [_c('p', [_c('label', [_vm._v("牌号")]), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.model),
      expression: "model"
    }],
    attrs: {
      "type": "text"
    },
    domProps: {
      "value": (_vm.model)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.model = $event.target.value
      }
    }
  })]), _vm._v(" "), _c('p', [_c('label', [_vm._v("厂家")]), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.f_name),
      expression: "f_name"
    }],
    attrs: {
      "type": "text"
    },
    domProps: {
      "value": (_vm.f_name)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.f_name = $event.target.value
      }
    }
  })]), _vm._v(" "), _c('p', [_c('label', [_vm._v("价格")]), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.price),
      expression: "price"
    }],
    attrs: {
      "type": "number"
    },
    domProps: {
      "value": (_vm.price)
    },
    on: {
      "blur": [_vm.checkNum, function($event) {
        _vm.$forceUpdate()
      }],
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.price = $event.target.value
      }
    }
  })]), _vm._v(" "), _c('p', [_c('label', [_vm._v("交货地")]), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.store_house),
      expression: "store_house"
    }],
    attrs: {
      "type": "text"
    },
    domProps: {
      "value": (_vm.store_house)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.store_house = $event.target.value
      }
    }
  })])])]), _vm._v(" "), _c('div', {
    staticClass: "footrelease"
  }, [_c('input', {
    staticStyle: {
      "border": "none",
      "border-bottom": "1px solid #b33901"
    },
    attrs: {
      "type": "button",
      "disabled": _vm.isDisable,
      "value": "发布"
    },
    on: {
      "click": _vm.sale
    }
  })])])
},staticRenderFns: [function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
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
  }, [_c('a', {
    staticClass: "back",
    attrs: {
      "href": "javascript:window.history.back();"
    }
  }), _vm._v("\n\t\t\t发布供给\n\t\t")])])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticStyle: {
      "padding": "0 10px"
    }
  }, [_c('div', {
    staticStyle: {
      "font-size": "12px",
      "color": "#999",
      "margin": "0"
    }
  }, [_vm._v("快速发布：简单粘贴或复制供求，快速录入；")]), _vm._v(" "), _c('div', {
    staticStyle: {
      "font-size": "12px",
      "color": "#999",
      "margin": "0"
    }
  }, [_vm._v("精准发布：填写准确供求，参与系统比价；")])])
}]}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-46ce435a", module.exports)
  }
}

/***/ }),

/***/ 37:
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(46)(
  /* script */
  __webpack_require__(94),
  /* template */
  __webpack_require__(127),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "D:\\xampp\\htdocs\\workspace2\\www\\view\\default\\plasticzone\\src\\views\\releasebssupply.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] releasebssupply.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-46ce435a", Component.options)
  } else {
    hotAPI.reload("data-v-46ce435a", Component.options)
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

/***/ 94:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });


/* harmony default export */ __webpack_exports__["default"] = ({
	data: function data() {
		return {
			type: 2,
			store_house: "",
			model: "",
			f_name: "",
			price: "",
			remark: "",
			show: false,
			content: "",
			id: "",
			user_id: "",
			isDisable: false,
			show1: true,
			show2: false,
			standard: 1
		};
	},
	methods: {
		spanshow1: function spanshow1() {
			this.show1 = true;
			this.show2 = false;
			this.standard = 1;
		},
		spanshow2: function spanshow2() {
			this.show1 = false;
			this.show2 = true;
			this.standard = 2;
		},
		checkNum: function checkNum() {
			if (this.price < 1000 || this.price > 30000) {
				mui.alert("", "输入的价格不合理", function () {});
			}
		},
		sale: function sale() {
			var _this = this;
			this.isDisable = true;
			var data = [];
			var arr = {
				'model': this.model.toUpperCase(),
				'f_name': this.f_name,
				'store_house': this.store_house,
				'price': this.price,
				'type': 2,
				'quan_type': 0,
				'content': this.remark
			};
			data.push(arr);
			if (this.type && this.store_house && this.model && this.f_name && this.price || this.remark) {
				$.ajax({
					url: '/api/qapi1_2/pub',
					type: 'post',
					data: {
						data: data,
						token: window.localStorage.getItem("token")
					},
					dataType: 'JSON'
				}).then(function (res) {
					if (res.err == 0) {
						$.ajax({
							type: "post",
							url: "/api/score/addScore",
							data: {
								token: window.localStorage.getItem("token"),
								type: '8',
								standard: _this.standard
							},
							dataType: 'JSON'
						}).done(function (res) {}).fail(function () {});
						mui.toast('发布成功', {
							duration: 'long',
							type: 'div'
						});
						_this.isDisable = false;
						_this.$router.push({
							name: 'release'
						});
					} else {
						mui.alert("", res.msg, function () {
							window.location.reload();
						});
					}
				}, function () {});
			} else {
				mui.alert("", "请把信息填写完整", function () {
					_this.isDisable = false;
				});
			}
		}
	},
	activated: function activated() {
		var _this = this;
		try {
			var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
			piwikTracker.trackPageView();
		} catch (err) {}
		$.ajax({
			url: '/api/qapi1/secondPub',
			type: 'get',
			data: {
				id: _this.$route.query.id,
				token: window.localStorage.getItem("token")
			},
			dataType: 'JSON'
		}).then(function (res) {
			if (res.err == 0) {
				if (res.data.f_type == 1) {
					_this.show1 = false;
					_this.show2 = true;
					_this.f_name = res.data.f_name;
					_this.model = res.data.model;
					_this.store_house = res.data.store_house;
					_this.price = res.data.unit_price;
				} else {
					_this.show1 = true;
					_this.show2 = false;
					_this.remark = res.data.content;
				}
			} else if (res.err == 1) {
				mui.alert("", res.msg, function () {
					_this.$router.push({
						name: 'login'
					});
				});
			}
		}, function () {});
	}
});

/***/ })

});