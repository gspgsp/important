<template>
    <div id="app">
        <leftmodel></leftmodel>
        <myView></myView>
        <div class="right flt">
        	<div class="back">我的消息</div>
                <!--back end-->
                <div class="blank"></div>
                <!--my-list begin-->
                <div v-show="condition" class="my-msg w96" v-for="n in name"><p>{{n.content}}</p></div>
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
        condition: true
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
            $.ajax({
                url: "/qapi_3/myInfo/getRobotMsg",
                type: 'post',
                data: {
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

