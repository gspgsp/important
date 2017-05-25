__layout|public:mini_layout|layout__
<style type="text/css">
	.loginBg{background: url(__IMG__/admin/bg1.jpg)  no-repeat scroll 50% 0%; margin:0 auto;width: 920px;height: auto;overflow: hidden; opacity:1;}
</style>
<div id="loginWindow" class="mini-window" title="管理员登录" style="width:390px;height:235px;" 
   showModal="true" showCloseButton="false"
	>
  <div id="loginForm" style="padding:15px;padding-top:10px;">
	<table >
	  <tr>
		<td><label for="username$text">帐号：</label></td>
		<td><input id="username" name="username" vtype="rangeLength:3,20;" class="mini-textbox" required="true" style="width:200px;"/></td>
	  </tr>
	  <tr>
		<td><label for="pwd$text">密码：</label></td>
		<td><input id="pwd" name="pwd" vtype="minLength:5" class="mini-password" requiredErrorText="密码不能为空" required="true" style="width:200px;" onenter="onLoginClick"/></td>
	  </tr>
	  <?php if ($this->_var['login_mcode']): ?>
	  <tr>
		<td>手机动态码：</td>
		<td><input maxlength="6" id="mobileVcode" style="width:100px" class="mini-textbox" name="regmcode" requiredErrorText="请输入手机动态码" required="true" tabindex="4" />
					  <button class="codeGray" type="button" style="width:100px; float:right;" id="sendRegSms" tabindex="3">发送手机动态码</button></td>
	  </tr>
	  <?php endif; ?>
	  <tr>
		<td>验证码：</td>
		<td><input id="vcode" name="cpvcode" vtype="minLength:4" class="mini-textbox" requiredErrorText="验证码不能为空" required="true" style="width:80px; float:left;" onenter="onLoginClick"/> <img src="/public/api/vcode?name=cpvcode" name="cpvcode" id="vcodeImg" class="vImgchange" style="width:70px; height:26px; margin:0 5px; float:left;" ><a href="javascript:;" class="vImgchange">看不清？换一张</a>
		</td>
	  </tr>
	  <tr>
		<td></td>
		<td style="padding-top:5px;"><a onclick="onLoginClick" class="mini-button" style="width:60px;">登录</a> <a onclick="onResetClick" class="mini-button" style="width:60px;">重置</a></td>
	  </tr>
	  <tr>
		<td></td>
		<td><span id="returnMsg" style="padding-left:5px; color:#F00;"></span></td>
	  </tr>
	</table>
  </div>
</div>
<script type="text/javascript">
mini.parse();
var loginWindow = mini.get("loginWindow");
loginWindow.show();
window.onresize = function () {
	loginWindow.show();
}
$(function(){
	$(".vImgchange").click(function(){
		$("#vcodeImg").attr('src','/public/api/vcode?name='+$("#vcodeImg").attr('name')+'&t='+Math.random());
	});	
});

var passTime=0,passStatus=0;
$("#sendRegSms").live('click',function(){
	var $this=$(this);
	$("#returnMsg").html('');
	var passTimeOut=function(){
		if(passTime==0){
			$this.html('重新发送').addClass('codeGray').prop("disabled",false);
		}else{
			passTime--;
			$this.removeClass().addClass("codeGray").prop("disabled",true);
			$this.html(passTime+'秒后重发短信');
		}
	}
	if(passTime>0) return false;
	var usename = mini.get("username").getValue();
	var pwd = mini.get("pwd").getValue();
	$.post('/index/pass/sendMobileMsg',{name:usename,pwd:pwd},function(data){
		if(data.err=='0'){
			passTime=60; //默认60秒	
			$this.removeClass().addClass("codeGray").prop("disabled",true);
			$(this).html(passTime+'秒后重发短信')
			setInterval(function(){
				passTimeOut();				
			},1000)
		}else{
			$("#returnMsg").text(data.msg);
		}					   
	},'json');
});

function onLoginClick(e) {
	$("#returnMsg").html('');
	var form = new mini.Form("#loginWindow");

	form.validate();
	if (form.isValid() == false) return;
	
	var o = form.getData();
	var json = mini.encode(o);
	<?php if ($this->_var['login_mcode']): ?>
	var mcode = o.regmcode;
	//console.log(o);
	if(mcode.length!=6){
		$("#returnMsg").html('错误的手机动态码');
		return false;
	}
	<?php endif; ?>

	var callback=function(data){
		if(data.err=='0'){
			loginWindow.hide();
			mini.loading("登录成功，马上转到系统...", "登录成功");
			setTimeout(function () {
				window.location = "/";
			}, 1000);
		}else{
			$("#returnMsg").html(data.msg);
			$(".vImgchange").trigger('click');
			return false;
		}
	}
	utils.ajax('/index/pass/login',{data:json},callback,"POST","json");
}
function onResetClick(e) {
	var form = new mini.Form("#loginWindow");
	form.clear();
}
// $('.mini-panel-viewport').css({
// 	'background-color':'#ff0000',
// });
$('.mini-modal').addClass('loginBg');
</script>
