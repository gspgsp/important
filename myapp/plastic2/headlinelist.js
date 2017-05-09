webpackJsonp([3],{

/***/ 111:
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticStyle: {
      "padding": "45px 0 70px 0"
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
  }, [_c('a', {
    staticClass: "back",
    attrs: {
      "href": "javascript:window.history.back();"
    }
  }), _vm._v("\r\n\t" + _vm._s(_vm.cate) + "\r\n")]), _vm._v(" "), _c('loadingPage', {
    attrs: {
      "loading": _vm.loadingShow
    }
  }), _vm._v(" "), _c('errorPage', {
    attrs: {
      "loading": _vm.loadingHide
    }
  }), _vm._v(" "), _c('h3', {
    staticClass: "plasticfind"
  }, [_c('div', {
    staticStyle: {
      "float": "left"
    }
  }, [_vm._v("塑料头条")]), _vm._v(" "), _c('div', {
    staticClass: "plasticSearch"
  }, [_c('i', {
    staticClass: "searchIcon",
    staticStyle: {
      "position": "absolute",
      "top": "14px",
      "left": "5px",
      "margin": "0"
    }
  }), _vm._v(" "), _c('form', {
    attrs: {
      "action": "javascript:;"
    }
  }, [_c('input', {
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
  })])])]), _vm._v(" "), _c('div', {
    staticClass: "plasticnav"
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
  }, [_vm._v("\r\n\t\t\t\t\t推荐\r\n\t\t\t\t")])]), _vm._v(" "), _c('div', {
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
  }, [_vm._v("\r\n\t\t\t\t\t塑料上游\r\n\t\t\t\t")])]), _vm._v(" "), _c('div', {
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
  }, [_vm._v("\r\n\t\t\t\t\t早盘预报\r\n\t\t\t\t")])]), _vm._v(" "), _c('div', {
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
  }, [_vm._v("\r\n\t\t\t\t\t企业动态\r\n\t\t\t\t")])]), _vm._v(" "), _c('div', {
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
  }, [_vm._v("\r\n\t\t\t\t\t中晨塑说\r\n\t\t\t\t")])]), _vm._v(" "), _c('div', {
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
  }, [_vm._v("\r\n\t\t\t\t\t美金市场\r\n\t\t\t\t")])]), _vm._v(" "), _c('div', {
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
  }, [_vm._v("\r\n\t\t\t\t\t期货资讯\r\n\t\t\t\t")])]), _vm._v(" "), _c('div', {
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
  }, [_vm._v("\r\n\t\t\t\t\t装置动态\r\n\t\t\t\t")])]), _vm._v(" "), _c('div', {
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
  }, [_vm._v("\r\n\t\t\t\t\t期刊报告\r\n\t\t\t\t")])]), _vm._v(" "), _c('div', {
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
  }, [_vm._v("\r\n\t\t\t\t\t独家解读\r\n\t\t\t\t")])])])])]), _vm._v(" "), _c('ul', {
    staticClass: "headlineUl3"
  }, _vm._l((_vm.items), function(i) {
    return _c('li', [_c('router-link', {
      attrs: {
        "to": {
          name: 'headlinedetail',
          params: {
            id: i.id
          }
        }
      }
    }, [_c('h3', [_vm._v(_vm._s(i.type) + _vm._s(i.title))]), _vm._v(" "), _c('p', [_vm._v(_vm._s(i.description))]), _vm._v(" "), _c('p', {
      staticStyle: {
        "text-align": "right"
      }
    }, [_vm._v(_vm._s(i.input_time))])])], 1)
  })), _vm._v(" "), _c('footerbar'), _vm._v(" "), _c('div', {
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
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-1a18492f", module.exports)
  }
}

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

/***/ 47:
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
		toMyzone: function toMyzone() {
			var _this = this;
			if (window.localStorage.getItem("token")) {
				_this.$router.push({
					name: 'myzone'
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
		toHeadline: function toHeadline() {
			var _this = this;
			if (window.localStorage.getItem("token")) {
				_this.$router.push({
					name: 'headline'
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

/***/ 48:
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(46)(
  /* script */
  __webpack_require__(47),
  /* template */
  __webpack_require__(49),
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

/***/ 49:
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', [_c('footer', {
    attrs: {
      "id": "footer"
    }
  }, [_c('ul', [_c('li', [_c('a', {
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
  }), _c('br'), _vm._v("供求")])]), _vm._v(" "), _c('li', [_c('router-link', {
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
  }), _c('br'), _vm._v("发现")])]), _vm._v(" "), _c('li', [_c('a', {
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

/***/ 50:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });


/* harmony default export */ __webpack_exports__["default"] = ({
	props: ['loading']
});

/***/ }),

/***/ 51:
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(46)(
  /* script */
  __webpack_require__(50),
  /* template */
  __webpack_require__(52),
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

/***/ 52:
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

/***/ 53:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });


/* harmony default export */ __webpack_exports__["default"] = ({
	props: ['loading']
});

/***/ }),

/***/ 54:
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(46)(
  /* script */
  __webpack_require__(53),
  /* template */
  __webpack_require__(55),
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

/***/ 55:
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

/***/ 66:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__components_footer__ = __webpack_require__(48);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__components_footer___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__components_footer__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__components_loadingPage__ = __webpack_require__(51);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__components_loadingPage___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1__components_loadingPage__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__components_errorPage__ = __webpack_require__(54);
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
			loadingHide: ""
		};
	},
	beforeRouteEnter: function beforeRouteEnter(to, from, next) {
		next(function (vm) {
			vm.loadingShow = true;
		});
	},
	beforeRouteLeave: function beforeRouteLeave(to, from, next) {
		next(function () {});
		this.loadingHide = false;
	},
	methods: {
		arrow: function arrow() {
			window.scrollTo(0, 0);
		},
		getList: function getList(id) {
			var _this = this;
			this.cateid = id;
			if (id == 999) {
				$.ajax({
					type: "post",
					url: '/api/qapi1_1/getSubscribe',
					data: {
						token: window.localStorage.getItem("token"),
						subscribe: 2
					},
					dataType: 'JSON'
				}).done(function (res) {
					if (res.err == 0) {
						_this.items = res.data;
					} else if (res.err == 1) {
						mui.alert("", res.msg, function () {
							_this.$router.push({ name: 'login' });
						});
					}
				}).fail(function () {}).always(function () {});
			} else {
				$.ajax({
					type: "get",
					url: '/api/qapi1/getCateList',
					data: {
						page: 1,
						size: 10,
						cate_id: id,
						token: window.localStorage.getItem("token")
					},
					dataType: 'JSON'
				}).done(function (res) {
					if (res.err == 0) {
						_this.items = res.info;
					} else if (res.err == 1) {
						mui.alert("", res.msg, function () {
							_this.$router.push({ name: 'login' });
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
					url: '/api/qapi1_1/getSubscribe',
					type: 'post',
					data: {
						keywords: _this.keywords,
						page: 1,
						subscribe: 1,
						token: window.localStorage.getItem("token")
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
					url: '/api/qapi1_1/getSubscribe',
					data: {
						token: window.localStorage.getItem("token"),
						subscribe: 2
					},
					dataType: 'JSON'
				}).done(function (res) {
					if (res.err == 0) {
						_this.items = res.data;
						_this.isCircle = false;
						window.scrollTo(0, 0);
						weui.topTips('更新成功', 3000);
					} else {}
				}).fail(function () {}).always(function () {});
			} else {
				$.ajax({
					type: "get",
					url: '/api/qapi1/getCateList',
					data: {
						page: 1,
						size: 10,
						cate_id: _this.$route.params.id,
						token: window.localStorage.getItem("token")
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
	activated: function activated() {
		var _this = this;
		try {
			var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
			piwikTracker.trackPageView();
		} catch (err) {}

		switch (this.$route.params.id) {
			case 1:
				this.cateid = 1;
				this.cate = "早盘预测";
				break;
			case 2:
				this.cateid = 2;
				this.cate = "塑料上游";
				break;
			case 4:
				this.cateid = 4;
				this.cate = "中晨塑说";
				break;
			case 5:
				this.cateid = 5;
				this.cate = "美金市场";
				break;
			case 9:
				this.cateid = 9;
				this.cate = "企业动态";
				break;
			case 11:
				this.cateid = 11;
				this.cate = "装置动态";
				break;
			case 13:
				this.cateid = 13;
				this.cate = "期刊报告";
				break;
			case 21:
				this.cateid = 21;
				this.cate = "期货资讯";
				break;
			case 22:
				this.cateid = 22;
				this.cate = "独家解读";
				break;
			case 999:
				this.cateid = 999;
				this.cate = "推荐";
				break;
		}
		if (this.$route.params.id == 999) {
			$.ajax({
				type: "post",
				url: '/api/qapi1_1/getSubscribe',
				timeout: 15000,
				data: {
					token: window.localStorage.getItem("token"),
					subscribe: 2
				},
				dataType: 'JSON'
			}).done(function (res) {
				if (res.err == 0) {
					_this.items = res.data;
				} else {}
			}).fail(function () {
				_this.loadingHide = true;
			}).always(function () {
				_this.loadingShow = false;
			});
		} else {
			$.ajax({
				type: "get",
				url: '/api/qapi1/getCateList',
				timeout: 15000,
				data: {
					page: 1,
					size: 10,
					cate_id: _this.$route.params.id,
					token: window.localStorage.getItem("token")
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
			}).fail(function () {
				_this.loadingHide = true;
			}).always(function () {
				_this.loadingShow = false;
			});
		}

		this.$nextTick(function () {
			var swiper = new Swiper('.swiper-container', {
				slidesPerView: 4,
				spaceBetween: 15,
				freeMode: true
			});
		});

		$(window).scroll(function () {
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
					type: "get",
					url: "/api/qapi1/getCateList",
					data: {
						page: _this.page,
						size: 10,
						cate_id: _this.$route.params.id,
						token: window.localStorage.getItem("token")
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
		});
	},
	deactivated: function deactivated() {
		$(window).unbind('scroll');
	}

});

/***/ }),

/***/ 9:
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(46)(
  /* script */
  __webpack_require__(66),
  /* template */
  __webpack_require__(111),
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