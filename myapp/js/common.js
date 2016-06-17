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