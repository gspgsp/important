$(function(){
    String.prototype.trim=function(){
        return this.replace(/(^\s*)|(\s*$)/g, "");
    }
    //按下键盘 Enter
    $('#model').keyup(function(){
        loadsearch();
    });
    var timer;
    //查询公司
    function loadsearch(){
        clearTimeout(timer);
        var keyword = $("#model").val().trim();
        if(keyword != ''){
            timer = setTimeout(function(){
                $.get('/finance/index/getmodel', {keyword:keyword}, function(data){
                    if(data.length){
                        var html = "";
                        $(data).each(function (k, v){
                            html +='<li><a onClick="selectSearch(this)"><span class="t2">'+v.model+'</span></a></li>';
                        })
                        $('.suggest').show();
                        $("#search_list").html(html);
                    }else{
                        $('.suggest').hide();
                        $("#search_list").html('');
            			$("#product_type").val('');
            			$("#product_type").attr('data-value','');
            			$("#factory select").html('');
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
        $("#model").focus();
    });
    $("#model").blur(function(){
        if(r == 0){
            $('.suggest').hide();
            $("#search_list").html('');
        }
    });
    
    $("#factory").change(function(){
	   	 //获取被选中的option标签
	   	 var value = $('#factory option:selected').val();
	   	 $('#factory').attr("value",value);
//	   	 console.log(value);
    });
    
    $("#store").change(function(){
	   	 //获取被选中的option标签
	   	 var value = $('#store option:selected').val();
	   	 $('#store').attr("value",value);
//	   	 console.log(value);
   });

    var btnTry = $(".btn .try"),       
        btnAly = $(".btn .apply"),
        errorMsg = $(".error-msg"),
        errorStr = "";

    btnTry.bind("click",function(){
		//获取当前试算值并跳转试算界面
        var model = $(".tr.grade .val input").val(),//获取牌号
        product_type = $(".tr.breed .val input").val(),//获取品种
        factory = $(".tr.factory .val").attr("value"),//获取厂家
//        price = $(".tr.price .val input").val(),//获取价格
//        amount = $(".tr.amount .val input").val(),//获取数量
        total = $(".tr.sum .val input").val(),//获取金额
        percent = $(".tr.percent .val input").val(),//获取保证金比例
        storage = $(".tr.storage .val").attr("value"),//获取仓库
        day = $(".tr.day .val input").val();//获取预计赎期
        rzbl = $(".tr.rz .val input").val(); //融资比例
        finance_type = $("#finance_type").val(); //金融产品类型
        var obj = new Object();
        obj.model = model;
        obj.product_type=product_type;
        obj.factory = factory;
//        obj.price=price;
//        obj.amount = amount;
        obj.total= total;
        obj.percent=percent;
        obj.storage = storage;
        obj.day = day;
        obj.rzbl=rzbl;
        obj.finance_type = finance_type;
        var errmsg = valid();
        if(errmsg!=""){
            errorMsg.html(errmsg);
			return;
        }
        //验证登陆是否成功
		if(($("#user_id").val()=="")||($("#user_id").val()=="0")){			
            if($("#finance_type").val()=='1'){
//				console.log(1);
				$.ajax({
	                url:'/finance/index/SetSession',
	                data:{data:JSON.stringify(obj)},
	                cache:false, 
	                async:false,
	                type:"POST",
	                dataType:'json',
	                success:function (data){
	            		if(data.err=='0'){
	            			loginbox(-1);
	            		}else{
	            		    layer.msg(data.msg);
	            		}
	                }
	            });
            }
            if($("#finance_type").val()=='2'){
//				console.log(2);
				$.ajax({
	                url:'/finance/index/SetSession',
	                data:{data:JSON.stringify(obj)},
	                cache:false, 
	                async:false,
	                type:"POST",
	                dataType:'json',
	                success:function (data){
	            		if(data.err=='0'){
	            			loginbox(-1);
	            		}else{
	            		    layer.msg(data.msg);
	            		}
	                }
	            });
            }
            if($("#finance_type").val()=='3'){
//				console.log(3);
				$.ajax({
	                url:'/finance/index/SetSession',
	                data:{data:JSON.stringify(obj)},
	                cache:false, 
	                async:false,
	                type:"POST",
	                dataType:'json',
	                success:function (data){
	            		if(data.err=='0'){
	            			loginbox(-1);
	            		}else{
	            		    layer.msg(data.msg);
	            		}
	                }
	            });
            }
			return;
		}
    	$.post('/user/financeCalc/SetSession',{data:JSON.stringify(obj)},function(data){
    		//console.log(data);
    		if(data.err=='0'){
        		location.href = "/user/financeCalc";
    		}else{
    			layer.msg(data.msg);
    		}	
    	},'json');
    });

    btnAly.bind("click",function(){
		//获取当前试算值并跳转试算界面
        var model = $(".tr.grade .val input").val(),//获取牌号
        product_type = $(".tr.breed .val input").val(),//获取品种
        factory = $(".tr.factory .val").attr("value"),//获取厂家
//        price = $(".tr.price .val input").val(),//获取价格
//        amount = $(".tr.amount .val input").val(),//获取数量
        total = $(".tr.sum .val input").val(),//获取金额
        percent = $(".tr.percent .val input").val(),//获取保证金比例
        storage = $(".tr.storage .val").attr("value"),//获取仓库
        day = $(".tr.day .val input").val();//获取预计赎期
        rzbl = $(".tr.rz .val input").val(); //融资比例
        finance_type = $("#finance_type").val(); //金融产品类型
        var obj = new Object();
        obj.model = model;
        obj.product_type=product_type;
        obj.factory = factory;
//        obj.price=price;
//        obj.amount = amount;
        obj.total= total;
        obj.percent=percent;
        obj.storage = storage;
        obj.day = day;
        obj.rzbl=rzbl;
        obj.finance_type = finance_type;
        var arr =[];
        arr.push(obj);
        var errmsg = valid();
        if(errmsg!=""){
            errorMsg.html(errmsg);
			return;
        }
        //验证登陆是否成功
		if(($("#user_id").val()=="")||($("#user_id").val()=="0")){			
            if($("#finance_type").val()=='1'){
//				console.log(1);
				$.ajax({
	                url:'/finance/index/SetSession',
	                data:{data:JSON.stringify(obj)},
	                cache:false, 
	                async:false,
	                type:"POST",
	                dataType:'json',
	                success:function (data){
	            		if(data.err=='0'){
	            			loginbox(-1);
	            		}else{
	            		    layer.msg(data.msg);
	            		}
	                }
	            });
            }
            if($("#finance_type").val()=='2'){
//				console.log(2);
				$.ajax({
	                url:'/finance/index/SetSession',
	                data:{data:JSON.stringify(obj)},
	                cache:false, 
	                async:false,
	                type:"POST",
	                dataType:'json',
	                success:function (data){
	            		if(data.err=='0'){
	            			loginbox(-1);
	            		}else{
	            		    layer.msg(data.msg);
	            		}
	                }
	            });
            }
            if($("#finance_type").val()=='3'){
//				console.log(3);
				$.ajax({
	                url:'/finance/index/SetSession',
	                data:{data:JSON.stringify(obj)},
	                cache:false, 
	                async:false,
	                type:"POST",
	                dataType:'json',
	                success:function (data){
	            		if(data.err=='0'){
	            			loginbox(-1);
	            		}else{
	            		    layer.msg(data.msg);
	            		}
	                }
	            });
            }
			return;
		}
    	$.post('/user/financeCalc/Apply',{data:JSON.stringify(arr)},function(data){
    		//console.log(data);
    		if(data.err=='0'){
        		layer.msg(data.msg,2,1);
    		}else{
    			layer.msg(data.msg);
    		}	
    	},'json');
    });

    var valid = function(){
        var grade = $(".tr.grade .val input"),//获取牌号
            breed = $(".tr.breed .val input"),//获取品种
            factory = $(".tr.factory .val"),//获取厂家
//            price = $(".tr.price .val input"),//获取价格
//            amount = $(".tr.amount .val input"),//获取数量
            total = $(".tr.sum .val input"),//获取金额
            percent = $(".tr.percent .val input"),//获取保证金比例
            storage = $(".tr.storage .val"),//获取仓库
            day = $(".tr.day .val input");//获取预计赎期
            rzbl = $(".tr.rz .val input"); //融资比例
            errorStr="";
            if(grade.val()==""){
                errorStr="您没有填写牌号";
                grade.focus();
                return errorStr;
            }
            if(breed.val()==""){
                errorStr="您没有填写品种";
                return errorStr;
            }
            if(factory.attr("value")=="" || factory.attr("value")==undefined){
                errorStr="您没有选择厂家";
                factory.focus();
                return errorStr;
            }
//            if(price.val()==""){
//                errorStr="您没有填写价格";
//                price.focus();
//                return errorStr;
//            }else{
//            	var checkNum =/^([1-9]\d{0,15}|0)(\.\d{1,2})?$/;
//            	if(!checkNum.test(price.val())){
//            		errorStr="请输入正确的价格格式";
//            		price.focus();
//                    return errorStr;
//            	}
//            }
//            if(amount.val()==""){
//                errorStr="您没有填写数量";
//                amount.focus();
//                return errorStr;
//            }else{
//            	var checkNum =/^([1-9]\d{0,15}|0)(\.\d{1,4})?$/;
//            	if(!checkNum.test(amount.val())){
//            		errorStr="请输入正确的数量格式";
//            		amount.focus();
//                    return errorStr;
//            	}
//            }
		      if(total.val()==""){
		          errorStr="您没有填写金额";
		          total.focus();
		          return errorStr;
		      }else{
		      	var checkNum =/^([1-9]\d{0,15}|0)(\.\d{1,2})?$/;
		      	if(!checkNum.test(total.val())){
		      		errorStr="请输入正确的金额格式";
		      		total.focus();
		              return errorStr;
		      	}
		      }
            if(percent.val()!=undefined){
	            if(percent.val()==""){
	                errorStr="您没有填写保证金比例";
	                percent.focus();
	                return errorStr;
	            }else{
	            	var checkNum =/^(\d|[1-9]\d|\d{0,2}\.\d{1,2}|100)$/;
	            	if(!checkNum.test(percent.val())){
	            		errorStr="请输入正确的保证金比例";
	            		percent.focus();
	                    return errorStr;
	            	}
	            }
            }
            if(rzbl.val()!=undefined){
	            if(rzbl.val()==""){
	                errorStr="您没有填写融资比例";
	                rzbl.focus();
	                return errorStr;
	            }else{
	            	var checkNum =/^(\d|[1-7]\d|[0-7]\d{0,2}\.\d{1,2}|80)$/;
	            	if(!checkNum.test(rzbl.val())){
	            		errorStr="请输入正确的融资比例";
	            		rzbl.focus();
	                    return errorStr;
	            	}
	            }
            }
            if(typeof(storage.attr("value"))!="undefined"){
	            if(storage.attr("value")=="-1" || storage.attr("value")==undefined){
	                errorStr="您没有选择仓库";
	                storage.focus();
	                return errorStr;
	            }
            }
            if(day.val()==""){
                errorStr="您没有填写预计赎期";
                day.focus();
                return errorStr;
            }else{
            	var checkNum =/^[0-9]*$/;
            	if(!checkNum.test(day.val())){
            		errorStr="请输入正确的预计赎期";
            		day.focus();
                    return errorStr;
            	}
            }
            return errorStr;
    }
    
		//牌号模糊匹配
		var gradeInput = $(".grade input"),
		grade;
		//遍历页面加载时的厂家数据
		$(gradeInput).each(function(index,obj){
			//异步获取品种类型和厂家
			var model = $(obj).val();
			grade = $(obj).parent();
			$.post('/finance/index/getfactory',{model:model},function(data){
	//			console.log(data);
				if(data.err=='0'){
					//grade.parent().find(".factory").val(data.product_type.name);
					grade.parents().find(".factory").attr('data-value',data.product_type.value);
		            var html = '<option value="-1">全部</option>';
					$(data.factory).each(function(k,v){
		                html +='<option value="'+v.fid+'">'+v.f_name+'</option>';
					});
					grade.parents().find(".factory select").html(html);
					grade.parents().find(".factory select option[value='"+grade.parents().find(".factory .val").attr("value")+"']").attr("selected",true);
				}else{
	//				$("#returnMsg").text(data.msg);
				}					   
			},'json');
		});
		//根据仓库值来选择
		$storge=$(".tr.storage .val");
		if(typeof($storge.attr("value"))!="undefined"){
			if($storge.attr("value")!=""){
				$(".tr.storage .val select option[value='"+$storge.attr("value")+"']").attr("selected",true);
			}
		}
});
//选中当前牌号
var selectSearch =function(that){
    $('.suggest').hide();
    $("#model").val($(that).find("span").html());
    $("#search_list").html('');
    //异步获取品种类型和厂家
	var model = $("#model").val();
	$.post('/finance/index/getfactory',{model:model},function(data){
//		console.log(data);
		if(data.err=='0'){
			$("#product_type").val(data.product_type.name);
			$("#product_type").attr('data-value',data.product_type.value);
            var html = '<option value="">全部</option>';
			$(data.factory).each(function(k,v){
                html +='<option value="'+v.fid+'">'+v.f_name+'</option>';
			});
			$("#factory select").html(html);
		}else{
			layer.msg(data.msg);
		}					   
	},'json');
}





