<template>
<div class="buyWrap" style="padding: 0;">
	<header id="bigCustomerHeader">
		<a class="back" href="javascript:window.history.back();"></a>
		塑料配资
		<a class="configSobot" href="https://www.sobot.com/chat/h5/index.html?sysNum=137f8799efcb49fea05534057318dde0"></a>
		<a class="configTel" href="javascript:;" v-on:click="tel"></a>
	</header>
	<div class="configWrap">
		<ul class="configUl">
			<li v-for="(c,index) in configLi">
				<div class="configIco" v-bind:class="{config1:0==index%3,config2:1==index%3,config3:2==index%3}">Q：<span v-html="c.q"></span></div>
				A：<span v-html="c.a"></span>
			</li>
		</ul>
	</div>
</div>
</template>
<script>
export default {
	data: function() {
		return {
			configLi:[]
		}
	},
	methods: {
		tel: function() {
			weui.actionSheet([{
				label: '<a style=" color:#0091ff; display:block;" href="tel:4006129965">400-6129-965</a>',
				onClick: function() {

				}
			}, {
				label: '<a style=" color:#0091ff; display:block;" href="tel:02161070985">021-61070985</a>',
				onClick: function() {

				}
			}], [{
				label: '<span style=" color:#0091ff;">取消</span>',
				onClick: function() {
					
				}
			}], {
				className: 'custom-classname'
			});
		}
	},
	activated: function() {
		var _this = this;
		try {
			var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
			piwikTracker.trackPageView();
		} catch(err) {

		}
		
		var _this=this;
		$.ajax({
			type: "post",
			url: version+'/credit/creditLimitPage',
			data: {

			},
			headers: {
				'X-UA': window.localStorage.getItem("XUA")
			},
			dataType: 'JSON'
		}).then(function(res) {
			if(res.err==0){
				_this.configLi=res.data;			
			}
		}, function() {

		});
	}
}
</script>