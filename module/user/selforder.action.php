<?php

/**
 * 自营商城订单
 */
class selforderAction extends userBaseAction{

	protected $db;
	public function __init(){
		$this->db=M('public:common');
	}


	public function init()
	{
		$this->act="selforder";

		$this->transport_type=L('transport_type');
		$this->goods_status=L('goods_status');
		$this->invoice_status=L('invoice_status');
		$this->order_status=L('order_status');

		$where="user_id=$this->user_id";

		//订单筛选
		if($orderSn=sget('sn','s','')){
			$where.=" and order_sn=$orderSn";
		}
		//日期筛选
		if($input_time=sget('input_time','s','')){

		}
		//运输方式
		if($transport_type=sget('transport_type','i',0)){
			$where.=" and transport_type=$transport_type";
		}
		//发货状态
		if($goods_status=sget('goods_status','i',0)){
			$where.=" and goods_status=$goods_status";
		}
		//开票状态
		if($invoice_status=sget('invoice_status','i',0)){
			$where.=" and invoice_status=$invoice_status";
		}
		//订单状态
		if($order_status=sget('order_status','i',0)){
			$where.=" and order_status=$order_status";
		}
		$page=sget('page','i',1);
		$size=10;
		$orderList=M('product:order')
			->select('o_id,order_name,order_sn,user_id,admin_id,total_price,pay_method,transport_type,freight_price,order_status,invoice_status,input_time,remark')
			->where($where)
			->page($page,$size)
			->order('input_time desc')
			->getPage();

		$this->pages = pages($orderList['count'], $page, $size);

		foreach ($orderList['data'] as &$value) {
			$value['totalNum']=$this->db->model('sale_log')->where("o_id={$value['o_id']}")->select("sum(number)")->getOne();
		}
		$this->assign('orderList',$orderList);
		$this->display('selforder');
	}


	// 订单详细查看
	public function detail()
	{
		$id=sget('id','i',0);
		if(!$this->db->model('order')->where("o_id=$id and user_id=$this->user_id")->getRow()) $this->forward('/');
		$order=$this->db->from('order o')
			->join('admin ad','o.admin_id=ad.admin_id')
			->select('o.*,ad.name,ad.mobile')
			->where("o_id=$id and user_id={$this->user_id}")
			->getRow();
		$sale_log=$this->db->from('sale_log s')
			->leftjoin('product p','s.p_id=p.id')
			->leftjoin('factory f','p.f_id=f.fid')
			->select('s.id,s.number,s.unit_price,p.model,p.product_type,f.f_name')
			->where("o_id={$order['o_id']}")
			->getAll();
		foreach ($sale_log as $key => &$value) {
			$value['totalPrice']=$value['number']*$value['unit_price'];
		}

		$this->assign('sale_log',$sale_log);
		$this->assign('order',$order);
		$this->display('selforder.detail');
	}


	/**
	 * 下载pdf
	 *
	 */
	public function pdf(){
		$dir=dirname(dirname(dirname(dirname(__FILE__))));
		$this->act='pdf';
		if($_GET){
			$oid=trim($_GET['oid']);
			$orderList=$this->db->from('order as o')
				->leftjoin('customer as c','c.c_id=o.c_id')
				->select('o.order_sn,o.total_price,o.pay_method,o.pickup_location,o.delivery_location,o.sign_time,o.sign_place,o.transport_type,o.payment_time,o.pickup_time,c.c_name')->where('o_id='.$oid)->getRow();
			$detiles=$this->db->from('sale_log as s')
				->leftjoin('product as p','p.id=s.p_id')
				->leftjoin('factory as f','p.f_id=f.fid')
				->select('s.o_id,s.number,s.unit_price,p.model,p.product_type,f.f_name')
				->where('o_id='.$oid)->getAll();
			//showTrace();
		}

//p($detiles);
		foreach($detiles as $k => $v){
			$detail_info .= '<tr >
					  <td  width=\"120\" align=\"center\">'.L('product_type')[$v['product_type']].'</td>
					  <td  width=\"140\" align=\"center\">'.$v['model'].'</td>
					  <td  width=\"80\"  align=\"center\">'.$v['f_name'].'</td>
					  <td  width=\"80\"  align=\"center\">吨</td>
					  <td  width=\"80\" align=\"center\">'.$v['number'].'</td>
					  <td  width=\"80\"  align=\"center\">'.$v['unit_price'].'</td>
					  <td  width=\"80\"  align=\"center\">'.$v['number']*$v['unit_price'].'</td>
				 </tr >';

		}

// Include the main TCPDF library (search for installation path).

	require_once ($dir.'/common/extend/TCPdf/tcpdf.php');//tcpdf入口文件
	require_once($dir.'/common/extend/TCPdf/examples/tcpdf_include.php');//引入相关配置及文档

// create new PDF document
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('admin');
	$pdf->SetTitle('上海中晨电商合同报表');
	$pdf->SetSubject('TCPDF Tutorial');
	$pdf->SetKeywords('TCPDF, PDF, example, test, guide');


	// 设置头信息
	// 第一个array 设置头部颜色(logo)
	$pdf->SetHeaderData('slw_log.jpg', 33, '','', array(0,33,43), array(0,64,128));
	//设置尾信息
	$pdf->setFooterData(array(0,64,0), array(0,64,128));

	// 设置页眉和页脚字体
	$pdf->setHeaderFont(Array('stsongstdlight', '', 10));
	$pdf->setFooterFont(Array('stsongstdlight', '', 8));


	// 设置默认等宽
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	// 设置间距
	$pdf->SetMargins(15, 25, 15);
	$pdf->SetHeaderMargin(5);
	$pdf->SetFooterMargin(10);

	// 设置分页
	$pdf->SetAutoPageBreak(TRUE, 25);

	// set image scale factor
	$pdf->setImageScale(1.25);

// set some language-dependent strings (optional)
	if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
			require_once(dirname(__FILE__).'/lang/eng.php');
			$pdf->setLanguageArray($l);
	}
// ---------------------------------------------------------
	// set default font subsetting mode
	$pdf->setFontSubsetting(true);

	// Set font
	// dejavusans is a UTF-8 Unicode font, if you only need to
	// print standard ASCII chars, you can use core fonts like
	// helvetica or times to reduce file size.
	// 设置中文字体
	$pdf->SetFont('stsongstdlight','', 10);

	// Add a page
	// This method has several options, check the source code documentation for more information.
	$pdf->AddPage();

	// set text shadow effect
	$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));


	//==========================================
	function cny($ns){
		static $cnums = array("零","壹","贰","叁","肆","伍","陆","柒","捌","玖"),
		$cnyunits = array("圆","角","分"),
		$grees = array("拾","佰","仟","万","拾","佰","仟","亿");
		list($ns1,$ns2) = explode(".",$ns,2);
		$ns2 = array_filter(array($ns2[1],$ns2[0]));
		$ret = array_merge($ns2,array(implode("", _cny_map_unit(str_split($ns1), $grees)), ""));
		$ret = implode("",array_reverse(_cny_map_unit($ret,$cnyunits)));
		return str_replace(array_keys($cnums), $cnums,$ret);
	}

	function _cny_map_unit($list,$units){
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
	$total=cny($orderList['total_price']);
//==========================================
// Print text using writeHTMLCell()

//第一个表格
	$table1='
			 <tr >
				  <td  width=\"120\" align=\"center\"><b>产品名称</b></td>
				  <td   width=\"140\" align=\"center\" ><b>规格/规格</b></td>
				  <td   width=\"80\" align=\"center\"><b>产地</b></td>
				  <td  width=\"80\" align=\"center\"> <b>单位</b></td>
				  <td  width=\"80\" align=\"center\"><b>数量</b></td>
				  <td  width=\"80\" align=\"center\"><b>单价</b></td>
				  <td width=\"80\" align=\"center\"><b>金额</b></td>
			 </tr>'
			 .$detail_info.
			 '<tr >
				  <td  width=\"120\"  align=\"center\">合计</td>
				  <td  width=\"120\" colspan="6">'.$orderList['total_price'].'</td>
			 </tr>
			 <tr >
				  <td  width=\"120\" align=\"center\">合计人民币(大写)</td>
				   <td  width=\"120\" colspan="6">'.$total.'整</td>
			 </tr>';



	//第二个表格
	$table2='<tr >
				  <td  width=\"70\" align=\"center\">甲方(签章):</td>
				  <td  width=\"70\" align=\"center\">'.上海梓晨实业有限公司.'</td>
				  <td  width=\"70\" align=\"center\">乙方(签章):</td>
				  <td  width=\"70\" align=\"center\">'.$orderList['c_name'].'</td>
			 </tr>
			  <tr >
				  <td  width=\"70\" align=\"center\">法人:</td>
				  <td  width=\"70\" align=\"center\">'.李铁道.'</td>
				  <td  width=\"70\" align=\"center\">法人:</td>
				  <td  width=\"70\" align=\"center\"></td>
			 </tr>
			  <tr >
				  <td  width=\"70\"align=\"center\">经办人:</td>
				  <td  width=\"70\" align=\"center\"></td>
				  <td  width=\"70\" align=\"center\">经办人:</td>
				  <td  width=\"70\"align=\"center\"></td>
			 </tr >
			 <tr>
				  <td  width=\"70\" align=\"center\">传真:</td>
				  <td  width=\"70\" align=\"center\">010-123456789</td>
				  <td  width=\"70\" align=\"center\">传真:</td>
				  <td  width=\"70\" align=\"center\">020-98765432</td>
			 </tr>';
	$location=!empty($orderList['pickup_location'])?$orderList['pickup_location']:$orderList['delivery_location'];

	$str = sprintf(L('htmls.html'),'上海梓晨实业有限公司',$orderList['order_sn'],$orderList['c_name'],$orderList['sign_place'],date('Y-m-d',$orderList['sign_time']),$table1,$location,date('Y年m月d日',$orderList['pickup_time']),(L('transport_type')[$orderList['transport_type']]),(L('pay_method')[$orderList['pay_method']]),date('Y-m-d',$orderList['payment_time']),'提前付清全款',$table2);
	//echo $str;
	$pdf->writeHTMLCell(0, 0, '', '', $str, 0, 1, 0, true, '', true);


	// 输出pdf
	$pdf->Output('zcds.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+














	}



}