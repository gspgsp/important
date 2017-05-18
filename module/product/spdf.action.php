<?php

class spdfAction extends adminBaseAction {
	public function __init(){
		ini_set('display_errors','on');
		E('TCPdf',APP_LIB.'extend');
		$this->debug = false;
		$this->template = $this->temp();
		$this->db=M('public:common')->model('out_logs');
	}

	/**
	 * 合同 PDF
	 *
	 */
	public function tihuo(){
			// foreach($detiles as $k => $v){
			// 	$sign = round($v['number']*$v['unit_price'],2);
			// 	$detail_info .= '<tr >
			// 		<td bgcolor="#FFFFFF" align="center"  height="20" style="line-height:20px;">'.L('product_type')[$v['product_type']].'</td>
			// 		<td bgcolor="#FFFFFF" align="center"  height="20" style="line-height:20px;">'.strtoupper($v['model']).'</td>
			// 		<td bgcolor="#FFFFFF" align="center"  height="20" style="line-height:20px;">'.$v['f_name'].'</td>
			// 		<td bgcolor="#FFFFFF" align="center"  height="20" style="line-height:20px;">吨</td>
			// 		<td bgcolor="#FFFFFF" align="center"  height="20" style="line-height:20px;">'.$v['number'].'</td>
			// 		<td bgcolor="#FFFFFF" align="center"  height="20" style="line-height:20px;">'.$v['unit_price'].'</td>
			// 		<td bgcolor="#FFFFFF" align="center"  height="20" style="line-height:20px;">'.$sign.'</td>
			// 	</tr >';
			// 	$totall += $sign;
			// }
			$contract = $this->template['tihuo'];
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
			$pdf->SetTitle('上海中晨电商合同报表');
			$pdf->SetHeaderData('config/pdflogo.jpg', 180, '','', array(0,33,43), array(0,64,128));
			// 设置默认等宽
			$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
			// 设置间距
			$pdf->SetMargins(15, 20, 15);
			$pdf->SetHeaderMargin(3);
			$pdf->SetFooterMargin(10);
			// 设置分页
			$pdf->SetAutoPageBreak(TRUE, 25);
			$pdf->setImageScale(1.25);
			$pdf->setFontSubsetting(true);
			// 设置中文字体
			$pdf->SetFont('stsongstdlight','', 10);
			$pdf->AddPage();
			// $total=$this->_cny($totall);
			// $table1='<tr>
			// 		<td width="130" bgcolor="#FFFFFF" align="center"  height="20" style="line-height:20px;">产品名称</td>
			// 		<td width="80" bgcolor="#FFFFFF" align="center"  height="20" style="line-height:20px;">规格/型号</td>
			// 		<td width="140" bgcolor="#FFFFFF" align="center"  height="20" style="line-height:20px;">产地</td>
			// 		<td width="30" bgcolor="#FFFFFF" align="center"  height="20" style="line-height:20px;">单位</td>
			// 		<td width="75" bgcolor="#FFFFFF" align="center"  height="20" style="line-height:20px;">数量</td>
			// 		<td width="82" bgcolor="#FFFFFF" align="center"  height="20" style="line-height:20px;">单价</td>
			// 		<td width="90" bgcolor="#FFFFFF" align="center"  height="20" style="line-height:20px;">金额</td>
			// 	</tr>
			// 	' .$detail_info. '
			// 	<tr>
			// 		<td bgcolor="#FFFFFF"  height="20" style="line-height:20px;">合计</td>
			// 		<td colspan="6" bgcolor="#FFFFFF" class="number"  height="20" style="line-height:20px;">'.$totall.'</td>
			// 	</tr>
			// 	<tr>
			// 		<td bgcolor="#FFFFFF"  height="20" style="line-height:20px;">合计人民币（大写）</td>
			// 		<td colspan="6" bgcolor="#FFFFFF" class="number"  height="20" style="line-height:20px;">'.$total.'</td>
			// 	</tr>
			// 	';
			$pdf->writeHTMLCell(0, 0, '', '', $contract, 0, 1, 0, true, '', true);
			// 输出pdf
			$pdf->Output("1234.pdf", 'I');
		}
	/**
	 * 货权转移
	 * @Author   cuiyinming               QQ:1203116460
	 * @DateTime 2017-05-18T15:03:51+0800
	 * @return   [type]                   [description]
	 */
	public function zhuanyi(){
		$contract = $this->template['zhuanyi'];
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->SetTitle('上海中晨电商合同报表');
		$pdf->SetHeaderData('config/pdflogo.jpg', 180, '','', array(0,33,43), array(0,64,128));
		// 设置默认等宽
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		// 设置间距
		$pdf->SetMargins(15, 20, 15);
		$pdf->SetHeaderMargin(3);
		$pdf->SetFooterMargin(10);
		// 设置分页
		$pdf->SetAutoPageBreak(TRUE, 25);
		$pdf->setImageScale(1.25);
		$pdf->setFontSubsetting(true);
		// 设置中文字体
		$pdf->SetFont('stsongstdlight','', 10);
		$pdf->AddPage();
		$pdf->writeHTMLCell(0, 0, '', '', $contract, 0, 1, 0, true, '', true);
		// 输出pdf
		$pdf->Output("1234.pdf", 'I');
	}
	/**
	 * 货权委托单
	 * @Author   cuiyinming               QQ:1203116460
	 * @DateTime 2017-05-18T15:03:51+0800
	 * @return   [type]                   [description]
	 */
	public function weituo(){
		$contract = $this->template['weituo'];
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->SetTitle('上海中晨电商合同报表');
		$pdf->SetHeaderData('config/pdflogo.jpg', 180, '','', array(0,33,43), array(0,64,128));
		// 设置默认等宽
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		// 设置间距
		$pdf->SetMargins(15, 20, 15);
		$pdf->SetHeaderMargin(3);
		$pdf->SetFooterMargin(10);
		// 设置分页
		$pdf->SetAutoPageBreak(TRUE, 25);
		$pdf->setImageScale(1.25);
		$pdf->setFontSubsetting(true);
		// 设置中文字体
		$pdf->SetFont('stsongstdlight','', 10);
		$pdf->AddPage();
		$pdf->writeHTMLCell(0, 0, '', '', $contract, 0, 1, 0, true, '', true);
		// 输出pdf
		$pdf->Output("1234.pdf", 'I');
	}
		/**
		 * 人民币转文字
		 * @Author   cuiyinming
		 * @DateTime 2016-06-16T12:17:28+0800
		 * @param    [type]                   $ns [description]
		 * @return   [type]                       [description]
		 */
		private function _cny($ns){
			static $cnums = array("零","壹","贰","叁","肆","伍","陆","柒","捌","玖"),
			$cnyunits = array("圆","角","分"),
			$grees = array("拾","佰","仟","万","拾","佰","仟","亿");
			list($ns1,$ns2) = explode(".",$ns,2);
			$ns2 = array_filter(array($ns2[1],$ns2[0]));
			$ret = array_merge($ns2,array(implode("",$this->_cny_map_unit(str_split($ns1), $grees)), ""));
			$ret = implode("",array_reverse($this->_cny_map_unit($ret,$cnyunits)));
			return str_replace(array_keys($cnums), $cnums,$ret);
		}
		/**
		 * 循环处理
		 * @Author   cuiyinming
		 * @DateTime 2016-06-16T12:18:07+0800
		 * @param    [type]                   $list  [description]
		 * @param    [type]                   $units [description]
		 * @return   [type]                          [description]
		 */
		private function _cny_map_unit($list,$units){
			$ul = count($units);
			$xs = array();
			foreach (array_reverse($list) as $x)
			{
				$l = count($xs);
				if($x!="0" || !($l%4))
				{
					$n=($x=='0'?'':$x).($units[($l-1)%$ul]);
				}
				else
				{
					$n=is_numeric($xs[0][0]) ? $x : '';
				}
				array_unshift($xs, $n);
			}
			return $xs;
		}
		//全局模板文件
		private function temp(){
			return array(
				'tihuo'=>'<table width="680" border="0" cellpadding="0" cellpadding="0" align="center">
					<tr height="30">
						<td colspan="2" align="center"><h1 style="font-size:16px;">上海中晨电子商务股份有限公司</h1></td>
					</tr>
					<tr height="30">
						<td colspan="2" align="center"><h2 style="font-size:14px;"><u>提货单</u></h2></td>
					</tr>
					<tr height="30">
						<td>此单发给：<u>上架供应商名称</u></td>
						<td align="right">NO：系统自动显示</td>
					</tr>
					<tr height="30">
						<td>传真：<u>供应商传真</u></td>
						<td align="right">2017年5月17日</td>
					</tr>
					<tr height="30">
						<td colspan="2">
							<table width="680" cellpadding="0" cellspacing="1" bgcolor="#ccc">
								<tr height="30" align="center">
									<td bgcolor="#fff">产品名称</td>
									<td bgcolor="#fff">规格/型号</td>
									<td bgcolor="#fff">数量（吨）</td>
									<td bgcolor="#fff">件数（包）</td>
									<td bgcolor="#fff">提货车辆牌号</td>
									<td bgcolor="#fff">司机姓名，身份证</td>
								</tr>
								<tr height="30" align="center">
									<td bgcolor="#fff">（根据订单内容自动显示）</td>
									<td bgcolor="#fff">（根据订单内容自动显示）</td>
									<td bgcolor="#fff">填写</td>
									<td bgcolor="#fff">数量顿数*40</td>
									<td bgcolor="#fff">填写</td>
									<td bgcolor="#fff">填写</td>
								</tr>
								<tr height="30" align="center">
									<td bgcolor="#fff">&nbsp;</td>
									<td bgcolor="#fff">&nbsp;</td>
									<td bgcolor="#fff">&nbsp;</td>
									<td bgcolor="#fff">&nbsp;</td>
									<td bgcolor="#fff">&nbsp;</td>
									<td bgcolor="#fff">&nbsp;</td>
								</tr>
								<tr height="30" align="center">
									<td bgcolor="#fff">&nbsp;</td>
									<td bgcolor="#fff">&nbsp;</td>
									<td bgcolor="#fff">&nbsp;</td>
									<td bgcolor="#fff">&nbsp;</td>
									<td bgcolor="#fff">&nbsp;</td>
									<td bgcolor="#fff">&nbsp;</td>
								</tr>
								<tr height="30">
									<td bgcolor="#fff" align="center">备&nbsp;&nbsp;注</td>
									<td bgcolor="#fff" colspan="5">&nbsp;&nbsp;&nbsp;请速传至仓库（可更改）</td>
								</tr>
								<tr height="30">
									<td bgcolor="#fff" align="center">仓&nbsp;&nbsp;库</td>
									<td bgcolor="#fff" colspan="5">&nbsp;&nbsp;&nbsp;仓库地址</td>
								</tr>
								<tr height="90">
									<td bgcolor="#fff" align="center">说&nbsp;&nbsp;明</td>
									<td bgcolor="#fff" colspan="5">
										<table cellpadding="0" cellspacing="0" border="0">
											<tr>
												<td>1、此提货单需加盖本公司提货专用章方可有效。</td>
											</tr>
											<tr>
												<td><hr color="#ccc" width="535" size="1"/></td>
											</tr>
											<tr>
												<td>2、本提货单有效期为<u>贰</u>天，手写无效。</td>
											</tr>
											<tr>
												<td><hr color="#ccc" width="535" size="1"/></td>
											</tr>
											<tr>
												<td>3、原厂原包装，保证品质：无破包、无污损，装货过程中如有破包，烦请贵公司负责安排更换。</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr height="30">
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr height="30">
						<td colspan="2"><u>如有问题，请联系：（提交订单的助理姓名和电话）</u></td>
					</tr>
				</table>',
				'weituo'=>'<table width="635" border="0" cellpadding="0" cellpadding="0" align="center">
						<tr height="30">
							<td>NO：</td>
						</tr>
						<tr height="30">
							<td align="center"><h1 style="font-size:16px;">委托送货单</h1></td>
						</tr>
						<tr height="30">
							<td><h2 style="font-size:14px;">TO:供应商名称</h2></td>
						</tr>
						<tr height="30">
							<td>请将我司采购的塑料粒子合同编号：（填写）安排送货：</td>
						</tr>
						<tr>
							<td>
								<table width="635" cellpadding="0" cellspacing="1" bgcolor="#ccc">
									<tr height="30" align="center">
										<td bgcolor="#fff">产品名称</td>
										<td bgcolor="#fff">规格/型号</td>
										<td bgcolor="#fff">产地</td>
										<td bgcolor="#fff">数量（吨）</td>
									</tr>
									<tr height="30" align="center">
										<td bgcolor="#fff">（根据订单内容自动显示）</td>
										<td bgcolor="#fff">（根据订单内容自动显示）</td>
										<td bgcolor="#fff">（根据订单内容自动显示）</td>
										<td bgcolor="#fff">（根据订单内容自动显示）</td>
									</tr>
									<tr height="30">
										<td bgcolor="#fff" colspan="4">&nbsp;&nbsp;&nbsp;配送地址&电话：</td>
									</tr>
									<tr height="30">
										<td bgcolor="#fff" colspan="4">&nbsp;&nbsp;&nbsp;备注：</td>
									</tr>
									<tr height="30">
										<td bgcolor="#fff" colspan="4">&nbsp;&nbsp;&nbsp;注明：无破包，不湿料，不少料</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr height="30">
							<td>（此单手写无效）</td>
						</tr>
						<tr height="30">
							<td align="right">上海中晨电子商务股份有限公司</td>
						</tr>
						<tr height="30">
							<td align="right">2017年5月17日</td>
						</tr>
						<tr height="30">
							<td>如有问题，请联系：（助理电话和姓名）</td>
						</tr>
					</table>',
				'zhuanyi'=>'<table width="635" border="0" cellpadding="0" cellpadding="0" align="center">
					<tr height="30">
						<td>NO：</td>
					</tr>
					<tr height="30">
						<td align="center"><h1 style="font-size:16px;">货权转移单</h1></td>
					</tr>
					<tr height="30">
						<td><h2 style="font-size:14px;">TO（供应商）：供应商名称</h2></td>
					</tr>
					<tr height="30">
						<td>请将我司采购的塑料粒子合同编号：（填写）</td>
					</tr>
					<tr>
						<td>
							<table width="635" cellpadding="0" cellspacing="1" bgcolor="#ccc">
								<tr height="30" align="center">
									<td bgcolor="#fff">产品名称</td>
									<td bgcolor="#fff">规格/型号</td>
									<td bgcolor="#fff">产地</td>
									<td bgcolor="#fff">数量（吨）</td>
								</tr>
								<tr height="30" align="center">
									<td bgcolor="#fff">（根据订单内容自动显示）</td>
									<td bgcolor="#fff">（根据订单内容自动显示）</td>
									<td bgcolor="#fff">（根据订单内容自动显示）</td>
									<td bgcolor="#fff">（根据订单内容自动显示）</td>
								</tr>
								<tr height="30">
									<td bgcolor="#fff" colspan="4">&nbsp;&nbsp;&nbsp;仓库地址：（填写）</td>
								</tr>
								<tr height="30">
									<td bgcolor="#fff" colspan="4">&nbsp;&nbsp;&nbsp;备注：承担费用的注明（填写）</td>
								</tr>
								<tr height="30">
									<td bgcolor="#fff" colspan="4">&nbsp;&nbsp;&nbsp;注明：无破包，不湿料，不少料</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr height="30">
						<td>以上货物的货权转至<u>（填写）</u></td>
					</tr>
					<tr height="30">
						<td>请将入库单传真至：<u>（填写订单的物流助理传真和电话）</u></td>
					</tr>
					<tr height="30">
						<td>（此单手写无效）</td>
					</tr>
					<tr height="30">
						<td align="right">上海中晨电子商务股份有限公司</td>
					</tr>
					<tr height="30">
						<td align="right">2017年5月17日</td>
					</tr>
				</table>',
			);
		}
	}