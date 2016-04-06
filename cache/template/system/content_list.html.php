__layout|public:mini_layout|layout__
<div class="mini-toolbar" style="margin:3px 3px 0;">
    <table style="width:100%;">
        <tr>
            <td style="white-space:nowrap;">
            <?php if ($this->_var['doact'] != 'search'): ?>
                <a class="mini-button" iconCls="icon-add" plain="true" onclick="add(0)">新增</a>
                <a class="mini-button" iconCls="icon-edit" plain="true" onclick="edit()">编辑</a>
                <a class="mini-button" iconCls="icon-remove" plain="true" onclick="removeRow()">删除</a>     
                <span class="separator"></span>             
                <a class="mini-button" iconCls="icon-save" plain="true" onclick="saveData()">保存</a> 
            </td>
            <td style="float:right;">
            <?php endif; ?>
            <form id="soform" style="<?php if ($this->_var['cate_id']): ?>display:none;<?php endif; ?>">
            	<?php if ($this->_var['cate']): ?><select name="cate_id" id="soCate">
                    <option value="">=分类=</option>
                    <?php $_from = $this->_var['cate']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->_push_vars('k', 'v');if (count($_from)):
    foreach ($_from AS $this->_var['k'] => $this->_var['v']):
?><option value="<?php echo $this->_var['k']; ?>" <?php if ($this->_var['k'] == $this->_var['cate_id']): ?>selected="selected"<?php endif; ?>><?php echo $this->_var['v']; ?></option><?php endforeach; endif; unset($_from); ?><?php $this->_pop_vars();; ?>
                </select><?php endif; ?>
            	<?php if ($this->_var['brand']): ?><select name="brand_id" id="soBrand">
                    <option value="">=品牌=</option>
                    <?php $_from = $this->_var['brand']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->_push_vars('k', 'v');if (count($_from)):
    foreach ($_from AS $this->_var['k'] => $this->_var['v']):
?><option value="<?php echo $this->_var['k']; ?>"><?php echo $this->_var['v']; ?></option><?php endforeach; endif; unset($_from); ?><?php $this->_pop_vars();; ?>
                </select><?php endif; ?>
                <select name="status">
                    <option value="">=状态=</option>
                    <option value="2">可用</option>
                    <option value="1">禁用</option>
                </select>
            	<select name="key_type">
                    <option value="title">名称</option>
                    <option value="sn">编号</option>
                    <option value="id">ID</option>
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
        url="/system/content/<?php echo $this->_var['action']; ?>?action=grid&do=<?php echo $this->_var['doact']; ?>"
        showFilterRow="true" idField="id" multiSelect="true"
        <?php if ($this->_var['doact'] != 'search'): ?>onrowdblclick="onRowDblClick" allowCellSelect="true" allowCellEdit="true"<?php endif; ?>
        >
        <div property="columns">    
            <div type="checkcolumn"></div>
      		<div field="id" width="50" headerAlign="center" align="center" allowSort="true">ID</div>
            <div field="title" width="120" headerAlign="center">名称                        
                <input property="editor" class="mini-textbox" style="width:100%;"/>
            </div>
            <?php if ($this->_var['cate']): ?><div field="cate_id" width="100" headerAlign="center" renderer="onLoadCate">分类</div><?php endif; ?>       
            <div field="sn" width="80" headerAlign="center">编号</div>                
            <div field="sort_order" width="60" headerAlign="center" allowSort="true" align="center">排序
                <input property="editor" class="mini-spinner" minValue="0" maxValue="1000" style="width:100%;"/>
            </div>      
            <div field="status" width="60" headerAlign="center" type="comboboxcolumn" autoShowPopup="true" align="center">状态
                <input property="editor" class="mini-combobox" style="width:100%;" data="dStatus" />     
            </div>
            <div field="update_time" width="110" headerAlign="center" dateFormat="yyyy-MM-dd HH:mm" allowSort="true" align="center">更新</div>
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
var dStatus=[{id: 1, text: '可用'}, {id: 0, text: '禁用'}];

function onLoadCate(e) {
	var record = e.record.cate_id; //record.id
	return $("#soCate").find("option[value="+record+"]").text();
}
function onLoadBrand(e) {
	var record = e.record.brand_id; //record.id
	return $("#soBrand").find("option[value="+record+"]").text();
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
function edit() {
	var row = grid.getSelected();
	if (row) {
		add(row.id)
	}
}
//删除数据
function removeRow() {
    var rows = grid.getSelecteds(),_ids=new Array();
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
			utils.ajax('/system/content/<?php echo $this->_var['action']; ?>?action=remove',{ids:ids},callback,"POST","json");
		}
	);
}


//查看编辑卡片信息
function add(id){
	mini.open({
		url: "/system/content/<?php echo $this->_var['action']; ?>?action=info&cate_id=<?php echo $this->_var['cate_id']; ?>&id="+id,
		showMaxButton:true,
		title: '信息编辑', width: 850, height:550,
		ondestroy: function (action) {
			if(action=='save'){ //重新加载
				grid.reload();
			}
		}
	});		
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
	utils.ajax('/system/content/<?php echo $this->_var['action']; ?>?action=save',{data:json},callback,"POST","json");
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
	var row = grid.getSelecteds();
	return row;
}
</script>
