__layout|public:mini_layout|layout__
<div class="mini-toolbar" style="margin:3px 3px 0;">
<style>table p{padding:0; margin:0; padding-top:3px;}</style>
	<table style="width:100%;">
		<tr>
			<td style="white-space:nowrap;">
			<form id="soform">
				添加时间
				<input name="startTime" class="mini-datepicker" style="width:100px;"/> -
				<input name="endTime" class="mini-datepicker" style="width:100px;"/>
				<select name="identification" id="soidentification">
					<option value="" selected="selected">=认证=</option>
					<?php echo $this->html_options(array('options'=>$this->_var['identification'])); ?>
				</select>
				<select name="level" id="soLevel">
					<option value="" selected="selected">=客户级别=</option>
					<?php echo $this->html_options(array('options'=>$this->_var['level'])); ?>
				</select>
				<select name="type" id="soType">
					<option value="" selected="selected">=客户类型=</option>
					<?php echo $this->html_options(array('options'=>$this->_var['type'])); ?>
				</select>
				<select name="invoice">
					<option value="" selected="selected">=开票资料=</option>
					<option value="1">否</option>
					<option value="2">是</option>
				</select>
				<select name="key_type">
					<option value="c_name" selected="selected">客户名称</option>
					<option value="c_id">客户ID</option>
					<option value="legal_idcard">法人身份证</option>
					<option value="need_product" >所需牌号</option>
					<option value="business_licence">营业执照号</option>
					<option value="organization_code">组织代码</option>
					<option value="tax_registration">税务登记号码</option>
					<option value="customer_manager">交易员</option>
				</select>
				<input name="keyword" class="mini-textbox" emptyText="请输入关键词" style="width:140px;" onenter="onKeyEnter"/>
				<a class="mini-button" iconCls="icon-search" onclick="search()">查询</a>
				<span id="searchMsg"></span>
			   </form>
			</td>
		</tr>
	</table>
</div>
<div class="mini-fit" style="padding:1px 3px 3px;">
	<div id="gridCell" class="mini-datagrid" style="width:100%;height:100%;"  sizeList="[10,20,50,100]" pageSize="20" allowCellWrap="true"
		url="/user/customerchose/init?action=grid&do=<?php echo $this->_var['doact']; ?>&pt=<?php echo $this->_var['pt']; ?>" idField="user_id" multiSelect="true">
		<div property="columns">
			<div type="checkcolumn"></div>
			<div field="c_name" width="70" headerAlign="center" allowSort="true" align="center" renderer="onLoadHandle">客户名字</div>
			<div field="chanel" width="35" headerAlign="center" align="center">渠道</div>
			<div field="type" width="35" headerAlign="center" align="center">客户类型</div>
			<div field="need_product_adm" width="50" headerAlign="center" align="center">所需牌号</div>
			<div field="need_product" width="50" headerAlign="center" align="center">主营产品</div>
			<div field="month_consum" width="50" headerAlign="center" align="center">月用量</div>
			<div field="invoice" width="30" headerAlign="center"  align="center">开票资料</div>
			<div field="name" width="30" headerAlign="center" align="center" >联系人姓名</div>
			<div field="tel" width="60" headerAlign="center"  align="center">联系人电话</div>
			<div field="mobile" width="60" headerAlign="center"  align="center">联系人手机</div>
			<div field="remark" width="60" headerAlign="center"  align="center">最新跟进</div>
			<div field="credit_limit" width="50" headerAlign="center"  align="center">信用额度</div>
			<div field="available_credit_limit" width="50" headerAlign="center"  align="center">可用额度</div>
			<div field="input_time" width="50" headerAlign="center" dateFormat="yy-MM-dd HH:mm" align="center">添加时间</div>
			<div field="update_time" width="50" headerAlign="center" dateFormat="yy-MM-dd HH:mm" align="center">更新时间</div>
			<div field="customer_manager" width="30" headerAlign="center"  align="center">交易员</div>
			<div field="depart" width="30" headerAlign="center"  align="center">部门</div>
			<div field="identification" width="40" headerAlign="center" align="center" renderer="identificationStatus">认证</div>
		</div>
	</div>
</div>
<?php if ($this->_var['doact'] == 'search'): ?>
<div class="mini-toolbar" style="text-align:center;padding-top:8px;padding-bottom:8px;" borderStyle="border:0;">
		<a class="mini-button" style="width:60px;" onClick="onComfirm()">确定</a>
		<span style="display:inline-block;width:25px;"></span>
		<a class="mini-button" style="width:60px;" onClick="onCancel()">取消</a>
</div>
<?php endif; ?>

<script type="text/javascript">
mini.parse();
var grid = mini.get("gridCell");
grid.load();
grid.on("drawcell", function (e) {
	var record = e.record,column = e.column,field = e.field,value = e.value;
	if (column.field == "available_credit_limit") {
		if (e.value<0) {
			e.cellStyle = "background:#FF00BA"
		} else {
			e.rowStyle = "";
		}
	}
});

function search() {
	grid.load($("#soform").serializeObject(),function(e){
		$("#searchMsg").html(e.msg);
	});
}
function onKeyEnter(e) {
	search();
}
function identificationStatus(e) {
	var record = e.record.identification; //record.id
	return $("#soidentification").find("option[value="+record+"]").text();
}
//筛选数据
function CloseWindow(action) {
	if (window.CloseOwnerWindow) return window.CloseOwnerWindow(action);
	else window.close();
}
function onComfirm() {
	CloseWindow("ok");
}
function onCancel() {
	CloseWindow("cancel");
}
function GetData() {
	var row = grid.getSelected();
	return row;
}
</script>