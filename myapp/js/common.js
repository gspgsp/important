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

function stampToDate2(date) {
    var newDate=new Date();
    newDate.setTime(date*1000);
    return newDate.toLocaleDateString();
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
