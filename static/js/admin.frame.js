$(function(){
	frameInit();
});

//初始化框架
function frameInit(){
	//调整自动大小-0
	window.onresize = function(){
		$('#workspace').height(document.documentElement.clientHeight-70);
		$("#openClose").height($("#workspace").height());	
		$('.lmenu').height($("#workspace").height()+10);
	}
	window.onresize();
	$(window).resize(function(){
		if($(window).width()<960){
			$('#head').css('width',960+'px');
			$('#content').css('width',960+'px');
			$('body').attr('scroll','');
			$('body').css('overflow','');
		}else{
			$('#head').css('width','auto');
			$('#content').css('width','auto');
			$('body').attr('scroll','no');
			$('body').css('overflow','hidden');
		}
		//var mheight=$("#lmenu").height(),lheight=$('.lmenu').height();
		//if(lheight<=mheight+25){$("#scrollLink").show();}else{$("#scrollLink").hide();}
	});
	
	//左侧开关:点击-1
	$("#openClose").click(function(){
		if(_openClose=='close') {
			__doOpen();
		}else { 
			__doClose(); _openClose='close'; $.cookie('_openClose',_openClose);
		}
	});
	var __doClose=function(){
		$(".lmenu").addClass("lmenu_on");
		$("#openClose").addClass("close");
		$("html").addClass("on");
	};
	var __doOpen=function(){
		$("html").removeClass("on");
		$(".lmenu").removeClass("lmenu_on");
		$("#openClose").removeClass("close");
		_openClose='open';
		$.cookie('_openClose',_openClose);
	};
	var _openClose=$.cookie('_openClose');
	if(_openClose=='close')	__doClose(); //默认关闭时执行

	//左侧菜单:事件-2
	$("#lmenu a").click(function(){
		var hash=$(this).attr("href");//.substr(1);
		loadHash(hash);
		return false;
	});
	$("#lmenu h3").click(function(){
		if($(this).data('clicknum')==1) {
			$(this).next('ul').fadeIn(300);
			$(this).children('span').removeClass('on');			
			$(this).data('clicknum', 0);
		} else {
			$(this).next('ul').fadeOut(300);
			$(this).children('span').addClass('on');			
			$(this).data('clicknum', 1);
		}
		return false;
	});

	//顶部菜单:点击-3
	$("ul.nav li").click(function(){
		var menu=$(this).attr('data-menu');
		$("#lmenu .menus").hide();
		$("#menu_"+menu).show();
		$("#menu_"+menu+" h3:first").data('clicknum',0);//第一组菜单点开
		$("#menu_"+menu+" h3:gt(0)").data('clicknum',1);//第二组关闭
		//$("#menu_"+menu+" h3 span").removeClass('on');
		$("#menu_"+menu+" h3:gt(0) span").addClass('on');
		$("#menu_"+menu+" ul:first").show();
		$("#menu_"+menu+" ul:gt(0)").hide();
		$("#menu_"+menu+" li:first a").trigger('click');
		$(this).addClass('on').siblings().removeClass('on');
		__doOpen();
	});
	triggerHash(); //锚点定位
}

//锚点定位：初始化
function triggerHash(){
	var locationHref = window.location.href;
	if(locationHref.indexOf('#')>0) {//点击
		hash=locationHref.substr(locationHref.indexOf('#')+1);
	}else{
		//hash=$("#lmenu li:first a").attr("href").substr(1);
		hash=$("#lmenu li:first a").attr("href");
	}
	$obj=$("#lmenu a[href='"+hash+"']");
	if($obj.length>0){
		//菜单标示.
		$("#lmenu .menus").hide();
		nav_menu=$obj.parents("div.menus").show().attr("id").substr(5);
		//头部标示
		$("ul.nav li[data-menu="+nav_menu+"]").addClass('on').siblings().removeClass('on');
		loadHash(hash);
	}
}

//处理左侧菜单+加载文件
function loadHash(hash) {
	if(hash=='' || hash=='undefined' || hash==undefined) return false;
	window.location.hash=hash;
	$("#lmenu li").removeClass("on");
	$obj=$("#lmenu a[href='"+hash+"']");
	$obj.parent("li").addClass("on");
	
	if($("#workspace").get(0).tagName=='DIV'){ //标签打开
		showTab({id:hash,text:$obj.text(),url:hash}); return false;
	}
	
	$("#loadMask").show();
	$("#workspace").attr('src',hash).load(function(){$("#loadMask").hide();}); 
}

function showTab(node) {
	tabChange(node,1);
}
function tabNoActive(node) {
	tabChange(node,0);
}
function tabChange(node,isActive){
	var tabs = mini.get("mainTabs");
	var id = "tab$" + node.id;
	var tab = tabs.getTab(id);
	if (!tab) {
		tab = {};
		tab.name = id;
		tab.title = node.text;
		tab.showCloseButton = true;
		tab.url = node.url;
		tabs.addTab(tab);
	}else{
		tabs.reloadTab(tab);
	}
	if(isActive){
		tabs.activeTab(tab);
	}
}

//激活当前标签时
function activeTab(e){
	window.location.hash=e.tab.url;
}
//刷新本标签页
function tab_reload(){
	var tabs = mini.get("mainTabs");
	var id=tabs.getActiveIndex();
	var tab = tabs.getTab(id);
	tabs.reloadTab(tab);
}
//修改密码
function moidfy_pwd(){
	mini.open({
		url: '/index/index/modifyPasswd',
		title: "修改管理员密码", width: 250, height:200
	});		
}