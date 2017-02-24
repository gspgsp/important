<template>
    <footer id="footer">
        <ul>
            <li><a v-link="{name:'index'}" v-bind:class="{'footerOn':isIndex}"><i class="foot2"></i><br>通讯录</a></li>
            <li><a v-link="{name:'release'}" v-bind:class="{'footerOn':isRelease}"><i class="foot3"></i><br>供求</a></li>
            <li><a v-link="{name:'headline'}" v-bind:class="{'footerOn':isHeadline}"><i class="foot5"></i><br>头条</a></li>
            <li><a v-link="{name:'myzone'}" v-bind:class="{'footerOn':isMyzone}"><i class="foot4"></i><br>我</a></li>
        </ul>
    </footer>
</template>
<script>
	module.exports={
        data:function () {
            return {
            	isIndex:false,
            	isRelease:false,
            	isMyzone:false,
            	isHeadline:false
            }
        },
        ready:function () {
   				var uri=this.$route.name;
   				switch(uri){
   					case 'index':
   						this.isIndex=true;
   						this.isRelease=false;
   						this.isMyzone=false;
   						this.isHeadline=false;
   					break;
   					case 'release':
   						this.isIndex=false;
   						this.isRelease=true;
   						this.isMyzone=false;
   						this.isHeadline=false;
   					break;
   					case 'myzone':
   					case 'mysupply':
   					case 'mybuy':
   					case 'myinvite':
   					case 'myfans':
   					case 'mypay':
   					case 'mymsg':
   					case 'mymsg2':
   					case 'myinfo':
   						this.isIndex=false;
   						this.isRelease=false;
   						this.isMyzone=true;
   						this.isHeadline=false;
   					break;
   					case 'headline':
   					case 'headlinedetail':
   					case 'headlinelist':
   						this.isIndex=false;
   						this.isRelease=false;
   						this.isMyzone=false;
   						this.isHeadline=true;
   					break;
   				}
        }
	}
</script>