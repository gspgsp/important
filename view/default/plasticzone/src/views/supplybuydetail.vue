<template>
<div class="buyWrap" style="padding: 45px 0 70px 0;">
<div style="position: fixed; top: 0; left: 0; width: 100%; z-index: 6;">
	<header id="bigCustomerHeader">
		Ta的报价
	</header>
</div>
<div class="releaseHead">
	<div style="width: 80px; height: 80px; float: left; position: relative;">
		<div class="avator2">
			<img v-bind:src="thumb">
		</div>
	</div>
	<div class="nameinfo2">
		<p class="first">{{name}}</p>
		<p class="second">{{c_name}}</p>
		<p class="second">电话：{{mobile}}</p>
	</div>
</div>
<ul class="releaseTa">
	<li>
		<span style="color: #999999;">{{time}}</span><br>
		<span style="color: #999999;"><i class="myicon2 iconSupply"></i>报价</span>
		<span style="color: #333333; line-height: 23px;">{{contents}}</span>
	</li>
</ul>
<footerbar></footerbar>
</div>
</template>
<script>
var footer = require("../components/footer");
export default{
	components: {
		'footerbar': footer
},
data: function() {
	return {
		name: "",
		c_name: "",
		mobile: "",
		thumb: "",
		time: "",
		msg: "",
		contents: "",
		user_id:""
	}
},
methods:{

},
activated: function() {
	var _this = this;
	try {
	    var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
	    piwikTracker.trackPageView();
	} catch( err ) {
		
	}
	$.ajax({
		type: "post",
		url: version+"/wechat/shareMyPur",
		data: {
			id: _this.$route.params.id
		},
		headers: {
			'X-UA': headers
		},
		dataType: 'JSON'
		}).then(function(res) {
			console.log(res);
			if(res.err == 0) {
				_this.name = res.info.name;
				_this.c_name = res.info.c_name;
				_this.thumb = res.info.thumb;
				_this.mobile = res.info.mobile;
				_this.time = res.data.input_time;
				_this.user_id= res.info.user_id;
				_this.contents = res.data.content || res.data.contents;
			}
		}, function() {

		});
	}
}
</script>