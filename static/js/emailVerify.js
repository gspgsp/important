//邮箱认证类
window.ssrong.emailVerfiy = window.ssrong.emailVerfiy || {};
ssrong.emailVerfiy.stepOne = function() {
	$("#form_email_verify").validtip({
		ajaxPost:true, //使用ajax方式
		btnSubmit:"#btn_submit",
		beforeSubmit:function(form){
			$("#email_tip").attr('class','tip').html('');
		},
		callback:function(data){  //
			if(data.err==0){
				window.location.href='/my/profile/email?act=resend';
				$("#email_tip").attr('class','tip').html('');
			}else{
				$("#email_tip").attr('class','tip vtip_fail').html(data.msg);
			}
			return false;
		}
	});
}
ssrong.emailVerfiy.stepTwo = function() {
	doCountDown();
	var canSend=0;
	$("#btn_resend").click(function(){
		if($(this).hasClass('btns_gray')){
			return false;	
		}
		if(canSend==1) return false;
		$.post('/my/profile/emailSend',{email:$(this).attr('email')},function(data){
			if(data.err==0){
				doCountDown();
			}else{
				$("#email_tip").attr('class','tip vtip_fail').html(data.msg);
			}
																		  
		},"json");
		
	});
	function doCountDown(){
		$("#email_tip").attr('class','tip').html('');
		var countdown_sec = 60;
		var countdown = setInterval(function(){
			if(--countdown_sec <= 0){
				canSend=0;
				$("#btn_resend").text("重新发送邮件").removeClass('btns_gray').addClass("btns").prop('disabled',false);
				clearInterval(countdown);
			}else{
				$("#btn_resend").removeClass("btns").addClass('btns_gray').text(countdown_sec+"秒后可重新发送邮件");
			}
		},1000);
	}
}
