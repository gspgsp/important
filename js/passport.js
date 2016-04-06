//通行证类
window.ssrong.passport = window.utool.passport || {};
ssrong.passport.login = function() {
	var $errOjb=$("#errMsg"),usrname='',passwd='',err=0,status=0;
	$("#loginForm input[name=userName]").blur(function(){
		usrname=$(this).val();
		if(!utils.isMobile(usrname)){
			err=1;
			$errOjb.text('请输入正确的手机号').show();
		}else{
			err=0;
			$errOjb.text('').hide();
		}
	});
	$("#loginForm input[name=passWord]").blur(function(){
		passwd=$(this).val();
		if(passwd.length<6 || passwd.length>20){
			err=1;
			$errOjb.text('请输入正确的密码').show();
		}else{
			err=0;
			$errOjb.text('').hide();
		}
	});
	
	$("#loginForm").submit(function(){
		$("#btnLoginSmt").triggerHandler('click');
		return false;
	});

	$("#btnLoginSmt").click(function(){
		$("#loginForm input:visible").trigger('blur');
		if(err>0 || status>0){
			return false;	
		}

		//记住用户名
		if(window.localStorage && $("#rememberLoginName").size()){
			if($("#rememberLoginName").prop("checked")){
				localStorage.setItem("LoginName",$("#loginForm input[name=userName]").val());
			}else{
				localStorage.removeItem("LoginName");
			}
		}

		status=1;
		$.post('/user/login',{username:usrname,password:passwd},function(data){
			status=0;
			if(data.err!='0'){
				$errOjb.text(data.msg).show();
				return false;
			}
			window.location.href=$("#gurl").val()||'/my';
		},'json');
	});

	//记住用户名
	if(window.localStorage && (val = localStorage.getItem("LoginName"))){
		$("#loginForm input[name=userName]").val(val);
	}
}
ssrong.passport.vcode = function() {
	//重新加载验证码
	$(".vImgchange").click(function(){
		$("#vcodeImg").attr('src','/api/vcode?name='+$("#vcodeImg").attr('name')+'&t='+Math.random());
		$("#regInputVcode").val("").focus();
	});		   
}

//用户注册
ssrong.passport.register = function(){
	//默认提示信息
	$("#notice").data("default",$("#notice").find("span").text());

	//表单提交验证
	$("#regform").validtip({
		btnSubmit:"#btnRegSubmit",
		ajaxPost:true,
		postonce:true,
		rule:{
			check:function(value,obj,form,rules){
				return obj.is(":checked");
			}
		},
		tiptype:function(error,obj){
			if(obj.prop("name") == 'mobile'){
				if(error.type == 'pass')
					$("#sendRegSms").removeClass().addClass("MessageCode").prop("disabled",false)
				else
					$("#sendRegSms").removeClass().addClass("MessageCodeGray").prop("disabled",true)
			}

			switch(error.type){
				case 'pass':
					obj.next("i").attr("class","IconBasicSuccessSingle").removeAttr("title").show();
				case 'chking':
					$("#notice").find(".IconBasic").removeClass("Fail").addClass("Info").end().find("span").removeClass("c_f00").text(error.msg||$("#notice").data("default"));
					obj.removeClass("error");
					break;
				case 'fail':
					$("#notice").find(".IconBasic").removeClass("Info").addClass("Fail").end().find("span").addClass("c_f00").text(error.msg);
					obj.addClass("error").next("i").attr("class","IconBasicFailSingle").attr("title",error.msg).show();
			}
		},
		callback:function(ret){
			if(ret.err){
				ssrong.pop.show({"content":ret.msg});
				if(ret.msg.indexOf('验证码') > -1) $(".vImgchange").triggerHandler('click');
			}
			else
				window.location.href='/my/verify/guide';
		},
		plugin:{
			passwordstrength:{
				minLen:8,//设置密码长度最小值，默认为0;
				maxLen:20,//设置密码长度最大值，默认为30;
				trigger:function(obj,error,score){
					if(score<35){
						$(".strength-step").addClass('low').removeClass('mid high');
					}else if(score<70){
						$(".strength-step").addClass('mid').removeClass('low high');
					}else{
						$(".strength-step").addClass('high').removeClass('low mid');
					}
				}
			}
		}
	});
	
	var itl_reg_sms = null;
	var passTime=0,passStatus=0;
	$("#sendRegSms").on('click',function(){
		var $this=$(this);
		$.post('/user/register/sendmsg',$("#regInputMobile,#regInputVcode,#regType").serialize(),function(data){
			if(data.err=='0'){
				$("#mobileVcode").val("").focus();
				passTime=120;
				$this.removeClass().addClass("MessageCodeGray").prop("disabled",true);
				itl_reg_sms = setInterval(function(){
					if(passTime==0){
						$this.text('重新发送验证码').removeClass().addClass('MessageCode').prop("disabled",false);
						clearInterval(itl_reg_sms);
					}else{
						passTime--;
						$this.text(passTime+'秒后可重新发送');
					}			
				},1000)
			}else{
				ssrong.pop.show({"content":data.msg});
				if(data.msg.indexOf('验证码') > -1)
					$(".vImgchange").triggerHandler('click');
			}					   
		},'json');
	});

}

//修改登录密码
ssrong.passport.modify = function(){
	
	function errShow(msg){
		$("#vcodeImg").trigger('click');
		$("#modify_err").addClass('vtip_fail').text(msg);	
	}
	
	function errHide(){
		$("#modify_err").removeClass('vtip_fail').text('');
	}
	
	var passStatus=0;
	$("#modifyForm").validtip({
		ajaxPost:true,
		btnSubmit:"#btnModifySubmit",
		beforeSubmit:function(form){
			errHide();
			if($("#oldloginpass").val()==$("#new_password").val()){
				errShow('新老密码不能相同');
				return false;	
			}
			
			//提交数据
			if(passStatus>0) return false;
			passStatus=1;
			$.post('/my/passwd/modify',$("#modifyForm").serialize(),function(data){
				passStatus=0;
				if(data.err=='0'){ //更新成功
					window.location.href='/my/passwd/modifySuccess';
				}else{
					errShow(data.msg);
				}					   
			},'json');
			return false;
		}
	});
	
	errSend=function(msg){
		$("#modify_err").text(msg);	
	}
	
	//设置发送短信[//发送的按钮/发送类型/手机号或对象/返回函数]
	ssrong.smsSend($("#sendRegSms"),'modPasswd',$("#mobile"),errSend);
}

//设置支付密码
ssrong.passport.setPaypwd = function(){
	//设置发送短信
	ssrong.smsSend($("#sendPaySms"),'setPayPasswd',$("#mobile"),function(msg){
		$("#mcode_tip").text(msg);	
	});
	
	var passStatus=0;
	$("#paypwdForm").validtip({
		btnSubmit:"#btnPaypwdSubmit",
		beforeSubmit:function(form){
			//提交数据
			if(passStatus>0) return false;
			passStatus=1;
			$("#pay_err").removeClass('vtip_fail').text('');
			$.post('/my/passwd/pay',$("#paypwdForm").serialize(),function(data){
				passStatus=0;
				if(data.err=='0'){ //更新成功
					window.location.href='/my/passwd/paySuccess';
				}else{
					$("#pay_err").addClass('vtip_fail').text(data.msg);	
				}					   
			},'json');
			return false;
		}
	});
}

//修改支付密码
ssrong.passport.modPaypwd = function(){
	var passStatus=0;
	$("#paypwdForm").validtip({
		btnSubmit:"#btnPaypwdSubmit",
		beforeSubmit:function(form){
			//提交数据
			$("#pay_err").removeClass('vtip_fail').text('');
			if($("#oldPayPasswd").val()==$("#payPasswd").val()){
				$("#pay_err").addClass('vtip_fail').text('新老支付密码不能相同');
				return false;	
			}
			
			if(passStatus>0) return false;
			passStatus=1;
			$("#pay_err").removeClass('vtip_fail').text('');
			$.post('/my/passwd/payModify',$("#paypwdForm").serialize(),function(data){
				passStatus=0;
				if(data.err=='0'){ //更新成功
					window.location.href='/my/passwd/paySuccess';
				}else{
					$("#pay_err").addClass('vtip_fail').text(data.msg);	
				}					   
			},'json');
			return false;
		}
	});
	
	errSend=function(msg){
		$("#pay_err").text(msg);	
	}
	
	//设置发送短信[//发送的按钮/发送类型/手机号或对象/返回函数]
	ssrong.smsSend($("#sendRegSms"),'setPayPasswd',$("#mobile"),errSend);
}

//找回支付密码
ssrong.passport.findPaypwd = function(){
	var passStatus=0;
	$("#paypwdForm").validtip({
		btnSubmit:"#btnPaypwdSubmit",
		beforeSubmit:function(form){
			//提交数据
			$("#pay_err").removeClass('vtip_fail').text('');
			if($("#oldPayPasswd").val()==$("#payPasswd").val()){
				$("#pay_err").addClass('vtip_fail').text('新老支付密码不能相同');
				return false;	
			}
			
			if(passStatus>0) return false;
			passStatus=1;
			$("#pay_err").removeClass('vtip_fail').text('');
			$.post('/my/passwd/findPayPw',$("#paypwdForm").serialize(),function(data){
				passStatus=0;
				if(data.err=='0'){ //更新成功
					window.location.href='/my/passwd/paySuccess';
				}else{
					$("#pay_err").addClass('vtip_fail').text(data.msg);	
				}					   
			},'json');
			return false;
		}
	});
	
	errSend=function(msg){
		$("#pay_err").text(msg);	
	}
	
	//设置发送短信[//发送的按钮/发送类型/手机号或对象/返回函数]
	ssrong.smsSend($("#sendRegSms"),'findPayPasswd',$("#mobile"),errSend);
}

//找加密码
ssrong.passport.passwdFind = function(){
	ssrong.passport.vcode();
	//表单提交验证
	var passStatus=0;
	$("#passwdFingForm").validtip({
		btnSubmit:"#passwdFindSubmit",
		beforeSubmit:function(){
			if(passStatus>0) return false;
			passStatus=1;
			$.post('/user/find/submit',$("#passwdFingForm").serialize(),function(data){
				passStatus=0;
				if(data.err=='0'){
					if(data.url!=''){
						window.location.href=data.url;	
					}else{
						window.location.href='/user/find/passwdFindStep';
					}
				}else{
					$("#error_tip").removeClass('vtip_pass').text(data.msg);
				}					   
			},'json');
			return false;
		}
		
	});

}

//找回密码
ssrong.passport.passwdFindStep2 = function(){
	errSend=function(msg){
		$("#mcode_tip").text(msg);	
	}
	//设置发送短信
	ssrong.smsSend($("#sendPaySms"),'passwdFind',$("#mobile"),errSend);
	
	var passStatus=0;
	$("#passwdFingForm").validtip({
		btnSubmit:"#passwdFindSubmit",
		rule:{//传入自定义tiprule类型，可以是正则，也可以是函数;
			"idcard":function(value,obj,form,rules){	//value:表单值，obj表单元素，form表单，rules内置正则
				var Wi = [ 7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2, 1 ];// 加权因子  
				var ValideCode = [ 1, 0, 10, 9, 8, 7, 6, 5, 4, 3, 2 ];// 身份证验证位值.10代表X
				if(isTrueValidateIdCard(value)){
					return true; 
				}else{
					return false;
				}
				function isTrueValidateIdCard(a_numb) {  
					if(a_numb.length<18) return false;
					var a_numb = a_numb.split("");
					var sum = 0; // 声明加权求和变量  
					if (a_numb[17].toLowerCase() == 'x') {  
						a_numb[17] = 10;// 将最后位为x的验证码替换为10方便后续操作  
					}  
					for(var i = 0; i < 17; i++) {  
						sum += Wi[i] * a_numb[i];// 加权求和  
					}  
					valCodePosition = sum % 11;// 得到验证码所位置  
					if (a_numb[17] == ValideCode[valCodePosition]) {  
						return true;  
					} else {  
						return false;  
					}  
				}   
			}
		},
		beforeSubmit:function(){
			if(passStatus>0) return false;
			passStatus=1;
			$.post('/user/find/submit',$("#passwdFingForm").serialize(),function(data){
				passStatus=0;
				if(data.err=='0'){
					if(data.url!=''){
						window.location.href=data.url;	
					}else{
						window.location.href='/user/find/passwdFindStep';
					}
				}else{
					ssrong.pop.show({"content":data.msg});
				}					   
			},'json');
			return false;
		}
	});
}

//找回密码
ssrong.passport.passwdFindStep3 = function(){
	var passStatus=0;
	$("#passwdFingForm").validtip({
		btnSubmit:"#passwdFindSubmit"
	});
}
