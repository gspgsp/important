<template>
<div class="buyWrap" style="padding: 0;">
	<header id="bigCustomerHeader">
		<a class="back" href="javascript:window.history.back();"></a>
			企业信用额度
		<a class="configSobot" href="https://www.sobot.com/chat/h5/index.html?sysNum=137f8799efcb49fea05534057318dde0"></a>
		<a class="configTel" href="javascript:;" v-on:click="tel"></a>
	</header>
	<div class="searchWrap">
		<div class="searchWrapTitle">信用额度查询：</div>
		<div class="searchfname">
			<div class="searchfnameWrap">
			<div style=" width: auto; margin-right: 80px;">
				<input type="text" v-model="fname" style=" width: 100%; line-height: 22px; float: left; border: none; padding: 5px 7px; background: none; font-size: 12px;" placeholder="请输入企业名称" />
				<div class="searchbtn" v-on:click="search">查询</div>
			</div>	
			</div>
		</div>
		<div class="belongFirm">
			您所属企业：<a href="#">上海中晨电子商务</a>
		</div>
	</div>
	<div class="configWrap">
		<ul class="configUl">
			<li>
				<div class="configIco config1">Q：什么是塑料配资？</div>
				A：塑料行情上涨，但企业流动资金受限，“我的塑料网”可为用户垫付资金，进行代理采购。
			</li>
			<li>
				<div class="configIco config2">Q：什么是塑料配资？</div>
				A：塑料行情上涨，但企业流动资金受限，“我的塑料网”可为用户垫付资金，进行代理采购。
			</li>
		</ul>
	</div>
	<ul class="searchli">
		<li v-for="c in creditli">
			<router-link :to="{name:'credit2',params:{id:c.contact_id}}">{{c.c_name}}</router-link></li>
		</li>	
	</ul>
</div>
</template>
<script>
export default{
	data: function() {
		return {
			fname:"",
			creditli:[]
		}
	},
	methods:{
		tel: function() {
			weui.actionSheet([{
				label: '<a style=" color:#0091ff; display:block;" href="tel:4006129965">400-6129-965</a>',
				onClick: function() {

				}
			}, {
				label: '<a style=" color:#0091ff; display:block;" href="tel:02161070985">021-61070985</a>',
				onClick: function() {

				}
			}], [{
				label: '<span style=" color:#0091ff;">取消</span>',
				onClick: function() {
					
				}
			}], {
				className: 'custom-classname'
			});
		},
		search:function(){
			var _this=this;
			$.ajax({
				type: "post",
				url: version+'/credit/creditCertificate',
				data: {
					token: window.localStorage.getItem("token"),
					type:2,
					page:1,
					fname:_this.fname
				},
				headers: {
					'X-UA': window.localStorage.getItem("XUA")
				},
				dataType: 'JSON'
			}).then(function(res) {
				if(res.err==0){
					_this.creditli=res.data;			
				}else{
					weui.alert(res.msg, {
						title: '塑料圈通讯录',
						buttons: [{
							label: '确定',
							type: 'parimary',
							onClick: function() {
								_this.$router.push({
									name: 'login'
								});
							}
						}]
					});				
				}
			}, function() {
	
			});
		}
	},
	activated: function() {
		this.creditli=[];
		this.fname="";
		try {
		    var piwikTracker = Piwik.getTracker("http://wa.myplas.com/piwik.php", 2);
		    piwikTracker.trackPageView();
		} catch( err ) {
			
		}
	}
}
</script>