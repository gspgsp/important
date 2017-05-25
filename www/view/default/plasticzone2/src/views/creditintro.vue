<template>
<div class="buyWrap" style="padding: 45px 0 0 0;">
	<div style="position: fixed; top: 0; left: 0; width: 100%; z-index: 10;">
		<header id="bigCustomerHeader">
			<a class="back" href="javascript:window.history.back();"></a>
			企业授信说明
		</header>
	</div>
	<div style="padding: 10px; background: #FFFFFF; font-size: 12px;">
		<p>授信说明:</p>
		<p>本授信是[我的塑料网]针对网站的优质客户，提供的供应链金融业务授信，客户可以在授信额度范围内使用[我的塑料网]提供的供应链金融产品，满足企业的融资需求。</p>
		<p>特点:</p>
		<p>1.以便捷、高效的整体合作模式，使客户迅速得到全面和规范的供应链金融服务；</p>
		<p>2.节省客户的用款成本，简化工作手续，提高融资效率；</p>
		<p>3.基于真实订单需求，在整体信用额度的前提下，随借随用，即时到账；</p>
		<p>4.低利息、低费用。</p>
		<p>适用客户:</p>
		<p>企业信用良好、与[我的塑料网]订单成交三笔以上以及其他必要条件。</p>
		<p>注：授信解释权归[我的塑料网]所有。</p>
	</div>
</div>
</template>
<script>
module.exports = {
	data: function() {
		return {

		}
	},
	mounted: function() {
	try {
	    var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
	    piwikTracker.trackPageView();
	} catch( err ) {
		
	}
	}
}
</script>