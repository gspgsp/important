__layout|public:mini_layout|layout__
<div class="mini-toolbar" style="margin:3px 3px 0;">
    <style>table p{padding:0; margin:0; padding-top:3px;}</style>
   <table style="width:100%;">
        <tr>
            <td style="white-space:nowrap;">
                <form id="soform">
                    <?php if ($this->_var['helper'] == 1): ?>
                    <a class="mini-button" iconCls="icon-add" plain="true" onclick="send()">发送短信</a>
                    <span class="separator"></span>
                    <?php endif; ?>
                    <!--添加时间-->
                    <!--<input name="startTime" class="mini-datepicker" style="width:100px;"/> - -->
                    <!--<input name="endTime" class="mini-datepicker" style="width:100px;"/>-->
                    <!--<?php if ($this->_var['isPublic'] == '1'): ?>-->
                    <!--<select name="status" id="soStatus">-->
                        <!--<option value="" selected="selected">=状态=</option>-->
                        <!--<?php echo $this->html_options(array('options'=>$this->_var['status'])); ?>-->
                    <!--</select>-->
                    <!--<?php endif; ?>-->
                    <!--<select name="identification" id="soidentification">-->
                        <!--<option value="" selected="selected">=认证=</option>-->
                        <!--<?php echo $this->html_options(array('options'=>$this->_var['identification'])); ?>-->
                    <!--</select>-->
                    <!--<select name="level" id="soLevel">-->
                        <!--<option value="" selected="selected">=客户级别=</option>-->
                        <!--<?php echo $this->html_options(array('options'=>$this->_var['level'])); ?>-->
                    <!--</select>-->
                    <!--<select name="type" id="soType">-->
                        <!--<option value="" selected="selected">=客户类型=</option>-->
                        <!--<?php echo $this->html_options(array('options'=>$this->_var['type'])); ?>-->
                    <!--</select>-->
                    <select name="key_type">
                        <option value="supplier_id" selected="selected">供应商ID</option>
                        <option value="supplier_name">供应商名称</option>


                        <!--<?php if ($this->_var['isPublic'] != '1'): ?>-->
                        <!--<option value="customer_manager">交易员</option>-->
                        <!--<?php endif; ?>-->
                    </select>
                    <input name="keyword" class="mini-textbox" emptyText="请输入关键词" style="width:140px;" onenter="onKeyEnter"/>
                    <a class="mini-button" iconCls="icon-search" onclick="search()">查询</a>
                    <span id="searchMsg"></span>
                </form>
            </td>
            <?php if ($this->_var['doact'] != 'search'): ?>
            <td style="float:right;">
                <?php if ($this->_var['isPublic'] != '1' && $this->_var['helper'] != 1): ?>
                <!--<a class="mini-button" iconCls="icon-user" onclick="exchange()">批量替换交易员</a>-->
                <!--<a class="mini-button" iconCls="icon-addnew" onclick="share()">共享</a>-->
                <?php if ($this->_var['cooperation'] != 1): ?>
                <a class="mini-button" iconCls="icon-add" onclick="addSupplier(0,1)">新增供应商联系人</a>
                <a class="mini-button" iconCls="icon-add" onclick="addSupplier(0,3)">新增供应商</a>
                <a class="mini-button" iconCls="icon-save" plain="true" onclick="saveTags()">保存更改</a>
                <?php endif; ?>
                <?php endif; ?>
                </p>
            </td>
            <?php endif; ?>
        </tr>
    </table>
</div>
<div class="mini-fit" style="padding:1px 3px 3px;">
    <div id="gridCell" class="mini-datagrid" style="width:100%;height:100%;"  sizeList="[10,20,50,100]" pageSize="20" allowCellWrap="true"
         url="/operator/supplier/init?action=grid&do=<?php echo $this->_var['doact']; ?>" idField="user_id"  onrowdblclick="onRowDblClick" <?php if ($this->_var['choose'] == '1'): ?>multiSelect="false"<?php endif; ?>
    multiSelect="true" <?php if ($this->_var['doact'] != 'search'): ?>allowCellEdit="true" allowCellSelect="true"<?php endif; ?>
    >
    <div property="columns">
        <div type="checkcolumn"></div>
        <div field="supplier_id" width="70" headerAlign="center" allowSort="true" align="center">供应商ID</div>
        <div field="supplier_name" width="70" headerAlign="center" allowSort="true" align="center"   renderer="onLoadHandle">供应商名称</div>
        <div field="type" width="35" headerAlign="center" align="center">客户类型</div>
        <div field="legal_person" width="30" headerAlign="center" align="center" >法人姓名</div>
        <div field="company_tel" width="60" headerAlign="center"  align="center">供应商固话</div>
        <div field="status" width="60" headerAlign="center"  align="center">状态</div>
        <div field="remark" width="60" headerAlign="center"  align="center">备注</div>
        <div field="create_time" width="50" headerAlign="center" dateFormat="yy-MM-dd HH:mm" align="center">添加时间</div>
        <div field="update_name" width="60" headerAlign="center"  align="center">更新人</div>
        <div field="update_time" width="50" headerAlign="center" dateFormat="yy-MM-dd HH:mm" align="center">更新时间</div>
        <?php if ($this->_var['isPublic'] == '1'): ?>
        <!--<div field="identification" width="40" headerAlign="center" align="center" renderer="identificationStatus">认证</div>-->
        <!--<div field="status" width="40" headerAlign="center"  align="center" renderer="LoadStatus" >状态</div>-->
        <!--<?php else: ?>-->
        <!--<div field="identification" width="40" headerAlign="center" type="comboboxcolumn" autoShowPopup="true" align="center">认证-->
            <!--<input property="editor" class="mini-combobox" style="width:100%;" textfield="name" valuefield="id"  data='<?php echo setMiniConfig($this->_var['identification']); ?>' />-->
        <!--</div>-->
        <?php endif; ?>
        <?php if ($this->_var['helper'] != 1): ?>
        <div field="" width="45" headerAlign="center"  align="center" renderer="onLoadFllow">操作</div>
        <?php endif; ?>
    </div>
</div>
</div>
<div id="allotInfo" class="mini-window" title="分配" style="width:200px;"
     showModal="true" allowResize="true" allowDrag="true"
>
    <div id="replaceForm" class="form" >
        <table style="width:100%;">
            <input class="mini-hidden" name="id"/>
            <tr>
                <td>分配交易员:</td>
                <td>
                    <input name="id" class="mini-buttonedit" onbuttonclick="allotCustomer" valueField="id"  value="" allowInput="false"  style="width:100px" id="id"/>
                    <input id="c_id" class="mini-hidden" value="">
                </td>

            </tr>

            <tr>
                <td style="text-align:right;padding-top:5px;padding-right:20px;" colspan="2">
                    <a class="mini-button" iconCls="icon-save" plain="true" href="javascript:submitAllot()">保存</a>
                </td>
            </tr>
        </table>
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
    var supplier = parseInt(<?php echo $this->_var['supplier']; ?>);
    var cooperation = parseInt(<?php echo $this->_var['cooperation']; ?>);
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
    function onRowDblClick(e) {
        onEdit();
    }
    // 编辑供应商信息(修改)
    function onLoadFllow(e) {
        var record = e.record,supplier_id=record.supplier_id, status=record.status,s='';
        s += '<a href="javascript:onEdits('+supplier_id+')">编辑</a>';
        if(status!=2){
            s += '|<a href="javascript:chk('+supplier_id+')">审核</a>';
        }
        return s;
    }

    // 审核供应商状态
    function chk(cid){
        mini.open({
            url: "/operator/supplier/chkPage?id="+cid,
            showMaxButton:true,
            title: "审核用户",
            width: 400, height:200,
            ondestroy: function (action) {
                if(action=='save'){ //重新加载
                    grid.reload();
                }
            }
        });
    }

    //编辑物流供应商
    function onLoadHandle(e) {
        var record = e.record,supplier_id=record.supplier_id, supplier_name=record.supplier_name, s='';
        s += '<a href="javascript:addSuppliers('+supplier_id+')">'+supplier_name+'</a> ';
        return s;
    }

    //查看供应商相关信息
    function addSuppliers(supplier_id){
        mini.open({
            url: "/operator/supplier/addSupplier?id="+supplier_id,
            showMaxButton:true,
            title: "查看企业用户相关信息",
            width: 1200, height:650
        });
    }

    //新增供应商、新增供应商联系人
    function addSupplier(id,ctype){      // ctype   1：新增供应商联系人   3：新增供应商和联系人
        if(ctype==1){
            var width=525,height=340;
            title='新增供应商联系人';
        }else if(ctype==3){
            var width=740,height=700;
            title='新增供应商';
        }
        mini.open({
            url: "/operator/supplier/addSupplier?id="+id+"&ctype="+ctype+"&supplier=<?php echo $this->_var['supplier']; ?>",
            showMaxButton:true,
            title: title, width: width, height:height,
            ondestroy: function (action) {
                if(action=='save'){ //重新加载
                    grid.reload();
                }
            }
        });
    }


    //发送短信
    function send(){
        var rows = grid.getSelecteds(),_ids=new Array();
        if(rows.length<1) return;
        for(var i=0;i<rows.length;i++){
            var _id=parseInt(rows[i].c_id);
            if(_id<1){
                grid.removeRow(rows[i],false);
            }else{
                _ids.push(_id);
            }
        }
        var ids=_ids.join(',');
        if(ids=='') return;
        mini.open({
            url: "/product/interface/customer_send?ids="+ids,
            title: '给客户发送短信', width: 550, height:400,
            ondestroy: function (action) {
                if(action=='save'){ //重新加载
                    grid.reload();
                }
            }
        });
    }


    //行内编辑后保存数据
//    function saveTags(){
//        var data = grid.getChanges();
//        var json = mini.encode(data);
//        if(json.length<10) return false;
//        grid.loading("保存中，请稍后......");
//        var callback=function(data){
//            if(data.error>0){
//                alert(data.content);
//                return false;
//            }else{
//                grid.reload();
//            }
//        }
//        utils.ajax('/user/customer/saveTags',{data:json},callback,"POST","json");
//    }
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

    function onEdits(supplier_id) {
        var row = grid.getSelected();
        if (row) {
            var width=740,height=700,title='编辑供应商信息';
            urlStr="/operator/supplier/edit?id="+row.supplier_id;
            mini.open({
                url: urlStr,
                title: title, width: width, height:height,
                ondestroy: function (action) {
                    if(action=='save'){ //重新加载
                        grid.reload();
                    }
                }
            });
        }
    }
    //分配管理员列表
    function allotCustomer(){
        var btn = this;
        mini.open({
            url: "rbac/adm/init?isPublic=1&do=search",
            title: "分配客户",
            width: 1200,
            height: 550,
            onload: function () {
                var iframe = this.getIFrameEl();
                iframe.contentWindow.SetData();
            },
            ondestroy: function (action) {
                if (action == "ok") {
                    var iframe = this.getIFrameEl();
                    var data = iframe.contentWindow.GetData();
                    data = mini.clone(data);    //必须
                    if (data) {
                        btn.setValue(data.admin_id);
                        btn.setText(data.name);
                        // $("#"+btn.id+"\\$value").val(data.name);
                    }
                }
            }
        });
    }
    //提交分配
    function submitAllot(){
        var cid = mini.get(c_id).getValue();
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
                allotInfo.hide();
            }
        }
        utils.ajax('/user/customer/allotCustomer',{data:json,cid:cid},callback,"POST","json");
    }
    //选择框根据传入值选择
    top['win']=window;
    function setDvalue(a){
        console.log(a);
        var row=grid.findRow(function(row){
            if(row.c_id==a) return true;
        })
        grid.select(row);
    }

</script>
