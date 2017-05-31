<template>
  <div id="app">
  	我是主页
  	{{msg}}
  	{{msg2}}
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
			msg:"asdfsafdfssadf",
			msg2:"213123"
		}
	},
	mounted: function() {
		
	}
}
</script>
