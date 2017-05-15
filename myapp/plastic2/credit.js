webpackJsonp([41],{

/***/ 134:
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "buyWrap"
  }, [_c('header', {
    attrs: {
      "id": "bigCustomerHeader"
    }
  }, [_c('a', {
    staticClass: "back",
    attrs: {
      "href": "javascript:window.history.back();"
    }
  }), _vm._v("\n    \t我的信用信息\n    \t"), _c('a', {
    staticClass: "detailShare",
    attrs: {
      "href": "javascript:;"
    },
    on: {
      "click": _vm.shareshow
    }
  })]), _vm._v(" "), _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.creditshow),
      expression: "creditshow"
    }]
  }, [_c('div', {
    staticClass: "creditwrap"
  }, [_c('div', {
    staticClass: "notice"
  }), _vm._v(" "), _c('h3', {
    staticStyle: {
      "font-size": "18px",
      "color": "#333333",
      "text-align": "center",
      "margin": "10px 0"
    }
  }, [_vm._v("热烈祝贺" + _vm._s(_vm.c_name))]), _vm._v(" "), _c('h3', {
    staticStyle: {
      "font-size": "13px",
      "color": "#333333",
      "text-align": "center"
    }
  }, [_vm._v("\n    \t\t获得信用"), _c('span', {
    staticStyle: {
      "color": "#ff5000"
    }
  }, [_vm._v(_vm._s(_vm.credit_level))]), _vm._v("\n    \t\t级客户称号/"), _c('span', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.is_credit === '2'),
      expression: "is_credit==='2'"
    }]
  }, [_vm._v("预计")]), _vm._v("获得"), _c('span', {
    staticStyle: {
      "color": "#ff5000"
    }
  }, [_vm._v(_vm._s(_vm.credit_limit))]), _vm._v("万授信额度")]), _vm._v(" "), _c('p', {
    staticStyle: {
      "color": "#666666",
      "font-size": "12px"
    }
  }, [_vm._v("\n    \t\t经“我的塑料网”塑料电商交易平台信用认证，贵司企业信用良好，为" + _vm._s(_vm.credit_level) + "级，"), _c('span', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.is_credit === '2'),
      expression: "is_credit==='2'"
    }]
  }, [_vm._v("预计")]), _vm._v("授信额度：" + _vm._s(_vm.credit_limit) + "万元人民币，特发此证！\n    \t")])]), _vm._v(" "), _c('div', {
    staticStyle: {
      "text-align": "center",
      "margin": "30px 0"
    }
  }, [_c('div', {
    staticClass: "creditbg"
  }, [_c('div', {
    staticClass: "creditname"
  }, [_vm._v(_vm._s(_vm.c_name))]), _vm._v(" "), _c('div', {
    staticClass: "credittxt"
  }, [_vm._v("经我司评定，确认贵单位为二○一七年度信用" + _vm._s(_vm.credit_level) + "级客户，授信额度" + _vm._s(_vm.credit_limit) + "万人民币，有效期一年。")])])]), _vm._v(" "), _c('div', {
    staticClass: "creditbtn"
  }, [_c('span', {
    staticClass: "orange",
    on: {
      "click": _vm.shareshow
    }
  }, [_vm._v("分享给别人")]), _vm._v("   \n\t\t"), _c('span', {
    staticClass: "green",
    on: {
      "click": _vm.toCreditintro
    }
  }, [_vm._v("?授信说明")])])]), _vm._v(" "), _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (!_vm.creditshow),
      expression: "!creditshow"
    }],
    staticStyle: {
      "text-align": "center",
      "padding": "20px"
    }
  }, [_vm._v("\n    \t" + _vm._s(_vm.msg) + "\n   ")]), _vm._v(" "), _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.share),
      expression: "share"
    }],
    staticClass: "sharelayer",
    on: {
      "click": _vm.sharehide
    }
  }), _vm._v(" "), _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.share3),
      expression: "share3"
    }],
    staticClass: "tip"
  })])
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-8926b814", module.exports)
  }
}

/***/ }),

/***/ 3:
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(46)(
  /* script */
  __webpack_require__(60),
  /* template */
  __webpack_require__(134),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "D:\\xampp\\htdocs\\workspace2\\www\\view\\default\\plasticzone\\src\\views\\credit.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] credit.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-8926b814", Component.options)
  } else {
    hotAPI.reload("data-v-8926b814", Component.options)
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

/***/ 60:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });


/* harmony default export */ __webpack_exports__["default"] = ({
	data: function data() {
		return {
			c_name: "",
			credit_level: "",
			credit_limit: "",
			user_id: "",
			share: false,
			share3: false,
			is_credit: "",
			creditshow: true,
			msg: ""
		};
	},
	methods: {
		shareshow: function shareshow() {
			this.share = true;
			this.share3 = true;
		},
		sharehide: function sharehide() {
			this.share = false;
			this.share3 = false;
		},
		toCreditintro: function toCreditintro() {
			this.$router.push({ name: "creditintro" });
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
			url: "/api/qapi1_1/creditCertificate",
			data: {
				token: window.localStorage.getItem("token")
			},
			dataType: 'JSON'
		}).done(function (res) {
			if (res.err == 0) {
				_this.creditshow = true;
				_this.c_name = res.data.c_name;
				_this.credit_level = res.data.credit_level;
				_this.credit_limit = res.data.credit_limit / 10000;
				_this.user_id = res.data.user_id;
				_this.is_credit = res.data.is_credit;
				$.ajax({
					type: "post",
					url: "/mobi/wxShare/getSignPackage",
					data: {
						targetUrl: window.location.href,
						random: Math.random()
					},
					dataType: 'JSON'
				}).then(function (res) {
					wx.config({
						debug: false,
						appId: res.signPackage.appId,
						timestamp: res.signPackage.timestamp,
						nonceStr: res.signPackage.noncestr,
						signature: res.signPackage.signature,
						jsApiList: ['showOptionMenu', 'onMenuShareTimeline', 'onMenuShareAppMessage']
					});
					wx.ready(function () {
						wx.onMenuShareTimeline({
							title: "热烈祝贺" + _this.c_name + "获得企业信用等级证书" + _this.credit_limit + "万",
							link: 'http://q.myplas.com/#/credit2/' + _this.user_id,
							imgUrl: 'http://statics.myplas.com/myapp/img/shareLogo.png',
							success: function success() {},
							cancel: function cancel() {}
						});
						wx.onMenuShareAppMessage({
							title: "热烈祝贺" + _this.c_name + "获得企业信用等级证书" + _this.credit_limit + "万",
							desc: "我的塑料网-塑料圈通讯录",
							link: 'http://q.myplas.com/#/credit2/' + _this.user_id,
							imgUrl: 'http://statics.myplas.com/myapp/img/shareLogo.png',
							type: '',
							dataUrl: '',
							success: function success() {},
							cancel: function cancel() {}
						});
					});
				}, function () {});
			} else if (res.err == 2) {
				_this.creditshow = false;
				_this.msg = res.msg;
			}
		}).fail(function () {}).always(function () {});
	}
});

/***/ })

});