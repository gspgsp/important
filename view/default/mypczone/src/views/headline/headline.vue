<template>
    <div id="app">
        	<div class="headline-head">
              <h3>塑料头条</h3>
                <!--search begin-->
                <div class="search">
                  <!--inner begin-->
                    <div class="inner">
                        <form name="" action="" method="post">
                          <input type="text" class="import" placeholder="搜你想搜的"/>
                            <input type="submit" class="submit" value="搜索"/>
                        </form>
                    </div>
                    <!--inner end-->
                </div>
                <!--search end-->
            </div>
            <!--headline-head end-->
            <!--headline-tab begin-->
            <div class="headline-tab">
              <ul>
                  <li>推荐</li>
                    <li>塑料上游</li>
                    <li>早盘预报</li>
                    <li>企业动态</li>
                    <li>中晨塑说</li>
                    <li>
                      <select>
                          <option>美金市场</option>
                            <option>期货资讯</option>
                            <option>装置动态</option>
                          <option>期刊报告</option>
                            <option>独家解读</option>
                        </select>
                    </li>
                </ul>
            </div>
            <!--headline-tab end-->
            <!--headline-list begin-->
            <div class="headline-list">
                <ul>
                    <li>
                      <h4><a href="javascript:;">【PP】11月3聚丙烯PP早提示</a></h4>
                        <p><a href="javascript:;">昨日PP市场价格高位盘整，个别小幅涨跌50元/吨，拉丝主流价格在8560-8800元/吨。石化出厂价格上涨，加...</a></p>
                        <span>09:22</span>
                    </li>
                    <li>
                      <h4><a href="javascript:;">【PP】11月3聚丙烯PP早提示</a></h4>
                        <p><a href="javascript:;">昨日PP市场价格高位盘整，个别小幅涨跌50元/吨，拉丝主流价格在8560-8800元/吨。石化出厂价格上涨，加...</a></p>
                        <span>09:22</span>
                    </li>
                    <li>
                      <h4><a href="javascript:;">【PP】11月3聚丙烯PP早提示</a></h4>
                        <p><a href="javascript:;">昨日PP市场价格高位盘整，个别小幅涨跌50元/吨，拉丝主流价格在8560-8800元/吨。石化出厂价格上涨，加...</a></p>
                        <span>09:22</span>
                    </li>
                    <li>
                      <h4><a href="javascript:;">【PP】11月3聚丙烯PP早提示</a></h4>
                        <p><a href="javascript:;">昨日PP市场价格高位盘整，个别小幅涨跌50元/吨，拉丝主流价格在8560-8800元/吨。石化出厂价格上涨，加...</a></p>
                        <span>09:22</span>
                    </li>
                    <li>
                      <h4><a href="javascript:;">【PP】11月3聚丙烯PP早提示</a></h4>
                        <p><a href="javascript:;">昨日PP市场价格高位盘整，个别小幅涨跌50元/吨，拉丝主流价格在8560-8800元/吨。石化出厂价格上涨，加...</a></p>
                        <span>09:22</span>
                    </li>
                    <li>
                      <h4><a href="javascript:;">【PP】11月3聚丙烯PP早提示</a></h4>
                        <p><a href="javascript:;">昨日PP市场价格高位盘整，个别小幅涨跌50元/吨，拉丝主流价格在8560-8800元/吨。石化出厂价格上涨，加...</a></p>
                        <span>09:22</span>
                    </li>
                    <li>
                      <h4><a href="javascript:;">【PP】11月3聚丙烯PP早提示</a></h4>
                        <p><a href="javascript:;">昨日PP市场价格高位盘整，个别小幅涨跌50元/吨，拉丝主流价格在8560-8800元/吨。石化出厂价格上涨，加...</a></p>
                        <span>09:22</span>
                    </li>
                </ul>
            </div>
    </div>
</template>
<script>
export default {
    data: function() {
        return {
                cate: "",
                items: [],
                mySubscribe: [],
                subscribeshow: false,
                subchecked: [],
                keywords: "",
                loadingShow: "",
                loadingHide: "",
                plasticlevel:false,
                plasticdefine:false,
                plasticrate:false
            }
    },
    methods: {

    },
    mounted: function(){
        alert(1)
        $.ajax({
            type: "post",
            url: version + '/toutiao/getSelectCate',
            data: {
                token: window.localStorage.getItem("token"),
                type: 2
            },
            headers: {
                'X-UA': window.localStorage.getItem("XUA")
            },
            dataType: 'JSON'
        }).done(function(res) {
            if(res.err == 0) {
                console.log(res);
                // _this.mySubscribe = res.data;
                // _this.subchecked = res.data;
            } else {

            }
        }).fail(function() {

        }).always(function() {

        });
    }


}
</script>

<style>
#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}
</style>
