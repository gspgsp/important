<template>
<div class="buyWrap">
	<header id="bigCustomerHeader">
		<a class="back" href="javascript:window.history.back();"></a>
		塑料配资
	</header>
	<div class="configWrap">
		<ul class="configUl">
			<li>
				<div class="configIco config1"></div>
				A：塑料行情上涨，但企业流动资金受限，“我的塑料网”可为用户垫付资金，进行代理采购。
			</li>
			<li>
				<div class="configIco config1"></div>
				A：塑料行情上涨，但企业流动资金受限，“我的塑料网”可为用户垫付资金，进行代理采购。
			</li>
		</ul>
	</div>

</div>
</template>
<script>
export default{
	data: function() {
		return {

		}
	},
	methods: {

	},
	activated: function() {
		var _this = this;
		try {
		    var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
		    piwikTracker.trackPageView();
		} catch( err ) {
			
		}

	}
}
</script>