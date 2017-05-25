<template>
    <header id='header'>
        <a class='headerMenu' href='/mobi/mainPage/enProductType'></a>
        <div class='appSearch'><input type='text' placeholder='请输入产品分类、牌号' /><a class='appQuery' href='#'></a></div>
        <a class='headerService' href='tel:4006129965'></a>
    </header>
    <div id="slider" class="mui-slider">
        <div class="mui-slider-group mui-slider-loop">
            <div class="mui-slider-item"><img src="http://pic.myplas.com/upload/16/07/19/578d86ea86395.jpg"></div>
            <div class="mui-slider-item"><img src="http://pic.myplas.com/upload/16/07/19/578d86ea86395.jpg"></div>
            <div class="mui-slider-item"><img src="http://pic.myplas.com/upload/16/07/19/578d86ea86395.jpg"></div>
            <div class="mui-slider-item"><img src="http://pic.myplas.com/upload/16/07/19/578d86ea86395.jpg"></div>
        </div>
        <div class="mui-slider-indicator">
            <div class="mui-indicator mui-active"></div>
            <div class="mui-indicator"></div>
        </div>
    </div>
    <div class='appInfo'>
        <span v-for="(index,item) in items" :class="{'appInfoOn':index==itemIndex}" @click="itemsActive(index)">{{item}}</span>
        <div class='mui-clearfix'></div>
    <div id='appInfo' v-show="0==itemIndex">
        <ul id='appInfoUl'>
            <li v-for='info in infos'><a v-link={path:'/infodetail?id='+info.id}><font>{{info.title}}</font></a><b>{{info.input_time}}</b></li>
        </ul>
        <a class='appMore' v-link={name:'infolist'}>更多</a>
    </div>
    <div id='appInfo2' v-show="1==itemIndex">
        <ul id='appInfoUl2'>
            <li v-for='oil in oils'>{{oil.type}}<i class="{{oil.alph|upDownColor}}">{{oil.alph|upDown}}{{oil.ups_downs}}</i><b>{{oil.price}}</b><b style="margin-right: 10px;">{{oil.input_time}} </b></li>
        </ul>
        <a v-link={name:'oilprice'} class='appMore'>更多</a>
    </div>
    </div>
    <div class="appOperate">
        <ul>
            <li><div class="appOperateWrap">
                <a href="/mobi/mainPage/enReleaseSale"><img width="22" src="../../img/icon.png"><br>发布报价</a>
            </div></li>
            <li><div class="appOperateWrap">
                <a href="/mobi/mainPage/enPurchase"><img width="22" src="../../img/icon2.png"><br>委托采购</a>
            </div></li>
            <li><div class="appOperateWrap">
                <a href="/mobi/mainPage/enSupply"><img width="22" src="../../img/icon3.png"><br>供求</a>
            </div></li>
            <li><div class="appOperateWrap">
                <a href="/mobi/mainPage/enResource"><img width="22" src="../../img/icon4.png"><br>资源库</a>
            </div></li>
        </ul>
    </div>
    <footer id='footer'>
        <ul>
            <li><a><i class='foot'></i><br>大客户专区</a></li>
            <li><a href='/mobi/mainPage/enPhysical'><i class='foot2'></i><br>物性表</a></li>
            <li><a class='footerOn'><i class='foot3'></i><br>首页</a></li>
            <li><a v-link={name:'infolist'}><i class='foot4'></i><br>资讯</a></li>
            <li><a v-link={name:'melogged'}><i class='foot5'></i><br>我</a></li>
        </ul>
    </footer>
</template>
<script>
	module.exports={
		el:"#app",
		data:function(){
            return {
                oils:[],
                infos:[],
                itemIndex:0,
                items:['今日头条','原油价格']
            }
       },
		ready:function () {
            var slider = mui("#slider");
            slider.slider({
                interval: 3000
            });
            console.log(this.$route.path);
            this.$http.post('/mobi/mainPage/getMainPage',{type:2}).then(function(res){
            	console.log(res.json());
                this.$set('oils',res.json().infos);
            },function(){

            });
            this.$http.post('/mobi/mainPage/getMainPage',{type:1}).then(function(res){
            	console.log(res.json());
                this.$set('infos',res.json().infos);
            },function(){

            })
        },
        methods:{
            itemsActive:function (index) {
                this.itemIndex=index ? index : 0;
            }
        }
	}
</script>