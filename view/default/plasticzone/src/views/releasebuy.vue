<template>
<div class="buyWrap" style="padding: 45px 0 70px 0;">
	<div style="position: fixed; top: 0; left: 0; width: 100%; z-index: 6;">
		<header id="bigCustomerHeader">
			<a class="back" href="javascript:window.history.back();"></a>
			查看Ta的求购
		</header>
	</div>
	<div class="releaseHead">
		<div style="width: 80px; height: 80px; float: left; position: relative;">
			<div class="avator2">
				<img v-bind:src="thumb">
			</div>
			<i class="iconV" v-bind:class="{'v1':is_pass==1,'v2':is_pass==0}"></i>
		</div>
		<div class="nameinfo2">
			<p class="first">{{name}}&nbsp;{{sex}}&nbsp;</p>
			<p class="second">{{c_name}}</p>
			<p class="second">电话：{{mobile}}</p>
		</div>
	</div>
	<ul class="releaseTa">
		<li v-show="condition" v-for="r in release">
			<span style="color: #999999;">{{r.input_time}}</span><br>
			<span v-if="r.type==2"><i class="myicon2 iconSupply"></i>供给</span>
			<span v-else>求购</span>
			<span style="color: #333333; line-height: 23px;">{{r.contents}}</span>
		</li>
		<li v-show="!condition" style="text-align: center; line-height: 50px;">
			没有相关数据
		</li>
	</ul>
	<footerbar></footerbar>
</div>
</template>
<script>
import footer from "../components/footer";
export default{
	components: {
		'footerbar': footer
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
	activated: function() {
		var _this = this;
			try {
	    var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
	    piwikTracker.trackPageView();
	} catch( err ) {
		
	}
		$.ajax({
			url: '/api/qapi1/getTaPur',
			type: 'get',
			data: {
				userid: _this.$route.params.id,
				type: 1,
				page: _this.page,
				token: window.localStorage.getItem("token"),
				size: 10
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
			url: '/api/qapi1/getZoneFriend',
			type: 'get',
			data: {
				userid: _this.$route.params.id,
				token: window.localStorage.getItem("token"),
				size: 10
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