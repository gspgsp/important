<template>
<div class="buyWrap" style="padding: 0px 0 70px 0;">
<header id="bigCustomerHeader">
	<a class="back" href="javascript:window.history.back();"></a>
	详情
</header>
<div class="releasedetail">
	<span v-on:click="pay" style=" width: auto; padding: 0 5px; height: 25px; color: #ea8010; border-radius: 3px; position: absolute; right: 10px; top: 10px; text-align: center; font-size: 13px; line-height: 25px; border: 1px solid #ea8010;">
{{status}}
</span>
	<div class="myreleaseInfo">
		<div style="width: 30px; height: 30px; float: left; position: relative;">
			<div class="avator">
				<img v-bind:src="thumb">
			</div>
			<i class="iconV" v-bind:class="{'v1':is_pass==1,'v2':is_pass==0}"></i>
		</div>
		<div class="myreleasetxt">
			<p style="height: 30px; line-height: 15px;">
				{{c_name}}&nbsp;{{name}}<br> 粉丝：{{fans}} 等级：{{level}}
			</p>
		</div>
	</div>
	<div class="myreleasetxt2">
		<p>
			<strong v-if="type==2" style=" color: #63769d;">
	<i class="iconSale"></i>供给
</strong>
			<strong v-else style="color: #ea8010;">
	<i class="iconBuy"></i>求购
</strong>
			<strong>{{content}}</strong>
		</p>
		<p style="color: #999999;">
			{{time}}
			<span v-show="mine" style="margin: 0 0 0 10px; float: right; color: #999999;">
	<i class="releasereplyicon"></i>回复<i style="color: #63769d; font-style: normal;">({{saysCount}})</i>
</span>
			<span v-show="mine" style=" color: #999999; float: right;">
	<i class="releasesaleicon"></i>出价<i style="color: #63769d; font-style: normal;">({{deliverPriceCount}})</i>
</span>
		</p>
	</div>
</div>
<div class="bidreply">
	<div style="text-align: center; padding: 5px 0 10px 0;">
		<div style="width: 100%; text-align: center;">
			<div class="releasebschoose">
				<span v-bind:class="{releasebson:show1}" v-on:click="spanshow1">出价消息</span>
				<span v-bind:class="{releasebson:show2}" v-on:click="spanshow2">回复消息</span>
			</div>
		</div>
	</div>
	<div v-show="show1">
		<table class="releasetb" cellpadding="0" cellspacing="0">
			<tr>
				<th>出价人</th>
				<th>出价</th>
				<th>操作</th>
			</tr>
			<tr v-for="p in price">
				<td>
					<div class="myreleaseInfo" style="display: inline-block; width: 150px; margin: 5px 0 0 0;">
						<div style="width: 30px; height: 30px; float: left; position: relative;">
							<div class="avator">
								<img v-bind:src="p.info.thumb">
							</div>
							<i class="iconV" v-bind:class="{'v1':p.info.is_pass==1,'v2':p.info.is_pass==0}"></i>
						</div>
						<div class="myreleasetxt">
							<p style=" width: 110px; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; line-height: 15px;">
								{{p.info.c_name}} {{p.info.name}}
							</p>
							<p style="text-align: left; color: #999999;">
								{{p.input_time}}
							</p>
						</div>
					</div>
				</td>
				<td class="orange">{{p.price}}</td>
				<td>
					<a v-show="p.info.mobile.indexOf('*')=='-1'? true : false" style=" margin: 5px 0 0 0;" v-bind:href="'tel:'+p.info.mobile" class="telephone2"></a>
				</td>
			</tr>
		</table>
		<div class="replymsg" v-show="mine">
			<div style="width: auto; margin-right: 60px;">
				<form>
					<i class="writeicon" v-on:click="deliver"></i>
					<input type="number" placeholder="期待你的出价" v-model="deliverprice" />
				</form>
			</div>
			<span v-on:click="deliver" class="releasedetailbtn">出价</span>
		</div>
	</div>
	<div v-show="show2">
		<ul class="replyul">
			<li v-for="r in reply">
				<div class="myreleaseInfo">
					<div style="width: 30px; height: 30px; float: left; position: relative;">
						<div class="avator">
							<img v-bind:src="r.info.thumb">
						</div>
						<i class="iconV" v-bind:class="{'v1':r.info.is_pass==1,'v2':r.info.is_pass==0}"></i>
					</div>
					<div class="myreleasetxt">
						<p style="height: 30px; line-height: 15px;">
							{{r.info.c_name}} {{r.info.name}}<br> {{r.input_time}}
						</p>
					</div>
				</div>
				<div class="myreleasetxt2">
					<p>
						<strong style="margin: 0 0 0 40px;">{{r.content}}</strong>
					</p>
				</div>
			</li>
		</ul>
		<div class="replymsg" v-show="mine">
			<div style="width: auto; margin-right: 60px;">
				<form>
					<i class="writeicon" v-on:click="replyMsg"></i>
					<input type="text" placeholder="期待你的回复" v-model="msg" />
				</form>
			</div>
			<span v-on:click="replyMsg" class="releasedetailbtn">回复</span>
		</div>
	</div>
</div>
</div>
</template>
<script>
export default{
	data: function() {
		return {
			content: "",
		id: "",
		saysCount: "",
		name: "",
		c_name: "",
		thumb: "",
		is_pass: "",
		deliverPriceCount: "",
		type: "",
		show1: true,
		show2: false,
		fans: "",
		level: "",
		price: [],
		deliverprice: "",
		reply: [],
		msg: "",
		deliverbtn: false,
		replybtn: false,
		right: 0,
		right2: 0,
		mine: true,
		userinfoid: ""
	}
},
methods: {
	spanshow1: function() {
		this.show1 = true;
		this.show2 = false;
	},
	spanshow2: function() {
		this.show1 = false;
		this.show2 = true;
	},
	replyMsg: function() {
		var _this = this;
		$.ajax({
			url: '/api/qapi1/saveMsg',
			type: 'get',
			data: {
				pur_id: _this.id,
				content: _this.msg,
				send_id: _this.user_id,
				token: window.localStorage.getItem("token")
			},
			dataType: 'JSON'
		}).then(function(res) {
			if(res.err == 0) {
				$.ajax({
					url: '/api/qapi1/getReleaseMsgDetailReply',
					type: 'get',
					data: {
						id: _this.$route.query.id,
						user_id: _this.$route.query.userid,
						token: window.localStorage.getItem("token"),
						page: 1,
						size: 10
					},
					dataType: 'JSON'
				}).then(function(res) {
					console.log(res);
					if(res.err == 0) {
						_this.reply = res.data.data;
						_this.msg = "";
					}
				}, function() {

				});
			} else {
				mui.alert("", res.msg, function() {
					window.location.reload();
				})
			}
		}, function() {

		});
	},
	deliver: function() {
		var _this = this;
		$.ajax({
			url: '/api/qapi1/deliverPrice',
			type: 'get',
			data: {
				id: _this.$route.query.id,
				rev_id: _this.$route.query.userid,
				token: window.localStorage.getItem("token"),
				type: _this.type,
				price: _this.deliverprice
			},
			dataType: 'JSON'
		}).then(function(res) {
			console.log(res);
			if(res.err == 0) {
				$.ajax({
					url: '/api/qapi1/getDeliverPrice',
					type: 'get',
					data: {
						id: _this.$route.query.id,
						rev_id: _this.$route.query.userid,
						token: window.localStorage.getItem("token"),
						page: 1,
						size: 10
					},
					dataType: 'JSON'
				}).then(function(res) {
					console.log(res);
					if(res.err == 0) {
						_this.price = res.data.data;
						_this.deliverprice = "";
					}
				}, function() {

				});
			} else if(res.err == 1) {
				mui.alert("", res.msg, function() {
					_this.$router.push({
						name: 'login'
					});
				});
			} else {
				mui.alert("", res.msg, function() {
					window.location.reload();
				});
			}
		}, function() {

		});
	},
	pay: function() {
		var _this = this;
		$.ajax({
			url: '/api/qapi1/focusOrCancel',
			type: 'get',
			data: {
				focused_id: _this.user_id,
				token: window.localStorage.getItem("token")
			},
			dataType: 'JSON'
		}).then(function(res) {
			console.log(">>>", res.msg);
			window.location.reload();
		}, function() {

		});
	}
},
activated: function() {
	var _this = this;
	window.scrollTo(0, 0);
		try {
	    var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
	    piwikTracker.trackPageView();
	} catch( err ) {
		
	}
	$.ajax({
		url: '/api/qapi1/getReleaseMsgDetail',
		type: 'get',
		data: {
			id: _this.$route.query.id,
			user_id: _this.$route.query.userid,
			token: window.localStorage.getItem("token")
		},
		dataType: 'JSON'
	}).then(function(res) {
		console.log(res);
		if(res.err == 0) {
			_this.id = res.data.id;
			_this.user_id = res.data.user_id;
			_this.content = res.data.contents;
			_this.saysCount = res.data.saysCount;
			_this.time = res.data.input_time;
			_this.type = res.data.type;
			_this.name = res.data.info.name;
			_this.fans = res.data.info.fans;
			_this.level = res.data.info.member_level;
			_this.c_name = res.data.info.c_name;
			_this.status = res.data.info.status;
			_this.is_pass = res.data.info.is_pass;
			_this.thumb = res.data.info.thumb;
			_this.deliverPriceCount = res.data.deliverPriceCount;
			_this.userinfoid = res.data.info.user_id;
			if(_this.$route.query.userid == window.localStorage.getItem("userid")) {
				_this.mine = false;
			} else {
				_this.mine = true;
			}
			if(_this.$route.query.tab == 1) {
				_this.show1 = true;
				_this.show2 = false;
			} else {
				_this.show1 = false;
				_this.show2 = true;
			}
		} else if(res.err == 1) {
			mui.alert("", res.msg, function() {
				_this.$router.push({
					name: 'login'
				});
			});
		}
	}, function() {

	});

	$.ajax({
		url: '/api/qapi1/getDeliverPrice',
		type: 'get',
		data: {
			id: _this.$route.query.id,
			rev_id: _this.$route.query.userid,
			token: window.localStorage.getItem("token"),
			page: 1,
			size: 10
		},
		dataType: 'JSON'
	}).then(function(res) {
		console.log(res);
		if(res.err == 0) {
			_this.price = res.data.data;
		} else if(res.err == 1) {
			mui.alert("", res.msg, function() {
				_this.$router.push({
					name: 'login'
				});
			});
		} else if(res.err == 2) {
			_this.price = [];
		}
	}, function() {

	});

	$.ajax({
		url: '/api/qapi1/getReleaseMsgDetailReply',
		type: 'get',
		data: {
			id: _this.$route.query.id,
			user_id: _this.$route.query.userid,
			token: window.localStorage.getItem("token"),
			page: 1,
			size: 10
		},
		dataType: 'JSON'
	}).then(function(res) {
		console.log(res);
		if(res.err == 0) {
			_this.reply = res.data.data;
		} else if(res.err == 1) {
			mui.alert("", res.msg, function() {
				_this.$router.push({
					name: 'login'
					});
				});
			} else if(res.err == 2) {
				_this.reply = [];
			}
		}, function() {

		});

	}
}
</script>