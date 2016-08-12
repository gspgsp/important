import Vue from "../node_modules/vue/dist/vue.min.js";
import VueRouter from "../node_modules/vue-router/dist/vue-router.min.js";
import VueResource from "../node_modules/vue-resource/dist/vue-resource.min.js";
import index from "./components/index";
import infolist from "./components/infolist";
import infodetail from "./components/infodetail";
import melogged from "./components/melogged";
import login from "./components/login";
import oilprice from "./components/oilprice";
import mycreditdetail from "./components/mycreditdetail";
import shopdetail from "./components/shopdetail";
import myset from "./components/myset";

Vue.use(VueRouter);
Vue.use(VueResource);

Vue.http.options.emulateJSON = true;

Vue.config.debug=true;

Vue.filter('upDown',function (value) {
    if(value>0){
        return '+'
    }else if(value<0){
        return '-'
    }else{
        return ''
    }
});

Vue.filter('upDownColor',function (value) {
    if(value>0){
        return 'red'
    }else if(value<0){
        return 'green'
    }else{
        return 'blue'
    }
});

    var App = Vue.extend({});
    var router = new VueRouter();
    router.map({
        '/':{
            name:'index',
            component: index
        },
        '/infolist': {
            name:'infolist',
            component: infolist
	    },
      	'/infodetail':{
          	name:'infodetail',
          	component:infodetail
      	},
      	'/melogged':{
	        name:'melogged',
	        component:melogged
	    },
	    '/login':{
            name:'login',
            component:login
        },
        '/oilprice':{
            name:'oilprice',
            component:oilprice
        },
        '/mycreditdetail':{
        	name:'mycreditdetail',
        	component:mycreditdetail
        },
        '/shopdetail':{
        	name:'shopdetail',
        	component:shopdetail
        },
        '/myset':{
        	name:'myset',
        	component:myset
        }
//      '/bigCustomer':{
//          name:'bigCustomer',
//          component:bigCustomer
//      },
//      '*':{
//          name:'error',
//          component:error
//      }
    });
    router.start(App, '#app');