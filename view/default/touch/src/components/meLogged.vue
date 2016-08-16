<template>
    <header id="meHeader" style="background: #ff5000; color: #ffffff;">
        <a class="left" style="color: #ffffff; font-size: 15px;" href="tel:4006129965">联系客服</a>
        我的
    </header>
    <div class="meWrap">
        <div class="meAvator">
            <img id="avator" v-bind:src=thumb>
            <img id="meAlpha" src="../../img/meAlpha.png">
        </div>
    </div>
    <div class="meName">{{name}}</div>
    <ul class="meUl">
        <li><a href="/mobi/personalCenter/enMyQuotation"><img src="../../img/me.png">我的报价单<span id="qcount">{{qcount}}</span></a></li>
        <li><a href="/mobi/personalCenter/enMyPurchase"><img src="../../img/me2.png">我的采购<span id="pcount">{{pcount}}</span></a></li>
    </ul>
    <ul class="meUl">
        <li><a href="/mobi/personalCenter/enMyAttention"><img src="../../img/me4.png">我的关注<span id="proattcount">{{proattcount}}</span></a></li>
        <li><a v-link={name:'mycreditdetail'}><img src="../../img/me5.png">我的积分<span id="points">{{points}}</span></a></li>
        <li><a href="javascript:;"><img src="../../img/me7.png">我的交易员<span id="cus_mana">{{cus_mana}}</span></a></li>
    </ul>
    <ul class="meUl">
        <li><a v-link={name:'myset'}><img src="../../img/me6.png">设置</a></li>
        <!--<li><a href="/mobi/personalCenter/enMyFeedBack"><img src="__MYAPP__/img/me8.png">意见反馈</a></li>-->
    </ul>
    <div class="bigCustomerDetailBtn">
        <input id="tradeBtn" @click="logOut()" style="background: #ff6600; border-bottom: 2px solid #cc5200;" type="button" value="退出登录">
    </div>
    <footer id="footer">
        <ul>
            <li><a><i class="foot"></i><br>大客户专区</a></li>
            <li><a><i class="foot2"></i><br>物性表</a></li>
            <li><a v-link={name:'index'}><i class="foot3"></i><br>首页</a></li>
            <li><a v-link={name:'infolist'}><i class="foot4"></i><br>资讯</a></li>
            <li><a class='footerOn' v-link={name:'melogged'}><i class="foot5"></i><br>我</a></li>
        </ul>
    </footer>
</template>

<script>
	module.exports={
        el:"#app",
        data:function () {
           return {
               cus_mana:"",
               name:"",
               pcount:"",
               points:"",
               proattcount:"",
               qcount:"",
               thumb:""
           }
        },
        ready:function () {
        	this.$http.post('/mobi/personalCenter/getPersonalCenter',{type1:1,type2:2}).then(function(res){
        		console.log(res.json());
        		this.$set('cus_mana',res.json().cus_mana);
                this.$set('name',res.json().name);
                this.$set('pcount',res.json().pcount);
                this.$set('points',res.json().points);
                this.$set('proattcount',res.json().proattcount);
                this.$set('qcount',res.json().qcount);
                this.$set('thumb',res.json().thumb);
                if(res.json().err==1){
                	this.$route.router.go('/login');
                }
        	},function(){
        		
        	});
        },
        methods:{
        	logOut:function(){
        		var _this=this;
        		this.$http.post("/mobi/personalCenter/logOut",{}).then(function(res){
        			console.log(res.json())
        			if(res.json().err==0){
	        			mui.alert('',res.json().msg,function(){
	        				_this.$route.router.go("/");
						});	
        			}else{
        				this.$route.router.go("/login");
        			}
        		},function(){
        			
        		});
        	}
        }
	}
</script>