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
				<p>参考价：<span>塑料圈查无此价格</span></p>
				<h3><i class="myicon iconSupply3"></i>塑料圈友</h3>
				<div class="triangle-up" v-if="n.says.length!==0"></div>
				<div class="replyRelease2" v-if="n.says.length!==0">
					<p v-for="n2 in n.says" v-if="n.user_id==n2.rev_id">
						<router-link :to="{name:'personinfo',params:{id:n2.user_id}}">{{n2.user_name}}</router-link>说:<i>{{n2.content}}</i>
					</p>
					<p v-for="n2 in n.says" v-if="n.user_id!==n2.rev_id">
						<router-link :to="{name:'personinfo',params:{id:n2.user_id}}">{{n2.user_name}}</router-link>回复
						<router-link :to="{name:'personinfo',params:{id:n2.rev_id}}">{{n2.rev_name}}</router-link>:<i>{{n2.content}}</i>
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
export default{
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
			user_id: ""
		}
	},
	methods: {

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