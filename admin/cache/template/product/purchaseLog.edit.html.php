__layout|public:mini_layout|layout__
<div style="padding:5px;">
	<div id="infoForm" class="form">
	<input class="mini-hidden" name="id" value="<?php echo $this->_var['info']['id']; ?>"/>
	<input class="mini-hidden" name="order_sn" value="<?php echo $this->_var['order_sn']; ?>"/>
	<?php if ($this->_var['choose'] != 1): ?>
	<input class="mini-hidden" name="o_id" value="<?php echo $this->_var['o_id']; ?>"/>		
	<?php endif; ?>
	<?php if ($this->_var['sale_id'] != 0): ?>
			<fieldset style="border:solid 1px #aaa;margin-top:8px;position:relative;">
			<legend>采购订单</legend>
				<table width="100%">
					<tr>
						<td>订单编号 : </td>
						<td><?php echo $this->_var['order_sn']; ?></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>订单名称：</td>
						<td><input name="order_name" class="mini-textbox" style="width:125px;" maxlength="8"  required="true" value="<?php echo $this->_var['info']['order_name']; ?>"/>
						</td>
						<td>客户名称：</td>
						<td>
							<input name="c_id" class="mini-buttonedit" onbuttonclick="usrChoose" value="<?php echo $this->_var['info']['c_id']; ?>" allowInput="false"  text="<?php echo $this->_var['c_name']; ?>"  style="width:125px;" id="usrId"/>
						</td>
						
					</tr>
					<tr>
						<td>签订日期：</td>
						<td><input  class="mini-datepicker"  name="sign_time"  required="true" value="<?php echo $this->_var['info']['sign_time']; ?>"/>
						</td>
						<td>签订地点 : </td>
						<td><input name="sign_place" class="mini-textbox" style="width:125px;" maxlength="8"  required="true" value="<?php echo $this->_var['info']['sign_place']; ?>"/>
						</td>
						
					</tr>
					<tr>
						<td>财务记录 ：</td>
						<td><input name="financial_records" class="mini-combobox" style="width:125px;" value="<?php echo $this->_var['info']['financial_records']; ?>" data='<?php echo setMiniConfig($this->_var['financial_records']); ?>'  required="true" textfield="name" valuefield="id"/></td>
						<td>运输方式 : </td>
						<td><input name="transport_type" class="mini-combobox" style="width:125px;" value="<?php echo $this->_var['info']['transport_type']; ?>" data='<?php echo setMiniConfig($this->_var['transport_type']); ?>'  required="true" textfield="name" valuefield="id"/></td>
						
					</tr>
					<tr>
						<td>运费 : </td>
						<td><input name="freight_price" class="mini-textbox" style="width:125px;" maxlength="8"  required="true" value="<?php echo $this->_var['info']['freight_price']; ?>"/>
						</td>
						<td>提货日期：</td>
						<td><input  class="mini-datepicker"  name="pickup_time"  required="true" value="<?php echo $this->_var['info']['pickup_time']; ?>"/>
						</td>
					</tr>	
					<tr>
						<td>送货日期：</td>
						<td><input  class="mini-datepicker"  name="delivery_time"  required="true" value="<?php echo $this->_var['info']['delivery_time']; ?>"/>
						</td>
						<td>提货地点：</td>
						<td><input name="pickup_location" class="mini-textbox" style="width:125px;"  required="true" value="<?php echo $this->_var['info']['pickup_location']; ?>"/></td>
						
					</tr>
					<tr>
						<td>送货地点：</td>
						<td><input name="delivery_location" class="mini-textbox" style="width:125px;"  required="true" value="<?php echo $this->_var['info']['delivery_location']; ?>"/></td>
						<td>付款方式 : </td>
						<td><input name="pay_method" class="mini-combobox" style="width:125px;" value="<?php echo $this->_var['info']['pay_method']; ?>" data='<?php echo setMiniConfig($this->_var['pay_method']); ?>'  required="true" textfield="name" valuefield="id"/></td>
						
					</tr>
					<tr>
						<td>付款日期：</td>
						<td>
						<input  class="mini-datepicker"  name="payment_time"  required="true" value="<?php echo $this->_var['info']['payment_time']; ?>"/>
						</td>
						<td>业务模式 : </td>
						<td><input name="business_model" class="mini-combobox" style="width:125px;" value="<?php echo $this->_var['info']['business_model']; ?>" data='<?php echo setMiniConfig($this->_var['business_model']); ?>'  required="true" textfield="name" valuefield="id"/></td>
					</tr>
				</table>
			</fieldset>
		<?php endif; ?>
		<fieldset style="border:solid 1px #aaa;margin-top:8px;position:relative;">
			<legend>采购明细</legend>
				<table width="100%">
					<?php if (( $this->_var['choose'] != 1 ) && ( $this->_var['sale_id'] == 0 )): ?>
					<tr>
						<td>订单名称：</td>
						<td>
						<?php if ($this->_var['o_id'] > 0): ?>
							<?php echo $this->_var['order_name']; ?>
						<?php else: ?>
							<input name="o_id" class="mini-buttonedit" onbuttonclick="ordChoose" value="<?php echo $this->_var['info']['o_id']; ?>" allowInput="false"  text="<?php echo $this->_var['order_name']; ?>"  style="width:100px" id="usrId"/>
						<?php endif; ?>
						</td>
					<?php endif; ?>
					</tr>
					<tr>
						<td>产品牌号：</td>
						<td>
							<input name="p_id" class="mini-buttonedit" onbuttonclick="proChoose" value="<?php echo $this->_var['info']['p_id']; ?>" allowInput="false"  text="<?php echo $this->_var['model']; ?>"  style="width:100px" id="usrId"/>
						</td>
					</tr>
					<tr>
						<td>厂家<font style="color:red;"></td>
						<td id="f_name"></td>
						<input class="mini-hidden" name="f_name" id="ff_name" value=""/>
					</tr>
					<tr>
						<td><font style="color:blue;">数量 : </font></td>
						<td><input name="number" class="mini-textbox" style="width:100px;" maxlength="8" required="true" value="<?php echo $this->_var['info']['number']; ?>"/>
						</td>
					</tr>
					<tr>
						<td><font style="color:blue;">单价 ：</font></td>
						<td><input name="unit_price" class="mini-textbox" style="width:100px;" maxlength="8" required="true" value="<?php echo $this->_var['info']['unit_price']; ?>"/>
						</td>
					</tr>
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
<!-- 					<tr>						
						<td>备注:</td>
						<td><input name="remark" class="mini-textarea" style="width:100px" required="false"/ value="<?php echo $this->_var['info']['remark']; ?>"></td>

					</tr> -->
					<input class="mini-hidden" id="hidden_model" name="model" value=""/>
				</table>
		</fieldset>


			</div>
		</div><input name="utype" class="mini-textbox" style="display:none" value="3" vtype="utype"/>
		<div align="center" style="margin-top:10px;">
		<?php if ($this->_var['choose'] == 1): ?>
			<div class="mini-toolbar" style="text-align:center;padding-top:8px;padding-bottom:8px; border:1px solid #000;" borderStyle="border:0;">
				<a class="mini-button" style="width:60px;" onClick="onComfirm()">确定</a>
			<span style="display:inline-block;width:25px;"></span>
				<a class="mini-button" style="width:60px;" onClick="onCancel()">取消</a>
			</div>
		<?php else: ?>
			<a class="mini-button" iconcls="icon-ok" onclick="submitForm">确定</a>
			<a class="mini-button" iconcls="icon-cancel" onclick="onCancel">关闭</a><span id="returnMsg" style="padding-left:5px; color:#F00;"></span>
		<?php endif; ?>
			
		</div>
	</div>
</div>
<script charset="utf-8" src="__JS__/kindeditor/kindeditor.js"></script>
<script charset="utf-8" src="__JS__/kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript">
mini.parse();
var form = new mini.Form("#infoForm");
var cid = mini.get("#usrId").getValue();
function submitForm(){
	form.validate();
	if(form.isValid() == false) return;
	//提交数据
	var o = form.getData();
	var json = mini.encode(o);
	$("#returnMsg").text('');
	form.loading("数据提交中，请稍后......");
	var urlstr = '/product/purchaseLog/addSubmit';
	$.post(urlstr,{data:json},function(data){
		form.unmask();
		$("#returnMsg").text(data.msg);
		if(data.err=='0'){
			CloseWindow("save");
		}else{
			return false;
		}
	},'json');
}
//确定并获取数据
function onComfirm() {
	form.validate();
	if(form.isValid() == false) return;
	//提交数据
	CloseWindow("ok");
}
function CloseWindow(action) {
	if (window.CloseOwnerWindow) return window.CloseOwnerWindow(action);
	else window.close();
}
function onCancel(e) {
	CloseWindow("cancel");
}

//强制选择归属订单
function ordChoose(e){
	var btn = this;
		mini.open({
		url: "/product/order/init?do=search&order_type=1",
		title: "选择订单",
		width: 1250,
		height: 550,
		onload: function () {
			var grid = e.sender;
			var data=grid.getValue();
			top['win'].setDvalue(data);  //去调用子页面的方法。
			// var iframe = this.getIFrameEl();
			// iframe.contentWindow.SetData(data);
		},
		ondestroy: function (action) {
			if (action == "ok") {
				var iframe = this.getIFrameEl();
				var data = iframe.contentWindow.GetData();//父窗口向子窗口传值
				data = mini.clone(data);    //必须
				if (data) {
					btn.setValue(data.o_id);
					btn.setText(data.order_name);
				}
			}
		}
	});
}
//强制选择归属产品
function proChoose(e){
	var btn = this;
	var model = mini.get('hidden_model');
	var f_name = mini.get('f_name');
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
				data = mini.clone(data);    //必须
				if (data) {
					btn.setValue(data.id);
					btn.setText(data.model);
					model.setValue(data.model);
					$('#f_name').html(data.f_name);
					var ff_name = mini.get('ff_name');
					ff_name.setValue(data.f_name);
					mini.get('product_id').setValue(data.id);
					mini.get('history_price').setValue(data.price_s);
					$('#neighbor_price').html(data.price_p);
					$('#history').show();
					$('#s_price').show();
				}
			}
		}
	});
}
//展示10条历史成交价
function proHistory(){
	var product_id = $('#product_id').val();
	console.log(product_id);
		mini.open({
		url: " /order/purchase/init?p_id="+product_id,
		title: "产品历史成交价",
		width: 1250,
		height: 550,
	});
}
//强制选择归属供应商
function usrChoose(e){
	var btn = this;
	var hidden_supplier = mini.get('hidden_supplier');
		mini.open({
		url: "/user/customer/init?do=search",
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
					btn.setValue(data.c_id);
					btn.setText(data.c_name);
					hidden_supplier.setValue(data.c_name);
					$("#"+btn.id+"\\$value").val(data.c_id);
				}
			}
		}
	});         
}
function GetData() {
	//提交数据
	var o = form.getData();
	var json = mini.encode(o);
	$("#returnMsg").text('');
	return json;
}
</script>
