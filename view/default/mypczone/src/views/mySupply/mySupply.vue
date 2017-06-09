<template>
    <div id="app">
        <leftmodel></leftmodel>
        <myView></myView>
        <div class="right flt">
        	<div class="back" v-if="type == 2">我的供给</div>
            <div class="back" v-else>我的求购</div>
            <!--back end-->
            <div class="blank"></div>
            <!--my-wrap begin-->
            <div class="my-wrap w96 sell" v-show="condition" v-for="n in name">
                <!--my-wrap-top begin-->
                <div class="my-wrap-top">
                    <div class="w96">
                        <!--time begin-->
                        <p class="time"><b></b><span>{{n.input_time}}</span></p>
                        <!--time end-->
                        <!--info begin-->
                        <div class="info"><span v-if="type == 2">供给：</span><span v-else>求购：</span><p>{{n.contents}}</p></div>
                        <!--info end-->
                        <!--del begin-->
                        <p class="del" v-on:click="delData(n.id)"><b></b><span>删除</span></p>
                        <!--del end-->
                    </div>
                </div>
                <!--my-wrap-top end-->
                <!--my-wrap-bot begin-->
                <div class="my-wrap-bot">
                    <div class="w96">
                        <!--msg begin-->
                        <div class="msg">
                            <h3>系统消息</h3>
                            <p>在信息库中，没有找到卖（买）此牌号的商家！</p>
                            <p>参考价 ：<span>塑料圈查无此价格</span></p>
                        </div>
                        <!--msg end-->
                        <!--words begin-->
                        <div class="words">
                            <h3>塑料圈友</h3>
                            <ul v-if="n.says != ''">
                                <li v-for="n2 in n.says" v-if="n2.rev_id == n.user_id"><span>{{n2.user_name}}</span>说：{{n2.content}}</li>
                                <li v-for="n2 in n.says" v-if="n2.rev_id != n.user_id"><span>{{n2.user_name}}</span>回复<span>{{n2.rev_name}}</span>：{{n2.content}}</li>
                            </ul>
                            <ul v-else>暂无消息回复</ul>
                        </div>
                        <!--words end-->
                    </div>
                </div>
                <!--my-wrap-bot end-->
            </div>
            <!--my-wrap end-->
            <div v-show="!condition" style="text-align: center; height: 60px; line-height: 60px;">
        没有相关数据
            </div>
        </div>
    </div>
</template>
<script>
import Leftmodel from "../../components/leftmodel";
import myView from "../../components/myView";
export default{
    components: {
        'Leftmodel':Leftmodel,
        'myView': myView
    },
    data: function() {
        return {
        name: [],
        page: 1,
        condition: true,
        countShow: false,
        count: "",
        id: "",
        user_id: "",
        is_says:true,
        type:""
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
        delData: function(id){
            //
            $.ajax({
                    url: "/qapi_3/releaseMsg/deleteMyMsg",
                    type: 'post',
                    data: {
                        id: id,
                        token: window.localStorage.getItem("token")
                    },
                    headers: {
                        'X-UA': window.localStorage.getItem("XUA")
                    },
                    dataType: 'JSON'
                    }).then(function(res) {
                        if(res.err == 0) {
                            alert('删除成功');
                            window.location.reload();
                        }
                    }, function() {
    
                    });
            //
        }
    },
    mounted: function() {
        var _this = this;
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
            _this.type = _this.getParam('type');
            $.ajax({
                url: "/qapi_3/releaseMsg/getMyMsg",
                type: 'post',
                data: {
                    type: _this.type,
                    page: _this.page,
                    token: window.localStorage.getItem("token"),
                    size: 50
                },
                headers: {
                    'X-UA': window.localStorage.getItem("XUA")
                },
                dataType: 'JSON'
            }).then(function(res) {
                if(res.err == 2) {
                    _this.condition = false;
                } else if(res.err == 1) {
                        alert('请登录');
                    } else {
                        _this.condition = true;
                        _this.name = res.data;
                    }
                }, function() {

                });
    }
}
</script>

