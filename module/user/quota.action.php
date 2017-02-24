	<?php 
/**
* 用户月指标数据报表
*/
class quotaAction extends adminBaseAction
{
	public function init()
	{
		$team = $this->db=M('public:common')->model('adm_role')->where('pid=22')->getAll();
		if(empty($team_id)){
			$team_id = $team[0]['id'];
		}
		$this->assign('team',$team);
		$this->display('report.view.html');
	}
	public function getPersonData(){
		$time_type = spost('time_type','s','');
		$team_id = spost('team_id','i','');
		$team_name = spost('team_name','s','');
		$data_type = spost('data_type','s','');
		$return_data = $this->data = M('user:quotaView')->getDataByTime($time_type,$team_id);
		foreach ($return_data as $key => $value) {
			$name[] = $value['name'];
			$sales[] = (int)$value['sales'];
			$buys[] = (int)$value['buys'];
			$saled[] = (int)$value['saled']/10000;
			$buyd[] = (int)$value['buyd']/10000;
			$buy_num[] =(int)$value['buy_num'];
            $buyd_num[] =(int)$value['buyd_num'];
            $sale_num[] =(int)$value['sale_num'];
            $saled_num[] =(int)$value['saled_num'];
            $old_user[] =(int)$value['old_user'];
            $old_userd[] =(int)$value['old_userd'];
            $new_user[] =(int)$value['new_user'];
            $new_userd[] =(int)$value['new_userd'];
            $day_call[] =(int)$value['day_call'];
            $day_calld[] =(int)$value['day_calld'];
            $month_call[] =(int)$value['month_call'];
            $month_calld[] =(int)$value['month_calld'];
            $profit[] =(int)$value['profit'];
            $profitd[] =(int)$value['profitd'];
		}

		switch ($data_type) {
			case 'sale_num':
				$data_type_name = '销售吨位';
				$yAxis_name1 = '销售吨位指标';
				$yAxis_name2 = '完成情况';
				$suffix = '吨';
				$yAxis_data1 = $sale_num;
				$yAxis_data2 = $saled_num;
				break;
			case 'buy_num':
				$data_type_name = '采购吨位';
				$yAxis_name1 = '采购吨位指标';
				$yAxis_name2 = '完成情况';
				$suffix = '吨';
				$yAxis_data1 = $buy_num;
				$yAxis_data2 = $buyd_num;
				break;
			case 'sales':
				$data_type_name = '销售金额';
				$yAxis_name1 = '销售金额指标';
				$yAxis_name2 = '完成情况';
				$suffix = '万元';
				$yAxis_data1 = $sales;
				$yAxis_data2 = $saled;
				break;
			case 'buys':
				$data_type_name = '采购金额';
				$yAxis_name1 = '采购金额指标';
				$yAxis_name2 = '完成情况';
				$suffix = '万元';
				$yAxis_data1 = $buys;
				$yAxis_data2 = $buyd;
				break;
			case 'old':
				$data_type_name = '开发老用户量';
				$yAxis_name1 = '老用户指标';
				$yAxis_name2 = '完成情况';
				$suffix = '个';
				$yAxis_data1 = $old_user;
				$yAxis_data2 = $old_userd;
				$bfb = $old_user_bfb;
				break;
			case 'new':
				$data_type_name = '开发新用户量';
				$yAxis_name1 = '新用户指标';
				$yAxis_name2 = '完成情况';
				$suffix = '个';
				$yAxis_data1 = $new_user;
				$yAxis_data2 = $new_userd;
				$bfb = $new_user_bfb;
				break;
			case 'day':
				$data_type_name = '日电话量';
				$yAxis_name1 = '日电话指标';
				$yAxis_name2 = '完成情况';
				$suffix = '个';
				$yAxis_data1 = $day_call;
				$yAxis_data2 = $day_calld;
				break;		
			case 'month':
				$data_type_name = '月电话量';
				$yAxis_name1 = '月电话量指标';
				$yAxis_name2 = '完成情况';
				$suffix = '个';
				$yAxis_data1 = $month_call;
				$yAxis_data2 = $month_calld;
				break;	
			case 'profit':
				$data_type_name = '利润';
				$yAxis_name1 = '利润指标';
				$yAxis_name2 = '完成情况';
				$suffix = '元';
				$yAxis_data1 = $profit;
				$yAxis_data2 = $profitd;
				break;			
		}
		$subtitle = $team_name . $data_type_name . '指标完成状况';
		$option_arr = array(
			'chart'=>array('type'=>'column','plotBorderWidth'=>1),
			'colors'=>array('#CD7B00','#8085E9','#D44A40'),
			'title'=>array('text'=>'','margin'=>30),
			'subtitle'=>array('text'=>$subtitle,'style'=>array('color'=>'#FF00FF','fontSize'=>'22',)),
			'xAxis'=>array('categories'=>$name,'title'=>array('text'=>null)),
			'yAxis'=>array(
				array('min'=>0,
					'title'=>array('text'=>'','align'=>'middle'),
					'labels'=>array('overflow'=>'justify','format'=>'{value}'.$suffix)),
				),
			'tooltip'=>array('valueSuffix'=>$suffix,'shared'=>true),
			'plotOptions'=>array('bar'=>array('dataLabels'=>array('enabled'=>true))),
			'legend'=>array('layout'=>'vertical','align'=>'left','verticalAlign'=>'top','floating'=>false,'backgroundColor'=>'#FFFFFF','borderWidth'=>0,'shadow'=>true),
			'credits'=>array('enabled'=>false),
    		'series'=>array(array('name'=>$yAxis_name1,'data'=>$yAxis_data1),array('name'=>$yAxis_name2,'data'=>$yAxis_data2)),
		);
		
		$this->ajaxReturn('1',json_encode($option_arr));
	}
	public function getPercent(){
		$time_type = spost('time_type','s','');
		$team_id = spost('team_id','i','');
		$team_name = spost('team_name','s','');
		$return_data = $this->data = M('user:quotaView')->getDataByTime($time_type,$team_id);
		// p($return_data);die();
		foreach ($return_data as $key => $value) {
			$name[] = $value['name'];
			$new=round(($value['new_userd']/$value['new_user'])*100,2);
			$old = round(($value['old_userd']/$value['old_user'])*100,2);
			$buy = round(($value['buyd']/10000/$value['buys'])*100,2);	
			$sale = round(($value['saled']/10000/$value['sales'])*100,2);
			$buynum = round(($value['buyd_num']/$value['buy_num'])*100,2);
			$salenum = round(($value['saled_num']/$value['sale_num'])*100,2);
			$day = round(($value['day_calld']/$value['day_call'])*100,2);
			$month = round(($value['month_calld']/$value['month_call'])*100,2);
			$profit = round(($value['profitd']/$value['profit'])*100,2);
			$old_bfb[] = !empty($old)?$old:0;
			$new_bfb[] = !empty($new)?$new:0;
			$buy_bfb[] = !empty($buy)?$buy:0;
			$sale_bfb[] = !empty($sale)?$sale:0;
			$salenum_bfb[] = !empty($salenum)?$salenum:0;
			$buynum_bfb[] = !empty($buynum)?$buynum:0;
			$day_bfb[] = !empty($day)?$day:0;
			$month_bfb[] = !empty($month)?$month:0;
			$profit_bfb[] = !empty($profit)?$profit:0;
		}

		$subtitle = $team_name . '成员根据自身各项指标完成百分比';
		$option_arr = array(
			'chart'=>array('type'=>'column','plotBorderWidth'=>1),
			// 'colors'=>array('#CD7B00','#8085E9','#D44A40'),
			'title'=>array('text'=>'','margin'=>30),
			'subtitle'=>array('text'=>$subtitle,'style'=>array('color'=>'#1AADCE','fontSize'=>'22',)),
			'xAxis'=>array('categories'=>$name,'title'=>array('text'=>null)),
			'yAxis'=>array(
				array('min'=>0,
					'title'=>array('text'=>'百分比','align'=>'middle'),
					'labels'=>array('overflow'=>'justify','format'=>'{value}%')),
				),
			'tooltip'=>array('valueSuffix'=>'%','shared'=>true),
			'plotOptions'=>array('bar'=>array('dataLabels'=>array('enabled'=>true))),
			'legend'=>array('layout'=>'vertical','align'=>'left','verticalAlign'=>'top','floating'=>false,'backgroundColor'=>'#FFFFFF','borderWidth'=>0,'shadow'=>true),
			'credits'=>array('enabled'=>false),
    		'series'=>array(array('name'=>'销售吨位','data'=>$salenum_bfb),
    						array('name'=>'采购吨位','data'=>$buynum_bfb),
    						array('name'=>'销售金额','data'=>$sale_bfb),
    						array('name'=>'采购金额','data'=>$buy_bfb),
    						array('name'=>'新用户','data'=>$new_bfb),
    						array('name'=>'老用户','data'=>$old_bfb),
    						array('name'=>'日电话','data'=>$day_bfb),
    						array('name'=>'月电话','data'=>$month_bfb),
    						array('name'=>'利润','data'=>$profit_bfb),
    						),
		);
		
		$this->ajaxReturn('1',json_encode($option_arr));
	}
	/**
	 * 导出报表
	 * @access public 
	 * @return html
	 */
	public function download(){
		$time_type = spost('time_type','s','');
		$return_data = $this->data = M('user:quotaView')->getExcelData($time_type);
		foreach ($return_data as $key => $value) {
				$return_data[$key]['ave_num'] = round(($value['saled_num'] + $value['buyd_num'])/2,4);
				$return_data[$key]['plan_num'] = round(($value['sale_num'] + $value['buy_num'])/2,4);
				$return_data[$key]['percent'] = round(($return_data[$key]['ave_num'] / $return_data[$key]['plan_num'])*100,2);
		}
		$this_month = date("Y年m月",mktime(0, 0 , 0,date("m"),1,date("Y")));
		$last_month = date("Y年m月",mktime(0, 0 , 0,date("m")-1,1,date("Y")));
		if($time_type == 'this_month'){
			$title = $this_month."指标报表";
		}elseif($time_type == 'last_month'){
			$title = $last_month."指标报表";
		}
		$str = '<meta http-equiv="Content-Type" content="text/html; charset=utf8" /><table width="100%" border="1" cellspacing="0">';
			$str .= '<tr><td>战队名称</td><td>业务员</td><td>销售吨数</td><td>采购吨数</td>
						<td>个人平均</td><td>计划数量</td><td>完成百分比（%）</td>
					</tr>';
			foreach($return_data as $k=>$v){
				$str .= "<tr><td>".$v['team']."</td><td>".$v['name']."</td><td style='vnd.ms-excel.numberformat:@'>".$v['saled_num']."</td><td style='vnd.ms-excel.numberformat:@'>".$v['buyd_num']."</td><td style='vnd.ms-excel.numberformat:@'>".$v['ave_num']."</td><td style='vnd.ms-excel.numberformat:@'>".$v['plan_num']."</td><td style='vnd.ms-excel.numberformat:@'>".$v['percent']."</td></tr>";
			}
		$str .= '</table>';
		$filename = $title;
		header("Content-type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment; filename=$filename.xls");
		echo $str;
		exit;
	}
}
?>