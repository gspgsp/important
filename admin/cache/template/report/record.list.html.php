__layout|public:mini_layout|layout__
<div class="mini-toolbar" style="margin:3px 3px 0;">
  <table style="width:100%;">
    <tr>
      <td style="white-space:nowrap;"><form id="soform">
          <select name="operation_type">
            <option value="" selected="selected">=操作类型=</option>

        <?php echo $this->html_options(array('options'=>$this->_var['action_type'])); ?>

          </select>
          <span>请求时间</span>
          <input name="startTime" class="mini-datepicker" style="width:100px;"/>
          -
          <input name="endTime" class="mini-datepicker" style="width:100px;"/>
          <input name="c_name" class="mini-textbox" emptyText="请输入公司名字" style="width:140px;" onenter="onKeyEnter"/>
          <a class="mini-button" iconCls="icon-search" onclick="search()">查询</a> <span id="searchMsg"></span>
        </form></td>
    </tr>
  </table>
</div>
<div class="mini-fit" style="padding:1px 3px 3px;">
  <div id="gridCell" class="mini-datagrid" style="width:100%;height:100%;"  sizeList="[10,20,50,100]" pageSize="20"
        url="/report/record/init?action=grid" showFilterRow="true" idField="id">
    <div property="columns">
      <div field="id" width="40" headerAlign="center" align="center">ID</div>
      <div field="c_id" width="50" headerAlign="center" allowSort="true" align="center" renderer="viewCustomer">客户ID</div>
      <div field="action_type" width="100" headerAlign="center" allowSort="true" align="center">操作类型</div>
      <div field="old_value" width="120" headerAlign="center" align="center">操作之前</div>
      <div field="new_value" width="120" headerAlign="center"align="center">操作之后</div>
      <div field="success" width="50" headerAlign="center" allowSort="true" align="center">是否成功</div>
      <!-- <div field="channel" width="50" headerAlign="center" allowSort="true" align="center">操作渠道</div> -->
      <div field="input_time" width="70" headerAlign="center" dateFormat="yyyy-MM-dd HH:mm:ss" allowSort="true" align="center">请求时间</div>
      <div field="operator" width="60" headerAlign="center" align="center">操作员</div>
      <div field="user_ip" width="60" headerAlign="center" allowSort="true" align="center">用户IP</div>
      <div field="description" width="220" headerAlign="center" allowSort="true">操作描述</div>
    </div>
  </div>
</div>
<script type="text/javascript">
mini.parse();
var grid = mini.get("gridCell");
grid.load();

//查看公司信息
  function viewCustomer(e) {
    var record = e.record,c_id=record.c_id, c_name=record.c_name, s='';
    s += '<a href="javascript:viewCinfo('+c_id+')">'+c_id+'</a> ';
    return s;
  }

//追加查看按钮
function onLoadHandle(e) {
    var record = e.record, s='';
    if(String(record.chk_status)=='验证中'){
        s += '<a href="javascript:view()">审核验证</a> ';
    }
    return s;
}

//查看流水:打开新窗口
function view(){
    var row = grid.getSelected();
    var ids = row.bank_id;
    var uid = row.user_id;

    var rows = grid.getSelecteds(),_ids=new Array();
    if(rows.length<1) return;
    for(var i=0;i<rows.length;i++){
        _ids[i]=rows[i].id;
    }
    var ids=_ids.join(',');
    if(ids=='') return;

    mini.confirm("确定审核通过数据？", "提示", function(action){
            if(action!='ok') return;
            var callback=function(data){
                if(data.err!='0'){
                    alert(data.msg)
                    return false;
                }else{
                    grid.reload();
                }
            }
            utils.ajax('/user/bank/verify',{ids:ids,uid:uid},callback,"POST","json");
        }
    );
}
</script>