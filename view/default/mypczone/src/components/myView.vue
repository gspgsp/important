<template>
<div id="app">
    <div class="center flt">
      	<div class="my-title">我的塑料圈</div>
            <!--my-title end-->
            <!--my-summary begin-->
            <div class="my-summary">
              <!--my-summary-top begin-->
                <div class="my-summary-top">
                  <!--pic begin-->
                  <div class="pic flt">
                        <img v-bind:src="thumb">
                        <div class="authen" v-bind:class="{'yes':is_pass == 1,'no':is_pass == 0}">V</div>
                    </div>
                    <!--pic end-->
                    <!--person-info begin-->
                    <div class="person-info flt">
                      <p>{{c_name}}</p>
                        <p>{{name}} {{mobile}}</p>
                        <p class="opt">
                            <span class="change" v-on:click="editInfo">更改用户信息<b></b></span>
                            <span v-on:click="editInfo">上传名片加V认证<b>>></b></span>
                        </p>
                    </div>
                    <!--person-info end-->
                </div>
                <!--my-summary-top end-->
                <!--my-summary-bot begin-->
                <div class="my-summary-bot">
                  <ul>
                      <li>
                          <span>{{supply}}</span>
                            <p>供给</p>
                        </li>
                        <li>
                          <span>{{buy}}</span>
                            <p>求购</p>
                        </li>
                        <li>
                          <span>{{msg}}</span>
                            <p>留言</p>
                        </li>
                        <li>
                          <span>{{invite}}</span>
                            <p>引荐</p>
                        </li>
                        <li>
                          <span>{{fans}}</span>
                            <p>粉丝</p>
                        </li>
                        <li>
                          <span>{{pay}}</span>
                            <p>关注</p>
                        </li>
                        <li>
                          <span>{{points}}</span>
                            <p>积分</p>
                        </li>
                    </ul>
                </div>
                <!--my-summary-bot end-->
            </div>
            <!--my-summary end-->
            <!--my-info begin-->
            <div class="my-info">
              <ul>
                  <!--li begin-->
                  <li>
                      <!--item begin-->
                        <div class="item item1"  v-on:click="toNext(2)">
                          <div class="icon icon1"></div>
                          <p>我的供给</p>
                            <div class="text">{{supply}}</div>
                            <div class="arrow"></div>
                        </div>
                        <!--item end-->
                        <!--item begin-->
                        <div class="item item2"   v-on:click="toNext(1)">
                          <div class="icon icon2"></div>
                          <p>我的求购</p>
                            <div class="text">{{buy}}</div>
                            <div class="arrow"></div>
                        </div>
                        <!--item end-->
                    </li>
                    <!--li begin-->
                    <!--li begin-->
                  <li>
                      <!--item begin-->
                        <div class="item item3" v-on:click="toIntro(3)">
                          <div class="icon icon3"></div>
                          <p>我的引荐</p>
                            <div class="text">{{invite}}</div>
                            <div class="arrow"></div>
                        </div>
                        <!--item end-->
                        <!--item begin-->
                        <div class="item item4" v-on:click="toIntro(1)">
                          <div class="icon icon4"></div>
                          <p>我的粉丝</p>
                            <div class="text">{{fans}}</div>
                            <div class="arrow"></div>
                        </div>
                        <!--item end-->
                        <!--item begin-->
                        <div class="item item5" v-on:click="toIntro(2)">
                          <div class="icon icon5"></div>
                          <p>我的关注</p>
                            <div class="text">{{pay}}</div>
                            <div class="arrow"></div>
                        </div>
                        <!--item end-->
                    </li>
                    <!--li begin-->
                    <!--li begin-->
                  <li>
                      <!--item begin-->
                        <div class="item item6"  v-on:click="toComment(3)">
                          <div class="icon icon6"></div>
                          <p>我的留言</p>
                            <div class="text">未读留言{{msg}}</div>
                            <div class="arrow"></div>
                        </div>
                        <!--item end-->
                        <!--item begin-->
                        <div class="item item7" v-on:click="toMsg">
                          <div class="icon icon7"></div>
                          <p>我的消息</p>
                            <div class="text">未读消息{{msg2}}</div>
                            <div class="arrow"></div>
                        </div>
                        <!--item end-->
                    </li>
                    <!--li begin-->
                    <!--li begin-->
                  <li>
                      <!--item begin-->
                        <div class="item item8"  v-on:click="toSudou">
                          <div class="icon icon8"></div>
                          <p>我的塑豆</p>
                            <div class="text">可兑换礼品</div>
                            <div class="arrow"></div>
                        </div>
                        <!--item end-->
                    </li>
                    <!--li begin-->
                     <!--li begin-->
                  <li>
                      <!--item begin-->
                        <div class="item item9" v-on:click="toHelp">
                          <div class="icon icon9"></div>
                          <p>帮助</p>
                            <div class="text">有问题点这里</div>
                            <div class="arrow"></div>
                        </div>
                        <!--item end-->
                    </li>
                    <!--li begin-->
                     <!--li begin-->
                  <li class="quit">
                      <!--item begin-->
                        <div class="item item10" v-on:click="logout">退出登录</div>
                        <!--item end-->
                    </li>
                    <!--li begin-->
                </ul>
        </div>
    </div>
</div>
</template>
<script>
export default {
    data: function() {
        return {
            buy: "",
            supply: "",
            points: "",
            fans: "",
            pay: "",
            invite: "",
            msg: "",
            msg2: "",
            c_name: "",
            name: "",
            mobile: "",
            mobile2: "",
            thumb: "",
            is_pass: "",
            share: false,
            share3: false,
            loadingShow: ""
        }
    },
    methods: {
      toNext: function(type){
        window.location.href = "/mypczone/index/mySupply?type="+type;
      },
      toIntro: function(type){
        window.location.href = "/mypczone/index/myIntro?type="+type;
      },
      toComment: function(type){
        window.location.href = "/mypczone/index/myComment?type="+type;
      },
      toMsg: function(){
        window.location.href = "/mypczone/index/myMsg";
      },
      toSudou: function(){
        window.location.href = "/mypczone/index/mySudou";
      },
      toHelp: function(){
        window.location.href = "/mypczone/index/myHelp";
      },
      logout: function() {
          var _this = this;
          $.ajax({
            url: '/qapi_3/user/logout',
            type: 'post',
            data: {
              token: window.localStorage.getItem("token")
            },
            headers: {
              'X-UA': window.localStorage.getItem("XUA")
            },
            dataType: 'JSON'
          }).then(function(res) {
            console.log(res.err);
            if(res.err == 0) {
              window.localStorage.setItem("token", "");
              window.localStorage.setItem("userid", "");
              layer.open({
                    title: false,
                    offset : "28%",
                    content : "退出登录成功！",
                    closeBtn : false,
                    btnAlign: 'c',
                    anim : 2,
                    yes : function () {
                        location.href = "/mypczone/index";    
                    }
                });
            } else {
              window.localStorage.setItem("token", "");
              window.localStorage.setItem("userid", "");
            }
          }, function() {

          });
        },
        editInfo: function(){
            window.location.href = "/mypczone/index/myEdit";
        }
    },
    mounted: function(){
      var _this = this;
      $.ajax({
            url: '/qapi_3/myInfo/myZone',
            type: 'post',
            data: {
                token: '3bf198c15c2b3b98bd41832df8445a89'
            },
            headers: {
                'X-UA': window.localStorage.getItem("XUA")
            },
            dataType: 'JSON'
        }).done(function(res) {
                _this.name = res.data.name;
                _this.c_name = res.data.c_name;
                _this.mobile = res.data.mobile;
                _this.mobile2 = res.data.mobile;
                _this.thumb = res.data.thumb;
                _this.is_pass = res.data.is_pass;
                _this.buy = res.s_in_count;
                _this.supply = res.s_out_count;
                _this.points = res.points;
                _this.msg = res.leaveword;
                _this.msg2 = res.message;
                _this.invite = res.introduction;
                _this.fans = res.myfans;
                _this.pay = res.myconcerns;
        });
    }
}
</script>
