__layout|public:main_layout|layout__
<?php echo $this->_smarty_insert_css(array('files'=>'home/user2.css')); ?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>用户中心首页</title>
</head>

<body>
<!--user2-tab begin-->
<!--<div class="user2-tab">-->
	<!--<ul>-->
		<!--<li id="user2-tab1" class="hover">我是买家</li>-->
		<!--<li id="user2-tab2">我是卖家</li>-->
	<!--</ul>-->
	<!--<span></span>-->
<!--</div>-->
<!--user2-tab end-->
<!--user2-wrap begin-->
<div class="user2-wrap w1220">
	<!--menus begin-->
	<div class="menus2 link6 flt">
		<!--user-info begin-->
		<div class="user-info">
			<!--pic begin-->
			<div class="pic">
				<?php if ($this->_var['data']['thumb'] != ''): ?>
				<img width="94" height="94"  src="__UPLOAD__/<?php echo $this->_var['data']['thumb']; ?>"/>
				<?php else: ?>
                <img  width="94" height="94" src="__IMG__/home/uesr_image_defaul.png">
				<?php endif; ?>
			</div>
			<p class="name"><?php echo $this->_var['data']['u_name']; ?></p>
			<?php if ($this->_var['data']['identification'] == 2): ?>
			<p class="authen yes">已认证 </p>
			<?php else: ?>
			<p class="authen no">未认证 | <a href="/user/fundManager/fundManager">去认证</a></p>
			<?php endif; ?>
			<p class="company"><?php echo $this->_var['data']['c_name']; ?></p>
			<p class="integral">积分 <span><?php echo $this->_var['data']['points']; ?></span></p>
			<!--pic end-->
		</div>
		<!--user-info end-->
		<!--menu begin-->
		<div class="menu msg">
			<h3>消息中心</h3>
			<p><a href="/user/msg?status=1">消息列表</a><span><?php echo $this->_var['data']['number']; ?></span></p>
		</div>
		<!--menu begin-->
		<!--menu begin-->
		<div class="menu order">
			<h3>订单中心</h3>
			<p <?php if ($this->_var['act'] == 'unionorder'): ?>class="hover"<?php endif; ?>><a href="/user/unionorder">商城订单</a></p>
		</div>
		<!--menu begin-->
		<!--menu begin-->
		<?php if ($_SESSION['uinfo']['type'] == 2): ?>
		<div class="menu wantbuy">
			<h3>我的求购</h3>
			<p <?php if ($this->_var['act'] == 'mypurchase'): ?>class="hover"<?php endif; ?>><a href="/user/mypurchase">求购发布</a></p>
			<p <?php if ($this->_var['act'] == 'purchaselist'): ?>class="hover"<?php endif; ?>><a href="/user/mypurchase/lists">求购管理</a></p>
			<p <?php if ($this->_var['act'] == 'wantBuy'): ?>class="hover"<?php endif; ?>><a href="/user/mypurchase/wantBuy">我的求购</a></p>
		</div>
		<?php else: ?>
		<div class="menu supply">
			<h3>我的供应</h3>
			<p <?php if ($this->_var['act'] == 'myoffers'): ?>class="hover"<?php endif; ?>><a href="/user/myoffers">报价发布</a></p>
			<p <?php if ($this->_var['act'] == 'offerlist'): ?>class="hover"<?php endif; ?>><a href="javascript:;">报价管理</a></p>
			<p <?php if ($this->_var['act'] == 'supply'): ?>class="hover"<?php endif; ?>><a href="/user/myoffers/supply">我的供货</a></p>
		</div>
		<?php endif; ?>
		<!--menu begin-->
		<!--menu begin-->
		<div class="menu finance">
			<h3>财务中心</h3>
			<p <?php if ($this->_var['act'] == 'fundManager'): ?>class="hover"<?php endif; ?>><a href="/user/fundManager/fundManager">资金管理</a></p>
			<p <?php if ($this->_var['act'] == 'fundInfomation'): ?>class="hover"<?php endif; ?>><a href="/user/fundInfomation/fundInfomation">收支明细</a></p>
			<p <?php if ($this->_var['act'] == 'showMyVoucher'): ?>class="hover"<?php endif; ?>><a href="/user/myVoucher/showMyVoucher">我的抵用券</a></p>
			<p <?php if ($this->_var['act'] == 'creditDetail'): ?>class="hover"<?php endif; ?>><a href="/user/myPoints/creditDetail">积分商城</a></p>
			<p <?php if ($this->_var['act'] == 'billInfo'): ?>class="hover"<?php endif; ?>><a href="/user/billInformation/billInfo">开票资料</a></p>
		</div>
		<!--menu begin-->
		<!--menu begin-->
		<div class="menu concern">
			<h3>关注中心</h3>
			<p <?php if ($this->_var['act'] == 'proAttentionValue'): ?>class="hover"<?php endif; ?>><a href="/user/productAttention/proAttentionValue">关注的产品</a></p>
			<p <?php if ($this->_var['act'] == 'cusAttentionList'): ?>class="hover"<?php endif; ?>><a href="/user/customerAttention/cusAttentionList">关注的商家</a></p>
		</div>
		<!--menu begin-->
		<!--menu begin-->
		<div class="menu website">
			<h3>我的网站</h3>
			<p <?php if ($this->_var['act'] == 'myHomepage'): ?>class="hover"<?php endif; ?>><a href="/user/myHomeWeb/getMyWeb">我的门户网站</a></p>
			<p <?php if ($this->_var['act'] == 'myProduct'): ?>class="hover"<?php endif; ?>><a href="/user/mainProduct/getMainProduct">主营产品</a></p>
			<p <?php if ($this->_var['act'] == 'myArticle'): ?>class="hover"<?php endif; ?>><a href="/user/myArticle/getMyArticle">我的资讯</a></p>
		</div>
		<!--menu begin-->
		<!--menu begin-->
		<div class="menu set">
			<h3>账户设置</h3>
			<p <?php if ($this->_var['act'] == 'myset'): ?>class="hover"<?php endif; ?>><a href="/user/myset">账户信息</a></p>
			<p <?php if ($this->_var['act'] == 'editAddress'): ?>class="hover"<?php endif; ?>><a href="/user/myset/editAddress">收货地址</a></p>
			<p <?php if ($this->_var['act'] == 'changepass'): ?>class="hover"<?php endif; ?>><a href="/user/changepass">修改密码</a></p>
		</div>
		<!--menu begin-->
	</div>
	<!--menus end-->
	<!--main begin-->
	<div class="main flt">
		<!--order begin-->
		<div class="order link6">
			<div class="title"><h3>最近三月订单信息</h3></div>
			<ul class="con">
				<li><a href="user/unionorder?type=3">待审核<span><?php echo $this->_var['info1']; ?></span></a></li>
				<li><a href="javascript:;">待付款<span><?php echo $this->_var['info2']; ?></span></a></li>
				<li><a href="/user/unionorder?type=4">待开票<span><?php echo $this->_var['info3']; ?></span></a></li>
				<li class="last"><a href="/user/unionorder?type=6">已取消<span><?php echo $this->_var['info4']; ?></span></a></li>
			</ul>
		</div>
		<!--order end-->
		<!--product begin-->
		<div class="product">
			<div class="title"><h3>关注产品信息</h3><a href="/user/productAttention/proAttentionValue">添加</a></div>
			<!--con begin-->
			<ul class="con">
				<?php $_from = $this->_var['prices']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->_push_vars('key', 'value');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['value']):
?>
				<li>
					<p class="change increase"><?php echo $this->_var['value']['absprice']; ?></p>
					<p class="price">￥<span><?php if ($this->_var['value']['unit_price'] > 0): ?>¥<?php echo $this->_var['value']['unit_price']; ?><?php else: ?>暂无报价<?php endif; ?></span></p>
					<p class="address"><?php echo $this->_var['value']['product_type']; ?>/<?php echo $this->_var['value']['model']; ?>/<?php echo $this->_var['value']['f_name']; ?></p>
					<p class="date"><?php if ($this->_var['value']['input_time'] > 0): ?><?php echo date("Y-m-d",$this->_var['value']['input_time']); ?><?php else: ?>--<?php endif; ?></p>
				</li>
				<?php endforeach; endif; unset($_from); ?><?php $this->_pop_vars();; ?>
			</ul>
			<!--con end-->
		</div>
		<!--product end-->
		<!--talk begin-->
		<div class="talk">
			<?php if ($_SESSION['uinfo']['type'] == 1): ?>
			<div class="title"><h3>洽谈采购需求</h3><a href="/purchase">MORE</a></div>
			<!--con begin-->
			<ul class="con">
				<?php $_from = $this->_var['rest']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->_push_vars('key', 'val');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['val']):
?>
				<li>
					<p class="wantbuy">求购<?php if ($this->_var['val']['product_type']): ?><?php echo witchType($this->_var['val']['product_type']); ?><?php else: ?>&nbsp;<?php endif; ?> <?php echo $this->_var['val']['model']; ?> <?php echo $this->_var['val']['number']; ?>吨</p>
					<p class="price">￥<?php echo $this->_var['val']['unit_price']; ?>元</p>
					<p class="talk-other">
						<span class="address"><?php echo $this->_var['val']['name']; ?></span>
						<span class="date"><?php echo date("Y-m-d",$this->_var['val']['input_time']); ?></span>
					</p>
					<a href="/offers/talk?id=<?php echo $this->_var['val']['id']; ?>"><span>我要供货<br/>我要供货</span></a>
				</li>
				<?php endforeach; endif; unset($_from); ?><?php $this->_pop_vars();; ?>
			</ul>
			<?php else: ?>
			<div class="title"><h3>洽谈报价需求</h3><a href="/offers">MORE</a></div>
			<!--con begin-->
			<ul class="con">
				<?php $_from = $this->_var['pur_sale']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->_push_vars('key', 'val');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['val']):
?>
				<li>
					<p class="wantbuy">供货<?php if ($this->_var['val']['product_type']): ?><?php echo witchType($this->_var['val']['product_type']); ?><?php else: ?>&nbsp;<?php endif; ?> <?php echo $this->_var['val']['model']; ?> <?php echo $this->_var['val']['number']; ?>吨</p>
					<p class="price">￥<?php echo $this->_var['val']['unit_price']; ?>元</p>
					<p class="talk-other">
						<span class="address"><?php echo $this->_var['val']['city']; ?></span>
						<span class="date"><?php echo date("Y-m-d",$this->_var['val']['input_time']); ?></span>
					</p>
					<a href="/offers"><span>查看<br/>查看</span></a>
				</li>
				<?php endforeach; endif; unset($_from); ?><?php $this->_pop_vars();; ?>
			</ul>
			<!--con end-->
			<?php endif; ?>
		</div>
		<!--talk end-->
		<!--other begin-->
		<div class="other">
			<!--resource begin-->
			<div class="resource flt">
				<div class="title"><h3>资源库信息</h3></div>
				<!--summary begin-->
				<div class="summary">
					<div class="summary1">
						<p>今日发布求购</p>
						<span><?php echo $this->_var['count2']['0']['total']; ?></span>
					</div>
					<div class="summary2">
						<p>今日发布供应</p>
						<span><?php echo $this->_var['count1']['0']['total']; ?></span>
					</div>
				</div>
				<!--summary end-->
				<!--forthwith begin-->
				<div class="forthwith" id="refreshs">
					<h4>实时资源</h4>
					<p><?php echo $this->_var['ref']['0']['user_nick']; ?><br/><?php echo sub_str($this->_var['ref']['0']['contents'],40); ?></p>
				</div>
				<!--forthwith end-->
			</div>
			<!--resource end-->
			<!--release begin-->
			<div class="release frt">
				<div class="title"><h3>免费发布采购需求</h3></div>
				<!--form begin-->
				<div class="form">
					<form name="" method="post" action="" class="add_form">
						<textarea placeholder="请输入您的真实需求，包括牌号、吨数等。"></textarea>
						<input type="submit" class="submit frt" value="免费委托发布"/>
					</form>
				</div>
				<!--form end-->
			</div>
			<!--release end-->
		</div>
		<!--other end-->
	</div>
	<!--main end-->
	<!--sidebar begin-->
	<div class="sidebar frt">
		<!--dealer begin-->
		<div class="dealer">
			<div class="title">您的专属交易员</div>
			<!--con begin-->
			<div class="con">
				<!--pic begin-->
				<div class="pic">
					<?php if ($this->_var['data']['pic']): ?>
					<img  width="88" height="88" src="__UPLOAD__/<?php echo $this->_var['data']['pic']; ?>"/>
					<?php else: ?>
                    <img  width="88" height="88" src="__IMG__/home/trade_image_defaul.png"/>
					<?php endif; ?>
				</div>
				<!--pic end-->
				<p class="name"><?php echo $this->_var['data']['adm_name']; ?></p>
				<p class="mobile"><?php echo $this->_var['data']['mobile']; ?></p>
			</div>
			<!--con end-->
		</div>
		<!--dealer end-->
		<!--help begin-->
		<div class="help">
			<div class="title">帮助中心</div>
			<!--con begin-->
			<ul class="con">
				<li><p>购买流程</p><span>一键认购，快人一步</span></li>
				<li><p>物流配送</p><span>安全放心，使命必达</span></li>
				<li><p>仓库自提</p><span>手续简化，说走就走</span></li>
			</ul>
			<!--con end-->
		</div>
		<!--help end-->
		<!--wx begin-->
		<div class="wx">
			<div class="title">官方微信公众号</div>
			<!--con begin-->
			<div class="con">
				<div class="ewm"><img src="__IMG__/home/userIndex4.jpg" width="117" height="117"/></div>
				<p>了解行业资讯，在线查询最新同行报价</p>
			</div>
			<!--con end-->
		</div>
		<!--wx end-->
	</div>
	<!--sidebar end-->
</div>
<!--user2-wrap end-->
</body>
</html>

<script>
	//发布采购需求
	$('.add_form').submit(function(){
		var content = $('.add_form textarea').val().trim();
		var type=1;
		if(content == ''){
			layer.msg('请填写发布内容');
			return false;
		}
		$.post('/resource/index/release', {content:content,type:type}, function (data){
			if(data.err>0){
				if(data.err==1){
					loginbox('user');
				}else{
					alert(data.msg);
				}
			}else{
				layer.msg('发布成功');
				window.location.reload();
			}
		},'json')
		return false;
	})

	//资源库局部刷新
	setInterval(function(){
		$.ajax({
			url:'/user/index/ajax',
			type:'get',
			dataType:'json',
			cache:'false',
			data:{status:1},
			success:function(res){
				var str='';
				$.each(res,function(i,item){
					str='<p>'+ item.user_nick+'<br/>' +item.contents+'</p>';
				})
				$('#refreshs').html(str);
			}
		})
	},10000);

</script>
