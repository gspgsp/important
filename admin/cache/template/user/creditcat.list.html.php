__layout|public:mini_layout|layout__
<div class="mini-toolbar" style="margin:3px 3px 0;">
	<table style="width:100%;">
		<tr>
		<?php if ($this->_var['ischecked'] != 'checked'): ?>
			<td style="white-space:nowrap;">
			<?php if ($this->_var['doact'] != 'check'): ?>
				<a class="mini-button" iconCls="icon-add" plain="true" onclick="add()">新增</a>
				<span class="separator"></span>
				<a class="mini-button" iconCls="icon-edit" plain="true" onclick="onEdit()">编辑</a>
				<span class="separator"></span>
				<a class="mini-button" iconCls="icon-remove" plain="true" onclick="remove()">删除</a>
			<?php endif; ?>	
			</td>
		<?php endif; ?>
			<td style="float:right;">
			<form id="soform">
                            <span id="searchMsg" style="margin-left:30px;float:left;"></span>&nbsp;&nbsp;&nbsp;&nbsp;
				<select name="key_type">
					<option value="model">信用分类名称</option>
				</select>
				<input name="keyword" class="mini-textbox" emptyText="" style="width:140px;" onenter="onKeyEnter"/>
				<a class="mini-button" iconCls="icon-search" onclick="search()">查询</a>
				</form>
			</td>
		</tr>
	</table>
</div>
<div class="mini-fit" style="padding:1px 3px 3px;">
	<div id="gridCell" class="mini-datagrid" style="width:100%;height:100%;"url="/user/creditcat/init?action=grid&do=<?php echo $this->_var['doact']; ?>&ischecked=<?php echo $this->_var['ischecked']; ?>"  idField="id" 
	sizeList="[10,20,50,100]" pageSize="20" <?php if ($this->_var['ischecked'] == 'checked'): ?>multiSelect="false"<?php endif; ?> multiSelect="true"  onrowdblclick="onRowDblClick" showFilterRow="true" allowCellSelect="true" allowCellEdit="true">
		<div property="columns">
			<div type="checkcolumn"></div>
			<div type="indexcolumn" headerAlign="center" align="center">ID</div>
			<div field="id" width="0" headerAlign="center" align="center" allowSort="true">ID</div>
			<div field="catname" width="120" headerAlign="center" align="center">信用分类名称</div>
			<div field="catgrade" width="60" headerAlign="center" allowSort="true" align="center">信用分类分数</div>
			<div field="remark" width="200" headerAlign="center" allowSort="true" align="center">备注</div>
			<div field="input_time" width="110" headerAlign="center" dateFormat="yyyy-MM-dd HH:mm" allowSort="true" align="center">创建时间</div>
			<div field="input_admin" width="50" headerAlign="center" align="center">创建者</div>
			<div field="update_time" width="110" headerAlign="center" dateFormat="yyyy-MM-dd HH:mm" allowSort="true" align="center">更新</div>
			<div field="update_admin" width="50" headerAlign="center" align="center">修改者</div>
			 <div name="action" width="80" headerAlign="center" align="center" renderer="onLoadHandle" cellStyle="padding:0;">状态</div>			
		</div>
	</div>
</div>
<div id="admInfo" class="mini-window" title="信用分类添加" style="width:350px;" 
	showModal="true" allowResize="true" allowDrag="true"
	>
	<div id="addForm" class="form" >
		<table style="width:100%;">
			<input class="mini-hidden" name="id"/>
           <tr>
				<td>信用分类名称</td>
				<td><input name="catname" class="mini-textbox" style="width:150px" required="true"/></td>
			</tr>
			<tr>
				<td>信用分类分数</td>
				<td><input name="catgrade" class="mini-textbox" style="width:100px" required="true"/></td>
			</tr>			
			<tr>
				<td>备注</td>
				<td><input name="remark" class="mini-textarea" style="width:200px" required="false"/></td>
			</tr>
			<tr>
			<td style="text-align:right;padding-top:5px;padding-right:20px;" colspan="2">
				<a class="mini-button" iconCls="icon-save" plain="true" href="javascript:submitForm()">保存</a>
			</td>
			</tr>
		</table>
	</div>
</div>
<div id="checkInfo" class="mini-window" title="增加产品信息" style="width:300px;" showModal="true" allowResize="true" allowDrag="true">
	<div id="replaceForm" class="form" >
		<table style="width:100%;">
			<input class="mini-hidden" name="id"/>
			<tr>
				<td>更换已审核的产品</td>
				<td>
					<input name="id" class="mini-buttonedit" onbuttonclick="checkedProduct" valueField="id"  value="" allowInput="false"  style="width:170px" id="id"/>
					<input id="p_id" class="mini-hidden" value="">
				</td>
			</tr>
			<tr>
			<td style="text-align:right;padding-top:5px;padding-right:20px;" colspan="2">
				<a class="mini-button" iconCls="icon-save" plain="true" href="javascript:submitPro()">保存</a>
			</td>
			</tr>
		</table>
	</div>
</div>
<?php if ($this->_var['ischecked'] == 'checked'): ?>
	<div class="mini-toolbar" style="text-align:center;padding-top:8px;padding-bottom:8px; border:1px solid #000;" borderStyle="border:0;">
			<a class="mini-button" style="width:60px;" onClick="onComfirm()">确定</a>
			<span style="display:inline-block;width:25px;"></span>
			<a class="mini-button" style="width:60px;" onClick="onCancel()">取消</a>
	</div>
<?php endif; ?>
<script src="__JS__/jquery/jquery.upload.js" type="text/javascript"></script>
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
//弹出增加form表单
var admInfo = mini.get("admInfo");
var form = new mini.Form("#addForm");
function add() {
	form.clear();
	admInfo.show();
}
//增加后提交数据(保存)
function submitForm() {
var form2 = new mini.Form("#admInfo");
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
	  grid.reload();
          search();
	  admInfo.hide();
	}
  }
  utils.ajax('/user/creditcat/ajaxSave',{data:json},callback,"POST","json");
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
			if(data.result){
				width=300;
				title="操作完成";
				iconCls = 'mini-messagebox-warning';
				html = '以下分类存在指标无法删除,【ID】：';
				$.each(data.result,function(k,v){
					html += k+" &nbsp;";
				});
				html+="";
			}
			mini.showMessageBox({
				showHeader: false,
				width: width,
				title: title,
				buttons: ["ok"],
				html: html,
				iconCls: iconCls
			});
	}else{
		alert(data.msg)
		return false;
	}
	}
	utils.ajax('/user/creditcat/remove',{ids:ids},callback,"POST","json");
  });
}
//双击弹出
function onRowDblClick(e) {
	var record = e.record, status=record.status;
	onEdit();

}

function onEdit(e) {
	var row = grid.getSelected();
	if (row) {
		var width=450,height=370,title='分类信息';
		urlStr="/user/creditcat/edit?id="+row.id;
		mini.open({
			url: urlStr,
			title: title,
			width: width,
			height:height,
			ondestroy: function (action) {
				if(action=='save'){ //重新加载
					grid.reload();
					search();
				}
			}
		});
	}
}

//追加操作按钮
function onLoadHandle(e) {
  var record = e.record, state = record.status, s='',tag='',changeid = record.id;
	if(state==2){
		s += "<a href='javascript:changeState("+changeid+")'>禁用</a>";
	}else{
		s +="<a href='javascript:changeState("+changeid+")'>正常</a>";
	}
  return s;
}

//切换状态
function changeState(changeid,status){
	$.ajax({
		type:"post",
		url:"/user/creditcat/changeSave",
		data:{changeid:changeid},
		dataType:"json",
		success:function(data){
			if(data.err=='0'){
			  grid.reload();
			  search();
			}else{
			  alert(data.msg)
			  return false;
			}
		}
	})
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


jQuery.extend({
handleError: function( s, xhr, status, e ) {
// If a local callback was specified, fire it
if ( s.error )
s.error( xhr, status, e );
// If we have some XML response text (e.g. from an AJAX call) then log it in the console
else if(xhr.responseText)
console.log(xhr.responseText);
}
});

</script>