<template>
<div class="buyWrap" style="padding: 45px 0 70px 0;">
<div style="position: fixed; top: 0; left: 0; width: 100%; z-index: 10;">
	<header id="bigCustomerHeader">
		<a class="back" href="javascript:window.history.back();"></a>
		我的求购
	</header>
</div>
<ul class="supplyUl">
	<li v-show="condition" v-for="n in name">
		<div class="supplytitle">
			<h3>{{n.input_time}}<span v-on:click="del(n.id)">删除</span>
		<router-link :to="{name:'supplybuy',params:{id:n.id}}" style="float: right; margin: 0 10px 0 0; color: #62759e;">分享</router-link>
	</h3>
			<p><i class="myicon iconSupply4"></i>我的求购:{{n.contents}}</p>
		</div>
		<div class="supplycontent">
			<h3><i class="myicon iconSupply2"></i>系统消息</h3>
			<p>在信息库中，没有找到在卖（买）此牌号的商家！</p>
			<p>参考价：<span>塑料圈查无此价格</span></p>
			<h3 v-if="n.says.length!==0"><i class="myicon iconSupply3"></i>塑料圈友</h3>
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
		page: 1,
		condition: true,
		countShow: false,
		count: "",
		id: "",
		user_id: ""
	}
},
methods: {
	del: function(id) {
		var _this = this;
		mui.confirm('', '确定删除此条信息？', ['取消', '确定'], function(e) {
			if(e.index == 1) {
				$.ajax({
					url: '/api/qapi1/deleteMyMsg',
					type: 'get',
					data: {
						id: id,
						token: window.localStorage.getItem("token")
					},
					dataType: 'JSON'
				}).then(function(res) {
					if(res.err == 0) {
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
					} else {
						weui.alert("删除失败", {
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
				}, function() {

				});

			} else {

			}
		});
	}
},
mounted: function() {
	var _this = this;
	try {
		var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
		piwikTracker.trackPageView();
	} catch(err) {

	}
	$.ajax({
		url: '/api/qapi1/getMyMsg',
		type: 'get',
		data: {
			type: 1,
			page: _this.page,
			token: window.localStorage.getItem("token"),
			size: 100
		},
		dataType: 'JSON'
	}).done(function(res) {
		if(res.err == 2) {
			_this.condition = false;
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

			} else {
				_this.condition = true;
				_this.name = res.data;
			}
		}).fail(function(){
			
		}).always(function(){
			
		});
	}
}
</script>