webpackJsonp([18],{

/***/ 100:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });


/* harmony default export */ __webpack_exports__["default"] = ({
	data: function data() {
		return {
			input_time: "",
			contents: "",
			id: "",
			type: "",
			user_id: "",
			share: false,
			share3: false,
			share4: false
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
			this.share4 = false;
		},
		shareshow3: function shareshow3() {
			this.share = true;
			this.share4 = true;
		}
	},
	watch: {
		contents: function contents() {
			var _this = this;
			wx.onMenuShareTimeline({
				title: _this.contents,
				link: 'http://q.myplas.com/#/supplybuy/' + _this.id + '?invite=' + tel,
				imgUrl: 'http://statics.myplas.com/myapp/img/shareLogo.png',
				success: function success() {
					$.ajax({
						type: "post",
						url: "/api/qapi1_2/saveShareLog",
						data: {
							token: window.localStorage.getItem("token"),
							type: 1,
							id: _this.id
						},
						dataType: 'JSON'
					}).done(function (res) {}).fail(function () {});

					$.ajax({
						type: "post",
						url: "/api/score/addScore",
						data: {
							token: window.localStorage.getItem("token"),
							type: '4'
						},
						dataType: 'JSON'
					}).done(function (res) {}).fail(function () {});
				},
				cancel: function cancel() {}
			});
			wx.onMenuShareAppMessage({
				title: "我的塑料网-塑料圈通讯录",
				desc: _this.contents,
				link: 'http://q.myplas.com/#/supplybuy/' + _this.id + '?invite=' + tel,
				imgUrl: 'http://statics.myplas.com/myapp/img/shareLogo.png',
				type: '',
				dataUrl: '',
				success: function success() {
					$.ajax({
						type: "post",
						url: "/api/qapi1_2/saveShareLog",
						data: {
							token: window.localStorage.getItem("token"),
							type: 2,
							id: _this.id
						},
						dataType: 'JSON'
					}).done(function (res) {}).fail(function () {});

					$.ajax({
						type: "post",
						url: "/api/score/addScore",
						data: {
							token: window.localStorage.getItem("token"),
							type: '5'
						},
						dataType: 'JSON'
					}).done(function (res) {}).fail(function () {});
				},
				cancel: function cancel() {}
			});
		}
	},
	activated: function activated() {
		var _this = this;
		try {
			var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
			piwikTracker.trackPageView();
		} catch (err) {}
		$.ajax({
			type: "get",
			url: "/api/qapi1/shareMyPur",
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
				_this.input_time = res.data.input_time;
				_this.contents = res.data.contents || res.data.content;
				_this.id = _this.$route.params.id;
				_this.type = res.data.type;
				_this.user_id = res.data.user_id;
			}
		}, function () {});
	}
});

/***/ }),

/***/ 116:
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "buyWrap",
    staticStyle: {
      "padding": "45px 0 70px 0"
    }
  }, [_c('div', {
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
  }), _vm._v("\n\t\t\t我的供求\n\t\t\t"), _c('a', {
    staticClass: "headerMenu2",
    attrs: {
      "href": "javascript:;"
    },
    on: {
      "click": _vm.shareshow
    }
  })])]), _vm._v(" "), _c('div', {
    staticClass: "supplytitle",
    staticStyle: {
      "background": "#FFFFFF"
    }
  }, [_c('h3', [_vm._v(_vm._s(_vm.input_time))]), _vm._v(" "), _c('p', [_c('i', {
    staticClass: "myicon iconSupply"
  }), _vm._v("我的供求:" + _vm._s(_vm.contents))])]), _vm._v(" "), _c('div', {
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
  }), _vm._v(" "), _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.share4),
      expression: "share4"
    }],
    staticClass: "tip2"
  })])
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-218a294a", module.exports)
  }
}

/***/ }),

/***/ 43:
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(46)(
  /* script */
  __webpack_require__(100),
  /* template */
  __webpack_require__(116),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "D:\\xampp\\htdocs\\workspace2\\www\\view\\default\\plasticzone\\src\\views\\supplybuy.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] supplybuy.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-218a294a", Component.options)
  } else {
    hotAPI.reload("data-v-218a294a", Component.options)
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


/***/ })

});