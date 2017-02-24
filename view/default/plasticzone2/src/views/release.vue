<template>
<div style=" padding: 90px 0 60px 0;">
<div style="position: fixed; top: 0; left: 0; width: 100%; z-index: 6;">
	<header id="bigCustomerHeader">
		<span class="releaseinfo">供求信息</span>
		<div style="padding: 7px 0 0 0;">
			<div class="releasesearch" style="text-align: left;">
				<form action="javascript:;">
					<i v-on:click="search" class="searchIcon"></i><input v-on:keydown.enter="search" type="text" placeholder="请输入厂家或牌号" v-model="keywords" />
				</form>
			</div>
		</div>
		<div id="searchbox" v-on:click="filterShow2" style=" font-size: 14px; background: #FFFFFF; border-radius: 3px;">
			{{txt2}}
		</div>
	</header>
	<div class="releasefilter">
		<span v-on:click="choosedq('ALL')" v-bind:class="{'on':filter1}">全部</span>
		<!--<span v-on:click="filterShow" v-bind:class="{'on':filter1}">{{txt}}<i class="downarrow"></i></span>-->
		<span v-bind:class="{'on':filter2}" v-on:click="recommand">智能推荐</span>
		<span v-bind:class="{'on':filter3}" v-on:click="pay">我的关注</span>
		<span v-bind:class="{'on':filter4}" v-on:click="supplydemand">我的供求</span>
	</div>
</div>
<ul id="releaseUl">
	<li v-show="condition" v-for="r in release">
		<router-link :to="{name:'personinfo',params:{id:r.user_id}}" style="display: block; height: 35px; overflow: hidden;">
			<div class="myreleaseInfo">
				<div style="width: 30px; height: 30px; float: left; position: relative;">
					<div class="avator">
						<img v-bind:src="r.thumb">
					</div>
					<i class="iconV" v-bind:class="{'v1':r.is_pass==null||r.is_pass==1,'v2':r.is_pass==0}"></i>
				</div>
				<div class="myreleasetxt">
					<p style="line-height: 30px;">{{r.c_name}}&nbsp;{{r.name}}</p>
				</div>
			</div>
		</router-link>
		<div class="myreleasetxt2">
			<router-link :to="{name:'releasedetail', query:{id: r.id,userid:r.user_id}}">
				<p>
					<strong v-if="r.type==2" style=" color: #63769d;">
						<i class="iconSale"></i>供给</strong>
					<strong v-else style="color: #ea8010;">
						<i class="iconBuy"></i>求购
					</strong>
					<strong v-html="r.contents"></strong>
				</p>
			</router-link>
				<p style="color: #999999;">
					{{r.input_time}}
					<span v-show="mine" style="margin: 0 0 0 3px; color: #999999;">
						<router-link style=" color: #999999;" :to="{name:'supplybuy',params:{id:r.id}}">
						<i class="releaseshareicon"></i>分享
						</router-link>
					</span>
					<span v-show="mine" style="margin: 0 0 0 3px; color: #999999;">
						<router-link v-show="r.type==1" style=" color: #999999;" :to="{name:'releasebsbuy',query: { id: r.id}}">
						<i class="releaseresendicon"></i>重发
						</router-link>
						<router-link v-show="r.type==2" style=" color: #999999;" :to="{name:'releasebssupply',query: { id: r.id}}">
						<i class="releaseresendicon"></i>重发
						</router-link>
					</span>
					<span style="margin: 0 0 0 3px; color: #999999;">
						<router-link v-show="!mine" style=" color: #999999;" :to="{name:'releasedetail', query:{id: r.id,userid:r.user_id,tab:2}}">
						<i class="releasereplyicon"></i>回复<i style="color: #63769d; font-style: normal;">({{r.saysCount}})</i>
						</router-link>
						<router-link v-show="mine" style=" color: #999999;" :to="{name:'releasedetail', query:{id: r.id,userid:r.user_id,tab:2}}">
						<i class="releasereplyicon"></i>看回复<i style="color: #63769d; font-style: normal;">({{r.saysCount}})</i>
						</router-link>
					</span>
					<span style=" color: #999999;">
						<router-link v-show="!mine" style=" color: #999999;" :to="{name:'releasedetail', query:{id: r.id,userid:r.user_id,tab:1}}">
						<i class="releasesaleicon"></i>出价<i style="color: #63769d; font-style: normal;">({{r.deliverPriceCount}})</i>
						</router-link>
						<router-link v-show="mine" style=" color: #999999;" :to="{name:'releasedetail', query:{id: r.id,userid:r.user_id,tab:1}}">
						<i class="releasesaleicon"></i>看出价<i style="color: #63769d; font-style: normal;">({{r.deliverPriceCount}})</i>
						</router-link>
					</span>
				</p>
		</div>
	</li>
	<li v-show="!condition" style="text-align: center;">
			没有相关数据
	</li>
</ul>
<div v-show="condition" style="text-align: center; padding: 5px 0 15px 0;">
	<input class="more" v-on:click="more" type="button" v-model="moreTxt">
</div>
<footerbar></footerbar>
<div class="refresh" v-bind:class="{circle:isCircle}" v-on:click="circle"></div>
<div class="arrow" v-show="isArrow" v-on:click="arrow"></div>
<div class="filterwrap" style="background: #FFFFFF;" v-show="filtershow">
	<i class="right" v-bind:class="{on:right1}"></i><span v-bind:class="{on:right1}" v-on:click="choosedq('ALL')">全国站</span>
	<i class="right" v-bind:class="{on:right2}"></i><span v-bind:class="{on:right2}" v-on:click="choosedq('EC')">华东站</span>
	<i class="right" v-bind:class="{on:right3}"></i><span v-bind:class="{on:right3}" v-on:click="choosedq('SC')">华南站</span>
	<i class="right" v-bind:class="{on:right4}"></i><span v-bind:class="{on:right4}" v-on:click="choosedq('NC')">华北站</span>
	<i class="right" v-bind:class="{on:right5}"></i><span v-bind:class="{on:right5}" v-on:click="choosedq('OTHER')">其他</span>
</div>
<div class="filterwrap" style="background: #FFFFFF;" v-show="filtershow2">
	<i class="right" v-bind:class="{on:on1}"></i><span v-bind:class="{on:on1}" v-on:click="selectstatus(0)">全部</span>
	<i class="right" v-bind:class="{on:on2}"></i><span v-bind:class="{on:on2}" v-on:click="selectstatus(2)">供给</span>
	<i class="right" v-bind:class="{on:on3}"></i><span v-bind:class="{on:on3}" v-on:click="selectstatus(1)">求购</span>
</div>
<div class="indexlayer" v-on:click="layershow" v-show="filtershow"></div>
<div class="indexlayer" v-on:click="layershow" v-show="filtershow2"></div>
</div>
</template>
<script>
import footer from "../components/footer";
module.exports = {
components:{
	'footerbar':footer
},
data: function() {
	return {
		keywords: "",
		page: 1,
		release: [],
		type: 0,
		store_house: "",
		model: "",
		f_name: "",
		deal_price: "",
		remark: "",
		show: false,
		content: "",
		id: "",
		user_id: "",
		selected: "",
		isCircle: false,
		isArrow: false,
		sortfield1:"ALL",
		sortfield2:"AUTO",
		filter1:false,
		filter2:true,
		filter3:false,
		filter4:false,
		condition: true,
		txt:"全国站",
		txt2:"全部",
		filtershow:false,
		filtershow2:false,
		mine:false,
		on1:true,
		on2:false,
		on3:false,
		right1:true,
		right2:false,
		right3:false,
		right4:false,
		right5:false,
		moreTxt:"加载更多数据",
		isIndex: false,
		isRelease: false,
		isMyzone: false,
		isHeadline: false,
		isReleaseshow: false,
		buy: [],
		supply: []
	}
},
methods: {
	layershow:function(){
		this.filtershow = false;
		this.filtershow2 = false;
	},
	selectstatus:function(slc){
		var _this=this;
		_this.selected=slc;
		_this.condition = true;
		_this.filtershow2 = false;
		this.page=1;
		switch (slc){
			case 0:
				_this.txt2='全部';
				_this.on1=true;
				_this.on2=false;
				_this.on3=false;
				break;
			case 1:
				_this.txt2='求购';
				_this.on1=false;
				_this.on2=false;
				_this.on3=true;
				break;
			case 2:
				_this.txt2='供给';
				_this.on1=false;
				_this.on2=true;
				_this.on3=false;
				break;
			default:
				break;
		}
		$.ajax({
			url: '/api/qapi1/getReleaseMsg',
			type: 'get',
			data: {
				keywords: _this.keywords.toLocaleUpperCase(),
				page: _this.page,
				type:_this.selected,
				sortField1:_this.sortfield1,
				sortField2:_this.sortfield2,
				token: window.localStorage.getItem("token"),
				size: 10
			},
			dataType: 'JSON'
		}).then(function(res) {
			console.log(res)
			if(res.err == 0) {
				_this.release = res.data;
			}else if(res.err==2||res.err==5){
				_this.condition = false;
			}
		}, function() {

		});
	},
	choosedq:function(dq){
		window.scrollTo(0,0);
		var _this=this;
		this.filter1=true;
		this.filter2=false;
		this.filter3=false;
		this.filter4=false;
		switch (dq){
			case 'NC':
				_this.txt='华北站';
				_this.right1=false;
				_this.right2=false;
				_this.right3=false;
				_this.right4=true;
				_this.right5=false;
				break;
			case 'EC':
				_this.txt='华东站';
				_this.right1=false;
				_this.right2=true;
				_this.right3=false;
				_this.right4=false;
				_this.right5=false;
				break;
			case 'SC':
				_this.txt='华南站';
				_this.right1=false;
				_this.right2=false;
				_this.right3=true;
				_this.right4=false;
				_this.right5=false;
				break;
			case 'OTHER':
				_this.txt='其他';
				_this.right1=false;
				_this.right2=false;
				_this.right3=false;
				_this.right4=false;
				_this.right5=true;
				break;
			case 'ALL':
				_this.txt='全国站';
				_this.right1=true;
				_this.right2=false;
				_this.right3=false;
				_this.right4=false;
				_this.right5=false;
				break;
			default:
				break;
		}
		_this.filtershow=false;
		_this.filtershow2=false;
		_this.sortfield1=dq;
		_this.sortfield2="";
		_this.condition = true;
		this.page=1;
		$.ajax({
			url: '/api/qapi1/getReleaseMsg',
			type: 'get',
			data: {
				keywords: _this.keywords.toLocaleUpperCase(),
				page: _this.page,
				type:_this.selected,
				sortField1:_this.sortfield1,
				sortField2:_this.sortfield2,
				token: window.localStorage.getItem("token"),
				size: 10
			},
			dataType: 'JSON'
		}).then(function(res) {
			console.log(res)
			if(res.err == 0) {
				_this.release = res.data;
			}else if(res.err==2||res.err==5){
				_this.condition = false;
			}
		}, function() {

		});
	},
	filterShow:function(){
		this.filtershow=true;
		this.filtershow2=false;
		this.mine=false;
	},
	filterShow2:function(){
		this.filtershow=false;
		this.filtershow2=true;
		this.mine=false;
	},
	search:function(){
		var _this=this;
		this.condition = true;
		_this.filtershow=false;
		_this.filtershow2=false;
		this.page=1;
		try {
		    var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
		    piwikTracker.trackSiteSearch(this.keywords, "keywords", 20);
		} catch( err ) {}

		$.ajax({
			url: '/api/qapi1/getReleaseMsg',
			type: 'get',
			data: {
				keywords: _this.keywords.toLocaleUpperCase(),
				page: _this.page,
				type:_this.selected,
				sortField1:_this.sortfield1,
				sortField2:_this.sortfield2,
				token: window.localStorage.getItem("token"),
				size: 10
			},
			dataType: 'JSON'
		}).then(function(res) {
			console.log(res)
			if(res.err == 0) {
				_this.release = res.data;
			} else if(res.err == 1) {
				mui.alert("", res.msg, function() {
					_this.$router.push({
						name: 'login'
					});
				});
			}else if(res.err==2||res.err==5){
			_this.condition = false;
		}

		}, function() {

		});
	},
	recommand:function(){
		window.scrollTo(0,0);
		var _this=this;
		this.condition = true;
		this.filtershow=false;
		_this.filtershow2=false;
		this.page=1;
		this.filter1=false;
		this.filter2=true;
		this.filter3=false;
		this.filter4=false;
		this.sortfield1="";
		this.sortfield2="AUTO";
		$.ajax({
			url: '/api/qapi1/getReleaseMsg',
			type: 'get',
			data: {
				keywords: _this.keywords.toLocaleUpperCase(),
				page: _this.page,
				type:_this.selected,
				sortField1:_this.sortfield1,
				sortField2:_this.sortfield2,
				token: window.localStorage.getItem("token"),
				size: 10
			},
			dataType: 'JSON'
		}).then(function(res) {
			console.log(res)
			if(res.err == 0) {
				_this.release = res.data;
				_this.mine=false;
			} else if(res.err == 1) {
				mui.alert("", res.msg, function() {
					_this.$router.push({
						name: 'login'
					});
				});
			}else if(res.err==2||res.err==5){
			_this.condition = false;
		}

		}, function() {

		});		
	},
	pay:function(){
		window.scrollTo(0,0);
		var _this=this;
		this.condition = true;
		this.filtershow=false;
		_this.filtershow2=false;
		this.page=1;
		this.filter1=false;
		this.filter2=false;
		this.filter3=true;
		this.filter4=false;
		this.sortfield1="";
		this.sortfield2="CONCERN";
		$.ajax({
			url: '/api/qapi1/getReleaseMsg',
			type: 'get',
			data: {
				keywords: _this.keywords.toLocaleUpperCase(),
				page: _this.page,
				type:_this.selected,
				sortField1:_this.sortfield1,
				sortField2:_this.sortfield2,
				token: window.localStorage.getItem("token"),
				size: 10
			},
			dataType: 'JSON'
		}).then(function(res) {
			console.log(res)
			if(res.err == 0) {
				_this.release = res.data;
				_this.mine=false;
			} else if(res.err == 1) {
				mui.alert("", res.msg, function() {
					_this.$router.push({
						name: 'login'
					});
				});
			}else if(res.err==2||res.err==5){
			_this.condition = false;
		}

		}, function() {

		});		
		
	},
	supplydemand:function(){
		window.scrollTo(0,0); 
		var _this=this;
		this.condition = true;
		this.filtershow=false;
		this.page=1;
		this.filtershow2=false;
		this.filter1=false;
		this.filter2=false;
		this.filter3=false;
		this.filter4=true;
		this.sortfield1="";
		this.sortfield2="DEMANDORSUPPLY";
		$.ajax({
			url: '/api/qapi1/getReleaseMsg',
			type: 'get',
			data: {
				keywords: _this.keywords.toLocaleUpperCase(),
				page: _this.page,
				type:_this.selected,
				sortField1:_this.sortfield1,
				sortField2:_this.sortfield2,
				token: window.localStorage.getItem("token"),
				size: 10
			},
			dataType: 'JSON'
		}).then(function(res) {
			console.log(res)
			if(res.err == 0) {
				_this.release = res.data;
				_this.mine=true;
			} else if(res.err == 1) {
				mui.alert("", res.msg, function() {
					_this.$router.push({
						name: 'login'
					});
				});
			}else if(res.err==2||res.err==5){
			_this.condition = false;
		}

		}, function() {

		});		
		
	},
	arrow: function() {
		window.scrollTo(0, 0);
	},
	circle: function() {
		var _this = this;
		this.isCircle = true;
		this.page=1;
		$.ajax({
			url: '/api/qapi1/getReleaseMsg',
			type: 'get',
			data: {
				keywords: "",
				page: _this.page,
				sortField1:_this.sortfield1,
				sortField2:_this.sortfield2,
				token: window.localStorage.getItem("token"),
				size: 10
			},
			dataType: 'JSON'
		}).then(function(res) {
			console.log(res)
			if(res.err == 0) {
				_this.release = res.data;
				_this.isCircle=false;
				window.scrollTo(0,0);
				mui.toast('更新成功',{
				    duration:'long',
				    type:'div' 
				}) ;
			} else if(res.err == 1) {
				mui.alert("", res.msg, function() {
					_this.$router.push({
						name: 'login'
					});
				});
			}

		}, function() {

		});
	},
	more:function(){
		var _this=this;
			_this.page++;
			_this.moreTxt="加载中..."
			$.ajax({
				type: "get",
				url: "/api/qapi1/getReleaseMsg",
				data: {
					keywords: _this.keywords.toLocaleUpperCase(),
					page: _this.page,
					type:_this.selected,
					sortField1:_this.sortfield1,
					sortField2:_this.sortfield2,
					token: window.localStorage.getItem("token"),
					size: 10
				},
				dataType: 'JSON'
			}).then(function(res) {
				console.log(res);
				if(res.err == 0) {
					_this.release = _this.release.concat(res.data);
					_this.moreTxt="加载更多数据";
				} else if(res.err == 1) {
					_this.moreTxt="加载更多数据";
					mui.alert("", res.msg, function() {
						_this.$router.push({
							name: 'login'
						});
					});
				} else if(res.err==3){
					_this.moreTxt="加载更多数据";
					mui.toast(res.msg,{
					    duration:'long',
					    type:'div' 
					}) ;
				}
			}, function() {

			});
	}
},
mounted: function() {
	var _this = this;
		try {
	    var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
	    piwikTracker.trackPageView();
	} catch( err ) {
		
	}
	$(window).scroll(function() {
		var scrollTop = $(this).scrollTop();
		if(scrollTop > 600) {
			_this.isArrow = true;
		} else {
			_this.isArrow = false;
		}
	});
	
	$.ajax({
		type: "get",
		url: "/api/qapi1/supplyDemandList",
		data: {
			page: 1,
			token: window.localStorage.getItem("token"),
			size: 5,
			type: 1
		},  
		dataType: 'JSON'
	}).then(function(res) {
		console.log(res);
		if(res.err == 0) {
			_this.buy = res.data;
		} else if(res.err == 1) {
			_this.buy = [];
		} else if(res.err == 2) {
			_this.buy = [];
		}
	}, function() {

	});

	$.ajax({
		type: "get",
		url: "/api/qapi1/supplyDemandList",
		data: {
			page: 1,
			token: window.localStorage.getItem("token"),
			size: 5,
			type: 2
		},
		dataType: 'JSON'
	}).then(function(res) {
		console.log(res);
		if(res.err == 0) {
			_this.supply = res.data;
		} else if(res.err == 1) {
			_this.supply = [];
		} else if(res.err == 2) {
			_this.supply = [];
		}
	}, function() {

	});

	
	$.ajax({
		url: '/api/qapi1/getReleaseMsg',
		type: 'get',
		data: {
			keywords: _this.keywords.toLocaleUpperCase(),
			page: _this.page,
			type: _this.selected,
			sortField1:_this.sortfield1,
			sortField2:_this.sortfield2,
			token: window.localStorage.getItem("token"),
			size: 10
		},
		dataType: 'JSON'
	}).then(function(res) {
		console.log(res)
		if(res.err == 0) {
			_this.release = res.data;
		} else if(res.err == 1) {
			mui.alert("", res.msg, function() {
				_this.$router.push({
					name: 'login'
				});
			});
		}else if(res.err==2||res.err==5||res.err==3){
			_this.condition = false;
		}

	}, function() {

	});

}
}
</script>