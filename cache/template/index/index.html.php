<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" class="off">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $this->_var['page_title']; ?></title>
<link href="__CSS__/admin.css" rel="stylesheet" type="text/css" />
<script src="__JS__/miniui/boot.js" type="text/javascript"></script>
<script type="text/javascript" src="__JS__/jquery/jquery.cookie.js"></script>
<script type="text/javascript" src="__JS__/admin.frame.js"></script>
</head>
<body style="overflow-x:hidden;overflow-y:hidden;">
<div id="head">
  <div class="logo"><a href="/">管理系统</a></div>
  <div class="menu"> <span>您好，<strong><span style="font-size:14px;"><?php echo $_SESSION['name']; ?></span></strong> <a onclick="location.href='/index/pass/logout';">[退出]</a></span>
  	<a href="__URL__" class="btn home" target="_blank">返回首页</a>
    <a onclick="tab_reload();" class="btn reload" href="javascript:;">刷新</a>
    <a onclick="moidfy_pwd();" class="btn" href="javascript:;">修改密码</a> </div>
  <ul class="nav">
    <?php $_from = $this->_var['menu']['top']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->_push_vars('k', 'name');$this->_foreach['n'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['n']['total'] > 0):
    foreach ($_from AS $this->_var['k'] => $this->_var['name']):
        $this->_foreach['n']['iteration']++;
?>
    	<li data-menu="<?php echo $this->_var['k']; ?>" ><a hidefocus="true" ><?php echo $this->_var['name']; ?></a></li>
    <?php endforeach; endif; unset($_from); ?><?php $this->_pop_vars();; ?>
  </ul>
</div>
<div id="content">
  <div class="lmenu">
    <div id="lmenu">
      <?php $_from = $this->_var['menu']['left']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->_push_vars('k', 'list');$this->_foreach['n'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['n']['total'] > 0):
    foreach ($_from AS $this->_var['k'] => $this->_var['list']):
        $this->_foreach['n']['iteration']++;
?>
      <div id="menu_<?php echo $this->_var['k']; ?>" class="menus">
        <?php $_from = $this->_var['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->_push_vars('cname', 'menu_0_57431200_1459309557');if (count($_from)):
    foreach ($_from AS $this->_var['cname'] => $this->_var['menu_0_57431200_1459309557']):
?>
        <h3><span></span><?php echo $this->_var['cname']; ?></h3>
        <ul>
          <?php $_from = $this->_var['menu_0_57431200_1459309557']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->_push_vars('', 'subList');if (count($_from)):
    foreach ($_from AS $this->_var['subList']):
?>
          <li><a hidefocus="true" href="<?php echo $this->_var['subList']['file']; ?>"><?php echo $this->_var['subList']['name']; ?></a></li>
          <?php endforeach; endif; unset($_from); ?><?php $this->_pop_vars();; ?>
        </ul>
        <?php endforeach; endif; unset($_from); ?><?php $this->_pop_vars();; ?></div>
      <?php endforeach; endif; unset($_from); ?><?php $this->_pop_vars();; ?> </div>
    <div id="scrollLink"><span class="up"></span><span class="down"></span> </div>
    <div id="copyright">
      <p>Powered by <a target="_blank" href="http://www.ssrong.com">ssrong.com</a></p>
      <p>&copy; 2006-2015</p>
    </div>
    <a title="展开/关闭" class="open" hidefocus="hidefocus" id="openClose" href="javascript:;"><span class="hidden">展开</span></a></div>
  <?php if ($this->_var['frameTab']): ?>
  <div class="workspace" style="border:0;">
    <div showCollapseButton="false" style="border:0; margin-top:0px;" id="workspace" width="100%" height="auto">
      <!--Tabs-->
      <div id="mainTabs" class="mini-tabs" activeIndex="0" style="width:100%;height:100%;" onactivechanged="activeTab">
        <div title="首页" url="/index/index/main" ></div>
      </div>
    </div>
  </div>
  <?php else: ?>
  <div class="workspace">
    <div id="loadMask"><span>正在加载...</span></div>
    <iframe name="right" id="workspace" src="" frameborder="false" style="border: none; background: #fff;" width="100%" height="auto" allowtransparency="true" scrolling-y="auto"></iframe>
  </div>
  <?php endif; ?>
</div>
</body>
</html>
