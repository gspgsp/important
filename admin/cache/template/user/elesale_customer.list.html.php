__layout|public:mini_layout|layout__
<div class="mini-toolbar" style="margin:3px 3px 0;">
	<table style="width:100%;">
		<tr>
			<td style="white-space:nowrap;">
				<a class="mini-button" iconCls="icon-add" plain="true" onclick="add(0)">新增</a>
				<a class="mini-button" iconCls="icon-edit" plain="true" onclick="edit()">编辑</a>
				<a class="mini-button" iconCls="icon-remove" plain="true" onclick="removeRow()">删除</a>     
			</td>
			<td style="float:right;">
			<form id="soform">
				<select name="sTime">
					<option value="input_time">录入时间</option>
					<option value="update_time">更新时间</option>
				</select>
					<input name="startTime" class="mini-datepicker" style="width:100px;"/> -
					<input name="endTime" class="mini-datepicker" style="width:100px;"/>
				<select name="status">
					<option value="">=状态=</option>
					<option value="1">跟踪</option>
					<option value="2">无跟踪</option>
				</select>
				<select name="key_type">
					<option value="name">姓名</option>
					<option value="c_name">公司</option>
					<option value="mobile">手机</option>
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
		url="/user/customer_elesale/init?action=grid" onrowdblclick="onRowDblClick" showFilterRow="true" idField="id" multiSelect="true" allowCellSelect="true" allowCellEdit="true"  contextMenu="#gridMenu" headerContextMenu="#headerMenu">
		<div property="columns">
			<div type="checkcolumn"></div>
			<div field="id" width="30" headerAlign="center" align="center">联系人ID</div>
			<div field="name" width="30" headerAlign="center" align="center">联系人</div>
			<div field="sex" width="30" headerAlign="center"  align="center">性别</div>
			<div field="c_name" width="80" headerAlign="center" align="center">公司</div>
			<div field="mobile" width="50" headerAlign="center" align="center">手机号</div>
			<div field="telphone" width="50" headerAlign="center" align="center">座机号</div>
			<div field="fax" width="50" headerAlign="center" align="center">传真</div>
			<div field="email" width="50" headerAlign="center" align="center">邮箱</div>
			<div field="qq" width="50" headerAlign="center" align="center">qq号</div>
			<div field="sale_man" width="50" headerAlign="center" align="center">跟踪人</div>
			<div field="track" width="50" headerAlign="center" align="center"  renderer="onTrack">跟踪状态</div>
			<div field="member_status" width="40" headerAlign="center" renderer="onStatus" align="center">会员状态</div>
			<div field="input_time" width="60" headerAlign="center" dateFormat="yyyy-MM-dd HH:mm" align="center" allowSort="true">录入时间</div>
			<div field="update_time" width="60" headerAlign="center" dateFormat="yyyy-MM-dd HH:mm" align="center" allowSort="true">更新时间</div>
			<div name="action" width="80" headerAlign="center" align="center" cellStyle="padding:0;" renderer="onOperate">跟踪操作</div>
		</div>
	</div>
</div>
<div id="addRecord" class="mini-window" title="添加跟踪纪录" style="width:570px; height:180px;" showModal="true" allowResize="true" allowDrag="true">
	<div id="replaceForm" class="form" >
		<table style="width:100%;">
			<input id="c_id" class="mini-hidden" name="c_id">
			<tr>
				<td width="80">跟踪方式：</td>
				<td>
					<input name="way" width="150" class="mini-textbox" required="true" value="" />
				</td>
			</tr>
			<tr>
				<td>跟踪内容记录:</td>
				<td>
					<input name="content" class="mini-textarea" style="width:420px; height:50px;" value=""/>
				</td>
			</tr>
			<tr>
			<td style="text-align:center;padding-top:20px;" colspan="2">
				<a class="mini-button" iconCls="icon-save" plain="true" href="javascript:submitRecord()">提交</a>
			</td>
			</tr>
		</table>
	</div>
</div>
<div id="track_man" class="mini-window" title="添加跟踪人" style="width:310px; height:140px;" showModal="true" allowResize="true" allowDrag="true">
	<div id="twoForm" class="form" >
		<table style="width:100%;">
			<input id="cus_id" class="mini-hidden" name="cus_id">
			<tr>
				<td width="80">跟踪人：</td>
				<td>
					<input name="sale_man" width="150" class="mini-textbox" required="true" value="" />
				</td>
			</tr>
			<tr>
			<td style="text-align:center;padding-top:20px;" colspan="2">
				<a class="mini-button" iconCls="icon-save" plain="true" href="javascript:submitMan()">提交</a>
			</td>
			</tr>
		</table>
	</div>
</div>
<script type="text/javascript">
mini.parse();
var grid = mini.get("gridCell");
var dStatus=[{id: 1, text: '可用'}, {id: 0, text: '禁用'}];
//显示会员状态
function onStatus(e){
	var record = e.record,vip=record.member_status, s='';
	if (vip==1) {s += '已开通';}else{s += '未开通';}
	return s;
}
//是否是会员进行对应操作
function onOperate(e){
	var record = e.record,id=record.id,track=record.track,s='';
	if(track==1){
		s += '<a href="javascript:addRow('+id+')">添加</a> | <a href="javascript:noTrack('+id+')">放弃跟踪</a>';
	}else if(track==2){
		s += '<a href="javascript:trackCustomer('+id+')">跟踪客户</a>';
	}
	s += ' |<a href="javascript:viewRow('+id+')">查看</a>';
	return s;
}
//记录添加窗口
var addRecord = mini.get("addRecord");
var form = new mini.Form("#replaceForm");
//添加跟踪记录
function addRow(id){
	mini.get(c_id).setValue(id);
	addRecord.show();
}
//提交会员开通信息
function submitRecord(){
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
			form.clear();
			grid.reload();
			addRecord.hide();
		}
	}
	utils.ajax('/user/customer_elesale/addRecord',{data:json},callback,"POST","json");
}
//查看跟踪记录
function viewRow(id){
	var width=900, height=300,title="客户跟踪记录";
	mini.open({
		url: "/user/customer_elesale/viewRecord?c_id="+id,
		allowResize:true,
		showMaxButton:true,
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
//客户跟踪人添加窗口
var track_man = mini.get("track_man");
var form2 = new mini.Form("#twoForm");
//
function trackCustomer(id){
	mini.get(cus_id).setValue(id);
	track_man.show();
}
//提交会员开通信息
function submitMan(){
	form2.validate();
	if (form2.isValid() == false) return;
	var o = form2.getData();
	grid.loading("数据提交中，请稍后......");
	var json = mini.encode(o);
	var callback=function(data){
		if(data.err!='0'){
			grid.unmask();
			alert(data.msg);
			return false;
		}else{
			form.clear();
			grid.reload();
			track_man.hide();
		}
	}
	utils.ajax('/user/customer_elesale/addTrack',{data:json},callback,"POST","json");
}
//放弃跟踪客户操作
function noTrack(id){
	mini.confirm("确定放弃跟踪吗？", "提示",function(action){
		if(action!='ok') return;
		var callback=function(data){
			if(data.err!='0'){
				alert(data.msg)
				return false;
			}else{
				grid.reload();
			}
		}
		utils.ajax('/user/customer_elesale/noTrack',{id:id},callback,"POST","json");
	});
}
//显示跟踪状态
function onTrack(e){
	var record = e.record,track=record.track, s='';
	if (track==1) {s += '跟踪中';}else{s += '未跟踪';}
	return s;
}
function search() {
	grid.load($("#soform").serializeObject(),function(e){
		$("#searchMsg").html(e.msg);
	});
}
search();
function onKeyEnter(e) {
	search();
}
function onRowDblClick(e) {
	edit();
}
//编辑客户信息
function edit() {
	var row = grid.getSelected();
	if (row) {
		add(row.id)
	}
}
//删除数据
function removeRow() {
	var rows = grid.getSelecteds(), _ids=new Array();
	if(rows.length<1) return;
	for(var i=0;i<rows.length;i++){
		_ids[i]=parseInt(rows[i].id);
	}
	var ids=_ids.join(',');
	if(ids=='') return;
	mini.confirm("确定删除内容？", "提示",	function(action){
			if(action!='ok') return;
			var callback=function(data){
				if(data.err!='0'){
					alert(data.msg)
					return false;
				}else{
					grid.reload();
				}
			}
			utils.ajax('/user/customer_elesale/del',{ids:ids},callback,"POST","json");
		}
	);
}
//增加客户信息
function add(id){
	mini.open({
		url: "/user/customer_elesale/info?edit_id="+id,
		showMaxButton:true,
		title: '客户信息编辑', width: 800, height:400,
		ondestroy: function (action) {
			if(action=='save'){ //重新加载
				grid.reload();
			}
		}
	});		
}
</script>

