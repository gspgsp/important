webpackJsonp([24],{

/***/ 137:
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "buyWrap"
  }, [_vm._m(0), _vm._v(" "), _c('div', {
    staticClass: "registerWrap"
  }, [_vm._m(1), _vm._v(" "), _c('div', {
    staticClass: "registerInput"
  }, [_c('div', {
    staticClass: "registerBox"
  }, [_vm._m(2), _vm._v(" "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.mobile),
      expression: "mobile"
    }],
    attrs: {
      "type": "tel",
      "maxlength": "11",
      "placeholder": "请输入您的手机号码"
    },
    domProps: {
      "value": (_vm.mobile)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.mobile = $event.target.value
      }
    }
  })]), _vm._v(" "), _c('div', {
    staticClass: "registerBox"
  }, [_vm._m(3), _vm._v(" "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.password),
      expression: "password"
    }],
    attrs: {
      "type": "password",
      "maxlength": "20",
      "placeholder": "请输入密码"
    },
    domProps: {
      "value": (_vm.password)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.password = $event.target.value
      }
    }
  })]), _vm._v(" "), _c('div', {
    staticClass: "registerBox"
  }, [_vm._m(4), _vm._v(" "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.code),
      expression: "code"
    }],
    attrs: {
      "maxlength": "6",
      "type": "tel",
      "placeholder": "请输入收到的验证码"
    },
    domProps: {
      "value": (_vm.code)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.code = $event.target.value
      }
    }
  }), _vm._v(" "), _c('button', {
    staticClass: "validCode",
    on: {
      "click": _vm.sendCode
    }
  }, [_vm._v(_vm._s(_vm.validCode))])])]), _vm._v(" "), _vm._m(5), _vm._v(" "), _c('div', {
    staticClass: "registerInput"
  }, [_c('div', {
    staticClass: "registerBox"
  }, [_vm._m(6), _vm._v(" "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.name),
      expression: "name"
    }],
    attrs: {
      "type": "text",
      "placeholder": "请输入您的姓名"
    },
    domProps: {
      "value": (_vm.name)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.name = $event.target.value
      }
    }
  })]), _vm._v(" "), _c('div', {
    staticClass: "registerBox"
  }, [_vm._m(7), _vm._v(" "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.c_name),
      expression: "c_name"
    }],
    attrs: {
      "type": "text",
      "placeholder": "请输入您的公司全称"
    },
    domProps: {
      "value": (_vm.c_name)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.c_name = $event.target.value
      }
    }
  })]), _vm._v(" "), _c('div', {
    staticClass: "registerBox"
  }, [_vm._m(8), _vm._v(" "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.c_type),
      expression: "c_type"
    }],
    attrs: {
      "name": "firm",
      "type": "radio",
      "value": "1"
    },
    domProps: {
      "checked": _vm._q(_vm.c_type, "1")
    },
    on: {
      "__c": function($event) {
        _vm.c_type = "1"
      }
    }
  }), _c('label', [_vm._v("塑料制品企业")]), _vm._v(" "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.c_type),
      expression: "c_type"
    }],
    attrs: {
      "name": "firm",
      "type": "radio",
      "value": "2"
    },
    domProps: {
      "checked": _vm._q(_vm.c_type, "2")
    },
    on: {
      "__c": function($event) {
        _vm.c_type = "2"
      }
    }
  }), _c('label', [_vm._v("原料供应商")]), _vm._v(" "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.c_type),
      expression: "c_type"
    }],
    attrs: {
      "name": "firm",
      "type": "radio",
      "value": "4"
    },
    domProps: {
      "checked": _vm._q(_vm.c_type, "4")
    },
    on: {
      "__c": function($event) {
        _vm.c_type = "4"
      }
    }
  }), _c('label', [_vm._v("物流服务商")])])])]), _vm._v(" "), _c('div', {
    staticStyle: {
      "padding": "40px 20px 0 20px",
      "font-size": "14px"
    }
  }, [_c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.checked),
      expression: "checked"
    }],
    attrs: {
      "type": "checkbox"
    },
    domProps: {
      "checked": Array.isArray(_vm.checked) ? _vm._i(_vm.checked, null) > -1 : (_vm.checked)
    },
    on: {
      "__c": function($event) {
        var $$a = _vm.checked,
          $$el = $event.target,
          $$c = $$el.checked ? (true) : (false);
        if (Array.isArray($$a)) {
          var $$v = null,
            $$i = _vm._i($$a, $$v);
          if ($$c) {
            $$i < 0 && (_vm.checked = $$a.concat($$v))
          } else {
            $$i > -1 && (_vm.checked = $$a.slice(0, $$i).concat($$a.slice($$i + 1)))
          }
        } else {
          _vm.checked = $$c
        }
      }
    }
  }), _vm._v(" "), _c('label', {
    staticStyle: {
      "color": "#999999"
    }
  }, [_vm._v("已阅读")]), _vm._v(" "), _c('router-link', {
    attrs: {
      "to": {
        name: 'protocol'
      }
    }
  }, [_vm._v("《塑料圈网用户服务协议》")])], 1), _vm._v(" "), _c('div', {
    staticClass: "registerBtn"
  }, [_c('input', {
    attrs: {
      "type": "button",
      "value": "提交注册信息"
    },
    on: {
      "click": _vm.reg
    }
  })])])
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
  }), _vm._v("\n\t\t注册\n\t")])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "registerTitle"
  }, [_c('i', {
    staticClass: "arrowLeft"
  }), _vm._v("帐号信息\n\t\t")])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('strong', [_c('span', [_vm._v("*")]), _vm._v("手机号码:")])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('strong', [_c('span', [_vm._v("*")]), _vm._v("设置密码:")])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('strong', [_c('span', [_vm._v("*")]), _vm._v("手机验证码:")])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "registerTitle"
  }, [_c('i', {
    staticClass: "arrowLeft"
  }), _vm._v("更多信息\n\t\t")])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('strong', [_c('span', [_vm._v("*")]), _vm._v("姓名:")])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('strong', [_c('span', [_vm._v("*")]), _vm._v("公司名称:")])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('strong', [_c('span', [_vm._v("*")]), _vm._v("企业类型:")])
}]}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-9e53c540", module.exports)
  }
}

/***/ }),

/***/ 34:
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(46)(
  /* script */
  __webpack_require__(91),
  /* template */
  __webpack_require__(137),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "D:\\xampp\\htdocs\\workspace2\\www\\view\\default\\plasticzone\\src\\views\\register.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] register.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-9e53c540", Component.options)
  } else {
    hotAPI.reload("data-v-9e53c540", Component.options)
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

/***/ 91:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });


/* harmony default export */ __webpack_exports__["default"] = ({
	data: function data() {
		return {
			mobile: "",
			password: "",
			code: "",
			name: "",
			c_name: "",
			c_type: 1,
			times: 60,
			validCode: "获取验证码",
			checked: true
		};
	},
	methods: {
		sendCode: function sendCode() {
			var _this = this;
			if (this.mobile) {
				$.ajax({
					url: '/api/qapi1/sendmsg',
					type: 'get',
					data: {
						mobile: _this.mobile,
						type: 0
					},
					dataType: 'JSON'
				}).then(function (res) {
					if (res.err == 0) {
						weui.alert(res.msg, {
							title: '塑料圈通讯录',
							buttons: [{
								label: '确定',
								type: 'parimary',
								onClick: function onClick() {}
							}]
						});

						var countStart = setInterval(function () {
							_this.validCode = _this.times-- + '秒后重发';
							if (_this.times < 0) {
								clearInterval(countStart);
								_this.validCode = "获取验证码";
							}
						}, 1000);
					} else if (res.err == 1) {
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
				weui.alert("请填写手机号", {
					title: '塑料圈通讯录',
					buttons: [{
						label: '确定',
						type: 'parimary',
						onClick: function onClick() {}
					}]
				});
			}
		},
		reg: function reg() {
			var _this = this;
			if (this.checked && this.password && this.name && this.c_name) {
				$.ajax({
					url: '/api/qapi1_2/register',
					type: 'post',
					data: {
						mobile: _this.mobile,
						password: _this.password,
						code: _this.code,
						name: _this.name,
						c_name: _this.c_name,
						chanel: 6,
						quan_type: 0,
						parent_mobile: window.localStorage.invite,
						c_type: _this.c_type
					},
					dataType: 'JSON'
				}).then(function (res) {
					if (res.err == 0) {
						if (window.localStorage.getItem("invite") != "undefined") {
							window.localStorage.setItem("inviteReg", 1);
						} else {
							window.localStorage.setItem("commReg", 2);
						}
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
				weui.alert("请把信息填写完整", {
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
	mounted: function mounted() {
		try {
			var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
			piwikTracker.trackPageView();
		} catch (err) {}
	}

});

/***/ })

});