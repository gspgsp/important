<template>
  <div id="app">
    <Leftmodel></Leftmodel>
    <div class="center flt" style="background:#eee;">
        	<div class="discovery">
        <h2>塑料发现</h2>
          <ul class="discovery1">
            <li class="title last"><a href="/mypczone/index/headline">塑料头条</a></li>
          </ul>
          <ul class="discovery2">
            <li class="title">企业信用额度</li>
              <li class="link1"><a href="/mypczone/index/checkSelf">查自己</a></li>
              <li class="link2"><a href="/mypczone/index/checkOther">查别人</a></li>
              <li><a href="https://www.sobot.com/chat/pc/index.html?sysNum=137f8799efcb49fea05534057318dde0" target="_blank">要授信</a></li>
              <li class="last">
                <a href="https://www.sobot.com/chat/pc/index.html?sysNum=137f8799efcb49fea05534057318dde0" target="_blank">提额度</a>
              </li>
          </ul>
          <ul class="discovery3">
            <li class="title">塑料配资</li>
              <li><a href="https://www.sobot.com/chat/pc/index.html?sysNum=137f8799efcb49fea05534057318dde0" target="_blank">产品定义</a></li>
              <li><a href="https://www.sobot.com/chat/pc/index.html?sysNum=137f8799efcb49fea05534057318dde0" target="_blank">费率</a></li>
              <li class="last">
                <a href="https://www.sobot.com/chat/pc/index.html?sysNum=137f8799efcb49fea05534057318dde0" target="_blank">我要申请</a>
              </li>
          </ul>
      </div>
    </div>
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
            <img src="http://static.bendi.com/mypc/img/ewm.jpg" width="160" height="160"/>
            <p>扫一扫 加入通讯录</p>
        </div>
        <!--ewm end-->
    </div>
    </div>
    <!--default-bot end-->
</div>
  </div>
</template>
<script>
import Leftmodel from "../../components/leftmodel";
export default {
  name: 'app',
  components: {
        'Leftmodel': Leftmodel
    },
    data: function() {
        return {
            msg:""
        }
    },
    mounted: function() {
          if (!window.localStorage.getItem("token") ){
              layer.open({
                      title: false,
                      offset : "28%",
                      icon : 5,
                      content : "您未登录塑料圈,无法查看企业及个人信息",
                      closeBtn : false,
                      btnAlign: 'c',
                      anim : 2,
                      yes : function () {
                          location.href = "/mypczone/index/login?find";    
                      }
                  });
        }
    }
}
</script>
