<template>
  <div id="app">
				<Leftmodel></Leftmodel>
				<!--right begin-->
				<div class="right">
						<!--back begin-->
						<div class="back buy-sell-back"><a href="javascript:window.history.back();"></a>详情</div>
						<!--back end-->
						<!--buy-sell-con begin-->
						<div class="buy-sell-con buy-sell-con2">
							<ul>
						    	<li>
						            <div class="w96 sell settop">
						                <!--thumb begin-->
						                <div class="thumb">
						                    <div class="pic flt"><img v-bind:src="thumb"><div class="authen no">V</div></div>
						                    <span>{{c_name}}</span>
						                    <span>{{name}}</span>
						                </div>
						                <!--thumb end-->
						                <!--info begin-->
						                <div v-if="type==2" class="info sell">
						                		<span>供给：</span>{{content}}
						                </div>
						                <div v-else class="info buy">
						                		<span>求购：</span>{{content}}
						                </div>
						                <!--info end-->
						                <!--other begin-->
						                <div class="other">
						                    <!--time begin-->
						                    <div class="time flt">{{time}}</div>
						                    <!--time end-->
						                    <!--feedback begin-->
						                    <div class="feedback frt">
						                        <p class="bid">出价<span>({{deliverPriceCount}})</span></p>
						                        <p class="reply">回复<span>({{saysCount}})</span></p>
						                    </div>
						                    <!--feedback end-->
						                </div>
						                <!--other end-->
						                <!--attention begin-->
						                <div class="attention yes" v-on:click="pay">{{status}}</div>
						                <!--<div class="attention no">已关注</div>-->
						                <!--attention end-->
						            </div>
						        </li>
						    </ul>
						</div>
						<!--buy-sell-con end-->
						<!--feedback-wrap begin-->
						<div class="feedback-wrap">
							<!--feedback-tab begin-->
							<ul class="feedback-tab">
						    	<li id="taba1" v-bind:class="{hover:show1}" v-on:click="lishow1">出价消息</li>
						      <li id="taba2" v-bind:class="{hover:show2}" v-on:click="lishow2">回复消息</li>
						    </ul>
						    <!--feedback-tab end-->
						    <!--feedback-con begin-->
						    <div class="feedback-con">
						    	<!--con-taba-1 begin-->
						        <div  v-show="show1">
						        	<div class="w96">
						            	<!--bid-list begin-->
						                <div class="bid-list">
						                	<!--bid-title begin-->
						                    <div class="bid-title">
						                    	<div class="name">出价人</div>
						                        <div class="price">出价</div>
						                        <div class="mobile">手机号码</div>
						                    </div>
						                    <!--bid-title end-->
						                    <!--bid-con begin-->
						                    <ul class="bid-con">
						                       <li v-for="p in price">
						                        	<div class="name">
						                            	<!--thumb begin-->
						                    			<div class="thumb">
						                                    <div class="pic flt"><img v-bind:src="p.info.thumb"><div class="authen no">V</div></div>
						                                    <p class="person"><span>{{p.info.c_name}}</span><span>{{p.info.name}}</span></p>
						                                    <p class="time">{{p.input_time}}</p>
						                                </div>
						                                <!--thumb end-->
						                            </div>
						                            <div class="price">{{p.price}}</div>
						                            <div class="mobile">{{p.info.mobile}}</div>
						                       </li>
						                    </ul>
						                    <!--bid-con end-->
						                </div>
						                <!--bid-list end-->
						            </div>
						            <!--opt begin-->
						            <div class="opt">
						            	<div class="w96">
						                    <form name="" action="" method="post">
						                        <input type="number" placeholder="期待你的出价" class="import flt" v-model="deliverprice"/>
						                        <button type="button" class="submit frt" v-on:click="deliver">出价</button>
						                    </form>
						                </div>
						            </div>
						            <!--opt end-->
						        </div>
						        <!--con-taba-1 end-->
						        <!--con-taba-2 begin-->
						        <div v-show="show2">
						        	<!--reply-list begin-->
						            <ul class="reply-list">
						                <li v-for="r in reply">
						                	<!--thumb begin-->
						                    <div class="thumb">
						                        <div class="pic flt"><img v-bind:src="r.info.thumb"><div class="authen no">V</div></div>
						                        <p class="person"><span>{{r.info.c_name}}</span><span>{{r.info.name}}</span></p>
						                        <p class="time">{{r.input_time}}</p>
						                    </div>
						                    <!--thumb end-->
						                    <!--info begin-->
						                    <div class="info">{{r.content}}</div>
						                    <!--info end-->
						                </li>
						            </ul>
						            <!--reply-list end-->
						        	<!--opt begin-->
						            <div class="opt">
						            	<div class="w96">
						                    <form name="" action="" method="post">
						                        <input type="text" placeholder="期待你的回复" class="import flt" v-model="msg"/>
						                        <button type="button" class="submit frt" v-on:click="replyMsg">回复</button>
						                    </form>
						                </div>
						            </div>
						            <!--opt end-->
						        </div>
						        <!--con-taba-2 end-->
						    </div>
						    <!--feedback-con end-->
						</div>
						<!--feedback-wrap end-->
				</div>
				<!--right end-->
  </div>
</template>
<script>
import Leftmodel from "../../components/Leftmodel";
export default {
  name: 'app',
  components: {
		'Leftmodel': Leftmodel
	},
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
	//methods begin
	methods : {
			lishow1: function() {
				this.show1 = true;
				this.show2 = false;
			},
			lishow2: function() {
				this.show1 = false;
				this.show2 = true;
			},
			//deliver begin
			deliver : function () {
					var _this = this;
					$.ajax({
						url: '/api/qapi1/deliverPrice',
						type: 'get',
						data: {
							id: _this.id,
							rev_id: _this.user_id,
							token: window.localStorage.getItem("token"),
							type: _this.type,
							price: _this.deliverprice
						},
						dataType: 'JSON'
					}).then(function(res) {
						if(res.err == 0) {
							$.ajax({
								url: '/api/qapi1/getDeliverPrice',
								type: 'get',
								data: {
									id: _this.id,
									rev_id: _this.user_id,
									token: window.localStorage.getItem("token"),
									page: 1,
									size: 10
								},
								dataType: 'JSON'
							}).then(function(res) {
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
							alert( res.msg );
							window.location.reload();
						}
					}, function() {
					});
			},
			//deliver end
			//replyMsg begin
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
								id: _this.id,
								user_id: _this.user_id,
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
							alert( res.msg );
							window.location.reload();
					}
				}, function() {
		
				});
			},
			//replyMsg end
			//pay begin
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
			//pay end
	},
	//methods end
	mounted : function () {
			
			//?id=86088&userid=53453
			var _this = this,
					url=location.search,
					results = [],
					str = "",
					strs = "",
					linkId = "",
					linkUserId = "",
					tab = 0;
					
			if(url.indexOf("?")!=-1)
			{
				//去掉?号 id=86088&userid=53453
			　　str = url.substr(1)　
				//返回一个字符串数组 id=86088，userid=53453
			　　strs = str.split("&");
			
			}
			for ( var i = 0; i < strs.length; i ++ ) {
					results.push( strs[ i ].split( "=" ) );
			}
			
			//取出id,86088 
			linkId = results[0][1];
			//取出userid，53453
			linkUserId = results[1][1];
			//取出tab
			tab = results[2][1];
			
			//ajax begin
			$.ajax({
				url: '/api/qapi1/getReleaseMsgDetail',
				type: 'get',
				data: {
					id: linkId,
					user_id: linkUserId,
					token: window.localStorage.getItem("token")
				},
				dataType: 'JSON'
			}).then(function(res) {
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
					if(_this.user_id == window.localStorage.getItem("userid")) {
						_this.mine = false;
					} else {
						_this.mine = true;
					}
					if( tab === "0" || tab === "1") {
						_this.show1 = true;
						_this.show2 = false;
					} else {
						_this.show1 = false;
						_this.show2 = true;
					}
				} else if(res.err == 1) {
					/*mui.alert("", res.msg, function() {
						_this.$router.push({
							name: 'login'
						});
					});*/
				}
			}, function() {
		
			});
			//ajax end
			
			//ajax begin
			$.ajax({
				url: '/api/qapi1/getDeliverPrice',
				type: 'get',
				data: {
					id: linkId,
					rev_id: linkUserId,
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
			//ajax begin
			
			//ajax begin
			$.ajax({
				url: '/api/qapi1/getReleaseMsgDetailReply',
				type: 'get',
				data: {
					id: linkId,
					user_id: linkUserId,
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
		//ajax end
				$(".left li").removeClass( "hover" );
				$("#left2").addClass( "hover" );
	}
}
$( function () {
		var w = $( ".right" ).width(),
				opt = $( ".opt" );
		opt.width( w );
		$( window ).resize( function () {
				opt.width( w );
		} );
} );
</script>