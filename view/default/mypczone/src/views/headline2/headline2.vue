<template>
    <div id="app">
            <Leftmodel></Leftmodel>
            <MiddleView></MiddleView>
            <div class="right flt">
                <div class="back">
                    <a href="javascript:window.history.back();"></a>资讯详情
                </div>
                <!--back end-->
                <!--headline2-title begin-->
                <div class="headline2-title w96">
                    <h4>【{{type}}】{{title}}</h4>
                    <p><span>作者：{{author}}</span><span>阅读数量：{{pv}}</span><span>发布时间：{{time}}</span></p>
                </div> 
                <!--headline2-title end-->
                <!--headline2-con begin-->
                <div class="headline2-con w96">
                    <div v-html="content"></div>
                </div>
                <!--headline2-con end-->
                <!--headline2-other begin-->
                <div class="headline2-other w96">
                    <div class="prev-next">
                        <p class="prev flt"><a href="javascript:;" v-on:click="toPage(lastOne)">上一条</a></p>
                        <p class="next frt"><a href="javascript:;" v-on:click="toPage(nextOne)">下一条</a></p>
                    </div>
                    <!--prev-next end-->
                </div>
                <!--headline2-other end-->
                <!--headline2-hot begin-->
                <div class="headline2-hot w96">
                    <h4>热门追踪</h4>
                    <ul>
                        <li v-for="s in subscribe">
                            <a href="javascript:;" v-on:click="toPage(s.id)">【{{s.cate_name}}】{{s.title}}</a>{{s.input_time}}</li>
                    </ul>
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
            title: "",
            content: "",
            cate: "",
            id: "",
            cate_id: "",
            author: "",
            time: "",
            pv: "",
            type: "",
            subscribe: [],
            share: false,
            share3: false,
            share4: false,
            loadingShow: "",
            loadingHide: "",
            }
    },
    methods: {
        getParam: function(param){
          var reg = new RegExp("(^|&)" + param + "=([^&]*)(&|$)", "i"); 
          var r = window.location.search.substr(1).match(reg); //获取url中"?"符后的字符串并正则匹配
          var context = ""; 
          if (r != null) 
             context = r[2]; 
          reg = null; 
          r = null; 
          return context == null || context == "" || context == "undefined" ? "" : context;
        },
        toPage: function(id) {
        var _this = this;
        if(id) {
            $.ajax({
                type: "post",
                url: '/qapi_3/toutiao/getDetailInfo',
                timeout: 15000,
                data: {
                    // token: window.localStorage.getItem("token"),
                    id: id
                },
                headers: {
                'X-UA': window.localStorage.getItem("XUA")
                },
                dataType: 'JSON'
            }).done(function(res) {
                _this.id = res.info.id;
                _this.title = res.info.title;
                _this.cate_id = res.info.cate_id;
                _this.content = res.info.content;
                _this.time = res.info.input_time;
                _this.type = res.info.type;
                _this.pv = res.info.pv;
                _this.author = res.info.author;
                _this.lastOne = res.info.lastOne;
                _this.nextOne = res.info.nextOne;
                _this.subscribe = res.info.subscribe?res.info.subscribe.slice(0, 8):res.info.subscribe;
            }).fail(function() {

            }).always(function() {

            })
        }else{
            alert('没有相关数据');
        }
    }
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
        var id = _this.getParam("id");
        $.ajax({
            type: "post",
            url: '/qapi_3/toutiao/getDetailInfo',
            data: {
                // token: '36db9c73b98a81d566ba5922a5281854',
                id:id
            },
            headers: {
                'X-UA': window.localStorage.getItem("XUA")
            },
            dataType: 'JSON'
        }).done(function(res) {
            if(res.err == 0) {
                _this.id = res.info.id;
                _this.title = res.info.title;
                _this.cate_id = res.info.cate_id;
                _this.content = res.info.content;
                _this.time = res.info.input_time;
                _this.type = res.info.type;
                _this.pv = res.info.pv;
                _this.author = res.info.author;
                _this.lastOne = res.info.lastOne;
                _this.nextOne = res.info.nextOne;
                _this.subscribe = res.info.subscribe?res.info.subscribe.slice(0, 8):res.info.subscribe;
            } else {

            }
        }).fail(function() {

        }).always(function() {

        });
    }


}
</script>

