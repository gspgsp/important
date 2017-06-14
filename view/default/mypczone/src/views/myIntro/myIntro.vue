<template>
    <div id="app">
        <leftmodel></leftmodel>
        <myView></myView>
        <div class="right flt">
        	<div class="back" v-if="type == 3">我的引荐</div>
            <div class="back" v-else-if="type == 1">我的粉丝</div>
            <div class="back" v-else-if="type == 2">我的关注</div>
                <!--back end-->
                <div class="blank"></div>
                <!--my-list begin-->
                <div class="my-list w96">
                    <ul v-if="type == 3">
                        <li v-show="condition" v-for="n in name">
                            <!--pic begin-->
                            <div class="pic flt">
                                <img v-bind:src="n.thumb">
                                <div class="authen" v-bind:class="{'yes':n.is_pass == 1,'no':n.is_pass == 0}">V</div>
                            </div>
                            <!--pic end-->
                            <!--info begin-->
                            <div class="info flt">
                                <p class="company">{{n.c_name}}</p>
                                <p class="mix">
                                    <span class="name">{{n.name}}</span>
                                    <span class="mobile">{{n.mobile}}</span>
                                </p>
                                <p class="num">发布供给：<span>{{n.sale}}条</span>发布求购：<span>{{n.buy}}条</span></p>
                            </div>
                            <!--info end-->
                        </li>
                        <li v-show="!condition" style="text-align: center;">没有相关数据</li>
                    </ul>
                    <ul v-else-if="type == 1">
                        <li v-show="condition" v-for="n in name">
                            <!--pic begin-->
                            <div class="pic flt">
                                <img v-bind:src="n.user_id.thumb">
                                <div class="authen" v-bind:class="{'yes':n.user_id.is_pass == 1,'no':n.user_id.is_pass == 0}">V</div>
                            </div>
                            <!--pic end-->
                            <!--info begin-->
                            <div class="info flt">
                                <p class="company">{{n.user_id.c_name}}</p>
                                <p class="mix">
                                    <span class="name">{{n.user_id.name}}</span>
                                    <span class="mobile">{{n.user_id.mobile}}</span>
                                </p>
                                <p class="num">发布供给：<span>{{n.user_id.sale}}条</span>发布求购：<span>{{n.user_id.buy}}条</span></p>
                            </div>
                            <!--info end-->
                        </li>
                        <li v-show="!condition" style="text-align: center;">没有相关数据</li>
                    </ul>
                    <ul v-else-if="type == 2">
                        <li v-show="condition" v-for="n in name">
                            <!--pic begin-->
                            <div class="pic flt">
                                <img v-bind:src="n.focused_id.thumb">
                                <div class="authen" v-bind:class="{'yes':n.focused_id.is_pass == 1,'no':n.focused_id.is_pass == 0}">V</div>
                            </div>
                            <!--pic end-->
                            <!--info begin-->
                            <div class="info flt">
                                <p class="company">{{n.focused_id.c_name}}</p>
                                <p class="mix">
                                    <span class="name">{{n.focused_id.name}}</span>
                                    <span class="mobile">{{n.focused_id.mobile}}</span>
                                </p>
                                <p class="num">发布供给：<span>{{n.focused_id.sale}}条</span>发布求购：<span>{{n.focused_id.buy}}条</span></p>
                            </div>
                            <!--info end-->
                        </li>
                        <li v-show="!condition" style="text-align: center;">没有相关数据</li>
                    </ul>
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
        type:"",
        url:""
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
        }
    },
    mounted: function() {
        var _this = this;
        _this.name = [];
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
            if(_this.type == 3){
                _this.url = "/qapi_3/myInfo/getMyIntroduction";
            }else if(_this.type == 1){
                _this.url = "/qapi_3/myInfo/getMyFuns";
            }else if(_this.type == 2){
                _this.url = "/qapi_3/myInfo/getMyFuns";
            }
            $.ajax({
                url: _this.url,
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

