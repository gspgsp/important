webpackJsonp([16],{

/***/ 129:
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "buyWrap",
    staticStyle: {
      "padding": "45px 0 70px 0"
    }
  }, [_vm._m(0), _vm._v(" "), _c('ul', {
    staticClass: "supplyUl"
  }, [_vm._l((_vm.name), function(n) {
    return _c('li', {
      directives: [{
        name: "show",
        rawName: "v-show",
        value: (_vm.condition),
        expression: "condition"
      }]
    }, [_c('div', {
      staticClass: "supplytitle"
    }, [_c('h3', [_vm._v(_vm._s(n.input_time)), _c('span', {
      on: {
        "click": function($event) {
          _vm.del(n.id)
        }
      }
    }, [_vm._v("删除")]), _vm._v(" "), _c('router-link', {
      staticStyle: {
        "float": "right",
        "margin": "0 10px 0 0",
        "color": "#62759e"
      },
      attrs: {
        "to": {
          name: 'supplybuy',
          params: {
            id: n.id
          }
        }
      }
    }, [_vm._v("分享")])], 1), _vm._v(" "), _c('p', [_c('i', {
      staticClass: "myicon iconSupply4"
    }), _vm._v("我的求购:" + _vm._s(n.contents))])]), _vm._v(" "), _c('div', {
      staticClass: "supplycontent"
    }, [_vm._m(1, true), _vm._v(" "), _c('p', [_vm._v("在信息库中，没有找到在卖（买）此牌号的商家！")]), _vm._v(" "), _vm._m(2, true), _vm._v(" "), (n.says.length !== 0) ? _c('h3', [_c('i', {
      staticClass: "myicon iconSupply3"
    }), _vm._v("塑料圈友")]) : _vm._e(), _vm._v(" "), (n.says.length !== 0) ? _c('div', {
      staticClass: "triangle-up"
    }) : _vm._e(), _vm._v(" "), (n.says.length !== 0) ? _c('div', {
      staticClass: "replyRelease2"
    }, [_vm._l((n.says), function(n2) {
      return (n.user_id == n2.rev_id) ? _c('p', [_c('router-link', {
        attrs: {
          "to": {
            name: 'personinfo',
            params: {
              id: n2.user_id
            }
          }
        }
      }, [_vm._v(_vm._s(n2.user_name))]), _vm._v("说:"), _c('i', {
        on: {
          "click": function($event) {
            _vm.reply(n.id, n2.user_id, n2.id)
          }
        }
      }, [_vm._v(_vm._s(n2.content))])], 1) : _vm._e()
    }), _vm._v(" "), _vm._l((n.says), function(n2) {
      return (n.user_id !== n2.rev_id) ? _c('p', [_c('router-link', {
        attrs: {
          "to": {
            name: 'personinfo',
            params: {
              id: n2.user_id
            }
          }
        }
      }, [_vm._v(_vm._s(n2.user_name))]), _vm._v("回复\n\t\t\t\t\t\t"), _c('router-link', {
        attrs: {
          "to": {
            name: 'personinfo',
            params: {
              id: n2.rev_id
            }
          }
        }
      }, [_vm._v(_vm._s(n2.rev_name))]), _vm._v(":"), _c('i', {
        on: {
          "click": function($event) {
            _vm.reply(n.id, n2.user_id, n2.id)
          }
        }
      }, [_vm._v(_vm._s(n2.content))])], 1) : _vm._e()
    })], 2) : _vm._e()])])
  }), _vm._v(" "), _c('li', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (!_vm.condition),
      expression: "!condition"
    }],
    staticStyle: {
      "text-align": "center",
      "height": "60px",
      "line-height": "60px"
    }
  }, [_vm._v("\n\t\t\t没有相关数据\n\t\t")])], 2), _vm._v(" "), _c('footerbar')], 1)
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
  }), _vm._v("\n\t\t\t我的求购\n\t\t")])])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('h3', [_c('i', {
    staticClass: "myicon iconSupply2"
  }), _vm._v("系统消息")])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('p', [_vm._v("参考价："), _c('span', [_vm._v("塑料圈查无此价格")])])
}]}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-587f1b6d", module.exports)
  }
}

/***/ }),

/***/ 13:
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(46)(
  /* script */
  __webpack_require__(70),
  /* template */
  __webpack_require__(129),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "D:\\xampp\\htdocs\\workspace2\\www\\view\\default\\plasticzone\\src\\views\\mybuy.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] mybuy.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-587f1b6d", Component.options)
  } else {
    hotAPI.reload("data-v-587f1b6d", Component.options)
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

/***/ 70:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__components_footer__ = __webpack_require__(48);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__components_footer___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__components_footer__);



/* harmony default export */ __webpack_exports__["default"] = ({
	components: {
		'footerbar': __WEBPACK_IMPORTED_MODULE_0__components_footer___default.a
	},
	data: function data() {
		return {
			name: [],
			page: 1,
			condition: true,
			countShow: false,
			count: "",
			id: "",
			user_id: ""
		};
	},
	methods: {
		del: function del(id) {
			var _this = this;
			weui.confirm('确定删除此条信息', {
				title: '塑料圈通讯录',
				buttons: [{
					label: '取消',
					type: 'default',
					onClick: function onClick() {}
				}, {
					label: '确定',
					type: 'primary',
					onClick: function onClick() {
						$.ajax({
							url: '/api/qapi1/deleteMyMsg',
							type: 'get',
							data: {
								id: id,
								token: window.localStorage.getItem("token")
							},
							dataType: 'JSON'
						}).done(function (res) {
							if (res.err == 0) {
								weui.alert(res.msg, {
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
								weui.alert("删除失败", {
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
				}]
			});
		}
	},
	mounted: function mounted() {
		var _this = this;
		try {
			var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
			piwikTracker.trackPageView();
		} catch (err) {}
		$.ajax({
			url: '/api/qapi1/getMyMsg',
			type: 'get',
			data: {
				type: 1,
				page: _this.page,
				token: window.localStorage.getItem("token"),
				size: 50
			},
			dataType: 'JSON'
		}).done(function (res) {
			if (res.err == 2) {
				_this.condition = false;
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
			} else {
				_this.condition = true;
				_this.name = res.data;
			}
		}).fail(function () {}).always(function () {});
	}
});

/***/ })

});