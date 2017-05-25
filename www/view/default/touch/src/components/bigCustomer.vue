<template>
    <header id="bigCustomerHeader">
        <a class="back" href="javascript:window.history.back();"></a>
        大客户专区
        <span class="appMenu"></span>
    </header>
    <div class="bigCustomerMenu" style="-display:none;">
        <h3 class="bigCustomerMenuTitle">
            公司
            <!--<i class="icon2 iconUp iconDown"></i>-->
            <span id="gsBtn">全部</span>
        </h3>
        <p id="gs">
            <span v-for="gs in choseData.company">{{gs.gsname}}</span>
        </p>
        <h3 class="bigCustomerMenuTitle">
            厂家
            <!--<i class="icon2 iconUp iconDown"></i>-->
            <span id="cjBtn">全部</span>
        </h3>
        <p id="cj">
            <span v-for="cj in choseData.factory">{{cj.factory}}</span>
        </p>
        <h3 class="bigCustomerMenuTitle">
            交货地
            <!--<i class="icon2 iconUp iconDown"></i>-->
            <span id="jhdBtn">全部</span>
        </h3>
        <p id="jhd">
            <span v-for="jhd in choseData.address">{{jhd.address}}</span>
        </p>
        <div style="text-align: center; padding: 20px 0;">
            <a id="chooseBtn" class="classifyEnter" href="javascript:;">确定</a>
        </div>
    </div>
    <div class="supplyDemandStatus">
        <span data-number="3" class="on">默认</span>
        <span id="sale" data-big="true">价格</span>
    </div>
    <div class="supplyDemandUl">
        <ul id="searchWrapUl">
            <li v-for="(index,big) in largrBid" @click="itemsActive(index)">
                <h3 class='bigCustomerName'><a href='#'>{{big.type}}/{{big.model}}<br>{{big.factory}}</a></h3>
                <p class='supplyDemandStore'><i class='icon iconDown' @click="toggle()"></i></p>
                <p class='supplyDemandSales'><span>￥{{big.price}}/T起</span><br>{{big.input_time}}</p>
                <div class='mui-clearfix'></div>
                <table class='supplyDemandTb' v-show="index==itemIndex" cellpadding='0' cellspacing='0'>
                    <tr><th width='35%'>供应商</th><th width='20%'>价格(元/吨)</th><th width='10%'>数量</th><th width='15%'>交货地</th><th width='20%'>操作</th></tr>
                    <tr v-for="newBig in big.newBig">
                        <td>{{newBig.gsname}}</td><td>￥{{newBig.price}}</td>
                        <td>{{newBig.num}}T</td><td>{{newBig.address}}</td>
                        <td><a href='/mobi/mainPage/enBigBidDetail?id="+v.id+"'>查看</a></td>
                    </tr>
                </table>
            </li>
        </ul>
    </div>
    <footer id="footer">
        <ul>
            <li><a class="footerOn" v-link={name:'bigCustomer'}><i class="foot"></i><br>大客户专区</a></li>
            <li><a href="/mobi/mainPage/enPhysical"><i class="foot2"></i><br>物性表</a></li>
            <li><a v-link={name:'index'}><i class="foot3"></i><br>首页</a></li>
            <li><a v-link={name:'infolist'}><i class="foot4"></i><br>资讯</a></li>
            <li><a v-link={name:'meLogged'}><i class="foot5"></i><br>我</a></li>
        </ul>
    </footer>
</template>
<script>
	module.exports={
        el:"#app",
        data:function () {
            return {
                largrBid:[],
                choseData:[],
                open:false,
                itemIndex:-1
            }
        },
        methods:{
            itemsActive:function (index) {
                this.itemIndex=index ? index : 0;
            },
            toggle:function () {

            }
        },
        ready:function () {
            this.$http({url:'/mobi/mainPage/getLargeBid',method:"POST",data:{otype:3}}).then(function (res) {
                console.log(res);
                this.$set('largrBid',res.data.largrBid);
            },function (res) {

            });

            this.$http({url:'/mobi/mainPage/getLargeChose',method:"POST",data:{}}).then(function (res) {
                console.log(res);
                this.$set('choseData',res.data.choseData);
            },function (res) {

            });

        }
    
	}
</script>