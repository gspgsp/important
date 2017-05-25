<template>
  <div id="app">
  	我是主页
  	{{msg}}
  	<Hello></Hello>
  </div>
  
</template>
<script>
import Hello from "../../components/Hello";
export default {
  name: 'app',
  components: {
		'Hello': Hello
	},
	data: function() {
		return {
			msg:"134132"
		}
	},
	mounted: function() {

	}
}
</script>
