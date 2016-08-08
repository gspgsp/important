<template>
    <header id='infoHeader'><a class='back' href='javascript:window.history.back();'></a>今日资讯</header>
    <nav id='nav'>
        <span v-for="(index,item) in items" :class="{'on':index==itemIndex}" @click="itemsActive(index)">{{item.name}}</span>
    </nav>
    <div id='articleWrap'>
    <ul id='articleLi'>
        <li v-for='info in infoUl'>
        <div class='articleLi'>
            <a v-link={path:'/infoDetail?id='+info.id}>
                <h3>{{info.title}}</h3><p>{{info.brief}}</p>
            </a>
        </div><p class='time'>{{info.input_time}}</p>
        </li>
    </ul>
    </div>
    <footer id='footer'>
        <ul>
            <li><a v-link={name:'bigCustomer'}><i class='foot'></i><br>大客户专区</a></li>
            <li><a href='/mobi/mainPage/enPhysical'><i class='foot2'></i><br>物性表</a></li>
            <li><a v-link={name:'index'}><i class='foot3'></i><br>首页</a></li>
            <li><a class='footerOn' v-link={name:'infolist'}><i class='foot4'></i><br>资讯</a></li>
            <li><a href='/mobi/personalCenter'><i class='foot5'></i><br>我</a></li></ul>
    </footer>
</template>
<script>
	module.exports={
		el:"#app"
        data:function () {
            return {
                itemIndex: 0,
                items: [{name: '市场点评'}, {name: '行业热点'}, {name: '企业调价'}, {name: '装置动态'}, {name: '塑料期货'}],
                pid:[29,30,31,32,33],
                infoUl:[]
            }
        },
        ready:function () {
            this.$http({url:'/mobi/mainPage/getArticleInfo',method:"POST",data:{pid:29}}).then(function (res) {
                console.log(res);
                this.$set('infoUl',res.data.articleInfo);
            },function (res) {

            });

        },
        methods:{
            itemsActive:function (index) {
                console.log(this.pid[index]);
                this.itemIndex=index ? index : 0;
                this.$http({url:'/mobi/mainPage/getArticleInfo',method:"POST",data:{pid:this.pid[index]}}).then(function (res) {
                    console.log(res);
                    this.$set('infoUl',res.data.articleInfo);
                },function (res) {

                });
            }
        }

	}
</script>