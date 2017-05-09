//import Vue from '../node_modules/vue/dist/vue.min';
//import VueRouter from '../node_modules/vue-router/dist/vue-router.min';

import Vue from 'vue';
import VueRouter from 'vue-router';



Vue.use(VueRouter);
const Index = r => require.ensure([], () => r(require('./views/index.vue')), 'index')
const Login = r => require.ensure([], () => r(require('./views/login.vue')), 'login')
const Register= r => require.ensure([], () => r(require('./views/register.vue')), 'register')
const Resetpwd= r => require.ensure([], () => r(require('./views/resetpwd.vue')), 'resetpwd')
const Headline= r => require.ensure([], () => r(require('./views/headline.vue')), 'headline')
const Release= r => require.ensure([], () => r(require('./views/release.vue')), 'release')
const Releasedetail= r => require.ensure([], () => r(require('./views/releasedetail.vue')), 'releasedetail')
const Myzone= r => require.ensure([], () => r(require('./views/myzone.vue')), 'myzone')
const Myinfo= r => require.ensure([], () => r(require('./views/myinfo.vue')), 'myinfo')
const Mypoints= r => require.ensure([], () => r(require('./views/mypoints.vue')), 'mypoints')
const Pointsrule= r => require.ensure([], () => r(require('./views/pointsrule.vue')), 'pointsrule')
const Pointsrecord= r => require.ensure([], () => r(require('./views/pointsrecord.vue')), 'pointsrecord')
const Pointsdetail= r => require.ensure([], () => r(require('./views/pointsdetail.vue')), 'pointsdetail')
const Productdetail= r => require.ensure([], () => r(require('./views/productdetail.vue')), 'productdetail')
const Quickrelease= r => require.ensure([], () => r(require('./views/quickrelease.vue')), 'quickrelease')
const Releasesupply= r => require.ensure([], () => r(require('./views/releasesupply.vue')), 'releasesupply')
const Releasebuy= r => require.ensure([], () => r(require('./views/releasebuy.vue')), 'releasebuy')
const Releasebsbuy= r => require.ensure([], () => r(require('./views/releasebsbuy.vue')), 'releasebsbuy')
const Releasebssupply= r => require.ensure([], () => r(require('./views/releasebssupply.vue')), 'releasebssupply')
const Myinvite= r => require.ensure([], () => r(require('./views/myinvite.vue')), 'myinvite')
const Myfans= r => require.ensure([], () => r(require('./views/myfans.vue')), 'myfans')
const Mypay= r => require.ensure([], () => r(require('./views/mypay.vue')), 'mypay')
const Mysupply= r => require.ensure([], () => r(require('./views/mysupply.vue')), 'mysupply')
const Mybuy= r => require.ensure([], () => r(require('./views/mybuy.vue')), 'mybuy')
const Mymsg= r => require.ensure([], () => r(require('./views/mymsg.vue')), 'mymsg')
const Mymsg2= r => require.ensure([], () => r(require('./views/mymsg2.vue')), 'mymsg2')
const Headlinelist= r => require.ensure([], () => r(require('./views/headlinelist.vue')), 'headlinelist')
const Personinfo= r => require.ensure([], () => r(require('./views/personinfo.vue')), 'personinfo')
const Supplybuy= r => require.ensure([], () => r(require('./views/supplybuy.vue')), 'supplybuy')
const Headlinedetail= r => require.ensure([], () => r(require('./views/headlinedetail.vue')), 'headlinedetail')
const Supplybuydetail= r => require.ensure([], () => r(require('./views/supplybuydetail.vue')), 'supplybuydetail')
const Help= r => require.ensure([], () => r(require('./views/help.vue')), 'help')
const Protocol= r => require.ensure([], () => r(require('./views/protocol.vue')), 'protocol')
const Protocol2= r => require.ensure([], () => r(require('./views/protocol2.vue')), 'protocol2')
const Credit= r => require.ensure([], () => r(require('./views/credit.vue')), 'credit')
const Creditintro= r => require.ensure([], () => r(require('./views/creditintro.vue')), 'creditintro')
const Credit2= r => require.ensure([], () => r(require('./views/credit2.vue')), 'credit2')
const Searchcompany= r => require.ensure([], () => r(require('./views/searchcompany.vue')), 'searchcompany')
const Recharge= r => require.ensure([], () => r(require('./views/recharge.vue')), 'recharge')
const Recharge2= r => require.ensure([], () => r(require('./views/recharge2.vue')), 'recharge2')
const Pointsrule2= r => require.ensure([], () => r(require('./views/pointsrule2.vue')), 'pointsrule2')
const Error= r => require.ensure([], () => r(require('./views/error.vue')), 'error')

var router = new VueRouter({
  routes: [
	{path:'/index',name:'index',component:Index},
	{path:'/login',name:'login',component:Login},
	{path:'/register',name:'register',component:Register},
	{path:'/resetpwd',name:'resetpwd',component:Resetpwd},
	{path:'/headline',name:'headline',component:Headline},
	{path:'/release',name:'release',component:Release},
	{path:'/releasedetail',name:'releasedetail',component:Releasedetail},
	{path:'/myzone',name:'myzone',component:Myzone},
	{path:'/myinfo',name:'myinfo',component:Myinfo},
	{path:'/mypoints',name:'mypoints',component:Mypoints},
	{path:'/pointsrule',name:'pointsrule',component:Pointsrule},
	{path:'/pointsrecord',name:'pointsrecord',component:Pointsrecord},
	{path:'/pointsdetail',name:'pointsdetail',component:Pointsdetail},
	{path:'/productdetail/:id',name:'productdetail',component:Productdetail},
	{path:'/releasesupply/:id',name:'releasesupply',component:Releasesupply},
	{path:'/releasebuy/:id',name:'releasebuy',component:Releasebuy},
	{path:'/quickrelease',name:'quickrelease',component:Quickrelease},
	{path:'/releasebsbuy',name:'releasebsbuy',component:Releasebsbuy},
	{path:'/releasebssupply',name:'releasebssupply',component:Releasebssupply},
	{path:'/myinvite',name:'myinvite',component:Myinvite},
	{path:'/myfans',name:'myfans',component:Myfans},
	{path:'/mypay',name:'mypay',component:Mypay},
	{path:'/mysupply',name:'mysupply',component:Mysupply},
	{path:'/mybuy',name:'mybuy',component:Mybuy},
	{path:'/mymsg',name:'mymsg',component:Mymsg},
	{path:'/mymsg2',name:'mymsg2',component:Mymsg2},
	{path:'/headlinelist/:id',name:'headlinelist',component:Headlinelist},
	{path:'/personinfo/:id',name:'personinfo',component:Personinfo},
	{path:'/supplybuy/:id',name:'supplybuy',component:Supplybuy},
	{path:'/headlinedetail/:id',name:'headlinedetail',component:Headlinedetail},
	{path:'/supplybuydetail/:id',name:'supplybuydetail',component:Supplybuydetail},
	{path:'/help',name:'help',component:Help},
	{path:'/protocol',name:'protocol',component:Protocol},
	{path:'/protocol2',name:'protocol2',component:Protocol2},
	{path:'/credit',name:'credit',component:Credit},
	{path:'/creditintro',name:'creditintro',component:Creditintro},
	{path:'/credit2/:id',name:'credit2',component:Credit2},
	{path:'/searchcompany',name:'searchcompany',component:Searchcompany},
	{path:'/recharge',name:'recharge',component:Recharge},
	{path:'/recharge2',name:'recharge2',component:Recharge2},
	{path:'/pointsrule2',name:'pointsrule2',component:Pointsrule2},
	{path: '/', redirect: { name: 'index' }}
  ]
})

var app=new Vue({
	router
}).$mount('#app');

