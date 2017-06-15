webpackJsonp([3],{

/***/ 132:
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticStyle: {
      "padding": "88px 0 70px 0"
    }
  }, [_c('header', {
    staticStyle: {
      "position": "fixed",
      "top": "0",
      "left": "0",
      "z-index": "100"
    },
    attrs: {
      "id": "bigCustomerHeader"
    }
  }, [_c('div', {
    staticClass: "plasticSearch"
  }, [_c('i', {
    staticClass: "searchIcon",
    staticStyle: {
      "position": "absolute",
      "top": "8px",
      "left": "5px",
      "margin": "0"
    }
  }), _vm._v(" "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.keywords),
      expression: "keywords"
    }],
    attrs: {
      "type": "text",
      "placeholder": "搜你想搜的"
    },
    domProps: {
      "value": (_vm.keywords)
    },
    on: {
      "keydown": function($event) {
        if (!('button' in $event) && _vm._k($event.keyCode, "enter", 13)) { return null; }
        _vm.search($event)
      },
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.keywords = $event.target.value
      }
    }
  })]), _vm._v(" "), _c('div', {
    staticStyle: {
      "width": "50px",
      "border-radius": "0 3px 3px 0",
      "height": "30px",
      "line-height": "30px",
      "font-size": "12px",
      "font-weight": "normal",
      "background": "#802800",
      "color": "#FFFFFF",
      "position": "absolute",
      "top": "9px",
      "right": "10px",
      "text-align": "center"
    },
    on: {
      "click": _vm.search
    }
  }, [_vm._v("搜索")]), _vm._v(" "), _c('div', {
    staticClass: "plasticnav",
    staticStyle: {
      "margin": "5px 0 0 0"
    }
  }, [_c('div', {
    staticClass: "subscribe",
    on: {
      "click": _vm.subscribeClick
    }
  }), _vm._v(" "), _c('div', {
    staticStyle: {
      "width": "auto",
      "margin": "0 40px 0 0"
    }
  }, [_c('div', {
    staticClass: "swiper-container"
  }, [_c('div', {
    staticClass: "swiper-wrapper"
  }, [_c('div', {
    staticClass: "swiper-slide",
    on: {
      "click": function($event) {
        _vm.changeCate(999)
      }
    }
  }, [_c('a', {
    class: {
      on: _vm.cateid == 999
    }
  }, [_vm._v("\r\n\t\t\t\t\t\t\t\t推荐\r\n\t\t\t\t\t\t\t")])]), _vm._v(" "), _c('div', {
    staticClass: "swiper-slide",
    on: {
      "click": function($event) {
        _vm.changeCate(2)
      }
    }
  }, [_c('a', {
    class: {
      on: _vm.cateid == 2
    }
  }, [_vm._v("\r\n\t\t\t\t\t\t\t\t塑料上游\r\n\t\t\t\t\t\t\t")])]), _vm._v(" "), _c('div', {
    staticClass: "swiper-slide",
    on: {
      "click": function($event) {
        _vm.changeCate(1)
      }
    }
  }, [_c('a', {
    class: {
      on: _vm.cateid == 1
    }
  }, [_vm._v("\r\n\t\t\t\t\t\t\t\t早盘预报\r\n\t\t\t\t\t\t\t")])]), _vm._v(" "), _c('div', {
    staticClass: "swiper-slide",
    on: {
      "click": function($event) {
        _vm.changeCate(9)
      }
    }
  }, [_c('a', {
    class: {
      on: _vm.cateid == 9
    }
  }, [_vm._v("\r\n\t\t\t\t\t\t\t\t企业动态\r\n\t\t\t\t\t\t\t")])]), _vm._v(" "), _c('div', {
    staticClass: "swiper-slide",
    on: {
      "click": function($event) {
        _vm.changeCate(4)
      }
    }
  }, [_c('a', {
    class: {
      on: _vm.cateid == 4
    }
  }, [_vm._v("\r\n\t\t\t\t\t\t\t\t中晨塑说\r\n\t\t\t\t\t\t\t")])]), _vm._v(" "), _c('div', {
    staticClass: "swiper-slide",
    on: {
      "click": function($event) {
        _vm.changeCate(5)
      }
    }
  }, [_c('a', {
    class: {
      on: _vm.cateid == 5
    }
  }, [_vm._v("\r\n\t\t\t\t\t\t\t\t美金市场\r\n\t\t\t\t\t\t\t")])]), _vm._v(" "), _c('div', {
    staticClass: "swiper-slide",
    on: {
      "click": function($event) {
        _vm.changeCate(21)
      }
    }
  }, [_c('a', {
    class: {
      on: _vm.cateid == 21
    }
  }, [_vm._v("\r\n\t\t\t\t\t\t\t\t期货资讯\r\n\t\t\t\t\t\t\t")])]), _vm._v(" "), _c('div', {
    staticClass: "swiper-slide",
    on: {
      "click": function($event) {
        _vm.changeCate(11)
      }
    }
  }, [_c('a', {
    class: {
      on: _vm.cateid == 11
    }
  }, [_vm._v("\r\n\t\t\t\t\t\t\t\t装置动态\r\n\t\t\t\t\t\t\t")])]), _vm._v(" "), _c('div', {
    staticClass: "swiper-slide",
    on: {
      "click": function($event) {
        _vm.changeCate(13)
      }
    }
  }, [_c('a', {
    class: {
      on: _vm.cateid == 13
    }
  }, [_vm._v("\r\n\t\t\t\t\t\t\t\t期刊报告\r\n\t\t\t\t\t\t\t")])]), _vm._v(" "), _c('div', {
    staticClass: "swiper-slide",
    on: {
      "click": function($event) {
        _vm.changeCate(22)
      }
    }
  }, [_c('a', {
    class: {
      on: _vm.cateid == 22
    }
  }, [_vm._v("\r\n\t\t\t\t\t\t\t\t独家解读\r\n\t\t\t\t\t\t\t")])])])])])])]), _vm._v(" "), _c('loadingPage', {
    attrs: {
      "loading": _vm.loadingShow
    }
  }), _vm._v(" "), _c('errorPage', {
    attrs: {
      "loading": _vm.loadingHide
    }
  }), _vm._v(" "), _c('ul', {
    staticClass: "headlineUl3"
  }, _vm._l((_vm.items), function(i) {
    return _c('li', [_c('router-link', {
      attrs: {
        "target": "_blank",
        "to": {
          name: 'headlinedetail',
          params: {
            id: i.id
          }
        }
      }
    }, [_c('h3', [_vm._v(_vm._s(i.type) + _vm._s(i.title))]), _vm._v(" "), _c('p', [_vm._v(_vm._s(i.description))]), _vm._v(" "), _c('p', {
      staticStyle: {
        "text-align": "right",
        "margin": "5px 0 0 0"
      }
    }, [_vm._v("\r\n\t\t\t\t\t" + _vm._s(i.author)), _c('i', {
      staticClass: "headicon"
    }), _vm._v(_vm._s(i.input_time) + "\r\n\t\t\t\t\t"), _c('i', {
      staticClass: "headicon2"
    }), _c('span', {
      staticStyle: {
        "color": "#ff5000"
      }
    }, [_vm._v(_vm._s(i.pv))])])])], 1)
  })), _vm._v(" "), _c('footerbar'), _vm._v(" "), _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.subscribeshow),
      expression: "subscribeshow"
    }],
    staticClass: "subscribelayer"
  }, [_c('h3', {
    staticClass: "subscribetitle",
    staticStyle: {
      "position": "relative"
    }
  }, [_vm._v("订阅栏目："), _c('div', {
    staticClass: "subscribebtn",
    on: {
      "click": _vm.subscribeClose
    }
  })]), _vm._v(" "), _c('ul', {
    staticClass: "mysubscribe",
    staticStyle: {
      "border-bottom": "1px solid #D1D1D1"
    }
  }, [_c('li', {
    class: {
      on: _vm.subscribe.indexOf(2) !== -1, disabled: _vm.unsubscribe.indexOf(2) !== -1
    },
    on: {
      "click": function($event) {
        _vm.chooseCate(2)
      }
    }
  }, [_vm._m(0)]), _vm._v(" "), _c('li', {
    class: {
      on: _vm.subscribe.indexOf(1) !== -1, disabled: _vm.unsubscribe.indexOf(1) !== -1
    },
    on: {
      "click": function($event) {
        _vm.chooseCate(1)
      }
    }
  }, [_vm._m(1)]), _vm._v(" "), _c('li', {
    class: {
      on: _vm.subscribe.indexOf(9) !== -1, disabled: _vm.unsubscribe.indexOf(9) !== -1
    },
    on: {
      "click": function($event) {
        _vm.chooseCate(9)
      }
    }
  }, [_vm._m(2)]), _vm._v(" "), _c('li', {
    class: {
      on: _vm.subscribe.indexOf(4) !== -1, disabled: _vm.unsubscribe.indexOf(4) !== -1
    },
    on: {
      "click": function($event) {
        _vm.chooseCate(4)
      }
    }
  }, [_vm._m(3)]), _vm._v(" "), _c('li', {
    class: {
      on: _vm.subscribe.indexOf(20) !== -1, disabled: _vm.unsubscribe.indexOf(20) !== -1
    },
    on: {
      "click": function($event) {
        _vm.chooseCate(20)
      }
    }
  }, [_vm._m(4)]), _vm._v(" "), _c('li', {
    class: {
      on: _vm.subscribe.indexOf(21) !== -1, disabled: _vm.unsubscribe.indexOf(21) !== -1
    },
    on: {
      "click": function($event) {
        _vm.chooseCate(21)
      }
    }
  }, [_vm._m(5)]), _vm._v(" "), _c('li', {
    class: {
      on: _vm.subscribe.indexOf(11) !== -1, disabled: _vm.unsubscribe.indexOf(11) !== -1
    },
    on: {
      "click": function($event) {
        _vm.chooseCate(11)
      }
    }
  }, [_vm._m(6)]), _vm._v(" "), _c('li', {
    class: {
      on: _vm.subscribe.indexOf(13) !== -1, disabled: _vm.unsubscribe.indexOf(13) !== -1
    },
    on: {
      "click": function($event) {
        _vm.chooseCate(13)
      }
    }
  }, [_vm._m(7)]), _vm._v(" "), _c('li', {
    class: {
      on: _vm.subscribe.indexOf(22) !== -1, disabled: _vm.unsubscribe.indexOf(22) !== -1
    },
    on: {
      "click": function($event) {
        _vm.chooseCate(22)
      }
    }
  }, [_vm._m(8)]), _vm._v(" "), _c('div', {
    staticStyle: {
      "width": "100%",
      "clear": "both"
    }
  })]), _vm._v(" "), _c('h3', {
    staticClass: "subscribetitle"
  }, [_vm._v("制品分类：(推送至推荐栏目下)")]), _vm._v(" "), _c('ul', {
    staticClass: "mysubscribe"
  }, [_c('li', {
    class: {
      on: _vm.property.indexOf(1) !== -1
    },
    on: {
      "click": function($event) {
        _vm.chooseCate2(1)
      }
    }
  }, [_vm._m(9)]), _vm._v(" "), _c('li', {
    class: {
      on: _vm.property.indexOf(2) !== -1
    },
    on: {
      "click": function($event) {
        _vm.chooseCate2(2)
      }
    }
  }, [_vm._m(10)]), _vm._v(" "), _c('li', {
    class: {
      on: _vm.property.indexOf(3) !== -1
    },
    on: {
      "click": function($event) {
        _vm.chooseCate2(3)
      }
    }
  }, [_vm._m(11)]), _vm._v(" "), _c('li', {
    class: {
      on: _vm.property.indexOf(4) !== -1
    },
    on: {
      "click": function($event) {
        _vm.chooseCate2(4)
      }
    }
  }, [_vm._m(12)]), _vm._v(" "), _c('li', {
    class: {
      on: _vm.property.indexOf(5) !== -1
    },
    on: {
      "click": function($event) {
        _vm.chooseCate2(5)
      }
    }
  }, [_vm._m(13)]), _vm._v(" "), _c('li', {
    class: {
      on: _vm.property.indexOf(6) !== -1
    },
    on: {
      "click": function($event) {
        _vm.chooseCate2(6)
      }
    }
  }, [_vm._m(14)]), _vm._v(" "), _c('li', {
    class: {
      on: _vm.property.indexOf(7) !== -1
    },
    on: {
      "click": function($event) {
        _vm.chooseCate2(7)
      }
    }
  }, [_vm._m(15)]), _vm._v(" "), _c('li', {
    class: {
      on: _vm.property.indexOf(8) !== -1
    },
    on: {
      "click": function($event) {
        _vm.chooseCate2(8)
      }
    }
  }, [_vm._m(16)]), _vm._v(" "), _c('li', {
    class: {
      on: _vm.property.indexOf(9) !== -1
    },
    on: {
      "click": function($event) {
        _vm.chooseCate2(9)
      }
    }
  }, [_vm._m(17)]), _vm._v(" "), _c('li', {
    class: {
      on: _vm.property.indexOf(10) !== -1
    },
    on: {
      "click": function($event) {
        _vm.chooseCate2(10)
      }
    }
  }, [_vm._m(18)]), _vm._v(" "), _c('li', {
    class: {
      on: _vm.property.indexOf(11) !== -1
    },
    on: {
      "click": function($event) {
        _vm.chooseCate2(11)
      }
    }
  }, [_vm._m(19)]), _vm._v(" "), _c('li', {
    class: {
      on: _vm.property.indexOf(12) !== -1
    },
    on: {
      "click": function($event) {
        _vm.chooseCate2(12)
      }
    }
  }, [_vm._m(20)]), _vm._v(" "), _c('li', {
    class: {
      on: _vm.property.indexOf(13) !== -1
    },
    on: {
      "click": function($event) {
        _vm.chooseCate2(13)
      }
    }
  }, [_vm._m(21)])])]), _vm._v(" "), _c('div', {
    staticClass: "refresh",
    class: {
      circle: _vm.isCircle
    },
    on: {
      "click": _vm.circle
    }
  }), _vm._v(" "), _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.isArrow),
      expression: "isArrow"
    }],
    staticClass: "arrow",
    on: {
      "click": _vm.arrow
    }
  })], 1)
},staticRenderFns: [function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('i', {
    staticClass: "headlineicon hicon"
  }, [_c('i', {
    staticClass: "cateCheckbox"
  })])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('i', {
    staticClass: "headlineicon hicon2"
  }, [_c('i', {
    staticClass: "cateCheckbox"
  })])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('i', {
    staticClass: "headlineicon hicon3"
  }, [_c('i', {
    staticClass: "cateCheckbox"
  })])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('i', {
    staticClass: "headlineicon hicon4"
  }, [_c('i', {
    staticClass: "cateCheckbox"
  })])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('i', {
    staticClass: "headlineicon hicon5"
  }, [_c('i', {
    staticClass: "cateCheckbox"
  })])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('i', {
    staticClass: "headlineicon hicon6"
  }, [_c('i', {
    staticClass: "cateCheckbox"
  })])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('i', {
    staticClass: "headlineicon hicon7"
  }, [_c('i', {
    staticClass: "cateCheckbox"
  })])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('i', {
    staticClass: "headlineicon hicon8"
  }, [_c('i', {
    staticClass: "cateCheckbox"
  })])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('i', {
    staticClass: "headlineicon hicon9"
  }, [_c('i', {
    staticClass: "cateCheckbox"
  })])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('i', {
    staticClass: "propertyicon hicon"
  }, [_c('i', {
    staticClass: "cateCheckbox"
  })])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('i', {
    staticClass: "propertyicon hicon2"
  }, [_c('i', {
    staticClass: "cateCheckbox"
  })])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('i', {
    staticClass: "propertyicon hicon3"
  }, [_c('i', {
    staticClass: "cateCheckbox"
  })])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('i', {
    staticClass: "propertyicon hicon4"
  }, [_c('i', {
    staticClass: "cateCheckbox"
  })])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('i', {
    staticClass: "propertyicon hicon5"
  }, [_c('i', {
    staticClass: "cateCheckbox"
  })])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('i', {
    staticClass: "propertyicon hicon6"
  }, [_c('i', {
    staticClass: "cateCheckbox"
  })])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('i', {
    staticClass: "propertyicon hicon7"
  }, [_c('i', {
    staticClass: "cateCheckbox"
  })])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('i', {
    staticClass: "propertyicon hicon8"
  }, [_c('i', {
    staticClass: "cateCheckbox"
  })])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('i', {
    staticClass: "propertyicon hicon9"
  }, [_c('i', {
    staticClass: "cateCheckbox"
  })])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('i', {
    staticClass: "propertyicon hicon10"
  }, [_c('i', {
    staticClass: "cateCheckbox"
  })])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('i', {
    staticClass: "propertyicon hicon11"
  }, [_c('i', {
    staticClass: "cateCheckbox"
  })])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('i', {
    staticClass: "propertyicon hicon12"
  }, [_c('i', {
    staticClass: "cateCheckbox"
  })])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('i', {
    staticClass: "propertyicon hicon13"
  }, [_c('i', {
    staticClass: "cateCheckbox"
  })])
}]}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-1a18492f", module.exports)
  }
}

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

/***/ 49:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });


/* harmony default export */ __webpack_exports__["default"] = ({
	data: function data() {
		return {
			isIndex: false,
			isRelease: false,
			isMyzone: false,
			isHeadline: false
		};
	},
	methods: {
		toQuickRelease: function toQuickRelease() {
			var _this = this;
			if (window.localStorage.getItem("token")) {
				_this.$router.push({
					name: 'quickrelease'
				});
			} else {
				weui.alert('您未登录塑料圈,无法查看企业及个人信息', {
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
		},
		toRelease: function toRelease() {
			var _this = this;
			if (window.localStorage.getItem("token")) {
				_this.$router.push({
					name: 'release'
				});
			} else {
				weui.confirm('您未登录塑料圈,无法查看企业及个人信息', function () {
					_this.$router.push({
						name: 'login'
					});
				}, function () {}, {
					title: '塑料圈通讯录'
				});
			}
		},
		toMyzone: function toMyzone() {
			var _this = this;
			if (window.localStorage.getItem("token")) {
				_this.$router.push({
					name: 'myzone'
				});
			} else {
				weui.confirm('您未登录塑料圈,无法查看企业及个人信息', function () {
					_this.$router.push({
						name: 'login'
					});
				}, function () {}, {
					title: '塑料圈通讯录'
				});
			}
		},
		toHeadline: function toHeadline() {
			var _this = this;
			if (window.localStorage.getItem("token")) {
				_this.$router.push({
					name: 'headlinelist'
				});
			} else {
				weui.confirm('您未登录塑料圈,无法查看企业及个人信息', function () {
					_this.$router.push({
						name: 'login'
					});
				}, function () {}, {
					title: '塑料圈通讯录'
				});
			}
		}

	},
	mounted: function mounted() {
		var _this = this;
		var uri = this.$route.name;
		switch (uri) {
			case 'index':
				this.isIndex = true;
				this.isRelease = false;
				this.isMyzone = false;
				this.isHeadline = false;
				break;
			case 'release':
				this.isIndex = false;
				this.isRelease = true;
				this.isMyzone = false;
				this.isHeadline = false;
				break;
			case 'myzone':
			case 'mysupply':
			case 'mybuy':
			case 'myinvite':
			case 'myfans':
			case 'mypay':
			case 'mymsg':
			case 'mymsg2':
			case 'myinfo':
				this.isIndex = false;
				this.isRelease = false;
				this.isMyzone = true;
				this.isHeadline = false;
				break;
			case 'headline':
			case 'headlinedetail':
			case 'headlinelist':
				this.isIndex = false;
				this.isRelease = false;
				this.isMyzone = false;
				this.isHeadline = true;
				break;
		}
	}
});

/***/ }),

/***/ 50:
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(48)(
  /* script */
  __webpack_require__(49),
  /* template */
  __webpack_require__(51),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "D:\\xampp\\htdocs\\workspace2\\www\\view\\default\\plasticzone\\src\\components\\footer.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] footer.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-3efe2928", Component.options)
  } else {
    hotAPI.reload("data-v-3efe2928", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),

/***/ 51:
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', [_c('footer', {
    attrs: {
      "id": "footer"
    }
  }, [_c('ul', [_c('li', [_c('a', {
    class: {
      'footerOn': _vm.isHeadline
    },
    attrs: {
      "href": "javascript:;"
    },
    on: {
      "click": _vm.toHeadline
    }
  }, [_c('i', {
    staticClass: "foot5"
  }), _c('br'), _vm._v("头条")])]), _vm._v(" "), _c('li', [_c('router-link', {
    class: {
      'footerOn': _vm.isIndex
    },
    attrs: {
      "to": {
        name: 'index'
      }
    }
  }, [_c('i', {
    staticClass: "foot2"
  }), _c('br'), _vm._v("通讯录")])], 1), _vm._v(" "), _c('li', [_c('i', {
    staticClass: "releaseicon",
    on: {
      "click": _vm.toQuickRelease
    }
  })]), _vm._v(" "), _c('li', [_c('a', {
    class: {
      'footerOn': _vm.isRelease
    },
    attrs: {
      "href": "javascript:;"
    },
    on: {
      "click": _vm.toRelease
    }
  }, [_c('i', {
    staticClass: "foot3"
  }), _c('br'), _vm._v("供求")])]), _vm._v(" "), _c('li', [_c('a', {
    class: {
      'footerOn': _vm.isMyzone
    },
    attrs: {
      "href": "javascript:;"
    },
    on: {
      "click": _vm.toMyzone
    }
  }, [_c('i', {
    staticClass: "foot4"
  }), _c('br'), _vm._v("我的")])])])])])
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-3efe2928", module.exports)
  }
}

/***/ }),

/***/ 52:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });


/* harmony default export */ __webpack_exports__["default"] = ({
	props: ['loading']
});

/***/ }),

/***/ 53:
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(48)(
  /* script */
  __webpack_require__(52),
  /* template */
  __webpack_require__(54),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "D:\\xampp\\htdocs\\workspace2\\www\\view\\default\\plasticzone\\src\\components\\loadingPage.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] loadingPage.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-40e539ae", Component.options)
  } else {
    hotAPI.reload("data-v-40e539ae", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),

/***/ 54:
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.loading),
      expression: "loading"
    }],
    staticClass: "loadingPage"
  }, [_vm._m(0)])
},staticRenderFns: [function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "loadingWrap"
  }, [_c('div', {
    staticClass: "slqLoading"
  }), _vm._v(" "), _c('div', {
    staticClass: "slqLoadingTxt"
  }, [_vm._v("数据加载中,请稍候...")])])
}]}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-40e539ae", module.exports)
  }
}

/***/ }),

/***/ 56:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });


/* harmony default export */ __webpack_exports__["default"] = ({
	props: ['loading']
});

/***/ }),

/***/ 57:
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(48)(
  /* script */
  __webpack_require__(56),
  /* template */
  __webpack_require__(58),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "D:\\xampp\\htdocs\\workspace2\\www\\view\\default\\plasticzone\\src\\components\\errorPage.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] errorPage.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-50b3658c", Component.options)
  } else {
    hotAPI.reload("data-v-50b3658c", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),

/***/ 58:
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.loading),
      expression: "loading"
    }],
    staticClass: "errorPage"
  }, [_c('div', {
    staticClass: "errorWrap"
  }), _vm._v(" "), _vm._m(0)])
},staticRenderFns: [function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "errorTxt"
  }, [_c('a', {
    staticClass: "blue",
    attrs: {
      "href": "javascript:window.location.reload();"
    }
  }, [_vm._v("重新刷新")]), _vm._v("    \n\t\t"), _c('a', {
    staticClass: "orange",
    attrs: {
      "href": "http://q.myplas.com/"
    }
  }, [_vm._v("返回首页")])])
}]}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-50b3658c", module.exports)
  }
}

/***/ }),

/***/ 73:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__components_footer__ = __webpack_require__(50);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__components_footer___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__components_footer__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__components_loadingPage__ = __webpack_require__(53);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__components_loadingPage___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1__components_loadingPage__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__components_errorPage__ = __webpack_require__(57);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__components_errorPage___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_2__components_errorPage__);





/* harmony default export */ __webpack_exports__["default"] = ({
	components: {
		'footerbar': __WEBPACK_IMPORTED_MODULE_0__components_footer___default.a,
		'loadingPage': __WEBPACK_IMPORTED_MODULE_1__components_loadingPage___default.a,
		'errorPage': __WEBPACK_IMPORTED_MODULE_2__components_errorPage___default.a
	},
	data: function data() {
		return {
			items: [],
			cate: "",
			cateid: "",
			page: 1,
			isCircle: false,
			isArrow: false,
			keywords: "",
			loadingShow: "",
			loadingHide: "",
			subscribeshow: false,
			subscribe: [],
			property: [],
			unsubscribe: []
		};
	},
	beforeRouteEnter: function beforeRouteEnter(to, from, next) {
		next(function (vm) {
			$(window).on('scroll', function () {
				vm.loadingMore();
			});
			$(window).scrollTop(window.localStorage.getItem("HscrollTop"));
		});
	},
	beforeRouteLeave: function beforeRouteLeave(to, from, next) {
		next(function () {});
		$(window).off('scroll');
		window.localStorage.setItem("HscrollTop", $(window).scrollTop());
	},
	methods: {
		loadingMore: function loadingMore() {
			var _this = this;
			var scrollTop = $(this).scrollTop();
			var scrollHeight = $(document).height();
			var windowHeight = $(this).height();
			if (scrollTop > 600) {
				_this.isArrow = true;
			} else {
				_this.isArrow = false;
			}
			if (scrollTop + windowHeight >= scrollHeight) {
				_this.page++;
				$.ajax({
					type: "post",
					url: version + "/toutiao/getCateList",
					data: {
						page: _this.page,
						size: 10,
						cate_id: _this.$route.params.id,
						token: window.localStorage.getItem("token")
					},
					headers: {
						'X-UA': window.localStorage.getItem("XUA")
					},
					dataType: 'JSON'
				}).then(function (res) {
					console.log(res);
					if (res.err == 0) {
						_this.condition = true;
						_this.items = _this.items.concat(res.info);
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
					} else if (res.err == 2) {
						_this.condition = false;
					} else if (res.err == 3) {
						weui.topTips(res.msg, 3000);
					}
				}, function () {});
			}
		},
		chooseCate: function chooseCate(id) {
			var _this = this;
			if (this.subscribe.indexOf(id) == -1) {
				this.subscribe.push(id);
			} else {
				var index = _this.subscribe.indexOf(id);
				this.subscribe.splice(index, 1);
			}
		},
		chooseCate2: function chooseCate2(id) {
			var _this = this;
			if (this.property.indexOf(id) == -1) {
				this.property.push(id);
			} else {
				var index = _this.property.indexOf(id);
				this.property.splice(index, 1);
			}
		},
		arrow: function arrow() {
			window.scrollTo(0, 0);
		},
		subscribeClick: function subscribeClick() {
			var _this = this;
			this.subscribeshow = true;
			$.ajax({
				type: "post",
				url: version + '/toutiao/getSelectCate',
				data: {
					token: window.localStorage.getItem("token"),
					type: 2
				},
				headers: {
					'X-UA': window.localStorage.getItem("XUA")
				},
				dataType: 'JSON'
			}).done(function (res) {
				if (res.err == 0) {
					_this.subscribe = res.data.subscribe;
					_this.property = res.data.property;
					_this.unsubscribe = res.data.unconcealed_subscribe;
				} else {}
			}).fail(function () {}).always(function () {});
		},
		subscribeClose: function subscribeClose() {
			var _this = this;
			if (this.subscribe.length < 6 && this.property.length < 6) {

				weui.toast('订阅栏目与制品分类各选6个', {
					duration: 3000,
					className: 'dingyue',
					callback: function callback() {}
				});
			} else {
				this.subscribeshow = false;
				$.ajax({
					type: "post",
					url: version + '/toutiao/getSelectCate',
					data: {
						token: window.localStorage.getItem("token"),
						cate_id: _this.subscribe.join(),
						prop_id: _this.property.join(),
						type: 1
					},
					headers: {
						'X-UA': window.localStorage.getItem("XUA")
					},
					dataType: 'JSON'
				}).then(function (res) {}, function () {});
			}
		},
		getList: function getList(id) {
			var _this = this;
			this.cateid = id;
			if (id == 999) {
				$.ajax({
					type: "post",
					url: version + '/toutiao/getSubscribe',
					data: {
						token: window.localStorage.getItem("token"),
						subscribe: 2
					},
					headers: {
						'X-UA': window.localStorage.getItem("XUA")
					},
					dataType: 'JSON'
				}).done(function (res) {
					if (res.err == 0) {
						_this.items = res.data;
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
				}).fail(function () {}).always(function () {});
			} else {
				$.ajax({
					type: "post",
					url: version + '/toutiao/getCateList',
					data: {
						page: 1,
						size: 10,
						cate_id: id,
						token: window.localStorage.getItem("token")
					},
					headers: {
						'X-UA': window.localStorage.getItem("XUA")
					},
					dataType: 'JSON'
				}).done(function (res) {
					if (res.err == 0) {
						_this.items = res.info;
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
				}).fail(function () {}).always(function () {});
			}
		},
		search: function search() {
			var _this = this;
			if (this.keywords) {
				try {
					var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
					piwikTracker.trackSiteSearch(this.keywords, "keywords", 20);
				} catch (err) {}

				$.ajax({
					url: version + '/toutiao/getSubscribe',
					type: 'post',
					data: {
						keywords: _this.keywords,
						page: 1,
						subscribe: 1,
						token: window.localStorage.getItem("token")
					},
					headers: {
						'X-UA': window.localStorage.getItem("XUA")
					},
					dataType: 'JSON'
				}).done(function (res) {
					if (res.err == 0) {
						_this.items = res.data;
					}
				}).fail(function () {}).always(function () {});
			} else {}
		},
		changeCate: function changeCate(id) {
			switch (id) {
				case 1:
					this.cate = "早盘预报";
					this.getList(id);
					this.$nextTick(function () {
						var swiper = new Swiper('.swiper-container', {
							slidesPerView: 4,
							spaceBetween: 15,
							freeMode: true
						});
						swiper.slideTo(2, 1000, false);
					});
					break;
				case 2:
					this.cate = "塑料上游";
					this.getList(id);
					this.$nextTick(function () {
						var swiper = new Swiper('.swiper-container', {
							slidesPerView: 4,
							spaceBetween: 15,
							freeMode: true
						});
						swiper.slideTo(1, 1000, false);
					});
					break;
				case 4:
					this.getList(id);
					this.$nextTick(function () {
						var swiper = new Swiper('.swiper-container', {
							slidesPerView: 4,
							spaceBetween: 15,
							freeMode: true
						});
						swiper.slideTo(4, 1000, false);
					});
					this.cate = "中晨塑说";
					break;
				case 5:
					this.getList(id);
					this.$nextTick(function () {
						var swiper = new Swiper('.swiper-container', {
							slidesPerView: 4,
							spaceBetween: 15,
							freeMode: true
						});
						swiper.slideTo(5, 1000, false);
					});
					this.cate = "美金市场";
					break;
				case 9:
					this.getList(id);
					this.$nextTick(function () {
						var swiper = new Swiper('.swiper-container', {
							slidesPerView: 4,
							spaceBetween: 15,
							freeMode: true
						});
						swiper.slideTo(3, 1000, false);
					});
					this.cate = "企业动态";
					break;
				case 11:
					this.getList(id);
					this.$nextTick(function () {
						var swiper = new Swiper('.swiper-container', {
							slidesPerView: 4,
							spaceBetween: 15,
							freeMode: true
						});
						swiper.slideTo(7, 1000, false);
					});
					this.cate = "装置动态";
					break;
				case 13:
					this.getList(id);
					this.$nextTick(function () {
						var swiper = new Swiper('.swiper-container', {
							slidesPerView: 4,
							spaceBetween: 15,
							freeMode: true
						});
						swiper.slideTo(8, 1000, false);
					});
					this.cate = "期刊报告";
					break;
				case 21:
					this.getList(id);
					this.$nextTick(function () {
						var swiper = new Swiper('.swiper-container', {
							slidesPerView: 4,
							spaceBetween: 15,
							freeMode: true
						});
						swiper.slideTo(6, 1000, false);
					});
					this.cate = "期货资讯";
					break;
				case 22:
					this.getList(id);
					this.$nextTick(function () {
						var swiper = new Swiper('.swiper-container', {
							slidesPerView: 4,
							spaceBetween: 15,
							freeMode: true
						});
						swiper.slideTo(9, 1000, false);
					});
					this.cate = "独家解读";
					break;
				case 999:
					this.getList(id);
					this.$nextTick(function () {
						var swiper = new Swiper('.swiper-container', {
							slidesPerView: 4,
							spaceBetween: 15,
							freeMode: true
						});
						swiper.slideTo(0, 1000, false);
					});
					this.cate = "推荐";
					break;
			}
		},
		circle: function circle() {
			var _this = this;
			this.isCircle = true;
			if (this.$route.params.id == 999) {
				$.ajax({
					type: "post",
					url: version + '/toutiao/getSubscribe',
					data: {
						token: window.localStorage.getItem("token"),
						subscribe: 2
					},
					headers: {
						'X-UA': window.localStorage.getItem("XUA")
					},
					dataType: 'JSON'
				}).done(function (res) {
					if (res.err == 0) {
						_this.items = res.data;
						_this.isCircle = false;
						window.scrollTo(0, 0);
						if (res.show_msg) {
							weui.topTips(res.show_msg, 3000);
						}
					} else {}
				}).fail(function () {}).always(function () {});
			} else {
				$.ajax({
					type: "post",
					url: version + '/toutiao/getCateList',
					data: {
						page: 1,
						size: 10,
						cate_id: _this.$route.params.id,
						token: window.localStorage.getItem("token")
					},
					headers: {
						'X-UA': window.localStorage.getItem("XUA")
					},
					dataType: 'JSON'
				}).done(function (res) {
					if (res.err == 0) {
						_this.items = res.info;
						_this.isCircle = false;
						window.scrollTo(0, 0);
						weui.topTips('更新成功', 3000);
					}
				}).fail(function () {}).always(function () {});
			}
		}
	},
	mounted: function mounted() {
		var _this = this;
		try {
			var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
			piwikTracker.trackPageView();
		} catch (err) {}

		this.cateid = 999;

		$.ajax({
			type: "post",
			url: version + '/toutiao/getSubscribe',
			timeout: 15000,
			data: {
				token: window.localStorage.getItem("token"),
				subscribe: 2
			},
			headers: {
				'X-UA': window.localStorage.getItem("XUA")
			},
			dataType: 'JSON'
		}).done(function (res) {
			if (res.err == 0) {
				_this.items = res.data;
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
		}).fail(function () {
			_this.loadingHide = true;
		}).always(function () {
			_this.loadingShow = false;
		});

		this.$nextTick(function () {
			var swiper = new Swiper('.swiper-container', {
				slidesPerView: 4,
				spaceBetween: 15,
				freeMode: true
			});
		});
	}
});

/***/ }),

/***/ 9:
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(48)(
  /* script */
  __webpack_require__(73),
  /* template */
  __webpack_require__(132),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "D:\\xampp\\htdocs\\workspace2\\www\\view\\default\\plasticzone\\src\\views\\headlinelist.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] headlinelist.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-1a18492f", Component.options)
  } else {
    hotAPI.reload("data-v-1a18492f", Component.options)
  }
})()}

module.exports = Component.exports


/***/ })

});