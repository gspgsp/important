$(function(){
	String.prototype.trim=function(){
		return this.replace(/(^\s*)|(\s*$)/g, "");
	}
	$('#keyword').keyup(function(){
		loadsearch();
	});
	var timer;
	function loadsearch(){
		clearTimeout(timer);
		var keyword = $("#keyword").val().trim();
		if(keyword != ''){
			timer = setTimeout(function(){
				$.get('/physical/index/get_search_list', {keyword:keyword}, function(data){
					if(data.length){
						var html = "";
						$(data).each(function (k, v){
							var url = v.type +' '+ v.name +' '+ v.company;
							html +='<a target="_blank" onClick="selectSearch()" href="/physical/index/search?keyword='+url+' "><li><span class="t1">'+v.type+' / '+v.name+'</span><span class="t2">'+v.company+'</span><span class="t3"></span></li></a>';
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
		$("#keyword").focus();
	});
	$("#keyword").blur(function(){
		if(r == 0){
			$('.suggest').hide();
			$("#search_list").html('');
		}
	});
});
function selectSearch(){
	$('.suggest').hide();
	$("#search_list").html('');
}
