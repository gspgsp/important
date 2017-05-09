<template>
    <ul id="nameUl">
		<li v-show="condition" v-for="n in name">
			<div style="width: 55px; height: 55px; float: left; position: relative;">
				<div class="avator">
					<img v-bind:src="n.focused_id.thumb">
				</div>
				<i class="iconV" v-bind:class="{'v1':n.focused_id.is_pass==1,'v2':n.focused_id.is_pass==0}"></i>
			</div>
			<div class="nameinfo">
				<router-link :to="{name:'personinfo',params:{id:n.focused_id.user_id}}">
					<p class="first"><i class="icon wxGs"></i>{{n.focused_id.c_name}}</p>
					<p class="second"><i class="icon wxName"></i>{{n.focused_id.name}}<i class="icon wxMobile"></i>{{n.focused_id.mobile}}</p>
					<p class="second">&nbsp;发布供给：<b style="font-weight: normal;">{{n.focused_id.sale}}</b>条    发布求购：<b style="font-weight: normal;">{{n.focused_id.buy}}</b>条</p>
				</router-link>
			</div>
		</li>
		<li v-show="!condition" style="text-align: center;">
			没有相关数据
		</li>
	</ul>
</template>
<script>
	export default{
        props:['name','condition']
	}
</script>