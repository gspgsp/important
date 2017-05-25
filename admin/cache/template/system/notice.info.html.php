__layout|public:mini_layout|layout__
<style type="text/css">
#infoForm{margin:0px auto; padding:6px; width:800px;}
td.dt{width:80px; text-align:right; font-weight:bold;}
#errMsg{display:none; border:3px solid #F00; padding:5px; background:#FFC;}
td a.add,td a.del{cursor:pointer; color:#36F;}
.hidden {display:none;}
#addrs{margin:0 0 0 86px;}
#addrs span{display:block; height:25px; line-height:25px;}
#addrs span b{display:inline-block; font-weight:normal; width:200px; height:25px; overflow:hidden; margin:0 10px 0 0; float:left;}
#addrs span a{color:#999; text-decoration:none; display:inline-block; float:left;}
#addrs span a:hover{color:#337fe5; text-decoration:underline;}
</style>
<form id="infoForm">
    <input type="hidden" name="id" value="<?php echo $this->_var['info']['id']; ?>"/>
    <div class="mini-tabs" activeIndex="0" plain="false">
        <div title="基本信息">
        <table style="width:100%;">
                <tr>
                    <td class="dt">标题</td>
                    <td><input name="info[title]" class="mini-textbox" value="<?php echo $this->_var['info']['title']; ?>" style="width:300px" required="true"/></td>
                </tr>

                <tr>
                 <td class="dt">附件</td>
                <td>
                <input id="file_url"  name="info[accessory]" class="mini-textbox" value="" style="width:200px"/>
                <input id="url_str"  name="info[path]" class="mini-textbox" value="" style="display:none;"/>
                <input id="upfileId" type="file" name="upFile" style="width:70px" onChange="fileUpload();">
                </td>
                    <!-- <td rowspan="4" width="150">
                    <span style="display:none;" id="picLoading">loading...</span>
                      <div id="picDisplay" style="border:1px solid #efefef; padding:1px; height:80px; width:80px; text-align:center; vertical-align:middle;"><?php if ($this->_var['info']['accessory']): ?><img src="__UPLOAD__/<?php echo $this->_var['info']['accessory']; ?>"><?php endif; ?></div></td> -->
                </tr>
                <tr>
                    <td colspan="3">
                        <div id="addrs"></div>
                    </td>
                </tr>
                <tr>
                    <td valign="top" class="dt">详情内容</td>
                    <td colspan="2">
                        <textarea id="content" name="info[content]" style="width:660px; height:300px;"><?php echo $this->_var['info']['content']; ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td>是否已读：</td>
                    <td><input class="mini-combobox" name="info[is_read]" style="width:100px;" data='[{"id":"0","name":"未读"},{"id":"1","name":"已读"}]' textField="name" valueField="id" value="<?php echo $this->_var['info']['is_read']; ?>"/></td>
                </tr>
                <tr>
                    <td>是否可用：</td>
                    <td><input class="mini-combobox" name="info[status]" style="width:100px;" data='[{"id":"0","name":"可用"},{"id":"1","name":"不可用"}]' textField="name" valueField="id" value="<?php echo $this->_var['info']['status']; ?>"/></td>
                </tr>
                <?php if ($this->_var['info']['id']): ?>
                <tr>
                    <td class="dt">时间</td>
                    <td>创建时间@<?php echo $this->_var['info']['input_time']; ?>，修改时间@<?php echo $this->_var['info']['update_time']; ?> by <span style="text-decoration:underline"><?php echo $this->_var['info']['input_admin']; ?></span></td>
                </tr>
                <?php endif; ?>
            </table>
      </div>
    </div>
        <div style="text-align:center;padding:10px;">
           <a class="mini-button" iconCls="icon-ok" onclick="submitForm('<?php echo $this->_var['info']['id']; ?>')">确定</a>
            <a class="mini-button" iconCls="icon-cancel" onclick="onCancel">取消</a>
            <span id="returnMsg" style="padding-left:5px; color:#F00;"></span>
        </div>
</form>
<script src="__JS__/jquery/jquery.upload.js" type="text/javascript"></script>
<script charset="utf-8" src="__JS__/kindeditor/kindeditor.js"></script>
<script charset="utf-8" src="__JS__/kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript">
mini.parse();
var form = new mini.Form("#infoForm");
$(function(){
    if ("<?php echo $this->_var['info']['accessory']; ?>") var name_arr = "<?php echo $this->_var['info']['accessory']; ?>".split("#");
    if ("<?php echo $this->_var['info']['path']; ?>")      var path_arr = "<?php echo $this->_var['info']['path']; ?>".split("#");
        $.each(name_arr,function(i,v){
            if (v) {
                console.log(i);
                $("#addrs").append("<span><b>"+v+"</b>"+"<i style='display:none'>"+path_arr[i]+"</i><a href='#' onClick='delFile(this)' id='"+path_arr[i]+"'>删除</a><br/></span>");
            }
        });
        mini.get("file_url").setValue("<?php echo $this->_var['info']['accessory']; ?>");
        mini.get("url_str").setValue("<?php echo $this->_var['info']['path']; ?>");
})

function submitForm(id){
  form.validate();
  if(form.isValid() == false) return;
  form.loading("数据提交中，请稍后......");
  $.post('/system/notice/init?action=submit#id=id',
    $("#infoForm").serialize(),
    function(data){
      form.unmask();
      $("#returnMsg").text(data.msg);
      if(data.err=='0'){
          CloseWindow("save");
      }else{
          return false;
      }
  },'json');
}
function CloseWindow(action) {
    if (window.CloseOwnerWindow) return window.CloseOwnerWindow(action);
    else window.close();
}
function onCancel(e) {
    CloseWindow("cancel");
}

var editor1=editor2=null;
KindEditor.ready(function(K) {
  editor1 = K.create('textarea[id="content"]', {uploadJson : '/system/sysUpload/images?from=kind',afterCreate : function() {},afterChange:function (e) {this.sync()}});
});

$(function(){
    imgResize();
})

//上传附件
function fileUpload(filePathArr) {
    // $("#picLoading").ajaxStart(function(){
    // $(this).show();
    // }).ajaxComplete(function() {
    //     $(this).hide();
    // });
    $.ajaxFileUpload({
        url:'/system/notice/upload',
            secureuri:false,
            fileElementId:'upfileId',
            dataType:'json',
            success: function (data) {
                if(data.error=='0'){
                    var file_type = $("#upfileId").val().split('.')['1'];
                    var url = data.url;
                    var name = data.name;
                    $("#addrs").append("<span><b>"+name+"</b>"+"<i style='display:none'>"+url+"</i><a href='#' onClick='delFile(this)' id='"+url+"'>删除</a><br/></span>");
                    if(file_type=='jpg'||file_type=='jpeg'||file_type=='png'||file_type=='bmp') {
                        $("#picDisplay").html('').append("<img src='__UPLOAD__/"+data.url+"'>");
                        imgResize();
                    }
                    var name_str = "";
                    var url_str = "";
                    $("#addrs span b").each(function(){
                        name_str += $(this).html()+"#";
                    });
                    $("#addrs span i").each(function(){
                        url_str += $(this).html()+"#";
                    });
                    mini.get("file_url").setValue(name_str);
                    mini.get("url_str").setValue(url_str);
                }
            },
            error: function (data, status, e){
                $("#picResult").html(e);
            }
        })
    return false;
}
function imgResize(){
    $("#picDisplay img").load(function(){
        if(this.width>80) this.width = 80;
    });
}

function delFile(mThis){
    var myUrl = mThis.id;
    $(mThis).parent().remove();
    var callback=function(data){
        if(data.err!='0'){
            alert(data.msg)
            return false;
        }
    }
    var json = mini.encode(myUrl);
    utils.ajax('/system/notice/delFile',{data:json},callback,"POST","json");
    var name_str = "";
    var url_str = "";
    $("#addrs span b").each(function(){
        name_str += $(this).html()+"#";
    });
    $("#addrs span i").each(function(){
        url_str += $(this).html()+"#";
    });
    mini.get("file_url").setValue(name_str);
    mini.get("url_str").setValue(url_str);
}

</script>
