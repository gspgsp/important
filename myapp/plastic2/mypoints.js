webpackJsonp([35],{

/***/ 107:
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', [_vm._m(0), _vm._v(" "), _vm._m(1), _vm._v(" "), _c('div', {
    staticClass: "mypoints"
  }, [_c('router-link', {
    attrs: {
      "to": {
        name: 'pointsdetail'
      }
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
      "padding": "10px 0 0 0",
      "position": "relative"
    }
  }, [_c('img', {
    attrs: {
      "src": _vm.p1.thumb
    }
  })]), _vm._v(" "), (_vm.daySelected.length == 0) ? _c('div', {
    staticClass: "productNum"
  }, [_c('span', [_vm._v("*")]), _vm._v("请选置顶日期：\n\t\t\t\t"), _c('i', {
    staticClass: "iconSelect",
    on: {
      "click": _vm.calendarShow
    }
  })]) : _c('div', {
    staticClass: "calendarSelected"
  }, [_c('span', [_vm._v("已选择：")]), _vm._v(" "), _c('div', {
    staticStyle: {
      "width": "auto",
      "margin": "0 25px 0 0",
      "overflow": "hidden"
    }
  }, [_c('div', {
    staticClass: "calendarRange",
    staticStyle: {
      "width": "100%"
    }
  }, _vm._l((_vm.daySelected), function(d) {
    return _c('span', [_vm._v(_vm._s(new Date(d).getMonth() + 1) + "月" + _vm._s(new Date(d).getDate()) + "日")])
  }))]), _vm._v(" "), _c('i', {
    staticClass: "iconSelect",
    on: {
      "click": _vm.calendarShow
    }
  })]), _vm._v(" "), _c('div', {
    staticClass: "productCost"
  }, [_vm._v("共"), _c('span', [_vm._v(_vm._s(_vm.daySelected.length))]), _vm._v("件\n\t\t\t\t"), _c('div', {
    staticClass: "exchange",
    on: {
      "click": _vm.proExchange
    }
  }, [_vm._v("支付")]), _vm._v(" "), _c('div', {
    staticClass: "cost"
  }, [_vm._v("总计：" + _vm._s(_vm.pro.cost * _vm.daySelected.length) + "塑豆")])])]), _vm._v(" "), _c('li', [_c('div', {
    staticStyle: {
      "overflow": "hidden",
      "padding": "10px 0 0 0",
      "position": "relative"
    }
  }, [_c('img', {
    attrs: {
      "src": _vm.p2.thumb
    }
  })]), _vm._v(" "), (!_vm.selectedTxt) ? _c('div', {
    staticClass: "productMsg",
    staticStyle: {
      "line-height": "45px"
    }
  }, [_c('span', [_vm._v("*")]), _vm._v("请选择置顶供求信息：\n\t\t\t\t"), _c('i', {
    staticClass: "iconSelect",
    on: {
      "click": _vm.releaseWrapShow
    }
  })]) : _c('div', {
    staticClass: "productMsg",
    staticStyle: {
      "line-height": "45px"
    }
  }, [_c('span', {
    staticStyle: {
      "color": "#333333"
    }
  }, [_vm._v("已选择：")]), _vm._v(_vm._s(_vm.selectedTxt) + "\n\t\t\t\t"), _c('i', {
    staticClass: "iconSelect",
    on: {
      "click": _vm.releaseWrapShow
    }
  })]), _vm._v(" "), (_vm.daySelected2.length == 0) ? _c('div', {
    staticClass: "productNum"
  }, [_c('span', [_vm._v("*")]), _vm._v("请选置顶日期：\n\t\t\t\t"), _c('i', {
    staticClass: "iconSelect",
    on: {
      "click": _vm.calendarShow2
    }
  })]) : _c('div', {
    staticClass: "calendarSelected",
    staticStyle: {
      "border-top": "1px solid #D9D9D9"
    }
  }, [_c('span', [_vm._v("已选择：")]), _vm._v(" "), _c('div', {
    staticStyle: {
      "width": "auto",
      "margin": "0 25px 0 0",
      "overflow": "hidden"
    }
  }, [_c('div', {
    staticClass: "calendarRange",
    staticStyle: {
      "width": "100%"
    }
  }, _vm._l((_vm.daySelected2), function(d) {
    return _c('span', [_vm._v(_vm._s(new Date(d).getMonth() + 1) + "月" + _vm._s(new Date(d).getDate()) + "日")])
  }))]), _vm._v(" "), _c('i', {
    staticClass: "iconSelect",
    on: {
      "click": _vm.calendarShow2
    }
  })]), _vm._v(" "), _c('div', {
    staticClass: "productCost"
  }, [_vm._v("共"), _c('span', [_vm._v(_vm._s(_vm.daySelected2.length))]), _vm._v("件\n\t\t\t\t"), _c('div', {
    staticClass: "exchange",
    on: {
      "click": _vm.proExchange2
    }
  }, [_vm._v("支付")]), _vm._v(" "), _c('div', {
    staticClass: "cost"
  }, [_vm._v("总计：" + _vm._s(_vm.pro2.cost * _vm.daySelected2.length) + "塑豆")])])])])]), _vm._v(" "), (_vm.dateShow) ? _c('div', {
    staticClass: "calendarLayer"
  }, [_c('div', {
    staticClass: "calendarWrap"
  }, [_c('div', {
    staticClass: "calendarNav"
  }, [_vm._v("通讯录一天置顶卡"), _c('span', {
    on: {
      "click": _vm.calendarHide
    }
  }, [_vm._v("X")])]), _vm._v(" "), _c('div', {
    staticClass: "calendarTitle"
  }, [_vm._v("日期选择：")]), _vm._v(" "), _c('div', {
    staticClass: "calendar",
    attrs: {
      "id": "calendar"
    }
  }, [_c('div', {
    staticClass: "calendar-title-box"
  }, [_c('span', {
    staticClass: "calendar-title",
    attrs: {
      "id": "calendarTitle"
    }
  }, [_vm._v(_vm._s(_vm.currentYear) + "年" + _vm._s(_vm.currentMonth) + "月")])]), _vm._v(" "), _c('div', {
    staticClass: "calendar-body-box"
  }, [_vm._m(2), _vm._v(" "), _c('ul', {
    staticClass: "days"
  }, _vm._l((_vm.days), function(d) {
    return _c('li', [(d.show) ? _c('span', {
      class: {
        disabled: d.disabled, on: d.on
      },
      on: {
        "click": function($event) {
          _vm.pick(d.day)
        }
      }
    }, [_vm._v(_vm._s(new Date(d.day).getDate()))]) : _vm._e()])
  }))])]), _vm._v(" "), _c('div', {
    staticClass: "calendar",
    attrs: {
      "id": "calendar2"
    }
  }, [_c('div', {
    staticClass: "calendar-title-box"
  }, [_c('span', {
    staticClass: "calendar-title",
    attrs: {
      "id": "calendarTitle"
    }
  }, [_vm._v(_vm._s(_vm.currentYear2) + "年" + _vm._s(_vm.currentMonth2) + "月")])]), _vm._v(" "), _c('div', {
    staticClass: "calendar-body-box"
  }, [_vm._m(3), _vm._v(" "), _c('ul', {
    staticClass: "days"
  }, _vm._l((_vm.days2), function(d) {
    return _c('li', [(d.show) ? _c('span', {
      class: {
        disabled: d.disabled, on: d.on
      },
      on: {
        "click": function($event) {
          _vm.pick(d.day)
        }
      }
    }, [_vm._v(_vm._s(new Date(d.day).getDate()))]) : _vm._e()])
  }))])]), _vm._v(" "), _c('div', {
    staticClass: "calendarSelected"
  }, [_c('span', [_vm._v("已选择：")]), _vm._v(" "), _c('div', {
    staticClass: "calendarRange"
  }, _vm._l((_vm.daySelected), function(d) {
    return _c('span', [_vm._v(_vm._s(new Date(d).getMonth() + 1) + "月" + _vm._s(new Date(d).getDate()) + "日")])
  }))])])]) : _vm._e(), _vm._v(" "), (_vm.dateShow2) ? _c('div', {
    staticClass: "calendarLayer"
  }, [_c('div', {
    staticClass: "calendarWrap"
  }, [_c('div', {
    staticClass: "calendarNav"
  }, [_vm._v("供求信息一天置顶卡"), _c('span', {
    on: {
      "click": _vm.calendarHide2
    }
  }, [_vm._v("X")])]), _vm._v(" "), _c('div', {
    staticClass: "calendarTitle"
  }, [_vm._v("日期选择：")]), _vm._v(" "), _c('div', {
    staticClass: "calendar",
    attrs: {
      "id": "calendar"
    }
  }, [_c('div', {
    staticClass: "calendar-title-box"
  }, [_c('span', {
    staticClass: "calendar-title",
    attrs: {
      "id": "calendarTitle"
    }
  }, [_vm._v(_vm._s(_vm.currentYear_) + "年" + _vm._s(_vm.currentMonth_) + "月")])]), _vm._v(" "), _c('div', {
    staticClass: "calendar-body-box"
  }, [_vm._m(4), _vm._v(" "), _c('ul', {
    staticClass: "days"
  }, _vm._l((_vm.days), function(d) {
    return _c('li', [(d.show) ? _c('span', {
      class: {
        disabled: d.disabled, on: d.on
      },
      on: {
        "click": function($event) {
          _vm.pick2(d.day)
        }
      }
    }, [_vm._v(_vm._s(new Date(d.day).getDate()))]) : _vm._e()])
  }))])]), _vm._v(" "), _c('div', {
    staticClass: "calendar",
    attrs: {
      "id": "calendar2"
    }
  }, [_c('div', {
    staticClass: "calendar-title-box"
  }, [_c('span', {
    staticClass: "calendar-title",
    attrs: {
      "id": "calendarTitle"
    }
  }, [_vm._v(_vm._s(_vm.currentYear2_) + "年" + _vm._s(_vm.currentMonth2_) + "月")])]), _vm._v(" "), _c('div', {
    staticClass: "calendar-body-box"
  }, [_vm._m(5), _vm._v(" "), _c('ul', {
    staticClass: "days"
  }, _vm._l((_vm.days2_), function(d) {
    return _c('li', [(d.show) ? _c('span', {
      class: {
        disabled: d.disabled, on: d.on
      },
      on: {
        "click": function($event) {
          _vm.pick2(d.day)
        }
      }
    }, [_vm._v(_vm._s(new Date(d.day).getDate()))]) : _vm._e()])
  }))])]), _vm._v(" "), _c('div', {
    staticClass: "calendarSelected"
  }, [_c('span', [_vm._v("已选择：")]), _vm._v(" "), _c('div', {
    staticClass: "calendarRange"
  }, _vm._l((_vm.daySelected2), function(d) {
    return _c('span', [_vm._v(_vm._s(new Date(d).getMonth() + 1) + "月" + _vm._s(new Date(d).getDate()) + "日")])
  }))])])]) : _vm._e(), _vm._v(" "), (_vm.releaseShow) ? _c('div', {
    staticClass: "calendarLayer"
  }, [_c('div', {
    staticClass: "calendarWrap"
  }, [_c('div', {
    staticClass: "calendarNav"
  }, [_vm._v("供求信息一天置顶卡"), _c('span', {
    on: {
      "click": _vm.releaseWrapHide
    }
  }, [_vm._v("X")])]), _vm._v(" "), _c('div', {
    staticClass: "calendarTitle"
  }, [_vm._v("供求信息选择(限选一条)：")]), _vm._v(" "), _c('div', {
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
    }), _vm._v(" " + _vm._s(m.input_time) + "\r\n\t\t\t\t"), _c('br'), _vm._v(" 供求：\r\n\t\t\t\t"), _c('span', [_vm._v(_vm._s(m.contents))])])
  })), _vm._v(" "), _c('div', {
    staticClass: "calendarSelected",
    staticStyle: {
      "border-top": "1px solid #ddd"
    }
  }, [_c('span', [_vm._v("已选择：")]), _vm._v(" "), _c('div', [_vm._v(_vm._s(_vm.selectedTxt))])])])]) : _vm._e()])
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
  return _c('ul', {
    staticClass: "weekdays"
  }, [_c('li', [_vm._v("日")]), _vm._v(" "), _c('li', [_vm._v("一")]), _vm._v(" "), _c('li', [_vm._v("二")]), _vm._v(" "), _c('li', [_vm._v("三")]), _vm._v(" "), _c('li', [_vm._v("四")]), _vm._v(" "), _c('li', [_vm._v("五")]), _vm._v(" "), _c('li', [_vm._v("六")])])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('ul', {
    staticClass: "weekdays"
  }, [_c('li', [_vm._v("日")]), _vm._v(" "), _c('li', [_vm._v("一")]), _vm._v(" "), _c('li', [_vm._v("二")]), _vm._v(" "), _c('li', [_vm._v("三")]), _vm._v(" "), _c('li', [_vm._v("四")]), _vm._v(" "), _c('li', [_vm._v("五")]), _vm._v(" "), _c('li', [_vm._v("六")])])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('ul', {
    staticClass: "weekdays"
  }, [_c('li', [_vm._v("日")]), _vm._v(" "), _c('li', [_vm._v("一")]), _vm._v(" "), _c('li', [_vm._v("二")]), _vm._v(" "), _c('li', [_vm._v("三")]), _vm._v(" "), _c('li', [_vm._v("四")]), _vm._v(" "), _c('li', [_vm._v("五")]), _vm._v(" "), _c('li', [_vm._v("六")])])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('ul', {
    staticClass: "weekdays"
  }, [_c('li', [_vm._v("日")]), _vm._v(" "), _c('li', [_vm._v("一")]), _vm._v(" "), _c('li', [_vm._v("二")]), _vm._v(" "), _c('li', [_vm._v("三")]), _vm._v(" "), _c('li', [_vm._v("四")]), _vm._v(" "), _c('li', [_vm._v("五")]), _vm._v(" "), _c('li', [_vm._v("六")])])
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
				cost: 100
			},
			pro2: {
				id: "",
				cost: 100
			},
			selected: "",
			releaseTxt: [],
			selectedTxt: "",

			currentMonth: 1,
			currentYear: 1970,
			currentMonth2: 1,
			currentYear2: 1970,
			days: [],
			days2: [],
			daySelected: [],
			dateShow: false,

			currentMonth_: 1,
			currentYear_: 1970,
			currentMonth2_: 1,
			currentYear2_: 1970,
			days_: [],
			days2_: [],
			daySelected2: [],
			dateShow2: false,
			releaseShow: false

		};
	},
	watch: {
		selected: function selected() {
			var _this = this;
			if (this.selected) {
				this.releaseTxt.forEach(function (v, i, a) {
					if (v.id == _this.selected) {
						_this.selectedTxt = '供求：' + v.contents;
					}
				});
			}
		}
	},
	methods: {
		calendarShow: function calendarShow() {
			this.dateShow = true;
		},
		calendarHide: function calendarHide() {
			this.dateShow = false;
		},
		calendarShow2: function calendarShow2() {
			this.dateShow2 = true;
		},
		calendarHide2: function calendarHide2() {
			this.dateShow2 = false;
		},
		releaseWrapShow: function releaseWrapShow() {
			this.releaseShow = true;
		},
		releaseWrapHide: function releaseWrapHide() {
			this.releaseShow = false;
		},
		initCalendar: function initCalendar(startDate, endDate, tookDate) {
			var _this = this;
			var year = new Date(startDate).getFullYear();
			var month = new Date(startDate).getMonth() + 1;
			var firstDay = new Date(year, month - 1, 1);
			var daysTemp = [];
			var startDay = new Date(startDate).getDate();
			this.currentYear = new Date(startDate).getFullYear();
			this.currentMonth = new Date(startDate).getMonth() + 1;

			for (var i = 0; i < 35; i++) {
				var thisDay = new Date(year, month - 1, i + 1 - firstDay.getDay());
				var thisDayStr = this.formatDate(thisDay);
				var thisDayStr = {
					day: _this.formatDate(thisDay),
					disabled: false,
					show: false,
					on: false
				};
				daysTemp.push(thisDayStr);
			}

			daysTemp.forEach(function (v, i, a) {
				if (new Date(v.day).getDate() < startDay) {
					daysTemp[i].disabled = true;
				}
				if (new Date(v.day).getMonth() == month - 1) {
					daysTemp[i].show = true;
				}
			});
			tookDate.forEach(function (v, i, a) {
				for (var i = 0; i < daysTemp.length; i++) {
					if (daysTemp[i].day == v) {
						daysTemp[i].disabled = true;
					}
				}
			});
			this.days = daysTemp;

			var year2 = new Date(endDate).getFullYear();
			var month2 = new Date(endDate).getMonth() + 1;
			var firstDay2 = new Date(year2, month2 - 1, 1);
			var daysTemp2 = [];
			var endDay = new Date(endDate).getDate();
			this.currentYear2 = new Date(endDate).getFullYear();
			this.currentMonth2 = new Date(endDate).getMonth() + 1;

			for (var i = 0; i < 35; i++) {
				var thisDay2 = new Date(year2, month2 - 1, i + 1 - firstDay2.getDay());
				var thisDayStr2 = this.formatDate(thisDay2);
				var thisDayStr2 = {
					day: _this.formatDate(thisDay2),
					disabled: false,
					show: false,
					on: false
				};
				daysTemp2.push(thisDayStr2);
			}
			daysTemp2.forEach(function (v, i, a) {
				if (new Date(v.day).getDate() > endDay) {
					daysTemp2[i].disabled = true;
				}
				if (new Date(v.day).getMonth() == month2 - 1) {
					daysTemp2[i].show = true;
				}
			});
			tookDate.forEach(function (v, i, a) {
				for (var i = 0; i < daysTemp2.length; i++) {
					if (daysTemp2[i].day == v) {
						daysTemp2[i].disabled = true;
					}
				}
			});
			this.days2 = daysTemp2;
			this.totalDays = this.days.concat(this.days2);
		},
		initCalendar2: function initCalendar2(startDate, endDate, tookDate) {
			var _this = this;
			var year = new Date(startDate).getFullYear();
			var month = new Date(startDate).getMonth() + 1;
			var firstDay = new Date(year, month - 1, 1);
			var daysTemp = [];
			var startDay = new Date(startDate).getDate();
			this.currentYear_ = new Date(startDate).getFullYear();
			this.currentMonth_ = new Date(startDate).getMonth() + 1;

			for (var i = 0; i < 35; i++) {
				var thisDay = new Date(year, month - 1, i + 1 - firstDay.getDay());
				var thisDayStr = this.formatDate(thisDay);
				var thisDayStr = {
					day: _this.formatDate(thisDay),
					disabled: false,
					show: false,
					on: false
				};
				daysTemp.push(thisDayStr);
			}

			daysTemp.forEach(function (v, i, a) {
				if (new Date(v.day).getDate() < startDay) {
					daysTemp[i].disabled = true;
				}
				if (new Date(v.day).getMonth() == month - 1) {
					daysTemp[i].show = true;
				}
			});
			tookDate.forEach(function (v, i, a) {
				for (var i = 0; i < daysTemp.length; i++) {
					if (daysTemp[i].day == v) {
						daysTemp[i].disabled = true;
					}
				}
			});
			this.days_ = daysTemp;

			var year2 = new Date(endDate).getFullYear();
			var month2 = new Date(endDate).getMonth() + 1;
			var firstDay2 = new Date(year2, month2 - 1, 1);
			var daysTemp2 = [];
			var endDay = new Date(endDate).getDate();
			this.currentYear2_ = new Date(endDate).getFullYear();
			this.currentMonth2_ = new Date(endDate).getMonth() + 1;

			for (var i = 0; i < 35; i++) {
				var thisDay2 = new Date(year2, month2 - 1, i + 1 - firstDay2.getDay());
				var thisDayStr2 = this.formatDate(thisDay2);
				var thisDayStr2 = {
					day: _this.formatDate(thisDay2),
					disabled: false,
					show: false,
					on: false
				};
				daysTemp2.push(thisDayStr2);
			}
			daysTemp2.forEach(function (v, i, a) {
				if (new Date(v.day).getDate() > endDay) {
					daysTemp2[i].disabled = true;
				}
				if (new Date(v.day).getMonth() == month2 - 1) {
					daysTemp2[i].show = true;
				}
			});
			tookDate.forEach(function (v, i, a) {
				for (var i = 0; i < daysTemp2.length; i++) {
					if (daysTemp2[i].day == v) {
						daysTemp2[i].disabled = true;
					}
				}
			});
			this.days2_ = daysTemp2;
			this.totalDays2 = this.days.concat(this.days2_);
		},
		pick: function pick(date) {
			var _this = this;
			if (this.daySelected.indexOf(date) == -1) {
				this.daySelected.push(date);
				this.totalDays.forEach(function (v, i, a) {
					if (v.day == date) {
						v.on = true;
					}
				});
			} else {
				var index = _this.daySelected.indexOf(date);
				this.daySelected.splice(index, 1);
				this.totalDays.forEach(function (v, i, a) {
					if (v.day == date) {
						v.on = false;
					}
				});
			}
			console.log(this.daySelected);
		},
		pick2: function pick2(date) {
			var _this = this;
			if (this.daySelected2.indexOf(date) == -1) {
				this.daySelected2.push(date);
				this.totalDays2.forEach(function (v, i, a) {
					if (v.day == date) {
						v.on = true;
					}
				});
			} else {
				var index = _this.daySelected2.indexOf(date);
				this.daySelected2.splice(index, 1);
				this.totalDays2.forEach(function (v, i, a) {
					if (v.day == date) {
						v.on = false;
					}
				});
			}
			console.log(this.daySelected2);
		},
		formatDate: function formatDate(date) {
			var _year = date.getFullYear();
			var _month = date.getMonth() + 1;
			var _d = date.getDate();
			_month = _month > 9 ? "" + _month : "0" + _month;
			_d = _d > 9 ? "" + _d : "0" + _d;
			return _year + '-' + _month + '-' + _d;
		},
		proExchange: function proExchange() {
			var _this = this;
			if (this.daySelected.length != 0) {
				$.ajax({
					type: "post",
					url: version + "/product/newExchangeSupplyOrDemand",
					data: {
						token: window.localStorage.getItem("token"),
						goods_id: _this.pro.id,
						dates: _this.daySelected.join(),
						pur_id: ""
					},
					headers: {
						'X-UA': window.localStorage.getItem("XUA")
					},
					dataType: 'JSON'
				}).then(function (res) {
					if (res.err == 0) {
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
			} else {
				weui.alert("请选择置顶日期", {
					title: '塑料圈通讯录',
					buttons: [{
						label: '确定',
						type: 'parimary',
						onClick: function onClick() {}
					}]
				});
			}
		},
		proExchange2: function proExchange2() {
			var _this = this;
			if (this.selected && this.daySelected2.length != 0) {
				$.ajax({
					type: "post",
					url: version + "/product/newExchangeSupplyOrDemand",
					data: {
						token: window.localStorage.getItem("token"),
						goods_id: _this.pro2.id,
						dates: _this.daySelected2.join(),
						pur_id: _this.selected
					},
					headers: {
						'X-UA': window.localStorage.getItem("XUA")
					},
					dataType: 'JSON'
				}).then(function (res) {
					if (res.err == 0) {
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
			} else {
				weui.alert("请选择置顶日期和置顶信息", {
					title: '塑料圈通讯录',
					buttons: [{
						label: '确定',
						type: 'parimary',
						onClick: function onClick() {}
					}]
				});
			}
		}
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
			url: version + "/product/getValidDate",
			data: {
				type: 2
			},
			headers: {
				'X-UA': window.localStorage.getItem("XUA")
			},
			dataType: 'JSON'
		}).then(function (res) {
			if (res.err == 0) {
				_this.initCalendar(res.start_date, res.end_date, res.took_date);
			}
		}, function () {});

		$.ajax({
			type: "post",
			url: version + "/product/getValidDate",
			data: {
				type: 1
			},
			headers: {
				'X-UA': window.localStorage.getItem("XUA")
			},
			dataType: 'JSON'
		}).then(function (res) {
			if (res.err == 0) {
				_this.initCalendar2(res.start_date, res.end_date, res.took_date);
			}
		}, function () {});

		$.ajax({
			type: "post",
			url: version + "/product/getProductList",
			data: {
				token: window.localStorage.getItem("token"),
				page: 1,
				size: 10
			},
			headers: {
				'X-UA': window.localStorage.getItem("XUA")
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
							_this.$router.push({
								name: 'login'
							});
						}
					}]
				});
			} else {
				_this.p1 = res.info[0];
				_this.p2 = res.info[1];
				_this.releaseTxt = res.info[1].myMsg;
				_this.pro.price = res.info[0].points;
				_this.pro2.price = res.info[1].points;
				_this.pro.id = res.info[0].id;
				_this.pro2.id = res.info[1].id;
				_this.points = res.pointsAll;
				console.log(_this.releaseTxt);
			}
		}, function () {});
	}
});

/***/ })

});