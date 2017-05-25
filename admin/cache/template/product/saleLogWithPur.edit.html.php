__layout|public:mini_layout|layout__
<div style="padding:5px;">
	  <div title="基本信息" class="form" id="editForm">
		<input class="mini-hidden" name="id" value="<?php echo $this->_var['data']['id']; ?>"/>
		<table width="100%" border="0" cellpadding="1" cellspacing="2">
			<input class="mini-hidden" id="hidden_model" name="model" value=""/>
			<tr>
				<td>牌号<font style="color:red;"> * </font></td>

				<td>
					<input name="p_id" class="mini-buttonedit" onbuttonclick="proChoose" value="<?php echo $this->_var['info']['p_id']; ?>" allowInput="false" required="true" text="<?php echo $this->_var['model']; ?>"  style="width:100px" id="usrId"/>
				</td>
			</tr>
			<tr>
				<td>厂家<font style="color:red;"></td>
				<td id="f_name"></td>
				<input class="mini-hidden" name="f_name" id="ff_name" value=""/>
			</tr>
			<tr>
				<td>数量<font style="color:red;"> * </font></td>
				<td><input name="require_number" class="mini-textbox" style="width:100px" required="true" value=""></td>
			</tr>
			<tr>
				<td>售价<font style="color:red;"> * </font></td>
				<td><input name="unit_price" class="mini-textbox" style="width:100px" required="true" value=""></td>
			</tr>
			<!-- <tr>
				<td>备注:</td>
				<td><input name="remark" class="mini-textarea" style="width:100px"  value="<?php echo $this->_var['data']['remark']; ?>"
			</tr> -->
			<tr id ='history' style="display:none;">
				<td>历史成交记录:</td>
				<td>
					<a href="javascript:proHistory()" style="color:red;">点击查看</a>
					<input class="mini-hidden" name="history_price" id="history_price" value=""/>
					<input class="mini-hidden" id="product_id" value="0"/>
				</td>
			</tr>
			<tr id ='s_price' style="display:none;">
				<td>最近成交价:</td>
				<td><font style="color:red;" id="neighbor_price">0</font>&nbsp;&nbsp;元</td>
			</tr>
			</table>
			</div>
		<?php if ($this->_var['choose'] == 1): ?>
			<div align="center" style="margin-top:10px;margin-bottom:10px;">
				<a class="mini-button" style="width:60px;" onClick="onComfirm()">确定</a>
			<span style="display:inline-block;width:25px;"></span>
				<a class="mini-button" style="width:60px;" onClick="onCancel()">取消</a>
			</div>
		<?php else: ?>
			<div align="center" style="margin-top:10px;margin-bottom:10px;">
				<a class="mini-button" iconcls="icon-ok" onclick="submitForm">提交</a>
				<a class="mini-button" iconcls="icon-cancel" onclick="onCancel">关闭</a><span id="returnMsg" style="padding-left:5px; color:#F00;"></span>
			</div>
		<?php endif; ?>
</div>
<script type="text/javascript">
mini.parse();
//增加后提交数据(保存)
function submitForm() {
	var form = new mini.Form("#editForm");
	var o = form.getData();
	var json = mini.encode(o);
	var callback=function(data){
	if(data.err!='0'){
	alert(data.msg);
	return false;
	}else{
	CloseWindow('save');
	}
	}
  utils.ajax('/product/product/ajaxSave?action=edit',{data:json},callback,"POST","json");
}
//展示10条历史成交价
function proHistory(){
	var product_id = $('#product_id').val();
	console.log(product_id);
		mini.open({
		url: " /order/business/init?p_id="+product_id,
		title: "产品历史成交价",
		width: 1250,
		height: 550,
	});
}
//强制选择归属产品
function proChoose(e){
	var btn = this;
	var model = mini.get('hidden_model');
		mini.open({
		url: "/product/product/init?ischecked=checked",
		title: "选择产品",
		width: 1250,
		height: 550,
		onload: function () {
			var data=e.sender.getValue();
			top['win'].setDvalue(data);  //去调用子页面的方法。
		},
		ondestroy: function (action) {
			if (action == "ok") {
				var iframe = this.getIFrameEl();
				var data = iframe.contentWindow.GetData();
				data = mini.clone(data);//必须
				if (data) {
					btn.setValue(data.id);
					btn.setText(data.model);
					model.setValue(data.model);
					$('#f_name').html(data.f_name);
					var ff_name = mini.get('ff_name');
					ff_name.setValue(data.f_name);
					mini.get('product_id').setValue(data.id);
					mini.get('history_price').setValue(data.price_s);
					$('#neighbor_price').html(data.price_s);
					$('#history').show();
					$('#s_price').show();

				}
			}
		}
	});         
}
function onCancel(e) {
  CloseWindow("cancel");
}
function CloseWindow(action) {
  if (window.CloseOwnerWindow) return window.CloseOwnerWindow(action);
  else window.close();
}
//确定并获取数据
function onComfirm() {
	var form = new mini.Form("#editForm");
	form.validate();
	if(form.isValid() == false) return;
	//提交数据
	CloseWindow("ok");
}
function GetData() {
	var form = new mini.Form("#editForm");
	//提交数据
	var o = form.getData();
	var json = mini.encode(o);
	$("#returnMsg").text('');
	return json;
}
</script>