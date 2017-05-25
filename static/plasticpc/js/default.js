(function($){
	var main = $("body"),
		left = $(".left"),
		center = $(".center"),
		right = $(".right"),
		setHeight = $(window).height(),
		setWidth = $(window).width() - left.width() - center.width(); 
	setting(setHeight,setWidth);
	
	//当改变窗口大小时
	$(window).resize(function(){
		setHeight = $(window).height();
		setWidth = $(window).width() - left.width() - center.width(); 
		setting(setHeight,setWidth);
	});
	
	//进入页面，加载中部的内容
	// center.load("center.html");
	center.load("/plasticpczone/index/loadPage",{'type':'center'});

	//点击左侧菜单
	left.find("li").bind("click",function(){
		var myThis = $(this),
			flag = 1,
			index;
		//判断是否登录，若没有，则需要先登录
		if(!flag){
			right.load("login.html");
			return;
		}	
			
		myThis.addClass("hover").siblings().removeClass("hover");
		index = myThis.index();
		if(index==0) center.load("center.html");
		else if(index==3) center.load("center1.html");
		right.html("");
	});
	
	//点击注册按钮
	$(".go-reg").bind("click",function(e){
		// right.load("reg.html");
		right.load("/plasticpczone/index/loadPage",{'type':'reg'})
	});
	
	//点击注册页面里的回退按钮
	$(".back-reg").bind("click",function(){
		right.load("login.html");
	});
	
	//点击注册页面里的服务协议
	$(".check a").bind("click",function(){
		right.load("agreement.html");
	});
	
	//点击服务协议里的回退按钮
	$(".back-agree").bind("click",function(){
		right.load("reg.html");
	});
	//完善注册页面
	$(".reg-detail").bind("click",function(){
		right.load("reg-detail.html");
	});
	
	//从完善注册页面回退
	$(".back-reg-detail").bind("click",function(){
		right.load("reg.html");
	});
	//点击忘记密码
	$(".forget").bind("click",function(){
		right.load("find-pwd.html");
	});
	
	//点击忘记密码页面里的回退按钮
	$(".back-find").bind("click",function(){
		right.load("login.html");
	});
	
	//获取验证码
	$(".get-code").bind("click",function(){
		var _this = $(this);
		if(_this.attr("data-bool")=="true") time(_this,60);
		_this.attr("data-bool","false");
	});  	
	
	function setting(arg1,arg2){
		left.height(arg1);
		center.height(arg1);
		right.height(arg1);
		right.width(arg2);
		main.height(arg1);
	}
	
	//倒计时
	function time(btn,wait) {
	  if (wait == 0) {
		  btn.removeClass("disabled").addClass("enable").html("获取验证码");
		  btn.attr("data-bool","true");
		  wait = 60;
	  } else {
		  btn.removeClass("enable").addClass("disabled").html("倒计时 "+wait+"S");
		  wait--;
		  setTimeout(function () {
			  time(btn,wait);
		  },
		  1000)
	  }
	}
	
})(window.jQuery);