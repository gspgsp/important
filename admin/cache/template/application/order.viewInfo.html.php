__layout|public:mini_layout|layout__
<div style="padding:5px;">
<div class="mini-tabs" activeIndex="0" plain="false" onactivechanged="changeTab">
		<div title="订单详细信息">
			<div style="height:100%">
				<fieldset style="border:solid 1px #aaa;padding:3px;">
					<legend >基本信息</legend>
					<table width="100%">
					<tr>
						<td style="width:80px;">订单名称：</td>
						<td>
							<?php echo $this->_var['info']['order_name']; ?>
						</td>
						<td>订单编号：</td>
						<td><?php echo $this->_var['info']['order_sn']; ?></td>
					</tr>
					<tr>
						<td>订单来源：</td>
						<td><?php echo $this->_var['order_source'][$this->_var['info']['order_source']]; ?></td>
						<!-- <td>付款方式：</td>
						<td><?php echo $this->_var['pay_method'][$this->_var['info']['pay_method']]; ?></td> -->
						<?php if ($this->_var['order_type'] == 'saleLog'): ?>
						<td>销售类型: </td>
						<td><?php echo $this->_var['info']['sales_type']; ?></td>
						<?php else: ?>
						<td>采购类型: </td>
						<td><?php echo $this->_var['info']['purchase_type']; ?></td>
						<?php endif; ?>
					</tr>
					<tr>
						<td>客户名称：</td>
						<td><?php echo $this->_var['c_name']; ?></td>
						<td><font style="color:red;">总金额：</font></td>
						<td><font style="color:blue;"><?php echo $this->_var['info']['total_price']; ?></font></td>
					</tr>
					<tr>
						<td><font style="color:red;">销售审核：</font></td>
						<td><font style="color:blue;"><?php echo $this->_var['order_status'][$this->_var['info']['order_status']]; ?></font></td>
						<td><font style="color:red;">物流审核：</font></td>
						<td><font style="color:blue;"><?php echo $this->_var['transport_status'][$this->_var['info']['transport_status']]; ?></font></td>

					</tr>
					<tr>
						<td>审备注[销]：</td>
						<td><?php echo $this->_var['info']['order_remark']; ?></td>
						<td>审备注[物]：</td>
						<td><?php echo $this->_var['info']['transport_remark']; ?></td>
					</tr>
					<tr>
						<td>签约地点 : </td>
						<td><?php echo $this->_var['info']['sign_place']; ?></td>
						<td>签订日期：</td>
						<td><?php echo $this->_var['info']['sign_time']; ?></td>

					</tr>

					<tr>
						<td>运输方式：</td>
						<td><?php echo $this->_var['transport_type'][$this->_var['info']['transport_type']]; ?></td>
						<td>开票状态：</td>
						<td><?php echo $this->_var['invoice_status'][$this->_var['info']['invoice_status']]; ?></td>
					</tr>
					<tr>
						<td>运费：</td>
						<td><?php echo $this->_var['info']['freight_price']; ?></td>
						<td>业务模式 : </td>
						<td><?php echo $this->_var['business_model'][$this->_var['info']['business_model']]; ?></td>
					</tr>
					<?php if ($this->_var['info']['transport_type'] == 1): ?>
					<tr>
						<td>提货日期：</td>
						<td><?php echo $this->_var['info']['pickup_time']; ?></td>
						<td>提货地址：</td>
						<td><?php echo $this->_var['info']['pickup_location']; ?></td>
					</tr>
					<?php else: ?>
					<tr>
						<td>送货地点：</td>
						<td><?php echo $this->_var['info']['delivery_location']; ?></td>
						<td>送货日期 : </td>
						<td><?php echo $this->_var['info']['delivery_time']; ?></td>

					</tr>
					<?php endif; ?>
					<tr>
						<td>付款日期：</td>
						<td><?php echo $this->_var['info']['payment_time']; ?></td>
						<td>财务记录 : </td>
						<td><?php echo $this->_var['financial_records'][$this->_var['info']['financial_records']]; ?></td>
					</tr>
					<tr>
					<?php if ($this->_var['order_type'] == 'saleLog'): ?>
						<td>出库状态 : </td>
						<td><?php echo $this->_var['out_storage_status'][$this->_var['info']['out_storage_status']]; ?></td>
					<?php else: ?>

						<td>入库状态 : </td>
						<td><?php echo $this->_var['in_storage_status'][$this->_var['info']['in_storage_status']]; ?></td>
					<?php endif; ?>
						<td>结算方式 : </td>
						<td><?php echo $this->_var['info']['payment_way']; ?></td>
					</tr>
					<tr>
					<?php if ($this->_var['order_type'] == 'saleLog'): ?>
						<td>是否代采：</td>
						<td><?php if ($this->_var['info']['h_pur'] == 1): ?>否<?php else: ?>是<?php endif; ?></td>
						<?php if ($this->_var['info']['h_pur'] == 2): ?>
						<td>采购商名称 : </td>
						<td><?php echo $this->_var['h_pur_cid']; ?></td>
						<?php endif; ?>
					<?php endif; ?>
					</tr>
					<tr>
						<td>付款方式：</td>
						<td><?php echo $this->_var['pay_method'][$this->_var['info']['pay_method']]; ?></td>
						<!-- <td>创建者：</td>
						<td><?php echo $this->_var['info']['creater']; ?></td> -->
						<td>备注 : </td>
						<td><?php echo $this->_var['info']['remark']; ?></td>
					</tr>
					<tr>
						<td>创建者：</td>
						<td><?php echo $this->_var['info']['creater']; ?></td>
						<td>协助者：</td>
						<td><?php echo $this->_var['info']['partner']; ?></td>
						<!-- <td>&nbsp;</td>
						<td>&nbsp;</td> -->
					</tr>
					<tr>
						<td>授信额度：</td>
						<td><?php echo $this->_var['info']['credit_limit']; ?></td>
						<td>可用额度：</td>
						<?php if ($this->_var['info']['available_credit_limit'] < $this->_var['info']['total_price']): ?>
						<td><font style="color:red;"><?php echo $this->_var['info']['available_credit_limit']; ?></font></td>
						<?php else: ?>
						<td><?php echo $this->_var['info']['available_credit_limit']; ?></td>
						<?php endif; ?>
					</tr>
					<tr>
						<td>附加条款：</td>
						<td colspan="3"><?php echo $this->_var['info']['additional']; ?></td>
					</tr>
				</table>
				</fieldset>
			<!-- 	<?php if ($this->_var['info']['order_status'] == 1 || $this->_var['info']['transport_status'] == 1): ?>
				<fieldset style="border:solid 1px #aaa;padding:3px;">
					<legend >扩展信息</legend>
					<form id="infoForm" class="form">
					<div style="padding:5px;">
						<input name="id" class="mini-hidden" value="<?php echo $this->_var['info']['o_id']; ?>">
						<table style="border:1px solid red;">
							<tr>
								<?php if ($this->_var['sexits'] == 1 && $this->_var['info']['order_status'] == 1): ?>
								<td style="width:85px;">销售审核：</td>
								<td style="width:200px;">
									<input name="order_status" class="mini-radiobuttonlist" textfield="text" value="2"  valuefield="id"  data="[{id: 2, text: '审核通过'}, {id: 3, text: '已取消'}]"/>
								</td>
								<td style="width:85px;">销售备注：</td>
								<td style="width:110px;">
									<input name="order_remark" class="mini-textarea" style="width:100%; height:30px" emptyText="请输入您的备注"/>
								</td>
								<?php endif; ?>
							</tr>
							<tr>
								<?php if ($this->_var['texits'] == 1 && $this->_var['info']['transport_status'] == 1 && $this->_var['info']['order_status'] != 1): ?>
								<td style="width:85px;">物流审核：</td>
								<td style="width:110px;">
									<input name="transport_status" class="mini-radiobuttonlist"  textfield="text" value="2"  valuefield="id"  data="[{id: 2, text: '审核通过'}, {id: 3, text: '已取消'}]"/>
								</td>
								<td style="width:85px;">物流备注：</td>
								<td style="width:200px;">
									<input name="transport_remark" class="mini-textarea" style="width:100%; height:30px" emptyText="请输入您的备注"/>
								</td>
								<?php endif; ?>
							</tr>
							<div>
								<a class="mini-button" iconCls="icon-save" style="float:right;" plain="true" href="javascript:submitcheck()">保存审核</a>
							</div>
						</table>
					</div>
					</form>
				</fieldset>
				<?php endif; ?> -->
			</div>
		</div>
		<div title="详情列表">
			<div id="investGrid" class="mini-datagrid" style="width:auto;height:300px;" idField="id"  url="/product/<?php echo $this->_var['order_type']; ?>/init?action=grid&oid=<?php echo $this->_var['o_id']; ?>" sizeList="[10,20,50,100]" pageSize="20">
				<div property="columns">
				<!-- <div field="id" width="30" headerAlign="center" align="center">ID</div> -->
				<div field="order_name" width="30" headerAlign="center" align="center">订单名</div>
				<div field="order_sn" width="80" headerAlign="center" align="center">订单号</div>
				<div field="c_name" width="80" headerAlign="center" align="center">客户名称</div>
				<div field="f_name" width="50" headerAlign="center" align="center">厂家</div>
				<div field="model" width="35" headerAlign="center" align="center">牌号</div>
				<div field="number" width="70" headerAlign="center" allowSort="true" align="center">数量</div>
				<div field="unit_price" width="50" headerAlign="center" allowSort="true" align="center" renderer="tips">单价</div>
				<div field="total" width="60" headerAlign="center" allowSort="true" align="center">总价</div>
				<div field="invoice_status" width="50" headerAlign="center" allowSort="true" align="center">开票状态</div>
				<div field="cmanager" width="40" headerAlign="center" allowSort="true" align="center">交易员</div>
				<?php if ($this->_var['order_type'] != 'saleLog'): ?>
				<div field="in_storage_status" width="50" headerAlign="center" allowSort="true" align="center">入库状态</div>
				<div action='do_p' width="40" headerAlign="center" align="center" renderer="do_p">历史价</div>
				<?php else: ?>
				<div field="out_storage_status" width="50" headerAlign="center" allowSort="true" align="center">出库状态</div>
				<div field="out_check" width="50" headerAlign="center" allowSort="true" align="center" renderer="trans_ch">出库确认</div>
				<div action='do_s' width="40" headerAlign="center"  align="center" renderer="do_s">历史价</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<?php if ($this->_var['type'] == 1): ?>
	<div title="收款列表">
	<?php else: ?>
	<div title="付款列表">
	<?php endif; ?>
		<div class="mini-toolbar"  style="margin:3px 3px 0;">
			<table style="width:100%;">
				<tr>
					<td style="float:right;">
					    <form id="soform1">
						<select name="pay_method" id="pay_method">
							<option value="" selected="selected">=交易方式=</option>
							<?php echo $this->html_options(array('options'=>$this->_var['pay_method'])); ?>
						</select>
						<select name="collection_status" id="collection_status">
							<option value="" selected="selected">=<?php if ($this->_var['type'] == '1'): ?>收款状态<?php else: ?>付款状态<?php endif; ?>=</option>
								<?php echo $this->html_options(array('options'=>$this->_var['collection_status'])); ?>
						</select>

						<select name="invoice_status" id="invoice_status">
							<option value="" selected="selected">=开票状态=</option>
							<?php echo $this->html_options(array('options'=>$this->_var['invoice_status'])); ?>
						</select>

						<a class="mini-button" iconCls="icon-search" onclick="search()">查询</a>
						<span id="searchMsg1"></span>
			  			</form>
					</td>
				</tr>
			</table>
		</div>
		<div class="mini-fit" style="padding:3px;">
			<div id="gridCell" class="mini-datagrid" style="width:auto;height:300px;" <?php if ($this->_var['type'] == 1): ?>url="/application/collection/init?action=grid&oid=<?php echo $this->_var['o_id']; ?>"<?php else: ?>url="/application/collection/itin?action=grid&oid=<?php echo $this->_var['o_id']; ?>"<?php endif; ?>  idField="id"	sizeList="[10,20,50,100]" pageSize="20" multiSelect="true" showFilterRow="true" allowCellSelect="true" allowCellEdit="true">
				<div property="columns">
					<div type="checkcolumn"></div>
					<div field="order_sn" width="80" headerAlign="center" align="center" align="center">订单号</div>
					<div field="total_price" width="40" headerAlign="center" align="center" align="center">总金额</div>
					<div field="collected_price" width="40" headerAlign="center" align="center" align="center"><?php if ($this->_var['type'] == '1'): ?>收款金额<?php else: ?>付款金额<?php endif; ?></div>
					<div field="uncollected_price" width="40" headerAlign="center" align="center" align="center"><?php if ($this->_var['type'] == '1'): ?>未收款金额<?php else: ?>未付款金额<?php endif; ?></div>
					<div field="title" width="30" headerAlign="center" align="center" align="center">交易主题</div>
					<div field="pay_method" renderer="setPay" width="30" headerAlign="center" align="center"><?php if ($this->_var['type'] == '1'): ?>收款方式<?php else: ?>付款方式<?php endif; ?></div>
					<div field="collection_status" renderer="setCol" width="45" headerAlign="center" allowSort="true" align="center"><?php if ($this->_var['type'] == '1'): ?>收款状态<?php else: ?>付款状态<?php endif; ?></div>
					<!-- <div field="remark" renderer="setInv" width="30" headerAlign="center" align="center">备注</div> -->
					<div field="remark" width="30" headerAlign="center" align="center">备注</div>
					<div field="invoice_status" renderer="setInv" width="30" headerAlign="center" align="center" allowSort="true">开票状态</div>
					<div field="payment_time" width="60" headerAlign="center" align="center" allowSort="true" dateFormat="yyyy-MM-dd HH:mm" ><?php if ($this->_var['type'] == '1'): ?>收款日期<?php else: ?>付款日期<?php endif; ?></div>
				</div>
			</div>
		</div>
	</div>
	<div title="开票列表">
		<div class="mini-toolbar"  style="margin:3px 3px 0;">
			<table style="width:100%;">
				<tr>
					<td style="float:right;">
					    <form id="soform2">
						<select name="bile_type" id="bile_type">
							<option value="" selected="selected">=票据类型=</option>
							<?php echo $this->html_options(array('options'=>$this->_var['bile_type'])); ?>
						</select>
						<select name="invoice_one_status" id="invoice_one_status">
							<option value="" selected="selected">=开票状态=</option>
							<?php echo $this->html_options(array('options'=>$this->_var['invoice_one_status'])); ?>
						</select>
						<select name="key_type">
							<option value="invoice_sn">发票号</option>
							<option value="order_sn">订单号</option>
							<option value="total_price">订单金额</option>
						</select>
						<input name="keyword" class="mini-textbox" emptyText="请输入关键词" style="width:140px;" onenter="onKeyEnter"/>
						<a class="mini-button" iconCls="icon-search" onclick="search()">查询</a>
						<span id="searchMsg2"></span>
					  </form>
					</td>
				</tr>
			</table>
		</div>
		<div class="mini-fit" style="padding:3px;">
		  	<div id="billingCell" class="mini-datagrid" style="width:auto;height:300px;"  <?php if ($this->_var['type'] == 1): ?>url="/application/billing/init?action=grid&oid=<?php echo $this->_var['o_id']; ?>"<?php else: ?>url="/application/billing/itin?action=grid&oid=<?php echo $this->_var['o_id']; ?>"<?php endif; ?> idField="id" sizeList="[10,20,50,100]" pageSize="20" multiSelect="true" showFilterRow="true" allowCellSelect="true" allowCellEdit="true">
				<div property="columns">
					<div type="checkcolumn"></div>
					<div field="invoice_sn" width="70" headerAlign="center" align="center" align="center">发票号</div>
					<div field="billing_sn" width="70" headerAlign="center" align="center" align="center">开票号</div>
					<div field="order_sn" width="80" headerAlign="center" align="center" align="center">订单号</div>
					<div field="total_price" width="40" headerAlign="center" align="center" align="center">总金额</div>
					<div field="billing_price" width="40" headerAlign="center" align="center" align="center">本次开票金额</div>
					<div field="unbilling_price" width="40" headerAlign="center" align="center" align="center">未开票金额</div>
					<!-- <div field="tax_price" width="40" headerAlign="center" align="center" align="center">税额</div> -->
					<!-- <div field="rise" width="40" headerAlign="center" align="center" align="center">抬头</div> -->
					<!-- <div field="title" width="30" headerAlign="center" align="center" align="center">合同主题</div> -->
					<!-- <div field="remark" renderer="setInv" width="30" headerAlign="center" align="center">备注</div> -->
					<div field="remark" width="30" headerAlign="center" align="center">备注</div>
					<div field="bile_type" renderer="setBile" width="45" headerAlign="center" allowSort="true" align="center">票据类型</div>
					<div field="invoice_status" renderer="setInvone" width="40" headerAlign="center" align="center" allowSort="true">开票状态</div>
					<div field="payment_time" width="50" headerAlign="center" align="center" allowSort="true" dateFormat="yyyy-MM-dd HH:mm" >开票日期</div>

				</div>
		 	 </div>
		</div>
	</div>
</div>
</div>
<script type="text/javascript">
	mini.parse();
	var dStatus=[{id: 1, text: '正常'}, {id: 2, text: '冻结'}, {id: 3, text: '关闭'}];
	var investGrid=mini.get("investGrid");
	var gridCell=mini.get("gridCell");
	var billingCell=mini.get("billingCell");
	var form = new mini.Form("#infoForm");
	function changeTab(e){
		var tab=e.tab;
		if(tab.title=='详情列表'){
			var data=investGrid.getData();
			if(data.length<1){
				investGrid.load();
			}
		}
		if(tab.title=='收款列表' || tab.title=='付款列表'){
			var data=gridCell.getData();
			if(data.length<1){
				gridCell.load();
			}
		}
		if(tab.title=='开票列表'){
			var data=billingCell.getData();
			if(data.length<1){
				billingCell.load();
			}
		}
	}
	//追加操作按钮
	function onLoadHandle(e) {
		var record = e.record,s='',id = record.id;
		s += '<a href="javascript:viewOrdInfo('+id+')">'+id+'</a> ';
		return s;
	}
	//查看明细相关信息
	function viewOrdInfo(id){
		mini.open({
			url: "/product/saleLog/info?id="+id,
			showMaxButton:true,
			title: "查看明细相关信息",
			width: 700, height:450
		});
	}
	//设置收付款状态

	function setInv(e){
		//总订单开票状态
		var inv= e.record.invoice_status;
		return $("#invoice_status").find("option[value="+inv+"]").text();
	}

	function setInvone(e){
		//单笔订单开票状态
		var inv= e.record.invoice_status;
		return $("#invoice_one_status").find("option[value="+inv+"]").text();
	}
	function setCol(e){
		var col = e.record.collection_status;
		return $("#collection_status").find("option[value="+col+"]").text();
	}
	function setPay(e){
		var pay= e.record.pay_method;
		return $("#pay_method").find("option[value="+pay+"]").text();
	}

	function setBile(e){
		var pay= e.record.bile_type;
		return $("#bile_type").find("option[value="+pay+"]").text();
	}
	function submitcheck(){
	  form.validate();
	  if(form.isValid() == false) return;
	  form.loading("数据提交中，请稍后......");
	  $.post('/product/order/chkInfoSubmit',$("#infoForm").serialize(),function(data){
	      form.unmask();
	      $("#returnMsg").text(data.msg);
	      if(data.err=='0'){
	          CloseWindow("save");
	      }else{
	          return false;
	      }
	  },'json');
	}
	function tips(e) {
		var record = e.record,s='',unit_price = record.unit_price,min_price = record.min_price;
		if(parseFloat(min_price*0.99) > unit_price){
			return '<span style="color:red;">'+unit_price+'</span>';
		}
		return unit_price;
	}
	function trans_ch(e) {
		var record = e.record,out_check = record.out_check;
		if(out_check==1){
			return '是';
		}else{
			return '否';
		}
	}
	function do_p(e){
		var record = e.record,id=record.p_id,o_id=record.o_id;
		var s='<a href="javascript:History_p('+id+","+o_id+');">查看</a>';
		return s;
	}
	function do_s(e){
		var record = e.record,id=record.p_id,o_id=record.o_id;
		var s='<a href="javascript:History_s('+id+","+o_id+');">查看</a>';
		return s;
	}
	//展示10条历史成交价
	function History_s(id,o_id){
			mini.open({
			url: " /order/business/init?p_id="+id+"&o_id="+o_id,
			title: "产品历史成交价",
			width: 1250,
			height: 550,
		});
	}
	function History_p(id,o_id){
			mini.open({
			url: " /order/purchase/init?p_id="+id+"&o_id="+o_id,
			title: "产品历史成交价",
			width: 1250,
			height: 550,
		});
	}
	function CloseWindow(action) {
		if (window.CloseOwnerWindow) return window.CloseOwnerWindow(action);
		else window.close();
	}
	function onCancel(e) {
		CloseWindow("cancel");
	}
</script>