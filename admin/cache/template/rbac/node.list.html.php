__layout|public:mini_layout|layout__
<div class="mini-splitter" style="width:100%;height:100%;">
    <div size="240" showCollapseButton="true">
        <div class="mini-toolbar" style="padding:2px;border-top:0;border-left:0;border-right:0;">                
            <span style="padding-left:5px;">名称：</span>
            <input class="mini-textbox" onkeyup="onKeyEnterFilter" name="treekey"/>
            <a class="mini-button" iconCls="icon-search" plain="true" onclick="treeFilter()">查找</a>                  
        </div>
        <div class="mini-fit">
            <ul id="nodeTree" class="mini-tree" url="/rbac/node/init?action=menu" style="width:100%;" expandOnLoad="true"
                showTreeIcon="true" textField="title" idField="id" parentField="pid" resultAsTree="false">        
            </ul>
        </div>
    </div>
    <div showCollapseButton="true">
        <div class="mini-toolbar" style="padding:2px;border-top:0;border-left:0;border-right:0;">                
            <table style="width:100%;">
                <tr>
                    <td style="white-space:nowrap;">
                        <a class="mini-button" iconCls="icon-add" plain="true" onclick="addRow()">新增</a>
                        <a class="mini-button" iconCls="icon-remove" plain="true" onclick="removeRow()">删除</a>     
                        <a class="mini-button" iconCls="icon-node" plain="true" onclick="switchChild()" id="iconSwitch">【查看】子级</a>
                        <span class="separator"></span>             
                        <a class="mini-button" iconCls="icon-save" plain="true" onclick="saveData()">保存</a>
                          <span class="separator"></span>
                          <a class="mini-button" iconCls="icon-add" plain="true" onclick="addChk()">新增该节点审核流</a>    
                    </td>
                    <td style="float:right;">
                        <a class="mini-button" iconCls="icon-reload" plain="true" onclick="updateAccess()">更新栏目权限</a>    
                        <span class="separator"></span>             
                        <input name="keyword" class="mini-textbox" emptyText="请输入关键词" style="width:140px;" onenter="onKeyEnterGridSearch"/>   
                        <a class="mini-button" iconCls="icon-search" onclick="gridFilter()">查询</a>
                        <span id="searchMsg"></span>
                    </td>
                </tr>
            </table>           
        </div>
        <div class="mini-fit" >
            <div id="pCellList" class="mini-datagrid" style="width:100%;height:100%;"  pageSize=10000
                borderStyle="border:0;"  url="/rbac/node/init?action=grid" onrowdblclick="switchChild" showFooter=false
                showFilterRow="true" allowCellSelect="true" allowCellEdit="true" idField="id" multiSelect="true"
                >
                <div property="columns">    
                	<div type="checkcolumn"></div>
      				<div field="id" width="50" headerAlign="center" align="center">ID</div>
                    <div field="title" width="120" headerAlign="center">名称                        
                        <input property="editor" class="mini-textbox" style="width:100%;" vtype="english"/>
                    </div>                
                    <div field="name" width="120" headerAlign="center" allowSort="true">模块名
                        <input property="editor" class="mini-textbox" style="width:100%;"/>
                    </div>      
                    <div field="remark" width="150" headerAlign="center">备注
                        <input property="editor" class="mini-textarea" style="width:100%;" minHeight="50"/>
                    </div>
                    <div field="sort_order" width="50" allowSort="true">排序
                        <input property="editor" class="mini-spinner" minValue="0" maxValue="1000" value="25" style="width:100%;"/>
                    </div>
                    <div field="status" width="50" allowSort="true" renderer="onStatusRenderer" align="center" headerAlign="center">状态
                        <input property="editor" class="mini-combobox" style="width:100%;" data="Status"/>
                    </div>    
                </div>
            </div>
			<div id="cCellList" class="mini-datagrid" style="width:100%;height:100%; display:none;" pageSize=10000
                borderStyle="border:0;"  url="/rbac/node/init?action=grid" showFooter=false
                showFilterRow="true" allowCellSelect="true" allowCellEdit="true" idField="id"  multiSelect="true"
            >
                <div property="columns">    
                 	<div type="checkcolumn"></div>
      				<div field="id" width="50" headerAlign="center" align="center">ID</div>
                    <div field="title" width="120" headerAlign="center">名称                        
                        <input property="editor" class="mini-textbox" style="width:100%;" vtype="english"/>
                    </div>                
                    <div field="name" width="120" headerAlign="center" allowSort="true">模块名
                        <input property="editor" class="mini-textbox" style="width:100%;"/>
                    </div>      
                    <div field="remark" width="150" headerAlign="center">备注
                        <input property="editor" class="mini-textarea" style="width:100%;" minHeight="50"/>
                    </div>
                    <div field="sort_order" width="50" allowSort="true">排序
                        <input property="editor" class="mini-spinner" minValue="0" maxValue="1000" value="25" style="width:100%;"/>
                    </div>
                    <div field="status" width="50" allowSort="true" renderer="onStatusRenderer" align="center" headerAlign="center">状态
                        <input property="editor" class="mini-combobox" style="width:100%;" data="Status"/>
                    </div>    
                </div>
            </div>              
        </div>
    </div>        
</div>
<script type="text/javascript">
mini.parse();

var tree = mini.get("nodeTree");
var pGrid = mini.get("pCellList");
var cGrid = mini.get("cCellList");
var isChild=0; //是否操作的是子Grid
//左侧树状
tree.on("nodeselect", function (e) {
	pGrid.show();
	pGrid.load({pid:e.node.id});
	cGrid.hide();
	cGrid.setData([]); //清空数据
	isChild=0;
});
		
var Status = [{id:0, text: '禁用'}, {id: 1, text: '正常'}];
//格式化状态
function onStatusRenderer(e) {
	for (var i = 0, l = Status.length; i < l; i++) {
		var g = Status[i];
		if (g.id == e.value) return g.text;
	}
	return "";
}
//显示下一级
function switchChild() {	
	var record = pGrid.getSelected();
	if(record.id<1) return false; //新增的排除
	if (isChild==0 && record) {
		isChild=1; //操作的是子类
		cGrid.load({pid: record.id});
		cGrid.show();
		pGrid.hide();
		$("#iconSwitch span").text('【返回】父级');
	}else{
		pGrid.show();
		cGrid.hide();
		cGrid.setData([]);
		isChild=0;
		$("#iconSwitch span").text('【查看】子级');
	}
	return;
}
//增加一行
function addRow() {  
	var newRow = {name:"",title:"",remark:"",sort_order:0,status:1},obj=null,node=null;
	if(isChild>0){
		node = pGrid.getSelected();
		obj=cGrid;	
	}else{
		node = tree.getSelectedNode();
		obj=pGrid;	
	}
	if(node) {
		newRow.id = 0;
		newRow.pid = node.id;
		newRow.level = parseInt(node.level)+1;
		newRow.ntype = node.ntype;
		obj.addRow(newRow,0);
	}
}

//增加一行
function addChk() {
	var rows = pGrid.getSelecteds();  
	if(rows.length>1){
		alert('审核流程每次只能选择一个节点进行设置');
		return false;
	}else{
		mini.open({
			url: '/rbac/node/admChk?id='+rows[0].id,
			showMaxButton:true,
			title: "设置节点审核流程", width: 215, height:210
		});
	}
	
}


//保存数据
function saveData() {
	var data=null,obj=null;
	if(isChild){
		obj=cGrid;
	}else{
		obj=pGrid;
	}
    data = obj.getChanges();
	if(data.length<1){
		return;
	}
    var json = mini.encode(data);
    obj.loading("保存中，请稍后......");
	var callback=function(data){
		obj.unmask();
		if(data.err!='0'){
			alert(data.msg);
			return false;
		}else{
			obj.reload();
		}
	}
	utils.ajax('/rbac/node/save',{data:json},callback,"POST","json");
}

//删除数据
function removeRow() {
	var obj=null;
	if(isChild){
		obj=cGrid;
	}else{
		obj=pGrid;
	}
    var rows = obj.getSelecteds(),_ids=new Array();
	if(rows.length<1) return;
	for(var i=0;i<rows.length;i++){
		var _id=parseInt(rows[i].id);
		if(_id<1){
			obj.removeRow(rows[i],false);
		}else{
			_ids[i]=_id;
		}
	}
	var ids=_ids.join(',');
	if(ids=='') return;
	mini.confirm("确定删除节点？", "提示",	function(action){
			if(action!='ok') return;
			var callback=function(data){
				if(data.err!='0'){
					alert(data.msg)
					return false;
				}else{
					obj.reload();
				}
			}
			utils.ajax('/rbac/node/remove',{ids:ids},callback,"POST","json");
		}
	);
}
function onKeyEnterGridSearch(e) {
	gridFilter();
}
function gridFilter(){
	var key = mini.getbyName("keyword").getValue();
	var obj=null,node=null;
	if(isChild){
		obj=cGrid;
		node = pGrid.getSelected();
	}else{
		obj=pGrid;
		node = tree.getSelectedNode();
	}
	obj.load({pid: node.id, key: key});
}

//
function onKeyEnterFilter(e) {
	treeFilter();
}
function treeFilter(){
	var key = mini.getbyName("treekey").getValue();
	if(key == "") {
		tree.clearFilter();
	} else {
		key = key.toLowerCase();
		tree.filter(function (node) {
			var name = node.name ? node.name.toLowerCase() : "";
			var title = node.title ? node.title.toLowerCase() : "";
			if (name.indexOf(key) != -1 || title.indexOf(key) != -1) {
				return true;
			}
		});
	}
}
function updateAccess(){
	isChild=0; //强制为父类
	pGrid.show();
	cGrid.hide();
	pGrid.loading("保存中，请稍后......");
	var callback=function(data){
		pGrid.unmask();
		if(data.err!='0'){
			alert(data.msg);
			return false;
		}else{
			pGrid.reload();
		}
	}
	utils.ajax('/rbac/node/updateMenuAccess',{data:''},callback,"POST","json");
}
</script>