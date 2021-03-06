__layout|public:mini_layout|layout__
<div class="mini-toolbar" style="margin:3px 3px 0;">
	<table style="width:100%;">
		<tr>
		<?php if ($this->_var['doact'] != 'search'): ?>
			<td style="white-space:nowrap;">
				<!-- <a class="mini-button" iconCls="icon-add" plain="true" onclick="add()">新增</a>
				<span class="separator"></span> -->
				<a class="mini-button" iconCls="icon-remove" plain="true" onclick="remove()">删除</a>
			</td>
		<?php endif; ?>
			<td style="float:right;">
			<form id="soform">
				<select name="sTime">
					<option value="input_time">创建时间</option>
					<option value="update_time">更新时间</option>
					<option value="sign_time">签订日期</option>

				</select>
				<input name="startTime" class="mini-datepicker" style="width:95px;"/> -
				<input name="endTime" class="mini-datepicker" style="width:95px;"/>
				<select name="pay_method" >
					<option value="" selected="selected">付款</option>
					<?php echo $this->html_options(array('options'=>$this->_var['pay_method'])); ?>
				</select>
				<select name="transport_type" >
					<option value="" selected="selected">运输</option>
					<?php echo $this->html_options(array('options'=>$this->_var['transport_type'])); ?>
				</select>
				<select name="order_status" id="soStatus">
					<option value="" selected="selected">审核</option>
					<?php echo $this->html_options(array('options'=>$this->_var['order_status'])); ?>
				</select>
				<select name="goods_status" >
					<option value="" selected="selected">发货</option>
					<?php echo $this->html_options(array('options'=>$this->_var['goods_status'])); ?>
				</select>
				<select name="key_type">
					<option value="order_sn">订单号</option>
					<option value="order_name">订单名称</option>
					<option value="c_id">客户名称</option>
				</select>
				<input name="keyword" class="mini-textbox" emptyText="" style="width:100px;" onenter="onKeyEnter"/>
				<a class="mini-button" iconCls="icon-search" onclick="search()">查询</a>
				<span id="searchMsg"></span></form>
			</td>
		</tr>
	</table>
</div>
<div class="mini-fit" style="padding:1px 3px 3px;">
	<div id="gridCell" class="mini-datagrid" style="width:100%;height:100%;"url="/product/unionOrder/init?action=grid&do=<?php echo $this->_var['doact']; ?>&type=<?php echo $this->_var['order_type']; ?>"  idField="id"  allowCellWrap="true"
	sizeList="[10,20,50,100]" pageSize="20" multiSelect="true" showFilterRow="true" allowCellSelect="true" allowCellEdit="true" >
		<div property="columns">
			<div type="checkcolumn"></div>
			<div field="id" width="20" headerAlign="center" align="center" allowSort="true" renderer="onLoadHandle">OID</div>
			<div field="order_name" width="75" headerAlign="center" align="center" allowSort="true">订单名称</div>
			<div field="order_sn" width="45" headerAlign="center" align="center" allowSort="true">订单号</div>
			<div field="sale_id" width="35" headerAlign="center" align="center" allowSort="true" renderer="onLoadCinfo">卖家ID</div>
			<div field="buy_id" width="35" headerAlign="center" align="center" allowSort="true"  renderer="onLoadCinfo">买家ID</div>
			<div field="bc_name" width="65" headerAlign="center" align="center" allowSort="true">卖家</div>
			<div field="sc_name" width="65" headerAlign="center" align="center" allowSort="true">买家</div>
			<div field="deal_price" width="45" headerAlign="center" align="center" allowSort="true">成交价</div>
			<div field="total_price" width="50" headerAlign="center" align="center" allowSort="true">总金额</div>
			<div field="sign_time" width="50" headerAlign="center" align="center" allowSort="true" dateFormat="yyyy-MM-dd">签订时间</div>
			<div field="pay_method" width="45" headerAlign="center" align="center" allowSort="true">付款方式</div>
			<div field="order_status" width="35" headerAlign="center" align="center" renderer="LoadoStatus">审核</div>
			<div field="goods_status" width="35" headerAlign="center" align="center" allowSort="true">发货</div>
			<div field="invoice_status" width="35" headerAlign="center" align="center" allowSort="true">开票</div>
			<div field="input_time" width="80" headerAlign="center" align="center" allowSort="true" dateFormat="yyyy-MM-dd HH:mm">创建时间</div>
			<div field="input_admin" width="45" headerAlign="center" align="center">交易员</div>
			<div field="update_time" width="80" headerAlign="center" align="center" allowSort="true" dateFormat="yyyy-MM-dd HH:mm">修改时间</div>
			<div field="update_admin" width="45" headerAlign="center" align="center">修改者</div>
			 <div name="action" width="40" headerAlign="center" align="center" cellStyle="padding:0;" renderer="onLoadDetail">操作</div>
		</div>
	</div>
</div>
<div id="admInfo" class="mini-window" title="发货" style="width:300px;" 
	showModal="true" allowResize="true" allowDrag="true"
	>
	<div id="addForm" class="form" >
		<table style="width:100%;">
			<input id="id" class="mini-hidden" name="id"/>
			<tr>
				<td>物流公司:</td>
				<td><input name="ship_name" class="mini-textbox" style="width:200px;" required="true"/>
				</td>
			</tr>
			<tr>
				<td>发货方式:</td>
				<td><input name="goods_status" class="mini-combobox" data='<?php echo setMiniConfig($this->_var['goods_status']); ?>' textField="name" valueField="id"  style="width:200px;" required="true"/>
				</td>
			</tr>
			<tr>
				<td>送货时间:</td>
				<td><input name="delivery_time" class="mini-datepicker" style="width:200px;" required="true"/></td>
			</tr>
			<tr>
				<td>运费价格:</td>
				<td><input name="freight_price" class="mini-textbox" style="width:200px" required="true"/></td>
			</tr>
			<tr>
				<td>备&nbsp;&nbsp;注:</td>
				<td><input name="ship_remark" class="mini-textarea" style="width:200px" required="true"/></td>
			</tr>
			<tr>
			<td style="text-align:center;padding-top:5px;" colspan="2">
				<a class="mini-button" style="width:60px;" onClick="submitForm()">确定</a>
				<span style="display:inline-block;width:25px;"></span>
				<a class="mini-button" style="width:60px;" onClick="hide()">取消</a>
			</td>
			</tr>
		</table>
	</div>
</div>
<div id="admInfo1" class="mini-window" title="审核" style="width:270px;" 
	showModal="true" allowResize="true" allowDrag="true"
	>
	<div id="addForm1" class="form" >
		<table style="width:100%;">
			<input id="chkid" class="mini-hidden" name="id"/>
			<tr>
				<td>审核:</td>
				<td><input name="order_status" class="mini-combobox" data='<?php echo setMiniConfig($this->_var['order_status']); ?>' textField="name" valueField="id"  style="width:170px;" required="true"/>
				</td>
			</tr>
			<tr>
				<td>备注:</td>
				<td><input name="remark"  class="mini-textarea"  style="width:170px;" required="true"/></td>
			</tr>
			<tr>
				<td style="text-align:center;padding-top:5px;" colspan="2">
					<a class="mini-button" style="width:60px;" onClick="submitForm1()">确定</a>
					<span style="display:inline-block;width:25px;"></span>
					<a class="mini-button" style="width:60px;" onClick="hide()">取消</a>
				</td>
			</tr>
		</table>
	</div>
</div>
<?php if ($this->_var['doact'] == 'search'): ?>
<div class="mini-toolbar" style="text-align:center;padding-top:8px;padding-bottom:8px; border:1px solid #000;" borderStyle="border:0;">
	<a class="mini-button" style="width:60px;" onClick="onComfirm()">确定</a>
	<span style="display:inline-block;width:25px;"></span>
	<a class="mini-button" style="width:60px;" onClick="onCancel()">取消</a>
</div>
<?php endif; ?>
<script type="text/javascript">
mini.parse();
//搜索或刷新
var grid = mini.get("gridCell");
function search() {
	grid.load($("#soform").serializeObject(),function(e){
		$("#searchMsg").html(e.msg);
	});
}
search();
function onKeyEnter(e) {
	search();
}
//查看卖家买家信息
function onLoadCinfo(e) {
	var record = e.record,uid=e.value; //record.id
	if(uid>0){
		return s = '<a href="javascript:viewCinfo('+uid+')">'+uid+'</a> ';
	}else{
		return uid;
	}
}
//追加操作按钮
function onLoadHandle(e) {
	var record = e.record,s='',oid = record.id;
	s += '<a href="javascript:viewOrdInfo('+oid+')">查</a> ';
	return s;
}
//新增产品详情
function onLoadDetail(e){
	var record = e.record,s='',oid = record.id, order_status = record.order_status,collection_status = record.collection_status;
		if(order_status == 2 && collection_status !=1){
			s += '<a href="javascript:outStorage('+oid+')">发货</a>';
		}else if(order_status == 1){
			s += '<a href="javascript:outchk('+oid+')">审核</a>';
		}
		
		
	return s;
}
function LoadoStatus(e) {
	var record = e.record.order_status; //record.id
	return $("#soStatus").find("option[value="+record+"]").text();
}
//弹出发货form表单
var admInfo = mini.get("admInfo");
var form = new mini.Form("#addForm");
function outStorage(id) {
	form.clear();
	mini.get("id").setValue(id);
	admInfo.show();
}
//提交发货数据(保存)
function submitForm() {
	form.validate();
  if (form.isValid() == false) return;
  var o = form.getData();
  grid.loading("数据提交中，请稍后......");
  var json = mini.encode(o);

  var callback=function(data){
	if(data.err!='0'){
	  grid.unmask();
	  alert(data.msg);
	  return false;
	}else{
	  grid.reload();
	  admInfo.hide();
	}
  }
  utils.ajax('/product/unionOrder/shipSave',{data:json},callback,"POST","json");
}
// 提交审核相关代码段
//弹出审核form表单
var admInfo1 = mini.get("admInfo1");
var form = new mini.Form("#addForm1");
function outchk(id) {
	form.clear();
	mini.get("chkid").setValue(id);
	admInfo1.show();
}
//审核信息(保存)
function submitForm1() {
	form.validate();
  if (form.isValid() == false) return;
  var o = form.getData();
  grid.loading("数据提交中，请稍后......");
  var json = mini.encode(o);
  var callback=function(data){
	if(data.err!='0'){
	  grid.unmask();
	  alert(data.msg);
	  return false;
	}else{
	  grid.reload();
	  admInfo1.hide();
	}
  }
  utils.ajax('/product/unionOrder/chk',{data:json},callback,"POST","json");
}
//查看订单相关信息
function viewOrdInfo(oid){
	mini.open({
		url: "/product/unionOrder/info?oid="+oid,
		showMaxButton:true,
		title: "查看订单相关信息", 
		width: 800, height:480
	});		
}

//新增订单
function add(){
	mini.open({
		url: "/product/unionOrder/info",
		showMaxButton:true,
		title: '新增订单', width: 740, 
		ondestroy: function (action) {
			if(action=='save'){ //重新加载
				grid.reload();
			}
		}
	});	
}
//隐藏
function hide() {
	form.clear();
	admInfo.hide();
	admInfo1.hide();
}
//删除
function remove() {
	var rows = grid.getSelecteds(),_ids=new Array();
	if(rows.length<1) return;
		for(var i=0;i<rows.length;i++){
	var _id=parseInt(rows[i].id);
	if(_id<1){
		grid.removeRow(rows[i],false);
	}else{
		_ids.push(_id);
	}
  }
	var ids=_ids.join(',');
	if(ids=='') return;
	mini.confirm("确定删除？", "提示", function(action){
	if(action!='ok') return;
	var callback=function(data){
	if(data.err=='0'){
		grid.reload();
	}else{
		alert(data.msg)
		return false;
	}
	}
	utils.ajax('/product/unionOrder/remove',{ids:ids},callback,"POST","json");
  });
}
function GetData() {
	var row = grid.getSelected();
	return row;
}
function CloseWindow(action) {
	if (window.CloseOwnerWindow) return window.CloseOwnerWindow(action);
	else window.close();
}
//确定并获取数据
function onComfirm() {
	CloseWindow("ok");
}
//取消
function onCancel() {
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