__layout|public:mini_layout|layout__
<div style="padding:5px;">	
		<div title="订单详细信息">
			<div style="height:385px">
				<table width="100%">
					<caption><strong>明细详情</strong></caption>
					<tr>
						<td>订单名称：</td>
						<td><?php echo $this->_var['order_name']; ?></td>
						<td>订单编号：</td>
						<td><?php echo $this->_var['info']['order_sn']; ?></td>
						
					</tr>
					<tr>
						<td>牌号：</td>
						<td><?php echo $this->_var['info']['p_info']['model']; ?></td>
						<td>销售类型：</td>  
						<td><?php echo $this->_var['sales_type'][$this->_var['info']['sales_type']]; ?></td>
					</tr>
					<tr>
						<td>交易员：</td>  
						<td><?php echo $this->_var['info']['input_admin']; ?></td>
						<td>厂家;</td>
						<td><?php echo $this->_var['info']['p_info']['f_name']; ?></td>
					</tr>
					<tr>
						<td>产品类型：</td>
						<td><?php echo $this->_var['product_type'][$this->_var['info']['p_info']['product_type']]; ?></td>
						<td>加工级别：</td>
						<td><?php echo $this->_var['process_type'][$this->_var['info']['p_info']['process_type']]; ?></td>
					</tr>
					<tr>
						<td>数量：</td>
						<td><?php echo $this->_var['info']['number']; ?></td>
						<td>单价</td>
						<td><?php echo $this->_var['info']['unit_price']; ?></td>
					</tr>
					<tr>
						<td>总价 : </td>
						<td><?php echo $this->_var['info']['count']; ?></td>
						<td>批次号 :</td>
						<td><?php echo $this->_var['info']['lot_num']; ?></td>
					</tr>
					<tr>
						<td>仓库：</td>
						<td><?php echo $this->_var['info']['store_name']; ?></td>
						<td>出库人:</td>
						<td><?php echo $this->_var['info']['admin_name']; ?></td>
					</tr>
					<tr>
						<td>出库状态 : </td>
						<td><?php echo $this->_var['out_storage_status'][$this->_var['info']['out_storage_status']]; ?></td>
						<td>开票状态：</td>
						<td><?php echo $this->_var['invoice_status'][$this->_var['info']['invoice_status']]; ?></td>
					</tr>
					<tr>
						<td>备注 : </td>
						<td><?php echo $this->_var['info']['remark']; ?></td>
					</tr>
				</table>

			</div>
		</div>
</div>
<script type="text/javascript">
</script>