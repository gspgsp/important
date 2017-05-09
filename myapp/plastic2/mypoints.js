webpackJsonp([35],{

/***/ 107:
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', [_vm._m(0), _vm._v(" "), _vm._m(1), _vm._v(" "), _c('div', {
    staticClass: "mypoints"
  }, [_c('a', {
    attrs: {
      "href": "javascript:;"
    }
  }, [_c('i', {
    staticClass: "shopIcon iconPoints"
  }), _c('span', [_vm._v(_vm._s(_vm.points))]), _vm._v("塑豆")]), _vm._v(" "), _c('router-link', {
    staticStyle: {
      "color": "#ff5000"
    },
    attrs: {
      "to": {
        name: 'recharge'
      }
    }
  }, [_c('i', {
    staticClass: "shopIcon iconRecord"
  }), _vm._v("充值塑豆")]), _vm._v(" "), _c('router-link', {
    staticStyle: {
      "color": "#ff5000"
    },
    attrs: {
      "to": {
        name: 'pointsrule'
      }
    }
  }, [_c('i', {
    staticClass: "shopIcon iconIntro"
  }), _vm._v("如何赚塑豆")])], 1), _vm._v(" "), _c('div', {
    staticClass: "pointsWrap"
  }, [_c('div', {
    staticClass: "pointsTitle"
  }, [_vm._v("商品信息")]), _vm._v(" "), _c('ul', {
    attrs: {
      "id": "productUl"
    }
  }, [_c('li', [_c('div', {
    staticStyle: {
      "overflow": "hidden",
      "padding": "10px",
      "position": "relative"
    }
  }, [_c('img', {
    staticStyle: {
      "margin": "0 10px 0 0"
    },
    attrs: {
      "src": _vm.p1.thumb
    }
  }), _vm._v(" "), _c('div', {
    staticClass: "proTxt"
  }, [_vm._v(_vm._s(_vm.p1.name)), _c('br'), _vm._v("所需塑豆"), _c('span', [_vm._v(_vm._s(_vm.p1.points))]), _vm._v("塑豆")]), _vm._v(" "), _c('div', {
    staticClass: "proAmount"
  }, [_vm._v("x" + _vm._s(_vm.pro.num))])]), _vm._v(" "), _c('div', {
    staticClass: "productNum"
  }, [_c('span', [_vm._v("*")]), _vm._v("请选择兑换数量：\n\t\t\t\t"), _c('div', {
    staticClass: "proSelect"
  }, [_c('strong', {
    on: {
      "click": _vm.proMin
    }
  }, [_vm._v("-")]), _vm._v(" "), _c('strong', [_vm._v(_vm._s(_vm.pro.num))]), _vm._v(" "), _c('strong', {
    on: {
      "click": _vm.proAdd
    }
  }, [_vm._v("+")])])]), _vm._v(" "), _c('div', {
    staticClass: "productCost"
  }, [_vm._v("共"), _c('span', [_vm._v(_vm._s(_vm.pro.num))]), _vm._v("件\n\t\t\t\t"), _c('div', {
    staticClass: "exchange",
    on: {
      "click": _vm.proExchange
    }
  }, [_vm._v("提交兑换")]), _vm._v(" "), _c('div', {
    staticClass: "cost"
  }, [_vm._v("总塑豆：" + _vm._s(_vm.pro.cost))])])]), _vm._v(" "), _c('li', [_c('div', {
    staticStyle: {
      "overflow": "hidden",
      "padding": "10px",
      "position": "relative"
    }
  }, [_c('img', {
    staticStyle: {
      "margin": "0 10px 0 0"
    },
    attrs: {
      "src": _vm.p2.thumb
    }
  }), _vm._v(" "), _c('div', {
    staticClass: "proTxt"
  }, [_vm._v(_vm._s(_vm.p2.name)), _c('br'), _vm._v("所需塑豆"), _c('span', [_vm._v(_vm._s(_vm.p2.points))]), _vm._v("塑豆")]), _vm._v(" "), _c('div', {
    staticClass: "proAmount"
  }, [_vm._v("x" + _vm._s(_vm.pro2.num))])]), _vm._v(" "), _c('div', {
    staticClass: "productNum"
  }, [_c('span', [_vm._v("*")]), _vm._v("请选择兑换数量：\n\t\t\t\t"), _c('div', {
    staticClass: "proSelect"
  }, [_c('strong', {
    on: {
      "click": _vm.proMin2
    }
  }, [_vm._v("-")]), _vm._v(" "), _c('strong', [_vm._v(_vm._s(_vm.pro2.num))]), _vm._v(" "), _c('strong', {
    on: {
      "click": _vm.proAdd2
    }
  }, [_vm._v("+")])])]), _vm._v(" "), _vm._m(2), _vm._v(" "), _c('div', {
    staticClass: "proMsgLi"
  }, _vm._l((_vm.p2.myMsg), function(m) {
    return _c('div', [_c('input', {
      directives: [{
        name: "model",
        rawName: "v-model",
        value: (_vm.selected),
        expression: "selected"
      }],
      attrs: {
        "type": "radio",
        "name": "msg"
      },
      domProps: {
        "value": m.id,
        "checked": _vm._q(_vm.selected, m.id)
      },
      on: {
        "__c": function($event) {
          _vm.selected = m.id
        }
      }
    }), _vm._v("\n\t\t\t\t\t" + _vm._s(m.input_time)), _c('br'), _vm._v("\n\t\t\t\t\t供求："), _c('span', [_vm._v(_vm._s(m.contents))])])
  })), _vm._v(" "), _c('div', {
    staticClass: "productCost"
  }, [_vm._v("共"), _c('span', [_vm._v(_vm._s(_vm.pro2.num))]), _vm._v("件\n\t\t\t\t"), _c('div', {
    staticClass: "exchange",
    on: {
      "click": _vm.proExchange2
    }
  }, [_vm._v("提交兑换")]), _vm._v(" "), _c('div', {
    staticClass: "cost"
  }, [_vm._v("总塑豆：" + _vm._s(_vm.pro2.cost))])])])])])])
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
  }), _vm._v("\n\t塑豆商城\n")])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "shopBanner"
  }, [_c('img', {
    attrs: {
      "width": "100%",
      "src": "http://statics.myplas.com/myapp/img/shopBanner.jpg"
    }
  })])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "productMsg"
  }, [_c('span', [_vm._v("*")]), _vm._v("请选择要置顶的供求信息（限选一条）：\n\t\t\t")])
}]}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-09e1124c", module.exports)
  }
}

/***/ }),

/***/ 20:
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(46)(
  /* script */
  __webpack_require__(77),
  /* template */
  __webpack_require__(107),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "D:\\xampp\\htdocs\\workspace2\\www\\view\\default\\plasticzone\\src\\views\\mypoints.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] mypoints.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-09e1124c", Component.options)
  } else {
    hotAPI.reload("data-v-09e1124c", Component.options)
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

/***/ 77:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });


/* harmony default export */ __webpack_exports__["default"] = ({
	data: function data() {
		return {
			p1: "",
			p2: "",
			points: 0,
			pro: {
				id: "",
				cost: 100,
				num: 1,
				price: 0
			},
			pro2: {
				id: "",
				cost: 100,
				num: 1,
				price: 0
			},
			selected: ""
		};
	},
	methods: {
		proAdd: function proAdd() {
			this.pro.num++;
			this.pro.cost = this.pro.num * this.pro.price;
		},
		proMin: function proMin() {
			if (this.pro.num < 2) {
				return false;
			} else {
				this.pro.num--;
				this.pro.cost = this.pro.num * this.pro.price;
			}
		},
		proExchange: function proExchange() {
			var _this = this;
			$.ajax({
				type: "post",
				url: "/api/qapi1_2/new_exchangeSupplyOrDemand",
				data: {
					token: window.localStorage.getItem("token"),
					goods_id: _this.pro.id,
					num: _this.pro.num,
					pur_id: ""
				},
				dataType: 'JSON'
			}).then(function (res) {
				if (res.err == 0) {
					$.ajax({
						type: "post",
						url: "/api/score/decScore",
						data: {
							token: window.localStorage.getItem("token"),
							type: 1,
							points: _this.pro.cost,
							gid: _this.pro.id
						},
						dataType: 'JSON'
					}).then(function (res) {}, function () {});
					weui.alert("兑换成功", {
						title: '塑料圈通讯录',
						buttons: [{
							label: '确定',
							type: 'parimary',
							onClick: function onClick() {
								window.location.reload();
							}
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
		},
		proAdd2: function proAdd2() {
			this.pro2.num++;
			this.pro2.cost = this.pro2.num * this.pro2.price;
		},
		proMin2: function proMin2() {
			if (this.pro2.num < 2) {
				return false;
			} else {
				this.pro2.num--;
				this.pro2.cost = this.pro2.num * this.pro2.price;
			}
		},
		proExchange2: function proExchange2() {
			var _this = this;
			$.ajax({
				type: "post",
				url: "/api/qapi1_2/new_exchangeSupplyOrDemand",
				data: {
					token: window.localStorage.getItem("token"),
					goods_id: _this.pro2.id,
					num: _this.pro2.num,
					pur_id: _this.selected
				},
				dataType: 'JSON'
			}).then(function (res) {
				if (res.err == 0) {
					$.ajax({
						type: "post",
						url: "/api/score/decScore",
						data: {
							token: window.localStorage.getItem("token"),
							type: 1,
							points: _this.pro.cost,
							gid: _this.pro2.id
						},
						dataType: 'JSON'
					}).then(function (res) {}, function () {});
					weui.alert("兑换成功", {
						title: '塑料圈通讯录',
						buttons: [{
							label: '确定',
							type: 'parimary',
							onClick: function onClick() {
								window.location.reload();
							}
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
	},
	beforeRouteEnter: function beforeRouteEnter(to, from, next) {
		next(function (vm) {});
		document.title = "塑料圈通讯录";
	},
	activated: function activated() {
		var _this = this;
		window.scrollTo(0, 0);
		try {
			var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
			piwikTracker.trackPageView();
		} catch (err) {}

		$.ajax({
			type: "post",
			url: "/api/qapi1_2/getProductList",
			data: {
				token: window.localStorage.getItem("token"),
				page: 1,
				size: 10
			},
			dataType: 'JSON'
		}).then(function (res) {
			if (res.err == 1) {
				weui.alert(res.msg, {
					title: '塑料圈通讯录',
					buttons: [{
						label: '确定',
						type: 'parimary',
						onClick: function onClick() {
							_this.$router.push({ name: 'login' });
						}
					}]
				});
			} else {
				_this.p1 = res.info[0];
				_this.p2 = res.info[1];
				_this.pro.price = res.info[0].points;
				_this.pro2.price = res.info[1].points;
				_this.pro.id = res.info[0].id;
				_this.pro2.id = res.info[1].id;
				_this.points = res.pointsAll;
			}
		}, function () {});
	}
});

/***/ })

});