<template>
  <div id="app">
 <Leftmodel></Leftmodel>
  <!--address-list begin-->
  <div class="center flt" style="position:relative;">
<div class="address-list">
	<!--fixed begin-->
    <div class="fixed">
        <h2>
            <span>塑料人自己的通讯录({{member}}人)</span>
        </h2>
      <!--search begin-->
        <div class="search">
            <form name="" method="post" action="">
                <!--search-top begin-->
                <div class="search-top">
                  <select class="select1" v-on:change="onSelectChange" v-model="selected">
                        <option value="0">全国站</option>
                        <option value="1">华东</option>
                        <option value="2">华北</option>
                        <option value="3">华南</option>
                        <option value="4">其他</option>
                    </select>
                  <select  v-on:change="onSelectChange1" v-model="selected1">
                        <option value="0">所有分类</option>
                        <option value="1">塑料制品企业</option>
                        <option value="2">原料供应商</option>
                        <option value="4">物流服务商</option>
                        <option value="5">其他</option>
                    </select>
                </div>               
                <!--search-top end-->
                <!--search-bot begin-->
                <div class="search-bot">
				    <form action="javascript:;">
                    <input v-on:keyup.enter="search" type="text" placeholder="请输入公司、姓名、牌号查询"v-model="keywords">
                    <span v-on:click="search" class="icon-search flt">搜索</span>
					</form>
                </div>
                <!--search-bot end-->
            </form>
        </div>
      <!--search end-->
	  <!--concern begin-->
        <div v-if="isFocus" class="concern">
            <ul>
                <li v-on:click="payfans()" style="cursor:pointer"><p class="thumb thumb2"></p><span>我关注的人</span></li>
                <li v-on:click="myfans()" style="cursor:pointer"><p class="thumb thumb1"></p><span>关注我的人</span></li>
            </ul>
        </div>
      <!--concern end-->
      <!--banner begin-->
        <div class="banner" v-on:click="paysudu()" style="cursor:pointer"><img src="http://pic.myplas.com/mypc/img/banner.jpg" width="309" height="66"/></div>
      <!--banner end-->
      
    </div>
    <!--fixed end-->
    <!--set-top begin-->
    <ul class="list set-top">
      <li id="top" v-if="top" style="height:67px; top:207px; overflow:hidden; border-bottom:1px solid #ccc;  cursor:pointer;  position:fixed; z-index:9999; background:#fff;" v-on:click="personinfo(top.user_id)">
            <div style=" width: 100%; position: relative;">
                <!--pic begin-->
                <div class="pic flt">
                    <img v-bind:src="top.thumb">
                    <div class="authen no" v-bind:class="{'v1':top.is_pass==1,'v2':top.is_pass==0}">V</div>
                </div>			
                <!--pic end-->
                <!--info begin-->               
                <div class="info flt">
                        <p>
                                <span class="company" v-html="top.c_name"></span>
                                <span class="name" v-html="top.name"></span>&nbsp;{{top.sex}}</p>
                        <p>
                            <span v-if="top.type==='1'">产品:{{top.main_product}}</span>
                            <span v-if="top.type==='1'">月用量:{{top.month_consum}}</span>
                        </p>
                        <p v-if="top.type=='1'">
                            <span>供 :&nbsp;{{top.sale_count}}</span> 
                            <span>求 :&nbsp;{{top.buy_count}}&nbsp; 需求：</span>
                            <span style="color: #666666; font-weight: normal;" v-html="top.need_product"  class="need"></span>
                        </p>
                        <p v-if="top.type==='2'">
                            <span>供 :&nbsp;{{top.sale_count}}</span> 
                            <span>求 :&nbsp;{{top.buy_count}} 主营：</span>
                            <span v-html="top.need_product"  class="need"></span>
                        </p>
                        <p v-if="top.type==='3'||top.type==='5'||top.type==='6'||top.type==='7'||top.type==='8'||top.type==='9'||top.type==='10'">
                                <span>主营产品：</span><span v-html="top.need_product"  class="need"></span>
                        </p>
                        <p v-if="top.type==='4'">
                            <span>主营产品：</span><span v-html="top.main_product"></span>
                        </p>
                </div>
                <!--info end-->
                <div class="set-top">已置顶</div>
            </div>
        </li>
        </ul>
        <!--set-top end-->    
    <!--list begin-->
   <ul id="list" class="list" v-bind:class="{ padding1:isPadding1,padding2:isPadding2 }">   
        <li class="static" v-show="condition" v-for="n in name" style="font-size:12px; height:67px; padding:11px 0; overflow:hidden; border-bottom:1px solid #ccc;  cursor:pointer; " v-on:click="personinfo(n.user_id)">
            <!--pic begin-->
            <div class="pic flt">
                <img v-bind:src="n.thumb">                
				<div class="authen no" v-bind:class="{'v1':n.is_pass==1,'v2':n.is_pass==0}">V</div>
            </div>
            <!--pic end-->
            <!--info begin-->
             <div class="info flt" style="width:242px;">
                <p>
                    <span class="company" v-html="n.c_name"></span>
                    <span class="name" v-html="n.name"></span>
                    <span v-html="n.sex"></span>
                </p>
                <p>
                    <span class="product"  v-if="n.type==='1'">产品：{{n.main_product}}</span>
                    <span class="amount"  v-if="n.type==='1'">月用量：{{n.month_consum}}</span>
                </p>
                <p v-if="n.type=='1'">
                    <span>供：{{n.sale_count}}</span>
                    <span class="demand" ></span>
                    <span>求：{{n.buy_count}}&nbsp;</span>
	       <span>需求：</span>
                    <span v-html="n.need_product" class="need"></span>
                </p>
                <p v-if="n.type==='2'">
                    <span>供：{{n.sale_count}}</span>
                    <span>求：{{n.buy_count}}&nbsp;</span>
					<span>需求：</span>
                    <span v-html="n.need_product" class="need"></span>
                </p>
                <p v-if="n.type==='3'||n.type==='5'||n.type==='6'||n.type==='7'||n.type==='8'||n.type==='9'||n.type==='10'">
                    <span>主营产品：</span>
                </p>
                <p  v-if="n.type==='4'">
				    <span>主营产品：</span>
                    <span v-html="n.main_product"></span>
                </p>
            </div>
            <!--info end-->
        </li>
        <li v-show="!condition" style="text-align: center;">
            没有相关数据
        </li>
    </ul>
    <loadingPage :loading="loadingShow"></loadingPage>
    <div class="refresh" v-bind:class="{circle:isCircle}" v-on:click="circle"></div>
    <div class="index-top-arrow" v-show="isArrow" v-on:click="arrow"></div>
    <!--list end-->
</div>
</div>
  <!--address-list end-->
  <div class="right flt">
<div class="default">
    <div class="default-top"><img src="http://pic.myplas.com/mypc/img/default-top.png" width="440" height="170"/></div>
    <!--default-bot begin-->
    <div class="default-bot">
        <!--opt begin-->
        <div class="opt flt">
            <p class="opt1"><a target="_blank" href="https://itunes.apple.com/cn/app/id1172762802">iphone版下载</a></p>
            <p class="opt2"><a target="_blank" href="http://android.myapp.com/myapp/detail.htm?apkName=com.myplas.q">Android版下载</a></p>
        </div>
        <!--opt end-->
        <!--ewm begin-->
        <div class="ewm frt">
            <img src="http://pic.myplas.com/mypc/img/ewm.jpg" width="160" height="160"/>
            <p>扫一扫 加入通讯录</p>
        </div>
        <!--ewm end-->
		</div>
    </div>
    <!--default-bot end-->
</div>
<!--default end-->
  </div>
</template>
<style type="text/css">
.layui-layer-content{ text-align:center;}
</style>
<script>
import Leftmodel from "../../components/Leftmodel";
export default {
  name: 'app',
  components: {
		'Leftmodel': Leftmodel
	},
	data: function() {
        return {
            name: [],
            keywords: "",
            page: 1,
            condition: true,
            member: "",
            picarr: [],
            fans: [],
            isCircle: false,
            isArrow: false,
            region: 0,
            c_type: 0,
            txt: "所有分类",
            txt2: "全国站",
            loadingShow: "",
            top: "",
            isFocus: true,
            bannerLink: "",
            bannerImg: "",
            filterShow:true,
            selected:'0',
	        selected1:'0',
            isPadding1:"",
            isPadding2:""
        }
    },
    methods: {
	    onSelectChange:function(){
		            $(".center").scrollTop(0,0);
		            var _this = this;
					var selected = _this.selected;
					_this.page = 1;
					$.ajax({
						type: "post",
						url: version + "/friend/getPlasticPerson",
						headers: {
							'X-UA': window.localStorage.getItem("XUA")
						},
						data: {
							keywords: "",
							page: _this.page,
							token: window.localStorage.getItem("token"),
							size: 10,
							region: selected,
							c_type: _this.selected1
						},
						dataType: 'JSON'
					}).done(function(res) {
						if(res.err == 0) {
							_this.condition = true;
							_this.member = res.member;
							_this.name = res.persons;
						} else if(res.err == 2) {
							_this.condition = false;
						}
					}).fail(function() {

					}).always(function() {

					});
                },
		onSelectChange1:function(){
		            $(".center").scrollTop(0,0);
		            var _this = this;
					var selected1 = _this.selected1;
					_this.page = 1;
					$.ajax({
						type: "post",
						url: version + "/friend/getPlasticPerson",
						headers: {
							'X-UA': window.localStorage.getItem("XUA")
						},
						data: {
							keywords: "",
							page: _this.page,
							token: window.localStorage.getItem("token"),
							size: 10,
							region: _this.selected,
							c_type: selected1
						},
						dataType: 'JSON'
					}).done(function(res) {
						if(res.err == 0) {
							_this.condition = true;
							_this.member = res.member;
							_this.name = res.persons;
						} else if(res.err == 2) {
							_this.condition = false;
						}
					}).fail(function() {

					}).always(function() {

					});
                },
        focusShow:function(){
            this.filterShow=false;
        },
        toLogin: function() {
            if(window.localStorage.getItem("token")) {
                /*weui.alert("你已登录塑料圈", {
                    title: '塑料圈通讯录',
                    buttons: [{
                        label: '确定',
                        type: 'parimary',
                        onClick: function() {
                            _this.$router.push({
                                name: 'login'
                            });
                        }
                    }]
                });*/
            } else {
                this.$router.push({
                    name: 'login'
                });
            }
        },
        arrow: function() {
            $(".center").scrollTop(0,0);
        },
        circle: function() {
            var _this = this;
            this.isCircle = true;
            $.ajax({
                type: "post",
                url: version + "/friend/getPlasticPerson",
                headers: {
                    'X-UA': window.localStorage.getItem("XUA")
                },
                data: {
                    keywords: "",
                    page: 1,
                    token: window.localStorage.getItem("token"),
                    size: 10,
                    region: _this.region,
                    c_type: _this.c_type
                },
                dataType: 'JSON'
            }).then(function(res) {
                if(res.err == 0) {
                    _this.condition = true;
                    _this.member = res.member;
                    _this.name = res.persons;
                    _this.isCircle = false;
                    window.scrollTo(0, 0);
                    if (res.show_msg) {
                        layer.open({
									title: false,
									offset : "28%",
									content : res.msg,
									closeBtn : false,
									btnAlign: 'c',
									anim : 2
							});
                    }
                } else if(res.err == 2) {
                    _this.condition = false;
                }
            }, function() {

            });
        },
        search: function() {
            var _this = this;
            _this.page = 1;
            this.filterShow=true;
            if(this.keywords) {
                try {
                    var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
                    piwikTracker.trackSiteSearch(this.keywords, "keywords", 20);
                } catch(err) {

                }

                $.ajax({
                    url: version +"/friend/getPlasticPerson",
                    type: 'post',
                    headers: {
                        'X-UA': window.localStorage.getItem("XUA")
                    },

                    data: {
                        keywords: _this.keywords.toLocaleUpperCase(),
                        page: _this.page,
                        token: window.localStorage.getItem("token"),
                        size: 10,
                        region: _this.selected,
                        c_type: _this.selected1
                    },
                    dataType: 'JSON'
                }).done(function(res) {
                    if(res.err == 0) {
                        _this.condition = true;
                        _this.name = res.persons;
                    } else if(res.err == 2) {
                        _this.condition = false;
                    }
                }).fail(function() {

                }).always(function() {

                });
            } else {
                window.location.reload();
            }
        },
        loadingMore: function() {
            var _this = this;
            var scrollTop = $(".center").scrollTop();
            var scrollHeight = $(".address-list").height();
            var windowHeight = $(window).height();
            if(scrollTop + windowHeight >= scrollHeight) {
                _this.page++;
                $.ajax({
                    type: "post",
                    url: version + "/friend/getPlasticPerson",
                    headers: {
                        'X-UA': window.localStorage.getItem("XUA")
                    },
                    data: {
                        sortField: _this.sortField,
                        sortOrder: _this.sortOrder,
                        keywords: _this.keywords.toLocaleUpperCase(),
                        page: _this.page,
                        region: _this.selected,
                        token: window.localStorage.getItem("token"),
                        c_type: _this.selected1,
                        size: 10
                    },
                    dataType: 'JSON'
                }).then(function(res) {
                    if(res.err == 0) {
                        _this.condition = true;
                        _this.name = _this.name.concat(res.persons);
                    } else if(res.err == 1) {
					 layer.open({
                     title: ["塑料圈通讯录", "text-align:center"],
								offset : "28%",
								icon : 5,
								content : res.msg,
								btnAlign: 'c',
									   anim : 2,
									   yes : function () {
									location.href = "/mypczone/index/login?init";		
								}
								   });
                    } else if(res.err == 2) {
                        _this.condition = false;
                    } else if(res.err == 3&&scrollTop + windowHeight - scrollHeight>= 5) {
                       //没有更多数据
					   layer.open({
									title: false,
									offset : "28%",
									content : res.msg,
									closeBtn : false,
									btnAlign: 'c',
									anim : 2,
									time:2000,
									btn : 0,
							});
                    }
                }, function() {

                });

            }
        },
	myfans: function() {
        location.href="/mypczone/index/myIntro?type=1";
    },
    payfans: function() {
        location.href="/mypczone/index/myIntro?type=2";
    },
    paysudu: function() {
        location.href="/mypczone/index/mySudou";
    },	
	personinfo: function(user_id) {
	    var user_id = user_id;
        location.href="/mypczone/index/indexinfo?id="+user_id;
	    }
    },	
    mounted: function() {
        var _this = this;
        this.loadingShow = true;
        try {
            var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
            piwikTracker.trackPageView();
        } catch(err) {

        }

        $.ajax({
            type: "post",
            url: version + "/friend/getPlasticPerson",
            headers: {
                'X-UA': window.localStorage.getItem("XUA")
            },
            data: {
                keywords: "",
                page: 1,
                token: window.localStorage.getItem("token"),
                size: 10,
                region: _this.region
            },
            dataType: 'JSON'
        }).done(function(res) {
            if(res.err == 0) {
                _this.isFocus = res.is_show_focus;
                _this.bannerLink = res.banner_jump_url;
                _this.bannerImg = res.banner_url;
                _this.condition = true;
                _this.member = res.member;
                _this.name = res.persons;
                _this.c_type = res.show_ctype;
                if(_this.c_type == 0) {
                    _this.txt = "所有分类";
                } else if(_this.c_type == 1) {
                    _this.txt = "塑料制品厂";
                } else if(_this.c_type == 2) {
                    _this.txt = "原料供应商";
                } else if(_this.c_type == 4) {
                    _this.txt = "物流服务商";
                } else if(_this.c_type == 5) {
                    _this.txt = "其他";
                }
                if(JSON.stringify(res.top) == '{}') {
                    _this.top = null;
					_this.isPadding1=true;
					_this.isPadding2=false;
                } else {
                    _this.top = res.top;
					_this.isPadding1=false;
					_this.isPadding2=true;
                }
            } else if(res.err == 2) {
                _this.condition = false;
            }
        }).fail(function() {

        }).always(function() {
            _this.loadingShow = false;
        });

        $(".center").scroll(function() {
            var scrollTop = $(this).scrollTop();	
            _this.loadingMore();
            if(scrollTop > 600) {
                _this.isArrow = true;
            } else {
                _this.isArrow = false;
            }
        });
    }	
}


$( ".opt flt" ).find(".opt1").bind("mouseover",function(){
	$(this).find(".opt1").removeClass("opt1").addClass("opt2");
	});

</script>
