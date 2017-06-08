<template>
    <div id="app">
        <Leftmodel></Leftmodel>
        <MiddleView></MiddleView>
        <div class="right flt">
            <div class="back">查别人</div>
            <!--back end-->
            <!--credit1 begin-->
            <div class="credit1 w96">
                <h3><img src="http://pic.myplas.com/mypc/img/discovery2.png" width="80" height="80"/></h3>
                <h4>热烈庆祝{{c_name}}</h4>
                <h5>获得信用<span>{{credit_level}}</span>级客户称号/预计获得<span>{{credit_limit}}</span>万授信额度</h5>
                <p>经“我的塑料网”塑料电商交易平台信用认证，贵司企业信用良好，为{{credit_level}}级，预计授信额度：{{credit_limit}}万元人民币。特发此证，以兹鼓励！</p>
                <!--certificate begin-->
                <div class="certificate">
                    <div class="title">{{c_name}}</div>
                    <div class="con">经我司评定，确认贵单位为二０一七年度信用{{credit_level}}级客户，预授信额度{{credit_limit}}万人民币，有效期一年。</div>
                </div>
                <!--certificate end-->
                <a href="javascript:;" v-on:click="toCreditintro">？授信说明</a>
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
            c_name:"",
            credit_level:"",
            credit_limit:"",
            user_id:"",
            share: false,
            share3: false,
            is_credit:"",
            creditshow:true,
            msg:""
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
        toCreditintro: function() {
            window.location.href = "/mypczone/index/creditIntro";
        }
    },
    mounted: function() {
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
            var _this=this;
            var link_id = _this.getParam('link_id');
            // window.localStorage.setItem("XUA","pc|5.5|3858|3bf198c15c2b3b98bd41832df8445a89|0|"+navigator.platform+"|"+navigator.platform+"|"+navigator.platform+"|"+navigator.appName+"|"+navigator.appCodeName+"|0|0|0");
            $.ajax({
                type: "post",
                url: "/qapi_3/credit/creditCertificate",
                data: {
                    link_id: link_id
                },
                headers: {
                'X-UA': window.localStorage.getItem("XUA")
                },
                dataType: 'JSON'
            }).done(function(res) {
                if(res.err==0){
                    _this.creditshow=true;
                    _this.c_name=res.data.c_name;
                    _this.credit_level=res.data.credit_level;
                    _this.credit_limit=res.data.credit_limit/10000;
                    _this.user_id=res.data.user_id;
                    _this.is_credit=res.data.is_credit;
                }else if(res.err==2){
                    _this.creditshow=false;
                    _this.msg=res.msg;   
                    
                }
            }).fail(function(){
                
            }).always(function(){
                
            });
    }
}
</script>

