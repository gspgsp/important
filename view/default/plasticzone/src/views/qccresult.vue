<template>
	<div class="buyWrap" style="padding: 0;">
    <header id="bigCustomerHeader">
    	<a class="back" href="javascript:window.history.back();"></a>
    	查询结果
    </header>
    <div class="qccWrap">
    	<div v-show="show1">
    	<h3 class="qccTitle"><span>基本信息</span></h3>
    	<table width="100%" class="qcctable" cellpadding="0" cellspacing="0">
    		<tr>
    			<td width="50%" class="underline">
    				<i class="qccIcon icon1"></i>
    				<div>法定代表人<br>{{oper_name}}</div>
    			</td>
    			<td width="50%" class="underline">
    				<i class="qccIcon icon5"></i>
    				<div>注册资本<br>{{register_capi}}万人民币</div>
    			</td>
    		</tr>
    		<tr>
    			<td class="underline">
    				<i class="qccIcon icon3"></i>
    				<div>注册时间<br>{{start_date}}</div>
    			</td>
    			<td class="underline">
    				<i class="qccIcon icon4"></i>
    				<div>状态<br>{{status}}</div>
    			</td>
    		</tr>
    		<tr>
    			<td colspan="2" style="padding: 10px 15px;">
    				<p><span>工商注册号：</span>{{register_no}}</p>
    				<p><span>统一信用代码：</span>{{credit_code}}</p>
    				<p><span>企业类型：</span>{{econkind}}</p>
    				<p><span>行业：</span>{{industry}}</p>
    				<p><span>营业期限：</span>{{term_start}}至{{term_end}}</p>
    				<p><span>核准日期：</span>{{update_date}}</p>
    				<p><span>登记机关：</span>{{belong_org}}</p>
    				<p><span>注册地址：</span>{{address}}</p>
    				<p><span>经营范围：</span>{{scope}}</p>
    			</td>
    		</tr>
    	</table>
    </div>
    <div v-show="show2" style="text-align: center;">
    	{{msg}}
    </div>
    </div>
    </div>
</template>
<script>
	export default{
        data:function () {
            return {
				oper_name:"",
				register_capi:"",
				status:"",
				start_date:"",
				register_no:"",
				credit_code:"",
				econkind:"",
				industry:"",
				term_start:"",
				term_end:"",
				update_date:"",
				belong_org:"",
				address:"",
				scope:"",
				show1:false,
				show2:false,
				msg:""
            }
        },
        methods:{

        },
		beforeRouteLeave:function(to,from,next){
			next(function(){
				
			});
			this.show1=false;
			this.show2=false;				

		},
        activated:function () {
        	var _this=this;
			$.ajax({
				type: "post",
				url: "/api/qapi1_1/getQiChaCha",
				data: {
					name: _this.$route.query.cname,
					token: window.localStorage.getItem("token")
				},
				dataType: 'JSON'
			}).then(function(res) {
				if (res.err==0) {
					_this.show1=true;
					_this.show2=false;
					_this.oper_name=res.data.oper_name,
					_this.register_capi=parseInt(res.data.register_capi),
					_this.status=res.data.status.substring(0,2),
					_this.start_date=res.data.start_date,
					_this.register_no=res.data.register_no,
					_this.credit_code=res.data.credit_code,
					_this.econkind=res.data.econkind,
					_this.industry=res.data.industry,
					_this.term_start=res.data.term_start,
					_this.term_end=res.data.term_end,
					_this.update_date=res.data.update_date,
					_this.belong_org=res.data.belong_org,
					_this.address=res.data.address,
					_this.scope=res.data.scope
				} else if(res.err>0){
					_this.show1=false;
					_this.show2=true;
					_this.msg=res.msg;
				}
			}, function() {
		
			});
        }    
	}
</script>