__layout|public:main_layout|layout__
<div class="tblform" style="margin:5px;">
  <h4>网站设置</h4>
  <form method="post" action="/system/setting/init?action=submit" id="theForm" enctype="multipart/form-data">
  <div class="part">
	<table cellspacing="0" cellpadding="0" border="0">
	  <tbody>
		<tr>
		  <th>ICON: </th>
		  <td id="icon_previewer">
			<input name="site_icon" type="hidden" value="<?php echo $this->_var['cfg']['site_icon']; ?>" />
			<input id="upicon" type="file" name="upicon" onchange="uploadLogo(this);" style="width:68px;" /><br />
		  </td>
		</tr>
		<tr>
		  <th>LOGO: </th>
		  <td id="logo_previewer">
			<input name="site_logo" type="hidden" value="<?php echo $this->_var['cfg']['site_logo']; ?>" />
			<input id="upimg" type="file" name="uplogo" onchange="uploadLogo(this);" style="width:68px;" /><br />
		  </td>
		</tr>
		<tr>
		  <th>主题样式: </th>
		  <td>
			<select name="theme" class="mini-combobox" value="<?php echo $this->_var['cfg']['theme']; ?>"><?php echo $this->html_options(array('options'=>$this->_var['theme_types'])); ?></select>
		  </td>
		</tr>
		<tr>
		  <th>站点名称: </th>
		  <td>
			<input name="site_name" class="mini-textbox" value="<?php echo $this->_var['cfg']['site_name']; ?>" style="width:380px" />
		  </td>
		</tr>
		<tr>
		  <th>站点标题: </th>
		  <td>
			<input name="site_title" class="mini-textbox" value="<?php echo $this->_var['cfg']['site_title']; ?>" style="width:380px" />
		  </td>
		</tr>
		<tr>
		  <th>站点关键字: </th>
		  <td><input name="site_keywords" class="mini-textbox" value="<?php echo $this->_var['cfg']['site_keywords']; ?>" style="width:380px" /></td>
		</tr>
		<tr>
		  <th>站点描述: </th>
		  <td><input class="mini-textarea" name="site_desc" style="width:380px;" value="<?php echo $this->_var['cfg']['site_desc']; ?>"/></td>
		</tr>
		<tr>
		  <th> 客服邮件地址: </th>
		  <td><input name="service_email" class="mini-textbox" value="<?php echo $this->_var['cfg']['service_email']; ?>" style="width:380px"/></td>
		</tr>
		<tr>
		  <th> 客服热线: </th>
		  <td><input name="service_phone" class="mini-textbox" value="<?php echo $this->_var['cfg']['service_phone']; ?>" style="width:380px"/></td>
		</tr>
		<tr>
		  <th> 联系电话: </th>
		  <td><input name="service_mobile" class="mini-textbox" value="<?php echo $this->_var['cfg']['service_mobile']; ?>" style="width:380px"/></td>
		</tr>
		<tr>
		  <th> 服务时间: </th>
		  <td><input name="service_time" class="mini-textbox" value="<?php echo $this->_var['cfg']['service_time']; ?>" style="width:380px"/></td>
		</tr>
		<tr>
		  <th> 服务信息: </th>
		  <td><input name="service" class="mini-textbox" value="<?php echo $this->_var['cfg']['service']; ?>" style="width:380px"/></td>
		</tr>  
		<tr>
		  <th>微博链接: </th>
		  <td><input name="weibo[link]" value="<?php echo $this->_var['cfg']['weibo']['link']; ?>" class="mini-textbox" style="width:380px;"  /></td>
		</tr> 
		<tr>
		  <th>公司名称:</td>
		  <td><input class="mini-textbox" name="company_name" style="width:380px;" value="<?php echo $this->_var['cfg']['company_name']; ?>"/></td>
		</tr>
		<tr>
		  <th>公司地址:</td>
		  <td><input class="mini-textarea" name="company_address" style="width:380px;" value="<?php echo $this->_var['cfg']['company_address']; ?>"/></td>
		</tr>
		<tr>
		  <th>ICP备案号:</td>
		  <td><input class="mini-textarea" name="icp_number" style="width:380px;" value="<?php echo $this->_var['cfg']['icp_number']; ?>"/></td>
		</tr>
		<tr>
		  <th>统计代码: </th>
		  <td><input class="mini-textarea" name="stats_code" style="width:380px;" value="<?php echo $this->_var['cfg']['stats_code']; ?>"/></td>
		</tr>
		<tr>
		  <th>SEM统计: </th>
		  <td><input class="mini-textarea" name="stats_sem" style="width:380px;" value="<?php echo $this->_var['cfg']['stats_sem']; ?>"/></td>
		</tr>
	  </tbody>
	</table>
  </div>
  <div class="tblbtn">
	  <a class="mini-button" iconCls="icon-ok" onclick="frmSubmit">提交</a>
	  <a class="mini-button" iconCls="icon-undo" onclick="frmRest">重置</a>
	  <input type="reset" style="display:none;">
	  <span id="returnMsg" style="padding-left:5px; color:#F00;"></span>
  </div>
  </form>
</div>
<script src="__JS__/jquery/jquery.upload.js" type="text/javascript"></script>
<script type="text/javascript">
mini.parse();

function uploadLogo(obj) {
  var $obj=$(obj);
  var $input=$obj.prev('input:hidden');
  $.ajaxFileUpload({
	url:'/system/sysUpload/images?type=block',
	  secureuri:false,
	  fileElementId:$obj.attr('id'),
	  dataType: 'json',
	  success: function (data, status) {
		if(data.err=='0'){
		  $input.val(data.msg);
		  previewLogo();
		}
	  },
	  error: function (data, status, e){
		alert(e);
	  }
	}
  );
  return false;
}

function previewLogo(){
  $('#logo_previewer,#icon_previewer').each(function(){
	var $previewer = $(this);
	var src = $previewer.children('input:hidden').val();
	if(src)
	  $previewer.children('img').remove().end()
				.append($('<img />',{src: '__UPLOAD__/'+src, css: {maxHeight:'50px'}}));
  });
}
previewLogo();
</script>
