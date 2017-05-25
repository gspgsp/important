<template>
<div class="loadingPage" v-show="loading">
	<div class="loadingWrap">
		<div class="slqLoading"></div>
		<div class="slqLoadingTxt">数据加载中,请稍候...</div>
	</div>
</div>
</template>
<script>
module.exports = {
	props:['loading']
}
</script>