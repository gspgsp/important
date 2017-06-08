<template>
    <div id="app">
        <Leftmodel></Leftmodel>
        <MiddleView></MiddleView>
        <div class="right flt">
        	<div class="back">查别人</div>
            <!--back end-->
            <!--credit3 begin-->
            <div class="credit3 w96">
                <img src="http://pic.myplas.com/mypc/img/discovery4.png" width="245" height="253"/>
                <h3>精准查询</h3>
                <p>企业名称查询</p>
                <p>自动关联企业相关数据</p>
                <!--search begin-->
                <div class="search">
                    <form name="" method="post" action="">
                        <input type="text" v-model="fname" class="import flt" placeholder="请输入企业全称"/>
                        <input type="button" class="submit frt" value="查授信额度" v-on:click="search"/>
                    </form>
                </div>
                <!--search end-->
                <!--results begin-->
                <div class="results">
                    <ul>
                        <li v-for="c in creditli">
                            <a href="javascript:;" v-on:click="getInfo(c.contact_id)">{{c.c_name}}</a>
                        </li>
                    </ul>
                </div>
                <!--results end-->
            </div>
        </div>
    </div>
</template>
<script>
import Leftmodel from "../../components/leftmodel";
import MiddleView from "../../components/middleView";
export default{
    name: 'app',
    components: {
        'Leftmodel': Leftmodel,
        'MiddleView':MiddleView
    },
    data: function() {
        return {
            fname:"",
            creditli:[]
        }
    },
    methods:{
        search:function(){
            var _this=this;
            // window.localStorage.setItem("XUA","pc|5.5|3858|3bf198c15c2b3b98bd41832df8445a89|0|"+navigator.platform+"|"+navigator.platform+"|"+navigator.platform+"|"+navigator.appName+"|"+navigator.appCodeName+"|0|0|0");
            $.ajax({
                type: "post",
                url: "/qapi_3/credit/creditCertificate",
                data: {
                    token: '3bf198c15c2b3b98bd41832df8445a89',
                    type:2,
                    page:1,
                    fname:_this.fname
                },
                headers: {
                    'X-UA': window.localStorage.getItem("XUA")
                },
                dataType: 'JSON'
            }).then(function(res) {
                if(res.err==0){
                    _this.creditli=res.data;            
                }
            });
        },
        getInfo: function(contact_id){
            window.location.href = "/mypczone/index/checkOther2?link_id="+contact_id;
        }
    },
    mounted: function() {
        this.creditli=[];
        this.fname="";
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
    }
}
</script>

