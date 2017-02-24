import Vue from "../node_modules/vue/dist/vue.min.js";
import VueRouter from "../node_modules/vue-router/dist/vue-router.min.js";
import VueResource from "../node_modules/vue-resource/dist/vue-resource.min.js";

import login from "./views/login";
import register from "./views/register";
import resetpwd from "./views/resetpwd";
import completeinfo from "./views/completeinfo";
import index from "./views/index";
import myzone from "./views/myzone";
import release from "./views/release";
import releasebuy from "./views/releasebuy";
import releasesupply from "./views/releasesupply";
import personinfo from "./views/personinfo";
import myinvite from "./views/myinvite";
import myfans from "./views/myfans";
import mypay from "./views/mypay";
import mybuy from "./views/mybuy";
import mymsg from "./views/mymsg";
import mymsg2 from "./views/mymsg2";
import mysupply from "./views/mysupply";
import myinfo from "./views/myinfo";
import releasebs from "./views/releasebs";
import protocol from "./views/protocol";
import help from "./views/help";
import headline from "./views/headline";
import headlinelist from "./views/headlinelist";
import headlinedetail from "./views/headlinedetail";
import mypoints from "./views/mypoints";
import pointsrule from "./views/pointsrule";
import pointsrecord from "./views/pointsrecord";
import pointsdetail from "./views/pointsdetail";
import productdetail from "./views/productdetail";
import supplybuy from "./views/supplybuy";
import supplybuydetail from "./views/supplybuydetail";
import errorpage from "./views/error";

Vue.use(VueRouter);
Vue.use(VueResource);

Vue.http.options.emulateJSON = true;
Vue.config.debug=false;

var App = Vue.extend({});
var router = new VueRouter({
	  hashbang:true,
	  histroy:false,
    saveScrollPosition: false
});
router.beforeEach(function(){
    window.scrollTo(0,0);
})
router.map({
    '/login':{
        name:'login',
        component: login
    },
    '/index':{
        name:'index',
        component:index
    },
    '/protocol':{
        name:'protocol',
        component:protocol
    },
    '/register':{
        name:'register',
        component:register
    },
    '/personinfo/:id':{
        name:'personinfo',
        component:personinfo
    },
    '/myinfo':{
        name:'myinfo',
        component:myinfo
    },
    '/resetpwd':{
        name:'resetpwd',
        component:resetpwd
    },
    '/completeinfo':{
        name:'completeinfo',
        component:completeinfo
    },
    '/myzone':{
        name:'myzone',
        component:myzone
    },
    '/myinvite':{
        name:'myinvite',
        component:myinvite
    },
    '/myfans':{
        name:'myfans',
        component:myfans
    },
    '/mypay':{
        name:'mypay',
        component:mypay
    },
    '/mysupply':{
        name:'mysupply',
        component:mysupply
    },
    '/mymsg':{
        name:'mymsg',
        component:mymsg
    },
    '/mymsg2':{
        name:'mymsg2',
        component:mymsg2
    },
    '/mybuy':{
        name:'mybuy',
        component:mybuy
    },
    '/release':{
        name:'release',
        component:release
    },
    '/releasebuy/:id':{
        name:'releasebuy',
        component:releasebuy
    },
    '/releasebs':{
        name:'releasebs',
        component:releasebs
    },
    '/releasesupply/:id':{
        name:'releasesupply',
        component:releasesupply
    },
    '/headline':{
    		name:'headline',
    		component:headline
    },
    '/headlinelist/:id':{
    		name:'headlinelist',
    		component:headlinelist
    },
    '/headlinedetail/:id':{
    		name:'headlinedetail',
    		component:headlinedetail
    },
    '/supplybuy/:id':{
    		name:'supplybuy',
    		component:supplybuy
    },
    '/supplybuydetail/:id':{
    		name:'supplybuydetail',
    		component:supplybuydetail
    },
    '/help':{
        name:'help',
        component:help
    },
    '/mypoints':{
    	name:'mypoints',
    	component:mypoints
    },
    '/pointsrule':{
    	name:'pointsrule',
    	component:pointsrule
    },
    '/pointsrecord':{
    	name:'pointsrecord',
    	component:pointsrecord
    },
    '/pointsdetail':{
    	name:'pointsdetail',
    	component:pointsdetail
    },
    '/productdetail/:id':{
    	name:'productdetail',
    	component:productdetail
    },
    '*':{
        name:'errorpage',
        component:errorpage
    }
});
router.redirect({
  '/':'index'
});
router.start(App, '#app');