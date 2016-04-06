__layout|public:mini_layout|layout__
<style>
ul,li,p{padding:0 0 5px 0; margin:0;list-style:none;}
p{color:#F00; font-weight:bold; cursor:pointer;}
ul a{color:#39F; cursor:pointer;}
ul a:hover{color:#F00;}
ul input{width:120px;}
</style>
<form id="formInfo">
<div id="infoForm" class="form" style="width:760px; margin:0 auto;">
        <input class="mini-hidden" name="id" value="<?php echo $this->_var['info']['id']; ?>"/>
        <input class="mini-hidden" name="position" value="<?php echo $this->_var['info']['position']; ?>"/>
            <div style="padding:5px;">
              <table style="width:100%;">
                <tr>
                  <td style="width:90px;">栏目位置：</td>
                  <td><?php echo $this->_var['pos']['name']; ?></td>
                </tr>
                <tr>
                  <td style="width:90px;">栏目名称：</td>
                  <td><input name="name" class="mini-textbox" value="<?php echo $this->_var['info']['name']; ?>" required="true" style="width:300px;"/></td>
                </tr>
                 <tr>
                    <td class="dt">状态</td>
                    <td>
                    <input name="status" class="mini-combobox" valueField="id" textField="name" value="<?php echo empty($this->_var['info']['status']) ? '0' : $this->_var['info']['status']; ?>" data='[{"id":"0","name":"禁用"},{"id":"1","name":"可用"}]' required="true" />
                    </td>
            	</tr>
                <tr style="display:none;">
                  <td>起止日期</td>
                  <td><input name="start_time" class="mini-datepicker" style="width:200px;" value="<?php echo $this->_var['info']['start_time']; ?>" format="yyyy-MM-dd HH:mm:ss" showTime="true"/> <input name="end_time" class="mini-datepicker" style="width:200px;" value="<?php echo $this->_var['info']['end_time']; ?>" format="yyyy-MM-dd HH:mm:ss" showTime="true"/></td>
                </tr>
                <?php if ($this->_var['pos']['content']['info']): ?><?php $this->assign('val',$this->_var['pos']['content']['info']); ?>
                <tr>
                  <td>信息设置：</td>
                  <td><p onclick="selOption('/system/content/info?do=search','选择信息','info')">选择信息(最多<?php echo $this->_var['val']['num']; ?>个)</p></td>
                </tr>
                <tr>
                  <td colspan="2"><ul id="infoArea" length="<?php echo $this->_var['val']['num']; ?>"></ul></td>
                </tr>
                <?php endif; ?>
                <?php if ($this->_var['pos']['content']['self']): ?><?php $this->assign('val',$this->_var['pos']['content']['self']); ?>
	                <?php if ($this->_var['val']['num'] > 1): ?><tr>
	                  <td>自定义设置：</td>
	                  <td><p onclick="loadData('','self')">增加选项(最多<?php echo $this->_var['val']['num']; ?>个)</p></td>
	                </tr>
	                <?php endif; ?>
                <tr>
                  <td colspan="2"><ul id="selfArea" length="<?php echo $this->_var['val']['num']; ?>"></ul></td>
                </tr>
                <?php endif; ?>
                <?php if ($this->_var['info']['id']): ?>
                <tr>
                    <td class="dt">时间</td>
                    <td>最后编辑@<?php echo $this->_var['info']['input_time']; ?> by <span style="text-decoration:underline"><?php echo $this->_var['info']['admin_name']; ?></span></td>
                </tr>
                <?php endif; ?>
              </table>
</div>
        <div style="text-align:center;padding:10px;">
           <a class="mini-button" iconCls="icon-ok" onclick="submitForm">确定</a>
            <a class="mini-button" iconCls="icon-cancel" onclick="onCancel">取消</a>
            <span id="returnMsg" style="padding-left:5px; color:#F00;"></span>
        </div> 
        <div id="contentData" style="display:none;"><?php echo $this->_var['info']['content']; ?></div> 
        <div id="imgView" style="position:absolute;"></div>      
</div>
</form>
<script src="__JS__/jquery/jquery.upload.js" type="text/javascript"></script>
<script type="text/javascript">
mini.parse();
var form = new mini.Form("#infoForm");

//选择品牌/信息
function selOption(url,title,dtype){
	 mini.open({
		url: url,
		title: title,
		width: 990,
		height: 450,
		ondestroy: function (action) {
			if (action == "ok") {
				var iframe = this.getIFrameEl();
				var data = iframe.contentWindow.GetData();
				data = mini.clone(data);    //必须
				if(data) loadData(data,dtype);
			}
		}
	});   
}

//加载js数据
function loadData(data,type){
	var comLi=' <a class="up" title="上移">↑</a> <a class="down" title="下移">↓</a> <a class="view" title="预览">Ⅴ</a> <a class="delete" title="删除">×</a> ';
	if(type=='info'){ //商品数据
		var string="";
		var length=parseInt($("#infoArea").attr('length'))-parseInt($("#infoArea li").length); //允许数量
		for(var i in data) {
			if(data[i].id && $("#upfileI"+data[i].id).length<1 && length>0){ //不存在时
				length--;
				string+='<li><input type="hidden" name="iid[]" value="'+data[i].id+'">名称：<input type="text" name="iname[]" value="'+data[i].title+'"> 链接：<input type="text" name="iurl[]" value="'+data[i].url+'"> 图片：<input type="text" class="img" name="iimg[]" value="'+data[i].img+'"> <input id="upfileI'+data[i].id+'" type="file" name="upFile" onchange="fileUpload(this);" style="width:68px;" /> '+comLi+'</li>';
			}
		}
		$("#infoArea").append(string);
	}else if(type=='self'){ //自定义数据
		var string="",al=parseInt($("#selfArea").attr('length')),length=al-parseInt($("#selfArea li").length); //允许数量
		if(length>0){
			if(data){
				for(var i in data) {
					if(data[i].id && $("#upfileS"+data[i].id).length<1 && length>0){ //不存在时
						length--;
						string+='<li><input type="hidden" name="sid[]" value="'+data[i].id+'">名称：<input type="text" name="sname[]" value="'+data[i].title+'"> 链接：<input type="text" name="surl[]" value="'+data[i].url+'"> 图片：<input type="text" class="img" name="simg[]" value="'+data[i].img+'"> <input id="upfileS'+data[i].id+'" type="file" name="upFile" onchange="fileUpload(this);" style="width:68px;" /> '+comLi+'</li>';
					}
				}
			}else{
				for(id=1;id<=al;id++){
					if($("#upfileS"+id).length<1){
						break;
					}	
				}
				string+='<li><input type="hidden" name="sid[]" value="'+id+'">名称：<input type="text" name="sname[]" value=""> 链接：<input type="text" name="surl[]" value=""> 图片：<input type="text" class="img" name="simg[]" value"> <input id="upfileS'+id+'" type="file" name="upFile" onchange="fileUpload(this);" style="width:68px;" /> '+comLi+'</li>';
			}
		}
		$("#selfArea").append(string);
	}
}

$(function(){
	$('a.view').live('mouseenter',function(e){
		var img=$(this).parents('li').find('.img').val();
		if(img.length>5){
			x = e.pageX; y = e.pageY;
			$("#imgView").html("<img src='__UPLOAD__/"+img+"'>").css({'top': y + 10,'left': x - 200,'display':'block'});
		}							  
	}).live('mouseleave',function(e){
		$("#imgView").html("").css({'display':'block'});
	});	   
	$('a.delete').live('click',function(){
		$(this).parents('li').children('input').val('');
		//$(this).parents('li:not(:first:last)').remove();
	});	
	$('a.up').live('click',function(){
		var $obj=$(this).parents('li');
		$obj.prev().before($obj);
	});	
	$('a.down').live('click',function(){
		var $obj=$(this).parents('li');
		$obj.next().after($obj);
	});	
	
	//初始化数据
	if($("#contentData").html().length>10){
		var myContent = '';
		eval('myContent=' + $("#contentData").html() + ';');
		if(myContent.info){
			loadData(myContent.info,'info');	
		}
		if(myContent.self){
			loadData(myContent.self,'self');	
		}
	}else{
		loadData('','self');
	}

});

function submitForm(){
  form.validate();
  if(form.isValid() == false) return;
  
  $("#returnMsg").text('');
  form.loading("数据提交中，请稍后......");
  $.post('/system/block/submit',$("#formInfo").serialize(),function(data){
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
function fileUpload(obj) {
	$obj=$(obj),$img=$obj.parents('li').find('input.img');
	$.ajaxFileUpload({
		url:'/system/sysUpload/images?type=block',
			secureuri:false,
			fileElementId:$obj.attr('id'),
			dataType: 'json',
			success: function (data, status) {
				if(data.err=='0'){
					$img.val(data.msg);
				}
			},
			error: function (data, status, e){
				alert(e);
			}
		}
	)
	return false;
}
</script>
