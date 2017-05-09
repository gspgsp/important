webpackJsonp([37],{

/***/ 12:
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(46)(
  /* script */
  __webpack_require__(69),
  /* template */
  __webpack_require__(138),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "D:\\xampp\\htdocs\\workspace2\\www\\view\\default\\plasticzone\\src\\views\\login.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] login.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-ae4e2c08", Component.options)
  } else {
    hotAPI.reload("data-v-ae4e2c08", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),

/***/ 138:
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "buyWrap"
  }, [_c('header', {
    attrs: {
      "id": "bigCustomerHeader"
    }
  }, [_c('router-link', {
    staticClass: "back",
    attrs: {
      "to": {
        name: 'index'
      }
    }
  }), _vm._v("\n\t\t登录\n\t")], 1), _vm._v(" "), _c('div', {
    staticClass: "registerInput"
  }, [_c('div', {
    staticClass: "registerBox"
  }, [_c('strong', [_vm._v("手机号码:")]), _vm._v(" "), _c('input', {
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
  }, [_c('strong', [_vm._v("密码:")]), _vm._v(" "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.pwd),
      expression: "pwd"
    }],
    attrs: {
      "type": "password",
      "maxlength": "20",
      "placeholder": "请输入密码"
    },
    domProps: {
      "value": (_vm.pwd)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.pwd = $event.target.value
      }
    }
  })])]), _vm._v(" "), _c('div', {
    staticStyle: {
      "padding": "0 20px",
      "margin": "10px 0 0 0"
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
  }, [_vm._v("记住密码")])]), _vm._v(" "), _c('div', {
    staticClass: "registerBtn"
  }, [_c('input', {
    attrs: {
      "type": "button",
      "value": "登录"
    },
    on: {
      "click": _vm.login
    }
  })]), _vm._v(" "), _c('div', {
    staticClass: "loginLink",
    staticStyle: {
      "margin": "0 20px"
    }
  }, [_c('router-link', {
    staticStyle: {
      "float": "left"
    },
    attrs: {
      "to": {
        name: 'register'
      }
    }
  }, [_vm._v("注册")]), _vm._v(" "), _c('router-link', {
    staticStyle: {
      "float": "right"
    },
    attrs: {
      "to": {
        name: 'resetpwd'
      }
    }
  }, [_vm._v("忘记密码")])], 1)])
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-ae4e2c08", module.exports)
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

/***/ 69:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });


/* harmony default export */ __webpack_exports__["default"] = ({
	data: function data() {
		return {
			mobile: "",
			pwd: "",
			checked: false
		};
	},
	methods: {
		login: function login() {
			var _this = this;
			if (this.checked) {
				window.localStorage.setItem("username", this.mobile);
				window.localStorage.setItem("password", this.pwd);
			} else {
				window.localStorage.setItem("username", "");
				window.localStorage.setItem("password", "");
			}
			if (this.mobile && this.pwd) {
				$.ajax({
					url: '/api/qapi1_2/login',
					type: 'post',
					data: {
						username: _this.mobile,
						password: _this.pwd
					},
					dataType: 'JSON'
				}).done(function (res) {
					if (res.err == 0) {
						window.localStorage.setItem("token", res.dataToken);
						window.localStorage.setItem("userid", res.user_id);
						if (window.localStorage.getItem("inviteReg") == 1) {
							$.ajax({
								type: "post",
								url: "/api/score/addScore",
								data: {
									token: window.localStorage.getItem("token"),
									parent_mobile: window.localStorage.invite,
									type: '1'
								},
								dataType: 'JSON'
							}).done(function (res) {
								if (res.err == 0) {
									window.localStorage.setItem("inviteReg", "");
								} else {}
							}).fail(function () {});
						} else {
							if (window.localStorage.getItem("commReg") == 2) {
								$.ajax({
									type: "post",
									url: "/api/score/addScore",
									data: {
										token: window.localStorage.getItem("token"),
										type: '2'
									},
									dataType: 'JSON'
								}).done(function (res) {
									if (res.err == 0) {
										window.localStorage.setItem("commReg", "");
									} else {}
								}).fail(function () {});
							} else {
								$.ajax({
									type: "post",
									url: "/api/score/addScore",
									data: {
										token: window.localStorage.getItem("token"),
										type: '3'
									},
									dataType: 'JSON'
								}).done(function (res) {}).fail(function () {});
							}
						}
						_this.$router.push({
							name: 'index'
						});
					} else {
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
				weui.alert("手机号和密码不能为空", {
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
		try {
			var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
			piwikTracker.trackPageView();
		} catch (err) {}
		var lousername = window.localStorage.getItem("username") || "";
		var lopassword = window.localStorage.getItem("password") || "";
		this.mobile = lousername;
		this.pwd = lopassword;
		if (lousername !== "" && lopassword !== "") {
			this.checked = true;
		}
	}
});

/***/ })

});