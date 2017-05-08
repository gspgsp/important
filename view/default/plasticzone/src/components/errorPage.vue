<template>
<div class="errorPage" v-show="loading">
	<div class="errorWrap"></div>
	<div class="errorTxt">
		<a class="blue" href="javascript:window.location.reload();">重新刷新</a>&nbsp;&nbsp;&nbsp;&nbsp;
		<a class="orange" href="http://q.myplas.com/">返回首页</a>
	</div>
</div>
</template>
<script>
module.exports = {
	props:['loading']
}
</script>