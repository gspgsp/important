<template>
    <div id="app">
        <leftmodel></leftmodel>
        <myView></myView>
        <div class="right">
            <div class="back">塑豆商城</div>
                <!--back end-->
                <div class="blank"></div>
                <!--mall-ad begin-->
                <div class="mall-ad">
                    <img src="http://statics.myplas.com/myapp/img/shopBanner.jpg" width="520" height="162"/>
                </div>
                <!--mall-ad end-->
                <!--mall-link begin-->
                <div class="mall-link">
                    <ul>
                        <li class="mall-link1"><a href="javascript:;"><span>{{points}}</span>塑豆</a></li>
                        <li class="mall-link2"><a href="javascript:;">如何赚塑豆</a></li>
                        <li class="mall-link3 last"><a href="javascript:;">充值塑豆</a></li>
                    </ul>
                </div>
                <!--mall-link end-->
                <div class="mall-info-title">商品信息</div>
                <!--mall-info begin-->
                <div class="mall-info">
                    <!--ware begin-->
                    <div class="ware">
                        <img v-bind:src="p1.thumb" width="150" height="150"/>
                        <!--explain begin-->
                        <div class="explain flt">
                            <p class="name">{{p1.name}}</p>
                            <p class="need">所需塑豆：<span>{{p1.points}}塑豆</span></p>
                        </div>
                        <!--explain end-->
                        <div class="other flt">X {{count1}}</div>
                    </div>
                    <!--ware end-->
                    <!--amount begin-->
                    <div class="amount">
                        <!--name begin-->
                        <div class="name flt"><span>*</span>请选择兑换数量</div>
                        <!--name end-->
                        <!--change begin-->
                        <div class="change frt">
                            <p class="reduce" v-on:click="incrementData(-1,1)">-</p>
                            <p class="num">{{count1}}</p>
                            <p class="add" v-on:click="incrementData(1,1)">+</p>
                        </div>
                        <!--change end-->
                    </div>
                    <!--amount end-->
                    <!--opt begin-->
                    <div class="opt">
                        <!--total begin-->
                        <div class="total flt">共<span>{{count1}}</span>件</div>
                        <!--total end-->
                        <!--use begin-->
                        <div class="use flt">总塑豆：{{count1 * p1.points}}</div>
                        <!--use end-->
                        <button>提交兑换</button>
                    </div>
                    <!--opt end-->
                </div>
                <!--mall-info end-->
                <!--mall-info begin-->
                <div class="mall-info">
                    <!--ware begin-->
                    <div class="ware">
                        <img v-bind:src="p2.thumb" width="150" height="150"/>
                        <!--explain begin-->
                        <div class="explain flt">
                            <p class="name">{{p2.name}}</p>
                            <p class="need">所需塑豆：<span>{{p2.points}}塑豆</span></p>
                        </div>
                        <!--explain end-->
                        <div class="other flt">X {{count2}}</div>
                    </div>
                    <!--ware end-->
                    <!--amount begin-->
                    <div class="amount">
                        <!--name begin-->
                        <div class="name flt"><span>*</span>请选择兑换数量</div>
                        <!--name end-->
                        <!--change begin-->
                        <div class="change frt">
                            <p class="reduce" v-on:click="incrementData(-1,2)">-</p>
                            <p class="num">{{count2}}</p>
                            <p class="add" v-on:click="incrementData(1,2)">+</p>
                        </div>
                        <!--change end-->
                    </div>
                    <!--amount end-->
                    <!--tip begin-->
                    <div class="tip"><span>*</span>请选择要置顶的供求信息（限选一条）：</div>
                    <!--tip end-->
                    <!--set-top begin-->
                    <div class="set-top">
                        <ul>
                            <li v-for="t in releaseTxt">
                                <!--sel begin-->
                                <div class="sel flt" v-on:click="choseOne(t.id)" v-bind:class="{'yes':t.id == choseData[0]}"></div>
                                <!--sel end-->
                                <!--info begin-->
                                <div class="info flt">
                                    <p class="time">{{t.input_time}}</p>
                                    <p class="buy"><span v-if="t.type == 1">求购：</span><span v-else-if="t.type == 2">供给：</span>{{t.contents}}</p>
                                </div>
                                <!--info end-->
                            </li>
                        </ul>
                    </div>
                    <!--set-top end-->
                    <!--opt begin-->
                    <div class="opt">
                        <!--total begin-->
                        <div class="total flt">共<span>{{count2}}</span>件</div>
                        <!--total end-->
                        <!--use begin-->
                        <div class="use flt">总塑豆：{{p2.points * count2}}</div>
                        <!--use end-->
                        <button>提交兑换</button>
                    </div>
                    <!--opt end-->
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
        p1: "",
        p2: "",
        points: 0,
        pro: {
            id: "",
            cost: 100
        },
        pro2: {
            id: "",
            cost: 100
        },
        selected: "",
        releaseTxt:[],
        selectedTxt:"",
        
        currentMonth: 1,
        currentYear: 1970,
        currentMonth2: 1,
        currentYear2: 1970,
        days: [],
        days2:[],
        daySelected:[],
        dateShow:false,
        
        currentMonth_: 1,
        currentYear_: 1970,
        currentMonth2_: 1,
        currentYear2_: 1970,
        days_: [],
        days2_:[],
        daySelected2:[],
        dateShow2:false,
        releaseShow:false,
        count1:1,
        count2:1,
        choseData:[]
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
        incrementData: function(res,type){
            var _this = this;
            if(res < 0 && type == 1){
                if(_this.count1 == 1){
                    alert('至少有一件商品');
                    return false;
                }else{
                    _this.count1 -=1;
                }
            }else if(res > 0 && type == 1){
                if(_this.count1 == 30){
                    alert('商品数量达到上限');
                    return false;
                }else{
                    _this.count1 +=1;
                }
            }else if(res < 0 && type == 2){
                if(_this.count2 == 1){
                    alert('至少有一件商品');
                    return false;
                }else{
                    _this.count2 -=1;
                }
            }else if(res > 0 && type == 2){
                if(_this.count2 == 30){
                    alert('商品数量达到上限');
                    return false;
                }else{
                    _this.count2 +=1;
                }
            }
        },
        choseOne: function(choseid){
            var _this = this;
                if(_this.choseData[0] == choseid){
                    _this.choseData.pop();
                }else{
                    _this.choseData.pop();
                    _this.choseData.push(choseid);
                }
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
            h = $( window ).height();
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
                url:'/qapi_3/product/getProductList',
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
                        _this.p1 = res.info[0];
                        _this.p2 = res.info[1];
                        _this.releaseTxt=res.info[1].myMsg;
                        _this.pro.price = res.info[0].points;
                        _this.pro2.price = res.info[1].points;
                        _this.pro.id = res.info[0].id;
                        _this.pro2.id = res.info[1].id;
                        _this.points = res.pointsAll;
                        console.log(_this.releaseTxt);
                    }
                }, function() {

                });
    }
}
$(function(){
    $()
})
</script>

