<template>
<div class="buyWrap" style="padding: 0 0 70px 0;">
	<header id="bigCustomerHeader">
		<a class="back" href="javascript:window.history.back();"></a>
		{{cate}}
		<a class="detailShare" href="javascript:;" v-on:click="shareshow()"></a>
	</header>
	<div class="headlinecontent">
		<div class="headlinetitle">
			<h3>{{type}} {{title}}</h3>
			<p>作者:{{author}}&nbsp;&nbsp;&nbsp;阅读数量:{{pv}}&nbsp;发布时间：{{time}}</p>
		</div>
		<div class="headlinetxt">
			{{{content}}}
		</div>
		<span class="pre" v-on:click="pre()"></span>
		<span class="nex" v-on:click="nex()"></span>
	</div>
	<footerbar></footerbar>
</div>
<div class="sharelayer" v-show="share" v-on:click="sharehide"></div>
<div class="tip" v-show="share3"></div>
</template>
<script>
var footer=require("../components/footer");
module.exports = {
	components:{
    	'footerbar':footer
   },
	data: function() {
		return {
			title: "",
			content: "",
			cate: "",
			id: "",
			cate_id: "",
			author: "",
			time: "",
			pv: "",
			type:"",
		    share:false,
        	share3:false,
        	share4:false
		}
	},
	watch:{
		title:function(){
			var _this=this;
			if(this.type=="PUBLIC"){
				this.type="";
			}else{
				this.type='['+this.type+']';
			}
			wx.onMenuShareTimeline({
				title: _this.type+_this.title,
				link: window.location.href,
				imgUrl: 'http://statics.myplas.com/myapp/img/shareLogo.png',
				success: function() {
	
				},
				cancel: function() {
	
				}
			});
			wx.onMenuShareAppMessage({
				title: _this.type+_this.title,
				desc: "塑料圈-塑料人的通讯录",
				link: window.location.href,
				imgUrl: 'http://statics.myplas.com/myapp/img/shareLogo.png',
				type: '',
				dataUrl: '',
				success: function() {
	
				},
				cancel: function() {
	
				}
			});
			wx.onMenuShareQQ({
			    title: _this.type+_this.title,
			    desc: '塑料圈-塑料人的通讯录',
			    link: window.location.href,
			    imgUrl: 'http://statics.myplas.com/myapp/img/shareLogo.png',
			    success: function () { 
			       
			    },
			    cancel: function () { 
			       
			    }
			});
		}
	},
	methods: {
		shareshow:function(){
        	this.share=true;
        	this.share3=true;
        },
        sharehide:function(){
    		this.share=false;
    		this.share3=false;
    		this.share4=false;
    	},
		pre: function() {
			var _this = this;
			$.ajax({
				type: "post",
				url: "/plasticzone/plastic/getPlasticNewsDetail",
				data: {
					id: _this.id,
					cateId: _this.cate_id,
					pre: 1
				},
				dataType: 'JSON'
			}).then(function(res) {
				console.log(res);
				if(res.err == 0) {
					_this.$set("title", res.data.title);
					_this.$set("id", res.data.id);
					_this.$set("cate_id", res.data.cate_id);
					_this.$set("content", res.data.content);
					_this.$set("time", res.data.input_time);
					_this.$set("pv", res.data.pv);
					_this.$set("type", res.data.type);
					_this.$set("author", res.data.author);
					switch(_this.cate_id) {
						case "1":
							_this.cate = "早盘预测";
							break;
						case "2":
							_this.cate = "塑料上游";
							break;
						case "4":
							_this.cate = "中晨塑说";
							break;
						case "5":
							_this.cate = "外盘快递";
							break;
						case "9":
							_this.cate = "企业动态";
							break;
						case "11":
							_this.cate = "装置动态";
							break;
						case "12":
							_this.cate = "期刊报告";
							break;
						case "21":
							_this.cate = "期货资讯";
							break;
						case "22":
							_this.cate = "独家解读";
							break;
					}
				} else {
					mui.alert("", "没有相关文章", function() {

					});
				}

			}, function() {

			});

		},
		nex: function() {
			var _this = this;
			$.ajax({
				type: "post",
				url: "/plasticzone/plastic/getPlasticNewsDetail",
				data: {
					id: _this.id,
					cateId: _this.cate_id,
					nex: 2
				},
				dataType: 'JSON'
			}).then(function(res) {
				console.log(res);
				if(res.err == 0) {
					_this.$set("title", res.data.title);
					_this.$set("id", res.data.id);
					_this.$set("cate_id", res.data.cate_id);
					_this.$set("content", res.data.content);
					_this.$set("time", res.data.input_time);
					_this.$set("type", res.data.type);
					_this.$set("pv", res.data.pv);
					_this.$set("author", res.data.author);
					switch(_this.cate_id) {
						case "1":
							_this.cate = "早盘预测";
							break;
						case "2":
							_this.cate = "塑料上游";
							break;
						case "4":
							_this.cate = "中晨塑说";
							break;
						case "5":
							_this.cate = "外盘快递";
							break;
						case "9":
							_this.cate = "企业动态";
							break;
						case "11":
							_this.cate = "装置动态";
							break;
						case "12":
							_this.cate = "期刊报告";
							break;
						case "21":
							_this.cate = "期货资讯";
							break;
						case "22":
							_this.cate = "独家解读";
							break;
					}
				} else {
					mui.alert("", "没有相关文章", function() {

					});
				}

			}, function() {

			});
		}
	},
	ready: function() {
		var _this = this;
		$.ajax({
			type: "post",
			url: "/plasticzone/plastic/getPlasticNewsDetail",
			data: {
				id: _this.$route.params.id
			},
			dataType: 'JSON'
		}).then(function(res) {
			console.log(res);
			_this.$set("id", res.data.id);
			_this.$set("title", res.data.title);
			_this.$set("cate_id", res.data.cate_id);
			_this.$set("content", res.data.content);
			_this.$set("time", res.data.input_time);
			_this.$set("type", res.data.type);
			_this.$set("pv", res.data.pv);
			_this.$set("author", res.data.author);
			switch(_this.cate_id) {
				case "1":
					_this.cate = "早盘预测";
					break;
				case "2":
					_this.cate = "塑料上游";
					break;
				case "4":
					_this.cate = "中晨塑说";
					break;
				case "5":
					_this.cate = "外盘快递";
					break;
				case "9":
					_this.cate = "企业动态";
					break;
				case "11":
					_this.cate = "装置动态";
					break;
				case "12":
					_this.cate = "期刊报告";
					break;
				case "21":
					_this.cate = "期货资讯";
					break;
				case "22":
					_this.cate = "独家解读";
					break;
			}
		}, function() {

		});
	}
}
</script>