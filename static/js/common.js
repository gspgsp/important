String.prototype.trim = function () {
    return this.replace(/^(\s|\xA0)+|(\s|\xA0)+$/g, '');
}

String.prototype.format = function (args) {
    if (arguments.length == 0) return null;
    var args = Array.prototype.slice.call(arguments, 0);
    return this.replace(/\{(\d+)\}/g, function (m, i) {
        return args[i];
    });
};

String.prototype.safely = function () {
    return this.replace(/\n\r/g, '');
}

window.utool = window.utool || {};
window.ssrong = window.ssrong || {};

//金额转换类
window.utool.money = window.utool.money || {};
//将金额以逗号隔开，补足2位小数
utool.money.commaFormat = function(num) { //1000=>1,000.00
	if (num === 0 || num === "0") return "0.00";
	if (!num || isNaN(num))  return "";
	num += '';
	var x = num.split('.');
	var x1 = x[0];
	var x2 = x.length > 1 ? '.' + x[1] : '.00';
	if (x2.length < 3)   x2 += "00";
	x2 = x2.substring(0, 3);
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
}
utool.money.intFormat = function(num) { //1000=>1,000
	if (num === 0 || num === "0") return "0";
	if (!num || isNaN(num))return "";
	num += '';
	var x1 = num;
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1;
}
//金额前追击人民币符号
utool.money.yuanForamt = function(num) { //1000=>￥1,000.00
	var num = utool.money.commaFormat(num);
	if (num == '') {
		return num;
	}
	return "￥" + num;
}
//金额转中文
utool.money.toChinese =function (amount) {
	//转为字符
	amount = amount + "";
	if (amount == "") return "金额为空";
	if (amount.match(/[^,.\d]/) != null) {
		return "存在不合法字符";
	}
	if ((amount).match(/^((\d{1,3}(,\d{3})*(.\d+)?)|(\d+(.\d+)?))$/) == null) {
		return "不正确的金额格式";
	}

	amount = amount.replace(/,/g, ""); //除去逗号分隔符
	amount = amount.replace(/^0+/, ""); //除去头部零
	if (Number(amount) > 99999999999.99) {
		return "金额溢出";
	}
	amount = parseFloat(amount) + "";

	var p_int,p_dot,output; //整数部分+小数部分,输出结果
	var arr = amount.split(".");
	if (arr.length > 1) {
		p_int = arr[0];
		p_dot = (arr[1]).substr(0,2);
	} else {
		p_int = arr[0];
		p_dot = "";
	}

	//中文数字
	var cn_shu = new Array("零", "壹", "贰", "叁", "肆", "伍", "陆", "柒", "捌", "玖");
	var cn_bai = new Array("", "拾", "佰", "仟");
	var cn_wan = new Array("", "万", "亿");
	var cn_fen = new Array("角", "分");
	var output = "";
	if (Number(p_int) > 0) { //整数部分
		zero = 0;
		for (var i = 0; i < p_int.length; i++) {
			var p = p_int.length - i - 1;
			var d = p_int.substr(i, 1);
			var z = p / 4;
			var m = p % 4;
			if (d == "0") {
				zero++;
			} else {
				if (zero > 0) {
					output += cn_shu[0];
				}
				zero = 0;
				output += cn_shu[Number(d)] + cn_bai[m];
			}
			if (m == 0 && zero < 4) {
				output += cn_wan[z];
			}
		}
		output += "元";
	}

	//小数部分
	if (p_dot != "") {
		for (i = 0; i < p_dot.length; i++) {
			d = p_dot.substr(i, 1);
			if (d != "0") {
				output += cn_shu[Number(d)] + cn_fen[i];
			}
		}
	}

	if (output == "") {
		output = "零" + "元";
	}
	if (p_dot == "") {
		output += "整";
	}
	return output;
}
//转化为万
utool.money.toWan = function(num){  //100000=>10w
	if (num === 0 || num === "0")  return "0万";
	if (!num || isNaN(num)) return "";
	
	num = num / 10000;
	num += '';
	var x = num.split('.')[0];
	var y = num.split('.')[1];
	if (y) {
		y = y.substring(0, 2);
		return x + '.' + y + '万';
	} else {
		return x + '万';
	}
}
//将小数转化为百分数，补足2位小数
utool.money.percentformat = function(num) { //0.05=>5.00%
	if (num === 0 || num === "0") {
		return "0.00%"
	}
	if (!num || isNaN(num)) {
		return "";
	}
	num = parseFloat(num);
	num = Math.round(num * 10000) / 100 + "";
	if (num.indexOf(".") == -1) {
		num += ".00";
	} else {
		num += "00"
	}
	var numArr = num.split(".");
	var floatNumber = numArr[1].substring(0, 2);
	return numArr[0] + "." + floatNumber + "%";
}
//小数比例转百分数
utool.money.ratioFormat = function (num) {
	if (num === 0 || num === "0") return "0%"
	if (num > 1 || num > "1") return "100%"
	if (!num || isNaN(num)) return "";
	num = parseFloat(num);
	num = (num * 10000) / 100 + "";
	if (num.indexOf(".") === -1) return  num + "%";
	var numArr = num.split("."), secN = numArr[1].substring(1, 2), fstN = numArr[1].substring(0, 1), floatNumber = numArr[1].substring(0, 2);
	if (secN === "0") {
		return numArr[0] + "." + fstN + "%";
	} else {
		return numArr[0] + "." + floatNumber + "%";

	}
}
//逗号的金额转成数字
utool.money.toNumber = function (amount) {
	return amount.replace(/,/g, '').split('.')[0].trim();
}

window.ssrong.layer = window.ssrong.layer || {};
ssrong.layer={
	init:function(){ //初始化
		$(".pop_layer .close,.popup_layer .close").live('click',function(){
			ssrong.layer.maskClose();								  
		});
	},
	maskOpen:function(){
		if($(".mask").length>0){
			$(".mask").show();	
		}else{
			$("body").prepend('<div class="mask"></div>');
		}
	},
	maskClose:function(){
		$(".mask").remove();	
		$(".pop_layer,.popup_layer").hide();	
	}
}
ssrong.layer.init();

window.ssrong.pop = {
	options:{
		mask:true,
		title:"提示信息",
		type:"notice",//1=suc 2=fail 3=notice
		content:"",
		closeable:true,
		button_text:"确 定",
		button_attr:"",
		button_link:"javascript:;",
		button2_text:"",
		button2_link:"javascript:;"
	},
	show:function(opts){
		opts = $.extend({},this.options,opts);
		if(opts.mask) ssrong.layer.maskOpen();
		if($("#pop_msg").size()) $("#pop_msg").remove();
		$('<div class="pop_layer" id="pop_msg"><div class="tit"><span>'+opts.title+'</span>'+(opts.closeable?'<div class="close"><a href="javascript:;"></a></div>':'')
			+'</div><div class="pop_info"><dl>'+(opts.type?'<dt class="icon_status status_'+opts.type+'"></dt>':'')+'<dd class="topB"><span>'
			+opts.content+'</span><div style="margin-top:10px;"><a class="btns close" '+opts.button_attr+' href="'+opts.button_link+'">'+opts.button_text+'</a>'
			+(opts.button2_text?'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="btns_gray close" href="'+opts.button2_link+'">'+opts.button2_text+'</a>':'')
			+'</div></dd></dl></div></div>').appendTo($('body'));
		$("#pop_msg").css("margin-top",function(){
			return -$(this).height()/2 - 50;
		}).css("margin-left",function(){
			return -$(this).width()/2;
		});
	}
};

//发送的按钮/发送类型/手机号或对象/返回函数
ssrong.smsSend = function ($obj,type,mobile,callback){
	$mobile= mobile==undefined ? '' : mobile;
	callback = callback==undefined ? '' : callback;
	
	var passTime=0,passStatus=0;
	$obj.live('click',function(){
		var $this=$obj;
		var passTimeOut=function(){
			if(passTime==0){
				$this.removeClass('btn3').addClass("btn2").prop("disabled",false).html('重新发送');
			}else{
				passTime--;
				$obj.removeClass('btn2').addClass('btn3');
				$this.html(passTime+'秒后重发短信');
			}
		}
		if(passTime>0) return false;
		var mobile=typeof($mobile)=='object' ? $mobile.val() : $mobile;
		$.post('/api/sms',{type:type,mobile:mobile},function(data){
			if(data.err=='0'){
				passTime=120; //默认60秒	
				$obj.removeClass('btn2').addClass('btn3');
				$(this).html(passTime+'秒后重发短信').prop('disabled',true);
				setInterval(function(){
					passTimeOut();				
				},1000)
			}else{
				if(typeof(callback)=='function'){
					callback(data.msg);	
				}else{
					alert(data.msg);
				}
			}					   
		},'json');
	});
}