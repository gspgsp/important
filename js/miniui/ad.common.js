//Mini验证金额：为正值
function onValidMoney(e){
   if (e.isValid) {
	    var money=e.sender;
        money.setValue(e.value.replace(/[^\d.]/g,''));
		e.isValid = true;
   }
}
//Mini验证金额：为正为负
function onValidFullMoney(e){
   if (e.isValid) {
	    var money=e.sender;
        money.setValue(e.value.replace(/[^\d.-]/g,''));
		e.isValid = true;
   }
}

function search() {
	grid.load($("#soform").serializeObject(),function(e){
		$("#searchMsg").html(e.msg);
	});
}
function onKeyEnter(e) {
	search();
}

//表单提交
function frmSubmit(formID){
  if(typeof(formID) != 'string') formID="theForm";
  var form = new mini.Form("#"+formID),$form=$("#"+formID);
 
  form.validate();
  if(form.isValid() == false) return;
  
  //提交数据
  form.loading("数据提交中，请稍后......");
  var callback=function(data){
	  if($("#returnMsg").length>0){
	  	 form.unmask();
	 	 $("#returnMsg").text(data.msg);
	  	 setTimeout(function (){$("#returnMsg").html('')},800);
	  }else{
		  form.loading(data.msg);
	  	  setTimeout(function (){form.unmask();},800);
	  }
  }
  utils.ajax($form.attr('action'),$form.serialize(),callback,"POST","json");
}
//表单重置
function frmRest(formID){
  if(typeof(formID) != 'string') formID="theForm";
  var form = new mini.Form("#"+formID),$form=$("#"+formID);
  form.reset();
  //$form.find('[type=reset]').trigger('click');
}

//选择地区
function loadRegion($obj,$next){
	$obj.nextAll('select.region').find('option:gt(0)').remove();
	var pid=$obj.val();
	if(pid=='') return;
	var callback=function(data){
		if(data.regions){
			for(i=0;i<data.regions.length;i++){
				var _option=data.regions[i];
				$next.append('<option value="'+_option.id+'">'+_option.name+'</option>');	
			}
			
		}
	}
	utils.ajax('/public/api/region',{pid:pid},callback,"POST","json");
}