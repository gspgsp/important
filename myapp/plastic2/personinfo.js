webpackJsonp([6],{110:function(t,e){t.exports={render:function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"buyWrap",staticStyle:{padding:"45px 0 70px 0"}},[t._m(0),t._v(" "),a("div",{staticClass:"personInfo"},[a("div",{staticStyle:{float:"left",width:"100%",margin:"0 0 17px 0"}},[a("div",{staticStyle:{width:"80px",height:"80px",margin:"0 15px 0 0",position:"relative",float:"left"}},[a("div",{staticClass:"personAvator",on:{click:t.check}},[a("img",{attrs:{src:t.thumb}})]),t._v(" "),a("i",{staticClass:"iconV",class:{v1:1==t.is_pass,v2:0==t.is_pass}})]),t._v(" "),a("div",{staticClass:"personName",staticStyle:{margin:"20px 0 0 0"}},[t._v("\n\t\t\t\t"+t._s(t.name)+" "+t._s(t.sex)+"\n\t\t\t\t"),a("span",{staticClass:"orange",on:{click:t.pay}},[t._v(t._s(t.status))])]),t._v(" "),a("div",{staticClass:"personNum",staticStyle:{margin:"5px 0 0 0"}},[a("span",[t._v("发布供给："),a("span",{staticStyle:{color:"#63769d"}},[t._v(t._s(t.sale)+"条")])]),t._v(" "),a("span",[t._v("发布需求："),a("span",{staticStyle:{color:"#63769d"}},[t._v(t._s(t.buy)+"条")])])])]),t._v(" "),a("div",{staticClass:"personInfoList"},[a("p",[t._v("公司："+t._s(t.c_name))]),t._v(" "),a("p",[t._v("地址："+t._s(t.address))]),t._v(" "),a("p",[t._v("联系电话："+t._s(t.mobile)+"\n\t\t\t\t"),a("a",{directives:[{name:"show",rawName:"v-show",value:t.isMobile,expression:"isMobile"}],staticClass:"telephone",attrs:{href:t.mobile2}})]),t._v(" "),"0"===t.type||"2"===t.type?a("p",{staticStyle:{"border-bottom":"1px solid #D1D1D1"}},[t._v("我的主营："+t._s(t.need_product))]):t._e(),t._v(" "),"3"===t.type||"1"===t.type?a("p",{staticStyle:{"border-bottom":"1px solid #D1D1D1"}},[t._v("我的需求："+t._s(t.need_product))]):t._e(),t._v(" "),"3"===t.type||"1"===t.type?a("p",{staticStyle:{"border-bottom":"1px solid #D1D1D1"}},[t._v("生产产品："+t._s(t.main_product))]):t._e(),t._v(" "),"3"===t.type||"1"===t.type?a("p",{staticStyle:{"border-bottom":"1px solid #D1D1D1"}},[t._v("月用量："+t._s(t.month_consum))]):t._e(),t._v(" "),a("div",{staticClass:"registerBox",staticStyle:{height:"auto",padding:"10px 0",margin:"0","line-height":"0","text-align":"center"}},[a("div",{staticClass:"card",on:{click:t.cardcheck}},[a("img",{attrs:{src:t.cardImg}})])])]),t._v(" "),a("div",{staticClass:"personInfoList"},[a("h3",{staticClass:"supplydemandtitle"},[t._v("\n\t\t\t\t最近求购信息"),a("router-link",{staticStyle:{color:"#ff4f00"},attrs:{to:{name:"releasebuy",params:{id:t.$route.params.id}}}},[t._v("查看更多>>")])],1),t._v(" "),a("ul",{staticClass:"supplydemandul"},t._l(t.buylist,function(e){return a("li",[a("span",{staticStyle:{color:"#999999"}},[t._v(t._s(e.input_time))]),a("br"),t._v(" "),a("span",{staticStyle:{color:"#ec8000"}},[t._v("求购")]),t._v(":"+t._s(e.contents)+"\n\t\t\t\t")])}))]),t._v(" "),a("div",{staticClass:"personInfoList"},[a("h3",{staticClass:"supplydemandtitle",staticStyle:{background:"#b8d2e3"}},[t._v("\n\t\t\t\t最近供给信息"),a("router-link",{staticStyle:{color:"#267bd3"},attrs:{to:{name:"releasesupply",params:{id:t.$route.params.id}}}},[t._v("查看更多>>")])],1),t._v(" "),a("ul",{staticClass:"supplydemandul"},t._l(t.supplylist,function(e){return a("li",[a("span",{staticStyle:{color:"#999999"}},[t._v(t._s(e.input_time))]),a("br"),t._v(" "),a("span",{staticStyle:{color:"#63769d"}},[t._v("供给")]),t._v(":"+t._s(e.contents)+"\n\t\t\t\t")])}))])]),t._v(" "),a("loadingPage",{attrs:{loading:t.loadingShow}}),t._v(" "),a("footerbar"),t._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:t.avatorCheck,expression:"avatorCheck"}],staticClass:"imgLayer",on:{click:t.check}},[a("div",{staticClass:"avatorCheck",style:{backgroundImage:"url("+t.thumb+")"}})]),t._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:t.cardCheck,expression:"cardCheck"}],staticClass:"imgLayer",on:{click:t.cardcheck}},[a("div",{staticClass:"avatorCheck",style:{backgroundImage:"url("+t.cardImg+")"}})])],1)},staticRenderFns:[function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticStyle:{position:"fixed",top:"0",left:"0",width:"100%","z-index":"10"}},[a("header",{attrs:{id:"bigCustomerHeader"}},[a("a",{staticClass:"back",attrs:{href:"javascript:window.history.back();"}}),t._v("\n\t\t\t查看个人信息\n\t\t")])])}]}},23:function(t,e,a){var s=a(46)(a(80),a(110),null,null);t.exports=s.exports},46:function(t,e){t.exports=function(t,e,a,s){var i,n=t=t||{},o=typeof t.default;"object"!==o&&"function"!==o||(i=t,n=t.default);var r="function"==typeof n?n.options:n;if(e&&(r.render=e.render,r.staticRenderFns=e.staticRenderFns),a&&(r._scopeId=a),s){var c=Object.create(r.computed||null);Object.keys(s).forEach(function(t){var e=s[t];c[t]=function(){return e}}),r.computed=c}return{esModule:i,exports:n,options:r}}},47:function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default={data:function(){return{isIndex:!1,isRelease:!1,isMyzone:!1,isHeadline:!1}},methods:{toQuickRelease:function(){var t=this;window.localStorage.getItem("token")?t.$router.push({name:"quickrelease"}):weui.alert("您未登录塑料圈,无法查看企业及个人信息",{title:"塑料圈通讯录",buttons:[{label:"确定",type:"parimary",onClick:function(){t.$router.push({name:"login"})}}]})},toRelease:function(){var t=this;window.localStorage.getItem("token")?t.$router.push({name:"release"}):weui.alert("您未登录塑料圈,无法查看企业及个人信息",{title:"塑料圈通讯录",buttons:[{label:"确定",type:"parimary",onClick:function(){t.$router.push({name:"login"})}}]})},toMyzone:function(){var t=this;window.localStorage.getItem("token")?t.$router.push({name:"myzone"}):weui.alert("您未登录塑料圈,无法查看企业及个人信息",{title:"塑料圈通讯录",buttons:[{label:"确定",type:"parimary",onClick:function(){t.$router.push({name:"login"})}}]})},toHeadline:function(){var t=this;window.localStorage.getItem("token")?t.$router.push({name:"headline"}):weui.alert("您未登录塑料圈,无法查看企业及个人信息",{title:"塑料圈通讯录",buttons:[{label:"确定",type:"parimary",onClick:function(){t.$router.push({name:"login"})}}]})}},mounted:function(){switch(this.$route.name){case"index":this.isIndex=!0,this.isRelease=!1,this.isMyzone=!1,this.isHeadline=!1;break;case"release":this.isIndex=!1,this.isRelease=!0,this.isMyzone=!1,this.isHeadline=!1;break;case"myzone":case"mysupply":case"mybuy":case"myinvite":case"myfans":case"mypay":case"mymsg":case"mymsg2":case"myinfo":this.isIndex=!1,this.isRelease=!1,this.isMyzone=!0,this.isHeadline=!1;break;case"headline":case"headlinedetail":case"headlinelist":this.isIndex=!1,this.isRelease=!1,this.isMyzone=!1,this.isHeadline=!0}}}},48:function(t,e,a){var s=a(46)(a(47),a(49),null,null);t.exports=s.exports},49:function(t,e){t.exports={render:function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("footer",{attrs:{id:"footer"}},[a("ul",[a("li",[a("a",{class:{footerOn:t.isRelease},attrs:{href:"javascript:;"},on:{click:t.toRelease}},[a("i",{staticClass:"foot3"}),a("br"),t._v("供求")])]),t._v(" "),a("li",[a("router-link",{class:{footerOn:t.isIndex},attrs:{to:{name:"index"}}},[a("i",{staticClass:"foot2"}),a("br"),t._v("通讯录")])],1),t._v(" "),a("li",[a("i",{staticClass:"releaseicon",on:{click:t.toQuickRelease}})]),t._v(" "),a("li",[a("a",{class:{footerOn:t.isHeadline},attrs:{href:"javascript:;"},on:{click:t.toHeadline}},[a("i",{staticClass:"foot5"}),a("br"),t._v("发现")])]),t._v(" "),a("li",[a("a",{class:{footerOn:t.isMyzone},attrs:{href:"javascript:;"},on:{click:t.toMyzone}},[a("i",{staticClass:"foot4"}),a("br"),t._v("我的")])])])])])},staticRenderFns:[]}},50:function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default={props:["loading"]}},51:function(t,e,a){var s=a(46)(a(50),a(52),null,null);t.exports=s.exports},52:function(t,e){t.exports={render:function(){var t=this,e=t.$createElement;return(t._self._c||e)("div",{directives:[{name:"show",rawName:"v-show",value:t.loading,expression:"loading"}],staticClass:"loadingPage"},[t._m(0)])},staticRenderFns:[function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"loadingWrap"},[a("div",{staticClass:"slqLoading"}),t._v(" "),a("div",{staticClass:"slqLoadingTxt"},[t._v("数据加载中,请稍候...")])])}]}},80:function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var s=a(48),i=a.n(s),n=a(51),o=a.n(n);e.default={components:{footerbar:i.a,loadingPage:o.a},data:function(){return{name:"",buy:"",sale:"",c_name:"",mobile:"",address:"",sex:"",status:"",thumb:"",need_product:"",id:"",avatorCheck:!1,cardCheck:!1,user_id:"",content:"",is_pass:"",cardImg:"",mobile2:"",type:"",main_product:"",month_consum:"",buylist:[],supplylist:[],loadingShow:""}},methods:{cancel:function(){this.show=!1},check:function(){1==this.avatorCheck?this.avatorCheck=!1:this.avatorCheck=!0},cardcheck:function(){1==this.cardCheck?this.cardCheck=!1:this.cardCheck=!0},pay:function(){var t=this;$.ajax({url:"/api/qapi1/focusOrCancel",type:"get",data:{focused_id:t.$route.params.id,token:window.localStorage.getItem("token")},dataType:"JSON"}).then(function(t){window.location.reload()},function(){})}},beforeRouteEnter:function(t,e,a){a(function(t){t.loadingShow=!0})},activated:function(){var t=this;window.scrollTo(0,0);try{Piwik.getTracker("http://wa.myplas.com/piwik.php",2).trackPageView()}catch(t){}$.ajax({url:"/api/qapi1_2/getZoneFriend",type:"post",data:{userid:t.$route.params.id,token:window.localStorage.getItem("token")},dataType:"JSON"}).done(function(e){0==e.err?(t.name=e.data.name,t.c_name=e.data.c_name,t.address=e.data.address,t.mobile=e.data.mobile,t.mobile2="tel:"+e.data.mobile,t.need_product=e.data.need_product,t.status=e.data.status,t.thumb=e.data.thumb,t.buy=e.data.buy,t.sale=e.data.sale,t.sex=e.data.sex,t.id=e.data.user_id,t.is_pass=e.data.is_pass,t.cardImg=e.data.thumbcard,t.type=e.data.type,t.main_product=e.data.main_product,t.month_consum=e.data.month_consum,"-1"==t.mobile.indexOf("*")?t.isMobile=!0:t.isMobile=!1):1==e.err?weui.alert(e.msg,{title:"塑料圈通讯录",buttons:[{label:"确定",type:"parimary",onClick:function(){t.$router.push({name:"login"})}}]}):99==e.err&&weui.confirm(e.msg,function(){$.ajax({url:"/api/score/decScore",type:"post",data:{type:2,other_id:t.$route.params.id,token:window.localStorage.getItem("token")},dataType:"JSON"}).then(function(e){0==e.err?$.ajax({url:"/api/qapi1_2/getZoneFriend",type:"post",data:{userid:t.$route.params.id,token:window.localStorage.getItem("token")},dataType:"JSON"}).then(function(e){console.log(e),0==e.err&&(t.name=e.data.name,t.c_name=e.data.c_name,t.address=e.data.address,t.mobile=e.data.mobile,t.mobile2="tel:"+e.data.mobile,t.need_product=e.data.need_product,t.status=e.data.status,t.thumb=e.data.thumb,t.buy=e.data.buy,t.sale=e.data.sale,t.sex=e.data.sex,t.id=e.data.user_id,t.is_pass=e.data.is_pass,t.type=e.data.type,t.main_product=e.data.main_product,t.month_consum=e.data.month_consum,t.cardImg=e.data.thumbcard,"-1"==t.mobile.indexOf("*")?t.isMobile=!0:t.isMobile=!1)},function(){}):100==e.err&&weui.alert(e.msg,{title:"塑料圈通讯录",buttons:[{label:"确定",type:"parimary",onClick:function(){t.$router.push({name:"pointsrule"})}}]})},function(){})},function(){window.history.back()},{title:"塑料圈通讯录"})}).fail(function(){}).always(function(){t.loadingShow=!1}),$.ajax({url:"/api/qapi1/getTaPur",type:"get",data:{userid:t.$route.params.id,page:1,size:5,type:1,token:window.localStorage.getItem("token")},dataType:"JSON"}).then(function(e){0==e.err?t.buylist=e.data:1==e.err||2==e.err&&(t.buylist=[])},function(){}),$.ajax({url:"/api/qapi1/getTaPur",type:"get",data:{userid:t.$route.params.id,page:1,size:5,type:2,token:window.localStorage.getItem("token")},dataType:"JSON"}).then(function(e){0==e.err?t.supplylist=e.data:1==e.err||2==e.err&&(t.supplylist=[])},function(){})}}}});