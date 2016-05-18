$(function(){
    $(".req").focus(function(){
        $(this).removeClass('requires');
    })

    var applyChn = $(".sly-chn-lt"),
        applyChnBtn = $(".sly-chn-lt b");
    applyChn.bind("click",function(){
        if(applyChnBtn.hasClass("yes")) applyChnBtn.removeClass("yes").addClass("no");
        else  applyChnBtn.removeClass("no").addClass("yes");
    });
})
//表达提交
function submitFrom(){
    var data=$("#talkForm").serialize();
    var flag=true;

    $("#talkForm .req").each(function(k,v){
        if($(this).val()==''){
            $(this).addClass('requires');
            flag=false;
        }
    });
    if(!flag) return;
    $.post('/purchase/talk/addorder',data,function(data){
        if(data.err==0){
            alert(data.msg);
        }else{
            alert(data.msg);
        }
    },'json');
}