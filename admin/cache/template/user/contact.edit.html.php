__layout|public:mini_layout|layout__
<div style="padding:5px;">
<form id="infoForm" class="form">

	<input class="mini-hidden" name="ctype" value="1"/>
	<input class="mini-hidden" name="user_id" value="<?php echo $this->_var['info']['user_id']; ?>"/>
	<table border="0" cellpadding="1" cellspacing="2">
	  <tr>
		  <td>归属公司：</td>
		  <td>
			<input name="c_id" class="mini-buttonedit" onbuttonclick="usrChoose" value="<?php echo $this->_var['info']['c_id']; ?>" allowInput="false"  text="<?php echo $this->_var['c_name']; ?>"  style="width:170px" id="usrId"/>
		  </td>
		  <td ></td>
		  <td></td>
	  </tr>
	  <tr>
		<td >姓名：</td>
		<td><input name="name" class="mini-textbox" style="width:150px;" required="true" vtype="rangeLength:2,30" value="<?php echo $this->_var['info']['name']; ?>"/></td>
		<td>性别：</td>
		<td><input class="mini-combobox" name="sex" style="width:100px;" data='<?php echo setMiniConfig($this->_var['sex']); ?>' required="true" textField="name" valueField="id" value="<?php echo $this->_var['info']['sex']; ?>"/></td>
	  </tr>
	  <tr>
		<td style="width:80px;">手机：</td>
		<td style="width:210px;"><input name="mobile" class="mini-textbox" maxlength="11"  style="width:150px;" required="true" onvalidation="onValidMobile" value="<?php echo $this->_var['info']['mobile']; ?>"/></td>
		<td>电话：</td>
		<td><input name="tel" class="mini-textbox" style="width:150px;" vtype="rangeLength:6,14" value="<?php echo $this->_var['info']['tel']; ?>"/></td>
	  </tr>
	  <tr>
		<td style="width:80px;">QQ号：</td>
		<td style="width:210px;"><input name="qq" class="mini-textbox" maxlength="12"  style="width:150px;" value="<?php echo $this->_var['info']['qq']; ?>"/></td>
		<td>传真：</td>
		<td><input name="fax" class="mini-textbox" style="width:150px;" vtype="rangeLength:6,14" value="<?php echo $this->_var['info']['fax']; ?>"/></td>
	  </tr>
	  <tr>
		<td>邮件：</td>
		<td><input name="email" class="mini-textbox" style="width:150px;"   value="<?php echo $this->_var['info']['email']; ?>"/></td>
		<td></td>
		<td></td>
	  </tr>
	  <tr>
		<td>备注：</td>
		<td colspan="3">
		  <input name="remark" class="mini-textarea" style="width:403px; height:50px;" value="<?php echo $this->_var['info']['remark']; ?>"/>
		</td>
	  </tr>
	  <tr>
		<td>状态：</td>
		<td><input class="mini-combobox" name="status" style="width:100px;" data='<?php echo setMiniConfig($this->_var['status']); ?>' required="true" textField="name" valueField="id" value="<?php echo $this->_var['info']['status']; ?>"/></td>
	  </tr>
	</table>
	<div align="center" style="margin-top:10px;">
	  <a class="mini-button" iconCls="icon-ok" onclick="submitForm">确定</a>
	  <a class="mini-button" iconCls="icon-cancel" onclick="onCancel">关闭</a>
	  <span id="returnMsg" style="padding-left:5px; color:#F00;"></span>
	</div>
	</form>
  </div>

<script type="text/javascript">
mini.parse();
// var cid = mini.get("#usrId").getValue();
// $("#usrId").setValue(cid);
var form = new mini.Form("#infoForm");

function submitForm(){
  form.validate();
  if(form.isValid() == false) return;
  
  //提交数据
  var o = form.getData();
  var json = mini.encode(o);
  $("#returnMsg").text('');
  form.loading("数据提交中，请稍后......");
  $.post('/user/customer/addSubmit',{data:json},function(data){
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

//强制选择归属公司
function usrChoose(e){
	var btn = this;
		mini.open({
		url: "/user/customer/init?do=search",
		title: "选择公司",
		width: 1200,
		height: 550,
		onload: function () {
			var data=e.sender.getValue();
			top['win'].setDvalue(data);  //去调用子页面的方法。
		},
		ondestroy: function (action) {
			if (action == "ok") {
				var iframe = this.getIFrameEl();
				var data = iframe.contentWindow.GetData();
				data = mini.clone(data);    //必须
				if (data) {
					btn.setValue(data.c_id);
					btn.setText(data.c_name);
				}
			}
		}
	});         
}

</script>
