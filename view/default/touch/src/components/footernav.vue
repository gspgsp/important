<template>
    <footer id="footer">
    	<div class="releaseSale">
    		<textarea v-model="content" placeholder="写下您真实的需求,包括牌号，联系方式等，收到后我们会立即给您电话确认"></textarea>
    	</div>
    	<div class="releaseBtn" v-on:click="sendMsg">免费<br>委托<br>发布</div>
    	<div class="border-bottom"></div>
        <ul>
            <li><a v-link="{name:'buy'}" v-bind:class="{'footerOn':onePage}"><i class="foot"></i><br>我要买货</a></li>
            <li><a v-link="{name:'sale'}" v-bind:class="{'footerOn':twoPage}"><i class="foot2"></i><br>我要卖货</a></li>
            <li><a v-link="{name:'price'}" v-bind:class="{'footerOn':threePage}"><i class="foot3"></i><br>实时价格</a></li>
            <li><a v-link="{name:'todayindex'}" v-bind:class="{'footerOn':fourPage}"><i class="foot4"></i><br>行情指数</a></li>
        </ul>
    </footer>
</template>
<script>
	module.exports={
        data:function () {
            return {
            	onePage:true,
            	twoPage:false,
            	threePage:false,
            	fourPage:false
            }
        },
        ready:function () {            
        	var uri=window.location.hash;
        	console.log("---",uri);
        	switch (uri){
        		case "#!/buy":
        		this.onePage=true;
        		break;
        		case "#!/sale":
        		this.onePage=true;
        		break;
        		case "#!/price":
        		this.onePage=true;
        		break;
        		case "#!/todayindex":
        		this.onePage=true;
        		break;
        	}
        }
    
	}
</script>