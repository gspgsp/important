<template>
<div class="buyWrap" style="padding: 45px 0 70px 0;">
	<div style="position: fixed; top: 0; left: 0; width: 100%; z-index: 10;">
		<header id="bigCustomerHeader">
			<a class="back" href="javascript:window.history.back();"></a>
			我的资料
		</header>
	</div>
	<div class="personInfo">
		<div style="width: 80px; height: 80px; position: relative; float: left;">
			<div class="personAvator" id="uploader">
				<input type="file" accept="image/*" capture="camera" multiple="" style="width:80px; height: 80px; opacity: 0; position: absolute; top: 0; left: 0;">
				<img width="80" height="80" v-bind:src="thumb">
			</div>
			<i class="photo"></i>
		</div>
		<div class="personName">{{name}}&nbsp;<span style="font-size: 12px; color: #63769d;">等级:{{level}}</span></div>
		<div class="personNum">{{c_name}}</div>
		<div class="personNum">电话：{{mobile}}</div>
		<div class="personNum">
			发布供给：<span style=" color: #63769d;">{{sale}}条</span> 发布需求：<span style=" color: #63769d;">{{buy}}条</span>
		</div>
		<div class="personInfoList2" style="margin: 20px 0 0 0;">
			<p><span style="color: #333333; font-size: 12px;">地址：</span>
				<b v-show="addressshow">{{address}}</b>
				<strong v-show="!addressshow" class="address">
<input type="text" v-model="address" />
<input type="button" value="提交" style="position: absolute; top: 3px;" v-on:click="addresssubmit" />
</strong>
				<i class="editicon" v-on:click="edit" v-show="addressshow"></i>
			</p>
			<p><span style="color: #333333; font-size: 12px;">性别：</span>
				<b v-show="sexshow">{{sex}}</b>
				<b v-show="!sexshow">
<input type="radio" value="0" v-model="sexradio" />&nbsp;男&nbsp;
<input type="radio" value="1" v-model="sexradio" />&nbsp;女
<input type="button" value="提交" v-on:click="sexsubmit" />
</b>
				<i class="editicon" v-on:click="edit2" v-show="sexshow"></i>
			</p>
			<p><span style="color: #333333; font-size: 12px;">所属地区：</span>
				<b v-show="distinctshow">{{adistinct}}</b>
				<b v-show="!distinctshow">
<input type="radio" value="EC" v-model="distinctradio" />&nbsp;华东&nbsp;
<input type="radio" value="NC" v-model="distinctradio" />&nbsp;华北&nbsp;
<input type="radio" value="SC" v-model="distinctradio" />&nbsp;华南&nbsp;
<input type="button" value="提交" v-on:click="distinctsubmit" />
</b>
				<i class="editicon" v-on:click="edit5" v-show="distinctshow"></i>
			</p>
			<div style="position: relative;">
				<div v-show="!zyshow" style=" position: absolute; top: -30px; left: 75px; border-radius: 5px; background: rgba(0,0,0,0.7); color: #FFFFFF; font-size: 12px; padding: 2px 5px;">不同牌号之间用空格分开</div>
				<p><span style="color: #333333; font-size: 12px;">我的主营：</span>
					<b v-show="zyshow">{{need_product}}</b>
					<strong v-show="!zyshow" class="address">
<input type="text" style="width: 160px; float: right; margin: 0 60px 0 0;" v-model="need_product" />
<input type="button" value="提交" style="position: absolute; top: 3px;" v-on:click="zysubmit" />
</strong>
					<i class="editicon" v-show="zyshow" v-on:click="edit3"></i>
				</p>
			</div>
			<div style="position: relative;">
				<div v-show="!phshow" style=" position: absolute; top: -30px; left: 75px; border-radius: 5px; background: rgba(0,0,0,0.7); color: #FFFFFF; font-size: 12px; padding: 2px 5px;">不同牌号之间用空格分开</div>
				<p style=" border: none;"><span style="color: #333333; font-size: 12px;">关注的牌号：</span>
					<b v-show="phshow">{{concern_model}}</b>
					<strong v-show="!phshow" class="address">
<input type="text" style="width: 160px; float: right; margin: 0 60px 0 0;" v-model="need_ph" />
<input type="button" value="提交" style="position: absolute; top: 3px;" v-on:click="phsubmit" />
</strong>
					<i class="editicon" v-show="phshow" v-on:click="edit4"></i>
				</p>
			</div>
		</div>
		<div class="mui-content">
			<ul id="shortmsg" class="mui-table-view">
				<li class="mui-table-view-cell">
					<span style="color: #333333;">公开手机号码</span>
				</li>
				<li class="mui-table-view-cell">
					是否公开
					<div class="mui-switch mui-switch-mini" v-on:click="msgActive3" v-bind:class="{'mui-active':!active3}">
						<div class="mui-switch-handle"></div>
					</div>
				</li>
			</ul>
		</div>
		<div class="mui-content">
			<ul id="shortmsg" class="mui-table-view">
				<li class="mui-table-view-cell">
					<span style="color: #333333;">手机短信设置</span>
				</li>
				<li class="mui-table-view-cell">
					有人关注我，手机短信提醒
					<div class="mui-switch mui-switch-mini" v-on:click="msgActive" v-bind:class="{'mui-active':!active}">
						<div class="mui-switch-handle"></div>
					</div>
				</li>
				<li class="mui-table-view-cell">
					有人回复我的供求，手机短信提醒
					<div class="mui-switch mui-switch-mini" v-on:click="msgActive2" v-bind:class="{'mui-active':!active2}">
						<div class="mui-switch-handle"></div>
					</div>
				</li>
			</ul>
		</div>
		<div class="registerBox" style="height: auto; padding: 10px 0; margin: 10px 0 0 0; line-height: 0; text-align: center;">
			<div class="card">
				<img v-bind:src="cardImg">
			</div>
			<div class="card2" id="uploaderCard">
				<input type="file" name="upFile" style="width:133px; height: 73px; opacity: 0; position: absolute; top: 0; left: 0;">
				<div class="card2upload" v-show="!cardshow"></div>
				<div class="card2success" v-show="cardshow"></div>
			</div>
			<div class="personInfoList2">
				<div style=" font-size: 13px; color: #8f8f94; line-height: 20px; text-align: left; border-top: 1px solid #d1d1d1; padding: 10px 15px 0 15px;">通讯录排序:您目前排在通讯录的第{{rank}}位，共{{total}}人，按照粉丝数量、发布求购数量、发布供给数量进行排序</div>
			</div>
		</div>
	</div>
	<footerbar></footerbar>
</div>
</template>
<script>
import footer from "../components/footer";
module.exports = {
	components: {
		'footerbar': footer
	},
	data: function() {
		return {
			name: "",
			buy: "",
			sale: "",
			c_name: "",
			mobile: "",
			address: "",
			sex: "",
			status: "",
			thumb: "",
			concern_model: "",
			need_product: "",
			need_ph: "",
			rank: "",
			total: "",
			sexshow: true,
			addressshow: true,
			zyshow: true,
			phshow: true,
			sexradio: 0,
			distinctradio: "EC",
			cardImg: "",
			active: "",
			active2: "",
			active3: "",
			level: "",
			distinct: "",
			distinctshow: true
		}
	},
	methods: {
		msgActive: function() {
			var _this = this;
			this.active == 0 ? this.active = 1 : this.active = 0;
			$.ajax({
				url: '/api/qapi1/favorateSet',
				type: 'get',
				data: {
					type: 0,
					is_allow: _this.active,
					token: window.localStorage.getItem("token")
				},
				dataType: 'JSON'
			}).then(function(res) {
				mui.alert("", res.msg, function() {
					window.location.reload();
				});
			}, function() {

			});
		},
		msgActive2: function() {
			var _this = this;
			this.active2 == 0 ? this.active2 = 1 : this.active2 = 0;
			$.ajax({
				url: '/api/qapi1/favorateSet',
				type: 'get',
				data: {
					type: 1,
					is_allow: _this.active2,
					token: window.localStorage.getItem("token")
				},
				dataType: 'JSON'
			}).then(function(res) {
				mui.alert("", res.msg, function() {
					window.location.reload();
				});
			}, function() {

			});
		},
		msgActive3: function() {
			var _this = this;
			this.active3 == 0 ? this.active3 = 1 : this.active3 = 0;
			$.ajax({
				url: '/api/qapi1/favorateSet',
				type: 'get',
				data: {
					type: 2,
					is_allow: _this.active3,
					token: window.localStorage.getItem("token")
				},
				dataType: 'JSON'
			}).then(function(res) {
				mui.alert("", res.msg, function() {
					window.location.reload();
				});
			}, function() {

			});
		},
		edit: function() {
			this.addressshow = false;
		},
		edit2: function() {
			this.sexshow = false;
		},
		edit5: function() {
			this.distinctshow = false;
		},
		addresssubmit: function() {
			var _this = this;
			$.ajax({
				url: '/api/qapi1/saveSelfInfo',
				type: 'get',
				data: {
					type: 1,
					field: _this.address,
					token: window.localStorage.getItem("token")
				},
				dataType: 'JSON'
			}).then(function(res) {
				mui.alert("", res.msg, function() {
					window.location.reload();
				});
			}, function() {

			});
		},
		sexsubmit: function() {
			var _this = this;
			$.ajax({
				url: '/api/qapi1/saveSelfInfo',
				type: 'get',
				data: {
					type: 2,
					field: _this.sexradio,
					token: window.localStorage.getItem("token")
				},
				dataType: 'JSON'
			}).then(function(res) {
				mui.alert("", res.msg, function() {
					window.location.reload();
				});
			}, function() {

			});
		},
		distinctsubmit: function() {
			var _this = this;
			$.ajax({
				url: '/api/qapi1/saveSelfInfo',
				type: 'get',
				data: {
					type: 5,
					field: _this.distinctradio,
					token: window.localStorage.getItem("token")
				},
				dataType: 'JSON'
			}).then(function(res) {
				mui.alert("", res.msg, function() {
					window.location.reload();
				});
			}, function() {

			});
		},
		zysubmit: function() {
			var _this = this;
			$.ajax({
				url: '/api/qapi1/saveSelfInfo',
				type: 'get',
				data: {
					type: 3,
					field: _this.need_product,
					token: window.localStorage.getItem("token")
				},
				dataType: 'JSON'
			}).then(function(res) {
				mui.alert("", res.msg, function() {
					window.location.reload();
				});
			}, function() {

			});
		},
		phsubmit: function() {
			var _this = this;
			$.ajax({
				url: '/api/qapi1/saveSelfInfo',
				type: 'get',
				data: {
					type: 4,
					field: _this.need_ph,
					token: window.localStorage.getItem("token")
				},
				dataType: 'JSON'
			}).then(function(res) {
				mui.alert("", res.msg, function() {
					window.location.reload();
				});
			}, function() {

			});
		},
		edit3: function() {
			this.zyshow = false;
		},
		edit4: function() {
			this.phshow = false;
		}
	},
	activated: function() {
		var _this = this;
		window.scrollTo(0,0);
		try {
			var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
			piwikTracker.trackPageView();
		} catch(err) {

		}
		
		weui.uploader('#uploaderCard', {
			url: '/api/qapi1/saveCardImg',
			auto: true,
			type: 'file',
			fileVal: 'fileVal',
			data: {
				token: window.localStorage.getItem("token")
			},
			compress: {
				width: 500,
				height: 500,
				quality: .5
			},
			onBeforeQueued: function(files) {
				// `this` 是轮询到的文件, `files` 是所有文件
				if(["image/jpg", "image/jpeg", "image/png", "image/gif"].indexOf(this.type) < 0) {
					weui.alert('请上传图片');
					return false; // 阻止文件添加
				}
				if(this.size > 5 * 1024 * 1024) {
					weui.alert('请上传不超过5M的图片');
					return false;
				}
			},
			onQueued: function() {
				console.log(this);
			},
			onSuccess: function(res) {
				if(res.err == 0) {
					_this.cardImg = res.url;
				}
			},
			onError: function(err) {
				console.log("error",this, err);
			}
		});

		weui.uploader('#uploader', {
			url: '/api/qapi1/savePicToServer',
			auto: true,
			type: 'file',
			fileVal: 'fileVal',
			data: {
				token: window.localStorage.getItem("token")
			},
			compress: {
				width: 500,
				height: 500,
				quality: .5
			},
			onBeforeQueued: function(files) {
				// `this` 是轮询到的文件, `files` 是所有文件
				if(["image/jpg", "image/jpeg", "image/png", "image/gif"].indexOf(this.type) < 0) {
					weui.alert('请上传图片');
					return false; // 阻止文件添加
				}
				if(this.size > 5 * 1024 * 1024) {
					weui.alert('请上传不超过5M的图片');
					return false;
				}
			},
			onQueued: function() {
				console.log(this);
			},
			onSuccess: function(res) {
				console.log("success",this, res);
				window.location.reload();
			},
			onError: function(err) {
				console.log("error",this, err);
			}
		});

		$.ajax({
			url: '/api/qapi1/getSelfInfo',
			type: 'get',
			data: {
				token: window.localStorage.getItem("token")
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
				_this.adistinct = res.data.adistinct
			} else if(res.err == 1) {
				mui.alert("", res.msg, function() {
					_this.$router.push({
						name: 'login'
					});
				});
			}
		}, function() {

		});
	}

}
</script>