__layout|public:mini_layout|layout__
<div style="width:600px; padding:5px;">
  <div id="infoForm" class="form">
    <table border="0" cellpadding="1" cellspacing="2">
      <tr>
        <td style="width:80px;">手机：</td>
        <td style="width:210px;"><input name="mobile" class="mini-textbox" maxlength="11" value="" style="width:150px;" required="true" onvalidation="onValidMobile"/></td>
        <td>邮件：</td>
        <td><input name="email" class="mini-textbox" style="width:150px;" required="true" vtype="email"/></td>
      </tr>
      <tr>
        <td>登录密码：</td>
        <td><input name="password" class="mini-textbox" value="" style="width:100px;" vtype="rangeLength:6,20" required="true"/></td>
        <td>状态：</td>
        <td><input class="mini-combobox" name="status" style="width:100px;" data='<?php echo setMiniConfig($this->_var['status']); ?>' required="true" textField="name" valueField="id" value="1"/></td>
      </tr>
      <tr>
        <td >姓名：</td>
        <td><input name="real_name" class="mini-textbox" style="width:150px;" required="true" vtype="rangeLength:2,30"/></td>
        <td>身份证：<input class="mini-textbox" name="id_type" style="width:80px;display:none" value="1"/></td>
        <td><input name="id_card" class="mini-textbox" value="<?php echo $this->_var['user']['info']['id_card']; ?>" style="width:150px;" /></td>
      </tr>
		  <tr>
          <td>是否担保人：</td>
          <td><input name="is_security" class="mini-combobox" data='<?php echo setMiniConfig($this->_var['is_security']); ?>' textfield="name" valuefield="id" value="<?php echo $this->_var['user']['info']['is_security']; ?>"/></td>
          <td ></td>
          <td></td>
		  </tr>
    </table>
    <div align="center" style="margin-top:10px;">
      <a class="mini-button" iconCls="icon-ok" onclick="submitForm">确定</a>
      <a class="mini-button" iconCls="icon-cancel" onclick="onCancel">关闭</a>
      <span id="returnMsg" style="padding-left:5px; color:#F00;"></span>
    </div>
  </div>
</div>
<script type="text/javascript">
mini.parse();
var form = new mini.Form("#infoForm");

function submitForm(){
  form.validate();
  if(form.isValid() == false) return;
  
  //提交数据
  var o = form.getData();
  var json = mini.encode(o);
  $("#returnMsg").text('');
  form.loading("数据提交中，请稍后......");
  $.post('/user/user/addSubmit',{data:json},function(data){
	  form.unmask();
	  $("#returnMsg").text(data.msg);
	  if(data.err=='0'){
		  CloseWindow("save");
	  }else{
		  return false;
	  }
  },'json');
}

function CloseWindow(action) {            
	if (window.CloseOwnerWindow) return window.CloseOwnerWindow(action);
	else window.close();            
}
function onCancel(e) {
	CloseWindow("cancel");
}

function onValidMobile(e){
   if (e.isValid) {
	   if(!utils.isMobile(e.value)){
		   e.errorText = "错误的手机号";
		    e.isValid = false;
	   }
   }
}

</script>
