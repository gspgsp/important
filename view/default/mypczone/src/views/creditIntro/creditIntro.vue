<template>
    <div id="app">
            <Leftmodel></Leftmodel>
            <MiddleView></MiddleView>
            <div class="right flt">
        	   <div class="back"><a href="javascript:window.history.back();"></a>授信说明</div>
            <!--back end-->
            <!--credit2 begin-->
            <div class="credit2 w96">
                <h3>授信说明</h3>
                <p>本授信是[我的塑料网]针对网站的优质客户，提供的供应链金融业务授信，客户可以在授信额度范围内使用[我的塑料网]提供的供应链金融产品，满足企业的融资需求。</p>
                <h3>特点:</h3>
                <p>1.以便捷、高效的整体合作模式，使客户迅速得到全面和规范的供应链金融服务；</p>
                <p>2.节省客户的用款成本，简化工作手续，提高融资效率；</p>
                <p>3.基于真实订单需求，在整体信用额度的前提下，随借随用，即时到账；</p>
                <p>4.低利息、低费用。</p>
                <h3>适用客户:</h3>
                <p>企业信用良好、与[我的塑料网]订单成交三笔以上以及其他必要条件。</p>
                <h3>注：授信解释权归[我的塑料网]所有。</h3>
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
        toCreditintro: function() {
            var _this = this;
            window.location.href = '';
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
            // window.localStorage.setItem("XUA","pc|5.5|3858|3bf198c15c2b3b98bd41832df8445a89|0|"+navigator.platform+"|"+navigator.platform+"|"+navigator.platform+"|"+navigator.appName+"|"+navigator.appCodeName+"|0|0|0");
            $.ajax({
                type: "post",
                url: "/qapi_3/credit/creditCertificate",
                data: {
                    token: '3bf198c15c2b3b98bd41832df8445a89'
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

