/**
 * Created by zzh on 2016/6/17.
 */
var commonAjax=function (type,url,data) {
    var dtd=$.Deferred();
    $.ajax({
        type:type,
        url:url,
        data:data,
        dataType:'json'
    }).then(function (res) {
        dtd.resolve(res);
    },function (res) {
        dtd.reject(res);
    });
    return dtd.promise();
};

function price(sale){
    if(sale){
        return sale;
    }else{
        return "暂无报价";
    }
}

function getUrlParam(name) {
    var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
    var r = window.location.search.substr(1).match(reg);  //匹配目标参数
    if (r!=null) return unescape(r[2]); return null; //返回参数值
}

function arrow(num) {
    if(num>0){
        return "+";
    }else if(num==0){
        return "";
    }else{
        return "-";
    }
}

function sendMsg() {
    var times=60;
    var username=$("#phone").val();
    $.ajax({
        type: 'POST',
        url: "/touch/sign/sendmsg",
        data: {'mobile':username},
        dataType:'json',
        success:function(res){
            if(res.err!=0){
                layer.open({
                    content: res.msg,
                    time:2
                });
            }else{
                var countStart=setInterval(function(){
                    $(".validCode").text(times--+'秒后重发');
                    $(".validCode").attr("disabled",true);
                    if(times<0){
                        clearInterval(countStart);
                        $(".validCode").text("获取验证码");
                        $(".validCode").attr("disabled",false);
                    }
                },1000);
            }
        },
        error:function () {

        }
    });
}


function statusName(status) {
    if(status==1){
        return '已兑换，待确认';
    }else if(status==2){
        return '已确认，待发货';
    }else if(status==3){
        return '已发货';
    }else if(status==4){
        return '订单取消';
    }else if(status==5){
        return '订单完成';
    }
}

function typeName(type) {
    if(type==1){
        return '签到';
    }else if(type==2){
        return '登录';
    }else if(type==3){
        return '发布报价';
    }else if(type==4){
        return '订单取消积分返还';
    }else if(type==5){
        return '兑换礼品';
    }else if(type==6){
        return '发布采购';
    }else if(type==7){
        return '注册完善信息送';
    }else if(type==8){
        return 'app注册';
    }else if(type==9){
        return '资源库发布';
    }else if(type==10){
        return '资源库搜索';
    }
}

function stampToDate2(date) {
    var newDate=new Date();
    newDate.setTime(date*1000);
    return newDate.toLocaleDateString();
}

function mToH(time) {
    if(time>14400){
        return '4';
    }else{
        return parseInt(time/3600);
    }
}

function priceCompare(price) {
    if(price>0){
        return 'red';
    }else if(price==0){
        return 'orange';
    }else if(price<0){
        return 'green';
    }
}

function oilCompare(price) {
    if(price>0){
        return 'red';
    }else if(price==0){
        return 'blue';
    }else if(price<0){
        return 'green';
    }
}

function shelve(type) {
    if(type==1){
        return 'sold';
    }else if(type==2){
        return 'buy';
    }
}

function shelve2(type) {
    if(type==1){
        return '下架';
    }else if(type==2){
        return '上架 ';
    }
}
