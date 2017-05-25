//设置默认,页面加载时恢复默认选项。
function region_default() {
	$("option[value='']").attr('selected','selected');
}

function setregion(name,num,address_id,val) {
	var next=num+1;
	$.ajax({
		type:'POST',
		//设置json格式,接收返回数组。
		dataType:'json',
		url:'/api/region',
		//ajax传递当前选项的value值,也就是当前的region_id。
		data:'region_id='+$('#'+name+'_'+num+'_'+address_id).val(),
		success:function(msg) {
			//如果返回值不为空则执行。
			if (msg!=null) {
				var option_str='';
				//循环书写下一个select中要添加的内容。并添加name标记。
				for (var i=0; i<msg.length; i++) {
					if(msg[i].id==val && val !=""){
						option_str+='<option name="'+name+'_'+next+'"value="'+msg[i].id+'" selected="selected">'+msg[i].name+'</option>';
					}else{
						option_str+='<option name="'+name+'_'+next+'"value="'+msg[i].id+'">'+msg[i].name+'</option>';
					}
					
				}
				//删除下一个select中标记name为next的内容。
				$("option[name='"+name+"_"+next+"']").remove();
				//向下一个select中添加书写好的内容。
				$('#'+name+'_'+next+'_'+address_id).append(option_str);
			}else{
				//如果返回值为空,则移除所有带标记的option,恢复默认选项。
				for (var i=next; i<=4; i++) {
					$("option[name='"+name+"_"+i+"']").remove();   
				}
			}
		}
	})
}