webpackJsonp([4],{

/***/ 135:
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('div', {
    staticClass: "buyWrap",
    staticStyle: {
      "padding": "45px 0 70px 0"
    }
  }, [_c('header', {
    staticStyle: {
      "position": "fixed",
      "top": "0",
      "left": "0",
      "z-index": "10"
    },
    attrs: {
      "id": "bigCustomerHeader"
    }
  }, [_vm._v("\r\n塑料发现\r\n")]), _vm._v(" "), _c('loadingPage', {
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
    staticClass: "subscribe",
    on: {
      "click": _vm.subscribe
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
    staticClass: "swiper-slide"
  }, [_c('router-link', {
    staticClass: "on",
    attrs: {
      "to": {
        name: 'headlinelist',
        params: {
          id: 999
        }
      }
    }
  }, [_vm._v("\r\n\t\t\t\t\t推荐\r\n\t\t\t\t")])], 1), _vm._v(" "), _c('div', {
    staticClass: "swiper-slide"
  }, [_c('router-link', {
    attrs: {
      "to": {
        name: 'headlinelist',
        params: {
          id: 2
        }
      }
    }
  }, [_vm._v("\r\n\t\t\t\t\t塑料上游\r\n\t\t\t\t")])], 1), _vm._v(" "), _c('div', {
    staticClass: "swiper-slide"
  }, [_c('router-link', {
    attrs: {
      "to": {
        name: 'headlinelist',
        params: {
          id: 1
        }
      }
    }
  }, [_vm._v("\r\n\t\t\t\t\t早盘预报\r\n\t\t\t\t")])], 1), _vm._v(" "), _c('div', {
    staticClass: "swiper-slide"
  }, [_c('router-link', {
    attrs: {
      "to": {
        name: 'headlinelist',
        params: {
          id: 9
        }
      }
    }
  }, [_vm._v("\r\n\t\t\t\t\t企业动态\r\n\t\t\t\t")])], 1), _vm._v(" "), _c('div', {
    staticClass: "swiper-slide"
  }, [_c('router-link', {
    attrs: {
      "to": {
        name: 'headlinelist',
        params: {
          id: 4
        }
      }
    }
  }, [_vm._v("\r\n\t\t\t\t\t中晨塑说\r\n\t\t\t\t")])], 1), _vm._v(" "), _c('div', {
    staticClass: "swiper-slide"
  }, [_c('router-link', {
    attrs: {
      "to": {
        name: 'headlinelist',
        params: {
          id: 5
        }
      }
    }
  }, [_vm._v("\r\n\t\t\t\t\t美金市场\r\n\t\t\t\t")])], 1), _vm._v(" "), _c('div', {
    staticClass: "swiper-slide"
  }, [_c('router-link', {
    attrs: {
      "to": {
        name: 'headlinelist',
        params: {
          id: 21
        }
      }
    }
  }, [_vm._v("\r\n\t\t\t\t\t期货资讯\r\n\t\t\t\t")])], 1), _vm._v(" "), _c('div', {
    staticClass: "swiper-slide"
  }, [_c('router-link', {
    attrs: {
      "to": {
        name: 'headlinelist',
        params: {
          id: 11
        }
      }
    }
  }, [_vm._v("\r\n\t\t\t\t\t装置动态\r\n\t\t\t\t")])], 1), _vm._v(" "), _c('div', {
    staticClass: "swiper-slide"
  }, [_c('router-link', {
    attrs: {
      "to": {
        name: 'headlinelist',
        params: {
          id: 13
        }
      }
    }
  }, [_vm._v("\r\n\t\t\t\t\t期刊报告\r\n\t\t\t\t")])], 1), _vm._v(" "), _c('div', {
    staticClass: "swiper-slide"
  }, [_c('router-link', {
    attrs: {
      "to": {
        name: 'headlinelist',
        params: {
          id: 22
        }
      }
    }
  }, [_vm._v("\r\n\t\t\t\t\t独家解读\r\n\t\t\t\t")])], 1)])])])]), _vm._v(" "), _c('ul', {
    staticClass: "headlineUl2"
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
    }, [(i.type !== 'PUBLIC') ? _c('h3', [_vm._v("[" + _vm._s(i.type) + "]" + _vm._s(i.title))]) : _c('h3', [_vm._v(_vm._s(i.title))]), _vm._v(" "), _c('p', [_vm._v(_vm._s(i.description))]), _vm._v(" "), _c('p', {
      staticStyle: {
        "text-align": "right"
      }
    }, [_vm._v(_vm._s(i.input_time))])])], 1)
  })), _vm._v(" "), _c('h3', {
    staticClass: "plasticfind"
  }, [_vm._v("\r\n企业信用额度\r\n")]), _vm._v(" "), _c('ul', {
    staticClass: "plasticcredit"
  }, [_c('li', [_c('router-link', {
    attrs: {
      "to": {
        name: 'credit'
      }
    }
  }, [_c('i', {
    staticClass: "plasticIcon picon"
  }), _c('br'), _vm._v("查自己\r\n\t")])], 1), _vm._v(" "), _c('li', [_c('router-link', {
    attrs: {
      "to": {
        name: 'searchcompany'
      }
    }
  }, [_c('i', {
    staticClass: "plasticIcon picon2"
  }), _c('br'), _vm._v("查别人\r\n\t")])], 1), _vm._v(" "), _vm._m(0), _vm._v(" "), _vm._m(1)]), _vm._v(" "), _c('h3', {
    staticClass: "plasticfind"
  }, [_vm._v("\r\n塑料配资\r\n")]), _vm._v(" "), _vm._m(2), _vm._v(" "), _c('footerbar'), _vm._v(" "), _c('div', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.subscribeshow),
      expression: "subscribeshow"
    }],
    staticClass: "subscribelayer"
  }, [_c('h3', {
    staticClass: "subscribetitle"
  }, [_vm._v("\r\n我的频道：\r\n")]), _vm._v(" "), _c('ul', {
    staticClass: "mysubscribe"
  }, [_c('li', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.mySubscribe.indexOf('2') >= 0),
      expression: "mySubscribe.indexOf('2')>=0"
    }]
  }, [_c('i', {
    staticClass: "headlineicon hicon"
  }), _c('br'), _vm._v("塑料上游")]), _vm._v(" "), _c('li', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.mySubscribe.indexOf('1') >= 0),
      expression: "mySubscribe.indexOf('1')>=0"
    }]
  }, [_c('i', {
    staticClass: "headlineicon hicon2"
  }), _c('br'), _vm._v("早盘预报")]), _vm._v(" "), _c('li', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.mySubscribe.indexOf('9') >= 0),
      expression: "mySubscribe.indexOf('9')>=0"
    }]
  }, [_c('i', {
    staticClass: "headlineicon hicon3"
  }), _c('br'), _vm._v("企业动态")]), _vm._v(" "), _c('li', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.mySubscribe.indexOf('4') >= 0),
      expression: "mySubscribe.indexOf('4')>=0"
    }]
  }, [_c('i', {
    staticClass: "headlineicon hicon4"
  }), _c('br'), _vm._v("中晨塑说")]), _vm._v(" "), _c('li', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.mySubscribe.indexOf('20') >= 0),
      expression: "mySubscribe.indexOf('20')>=0"
    }]
  }, [_c('i', {
    staticClass: "headlineicon hicon5"
  }), _c('br'), _vm._v("美金市场")]), _vm._v(" "), _c('li', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.mySubscribe.indexOf('21') >= 0),
      expression: "mySubscribe.indexOf('21')>=0"
    }]
  }, [_c('i', {
    staticClass: "headlineicon hicon6"
  }), _c('br'), _vm._v("期货资讯")]), _vm._v(" "), _c('li', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.mySubscribe.indexOf('11') >= 0),
      expression: "mySubscribe.indexOf('11')>=0"
    }]
  }, [_c('i', {
    staticClass: "headlineicon hicon7"
  }), _c('br'), _vm._v("装置动态")]), _vm._v(" "), _c('li', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.mySubscribe.indexOf('13') >= 0),
      expression: "mySubscribe.indexOf('13')>=0"
    }]
  }, [_c('i', {
    staticClass: "headlineicon hicon8"
  }), _c('br'), _vm._v("期刊报告")]), _vm._v(" "), _c('li', {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: (_vm.mySubscribe.indexOf('22') >= 0),
      expression: "mySubscribe.indexOf('22')>=0"
    }]
  }, [_c('i', {
    staticClass: "headlineicon hicon9"
  }), _c('br'), _vm._v("独家解读")])]), _vm._v(" "), _c('h3', {
    staticClass: "subscribetitle"
  }, [_vm._v("\r\n全部频道：\r\n")]), _vm._v(" "), _c('ul', {
    staticClass: "mysubscribe"
  }, [_c('li', [_c('i', {
    staticClass: "headlineicon hicon"
  }), _c('br'), _vm._v(" "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.subchecked),
      expression: "subchecked"
    }],
    staticClass: "subscribecheckbox",
    attrs: {
      "type": "checkbox",
      "disabled": "disabled",
      "value": "2"
    },
    domProps: {
      "checked": Array.isArray(_vm.subchecked) ? _vm._i(_vm.subchecked, "2") > -1 : (_vm.subchecked)
    },
    on: {
      "__c": function($event) {
        var $$a = _vm.subchecked,
          $$el = $event.target,
          $$c = $$el.checked ? (true) : (false);
        if (Array.isArray($$a)) {
          var $$v = "2",
            $$i = _vm._i($$a, $$v);
          if ($$c) {
            $$i < 0 && (_vm.subchecked = $$a.concat($$v))
          } else {
            $$i > -1 && (_vm.subchecked = $$a.slice(0, $$i).concat($$a.slice($$i + 1)))
          }
        } else {
          _vm.subchecked = $$c
        }
      }
    }
  }), _vm._v(" 塑料上游")]), _vm._v(" "), _c('li', [_c('i', {
    staticClass: "headlineicon hicon2"
  }), _c('br'), _vm._v(" "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.subchecked),
      expression: "subchecked"
    }],
    staticClass: "subscribecheckbox",
    attrs: {
      "type": "checkbox",
      "value": "1"
    },
    domProps: {
      "checked": Array.isArray(_vm.subchecked) ? _vm._i(_vm.subchecked, "1") > -1 : (_vm.subchecked)
    },
    on: {
      "__c": function($event) {
        var $$a = _vm.subchecked,
          $$el = $event.target,
          $$c = $$el.checked ? (true) : (false);
        if (Array.isArray($$a)) {
          var $$v = "1",
            $$i = _vm._i($$a, $$v);
          if ($$c) {
            $$i < 0 && (_vm.subchecked = $$a.concat($$v))
          } else {
            $$i > -1 && (_vm.subchecked = $$a.slice(0, $$i).concat($$a.slice($$i + 1)))
          }
        } else {
          _vm.subchecked = $$c
        }
      }
    }
  }), _vm._v(" 早盘预报")]), _vm._v(" "), _c('li', [_c('i', {
    staticClass: "headlineicon hicon3"
  }), _c('br'), _vm._v(" "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.subchecked),
      expression: "subchecked"
    }],
    staticClass: "subscribecheckbox",
    attrs: {
      "type": "checkbox",
      "value": "9"
    },
    domProps: {
      "checked": Array.isArray(_vm.subchecked) ? _vm._i(_vm.subchecked, "9") > -1 : (_vm.subchecked)
    },
    on: {
      "__c": function($event) {
        var $$a = _vm.subchecked,
          $$el = $event.target,
          $$c = $$el.checked ? (true) : (false);
        if (Array.isArray($$a)) {
          var $$v = "9",
            $$i = _vm._i($$a, $$v);
          if ($$c) {
            $$i < 0 && (_vm.subchecked = $$a.concat($$v))
          } else {
            $$i > -1 && (_vm.subchecked = $$a.slice(0, $$i).concat($$a.slice($$i + 1)))
          }
        } else {
          _vm.subchecked = $$c
        }
      }
    }
  }), _vm._v(" 企业动态")]), _vm._v(" "), _c('li', [_c('i', {
    staticClass: "headlineicon hicon4"
  }), _c('br'), _vm._v(" "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.subchecked),
      expression: "subchecked"
    }],
    staticClass: "subscribecheckbox",
    attrs: {
      "type": "checkbox",
      "value": "4"
    },
    domProps: {
      "checked": Array.isArray(_vm.subchecked) ? _vm._i(_vm.subchecked, "4") > -1 : (_vm.subchecked)
    },
    on: {
      "__c": function($event) {
        var $$a = _vm.subchecked,
          $$el = $event.target,
          $$c = $$el.checked ? (true) : (false);
        if (Array.isArray($$a)) {
          var $$v = "4",
            $$i = _vm._i($$a, $$v);
          if ($$c) {
            $$i < 0 && (_vm.subchecked = $$a.concat($$v))
          } else {
            $$i > -1 && (_vm.subchecked = $$a.slice(0, $$i).concat($$a.slice($$i + 1)))
          }
        } else {
          _vm.subchecked = $$c
        }
      }
    }
  }), _vm._v(" 中晨塑说")]), _vm._v(" "), _c('li', [_c('i', {
    staticClass: "headlineicon hicon5"
  }), _c('br'), _vm._v(" "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.subchecked),
      expression: "subchecked"
    }],
    staticClass: "subscribecheckbox",
    attrs: {
      "type": "checkbox",
      "disabled": "disabled",
      "value": "20"
    },
    domProps: {
      "checked": Array.isArray(_vm.subchecked) ? _vm._i(_vm.subchecked, "20") > -1 : (_vm.subchecked)
    },
    on: {
      "__c": function($event) {
        var $$a = _vm.subchecked,
          $$el = $event.target,
          $$c = $$el.checked ? (true) : (false);
        if (Array.isArray($$a)) {
          var $$v = "20",
            $$i = _vm._i($$a, $$v);
          if ($$c) {
            $$i < 0 && (_vm.subchecked = $$a.concat($$v))
          } else {
            $$i > -1 && (_vm.subchecked = $$a.slice(0, $$i).concat($$a.slice($$i + 1)))
          }
        } else {
          _vm.subchecked = $$c
        }
      }
    }
  }), _vm._v(" 美金市场")]), _vm._v(" "), _c('li', [_c('i', {
    staticClass: "headlineicon hicon6"
  }), _c('br'), _vm._v(" "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.subchecked),
      expression: "subchecked"
    }],
    staticClass: "subscribecheckbox",
    attrs: {
      "type": "checkbox",
      "disabled": "disabled",
      "value": "21"
    },
    domProps: {
      "checked": Array.isArray(_vm.subchecked) ? _vm._i(_vm.subchecked, "21") > -1 : (_vm.subchecked)
    },
    on: {
      "__c": function($event) {
        var $$a = _vm.subchecked,
          $$el = $event.target,
          $$c = $$el.checked ? (true) : (false);
        if (Array.isArray($$a)) {
          var $$v = "21",
            $$i = _vm._i($$a, $$v);
          if ($$c) {
            $$i < 0 && (_vm.subchecked = $$a.concat($$v))
          } else {
            $$i > -1 && (_vm.subchecked = $$a.slice(0, $$i).concat($$a.slice($$i + 1)))
          }
        } else {
          _vm.subchecked = $$c
        }
      }
    }
  }), _vm._v(" 期货资讯")]), _vm._v(" "), _c('li', [_c('i', {
    staticClass: "headlineicon hicon7"
  }), _c('br'), _vm._v(" "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.subchecked),
      expression: "subchecked"
    }],
    staticClass: "subscribecheckbox",
    attrs: {
      "type": "checkbox",
      "disabled": "disabled",
      "value": "11"
    },
    domProps: {
      "checked": Array.isArray(_vm.subchecked) ? _vm._i(_vm.subchecked, "11") > -1 : (_vm.subchecked)
    },
    on: {
      "__c": function($event) {
        var $$a = _vm.subchecked,
          $$el = $event.target,
          $$c = $$el.checked ? (true) : (false);
        if (Array.isArray($$a)) {
          var $$v = "11",
            $$i = _vm._i($$a, $$v);
          if ($$c) {
            $$i < 0 && (_vm.subchecked = $$a.concat($$v))
          } else {
            $$i > -1 && (_vm.subchecked = $$a.slice(0, $$i).concat($$a.slice($$i + 1)))
          }
        } else {
          _vm.subchecked = $$c
        }
      }
    }
  }), _vm._v(" 装置动态")]), _vm._v(" "), _c('li', [_c('i', {
    staticClass: "headlineicon hicon8"
  }), _c('br'), _vm._v(" "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.subchecked),
      expression: "subchecked"
    }],
    staticClass: "subscribecheckbox",
    attrs: {
      "type": "checkbox",
      "value": "13"
    },
    domProps: {
      "checked": Array.isArray(_vm.subchecked) ? _vm._i(_vm.subchecked, "13") > -1 : (_vm.subchecked)
    },
    on: {
      "__c": function($event) {
        var $$a = _vm.subchecked,
          $$el = $event.target,
          $$c = $$el.checked ? (true) : (false);
        if (Array.isArray($$a)) {
          var $$v = "13",
            $$i = _vm._i($$a, $$v);
          if ($$c) {
            $$i < 0 && (_vm.subchecked = $$a.concat($$v))
          } else {
            $$i > -1 && (_vm.subchecked = $$a.slice(0, $$i).concat($$a.slice($$i + 1)))
          }
        } else {
          _vm.subchecked = $$c
        }
      }
    }
  }), _vm._v(" 期刊报告")]), _vm._v(" "), _c('li', [_c('i', {
    staticClass: "headlineicon hicon9"
  }), _c('br'), _vm._v(" "), _c('input', {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: (_vm.subchecked),
      expression: "subchecked"
    }],
    staticClass: "subscribecheckbox",
    attrs: {
      "type": "checkbox",
      "value": "22"
    },
    domProps: {
      "checked": Array.isArray(_vm.subchecked) ? _vm._i(_vm.subchecked, "22") > -1 : (_vm.subchecked)
    },
    on: {
      "__c": function($event) {
        var $$a = _vm.subchecked,
          $$el = $event.target,
          $$c = $$el.checked ? (true) : (false);
        if (Array.isArray($$a)) {
          var $$v = "22",
            $$i = _vm._i($$a, $$v);
          if ($$c) {
            $$i < 0 && (_vm.subchecked = $$a.concat($$v))
          } else {
            $$i > -1 && (_vm.subchecked = $$a.slice(0, $$i).concat($$a.slice($$i + 1)))
          }
        } else {
          _vm.subchecked = $$c
        }
      }
    }
  }), _vm._v(" 独家解读")])]), _vm._v(" "), _c('div', {
    staticClass: "subscribebtn"
  }, [_c('span', {
    staticClass: "subplasticbtn",
    on: {
      "click": _vm.subscribeSave
    }
  }, [_vm._v("保存")]), _vm._v("   \r\n\t"), _c('span', {
    staticClass: "subplasticbtn",
    on: {
      "click": _vm.subscribeClose
    }
  }, [_vm._v("关闭")])])])], 1)
},staticRenderFns: [function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('li', [_c('a', {
    attrs: {
      "href": "https://www.sobot.com/chat/h5/index.html?sysNum=137f8799efcb49fea05534057318dde0"
    }
  }, [_c('i', {
    staticClass: "plasticIcon picon3"
  }), _c('br'), _vm._v("要授信\r\n\t")])])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('li', [_c('a', {
    attrs: {
      "href": "https://www.sobot.com/chat/h5/index.html?sysNum=137f8799efcb49fea05534057318dde0"
    }
  }, [_c('i', {
    staticClass: "plasticIcon picon4"
  }), _c('br'), _vm._v("提额度\r\n\t")])])
},function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('ul', {
    staticClass: "plasticcredit2"
  }, [_c('li', [_c('a', {
    attrs: {
      "href": "https://www.sobot.com/chat/h5/index.html?sysNum=137f8799efcb49fea05534057318dde0"
    }
  }, [_c('i', {
    staticClass: "plasticIcon picon5"
  }), _c('br'), _vm._v("产品定义")])]), _vm._v(" "), _c('li', [_c('a', {
    attrs: {
      "href": "https://www.sobot.com/chat/h5/index.html?sysNum=137f8799efcb49fea05534057318dde0"
    }
  }, [_c('i', {
    staticClass: "plasticIcon picon6"
  }), _c('br'), _vm._v("费率")])]), _vm._v(" "), _c('li', [_c('a', {
    attrs: {
      "href": "https://www.sobot.com/chat/h5/index.html?sysNum=137f8799efcb49fea05534057318dde0"
    }
  }, [_c('i', {
    staticClass: "plasticIcon picon7"
  }), _c('br'), _vm._v("我要申请")])])])
}]}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-932307de", module.exports)
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

/***/ 64:
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
			cate: "",
			items: [],
			mySubscribe: [],
			subscribeshow: false,
			subchecked: [],
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
		subscribe: function subscribe() {
			this.subscribeshow = true;
		},
		subscribeClose: function subscribeClose() {
			this.subscribeshow = false;
		},
		subscribeSave: function subscribeSave() {
			var _this = this;
			this.subscribeshow = false;
			console.log(_this.subchecked);
			$.ajax({
				type: "post",
				url: '/api/qapi1_1/getSelectCate',
				data: {
					token: window.localStorage.getItem("token"),
					cate_id: _this.subchecked,
					type: 1
				},
				dataType: 'JSON'
			}).then(function (res) {
				if (res.err == 0) {
					$.ajax({
						type: "post",
						url: '/api/qapi1_1/getSelectCate',
						data: {
							token: window.localStorage.getItem("token"),
							type: 2
						},
						dataType: 'JSON'
					}).then(function (res) {
						if (res.err == 0) {
							_this.mySubscribe = res.data;
							_this.subchecked = res.data;
						} else {}
					}, function () {});
				} else {}
			}, function () {});
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
						_this.items = res.data.slice(0, 3);
					}
				}).fail(function () {}).always(function () {});
			} else {}
		}
	},
	activated: function activated() {
		try {
			var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
			piwikTracker.trackPageView();
		} catch (err) {}
		var _this = this;
		window.scrollTo(0, 0);

		$.ajax({
			type: "post",
			url: '/api/qapi1_1/getSelectCate',
			data: {
				token: window.localStorage.getItem("token"),
				type: 2
			},
			dataType: 'JSON'
		}).done(function (res) {
			if (res.err == 0) {
				_this.mySubscribe = res.data;
				_this.subchecked = res.data;
			} else {}
		}).fail(function () {}).always(function () {});

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
				_this.items = res.data.slice(0, 3);
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

/***/ 7:
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(46)(
  /* script */
  __webpack_require__(64),
  /* template */
  __webpack_require__(135),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "D:\\xampp\\htdocs\\workspace2\\www\\view\\default\\plasticzone\\src\\views\\headline.vue"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] headline.vue: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-932307de", Component.options)
  } else {
    hotAPI.reload("data-v-932307de", Component.options)
  }
})()}

module.exports = Component.exports


/***/ })

});