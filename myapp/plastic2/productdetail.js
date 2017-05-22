webpackJsonp([30],{

/***/ 132:
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', [_vm._m(0), _vm._v(" "), _c('div', {
    staticClass: "productwrap"
  }, [_c('img', {
    attrs: {
      "src": _vm.thumb
    }
  })]), _vm._v(" "), _c('div', {
    staticClass: "producttitle"
  }, [_vm._v("\n\t" + _vm._s(_vm.name)), _c('br'), _c('span', [_vm._v(_vm._s(_vm.points))]), _vm._v("积分\n")]), _vm._v(" "), _c('div', {
    staticClass: "productchoose"
  }, [_c('span', {
    class: {
      on: _vm.show
    },
    on: {
      "click": function($event) {
        _vm.isShow(1)
      }
    }
  }, [_vm._v("商品详情")]), _vm._v(" "), _c('span', {
    class: {
      on: !_vm.show
    },
    on: {
      "click": function($event) {
        _vm.isShow(2)
      }
    }
  }, [_vm._v("退换货规定")])]), _vm._v(" "), _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.show),
      expression: "show"
    }],
    staticClass: "product1"
  }, [_c('img', {
    attrs: {
      "src": _vm.img
    }
  })]), _vm._v(" "), _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (!_vm.show),
      expression: "!show"
    }],
    staticClass: "product2"
  }, [_c('p', [_vm._v("兑换商品若出现以下情况，我的塑料网允许退换货：")]), _vm._v(" "), _c('p', [_vm._v("1）商品本身有质量问题，影响使用")]), _vm._v(" "), _c('p', [_vm._v("2）兑换的商品在运输过程中出现损毁")]), _vm._v(" "), _c('p', [_vm._v("用户可在签收后7日内拨打我的塑料网客服热线400-6129-965，")]), _vm._v(" "), _c('p', [_vm._v("申请退换货，退回时，请务必将原包装、内附赠品及说明书和相关文件一并寄回。")]), _vm._v(" "), _c('p', [_vm._v("若出现以下情况，我的塑料网有权不予进行商品退换货：")]), _vm._v(" "), _c('p', [_vm._v("1)非我的塑料网积分商城的兑换商品")]), _vm._v(" "), _c('p', [_vm._v("2)不正常使用商品造成的质量问题")]), _vm._v(" "), _c('p', [_vm._v("3)超过我的塑料网积分商城承诺的7天退换货有效时间")]), _vm._v(" "), _c('p', [_vm._v("4)将商品存储、暴露在不适宜环境中造成的损坏")]), _vm._v(" "), _c('p', [_vm._v("5)因未经授权的修理、改动、不正确的安装造成损坏")]), _vm._v(" "), _c('p', [_vm._v("6)不可抗力导致礼品损坏")]), _vm._v(" "), _c('p', [_vm._v("7)商品的正常磨损")]), _vm._v(" "), _c('p', [_vm._v("8)在退换货之前未与我的塑料网客服取得联系，进行过退换货登记")]), _vm._v(" "), _c('p', [_vm._v("9)退回商品包装或其他附属物不完整或有毁损")]), _vm._v(" "), _c('p', [_vm._v("注：商品图片及文字仅供参考，具体以实物为准。")])]), _vm._v(" "), _c('div', {
    staticClass: "proExchange",
    on: {
      "click": _vm.exchange
    }
  }, [_vm._v("立即兑换")]), _vm._v(" "), _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.proinputshow),
      expression: "proinputshow"
    }],
    staticClass: "proInput"
  }, [_c('p', [_c('span', [_vm._v("收件人:")]), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.receiver),
      expression: "receiver"
    }],
    staticClass: "proText",
    attrs: {
      "id": "receiver",
      "type": "text"
    },
    domProps: {
      "value": (_vm.receiver)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.receiver = $event.target.value
      }
    }
  })]), _vm._v(" "), _c('p', [_c('span', [_vm._v("手机号:")]), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.phone),
      expression: "phone"
    }],
    staticClass: "proText",
    attrs: {
      "id": "phone",
      "type": "number"
    },
    domProps: {
      "value": (_vm.phone)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.phone = $event.target.value
      },
      "blur": function($event) {
        _vm.$forceUpdate()
      }
    }
  })]), _vm._v(" "), _c('p', [_c('span', [_vm._v("联系地址:")]), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.address),
      expression: "address"
    }],
    staticClass: "proText",
    attrs: {
      "id": "address",
      "type": "text"
    },
    domProps: {
      "value": (_vm.address)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.address = $event.target.value
      }
    }
  })]), _vm._v(" "), _c('p', {
    staticStyle: {
      "text-align": "center",
      "margin": "20px 0 0 0"
    }
  }, [_c('input', {
    staticClass: "cancel2",
    attrs: {
      "type": "button",
      "value": "取消"
    },
    on: {
      "click": _vm.cancel
    }
  }), _vm._v(" "), _c('input', {
    staticClass: "confirm2",
    staticStyle: {
      "background": "#ff5000"
    },
    attrs: {
      "type": "button",
      "value": "确定"
    },
    on: {
      "click": _vm.cargosubmit
    }
  })])]), _vm._v(" "), _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.layershow),
      expression: "layershow"
    }],
    staticClass: "layer"
  })])
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
  }), _vm._v("\n\t商品详情\n")])
}]}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-6b4d6213", module.exports)
  }
}

/***/ }),

/***/ 28:
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(46)(
  /* script */
  __webpack_require__(85),
  /* template */
  __webpack_require__(132),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "D:\\xampp\\htdocs\\workspace2\\www\\view\\default\\plasticzone\\src\\views\\productdetail.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] productdetail.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-6b4d6213", Component.options)
  } else {
    hotAPI.reload("data-v-6b4d6213", Component.options)
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

/***/ 85:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });


/* harmony default export */ __webpack_exports__["default"] = ({
	data: function data() {
		return {
			thumb: "",
			points: 0,
			name: "",
			show: true,
			img: "",
			ordertype: "",
			proinputshow: false,
			layershow: false,
			times: 60,
			validCode: "获取验证码",
			phone: "",
			receiver: "",
			address: "",
			vcode: ""
		};
	},
	methods: {
		isShow: function isShow(i) {
			i == 1 ? this.show = true : this.show = false;
		},
		cancel: function cancel() {
			this.proinputshow = false;
			this.layershow = false;
		},
		exchange: function exchange() {
			var _this = this;
			mui.confirm("此次兑换将使用" + _this.points + "积分，确定兑换码？", "温馨提示", ['取消', '确定'], function (e) {
				if (e.index == 1) {
					if (_this.type == 0) {
						_this.proinputshow = true;
						_this.layershow = true;
					}
				} else {}
			});
		},
		cargosubmit: function cargosubmit() {
			var _this = this;
			$.ajax({
				type: "get",
				url: "/api/qapi1/exchangeSupplyOrDemand",
				data: {
					type: 0,
					goods_id: _this.gid,
					receiver: _this.receiver,
					phone: _this.phone,
					address: _this.address,
					token: window.localStorage.getItem("token")
				},
				dataType: 'JSON'
			}).then(function (res) {
				console.log(res.err);
				if (res.err == 0) {
					mui.alert("", res.msg, function () {
						window.location.reload();
					});
				} else if (res.err == 1) {
					mui.alert("", res.msg, function () {
						_this.$router.push({ name: 'login' });
					});
				} else {
					mui.alert("", res.msg, function () {
						window.location.reload();
					});
				}
			}, function () {});
		}
	},
	mounted: function mounted() {
		var _this = this;
		try {
			var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
			piwikTracker.trackPageView();
		} catch (err) {}
		$.ajax({
			type: "get",
			url: "/api/qapi1/getProductInfo",
			data: {
				id: _this.$route.params.id,
				token: window.localStorage.getItem("token")
			},
			dataType: 'JSON'
		}).then(function (res) {
			console.log(res);
			if (res.err == 1) {
				mui.alert("", res.msg, function () {
					_this.$router.push({ name: 'login' });
				});
			} else {
				_this.thumb = res.info.thumb;
				_this.img = res.info.image;
				_this.points = res.info.points;
				_this.name = res.info.name;
				_this.ordertype = res.info.cate_id;
				_this.type = res.info.type;
				_this.gid = res.info.id;
			}
		}, function () {});
	}
});

/***/ })

});