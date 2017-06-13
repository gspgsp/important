<template>
  <div id="app">
 <Leftmodel></Leftmodel>
 <!--address-list begin-->
 <Centermodel></Centermodel>
    <!--default-bot end-->
 <div class="right flt">
<div class="summary w96">
    <div class="pic flt">
    	<img v-bind:src="thumb">
            <div class="authen yes">V</div>
    </div>
    <div class="info-person flt">
    	<p class="name">{{name}}<span>{{sex}}</span></p>
        <p>联系电话：{{mobile}}</p>
        <p>
        	<span>发布供给：<b>{{sale}}</b></span>
            <span>发布需求：<b>{{buy}}</b></span>
        </p>
    </div>
    <div class="opt yes">
    	<a v-on:click="pay">{{status}}</a>
    </div>
</div>
<!--summary end-->
<!--company-info begin-->
<div class="company-info triangle w96">
    <ul class="flt"style="line-height: 32px;">
    	<li>公司：{{c_name}}</li>
        <li>地址：{{address}}</li>
        <li v-if="type==='0'||type==='2'" >我的主营：{{need_product}}</li>
        <li v-if="type==='3'||type==='1'" >我的需求：{{need_product}}</li>
        <li v-if="type==='3'||type==='1'" >生产产品：{{main_product}}</li>
        <li v-if="type==='3'||type==='1'" >月用量：{{month_consum}}</li>
    </ul>
    <div class="card frt" v-on:click="cardcheck">
	<!--<img style="height:auto;width:auto;" v-bind:src="cardImg">-->
	<img style="height:auto;width:auto;" v-bind:src="cardImg" onerror="this.src='http://pic.myplas.com/mypc/img/pic2.png'"/></div>
    <span class="point point1"></span>
</div>
<!--company-info end-->
<!--buy begin-->
<div class="buy-sell buy w96">
	<div class="title">
    	<h3>最近求购信息</h3>
		<span hidden="hidden"id="buyid">{{lid}}</span>
        <a id="c_1"style="cursor:pointer;">查看更多>></a>
    </div>
    <ul>
    	<li v-for="b in buylist">
        	<span>{{b.input_time}}</span>
            <p><b>求购</b>：{{b.contents}}</p>
        </li>
    </ul>
</div>
<!--buy end-->
<!--buy begin-->
<div class="buy-sell sell w96">
	<div class="title">
    	<h3>最近供给信息</h3>
		<span hidden="hidden"id="saleid">{{lid}}</span>
        <a id="c_2"style="cursor:pointer;">查看更多>></a>
    </div>
    <ul>
    	<li v-for="s in supplylist">
        	<span>{{s.input_time}}</span>
            <p><b>供给</b>：{{s.contents}}</p>
        </li>
    </ul>
</div>
    </div>
    <!--default-bot end-->
</div>
<!--default end-->
</div>
  </div>
<!--default end-->

  </div>
</template>
<style type="text/css">
.layui-layer-btn{ text-align:center;}
</style>
<script>
import Leftmodel from "../../components/Leftmodel";
import Centermodel from "../../components/Centermodel";
export default {
  name: 'app',
  components: {
		'Leftmodel': Leftmodel,
		'Centermodel': Centermodel
	},
	data: function() {
        return {
            name: "",
            buy: "",
            sale: "",
            c_name: "",
            mobile: "",
            address: "",
            sex: "",
            status: "",
            thumb: "",
            need_product: "",
            id: "",
            avatorCheck: false,
            cardCheck: false,
            user_id: "",
            content: "",
            is_pass: "",
            cardImg: "",
            mobile2: "",
            type: "",
            main_product: "",
            month_consum: "",
            buylist: [],
            supplylist: [],
            loadingShow: "",
			lid:""
        }
    },
    methods: {
        cancel: function() {
            this.show = false;
        },
        check: function() {
            this.avatorCheck == true ? this.avatorCheck = false : this.avatorCheck = true;
        },
        cardcheck: function() {
            this.cardCheck == true ? this.cardCheck = false : this.cardCheck = true;
        },
        pay: function() {
            var _this = this;
            $.ajax({
                url:  version +"/friend/focusOrCancel",
                type: 'post',
                data: {
                    focused_id: _this.lid,
                    token: window.localStorage.getItem("token")
                },
                headers: {
                    'X-UA': window.localStorage.getItem("XUA")
                },
                dataType: 'JSON'
            }).then(function(res) {
                window.location.reload();
            }, function() {

            });
        }
    },
     beforeRouteEnter: function(to, from, next) {
        next(function(vm) {
            vm.loadingShow = true;
        });
    },
    mounted: function() {
        var _this = this;
		var url = window.location.search;
	    var lid = url.substring(url.lastIndexOf('=')+1, url.length);
		_this.lid = lid;
        console.log(_this);
        window.scrollTo(0, 0);
        try {
            var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
            piwikTracker.trackPageView();
        } catch(err) {

        }
        $.ajax({
            url:  version +"/friend/getZoneFriend",
            type: 'post',
            data: {
                user_id: lid,
                showType: 1,
                token: window.localStorage.getItem("token")
            },
            headers: {
                'X-UA': window.localStorage.getItem("XUA")
            },
            dataType: 'JSON'
        }).done(function(res) {
            if(res.err == 0) {
                _this.name = res.data.name;
                _this.c_name = res.data.c_name;
                _this.address = res.data.address;
                _this.mobile = res.data.mobile;
                _this.mobile2 = "tel:" + res.data.mobile;
                _this.need_product = res.data.need_product;
                _this.status = res.data.status;
                _this.thumb = res.data.thumb;
                _this.buy = res.data.buy;
                _this.sale = res.data.sale;
                _this.sex = res.data.sex;
                _this.id = res.data.user_id;
                _this.is_pass = res.data.is_pass;
                _this.cardImg = res.data.thumbcard;
                _this.type = res.data.type;
                _this.main_product = res.data.main_product;
                _this.month_consum = res.data.month_consum;
                if(_this.mobile.indexOf("*") == "-1") {
                    _this.isMobile = true;
                } else {
                    _this.isMobile = false;
                }
            } else if(res.err == 1) {
                layer.open({
                     title: ["塑料圈通讯录", "text-align:center"],
								offset : "28%",
								icon : 5,
								content : res.msg,
								btnAlign: 'c',
									   anim : 2,
									   yes : function () {
									location.href = "/mypczone/index/login";		
								}
								   });
            } else if(res.err == 99) {
                layer.confirm(res.msg,{icon: 3, title:['塑料圈通讯录','text-align:center;']}, function(m) {
                    $.ajax({
                        url: version +"/friend/getZoneFriend",
                        type: 'post',
                        data: {
                            user_id: lid,
                            showType: 5,
                            token: window.localStorage.getItem("token")
                        },
                        headers: {
                            'X-UA': window.localStorage.getItem("XUA")
                        },
                        dataType: 'JSON'
                    }).then(function(res) {
                        console.log(res);
                        if(res.err == 0) {
                            _this.name = res.data.name;
                            _this.c_name = res.data.c_name;
                            _this.address = res.data.address;
                            _this.mobile = res.data.mobile;
                            _this.mobile2 = "tel:" + res.data.mobile;
                            _this.need_product = res.data.need_product;
                            _this.status = res.data.status;
                            _this.thumb = res.data.thumb;
                            _this.buy = res.data.buy;
                            _this.sale = res.data.sale;
                            _this.sex = res.data.sex;
                            _this.id = res.data.user_id;
                            _this.is_pass = res.data.is_pass;
                            _this.type = res.data.type;
                            _this.main_product = res.data.main_product;
                            _this.month_consum = res.data.month_consum;
                            _this.cardImg = res.data.thumbcard;
                            if(_this.mobile.indexOf("*") == "-1") {
                                _this.isMobile = true;
                            } else {
                                _this.isMobile = false;
                            }
                        } else if(res.err == 100) {
                            layer.open({
                            title: ["塑料圈通讯录", "text-align:center"],
								offset : "28%",
								icon : 5,
								content : res.msg,
								btnAlign: 'c',
									   anim : 2,
									   yes : function () {
									location.href = "/mypczone/index";		
								}
								   });
                        }
                    }, function() {
					
                    });
                layer.close(m);
                }, function() {
                    window.history.back();
                }, {
                    title: '塑料圈通讯录'
                });
            }
        }).fail(function() {

        }).always(function() {
            _this.loadingShow = false;
        });

        $.ajax({
            url: version +"/friend/getTaPur",
            type: 'post',
            data: {
                userid: lid,
                page: 1,
                size: 5,
                type: 1,
                token: window.localStorage.getItem("token")
            },
            headers: {
                'X-UA': window.localStorage.getItem("XUA")
            },
            dataType: 'JSON'
        }).then(function(res) {
            if(res.err == 0) {
                _this.buylist = res.data;
            } else if(res.err == 1) {

            } else if(res.err == 2) {
                _this.buylist = [];
            }
        }, function() {

        });

        $.ajax({
            url:  version +"/friend/getTaPur",
            type: 'post',
            data: {
                userid: lid,
                page: 1,
                size: 5,
                type: 2,
                token: window.localStorage.getItem("token")
            },
            headers: {
                'X-UA': window.localStorage.getItem("XUA")
            },
            dataType: 'JSON'
        }).then(function(res) {
            if(res.err == 0) {
                _this.supplylist = res.data;
            } else if(res.err == 1) {

            } else if(res.err == 2) {
                _this.supplylist = [];
            }
        }, function() {

        });
    }
}
$(document).ready(function(){

})
$('#c_1').live('click',function(){
    var user_id = document.getElementById("buyid").innerText;
    //alert(user_id);
    location.href="/mypczone/index/infobuy?id="+user_id;
});
$('#c_2').live('click',function(){
    var user_id = document.getElementById("saleid").innerText;
    //alert(user_id);
    location.href="/mypczone/index/infosale?id="+user_id;
}); 
</script>
