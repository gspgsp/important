__layout|public:mini_layout|layout__
<div style="padding:5px;">
	<div class="mini-tabs" activeIndex="0" plain="false" onactivechanged="changeTab">
		<div title="客户详细信息">
			<div style="height:540px">
				<table width="100%">
					<caption><strong>基本信息</strong></caption>
					<tr>
						<td>客户名称：</td>
						<td>
							<?php echo $this->_var['info']['c_name']; ?>
						</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>法人身份证：</td>
						<td><?php echo $this->_var['info']['legal_idcard']; ?></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>填写牌号：</td>
						<td colspan="3"><span style="color:red;">(手机端填写)</span><?php echo $this->_var['info']['need_product']; ?></td>
					</tr>
					<tr>
						<td>所需牌号：</td>
						<td colspan="3"><span style="color:red;">(系统生成)</span><?php echo $this->_var['info']['need_product_adm']; ?></td>
					</tr>
					<tr>
						<td>主营商品：</td>
						<td><?php echo $this->_var['info']['main_product']; ?></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>月用量：</td>
						<td><?php echo $this->_var['info']['month_consum']; ?></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>备注：</td>
						<td><?php echo $this->_var['info']['need_product']; ?></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<!-- <td>邮政编码：</td>
						<td>
							<?php echo $this->_var['info']['zip']; ?>
						</td> -->
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>信用等级：</td>
						<td><?php echo $this->_var['credit_level'][$this->_var['info']['credit_level']]; ?></td>
					</tr>
					<tr>
						<td>成立日期：</td>
						<td><?php echo $this->_var['info']['fund_date']; ?>
						</td>
						<td>注册资本：</td>
						<td>
							<?php echo $this->_var['info']['registered_capital']; ?>
							万
						</td>
					</tr>
					<tr>
						<td>所在省市</td>
						<td><?php echo $this->_var['regionList'][$this->_var['info']['company_province']]; ?>,<?php echo $this->_var['regionList'][$this->_var['info']['company_city']]; ?></td>
						<td>公司地址：</td>
						<td><?php echo $this->_var['info']['address']; ?></td>
					</tr>
					<tr>
						<td>客户类型：</td>
						<td><?php echo $this->_var['type'][$this->_var['info']['type']]; ?></td>
						<!-- <td>客户级别：</td>
						<td><?php echo $this->_var['level'][$this->_var['info']['level']]; ?></td> -->
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>客户渠道：</td>
						<td><?php echo $this->_var['chanel'][$this->_var['info']['chanel']]; ?></td>
						<td>状态：</td>
						<td><?php echo $this->_var['status'][$this->_var['info']['status']]; ?></td>
					</tr>
					<tr>
						<td>附件地址：</td>
						<td><?php if ($this->_var['info']['file_url']): ?><a href="<?php echo $this->_var['info']['file_url1']; ?>">查看</a> <?php else: ?>--<?php endif; ?></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
				</table>
				<table width="100%">
					<caption><strong>扩展信息</strong></caption>
					<?php if ($this->_var['info']['merge_three'] != 1): ?>
					<tr>
						<td>营业执照号：</td>
						<td><?php echo $this->_var['info']['business_licence']; ?></td>
						<td>执照照片：</td>
						<td><?php if ($this->_var['info']['business_licence_pic']): ?><a href="<?php echo $this->_var['info']['business_licence_pic1']; ?>">查看</a> <?php else: ?>--<?php endif; ?></td>
					</tr>
					<tr>
						<td>组织代码：</td>
						<td><?php echo $this->_var['info']['organization_code']; ?></td>
						<td>组织图片：</td>
						<td><?php if ($this->_var['info']['organization_pic']): ?><a href="<?php echo $this->_var['info']['organization_pic1']; ?>">查看</a> <?php else: ?>--<?php endif; ?></td>
					</tr>
					<tr>
						<td>税务登记码：</td>
						<td><?php echo $this->_var['info']['tax_registration']; ?></td>
						<td>税务图片：</td>
						<td><?php if ($this->_var['info']['tax_registration_pic']): ?><a href="<?php echo $this->_var['info']['tax_registration_pic1']; ?>">查看</a> <?php else: ?>--<?php endif; ?></td>
					</tr>
					<?php else: ?>
					<tr>
						<td>统一社会信用代码：</td>
						<td><?php echo $this->_var['info']['business_licence']; ?></td>
						<td>证件图片：</td>
						<td><?php if ($this->_var['info']['business_licence_pic']): ?><a href="<?php echo $this->_var['info']['business_licence_pic1']; ?>">查看</a> <?php else: ?>--<?php endif; ?></td>
					</tr>
					<?php endif; ?>
					<tr>
						<td>法人姓名：</td>
						<td><?php echo $this->_var['info']['legal_person']; ?></td>
						<td>身份证照片：</td>
						<td><?php if ($this->_var['info']['legal_idcard_pic']): ?><a href="<?php echo $this->_var['info']['legal_idcard_pic1']; ?>">查看</a> <?php else: ?>--<?php endif; ?></td>
					</tr>
				</table>
			</div>
		</div>
		<div title="联系人列表">
			<div id="investGrid" class="mini-datagrid" style="width:auto;height:540px;" idField="id"  url="/user/contact/init?action=grid&c_id=<?php echo $this->_var['c_id']; ?>&filte=1" sizeList="[10,20,50,100]" pageSize="20">
				<div property="columns">
					<div field="user_id" width="50" headerAlign="center" renderer="onLoadHandle" align="center">联系人ID</div>
					<div field="name" width="50" headerAlign="center" align="center">联系人</div>
					<div field="is_default" width="50" headerAlign="center" align="center">是/否默认联系人</div>
					<div field="c_id" width="80" headerAlign="center" align="center">公司</div>
					<div field="sex" width="60" headerAlign="center"  align="center">性别</div>
					<div field="mobile" width="80" headerAlign="center" align="center">手机号</div>
					<div field="qq" width="80" headerAlign="center" align="center">qq号</div>
					<div field="email" width="80" headerAlign="center" align="center">邮箱</div>
					<div field="customer_manager" width="80" headerAlign="center" align="center">交易员</div>
					<div field="depart" width="80" headerAlign="center" align="center">所属部门</div>
					<div field="input_time" width="80" headerAlign="center" dateFormat="yyyy-MM-dd HH:mm" align="center">创建时间</div>
					<div field="status" width="40" headerAlign="center" type="comboboxcolumn" autoShowPopup="true" align="center">状态
						<input property="editor" class="mini-combobox" style="width:100%;" data="dStatus" />
					</div>
				</div>
			</div>
		</div>
		<div title="跟进列表">
			<div id="gridCell" class="mini-datagrid" style="width:auto;height:540px;" url="/user/follow/init?action=grid&c_id=<?php echo $this->_var['c_id']; ?>"  idField="id"
				 sizeList="[10,20,50,100]" pageSize="20">
				<div property="columns">
					<div field="name" width="30" headerAlign="center" allowSort="true" align="center">联系人</div>
					<div field="remark" width="120" headerAlign="center" allowSort="true" align="center">跟进内容</div>
					<div field="follow_up_way" width="30" headerAlign="center" allowSort="true" align="center">跟进方式</div>
					<div field="follow_time" width="40" headerAlign="center" dateFormat="yyyy-MM-dd HH:mm" allowSort="true" align="center">跟进时间</div>
				</div>
			</div>
		</div>
		<div title="采购记录">
			<div class="mini-toolbar" style="margin:3px 3px 0;">
				<table style="width:100%;">
					<tr>
						<td style="float:right;">
							<form id="soform">
								<span id="searchMsg"></span>
								<select name="sTime">
									<option value="input_time">创建时间</option>
									<option value="update_tim出e">更新时间</option>
									<option value="sign_time">签订日期</option>
									<option value="pickup_time">提货日期</option>
									<option value="delivery_time">送货日期</option>
									<option value="payment_time">付款日期</option>

								</select>
								<input name="startTime" class="mini-datepicker" style="width:95px;"/> -
								<input name="endTime" class="mini-datepicker" style="width:95px;"/>
								<select name="key_type">
									<option value="order_sn">订单号</option>
									<option value="c_id">客户名称</option>
									<option value="input_admin">交易员</option>
								</select>
								<input name="keyword" class="mini-textbox" emptyText="" style="width:100px;" onenter="onKeyEnter"/>
								<a class="mini-button" iconCls="icon-search" onclick="search()">查询</a>
								<span id="searchMsg"></span></form>
						</td>
					</tr>
				</table>
			</div>
			<div id="buy_log" class="mini-datagrid" style="width:auto;height:540px;"url="/product/order/init?action=grid&order_type=2&c_id=<?php echo $this->_var['info']['c_id']; ?>"  idField="id"
				 sizeList="[10,20,50,100]" pageSize="20" allowCellWrap="true">
				<div property="columns">
					<div field="order_sn" width="120" headerAlign="center" align="center" allowSort="true" renderer="see_order">订单号</div>
					<div field="order_name" width="30" headerAlign="center" align="center">抬头</div>
					<div field="total_num" width="40" headerAlign="center" align="center" allowSort="true">总数</div>
					<div field="total_price" width="60" headerAlign="center" align="center" allowSort="true">总金额</div>
					<div field="in_storage_status" width="45" headerAlign="center" align="center" >入库状态</div>
					<div field="out_storage_status" width="45" headerAlign="center" align="center">出库状态</div>
					<div field="invoice_status" width="45" headerAlign="center" align="center" >开票状态</div>
					<div field="remark" width="45" headerAlign="center" align="center" >备注</div>
					<div field="financial_records" width="45" headerAlign="center" align="center">财务记录</div>
					<div field="input_time" width="75" headerAlign="center" align="center" allowSort="true" dateFormat="yyyy-MM-dd HH:mm">创建时间</div>
					<div field="cmanager" width="40" headerAlign="center" align="center">交易员</div>
					<div name="action" width="35" headerAlign="center" align="center" cellStyle="padding:0;" renderer="onJoin">关联</div>
				</div>
			</div>
		</div>
		<div title="销售记录">
			<div class="mini-toolbar" style="margin:3px 3px 0;">
				<table style="width:100%;">
					<tr>
						<td style="float:right;">
							<form id="soform1">
								<span id="searchMsg1"></span>
								<select name="sTime">
									<option value="input_time">创建时间</option>
									<option value="update_tim出e">更新时间</option>
									<option value="sign_time">签订日期</option>
									<option value="pickup_time">提货日期</option>
									<option value="delivery_time">送货日期</option>
									<option value="payment_time">付款日期</option>

								</select>
								<input name="startTime" class="mini-datepicker" style="width:95px;"/> -
								<input name="endTime" class="mini-datepicker" style="width:95px;"/>
								<select name="key_type">
									<option value="order_sn">订单号</option>
									<option value="c_id">客户名称</option>
									<option value="input_admin">交易员</option>
								</select>
								<input name="keyword" class="mini-textbox" emptyText="" style="width:100px;" onenter="onKeyEnter1"/>
								<a class="mini-button" iconCls="icon-search" onclick="search2()">查询</a>
							</form>
						</td>
					</tr>
				</table>
			</div>
			<div id="sale_log" class="mini-datagrid" style="width:auto;height:540px;"url="/product/order/init?action=grid&order_type=1&c_id=<?php echo $this->_var['info']['c_id']; ?>"  idField="id"
				 sizeList="[10,20,50,100]" pageSize="20"  allowCellWrap="true">
				<div property="columns">
					<div field="order_sn" width="120" headerAlign="center" align="center" allowSort="true" renderer="see_order">订单号</div>
					<div field="order_name" width="30" headerAlign="center" align="center">抬头</div>
					<div field="total_num" width="40" headerAlign="center" align="center" allowSort="true">总数</div>
					<div field="total_price" width="60" headerAlign="center" align="center" allowSort="true">总金额</div>
					<div field="out_storage_status" width="45" headerAlign="center" align="center">出库状态</div>
					<div field="invoice_status" width="45" headerAlign="center" align="center" >开票状态</div>
					<div field="remark" width="45" headerAlign="center" align="center" >备注</div>
					<div field="financial_records" width="45" headerAlign="center" align="center">财务记录</div>
					<div field="input_time" width="75" headerAlign="center" align="center" allowSort="true" dateFormat="yyyy-MM-dd HH:mm">创建时间</div>
					<div field="cmanager" width="40" headerAlign="center" align="center">交易员</div>
					<div name="action" width="35" headerAlign="center" align="center" cellStyle="padding:0;" renderer="onJoin">关联</div>
				</div>
			</div>
		</div>
		<div title="流转记录">
			<div class="mini-toolbar" style="margin:3px 3px 0;">
				<table style="width:100%;">
				  <tr>
					<td style="white-space:nowrap;"><form id="soform">
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
			<div id="c_follow" class="mini-datagrid" style="width:auto;height:540px;"url="/report/record/init?action=grid&c_id=<?php echo $this->_var['info']['c_id']; ?>"  idField="id"
				 sizeList="[10,20,50,100]" pageSize="20"  allowCellWrap="true">
				<div property="columns">
					<div field="id" width="40" headerAlign="center" align="center">ID</div>
					<div field="action_type" width="100" headerAlign="center" allowSort="true" align="center">操作类型</div>
					<div field="old_value" width="120" headerAlign="center" align="center">操作之前</div>
					<div field="new_value" width="120" headerAlign="center"align="center">操作之后</div>
					<div field="success" width="50" headerAlign="center" allowSort="true" align="center">是否成功</div>
					<div field="input_time" width="70" headerAlign="center" dateFormat="yyyy-MM-dd HH:mm:ss" allowSort="true" align="center">请求时间</div>
					<div field="operator" width="60" headerAlign="center" align="center">操作员</div>
					<div field="user_ip" width="60" headerAlign="center" allowSort="true" align="center">用户IP</div>
					<div field="description" width="220" headerAlign="center" allowSort="true">操作描述</div>
				</div>
			</div>
		</div>
	</div>
</div>
	<script type="text/javascript">
		mini.parse();
		var dStatus=[{id: 1, text: '正常'}, {id: 2, text: '冻结'}, {id: 3, text: '关闭'}];
		var investGrid=mini.get("investGrid"), gridCell  =mini.get("gridCell"), buy_log  =mini.get("buy_log"), sale_log  =mini.get("sale_log"), c_follow  =mini.get("c_follow");
		function changeTab(e){
			var tab=e.tab;
			if(tab.title=='联系人列表'){
				var data=investGrid.getData();
				if(data.length<1){
					investGrid.load();
				}
			}
			if(tab.title=='跟进列表'){
				var data=gridCell.getData();
				if(data.length<1){
					gridCell.load();
				}
			}
			if(tab.title=='采购记录'){
				var data=buy_log.getData();
				if(data.length<1){
					buy_log.load();
				}
			}
			if(tab.title=='销售记录'){
				var data=sale_log.getData();
				if(data.length<1){
					sale_log.load();
				}
			}
			if(tab.title=='流转记录'){
				var data=c_follow.getData();
				if(data.length<1){
					c_follow.load();
				}
			}
		}
		//编辑联系人
		function onLoadHandle(e) {
			var record = e.record,uid=record.user_id, s='';
			s += '<a href="javascript:viewContactInfo('+uid+')">'+uid+'</a> ';

			return s;
		}
		//追加关联
		function onJoin(e) {
			var record = e.record,s='',o_id = record.o_id,s='',join_id = record.join_id,store_o_id=record.store_o_id,sales_type=record.sales_type;

			if(store_o_id > 0 && join_id == 0){
				s += '<a href="javascript:viewOrdInfo('+store_o_id+')">关联</a> ';
			}else if(store_o_id == 0 && join_id > 0){
				s += '<a href="javascript:viewOrdInfo('+join_id+')">关联</a> ';
			}else if(o_id == join_id && join_id>0 && store_o_id>0){
				s += '<a href="javascript:viewOrdInfo('+store_o_id+')">关联</a> ';
			}else if(o_id == store_o_id && join_id>0 && store_o_id>0){
				s += '<a href="javascript:viewOrdInfo('+join_id+')">关联</a> ';
			}
			return s;
		}
		function see_order(e){
			var record = e.record,s='',o_id = record.o_id,sn = record.order_sn;
			return '<a href="javascript:viewOrdInfo('+o_id+')">'+sn+'</a> ';
		}
		//查看订单相关信息
		function viewOrdInfo(oid,o_type){
			mini.open({
				url: "/application/order/info?oid="+oid+'&o_type='+o_type,
				showMaxButton:true,
				title: "查看订单相关信息",
				width: 800, height:460
			});
		}
		function search() {
			buy_log.load($("#soform").serializeObject(),function(e){
				$("#searchMsg").html(e.msg);
			});
		}
		function search2() {
			sale_log.load($("#soform1").serializeObject(),function(e){
				$("#searchMsg1").html(e.msg);
			});
		}
		search();
		search2();
		function onKeyEnter(e) {
			search();
		}
		function onKeyEnter1(e) {
			search2();
		}

	</script>