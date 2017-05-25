<template>
	<header id="bigCustomerHeader">
       	信息发布
    </header>
	<div class="releaseSale">
    	<textarea v-model="content" placeholder="写下您真实的需求,包括牌号，联系方式等，收到后我们会立即给您电话确认"></textarea>
    </div>
    <div class="releaseBtn" v-on:click="sendMsg" style="font-size: 12px; top: 50px;">免费<br>委托<br>发布</div>
    <div id="btn">
    	<button v-on:click="startRecord">开始录音</button>
    	<button v-on:click="stopRecord">结束录音</button>
    </div>
    <p style="padding: 0 10px;">1.点击开始录音（只需要点击一次）就可以发布需求了PS：如需授权请点击同意</p>
    <p style="padding: 0 10px;">2.点击结束录音，您发布的需求会自动上传到后台，一会我们的交易员就会和您联系了，请耐心等待。</p>
    <p style="padding: 0 10px;">3.除了录音功能外，您也可以在文本框内填写您的真实需求。点击右侧的免费委托发布按钮即可。</p>
</template>
<script>
	module.exports={
        el:"#app",
        data:function () {
            return {
                content:"",
                recordId:"",
                serverId:""
            }
        },
        ready:function(){
        	this.$http.post('/mobi/wxShare/getSignPackage',{targetUrl:window.location.href}).then(function(res){
			console.log(res.json());

			wx.onMenuShareTimeline({
				title: "塑料圈-塑料人的行业通讯录", 
				link: 'http://m.myplas.com/plasticzone/plastic#!/index?invite='+tel, 
				imgUrl: 'http://statics.myplas.com/myapp/img/shareLogo.png', 
				success: function () {
					
				},
				cancel: function () {
					
				}
			});
			wx.onMenuShareAppMessage({
				title: "塑料圈通讯录",
				desc: "塑料圈-塑料人的行业通讯录",
				link: 'http://m.myplas.com/plasticzone/plastic#!/index?invite='+tel, 
				imgUrl: 'http://statics.myplas.com/myapp/img/shareLogo.png', 
				type: '', 
				dataUrl: '', 
				success: function () {
					
				},
				cancel: function () {
					
				}
			});
			wx.onMenuShareQQ({
				title: "塑料圈通讯录",
				desc: "塑料圈-塑料人的行业通讯录",
				link: 'http://m.myplas.com/plasticzone/plastic#!/index?invite='+tel, 
				imgUrl: 'http://statics.myplas.com/myapp/img/shareLogo.png', 
				success: function () {
					
				},
				cancel: function () {
					
				}
			});
			
        	},function(){
        		
        	});
        
        },
        methods:{
        	startRecord:function(){
        		wx.startRecord();
        	},
        	stopRecord:function(){
				wx.stopRecord({
				    success: function (res) {
				        this.recordId = res.localId;
						wx.uploadVoice({
						    localId: this.recordId, // 需要上传的音频的本地ID，由stopRecord接口获得
						    isShowProgressTips: 1, // 默认为1，显示进度提示
						        success: function (res) {
						        this.serverId = res.serverId; // 返回音频的服务器端ID
						        var btnArray = ['否', '是'];
				                mui.confirm('录音结束是否上传录音', '', btnArray, function(e) {
				                    if (e.index == 1) {
				                        this.$http.post('/mobi/wxShare/downLoadVoice',{media_id:this.serverId}).then(function(res){
						            		console.log(res.json());
						            		mui.alert("",res.json().msg,function(){
						        				window.location.reload();
						        			});
						            	},function(){
						            		
						            	});        		
				                    } else {
				                        
				                    }
				                });
						    }
						});	   		

				    }
				});        		
        	},
            sendMsg:function(){
            	if(!this.content){
            		mui.alert("","请填写委托内容",function(){
            			
            		});
            	}else{
	            	this.$http.post('/touch/newPage/release',{content:this.content}).then(function(res){
	            		console.log(res.json());
	            		mui.alert("",res.json().msg,function(){
            				window.location.reload();
            			});
	            	},function(){
	            		
	            	});
            	}
            }
        }
	}
</script>