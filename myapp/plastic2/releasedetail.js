webpackJsonp([21],{

/***/ 145:
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "buyWrap",
    staticStyle: {
      "padding": "0px 0 70px 0"
    }
  }, [_vm._m(0), _vm._v(" "), _c('div', {
    staticClass: "releasedetail"
  }, [_c('span', {
    staticStyle: {
      "width": "auto",
      "padding": "0 5px",
      "height": "25px",
      "color": "#ea8010",
      "border-radius": "3px",
      "position": "absolute",
      "right": "10px",
      "top": "10px",
      "text-align": "center",
      "font-size": "13px",
      "line-height": "25px",
      "border": "1px solid #ea8010"
    },
    on: {
      "click": _vm.pay
    }
  }, [_vm._v("\n" + _vm._s(_vm.status) + "\n")]), _vm._v(" "), _c('div', {
    staticClass: "myreleaseInfo"
  }, [_c('div', {
    staticStyle: {
      "width": "30px",
      "height": "30px",
      "float": "left",
      "position": "relative"
    }
  }, [_c('div', {
    staticClass: "avator"
  }, [_c('img', {
    attrs: {
      "src": _vm.thumb
    }
  })]), _vm._v(" "), _c('i', {
    staticClass: "iconV",
    class: {
      'v1': _vm.is_pass == 1, 'v2': _vm.is_pass == 0
    }
  })]), _vm._v(" "), _c('div', {
    staticClass: "myreleasetxt"
  }, [_c('p', {
    staticStyle: {
      "height": "30px",
      "line-height": "15px"
    }
  }, [_vm._v("\n\t\t\t\t" + _vm._s(_vm.c_name) + " " + _vm._s(_vm.name)), _c('br'), _vm._v(" 粉丝：" + _vm._s(_vm.fans) + " 等级：" + _vm._s(_vm.level) + "\n\t\t\t")])])]), _vm._v(" "), _c('div', {
    staticClass: "myreleasetxt2"
  }, [_c('p', [(_vm.type == 2) ? _c('strong', {
    staticStyle: {
      "color": "#63769d"
    }
  }, [_c('i', {
    staticClass: "iconSale"
  }), _vm._v("供给\n")]) : _c('strong', {
    staticStyle: {
      "color": "#ea8010"
    }
  }, [_c('i', {
    staticClass: "iconBuy"
  }), _vm._v("求购\n")]), _vm._v(" "), _c('strong', [_vm._v(_vm._s(_vm.content))])]), _vm._v(" "), _c('p', {
    staticStyle: {
      "color": "#999999"
    }
  }, [_vm._v("\n\t\t\t" + _vm._s(_vm.time) + "\n\t\t\t"), _c('span', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.mine),
      expression: "mine"
    }],
    staticStyle: {
      "margin": "0 0 0 10px",
      "float": "right",
      "color": "#999999"
    }
  }, [_c('i', {
    staticClass: "releasereplyicon"
  }), _vm._v("回复"), _c('i', {
    staticStyle: {
      "color": "#63769d",
      "font-style": "normal"
    }
  }, [_vm._v("(" + _vm._s(_vm.saysCount) + ")")])]), _vm._v(" "), _c('span', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.mine),
      expression: "mine"
    }],
    staticStyle: {
      "color": "#999999",
      "float": "right"
    }
  }, [_c('i', {
    staticClass: "releasesaleicon"
  }), _vm._v("出价"), _c('i', {
    staticStyle: {
      "color": "#63769d",
      "font-style": "normal"
    }
  }, [_vm._v("(" + _vm._s(_vm.deliverPriceCount) + ")")])])])])]), _vm._v(" "), _c('div', {
    staticClass: "bidreply"
  }, [_c('div', {
    staticStyle: {
      "text-align": "center",
      "padding": "5px 0 10px 0"
    }
  }, [_c('div', {
    staticStyle: {
      "width": "100%",
      "text-align": "center"
    }
  }, [_c('div', {
    staticClass: "releasebschoose"
  }, [_c('span', {
    class: {
      releasebson: _vm.show1
    },
    on: {
      "click": _vm.spanshow1
    }
  }, [_vm._v("出价消息")]), _vm._v(" "), _c('span', {
    class: {
      releasebson: _vm.show2
    },
    on: {
      "click": _vm.spanshow2
    }
  }, [_vm._v("回复消息")])])])]), _vm._v(" "), _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.show1),
      expression: "show1"
    }]
  }, [_c('table', {
    staticClass: "releasetb",
    attrs: {
      "cellpadding": "0",
      "cellspacing": "0"
    }
  }, [_vm._m(1), _vm._v(" "), _vm._l((_vm.price), function(p) {
    return _c('tr', [_c('td', [_c('div', {
      staticClass: "myreleaseInfo",
      staticStyle: {
        "display": "inline-block",
        "width": "150px",
        "margin": "5px 0 0 0"
      }
    }, [_c('div', {
      staticStyle: {
        "width": "30px",
        "height": "30px",
        "float": "left",
        "position": "relative"
      }
    }, [_c('div', {
      staticClass: "avator"
    }, [_c('img', {
      attrs: {
        "src": p.info.thumb
      }
    })]), _vm._v(" "), _c('i', {
      staticClass: "iconV",
      class: {
        'v1': p.info.is_pass == 1, 'v2': p.info.is_pass == 0
      }
    })]), _vm._v(" "), _c('div', {
      staticClass: "myreleasetxt"
    }, [_c('p', {
      staticStyle: {
        "width": "110px",
        "overflow": "hidden",
        "white-space": "nowrap",
        "text-overflow": "ellipsis",
        "line-height": "15px"
      }
    }, [_vm._v("\n\t\t\t\t\t\t\t\t" + _vm._s(p.info.c_name) + " " + _vm._s(p.info.name) + "\n\t\t\t\t\t\t\t")]), _vm._v(" "), _c('p', {
      staticStyle: {
        "text-align": "left",
        "color": "#999999"
      }
    }, [_vm._v("\n\t\t\t\t\t\t\t\t" + _vm._s(p.input_time) + "\n\t\t\t\t\t\t\t")])])])]), _vm._v(" "), _c('td', {
      staticClass: "orange"
    }, [_vm._v(_vm._s(p.price))]), _vm._v(" "), _c('td', [_c('a', {
      directives: [{
        name: "show",
        rawName: "v-show",
        value: (p.info.mobile.indexOf('*') == '-1' ? true : false),
        expression: "p.info.mobile.indexOf('*')=='-1'? true : false"
      }],
      staticClass: "telephone2",
      staticStyle: {
        "margin": "5px 0 0 0"
      },
      attrs: {
        "href": 'tel:' + p.info.mobile
      }
    })])])
  })], 2), _vm._v(" "), _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.mine),
      expression: "mine"
    }],
    staticClass: "replymsg"
  }, [_c('div', {
    staticStyle: {
      "width": "auto",
      "margin-right": "60px"
    }
  }, [_c('form', [_c('i', {
    staticClass: "writeicon",
    on: {
      "click": _vm.deliver
    }
  }), _vm._v(" "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.deliverprice),
      expression: "deliverprice"
    }],
    attrs: {
      "type": "number",
      "placeholder": "期待你的出价"
    },
    domProps: {
      "value": (_vm.deliverprice)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.deliverprice = $event.target.value
      },
      "blur": function($event) {
        _vm.$forceUpdate()
      }
    }
  })])]), _vm._v(" "), _c('span', {
    staticClass: "releasedetailbtn",
    on: {
      "click": _vm.deliver
    }
  }, [_vm._v("出价")])])]), _vm._v(" "), _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.show2),
      expression: "show2"
    }]
  }, [_c('ul', {
    staticClass: "replyul"
  }, _vm._l((_vm.reply), function(r) {
    return _c('li', [_c('div', {
      staticClass: "myreleaseInfo"
    }, [_c('div', {
      staticStyle: {
        "width": "30px",
        "height": "30px",
        "float": "left",
        "position": "relative"
      }
    }, [_c('div', {
      staticClass: "avator"
    }, [_c('img', {
      attrs: {
        "src": r.info.thumb
      }
    })]), _vm._v(" "), _c('i', {
      staticClass: "iconV",
      class: {
        'v1': r.info.is_pass == 1, 'v2': r.info.is_pass == 0
      }
    })]), _vm._v(" "), _c('div', {
      staticClass: "myreleasetxt"
    }, [_c('p', {
      staticStyle: {
        "height": "30px",
        "line-height": "15px"
      }
    }, [_vm._v("\n\t\t\t\t\t\t\t" + _vm._s(r.info.c_name) + " " + _vm._s(r.info.name)), _c('br'), _vm._v(" " + _vm._s(r.input_time) + "\n\t\t\t\t\t\t")])])]), _vm._v(" "), _c('div', {
      staticClass: "myreleasetxt2"
    }, [_c('p', [_c('strong', {
      staticStyle: {
        "margin": "0 0 0 40px"
      }
    }, [_vm._v(_vm._s(r.content))])])])])
  })), _vm._v(" "), _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.mine),
      expression: "mine"
    }],
    staticClass: "replymsg"
  }, [_c('div', {
    staticStyle: {
      "width": "auto",
      "margin-right": "60px"
    }
  }, [_c('form', [_c('i', {
    staticClass: "writeicon",
    on: {
      "click": _vm.replyMsg
    }
  }), _vm._v(" "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.msg),
      expression: "msg"
    }],
    attrs: {
      "type": "text",
      "placeholder": "期待你的回复"
    },
    domProps: {
      "value": (_vm.msg)
    },
    on: {
      "input": function($event) {
        if ($event.target.composing) { return; }
        _vm.msg = $event.target.value
      }
    }
  })])]), _vm._v(" "), _c('span', {
    staticClass: "releasedetailbtn",
    on: {
      "click": _vm.replyMsg
    }
  }, [_vm._v("回复")])])])])])
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
  }), _vm._v("\n\t详情\n")])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('tr', [_c('th', [_vm._v("出价人")]), _vm._v(" "), _c('th', [_vm._v("出价")]), _vm._v(" "), _c('th', [_vm._v("操作")])])
}]}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-e80881aa", module.exports)
  }
}

/***/ }),

/***/ 39:
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(46)(
  /* script */
  __webpack_require__(96),
  /* template */
  __webpack_require__(145),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "D:\\xampp\\htdocs\\workspace2\\www\\view\\default\\plasticzone\\src\\views\\releasedetail.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] releasedetail.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-e80881aa", Component.options)
  } else {
    hotAPI.reload("data-v-e80881aa", Component.options)
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

/***/ 96:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });


/* harmony default export */ __webpack_exports__["default"] = ({
	data: function data() {
		return {
			content: "",
			id: "",
			saysCount: "",
			name: "",
			c_name: "",
			thumb: "",
			is_pass: "",
			deliverPriceCount: "",
			type: "",
			show1: true,
			show2: false,
			fans: "",
			level: "",
			price: [],
			deliverprice: "",
			reply: [],
			msg: "",
			deliverbtn: false,
			replybtn: false,
			right: 0,
			right2: 0,
			mine: true,
			userinfoid: ""
		};
	},
	methods: {
		spanshow1: function spanshow1() {
			this.show1 = true;
			this.show2 = false;
		},
		spanshow2: function spanshow2() {
			this.show1 = false;
			this.show2 = true;
		},
		replyMsg: function replyMsg() {
			var _this = this;
			$.ajax({
				url: '/api/qapi1/saveMsg',
				type: 'get',
				data: {
					pur_id: _this.id,
					content: _this.msg,
					send_id: _this.user_id,
					token: window.localStorage.getItem("token")
				},
				dataType: 'JSON'
			}).then(function (res) {
				if (res.err == 0) {
					$.ajax({
						url: '/api/qapi1/getReleaseMsgDetailReply',
						type: 'get',
						data: {
							id: _this.$route.query.id,
							user_id: _this.$route.query.userid,
							token: window.localStorage.getItem("token"),
							page: 1,
							size: 10
						},
						dataType: 'JSON'
					}).then(function (res) {
						console.log(res);
						if (res.err == 0) {
							_this.reply = res.data.data;
							_this.msg = "";
						}
					}, function () {});
				} else {
					mui.alert("", res.msg, function () {
						window.location.reload();
					});
				}
			}, function () {});
		},
		deliver: function deliver() {
			var _this = this;
			$.ajax({
				url: '/api/qapi1/deliverPrice',
				type: 'get',
				data: {
					id: _this.$route.query.id,
					rev_id: _this.$route.query.userid,
					token: window.localStorage.getItem("token"),
					type: _this.type,
					price: _this.deliverprice
				},
				dataType: 'JSON'
			}).then(function (res) {
				console.log(res);
				if (res.err == 0) {
					$.ajax({
						url: '/api/qapi1/getDeliverPrice',
						type: 'get',
						data: {
							id: _this.$route.query.id,
							rev_id: _this.$route.query.userid,
							token: window.localStorage.getItem("token"),
							page: 1,
							size: 10
						},
						dataType: 'JSON'
					}).then(function (res) {
						console.log(res);
						if (res.err == 0) {
							_this.price = res.data.data;
							_this.deliverprice = "";
						}
					}, function () {});
				} else if (res.err == 1) {
					mui.alert("", res.msg, function () {
						_this.$router.push({
							name: 'login'
						});
					});
				} else {
					mui.alert("", res.msg, function () {
						window.location.reload();
					});
				}
			}, function () {});
		},
		pay: function pay() {
			var _this = this;
			$.ajax({
				url: '/api/qapi1/focusOrCancel',
				type: 'get',
				data: {
					focused_id: _this.user_id,
					token: window.localStorage.getItem("token")
				},
				dataType: 'JSON'
			}).then(function (res) {
				console.log(">>>", res.msg);
				window.location.reload();
			}, function () {});
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
			url: '/api/qapi1/getReleaseMsgDetail',
			type: 'get',
			data: {
				id: _this.$route.query.id,
				user_id: _this.$route.query.userid,
				token: window.localStorage.getItem("token")
			},
			dataType: 'JSON'
		}).then(function (res) {
			console.log(res);
			if (res.err == 0) {
				_this.id = res.data.id;
				_this.user_id = res.data.user_id;
				_this.content = res.data.contents;
				_this.saysCount = res.data.saysCount;
				_this.time = res.data.input_time;
				_this.type = res.data.type;
				_this.name = res.data.info.name;
				_this.fans = res.data.info.fans;
				_this.level = res.data.info.member_level;
				_this.c_name = res.data.info.c_name;
				_this.status = res.data.info.status;
				_this.is_pass = res.data.info.is_pass;
				_this.thumb = res.data.info.thumb;
				_this.deliverPriceCount = res.data.deliverPriceCount;
				_this.userinfoid = res.data.info.user_id;
				if (_this.$route.query.userid == window.localStorage.getItem("userid")) {
					_this.mine = false;
				} else {
					_this.mine = true;
				}
				if (_this.$route.query.tab == 1) {
					_this.show1 = true;
					_this.show2 = false;
				} else {
					_this.show1 = false;
					_this.show2 = true;
				}
			} else if (res.err == 1) {
				mui.alert("", res.msg, function () {
					_this.$router.push({
						name: 'login'
					});
				});
			}
		}, function () {});

		$.ajax({
			url: '/api/qapi1/getDeliverPrice',
			type: 'get',
			data: {
				id: _this.$route.query.id,
				rev_id: _this.$route.query.userid,
				token: window.localStorage.getItem("token"),
				page: 1,
				size: 10
			},
			dataType: 'JSON'
		}).then(function (res) {
			console.log(res);
			if (res.err == 0) {
				_this.price = res.data.data;
			} else if (res.err == 1) {
				mui.alert("", res.msg, function () {
					_this.$router.push({
						name: 'login'
					});
				});
			} else if (res.err == 2) {
				_this.price = [];
			}
		}, function () {});

		$.ajax({
			url: '/api/qapi1/getReleaseMsgDetailReply',
			type: 'get',
			data: {
				id: _this.$route.query.id,
				user_id: _this.$route.query.userid,
				token: window.localStorage.getItem("token"),
				page: 1,
				size: 10
			},
			dataType: 'JSON'
		}).then(function (res) {
			console.log(res);
			if (res.err == 0) {
				_this.reply = res.data.data;
			} else if (res.err == 1) {
				mui.alert("", res.msg, function () {
					_this.$router.push({
						name: 'login'
					});
				});
			} else if (res.err == 2) {
				_this.reply = [];
			}
		}, function () {});
	}
});

/***/ })

});