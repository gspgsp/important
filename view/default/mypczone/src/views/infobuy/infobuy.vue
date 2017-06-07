<template>
  <div id="app">
 <Leftmodel></Leftmodel>
 <Centermodel></Centermodel>
  <div class="right flt">
<div class="back"><a href="/mypczone/index/index_info"></a>查看TA的求购</div>
<!--back end-->
<!--summary begin-->
<div class="summary w96" style="padding-top:30px;">
    <div class="pic flt">
        <img v-bind:src="thumb">
        <div class="authen yes">V</div>
    </div>
    <div class="info-person flt">
        <p class="name">{{name}}<span>{{sex}}</span></p>
        <p>{{c_name}}</p>
        <p>联系电话：{{mobile}}</p>
    </div>
</div>
<!--summary end-->
<!--buy-more begin-->
<div class="buy-sell buy triangle w96" style="overflow:visible;">
    <span class="point point1"></span>
    <ul>
        <li v-show="condition" v-for="r in release">
            <span>{{r.input_time}}</span>
            <p><b>求购</b>：{{r.contents}}</p>
        </li>
        <li v-show="!condition" style="text-align: center; line-height: 50px;">
            没有相关数据
        </li>
    </ul>
</div>
</div>
  </div>
</template>
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
            page: 1,
            type: "",
            show: false,
            id: "",
            user_id: "",
            countShow: false,
            count: "",
            name: "",
            c_name: "",
            mobile: "",
            thumb: "",
            sex: "",
            release: [],
            condition: true
        }
    },
    mounted: function() {
        var _this = this;
		var url = window.location.search;
	    var lid = url.substring(url.lastIndexOf('=')+1, url.length);
		_this.lid = lid;
        try {
            var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
            piwikTracker.trackPageView();
        } catch( err ) {
            
        }
        $.ajax({
            url:  version +"/friend/getTaPur",
            type: 'post',
            data: {
                userid: lid,
                type: 1,
                page: _this.page,
                token: window.localStorage.getItem("token"),
                size: 10
            },
            headers: {
                'X-UA': window.localStorage.getItem("XUA")
            },
            dataType: 'JSON'
        }).then(function(res) {
            if(res.err == 2) {
                _this.condition = false;
            } else if(res.err==0){
                _this.condition = true;
                _this.release = res.data;
            }

        }, function() {

        });

        $.ajax({
            url: version + '/friend/getZoneFriend',
            type: 'post',
            data: {
                userid: _this.$route.params.id,
                token: window.localStorage.getItem("token"),
                size: 10
            },
            headers: {
                'X-UA': window.localStorage.getItem("XUA")
            },
            dataType: 'JSON'
        }).then(function(res) {
            _this.name = res.data.name;
            _this.c_name = res.data.c_name;
            _this.mobile = res.data.mobile;
            _this.thumb = res.data.thumb;
            _this.sex = res.data.sex;
            _this.is_pass = res.data.is_pass;
        }, function() {

        });

	}
}
</script>
