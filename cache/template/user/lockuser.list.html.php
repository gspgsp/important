__layout|public:mini_layout|layout__
<div class="mini-toolbar" style="margin:3px 3px 0;">
<style>table p{padding:0; margin:0; padding-top:3px;}</style>
    <table style="width:100%;">
        <tr>
            <td style="white-space:nowrap;">
            <form id="soform">
            	<select name="sTime">
                	<option value="reg_time">注册时间</option>
                	<option value="last_login">登录时间</option>
                </select>       
            	<input name="startTime" class="mini-datepicker" style="width:100px;"/> -
            	<input name="endTime" class="mini-datepicker" style="width:100px;"/>
            	<select name="status" id="soStatus">
                	<option value="" selected="selected">状态</option>
                    <!--<?php echo $this->html_options(array('options'=>$this->_var['status'])); ?>-->
                    <option value="0">待审核</option>
                    <option value="1">正常</option>
                    <option value="2">冻结</option>
                    <option value="3">关闭</option>

                </select>
            	<select name="key_type">
                    <option value="mobile">手机号</option>
                    <option value="real_name">姓名</option>
                    <option value="u.user_id" selected="selected">会员ID</option>
                    <option value="email">Email</option>
                    <option value="chanel_name">渠道名</option>
                    <option value="reg_ip">IP</option>
                    <option value="invite_code">邀请码</option>
                </select>       
                <input name="keyword" class="mini-textbox" emptyText="请输入关键词" style="width:140px;" onenter="onKeyEnter"/>  
                <p>
                用户标签
            	<select name="user_tag" id="soTag">
                    <?php echo $this->html_options(array('options'=>$this->_var['user_tag'])); ?>
                </select>
            	<select name="chanel" id="soChanel">
                	<option value="">注册渠道</option>
                    <?php echo $this->html_options(array('options'=>$this->_var['user_chanel'])); ?>
                </select>
            	<select name="utype" id="soUtype">
                	<option value="">类型</option>
                    <?php echo $this->html_options(array('options'=>$this->_var['utype'])); ?>
                </select>
                推荐数
                <input name="beginCount" class="mini-textbox" style="width:50px;" />~~<input name="endCount" class="mini-textbox" style="width:50px;" />
                <a class="mini-button" iconCls="icon-search" onclick="search()">查询</a>
                 <span id="searchMsg"></span>
                </p>
               </form>
            </td>
            <td style="float:right;">
                <a class="mini-button" iconCls="icon-add" onclick="add(0,1)">个人</a> <a class="mini-button" iconCls="icon-add" onclick="add(0,3)">企业</a>
                <p>
                <a class="mini-button" iconCls="icon-save" plain="true" onclick="saveTags()">设置标签</a> </p>
            </td>
        </tr>
    </table>           
</div>

<div class="mini-fit" style="padding:1px 3px 3px;">
    <div id="gridCell" class="mini-datagrid" style="width:100%;height:100%;"  sizeList="[10,20,50,100]" pageSize="20"
        url="/user/user/lockUserList?action=grid&do=<?php echo $this->_var['doact']; ?><?php if ($this->_var['isSecurity'] == '1'): ?>&isSecurity=<?php echo $this->_var['isSecurity']; ?><?php endif; ?>&lock=1" idField="user_id"  onrowdblclick="onRowDblClick"
        <?php if ($this->_var['doact'] != 'search'): ?>multiSelect="true" allowCellEdit="true" allowCellSelect="true"<?php endif; ?> contextMenu="#gridMenu"  headerContextMenu="#headerMenu"
        >
        <div property="columns">    
            <?php if ($this->_var['doact'] != 'search'): ?><div type="checkcolumn"></div><?php endif; ?>
			<?php if ($this->_var['doact'] != 'search'): ?><div name="user_id" width="50" headerAlign="center" renderer="onLoadHandle">ID</div><?php endif; ?>
            <div field="mobile" width="60" headerAlign="center">手机</div>
            <div field="ref_count" width="40" headerAlign="center" allowSort="true" align="center" renderer="onLoadRec">推荐数</div>
            <div field="inviteCode" width="30" headerAlign="center">邀请码</div>
            <div field="name" width="80" headerAlign="center">名称</div>
            <div field="status" width="40" headerAlign="center" align="center">状态</div>
            <div field="user_tag" width="40" headerAlign="center" allowSort="true" type="comboboxcolumn" align="center">标签                        
            	<input property="editor" class="mini-combobox" style="width:100%;" data="user_tag" textField="name"/>      
            </div>    
            <div field="reg_time" width="90" headerAlign="center" dateFormat="yy-MM-dd HH:mm" align="center">注册</div>
            <div field="last_login" width="90" headerAlign="center" dateFormat="yy-MM-dd HH:mm" align="center">登录</div>
            <div field="reg_ip" width="80" headerAlign="center" align="center" allowSort="true">IP</div>
            <div field="visit_count" width="50" headerAlign="center" allowSort="true" align="center">登录次数</div>
            <div field="login_status" width="50" headerAlign="center" align="center">登录锁定</div>
        </div>
    </div>
    <ul id="gridMenu" class="mini-contextmenu" onbeforeopen="onBeforeOpen">              
	    <li name="edit" iconCls="icon-edit" onclick="onEdit">解除锁定</li> 
    </ul>
    <ul id="headerMenu" class="mini-contextmenu">              
        <li name="edit" iconCls="icon-edit" onclick="onEdit">解除锁定</li>          
    </ul>
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
var user_tag = <?php echo $this->_var['user_tag_config']; ?>;

var grid = mini.get("gridCell");
grid.load();

//追加查看按钮
function onLoadHandle(e) {
	var record = e.record,uid=record.user_id, s='';
	
	if(String(record.utype)=='经纪人'){
		s += '<a href="javascript:viewOwnUsers(2)">下线</a> | ';
	}
	s += '<a href="javascript:viewUinfo('+uid+')">'+uid+'</a> ';

	return s;
}

function onLoadRec(e){
	var record = e.record.ref_count, s='-';
	if(record!=='0'){
		s = '<a href="javascript:viewOwnUsers(1)"> '+record+' </a>';
	}
	return s;
}

function viewOwnUsers(atype){
	var row = grid.getSelected();
	if (row) {
		var width=900,height=500,title='用户信息';
		if(id==0){
			title='新增筹资机构'
		}
		mini.open({
			url: "/user/user/viewOwnUsers?id="+row.user_id+"&atpye="+atype,
			showMaxButton:true,
			title: title, width: width, height:height
			
		});	
	}
}

function onLoadStatus(e) {
	var record = e.record.status; //record.id
	return $("#soStatus").find("option[value="+record+"]").text();
}

function search() {
	grid.load($("#soform").serializeObject(),function(e){
		$("#searchMsg").html(e.msg);
	});
}
function onKeyEnter(e) {
	search();
}

function onRowDblClick(e) {
	<?php if ($this->_var['doact'] != 'search'): ?>
	edit();
	<?php else: ?>
	onComfirm();
	<?php endif; ?>
}
function edit() {
	var row = grid.getSelected();
	if (row) {
		add(row.user_id)
	}
}

//查看编辑用户信息
function add(id,ctype){
	var width=700,height=500,title='用户信息';
	if(id==0){
		if(ctype==1){
		title='新增个人用户';
		}else if(ctype==2){
		title='新增经纪人';
		}else if(ctype==3){
		title='新增企业用户';
		}
	}
	mini.open({
		url: "/user/user/info?id="+id+"&ctype="+ctype,
		showMaxButton:true,
		title: title, width: width, height:height,
		ondestroy: function (action) {
			if(action=='save'){ //重新加载
				grid.reload();
			}
		}
	});	
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

//行内编辑后保存数据
function saveTags() {
	var data = grid.getChanges();
	var json = mini.encode(data);
	if(json.length<10) return false;
	grid.loading("保存中，请稍后......");
	var callback=function(data){
		if(data.error>0){
			alert(data.content);
			return false;
		}else{
			grid.reload();
		}
	}
	utils.ajax('/user/user/saveTags',{data:json},callback,"POST","json");
}

</script>

<script type="text/javascript">

//grid.sortBy("loginname", "desc");

function applySort() {
	var sortField = document.getElementById("sortField").value;
	var sortOrder = document.getElementById("sortOrder").value;
	grid.sortBy(sortField, sortOrder);
}        


function onBeforeOpen(e) {
	var grid = mini.get("gridCell");
	var menu = e.sender;
		
	var row = grid.getSelected();
	var rowIndex = grid.indexOf(row);            
	if (!row) {
		e.cancel = true;
		//阻止浏览器默认右键菜单
		e.htmlEvent.preventDefault();
		return;
	}

}

function onEdit(e) {
	var row = grid.getSelected();
	if (row) {
		var ids=row.user_id;
		if(ids=='') return;
		
		mini.confirm("确定把此用户解除登录锁定？", "提示",	function(action){
				if(action!='ok') return;
				var callback=function(data){
					if(data.err!='0'){
						alert(data.msg)
						return false;
					}else{
						alert(data.msg)
						grid.reload();
					}
				}
				utils.ajax('/user/user/unlockSubmit',{ids:ids},callback,"POST","json");
			}
		);
		
	}             
}

</script>
