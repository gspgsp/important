import Vue from '../node_modules/vue/dist/vue.min';
import VueRouter from '../node_modules/vue-router/dist/vue-router.min';
import Index from './views/index';
import Login from './views/login';
import Register from './views/register';
import Resetpwd from './views/resetpwd';
import Completeinfo from "./views/completeinfo";
import Headline from './views/headline';
import Headlinelist from "./views/headlinelist";
import Headlinedetail from "./views/headlinedetail";
import Personinfo from "./views/personinfo";
import Supplybuy from "./views/supplybuy";
import Release from './views/release';
import Releasedetail from './views/releasedetail';
import Releasebsbuy from "./views/releasebsbuy";
import Releasebssupply from "./views/releasebssupply";
import Releasesupply from './views/releasesupply';
import Releasebuy from './views/releasebuy';
import Myzone from './views/myzone';
import Myinfo from "./views/myinfo";
import Myinvite from './views/myinvite';
import Myfans from './views/myfans';
import Mypay from './views/mypay';
import Mysupply from "./views/mysupply";
import Mybuy from "./views/mybuy";
import Mymsg from './views/mymsg';
import Mymsg2 from './views/mymsg2';
import Help from "./views/help";
import Protocol from "./views/protocol";
import Pointsrule from "./views/pointsrule";
import Mypoints from "./views/mypoints";
import Pointsrecord from "./views/pointsrecord";
import Pointsdetail from "./views/pointsdetail";
import Productdetail from "./views/productdetail";
import Supplybuydetail from "./views/supplybuydetail";
import Credit from "./views/credit";
import Credit2 from "./views/credit2";
import Searchcompany from "./views/searchcompany";
import Creditintro from "./views/creditintro";
import Errorpage from "./views/error";

Vue.use(VueRouter);

var routes=[
	{path:'/index',name:'index',component:Index},
	{path:'/login',name:'login',component:Login},
	{path:'/register',name:'register',component:Register},
	{path:'/resetpwd',name:'resetpwd',component:Resetpwd},
	{path:'/completeinfo',name:'completeinfo',component:Completeinfo},
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
	{path:'/credit',name:'credit',component:Credit},
	{path:'/creditintro',name:'creditintro',component:Creditintro},
	{path:'/credit2/:id',name:'credit2',component:Credit2},
	{path:'/searchcompany',name:'searchcompany',component:Searchcompany},
	{path:'/errorpage',name:'errorpage',component:Errorpage},
	{path: '/', redirect: { name: 'index' }}
];

var router=new VueRouter({
	routes
});

var url='/api/qapi1_1';

var app=new Vue({
	router
}).$mount('#app');
