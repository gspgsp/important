<template>
<div class="buyWrap" style="padding: 45px 0 50px 0;">
	<div style="position: fixed; top: 0; left: 0; width: 100%; z-index: 6;">
		<header id="bigCustomerHeader">
			<a class="back" href="javascript:window.history.back();"></a>
			发布供给
		</header>
	</div>
	<div class="releaseWrap2">
		<div style="text-align: center; padding: 20px 0;">
			<div style="width: 100%; text-align: center;">
			<div class="releasebschoose">
				<span v-bind:class="{releasebson:show1}" v-on:click="spanshow1">快速发布</span>
				<span v-bind:class="{releasebson:show2}" v-on:click="spanshow2">精准发布</span>
			</div>
			</div>
		</div>
		<div style="padding: 0 10px;">
			<div style=" font-size: 12px; color: #999; margin: 0;">快速发布：简单粘贴或复制供求，快速录入；</div>
			<div style=" font-size: 12px; color: #999; margin: 0;">精准发布：填写准确供求，参与系统比价；</div>
		</div>
		<div v-show="show1">
		<p style="width: 100%; margin: 20px 0 0 0;">
			<textarea autofocus style="width: 100%;" placeholder="在此文本框内，可快速复制粘贴供求信息，限制100字以内！" maxlength="100" v-model="remark"></textarea>
		</p>
		</div>
		<div v-show="show2" style="width: 100%; margin: 20px 0 0 0;">
		<p>
			<label>牌号</label><input type="text" v-model="model" />
		</p>
		<p>
			<label>厂家</label><input type="text" v-model="f_name" />
		</p>
		<p>
			<label>价格</label><input type="number" v-model="price" v-on:blur="checkNum" />
		</p>
		<p>
			<label>交货地</label><input type="text" v-model="store_house" />
		</p>
		</div>
	</div>
	<div class="footrelease">
		<input type="button" v-on:click="sale" style="border: none; border-bottom: 1px solid #b33901;" v-bind:disabled="isDisable" value="发布" />
	</div>
</div>
</template>
<script>
module.exports = {
	data: function() {
		return {
			type: 2,
			store_house: "",
			model: "",
			f_name: "",
			price: "",
			remark: "",
			show: false,
			content: "",
			id: "",
			user_id: "",
			isDisable: false,
			show1:true,
			show2:false
		}
	},
	methods: {
		spanshow1:function(){
			this.show1=true;
			this.show2=false;
		},
		spanshow2:function(){
			this.show1=false;
			this.show2=true;			
		},
		checkNum: function() {
			if(this.price < 1000 || this.price > 30000) {
				mui.alert("", "输入的价格不合理", function() {

				});
			}
		},
		sale: function() {
			var _this = this;
			this.isDisable = true;
			var data = [];
			var arr = {
				'model': this.model.toUpperCase(),
				'f_name': this.f_name,
				'store_house': this.store_house,
				'price': this.price,
				'type': 2,
				'quan_type': 0,
				'content': this.remark
			};
			data.push(arr);
			if(this.type && this.store_house && this.model && this.f_name && this.price || this.remark) {
				$.ajax({
					url: '/api/qapi1/pub',
					type: 'post',
					data: {
						data:data,
						token: window.localStorage.getItem("token")
					},
					dataType: 'JSON'
				}).then(function(res) {
					if(res.err==0){
						mui.toast('发布成功', {
							duration: 'long',
							type: 'div'
						});
						_this.isDisable = false;
						_this.$router.push({
							name: 'release'
						});
					}else{
						mui.alert("", res.msg, function() {
							window.location.reload();
						});
					}
				}, function() {

				});
			} else {
				mui.alert("", "请把信息填写完整", function() {
					_this.isDisable = false;
				});
			}
		}
	},
	activated: function() {
		var _this = this;
			try {
	    var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
	    piwikTracker.trackPageView();
	} catch( err ) {
		
	}
		$.ajax({
			url: '/api/qapi1/secondPub',
			type: 'get',
			data: {
				id: _this.$route.query.id,
				token: window.localStorage.getItem("token")
			},
			dataType: 'JSON'
		}).then(function(res) {
			console.log(res);
			if(res.err == 0) {
				if(res.data.f_type==1){
					_this.show1 = false;
					_this.show2 = true;
					_this.f_name=res.data.f_name;
					_this.model=res.data.model;
					_this.store_house=res.data.store_house;
					_this.price=res.data.unit_price;
				}else{
					_this.show1 = true;
					_this.show2 = false;
					_this.remark=res.data.content;
				}		
			} else if(res.err == 1) {
				mui.alert("", res.msg, function() {
					_this.$router.push({
						name: 'login'
					});
				});
			}
		}, function() {

		});
	}
}
</script>