<template>
<div class="buyWrap" style="padding: 45px 0 70px 0;">
	<div style="position: fixed; top: 0; left: 0; width: 100%; z-index: 10;">
		<header id="bigCustomerHeader">
			<a class="back" href="javascript:window.history.back();"></a>
			我的留言
		</header>
	</div>
	<ul class="supplyUl">
		<li v-show="condition" v-for="n in name">
			<div class="supplytitle">
				<h3>{{n.input_time}}</h3>
				<p><i class="myicon iconSupply"></i>我的供给:{{n.contents}}</p>
			</div>
			<div class="supplycontent">
				<h3><i class="myicon iconSupply2"></i>系统消息</h3>
				<p>在信息库中，没有找到在卖（买）此牌号的商家！</p>
				<!--<p>Ta要买：{{n.b_and_s[0].c_name}}<br><span style=" display: inline-block; width: 48px; height: 1px;"></span>
					<a v-link="{name:'personinfo',params:{id:n.b_and_s[0].user_id}}">{{n.b_and_s[0].name}}</a> {{n.b_and_s[0].mobile}}</p>
				<p>Ta在卖：{{n.b_and_s[1].c_name}}<br><span style=" display: inline-block; width: 48px; height: 1px;"></span>
					<a v-link="{name:'personinfo',params:{id:n.b_and_s[1].user_id}}">{{n.b_and_s[1].name}}</a> {{n.b_and_s[1].mobile}}</p>-->
				<p>参考价：<span>{{n.deal_price}}元/吨</span></p>
				<h3><i class="myicon iconSupply3"></i>塑料圈友</h3>
				<div class="triangle-up" v-if="n.says.length!==0"></div>
				<div class="replyRelease2" v-if="n.says.length!==0">
					<p v-for="n2 in n.says" v-if="n.user_id==n2.rev_id">
						<router-link :to="{name:'personinfo',params:{id:n2.user_id}}">{{n2.user_name}}</router-link>说:<i v-on:click="reply(n.id,n2.user_id,n2.id)">{{n2.content}}</i>
					</p>
					<p v-for="n2 in n.says" v-if="n.user_id!==n2.rev_id">
						<router-link :to="{name:'personinfo',params:{id:n2.user_id}}">{{n2.user_name}}</router-link>回复
						<router-link :to="{name:'personinfo',params:{id:n2.rev_id}}">{{n2.rev_name}}</router-link>:<i v-on:click="reply(n.id,n2.user_id,n2.id)">{{n2.content}}</i>
					</p>
				</div>
			</div>
		</li>
		<li v-show="!condition" style="text-align: center; height: 60px; line-height: 60px;">
			没有相关数据
		</li>
	</ul>
	<footerbar></footerbar>
<div class="releasebox" v-show="show">
	<div style="padding: 10px;">
		<textarea placeholder="请填写回复内容" v-model="content"></textarea>
		<div style="text-align: center;">
			<input type="button" v-on:click="replyMag" value="回复" />
			<input type="button" v-on:click="cancel" value="取消" />
		</div>
	</div>
</div>
<div class="layer" v-show="show"></div>
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
			name: [],
			letter: "",
			keywords: "",
			page: 1,
			condition: true,
			id: "",
			user_id: "",
			show: false
		}
	},
	methods: {
		reply: function(id, userid, delid) {
			var _this = this;
			if(userid == localStorage.getItem("userid")) {
				mui.confirm('', '确定删除此信息？', ['取消', '确定'], function(e) {
					if(e.index == 1) {
						$.ajax({
							url: '/api/qapi1/deleteRepeat',
							type: 'get',
							data: {
								id: delid,
								token: window.localStorage.getItem("token")
							},
							dataType: 'JSON'
						}).then(function(res) {
							if(res.err == 0) {
								window.location.reload();
							} else {
								mui.alert("", "删除失败", function() {

								});
							}
						}, function() {

						});
					} else {

					}
				});
			} else {
				this.show = true;
			}
			this.id = id;
			this.user_id = userid;
			console.log(this.id);
			console.log(this.user_id);
		},
		replyMag: function() {
			var _this = this;
			if(this.content) {
				$.ajax({
					url: '/api/qapi1/saveMsg',
					type: 'get',
					data: {
						pur_id: _this.id,
						content: _this.content,
						send_id: _this.user_id,
						token: window.localStorage.getItem("token")
					},
					dataType: 'JSON'
				}).then(function(res) {
					if(res.err == 0) {
						mui.alert("", res.msg, function() {
							window.location.reload();
						});
					} else {
						mui.alert("", res.msg, function() {
							window.location.reload();
						});
					}
				}, function() {

				});
			} else {
				mui.alert("", "请填写回复内容", function() {

				});
			}
		},
		cancel: function() {
			this.show = false;
		}
	},
	mounted: function() {
		var _this = this;
			try {
	    var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
	    piwikTracker.trackPageView();
	} catch( err ) {
		
	}
		$.ajax({
			url: '/api/qapi1/getMyComment',
			type: 'get',
			data: {
				page: _this.page,
				token: window.localStorage.getItem("token"),
				size: 10
			},
			dataType: 'JSON'
		}).then(function(res) {
			if(res.err == 2) {
				_this.condition = false;
			} else {
				_this.condition = true;
				_this.name=res.data;
			}
		}, function() {

		});
	}
}
</script>