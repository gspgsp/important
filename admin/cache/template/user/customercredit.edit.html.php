__layout|public:mini_layout|layout__
<div style="padding:5px;">
	  <div title="基本信息" class="form" id="editForm">
		<form method="post"  id="setform"  onSubmit="return myCheck()" name='form1'>
		<table width="100%" border="0" cellpadding="1" cellspacing="2">
			<tr>
				<td>客户名称：</td>
				<td><input name="catname" class="mini-textbox" style="width:150px" required="true"/ value="<?php echo $this->_var['c_name']; ?>" >&nbsp;
				总分：<input name="credit_sum" class="mini-textbox" id="sum2" value="" allowInput="false" style="width:40px;"/>
				</td>
				<input type="hidden" name='customer_id' value="<?php echo $this->_var['cuslist']['customer_id']; ?>"/>

				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
				
				<?php $_from = $this->_var['catlist']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->_push_vars('key1', 'value1');if (count($_from)):
    foreach ($_from AS $this->_var['key1'] => $this->_var['value1']):
?>
                <tr>
				<td>分类<?php echo $this->_var['key1']; ?>：<?php echo $this->_var['value1']['catname']; ?></td>
                </tr>
                <?php $_from = $this->_var['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->_push_vars('', 'value2');if (count($_from)):
    foreach ($_from AS $this->_var['value2']):
?>
                <?php if ($this->_var['value1']['id'] == $this->_var['value2']['cat_id']): ?>
               		
                <tr>
                    <td></td>
                    <td>
                        <?php echo $this->_var['value2']['lab_name']; ?>
					<?php $_from = $this->_var['cuslist']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->_push_vars('key3', 'value3');if (count($_from)):
    foreach ($_from AS $this->_var['key3'] => $this->_var['value3']):
?>
                       <?php if ($this->_var['key3'] == $this->_var['value2']['lab_id2']): ?>
                        <input type="number" name="field<?php echo $this->_var['value2']['lab_id']; ?>" min="0" max="<?php echo $this->_var['value2']['lab_grade']; ?>" step="0.5"  value="<?php echo $this->_var['value3']; ?>"  onfocus=this.select() style="width:80px" required="true"  onblur="inputSum(this.value)">【<?php echo $this->_var['value2']['lab_grade']; ?>分】&nbsp; 说明：<?php echo $this->_var['value2']['lab_desc']; ?>
					<?php endif; ?>
                     <?php endforeach; endif; unset($_from); ?><?php $this->_pop_vars();; ?>     
                        </td>
                </tr>
               
                  <?php endif; ?> 

          <?php endforeach; endif; unset($_from); ?><?php $this->_pop_vars();; ?>
          <?php endforeach; endif; unset($_from); ?><?php $this->_pop_vars();; ?>
          
			<tr>
				<td>备注:</td>
				<td><input name="remark" class="mini-textarea" style="width:200px" required="true"/ value="<?php echo $this->_var['remark']; ?>"></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			</table>
			</form>
			</div>
			<div align="center" style="margin-top:10px;">
				<a class="mini-button" iconcls="icon-ok" onclick="submitForm">确定</a>
				<a class="mini-button" iconcls="icon-cancel" onclick="onCancel">关闭</a><span id="returnMsg" style="padding-left:5px; color:#F00;"></span>
			</div>
</div>
<script type="text/javascript">
mini.parse();
function submitForm(){
	for(var i=0;i<document.form1.elements.length-1;i++)  
               {  
                  if(document.form1.elements[i].value=="")  
                  {  
                  	 alert("当前表单不能有空项");  
                  		//  grid.unmask();
						 // alert(data.msg);
						  //return false;
                    
             //一进入页面将光标定位到第一个input  
                     //document.form1.elements[i].focus();  
                     //return false;  
                     die;
                  }  
               }  
               //return true;  
	//var params = $("#setform").serialize();
	//var data = $('#setform').serializeArray();
            //console.log(data);
	//console.log(params);
	
//var data = $('#setform').serializeArray();
var admInfo = mini.get("editForm");

var data=JSON.stringify( $('#setform').serializeObject()  )  ;
 var jsonObj = JSON.parse(data);  
           // console.log(typeof(data));
           // console.log(data);
           // console.log(jsonObj);
           // console.log(typeof(jsonObj));
	//var ob= strToObj(data);
	//console.log(ob);  
   // alert(ob);//2012-02-01  
		$.ajax({  //json对象
		url:'/user/customercredit/ajaxSave?action=edit',
		data:jsonObj,
		type:'post',
		dataType:'json',
		success:function(){
	  				//admInfo.hide();
	  				CloseWindow('save');
	  			},  
		error:function(e){alert(e)}  
		});
}

function CloseWindow(action) {
  if (window.CloseOwnerWindow) return window.CloseOwnerWindow(action);
  else window.close();
}

function inputSum(e){
	var sum=0;
	var test = document.getElementById('setform');
    var inputs = test.getElementsByTagName('input');
    var sum = 0;
    console.log(inputs);
    console.log(inputs.length);
    for(var i = 5; i < inputs.length; i++) {
    	console.log(inputs[i].value);
    	console.log('取整后的值');
    	
    	var ab=inputs[i].value;
    	ab=parseFloat(ab);

    	// if(!isNaN(ab)){
    	// 	console.log(ab);
    	// }
    	//console.log(ab);

    	if(ab==null||ab==''||isNaN(ab)){
    		continue;
    	}else{
    		sum +=ab;
        	console.log('sum:');
    		console.log(sum);
    		console.log(ab);
    	}
        
    }
    
	//$("#spanSum")[0].innerHTML=sum+'分';
	//$("#sum2").val(sum);
	 var t = mini.get("sum2");
	 		//t.setValue();
            t.setValue(sum);
}

inputSum();

function CloseWindow(action) {
  if (window.CloseOwnerWindow) return window.CloseOwnerWindow(action);
  else window.close();
}
function onCancel(e) {
  CloseWindow("cancel");
}
//强制选择归属公司
function usrChoose(e){
	var btn = this;
		mini.open({
		url: "product/factory/init?do=search",
		title: "选择公司",
		width: 1200,
		height: 550,
		onload: function () {
			var iframe = this.getIFrameEl();
			iframe.contentWindow.SetData();
		},
		ondestroy: function (action) {
			if (action == "ok") {
				var iframe = this.getIFrameEl();
				var data = iframe.contentWindow.GetData();
				data = mini.clone(data);    //必须
				if (data) {
					btn.setValue(data.fid);
					btn.setText(data.f_name);
					$("#"+btn.id+"\\$value").val(data.fid);
				}
			}
		}
	});
}
</script>