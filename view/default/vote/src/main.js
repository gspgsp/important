import Vue from '../node_modules/vue/dist/vue.min';
import VueRouter from '../node_modules/vue-router/dist/vue-router.min';
import Index from './views/index';
import Index2 from './views/index2';

Vue.use(VueRouter);
var routes=[
	{path:'/index',name:'index',component:Index},
	{path:'/index2',name:'index2',component:Index2},
	{path: '/', redirect: { name: 'index' }}
];

var router=new VueRouter({
	routes
});

var app=new Vue({
	router
}).$mount('#app');
