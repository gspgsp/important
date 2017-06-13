<template>
<div>
	<header id="bigCustomerHeader">
		<a class="back" href="javascript:window.history.back();"></a>
		塑豆商城
		<router-link style="position: absolute; right: 5px; font-size: 12px; color: #FFFFFF;" :to="{name:'pointsrule'}">如何赚塑豆</router-link>
	</header>
	<div class="shopBanner">
		<img width="100%" src="http://statics.myplas.com/myapp/img/shopBanner.jpg">
	</div>
	<div class="mypoints">
		<router-link :to="{name:'pointsdetail'}"><i class="shopIcon iconPoints"></i><span>{{points}}</span>塑豆</router-link>
		<router-link :to="{name:'pointsrecord'}"><i class="shopIcon iconIntro"></i>购买记录</router-link>
		<router-link v-if="wechat" style=" color: #ff5000;" :to="{name:'pay'}">
			<i class="shopIcon iconRecord"></i>充值塑豆
		</router-link>		
		<router-link v-else style=" color: #ff5000;" :to="{name:'recharge'}">
			<i class="shopIcon iconRecord"></i>充值塑豆
		</router-link>
	</div>
	<div class="pointsWrap">
		<div class="pointsTitle">商品信息</div>
		<ul id="productUl">
			<li>
				<div style="overflow: hidden; padding: 10px 0 0 0; position: relative;">
					<img v-bind:src="p1.thumb">
				</div>
				<div v-if="daySelected.length==0" class="productNum">
					<span>*</span>请选置顶日期：
					<i class="iconSelect" v-on:click="calendarShow"></i>
				</div>
				<div v-else class="calendarSelected">
					<span>已选择：</span>
					<div style="width: auto; margin: 0 25px 0 0; overflow: hidden;">
						<div class="calendarRange2" style="width: 100%;">
							<span v-for="d in daySelected">{{new Date(d).getMonth()+1}}月{{new Date(d).getDate()}}日</span>
						</div>
					</div>
					<i class="iconSelect" v-on:click="calendarShow"></i>
				</div>
				<div class="productCost">共<span>{{daySelected.length}}</span>件
					<div class="exchange" v-on:click="proExchange">支付</div>
					<div class="cost">总计：{{pro.cost*daySelected.length}}塑豆</div>
				</div>
			</li>
			<li>
				<div style="overflow: hidden; padding: 10px 0 0 0; position: relative;">
					<img v-bind:src="p2.thumb">
				</div>

				<div v-if="!selectedTxt" class="productMsg" style="line-height: 45px;">
					<span>*</span>请选择置顶供求信息：
					<i class="iconSelect" v-on:click="releaseWrapShow"></i>
				</div>
				<div v-else class="productMsg" style="line-height: 45px;">
					<span style="color: #333333;">已选择：</span>{{selectedTxt}}
					<i class="iconSelect" v-on:click="releaseWrapShow"></i>
				</div>

				<div v-if="daySelected2.length==0" class="productNum">
					<span>*</span>请选置顶日期：
					<i class="iconSelect" v-on:click="calendarShow2"></i>
				</div>
				<div v-else class="calendarSelected" style="border-top: 1px solid #D9D9D9;">
					<span>已选择：</span>
					<div style="width: auto; margin: 0 25px 0 0; overflow: hidden;">
						<div class="calendarRange2" style="width: 100%;">
							<span v-for="d in daySelected2">{{new Date(d).getMonth()+1}}月{{new Date(d).getDate()}}日</span>
						</div>
					</div>
					<i class="iconSelect" v-on:click="calendarShow2"></i>
				</div>

				<div class="productCost">共<span>{{daySelected2.length}}</span>件
					<div class="exchange" v-on:click="proExchange2">支付</div>
					<div class="cost">总计：{{pro2.cost*daySelected2.length}}塑豆</div>
				</div>
			</li>
		</ul>
	</div>
	<div class="calendarLayer" v-if="dateShow">
		<div class="calendarWrap">
			<div class="calendarNav">通讯录一天置顶卡<span v-on:click="calendarHide">X</span></div>
			<div class="calendarTitle">日期选择：</div>
			<div class='calendar' id='calendar'>
				<div class="calendar-title-box"><span class="calendar-title" id="calendarTitle">{{ currentYear }}年{{ currentMonth }}月</span></div>
				<div class="calendar-body-box">
					<ul class="weekdays">
						<li>日</li>
						<li>一</li>
						<li>二</li>
						<li>三</li>
						<li>四</li>
						<li>五</li>
						<li>六</li>
					</ul>
					<ul class="days">
						<li v-for="d in days">
							<span v-on:click="pick(d.day)" v-if="d.show" v-bind:class="{disabled:d.disabled,on:d.on}">{{new Date(d.day).getDate()}}</span>
						</li>
					</ul>
				</div>
			</div>
			<div class='calendar' id='calendar2'>
				<div class="calendar-title-box"><span class="calendar-title" id="calendarTitle">{{ currentYear2 }}年{{ currentMonth2 }}月</span></div>
				<div class="calendar-body-box">
					<ul class="weekdays">
						<li>日</li>
						<li>一</li>
						<li>二</li>
						<li>三</li>
						<li>四</li>
						<li>五</li>
						<li>六</li>
					</ul>
					<ul class="days">
						<li v-for="d in days2">
							<span v-on:click="pick(d.day)" v-if="d.show" v-bind:class="{disabled:d.disabled,on:d.on}">{{new Date(d.day).getDate()}}</span>
						</li>
					</ul>
				</div>
			</div>
			<div class="calendarSelected">
				<span>已选择：</span>
				<div class="calendarRange">
					<span v-for="d in daySelected">{{new Date(d).getMonth()+1}}月{{new Date(d).getDate()}}日</span>
				</div>
			</div>
		</div>
	</div>

	<div class="calendarLayer" v-if="dateShow2">
		<div class="calendarWrap">
			<div class="calendarNav">供求信息一天置顶卡<span v-on:click="calendarHide2">X</span></div>
			<div class="calendarTitle">日期选择：</div>
			<div class='calendar' id='calendar'>
				<div class="calendar-title-box"><span class="calendar-title" id="calendarTitle">{{ currentYear_ }}年{{ currentMonth_ }}月</span></div>
				<div class="calendar-body-box">
					<ul class="weekdays">
						<li>日</li>
						<li>一</li>
						<li>二</li>
						<li>三</li>
						<li>四</li>
						<li>五</li>
						<li>六</li>
					</ul>
					<ul class="days">
						<li v-for="d in days_">
							<span v-on:click="pick2(d.day)" v-if="d.show" v-bind:class="{disabled:d.disabled,on:d.on}">{{new Date(d.day).getDate()}}</span>
						</li>
					</ul>
				</div>
			</div>
			<div class='calendar' id='calendar2'>
				<div class="calendar-title-box"><span class="calendar-title" id="calendarTitle">{{ currentYear2_ }}年{{ currentMonth2_ }}月</span></div>
				<div class="calendar-body-box">
					<ul class="weekdays">
						<li>日</li>
						<li>一</li>
						<li>二</li>
						<li>三</li>
						<li>四</li>
						<li>五</li>
						<li>六</li>
					</ul>
					<ul class="days">
						<li v-for="d in days2_">
							<span v-on:click="pick2(d.day)" v-if="d.show" v-bind:class="{disabled:d.disabled,on:d.on}">{{new Date(d.day).getDate()}}</span>
						</li>
					</ul>
				</div>
			</div>
			<div class="calendarSelected">
				<span>已选择：</span>
				<div class="calendarRange">
					<span v-for="d in daySelected2">{{new Date(d).getMonth()+1}}月{{new Date(d).getDate()}}日</span>
				</div>
			</div>
		</div>
	</div>

	<div class="calendarLayer" v-if="releaseShow">
		<div class="calendarWrap2">
			<div class="calendarNav">供求信息一天置顶卡<span v-on:click="releaseWrapHide">X</span></div>
			<div class="calendarTitle">供求信息选择(限选一条)：</div>
			<div class="proMsgLi">
				<div v-for="m in p2.myMsg">
					<input type="radio" name="msg" v-bind:value="m.id" v-model="selected"> {{m.input_time}}
					<br> 供求：
					<span>{{m.contents}}</span>
				</div>
			</div>
			<div class="calendarSelected" style="border-top: 1px solid #ddd;">
				<span>已选择：</span>
				<div>{{selectedTxt}}</div>
			</div>
		</div>
	</div>
</div>
</template>
<script>
export default {
	data: function() {
		return {
			wechat: false,
			p1: "",
			p2: "",
			points: 0,
			pro: {
				id: "",
				cost: 100
			},
			pro2: {
				id: "",
				cost: 100
			},
			selected: "",
			releaseTxt: [],
			selectedTxt: "",

			currentMonth: 1,
			currentYear: 1970,
			currentMonth2: 1,
			currentYear2: 1970,
			days: [],
			days2: [],
			daySelected: [],
			dateShow: false,

			currentMonth_: 1,
			currentYear_: 1970,
			currentMonth2_: 1,
			currentYear2_: 1970,
			days_: [],
			days2_: [],
			daySelected2: [],
			dateShow2: false,
			releaseShow: false

		}
	},
	watch: {
		selected: function() {
			var _this = this;
			if(this.selected) {
				this.releaseTxt.forEach(function(v, i, a) {
					if(v.id == _this.selected) {
						_this.selectedTxt = '供求：' + v.contents;
					}
				});
			}
		}
	},
	methods: {
		calendarShow: function() {
			this.dateShow = true;
		},
		calendarHide: function() {
			this.dateShow = false;
		},
		calendarShow2: function() {
			this.dateShow2 = true;
		},
		calendarHide2: function() {
			this.dateShow2 = false;
		},
		releaseWrapShow: function() {
			this.releaseShow = true;
		},
		releaseWrapHide: function() {
			this.releaseShow = false;
		},
		initCalendar: function(startDate, endDate, tookDate) {
			var _this = this;
			var year = new Date(startDate).getFullYear();
			var month = new Date(startDate).getMonth() + 1;
			var firstDay = new Date(year, month - 1, 1);
			var daysTemp = [];
			var startDay = new Date(startDate).getDate();
			this.currentYear = new Date(startDate).getFullYear();
			this.currentMonth = new Date(startDate).getMonth() + 1;

			for(var i = 0; i < 35; i++) {
				var thisDay = new Date(year, month - 1, i + 1 - firstDay.getDay());
				var thisDayStr = this.formatDate(thisDay);
				var thisDayStr = {
					day: _this.formatDate(thisDay),
					disabled: false,
					show: false,
					on: false
				}
				daysTemp.push(thisDayStr);
			}

			daysTemp.forEach(function(v, i, a) {
				if(new Date(v.day).getDate() < startDay) {
					daysTemp[i].disabled = true;
				}
				if(new Date(v.day).getMonth() == month - 1) {
					daysTemp[i].show = true;
				}
			});
			tookDate.forEach(function(v, i, a) {
				for(var i = 0; i < daysTemp.length; i++) {
					if(daysTemp[i].day == v) {
						daysTemp[i].disabled = true;
					}
				}
			});
			this.days = daysTemp;

			var year2 = new Date(endDate).getFullYear();
			var month2 = new Date(endDate).getMonth() + 1;
			var firstDay2 = new Date(year2, month2 - 1, 1);
			var daysTemp2 = [];
			var endDay = new Date(endDate).getDate();
			this.currentYear2 = new Date(endDate).getFullYear();
			this.currentMonth2 = new Date(endDate).getMonth() + 1;

			for(var i = 0; i < 35; i++) {
				var thisDay2 = new Date(year2, month2 - 1, i + 1 - firstDay2.getDay());
				var thisDayStr2 = this.formatDate(thisDay2);
				var thisDayStr2 = {
					day: _this.formatDate(thisDay2),
					disabled: false,
					show: false,
					on: false
				}
				daysTemp2.push(thisDayStr2);
			}
			daysTemp2.forEach(function(v, i, a) {
				if(new Date(v.day).getDate() > endDay) {
					daysTemp2[i].disabled = true;
				}
				if(new Date(v.day).getMonth() == month2 - 1) {
					daysTemp2[i].show = true;
				}

			});
			tookDate.forEach(function(v, i, a) {
				for(var i = 0; i < daysTemp2.length; i++) {
					if(daysTemp2[i].day == v) {
						daysTemp2[i].disabled = true;
					}
				}
			});
			this.days2 = daysTemp2;
			this.totalDays = this.days.concat(this.days2);
		},
		initCalendar2: function(startDate, endDate, tookDate) {
			var _this = this;
			var year = new Date(startDate).getFullYear();
			var month = new Date(startDate).getMonth() + 1;
			var firstDay = new Date(year, month - 1, 1);
			var daysTemp = [];
			var startDay = new Date(startDate).getDate();
			this.currentYear_ = new Date(startDate).getFullYear();
			this.currentMonth_ = new Date(startDate).getMonth() + 1;

			for(var i = 0; i < 35; i++) {
				var thisDay = new Date(year, month - 1, i + 1 - firstDay.getDay());
				var thisDayStr = this.formatDate(thisDay);
				var thisDayStr = {
					day: _this.formatDate(thisDay),
					disabled: false,
					show: false,
					on: false
				}
				daysTemp.push(thisDayStr);
			}

			daysTemp.forEach(function(v, i, a) {
				if(new Date(v.day).getDate() < startDay) {
					daysTemp[i].disabled = true;
				}
				if(new Date(v.day).getMonth() == month - 1) {
					daysTemp[i].show = true;
				}
			});
			tookDate.forEach(function(v, i, a) {
				for(var i = 0; i < daysTemp.length; i++) {
					if(daysTemp[i].day == v) {
						daysTemp[i].disabled = true;
					}
				}
			});
			this.days_ = daysTemp;

			var year2 = new Date(endDate).getFullYear();
			var month2 = new Date(endDate).getMonth() + 1;
			var firstDay2 = new Date(year2, month2 - 1, 1);
			var daysTemp2 = [];
			var endDay = new Date(endDate).getDate();
			this.currentYear2_ = new Date(endDate).getFullYear();
			this.currentMonth2_ = new Date(endDate).getMonth() + 1;

			for(var i = 0; i < 35; i++) {
				var thisDay2 = new Date(year2, month2 - 1, i + 1 - firstDay2.getDay());
				var thisDayStr2 = this.formatDate(thisDay2);
				var thisDayStr2 = {
					day: _this.formatDate(thisDay2),
					disabled: false,
					show: false,
					on: false
				}
				daysTemp2.push(thisDayStr2);
			}
			daysTemp2.forEach(function(v, i, a) {
				if(new Date(v.day).getDate() > endDay) {
					daysTemp2[i].disabled = true;
				}
				if(new Date(v.day).getMonth() == month2 - 1) {
					daysTemp2[i].show = true;
				}

			});
			tookDate.forEach(function(v, i, a) {
				for(var i = 0; i < daysTemp2.length; i++) {
					if(daysTemp2[i].day == v) {
						daysTemp2[i].disabled = true;
					}
				}
			});
			this.days2_ = daysTemp2;
			this.totalDays2 = this.days_.concat(this.days2_);
		},
		pick: function(date) {
			var _this = this;
			if(this.daySelected.indexOf(date) == -1) {
				this.daySelected.push(date);
				this.totalDays.forEach(function(v, i, a) {
					if(v.day == date) {
						v.on = true;
					}
				});
			} else {
				var index = _this.daySelected.indexOf(date);
				this.daySelected.splice(index, 1);
				this.totalDays.forEach(function(v, i, a) {
					if(v.day == date) {
						v.on = false;
					}
				});
			}
			console.log(this.daySelected);

		},
		pick2: function(date) {
			var _this = this;
			if(this.daySelected2.indexOf(date) == -1) {
				this.daySelected2.push(date);
				this.totalDays2.forEach(function(v, i, a) {
					if(v.day == date) {
						v.on = true;
					}
				});
			} else {
				var index = _this.daySelected2.indexOf(date);
				this.daySelected2.splice(index, 1);
				this.totalDays2.forEach(function(v, i, a) {
					if(v.day == date) {
						v.on = false;
					}
				});
			}
			console.log(this.daySelected2);
		},
		formatDate: function(date) {
			var _year = date.getFullYear();
			var _month = date.getMonth() + 1; // 月从0开始计数
			var _d = date.getDate();
			_month = (_month > 9) ? ("" + _month) : ("0" + _month);
			_d = (_d > 9) ? ("" + _d) : ("0" + _d);
			return _year + '-' + _month + '-' + _d;
		},
		proExchange: function() {
			var _this = this;
			if(this.daySelected.length != 0) {
				$.ajax({
					type: "post",
					url: version + "/product/newExchangeSupplyOrDemand",
					data: {
						token: window.localStorage.getItem("token"),
						goods_id: _this.pro.id,
						dates: _this.daySelected.join(),
						pur_id: ""
					},
					headers: {
						'X-UA': window.localStorage.getItem("XUA")
					},
					dataType: 'JSON'
				}).then(function(res) {
					if(res.err == 0) {
						weui.alert("兑换成功", {
							title: '塑料圈通讯录',
							buttons: [{
								label: '确定',
								type: 'parimary',
								onClick: function() {
									window.location.reload();
								}
							}]
						});
					} else {
						weui.alert(res.msg, {
							title: '塑料圈通讯录',
							buttons: [{
								label: '确定',
								type: 'parimary',
								onClick: function() {

								}
							}]
						});
					}
				}, function() {

				});
			} else {
				weui.alert("请选择置顶日期", {
					title: '塑料圈通讯录',
					buttons: [{
						label: '确定',
						type: 'parimary',
						onClick: function() {

						}
					}]
				});
			}

		},
		proExchange2: function() {
			var _this = this;
			if(this.selected && this.daySelected2.length != 0) {
				$.ajax({
					type: "post",
					url: version + "/product/newExchangeSupplyOrDemand",
					data: {
						token: window.localStorage.getItem("token"),
						goods_id: _this.pro2.id,
						dates: _this.daySelected2.join(),
						pur_id: _this.selected
					},
					headers: {
						'X-UA': window.localStorage.getItem("XUA")
					},
					dataType: 'JSON'
				}).then(function(res) {
					if(res.err == 0) {
						weui.alert("兑换成功", {
							title: '塑料圈通讯录',
							buttons: [{
								label: '确定',
								type: 'parimary',
								onClick: function() {
									window.location.reload();
								}
							}]
						});
					} else {
						weui.alert(res.msg, {
							title: '塑料圈通讯录',
							buttons: [{
								label: '确定',
								type: 'parimary',
								onClick: function() {

								}
							}]
						});
					}
				}, function() {

				});
			} else {
				weui.alert("请选择置顶日期和置顶信息", {
					title: '塑料圈通讯录',
					buttons: [{
						label: '确定',
						type: 'parimary',
						onClick: function() {

						}
					}]
				});
			}

		}
	},
	activated: function() {
		this.daySelected = [];
		this.daySelected2 = [];
		var _this = this;
		window.scrollTo(0, 0);
		try {
			var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
			piwikTracker.trackPageView();
		} catch(err) {

		}
		
		var ua = navigator.userAgent.toLowerCase();
	    if(ua.match(/MicroMessenger/i)=="micromessenger") { 
	        this.wechat=true; 
	    } else { 
	        this.wechat=false; 
	    } 

		$.ajax({
			type: "post",
			url: version + "/product/getValidDate",
			data: {
				type: 2
			},
			headers: {
				'X-UA': window.localStorage.getItem("XUA")
			},
			dataType: 'JSON'
		}).then(function(res) {
			if(res.err == 0) {
				_this.initCalendar(res.start_date, res.end_date, res.took_date);
			}
		}, function() {

		});

		$.ajax({
			type: "post",
			url: version + "/product/getValidDate",
			data: {
				type: 1
			},
			headers: {
				'X-UA': window.localStorage.getItem("XUA")
			},
			dataType: 'JSON'
		}).then(function(res) {
			if(res.err == 0) {
				_this.initCalendar2(res.start_date, res.end_date, res.took_date);
			}
		}, function() {

		});

		$.ajax({
			type: "post",
			url: version + "/product/getProductList",
			data: {
				token: window.localStorage.getItem("token"),
				page: 1,
				size: 10
			},
			headers: {
				'X-UA': window.localStorage.getItem("XUA")
			},
			dataType: 'JSON'
		}).then(function(res) {
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
				_this.p1 = res.info[0];
				_this.p2 = res.info[1];
				_this.releaseTxt = res.info[1].myMsg;
				_this.pro.price = res.info[0].points;
				_this.pro2.price = res.info[1].points;
				_this.pro.id = res.info[0].id;
				_this.pro2.id = res.info[1].id;
				_this.points = res.pointsAll;
				console.log(_this.releaseTxt);
			}
		}, function() {

		});

	}
}
</script>