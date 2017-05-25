<?php

class pdfAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
		$this->db=M('public:common')->model('order');
	}
	/**
	 * 合同 PDF
	 *
	 */
	public function pdf(){
		$arr = array(
			'pdf_template'=>array(
					'sale'=>'<table width="635" border="0" align="center" bgcolor="#fff">
							<tr ><td>&nbsp;</td></tr>
							<tr >
								<td colspan="2" align="center"><h1>运输合同书</h1></td>
							</tr>
							<tr height="20"><td>&nbsp;</td></tr>
							<tr align="left">
								<td width="380" height="20">甲方：上海中晨电子商务股份有限公司</td>
								<td height="20">合同日期：%s</td>
							</tr>
							<tr  align="left">
								<td  width="380" height="20">乙方：%s</td>
								<td height="20">签约地点：上海虹口欧阳路568号17楼</td>
							</tr>
							<tr  align="left">
								<td height="20"></td>
							</tr>
							<tr align="left">
								<td colspan="2" height="20" width="600">甲、乙双方经协商，根据《中华人民共和国合同法》相关规定，就以下产品的购销等有关事宜，签订本合同</td>
							</tr>
						</table>
						<!--<table width="635" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#666">
							<tr>
								<td colspan="7" bgcolor="#FFFFFF" height="20" style="line-height:20px;">&nbsp;一、产品名称、型号（规格）、产地、数量、单价、金额：</td>
							</tr>
							%s
						</table>-->
						<table width="635" align="left" bgcolor="#fff">
							<tr>
								<td width="30"  height="20" style="line-height:20px;">一、</td>
								<td width="100"  height="20" style="line-height:20px;">货物：</td>
								<td  width="465"  height="20" style="line-height:20px;">塑料粒子11T/3518BCB</td>
							</tr>
						</table>
						<table width="635" align="left" bgcolor="#fff">
							<tr>
								<td width="30"  height="20" style="line-height:20px;">二、</td>
								<td width="100" align="left"  height="20" style="line-height:20px;">提货地点：</td>
								<td>12/17提 宝安公路1877号</td>
							</tr>
						</table>
			           <table width="635" align="left" bgcolor="#fff">
							<tr>
								<td width="30"  height="20" style="line-height:20px;">三、</td>
								<td width="100" align="left"  height="20" style="line-height:20px;">送货地点：</td>
								<td width="505" height="20" style="line-height:20px;">宜兴市官林镇三木工业园区  张 15251960360</td>
							</tr>
						</table>
						<table width="635" align="left" bgcolor="#fff">
							<tr>
								<td width="30" height="20" style="line-height:20px;">四、</td>
								<td width="100" height="20" style="line-height:20px;">送货日期：</td>
								<td>2015年12月18日</td>
							</tr>				
						</table>
			            <table width="635" align="left" bgcolor="#fff">
							<tr>
								<td width="30" height="20" style="line-height:20px;">五、</td>
								<td width="100" height="20" style="line-height:20px;">合同责任：</td>
								<td width="505" height="20" style="line-height:20px;">乙方必须于甲方的指定期限内，将货物送到甲方的指定地点。</td>
							</tr>				
						</table>
						<table width="635" align="left" bgcolor="#fff">
							<tr>
								<td width="100" height="20" style="line-height:20px;">注意事项：</td>								
							</tr>						
						</table>
			           <table width="635" align="left" bgcolor="#fff">
							<tr>
								<td width="665" height="20" style="line-height:20px;">乙方必须保证甲方的货物，安全、无破损、无潮湿、无变质等情况。
			    若出现以上情况，乙方必须负责赔偿甲方的损失。</td>
							</tr>						
						</table>
						<table width="635" align="left" bgcolor="#fff">
							<tr>
								<td width="30" height="20" style="line-height:20px;">六、</td>
								<td width="100" height="20" style="line-height:20px;">备注：</td>
								<td width="505" height="20" style="line-height:20px;">	合同传真件有效。</td>
							</tr>
						</table>
						<table width="635" align="left" bgcolor="#fff">
							 <tr>
								<td width="30" height="20" style="line-height:20px;">七、</td>
								<td width="100" height="20" style="line-height:20px;">车号：</td>
								<td width="505" height="20" style="line-height:20px;"></td>
							</tr>
			                 <tr>
								<td width="30" height="20" style="line-height:20px;"></td>
								<td width="100" height="20" style="line-height:20px;">司机姓名：</td>
								<td width="505" height="20" style="line-height:20px;"></td>
							 </tr>
			                 <tr>
								<td width="30" height="20" style="line-height:20px;"></td>
								<td width="100" height="20" style="line-height:20px;">身份证号码：</td>
								<td width="505" height="20" style="line-height:20px;"></td>
							</tr>							
						</table>
						<table width="635" align="left" bgcolor="#fff">
							<tr>
								<td width="30" height="20" style="line-height:20px;">八、</td>
								<td width="100" height="20" style="line-height:20px;">运输费用：</td>
								<td width="505" height="20" style="line-height:20px;">105元/吨（含税）+装车费另计。</td>
							</tr>
						</table>
        			    <tr  align="left">
        								<td height="20"></td>
        							</tr>
        				<tr>
        				<td height="20" style="line-height:20px;" width="110">购货方（甲方）</td>
			            <td width="250"></td>
        				<td height="20" style="line-height:20px;"  width="110">供货方（乙方）</td>      
        			   </tr>
        			   <tr>
        				<td height="20" style="line-height:20px;" width="110">法人：</td>
        				<td >李铁道</td>
        				<td height="20" style="line-height:20px;" width="110">法人：</td>
        				<td align="left">'.$orderList['legal_person'].'</td>
        			   </tr>
        			   <tr>
        				<td height="20" style="line-height:20px;" width="110">经办人：</td>
        				<td align="left">'.$admname.'</td>
        				<td height="20" style="line-height:20px;" width="110">经办人：</td>
        				<td align="left"></td>
        			   </tr>
        			   <tr>
        				<td height="20" style="line-height:20px;" width="110">传真：</td>
        				<td align="left">'.L('c_fax')[$orderList['c_fax']].'</td>
        				<td height="20" style="line-height:20px;" width="110">传真：</td>
        				<td align="left"></td>
        			</tr>',
			),
		);
		$oid = sget('oid','s');
		$oarr = explode(',', $oid);
		if($oid){
			$orderLists=$this->db->from('order as o')->leftjoin('customer as c','c.c_id=o.c_id')->select('o.order_sn, o.total_price, o.order_name, o.order_type,o.h_pur, o.c_fax,  o.payment_way, o.pay_method, o.pickup_location, o.delivery_location, o.sign_time, o.sign_place, o.additional, o.transport_type, o.payment_time,o.pickup_time,c.c_name,c.legal_person, o.customer_manager as ocm')->where('o_id='.$oarr[0])->getRow();
			if($orderLists['order_type']==1){
				$detiles=$this->db->from('sale_log as s')
						->leftjoin('product as p','p.id=s.p_id')
						->leftjoin('factory as f','p.f_id=f.fid')
						->select('s.o_id,s.number,s.unit_price,p.model,p.product_type,f.f_name')
						->where("s.o_id in ($oid)")->order("s.id desc")->getAll();
			}else{
				$detiles=$this->db->from('purchase_log as s')
						->leftjoin('product as p','p.id=s.p_id')
						->leftjoin('factory as f','p.f_id=f.fid')
						->select('s.o_id,s.number,s.unit_price,p.model,p.product_type,f.f_name')
						->where("s.o_id in ($oid)")->order("s.id desc")->getAll();
			}
		}
		$admname = M('rbac:adm')->getUserByCol($orderLists['ocm']);
		$ordertitle = $orderLists['order_name'] == 1 ? '上海中晨电子商务股份有限公司' : ($orderLists['order_name'] == 2 ? '上海梓辰实业有限公司' : '嘉兴鼎辉信息科技有限公司');
		$shipname = $orderLists['transport_type'] == 1 ? '提货' : '送货';
		$orderList=!empty($orderLists)?$orderLists:'';
		//提货日期
		$pickup_time=!empty($orderLists['pickup_time'])?date('Y年m月d日',$orderLists['pickup_time']):'-';
		//签约时间
		$sign_time=!empty($orderLists['sign_time'])?date('Y-m-d',$orderLists['sign_time']):'-';
		//付款时间
		$payment_time=!empty($orderLists['payment_time'])?date('Y-m-d',$orderList['payment_time']):'-';
		$location=!empty($orderList['pickup_location'])?$orderList['pickup_location'] : $orderList['delivery_location'];
		foreach($detiles as $k => $v){
			$sign = round($v['number']*$v['unit_price'],2);
			$detail_info .= '<tr >
				<td bgcolor="#FFFFFF" align="center"  height="20" style="line-height:20px;">'.L('product_type')[$v['product_type']].'</td>
				<td bgcolor="#FFFFFF" align="center"  height="20" style="line-height:20px;">'.strtoupper($v['model']).'</td>
				<td bgcolor="#FFFFFF" align="center"  height="20" style="line-height:20px;">'.$v['f_name'].'</td>
				<td bgcolor="#FFFFFF" align="center"  height="20" style="line-height:20px;">吨</td>
				<td bgcolor="#FFFFFF" align="center"  height="20" style="line-height:20px;">'.$v['number'].'</td>
				<td bgcolor="#FFFFFF" align="center"  height="20" style="line-height:20px;">'.$v['unit_price'].'</td>
				<td bgcolor="#FFFFFF" align="center"  height="20" style="line-height:20px;">'.$sign.'</td>
			</tr >';
			$totall += $sign;
		}
		E('TCPdf',APP_LIB.'extend');
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
		$total=$this->_cny($totall);
		$table1='<tr>
				<td width="130" bgcolor="#FFFFFF" align="center"  height="20" style="line-height:20px;">产品名称</td>
				<td width="80" bgcolor="#FFFFFF" align="center"  height="20" style="line-height:20px;">规格/型号</td>
				<td width="140" bgcolor="#FFFFFF" align="center"  height="20" style="line-height:20px;">产地</td>
				<td width="30" bgcolor="#FFFFFF" align="center"  height="20" style="line-height:20px;">单位</td>
				<td width="75" bgcolor="#FFFFFF" align="center"  height="20" style="line-height:20px;">数量</td>
				<td width="82" bgcolor="#FFFFFF" align="center"  height="20" style="line-height:20px;">单价</td>
				<td width="90" bgcolor="#FFFFFF" align="center"  height="20" style="line-height:20px;">金额</td>
			</tr>
			' .$detail_info. '
			<tr>
				<td bgcolor="#FFFFFF"  height="20" style="line-height:20px;">合计</td>
				<td colspan="6" bgcolor="#FFFFFF" class="number"  height="20" style="line-height:20px;">'.$totall.'</td>
			</tr>
			<tr>
				<td bgcolor="#FFFFFF"  height="20" style="line-height:20px;">合计人民币（大写）</td>
				<td colspan="6" bgcolor="#FFFFFF" class="number"  height="20" style="line-height:20px;">'.$total.'</td>
			</tr>
			';
		//销售
		$table2='
			<tr>
				<td align="right" height="20" style="line-height:20px;" width="110">&nbsp;甲方（签章）：</td>
				<td align="left" height="20" style="line-height:20px;"  width="200">'.$ordertitle.'</td>
				<td align="right" height="20" style="line-height:20px;"  width="110">&nbsp;乙方（签章）：</td>
				<td align="left" height="20" style="line-height:20px;"  width="215">'.$orderList['c_name'].'</td>
			</tr>
			<tr>
				<td align="right" height="20" style="line-height:20px;" width="110">法人：</td>
				<td align="left">李铁道</td>
				<td align="right" height="20" style="line-height:20px;" width="110">法人：</td>
				<td align="left">'.$orderList['legal_person'].'</td>
			</tr>
			<tr>
				<td align="right" height="20" style="line-height:20px;" width="110">经办人：</td>
				<td align="left">'.$admname.'</td>
				<td align="right" height="20" style="line-height:20px;" width="110">经办人：</td>
				<td align="left"></td>
			</tr>
			<tr>
				<td align="right" height="20" style="line-height:20px;" width="110">传真：</td>
				<td align="left">'.L('c_fax')[$orderList['c_fax']].'</td>
				<td align="right" height="20" style="line-height:20px;" width="110">传真：</td>
				<td align="left"></td>
			</tr>
			';
		//采购
		$table3='
			<tr>
				<td align="right" height="20" style="line-height:20px;" width="110">&nbsp;甲方（签章）：</td>
				<td align="left" height="20" style="line-height:20px;"  width="200">'.$orderList['c_name'].'</td>
				<td align="right" height="20" style="line-height:20px;"  width="110">&nbsp;乙方（签章）：</td>
				<td align="left" height="20" style="line-height:20px;"  width="215">'.$ordertitle.'</td>
			</tr>
			<tr>
				<td align="right" height="20" style="line-height:20px;" width="110">法人：</td>
				<td align="left">'.$orderList['legal_person'].'</td>
				<td align="right" height="20" style="line-height:20px;" width="110">法人：</td>
				<td align="left">李铁道</td>
			</tr>
			<tr>
				<td align="right" height="20" style="line-height:20px;" width="110">经办人：</td>
				<td align="left"></td>
				<td align="right" height="20" style="line-height:20px;" width="110">经办人：</td>
				<td align="left">'.$admname.'</td>
			</tr>
			<tr>
				<td align="right" height="20" style="line-height:20px;" width="110">传真：</td>
				<td align="left"></td>
				<td align="right" height="20" style="line-height:20px;" width="110">传真：</td>
				<td align="left">'.L('c_fax')[$orderList['c_fax']].'</td>
			</tr>
			';
		$additional = empty($orderList['additional']) ? '' : '
			<tr>
				<td></td>
				<td></td>
				<td width="505" height="20" style="line-height:20px;">4、'.$orderList['additional'].'</td>
			</tr>
			';

		// 销售情况
		$contract = sprintf($arr['pdf_template']['sale'],$ordertitle,$orderList['order_sn'],$orderList['c_name'],$sign_time,$table1,$shipname,$location,$shipname,$pickup_time,$orderList['transport_type'],(L('pay_method')[$orderList['pay_method']]),$payment_time,$orderLists['payment_way'],$additional,$table2);
		$pdf->writeHTMLCell(0, 0, '', '', $contract, 0, 1, 0, true, '', true);
		// 输出pdf
		$pdf->Output("{$orderLists['order_sn']}.pdf", 'I');
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
		//出货凭证单
		public function outpdf(){
			$arr = array(
				'pdf_template'=>array(
					'out'=>'<table width="635" border="0" align="center" bgcolor="#fff">
							<tr ><td>&nbsp;</td></tr>
							<tr >
								<td colspan="2" align="center"><h1>%s</h1></td>
							</tr>
							<tr height="20"><td><h2>收货收据</h2></td></tr>
							<tr align="left">
								<td width="440" height="20"></td>
								<td height="20">合同号：%s</td>
							</tr>
							<tr  align="left">
								<td height="20">兹收到贵司如下货物：</td>
								<td height="20">时间：%s</td>
							</tr>
							<tr>
								<td colspan="4" height="2" style="line-height:2px;"></td>
							</tr>
							<table width="635" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#666">
								<tr>
									<td colspan="7" bgcolor="#FFFFFF" height="20" style="line-height:20px;">&nbsp;一、产品名称、型号（规格）、产地、数量、单价、金额：</td>
								</tr>
								%s
							</table>
							<tr>
								<td colspan="4" height="2" style="line-height:15px;"></td>
							</tr>
							<tr align="left">
								<td colspan="2" height="20" width="600">以上货物确认已收到（无少料，无破包，无湿料等问题）。</td>
							</tr>
						</table>

						<table width="635" align="center" bgcolor="#fff">
							<tr>
								<td colspan="4" height="2" style="line-height:2px;"></td>
							</tr>
						</table>
						<table width="635" align="center" bgcolor="#fff">
							<tr>
								<td colspan="4" height="20" style="line-height:20px;"></td>
							</tr>
							%s
						</table>',
				),
			);
			//收货表体
			$table5='
				<tr>
					<td align="right" height="20" style="line-height:90px;" width="110"></td>
					<td align="left"></td>
				</tr>
				<tr>
					<td align="right" height="20" style="line-height:20px;" width="50"></td>
					<td align="left" height="20" style="line-height:20px;"  width="100"></td>
					<td align="right" height="20" style="line-height:20px;"  width="290">&nbsp;客户名（签章）：%s</td>
					<td align="left" height="20" style="line-height:20px;"  width="315"></td>
				</tr>
				<tr>
					<td align="right" height="20" style="line-height:90px;" width="110"></td>
					<td align="left"></td>
					<td align="right" height="20" style="line-height:190px;" width="110"></td>
					<td align="left"></td>
				</tr>
				<tr>
					<td align="left" colspan="4">请加盖公章或合同章回传，以便我司开具增值税发票（提货章，财务章等均无效）。</td>

				</tr>
				<tr>
					<td align="left" colspan="4">附贵司正楷开票资料和快递地址，一起传真至：</td>
				</tr>
				';
			// 价格详情基本信息查询
			$id = sget('oid','i',0);
			if($id>0){
				$info = $this->db->model('out_log')->where("id = $id")->getRow();
				$info['model'] =strtoupper(M("product:product")->getModelById($info['p_id']));//获取牌号
				$info['cname'] = M("product:product")->getFnameByid($info['p_id']);//获取厂家
				$info['ptype'] =L('product_type')[M("product:product")->getModelById($info['p_id'],'product_type')];
				//根据订单查出清单信息并获取详情价格
				$oinfo = M("product:order")->getOinfoById($info['o_id']);
				if($oinfo['order_name'] == 1){
					$ordertitle =  '上海中晨电子商务股份有限公司';
				}elseif($oinfo['order_name'] == 2){
					$ordertitle =  '上海梓辰实业有限公司';
				}else{
					$ordertitle =  '嘉兴鼎辉信息科技有限公司';
				}
				if($oinfo['order_type']==1){
					$o_detail = $this->db->model("sale_log")->where("o_id = {$info['o_id']} and p_id = {$info['p_id']} ")->getRow();
				}else{
					$o_detail = $this->db->model("purchase_log")->where("o_id = {$info['o_id']}  and p_id = {$info['p_id']} ")->getRow();
				}
				$info['unit_price'] = $o_detail['unit_price'];

			}
			$out_cid = $this->db->model('order')->select('c_id')->where("`o_id` = {$info['o_id']}")->getOne();
			$out_name = $this->db->model('customer')->select('c_name')->where("`c_id` = $out_cid")->getOne();
			$table5 = sprintf($table5,$out_name);
			$detail_info = '<tr >
				<td bgcolor="#FFFFFF" align="center"  height="20" style="line-height:20px;">'.$info['ptype'].'</td>
				<td bgcolor="#FFFFFF" align="center"  height="20" style="line-height:20px;">'.$info['model'].'</td>
				<td bgcolor="#FFFFFF" align="center"  height="20" style="line-height:20px;">'.$info['cname'].'</td>
				<td bgcolor="#FFFFFF" align="center"  height="20" style="line-height:20px;">吨</td>
				<td bgcolor="#FFFFFF" align="center"  height="20" style="line-height:20px;">'.$info['number'].'</td>
				<td bgcolor="#FFFFFF" align="center"  height="20" style="line-height:20px;">'.$info['unit_price'].'</td>
				<td bgcolor="#FFFFFF" align="center"  height="20" style="line-height:20px;">'.$info['number']*$info['unit_price'].'</td>
			</tr >';
			E('TCPdf',APP_LIB.'extend');
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
			$pdf->SetTitle('上海中晨电商出货证明');
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
			$total=$this->_cny($info['number']*$info['unit_price']);
			//收货收据
			$table4='<tr>
				<td width="130" bgcolor="#FFFFFF" align="center"  height="20" style="line-height:20px;">产品名称</td>
				<td width="80" bgcolor="#FFFFFF" align="center"  height="20" style="line-height:20px;">规格/型号</td>
				<td width="140" bgcolor="#FFFFFF" align="center"  height="20" style="line-height:20px;">产地</td>
				<td width="30" bgcolor="#FFFFFF" align="center"  height="20" style="line-height:20px;">单位</td>
				<td width="75" bgcolor="#FFFFFF" align="center"  height="20" style="line-height:20px;">数量</td>
				<td width="82" bgcolor="#FFFFFF" align="center"  height="20" style="line-height:20px;">单价</td>
				<td width="90" bgcolor="#FFFFFF" align="center"  height="20" style="line-height:20px;">金额</td>
			</tr>
			' .$detail_info. '
			<tr>
				<td bgcolor="#FFFFFF"  height="20" style="line-height:20px;">合计</td>
				<td colspan="6" bgcolor="#FFFFFF" class="number"  height="20" style="line-height:20px;">'.$info['number']*$info['unit_price'].'</td>
			</tr>
			<tr>
				<td bgcolor="#FFFFFF"  height="20" style="line-height:20px;">合计人民币（大写）</td>
				<td colspan="6" bgcolor="#FFFFFF" class="number"  height="20" style="line-height:20px;">'.$total.'</td>
			</tr>
			';
			// 出库信息表
			$str = sprintf($arr['pdf_template']['out'],$ordertitle,$oinfo['order_sn'],date('Y-m-d',$info['input_time']),$table4,$table5);
			$pdf->writeHTMLCell(0, 0, '', '', $str, 0, 1, 0, true, '', true);
			// 输出pdf
			$pdf->Output("{$oinfo['order_sn']}.pdf", 'I');
		}
	}