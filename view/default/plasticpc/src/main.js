import Vue from "../node_modules/vue/dist/vue.min.js";
import VueRouter from "../node_modules/vue-router/dist/vue-router.min.js";
import index from "../src/views/index";

Vue.use(VueRouter);

Vue.config.debug=false;

var App = Vue.extend({});

var routes = [
  { path: '/index', component: index }
];

var router = new VueRouter({
  routes // （缩写）相当于 routes: routes
})

var app = new Vue({
  router
}).$mount('#app');