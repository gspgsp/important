webpackJsonp([35],{

/***/ 134:
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', [_vm._m(0), _vm._v(" "), _c('div', {
    staticClass: "payWrap"
  }, [_c('div', {
    staticStyle: {
      "padding": "0 12px"
    }
  }, [_c('h3', {
    staticClass: "payTitle"
  }, [_vm._v("充值金额:")]), _vm._v(" "), _c('ul', {
    staticClass: "payList"
  }, _vm._l((_vm.pay), function(p, index) {
    return _c('li', {
      class: {
        on: index == _vm.eq ? true : false
      },
      on: {
        "click": function($event) {
          _vm.paySelect(p.money, index)
        }
      }
    }, [_c('div', {
      staticClass: "payBox"
    }, [_c('span', [_vm._v(_vm._s(p.money) + "元")]), _c('br'), _vm._v(_vm._s(p.plasticBean) + "塑豆\n\t\t\t\t")])])
  })), _vm._v(" "), _c('div', {
    staticClass: "payOther"
  }, [_c('span', [_vm._v("其他金额：")]), _vm._v(" "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.inputMoney),
      expression: "inputMoney"
    }],
    staticClass: "payNum",
    attrs: {
      "type": "tel",
      "placeholder": "15"
    },
    domProps: {
      "value": (_vm.inputMoney)
    },
    on: {
      "input": [function($event) {
        if ($event.target.composing) { return; }
        _vm.inputMoney = $event.target.value
      }, _vm.payInput]
    }
  }), _vm._v(" "), _c('b', [_vm._v(_vm._s(_vm.plasticBean))])])]), _vm._v(" "), _vm._m(1), _vm._v(" "), _c('div', {
    staticStyle: {
      "padding": "0 15px",
      "margin": "30px 0 0 0"
    }
  }, [_c('div', {
    staticClass: "wxPayBtn",
    on: {
      "click": _vm.payMoney
    }
  }, [_vm._v("支付")])])])])
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
  }), _vm._v("\n\t充值中心\n")])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "payWay"
  }, [_c('h3', [_vm._v("选择支付方式:")]), _vm._v(" "), _c('div', {
    staticClass: "wxPay"
  }, [_c('i', {
    staticClass: "iconWxPay"
  }), _vm._v("微信支付\n\t\t\t"), _c('i', {
    staticClass: "iconWxRight"
  })])])
}]}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-1e88d67b", module.exports)
  }
}

/***/ }),

/***/ 23:
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(47)(
  /* script */
  __webpack_require__(86),
  /* template */
  __webpack_require__(134),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "D:\\xampp\\htdocs\\workspace2\\www\\view\\default\\plasticzone\\src\\views\\pay.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] pay.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-1e88d67b", Component.options)
  } else {
    hotAPI.reload("data-v-1e88d67b", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),

/***/ 47:
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

/***/ 86:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });


/* harmony default export */ __webpack_exports__["default"] = ({
	data: function data() {
		return {
			pay: [],
			money: null,
			eq: null,
			inputMoney: null,
			plasticBean: ""
		};
	},
	methods: {
		payMoney: function payMoney() {
			var _this = this;
			var jsApiParameters = {};
			function onBridgeReady() {
				console.log("11111");
				WeixinJSBridge.invoke('getBrandWCPayRequest', jsApiParameters, function (res) {
					if (res.err_msg == "get_brand_wcpay_request:ok") {
						alert(ok);
					}
				});
			}

			$.ajax({
				url: version + '/pay/getPrePayOrder',
				type: 'post',
				data: {
					type: 1,
					goods_id: "99",
					total_fee: "0.01",
					goods_num: "1",
					open_id: window.localStorage.getItem("openid")
				},
				headers: {
					'X-UA': window.localStorage.getItem("XUA")
				},
				dataType: 'JSON'
			}).done(function (res) {
				console.log(res);
				console.log(JSON.parseInt(res.data));
				if (res.err == 0) {
					jsApiParameters = {};
					if (typeof WeixinJSBridge == "undefined") {
						if (document.addEventListener) {
							document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
						} else if (document.attachEvent) {
							document.attachEvent('WeixinJSBridgeReady', onBridgeReady);
							document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
						}
					} else {
						onBridgeReady();
					}
				}
			}).fail(function () {});
		},
		paySelect: function paySelect(num, i) {
			this.money = num;
			this.eq = i;
			this.inputMoney = null;
			this.plasticBean = null;
			console.log(this.money);
			console.log(this.eq);
		},
		payInput: function payInput() {
			var _this = this;
			this.eq = null;
			if (this.inputMoney && this.inputMoney <= 10000) {
				$.ajax({
					type: "post",
					url: version + "/pay/getExactAmount",
					data: {
						money: _this.inputMoney
					},
					headers: {
						'X-UA': window.localStorage.getItem("XUA")
					},
					dataType: 'JSON'
				}).then(function (res) {
					if (res.err == 0) {
						_this.plasticBean = res.plasticBean + "塑豆";
					}
				}, function () {});
			} else if (this.inputMoney > 10000) {
				this.inputMoney = 10000;
				$.ajax({
					type: "post",
					url: version + "/pay/getExactAmount",
					data: {
						money: _this.inputMoney
					},
					headers: {
						'X-UA': window.localStorage.getItem("XUA")
					},
					dataType: 'JSON'
				}).then(function (res) {
					if (res.err == 0) {
						_this.plasticBean = res.plasticBean + "塑豆";
					}
				}, function () {});
			} else {
				this.plasticBean = "";
				this.paySelect(10, 0);
			}
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
			url: version + "/pay/getPayConfig",
			data: {},
			headers: {
				'X-UA': window.localStorage.getItem("XUA")
			},
			dataType: 'JSON'
		}).then(function (res) {
			if (res.err == 0) {
				_this.pay = res.data;
				_this.paySelect(res.data[0].money, 0);
			}
		}, function () {});
	}
});

/***/ })

});