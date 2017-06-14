<template>
  <div id="app">
  	<Leftmodel></Leftmodel>
  	<!--right begin-->
  	<div class="right flt">
				<!--buy-sell-fixed begin-->
				<div class="buy-sell-fixed">
				    <!--buy-sell-search begin-->
				    <div class="buy-sell-search">
				        <div class="w96">
				            <form action="javascript:;">
				                <!--select begin-->
				                <div class="select flt">
				                    <select v-on:change="selectAll">
				                        <option value="all">全部</option>
				                        <option value="sell">供给</option>
				                        <option value="buy">求购</option>
				                    </select>
				                </div>
				                <!--select end-->
				                <!--search begin-->
				                <div class="search flt">
								    <form action="javascript:;">
				                    <input v-on:keyup.enter="search" type="text" placeholder="请输入厂家或牌号搜索" class="import flt" v-model="keywords"/>
				                    <input type="button" class="submit frt" value="搜索" v-on:click="search()"/>
									</form>
				                </div>
				                <!--search end-->
				            </form>
				        </div>
				    </div>
				    <!--buy-sell-search end-->
				    <!--buy-sell-tab begin-->
				    <ul class="buy-sell-tab">
				        <li id="tab1" v-bind:class="{'hover':filter1}" v-on:click="getRelease('all')">全部</li>
				        <li id="tab2" v-bind:class="{'hover':filter2}" v-on:click="getRelease('recommend')">智能推荐</li>
				        <li id="tab3" v-bind:class="{'hover':filter3}" v-on:click="getRelease('attention')">我的关注</li>
				        <li id="tab4" v-bind:class="{'hover':filter4}" v-on:click="getRelease('supplydemand')">我的供求</li>
				    </ul>
				    <!--buy-sell-tab end-->
				</div>
				<!--buy-sell-fixed end-->
				<!--buy-sell-con begin-->
				<div class="buy-sell-con">
					<!--con-tab-1 begin-->
				    <div id="con-tab-1" class="tab-con">
				    	<!--list begin-->
				     	<ul>
				     			<li v-for="r in release">
				            		<div class="w96">
				                    <!--thumb begin-->
				                    <div class="thumb">
				                        <div class="pic flt">
				                        		<img v-bind:src="r.thumb"><div class="authen" v-bind:class="{'yes':r.is_pass==null||r.is_pass==1,'no':r.is_pass==0}">V</div>
				                        </div>
				                        <span>{{r.c_name}}</span>
				                        <span>{{r.name}}</span>
				                    </div>
				                    <!--thumb end-->
		    		     						<!--todetail begin-->
				     								<div class="todetail" v-on:click="detail(r.id,r.user_id)">
						                    <!--info begin-->
						                    <div v-if="r.type==2" class="info sell"><span>供给：</span><strong v-html="r.contents"></strong></div>
						                    <div v-else class="info buy"><span>求购：</span><strong v-html="r.contents"></strong></div>
						                    <!--info end-->
		    	               		</div>
		               					<!--todetail end-->
				                    <!--other begin-->
				                    <div class="other">
				                    	<!--time begin-->
				                    	<div class="time flt">{{r.input_time}}</div>
				                        <!--time end-->
				                        <!--feedback begin-->
				                        <div class="feedback frt">
				                        		<p class="bid" v-on:click="detail(r.id,r.user_id,1)">
				                        				<a href="javascript:;">出价<span>({{r.deliverPriceCount}})</span></a>
				                        		</p>
				                            <p class="reply" v-on:click="detail(r.id,r.user_id,2)">
				                            		<a href="javascript:;">回复<span>({{r.saysCount}})</span></a>
				                            </p>
				                        </div>
				                        <!--feedback end-->
				                    </div>
				                    <!--other end-->
				               </div>
		        			</li>
				     	</ul>
				      <!--list end-->
				      <!--no-results begin-->
				      <div class="releaseMsg" v-if="condition==7">
									<div class="releaseMsgHead"></div>
									<div class="releaseTxt">{{errmsg}}</div>
									<div class="releaseMsgIntro"></div>
							</div>
							<div class="releaseMsg" v-if="condition==8">
									<div class="releaseMsgHead"></div>
									<div class="releaseTxt">{{errmsg}}</div>
							</div>
							<div class="releaseMsg" v-if="condition==2">
									<div class="releaseMsgHead2"></div>
									<div class="releaseTxt">{{errmsg}}</div>
							</div>
							<div class="releaseMsg" v-if="condition==6">
									<div class="releaseMsgHead2"></div>
									<div class="releaseTxt">{{errmsg}}</div>
							</div>
							<div class="releaseMsg" v-if="condition==9">
									<div class="releaseMsgHead3"></div>
									<div class="releaseTxt">{{errmsg}}</div>
							</div>
							<div class="releaseMsg" v-if="condition==4">
									<div class="releaseMsgHead2"></div>
									<div class="releaseTxt">{{errmsg}}</div>
									<div class="releaseMsgIntro"></div>
							</div>
					    <!--no-results end-->
				    </div>
				    <!--con-tab-1 end-->
				</div>
				<!--buy-sell-con end-->
				<div class="top-arrow" v-show="isArrow" v-on:click="arrow"></div>
				<!--release begin-->
				<div class="release">
					<form name="" method="post" action="">
				        <!--radio begin-->
				        <div class="radio">
				            <input type="radio" name="opt" id="radio-buy" checked="checked"/><label for="radio-buy">我要<span>求购</span></label>
				            <input type="radio" name="opt" id="radio-sell"/><label for="radio-sell">我要<span>供给</span></label>
				        </div>
				        <!--radio end-->
				        <!--con begin-->
				        <div class="con w96">
				        	<!--import begin-->
				            <div class="import flt">
				            	<form name="" method="post" action="">
				                    <!--release-tab begin-->
				                    <div class="release-tab flt">
				                        <ul class="tabx">
				                            <li id="tabx1" v-bind:class="{hover:re_show1}" v-on:click="lishow1">快速发布</li>
				                            <li id="tabx2" v-bind:class="{hover:re_show2}" v-on:click="lishow2">精准发布</li>
				                        </ul>
				                    </div>
				                    <!--release-tab end-->
				                    <!--release-tab-con begin-->
				                    <div class="release-tab-con flt">
				                        <!--con-tabx-1 begin-->
				                        <div id="con-tabx-1" v-show="re_show1"> 
				                        	<textarea placeholder="在此文本框内，可快速复制粘贴供求信息，限制100字以内！"  v-model="re_remark"></textarea>
				                        </div>
				                        <!--con-tabx-1 end-->
				                        <!--con-tabx-2 begin-->
				                        <div id="con-tabx-2" v-show="re_show2">
				                        	<!--tr begin-->
				                            <div class="tr">
				                            	<div class="name">牌号</div>
				                                <div class="val"><input type="text" v-model="re_model"/></div>
				                                <div class="name">厂家</div>
				                                <div class="val"><input type="text" v-model="re_f_name"/></div>
				                            </div>
				                            <!--tr end-->
				                            <!--tr begin-->
				                            <div class="tr">
				                            	<div class="name">价格</div>
				                                <div class="val"><input type="text" v-model="re_price"/></div>
				                                <div class="name">仓库</div>
				                                <div class="val"><input type="text" v-model="re_store_house"/></div>
				                            </div>
				                            <!--tr end-->
				                        </div>
				                        <!--con-tabx-2 end-->
				                    </div>
				                    <!--release-tab-con end-->
				                    <input type="button" class="submit" value="发布" v-on:click="sale"/>
				                </form>
				            </div>
				            <!--import end-->
				            <!--tips begin-->
				            <div class="tips frt">
				            	<p class="title">信息发布提示：</p> 
				                <p>快速发布：简单粘贴或复制供求，快速录入；</p>
												<p>精准发布：填写准确供求，参与系统比价。</p>
				            </div>
				            <!--tips end-->
				        </div>
				        <!--con end-->
				    </form>
				</div>
				<!--release end-->
		</div>
		<!--right end-->
  </div>
</template>
<style type="text/css">
.layui-layer-content{ text-align:center;}
</style>
<script>
import Leftmodel from "../../components/Leftmodel";
export default {
  name: 'app',
  components: {
		'Leftmodel': Leftmodel
	},
  data : function () {
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
					isArrow: false,
					sortfield1: "ALL",
					sortfield2: "AUTO",
					filter1: false,
					filter2: true,
					filter3: false,
					filter4: false,
					condition: null,
					txt2: "全部",
					filtershow: false,
					mine: false,
					on1: true,
					errmsg: "",
					loadingShow: "",
					top: "",
					
					//release返回值
					re_type: 2,
					re_store_house: "",
					re_model: "",
					re_f_name: "",
					re_price: "",
					re_remark: "",
					re_show: false,
					re_content: "",
					re_isDisable: false,
					re_show1: true,
					re_show2: false,
					re_standard:1
  		}
  },
  methods : {
  		//selectAll begin
  		selectAll: function() {
					var _this = this,
							curSel =  $(".select option:selected").val();
					//all begin
					if ( curSel === "all" ) {
							_this.selected = 0;
							$.ajax({
								url: '/api/qapi1_2/getReleaseMsg',
								type: 'post',
								data: {
									keywords: _this.keywords.toLocaleUpperCase(),
									page: _this.page,
									type: _this.selected,
									sortField1: _this.sortfield1,
									sortField2: _this.sortfield2,
									token: window.localStorage.getItem("token"),
									size: 10
								},
								dataType: 'JSON'
							}).then(function(res) {
								if(res.err == 0) {
									_this.release = res.data;
								} else if(res.err == 2) {
									_this.condition = res.err;
									_this.errmsg = res.msg;
									_this.release = [];
									_this.top = null;
								} else if(res.err == 4) {
									_this.condition = res.err;
									_this.errmsg = res.msg;
									_this.release = [];
									_this.top = null;
								} else if(res.err == 9) {
									_this.condition = res.err;
									_this.errmsg = res.msg;
									_this.release = [];
									_this.top = null;
								} else if(res.err == 6) {
									_this.condition = res.err;
									_this.errmsg = res.msg;
									_this.release = [];
									_this.top = null;
								} else if(res.err == 7) {
									_this.condition = res.err;
									_this.errmsg = res.msg;
									_this.release = [];
									_this.top = null;
								} else if(res.err == 8) {
									_this.condition = res.err;
									_this.errmsg = res.msg;
									_this.release = [];
									_this.top = null;
								}
							}, function() {
		
							});
					}
					//all end
					//sell begin
					else if ( curSel === "sell" ) {
							_this.selected = 2;
							$.ajax({
								url: '/api/qapi1_2/getReleaseMsg',
								type: 'post',
								data: {
									keywords: _this.keywords.toLocaleUpperCase(),
									page: _this.page,
									type: _this.selected,
									sortField1: _this.sortfield1,
									sortField2: _this.sortfield2,
									token: window.localStorage.getItem("token"),
									size: 10
								},
								dataType: 'JSON'
							}).then(function(res) {
								if(res.err == 0) {
									_this.release = res.data;
								} else if(res.err == 2) {
									_this.condition = res.err;
									_this.errmsg = res.msg;
									_this.release = [];
									_this.top = null;
								} else if(res.err == 4) {
									_this.condition = res.err;
									_this.errmsg = res.msg;
									_this.release = [];
									_this.top = null;
								} else if(res.err == 9) {
									_this.condition = res.err;
									_this.errmsg = res.msg;
									_this.release = [];
									_this.top = null;
								} else if(res.err == 6) {
									_this.condition = res.err;
									_this.errmsg = res.msg;
									_this.release = [];
									_this.top = null;
								} else if(res.err == 7) {
									_this.condition = res.err;
									_this.errmsg = res.msg;
									_this.release = [];
									_this.top = null;
								} else if(res.err == 8) {
									_this.condition = res.err;
									_this.errmsg = res.msg;
									_this.release = [];
									_this.top = null;
								}
							}, function() {
		
							});
					}
					//sell end
					//buy end
					else if ( curSel === "buy" ) {
							_this.selected = 1;
							$.ajax({
								url: '/api/qapi1_2/getReleaseMsg',
								type: 'post',
								data: {
									keywords: _this.keywords.toLocaleUpperCase(),
									page: _this.page,
									type: _this.selected,
									sortField1: _this.sortfield1,
									sortField2: _this.sortfield2,
									token: window.localStorage.getItem("token"),
									size: 10
								},
								dataType: 'JSON'
							}).then(function(res) {
								if(res.err == 0) {
									_this.release = res.data;
								} else if(res.err == 2) {
									_this.condition = res.err;
									_this.errmsg = res.msg;
									_this.release = [];
									_this.top = null;
								} else if(res.err == 4) {
									_this.condition = res.err;
									_this.errmsg = res.msg;
									_this.release = [];
									_this.top = null;
								} else if(res.err == 9) {
									_this.condition = res.err;
									_this.errmsg = res.msg;
									_this.release = [];
									_this.top = null;
								} else if(res.err == 6) {
									_this.condition = res.err;
									_this.errmsg = res.msg;
									_this.release = [];
									_this.top = null;
								} else if(res.err == 7) {
									_this.condition = res.err;
									_this.errmsg = res.msg;
									_this.release = [];
									_this.top = null;
								} else if(res.err == 8) {
									_this.condition = res.err;
									_this.errmsg = res.msg;
									_this.release = [];
									_this.top = null;
								}
							}, function() {
		
							});
					}
					//buy end
			
			},
  		//selectAll end
  		//getRelease begin
  		getRelease : function ( cate ) {
		        $(".right").scrollTop(0,0);
  				var _this = this;
  				switch(cate) {
						case 'all':
							_this.filter1 = true;
							_this.filter2 = false;
							_this.filter3 = false;
							_this.filter4 = false;
							_this.mine = false;
							_this.sortfield1 = "ALL";
							_this.sortfield2 = "";
							_this.condition = true;
							_this.page = 1;
							break;
						case 'recommend':
							_this.filter1 = false;
							_this.filter2 = true;
							_this.filter3 = false;
							_this.filter4 = false;
							_this.mine = false;
							_this.sortfield1 = "";
							_this.sortfield2 = "AUTO";
							_this.condition = true;
							_this.page = 1;
							break;
						case 'attention':
							_this.filter1 = false;
							_this.filter2 = false;
							_this.filter3 = true;
							_this.filter4 = false;
							_this.mine = false;
							_this.sortfield1 = "";
							_this.sortfield2 = "CONCERN";
							_this.condition = true;
							_this.page = 1;
							break;
						case 'supplydemand':
							_this.filter1 = false;
							_this.filter2 = false;
							_this.filter3 = false;
							_this.filter4 = true;
							_this.mine = true;
							_this.sortfield1 = "";
							_this.sortfield2 = "DEMANDORSUPPLY";
							_this.condition = true;
							_this.page = 1;
						default:
							break;
					}
  				$.ajax({
							url: '/api/qapi1_2/getReleaseMsg',
							type: 'post',
							data: {
								keywords: _this.keywords.toLocaleUpperCase(),
								page: _this.page,
								type: _this.selected,
								sortField1: _this.sortfield1,
								sortField2: _this.sortfield2,
								token: window.localStorage.getItem("token"),
								size: 10
							},
							dataType: 'JSON'
					}).done( function ( res ) {
							if(res.err == 0) {
									_this.release = res.data;
									if(JSON.stringify(res.top) == '{}') {
										_this.top = null
									} else {
										_this.top = res.top;
									}
								} else if(res.err == 2) {
									_this.condition = res.err;
									_this.errmsg = res.msg;
									_this.release = [];
									_this.top = null;
								} else if(res.err == 4) {
									_this.condition = res.err;
									_this.errmsg = res.msg;
									_this.release = [];
									_this.top = null;
								} else if(res.err == 9) {
									_this.condition = res.err;
									_this.errmsg = res.msg;
									_this.release = [];
									_this.top = null;
								} else if(res.err == 6) {
									_this.condition = res.err;
									_this.errmsg = res.msg;
									_this.release = [];
									_this.top = null;
								} else if(res.err == 7) {
									_this.condition = res.err;
									_this.errmsg = res.msg;
									_this.release = [];
									_this.top = null;
								} else if(res.err == 8) {
									_this.condition = res.err;
									_this.errmsg = res.msg;
									_this.release = [];
									_this.top = null;
								}
								console.log( "共有数据"+ _this.release.length+"条" );
								console.log( "condition = "+_this.condition );
					}).fail(function() {

					}).always(function() {

					});
  		},
  		//getRelease end
  		//search begin
  		search: function() {
				var _this = this;
				this.condition = true;
				_this.filtershow = false;
				_this.filtershow2 = false;
				this.page = 1;
	
				$.ajax({
					url: '/api/qapi1_2/getReleaseMsg',
					type: 'post',
					data: {
						keywords: _this.keywords.toLocaleUpperCase(),
						page: _this.page,
						type: _this.selected,
						sortField1: _this.sortfield1,
						sortField2: _this.sortfield2,
						token: window.localStorage.getItem("token"),
						size: 10
					},
					dataType: 'JSON'
				}).then(function(res) {
					if(res.err == 0) {
						_this.release = res.data;
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
					} else if(res.err == 2) {
						_this.condition = res.err;
						_this.errmsg = res.msg;
						_this.release = [];
						_this.top = null;
					} else if(res.err == 4) {
						_this.condition = res.err;
						_this.errmsg = res.msg;
						_this.release = [];
						_this.top = null;
					} else if(res.err == 9) {
						_this.condition = res.err;
						_this.errmsg = res.msg;
						_this.release = [];
						_this.top = null;
					} else if(res.err == 6) {
						_this.condition = res.err;
						_this.errmsg = res.msg;
						_this.release = [];
						_this.top = null;
					} else if(res.err == 7) {
						_this.condition = res.err;
						_this.errmsg = res.msg;
						_this.release = [];
						_this.top = null;
					} else if(res.err == 8) {
						_this.condition = res.err;
						_this.errmsg = res.msg;
						_this.release = [];
						_this.top = null;
					}
				}, function() {
	
				});
			},
  		//search end
  	arrow: function() {
			$(".right").scrollTop(0);
		},
  		//loadingMore begin
		loadingMore: function( ) {
				var _this = this;
				var scrollTop = $(".right").scrollTop();
        var scrollHeight = $(".buy-sell-con").height();
        var windowHeight = $(window).height();
				if(scrollTop + windowHeight == scrollHeight) {
						_this.page++;
						$.ajax({
						type: "post",
						url: "/api/qapi1_2/getReleaseMsg",
						data: {
							keywords: _this.keywords.toLocaleUpperCase(),
							page: _this.page,
							type: _this.selected,
							sortField1: _this.sortfield1,
							sortField2: _this.sortfield2,
							token: window.localStorage.getItem("token"),
							size: 10
						},
						dataType: 'JSON'
				}).then(function(res) {
						if(res.err == 0) {
							//_this.release = res.data;
							_this.release = _this.release.concat(res.data);
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
						} else if(res.err == 3) {
							//没有更多数据
							layer.open({
									title: false,
									offset : "28%",
									content : res.msg,
									closeBtn : false,
									btnAlign: 'c',
									anim : 2,
									time:2000,
									btn : 0,
							});
						}
					}, function() {
	
					});
				}
		},
  	//loadingMore end
  	//detail begin
  	detail : function ( id1,id2, tab ) {
  			tab = tab || 0;
  			location.href = "releasedetail?id="+id1+"&userid="+id2+"&tab="+tab;
  	},
  	//detail end
  	lishow1: function() {
			this.re_show1 = true;
			this.re_show2 = false;
			this.re_standard=1;
		},
		lishow2: function() {
			this.re_show1 = false;
			this.re_show2 = true;
			this.re_standard=2;
		},
		//sale begin
		sale : function () {
				var _this = this;
				this.re_isDisable = true;
				var data = [];
				var arr = {
					'model': this.re_model.toUpperCase(),
					'f_name': this.re_f_name,
					'store_house': this.re_store_house,
					'price': this.re_price,
					'type': 1,
					'quan_type': 0,
					'content': this.re_remark
				};
				data.push(arr);
				//求购1，供给2
				arr.type = ( $('.radio input:checked').attr( "id" ) === "radio-buy" ) ? 1 : 2;
				//if begin
				if(this.re_type && this.re_store_house && this.re_model && this.re_f_name && this.re_price || this.re_remark) {
						$.ajax({
							url: '/api/qapi1_2/pub',
							type: 'post',
							data: {
								data: data,
								token: window.localStorage.getItem("token")
							},
							dataType: 'JSON'
						}).then(function(res) {
								if(res.err == 0) {
										$.ajax({
											type:"post",
											url:"/api/score/addScore",
											data:{
												token:window.localStorage.getItem("token"),
												type:'7',
												standard:_this.re_standard
											},
											dataType: 'JSON'
										}).done(function(res){
											
										}).fail(function(){
											
										});
										_this.re_isDisable = false;
										window.location.reload();
								} else {
										/*mui.alert("", res.msg, function() {
											window.location.reload();
										});*/
								}
						},function() {

						})
				}
				//if end
				else {
						layer.open({
								title: false,
								offset : "28%",
								icon : 5,
								closeBtn : false,
								content : "请把相关信息填写完整，谢谢！",
								btnAlign: 'c',
								anim : 2
						});
						_this.re_isDisable = false;
				}
		}
		//sale end
  },
  //mounted begin
	mounted : function () {
				var _this = this;
				$(".right").scroll( function(){
						var scrollTop = $(this).scrollTop();
            var scrollHeight = $(document).height();
            var windowHeight = $(this).height();	
            _this.loadingMore();
            if(scrollTop > 600) {
                _this.isArrow = true;
            } else {
                _this.isArrow = false;
            }
				} );
				$.ajax({
					url: '/api/qapi1_2/getReleaseMsg',
					type: 'post',
					data: {
						keywords: _this.keywords.toLocaleUpperCase(),
						page: _this.page,
						type: _this.selected,
						sortField1: _this.sortfield1,
						sortField2: _this.sortfield2,
						token: window.localStorage.getItem("token"),
						size: 10
					},
					dataType: 'JSON'
				}).done(function(res) {
					if(res.err == 0) {
						_this.release = res.data;
						_this.top = res.top;
					} else if(res.err == 1) {
							layer.open({
									title: false,
									offset : "28%",
									icon : 5,
									content : res.msg,
									closeBtn : false,
									btnAlign: 'c',
									anim : 2,
									yes : function () {
											location.href = "/mypczone/index/login?supplybuy";		
									}
							});
							
					} else if(res.err == 2) {
						_this.condition = res.err;
						_this.errmsg = res.msg;
						_this.top = null;
					} else if(res.err == 4) {
						_this.condition = res.err;
						_this.errmsg = res.msg;
						_this.top = null;
					} else if(res.err == 5) {
						_this.condition = res.err;
						_this.errmsg = res.msg;
						_this.top = null;
					} else if(res.err == 6) {
						_this.condition = res.err;
						_this.errmsg = res.msg;
						_this.top = null;
					} else if(res.err == 7) {
						_this.condition = res.err;
						_this.errmsg = res.msg;
						_this.top = null;
					} else if(res.err == 8) {
						_this.condition = res.err;
						_this.errmsg = res.msg;
						_this.release = [];
						_this.top = null;
					}
				}).fail(function() {
		
				}).always(function() {
					
				});
	}
	//mounted end
}
</script>