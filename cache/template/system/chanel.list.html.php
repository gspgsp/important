__layout|public:mini_layout|layout__
<div class="mini-toolbar"  style="margin:3px 3px 0;">
    <table style="width:100%;">
        <tr>
            <td style="white-space:nowrap;">
                <a class="mini-button" iconCls="icon-addnew" plain="true" onclick="add()">新增</a>
                <a class="mini-button" iconCls="icon-remove" plain="true" onclick="remove()">删除</a>
                <span class="separator"></span>
                <a class="mini-button" iconCls="icon-save" onclick="saveData()" plain="true">保存</a> 
            </td>
            <td style="float:right;">
            
            	<form id="soform">
            	<select name="key_type">
                    <option value="name">渠道代码</option>
                    <option value="chanel_id">渠道ID</option>
                    <option value="remark">备注</option>
                    <option value="username">用户名</option>
                </select>       
                <input name="keyword" class="mini-textbox" emptyText="请输入关键词" style="width:140px;" onenter="onKeyEnter"/>   
                <a class="mini-button" iconCls="icon-search" onclick="search()">查询</a>
                <span id="searchMsg"></span>
                </form>
            </td>
        </tr>
    </table>           
</div>
<div class="mini-fit" style="padding:3px;">
  <div id="cellList" class="mini-datagrid" style="width:100%;height:100%;" 
            url="/system/chanel/init?action=grid&uid=<?php echo $this->_var['uid']; ?>"  idField="chanel_id"
            sizeList="[10,20,50,100]" pageSize="20" multiSelect="true"  onrowdblclick="onRowDblClick" showFilterRow="true" allowCellSelect="true" allowCellEdit="true"
   >
    <div property="columns">
      <div type="checkcolumn"></div>
      <div field="chanel_id" width="50" headerAlign="center" align="center">ID</div>
      <div field="name" width="120" headerAlign="center">渠道代码</div>
      <div field="username" width="120" headerAlign="center" align="center" allowSort="true">用户</div>
      <div field="status" width="60" headerAlign="center" allowSort="true" type="comboboxcolumn" align="center">状态                        
          <input property="editor" class="mini-combobox" style="width:100%;" data='[{"id":0,"name":"禁用"},{"id":1,"name":"正常"}]' textField="name"/>      
      </div>    
      <div field="remark" width="200" headerAlign="center">备注
      	 <input property="editor" class="mini-textarea" style="width:100%;" minHeight="50"/>
      </div>
      <div field="rec_count" width="60" headerAlign="center" align="center" allowSort="true">推荐数</div>
      <div field="rec_invest" width="60" headerAlign="center" align="center" allowSort="true" renderer="onLoadInvest">投资数</div>
      <div field="input_time" width="110" headerAlign="center" dateFormat="yyyy-MM-dd HH:mm" allowSort="true" align="center">创建</div>
      <div field="update_time" width="110" headerAlign="center" dateFormat="yyyy-MM-dd HH:mm" allowSort="true" align="center">更新</div>
    </div>
  </div>
</div>
<div id="admInfo" class="mini-window" title="设置渠道信息" style="width:300px;" 
    showModal="true" allowResize="true" allowDrag="true"
    >
    <div id="addForm" class="form" >
        <table style="width:100%;">
        	<input class="mini-hidden" name="chanel_id"/>
            <tr>
                <td style="width:80px;">渠道代码：</td>
                <td><input name="name" class="mini-textbox" style="width:80px" required="true"/></td>
            </tr>
            <tr>
                <td>关联用户：</td>
                <td><input name="uid" class="mini-buttonedit" onbuttonclick="usrChoose" allowInput="false"/></td>
            </tr>
            <tr>
                <td>备注：</td>
                <td><input name="remark" class="mini-textarea" style="width:180px;"/></td>
            </tr>
            <tr>
                <td>状态：</td>
                <td><input name="status" class="mini-combobox" data='[{"id":0,"name":"禁用"},{"id":1,"name":"正常"}]' textField="name" valueField="id" style="width:90px;" required="true"/>
</td>
            </tr>
          <tr>
              <td style="text-align:right;padding-top:5px;padding-right:20px;" colspan="2">
                 <a class="mini-button" iconCls="icon-save" plain="true" href="javascript:submitForm()">保存</a>
              </td>                
          </tr>
        </table>
    </div>
</div>


<script type="text/javascript">
mini.parse();
var grid = mini.get("cellList");

grid.load();

function onLoadInvest(e) {
	var record = e.record,invest=parseInt(record.rec_invest),count=parseInt(record.rec_count);
	return invest>0 && count>0 ? invest+"/"+(invest*100/count).toFixed(1) : '-';
}


function remove() {
    var rows = grid.getSelecteds(),_ids=new Array();
	if(rows.length<1) return;
	for(var i=0;i<rows.length;i++){
		var _id=parseInt(rows[i].chanel_id);
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
		utils.ajax('/system/chanel/remove',{ids:ids},callback,"POST","json");
	});
}

function onLoadStatus(e){
	var record = e.record; //record.id
	if(record.status=='1'){
		return '正常';	
	}
	return '禁用';
}


var admInfo = mini.get("admInfo");
var form = new mini.Form("#addForm");
function add() {
	form.clear();
	admInfo.show();
}
function onRowDblClick(e) {
	form.clear();
	edit();
}
function edit() {
	var row = grid.getSelected();
	if (row) {
		mini.getbyName('chanel_id').setValue(row.chanel_id);	
		mini.getbyName('uid').setValue(row.uid);	
		mini.getbyName('uid').setText(row.username);	
		mini.getbyName('name').setValue(row.name);
		mini.getbyName('status').setValue(row.status);
		mini.getbyName('remark').setValue(row.remark);
		admInfo.show();
	}
}
function usrChoose(e){
	btn=this;
    mini.open({
		url: "/system/chanelUser/init?do=search",
		title: "选择账号",
		width: 960,
		height: 450,
		ondestroy: function (action) {
			if (action == "ok") {
				var iframe = this.getIFrameEl();
				var data = iframe.contentWindow.GetData();
				if (data) {
					btn.setValue(data.uid);
					btn.setText(data.username);
				}
			}
		}
	});         
}

//提交数据
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
	utils.ajax('/system/chanel/ajaxSave',{data:json},callback,"POST","json");
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
	utils.ajax('/system/chanel/save',{data:json},callback,"POST","json");
}
</script>