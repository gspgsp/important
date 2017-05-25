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
<div class="replymsg" style=" bottom: 60px;">
	<div style="width: auto; margin-right: 60px;">
		<form>
			<i class="writeicon" v-on:click="replyMsg"></i>
			<input type="text" placeholder="期待你的回复" v-model="msg" />
		</form>
	</div>
	<span v-on:click="replyMsg" class="releasedetailbtn">回复</span>
</div>
<footerbar></footerbar>
</div>
</template>
<script>
var footer = require("../components/footer");
module.exports = {
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
		replyMsg: function() {
		var _this = this;
		$.ajax({
			url: '/api/qapi1/saveMsg',
			type: 'get',
			data: {
				pur_id: _this.$route.params.id,
				content: _this.msg,
				send_id: _this.user_id,
				token: window.localStorage.getItem("token")
			},
			dataType: 'JSON'
		}).then(function(res) {
			if(res.err == 0) {
				mui.toast(res.msg,{
				    duration:'long',
				    type:'div' 
				})
			} else if(res.err==1) {
				mui.alert("", res.msg, function() {
					_this.$router.push({
						name: 'login'
					});
				})
			} else if(res.err==6){
				mui.alert("", res.msg, function() {

				})								
			}else{
				mui.alert("", res.msg, function() {

				})				
			}
		}, function() {

		});
	}
},
activated: function() {
	var _this = this;
	try {
	    var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
	    piwikTracker.trackPageView();
	} catch( err ) {
		
	}
	$.ajax({
		type: "get",
		url: "/api/qapi1/shareMyPur",
		data: {
			id: _this.$route.params.id
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