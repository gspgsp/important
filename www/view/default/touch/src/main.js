import Vue from "../node_modules/vue/dist/vue.min.js";
import VueRouter from "../node_modules/vue-router/dist/vue-router.min.js";
import VueResource from "../node_modules/vue-resource/dist/vue-resource.min.js";

import buy from "./components/buy";
import sale from "./components/sale";
import buydetail from "./components/buydetail";
import saledetail from "./components/saledetail";
import todayindex from "./components/todayindex";
import price from "./components/price";
import release from "./components/release";
import errorpage from "./components/error";


Vue.use(VueRouter);
Vue.use(VueResource);

Vue.http.options.emulateJSON = true;

Vue.config.debug=false;

Vue.filter('upDown', function (value) {
  if(value>0){
  	return 'red'
  }else if(value==0){
  	return 'blue'
  }else{
  	return 'green'
  }
});

Vue.filter('plusMinus', function (value) {
  if(value>0){
  	return '+'
  }else if(value==0){
  	return ''
  }else{
  	return '-'
  }
});

var App = Vue.extend({});
var router = new VueRouter();
router.map({
    '/buy':{
        name:'buy',
        component: buy
    },
    '/sale':{
        name:'sale',
        component:sale
    },
    '/buydetail':{
        name:'buydetail',
        component:buydetail
    },
    '/saledetail':{
        name:'saledetail',
        component:saledetail
    },
    '/todayindex':{
        name:'todayindex',
        component:todayindex
    },
    '/price':{
        name:'price',
        component:price
    },
    '/release':{
        name:'release',
        component:release
    },
    '*':{
        name:'errorpage',
        component:errorpage
    }
});
router.redirect({
  '/':'buy'
});
router.start(App, '#app');