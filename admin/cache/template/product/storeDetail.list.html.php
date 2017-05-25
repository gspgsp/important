__layout|public:mini_layout|layout__
<div class="mini-toolbar" style="margin:3px 3px 0;">
	<table style="width:100%;">
		<tr>
		<!-- <?php if ($this->_var['doact'] != 'search' && $this->_var['id'] == 0): ?>
			<td style="white-space:nowrap;">
				<a class="mini-button" iconCls="icon-remove" plain="true" onclick="remove()">删除</a>
			</td>
		<?php endif; ?> -->
			<td style="float:left;">
				<a class="mini-button" iconCls="icon-save" plain="true" onclick="saveData()">保存期望价格</a> 
				<span id="searchMsg" style="color:red;"></span>
			</td>
			<td style="float:right;">
			<form id="soform">
				<select name="company_account">
					<option value="" selected="selected">= 抬头 =</option>
					<?php echo $this->html_options(array('options'=>$this->_var['company_account'])); ?>
				</select>
				<select name="sTime">
					<option value="il.input_time">入库时间</option>
					<option value="il.update_time">更新时间</option>
				</select>
				<input name="startTime" class="mini-datepicker" style="width:95px;"/> -
				<input name="endTime" class="mini-datepicker" style="width:95px;"/>
				<select name="remainder">
					<option value="1">有库存</option>
					<option value="2">虚拟入库</option>
				</select>
				<select name="controlled_number">
					<option value="1" selected="selected">是</option>
					<option value="2">否</option>
				</select>
				<select name="pay_status">
					<option value="">=付款=</option>
					<option value="1">待付款</option>
					<option value="2">部分付</option>
					<option value="3">全部付款</option>
				</select>
				<select name="key_type">
					<option value="p_id">牌号</option>
					<option value="order_sn">订单号</option>
					<option value="store_id">仓库</option>
					<option value="store_aid">入库人</option>
					<option value="unit_price">采购价</option>
					<option value="customer_manager">交易员</option>
				</select>
				<input name="keyword" class="mini-textbox" emptyText="" style="width:100px;" onenter="onKeyEnter"/>
				<a class="mini-button" iconCls="icon-search" onclick="search()">查询</a>
				</form>
			</td>
		</tr>
	</table>
</div>
<div class="mini-fit" style="padding:1px 3px 3px;">
	<div id="gridCell" class="mini-datagrid" style="width:100%;height:100%;"url="/product/storeDetail/init?action=grid&do=<?php echo $this->_var['doact']; ?>&pid=<?php echo $this->_var['pid']; ?>"  idField="id"
	sizeList="[10,20,50,100]" pageSize="20" multiSelect="true"  onrowdblclick="onRowDblClick" showFilterRow="true" allowCellSelect="true" allowCellEdit="true" >
		<div property="columns">
			<div type="checkcolumn"></div>
			<div field="store_name" width="45" headerAlign="center" align="center" allowSort="true">仓库</div>
			<?php if ($this->_var['exits'] == 1): ?>
			<div field="cname" width="95" headerAlign="center" align="center" allowSort="true">供应商</div>
			<?php endif; ?>
			<div field="purchase_id" width="90" headerAlign="center" align="center" allowSort="true" renderer="onLoadHandle">订单号</div>
			<div field="model" width="50" headerAlign="center" align="center" allowSort="true" renderer="onLoadProduct">牌号</div>
			<div field="f_name" width="60" headerAlign="center" align="center" allowSort="true">厂家</div>
			<div field="unit_price" width="60" headerAlign="center" align="center" allowSort="true">采购价</div>
			<div field="expect_price" width="35" headerAlign="center" allowSort="true" align="center">期望售价
				<input property="editor" class="mini-textbox" style="width:100%;"/>
			</div>
			<div field="number" width="40" headerAlign="center" align="center" allowSort="true">进货数量</div>
			<div field="remainder" width="40" headerAlign="center" align="center" allowSort="true">剩余数量</div>
			<div field="controlled_number" width="40" headerAlign="center" align="center" allowSort="true">可用数量</div>
			<div field="business_model" width="40" headerAlign="center" align="center">订单模式</div>
			<div field="collection" width="40" headerAlign="center" align="center" allowSort="true">付款数目</div>
			<div field="lock_number" width="40" headerAlign="center" align="center" allowSort="true">锁定数量</div>
			<div field="customer_manager" width="40" headerAlign="center" align="center" allowSort="true">交易员</div>
			<div field="input_time" width="70" headerAlign="center" align="center" allowSort="true" dateFormat="yyyy-MM-dd HH:mm">入库时间</div>
			<?php if ($this->_var['pid'] == 0): ?>
			<div field="update_time" width="70" headerAlign="center" align="center" allowSort="true" dateFormat="yyyy-MM-dd HH:mm">最后操作时间</div>
			<?php endif; ?>
			<div field="id" width="30" headerAlign="center" align="center" allowSort="true"  renderer="onLoadLogDetail">查看</div>
			<!-- <div field="admin_name" width="45" headerAlign="center" align="center">操作者</div> -->
			<div field="admin_name" width="30" headerAlign="center" align="center" allowSort="true">入库人</div>

		</div>
	</div>
</div></div>
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
//追加详情按钮
function onLoadLogDetail(e){
	var record = e.record,s='',id=record.id;
	s+='<a href="javascript:viewLogInfo('+id+')">详情</a>';
	return s;
}
function viewLogInfo(id){
	mini.open({
		url: "/product/storeDetail/logDetail?id="+id,
		showMaxButton:true,
		title: "查看订单相关信息",
		width: 950, height:450
	});
}

//追加操作按钮
function onLoadHandle(e) {
	var record = e.record,s='',o_id = record.o_id,order_sn = record.order_sn;
	s += '<a href="javascript:viewOrdInfo('+o_id+')">'+order_sn+'</a> ';
	return s;
}
//查看订单相关信息
function viewOrdInfo(oid){
	mini.open({
		url: "/product/order/info?oid="+oid,
		showMaxButton:true,
		title: "查看订单相关信息",
		width: 800, height:450
	});
}
//追加查看产品按钮
function onLoadProduct(e){
	var record = e.record,s='',p_id = record.p_id,model=record.model;
	s += '<a href="javascript:viewProInfo('+p_id+')">'+model+'</a> ';
	return s;
}
//查看产品相关信息
function viewProInfo(pid){
	mini.open({
		url: "/product/product/info?pid="+pid,
		showMaxButton:true,
		title: "产品相关信息",
		width: 250, height:250
	});
}
//删除
function remove() {
	var rows = grid.getSelecteds(),_ids=new Array(),inlog_ids=new Array();
	if(rows.length<1) return;
	for(var i=0;i<rows.length;i++){
		var _id=parseInt(rows[i].p_id);
		var _idlog=parseInt(rows[i].id);
	if(_id<1){
		grid.removeRow(rows[i],false);
	}else{
		_ids.push(_id);
		inlog_ids.push(_idlog);
	}
 	}
	var ids=_ids.join(',');
	var inlog=inlog_ids.join(',');
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
	utils.ajax('/product/storeDetail/remove',{ids:ids,inlog:inlog},callback,"POST","json");
  });
}
//双击弹出
// function onRowDblClick(e) {
// 	<?php if ($this->_var['doact'] != 'search'): ?>
// 	var record = e.record, status=record.status;
// 	onEdit();
// 	<?php endif; ?>
// }

function onEdit(e) {
	var row = grid.getSelected();
	if (row) {
		var width=500,height=200,title='库存信息';
		urlStr="/product/storeDetail/info?type=edit&id="+row.id;
		mini.open({
			url: urlStr,
			title: title,
			width: width,
			height:height,
			ondestroy: function (action) {
				if(action=='save'){ //重新加载
					grid.reload();
				}
			}
		});
	}
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
//行内编辑后保存数据
function saveData() {
	var data = grid.getChanges();
	var json = mini.encode(data);
	if(json.length<10) return false;
	grid.loading("保存中，请稍后......");
	var callback=function(data){
		if(data.err!='0'){
			alert(data.msg)
			return false;
		}else{
			grid.reload();
		}
	}
	utils.ajax('/product/storeDetail/saveData',{data:json},callback,"POST","json");
}
</script>