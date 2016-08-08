import Vue from "../node_modules/vue/dist/vue.min.js";
import VueRouter from "../node_modules/vue-router/dist/vue-router.min.js";
import VueResource from "../node_modules/vue-resource/dist/vue-resource.min.js";
import index from "./components/index";
Vue.use(VueRouter);
Vue.use(VueResource);

Vue.http.options.emulateJSON = true;

Vue.config.debug=true;

    var App = Vue.extend({});
    var router = new VueRouter();
    router.map({
        '/':{
            name:'index',
            component: index
        }
//      '/bigCustomer':{
//          name:'bigCustomer',
//          component:bigCustomer
//      },
//      '/meLogged':{
//          name:'meLogged',
//          component:meLogged
//      },
//      '/infoDetail':{
//          name:'infoDetail',
//          component:infoDetail
//      },
//      '/oilPrice':{
//          name:'oilPrice',
//          component:oilPrice
//      },
//      '/infolist': {
//          name:'infolist',
//          component: infolist
//      },
//      '/login':{
//          name:'login',
//          component:login
//      },
//      '*':{
//          name:'error',
//          component:error
//      }
    });
    router.start(App, '#app');