__layout|public:mini_layout|layout__
<div class="mini-toolbar" style="margin:3px 3px 0;">
	<table style="width:100%;">
		<tr>
			<td style="white-space:nowrap;">

				<!-- <a class="mini-button" iconCls="icon-no" plain="true" onclick="change_status()">禁用联系人</a> -->
				<a class="mini-button" iconCls="icon-remove" plain="true" onclick="remove()">删除</a>
				<span class="separator"></span>
				<a class="mini-button" class="output" iconCls="icon-downgrade" plain="true" onclick="outputExcel()">excel导出</a>
				<span class="separator"></span>
				<a class="mini-button" iconCls="icon-save" onclick="saveData()" plain="true">保存</a>
				<span class="separator"></span>
				<a class="mini-button" iconCls="icon-add" plain="true" onclick="send()">发送短信</a>
			</td>
			<td style="float:right;">
			<form id="soform" method="get" action="/product/interface/download">
				<input class="mini-hidden" name="ctype"  value="<?php echo $this->_var['ctype']; ?>"/>
				创建时间
				<input name="startTime" class="mini-datepicker" style="width:100px;"/> -
				<input name="endTime" class="mini-datepicker" style="width:100px;"/>
				<select name="type" id="type">
					<option value="">=供求=</option>
					<option value="1">采购</option>
					<option value="2">报价</option>
				</select>
				<select name="is_erp" id="">
					<option value="">=资源库=</option>
					<option value="1">是</option>
					<option value="2">否</option>
				</select>
				<select name="key_type">
					<option value="model" selected="selected">牌号</option>
					<option value="store_house">仓库</option>
					<option value="c_id">客户姓名</option>
					<option value="content">文本内容</option>
				</select>
				<input name="keyword" class="mini-textbox" emptyText="请输入关键词" style="width:140px;" onenter="onKeyEnter"/>    
				<a class="mini-button" iconCls="icon-search" onclick="search()">查询</a>
				<span id="searchMsg"></span></form>
			</td>
		</tr>
	</table>
</div>

<div class="mini-fit" style="padding:1px 3px 3px;">
	<div id="gridCell" class="mini-datagrid" style="width:100%;height:100%;"  sizeList="[10,20,50,100]" pageSize="20"
		url="/product/interface/init?action=grid&do=<?php echo $this->_var['slt']; ?>&doact=<?php echo $this->_var['doact']; ?>&id=<?php echo $this->_var['id']; ?>" onrowdblclick="onRowDblClick"
		showFilterRow="true" idField="id" multiSelect="true" allowCellSelect="true" allowCellEdit="true"
		>
		<div property="columns">
			<div type="checkcolumn"></div>

			<div field="c_name" width="50" headerAlign="center" align="center" allowSort="true" renderer="onLoadHandle">客户名称</div>
			<div field="name" width="50" headerAlign="center" align="center" allowSort="true" renderer="onLoad">联系人</div>
			<div field="mobile" width="50" headerAlign="center" align="center" allowSort="true">联系电话</div>
			<div field="type" width="50" headerAlign="center" align="center" allowSort="true">供求</div>
			<div field="f_name" width="50" headerAlign="center" align="center" allowSort="true">厂家</div>
			<div field="model" width="50" headerAlign="center" align="center" allowSort="true">牌号</div>
			<div field="store_house" width="50" headerAlign="center" align="center" allowSort="true">仓库</div>
			<div field="unit_price" width="50" headerAlign="center" align="center" allowSort="true">价格</div>
			<div field="content" width="200" headerAlign="center" allowSort="true" align="center">塑料内容</div>
			<div field="msg_count" width="40" headerAlign="center" allowSort="true" align="center" renderer="send_sum">已发短信数</div>
			<div field="is_erp" width="50" headerAlign="center" allowSort="true" align="center">资源库</div>
			<div field="remark" width="100" headerAlign="center" allowSort="true" align="center">备注<span style="color:red">*</span>
			<input property="editor" class="mini-textarea" style="width:100%;" minHeight="50"/>
			</div>
			<div field="input_time" width="50" headerAlign="center" dateFormat="yy-MM-dd HH:mm" allowSort="true" align="center">创建时间</div>
			<div field="update_time" width="50" headerAlign="center" dateFormat="yy-MM-dd HH:mm" allowSort="true" align="center">更新时间</div>
			<div field="customer_manager" width="45" headerAlign="center" align="center">交易员</div>
			<!-- <div field="status" width="30" headerAlign="center" allowSort="true" align="center" renderer="LoadStatus">状态</div> -->
			<div action='do' width="40" renderer="normalize">操作</div>
		</div>
	</div>
</div>
<script type="text/javascript">
mini.parse();
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

function viewhander(e){
	var record = e.record,id=record.id, supply_count=record.supply_count, status=record.status, s='';
	if(status==1){
		s +='&nbsp;<a href="javascript:chk('+id+')">审</a>';
	}
	return s;
}

//弹出审核窗口
function chkOrder(id){
	mini.open({
		url:"/product/saleBuy/chk?id="+id,
		showMaxButton:true,
		title:'报价单审核',width:750,height:630,
		ondestroy: function(action){
			if(action=='save'){ //重新加载
				grid.reload();
			}
		}
	});
}
//编辑联系人
function onLoad(e) {
	var record = e.record,uid=record.user_id, name=record.name, s='';
	if(uid>0){
		s += '<a href="javascript:viewContactInfo('+uid+')">'+name+'</a> ';
	}
	return s;
}
//发送短信总条数
function send_sum(e) {
	var record = e.record,uid=record.user_id, msg_count=record.msg_count, s='-';
	if(msg_count>0){
		return '<a href="javascript:msgView('+uid+')">'+msg_count+'</a> ';
	}
	return s;
}
//弹出短信发送记录
function msgView(uid){
	mini.open({
		url:"/report/logsms/init?uid="+uid,
		showMaxButton:true,
		title:'发送短信历史记录',width:1250,height:630,
		ondestroy: function(action){
			if(action=='save'){ //重新加载
				grid.reload();
			}
		}
	});
}
//弹出阶段审核窗口
function chk(id){
	mini.open({
		url:"/product/purchase/chkpage?id="+id,
		showMaxButton:true,
		title:'审核',width:190,height:210,
		ondestroy: function(action){
			if(action=='save'){ //重新加载
				grid.reload();
			}
		}
	});
}
//规范化数据
function normalize(e){
	var record = e.record,id=record.id, supply_count=record.supply_count, status=record.status, s='';
	s +='&nbsp;<a href="javascript:alze('+id+')">规范化</a>';
	return s;
}
//规范化展示窗口
function alze(id){
	mini.open({
		url:"/product/interface/shows?id="+id,
		showMaxButton:true,
		title:'规范化',width:690,height:510,
		ondestroy: function(action){
			if(action=='save'){ //重新加载
				grid.reload();
			}
		}
	});
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
	utils.ajax('/product/interface/remove',{ids:ids},callback,"POST","json");
  });
}
//发送短信
function send(){
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
	console.log(ids);
	if(ids=='') return;
	mini.open({
	url: "/product/interface/send?ids="+ids,
	title: '发送短信', width: 550, height:400,
	ondestroy: function (action) {
	  if(action=='save'){ //重新加载
		grid.reload();
	  }
	}
	});
}
//禁用公司相关联系人
function change_status() {
	var rows = grid.getSelecteds(),_uids=new Array();
	if(rows.length<1) return;
		for(var i=0;i<rows.length;i++){
			var _uid=parseInt(rows[i].user_id);
			if(_uid<1){
				grid.removeRow(rows[i],false);
			}else{
				_uids.push(_uid);
			}
		}
	var uids=_uids.join(',');
	if(uids=='') return;
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
	utils.ajax('/product/interface/defriend',{uids:uids},callback,"POST","json");
	});
}
//编辑联系人
function onLoadHandle(e) {
	var record = e.record,uid=record.c_id, c_name=record.c_name, s='';
	if(uid>0){
		s += '<a href="javascript:viewCinfo('+uid+')">'+c_name+'</a> ';
	}
	return s;
}

//导出excel
function outputExcel(){
	$("#soform").submit();
}

//行内编辑后保存数据
function saveData() {
	var data = grid.getChanges();
	var json = mini.encode(data);
	if(json.length<10) return false;

	grid.loading("保存中，请稍后......");
	var callback=function(data){
	grid.unmask();
	if(data.err=='0'){
	  grid.reload();
	}else{
	  alert(data.msg)
	  return false;
	}
  }
	utils.ajax('/product/interface/save',{data:json},callback,"POST","json");
}

//筛选数据
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
function GetData() {
	var row = grid.getSelected();
	return row;
}
</script>