$(function(){
    String.prototype.trim=function(){
        return this.replace(/(^\s*)|(\s*$)/g, "");
    }
    //按下键盘 Enter
    $('#company').keyup(function(){
        loadsearch();
    });
    var timer;
    //查询公司
    function loadsearch(){
        clearTimeout(timer);
        var keyword = $("#company").val().trim();
        if(keyword != ''){
            timer = setTimeout(function(){
                $.get('/user/register/getCompany', {keyword:keyword}, function(data){
                    if(data.length){
                        var html = "";
                        $(data).each(function (k, v){
                            html +='<li><a onClick="selectSearch(this)"><span class="t2">'+v.c_name+'</span></a></li>';
                        })
                        $('.suggest').show();
                        $("#search_list").html(html);
                    }else{
                        $('.suggest').hide();
                        $("#search_list").html('');
                    }
                }, 'json')
            }, 300)
        }else{
            $('.suggest').hide();
            $("#search_list").html('');
        }
    }
    var r = 0;
    $("#search_list").mouseenter(function(){
        r = 1;
    });
    $("#search_list").mouseleave(function(){
        r = 0;
        $("#company").focus();
    });
    $("#company").blur(function(){
        if(r == 0){
            $('.suggest').hide();
            $("#search_list").html('');
        }
    });
});
//选中公司
function selectSearch(that){

    $('.suggest').hide();
    $("#company").val($(that).find("span").html());
    $("#search_list").html('');
}



