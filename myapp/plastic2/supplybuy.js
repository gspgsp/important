webpackJsonp([0],{

/***/ 107:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_babel_runtime_helpers_defineProperty__ = __webpack_require__(110);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_babel_runtime_helpers_defineProperty___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_babel_runtime_helpers_defineProperty__);



/* harmony default export */ __webpack_exports__["default"] = ({
	data: function data() {
		var _ref;

		return _ref = {
			input_time: "",
			contents: "",
			id: "",
			type: "",
			user_id: ""
		}, __WEBPACK_IMPORTED_MODULE_0_babel_runtime_helpers_defineProperty___default()(_ref, "type", ""), __WEBPACK_IMPORTED_MODULE_0_babel_runtime_helpers_defineProperty___default()(_ref, "name", "供给"), __WEBPACK_IMPORTED_MODULE_0_babel_runtime_helpers_defineProperty___default()(_ref, "share", false), __WEBPACK_IMPORTED_MODULE_0_babel_runtime_helpers_defineProperty___default()(_ref, "share3", false), __WEBPACK_IMPORTED_MODULE_0_babel_runtime_helpers_defineProperty___default()(_ref, "share4", false), __WEBPACK_IMPORTED_MODULE_0_babel_runtime_helpers_defineProperty___default()(_ref, "typeName", "供给消息"), _ref;
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
			$.ajax({
				type: "post",
				url: "/mobi/wxShare/getSignPackage",
				data: {
					targetUrl: window.location.href
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
						title: _this.typeName + ":" + _this.contents,
						link: 'http://q.myplas.com/#/supplybuy/' + _this.id + '?invite=' + tel,
						imgUrl: 'http://statics.myplas.com/myapp/img/shareLogo.png',
						success: function success() {
							$.ajax({
								type: "post",
								url: version + "/wechat/saveShareLog",
								data: {
									token: window.localStorage.getItem("token"),
									type: 1,
									id: _this.id
								},
								headers: {
									'X-UA': window.localStorage.getItem("XUA")
								},
								dataType: 'JSON'
							}).done(function (res) {}).fail(function () {});
						},
						cancel: function cancel() {}
					});
					wx.onMenuShareAppMessage({
						title: _this.typeName + ":" + _this.contents,
						desc: "我的塑料网-塑料圈通讯录",
						link: 'http://q.myplas.com/#/supplybuy/' + _this.id + '?invite=' + tel,
						imgUrl: 'http://statics.myplas.com/myapp/img/shareLogo.png',
						type: '',
						dataUrl: '',
						success: function success() {
							$.ajax({
								type: "post",
								url: version + "/wechat/saveShareLog",
								data: {
									token: window.localStorage.getItem("token"),
									type: 2,
									id: _this.id
								},
								headers: {
									'X-UA': window.localStorage.getItem("XUA")
								},
								dataType: 'JSON'
							}).done(function (res) {}).fail(function () {});
						},
						cancel: function cancel() {}
					});
				});
			}, function () {});
		}
	},
	activated: function activated() {
		var _this = this;
		try {
			var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
			piwikTracker.trackPageView();
		} catch (err) {}
		$.ajax({
			type: "post",
			url: version + "/wechat/shareMyPur",
			data: {
				id: _this.$route.params.id,
				token: window.localStorage.getItem("token")
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
				_this.type = res.data.type;
				if (_this.type == 1) {
					_this.name = "求购";
					_this.typeName = "求购消息";
				} else {
					_this.name = "供给";
					_this.typeName = "供给消息";
				}
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

/***/ 109:
/***/ (function(module, exports, __webpack_require__) {

module.exports = { "default": __webpack_require__(111), __esModule: true };

/***/ }),

/***/ 110:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


exports.__esModule = true;

var _defineProperty = __webpack_require__(109);

var _defineProperty2 = _interopRequireDefault(_defineProperty);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = function (obj, key, value) {
  if (key in obj) {
    (0, _defineProperty2.default)(obj, key, {
      value: value,
      enumerable: true,
      configurable: true,
      writable: true
    });
  } else {
    obj[key] = value;
  }

  return obj;
};

/***/ }),

/***/ 111:
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(121);
var $Object = __webpack_require__(54).Object;
module.exports = function defineProperty(it, key, desc){
  return $Object.defineProperty(it, key, desc);
};

/***/ }),

/***/ 112:
/***/ (function(module, exports) {

module.exports = function(it){
  if(typeof it != 'function')throw TypeError(it + ' is not a function!');
  return it;
};

/***/ }),

/***/ 113:
/***/ (function(module, exports, __webpack_require__) {

var isObject = __webpack_require__(61);
module.exports = function(it){
  if(!isObject(it))throw TypeError(it + ' is not an object!');
  return it;
};

/***/ }),

/***/ 114:
/***/ (function(module, exports, __webpack_require__) {

// optional / simple context binding
var aFunction = __webpack_require__(112);
module.exports = function(fn, that, length){
  aFunction(fn);
  if(that === undefined)return fn;
  switch(length){
    case 1: return function(a){
      return fn.call(that, a);
    };
    case 2: return function(a, b){
      return fn.call(that, a, b);
    };
    case 3: return function(a, b, c){
      return fn.call(that, a, b, c);
    };
  }
  return function(/* ...args */){
    return fn.apply(that, arguments);
  };
};

/***/ }),

/***/ 115:
/***/ (function(module, exports, __webpack_require__) {

var isObject = __webpack_require__(61)
  , document = __webpack_require__(63).document
  // in old IE typeof document.createElement is 'object'
  , is = isObject(document) && isObject(document.createElement);
module.exports = function(it){
  return is ? document.createElement(it) : {};
};

/***/ }),

/***/ 116:
/***/ (function(module, exports, __webpack_require__) {

var global    = __webpack_require__(63)
  , core      = __webpack_require__(54)
  , ctx       = __webpack_require__(114)
  , hide      = __webpack_require__(117)
  , PROTOTYPE = 'prototype';

var $export = function(type, name, source){
  var IS_FORCED = type & $export.F
    , IS_GLOBAL = type & $export.G
    , IS_STATIC = type & $export.S
    , IS_PROTO  = type & $export.P
    , IS_BIND   = type & $export.B
    , IS_WRAP   = type & $export.W
    , exports   = IS_GLOBAL ? core : core[name] || (core[name] = {})
    , expProto  = exports[PROTOTYPE]
    , target    = IS_GLOBAL ? global : IS_STATIC ? global[name] : (global[name] || {})[PROTOTYPE]
    , key, own, out;
  if(IS_GLOBAL)source = name;
  for(key in source){
    // contains in native
    own = !IS_FORCED && target && target[key] !== undefined;
    if(own && key in exports)continue;
    // export native or passed
    out = own ? target[key] : source[key];
    // prevent global pollution for namespaces
    exports[key] = IS_GLOBAL && typeof target[key] != 'function' ? source[key]
    // bind timers to global for call from export context
    : IS_BIND && own ? ctx(out, global)
    // wrap global constructors for prevent change them in library
    : IS_WRAP && target[key] == out ? (function(C){
      var F = function(a, b, c){
        if(this instanceof C){
          switch(arguments.length){
            case 0: return new C;
            case 1: return new C(a);
            case 2: return new C(a, b);
          } return new C(a, b, c);
        } return C.apply(this, arguments);
      };
      F[PROTOTYPE] = C[PROTOTYPE];
      return F;
    // make static versions for prototype methods
    })(out) : IS_PROTO && typeof out == 'function' ? ctx(Function.call, out) : out;
    // export proto methods to core.%CONSTRUCTOR%.methods.%NAME%
    if(IS_PROTO){
      (exports.virtual || (exports.virtual = {}))[key] = out;
      // export proto methods to core.%CONSTRUCTOR%.prototype.%NAME%
      if(type & $export.R && expProto && !expProto[key])hide(expProto, key, out);
    }
  }
};
// type bitmap
$export.F = 1;   // forced
$export.G = 2;   // global
$export.S = 4;   // static
$export.P = 8;   // proto
$export.B = 16;  // bind
$export.W = 32;  // wrap
$export.U = 64;  // safe
$export.R = 128; // real proto method for `library` 
module.exports = $export;

/***/ }),

/***/ 117:
/***/ (function(module, exports, __webpack_require__) {

var dP         = __webpack_require__(64)
  , createDesc = __webpack_require__(119);
module.exports = __webpack_require__(58) ? function(object, key, value){
  return dP.f(object, key, createDesc(1, value));
} : function(object, key, value){
  object[key] = value;
  return object;
};

/***/ }),

/***/ 118:
/***/ (function(module, exports, __webpack_require__) {

module.exports = !__webpack_require__(58) && !__webpack_require__(62)(function(){
  return Object.defineProperty(__webpack_require__(115)('div'), 'a', {get: function(){ return 7; }}).a != 7;
});

/***/ }),

/***/ 119:
/***/ (function(module, exports) {

module.exports = function(bitmap, value){
  return {
    enumerable  : !(bitmap & 1),
    configurable: !(bitmap & 2),
    writable    : !(bitmap & 4),
    value       : value
  };
};

/***/ }),

/***/ 120:
/***/ (function(module, exports, __webpack_require__) {

// 7.1.1 ToPrimitive(input [, PreferredType])
var isObject = __webpack_require__(61);
// instead of the ES6 spec version, we didn't implement @@toPrimitive case
// and the second argument - flag - preferred type is a string
module.exports = function(it, S){
  if(!isObject(it))return it;
  var fn, val;
  if(S && typeof (fn = it.toString) == 'function' && !isObject(val = fn.call(it)))return val;
  if(typeof (fn = it.valueOf) == 'function' && !isObject(val = fn.call(it)))return val;
  if(!S && typeof (fn = it.toString) == 'function' && !isObject(val = fn.call(it)))return val;
  throw TypeError("Can't convert object to primitive value");
};

/***/ }),

/***/ 121:
/***/ (function(module, exports, __webpack_require__) {

var $export = __webpack_require__(116);
// 19.1.2.4 / 15.2.3.6 Object.defineProperty(O, P, Attributes)
$export($export.S + $export.F * !__webpack_require__(58), 'Object', {defineProperty: __webpack_require__(64).f});

/***/ }),

/***/ 136:
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
  }), _vm._v("\n\t\t\t我的" + _vm._s(_vm.name) + "\n\t\t\t"), _c('a', {
    staticClass: "detailShare",
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
  }), _vm._v("我的" + _vm._s(_vm.name) + ":" + _vm._s(_vm.contents))])]), _vm._v(" "), _c('div', {
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

/***/ 44:
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(47)(
  /* script */
  __webpack_require__(107),
  /* template */
  __webpack_require__(136),
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

/***/ 47:
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

/***/ 54:
/***/ (function(module, exports) {

var core = module.exports = {version: '2.4.0'};
if(typeof __e == 'number')__e = core; // eslint-disable-line no-undef

/***/ }),

/***/ 58:
/***/ (function(module, exports, __webpack_require__) {

// Thank's IE8 for his funny defineProperty
module.exports = !__webpack_require__(62)(function(){
  return Object.defineProperty({}, 'a', {get: function(){ return 7; }}).a != 7;
});

/***/ }),

/***/ 61:
/***/ (function(module, exports) {

module.exports = function(it){
  return typeof it === 'object' ? it !== null : typeof it === 'function';
};

/***/ }),

/***/ 62:
/***/ (function(module, exports) {

module.exports = function(exec){
  try {
    return !!exec();
  } catch(e){
    return true;
  }
};

/***/ }),

/***/ 63:
/***/ (function(module, exports) {

// https://github.com/zloirock/core-js/issues/86#issuecomment-115759028
var global = module.exports = typeof window != 'undefined' && window.Math == Math
  ? window : typeof self != 'undefined' && self.Math == Math ? self : Function('return this')();
if(typeof __g == 'number')__g = global; // eslint-disable-line no-undef

/***/ }),

/***/ 64:
/***/ (function(module, exports, __webpack_require__) {

var anObject       = __webpack_require__(113)
  , IE8_DOM_DEFINE = __webpack_require__(118)
  , toPrimitive    = __webpack_require__(120)
  , dP             = Object.defineProperty;

exports.f = __webpack_require__(58) ? Object.defineProperty : function defineProperty(O, P, Attributes){
  anObject(O);
  P = toPrimitive(P, true);
  anObject(Attributes);
  if(IE8_DOM_DEFINE)try {
    return dP(O, P, Attributes);
  } catch(e){ /* empty */ }
  if('get' in Attributes || 'set' in Attributes)throw TypeError('Accessors not supported!');
  if('value' in Attributes)O[P] = Attributes.value;
  return O;
};

/***/ })

});