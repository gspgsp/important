webpackJsonp([35],{27:function(module,exports){eval("'use strict';\n\nmodule.exports = {\n\tdata: function data() {\n\t\treturn {\n\t\t\tresMsg: [],\n\t\t\ttabIndex: 0\n\t\t};\n\t},\n\tmethods: {},\n\tmounted: function mounted() {\n\t\tvar _this = this;\n\t\ttry {\n\t\t\tvar piwikTracker = Piwik.getTracker(\"http://wa.myplas.com/piwik.php\", 2);\n\t\t\tpiwikTracker.trackPageView();\n\t\t} catch (err) {}\n\t\t$.ajax({\n\t\t\turl: '/api/qapi1/getRobotMsg',\n\t\t\ttype: 'get',\n\t\t\tdata: {\n\t\t\t\tpage: 1,\n\t\t\t\ttoken: window.localStorage.getItem(\"token\"),\n\t\t\t\tsize: 100\n\t\t\t},\n\t\t\tdataType: 'JSON'\n\t\t}).then(function (res) {\n\t\t\tif (res.err == 0) {\n\t\t\t\t_this.resMsg = res.data;\n\t\t\t}\n\t\t}, function () {});\n\t}\n};//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vbXltc2cyLnZ1ZT83NTUxIl0sIm5hbWVzIjpbXSwibWFwcGluZ3MiOiI7O0FBeUJBO3VCQUVBOztXQUVBO2FBRUE7QUFIQTtBQUlBO1VBR0E7NkJBQ0E7Y0FDQTtNQUNBO3lFQUNBO2dCQUNBO2dCQUVBLENBQ0E7O1FBRUE7U0FDQTs7VUFFQTt1Q0FDQTtVQUVBO0FBSkE7YUFLQTtBQVJBLHlCQVNBO3FCQUNBO3VCQUNBO0FBQ0E7aUJBRUEsQ0FDQTtBQUNBO0FBbENBIiwiZmlsZSI6IjI3LmpzIiwic291cmNlc0NvbnRlbnQiOlsiPHRlbXBsYXRlPlxuPGRpdiBjbGFzcz1cImJ1eVdyYXBcIiBzdHlsZT1cInBhZGRpbmc6IDQ1cHggMCAwIDA7XCI+XG5cdDxkaXYgc3R5bGU9XCJwb3NpdGlvbjogZml4ZWQ7IHRvcDogMDsgbGVmdDogMDsgd2lkdGg6IDEwMCU7IHotaW5kZXg6IDEwO1wiPlxuXHRcdDxoZWFkZXIgaWQ9XCJiaWdDdXN0b21lckhlYWRlclwiPlxuXHRcdFx0PGEgY2xhc3M9XCJiYWNrXCIgaHJlZj1cImphdmFzY3JpcHQ6d2luZG93Lmhpc3RvcnkuYmFjaygpO1wiPjwvYT5cblx0XHRcdOaIkeeahOa2iOaBr1xuXHRcdDwvaGVhZGVyPlxuXHQ8L2Rpdj5cblx0PGRpdiBjbGFzcz1cIm15bXNnMmNob29zZVwiPlxuXHRcdDxzcGFuIHYtYmluZDpjbGFzcz1cInsnb24nOjA9PXRhYkluZGV4fVwiPuezu+e7n+a2iOaBrzwvc3Bhbj5cblx0PC9kaXY+XG5cdDx1bCBjbGFzcz1cIm15bXNnMnVsXCI+XG5cdFx0PGxpIHYtZm9yPVwiciBpbiByZXNNc2dcIj5cblx0XHRcdDxkaXYgY2xhc3M9XCJteXJlbGVhc2VJbmZvXCI+XG5cdFx0XHRcdDxkaXYgY2xhc3M9XCJteXJlbGVhc2V0eHQzXCIgc3R5bGU9XCJtYXJnaW46IDA7XCI+XG5cdFx0XHRcdFx0PGRpdiBjbGFzcz1cIm15bXNnd3JhcFwiPlxuXHRcdFx0XHRcdFx0e3tyLmNvbnRlbnR9fVxuXHRcdFx0XHRcdDwvZGl2PlxuXHRcdFx0XHQ8L2Rpdj5cblx0XHRcdDwvZGl2PlxuXHRcdDwvbGk+XG5cdDwvdWw+XG48L2Rpdj5cbjwvdGVtcGxhdGU+XG48c2NyaXB0PlxubW9kdWxlLmV4cG9ydHMgPSB7XG5cdGRhdGE6IGZ1bmN0aW9uKCkge1xuXHRcdHJldHVybiB7XG5cdFx0XHRyZXNNc2c6IFtdLFxuXHRcdFx0dGFiSW5kZXg6IDBcblx0XHR9XG5cdH0sXG5cdG1ldGhvZHM6IHtcblxuXHR9LFxuXHRtb3VudGVkOiBmdW5jdGlvbigpIHtcblx0XHR2YXIgX3RoaXMgPSB0aGlzO1xuXHRcdHRyeSB7XG5cdFx0XHR2YXIgcGl3aWtUcmFja2VyID0gUGl3aWsuZ2V0VHJhY2tlcihcImh0dHA6Ly93YS5teXBsYXMuY29tL3Bpd2lrLnBocFwiLCAyKTtcblx0XHRcdHBpd2lrVHJhY2tlci50cmFja1BhZ2VWaWV3KCk7XG5cdFx0fSBjYXRjaChlcnIpIHtcblxuXHRcdH1cblx0XHQkLmFqYXgoe1xuXHRcdFx0dXJsOiAnL2FwaS9xYXBpMS9nZXRSb2JvdE1zZycsXG5cdFx0XHR0eXBlOiAnZ2V0Jyxcblx0XHRcdGRhdGE6IHtcblx0XHRcdFx0cGFnZTogMSxcblx0XHRcdFx0dG9rZW46IHdpbmRvdy5sb2NhbFN0b3JhZ2UuZ2V0SXRlbShcInRva2VuXCIpLFxuXHRcdFx0XHRzaXplOiAxMDBcblx0XHRcdH0sXG5cdFx0XHRkYXRhVHlwZTogJ0pTT04nXG5cdFx0fSkudGhlbihmdW5jdGlvbihyZXMpIHtcblx0XHRcdGlmKHJlcy5lcnIgPT0gMCkge1xuXHRcdFx0XHRfdGhpcy5yZXNNc2cgPSByZXMuZGF0YTtcblx0XHRcdH1cblx0XHR9LCBmdW5jdGlvbigpIHtcblxuXHRcdH0pO1xuXHR9XG59XG48L3NjcmlwdD5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gbXltc2cyLnZ1ZT82NjU1YzVjYSJdLCJzb3VyY2VSb290IjoiIn0=")},70:function(module,exports,__webpack_require__){eval('var __vue_exports__, __vue_options__\nvar __vue_styles__ = {}\n\n/* script */\n__vue_exports__ = __webpack_require__(27)\n\n/* template */\nvar __vue_template__ = __webpack_require__(126)\n__vue_options__ = __vue_exports__ = __vue_exports__ || {}\nif (\n  typeof __vue_exports__.default === "object" ||\n  typeof __vue_exports__.default === "function"\n) {\n__vue_options__ = __vue_exports__ = __vue_exports__.default\n}\nif (typeof __vue_options__ === "function") {\n  __vue_options__ = __vue_options__.options\n}\n\n__vue_options__.render = __vue_template__.render\n__vue_options__.staticRenderFns = __vue_template__.staticRenderFns\n\nmodule.exports = __vue_exports__\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9zcmMvdmlld3MvbXltc2cyLnZ1ZT8yNWY2Il0sIm5hbWVzIjpbXSwibWFwcGluZ3MiOiJBQUFBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQSIsImZpbGUiOiI3MC5qcyIsInNvdXJjZXNDb250ZW50IjpbInZhciBfX3Z1ZV9leHBvcnRzX18sIF9fdnVlX29wdGlvbnNfX1xudmFyIF9fdnVlX3N0eWxlc19fID0ge31cblxuLyogc2NyaXB0ICovXG5fX3Z1ZV9leHBvcnRzX18gPSByZXF1aXJlKFwiISFiYWJlbC1sb2FkZXIhdnVlLWxvYWRlci9saWIvc2VsZWN0b3I/dHlwZT1zY3JpcHQmaW5kZXg9MCEuL215bXNnMi52dWVcIilcblxuLyogdGVtcGxhdGUgKi9cbnZhciBfX3Z1ZV90ZW1wbGF0ZV9fID0gcmVxdWlyZShcIiEhdnVlLWxvYWRlci9saWIvdGVtcGxhdGUtY29tcGlsZXI/aWQ9ZGF0YS12LTg5OThlMzU0IXZ1ZS1sb2FkZXIvbGliL3NlbGVjdG9yP3R5cGU9dGVtcGxhdGUmaW5kZXg9MCEuL215bXNnMi52dWVcIilcbl9fdnVlX29wdGlvbnNfXyA9IF9fdnVlX2V4cG9ydHNfXyA9IF9fdnVlX2V4cG9ydHNfXyB8fCB7fVxuaWYgKFxuICB0eXBlb2YgX192dWVfZXhwb3J0c19fLmRlZmF1bHQgPT09IFwib2JqZWN0XCIgfHxcbiAgdHlwZW9mIF9fdnVlX2V4cG9ydHNfXy5kZWZhdWx0ID09PSBcImZ1bmN0aW9uXCJcbikge1xuX192dWVfb3B0aW9uc19fID0gX192dWVfZXhwb3J0c19fID0gX192dWVfZXhwb3J0c19fLmRlZmF1bHRcbn1cbmlmICh0eXBlb2YgX192dWVfb3B0aW9uc19fID09PSBcImZ1bmN0aW9uXCIpIHtcbiAgX192dWVfb3B0aW9uc19fID0gX192dWVfb3B0aW9uc19fLm9wdGlvbnNcbn1cblxuX192dWVfb3B0aW9uc19fLnJlbmRlciA9IF9fdnVlX3RlbXBsYXRlX18ucmVuZGVyXG5fX3Z1ZV9vcHRpb25zX18uc3RhdGljUmVuZGVyRm5zID0gX192dWVfdGVtcGxhdGVfXy5zdGF0aWNSZW5kZXJGbnNcblxubW9kdWxlLmV4cG9ydHMgPSBfX3Z1ZV9leHBvcnRzX19cblxuXG5cbi8vLy8vLy8vLy8vLy8vLy8vL1xuLy8gV0VCUEFDSyBGT09URVJcbi8vIC4vc3JjL3ZpZXdzL215bXNnMi52dWVcbi8vIG1vZHVsZSBpZCA9IDcwXG4vLyBtb2R1bGUgY2h1bmtzID0gMzUiXSwic291cmNlUm9vdCI6IiJ9')},126:function(module,exports){eval('module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;\n  return _h(\'div\', {\n    staticClass: "buyWrap",\n    staticStyle: {\n      "padding": "45px 0 0 0"\n    }\n  }, [_vm._m(0), " ", _h(\'div\', {\n    staticClass: "mymsg2choose"\n  }, [_h(\'span\', {\n    class: {\n      \'on\': 0 == _vm.tabIndex\n    }\n  }, ["系统消息"])]), " ", _h(\'ul\', {\n    staticClass: "mymsg2ul"\n  }, [_vm._l((_vm.resMsg), function(r) {\n    return _h(\'li\', [_h(\'div\', {\n      staticClass: "myreleaseInfo"\n    }, [_h(\'div\', {\n      staticClass: "myreleasetxt3",\n      staticStyle: {\n        "margin": "0"\n      }\n    }, [_h(\'div\', {\n      staticClass: "mymsgwrap"\n    }, ["\\n\\t\\t\\t\\t\\t\\t" + _vm._s(r.content) + "\\n\\t\\t\\t\\t\\t"])])])])\n  })])])\n},staticRenderFns: [function (){var _vm=this;var _h=_vm.$createElement;\n  return _h(\'div\', {\n    staticStyle: {\n      "position": "fixed",\n      "top": "0",\n      "left": "0",\n      "width": "100%",\n      "z-index": "10"\n    }\n  }, [_h(\'header\', {\n    attrs: {\n      "id": "bigCustomerHeader"\n    }\n  }, [_h(\'a\', {\n    staticClass: "back",\n    attrs: {\n      "href": "javascript:window.history.back();"\n    }\n  }), "\\n\\t\\t\\t我的消息\\n\\t\\t"])])\n}]}//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9zcmMvdmlld3MvbXltc2cyLnZ1ZT82NWUzIl0sIm5hbWVzIjpbXSwibWFwcGluZ3MiOiJBQUFBLGdCQUFnQixtQkFBbUIsYUFBYTtBQUNoRDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsR0FBRztBQUNIO0FBQ0EsR0FBRztBQUNIO0FBQ0E7QUFDQTtBQUNBLEdBQUc7QUFDSDtBQUNBLEdBQUc7QUFDSDtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0EsS0FBSztBQUNMLEdBQUc7QUFDSCxDQUFDLCtCQUErQixhQUFhO0FBQzdDO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxHQUFHO0FBQ0g7QUFDQTtBQUNBO0FBQ0EsR0FBRztBQUNIO0FBQ0E7QUFDQSxnREFBZ0Q7QUFDaEQ7QUFDQSxHQUFHO0FBQ0gsQ0FBQyIsImZpbGUiOiIxMjYuanMiLCJzb3VyY2VzQ29udGVudCI6WyJtb2R1bGUuZXhwb3J0cz17cmVuZGVyOmZ1bmN0aW9uICgpe3ZhciBfdm09dGhpczt2YXIgX2g9X3ZtLiRjcmVhdGVFbGVtZW50O1xuICByZXR1cm4gX2goJ2RpdicsIHtcbiAgICBzdGF0aWNDbGFzczogXCJidXlXcmFwXCIsXG4gICAgc3RhdGljU3R5bGU6IHtcbiAgICAgIFwicGFkZGluZ1wiOiBcIjQ1cHggMCAwIDBcIlxuICAgIH1cbiAgfSwgW192bS5fbSgwKSwgXCIgXCIsIF9oKCdkaXYnLCB7XG4gICAgc3RhdGljQ2xhc3M6IFwibXltc2cyY2hvb3NlXCJcbiAgfSwgW19oKCdzcGFuJywge1xuICAgIGNsYXNzOiB7XG4gICAgICAnb24nOiAwID09IF92bS50YWJJbmRleFxuICAgIH1cbiAgfSwgW1wi57O757uf5raI5oGvXCJdKV0pLCBcIiBcIiwgX2goJ3VsJywge1xuICAgIHN0YXRpY0NsYXNzOiBcIm15bXNnMnVsXCJcbiAgfSwgW192bS5fbCgoX3ZtLnJlc01zZyksIGZ1bmN0aW9uKHIpIHtcbiAgICByZXR1cm4gX2goJ2xpJywgW19oKCdkaXYnLCB7XG4gICAgICBzdGF0aWNDbGFzczogXCJteXJlbGVhc2VJbmZvXCJcbiAgICB9LCBbX2goJ2RpdicsIHtcbiAgICAgIHN0YXRpY0NsYXNzOiBcIm15cmVsZWFzZXR4dDNcIixcbiAgICAgIHN0YXRpY1N0eWxlOiB7XG4gICAgICAgIFwibWFyZ2luXCI6IFwiMFwiXG4gICAgICB9XG4gICAgfSwgW19oKCdkaXYnLCB7XG4gICAgICBzdGF0aWNDbGFzczogXCJteW1zZ3dyYXBcIlxuICAgIH0sIFtcIlxcblxcdFxcdFxcdFxcdFxcdFxcdFwiICsgX3ZtLl9zKHIuY29udGVudCkgKyBcIlxcblxcdFxcdFxcdFxcdFxcdFwiXSldKV0pXSlcbiAgfSldKV0pXG59LHN0YXRpY1JlbmRlckZuczogW2Z1bmN0aW9uICgpe3ZhciBfdm09dGhpczt2YXIgX2g9X3ZtLiRjcmVhdGVFbGVtZW50O1xuICByZXR1cm4gX2goJ2RpdicsIHtcbiAgICBzdGF0aWNTdHlsZToge1xuICAgICAgXCJwb3NpdGlvblwiOiBcImZpeGVkXCIsXG4gICAgICBcInRvcFwiOiBcIjBcIixcbiAgICAgIFwibGVmdFwiOiBcIjBcIixcbiAgICAgIFwid2lkdGhcIjogXCIxMDAlXCIsXG4gICAgICBcInotaW5kZXhcIjogXCIxMFwiXG4gICAgfVxuICB9LCBbX2goJ2hlYWRlcicsIHtcbiAgICBhdHRyczoge1xuICAgICAgXCJpZFwiOiBcImJpZ0N1c3RvbWVySGVhZGVyXCJcbiAgICB9XG4gIH0sIFtfaCgnYScsIHtcbiAgICBzdGF0aWNDbGFzczogXCJiYWNrXCIsXG4gICAgYXR0cnM6IHtcbiAgICAgIFwiaHJlZlwiOiBcImphdmFzY3JpcHQ6d2luZG93Lmhpc3RvcnkuYmFjaygpO1wiXG4gICAgfVxuICB9KSwgXCJcXG5cXHRcXHRcXHTmiJHnmoTmtojmga9cXG5cXHRcXHRcIl0pXSlcbn1dfVxuXG5cbi8vLy8vLy8vLy8vLy8vLy8vL1xuLy8gV0VCUEFDSyBGT09URVJcbi8vIC4vfi92dWUtbG9hZGVyL2xpYi90ZW1wbGF0ZS1jb21waWxlci5qcz9pZD1kYXRhLXYtODk5OGUzNTQhLi9+L3Z1ZS1sb2FkZXIvbGliL3NlbGVjdG9yLmpzP3R5cGU9dGVtcGxhdGUmaW5kZXg9MCEuL3NyYy92aWV3cy9teW1zZzIudnVlXG4vLyBtb2R1bGUgaWQgPSAxMjZcbi8vIG1vZHVsZSBjaHVua3MgPSAzNSJdLCJzb3VyY2VSb290IjoiIn0=')}});