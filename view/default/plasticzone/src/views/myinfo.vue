<template>
<div class="buyWrap" style="padding: 45px 0 70px 0;">
	<div style="position: fixed; top: 0; left: 0; width: 100%; z-index: 10;">
		<header id="bigCustomerHeader">
			<a class="back" href="javascript:window.history.back();"></a>
			我的资料
			<span v-if="isDisabled" v-on:click="editor" style="position: absolute; right: 10px; font-size: 14px;">编辑</span>
			<span v-if="!isDisabled" v-on:click="save" style="position: absolute; right: 10px; font-size: 14px;">保存</span>
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
		<table class="myinfotb" cellpadding="0" cellspacing="0">
			<tr>
				<td width="30%" style="padding: 0 0 0 15px;">地址：</td>
				<td style="padding: 0 15px 0 0;">
					<input v-bind:disabled="isDisabled" type="text" v-model="address" />
				</td>
			</tr>
			<tr>
				<td style="padding: 0 0 0 15px;">性别：</td>
				<td style="padding: 0 15px 0 7px;">
					<span v-if="isDisabled">{{sex}}</span>
					<span v-if="!isDisabled">
						<input type="radio" value="0" v-model="sexradio" />&nbsp;男&nbsp;
						<input type="radio" value="1" v-model="sexradio" />&nbsp;女
					</span>
				</td>
			</tr>
			<tr>
				<td style="padding: 0 0 0 15px;">所属地区：</td>
				<td style="padding: 0 15px 0 7px;">
					<span v-if="isDisabled">{{adistinct}}</span>
					<span v-if="!isDisabled">
						<input type="radio" value="EC" v-model="distinctradio" />&nbsp;华东&nbsp;
						<input type="radio" value="NC" v-model="distinctradio" />&nbsp;华北&nbsp;
						<input type="radio" value="SC" v-model="distinctradio" />&nbsp;华南&nbsp;
						<input type="radio" value="OT" v-model="distinctradio" />&nbsp;其他&nbsp;
					</span>
				</td>
			</tr>
			<tr>
				<td style="padding: 0 0 0 15px;">企业类型：</td>
				<td style="padding: 0 15px 0 7px;">
					<span v-if="isDisabled">
						{{c_nametype}}
					</span>
					<span v-if="!isDisabled">
						<input type="radio" value="1" v-on:change="ctypeShow" v-model="c_type" />&nbsp;塑料制品企业&nbsp;
						<input type="radio" value="2" v-on:change="ctypeShow" v-model="c_type" />&nbsp;原料供应商&nbsp;
						<input type="radio" value="4" v-on:change="ctypeShow" v-model="c_type" />&nbsp;物流服务商&nbsp;
					</span>					
				</td>
			</tr>
			<tr v-if="isType">
				<td style="padding: 0 0 0 15px;">产品：</td>
				<td style="padding: 0 15px 0 7px;">
					<span v-if="isDisabled">{{main_product}}</span>
					<input v-if="!isDisabled" v-bind:disabled="isDisabled" type="text" v-model="main_product" />
				</td>
			</tr>
			<tr v-if="isType">
				<td style="padding: 0 0 0 15px;">月用量：</td>
				<td style="padding: 0 15px 0 7px;">
					<span v-if="isDisabled">{{month_consum}}</span>
					<input v-if="!isDisabled" v-bind:disabled="isDisabled" type="text" v-model="month_consum" />
				</td>
			</tr>
			<tr>
				<td style="padding: 0 0 0 15px;">我的主营：</td>
				<td style="padding: 0 15px 0 0;">
					<input v-bind:disabled="isDisabled" type="text" v-model="need_product" />
				</td>
			</tr>
			<tr>
				<td style="padding: 0 0 0 15px;">关注的牌号：</td>
				<td style="padding: 0 15px 0 0;">
					<input v-bind:disabled="isDisabled" type="text" v-model="need_ph" />
				</td>
			</tr>
		</table>
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
		<div class="registerBox" style="height: auto; background: #FFFFFF; padding: 10px 0; margin: 10px 0 0 0; line-height: 0; text-align: center;">
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
	<loadingPage :loading="loadingShow"></loadingPage>
	<footerbar></footerbar>
</div>
</template>
<script>
import footer from "../components/footer";
import loadingPage from "../components/loadingPage";
export default{
	components: {
		'footerbar': footer,
		'loadingPage': loadingPage
	},
	data: function() {
		return {
			name: "",
			buy: "",
			sale: "",
			c_name: "",
			c_type:"",
			c_nametype:"",
			mobile: "",
			address: "",
			sex: "",
			status: "",
			thumb: "",
			concern_model: "",
			need_product: "",
			main_product:"",
			month_consum:"",
			isType:"",
			need_ph: "",
			rank: "",
			total: "",
			sexradio: "",
			distinctradio: "EC",
			cardImg: "",
			active: "",
			active2: "",
			active3: "",
			level: "",
			distinct: "",
			loadingShow: "",
			isDisabled:true
		}
	},
	beforeRouteEnter: function(to, from, next) {
		next(function(vm) {
			vm.loadingShow = true;
		});
	},
	methods: {
		editor:function(){
			this.isDisabled=false;
		},
		ctypeShow:function(){
			if(this.c_type=="1"){
				this.isType=true;
			}else{
				this.isType=false;
			}		
		},
		save:function(){
			var _this=this;
			this.isDisabled=true;
			$.ajax({
				url: '/api/qapi1_2/saveSelfInfo',
				type: 'post',
				data: {
					token: window.localStorage.getItem("token"),
					address:_this.address,
					sex:_this.sexradio,
					major:_this.need_product,
					concern:_this.need_ph,
					dist:_this.distinctradio,
					type:_this.c_type,
					month_consum:_this.month_consum,
					main_product:_this.main_product
				},
				dataType: 'JSON'
			}).then(function(res) {
				weui.alert(res.msg, {
					title: '塑料圈通讯录',
					buttons: [{
						label: '确定',
						type: 'parimary',
						onClick: function() {
							window.location.reload();
						}
					}]
				});
			}, function() {

			});
		},
		msgActive: function() {
			var _this = this;
			this.active == 0 ? this.active = 1 : this.active = 0;
			$.ajax({
				url: '/api/qapi1_2/favorateSet',
				type: 'post',
				data: {
					type: 0,
					is_allow: _this.active,
					token: window.localStorage.getItem("token")
				},
				dataType: 'JSON'
			}).then(function(res) {

			}, function() {

			});
		},
		msgActive2: function() {
			var _this = this;
			this.active2 == 0 ? this.active2 = 1 : this.active2 = 0;
			$.ajax({
				url: '/api/qapi1_2/favorateSet',
				type: 'post',
				data: {
					type: 1,
					is_allow: _this.active2,
					token: window.localStorage.getItem("token")
				},
				dataType: 'JSON'
			}).then(function(res) {

			}, function() {

			});
		},
		msgActive3: function() {
			var _this = this;
			this.active3 == 0 ? this.active3 = 1 : this.active3 = 0;
			$.ajax({
				url: '/api/qapi1_2/favorateSet',
				type: 'post',
				data: {
					type: 2,
					is_allow: _this.active3,
					token: window.localStorage.getItem("token")
				},
				dataType: 'JSON'
			}).then(function(res) {

			}, function() {

			});
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
				
			},
			onSuccess: function(res) {
				if(res.err == 0) {
					_this.cardImg = res.url;
				}
			},
			onError: function(err) {
				weui.alert("上传失败", {
					title: '塑料圈通讯录',
					buttons: [{
						label: '确定',
						type: 'parimary',
						onClick: function() {

						}
					}]
				});
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

			},
			onSuccess: function(res) {
				window.location.reload();
			},
			onError: function(err) {
				weui.alert("上传失败", {
					title: '塑料圈通讯录',
					buttons: [{
						label: '确定',
						type: 'parimary',
						onClick: function() {

						}
					}]
				});
			}
		});

		$.ajax({
			url: '/api/qapi1_2/getSelfInfo',
			type: 'post',
			data: {
				token: window.localStorage.getItem("token")
			},
			dataType: 'JSON'
		}).done(function(res) {
			if(res.err == 0) {
				_this.name = res.data.name;
				_this.c_name = res.data.c_name;
				_this.address = res.data.address;
				_this.mobile = res.data.mobile;
				_this.need_ph = res.data.concern_model;
				_this.need_product = res.data.need_product;
				_this.main_product = res.data.main_product;
				_this.month_consum = res.data.month_consum;
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
				_this.adistinct = res.data.adistinct;
				_this.c_type=res.data.type;
				if (_this.sex=="男") {
					_this.sexradio=0;
				} else{
					_this.sexradio=1;
				}
				if(_this.c_type=="2"){
					_this.c_nametype="原料供应商 ";
				}else if(_this.c_type=="1"){
					_this.c_nametype="塑料制品企业";
					_this.isType=true;
				}else if(_this.c_type=="4"){
					_this.c_nametype="物流服务商";
				}
			} else if(res.err == 1) {
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
			}
		}).fail(function(){
			
		}).always(function(){
			_this.loadingShow = false;
		});
	}

}
</script>