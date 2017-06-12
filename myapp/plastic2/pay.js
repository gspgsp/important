webpackJsonp([36],{

/***/ 136:
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
          _vm.paySelect(p.plasticBean, p.money, index)
        }
      }
    }, [_c('div', {
      staticClass: "payBox"
    }, [_c('span', [_vm._v(_vm._s(p.money) + "元")]), _c('br'), _vm._v(_vm._s(p.plasticBean) + "塑豆\n\t\t\t\t\t")])])
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
  }), _vm._v("\n\t\t充值中心\n\t")])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "payWay"
  }, [_c('h3', [_vm._v("选择支付方式:")]), _vm._v(" "), _c('div', {
    staticClass: "wxPay"
  }, [_c('i', {
    staticClass: "iconWxPay"
  }), _vm._v("微信支付\n\t\t\t\t"), _c('i', {
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

var Component = __webpack_require__(48)(
  /* script */
  __webpack_require__(87),
  /* template */
  __webpack_require__(136),
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

/***/ 87:
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
			plasticBean: "",
			order_id: "",
			beanOrder: {
				bean: "",
				money: ""
			}
		};
	},
	methods: {
		payMoney: function payMoney() {
			var _this = this;
			var jsApiParameters = {};
			function onBridgeReady() {
				WeixinJSBridge.invoke('getBrandWCPayRequest', jsApiParameters, function (res) {
					if (res.err_msg == "get_brand_wcpay_request:ok") {
						$.ajax({
							type: "post",
							url: version + '/pay/updateOrderStatus',
							data: {
								type: 1,
								order_id: _this.order_id,
								status: "4"
							},
							headers: {
								'X-UA': window.localStorage.getItem("XUA")
							},
							dataType: 'JSON'
						}).then(function (res) {
							if (res.err == 0) {
								weui.alert('您支付成功', {
									title: '支付成功',
									buttons: [{
										label: '确定',
										type: 'primary',
										onClick: function onClick() {}
									}]
								});
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
					if (res.err_msg == "get_brand_wcpay_request:cancel") {
						$.ajax({
							type: "post",
							url: version + '/pay/updateOrderStatus',
							data: {
								type: 1,
								order_id: _this.order_id,
								status: "-3"
							},
							headers: {
								'X-UA': window.localStorage.getItem("XUA")
							},
							dataType: 'JSON'
						}).then(function (res) {
							if (res.err == 0) {
								weui.alert('您已取消支付', {
									title: '支付取消',
									buttons: [{
										label: '确定',
										type: 'primary',
										onClick: function onClick() {}
									}]
								});
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
					if (res.err_msg == "get_brand_wcpay_request:fail") {
						$.ajax({
							type: "post",
							url: version + '/pay/updateOrderStatus',
							data: {
								type: 1,
								order_id: _this.order_id,
								status: "-4"
							},
							headers: {
								'X-UA': window.localStorage.getItem("XUA")
							},
							dataType: 'JSON'
						}).then(function (res) {
							if (res.err == 0) {
								weui.alert('请重新充值', {
									title: '支付失败',
									buttons: [{
										label: '确定',
										type: 'primary',
										onClick: function onClick() {}
									}]
								});
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
				});
			}

			$.ajax({
				url: version + '/pay/getPrePayOrder',
				type: 'post',
				data: {
					type: 1,
					goods_id: "99",
					total_fee: _this.beanOrder.money,
					goods_num: _this.beanOrder.bean,
					open_id: window.localStorage.getItem("openid")
				},
				headers: {
					'X-UA': window.localStorage.getItem("XUA")
				},
				dataType: 'JSON'
			}).done(function (res) {
				if (res.err == 0) {
					_this.order_id = res.order_id;
					jsApiParameters = JSON.parse(res.data);
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
			}).fail(function () {});
		},
		paySelect: function paySelect(num, money, i) {
			this.beanOrder.bean = num;
			this.beanOrder.money = money;
			this.money = money;
			this.eq = i;
			this.inputMoney = null;
			this.plasticBean = null;
		},
		payInput: function payInput() {
			var _this = this;
			this.eq = null;
			if (this.inputMoney == 0) {
				this.inputMoney = "";
				this.plasticBean = "";
				this.paySelect(100, 10, 0);
			} else if (this.inputMoney > 0 && this.inputMoney <= 10000) {
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
						_this.beanOrder.bean = res.plasticBean;
						_this.beanOrder.money = _this.inputMoney;
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
						_this.beanOrder.bean = res.plasticBean;
						_this.beanOrder.money = _this.inputMoney;
					}
				}, function () {});
			} else {
				this.plasticBean = "";
				this.paySelect(100, 10, 0);
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
				_this.paySelect(res.data[0].plasticBean, res.data[0].money, 0);
			} else if (res.err == 1) {
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

/***/ })

});