__layout|public:mini_layout|layout__
<div class="mini-toolbar" style="margin:3px 3px 0;">
    <table style="width:100%;">
        <tr>
            <td style="white-space:nowrap;">
            	<form id="soform">
            	<select name="stype" id="soType">
                	<option value="">媒体类型</option>
                    <?php echo $this->html_options(array('options'=>$this->_var['site_type'])); ?>
                </select>
            	<select name="chk_status" id="soStatus">
                	<option value="">审核状态</option>
                    <option value="1">待审核</option>
                    <option value="2">已审核</option>
                    <option value="3">未通过</option>
                </select>
            	<select name="key_type">
                    <option value="c.name">媒体名</option>
                    <option value="c.siteurl">媒体地址</option>
                    <option value="u.username">用户名</option>
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
  <div id="gridCell" class="mini-datagrid" style="width:100%;height:100%;" 
            url="/system/chanelSite/init?action=grid&uid=<?php echo $this->_var['uid']; ?>"  idField="uid"
            sizeList="[10,20,50,100]" pageSize="20" onrowdblclick="onRowDblClick"
   >
    <div property="columns">
      <div type="indexcolumn" headerAlign="center">序号</div>
      <div name="action" width="30" headerAlign="center" align="right" renderer="onLoadHandle">操作</div>
      <div field="username" width="50" align="center" headerAlign="center">用户ID</div>
	  <div field="sitename" width="100" align="center" headerAlign="center">媒体名</div>
      <div field="stype" width="60" align="center" headerAlign="center" renderer="onLoadType">类型</div>
      <div field="siteurl" width="120" align="center" headerAlign="center">媒体地址</div>
      <div field="chk_status" width="50" headerAlign="center" align="center" renderer="onLoadStatus">状态</div>
      <div field="chanel_id" width="50" headerAlign="center" align="center">渠道ID</div>
      <div field="input_time" width="80" headerAlign="center" dateFormat="yy-MM-dd HH:mm" align="center">申请时间</div>
      <div field="chk_time" width="80" headerAlign="center" dateFormat="yy-MM-dd HH:mm" align="center">审核时间</div>
      <div field="user_ip" width="80" headerAlign="center">用户IP</div>
    </div>
  </div>
</div>

<script type="text/javascript">
mini.parse();
var grid = mini.get("gridCell");
grid.load();

//追加查看按钮
function onLoadHandle(e) {
	var record = e.record, s='';
	s += ' <a href="javascript:siteInfo()">查看</a>';
	return s;
}
function onRowDblClick(e) {
	siteInfo();
}
function siteInfo(){
	var row = grid.getSelected();
	if (row) {
		var width=600,height=400,title='媒体信息审核';
		mini.open({
			url: "/system/chanelSite/info?id="+row.id,
			showMaxButton:true,
			title: title, width: width, height:height,
			ondestroy: function (action) {
				if(action=='save'){ //重新加载
					grid.reload();
				}
			}
		});	
	}
}


//查看类型
function onLoadType(e) {
	var record = e.record.stype; //record.id
	return $("#soType").find("option[value="+record+"]").text();
}
//查看状态
function onLoadStatus(e) {
	var record = e.record.chk_status; //record.id
	return $("#soStatus").find("option[value="+(parseInt(record)+1)+"]").text();
}
</script>
<?php echo $this->fetch('admin/account/js.html'); ?>