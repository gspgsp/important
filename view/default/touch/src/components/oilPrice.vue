<template>
    <header id="infoHeader">
        <a class="back" href="javascript:window.history.back();"></a>
        原油价格
    </header>
    <ul id="infoOil">
        <li v-for='oil in oilInfo'>{{oil.type}}<i class="{{oil.alph|upDownColor}}">{{oil.alph|upDown}}{{oil.ups_downs}}</i><b>{{oil.price}}</b><b style="margin-right: 10px;">{{oil.input_time}} </b></li>
    </ul>
    <footer id="footer">
        <ul>
            <li><a v-link={name:'bigCustomer'}><i class="foot"></i><br>大客户专区</a></li>
            <li><a href="/mobi/mainPage/enPhysical"><i class="foot2"></i><br>物性表</a></li>
            <li><a v-link={name:'index'}><i class="foot3"></i><br>首页</a></li>
            <li><a v-link={name:'infolist'}><i class="foot4"></i><br>资讯</a></li>
            <li><a href="/mobi/personalCenter"><i class="foot5"></i><br>我</a></li>
        </ul>
    </footer>
</template>
<script>
	module.exports={
        el:"#app",
        data:function () {
            return {
                oilInfo:[]
            }
        },
        ready:function () {
            this.$http({url:'/mobi/mainPage/getMoreDyPrice',method:"POST",data:{}}).then(function (res) {
                console.log(res.data.dyprice);
                this.$set('oilInfo',res.data.dyprice);
            },function (res) {

            });

        }
	}
</script>