<template>
    <div id="app">
        <leftmodel></leftmodel>
        <myView></myView>
        <div class="right flt">
        	<div class="back">更改用户信息</div>
                <!--back end-->
                <div class="blank"></div>
                <!--change-person begin-->
                <div class="change-person w96">
                    <div style="width: 80px; height: 80px; position: relative; float: left; margin:0 15px 0 0;">
                        <div class="personAvator">
                            <input type="file" id="uploader" v-on:click="uploaderCard('uploader',1)" capture="camera" multiple="" style="width:80px; height: 80px; opacity: 0; position: absolute; top: 0; left: 0;">
                            <img width="80" height="80" v-bind:src="thumb">
                        </div>
                        <i class="photo"></i>
                    </div>
                    <!-- <div class="pic flt">
                        <img v-bind:src="thumb">
                        <input type="file" name="upFile" id="uploaderCard" v-on:click="uploaderCard('uploaderCard')">
                        <div class="change-pic"></div>
                    </div> -->
                    <!--change-info begin-->
                    <div class="change-info flt">
                        <p class="name">{{name}}</p>
                        <p class="company">{{c_name}}</p>
                        <p class="mobile">{{mobile}}</p>
                        <p>
                            <span>发布供给：<b>{{sale}}条</b></span>
                            <span>发布求购：<b>{{buy}}条</b></span>
                        </p>
                    </div>
                    <!--change-info end-->
                    <button v-if="isDisabled" v-on:click="editor">编辑</button>
                    <button v-if="!isDisabled" v-on:click="save">保存</button>
                </div>
                <!--change-person end-->
                <!--edit begin-->
                <div class="edit routine-set border-wrap w96">
                    <ul>
                        <li>
                            <p class="confirm modify">地址：<input type="text" v-bind:disabled="isDisabled" v-model="address" class="import"/></p>
                        </li>
                        <li>
                            <p class="confirm" v-if="isDisabled">性别：{{sex}}</p>
                            <p class="modify" v-if="!isDisabled">
                                性别：
                                <input type="radio" class="radio" name="gender" id="gender1" value="0" v-model="sexradio"/><label for="gender1">男</label> 
                                <input type="radio" class="radio" name="gender" id="gender2" value="1" v-model="sexradio"/><label for="gender2">女</label>
                            </p>
                        </li>
                        <li>
                            <p class="confirm" v-if="isDisabled">所属地区：{{adistinct}}</p>
                            <p class="modify" v-if="!isDisabled">
                                所属地区：
                                <input type="radio" class="radio" name="region" id="region1" value="EC" v-model="distinctradio"/><label for="region1">华东</label>
                                <input type="radio" class="radio" name="region" id="region2" value="NC" v-model="distinctradio"/><label for="region2">华北</label>
                                <input type="radio" class="radio" name="region" id="region3" value="SC" v-model="distinctradio"/><label for="region3">华南</label>
                                <input type="radio" class="radio" name="region" id="region4" value="OT" v-model="distinctradio"/><label for="region4">其他</label>
                            </p>
                        </li>
                        <li class="last">
                            <p>企业类型：物流服务商</p>
                        </li>
                    </ul>
                </div>
                <!--edit end-->
                <!--edit begin-->
                <div class="edit border-wrap w96">
                    <ul>
                        <li>
                            <p>公开手机号码</p>
                        </li>
                        <li class="last">
                            <p>是否公开</p>
                            <span>
                                <input v-on:click="msgActive(2)" v-bind:checked="active3" type="checkbox">
                            </span>
                        </li>
                    </ul>
                </div>
                <!--edit end-->
                <!--edit begin-->
                <div class="edit msg-set border-wrap w96">
                    <ul>
                        <li><p>手机短信设置</p></li>
                        <li>
                            <p>有人关注我，手机短信提示</p>
                            <span>
                                <input v-on:click="msgActive(0)" v-bind:checked="active" type="checkbox">
                            </span>
                        </li>
                        <li class="last">
                            <p>有人回复我的供求，手机短信提醒</p>
                            <span>
                                <input v-on:click="msgActive(1)" v-bind:checked="active2" class="weui-switch" type="checkbox">
                            </span>
                        </li>
                    </ul>
                </div>
                <!--edit end-->
                <!--upload-card begin-->
                <div class="upload-card border-wrap w96">
                    <div class="card">
                        <img v-bind:src="cardImg">
                    </div>
                    <div class="card2">
                        <input type="file" name="upFile" style="width:133px; height: 73px; opacity: 0; position: absolute; top: 0; left: 0;" id="uploaderCard" v-on:click="uploaderCard('uploaderCard',2)">
                        <div class="card2upload" v-show="!cardshow"></div>
                        <div class="card2success" v-show="cardshow"></div>
                    </div>
                    <div class="remark frt">
                        <p>通讯录排序：您目前通讯录排名第{{rank}}位，共{{total}}名，按照粉丝数量、发布求购数量、发布供给数量进行排序。</p>
                    </div>
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
            name: "",
            buy: "",
            sale: "",
            c_name: "",
            c_type:"",
            c_nametype:"",
            mobile: "",
            address: "",
            sex: "",
            status: "",
            thumb: "",
            concern_model: "",
            need_product: "",
            main_product:"",
            month_consum:"",
            isType:"",
            need_ph: "",
            rank: "",
            total: "",
            sexradio: "",
            distinctradio: "EC",
            cardImg: "",
            active: "",
            active2: "",
            active3: "",
            level: "",
            distinct: "",
            loadingShow: "",
            isDisabled:true,
            cardshow:false
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
        editor: function(){
            var _this = this;
            _this.isDisabled = false;
        },
        save: function(){
            var _this = this;
            _this.isDisabled = true;
            $.ajax({
                url: '/qapi_3/myInfo/saveSelfInfo',
                type: 'post',
                data: {
                    token: window.localStorage.getItem("token"),
                    address:_this.address,
                    sex:_this.sexradio,
                    major:_this.need_product,
                    concern:_this.need_ph,
                    dist:_this.distinctradio,
                    type:_this.c_type,
                    month_consum:_this.month_consum,
                    main_product:_this.main_product
                },
                headers: {
                    'X-UA': window.localStorage.getItem("XUA")
                },
                dataType: 'JSON'
            }).then(function(res) {
                layer.open({
									title: false,
									offset : "28%",
									content : res.msg,
									closeBtn : false,
									btnAlign: 'c',
									anim : 2
							});
				window.setTimeout("window.location.reload()",2000)			
                //window.location.reload();
            }, function() {

            });
        },
        msgActive: function(type){
            var _this = this, is_allow = '';
            if(type == 2){
                is_allow =  _this.active3 == 1? 0: 1;
            }else if(type == 0){
                is_allow = _this.active == 1? 0: 1;
            }else if(type == 1){
                is_allow = _this.active2 == 1? 0: 1;
            }
            $.ajax({
                url: '/qapi_3/myInfo/favorateSet',
                type: 'post',
                data: {
                    type: type,
                    is_allow: is_allow,
                    token: window.localStorage.getItem("token")
                },
                headers: {
                    'X-UA': window.localStorage.getItem("XUA")
                },
                dataType: 'JSON'
            }).then(function(res) {

            }, function() {

            });
        },
        uploaderCard: function(obj,index){
                var _this = this, action = '';
                if(index == 1){
                    action = '/api/qapi1/savePicToServer';
                }else if(index == 2){
                    action = '/api/qapi1/saveCardImg';
                }
                new AjaxUpload(obj, {
                action: action,
                name: 'image',
                data: {
                    token: window.localStorage.getItem("token")
                },
                onSubmit: function(file, suffix)
                {
                    // 'gif','jpg','jpeg','png','swf'
                    var patrn = /^(jpg|jpeg|png)$/i;
                    if (!patrn.test(suffix)) {
                        alert('不支持上传 *.' + suffix + '格式的文件。')
                        return false;
                    }
                },
                onComplete: function(file, response) {
                    var res = jQuery.parseJSON(response);
                    if(res.err == 0) {
                        if(index == 1){
                            _this.thumb = res.url;
                        }else if(index == 2){
                            _this.cardImg = res.url;
                            _this.cardshow = true;
                        }
                    } else {
                        alert(res.msg);
                    }
                }
                });
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
            $.ajax({
                url: '/qapi_3/myInfo/getSelfInfo',
                type: 'post',
                data: {
                    token: window.localStorage.getItem("token")
                },
                headers: {
                    'X-UA': window.localStorage.getItem("XUA")
                },
                dataType: 'JSON'
            }).then(function(res) {
                    if(res.err == 0) {
                        _this.name = res.data.name;
                        _this.c_name = res.data.c_name;
                        _this.address = res.data.address;
                        _this.mobile = res.data.mobile;
                        _this.need_ph = res.data.concern_model;
                        _this.need_product = res.data.need_product;
                        _this.main_product = res.data.main_product;
                        _this.month_consum = res.data.month_consum;
                        _this.status = res.data.status;
                        _this.concern_model = res.data.concern_model;
                        _this.thumb = res.data.thumb;
                        _this.buy = res.data.buy;
                        _this.sale = res.data.sale;
                        _this.sex = res.data.sex;
                        _this.rank = res.data.rank;
                        _this.total = res.data.total;
                        _this.cardImg = res.data.thumbcard;
                        _this.active = res.data.allow_send.focus;
                        _this.active2 = res.data.allow_send.repeat;
                        _this.active3 = res.data.allow_send.show;
                        _this.level = res.data.member_level;
                        _this.adistinct = res.data.adistinct;
                        _this.c_type=res.data.type;
                        if (_this.sex=="男") {
                            _this.sexradio=0;
                        } else{
                            _this.sexradio=1;
                        }
                        if (_this.adistinct=="华东") {
                            _this.distinctradio="EC";
                        }else if(_this.adistinct=="华北"){
                            _this.distinctradio="NC";
                        }else if(_this.adistinct=="华南"){
                            _this.distinctradio="SC";
                        }else{
                            _this.distinctradio="OT";
                        }
                        
                        if(_this.c_type=="2"){
                            _this.c_nametype="原料供应商 ";
                        }else if(_this.c_type=="1"){
                            _this.c_nametype="塑料制品厂";
                            _this.isType=true;
                        }else if(_this.c_type=="4"){
                            _this.c_nametype="物流服务商";
                        }else if(_this.c_type=="3"){
                            _this.c_nametype="其他";
                        }
                    } else if(res.err == 1) {
                        alert('请登录');
                    }
                }, function() {

                });
        //
        
        //
    }
}
</script>

