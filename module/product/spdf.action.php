<?php

class spdfAction extends adminBaseAction {
	public function __init(){
		ini_set('display_errors', 'on');
		E('TCPdf',APP_LIB.'extend');
		$this->debug = false;
		$this->template = $this->temp();
		$this->db=M('public:common')->model('out_logs');
		$ids = sget('id','s');
		$data = $this->db->where("id in ($ids)")->getAll();
		//获取订单的联系人信息
		$ship =M('rbac:adm')->getUserInfoById($data[0]['store_aid']);
		$this->ship_adm = $ship['name'].'/'.$ship['mobile'];

		//处理基础信息
		foreach ($data as &$v) {
			$v['product_info'] = M("product:product")->getFnameByPid($v['p_id']);
		}
		$oid = intval($this->db->model('sale_log')->select('o_id')->where("`id` = {$data[0]['sale_id']}")->getOne());
		$oinfo = $this->db->model('order')->where("`o_id` = $oid")->getRow();
		$this->company = L('companys')[$oinfo['order_name']];
		$this->cname = M('user:customer')->getColByName($oinfo['c_id']);
		$this->out_no = $this->db->model('out_storage')->select('out_no')->where("id = {$data[0]['storage_id']}")->getOne();
		$this->info = $data;
	}

	/**
	 * 合同 PDF
	 *
	 */
	public function tihuo(){
			//根据id获取详情
			$detail_info = '';
			foreach($this->info as $k => $v){
				$detail_info .= '<tr height="30">
					<td bgcolor="#FFFFFF" align="center"  height="20" style="line-height:20px;">'.L('product_type')[$v['product_info']['product_type']].'</td>
					<td bgcolor="#FFFFFF" align="center"  height="20" style="line-height:20px;">'.strtoupper($v['product_info']['model']).'</td>
					<td bgcolor="#FFFFFF" align="center"  height="20" style="line-height:20px;">'.$v['number'].'</td>
					<td bgcolor="#FFFFFF" align="center"  height="20" style="line-height:20px;">'.ceil($v['number']*40).'</td>
					<td bgcolor="#FFFFFF" align="center"  height="20" style="line-height:20px;">'.$v['car_code'].'</td>
					<td bgcolor="#FFFFFF" align="center"  height="20" style="line-height:20px;">'.$v['driver'].'</td>
				</tr >';
			}
			$contract = $this->template['tihuo'];
			$contract = sprintf($contract,$this->company,$this->out_no,$this->cname,$this->info[0]['fax'],$detail_info,$this->info[0]['remark'],$this->info[0]['store_address'],$this->ship_adm);
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
	 * 货权转移
	 * @Author   cuiyinming               QQ:1203116460
	 * @DateTime 2017-05-18T15:03:51+0800
	 * @return   [type]                   [description]
	 */
	public function zhuanyi(){
	//根据id获取详情
		$detail_info = '';
		foreach($this->info as $k => $v){
			$detail_info .= '<tr height="30">
				<td bgcolor="#FFFFFF" align="center"  height="20" style="line-height:20px;">'.L('product_type')[$v['product_info']['product_type']].'</td>
				<td bgcolor="#FFFFFF" align="center"  height="20" style="line-height:20px;">'.strtoupper($v['product_info']['model']).'</td>
				<td bgcolor="#FFFFFF" align="center"  height="20" style="line-height:20px;">'.$v['product_info']['factory'].'</td>
				<td bgcolor="#FFFFFF" align="center"  height="20" style="line-height:20px;">'.$v['number'].'</td>
			</tr >';
		}
		$contract = $this->template['zhuanyi'];
		$contract = sprintf($contract,$this->out_no,$this->cname,$this->info[0]['order_num'],$detail_info,$this->info[0]['store_address'],$this->info[0]['remark'],$this->cname,$this->ship_adm,$this->company);
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
		$detail_info = '';
		foreach($this->info as $k => $v){
			$detail_info .= '<tr height="30">
				<td bgcolor="#FFFFFF" align="center"  height="20" style="line-height:20px;">'.L('product_type')[$v['product_info']['product_type']].'</td>
				<td bgcolor="#FFFFFF" align="center"  height="20" style="line-height:20px;">'.strtoupper($v['product_info']['model']).'</td>
				<td bgcolor="#FFFFFF" align="center"  height="20" style="line-height:20px;">'.$v['product_info']['factory'].'</td>
				<td bgcolor="#FFFFFF" align="center"  height="20" style="line-height:20px;">'.$v['number'].'</td>
			</tr >';
		}
		$contract = $this->template['weituo'];
		$contract = sprintf($contract,$this->out_no,$this->cname,$this->info[0]['order_num'],$detail_info,$this->info[0]['store_address'],$this->info[0]['remark'],$this->company,$this->ship_adm);
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
					<tr height="20">
						<td colspan="2"  height="20" align="center"></td>
					</tr>
					<tr height="30">
						<td colspan="2"  height="30" style="line-height:30px" align="center"><h1 style="font-size:16px;">%s</h1></td>
					</tr>
					<tr height="30">
						<td colspan="2" style="line-height:30px" height="20" align="center"><h2 style="font-size:14px;"><u>提货单</u></h2></td>
					</tr>
					<tr height="30">
						<td align="left" height="20" style="line-height:20px">此单发给：<u>%s</u></td>
						<td align="left">NO：%s</td>
					</tr>
					<tr height="30">
						<td align="left" height="20" style="line-height:20px">传真：<u>%s</u></td>
						<td align="left" height="20" style="line-height:20px">'.date('Y年m月d日',CORE_TIME).'</td>
					</tr>
					<tr height="30"><td  colspan="2" height="20">&nbsp;</td></tr>
					<tr height="30" >
						<td colspan="2">
							<table width="700" cellpadding="0" cellspacing="1" bgcolor="#ccc">
								<tr height="30" align="center">
									<td height="20" width="60" bgcolor="#fff" style="line-height:20px">产品名称</td>
									<td height="20" bgcolor="#fff" style="line-height:20px">规格/型号</td>
									<td height="20" bgcolor="#fff" style="line-height:20px">数量（吨）</td>
									<td height="20" bgcolor="#fff" style="line-height:20px">件数（包）</td>
									<td height="20" bgcolor="#fff" style="line-height:20px">提货车辆牌号</td>
									<td height="20" bgcolor="#fff" style="line-height:20px">司机姓名，身份证</td>
								</tr>
								%s
								<tr height="30">
									<td height="20" bgcolor="#fff" style="line-height:20px" align="center">备&nbsp;&nbsp;注</td>
									<td height="20" bgcolor="#fff" style="line-height:20px" colspan="5">&nbsp;&nbsp;&nbsp;%s</td>
								</tr>
								<tr height="30">
									<td height="20" bgcolor="#fff" style="line-height:20px" align="center">仓&nbsp;&nbsp;库</td>
									<td height="20" bgcolor="#fff" style="line-height:20px" colspan="5">&nbsp;&nbsp;&nbsp;%s</td>
								</tr>
								<tr height="90">
									<td height="20" bgcolor="#fff" align="center">说&nbsp;&nbsp;明</td>
									<td height="20" bgcolor="#fff" colspan="5">
										<table cellpadding="0" cellspacing="0" border="0">
											<tr>
												<td height="20" align="left" style="line-height:20px">1、此提货单需加盖本公司提货专用章方可有效。</td>
											</tr>
											<tr>
												<td height="20" align="left" style="line-height:20px"><hr color="#ccc" width="535" size="1"/></td>
											</tr>
											<tr>
												<td height="20" align="left" style="line-height:20px">2、本提货单有效期为<u>贰</u>天，手写无效。</td>
											</tr>
											<tr>
												<td height="20" style="line-height:20px"><hr color="#ccc" width="535" size="1"/></td>
											</tr>
											<tr>
												<td height="20" align="left" style="line-height:20px">3、原厂原包装，保证品质：无破包、无污损，装货过程中如有破包，烦请贵公司负责安排更换。</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr height="30">
						<td height="20" colspan="2">&nbsp;</td>
					</tr>
					<tr height="30">
						<td height="20" align="left"  style="line-height:20px" colspan="2"><u>如有问题，请联系：（%s）</u></td>
					</tr>
				</table>',
				'weituo'=>'<table width="635" border="0" cellpadding="0" cellpadding="0" align="center">
						<tr height="30">
							<td height="20" align="left" style="line-height:20px">NO：%s</td>
						</tr>
						<tr height="30">
							<td height="20" align="left" style="line-height:20px" align="center"><h1 style="font-size:16px;">委托送货单</h1></td>
						</tr>
						<tr height="30">
							<td height="20" align="left" style="line-height:20px"><h2 style="font-size:14px;">TO:%s</h2></td>
						</tr>
						<tr height="30">
							<td height="20" align="left" style="line-height:20px">请将我司采购的塑料粒子合同编号：（%s）安排送货：</td>
						</tr>
						<tr>
							<td height="20">
								<table width="635" cellpadding="0" cellspacing="1" bgcolor="#ccc">
									<tr height="30" align="center">
										<td style="line-height:20px" height="20" bgcolor="#fff">产品名称</td>
										<td style="line-height:20px" height="20" bgcolor="#fff">规格/型号</td>
										<td style="line-height:20px" height="20" bgcolor="#fff">产地</td>
										<td style="line-height:20px" height="20" bgcolor="#fff">数量（吨）</td>
									</tr>
									%s
									<tr height="30">
										<td align="left" style="line-height:20px" height="20" bgcolor="#fff" colspan="4">&nbsp;&nbsp;&nbsp;配送地址&电话：(%s)</td>
									</tr>
									<tr height="30">
										<td align="left" style="line-height:20px" height="20" bgcolor="#fff" colspan="4">&nbsp;&nbsp;&nbsp;备注：(%s)</td>
									</tr>
									<tr height="30">
										<td align="left" style="line-height:20px" height="20" bgcolor="#fff" colspan="4">&nbsp;&nbsp;&nbsp;注明：无破包，不湿料，不少料</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr height="30">
							<td height="20" align="left" style="line-height:20px">（此单手写无效）</td>
						</tr>
						<tr height="30">
							<td height="20" align="left" style="line-height:20px" align="right">%s</td>
						</tr>
						<tr height="30">
							<td height="20" align="left" style="line-height:20px" align="right">'.date('Y年m月d日',CORE_TIME).'</td>
						</tr>
						<tr height="30">
							<td height="20" align="left" style="line-height:20px">如有问题，请联系：（%s）</td>
						</tr>
					</table>',
				'zhuanyi'=>'<table width="635" border="0" cellpadding="0" cellpadding="0" align="center">
					<tr height="30">
						<td align="left" height="20" style="line-height:20px">NO：%s</td>
					</tr>
					<tr height="30">
						<td height="20" style="line-height:20px" align="center"><h1 style="font-size:16px;">货权转移单</h1></td>
					</tr>
					<tr height="30">
						<td align="left" height="20" style="line-height:20px"><h2 style="font-size:14px;">TO（供应商）：%s</h2></td>
					</tr>
					<tr height="30">
						<td height="20" align="left" style="line-height:20px">请将我司采购的塑料粒子合同编号：（%s）</td>
					</tr>
					<tr>
						<td height="20">
							<table width="635" cellpadding="0" cellspacing="1" bgcolor="#ccc">
								<tr height="30" align="center">
									<td style="line-height:20px" height="20" bgcolor="#fff">产品名称</td>
									<td style="line-height:20px" height="20" bgcolor="#fff">规格/型号</td>
									<td style="line-height:20px" height="20" bgcolor="#fff">产地</td>
									<td style="line-height:20px" height="20" bgcolor="#fff">数量（吨）</td>
								</tr>
								%s
								<tr height="30">
									<td align="left" style="line-height:20px" height="20" bgcolor="#fff" colspan="4">&nbsp;&nbsp;&nbsp;仓库地址：（%s）</td>
								</tr>
								<tr height="30">
									<td align="left" style="line-height:20px" height="20" bgcolor="#fff" colspan="4">&nbsp;&nbsp;&nbsp;备注：承担费用的注明（%s）</td>
								</tr>
								<tr height="30">
									<td align="left" style="line-height:20px" height="20" bgcolor="#fff" colspan="4">&nbsp;&nbsp;&nbsp;注明：无破包，不湿料，不少料</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr height="30">
						<td height="20" align="left" style="line-height:20px">以上货物的货权转至<u>（%s）</u></td>
					</tr>
					<tr height="30">
						<td height="20" align="left" style="line-height:20px">请将入库单传真至：<u>（%s）</u></td>
					</tr>
					<tr height="30">
						<td height="20" align="left" style="line-height:20px">（此单手写无效）</td>
					</tr>
					<tr height="30">
						<td height="20" style="line-height:20px" align="right">%s</td>
					</tr>
					<tr height="30">
						<td height="20" style="line-height:20px" align="right">'.date('Y年m月d日',CORE_TIME).'</td>
					</tr>
				</table>',
			);
		}
	}