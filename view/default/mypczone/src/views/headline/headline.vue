<template>
    <div id="app">
            <Leftmodel></Leftmodel>
            <MiddleView></MiddleView>
            <div class="right flt">
                <!--headline-fixed begin-->
                <div class="headline-fixed">
                        <!--headline-head begin-->
                        <div class="headline-head">
                          <h3>塑料头条</h3>
                            <!--search begin-->
                            <div class="search">
                              <!--inner begin-->
                                <div class="inner">
                                    <!-- <form name="" action="" method="post" onsubmit="return false;"> -->
                                    <form action="javascript:;">
                                      <input type="text" class="import" placeholder="搜你想搜的" v-model="keywords" v-on:keydown.enter="search"/>
                                        <input type="submit" class="submit" value="搜索" v-on:click="search"/>
                                    </form>
                                </div>
                                <!--inner end-->
                            </div>
                            <!--search end-->
                        </div>
                        <!--headline-head end-->
                        <!--headline-tab begin-->
                        <div class="headline-tab">
                          <ul class="w96">
                                <li v-on:click="tabs(22)" v-bind:class="{hover:isHover1}">推荐</li>
                                <li v-on:click="tabs(12)" v-bind:class="{hover:isHover2}">塑料上游</li>
                                <li v-on:click="tabs(3)"   v-bind:class="{hover:isHover3}">早盘预报</li>
                                <li v-on:click="tabs(4)"   v-bind:class="{hover:isHover4}">企业动态</li>
                                <li v-on:click="tabs(5)"   v-bind:class="{hover:isHover5}">中晨塑说</li>
                                <li v-bind:class="{hover:isHover6}">
                                    <select v-on:change="onSelectChange" v-model="selected">
                                        <option :value="25">美金市场</option>
                                        <option :value="26">期货资讯</option>
                                        <option :value="27">装置动态</option>
                                        <option :value="28">期刊报告</option>
                                        <option :value="29">独家解读</option>
                                    </select>
                                </li>
                            </ul>
                        </div>
                        <!--headline-tab end-->
                </div>
                <!--headline-fixed end-->
                <!--headline-list begin-->
                <div class="headline-list">
                    <ul>
                        <li v-for="item in items">
                            <h4><a href="javascript:;" v-on:click="moveToDetail(item.id)">{{item.title}}</a></h4>
                            <p><a href="javascript:;" v-on:click="moveToDetail(item.id)">{{item.description}}</a></p>
                            <span>{{item.input_time}}</span>
                        </li>
                    </ul>
                </div>
                <div class="headline-next">
                        <p><a href="javascript:;" v-on:click="nextPage(page,cate_id)">下一页</a></p>
                </div>
            </div>
    </div>
</template>
<script>
import Leftmodel from "../../components/leftmodel";
import MiddleView from "../../components/middleView";
export default {
    name: 'app',
    components: {
        'Leftmodel': Leftmodel,
        'MiddleView':MiddleView
    },
    data: function() {
        return {
                items: [],
                selected:'25',
                cate_id:22,
                page:1,
                isHover1:true,
                isHover2:false,
                isHover3:false,
                isHover4:false,
                isHover5:false,
                isHover6:false
            }
    },
    methods: {
        tabs: function(id){
            var _this = this;
            _this.cate_id = id;
            _this.page = 1;
            switch( _this.cate_id ){
                case 22 :
                    this.isHover1 = true;
                    this.isHover2 = false;
                    this.isHover3 = false;
                    this.isHover4 = false;
                    this.isHover5 = false;
                    this.isHover6 = false;
                    break;
                case 12 :
                    this.isHover1 = false;
                    this.isHover2 = true;
                    this.isHover3 = false;
                    this.isHover4 = false;
                    this.isHover5 = false;
                    this.isHover6 = false;
                    break;
                case 3 :
                    this.isHover1 = false;
                    this.isHover2 = false;
                    this.isHover3 = true;
                    this.isHover4 = false;
                    this.isHover5 = false;
                    this.isHover6 = false;
                    break;
                case 4 :
                    this.isHover1 = false;
                    this.isHover2 = false;
                    this.isHover3 = false;
                    this.isHover4 = true;
                    this.isHover5 = false;
                    this.isHover6 = false;
                    break;
                case 5 :
                    this.isHover1 = false;
                    this.isHover2 = false;
                    this.isHover3 = false;
                    this.isHover4 = false;
                    this.isHover5 = true;
                    this.isHover6 = false;
                    break;
            }
            $.ajax({
            type: "post",
            url: '/qapi_3/toutiao/getCateList',
            data: {
                token: window.localStorage.getItem("token"),
                page: 1,
                sixe: 10,
                cate_id:id
            },
            headers: {
                'X-UA': window.localStorage.getItem("XUA")
            },
            dataType: 'JSON'
        }).done(function(res) {
            if(res.err == 0) {
                console.log(res);
                _this.items = res.info;
            } else {

            }
        }).fail(function() {

        }).always(function() {

        });
        },
    onSelectChange: function(){
            var _this = this;
            var id = _this.selected;
            _this.cate_id = id;
            this.isHover1 = false;
            this.isHover2 = false;
            this.isHover3 = false;
            this.isHover4 = false;
            this.isHover5 = false;
            this.isHover6 = true;

            $.ajax({
            type: "post",
            url: '/qapi_3/toutiao/getCateList',
            data: {
                token: window.localStorage.getItem("token"),
                page: 1,
                sixe: 10,
                cate_id:id
            },
            headers: {
                'X-UA': window.localStorage.getItem("XUA")
            },
            dataType: 'JSON'
        }).done(function(res) {
            if(res.err == 0) {
                console.log(res);
                _this.items = res.info;
            } else {

            }
        }).fail(function() {

        }).always(function() {

        });
        },
    search: function() {
        var _this = this;
        if(this.keywords) {
            $.ajax({
                url: '/qapi_3/toutiao/getSubscribe',
                type: 'post',
                data: {
                    keywords: _this.keywords,
                    page: 1,
                    subscribe: 1,
                    token: window.localStorage.getItem("token"),
                },
                headers: {
                'X-UA': window.localStorage.getItem("XUA")
            },
                dataType: 'JSON'
                }).done(function(res) {
                    if(res.err == 0) {
                        _this.items = res.data.slice(0, 3);
                        console.log(_this.items);
                    }else if(res.err == 2){
                            alert('没有相关数据');
                    }
                }).fail(function(){

                }).always(function(){

                });
            } else {

            }
        },
    moveToDetail: function(id){
        window.location.href = "/mypczone/index/headline2?id="+id;//本地路径
        },
    nextPage: function(page,cate_id){
        var _this = this;
        _this.page = page + 1;
        $.ajax({
            type: "post",
            url: '/qapi_3/toutiao/getCateList',
            data: {
                token: window.localStorage.getItem("token"),
                page: _this.page,
                sixe: 10,
                cate_id:cate_id
            },
            headers: {
                'X-UA': window.localStorage.getItem("XUA")
            },
            dataType: 'JSON'
        }).done(function(res) {
            if(res.err == 0) {
                console.log(res);
                _this.items = _this.items.concat(res.info);
            } else {

            }
        }).fail(function() {

        }).always(function() {

        });
    },
    },
    mounted: function(){
        var left = $( ".left" ),
                left2 = $( "#left2" ),
                center = $( ".center" ),
                right = $( ".right" ),
                html = $( "html" ),
                index = 0,
                h = $( document ).height();
            
            //设置各个部分的高度
            left.height( h );
            center.height( h );
            right.height( h );
            html.height( h );

            //设置右侧的宽度
            setW( left, center, right );
            
            $( window ).resize( function () {
                setW( left, center, right );
            } );
            
            
            //设置右侧的宽度
            function setW ( gid1, gid2, elem, index ) {
                index = index || 0;
                var w = ( index === 1 ) ? $( document ).width() - gid1.width() : $( document ).width() - gid1.width() - gid2.width();
                elem.width( w );
            };

        var _this = this;
        $.ajax({
            type: "post",
            url: '/qapi_3/toutiao/getCateList',
            data: {
                token: window.localStorage.getItem("token"),
                page: 1,
                sixe: 10,
                cate_id:22
            },
            headers: {
                'X-UA': window.localStorage.getItem("XUA")
            },
            dataType: 'JSON'
        }).done(function(res) {
            if(res.err == 0) {
                console.log(res);
                _this.items = res.info;
            } else {

            }
        }).fail(function() {

        }).always(function() {

        });
    }


}
</script>

