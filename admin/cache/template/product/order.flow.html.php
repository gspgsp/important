__layout|public:mini_layout|layout__
<style type="text/css">
*{margin:0; padding:0;}
.views{color:#666; font-size:12px; width:100%; height:auto; overflow:hidden;}
.views .view{width:25%; height:590px; overflow:hidden; float:left; border-right:1px solid #ccc;}
.views .view.last{width:24%; border-right:none;}
.views .view h3{color:#333; font-weight:700; font-size:14px;font-family: 'Yahei'; text-align:center; height:40px; line-height:40px;border-bottom: 1px solid #ccc;margin-bottom: 20px;}
.views .view .track{color:#fff; text-align:center; width:80px; height:30px; line-height:30px; margin:10px auto;border-radius: 4px;}
.views .view .track.done{background:#3cae3c;}
.views .view .track.doing{background:#f93838;}
.views .view .track.undetermined{background:#a2a1a1;}
.views .view .info{height:80px; overflow:hidden; line-height:20px; text-indent:20px;}
</style>
</head>

<body>
<div class="views">
	<!--view begin-->
	<div class="view">
		<h3>信息流</h3>
		<p class="track <?php if ($this->_var['info1']): ?>done<?php else: ?>doing<?php endif; ?>">订单创建</p>
		<ul class="info">
		<?php if ($this->_var['info1']): ?>
			<li><?php if ($this->_var['info1'] [ 0 ] [ 'input_time' ]): ?><?php echo date(" Y/m/d H:i:s",$this->_var['info1']['0']['input_time']); ?>&nbsp;&nbsp;&nbsp;<?php echo $this->_var['info1']['0']['input_admin']; ?><?php endif; ?></li>
		<?php endif; ?>
		</ul>
		<p class="track <?php if ($this->_var['info2']): ?>done<?php else: ?>doing<?php endif; ?>">合同审核</p>
		<ul class="info">
		<?php if ($this->_var['info2']): ?>
			<li><?php if ($this->_var['info2'] [ 0 ] [ 'input_time' ]): ?><?php echo date(" Y/m/d H:i:s",$this->_var['info2']['0']['input_time']); ?>&nbsp;&nbsp;&nbsp;<?php echo $this->_var['info2']['0']['input_admin']; ?><?php endif; ?></li>
			<li><?php if ($this->_var['info2'] [ 0 ] [ 'spend_time' ]): ?>耗时：<?php echo $this->_var['info2']['0']['spend_time']; ?><?php else: ?>暂无数据<?php endif; ?></li>
		<?php endif; ?>
		</ul>
		<p class="track <?php if ($this->_var['info3']): ?>done<?php else: ?>doing<?php endif; ?>">合同回传</p>
		<ul class="info">
		<?php if ($this->_var['info3']): ?>
			<li><?php if ($this->_var['info3'] [ 0 ] [ 'input_time' ]): ?><?php echo date(" Y/m/d H:i:s",$this->_var['info3']['0']['input_time']); ?>&nbsp;&nbsp;&nbsp;<?php echo $this->_var['info3']['0']['input_admin']; ?><?php endif; ?></li>
			<li>耗时：<?php if ($this->_var['info3'] [ 0 ] [ 'spend_time' ]): ?><?php echo $this->_var['info3']['0']['spend_time']; ?><?php else: ?>暂无数据<?php endif; ?></li>
		<?php endif; ?>
		</ul>
		<p class="track <?php if ($this->_var['info4']): ?>done<?php else: ?>doing<?php endif; ?>">订单取消</p>
		<ul class="info">
		<?php if ($this->_var['info4']): ?>
			<li><?php if ($this->_var['info4'] [ 0 ] [ 'input_time' ]): ?><?php echo date(" Y/m/d H:i:s",$this->_var['info4']['0']['input_time']); ?>&nbsp;&nbsp;&nbsp;<?php echo $this->_var['info4']['0']['input_admin']; ?><?php endif; ?></li>
		<?php endif; ?>
		</ul>
	</div>
	<!--view end-->
	<!--view begin-->
	<div class="view">
		<h3>物流</h3>
		<p class="track <?php if ($this->_var['ship1']): ?>done<?php else: ?>doing<?php endif; ?>"><?php if ($this->_var['type'] == 1): ?>已派车<?php else: ?>已入库<?php endif; ?></p>
		<ul class="info">
		<?php if ($this->_var['ship1']): ?>
			<li><?php if ($this->_var['ship1'] [ 0 ] [ 'input_time' ]): ?><?php echo date(" Y/m/d H:i:s",$this->_var['ship1']['0']['input_time']); ?>&nbsp;&nbsp;&nbsp;<?php echo $this->_var['ship1']['0']['input_admin']; ?><?php endif; ?></li>
			<li>耗时：<?php if ($this->_var['ship1'] [ 0 ] [ 'spend_time' ]): ?><?php echo $this->_var['ship1']['0']['spend_time']; ?><?php else: ?>暂无数据<?php endif; ?></li>
		<?php endif; ?>
		</ul>
		<?php if ($this->_var['type'] == 1): ?>
		<p class="track <?php if ($this->_var['ship2']): ?>done<?php else: ?>doing<?php endif; ?>">POD签收</p>
		<ul class="info">
		<?php if ($this->_var['ship2']): ?>
			<li><?php if ($this->_var['ship2'] [ 0 ] [ 'input_time' ]): ?><?php echo date(" Y/m/d H:i:s",$this->_var['ship2']['0']['input_time']); ?>&nbsp;&nbsp;&nbsp;<?php echo $this->_var['ship2']['0']['input_admin']; ?><?php endif; ?></li>
			<li>耗时：<?php if ($this->_var['ship2'] [ 0 ] [ 'spend_time' ]): ?><?php echo $this->_var['ship2']['0']['spend_time']; ?><?php else: ?>暂无数据<?php endif; ?></li>
		<?php endif; ?>
		</ul>
		<?php endif; ?>
	</div>
	<!--view end-->
	<!--view begin-->
	<div class="view">
		<h3>资金流</h3>
		<?php if ($this->_var['fund3']): ?>
		<?php $_from = $this->_var['fund3']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->_push_vars('k', 'val');$this->_foreach['n'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['n']['total'] > 0):
    foreach ($_from AS $this->_var['k'] => $this->_var['val']):
        $this->_foreach['n']['iteration']++;
?>
			<?php if ($this->_var['val']['step'] == 3): ?>
			<p class="track done">全部<?php if ($this->_var['type'] == 1): ?>收<?php else: ?>付<?php endif; ?>款</p>
			<?php elseif ($this->_var['val']['step'] == 2): ?>
			<p class="track done">部分<?php if ($this->_var['type'] == 1): ?>收<?php else: ?>付<?php endif; ?>款</p>
			<?php elseif ($this->_var['val']['step'] < 2): ?>
			<p class="track doing">待<?php if ($this->_var['type'] == 1): ?>收<?php else: ?>付<?php endif; ?>款</p>
			<?php endif; ?>
			<ul class="info">
			<?php if ($this->_var['val'] && $this->_var['val']['step'] > 1): ?>
				<li><?php echo date(" Y/m/d H:i:s",$this->_var['val']['input_time']); ?>&nbsp;&nbsp;&nbsp;<?php echo $this->_var['val']['input_admin']; ?></li>
				<li>总额：<?php echo $this->_var['val']['total']; ?>元</li>
				<li>累计<?php if ($this->_var['type'] == 1): ?>收<?php else: ?>付<?php endif; ?>款：<?php echo $this->_var['val']['payed']; ?>元</li>
				<li>余额：<?php echo $this->_var['val']['lefted']; ?>元</li>
			<?php endif; ?>
			</ul>
		<?php endforeach; endif; unset($_from); ?><?php $this->_pop_vars();; ?>
		<?php else: ?>
			<p class="track doing">待<?php if ($this->_var['type'] == 1): ?>收<?php else: ?>付<?php endif; ?>款</p>
			<ul class="info">
			</ul>
		<?php endif; ?>
		<h3>关联单子</h3>
		<?php if ($this->_var['re_fund3']): ?>
		<?php $_from = $this->_var['re_fund3']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->_push_vars('k', 'val');$this->_foreach['n'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['n']['total'] > 0):
    foreach ($_from AS $this->_var['k'] => $this->_var['val']):
        $this->_foreach['n']['iteration']++;
?>
			<?php if ($this->_var['val']['step'] == 3): ?>
			<p class="track done">全部<?php if ($this->_var['type'] == 1): ?>付<?php else: ?>收<?php endif; ?>款</p>
			<?php elseif ($this->_var['val']['step'] == 2): ?>
			<p class="track done">部分<?php if ($this->_var['type'] == 1): ?>付<?php else: ?>收<?php endif; ?>款</p>
			<?php elseif ($this->_var['val']['step'] < 2): ?>
			<p class="track doing">待<?php if ($this->_var['type'] == 1): ?>付<?php else: ?>收<?php endif; ?>款</p>
			<?php endif; ?>
			<ul class="info">
			<?php if ($this->_var['val'] && $this->_var['val']['step'] > 1): ?>
				<li><?php echo date(" Y/m/d H:i:s",$this->_var['val']['input_time']); ?>&nbsp;&nbsp;&nbsp;<?php echo $this->_var['val']['input_admin']; ?></li>
				<li>总额：<?php echo $this->_var['val']['total']; ?>元</li>
				<li>共计<?php if ($this->_var['type'] == 1): ?>付<?php else: ?>收<?php endif; ?>款：<?php echo $this->_var['val']['payed']; ?>元</li>
				<li>余额：<?php echo $this->_var['val']['lefted']; ?>元</li>
			<?php endif; ?>
			</ul>
		<?php endforeach; endif; unset($_from); ?><?php $this->_pop_vars();; ?>
		<?php else: ?>
			<p class="track doing">待<?php if ($this->_var['type'] == 1): ?>付<?php else: ?>收<?php endif; ?>款</p>
			<ul class="info">
			</ul>
		<?php endif; ?>
	</div>
	<!--view end-->
	<!--view begin-->
	<div class="view last">
		<h3 style="border-top:#333">发票</h3>
		<?php if ($this->_var['tick3']): ?>
		<?php $_from = $this->_var['tick3']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->_push_vars('k', 'val');$this->_foreach['n'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['n']['total'] > 0):
    foreach ($_from AS $this->_var['k'] => $this->_var['val']):
        $this->_foreach['n']['iteration']++;
?>
			<?php if ($this->_var['val']['step'] == 3): ?>
			<p class="track done">全部<?php if ($this->_var['type'] == 1): ?>开<?php else: ?>收<?php endif; ?>票</p>
			<?php elseif ($this->_var['val']['step'] == 2): ?>
			<p class="track done">部分<?php if ($this->_var['type'] == 1): ?>开<?php else: ?>收<?php endif; ?>票</p>
			<?php elseif ($this->_var['val']['step'] < 2): ?>
			<p class="track doing">待<?php if ($this->_var['type'] == 1): ?>开<?php else: ?>收<?php endif; ?>款</p>
			<?php endif; ?>
			<ul class="info">
			<?php if ($this->_var['val'] && $this->_var['val']['step'] > 1): ?>
				<li><?php echo date(" Y/m/d H:i:s",$this->_var['val']['input_time']); ?>&nbsp;&nbsp;&nbsp;<?php echo $this->_var['val']['input_admin']; ?></li>
				<li>总额：<?php echo $this->_var['val']['total']; ?>元</li>
				<li>本次<?php if ($this->_var['type'] == 1): ?>开<?php else: ?>收<?php endif; ?>票：<?php echo $this->_var['val']['payed']; ?>元</li>
				<li>余额：<?php echo $this->_var['val']['lefted']; ?></li>
			<?php endif; ?>
			</ul>
		<?php endforeach; endif; unset($_from); ?><?php $this->_pop_vars();; ?>
		<?php else: ?>
			<p class="track doing">待<?php if ($this->_var['type'] == 1): ?>开<?php else: ?>收<?php endif; ?>票</p>
			<ul class="info">
			</ul>
		<?php endif; ?>
		<h3  style="border-top:#333">关联单子</h3>
		<?php if ($this->_var['re_tick3']): ?>
		<?php $_from = $this->_var['re_tick3']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->_push_vars('k', 'val');$this->_foreach['n'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['n']['total'] > 0):
    foreach ($_from AS $this->_var['k'] => $this->_var['val']):
        $this->_foreach['n']['iteration']++;
?>
			<?php if ($this->_var['val']['step'] == 3): ?>
			<p class="track done">全部<?php if ($this->_var['type'] == 1): ?>收<?php else: ?>开<?php endif; ?>票</p>
			<?php elseif ($this->_var['val']['step'] == 2): ?>
			<p class="track done">部分<?php if ($this->_var['type'] == 1): ?>收<?php else: ?>开<?php endif; ?>票</p>
			<?php elseif ($this->_var['val']['step'] < 2): ?>
			<p class="track doing">待<?php if ($this->_var['type'] == 1): ?>收<?php else: ?>开<?php endif; ?>款</p>
			<?php endif; ?>
			<ul class="info">
			<?php if ($this->_var['val'] && $this->_var['val']['step'] > 1): ?>
				<li><?php echo date(" Y/m/d H:i:s",$this->_var['val']['input_time']); ?>&nbsp;&nbsp;&nbsp;<?php echo $this->_var['val']['input_admin']; ?></li>
				<li>总额：<?php echo $this->_var['val']['total']; ?>元</li>
				<li>本次<?php if ($this->_var['type'] == 1): ?>收<?php else: ?>开<?php endif; ?>票：<?php echo $this->_var['val']['payed']; ?>元</li>
				<li>余额：<?php echo $this->_var['val']['lefted']; ?></li>
			<?php endif; ?>
			</ul>
		<?php endforeach; endif; unset($_from); ?><?php $this->_pop_vars();; ?>
		<?php else: ?>
			<p class="track doing">待<?php if ($this->_var['type'] == 1): ?>收<?php else: ?>开<?php endif; ?>票</p>
			<ul class="info">
			</ul>
		<?php endif; ?>
	</div>
	<!--view end-->
</div>