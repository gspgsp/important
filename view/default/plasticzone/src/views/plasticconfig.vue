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
			<li>
				<div class="configIco config1">Q：什么是塑料配资？</div>
				A：塑料行情上涨，但企业流动资金受限，“我的塑料网”可为用户垫付资金，进行代理采购。
			</li>
			<li>
				<div class="configIco config2">Q：什么是塑料配资？</div>
				A：塑料行情上涨，但企业流动资金受限，“我的塑料网”可为用户垫付资金，进行代理采购。
			</li>
			<li>
				<div class="configIco config3">Q：什么是塑料配资？</div>
				A：塑料行情上涨，但企业流动资金受限，“我的塑料网”可为用户垫付资金，进行代理采购。
			</li>
		</ul>
	</div>
</div>
</template>
<script>
export default {
	data: function() {
		return {

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

	}
}
</script>