<?php
	class testAction extends homeBaseAction{
		protected $db;
		public function __init(){
			$this->db=M('public:common');
		}
	public function pdf(){
		$this->act='pdf';
		if($_GET['oid']){
			$oid=trim($_GET['oid']);
				$orderLists=$this->db->from('order as o')
					->leftjoin('customer as c','c.c_id=o.c_id')
					->select('o.order_sn,o.total_price,o.pay_method,o.pickup_location,o.delivery_location,o.sign_time,o.sign_place,o.transport_type,o.payment_time,o.pickup_time,c.c_name')->where('o_id='.$oid)->getRow();
				$detiless=$this->db->from('sale_log as s')
					->leftjoin('product as p','p.id=s.p_id')
					->leftjoin('factory as f','p.f_id=f.fid')
					->select('s.o_id,s.number,s.unit_price,p.model,p.product_type,f.f_name')
					->where('o_id='.$oid)->getAll();
		}
	        	$orderList=!empty($orderLists)?$orderLists:false;
	        	$detiles=!empty($detiless)?$detiless:false;
		        //提货日期
				$pickup_time=!empty($orderLists['pickup_time'])?date('Y年m月d日',$orderLists['pickup_time']):'-';
		        //签约时间
				$sign_time=!empty($orderLists['sign_time'])?date('Y-m-d',$orderLists['sign_time']):'-';
				//付款时间
				$payment_time=!empty($orderLists['payment_time'])?date('Y-m-d',$orderList['payment_time']):'-';
		foreach($detiles as $k => $v){
			$detail_info .= '<tr >
				<td  width="120" align="center">'.L('product_type')[$v['product_type']].'</td>
				<td  width="140" align="center">'.$v['model'].'</td>
				<td  width="80"  align="center">'.$v['f_name'].'</td>
				<td  width="80"  align="center">吨</td>
				<td  width="80"  align="center">'.$v['number'].'</td>
				<td  width="80"  align="center">'.$v['unit_price'].'</td>
				<td  width="80"  align="center">'.$v['number']*$v['unit_price'].'</td>
		 	</tr >';
		}
		E('TCPdf',APP_LIB.'extend');
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->SetTitle('上海中晨电商合同报表');
		$pdf->SetHeaderData('config/pdflogo.jpg', 33, '','', array(0,33,43), array(0,64,128));
		// 设置默认等宽
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		// 设置间距
		$pdf->SetMargins(15, 25, 15);
		$pdf->SetHeaderMargin(5);
		$pdf->SetFooterMargin(10);
		// 设置分页
		$pdf->SetAutoPageBreak(TRUE, 25);
		$pdf->setImageScale(1.25);
		$pdf->setFontSubsetting(true);
		// 设置中文字体
		$pdf->SetFont('stsongstdlight','', 10);
		$pdf->AddPage();
		$total=$this->cny($orderList['total_price']);
		$table1='<tr >
			<td  width="120" align="center"><b>产品名称</b></td>
			<td  width="140" align="center" ><b>规格/规格</b></td>
			<td  width="80"  align="center"><b>产地</b></td>
			<td  width="80"  align="center"> <b>单位</b></td>
			<td  width="80"  align="center"><b>数量</b></td>
			<td  width="80"  align="center"><b>单价</b></td>
			<td  width="80"  align="center"><b>金额</b></td>
			</tr>' .$detail_info. '<tr >
			<td  width="120"  align="center">合计</td>
			<td  width="540" colspan="6">'.$orderList['total_price'].'</td>
			</tr>
			<tr >
			<td  width="120" align="center">合计人民币(大写)</td>
			<td  width="540" colspan="6" text-align="left">'.$total.'整</td>
			</tr>';
		$table2='<tr >
			<td  width="140" align="center">甲方(签章):</td>
			<td  width="140" align="center">'.上海梓晨实业有限公司.'</td>
			<td  width="140" align="center">乙方(签章):</td>
			<td  width="200" align="center">'.$orderList['c_name'].'</td>
		</tr>
		<tr >
			<td  width="140" align="center">法人:</td>
			<td  width="140" align="center">'.李铁道.'</td>
			<td  width="140" align="center">法人:</td>
			<td  width="140" align="center"></td>
		</tr>
		<tr >
			<td  width="140"align="center">经办人:</td>
			<td  width="140" align="center"></td>
			<td  width="140" align="center">经办人:</td>
			<td  width="140"align="center"></td>
		</tr >
		<tr>
			<td  width="140" align="center">传真:</td>
			<td  width="140" align="center">010-123456789</td>
			<td  width="140" align="center">传真:</td>
			<td  width="140" align="center">020-98765432</td>
		 </tr>';
		$location=!empty($orderList['pickup_location'])?$orderList['pickup_location']:$orderList['delivery_location'];
		$str = sprintf(L('htmls.html'),(L('transport_type')[$orderList['transport_type']]),'上海梓晨实业有限公司',$orderList['order_sn'],$orderList['c_name'],$orderList['sign_place'],$sign_time,$table1,$location,$pickup_time,(L('transport_type')[$orderList['transport_type']]),(L('pay_method')[$orderList['pay_method']]),$payment_time,'提前付清全款',$table2);
		//echo $str;
		$pdf->writeHTMLCell(0, 0, '', '', $str, 0, 1, 0, true, '', true);
		// 输出pdf
		$pdf->Output('zcds.pdf', 'I');
		}
		/**
		 * 人民币转文字
		 * @Author   cuiyinming
		 * @DateTime 2016-06-16T12:17:28+0800
		 * @param    [type]                   $ns [description]
		 * @return   [type]                       [description]
		 */
		private function cny($ns){
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



}