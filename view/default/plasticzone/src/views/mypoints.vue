<template>
<div>
<header id="bigCustomerHeader">
	<a class="back" href="javascript:window.history.back();"></a>
	塑豆商城
</header>
<div class="shopBanner">
	<img width="100%" src="http://statics.myplas.com/myapp/img/shopBanner.jpg">
</div>
<div class="mypoints">
	<router-link :to="{name:'pointsdetail'}"><i class="shopIcon iconPoints"></i><span>{{points}}</span>塑豆</router-link>
	<router-link :to="{name:'recharge'}" style=" color: #ff5000;"><i class="shopIcon iconRecord"></i>充值塑豆</router-link>
	<router-link :to="{name:'pointsrule'}" style=" color: #ff5000;"><i class="shopIcon iconIntro"></i>如何赚塑豆</router-link>
</div>
<div class="pointsWrap">
	<div class="pointsTitle">商品信息</div>
	<ul id="productUl">
		<li>
			<div style="overflow: hidden; position: relative;">
				<img v-bind:src="p1.thumb">
			</div>
			<div class="productNum">
				<span>*</span>请选置顶日期：
			</div>
			<div class="productCost">共<span>{{pro.num}}</span>件
				<div class="exchange" v-on:click="proExchange">支付</div>
				<div class="cost">总塑豆：{{pro.cost}}</div>
			</div>
		</li>
		<li>
			<div style="overflow: hidden; position: relative;">
				<img v-bind:src="p2.thumb">
			</div>
			<div class="productNum">
				<span>*</span>请选置顶日期：
			</div>
			<div class="productMsg">
				<span>*</span>请选择要置顶的供求信息（限选一条）：
			</div>
			<div class="proMsgLi">
				<div v-for="m in p2.myMsg">
					<input type="radio" name="msg" v-bind:value="m.id" v-model="selected"> {{m.input_time}}
					<br> 供求：
					<span>{{m.contents}}</span>
				</div>
			</div>
			<div class="productCost">共<span>{{pro2.num}}</span>件
				<div class="exchange" v-on:click="proExchange2">支付</div>
				<div class="cost">总塑豆：{{pro2.cost}}</div>
			</div>
		</li>
	</ul>
</div>
<div class="calendarLayer">
	<div class="calendarWrap">
		<div class="calendarNav">通讯录一天置顶卡<span>X</span></div>
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
						<span v-if="d.show" v-bind:class="{disabled:d.disabled,on:d.selected}">
							{{new Date(d.day).getDate()}}
						</span>
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
						<span v-on:click="pick(d.day)" v-if="d.show" v-bind:class="{disabled:d.disabled,on:d.on}">
							{{new Date(d.day).getDate()}}
						</span>
					</li>
				</ul>
			</div>

		</div>
	</div>
</div>
</div>
</template>
<script>
export default {
	data: function() {
		return {
			p1: "",
		p2: "",
		points: 0,
		pro: {
			id: "",
			cost: 100,
			num: 1,
			price: 0
		},
		pro2: {
			id: "",
			cost: 100,
			num: 1,
			price: 0
		},
		selected: "",
		startDay: 1,
		currentMonth: 1,
		currentYear: 1970,
		currentMonth2: 1,
		currentYear2: 1970,
		days: [],
		days2:[],
		daySelected:[]
	}
},
methods: {
	initCalendar: function(startDate,endDate,tookDate) {
		var _this=this;
	
		var year = new Date(startDate).getFullYear();
		var month = new Date(startDate).getMonth() + 1;
		var firstDay = new Date(year, month - 1, 1);
		var daysTemp=[];
		var startDay = new Date(startDate).getDate();
		this.currentYear=new Date(startDate).getFullYear();
		this.currentMonth=new Date(startDate).getMonth() + 1;
		
		for (var i=0;i<35;i++) {
			var thisDay = new Date(year, month - 1, i + 1 - firstDay.getDay());
			var thisDayStr = this.formatDate(thisDay);
			var thisDayStr={
				day:_this.formatDate(thisDay),
				disabled:false,
				show:false,
				selected:false
			}
			daysTemp.push(thisDayStr);
		}
		
		daysTemp.forEach(function(v,i,a){
			if (new Date(v.day).getDate()<startDay) {
				daysTemp[i].disabled=true;
			}
			if(new Date(v.day).getMonth()==month-1){
				daysTemp[i].show=true;
			}
		});
		tookDate.forEach(function(v,i,a){
			for (var i=0;i<daysTemp.length;i++) {
				if(daysTemp[i].day==v){
					daysTemp[i].disabled=true;
				}
			}
		});
		this.days=daysTemp;
		
		var year2 = new Date(endDate).getFullYear();
		var month2 = new Date(endDate).getMonth() + 1;
		var firstDay2 = new Date(year2, month2 - 1, 1);
		var daysTemp2=[];
		var endDay=new Date(endDate).getDate();
		this.currentYear2=new Date(endDate).getFullYear();
		this.currentMonth2=new Date(endDate).getMonth() + 1;
		
		for (var i=0;i<35;i++) {
			var thisDay2 = new Date(year2, month2 - 1, i + 1 - firstDay2.getDay());
			var thisDayStr2 = this.formatDate(thisDay2);
			var thisDayStr2={
				day:_this.formatDate(thisDay2),
				disabled:false,
				show:false,
				on:false
			}
			daysTemp2.push(thisDayStr2);
		}
		daysTemp2.forEach(function(v,i,a){
			if (new Date(v.day).getDate()>endDay) {
				daysTemp2[i].disabled=true;
			}
			if(new Date(v.day).getMonth()==month2-1){
				daysTemp2[i].show=true;
			}
	
		});
		tookDate.forEach(function(v,i,a){
			for (var i=0;i<daysTemp2.length;i++) {
				if(daysTemp2[i].day==v){
					daysTemp2[i].disabled=true;
				}
			}
		});
		this.days2=daysTemp2;
	},
	pick:function(date){
		var _this=this;
		if (this.daySelected.indexOf(date)==-1) {
			this.daySelected.push(date);
			console.log(this.days2.indexOf(date));
			this.days2[this.days2.indexOf(date)].on=true;
		} else{
			var index=_this.daySelected.indexOf();
			this.days2[this.days2.indexOf(date)].on=false;
			this.daySelected.splice(index,1);
		}
		console.log(this.daySelected.length);
		
		
//		this.daySelected.forEach(function(v,i,a){
//			for (var i=0;i<_this.days2.length;i++) {
//				if(_this.days2[i].day==v){
//					_this.days2[i].on=true;
//				}
//			}			
//		});
		console.log(this.daySelected);
		
	},
	formatDate: function(date) {
		var _year = date.getFullYear();
		var _month = date.getMonth() + 1; // 月从0开始计数
		var _d = date.getDate();
		_month = (_month > 9) ? ("" + _month) : ("0" + _month);
		_d = (_d > 9) ? ("" + _d) : ("0" + _d);
		return _year + '-' + _month + '-' + _d;
	},
	proAdd: function() {
		this.pro.num++;
		this.pro.cost = this.pro.num * this.pro.price;
	},
	proMin: function() {
		if(this.pro.num < 2) {
			return false;
		} else {
			this.pro.num--;
			this.pro.cost = this.pro.num * this.pro.price;
		}
	},
	proExchange: function() {
		var _this = this;
		$.ajax({
			type: "post",
			url: version + "/product/newExchangeSupplyOrDemand",
			data: {
				token: window.localStorage.getItem("token"),
				goods_id: _this.pro.id,
				num: _this.pro.num,
				pur_id: ""
			},
			headers: {
				'X-UA': headers
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
	},
	proAdd2: function() {
		this.pro2.num++;
		this.pro2.cost = this.pro2.num * this.pro2.price;
	},
	proMin2: function() {
		if(this.pro2.num < 2) {
			return false;
		} else {
			this.pro2.num--;
			this.pro2.cost = this.pro2.num * this.pro2.price;
		}
	},
	proExchange2: function() {
		var _this = this;
		$.ajax({
			type: "post",
			url: version + "/product/newExchangeSupplyOrDemand",
			data: {
				token: window.localStorage.getItem("token"),
				goods_id: _this.pro2.id,
				num: _this.pro2.num,
				pur_id: _this.selected
			},
			headers: {
				'X-UA': headers
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
	}
},
activated: function() {
	var _this = this;
	window.scrollTo(0, 0);
	try {
		var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
		piwikTracker.trackPageView();
	} catch(err) {

	}

	this.initCalendar("2017-05-18","2017-06-18",["2017-05-19","2017-05-22"]);

	$.ajax({
		type: "post",
		url: version + "/product/getProductList",
		data: {
			token: window.localStorage.getItem("token"),
			page: 1,
			size: 10
		},
		headers: {
			'X-UA': headers
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
				_this.pro.price = res.info[0].points;
				_this.pro2.price = res.info[1].points;
				_this.pro.id = res.info[0].id;
				_this.pro2.id = res.info[1].id;
				_this.points = res.pointsAll;
			}
		}, function() {

		});
	}
}
</script>