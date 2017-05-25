<template>
<div>
	<header id="bigCustomerHeader" style="position: fixed; top: 0; left: 0; z-index: 5;">
		我的塑料圈
		<a class="detailShare" href="javascript:;" v-on:click="shareshow"></a>
	</header>
	<div style="padding: 45px 0 70px 0;">
	<div class="myzoneHeader">
		<div class="myzoneInfo">
			<div style="width: 55px; height: 55px; margin: 0; float: left; position: relative;">
				<div class="avator">
					<img v-bind:src="thumb">
				</div>
				<i class="iconV" v-bind:class="{'v1':is_pass==1,'v2':is_pass==0}"></i>
			</div>
			<div class="myzonetxt">
				<p>{{c_name}}</p>
				<p>{{name}} {{mobile}}</p>
				<p>
					<router-link :to="{name:'myinfo'}" style="color: #6b6767;"><span style="float: left;">更改用户信息</span><i class="iconinfo edit"></i>上传名片加V认证</router-link>
				</p>
			</div>
			<div class="mui-clearfix"></div>
		</div>
		<div class="myzonenum">
			<span><router-link :to="{name:'mysupply'}">{{supply}}<br>供给</router-link></span>
			<span><router-link :to="{name:'mybuy'}">{{buy}}<br>求购</router-link></span>
			<span><router-link :to="{name:'mymsg'}">{{msg}}<br>留言</router-link></span>
			<span><router-link :to="{name:'myinvite'}">{{invite}}<br>引荐</router-link></span>
			<span><router-link :to="{name:'myfans'}">{{fans}}<br>粉丝</router-link></span>
			<span><router-link :to="{name:'mypay'}">{{pay}}<br>关注</router-link></span>
			<span><router-link :to="{name:'mypoints'}">{{points}}<br>塑豆</router-link></span>
		</div>
	</div>
	<ul class="myzoneUl">
		<li>
			<router-link :to="{name:'mysupply'}"><i class="iconZone zone"></i>我的供给<span>{{supply}}</span>
				<i class="icon2 rightArrow" style="right: 0; top: 8px;"></i></router-link>
		</li>
		<li>
			<router-link :to="{name:'mybuy'}"><i class="iconZone zone2"></i>我的求购<span>{{buy}}</span>
				<i class="icon2 rightArrow" style="right: 0; top: 8px;"></i></router-link>
		</li>
	</ul>
	<ul class="myzoneUl">
		<li>
			<router-link :to="{name:'myinvite'}"><i class="iconZone zone4"></i>我的引荐<i class="icon2 rightArrow" style="right: 0; top: 8px;"></i><span>{{invite}}</span></router-link>
		</li>
		<li>
			<router-link :to="{name:'myfans'}"><i class="iconZone zone5"></i>我的粉丝<i class="icon2 rightArrow" style="right: 0; top: 8px;"></i><span>{{fans}}</span></router-link>
		</li>
		<li>
			<router-link :to="{name:'mypay'}"><i class="iconZone zone6"></i>我的关注<i class="icon2 rightArrow" style="right: 0; top: 8px;"></i><span>{{pay}}</span></router-link>
		</li>
	</ul>
	<ul class="myzoneUl">
		<li>
			<router-link :to="{name:'mymsg'}"><i class="iconZone zone3"></i>我的留言<span>未读留言{{msg}}</span>
				<i class="icon2 rightArrow" style="right: 0; top: 8px;"></i></router-link>
		</li>
		<li>
			<router-link :to="{name:'mymsg2'}"><i class="iconZone zone9"></i>我的消息<span>未读消息{{msg2}}</span>
				<i class="icon2 rightArrow" style="right: 0; top: 8px;"></i></router-link>
		</li>
	</ul>
	<ul class="myzoneUl">
		<li>
			<router-link :to="{name:'mypoints'}"><i class="iconZone zone7"></i>我的塑豆<strong style="font-weight: normal; color: #FF0000;">HOT</strong>
				<span>可兑换礼品</span><i class="icon2 rightArrow" style="right: 0; top: 8px;"></i></router-link>
		</li>
	</ul>
	<ul class="myzoneUl">
		<li>
			<router-link :to="{name:'help'}"><i class="iconZone zone10"></i>帮助<span>常见问题及联系客服</span>
				<i class="icon2 rightArrow" style="right: 0; top: 8px;"></i></router-link>
		</li>
	</ul>
	<ul class="myzoneUl">
		<li style="text-align: center; color: #ff5000;" v-on:click="logout">
			退出登录
		</li>
	</ul>
	</div>
	<footerbar></footerbar>
	<loadingPage :loading="loadingShow"></loadingPage>
	<div class="sharelayer" v-show="share" v-on:click="sharehide"></div>
	<div class="tip" v-show="share3"></div>
</div>
</template>
<script>
import footer from "../components/footer";
import loadingPage from "../components/loadingPage";
module.exports = {
	components: {
		'footerbar': footer,
		'loadingPage': loadingPage
	},
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
		shareshow: function() {
			this.share = true;
			this.share3 = true;
		},
		sharehide: function() {
			this.share = false;
			this.share3 = false;
			this.share4 = false;
		},
		logout: function() {
			var _this = this;
			$.ajax({
				url: '/api/qapi1/logOut',
				type: 'get',
				data: {
					token: window.localStorage.getItem("token")
				},
				dataType: 'JSON'
			}).then(function(res) {
				console.log(res.err);
				if(res.err == 0) {
					window.localStorage.setItem("token", "");
					weui.alert(res.msg, {
						title: '塑料圈通讯录',
						buttons: [{
							label: '确定',
							type: 'parimary',
							onClick: function() {
								_this.$router.push({
									name: 'index'
								});
							}
						}]
					});
				} else {
					window.localStorage.setItem("token", "");
				}
			}, function() {

			});
		}
	},
	activated: function() {
		var _this = this;
		window.scrollTo(0, 0);
		this.loadingShow = true;
		try {
			var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
			piwikTracker.trackPageView();
		} catch(err) {

		}

		$.ajax({
			url: '/api/qapi1/myZone',
			type: 'get',
			data: {
				token: window.localStorage.getItem("token")
			},
			dataType: 'JSON'
		}).done(function(res) {
			if(res.err == 1) {
				weui.alert(res.msg, {
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
				});
			} else {
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
			}
		}).fail(function() {

		}).always(function() {
			_this.loadingShow = false;
		});
	}
}
</script>